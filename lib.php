<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin functions.
 *
 * @package   block_ls2
 * @copyright 2025 ls2.io
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link      https://ls2.io
 */

/**
 * Returns the base URL of the LS2 application.
 *
 * An explicit admin setting wins; otherwise the URL is derived from the Moodle site.
 *
 * @return string Base URL, without trailing slash.
 */
function block_ls2_get_base_url(): string {
    global $CFG;

    $configured = trim((string) get_config('block_ls2', 'baseurl'));
    if ($configured !== '') {
        return rtrim($configured, '/');
    }

    if ($CFG->wwwroot == "http://localhost/moodle") {
        return "https://localhost:44305";
    }
    if ($CFG->wwwroot == "https://moodle.teameo.io") {
        return "https://dev-app.teameo.io";
    }
    return "https://app.teameo.io";
}

/**
 * Returns the origin (scheme://host[:port]) of the LS2 application, used to validate postMessage events.
 *
 * @return string
 */
function block_ls2_get_origin(): string {
    $parts = parse_url(block_ls2_get_base_url());
    $origin = ($parts['scheme'] ?? 'https') . '://' . ($parts['host'] ?? '');
    if (!empty($parts['port'])) {
        $origin .= ':' . $parts['port'];
    }
    return $origin;
}

/**
 * Builds the context sent to the LS2 application for the current user.
 *
 * @param stdClass|null $course The current course, if any.
 * @return array
 */
function block_ls2_get_context(?stdClass $course = null): array {
    global $CFG, $USER;

    $ctx = [
        'siteUrl' => $CFG->wwwroot,
        'userName' => $USER->username,
        'userEmail' => $USER->email,
        'userId' => (int) $USER->id,
    ];

    if ($course !== null && !empty($course->id) && $course->id != SITEID) {
        $ctx['courseId'] = (int) $course->id;
        $ctx['courseFullname'] = $course->fullname;
        $ctx['courseShortname'] = $course->shortname;
        $ctx['isCourseOwner'] = has_capability('moodle/course:update', context_course::instance($course->id));
    }

    return $ctx;
}

/**
 * Builds the URL of an LS2 application page to embed.
 *
 * @param string $path Path in the LS2 application, starting with a slash.
 * @param stdClass|null $course The current course, if any.
 * @return moodle_url
 */
function block_ls2_get_app_url(string $path, ?stdClass $course = null): moodle_url {
    return new moodle_url(block_ls2_get_base_url() . $path, [
        'hostApp' => 'Moodle',
        'colorScheme' => block_ls2_get_colour_scheme(),
        'moodleContext' => json_encode(block_ls2_get_context($course)),
    ]);
}

/**
 * Returns the colour scheme the LS2 application should use: light, dark or auto (follow the device).
 *
 * By default it follows Moodle: the Boost colour mode where the site supports it (Moodle 6.0+),
 * light otherwise, as Boost has no dark mode before 6.0. Admins can force a value, e.g. for a dark theme.
 *
 * @return string
 */
function block_ls2_get_colour_scheme(): string {
    $setting = get_config('block_ls2', 'colourscheme');
    if (in_array($setting, ['light', 'dark', 'auto'], true)) {
        return $setting;
    }

    if (class_exists('\theme_boost\colour_mode') && \theme_boost\colour_mode::is_boost_theme()) {
        $mode = \theme_boost\colour_mode::get_current_mode();
        if (in_array($mode, ['light', 'dark', 'auto'], true)) {
            return $mode;
        }
    }

    return 'light';
}

/**
 * Keeps the colour scheme of the embedded LS2 application in sync when the Moodle colour mode changes without
 * a page reload (Moodle 6.0+), by posting {type: 'colorScheme', value: 'light'|'dark'} to each LS2 iframe.
 *
 * @param moodle_page $page
 * @return void
 */
