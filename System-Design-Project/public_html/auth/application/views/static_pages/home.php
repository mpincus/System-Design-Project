<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Home View
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

<h1>SUNY TECH</h1>

<?php

	if( isset( $auth_level ) )
	{
		echo '
			<p>
				You are seeing this version of the home page because you are logged in. If you look in the view for this home page, you will see how the variable "$auth_level" is being used to test for this condition. $auth_level is only set if a logged in user is making the request.
			</p>
		';
	}
	else
	{
		?>

        <div id="main">
			<div align="right"><img src="img/school-spirit.gif" alt="We've got School Spirit!" /></div>
			<div id="mainphotos"><img src="img/photo-1.jpg" alt="Photo 1" /><img src="img/photo-2.jpg" alt="Photo 2" /><img src="img/learning-is-fun.gif" alt="Learning is Fun" /><img src="img/photo-3.jpg" alt="Photo 3" /></div>
			<div id="maintext"><img src="img/welcome.gif" alt="Welcome" />
				<p>SUNY Tech is a large-size, public college rooted in scholarship across the liberal arts and sciences. We prepare students for successful careers and the pursuit of advanced degrees.
                As a member of the State University of New York, our small classes, opportunities for undergraduate research, and student/faculty interaction are as attractive as our cost.
				</p><p>Nestled in the hills of Central New York, the college idyllic setting is ideal for study, adventure and self-discovery. Just “up the hill” from the charming City of westbury, our campus blends community and academe to engage, nurture, and inspire.
                Initially established in 2002 as a state normal school, Tech has become a premier school of the SUNY system. Our strong alumni network spans the globe, demonstrating the leadership and accomplishment that exemplify an Tech education. </p>

        </div>
 <div class="clear"></div><br>

</div>
    <?php
	}

?>

<p style="padding-top:18px;font-size:85%;color:#777;">
	Page rendered in {elapsed_time} seconds
</p>

<?php

/* End of file home.php */
/* Location: /application/views/home/home.php */