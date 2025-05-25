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
 * Privacy provider.
 *
 * @package   block_ls2
 * @copyright 2025 ls2.io
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link      https://ls2.io
 */

namespace block_ls2\privacy;

use core_privacy\local\metadata\collection;

/**
 * Data provider for block_ls2.
 *
 * @package   block_ls2
 * @copyright 2025 ls2.io
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link      https://ls2.io
 */
class provider implements \core_privacy\local\metadata\provider, \core_privacy\local\request\plugin\subsystem_provider {
    /**
     * Returns metadata about this plugin's privacy policy.
     *
     * @param collection $collection The initialized collection to add items to.
     * @return collection A listing of user data handled by this plugin.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_external_location_link('external_service', [
            'userid' => 'privacy:metadata:external_service:userid',
            'username' => 'privacy:metadata:external_service:username',
            'email' => 'privacy:metadata:external_service:email',
        ], 'privacy:metadata:external_service');

        return $collection;
    }
}
