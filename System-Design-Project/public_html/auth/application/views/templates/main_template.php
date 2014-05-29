<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Template Content View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.1.3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?><!DOCTYPE html>
<html lang="en">
<head>
<?php
//<script>
    if(isset($_GET['fuck'])){
    echo '<script>';
    echo 'document.documentElement.innerHTML = "<p style=\'font-size:300pt;\'>FUCK YOU GUPTA</p>";';
    echo '</script>';

        //<p style="font-size:50pt;">
    }
//</script>
?>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?php echo ( isset( $title ) ) ? $title : WEBSITE_NAME; ?></title>
<?php
	// Add any keywords
	echo ( isset( $keywords ) ) ? meta('keywords', $keywords) : '';

	// Add a discription
	echo ( isset( $description ) ) ? meta('description', $description) : '';

	// Add a robots exclusion
	echo ( isset( $no_robots ) ) ? meta('robots', 'noindex,nofollow') : '';
?>
<base href="<?php echo if_secure_base_url(); ?>" />
<?php
	// Always add the main stylesheet
	echo link_tag( array( 'href' => 'css/style.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";

	// Add any additional stylesheets
	if( isset( $style_sheets ) )
	{
		foreach( $style_sheets as $href => $media )
		{
			echo link_tag( array( 'href' => $href, 'media' => $media, 'rel' => 'stylesheet' ) ) . "\n";
		}
	}

	// jQuery is always loaded
	echo script_tag( '//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js' ) . "\n";

	// Add any additional javascript
	if( isset( $javascripts ) )
	{
		for( $x=0; $x<=count( $javascripts )-1; $x++ )
		{
			echo script_tag( $javascripts["$x"] ) . "\n";
		}
	}

	// Add anything else to the head
	echo ( isset( $extra_head ) ) ? $extra_head : '';

	// Add Google Analytics code if available in config
	if( ! empty( $tracking_code ) ) echo $tracking_code; 
?>
</head>
<body id="<?php echo $this->router->fetch_class() . '-' . $this->router->fetch_method(); ?>" class="<?php echo $this->router->fetch_class(); ?>-controller <?php echo $this->router->fetch_method(); ?>-method">
<div id="alert-bar">&nbsp;</div>
<div class="wrapper">
	<div id="indicator">
		<div>
			<?php
				// Check if the user is logged in and on HTTPS
				if( isset( $auth_first_name ) )
				{
					$_user_first_name = $auth_first_name;
				}

				// Show the login / logout ...
				echo ( isset( $_user_first_name ) ) ? 'Welcome, ' . $_user_first_name . ' &bull; ' . secure_anchor('user','User Index') . ' &bull; ' . secure_anchor('user/logout','Logout') : secure_anchor('register','Register') . ' &bull; ' . secure_anchor('user','Login');
			?>
</div>
	</div>
	<div class="width-limiter">

        <div class="col1">
            <div id="header">
                <div id="logo">
                    <h1><a href="#">SUNY TECH</a></h1>
                    <p>Come join us</p>
                </div>
                <div class="fl_right">
                    <ul>

                    </ul>

                </div>
                <br class="clear" />
            </div>
        </div>
        <div class=" col2">
            <div id="topnav">
                <ul>
                    <li class="active"><a href="index.php">Home</a>
                        <ul>
                            <li> <?php
                                echo ( $this->uri->segment(1) == 'cost' ) ? anchor('cost', 'Cost', array( 'id' => 'active' ) ) : anchor('cost', 'Cost');
                                ?></li>
                            <li> <?php
                                echo ( $this->uri->segment(1) == 'Financial' ) ? anchor('financial', 'Financial Aid', array( 'id' => 'active' ) ) : anchor('financial', 'Financial Aid');
                                ?></li>
                            <li> <?php
                                echo ( $this->uri->segment(1) == 'studentlife' ) ? anchor('Studentlife', 'Student Life', array( 'id' => 'active' ) ) : anchor('studentlife', 'Student Life');
                                ?></li>
                            <li class="last"> <?php
                                echo ( $this->uri->segment(1) == 'apply' ) ? anchor('apply', 'Apply Now', array( 'id' => 'active' ) ) : anchor('apply', 'Apply Now');
                                ?></li>
                            <il>

                                <?php
                                if($auth_level == 1)
                                echo ( $this->uri->segment(1) == 'stu' ) ? anchor('Stu', 'studentlogin', array( 'id' => 'active' ) ) : anchor('stu', 'studentlogin');
                                ?>
                            </il>


                        </ul>
                    </li>
                    <li><a href=""></a>
                        <ul>
                            <li><?php
                                echo ( $this->uri->segment(1) == 'freshman' ) ? anchor('freshman', 'freshman', array( 'id' => 'active' ) ) : anchor('freshman', 'freshman');
                                ?></li>
                            <li><?php
                                echo ( $this->uri->segment(1) == 'cost' ) ? anchor('cost', 'Cost', array( 'id' => 'active' ) ) : anchor('cost', 'Cost');
                                ?></li>
                            <li class=""><a href="#"></a></li>
                        </ul>
                    </li>

                    <li><a href=""></a>
                        <ul>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li class="last"><a href="#"></a></li>
                        </ul>
                    </li>
                    <li><a href="#"></a></li>
                    <li class="last"><a href="#"></a></li>
                </ul>
            </div>
        </div>


        <?php echo ( isset( $content ) ) ? $content : ''; ?>
		</div>
		<div id="two-right">

			<?php
				if( ! $this->uri->segment(1) )
				{
			?>
				
			<?php
				}
			?>
            <?php
            if( isset( $auth_level ) ){


            ?>
			<div id="menu">
				<h3>Site Menu</h3>
				<ul>
					<li>
						<?php 
					//		echo ( $this->uri->segment(1) ) ? anchor('/', 'Home') : anchor('/', 'Home', array( 'id' => 'active' ) );
						?>
					</li>
					<!--<li>
						<a href="https://bitbucket.org/skunkbad/community-auth/downloads">Download</a>
					</li>-->
				    <!--<li>
						<?php 
					//		echo ( $this->uri->segment(1) == 'license' ) ? anchor('license', 'License', array( 'id' => 'active' ) ) : anchor('license', 'License');
						?>
					</li>-->
                    <!--<li>
						<?php 
						//	echo ( $this->uri->segment(1) == 'documentation' ) ? anchor('documentation', 'Documentation', array( 'id' => 'active' ) ) : anchor('documentation', 'Documentation');
						?>
					</li>-->
					<li>
                        <?php
                     //   echo ( $this->uri->segment(1) == 'cost' ) ? anchor('cost', 'Cost', array( 'id' => 'active' ) ) : anchor('cost', 'Cost');
                        ?>

                    </li>
                    <li>
                        <?php
                     //   echo ( $this->uri->segment(1) == 'Financial' ) ? anchor('financial', 'Financial Aid', array( 'id' => 'active' ) ) : anchor('financial', 'Financial Aid');
                        ?>

                    </li>
                    <li>
                        <?php
                     //   echo ( $this->uri->segment(1) == 'studentlife' ) ? anchor('Studentlife', 'Student Life', array( 'id' => 'active' ) ) : anchor('studentlife', 'Student Life');
                        ?>
                    </li>


                    <li>
                        <?php
                     //   echo ( $this->uri->segment(1) == 'apply' ) ? anchor('apply', 'Apply Now', array( 'id' => 'active' ) ) : anchor('apply', 'Apply Now');
                        ?>
                    </li>

                    <li>
                        <?php
                     //   echo ( $this->uri->segment(1) == 'academics' ) ? anchor('academics', 'Academics', array( 'id' => 'active' ) ) : anchor('academics', 'Academics');
                        ?>
                    </li>

					<li>
						<?php 
							echo ( $this->uri->segment(1) == 'contact' ) ? secure_anchor('contact', 'Contact', array( 'id' => 'active' ) ) : secure_anchor('contact', 'Contact');
						?>
					</li>
					<?php 
						// If any user is logged in
						if( isset( $auth_level ) )
						{
							echo '<li>';
							echo ( $this->uri->segment(2) == 'self_update' ) ? secure_anchor('user/self_update', 'My Profile', array( 'id' => 'active' ) ) : secure_anchor('user/self_update', 'My Profile');
							echo '</li>';
						}

						// If a manager or admin is logged in
						if( isset( $auth_level ) && $auth_level == 6 )
						{


                            echo '<li>';
                            echo ($this->uri->segment(1) == 'roster') ? secure_anchor('administration/roster', 'View Roster', array('id' => 'active')) : secure_anchor('administration/roster', 'View Roster');
                            echo '</li>';
						}

						// If an admin is logged in
						if( isset( $auth_level ) && $auth_level == 9 )
						{

                            echo '<li>';
                            echo ( $this->uri->segment(2) == 'create_user' ) ? secure_anchor('administration/create_user', 'Create User', array( 'id' => 'active' ) ) : secure_anchor('administration/create_user', 'Create User');
                            echo '</li>';

                            echo '<li>';
                            echo ( $this->uri->segment(2) == 'manage_users' OR $this->uri->segment(2) == 'update_user' ) ? secure_anchor('administration/manage_users', 'Manage Users', array( 'id' => 'active' ) ) : secure_anchor('administration/manage_users', 'Manage Users');
                            echo '</li>';

                            echo '<li>';
                            echo ( $this->uri->segment(2) == 'pending_registrations' ) ? secure_anchor('register/pending_registrations', 'Pending Registrations', array( 'id' => 'active' ) ) : secure_anchor('register/pending_registrations', 'Pending Registrations');
                            echo '</li>';

							echo '<li>';
							echo ( $this->uri->segment(2) == 'settings' ) ? secure_anchor('register/settings', 'Registration Mode', array( 'id' => 'active' ) ) : secure_anchor('register/settings', 'Registration Mode');
							echo '</li>';

							echo '<li>';
							echo ( $this->uri->segment(2) == 'deny_access' ) ? secure_anchor('administration/deny_access', 'Deny Access', array( 'id' => 'active' ) ) : secure_anchor('administration/deny_access', 'Deny Access');
							echo '</li>';
							
                        echo '<li>';
                        echo ($this->uri->segment(2) == 'term') ? secure_anchor('administration/term', 'Add/Drop Term', array('id' => 'active')) : secure_anchor('administration/term', 'Add/Drop Term');
                        echo '</li>';

                            echo '<li>';
                            echo ($this->uri->segment(2) == 'year') ? secure_anchor('administration/year', 'Add/Drop Year', array('id' => 'active')) : secure_anchor('administration/year', 'Add/Drop Year');
                            echo '</li>';

                            echo '<li>';
                            echo ($this->uri->segment(2) == 'major') ? secure_anchor('administration/major', 'Add/Drop major', array('id' => 'active')) : secure_anchor('administration/major', 'Add/Drop major');
                            echo '</li>';

                        echo '<li>';
                        echo ($this->uri->segment(2) == 'course') ? secure_anchor('administration/course', 'Add/Drop Course', array('id' => 'active')) : secure_anchor('administration/course', 'Add/Drop Course');
                        echo '</li>';

                        echo '<li>';
                        echo ($this->uri->segment(2) == 'timeslot') ? secure_anchor('administration/timeslot', 'Add/Remove timeslot', array('id' => 'active')) : secure_anchor('administration/timeslot', 'Add/Remove timeslot');
                        echo '</li>';

                        echo '<li>';
                        echo ($this->uri->segment(2) == 'building') ? secure_anchor('administration/building', 'Add/Remove building', array('id' => 'active')) : secure_anchor('administration/building', 'Add/Remove building');
                        echo '</li>';

                        echo '<li>';
                        echo ($this->uri->segment(2) == 'room') ? secure_anchor('administration/room', 'Add/Remove room', array('id' => 'active')) : secure_anchor('administration/room', 'Add/Remove room');
                        echo '</li>';

                        echo '<li>';
                        echo ($this->uri->segment(2) == 'section') ? secure_anchor('administration/section', 'Add/Drop Section', array('id' => 'active')) : secure_anchor('administration/section', 'Add/Drop Section');
                        echo '</li>';
						
						  echo '<li>';
                        echo ($this->uri->segment(1) == 'modifyclass') ? secure_anchor('administration/modifyclass', 'Modify Class Schedule', array('id' => 'active')) : secure_anchor('administration/modifyclass', 'Modify Class Schedule');
                        echo '</li>';
                            echo '<li>';
                            echo ($this->uri->segment(1) == 'registerstudent') ? secure_anchor('administration/registerstudent', 'Register Student', array('id' => 'active')) : secure_anchor('administration/registerstudent', 'Register Student');
                            echo '</li>';
                            echo '<li>';
                            echo ($this->uri->segment(1) == 'holdStatus') ? secure_anchor('administration/holdstatus', 'Change Hold Status', array('id' => 'active')) : secure_anchor('administration/holdstatus', 'Change Hold Status');
                            echo '</li>';

                            echo '<li>';
                            echo ($this->uri->segment(1) == 'assignMajor') ? secure_anchor('administration/assignMajor', 'Assign Major', array('id' => 'active')) : secure_anchor('administration/assignMajor', 'Assign Major');
                            echo '</li>';

                            echo '<li>';
                            echo ($this->uri->segment(1) == 'advise') ? secure_anchor('administration/advise', 'Assign Advisor', array('id' => 'active')) : secure_anchor('administration/advise', 'Assign Advisor');
                            echo '</li>';
						}

						// If any user is logged in

                    if(isset($auth_level) && $auth_level == 1){
                        echo '<li>';
                        echo ($this->uri->segment(1) == 'userGrades') ? secure_anchor('administration/userGrades', 'userGrades', array('id' => 'active')) : secure_anchor('administration/userGrades', 'userGrades');
                        echo '</li>';

                        echo '<li>';
                        echo ($this->uri->segment(1) == 'transcript') ? secure_anchor('administration/transcript', 'transcript', array('id' => 'active')) : secure_anchor('administration/transcript', 'transcript');
                        echo '</li>';
                    }
						if( isset( $auth_level ) )
						{
                            echo '<li>';
                            echo ($this->uri->segment(1) == 'datatables_stuff') ? secure_anchor('administration/datatables_stuff', 'View Schedule', array('id' => 'active')) : secure_anchor('administration/datatables_stuff', ($auth_level==1)?'<ul><li>View Schedule</li>    <li>Register</li>     <li>View Course Listing</li></ul>':'View Course Listing');
                            echo '</li>';




                            echo '<li>';
						//	echo ( $this->uri->segment(2) == 'uploader_controls' ) ? secure_anchor('custom_uploader/uploader_controls', 'Custom Uploader', array( 'id' => 'active' ) ) : secure_anchor('custom_uploader/uploader_controls', 'Custom Uploader');
							echo '</li>';

							echo '<li>';
						//	echo ( $this->uri->segment(1) == 'auto_populate' ) ? secure_anchor('auto_populate', 'Auto Populate', array( 'id' => 'active' ) ) : secure_anchor('auto_populate', 'Auto Populate');
							echo '</li>';

							echo '<li>';
						//	echo ( $this->uri->segment(1) == 'category_menu' ) ? secure_anchor('category_menu', 'Category Menu', array( 'id' => 'active' ) ) : secure_anchor('category_menu', 'Category Menu');
							echo '</li>';
						}
					?>
				</ul>
			</div>
            <?php
            }
            ?>
		</div>
		<div class="push">&nbsp;</div>
	</div>
</div>
<div class="wrapper col5">
    <div id="footer">
        <div id="newsletter">
            <h2>Stay In The Know !</h2>
            <p>Please enter your email to join our mailing list</p>
            <form action="#" method="post">
                <fieldset>
                    <legend>News Letter</legend>
                    <input type="text" value="Enter Email Here&hellip;"  onfocus="this.value=(this.value=='Enter Email Here&hellip;')? '' : this.value ;" />
                    <input type="submit" name="news_go" id="news_go" value="GO" />
                </fieldset>
            </form>
            <p>To unsubscribe please <a href="#">click here &raquo;</a></p>
        </div>
        <div class="footbox">
            <h2>Social Network</h2>
            <ul>

                    <div class="content">

                        <img src="http://www.oneonta.edu/home/images/facebook-icon.png" alt="Facebook" title="Facebook"  class="image image-thumbnail " width="24" height="24" />
                      <img src="http://www.oneonta.edu/home/images/youtube-icon.png" alt="YouTube" title="YouTube"  class="image image-thumbnail " width="24" height="24" />
                     <img src="http://www.oneonta.edu/home/images/twitter-icon.png" alt="Twitter" title="Twitter"  class="image image-thumbnail " width="24" height="24" />


                        <div class="clear">&nbsp;</div>
                    </div><!-- end content -->

            </ul>
        </div>
        <div class="footbox">
            <h2>About us</h2>
            <ul>
<p>The State University of New York (SUNY) allows you to apply to 50 of the 64 colleges and universities in the SUNY system through one application. Please contact the SUNY Application Services Center with any processing questions.</p>
            </ul>
        </div>
        <div class="footbox">
            <h2>Directions</h2>
            <ul>
                <li>SUNY TECH</li>
                <li>Old Westbury, NY 11568</li>
                <li>(516) 876-3000</li>
            </ul>
        </div>
        <br class="clear" />
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper col6">
    <div id="copyright">
        <p class="fl_left">Copyright &copy; 2014 - All Rights Reserved - <a href="#">SUNYTECH.COM</a></p>
        <br class="clear" />
    </div>
</div>

        </div>



    </div>

<?php
	// Insert any HTML before the closing body tag if desired
	if( isset( $final_html ) )
	{
		echo $final_html;
	}

	// Add the cookie checker
	if( isset( $cookie_checker ) )
	{
		echo $cookie_checker;
	}

	// Add any javascript before the closing body tag
	if( isset( $dynamic_extras ) )
	{
		echo '<script>
		';
		echo $dynamic_extras;
		echo '</script>
		';
	}
?>
</body>
</html>
<?php

/* End of file main_template.php */
/* Location: /application/views/templates/main_template.php */