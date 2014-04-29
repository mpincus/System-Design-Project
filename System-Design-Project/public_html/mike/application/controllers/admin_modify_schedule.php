<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Community Auth - Auto Populate Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
class Auto_populate extends MY_Controller
{

    private $recursion = 0;
    private $dropdown_data;
    private $selections;
    private $options_output;

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Load common resources
        $this->load->model('auth_model', 'autopop');
    }

    // --------------------------------------------------------------

    /**
     * Display the auto population form
     */
    public function index()
    {
        // Make sure admin is logged in
        if ($this->require_role('admin')) {
            if (config_item('deny_access') > 0) { //needed
                // If POST, do delete or addition of IP
                $view_data['year'] = $this->auth_model->get_modify_class_year(config_item('section_table'));

                if ($this->tokens->match) {
                    if ($this->input->post('year')) {
                        $view_data['term'] = $this->auth_model->get_modify_class_term_in_year(config_item('section_table'));

                        if ($this->input->post('term')) {
                            $view_data['course'] = $this->auth_model->get_modify_class_course_in_term(config_item('section_table'));

                            if ($this->input->post('course')) {
                                $view_data['section'] = $this->auth_model->get_modify_class_section_in_course(config_item('section_table'));

                                if ($this->input->post('section')) {
                                    $view_data['other'] = $this->auth_model->get_modify_class_otherInfo_in_section(config_item('section_table'));
                                }
                            }
                        }
                    }
                }

         /*       if ($this->tokens->match) {
                    $this->auth_model->process_modifyClassScheduling(config_item('section_table'),'teacher');
                }

                // Get the current deny list
                $view_data['stuff_list'] = $this->auth_model->get_stuff_list(config_item('manager_profiles_table'));
            }
*/
            }
            $data = array(
                'content' => $this->load->view('administration/modifyClassSchedule', (isset($view_data)) ? $view_data : '', TRUE),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js',
                    'js/administration/modifyClassSchedule.js'
                )
            );

            $this->load->view($this->template, $data);
        }
    }

    // --------------------------------------------------------------

    /**
     * This is the method that is called by the ajax request
     */
    public function process_request($type)
    {
        if ($this->require_min_level(1)) {
            if ($this->input->is_ajax_request() && $this->tokens->match) {
                // Load resources
                $this->config->load('auto_populate/' . $type);

                // Get config
                $config = config_item($type);

                // Count the levels
                $levels_count = count($config['levels']);

                // Start with some empty arrays
                for ($x = 2; $x <= $levels_count; $x++) {
                    $options_data[$x] = array();
                }

                if ($this->input->post($config['levels'][0])) {
                    $this->_build_dropdown_data($config);

                    $this->dropdown_data = array_merge($options_data, $this->dropdown_data);

                    $this->recursion = 0;

                    $this->_build_output($config);
                } // If for some reason the level 1 selection is set to the default
                else {
                    for ($x = 1; $x < $levels_count; $x++) {
                        $this->options_output[$config['levels'][$x]] = '<option value="0">' . $config['defaults'][0] . '</option>';
                    }
                }

                $this->options_output['status'] = 'success';
                $this->options_output['token'] = $this->tokens->token();
                $this->options_output['ci_csrf_token'] = $this->security->get_csrf_hash();

                echo json_encode($this->options_output);
            }
        }
    }

    // --------------------------------------------------------------

    /**
     * This method contacts the model for data and puts it
     * all into an array that is used by _build_output().
     * This method also creates an array for selected options.
     */
    private function _build_dropdown_data($config)
    {
        // Count the levels
        $levels_count = count($config['levels']);

        if ($this->recursion + 2 <= $levels_count) {
            $data_key = $this->recursion + 2;

            // Set default option
            $this->dropdown_data[$data_key]['0'] = '-- Select --';

            // Set the method
            $method = $config['methods'][$this->recursion];

            if ($result = $this->autopop->$method()) {
                foreach ($result as $k => $v) {
                    // Build up the array of select data for this set
                    if (!empty($config['keys'])) {
                        $this->dropdown_data[$data_key][$v[$config['keys'][$this->recursion]]] = $v[$config['levels'][$this->recursion + 1]];
                    } else {
                        $this->dropdown_data[$data_key][$v[$config['levels'][$this->recursion + 1]]] = $v[$config['levels'][$this->recursion + 1]];
                    }
                }

                // If this isn't the last set
                if ($data_key != $levels_count) {
                    foreach ($result as $k => $v) {
                        // Check to see if the posted value for the next set is in this select data
                        if (in_array($this->input->post($config['levels'][$this->recursion + 1]), $v)) {
                            // Mark as selected
                            $this->selections[$data_key] = $this->input->post($config['levels'][$this->recursion + 1]);

                            $this->recursion++;
                            $this->_build_dropdown_data($config);

                            break;
                        }
                    }
                }
            }
        }
    }

    // --------------------------------------------------------------

    /**
     * This method takes the arrays created by _build_dropdown_data()
     * and makes sets of options that are sent back to process_request()
     */
    private function _build_output($config)
    {
        // Count the levels
        $levels_count = count($config['levels']);

        $data_key = $this->recursion + 2;

        $this->options_output[$config['levels'][$this->recursion + 1]] = '';

        if (!empty($this->dropdown_data[$data_key])) {
            foreach ($this->dropdown_data[$data_key] as $k => $v) {
                // If this is the selected option
                if (isset($this->selections[$data_key]) && $this->selections[$data_key] == $k) {
                    $this->options_output[$config['levels'][$this->recursion + 1]] .= '<option selected="selected" value="' . $k . '">' . $v . '</option>';
                } else {
                    $this->options_output[$config['levels'][$this->recursion + 1]] .= '<option value="' . $k . '">' . $v . '</option>';
                }
            }
        } else {
            $this->options_output[$config['levels'][$this->recursion + 1]] .= '<option value="0">' . $config['defaults'][$this->recursion] . '</option>';
        }

        $this->recursion++;

        if ($this->recursion + 2 <= $levels_count) {
            $this->_build_output($config);
        }
    }

    // --------------------------------------------------------------
}

/* End of file auto_populate.php */
/* Location: ./application/controllers/auto_populate.php */