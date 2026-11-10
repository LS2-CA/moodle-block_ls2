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
 * Digital Space page.
 *
 * @package   block_ls2
 * @copyright 2025 ls2.io
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link      https://ls2.io
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/blocks/ls2/lib.php');

require_login(null, false);
if (isguestuser()) {
    throw new moodle_exception('noguest');
}

$title = get_string('digital_space_title', 'block_ls2');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/blocks/ls2/digital_space.php'));
$PAGE->set_pagelayout('mydashboard');
$PAGE->set_cacheable(false);
$PAGE->set_title($title);
$PAGE->add_body_class('block_ls2-fullpage');

block_ls2_require_page_js($PAGE);

echo $OUTPUT->header();
echo html_writer::div(
    block_ls2_render_iframe(block_ls2_get_app_url('/digitalSpaces/my'), $title),
    'block_ls2-page'
);
echo $OUTPUT->footer();
