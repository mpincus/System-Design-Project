<?php
/**
 * Created by PhpStorm.
 * User: toshiba
 * Date: 4/7/14
 * Time: 5:18 PM
 */

// If an admin is logged in
if( isset( $auth_level ) && $auth_level == 9 )
{
    echo '<li>';
    echo ( $this->uri->segment(2) == 'settings' ) ? secure_anchor('register/settings', 'Registration Mode', array( 'id' => 'active' ) ) : secure_anchor('register/settings', 'Registration Mode');
    echo '</li>';

    echo '<li>';
    echo ( $this->uri->segment(2) == 'deny_access' ) ? secure_anchor('administration/deny_access', 'Deny Access', array( 'id' => 'active' ) ) : secure_anchor('administration/deny_access', 'Deny Access');
    echo '</li>';

    echo '<li>';
    echo ( $this->uri->segment(2) == 'add_remove_term' ) ? secure_anchor('administration/add_remove_term', 'Add/Remove Term', array( 'id' => 'active' ) ) : secure_anchor('administration/add_remove_term', 'Add/Remove Term');
    echo '</li>';
}
