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
 * @package    mod_better_user_selector
 * @copyright  2015 Alessandro Romani
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once('lib.php');
require_once($CFG->dirroot.'/course/lib.php');
ini_set('max_execution_time', 3000);
global $DB;
require_login();
$ora=time();  
    
    $utenti = $DB->get_records('user');
    $jsonrighe=array();
    foreach($utenti as $a){
	//    array_push($json,[$a->username,$a->firstname,$a->lastname,$a->id]);
    	$tipo2="altro";
    	$riga = "<tr id='".$a->username."'><td>".$a->id."</td><td>".$a->firstname."</td><td>".$a->lastname."</td>";
    	array_push($jsonrighe,[$riga,$a->username,$a->firstname,$a->lastname,$a->id,$a->id]);
    }

    echo json_encode($jsonrighe);

?>