function block_ls2_require_colour_scheme_js(moodle_page $page): void {
    if (get_config('block_ls2', 'colourscheme') && get_config('block_ls2', 'colourscheme') !== 'moodle') {
        return;
    }
    $origin = json_encode(block_ls2_get_origin());
    $page->requires->js_amd_inline(<<<JS
        if (!window.blockLs2ColourScheme) {
            window.blockLs2ColourScheme = true;
            var root = document.documentElement;
            var current = root.getAttribute('data-bs-theme');
            new MutationObserver(function() {
                var mode = root.getAttribute('data-bs-theme');
                if (mode === current || (mode !== 'light' && mode !== 'dark')) {
                    return;
                }
                current = mode;
                document.querySelectorAll('iframe.block_ls2-iframe').forEach(function(frame) {
                    if (frame.contentWindow) {
                        frame.contentWindow.postMessage({type: 'colorScheme', value: mode}, {$origin});
                    }
                });
            }).observe(root, {attributes: true, attributeFilter: ['data-bs-theme']});
        }
    JS);
}

/**
 * Renders the iframe embedding the LS2 application.
 *
 * @param moodle_url $src The iframe source.
 * @param string $title Accessible title of the iframe.
 * @param string $class Extra CSS class.
 * @return string HTML
 */
function block_ls2_render_iframe(moodle_url $src, string $title, string $class = ''): string {
    return html_writer::tag('iframe', '', [
        'src' => $src->out(false),
        'title' => $title,
        'class' => trim('block_ls2-iframe ' . $class),
        'allow' => 'clipboard-write; fullscreen',
        'allowfullscreen' => 'allowfullscreen',
        'loading' => 'lazy',
    ]);
}

/**
 * Adds the JavaScript used by the full page views.
 *
 * Sizes the iframe to fill the rest of the viewport, reloads the page when restored from the
 * back/forward cache (the embedded session must be fresh), and lets the LS2 application request
 * a top-level navigation.
 *
 * @param moodle_page $page
 * @return void
 */
function block_ls2_require_page_js(moodle_page $page): void {
    block_ls2_require_colour_scheme_js($page);
    $origin = json_encode(block_ls2_get_origin());
    $page->requires->js_amd_inline(<<<JS
        var nav = window.performance && performance.getEntriesByType
            ? performance.getEntriesByType('navigation')[0] : null;
        if (nav && nav.type === 'back_forward') {
            window.location.reload();
        }
        window.addEventListener('pageshow', function(e) {
            if (e.persisted) {
                window.location.reload();
            }
        });
        var frame = document.querySelector('.block_ls2-page .block_ls2-iframe');
        var fit = function() {
            if (frame) {
                var top = frame.getBoundingClientRect().top + window.scrollY;
                frame.style.height = Math.max(400, window.innerHeight - top) + 'px';
            }
        };
        fit();
        window.addEventListener('resize', fit);
        window.addEventListener('message', function(e) {
            if (e.origin !== {$origin} || !e.data || e.data.type !== 'redirect' || typeof e.data.url !== 'string') {
                return;
            }
            var target;
            try {
                target = new URL(e.data.url, window.location.href);
            } catch (err) {
                return;
            }
            if (target.protocol === 'https:' || target.protocol === 'http:') {
                window.location.href = target.href;
            }
        });
    JS);
}

/**
 * Adds the Digital Space link to the top navbar.
 *
 * @param renderer_base $renderer
 * @return string navbar output
 */
function block_ls2_render_navbar_output(renderer_base $renderer): string {
    global $PAGE, $CFG;

    if (!isloggedin() || isguestuser() || during_initial_install()) {
        return '';
    }

    $pageurl = new moodle_url('/blocks/ls2/digital_space.php');
    $title = get_string('digital_space_title', 'block_ls2');
    $iconurl = new moodle_url(block_ls2_get_base_url() . '/api/config/DigitalSpace/Moodle/Plugin/Icon', [
        'siteUrl' => $CFG->wwwroot,
    ]);

    $attributes = ['class' => 'nav-link position-relative icon-no-margin block_ls2-navlink', 'title' => $title];
    if ($PAGE->has_set_url() && $PAGE->url->compare($pageurl, URL_MATCH_BASE)) {
        $attributes['class'] .= ' block_ls2-navlink-active';
        $attributes['aria-current'] = 'page';
    }

    $icon = html_writer::empty_tag('img', ['src' => $iconurl->out(false), 'class' => 'icon', 'alt' => $title]);
    return html_writer::link($pageurl, $icon, $attributes);
}
