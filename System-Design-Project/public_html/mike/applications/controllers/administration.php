<?php

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
        if( $this->require_role('admin') )
        {
            if( config_item('add_remove_term') > 0 )
            {
                // If POST, do delete or addition of IP
                if( $this->tokens->match )
                {
                    $this->auth_model->process_term();
                }

                // Get the current deny list
                $view_data['term_list'] = $this->auth_model->get_term_list();
            }

            $data = array(
                'content' => $this->load->view( 'administration/add_remove_term', ( isset( $view_data ) ) ? $view_data : '', TRUE ),
                'javascripts' => array(
                    'js/jquery.char-limiter-3.0.0.js',
                    'js/default-char-limiters.js'
                )
            );

            $this->load->view( $this->template, $data );
        }
    }

    // --------------------------------------------------------------
}

/* End of file administration.php */
/* Location: /application/controllers/administration.php */