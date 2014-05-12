<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - User Index View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?>

<?php
if($auth_level == 9)
    echo '<h1>Admin Page</h1>';
elseif($auth_level == 6)
    echo '<h1>Staff Page</h1>';
else{
    echo '<h1>Student Page</h1>';
    $this->load->view('static_pages/stu');
}
?>


    
<?php
/* End of file user_index.php */
/* Location: /application/views/user/user_index.php */