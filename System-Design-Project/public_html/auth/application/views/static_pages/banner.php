<h1>Banner Self Service</h1>


<h2>Student Services </h2>
<li>Registration</li>

<ul>Add Or drop Class</ul>
<ul>
    <?php
    echo ( $this->uri->segment(1) == 'lookup' ) ? anchor('lookup', 'Look Up classes', array( 'id' => 'active' ) ) : anchor('lookup', 'Look Up Classes');
    ?>
</ul>
<ul>Student Schedule By day & Time</ul>
<ul>
    <?php
    echo ( $this->uri->segment(1) == 'fee' ) ? anchor('fee', 'Registration Fee', array( 'id' => 'active' ) ) : anchor('fee', 'Registration Fee');
    ?>
</ul>
<ul>check your registration Status</ul>

<br>
<h2>Student Record</h2>
<ul>View Holds</ul>
<ul>Midterm Grades</ul>
<ul>Final Grades</ul>
<ul>Request Printed Transcript</ul>
<ul>Class Schedule</ul>



