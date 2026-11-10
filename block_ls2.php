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
 * Block definition class for the block_ls2 plugin.
 *
 * @package   block_ls2
 * @copyright 2025 ls2.io
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link      https://ls2.io
 */

/**
 * Block class for LS2.
 *
 * @package   block_ls2
 * @copyright 2025 ls2.io
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_ls2 extends block_base {
    /**
     * Initialises the block.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('block_title', 'block_ls2');
    }

    /**
     * Gets the block contents.
     *
     * @return stdClass The block content.
     */
    public function get_content() {
        global $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        $course = $this->page->course;
        if (!isloggedin() || isguestuser() || empty($course->id) || $course->id == SITEID) {
            return $this->content;
        }

        require_once($CFG->dirroot . '/blocks/ls2/lib.php');

        $title = get_string('block_title', 'block_ls2');
        $frameid = html_writer::random_id('block_ls2-frame-');

        $iframe = block_ls2_render_iframe(block_ls2_get_app_url('/moodle/courseBlock', $course), $title);
        $this->content->text = html_writer::div($iframe, 'block_ls2-frame', ['id' => $frameid]);

        $fullpage = html_writer::link(
            new moodle_url('/blocks/ls2/view.php', ['id' => $course->id]),
            get_string('openfullpage', 'block_ls2'),
            ['class' => 'btn btn-sm btn-secondary']
        );
        $fullscreen = html_writer::tag('button', get_string('fullscreen', 'block_ls2'), [
            'type' => 'button',
            'class' => 'btn btn-sm btn-secondary block_ls2-fullscreen d-none',
            'data-target' => $frameid,
        ]);
        $this->content->footer = html_writer::div($fullpage . ' ' . $fullscreen, 'block_ls2-actions');

        $this->page->requires->js_amd_inline(<<<JS
            var button = document.querySelector('button.block_ls2-fullscreen[data-target="{$frameid}"]');
            var frame = document.getElementById('{$frameid}');
            if (button && frame && document.fullscreenEnabled && frame.requestFullscreen) {
                button.classList.remove('d-none');
                button.addEventListener('click', function() {
                    frame.requestFullscreen().catch(function() {
                        return null;
                    });
                });
            }
        JS);

        return $this->content;
    }

    /**
     * Defines in which pages this block can be added.
     *
     * @return array of the pages where the block can be added.
     */
    public function applicable_formats() {
        return [
            'admin' => false,
            'site-index' => false,
            'course-view' => true,
            'mod' => true,
            'my' => false,
        ];
    }

    /**
     * The block has a global settings page.
     *
     * @return bool
     */
    public function has_config() {
        return true;
    }

    /**
     * Only one instance per page makes sense.
     *
     * @return bool
     */
    public function instance_allow_multiple() {
        return false;
    }
}
