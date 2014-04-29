<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Community Auth - Deny Access View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?>

    <h1>Deny Access</h1>

<?php

if (config_item('deny_access') > 0) {
    if (isset($confirm_add_denial)) {
        echo '
			<div class="feedback confirmation">
				<p class="feedback_header">
					The IP address was added to the deny list.
				</p>
			</div>
		';
    } else if (isset($confirm_removal)) {
        echo '
			<div class="feedback confirmation">
				<p class="feedback_header">
					The specified IP address(es) were removed from the deny list.
				</p>
			</div>
		';
    } else if (isset($validation_errors)) {
        echo '
			<div class="feedback error_message">
				<p class="feedback_header">
					Your attempt to update the deny list contained the following errors:
				</p>
				<ul>
					' . $validation_errors . '
				</ul>
				<p>
					NO CHANGE TO DENY LIST
				</p>
			</div>
		';
    }

    echo form_open('', array('class' => 'std-form', 'style' => 'margin-top:24px;')); ?>
    <div class="form-column-left">
        <fieldset>
            <legend>Add/Remove Section:</legend>
            <div class="form-row">

                <?php
                // VEHICLE TYPE LABEL AND INPUT ***********************************
                echo form_label('Year', 'year', array('class' => 'form_label'));

                echo input_requirement();

                // Default option
                $years[] = '-- Select --';

                // Options from query
                foreach ($term_list as $row) {
                    $years[$row->ID] = $row->term_year ;
                }

                echo form_dropdown('year', $years, set_value('years'), 'id="year" class="form_select"');

                ?>

            </div>
            <div class="form-row">

                <?php
                // VEHICLE TYPE LABEL AND INPUT ***********************************
                echo form_label('Term', 'term', array('class' => 'form_label'));

                echo input_requirement();

                // Default option
                $term1[] = '-- Select --';

                // Options from query
                foreach ($term_list as $row) {
                    $term1[$row->ID] = $row->term_season;
                }

                echo form_dropdown('term', $term1, set_value('term1'), 'id="term" class="form_select"');

                ?>

            </div>
            <div class="form-row">

                <?php
                // VEHICLE TYPE LABEL AND INPUT ***********************************
                echo form_label('Course Name', 'course_name', array('class' => 'form_label'));

                echo input_requirement();

                // Default option
                $course_names[] = '-- Select --';
                foreach($course_list as $row){
                    $course_names[$row->ID]=$row->courseName;
                }

                // Options from query
               // foreach ($course_name_list as $row) {
               //     $course_names[$row->courseName] = $row->courseName;
               // }

                echo form_dropdown('course_name', $course_names, set_value('course_names'), 'id="course_name" class="form_select"');

                ?>

            </div>
            <div class="form-row">

                <?php
                // VEHICLE TYPE LABEL AND INPUT ***********************************
                echo form_label('Time Slot', 'timeslot', array('class' => 'form_label'));

                echo input_requirement();

                // Default option
                $timeslot[] = '-- Select --';

                // Options from query
                foreach ($timeslot_list as $row) {
                    $timeslot[$row->ID] = $row->timeslot;
                }

                echo form_dropdown('timeslot', $timeslot, set_value('timeslot'), 'id="timeslot" class="form_select"');

                ?>

            </div>
            <div class="form-row">

                <?php
                // VEHICLE TYPE LABEL AND INPUT ***********************************
                echo form_label('Building', 'building', array('class' => 'form_label'));

                echo input_requirement();

                // Default option
                $building[] = '-- Select --';

                // Options from query
                foreach ($building_list as $row) {
                    $building[$row->ID] = $row->building;
                }

                echo form_dropdown('building', $building, set_value('building'), 'id="building" class="form_select"');

                ?>

            </div>
            <div class="form-row">

                <?php
                // VEHICLE TYPE LABEL AND INPUT ***********************************
                echo form_label('Room', 'room', array('class' => 'form_label'));

                echo input_requirement();

                // Default option
                $room[] = '-- Select --';

                // Options from query
                foreach ($room_list as $num => $text) {
                    $room[$text->ID] = $text->room;
                }

                echo form_dropdown('room', $room, set_value('room'), 'id="room" class="form_select"');
                ?>

            </div>
            <div class="form-row">

                <?php
                // VEHICLE TYPE LABEL AND INPUT ***********************************
                echo form_label('Instructor Name', 'instructor_name', array('class' => 'form_label'));

                echo input_requirement();

 //               Default option
                $instructor_names[] = '-- To be determined --';
                foreach($instructor_list as $row){
                    $instructor_names[$row->user_id]=$row->first_name.$row->last_name;
                }



                echo form_dropdown('instructor_name', $instructor_names, set_value('instructor_names'), 'id="course_name" class="form_select"');

                ?>

            </div>

            <div class="form-row">
                <div id="submit_box">

                    <?php
                    // SUBMIT BUTTON **************************************************************
                    $input_data = array(
                        'name' => 'add_section',
                        'id' => 'submit_button',
                        'value' => 'Deny'
                    );
                    echo form_submit($input_data);
                    ?>

                </div>
            </div>
        </fieldset>
    </div>
    <div id="table-wrapper">
        <h2>Deny List</h2>

        <div id="table-wrapper">
            <table id="myTable" class="tablesorter">
                <thead>
                <tr>
                    <th></th>
                    <th>term</th>
                    <th>year</th>
                    <th>course</th>
                    <th>section</th>
                    <th>instructor</th>
                    <th>timeslot</th>
                    <th>room</th>
                    <th>building</th>
                </tr>
                </thead>
                <tbody>

                <?php

                if (!empty($sect_list)) {
                    //$denial_reasons = config_item('denied_access_reason');

                    foreach ($sect_list as $row) {
                        echo '
				<tr>
					<td>
						<input type="checkbox" name="ip_removals[]" value="' . $row->ID . '" />
					</td>
					<td>'
                            .$row->term.

					'</td>
					<td>'
                            .$row->year.
                    '</td>
					<td>'
                            .$row->courseName.

                    '</td>
                     <td>'
                            .$row->section.
                    '</td>
                    <td>'
                            .$row->teacher.
                    '</td>
                    <td>'
                            .$row->timeslot.
                    '</td>
                    <td>'
                            .$row->room.
                    '</td>
                    <td>'
                            .$row->building.
                    '</td>

                </tr>
                    ';
                    }
                }

                ?>

                </tbody>
            </table>
        </div>
        <div id="decision_buttons">
            <input type="submit" class="form_button" name="remove_selected" value="Remove Selected"
                   style="margin-top:10px;"/>
           <?php echo anchor_popup('administration/modifyclass', 'My News', array('title' => 'The best news!')); ?>
        </div>


    </div>
    </form>
    <script type="text/javascript">/*
       // document.getElementById('fieldstuff').style.display='none';
        function hideshow(){
            if (!document.getElementById)
                return
            if (document.getElementById('fieldstuff').style.display=="block")
                document.getElementById('fieldstuff').style.display="none"
            else
                document.getElementById('fieldstuff').style.display="block"
        }
        function changestuff(){
            <?php/* echo anchor_popup('administration/modifyclass');*/?>
            var blah;
        }

   */ </script>

<?php

} else {
    echo '
		<p>
			Deny Access functionality has been disabled in the authentication configuration. If you wish to enable this functionality, please update <br /><b>/application/config/authentication.php</b>.
		</p>
	';
}

/* End of file deny_access.php */
/* Location: /application/views/administration/deny_access.php */