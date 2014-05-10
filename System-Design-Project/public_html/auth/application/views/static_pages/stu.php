<div class="wrapper col7">
    <div id="banners">
        <div id="banners1">
            <h2>Banner self-Service</h2>
            <img src="img/menud.gif" alt="">
                <?php
                echo ( $this->uri->segment(1) == 'banner' ) ? anchor('banner', 'Banner Self Service', array( 'id' => 'active' ) ) : anchor('banner', 'Banner Self Service');
                ?>






        </div>

        <div class="banners2">
            <h2>Registration </h2>
            <img src="img/menud.gif" alt=""> <?php
            echo ( $this->uri->segment(1) == 'registatus' ) ? anchor('registatus', 'Registration Status', array( 'id' => 'active' ) ) : anchor('registatus', 'Registration Status');
            ?>
            <img src="img/menud.gif" alt=""><?php
            echo ( $this->uri->segment(1) == 'lookup' ) ? anchor('lookup', 'Look Up Classes', array( 'id' => 'active' ) ) : anchor('lookup', 'Look Up Classes');
            ?><br>
            <img src="img/menud.gif" alt=""><?php
            echo ( $this->uri->segment(1) == 'adclass' ) ? anchor('lookup', 'Add or Drop classes', array( 'id' => 'active' ) ) : anchor('adclass', 'Add or Drop Classes');
            ?><br>


        </div>
        <div class="banners3">
            <h2>Student information</h2>

                <li>Now Available: Online Parking Ticket. </li>
                 <ul>How to Access the new Online Parking </ul>
                Permit System:
               <li>On the left hand side, click on the Banner Self-Service link</li>

               <p> IMPORTANT: To protect your privacy, it is recommended that you Change your PIN. How to Access CHANGE your PIN</p>
                On the left hand side, click on the Personal Information link
</ul>

            </ul>

        </div>
        <br class="clear" />
    </div>
</div><p></p>
<div class="wrapper col8">
    <div id="stulink">
        <div id="stulink1">
            <h2>Financial Aid requierements</h2>
            <ul>Last day to submit Fafsa 5/08/2014</ul>
            <ul>Last day to submit eop application 5/08/2014</ul>
            <ul>Last day to submit Tap Application</ul>
        </div>
        <div class="stulink2">


            <h2>Student Grades</h2>
            <br>

            <select name="hello">
                <option value="one">Select Another Term</option>
                <option value="two">fall 2014</option>
                <option value="three">Spring 2014</option>
                <option value="four">Summer 2014</option>
            </select>
        </div>
        <div class="stulink3">

            <h2>Student Links</h2>
            <ul>
                <p>Student last day to register May 18 2014</p>
                <p>Last day for basketball try outs May 19 2014</p>
            </ul>
            <ul>
                <p>Student immunization shot day May 19 2014</p>
                <p></p>
            </ul>


        </div>
        <br class="clear" />
    </div>
</div><p></p>
