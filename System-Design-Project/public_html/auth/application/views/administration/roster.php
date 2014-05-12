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

    <link rel="stylesheet" type="text/css" href="DataTables/media/css/demo_page.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="DataTables/media/css/demo_table.css" media="screen" />


    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.3.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>

    <script type="text/javascript" charset="utf-8">
        var asInitVals = new Array();

        $(document).ready(function () {
            var oTable = $('#example').dataTable({
                "oLanguage": {
                    "sSearch": "Search all columns:"
                }
            });

            $("tfoot input").keyup(function () {
                /* Filter on the column (the index) of this element */
                oTable.fnFilter(this.value, $("tfoot input").index(this));
            });


            /*
             * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
             * the footer
             */
            $("tfoot input").each(function (i) {
                asInitVals[i] = this.value;
            });

            $("tfoot input").focus(function () {
                if (this.className == "search_init") {
                    this.className = "";
                    this.value = "";
                }
            });

            $("tfoot input").blur(function (i) {
                if (this.value == "") {
                    this.className = "search_init";
                    this.value = asInitVals[$("tfoot input").index(this)];
                }
            });
        });
    </script>
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
    <?php
   /* if(($auth_level < 9)){
        if(!isset($workdamnit)){
            echo '<div id="decision_buttons">';
            echo '<input type="submit" class="form_button" name="view schedule" value="View Schedule"';
            echo 'style="margin-top:10px;"/></div>';
        }
    }*/
   // echo $courses_teaching;
    //print_r($_POST['grades']);
    echo '<fieldset>';
    echo '<legend>Courses Teaching</legend>';
    $currentSeason = '';
    if(date("m") < 6)
        $currentSeason='Spring';
    else
        $currentSeason='Fall';
    foreach ($courses_teaching as $k => $v){
        if(($v['year'] == date("Y")) && ($v['term'] == $currentSeason)){

    echo '<li>' . secure_anchor('administration/roster?n=' . $v['ID'], $v['year'].' '.$v['term'].' '.$v['courseName'].' '.$v['section']) . '</li>';
    }
    }
    echo '</fieldset>';
    ?>

    <div id="'dt_example">
    <div id="container">
    <div id="demo">
    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
<?php if(isset($_GET['n'])){ ?>
    <h2>Deny List</h2>

    <div id="table-wrapper">
    <table aria-describedby="example_info" class="display dataTable" id="example" border="0"
           cellpadding="0" cellspacing="0">
        <thead>
        <tr role="row">

            <th aria-label="Rendering engine: activate to sort column descending"
                aria-sort="ascending"
                style="width: 136px;" colspan="1" rowspan="1" aria-controls="example"
                tabindex="0"
                role="columnheader" class="sorting_asc">ID
            </th>
            <th aria-label="Browser: activate to sort column ascending" style="width: 134px;"
                colspan="1"
                rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                class="sorting">UserName
            </th>
            <th aria-label="Platform(s): activate to sort column ascending"
                style="width: 138px;" colspan="1"
                rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                class="sorting">First Name
            </th>
            <th aria-label="Engine version: activate to sort column ascending"
                style="width: 128px;" colspan="1"
                rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                class="sorting">Last Name
            </th>
            <th aria-label="CSS grade: activate to sort column ascending" style="width: 123px;"
                colspan="1"
                rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                class="sorting">Email
            </th>
            <th aria-label="CSS grade: activate to sort column ascending" style="width: 123px;"
                colspan="1"
                rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                class="sorting">Midterm
            </th>
            <th aria-label="CSS grade: activate to sort column ascending" style="width: 123px;"
                colspan="1"
                rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                class="sorting">Final
            </th>
            <th></th>


        </tr>
        </thead>
        <tfoot>
        <tr>

            <th colspan="1" rowspan="1"><input name="search_engine" value="Search engines" class="search_init"
                                               type="text"></th>
            <th colspan="1" rowspan="1"><input name="search_browser" value="Search browsers" class="search_init"
                                               type="text"></th>
            <th colspan="1" rowspan="1"><input name="search_platform" value="Search platforms"
                                               class="search_init" type="text"></th>
            <th colspan="1" rowspan="1"><input name="search_version" value="Search versions" class="search_init"
                                               type="text"></th>
            <th colspan="1" rowspan="1"><input name="search_grade" value="Search grades" class="search_init"
                                               type="text"></th>
            <th colspan="1" rowspan="1"><input name="search_grade" value="Search grades" class="search_init"
                                               type="text"></th>
            <th colspan="1" rowspan="1"><input name="search_engine" value="Search engines" class="search_init"
                                               type="text"></th>
            <th colspan="1" rowspan="1"><input name="search_engine" value="Search engines" class="search_init" hidden="true"
                                               type="text"></th>

        </tr>
        </tfoot>
        <tbody aria-relevant="all" aria-live="polite" role="alert">

        <?php

        if (!empty($roster)) {
            //$denial_reasons = config_item('denied_access_reason');

            foreach ($roster as $row) {
               if($auth_level == 6){

                    echo '
                    <input type="text"  hidden="true" name="course" value="' . $row['cID'].'">
                                <tr class="gradeA odd">

                                    <td class="  sorting_1">'
                        . $row['user_id'] .

                        '</td>
                        <td>'
                        . $row['user_name'] .
                        '</td>
                        <td>'
                        . $row['first_name'] .

                        '</td>
                         <td>'
                        . $row['last_name'] .
                        '</td>
                        <td>'
                        . $row['user_email'] .
                        '</td>
                          <td><input type="text" id="stuff" name="midterms[]" value="' . $row['midterm'].'">'.

                        '</td>
                        <td><input type="text" id="stuff" name="finals[]" value="' . $row['final'].'">'.

                        '</td>
                        <td>
                                        <input type="checkbox"  name="ip_removals[]" value="' . $row['user_id'].'" />
                                    </td>







                    </tr>
                        ';

                   echo '';


               }
            }

            }
        }

        ?>

        </tbody>
    </table>
    </div>
    <?php if(!isset($workdamnit)){?>
        <div id="decision_buttons">
            <input type="submit" class="form_button" name="remove_selected" value="Remove Selected"
                   style="margin-top:10px;"/>
        </div>
    <?php }else{?>
        <div id="decision_buttons">
            <input type="submit" class="form_button" name="drop_selected" value="Drop Selected"
                   style="margin-top:10px;"/>
        </div>

    <?php }?>

    </div>
    </div>
    </div>
    </div>

    </form>
    </div>


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