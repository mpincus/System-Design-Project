<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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
        This is just a simple example to show how to dynamically populate form dropdowns using jQuery. There are a lot
        of questions in the CodeIgniter forum asking how to do this, and for my own projects I felt the need to perfect
        this task. While there is no form validation for this example, most everything else is complete so you can have
        a solid working example for your own needs.
    </p>
<?php echo form_open('administration/admin_modify_schedule', array('class' => 'std-form', 'style' => 'margin-top:24px;','id' => 'form1')); ?>
    <div class="form-column-left">
        <fieldset>
            <legend>Select Vehicle:</legend>
            <div class="form-row">

                <?php

                $state_options = array('select');
                foreach($years_list as $year){
                    $state_options[$year->year] = $year->year;
                }

                echo form_label('Choose a year: ', 'years', array('class' => 'form_label'));
                echo form_dropdown('years', $state_options, '', 'id="yeardrop" class="form_select"');
                echo form_label('Choose a term: ', 'terms', array('class' => 'form_label'));
                echo form_dropdown('terms', array('select'), '', 'id="termdrop" class="form_select"');
                echo form_label('Choose a course: ', 'course', array('class' => 'form_label'));
                echo form_dropdown('course', array('select'), '', 'id="coursedrop" class="form_select"');
                echo form_label('Choose a section: ', 'section', array('class' => 'form_label'));
                echo form_dropdown('section', array('select'), '', 'id="sectiondrop" class="form_select"');
                echo br(3);
                echo form_submit('zipsubmit', 'Get Data');


                /*
                // VEHICLE TYPE LABEL AND INPUT ***********************************
                echo form_label('Year', 'year', array('class' => 'form_label'));

                echo input_requirement();

                // Default option
                $year_list[] = '-- Select --';

                // Options from query
                foreach ($years as $row) {
                    $year_list[$row->year] = $row->year;
                }

                echo form_dropdown('year', $year_list, set_value('years'), 'id="year" class="form_select"');

                */?>

            </div>

        </fieldset>
        <div class="form-row">
            <div id="submit_box">

                <?php /*
                // SUBMIT BUTTON ***********************
                $input_data = array(
                    'name' => 'submit',
                    'id' => 'submit_button',
                    'value' => 'Submit'
                );

                echo form_submit($input_data);
                */?>

            </div>
        </div>
    </div>
    </form>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/administration/dropdown.js"></script>
<?php

/* End of file auto_populate.php */
/* Location: /application/views/auto_populate/auto_populate.php */