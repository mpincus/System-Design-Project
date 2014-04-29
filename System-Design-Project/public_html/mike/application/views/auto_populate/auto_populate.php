<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Auto Populate View
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

    <h1>Auto Population of Form Dropdowns</h1>
    <p>
        This is just a simple example to show how to dynamically populate form dropdowns using jQuery. There are a lot of questions in the CodeIgniter forum asking how to do this, and for my own projects I felt the need to perfect this task. While there is no form validation for this example, most everything else is complete so you can have a solid working example for your own needs.
    </p>
<?php echo form_open( '', array( 'class' => 'std-form', 'style' => 'margin-top:24px;' ) ); ?>
    <div class="form-column-left">
        <fieldset>
            <legend>Select Vehicle:</legend>
            <div class="form-row">

                <?php
                // VEHICLE TYPE LABEL AND INPUT ***********************************
                echo form_label('Year','year',array('class'=>'form_label'));

                echo input_requirement();

                // Default option
                $vehicle_years[] = '-- Select --';

                // Options from query
                foreach( $years as $row )
                {
                    $vehicle_years[$row->year] = $row->year;
                }

                echo form_dropdown( 'year', $vehicle_years, set_value('year'), 'id="year" class="form_select"' );

                ?>

            </div>
            <div class="form-row">

                <?php
                // VEHICLE MAKE LABEL AND INPUT ***********************************
                echo form_label('Term','term',array('class'=>'form_label'));

                echo input_requirement();

                // If POST, there may be vehicle makes
                if( isset( $terms ) )
                {
                    // Default option
                    $vehicle_terms[] = '-- Select --';

                    // Options from query
                    foreach( $terms as $row )
                    {
                        $vehicle_terms[$row['term']] = $row['term'];
                    }
                }
                else
                {
                    // Default option if not POST request
                    $vehicle_terms[] = '-- Select year --';
                }

                echo form_dropdown( 'term', $vehicle_terms, set_value('term'), 'id="term" class="form_select"' );

                ?>

            </div>
            <div class="form-row">

                <?php
                // VEHICLE MODEL LABEL AND INPUT ***********************************
                echo form_label('Course Name','courseName',array('class'=>'form_label'));

                echo input_requirement();

                // If POST, there may be vehicle models
                if( isset( $courseNames ) && ! empty( $courseNames ) )
                {
                    // Default option
                    $vehicle_courseNames[] = '-- Select --';

                    // Options from query
                    foreach( $courseNames as $row )
                    {
                        $vehicle_courseNames[$row['courseName']] = $row['courseName'];
                    }
                }

                // If POST and makes not empty
                else if( isset( $terms ) && ! empty( $terms ) )
                {
                    $vehicle_courseNames[] = '-- Select term --';
                }
                else
                {
                    // Default option if not POST request
                    $vehicle_courseNames[] = '-- Select year --';
                }

                echo form_dropdown( 'courseName', $vehicle_courseNames, set_value('courseName'), 'id="courseName" class="form_select"' );

                ?>

            </div>
            <div class="form-row">

                <?php
                // VEHICLE COLOR LABEL AND INPUT ***********************************
                echo form_label('Section','section',array('class'=>'form_label'));

                echo input_requirement();

                // If POST, there may be vehicle models
                if( isset( $sections ) && ! empty( $sections ) )
                {
                    // Default option
                    $vehicle_sections[] = '-- Select --';

                    // Options from query
                    foreach( $sections as $row )
                    {
                        $vehicle_sections[$row['section']] = $row['section'];
                    }
                }

                // If POST and models not empty
                else if( isset( $courseNames ) && ! empty( $courseNames ) )
                {
                    $vehicle_sections[] = '-- Select courseName --';
                }
                // If POST and makes not empty
                else if( isset( $terms ) && ! empty( $terms ) )
                {
                    $vehicle_sections[] = '-- Select term --';
                }
                else
                {
                    // Default option if not POST request
                    $vehicle_sections[] = '-- Select year --';
                }

                echo form_dropdown( 'section', $vehicle_sections, set_value('section'), 'id="section" class="form_select"' );

                ?>

            </div>
            <input type="hidden" id="ci_csrf_token_name" value="<?php echo config_item('csrf_token_name'); ?>" />
            <input type="hidden" id="ajax_url" value="<?php echo if_secure_site_url('auto_populate/process_request/example'); ?>" />
        </fieldset>
        <div class="form-row">
            <div id="submit_box">

                <?php
                // SUBMIT BUTTON ***********************
                $input_data = array(
                    'name'		=> 'get_classes',
                    'id'		=> 'submit_button',
                    'value'		=> 'Submit'
                );

                echo form_submit($input_data);
                ?>

            </div>
        </div>
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

                if (!empty($classes)) {
                    //$denial_reasons = config_item('denied_access_reason');

                    foreach ($classes as $row) {
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
        </div>
    </div>
    </form>
<?php

/* End of file auto_populate.php */
/* Location: /application/views/auto_populate/auto_populate.php */