<h1>International</h1>
<p>Thank you for including SUNY TECH in your college search process. Students may apply now for Fall 2014 admission.</p><br>
<div class="apply">

    <div class="apply2">

        <img src="img/applysuny.jpg" alt="" />
        <p>The State University of New York (SUNY) allows you to apply to 50 of the 64 colleges and universities in the SUNY system through one application. Please contact the SUNY Application Services Center with any processing questions.</p>

        <p>The Common Application enables you to apply to any of the 456 participating colleges and universities nationwide with one application. Please contact the Common Application with any processing questions.</p>
    </div>
    <div class="apply3">
        <div>
            <dl>

                <dt> <h1>Are you Ready!!</h1>

                </dt>
                <dd> <?php
                    echo ( $this->uri->segment(1) == 'apply' ) ? anchor('apply', 'Freshman', array( 'id' => 'active' ) ) : anchor('apply', 'Freshman');
                    ?></dd>
                <dd> <?php
                    echo ( $this->uri->segment(1) == 'transfer' ) ? anchor('transfer', 'Transfer', array( 'id' => 'active' ) ) : anchor('transfer', 'Transfer');
                    ?></dd>
                <dd> <?php
                    echo ( $this->uri->segment(1) == 'eop' ) ? anchor('eop', 'EOP', array( 'id' => 'active' ) ) : anchor('eop', 'EOP');
                    ?></dd>
                <dd> <?php
                    echo ( $this->uri->segment(1) == 'international' ) ? anchor('international', 'International', array( 'id' => 'active' ) ) : anchor('international', 'International');
                    ?></dd>
                <dd> <?php
                    echo ( $this->uri->segment(1) == 'graduate' ) ? anchor('graduate', 'Graduate', array( 'id' => 'active' ) ) : anchor('graduate', 'Graduate');
                    ?></dd>
                <dd> <?php
                    echo ( $this->uri->segment(1) == 'nondegree' ) ? anchor('nondegree', 'Non-degree', array( 'id' => 'active' ) ) : anchor('nondegree', 'Non-degree');
                    ?></dd>
            </dl>
        </div>

    </div>

</div>
<p>International students are encouraged to apply as early as possible. The deadline for degree-seeking and exchange students is November 16th for the Spring 2014 semester. The deadline for Fall 2014, is May 4th.</p>
<p>Required materials can be found at the International Undergraduate Application.</p>
<p> For more information on undergraduate admission criteria, transfer degree partners, ESL courses and other opportunities for international students, please visit the SUNY TECH Office of International Education.</p>
