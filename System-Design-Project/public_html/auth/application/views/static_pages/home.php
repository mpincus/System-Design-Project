<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">
<head profile="http://gmpg.org/xfn/11">
    <title>SUNY TECH</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="imagetoolbar" content="no" />

    <script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.slidepanel.setup.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.tabs.setup.js"></script>
</head>
<body>
<div class="wrapper col0">
    <div id="topbar">
        <div id="slidepanel">
            <div class="topbox">
                <h2>Nullamlacus dui ipsum</h2>
                <p>Nullamlacus dui ipsum conseque loborttis non euisque morbi penas dapibulum orna. Urnaultrices quis curabitur phasellentesque congue magnis vestibulum quismodo nulla et feugiat. Adipisciniapellentum leo ut consequam ris felit elit id nibh sociis malesuada.</p>
                <p class="readmore"><a href="#">Continue Reading &raquo;</a></p>
            </div>
            <div class="topbox">
                <h2>Teachers Login Here</h2>
                <form action="#" method="post">
                    <fieldset>
                        <legend>Teachers Login Form</legend>
                        <label for="teachername">Username:
                            <input type="text" name="teachername" id="teachername" value="" />
                        </label>
                        <label for="teacherpass">Password:
                            <input type="password" name="teacherpass" id="teacherpass" value="" />
                        </label>
                        <label for="teacherremember">
                            <input class="checkbox" type="checkbox" name="teacherremember" id="teacherremember" checked="checked" />
                            Remember me</label>
                        <p>
                            <input type="submit" name="teacherlogin" id="teacherlogin" value="Login" />
                            &nbsp;
                            <input type="reset" name="teacherreset" id="teacherreset" value="Reset" />
                        </p>
                    </fieldset>
                </form>
            </div>
            <div class="topbox last">
                <h2>Pupils Login Here</h2>
                <form action="#" method="post">
                    <fieldset>
                        <legend>Pupils Login Form</legend>
                        <label for="pupilname">Username:
                            <input type="text" name="pupilname" id="pupilname" value="" />
                        </label>
                        <label for="pupilpass">Password:
                            <input type="password" name="pupilpass" id="pupilpass" value="" />
                        </label>
                        <label for="pupilremember">
                            <input class="checkbox" type="checkbox" name="pupilremember" id="pupilremember" checked="checked" />
                            Remember me</label>
                        <p>
                            <input type="submit" name="pupillogin" id="pupillogin" value="Login" />
                            &nbsp;
                            <input type="reset" name="pupilreset" id="pupilreset" value="Reset" />
                        </p>
                    </fieldset>
                </form>
            </div>
            <br class="clear" />
        </div>

        <br class="clear" />
    </div>
</div>
<!-- ####################################################################################################### -->

<div class="wrapper col3">
    <div id="featured_slide">
        <div id="featured_wrap">
            <ul id="featured_tabs">
                <li><a href="#fc1">SUNY TECH Football Team<br />
                        <span>Number one in the USA</span></a></li>
                <li><a href="#fc2">SUNY TECH Front View<br />
                        <span>Number one Tech Suny in New York</span></a></li>
                <li><a href="#fc3">Graduation<br />
                        <span>The most memorable day of your life!</span></a></li>
                <li class="last"><a href="#fc4">Library<br />
                        <span>One of the biggets library in the world</span></a></li>
            </ul>
            <div id="featured_content">
                <div class="featured_box" id="fc1"><img src="img/college.jpg" alt="" />
                    <div class="floater"><a href="#">Continue Reading &raquo;</a></div>
                </div>
                <div class="featured_box" id="fc2"><img src="img/frontv.jpg" alt="" />
                    <div class="floater"><a href="#">Continue Reading &raquo;</a></div>
                </div>
                <div class="featured_box" id="fc3"><img src="img/hats.jpg" alt="" />
                    <div class="floater"><a href="#">Continue Reading &raquo;</a></div>
                </div>
                <div class="featured_box" id="fc4"><img src="img/lib.jpg" alt="" />
                    <div class="floater"><a href="#">Continue Reading &raquo;</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper col4">
    <div id="container">
        <div id="hpage">
            <ul>
                <li>
                    <h2>Financial Aid</h2>
                    <div class="imgholder"><img src="img/financial2.jpg" alt="" /></div>
                    <p>The counselors and staff in the SUNY TECH Financial Aid Office work hard to make sure all qualified students have the opportunity to pursue a post-secondary education. A variety of forms of assistance are available to families whose resources are inadequate to meet the full cost of education. Financial aid comes in three forms: loans, grants, and work-study programs.
                    <p class="readmore"> <?php
                    echo ( $this->uri->segment(1) == 'Financial' ) ? anchor('financial', 'Continue reading', array( 'id' => 'active' ) ) : anchor('financial', 'Continue reading');
                    ?> </p>
                </li>
                <li>
                    <h2>Student Life </h2>
                    <div class="imgholder"><img src="img/life2.jpg" alt="" /></div>
                    <p>You should make memories that last a lifetime in college. We are dedicated to this idea, not in spite of our scholarly mission, but as a complement to it. Boredom and learning simply are not compatible. This may be our strongest argument for the nearly 50 clubs and organizations on our campus, for the hundreds of hours of entertainment</p>
                    <p class="readmore">
                        <?php
                        echo ( $this->uri->segment(1) == 'studentlife' ) ? anchor('Studentlife', 'Continue reading', array( 'id' => 'active' ) ) : anchor('studentlife', 'Continue reading');
                        ?>
                    </p>
                </li>
                <li>
                    <h2>Tuition</h2>
                    <div class="imgholder"><img src="img/tui.jpg" alt="" /></div>
                    <p>SUNY TECH has a growing reputation for providing an exceptional educational experience at an affordable cost. In addition to frequent recognition by national organizations for our academic quality and commitment to student engagement, we have been named to the Strong nation magazine</p>
                    <p class="readmore"> <?php
                        echo ( $this->uri->segment(1) == 'cost' ) ? anchor('cost', 'Continue reading', array( 'id' => 'active' ) ) : anchor('cost', 'Continue reading');
                        ?></p>
                </li>
                <li class="last">
                    <h2>Apply Now!</h2>
                    <div class="imgholder"><img src="img/info.jpg" alt="" /></div>
                    <p>The State University of New York (SUNY) allows you to apply to 50 of the 64 colleges and universities in the SUNY system through one application. Please contact the SUNY Application Services Center with any processing questions.
                        The Common Application enables you to apply to any of the 456 participating colleges and universities nationwide with one application. Please contact the Common Application with any processing questions.</p>
                    <p class="readmore"> <?php
                        echo ( $this->uri->segment(1) == 'apply' ) ? anchor('apply', 'Continue reading', array( 'id' => 'active' ) ) : anchor('apply', 'Continue reading');
                        ?></p>
                </li>
            </ul>
            <br class="clear" />
        </div>
    </div>
</div>
<!-- ####################################################################################################### -->

</body>
</html>
