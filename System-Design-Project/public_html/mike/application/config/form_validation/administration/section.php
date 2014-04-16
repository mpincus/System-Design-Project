<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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

$config['section_rules'] = array(
    // Validation of IP address added to deny list
    array(
        'field' => 'term',
        'label' => 'Term',
        'rules' => 'trim|string'
    ),
    array(
        'field' => 'year',
        'label' => 'Year',
        'rules' => 'trim|string'
    ),
    array(
        'field' => 'course_name',
        'label' => 'Course Name',
        'rules' => 'trim|string'
    ),
    array(
        'field' => 'timeslot',
        'label' => 'Time Slot',
        'rules' => 'trim|string'
    ),
    array(
        'field' => 'building',
        'label' => 'Building',
        'rules' => 'trim|string'
    ),
    array(
        'field' => 'room',
        'label' => 'Room',
        'rules' => 'trim|string'
    ),
    // Validation of any IP addresses being removed from deny list
    array(
        'field' => 'ip_removals[]',
        'label' => 'IP ADDRESSES TO REMOVE',
        'rules' => 'trim|string'
    )
);

/* End of file deny_access.php */
/* Location: /application/config/form_validation/administration/deny_access.php */