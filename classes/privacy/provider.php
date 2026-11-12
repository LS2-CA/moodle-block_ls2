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
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;

/**
 * Privacy provider for block_ls2.
 *
 * The plugin stores no personal data in Moodle; it only sends data to the LS2 application.
 *
 * @package   block_ls2
 * @copyright 2025 ls2.io
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link      https://ls2.io
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\core_userlist_provider,
    \core_privacy\local\request\plugin\provider {
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
            'courseid' => 'privacy:metadata:external_service:courseid',
            'iscourseowner' => 'privacy:metadata:external_service:iscourseowner',
        ], 'privacy:metadata:external_service');

        return $collection;
    }

    /**
     * No data is stored in Moodle, so no context holds user data.
     *
     * @param int $userid The user to search.
     * @return contextlist
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        return new contextlist();
    }

    /**
     * No data is stored in Moodle, so no user is found.
     *
     * @param userlist $userlist The userlist containing the list of users.
     * @return void
     */
    public static function get_users_in_context(userlist $userlist) {
    }

    /**
     * No data is stored in Moodle, so nothing to export.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     * @return void
     */
    public static function export_user_data(approved_contextlist $contextlist) {
    }

    /**
     * No data is stored in Moodle, so nothing to delete.
     *
     * @param \context $context The specific context to delete data for.
     * @return void
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
    }

    /**
     * No data is stored in Moodle, so nothing to delete.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     * @return void
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
    }

    /**
     * No data is stored in Moodle, so nothing to delete.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     * @return void
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
    }
}
