<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Auto_populate_model Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 *
 *
 * Some alternate query examples are shown in the comments above each method.
 * These alternate queries would be used if you were using "keys" in the
 * config file, and if seperate tables were being used for types, makes, and models.
 */

class Auto_populate_model extends CI_Model {

    /**
     * Method to query database for vehicle types.
     *
     * If you were using "keys" in the config file,
     * you might use a query like this:
     *
    $query = $this->db->distinct()
    ->select('type_id,type')
    ->get('auto_types');
     */
    public function get_years()
    {
        $query = $this->db->distinct()
            ->select('year')
            ->get( config_item('section_table') );

        if( $query->num_rows() > 0 )
        {
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
    $this->db->distinct();
    $this->db->select('make_id,make');
    $this->db->where('type_id_fk',$type );
     */
    public function get_terms_in_year()
    {
        $year = $this->input->post('year');

        $this->db->distinct();

        $this->db->select('term');

        $this->db->where('year',$year );

        $query = $this->db->get( config_item('section_table') );

        if( $query->num_rows() > 0 )
        {
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
    $this->db->select('model_id,model');
    $this->db->where('type_id_fk',$type);
    $this->db->where('make_id_fk',$make);
     */
    public function get_courseNames_in_term()
    {
        $year = $this->input->post('year');
        $term = $this->input->post('term');

        $this->db->select('courseName');

        $this->db->where('year',$year);
        $this->db->where('term',$term);

        $query = $this->db->get( config_item('section_table') );

        if( $query->num_rows() > 0 )
        {
            return $query->result_array();
        }

        return FALSE;
    }

    // --------------------------------------------------------------

    /**
     * Method to query database for vehicle colors.
     */
    public function get_sections_in_courseName()
    {
        $year = $this->input->post('year');
        $term = $this->input->post('term');
        $courseName = $this->input->post('courseName');

        $this->db->select('section');

        $this->db->where('year',$year);
        $this->db->where('term',$term);
        $this->db->where('courseName',$courseName);

        $query = $this->db->get( config_item('section_table') );

        if( $query->num_rows() > 0 )
        {
            return $query->result_array();
        }

        return FALSE;
    }
    public function get_classes()
    {
        $year = $this->input->post('year');
        $term = $this->input->post('term');
        $courseName = $this->input->post('courseName');
       // $section = $this->input->post('section');

       // $this->db->select('section');

        $this->db->where('year',$year);
        $this->db->where('term',$term);
        $this->db->where('courseName',$courseName);


        $query = $this->db->get( config_item('section_table') );
       // $query = $this->db->get_where(config_item('section_table'), array('year' => $year), $limit, $offset);

        if( $query->num_rows() > 0 )
        {
            return $query->result();
        }

        return FALSE;
    }

    // --------------------------------------------------------------

    public function postClasses(){
        if ($this->input->post('get_classes')) {
            $termYear = set_value('year');
            $trueTermYear=$this->auth_model->getTrueVal('year',$termYear,config_item('section_table'));
            $termSeason = set_value('term');
            $trueTermSeason=$this->auth_model->getTrueVal('term',$termSeason,config_item('section_table'));
            $courseName = set_value('course_name');
            $trueCourseName=$this->auth_model->getTrueVal('courseName',$courseName,config_item('section_table'));
            $sectionID = set_value('section_id');
            $trueSectionID=$this->auth_model->getTrueVal('section',$sectionID,config_item('section_table'));
           // $instructor = set_value('instructor_name');
           // $trueInstructor = $this->auth_model->getInstructorVal('first_name, last_name',$instructor,config_item('manager_profiles_table'));

         // Show confirmation that denial was added
                $this->load->vars(array('confirm_add_stuff' => 1));

                // Kill set_value() since we won't need it

        } // Necessary values were not available

         // If form submission is removing from deny list
        else if ($this->input->post('remove_selected')) {
            // Get the IPs to remove
            $ips = set_value('ip_removals[]');

            // If there were IPs
            if (!empty($ips)) {
                // Remove the IPs
               // $this->_remove_stuff($ips,$stuff_table);

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

/* End of file auto_populate_model.php */
/* Location: /application/models/auto_populate_model.php */