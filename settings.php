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
 * Admin settings.
 *
 * @package   block_ls2
 * @copyright 2025 ls2.io
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link      https://ls2.io
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext(
        'block_ls2/baseurl',
        get_string('baseurl', 'block_ls2'),
        get_string('baseurl_desc', 'block_ls2'),
        '',
        PARAM_URL
    ));
    $settings->add(new admin_setting_configselect(
        'block_ls2/colourscheme',
        get_string('colourscheme', 'block_ls2'),
        get_string('colourscheme_desc', 'block_ls2'),
        'moodle',
        [
            'moodle' => get_string('colourscheme_moodle', 'block_ls2'),
            'light' => get_string('colourscheme_light', 'block_ls2'),
            'dark' => get_string('colourscheme_dark', 'block_ls2'),
            'auto' => get_string('colourscheme_auto', 'block_ls2'),
        ]
    ));
}
