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
            <legend>Add Denial:</legend>
            <div class="form-row">

                <?php
                // IP ADDRESS ***********************************
                echo form_label('Room', 'stuffName', array('class' => 'form_label'));

                echo input_requirement('*');

                $input_data = array(
                    'name' => 'stuffName',
                    'id' => 'stuffName',
                );

                echo form_input($input_data);

                ?>

            </div>
            <div class="form-row">
                <div id="submit_box">

                    <?php
                    // SUBMIT BUTTON **************************************************************
                    $input_data = array(
                        'name' => 'add_stuff',
                        'id' => 'submit_button',
                        'value' => 'Submit'
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
                    <th>TimeSlot</th>
                </tr>
                </thead>
                <tbody>

                <?php

                if (!empty($stuff_list)) {
                    // $denial_reasons = config_item('denied_access_reason');

                    foreach ($stuff_list as $row) {
                        echo '
				<tr>
					<td>
						<input type="checkbox" name="ip_removals[]" value="' . $row->ID . '" />
					</td>
					<td>
						' . $row->room . '
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