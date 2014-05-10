<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Community Auth - Auth_model Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
class Auth_model extends MY_Model
{

    /**
     * An array of profile data to select when logging in or checking login.
     * Anything in this array should exist in all user profile tables
     *
     * @var array
     * @access protected
     */
    protected $selected_profile_columns = array();
    var $getips;

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->selected_profile_columns = config_item('selected_profile_columns');

        //$this->getips;
    }

    /**
     * Check the user table to see if a user exists by username or email address.
     * If there was a matching record, combine with select profile data and
     * return merged object.
     *
     * While this query is rather limited, you could easily add specific user
     * profile data. Remember, however, that while encrypted by
     * default, the http user cookie is tranmitted via standard http requests.
     *
     * @param   string  either the username or email address of a user
     * @return  mixed   either query data as object or FALSE
     */
    public function get_auth_data($user_string)
    {
        // Selected user table data
        $selected_columns = array(
            'user_name',
            'user_email',
            'user_level',
            'user_pass',
            'user_salt',
            'user_id',
            'user_modified',
            'user_banned'
        );

        // User table query
        $query = $this->db->select($selected_columns)
            ->from(config_item('user_table'))
            ->where('user_name', $user_string)
            ->or_where('user_email', $user_string)
            ->limit(1)
            ->get();

        if ($query->num_rows() == 1) {
            $row = $query->row_array();

            // Profile data query
            if (!empty($this->selected_profile_columns)) {
                // Get the role associated with the user level
                $role = $this->authentication->roles[$row['user_level']];

                $query = $this->db->select($this->selected_profile_columns)
                    ->from(config_item($role . '_profiles_table'))
                    ->where('user_id', $row['user_id'])
                    ->limit(1)
                    ->get();

                if ($query->num_rows() == 1) {
                    // Merge the user data and profile data and return
                    return (object)array_merge($row, $query->row_array());
                }
            }

            return $row;
        }

        return FALSE;
    }

    // --------------------------------------------------------------

    /**
     * Update the user's user table record when they login
     *
     * @param  array  the user's user table data
     */
    public function login_update($user_id, $login_time, $session_id)
    {
        $data = array(
            'user_last_login' => $login_time,
            'user_login_time' => $login_time,
            'user_agent_string' => md5($this->input->user_agent()),
            'user_session_id' => $session_id
        );

        $this->db->where('user_id', $user_id)
            ->update(config_item('user_table'), $data);
    }

    // --------------------------------------------------------------

    /**
     * Check user table and confirm there is a record where:
     *
     * 1) The last user modification date matches
     * 2) The user ID matches
     * 3) The user login time matches ( if multiple logins are not allowed )
     *
     * If there is a matching record, return a specified subset of the record.
     *
     * @param   int    the last modification date
     * @param   int    the user ID
     * @return  mixed  either query data as an object or FALSE
     */
    public function check_login_status($user_last_mod, $user_id, $login_time)
    {
        // Selected user table data
        $selected_columns = array(
            'user_name',
            'user_email',
            'user_level',
            'user_agent_string',
            'user_id',
            'user_banned'
        );

        $this->db->select($selected_columns);
        $this->db->from(config_item('user_table'));
        $this->db->where('user_modified', $user_last_mod);
        $this->db->where('user_id', $user_id);

        /**
         * If multiple devices are allowed to login at the same time,
         * the user_login_time cannot be checked. The session ID is also useless.
         */
        if (config_item('disallow_multiple_logins') === TRUE) {
            $this->db->where('user_login_time', $login_time);

            // If the session ID was NOT regenerated, the session IDs should match
            if (is_null($this->session->regenerated_session_id)) {
                $this->db->where('user_session_id', $this->session->userdata('session_id'));
            } // If it was regenerated, we can only compare the old session ID for this request
            else {
                $this->db->where('user_session_id', $this->session->pre_regenerated_session_id);
            }
        }

        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $row = $query->row_array();

            // Profile data query
            if (!empty($this->selected_profile_columns)) {
                // Get the role associated with the user level
                $role = $this->authentication->roles[$row['user_level']];

                $query = $this->db->select($this->selected_profile_columns)
                    ->from(config_item($role . '_profiles_table'))
                    ->where('user_id', $row['user_id'])
                    ->limit(1)
                    ->get();

                if ($query->num_rows() == 1) {
                    // Merge the user data and profile data and return
                    return (object)array_merge($row, $query->row_array());
                }
            }

            return $row;
        }

        return FALSE;
    }

    // --------------------------------------------------------------

    /**
     * Update a user's user record session ID if it was regenerated
     */
    public function update_user_session_id($user_id)
    {
        if (!is_null($this->session->regenerated_session_id)) {
            $this->db->where('user_id', $user_id)
                ->update(
                    config_item('user_table'),
                    array('user_session_id' => $this->session->regenerated_session_id)
                );
        }
    }

    // -----------------------------------------------------------------------

    /**
     * Clear user holds that have expired
     */
    public function clear_expired_holds()
    {
        $duration = time() - config_item('seconds_on_hold');

        $this->db->delete(config_item('IP_hold_table'), array('time <' => $duration));

        $this->db->delete(config_item('username_or_email_hold_table'), array('time <' => $duration));
    }

    // --------------------------------------------------------------

    /**
     * Clear login errors that have expired
     */
    public function clear_login_errors()
    {
        $duration = time() - config_item('seconds_on_hold');

        $this->db->delete(config_item('errors_table'), array('time <' => $duration));
    }

    // --------------------------------------------------------------

    /**
     * Check that the IP address, username, or email address is not on hold.
     *
     * @param   bool   if check is from recovery (FALSE if from login)
     * @return  bool
     */
    public function check_holds($recovery)
    {
        $ip_hold = $this->check_ip_hold();

        $string_hold = $this->check_username_or_email_hold($recovery);

        if ($ip_hold === TRUE OR $string_hold === TRUE) {
            return TRUE;
        }

        return FALSE;
    }

    // --------------------------------------------------------------

    /**
     * Check that the IP address is not on hold
     *
     * @return  bool
     */
    public function check_ip_hold()
    {
        $ip_hold = $this->db->get_where(
            config_item('IP_hold_table'),
            array('IP_address' => $this->input->ip_address())
        );

        if ($ip_hold->num_rows() > 0) {
            return TRUE;
        }

        return FALSE;
    }

    // --------------------------------------------------------------

    /**
     * Check that the username or email address is not on hold
     *
     * @param   bool   if check is from recovery (FALSE if from login)
     * @return  bool
     */
    public function check_username_or_email_hold($recovery)
    {
        $posted_string = (!$recovery) ? $this->input->post('login_string') : $this->input->post('user_email', TRUE);

        // Check posted string for basic validity.
        if (!empty($posted_string) && strlen($posted_string) < 256) {
            $string_hold = $this->db->get_where(
                config_item('username_or_email_hold_table'),
                array('username_or_email' => $posted_string)
            );

            if ($string_hold->num_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }

    // --------------------------------------------------------------

    /**
     * Insert a login error into the database
     *
     * @param  array  the details of the login attempt
     */
    public function create_login_error($data)
    {
        $this->db->set($data)
            ->insert(config_item('errors_table'));
    }

    // --------------------------------------------------------------

    /**
     * Check login errors table to determine if a IP address, username,
     * or email address should be placed on hold.
     *
     * @param  string  the supplied username or email address
     */
    public function check_login_attempts($string)
    {
        $ip_address = $this->input->ip_address();

        // Check if this IP now has too many login attempts
        $count = $this->db->where('IP_address', $ip_address)
            ->count_all_results(config_item('errors_table'));

        if ($count == config_item('max_allowed_attempts')) {
            // Place the IP on hold
            $data = array(
                'IP_address' => $ip_address,
                'time' => time()
            );

            $this->db->set($data)
                ->insert(config_item('IP_hold_table'));
        } /**
         * If for some reason login attempts exceed
         * the max_allowed_attempts number, we have
         * the option of banning the user by IP address.
         */
        else if (
            $count > config_item('max_allowed_attempts') &&
            $count >= config_item('deny_access')
        ) {
            // Send an email to
            $this->load->library('email');
            $this->config->load('email');
            $admin_email_config = config_item('admin_email_config');

            $this->email->quick_email(array(
                'subject' => WEBSITE_NAME . ' - Excessive Login Attempts Warning - ' . date("M j, Y"),
                'email_template' => 'email_templates/excessive-login-attempts',
                'from_name' => 'admin_email_config',
                'to' => $admin_email_config['from_email']
            ));

            if (config_item('deny_access') > 0) {
                // Log the IP address in the denied_access database
                $data = array(
                    'IP_address' => $ip_address,
                    'time' => time(),
                    'reason_code' => '1'
                );

                $this->_insert_denial($data);

                // Output white screen of death
                header('HTTP/1.1 403 Forbidden');
                die('<h1>Forbidden</h1><p>You don\'t have permission to access ANYTHING on this server.</p><hr><address>Go fly a kite!</address>');
            }
        }

        // Check to see if this username/email-address has too many login attempts
        if ($string != '') {
            $count = $this->db->where('username_or_email', $string)
                ->count_all_results(config_item('errors_table'));

            if ($count == config_item('max_allowed_attempts')) {
                // Place the username/email-address on hold
                $data = array(
                    'username_or_email' => $string,
                    'time' => time()
                );

                $this->db->set($data)
                    ->insert(config_item('username_or_email_hold_table'));
            }
        }
    }

    // --------------------------------------------------------------

    /**
     * Get all data from the denied access table,
     * or set the field parameter to retrieve a single field.
     */
    public function get_deny_list($field = FALSE)
    {
        if ($field !== FALSE) {
            $this->db->select($field);
        }

        $query = $this->db->from(config_item('denied_access_table'))->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }

    // --------------------------------------------------------------

    /**
     * Validate and process the denial or removal of IP addresses
     * in the denied access table.
     */
    public function process_denial()
    {
        // The form validation class doesn't allow for multiple config files, so we do it the old fashion way
        $this->config->load('form_validation/administration/deny_access');
        $this->validation_rules = config_item('deny_access_rules');

        if ($this->validate()) {
            // If form submission is adding to deny list
            if ($this->input->post('add_denial')) {
                $ip = set_value('ip_address');
                $reason = set_value('reason_code');

                // Make sure that the values we need were posted
                if (!empty($ip) && is_numeric($reason)) {
                    $insert_data = array(
                        'IP_address' => $ip,
                        'reason_code' => $reason,
                        'time' => time()
                    );

                    // Insert the denial
                    $this->_insert_denial($insert_data);

                    // Show confirmation that denial was added
                    $this->load->vars(array('confirm_add_denial' => 1));

                    // Kill set_value() since we won't need it
                    $this->kill_set_value();
                } // Necessary values were not available
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>An <span class="redfield">IP ADDRESS</span> is required.</li>'));
                }
            } // If form submission is removing from deny list
            else if ($this->input->post('remove_selected')) {
                // Get the IPs to remove
                $ips = set_value('ip_removals[]');

                // If there were IPs
                if (!empty($ips)) {
                    // Remove the IPs
                    $this->_remove_denial($ips);

                    // Show confirmation of removal
                    $this->load->vars(array('confirm_removal' => 1));
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
                }
            }
        }
    }

    // --------------------------------------------------------------

    /**
     * Add a record to the denied access table
     */
    protected function _insert_denial($data)
    {
        if ($data['IP_address'] == '0.0.0.0') {
            return FALSE;
        }

        $this->db->set($data)
            ->insert(config_item('denied_access_table'));

        $this->_rebuild_deny_list();
    }

    // --------------------------------------------------------------

    /**
     * Remove a record from the denied access table
     */
    protected function _remove_denial($ips)
    {
        $i = 0;

        foreach ($ips as $ip) {
            if ($i == 0) {
                $this->db->where('IP_address', $ip);
            } else {
                $this->db->or_where('IP_address', $ip);
            }

            $i++;
        }

        $this->db->delete(config_item('denied_access_table'));

        $this->_rebuild_deny_list();
    }

    // --------------------------------------------------------------

    /**
     * Rebuild the deny list in the local Apache configuration file
     */
    private function _rebuild_deny_list()
    {
        // Get all of the IP addresses in the denied access database
        $query_result = $this->get_deny_list('IP_address');

        if ($query_result !== FALSE) {
            // Create the denial list to be inserted into the Apache config file
            $deny_list = "\n" . '<Limit GET POST>' . "\n" . 'order deny,allow';

            foreach ($query_result as $row) {
                $deny_list .= "\n" . 'deny from ' . $row->IP_address;
            }

            $deny_list .= "\n" . '</Limit>' . "\n";
        } else {
            $deny_list = "\n";
        }

        // Get the path to the Apache config file
        $htaccess = config_item('apache_config_file_location');

        $this->load->helper('file');

        // Store the file permissions so we can reset them after writing to the file
        $initial_file_permissions = fileperms($htaccess);

        // Change the file permissions so we can read/write
        chmod($htaccess, 0644);

        // Read in the contents of the Apache config file
        $string = read_file($htaccess);

        $pattern = '/(?<=# BEGIN DENY LIST --)(.|\n)*(?=# END DENY LIST --)/';

        // Within the string, replace the denial list with the new one
        $string = preg_replace($pattern, $deny_list, $string);

        // Write the new file contents
        if (!write_file($htaccess, $string)) {
            $this->fb->log('Could not write to Apache configuration file');
        }

        // Change the file permissions back to what they were before the read/write
        chmod($htaccess, $initial_file_permissions);
    }

    // --------------------------------------------------------------

    /**
     * Remove the user's user_login_time time when they logout
     *
     * @param  int  the user's ID
     */
    public function logout($user_id)
    {
        $data = array(
            'user_login_time' => NULL,
            'user_session_id' => NULL
        );

        $this->db->where('user_id', $user_id)
            ->update(config_item('user_table'), $data);
    }

    // --------------------------------------------------------------

    /**
     * Get all data from the denied access table,
     * or set the field parameter to retrieve a single field.
     */
    public function get_term_list($field = FALSE)
    {
        if ($field !== FALSE) {
            $this->db->select($field);
        }

        $query = $this->db->from(config_item('term_table'))->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }

    // --------------------------------------------------------------

    /**
     * Validate and process the add or removal of term
     * in the denied access table.
     */
    public function process_term()
    {
        // The form validation class doesn't allow for multiple config files, so we do it the old fashion way
        $this->config->load('form_validation/administration/term');
        $this->validation_rules = config_item('term_rules');

        if ($this->validate()) {
            // If form submission is adding to deny list
            if ($this->input->post('add_term')) {
                $season = set_value('term_season');
               // $year = set_value('term_year');

                // Make sure that the values we need were posted
                if (!empty($season)) {
                    $insert_data = array(
                        'term_season' => $season
                 //       'term_year' => $year
                        //    'time' => time()
                    );

                    // Insert the denial
                    $this->_insert_term($insert_data);

                    // Show confirmation that denial was added
                    $this->load->vars(array('confirm_add_term' => 1));

                    // Kill set_value() since we won't need it
                    $this->kill_set_value();
                } // Necessary values were not available
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>An <span class="redfield">IP ADDRESS</span> is required.</li>'));
                }
            } // If form submission is removing from deny list
            else if ($this->input->post('remove_selected')) {
                // Get the IPs to remove
                $ips = set_value('ip_removals[]');

                // If there were IPs
                if (!empty($ips)) {
                    // Remove the IPs
                    $this->_remove_term($ips);

                    // Show confirmation of removal
                    $this->load->vars(array('confirm_removal' => 1));
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
                }
            }
        }
    }

    // --------------------------------------------------------------

    /**
     * Add a record to the denied access table
     */
    protected function _insert_term($data)
    {
        //if ($data['IP_address'] == '0.0.0.0') {
        //    return FALSE;
        //}

        $this->db->set($data)
            ->insert(config_item('term_table'));

        // $this->_rebuild_deny_list();
    }

    // --------------------------------------------------------------

    /**
     * Remove a record from the denied access table
     */
    protected function _remove_term($ips)
    {
        $i = 0;

        foreach ($ips as $season) {
            if ($i == 0) {
                $this->db->where('term_season', $season);
            } else {
                $this->db->or_where('term_season', $season);
            }

            $i++;
        }

        $this->db->delete(config_item('term_table'));

        // $this->_rebuild_deny_list();
    }



    // --------------------------------------------------------------

    /**
     * Validate and process the add or removal of course
     * in the denied access table.
     */
    public function get_course_list($field = FALSE)
    {
        if ($field !== FALSE) {
            $this->db->select($field);
        }

        $query = $this->db->from(config_item('course_table'))->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }

    public function process_course()
    {
        // The form validation class doesn't allow for multiple config files, so we do it the old fashion way
        $this->config->load('form_validation/administration/course');
        $this->validation_rules = config_item('course_rules');

        if ($this->validate()) {
            // If form submission is adding to deny list
            if ($this->input->post('add_course')) {
                $courseName = set_value('courseName');
                $courseDesc = set_value('courseDesc');
               // $dept = set_value('DeptID');
                $credit = set_value('credit');
                $major = $_POST['major'];
                $trueMajor = $this->getTrueVal('major', $major, config_item('major_table'));

                // Make sure that the values we need were posted
                if (!empty($courseName)) {
                    $insert_data = array(
                        'courseName' => $courseName,
                        'courseDesc' => $courseDesc,
                        'major' => $trueMajor->major,
                        'credit' => $credit
                        // 'time' => time()
                    );

                    // Insert the denial
                    $this->_insert_course($insert_data);

                    // Show confirmation that denial was added
                    $this->load->vars(array('confirm_add_course' => 1));

                    // Kill set_value() since we won't need it
                    $this->kill_set_value();
                } // Necessary values were not available
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>An <span class="redfield">IP ADDRESS</span> is required.</li>'));
                }
            } // If form submission is removing from deny list
            else if ($this->input->post('remove_selected')) {
                // Get the IPs to remove
                $ips = set_value('ip_removals[]');

                // If there were IPs
                if (!empty($ips)) {
                    // Remove the IPs
                    $this->_remove_course($ips);

                    // Show confirmation of removal
                    $this->load->vars(array('confirm_removal' => 1));
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
                }
            }
        }
    }

    // --------------------------------------------------------------

    /**
     * Add a record to the denied access table
     */
    protected function _insert_course($data)
    {
        //if ($data['IP_address'] == '0.0.0.0') {
        //    return FALSE;
        //}

        $this->db->set($data)
            ->insert(config_item('course_table'));

        // $this->_rebuild_deny_list();
    }

    // --------------------------------------------------------------

    /**
     * Remove a record from the denied access table
     */
    protected function _remove_course($ips)
    {
        $i = 0;

        foreach ($ips as $cname) {
            if ($i == 0) {
                $this->db->where('ID', $cname);
            } else {
                $this->db->or_where('ID', $cname);
            }

            $i++;
        }

        $this->db->delete(config_item('course_table'));

        // $this->_rebuild_deny_list();
    }

    /**
     * Validate and process the add or removal of course
     * in the denied access table.
     */
    public function get_section_list($field = FALSE)
    {
        if ($field !== FALSE) {
            $this->db->select($field);
        }

        $query = $this->db->from(config_item('section_table'))->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }


        return FALSE;
    }

    public function getTrueVal($field, $fieldVal, $table)
    {
        $this->db->select($field);
        $this->db->where('ID', $fieldVal);
        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function getTrueVall($field, $fieldVal, $table)
    {
        if ($fieldVal == 0) {


            return 0;
        } else {
            $this->db->select($field);
            $this->db->where('ID', $fieldVal);
            $query = $this->db->get($table);

            if ($query->num_rows() > 0) {

                return $query->row();
            }
        }
    }

    public function getInstructorVal($field, $fieldVal, $table)
    {
        if ($fieldVal == 0) {

            return 0;
        } else {
            $this->db->select($field);
            $this->db->where('user_id', $fieldVal);
            $query = $this->db->get($table);

            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }
    }

    public function createSectionID($trueTermSeason, $trueTermYear, $trueCourseName)
    {
        $this->db->select_max('section');
        $this->db->where('term', $trueTermSeason);
        $this->db->where('year', $trueTermYear);
        $this->db->where('courseName', $trueCourseName);
        $query = $this->db->get(config_item('section_table'));
        if (!empty($query)) {
            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }
        echo "<script>console.log('god i had this shit');</script>";
        return false;
    }

    public function get_instructor_list($table)
    {
        $query = $this->db->from($table)->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }

    public function get_ips($ips)
    {
        $this->db->where('ID', $ips[0]);
        $query = $this->db->get(config_item('section_table'));
        if (!empty($query)) {
            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }
    }

    public function process_section()
    {
        // The form validation class doesn't allow for multiple config files, so we do it the old fashion way
        $this->config->load('form_validation/administration/section');
        $this->validation_rules = config_item('section_rules');

        if ($this->validate()) {
            // If form submission is adding to deny list
            if ($this->input->post('add_section')) {
                $termYear = $_POST['year'];
                //echo $termYear;

                $trueTermYear = $this->getTrueVal('year', $termYear, config_item('year_table'));
                $termSeason = $_POST['term'];
                $trueTermSeason = $this->getTrueVal('term_season', $termSeason, config_item('term_table'));
                $courseName = $_POST['course_name'];
                $trueCourseName = $this->getTrueVal('courseName', $courseName, config_item('course_table'));
                $timeslot = $_POST['timeslot'];
                $trueTimeslot = $this->getTrueVal('timeslot', $timeslot, config_item('timeslot_table'));
                $building = $_POST['building'];
                $trueBuilding = $this->getTrueVal('building', $building, config_item('building_table'));
                $room = $_POST['room'];
                $trueRoom = $this->getTrueVal('room', $room, config_item('room_table'));
                $instructor = $_POST['instructor_name'];
                $trueInstructor = $this->getInstructorVal('first_name, last_name', $instructor, config_item('manager_profiles_table'));

                // Make sure that the values we need were posted
                echo "<script>console.log(".print_r($trueTermSeason).")</script>";
                echo "<script>console.log(".$_POST['term'].")</script>";
                //echo "<script>console.log(".$trueTermSeason->term_season.")</script>";
               // exit();
                if (!empty($termYear)) {
                    $query = $this->createSectionID($trueTermSeason->term_season, $trueTermYear->year, $trueCourseName->courseName);
                    //$query = $this->createSectionID($_POST['term'], $_POST['year'], $_POST['course_name']);
                    if (empty($query)) {
                        $i = '1';
                    } else {
                        $i = $query->section;
                        $i++;
                    }

                    $getDept = $this->get_course_list();
                    $dept='';
                    foreach($getDept as $row){
                        if($row->courseName == $trueCourseName->courseName){
                            $dept = $row->major;
                        }
                    }




                    $insert_data = array(
                        'year' => $trueTermYear->year,
                        'term' => $trueTermSeason->term_season,
                        'courseName' => $trueCourseName->courseName,
                        'timeslot' => $trueTimeslot->timeslot,
                        'building' => $trueBuilding->building,
                        'room' => $trueRoom->room,
                        'section' => $i,
                        'teacher' => $trueInstructor->first_name . " " . $trueInstructor->last_name,
                        'major' => $dept

                       /* 'year' => $_POST['year'],
                        'term' => $_POST['term'],
                        'courseName' => $_POST['course_name'],
                        'timeslot' => $_POST['timeslot'],
                        'building' => $_POST['building'],
                        'room' => $_POST['room'],
                        'sectionID' => $i,
                        'teacher' => $trueInstructor->first_name . " " . $trueInstructor->last_name
*/
                        // 'time' => time()
                    );


                    // Insert the denial
                    $this->_insert_section($insert_data);

                    // Show confirmation that denial was added
                    $this->load->vars(array('confirm_add_section' => 1));

                    // Kill set_value() since we won't need it
                    $this->kill_set_value();
                } // Necessary values were not available
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>An <span class="redfield">IP ADDRESS</span> is required.</li>'));
                }
            } // If form submission is removing from deny list
            else if ($this->input->post('remove_selected')) {
                // Get the IPs to remove
                $ips = set_value('ip_removals[]');

                // If there were IPs
                if (!empty($ips)) {
                    // Remove the IPs
                    $this->_remove_section($ips);

                    // Show confirmation of removal
                    $this->load->vars(array('confirm_removal' => 1));
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
                }
            } else if ($this->input->post('modify')) {
                // Get the IPs to remove
                $ips = set_value('ip_removals[]');


                // If there were IPs
                if (!empty($ips)) {
                    $getips = $this->get_ips($ips);
                    // Remove the IPs
                    //  $this->_remove_section($ips);
                    echo "im here";
                    return $getips;

                    // Show confirmation of removal
                    $this->load->vars(array('confirm_removal' => 1));
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
                }
            }
        } else {
            $this->load->vars(array('validation_errors' => '<li><span class="redfield">check validation files </span></li>'));
        }
    }

    // --------------------------------------------------------------

    public function process_modify()
    {
        // The form validation class doesn't allow for multiple config files, so we do it the old fashion way
        $this->config->load('form_validation/administration/section');
        $this->validation_rules = config_item('section_rules');

        if ($this->validate()) {
            // If form submission is adding to deny list
            if ($this->input->post('add_section')) {
                $termYear = set_value('year');


                // echo $shit;
                $trueTermYear = $this->getTrueVall('term_year', $termYear, config_item('term_table'));
                $termSeason = set_value('term');
                $trueTermSeason = $this->getTrueVall('term_season', $termSeason, config_item('term_table'));
                $courseName = set_value('course_name');
                $trueCourseName = $this->getTrueVall('courseName', $courseName, config_item('course_table'));
                $timeslot = set_value('timeslot');
                $trueTimeslot = $this->getTrueVall('timeslot', $timeslot, config_item('timeslot_table'));
                $building = set_value('building');
                $trueBuilding = $this->getTrueVall('building', $building, config_item('building_table'));
                $room = set_value('room');
                $trueRoom = $this->getTrueVall('room', $room, config_item('room_table'));
                $instructor = set_value('instructor_name');
                $trueInstructor = $this->getInstructorVal('first_name, last_name', $instructor, config_item('manager_profiles_table'));

                // Make sure that the values we need were posted
                if (isset($termYear)) {

                    $insert_data = array();
                    if ($termYear != '0'){
                        echo "success";
                        $insert_data = array('year' => $trueTermYear->term_year);
                    }
                    if ($termYear > 0)
                        $insert_data = array('term' => $trueTermSeason->term_season);
                    if ($courseName > 0)
                        $insert_data = array('courseName' => $trueCourseName->courseName);
                    if ($timeslot > 0)
                        $insert_data = array('timeslot' => $trueTimeslot->timeslot);
                    if ($building > 0)
                        $insert_data = array('building' => $trueBuilding->building);
                    if ($room > 0)
                        $insert_data = array('room' => $trueRoom->room);
                    if ($instructor > 0)
                        $insert_data = array('teacher' => $trueInstructor->first_name . " " . $trueInstructor->last_name);

                    // Insert the denial
                    $hiddenID = $this->input->post('id');
                    $this->_update_section($insert_data, $hiddenID);

                    // Show confirmation that denial was added
                    $this->load->vars(array('confirm_add_section' => 1));

                    // Kill set_value() since we won't need it
                    $this->kill_set_value();
                } // Necessary values were not available
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>An <span class="redfield">IP ADDRESS</span> is required.</li>'));
                }
            } // If form submission is removing from deny list
            else if ($this->input->post('remove_selected')) {
                // Get the IPs to remove
                $ips = set_value('ip_removals[]');

                // If there were IPs
                if (!empty($ips)) {
                    // Remove the IPs
                    $this->_remove_section($ips);

                    // Show confirmation of removal
                    $this->load->vars(array('confirm_removal' => 1));
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
                }
            } else if ($this->input->post('modify')) {
                // Get the IPs to remove
                $ips = set_value('ip_removals[]');


                // If there were IPs
                if (!empty($ips)) {
                    $getips = $this->get_ips($ips);
                    // Remove the IPs
                    //  $this->_remove_section($ips);
                    echo "im here";
                    return $getips;

                    // Show confirmation of removal
                    $this->load->vars(array('confirm_removal' => 1));
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
                }
            }
        } else {
            $this->load->vars(array('validation_errors' => '<li><span class="redfield">check validation files </span></li>'));
        }
    }

    /**
     * Add a record to the denied access table
     */
    protected function _insert_section($data)
    {
        //if ($data['IP_address'] == '0.0.0.0') {
        //    return FALSE;
        //}

        $this->db->set($data)
            ->insert(config_item('section_table'));

        // $this->_rebuild_deny_list();
    }

    // --------------------------------------------------------------

    /**
     * Remove a record from the denied access table
     */
    protected function _remove_section($ips)
    {
        $i = 0;

        foreach ($ips as $cname) {
            if ($i == 0) {
                $this->db->where('ID', $cname);
            } else {
                $this->db->or_where('ID', $cname);
            }

            $i++;
        }

        $this->db->delete(config_item('section_table'));

        // $this->_rebuild_deny_list();
    }

    protected function _update_section($data, $id)
    {
        $this->db->where('ID', $id);
        $this->db->update(config_item('section_table'), $data);

    }

    public function get_course_name()
    {
        $query = $this->db->distinct()
            ->select('courseName')
            ->get(config_item('course_table'));

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }

    public function get_sect_list()
    {
        $query = $this->db->distinct()
            ->select('courseName', 'sectionID', 'termID')
            ->get(config_item('section_table'));

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }

    public function get_stuff_list($stuff_table)
    {
        $query = $this->db->from($stuff_table)->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }

    public function process_stuff($stuff_table, $table_field)
    {
        // The form validation class doesn't allow for multiple config files, so we do it the old fashion way
        $this->config->load('form_validation/administration/stuff');
        $this->validation_rules = config_item('stuff_rules');

        if ($this->validate()) {
            // If form submission is adding to deny list
            if ($this->input->post('add_stuff')) {
                $stuffName = set_value('stuffName');
                echo "<script>console.log($stuffName);</script>";
                //echo "<script>console.log('Testing console');</script>";


                // Make sure that the values we need were posted
                if (!empty($stuffName)) {
                    $insert_data = array(
                        $table_field => $stuffName,

                        // 'time' => time()

                    );
                    // Insert the denial

                    $this->_insert_stuff($insert_data, $stuff_table);


                    // Show confirmation that denial was added
                    $this->load->vars(array('confirm_add_stuff' => 1));

                    // Kill set_value() since we won't need it
                    $this->kill_set_value();
                } // Necessary values were not available
                else {

                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>An <span class="redfield">IP ADDRESS</span> is required.</li>'));
                }
            } // If form submission is removing from deny list
            else if ($this->input->post('remove_selected')) {
                // Get the IPs to remove
                $ips = set_value('ip_removals[]');

                // If there were IPs
                if (!empty($ips)) {
                    // Remove the IPs
                    $this->_remove_stuff($ips, $stuff_table);

                    // Show confirmation of removal
                    $this->load->vars(array('confirm_removal' => 1));
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
                }
            }
        }
    }

    // --------------------------------------------------------------

    /**
     * Add a record to the denied access table
     */
    protected function _insert_stuff($data, $stuff_table)
    {
        //if ($data['IP_address'] == '0.0.0.0') {
        //    return FALSE;
        //}

        $this->db->set($data)
            ->insert($stuff_table);

        // $this->_rebuild_deny_list();
    }

    // --------------------------------------------------------------

    /**
     * Remove a record from the denied access table
     */
    protected function _remove_stuff($ips, $stuff_table)
    {
        $i = 0;

        foreach ($ips as $cname) {
            if ($i == 0) {
                $this->db->where('ID', $cname);
            } else {
                $this->db->or_where('ID', $cname);
            }

            $i++;
        }

        $this->db->delete($stuff_table);

        // $this->_rebuild_deny_list();
    }

    public function process_modifyClassScheduling($stuff_table, $table_field)
    {
        // The form validation class doesn't allow for multiple config files, so we do it the old fashion way
        // $this->config->load('form_validation/administration/stuff');
        // $this->validation_rules = config_item('stuff_rules');

        //  if ($this->validate()) {
        // If form submission is adding to deny list
        if ($this->input->post('add_stuff')) {
            $termYear = set_value('year');
            $trueTermYear = $this->getTrueVal('term_year', $termYear, config_item('term_table'));
            $termSeason = set_value('term');
            $trueTermSeason = $this->getTrueVal('term_season', $termSeason, config_item('term_table'));
            $courseName = set_value('course_name');
            $trueCourseName = $this->getTrueVal('courseName', $courseName, config_item('section_table'));
            $sectionID = set_value('section_id');
            $trueSectionID = $this->getTrueVal('sectionID', $sectionID, config_item('section_table'));
            $instructor = set_value('instructor_name');
            $trueInstructor = $this->getInstructorVal('first_name, last_name', $instructor, config_item('manager_profiles_table'));


            //$stuffName = set_value('stuffName');
            //           echo "<script>console.log($stuffName);</script>";
            //echo "<script>console.log('Testing console');</script>";


            // Make sure that the values we need were posted
            if (!empty($courseName)) {
                $insert_data = array(
                    $table_field => $trueInstructor,

                    // 'time' => time()

                );
                // Insert the denial

                $this->_insert_teacher($insert_data, $stuff_table, $trueTermYear, $trueTermSeason, $trueCourseName, $trueSectionID);


                // Show confirmation that denial was added
                $this->load->vars(array('confirm_add_stuff' => 1));

                // Kill set_value() since we won't need it
                $this->kill_set_value();
            } // Necessary values were not available
            else {

                // Show error message
                $this->load->vars(array('validation_errors' => '<li>An <span class="redfield">IP ADDRESS</span> is required.</li>'));
            }
        } // If form submission is removing from deny list
        else if ($this->input->post('remove_selected')) {
            // Get the IPs to remove
            $ips = set_value('ip_removals[]');

            // If there were IPs
            if (!empty($ips)) {
                // Remove the IPs
                $this->_remove_stuff($ips, $stuff_table);

                // Show confirmation of removal
                $this->load->vars(array('confirm_removal' => 1));
            } // If there were no IPs posted
            else {
                // Show error message
                $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
            }
        }
        // }
    }

    protected function _insert_teacher($data, $stuff_table, $trueTermSeason, $trueTermYear, $trueCourseName, $trueSectionID)
    {
        //if ($data['IP_address'] == '0.0.0.0') {
        //    return FALSE;
        //}
        $this->db->where('term', $trueTermSeason);
        $this->db->where('year', $trueTermYear);
        $this->db->where('courseName', $trueCourseName);
        $this->db->where('sectionID', $trueSectionID);
        $this->db->set($data)
            ->insert($stuff_table);

        // $this->_rebuild_deny_list();
    }

    /**
     * Method to query database for vehicle types.
     *
     * If you were using "keys" in the config file,
     * you might use a query like this:
     *
     * $query = $this->db->distinct()
     * ->select('type_id,type')
     * ->get('auto_types');
     */
    public function get_modify_class_year()
    {
        $query = $this->db->distinct()
            ->select('year')
            ->get(config_item('section_table'));

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }

    // --------------------------------------------------------------

    /**
     * Method to query database for vehicle makes.
     *
     * If you were using "keys" in the config file,
     * you might use a query like this:
     *
     * $this->db->distinct();
     * $this->db->select('make_id,make');
     * $this->db->where('type_id_fk',$type );
     */
    public function get_modify_class_term_in_year($years)
    {
        //$years = $this->input->post('year');

        $this->db->distinct();

        $this->db->select('term');

        $this->db->where('year', $years);

        $query = $this->db->get(config_item('section_table'));

        if ($query->num_rows() > 0) {
            //$query->result_array();
            // print_r($query);
            return $query->result_array();
        }

        return FALSE;
    }

    // --------------------------------------------------------------

    /**
     * Method to query database for vehicle models.
     *
     * If you were using "keys in the config file",
     * you might use a query like this:
     *
     * $this->db->select('model_id,model');
     * $this->db->where('type_id_fk',$type);
     * $this->db->where('make_id_fk',$make);
     */
    public function get_modify_class_course_in_term($years, $terms)
    {
        // $years = $this->input->post('year');
        //  $terms = $this->input->post('term');

        $this->db->distinct();
        $this->db->select('courseName');

        $this->db->where('year', $years);
        $this->db->where('term', $terms);

        $query = $this->db->get(config_item('section_table'));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return FALSE;
    }

    public function get_modify_class_section_in_course($years, $terms, $course)
    {
        // $years = $this->input->post('year');
        // $terms = $this->input->post('term');
        // $courseName = $this->input->post('courseName');

        $this->db->distinct();
        $this->db->select('section');

        $this->db->where('year', $years);
        $this->db->where('term', $terms);
        $this->db->where('courseName', $course);

        $query = $this->db->get(config_item('section_table'));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return FALSE;
    }

    public function get_modify_class_otherInfo_in_section($table)
    {
        $years = $this->input->post('year');
        $terms = $this->input->post('term');
        $courseNames = $this->post('courseName');
        $sectionIDs = $this->post('sectionID');

        $this->db->distinct();
        $this->db->select('teacher, timeslot, room, building');

        $this->db->where('year', $years);
        $this->db->where('term', $terms);
        $this->db->where('courseName', $courseNames);
        $this->db->where('sectionID', $sectionIDs);

        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return FALSE;
    }

    public function get_teacher_schedule($table)
    {

      //  echo config_item('auth_first_name').' '.config_item('auth_last_name');
        $teacherstuff = config_item('auth_first_name').' '.config_item('auth_last_name');
        $this->db->where('teacher',$teacherstuff);
        $query = $this->db->get($table);


        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return FALSE;
    }
    public function process_student_schedule($stuff_table){
      //  echo config_item('auth_user_id');
        $studentid = config_item('auth_user_id');
      //  $data = array(
      //      'user_id' => $studentid
     //   );
      //  $this->db->insert($table, $data);


            if ($this->input->post('remove_selected')) {
                // Get the IPs to remove
                $insert_data = $_POST['ip_removals'];//('ip_removals[]');

              //  echo "<script>console.log(".print_r($insert_data).");</script>";//;print_r($insert_data);
                    foreach ($insert_data as $cname){
                        $this->db->select();
                        $this->db->where('user_id', $studentid);
                        $this->db->where('cID', $cname);
                        $query = $this->db->get($stuff_table);
                      //  print_r($query);
                        if ($query->num_rows() > 0) {
                            $this->load->vars(array('validation_errors' => '<li>You have already registered for this course. <span class="redfield">CLASS NOT ADDED</span></li>'));

                        }
                        else {
                            $ips= array(
                            'user_id' => $studentid,
                            'cID' => $cname
                            );
                            $diditwork =  $this->_set_student_schedule($ips, $stuff_table);
                        }

                    }
               // print_r($ips);
          //      exit();


                // If there were IPs
                if (!empty($ips)) {
                    // Show confirmation of removal
                    if (!empty($diditwork)) {
                    $this->load->vars(array('confirm_removal' => 1));
                    }
                    else{

                    }
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>You have already registered for this course. <span class="redfield">CLASS NOT ADDED</span></li>'));
                }
            }
        else if($this->input->post('drop_selected')){
            $ips = $_POST['ip_removals'];
            $this->_remove_shit($ips, $stuff_table);
        }
            // }
        return 1;
        }
    function checkHoldStatus(){
        $this->db->select('holdStatus');
        $this->db->where('user_id', config_item('auth_user_id'));
        //$this->db->where('holdStatus', $cname);
        $query = $this->db->get(config_item('customer_profiles_table'));
        if($query->row()->holdStatus == 1){
            $this->load->vars(array('validation_errors' => '<li>You have a Financial Hold. <span class="redfield">YOU CANNOT REGISTER</span></li>'));
        }
        elseif($query->row()->holdStatus == 2){
            $this->load->vars(array('validation_errors' => '<li>You have a GPA Hold. <span class="redfield">YOU CANNOT REGISTER</span></li>'));
        }
        elseif($query->row()->holdStatus == 3){
            $this->load->vars(array('validation_errors' => '<li>You have a Health Hold. <span class="redfield">YOU CANNOT REGISTER</span></li>'));
        }
        return $query->row();
    }

    protected function _set_student_schedule($ips, $stuff_table)
    {
        $this->db->set($ips)
            ->insert($stuff_table);
    }



    public function get_student_schedule($table){


        $this->db->select('ID,term,year,courseName,timeslot,building,room,section,teacher,major');
        $this->db->from('section');
        $this->db->where( $table.'.user_id',config_item('auth_user_id'));

        $this->db->join($table, 'student_courses.cID = section.ID ');
        $query = $this->db->get();
        $stuff=$query->result_array();
        $count = 0;
        $year = '';
        $term = '';
        $delID='';
       // print_r($stuff);
        foreach($stuff as $row){
            foreach($stuff as $otherrow){
                if(($otherrow['year'] == $row['year']) && ($otherrow['term'] == $row['term']) &&( $otherrow['ID'] != $row['ID'])) {
                    $count++;
                    $year = $otherrow['year'];
                    $term = $otherrow['term'];
                    $delID = $otherrow['ID'];

                }
            }
            echo $count;
        }

        if($count < 18){
        if (($query->num_rows() > 0)) {
            return $query->result_array();
        }
        }
        else{
        //if($query->num_rows() > 4){
            $this->load->vars(array('validation_errors' => '<li>You have already registered for 4 courses. Please contact the administrator. <span class="redfield">CLASS NOT ADDED</span></li>'));

           $this->db->limit(1);
            $this->db->select('student_courses.user_id, ID,term,year,courseName,major,timeslot,building,room,section,teacher');
            $this->db->from('section');
            $this->db->where( $table.'.user_id',config_item('auth_user_id'));
            $this->db->where('section.year',$year);
            $this->db->where('section.term',$term);
            $this->db->where('section.ID',$delID);
            $this->db->join($table, 'student_courses.cID = section.ID ');
            //$this->db->delete($table);
            $query = $this->db->get();
         //  $stuff=$query->row();
         //   $this->db->insert('tempclasses', $stuff);

           // this->db->where()*/
            $data = array('user_id'=>config_item('auth_user_id'), 'cID'=>$delID);
            $this->db->delete($table,$data);
            return $query->result_array();


        }
    }
    protected function _remove_shit($ips, $stuff_table)
    {
        $i = 0;

        foreach ($ips as $cname) {
            if ($i == 0) {
                $this->db->where('cID', $cname);
            } else {
                $this->db->or_where('cID', $cname);
            }

            $i++;
        }

        $this->db->delete($stuff_table);

        // $this->_rebuild_deny_list();
    }

    public function process_student_register()
    {
        // The form validation class doesn't allow for multiple config files, so we do it the old fashion way
        $this->config->load('form_validation/administration/term');
        $this->validation_rules = config_item('term_rules');

        if ($this->validate()) {
            // If form submission is adding to deny list
            if ($this->input->post('add_term')) {
                $studentuser = $_POST['student_user_name'];
                $classid = $_POST['class_id'];

                $this->db->select('user_id');
                $this->db->from(config_item('user_table'));
                $this->db->where('user_name', $studentuser);
                $query = $this->db->get();
                $user_id = $query->row();
             //   print_r($user_id);
                //exit();


                // Make sure that the values we need were posted
                if (!empty($user_id)) {
                    $insert_data = array(
                        'user_id' => $user_id->user_id,
                        'cID' => $classid
                        //    'time' => time()
                    );

                    // Insert the denial
                    $this->_insert_stuff($insert_data, config_item('student_courses_table'));

                    // Show confirmation that denial was added
                    $this->load->vars(array('confirm_add_term' => 1));

                    // Kill set_value() since we won't need it
                    $this->kill_set_value();
                } // Necessary values were not available
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>An <span class="redfield">IP ADDRESS</span> is required.</li>'));
                }
            } // If form submission is removing from deny list
            else if ($this->input->post('remove_selected')) {
                // Get the IPs to remove
                $ips = set_value('ip_removals[]');

                // If there were IPs
                if (!empty($ips)) {
                    // Remove the IPs
                    $this->_remove_term($ips);

                    // Show confirmation of removal
                    $this->load->vars(array('confirm_removal' => 1));
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
                }
            }
        }
    }
    public function process_student_hold()
    {
        // The form validation class doesn't allow for multiple config files, so we do it the old fashion way
        $this->config->load('form_validation/administration/term');
        $this->validation_rules = config_item('term_rules');

        if ($this->validate()) {
            // If form submission is adding to deny list
            if ($this->input->post('add_term')) {
                $studentuser = $_POST['student_user_name'];
                $statustype = $_POST['hold_status'];

                $this->db->select('user_id');
                $this->db->from(config_item('user_table'));
                $this->db->where('user_name', $studentuser);
                $query = $this->db->get();
                $user_id = $query->row();
                //   print_r($user_id);
                //exit();


                // Make sure that the values we need were posted
                if (!empty($user_id)) {
                    $insert_data = array(
                        //'user_id' => $user_id->user_id,
                        'holdStatus' => $statustype
                        //    'time' => time()
                    );

                    // Insert the denial
                    $this->_insert_stuff_hold($insert_data, config_item('customer_profiles_table'), $user_id->user_id);

                    // Show confirmation that denial was added
                    $this->load->vars(array('confirm_add_term' => 1));

                    // Kill set_value() since we won't need it
                    $this->kill_set_value();
                } // Necessary values were not available
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>An <span class="redfield">IP ADDRESS</span> is required.</li>'));
                }
            } // If form submission is removing from deny list
            else if ($this->input->post('remove_selected')) {
                // Get the IPs to remove
                $ips = set_value('ip_removals[]');

                // If there were IPs
                if (!empty($ips)) {
                    // Remove the IPs
                    $this->_remove_term($ips);

                    // Show confirmation of removal
                    $this->load->vars(array('confirm_removal' => 1));
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
                }
            }
        }
    }

    protected function _insert_stuff_hold($data, $stuff_table, $id)
    {
        //if ($data['IP_address'] == '0.0.0.0') {
        //    return FALSE;
        //}
        $this->db->where('user_id', $id);
        $this->db->update($stuff_table, $data);

        // $this->_rebuild_deny_list();
    }

    public function get_students_in_class(){
      $sql = "SELECT DISTINCT users.user_id, users.user_name, users.user_email, customer_profiles.first_name, customer_profiles.last_name, student_courses.cID, student_courses.grade
FROM `users`
JOIN `customer_profiles` ON `customer_profiles`.user_id = `users`.user_id
JOIN `student_courses` ON `student_courses`.user_id = `customer_profiles`.user_id
where cID = ?";
        $query=$this->db->query($sql, $_GET['n']);
        return $query->result_array();

    }
    public function process_grading($stuff_table){
        //  echo config_item('auth_user_id');
       // $studentid = config_item('auth_user_id');
        //  $data = array(
        //      'user_id' => $studentid
        //   );
        //  $this->db->insert($table, $data);


        if ($this->input->post('remove_selected')) {
            // Get the IPs to remove
            $insert_id = $_POST['ip_removals'];//('ip_removals[]');
            $insert_grade = $_POST['grades'];
            if(count($insert_id) != count($insert_grade)){
                $this->load->vars(array('validation_errors' => '<li>Make sure you confirm the grade by selecting the corresponding checkbox></li>'));
                return 0;
            }

         //   $course = $_POST['course'];
            $i=0;
           // print_r($insert_grade);
          //  print_r($insert_id);
         //   print_r( $_POST['course']);
          //  exit();


            //  echo "<script>console.log(".print_r($insert_data).");</script>";//;print_r($insert_data);
            foreach ($insert_id as $cname){

                //  print_r($query);


                    $ips= array(

                        'grade' => $insert_grade[$i]
                    );
                    $diditwork =  $this->_set_student_grade($cname, $ips, config_item('student_courses_table'));


                $i++;
            }
            // print_r($ips);
            //      exit();


            // If there were IPs
            if (!empty($ips)) {
                // Show confirmation of removal
                if (!empty($diditwork)) {
                    $this->load->vars(array('confirm_removal' => 1));
                }
                else{

                }
            } // If there were no IPs posted
            else {
                // Show error message
                $this->load->vars(array('validation_errors' => '<li>You have already registered for this course. <span class="redfield">CLASS NOT ADDED</span></li>'));
            }
        }
        else if($this->input->post('drop_selected')){
            $ips = $_POST['ip_removals'];
            $this->_remove_shit($ips, $stuff_table);
        }
        // }
        return 1;
    }
    protected function _set_student_grade($id, $ips, $stuff_table)
    {
        $this->db->query("SET FOREIGN_KEY_CHECKS=0");
        $this->db->where('user_id',$id);
        $this->db->where('cID', $_POST['course']);
        $this->db->update($stuff_table, $ips);
        $this->db->query("SET FOREIGN_KEY_CHECKS=1");
    }

    public function process_assignmajor()
    {
        // The form validation class doesn't allow for multiple config files, so we do it the old fashion way
      //  $this->config->load('form_validation/administration/course');
     //   $this->validation_rules = config_item('course_rules');

       // if ($this->validate()) {
            // If form submission is adding to deny list
            if ($this->input->post('add_term')) {

                    $studentuser = $_POST['student_user_name'];

                $major = $_POST['major'];
                $trueMajor = $this->getTrueVal('major', $major, config_item('major_table'));

                $this->db->select('user_id');
                $this->db->from(config_item('user_table'));
                $this->db->where('user_name', $studentuser);
                $query = $this->db->get();
                $user_id = $query->row();

                // Make sure that the values we need were posted
                if (!empty($user_id)) {
                    $insert_data = array(
                        'major' => $trueMajor->major

                        // 'time' => time()
                    );

                    // Insert the denial
                    $this->_insert_stuff_hold($insert_data, config_item('customer_profiles_table'), $user_id->user_id);

                    // Show confirmation that denial was added
                    $this->load->vars(array('confirm_add_course' => 1));

                    // Kill set_value() since we won't need it
              //      $this->kill_set_value();
                } // Necessary values were not available
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>An <span class="redfield">IP ADDRESS</span> is required.</li>'));
                }
            } // If form submission is removing from deny list
            else if ($this->input->post('remove_selected')) {
                // Get the IPs to remove
                $ips = set_value('ip_removals[]');

                // If there were IPs
                if (!empty($ips)) {
                    // Remove the IPs
                    $this->_remove_course($ips);

                    // Show confirmation of removal
                    $this->load->vars(array('confirm_removal' => 1));
                } // If there were no IPs posted
                else {
                    // Show error message
                    $this->load->vars(array('validation_errors' => '<li>At least one <span class="redfield">IP ADDRESS</span> must be selected for removal.</li>'));
                }
            }
       // }
    }

//589798833
}

/* End of file auth_model.php */
/* Location: /application/models/auth_model.php */