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
 * Prints a particular instance of preiscrizione
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_preiscrizione
 * @copyright  2015 Alessandro Romani
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace preiscrizione with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->dirroot.'/mod/facetoface/lib.php');
require_once($CFG->dirroot.'/mod/facetoface/attendees_message_form.php');
$c_id        = $_COOKIE['c_idd'];
 ini_set('memory_limit','2048M'); 
set_time_limit(0);
ini_set('max_execution_time', 0); // Changes the 30 seconds parser exit to infinite

$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/local/preiscrizione/js/edit_view.js'));


$PAGE->set_url( new moodle_url($CFG->wwwroot . '/local/preiscrizione/view.php?c_id='.$c_id));

$s = 16204;
// Cancel request
$cancelform        = optional_param('cancelform', false, PARAM_BOOL);
// Action being performed
$action            = optional_param('action', 'attendees', PARAM_ALPHA);
// Only return content
$onlycontent        = optional_param('onlycontent', false, PARAM_BOOL);
// export download
$download = optional_param('download', '', PARAM_ALPHA);
$elements = $_POST['elements'];


		
// Load data
if (!$session = facetoface_get_session($s)) {
    print_error('error:incorrectcoursemodulesession', 'facetoface');
}
if (!$facetoface = $DB->get_record('facetoface', array('id' => $session->facetoface))) {
    print_error('error:incorrectfacetofaceid', 'facetoface');
}
if (!$course = $DB->get_record('course', array('id' => $facetoface->course))) {
    print_error('error:coursemisconfigured', 'facetoface');
}
if (!$cm = get_coursemodule_from_instance('facetoface', $facetoface->id, $course->id)) {
    print_error('error:incorrectcoursemodule', 'facetoface');
}
$context = context_module::instance($cm->id);
//print_r($cm->id); = 35
require_login();

// Setup urls
$baseurl = new moodle_url('/local/preiscrizione/view.php?c_id='.$c_id);

$allowed_actions = array();

$available_actions = array();

$PAGE->set_context($context);

// Actions the user can perform
$has_attendees = facetoface_get_num_attendees($s);

    $allowed_actions[] = 'attendees';
    $allowed_actions[] = 'waitlist';
    $allowed_actions[] = 'addattendees';
    $available_actions[] = 'attendees';

    if (facetoface_get_users_by_status($s, MDL_F2F_STATUS_WAITLISTED)) {
        $available_actions[] = 'waitlist';
    }
/***************************************************************************
 * Handle actions
 */
$heading_message = '';
$params = array('sessionid' => $s);
$actions = array();
    // Check if any dates are set
   
    // Get list of actions
        $actions['addremove']    = get_string('addremoveattendees', 'facetoface');
        $actions['bulkaddfile']  = get_string('bulkaddattendeesfromfile', 'facetoface');
        $actions['bulkaddinput'] = get_string('bulkaddattendeesfrominput', 'facetoface');
 

/**
 * Print page header
 */
   

    $PAGE->requires->string_for_js('save', 'admin');
    $PAGE->requires->string_for_js('cancel', 'moodle');
    $PAGE->requires->strings_for_js(
        array('uploadfile', 'addremoveattendees', 'approvalreqd', 'areyousureconfirmwaitlist',
            'bulkaddattendeesfrominput', 'submitcsvtext', 'bulkaddattendeesresults', 'bulkaddattendeesfromfile',
            'bulkaddattendeesresults', 'wait-list', 'cancellations', 'approvalreqd', 'takeattendance',
            'updateattendeessuccessful', 'updateattendeesunsuccessful', 'waitlistselectoneormoreusers',
            'confirmlotteryheader', 'confirmlotterybody', 'updatewaitlist', 'close'),
        'facetoface'
    );

    $json_action = json_encode($action);
    $args = array('args' => '{"sessionid":'.$session->id.','.
        '"action":'.$json_action.','.
        '"sesskey":"'.sesskey().'",'.
        '"approvalreqd":"'.$facetoface->approvalreqd.'"}');

    $jsmodule = array(
        'name' => 'totara_f2f_attendees',
        'fullpath' => '/local/preiscrizione/preiscrizione.js',
        'requires' => array('json', 'totara_core'));

    if ($action == 'messageusers') {
        $PAGE->requires->strings_for_js(array('editmessagerecipientsindividually', 'existingrecipients', 'potentialrecipients'), 'facetoface');
        $PAGE->requires->string_for_js('update', 'moodle');

        $jsmodule = array(
            'name' => 'totara_f2f_attendees_message',
            'fullpath' => '/mod/facetoface/attendees_messaging.js',
            'requires' => array('json', 'totara_core'));

        $PAGE->requires->js_init_call('M.totara_f2f_attendees_messaging.init', $args, false, $jsmodule);
    } else {
        $jsmodule = array(
            'name' => 'totara_f2f_attendees',
            'fullpath' => '/mod/facetoface/attendees.js',
            'requires' => array('json', 'totara_core'));

        $args = array('args' => '{"sessionid":'.$session->id.','.
            '"action":'.$json_action.','.
            '"sesskey":"'.sesskey().'",'.
            '"selectall":'.MDL_F2F_SELECT_ALL.','.
            '"selectnone":'.MDL_F2F_SELECT_NONE.','.
            '"selectset":"'.MDL_F2F_SELECT_SET.'",'.
            '"selectnotset":"'.MDL_F2F_SELECT_NOT_SET.'",'.
            '"courseid":"'.$course->id.'",'.
            '"facetofaceid":"'.$facetoface->id.'",'.
            '"notsetop":"'.MDL_F2F_STATUS_NOT_SET.'",'.
            '"approvalreqd":"'.$facetoface->approvalreqd.'"}');

        $PAGE->requires->js_init_call('M.totara_f2f_attendees.init', $args, false, $jsmodule);
    }

    \mod_facetoface\event\attendees_viewed::create_from_session($session, $context, $action)->trigger();
	
	
	$cod_fiscali= $_COOKIE['utenti'];
	$array_profili= explode( ',', $cod_fiscali);		

foreach($array_profili as $piece){
			$coppie[] = explode(":", $piece);
		
		}
    // ESPLODO PER OTTENERE UN ARRAY PER OGNI COPPIA DI VALORI
		$organizzazione = 0;
	foreach($coppie as $coppia){
		    // PER OGNI COPPIA, ISCRIVO UTENTE E INSERISCO VALIDAZIONE.
	print_r($coppia);

		$utenti[]=$DB->get_record('user',array('username'=>$coppia[0]));

		$utente=$DB->get_record('user',array('username'=>$coppia[0]));
		$organizzazione_old=$organizzazione;
		$organizzazione=$DB->get_record('org',array('idnumber'=>$coppia[1]));	
		if($organizzazione==NULL){		$organizzazione=$organizzazione_old;
}
				preiscrizione_iscrivi_utenti3($c_id,$utente,$organizzazione);

}


		header("Location: ".$CFG->wwwroot."/course/view.php?id=".$c_id."");

?>
