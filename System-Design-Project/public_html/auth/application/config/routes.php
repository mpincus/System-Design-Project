<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'static_pages';
$route['404_override']       = 'custom_error_page/error_404';

// Static pages
$route['language_test']      = 'static_pages/language_test';
$route['license']            = 'static_pages/license';
$route['privacy']            = 'static_pages/privacy';
$route['robots.txt']         = 'static_pages/robots_txt';
$route['cost']               = 'static_pages/cost';
$route['studentlife']        = 'static_pages/studentlife';
$route['financial']        = 'static_pages/financial';
$route['academics']        = 'static_pages/academics';
$route['apply']        = 'static_pages/apply';
$route['transfer']        = 'static_pages/transfer';
$route['eop']        = 'static_pages/eop';
$route['international']        = 'static_pages/international';
$route['graduate']        = 'static_pages/graduate';
$route['nondegree']        = 'static_pages/nondegree';
$route['stu']        = 'static_pages/stu';
$route['banner']        = 'static_pages/banner';
$route['fee']        = 'static_pages/fee';
// pages when a student logs in


$route['registatus']        = 'static_pages/registatus';
$route['lookup']        = 'static_pages/lookup';
$route['adclass']        = 'static_pages/adclass';

/* End of file routes.php */
/* Location: ./application/config/routes.php */