<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Community Auth - Administration Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
class Administration extends MY_Controller
{

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Force encrypted connection
        $this->force_ssl();
    }

    // --------------------------------------------------------------

    /**
     * Create a user
     */
    public function create_user($type = '')
    {
        // Make sure an admin or manager is logged in. (showing how to log in by group)
        if ($this->require_group('employees')) {
            $view_data['roles'] = $this->authentication->roles;

            if (!empty($type)) {
                // Get level associated with type (role)
                $view_data['level'] = $this->authentication->levels[$type];
                $view_data['type'] = $type;

                // Confirm the type of user is permitted
                if ($this->auth_level <= $view_data['level']) {
                    die();
                }

                // Check if a valid form submission has been made
                if ($this->tokens->match) {
                    // Create the user
                    $this->load->model('user_model');
                    $this->user_model->create_user($type);
                }

                // Load the appropriate user creation form
                $view_data['user_creation_form'] = $this->load->view('administration/create_user/create_' . $type, '', TRUE);
            } /**
             * If there is only one choice of user types to create,
             * we'll just automatically redirect to that choice
             */
            else {
                foreach ($this->authentication->roles as $k => $v) {
                    if ($k < $this->auth_level) {
                        $types[] = $v;
                    }
                }

                // If there's only one choice
                if (isset($types) && count($types) == 1) {
                    redirect(secure_site_url('administration/create_user/' . $types[0]));
                }
            }

            $data = array(
                'content' => $this->load->view('administration/create_user', (isset($view_data)) ? $view_data : '', TRUE),

                // Load the show password script
                'javascripts' => array(
                    'js/jquery.passwordToggle-1.1.js',
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                ),

                // Use the show password script
                'extra_head' => '
					<script>
						$(document).ready(function(){
							$("#show-password").passwordToggle({target:"#user_pass"});
						});
					</script>
				'
            );

            $this->load->view($this->template, $data);

        }

    }

    // --------------------------------------------------------------

    /**
     * Manage users
     *
     * @param  int  the pagination page number
     */
    public function manage_users($page = 1)
    {
        // Make sure an admin or manager is logged in
        if ($this->require_role('admin,manager')) {
            // Load resources
            $this->load->model('user_model');
            $this->load->library('pagination');

            // If an ajax request
            if ($this->input->is_ajax_request()) {
                // If form token matches
                if ($this->tokens->match) {
                    // Get table and pagination content
                    $this->_manage_users_table_content($page, 'json');
                } else {
                    // Send error back instead of table and pagination content
                    $view_data = array(
                        'test' => 'error',
                        'message' => 'No Token Match - Please Reload Page'
                    );

                    // Send response
                    echo json_encode($view_data);
                }
            } // If the initial page load, or a standard request
            else {
                $view_data = $this->_manage_users_table_content($page);

                $data = array(
                    'javascripts' => array(
                        'js/administration/manage-users.js'
                    ),
                    'content' => $this->load->view('administration/manage_users', $view_data, TRUE)
                );

                $this->load->view($this->template, $data);
            }
        }

    }

    // --------------------------------------------------------------

    /**
     * Create user management table content
     *
     * @param  int     the pagination page number
     * @param  mixed   NULL if a standard page request or json if an ajax request
     */
    private function _manage_users_table_content($page = 1, $format = NULL)
    {
        // The pagination class doesn't allow for multiple config files, so we load configuration the old fashion way
        $this->config->load('pagination/administration/manage_users_pagination');
        $pagination_config = config_item('manage_users_pagination_settings');

        // If search_in and search_for are not empty, this is a search
        $search_in = $this->input->post('search_in', TRUE);
        $search_for = $this->input->post('search_for', TRUE);

        // Make sure that search_in is one of the allowable values in the config
        if ($search_in !== FALSE && !in_array($search_in, array_keys(config_item('manage_users_search_options')))) {
            $search_in = FALSE;
        }

        // Set the query params for both count and data queries
        $query_params = array(
            'user_level' => $this->auth_level,
            'search_in' => $search_in,
            'search_for' => $search_for,
            'limit' => $pagination_config['per_page'],
            'page' => (int)$page
        );

        // Get the total rows that match the requested set of users, or all by default
        $pagination_config['total_rows'] = $this->user_model->manage_user_records_data($query_params, TRUE);

        // Initialize pagination and create links
        $this->pagination->initialize($pagination_config);
        $view_data['pagination_links'] = $this->pagination->create_links();

        // Get the actual user data that matches the requested set of users
        $view_data['users_data'] = $this->user_model->manage_user_records_data();

        // Get roles for view
        $view_data['roles'] = $this->authentication->roles;

        // Insert the user data into table rows ( a nested view )
        $view_data['table_content'] = $this->load->view('administration/manage_users_table_content', $view_data, TRUE);

        // If an ajax request
        if ($format == 'json') {
            // Remove the raw user data from the ajax response
            unset($view_data['users_data']);

            // Add success confirmation, token, and CI csrf token to the response
            $view_data['test'] = 'success';
            $view_data['token'] = $this->tokens->token();
            $view_data['ci_csrf_token'] = $this->security->get_csrf_hash();

            // Send the ajax response
            echo json_encode($view_data);
        } // If the initial page load, or standard request
        else {
            return $view_data;
        }
    }

    // --------------------------------------------------------------

    /**
     * Delete a user
     *
     * @param  int  the user_id of the user to delete.
     * @param  int  the pagination page number to redirect back to.
     */
    public function delete_user($user_to_delete = FALSE, $page = FALSE)
    {
        // Make sure admin or manager is logged in
        if ($this->require_role('admin,manager')) {
            // Load resources
            $this->load->model('user_model');

            // Must not be a user trying to delete themeselves
            if (is_numeric($user_to_delete) && $user_to_delete != $this->auth_user_id) {
                // If an ajax request
                if ($this->input->is_ajax_request()) {
                    // Must pass token match and delete_user must return TRUE
                    if (
                        $this->tokens->match &&
                        $this->user_model->delete_user($user_to_delete, $this->auth_level)
                    ) {
                        // Send success message back
                        $response = array(
                            'test' => 'success',
                            'token' => $this->tokens->token(),
                            'ci_csrf_token' => $this->security->get_csrf_hash()
                        );
                    } else {
                        // CSRF token mismatch or delete_user was FALSE
                        $response = array(
                            'test' => 'error',
                            'message' => 'No Token Match - Please Reload Page'
                        );
                    }

                    echo json_encode($response);
                } // If standard request
                else {
                    $test = $this->user_model->delete_user($user_to_delete, $this->auth_level);

                    $page = ($page) ? '/' . $page : '';

                    header("Location: " . secure_site_url('administration/manage_users' . $page));

                    exit;
                }
            }
        }
    }

    // --------------------------------------------------------------

    /**
     * Update a user
     */
    public function update_user($the_user = 0)
    {
        // Make sure an admin or manager is logged in
        if ($this->require_role('admin,manager')) {
            // Load resources
            $this->load->library('encrypt');
            $this->load->model('user_model');
            $this->config->load('uploads_manager');

            // Get the user level of the user to be updated
            $users_level = $this->user_model->view_user_record($the_user, 'user_level');

            /**
             * If the user to be updated has a user level greater than or equal
             * to the logged in user, then we don't want to show the user's details
             */
            if ($users_level >= $this->auth_level) {
                die();
            }

            /**
             * Get the role associated with the user level
             */
            $role = $this->authentication->roles[$users_level];

            /*
             * Check if form posted
             */
            if ($this->tokens->match) {
                // Update the user
                $this->user_model->update_user($role, $the_user, 'update_user');
            }

            // Show the current user and profile record
            $user_row = $this->user_model->view_user_record($the_user);

            // Decrypt any sensitive data for display
            if (isset($user_row->license_number)) {
                $user_row->license_number = $this->encrypt->decode($user_row->license_number);
            }

            // Send user data to view
            $view_data['user_data'] = $user_row;

            $data = array(

                // Load the show password script
                'javascripts' => array(
                    'js/jquery.passwordToggle-1.1.js',
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                ),

                // Use the show password script
                'extra_head' => '
					<script>
						$(document).ready(function(){
							$("#show-password").passwordToggle({target:"#user_pass"});
							$("#show-password").passwordToggle({target:"#user_pass_confirm"});
						});
					</script>
				',

                'content' => $this->load->view('administration/update_user/update_' . $role, $view_data, TRUE)
            );

            $this->load->view($this->template, $data);
        }
    }

    // --------------------------------------------------------------

    /**
     * Deny access and manage denied access to an IP, IP block, etc.
     *
     * Here you can deny access or manage the deny list in
     * your local Apache configuration file. Please note that we've
     * all had experiences where a little mistake in one of these
     * files can bring down the whole website. For this reason,
     * access is restricted to admin only.
     */
    public function deny_access()
    {
        // Make sure admin is logged in
        if ($this->require_role('admin')) {
            if (config_item('deny_access') > 0) {
                // If POST, do delete or addition of IP
                if ($this->tokens->match) {
                    $this->auth_model->process_denial();
                }

                // Get the current deny list
                $view_data['deny_list'] = $this->auth_model->get_deny_list();
            }

            $data = array(
                'content' => $this->load->view('administration/deny_access', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                )
            );

            $this->load->view($this->template, $data);
        }
    }

    public function term()
    {
        // Make sure admin is logged in
        if ($this->require_role('admin')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP
                if ($this->tokens->match) {
                    $this->auth_model->process_term();
                }

                // Get the current deny list
                $view_data['term_list'] = $this->auth_model->get_term_list();
            }

            $data = array(
                'content' => $this->load->view('administration/term', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                )
            );

            $this->load->view($this->template, $data);
        }
    }

    public function course()
    {
        // Make sure admin is logged in
        if ($this->require_role('admin')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP
                if ($this->tokens->match) {
                    $this->auth_model->process_course();
                }

                // Get the current deny list
                $view_data['course_list'] = $this->auth_model->get_course_list();
            }

            $data = array(
                'content' => $this->load->view('administration/course', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                )
            );

            $this->load->view($this->template, $data);
        }
    }

    // --------------------------------------------------------------
    public function timeslot()
    {
        // Make sure admin is logged in
        if ($this->require_role('admin')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP
                if ($this->tokens->match) {
                    $this->auth_model->process_stuff(config_item('timeslot_table'), 'timeslot');
                }


                // Get the current deny list
                $view_data['stuff_list'] = $this->auth_model->get_stuff_list(config_item('timeslot_table'));

            }

            $data = array(
                'content' => $this->load->view('administration/timeslot', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                )
            );

            $this->load->view($this->template, $data);
        }
    }

    // --------------------------------------------------------------
    public function building()
    {
        // Make sure admin is logged in
        if ($this->require_role('admin')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP
                if ($this->tokens->match) {
                    $this->auth_model->process_stuff(config_item('building_table'),'building');
                }

                // Get the current deny list
                $view_data['stuff_list'] = $this->auth_model->get_stuff_list(config_item('building_table'));
            }

            $data = array(
                'content' => $this->load->view('administration/building', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                )
            );

            $this->load->view($this->template, $data);
        }
    }

    // --------------------------------------------------------------
    public function room()
    {
        // Make sure admin is logged in
        if ($this->require_role('admin')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP
                if ($this->tokens->match) {
                    $this->auth_model->process_stuff(config_item('room_table'),'room');
                }

                // Get the current deny list
                $view_data['stuff_list'] = $this->auth_model->get_stuff_list(config_item('room_table'));
            }

            $data = array(
                'content' => $this->load->view('administration/room', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                )
            );

            $this->load->view($this->template, $data);
        }
    }

    // --------------------------------------------------------------
    public function section()
    {
        // Make sure admin is logged in
        if ($this->require_role('admin')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP
                if ($this->tokens->match) {
                    $this->auth_model->process_section();
                    if( $this->input->post('modify') ){
                        $view_data['modify'] = $this->auth_model->getips;
                        print_r($view_data['modify']);

                    }
                }

                // Get the current deny list
                $view_data['course_name_list'] = $this->auth_model->get_course_name();
                $view_data['course_list']=$this->auth_model->get_course_list();
                $view_data['term_list'] = $this->auth_model->get_term_list();
                $view_data['timeslot_list'] = $this->auth_model->get_stuff_list(config_item('timeslot_table'));
                $view_data['building_list'] = $this->auth_model->get_stuff_list(config_item('building_table'));
                $view_data['room_list'] = $this->auth_model->get_stuff_list(config_item('room_table'));
                $view_data['sect_list'] = $this->auth_model->get_section_list();
                //print_r($view_data['sect_list']);
                $view_data['instructor_list'] = $this->auth_model->get_instructor_list(config_item('manager_profiles_table'));

            }

            $data = array(
                'content' => $this->load->view('administration/section', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                )
            );

            $this->load->view($this->template, $data);
        }
    }

    /*==================================================
    =================================================
    =================================================
    =================================================
    =================================================
    */

    public function modifyclass()
    {
        // Make sure admin is logged in
        if ($this->require_role('admin')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP

                if ($this->tokens->match) {
                    $view_data['modify'] = $this->auth_model->process_modify();
                    print_r($view_data['modify']);



                }

                // Get the current deny list
                $view_data['course_name_list'] = $this->auth_model->get_course_name();
                $view_data['course_list']=$this->auth_model->get_course_list();
                $view_data['term_list'] = $this->auth_model->get_term_list();
                $view_data['timeslot_list'] = $this->auth_model->get_stuff_list(config_item('timeslot_table'));
                $view_data['building_list'] = $this->auth_model->get_stuff_list(config_item('building_table'));
                $view_data['room_list'] = $this->auth_model->get_stuff_list(config_item('room_table'));
                $view_data['sect_list'] = $this->auth_model->get_section_list();
                //print_r($view_data['sect_list']);
                $view_data['instructor_list'] = $this->auth_model->get_instructor_list(config_item('manager_profiles_table'));


            }


            $data = array(
                'content' => $this->load->view('administration/modifyclass', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                    //    'js/administration/dropdown.js'
                )
            );

            $this->load->view($this->template, $data);
        }
    }
    public function admin_modify_schedule()
    {
        // Make sure admin is logged in
        if ($this->require_role('admin')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP
            }

            // $this->load->helper(array('url','form','html'));
            $view_data['years_list'] = $this->auth_model->get_modify_class_year();
            //  $this->load->view('multi_message', $data);

            $data = array(
                'content' => $this->load->view('administration/modifyClassSchedule', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                    //    'js/administration/dropdown.js'
                )
            );

            $this->load->view($this->template, $data);
        }
    }

    public function datatables_stuff()
    {
        // Make sure admin is logged in
        //if ($this->require_role('admin')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP
            }
            //$view_data['schedule'] = $this->auth_model->get_stuff_list(config_item('section_table'));
            $view_data['schedule'] = $this->auth_model->get_teacher_schedule(config_item('section_table'));
           // print_r($view_data['schedule']);
            $data = array(
                'content' => $this->load->view('administration/datatables_stuff', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                )
            );
        //if(isset(config_item('auth_first_name')))
            //print_r(config_item('auth_first_name'));
       // else

            $this->load->view($this->template, $data);
        //}
        // Make sure teacher is logged in
       /* if ($this->require_role('manager')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP
            }

            $view_data['schedule'] = $this->auth_model->get_teacher_schedule(config_item('section_table'), config_item('auth_first_name'), config_item('auth_last_name'));

            $data = array(
                'content' => $this->load->view('administration/datatables_stuff', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                )
            );
            $this->load->view($this->template, $data);
        }
        // Make sure user is logged in
        if ($this->require_role('customer')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP
            }
            $view_data['schedule'] = $this->auth_model->get_stuff_list(config_item('section_table'));

            $data = array(
                'content' => $this->load->view('administration/datatables_stuff', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                )
            );
            $this->load->view($this->template, $data);
        }*/
            //$this->load->view($this->template, $data);

    }


    /* public function multi_drop()
     {
         $this->load->helper(array('url','form','html'));
         $this->load->model('zip_model', '', TRUE);
         $data['all_states'] = $this->zip_model->get_state_list();
         $this->load->view('multi_message', $data);
     }
 */
    public function handle_submission()
    {
        $this->load->helper(array('url','form','html'));
        $this->load->view('multi_response');
    }

    public function ajaxdrop()
    {
        if($this->_is_ajax()){
            // $this->load->helper(array('url','form','html'));
            // $this->load->model('zip_model', '', TRUE);
            $year = $this->input->get('year', TRUE);
            $data['term_list'] = $this->auth_model->get_modify_class_term_in_year($year);
            $term = $this->input->get('term', TRUE);
            $data['course_list'] = $this->auth_model->get_modify_class_course_in_term($year,$term);
            $course = $this->input->get('course', TRUE);
            $data['section_list'] = $this->auth_model->get_modify_class_section_in_course($year,$term, $course);


            //echo "State is $state";
            echo json_encode($data);
            //return $data;
        }else{
            echo "Apparently is_ajax returned false!";
            show_error('This method can only be accessed internally.', 404);
        }
    }
    /**
     * Checks to see if its an AJAX call.
     */
    function _is_ajax(){
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }


    // --------------------------------------------------------------
}

/* End of file administration.php */
/* Location: /application/controllers/administration.php */