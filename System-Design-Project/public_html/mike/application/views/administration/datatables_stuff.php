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


    <div id="'dt_example">
        <div id="container">
            <div id="demo">
                <div id="example_wrapper" class="dataTables_wrapper" role="grid">

                        <h2>Deny List</h2>

                        <div id="table-wrapper">
                            <table aria-describedby="example_info" class="display dataTable" id="example" border="0"
                                   cellpadding="0" cellspacing="0">
                                <thead>
                                <tr role="row">
                                    <th></th>
                                    <th aria-label="Rendering engine: activate to sort column descending"
                                        aria-sort="ascending"
                                        style="width: 136px;" colspan="1" rowspan="1" aria-controls="example"
                                        tabindex="0"
                                        role="columnheader" class="sorting_asc">Term
                                    </th>
                                    <th aria-label="Browser: activate to sort column ascending" style="width: 134px;"
                                        colspan="1"
                                        rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                                        class="sorting">Year
                                    </th>
                                    <th aria-label="Platform(s): activate to sort column ascending"
                                        style="width: 138px;" colspan="1"
                                        rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                                        class="sorting">Course
                                    </th>
                                    <th aria-label="Engine version: activate to sort column ascending"
                                        style="width: 128px;" colspan="1"
                                        rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                                        class="sorting">Section
                                    </th>
                                    <th aria-label="CSS grade: activate to sort column ascending" style="width: 123px;"
                                        colspan="1"
                                        rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                                        class="sorting">Instructor
                                    </th>
                                    <th aria-label="CSS grade: activate to sort column ascending" style="width: 123px;"
                                        colspan="1"
                                        rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                                        class="sorting">TimeSlot
                                    </th>
                                    <th aria-label="CSS grade: activate to sort column ascending" style="width: 123px;"
                                        colspan="1"
                                        rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                                        class="sorting">Room
                                    </th>
                                    <th aria-label="CSS grade: activate to sort column ascending" style="width: 123px;"
                                        colspan="1"
                                        rowspan="1" aria-controls="example" tabindex="0" role="columnheader"
                                        class="sorting">Building

                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th colspan="1" rowspan="1"><input name="search_engine" value="Search engines" class="search_init" hidden="true"
                                                                       type="text"></th>
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
                                    <th colspan="1" rowspan="1"><input name="search_grade" value="Search grades" class="search_init"
                                                                       type="text"></th>
                                    <th colspan="1" rowspan="1"><input name="search_grade" value="Search grades" class="search_init"
                                                                       type="text"></th>
                                </tr>
                                </tfoot>
                                <tbody aria-relevant="all" aria-live="polite" role="alert">

                                <?php

                                if (!empty($schedule)) {
                                    //$denial_reasons = config_item('denied_access_reason');

                                    foreach ($schedule as $row) {
                                        echo '
                                <tr class="gradeA odd">
                                    <td>
                                        <input type="checkbox" name="ip_removals[]" value="' . $row->ID . '" />
                                    </td>
                                    <td class="  sorting_1">'
                                            . $row->term .

                                            '</td>
                                            <td>'
                                            . $row->year .
                                            '</td>
                                            <td>'
                                            . $row->courseName .

                                            '</td>
                                             <td>'
                                            . $row->section .
                                            '</td>
                                            <td>'
                                            . $row->teacher .
                                            '</td>
                                            <td>'
                                            . $row->timeslot .
                                            '</td>
                                            <td>'
                                            . $row->room .
                                            '</td>
                                            <td>'
                                            . $row->building .
                                            '</td>

                                        </tr>
                                            ';
                                    }
                                }

                                ?>

                                </tbody>
                            </table>
                        </div>
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