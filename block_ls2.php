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
     * @return string The block HTML.
     */
    public function get_content() {
        global $OUTPUT, $CFG, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->footer = '';
        require_once($CFG->dirroot . '/blocks/ls2/lib.php');

        $baseurl = block_ls2_get_base_url();

        $contextquery = block_ls2_get_context_query($this->page->course);

        $iframesrc = "$baseurl/moodle/courseBlock?hostApp=Moodle&moodleContext=$contextquery";

        $html = '<iframe src="' . $iframesrc
            . '" style="overflow:hidden;height: 300px;width:100%" width="100%" frameborder="0"></iframe>';

        $this->content->text = $html;

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
}
