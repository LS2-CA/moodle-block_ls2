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
 * Block plugin "LS2" - Version file
 *
 * @package   block_ls2
 * @copyright 2025 ls2.io
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link      https://ls2.io
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'block_ls2';
$plugin->version = 2026111200;
$plugin->release = 'v0.2.0';
$plugin->maturity = MATURITY_STABLE;
// Moodle 4.5 (LTS) is the oldest release still supported upstream.
$plugin->requires = 2024100700;
// Tested branches: 4.5 LTS, 5.0, 5.1, 5.2 and 5.3 LTS. Raise the upper bound once 6.0 is validated.
$plugin->supported = [405, 503];
