<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Form Validation Rules for Managing Deny List
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

$config['add_remove_term_rules'] = array(
    // Validation of IP address added to deny list
    array(
        'field' => 'term_season',
        'label' => 'TERM SEASON',
        'rules' => 'trim|string'
    ),
    array(
        'field' => 'term_year',
        'label' => 'TERM YEAR',
        'rules' => 'trim|integer'
    ),
    // Validation of any IP addresses being removed from deny list
    array(
        'field' => 'term_remove',
        'label' => 'TERM TO REMOVE',
        'rules' => 'trim|string'
    )
);

/* End of file deny_access.php */
/* Location: /application/config/form_validation/administration/deny_access.php */