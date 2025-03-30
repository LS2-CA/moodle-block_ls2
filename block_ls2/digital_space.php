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
 * @package    block_ls2
 * @copyright  2025 https://teameo.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_ls2;

require_once('../../config.php');
require_login();
require_once($CFG->dirroot . '/blocks/ls2/lib.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/user/editlib.php');
require_once($CFG->dirroot . '/lib/weblib.php');
require_once($CFG->dirroot . '/lib/adminlib.php');
require_once($CFG->dirroot . '/lib/externallib.php');
require_once($CFG->dirroot . '/webservice/lib.php');
global $SITE;
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$context = \context_system::instance();
$PAGE->set_context($context);
$pageurl = new \moodle_url('/blocks/ls2/digital_space.php');
$PAGE->set_url($pageurl);
$PAGE->set_pagelayout('mydashboard');

$title = get_string('digital_space_title', 'block_ls2');
$PAGE->set_title($title);

$baseurl = block_ls2_get_base_url();
$context_query = get_context_query();
$iframesrc = "$baseurl/digitalSpaces/my?hostApp=Moodle&moodleContext=$context_query";
$html = '<iframe src="' . $iframesrc
    . '" style="overflow:hidden;height: calc(100vh - 100px);width:100%" width="100%" frameborder="0"></iframe>';

echo $OUTPUT->header();
echo $html;
?>
<style>
    #page-header {
        display: none !important;
    }

    #topofscroll {
        margin: 0px !important;
    }

    .nav-link.active {
        border-bottom: none !important;
    }
</style>

<script>
    if (performance.getEntriesByType("navigation")[0].type === "back_forward") {
        location.reload();
    }
    window.onmessage = function (e) {
        if (e.origin !== <?php echo $baseurl ?>) {
            return;
        }

        if (e.data.type == "redirect") {
            window.location.href = e.data.url;
        }
    }
</script>
<?php
echo $OUTPUT->footer();
