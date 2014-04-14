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
                echo form_label('Term', 'term', array('class' => 'form_label'));

                echo input_requirement();

                // Default option
                $term1[] = '-- Select --';

                // Options from query
                foreach ($term_list as $row) {
                    $term1[$row->termID] = $row->term_season . " " . $row->term_year ;
                }

                echo form_dropdown('type', $term1, set_value('term1'), 'id="term" class="form_select"');

                ?>

            </div>
            <div class="form-row">

                <?php
                // VEHICLE TYPE LABEL AND INPUT ***********************************
                echo form_label('Course Name', 'course_name', array('class' => 'form_label'));

                echo input_requirement();

                // Default option
                $course_names[] = '-- Select --';

                // Options from query
                foreach ($course_name_list as $row) {
                    $course_names[$row->courseName] = $row->courseName;
                }

                echo form_dropdown('type', $course_names, set_value('course_name'), 'id="course_name" class="form_select"');

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

                echo form_dropdown('type', $timeslot, set_value('timeslot'), 'id="timeslot" class="form_select"');

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

                echo form_dropdown('type', $building, set_value('building'), 'id="building" class="form_select"');

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
                foreach ($room_list as $row) {
                    $room[$row->ID] = $row->room;
                }

                echo form_dropdown('type', $room, set_value('room'), 'id="room" class="form_select"');

                ?>

            </div>
            <div class="form-row">
                <div id="submit_box">

                    <?php
                    // SUBMIT BUTTON **************************************************************
                    $input_data = array(
                        'name' => 'add_denial',
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
                    <th>IP Address</th>
                    <th>Reason Denied</th>
                    <th>Date Denied</th>
                </tr>
                </thead>
                <tbody>

                <?php

                if (!empty($course_list)) {
                    //$denial_reasons = config_item('denied_access_reason');

                    foreach ($course_list as $row) {
                        echo '
				<tr>
					<td>
						<input type="checkbox" name="ip_removals[]" value="' . $row->courseName . '" />
					</td>
					<td>'
                            .$row->courseName.

					'</td>
					<td>

                           </td>
                            <td>

                            </td>
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
        </div>
    </div>
    </form>

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