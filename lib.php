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
  * get base url
  *
  * @return string base url
  */
function block_ls2_get_base_url() {
    global $CFG;

    if ($CFG->wwwroot == "http://localhost/moodle") {
        return "https://localhost:44305";
    }
    if ($CFG->wwwroot == "https://moodle.teameo.io") {
        return "https://dev-app.teameo.io";
    }
    return "https://app.teameo.io";
}

/**
 * add digital_space link to the top navbar
 *
 * @return string navbar output
 */
function block_ls2_render_navbar_output() {
    global $PAGE, $CFG;
    if (!isloggedin()) {
        return '';
    }

    $pageurl = new \moodle_url('/blocks/ls2/digital_space.php');
    $title = get_string('digital_space_title', 'block_ls2');

    $baseurl = block_ls2_get_base_url();
    $iconurl = $baseurl . '/api/config/DigitalSpace/Moodle/Plugin/Icon' . '?siteUrl=' . urlencode($CFG->wwwroot);

    $currenturl = $PAGE->url->out();
    $style = "";
    if ($currenturl == $pageurl->out()) {
        $style = "border-bottom: 3px solid #0f6cbf;";
    }

    $icon = "<img src=\"$iconurl\" class=\"icon\" title=\"$title\" alt=\"$title\"/>";
    return "<a href=\"$pageurl\" class=\"nav-link position-relative icon-no-margin\" style=\"$style\">$icon</a>";
}

/**
 *
 * get the context query for the current user.
 * @param stdClass $course The current course.
 * @return string The urlencoded context query.
 */
function get_context_query($course = null) {
    global $CFG, $USER;

    $ctx = [
        'siteUrl' => $CFG->wwwroot,
        'userName' => $USER->username,
        'userEmail' => $USER->email,
        'userId' => (int) $USER->id,
    ];

    if (isset($course)) {
        $ctx['courseId'] = (int) $course->id;
        $ctx['courseFullname'] = $course->fullname;
        $ctx['courseShortname'] = $course->shortname;

        $context = context_course::instance($course->id);
        $ctx['isCourseOwner'] = has_capability('moodle/course:update', $context, $USER->id);
    }

    $json = json_encode($ctx);

    return urlencode($json);
}
