<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="/moodle/local/better_user_selector/include/jquery.dataTables.css">
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="/moodle/local/better_user_selector/include/jquery-1.10.2.min.js"></script>
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="/moodle/local/better_user_selector/include/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="/moodle/local/better_user_selector/include/user_select.js"></script>
<style>.js div#preloader { position: fixed; left: 0; top: 0; z-index: 999; width: 100%; height: 100%; overflow: visible; background: white url('/moodle/local/better_user_selector/pix/721.GIF') no-repeat center center; }
</style>

<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
/*$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/local/better_user_selector/include/jquery-1.10.2.min.js') );
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/local/better_user_selector/include/jquery.dataTables.js') );
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/local/better_user_selector/include/user_select.js') );
$PAGE->requires->css( new moodle_url($CFG->wwwroot . '/local/better_user_selector/include/jquery.dataTables.css') );*/
require_once($CFG->dirroot.'/course/lib.php');
require_once('lib.php');


$id = optional_param('id',0, PARAM_INT); //COURSE ID
$rete = optional_param('rete', 0, PARAM_INT);
$profili = optional_param('profili', 0, PARAM_INT);
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/better_user_selector/user_select.php', array('id' => $id));
$PAGE->set_title('User Select');
$PAGE->set_heading('User Select');
global $DB;
echo '<div class="js">';
/*
$s = $_GET["s"];
$profili = $_POST["profili"];
$rete =$_POST["rete"];*/
$rete_da_preiscrivere = $rete;
$PAGE->set_pagelayout('standard');
//print_r("<input type='hidden' id='rete' value='".$rete."'>");
//print_r("<input type='hidden' id='profili' value='".$profili."'>");
echo '<div id="preloader"></div>';echo $OUTPUT->header();
echo '<table id="example" class="display compact" cellspacing="0" width="100%">
       <thead>
            <tr>                
				<th>User id</th>
                <th>First Name</th>
                <th>Last Name</th>
				<th>Select</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
           	    <th>User id</th>
                <th>First Name</th>
                <th>Last Name</th>
				<th>Select</th>
            </tr>
        </tfoot><tbody>';	



 echo"</tbody></table><input type='submit' value='Seleziona Tutte' class='selezionatutte' style='right:5%; float:right;'><form method='POST' action='script_iscrivi_3.php'><input type='submit' value='Iscrivi utenti' id='iscrivi'>	</form>";

 if(isset($_POST['submit']))
   {
    //Do all the submission part or storing in DB work and all here
    redirect($CFG->wwwroot.'/local/preiscrizione/script_iscrivi_3.php');
   }echo $OUTPUT->footer();echo '</div>';?>
