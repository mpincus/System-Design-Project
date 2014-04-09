<h1>Transfer</h1>
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

    </div><br>

<h1> The sky is the limit</h1>
<p>There are no deadlines for transfer applications. It is recommended that applications be on file and completed by March 15 (fall applicants) or November 15 (spring applicants). Applications are reviewed on a rolling basis. Applications completed after the recommended filing dates will be considered on a space-available basis.</p>