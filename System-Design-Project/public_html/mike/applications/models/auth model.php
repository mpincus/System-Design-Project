<?php
/**
 * Get all data from the denied access table,
 * or set the field parameter to retrieve a single field.
 */
public function get_term_list( $field = FALSE )
{
    if( $field !== FALSE )
    {
        $this->db->select( $field );
    }

    $query = $this->db->from( config_item('term_table') )->get();

    if( $query->num_rows() > 0 )
    {
        return $query->result();
    }

    return FALSE;
}

// --------------------------------------------------------------

/**
 * Validate and process the denial or removal of IP addresses
 * in the denied access table.
 */
public function process_term()
{
    // The form validation class doesn't allow for multiple config files, so we do it the old fashion way
    $this->config->load( 'form_validation/administration/add_remove_term' );
    $this->validation_rules = config_item('add_remove_term_rules');

    if( $this->validate() )
    {
        // If form submission is adding to deny list
        if( $this->input->post('add_term') )
        {
            $season     = set_value('term_season');
            $year = set_value('term_year');

            // Make sure that the values we need were posted
            if( ! empty( $season ) && is_numeric( $year ) )
            {
                $insert_data = array(
                    'term_season'  => $season,
                    'term_year' => $year,
                    'time'        => time()
                );

                // Insert the denial
                $this->_insert_term( $insert_data );

                // Show confirmation that denial was added
                $this->load->vars( array( 'confirm_add_term' => 1 ) );

                // Kill set_value() since we won't need it
                $this->kill_set_value();
            }

            // Necessary values were not available
            else
            {
                // Show error message
                $this->load->vars( array( 'validation_errors' => '<li>An <span class="redfield">term</span> is required.</li>' ) );
            }
        }

        // If form submission is removing from deny list
        else if( $this->input->post('remove_selected') )
        {
            // Get the IPs to remove
            $terms = set_value('term_removals[]');

            // If there were IPs
            if( ! empty( $terms ) )
            {
                // Remove the IPs
                $this->_remove_term( $terms );

                // Show confirmation of removal
                $this->load->vars( array( 'confirm_removal' => 1 ) );
            }

            // If there were no IPs posted
            else
            {
                // Show error message
                $this->load->vars( array( 'validation_errors' => '<li>At least one <span class="redfield">term</span> must be selected for removal.</li>' ) );
            }
        }
    }
}

// --------------------------------------------------------------

/**
 * Add a record to the denied access table
 */
protected function _insert_term( $data )
{
   /* if( $data['IP_address'] == '0.0.0.0' )
    {
        return FALSE;
    }*/

    $this->db->set( $data )
        ->insert( config_item('term_table') );

 //   $this->_rebuild_deny_list();
}

// --------------------------------------------------------------

/**
 * Remove a record from the denied access table
 */
protected function _remove_term( $terms )
{
    $i = 0;

    foreach( $terms as $ip)
    {
        if( $i == 0 )
        {
            $this->db->where('IP_address', $ip );
        }
        else
        {
            $this->db->or_where('IP_address', $ip );
        }

        $i++;
    }

    $this->db->delete( config_item('term_table') );

   // $this->_rebuild_deny_list();
}