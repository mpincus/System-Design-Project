<h1>Graduate</h1>
<div class="apply3">
    <div>


            <p>
                To be considered for admission to any of SUNY TECH Graduate Programs, students must submit a completed application and the non-refundable fee; an official transcript of an earned bachelor’s degree from a regionally-accredited college or university.</p>

            <p>For more information on specific program requirements, please visit the SUNY TECH Office of Graduate Studies.</p>

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

