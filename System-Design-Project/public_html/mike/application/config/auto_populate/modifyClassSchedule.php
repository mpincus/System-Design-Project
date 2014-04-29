<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Community Auth - Example Config for Auto Population of Form Selects
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

$config['modifyClassSchedule'] = array(

    /**
     * Levels array has database table columns that become the visible text in the options.
     */
    'levels' => array(
        'year',
        'term',
        'courseName'
       // 'section'
    ),

    /**
     * Keys are the database column names that become the values of the options.
     * If left empty, the options will have a value that is the same as the visible text.
     * You only need to start keys at the second level. So for instance, if the vehicle
     * make had an ID, and you wanted to use that as the dropdown option value,
     * you would put the name of the ID field as the first array element.
     * See array elements that are commented out.
     */
    'keys' => array(
        //'make_id',
        //'model_id'
    ),

    /**
     * Methods are the methods in the model that apply for levels 2 and up.
     * The specific model is not currently a config option, so these methods
     * are found in the auto_populate_model.php.
     */
    'methods' => array(
        'get_modify_class_year',
        'get_modify_class_term_in_year',
        'get_modify_class_course_in_term'
        //'get_modify_class_section_in_course',
     //   'get_modify_class_otherInfo_in_section'

    ),

    /**
     * These are the default values of the dropdown options.
     */
    'defaults' => array(
        // Default option for second dropdown when first is ready
        '-- Select Year --',
        // Default option for third dropdown when second is ready
        '-- Select Term --',
        '-- Select Course --',
     //   '-- Select Section --'
    )

);

/* End of file example.php */
/* Location: /application/config/auto_populate/example.php */