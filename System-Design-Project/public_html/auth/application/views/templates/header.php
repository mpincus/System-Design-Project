<html>
<head>
	<title><?php echo $title; ?></title>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <!--[if IE ]>
    <link href="css/ie.css" rel="stylesheet" type="text/css" />
    <![endif]-->
</head>
<body>
<div class="wrapper col2">
    <div id="topnav">
        <ul>
            <li class="active"><a href="index.html">Home</a>
                <ul>
                    <li> <?php
                        echo ( $this->uri->segment(1) == 'cost' ) ? anchor('cost', 'Cost', array( 'id' => 'active' ) ) : anchor('cost', 'Cost');
                        ?></li>
                    <li> <?php
                        echo ( $this->uri->segment(1) == 'Financial' ) ? anchor('financial', 'Financial Aid', array( 'id' => 'active' ) ) : anchor('financial', 'Financial Aid');
                        ?></li>
                    <li><a href="#">Mauris</a></li>
                    <li class="last"><a href="#">Suspendisse</a></li>
                </ul>
            </li>
            <li><a href="style-demo.html">Style Demo</a>
                <ul>
                    <li><a href="#">Lorem ipsum dolor</a></li>
                    <li><a href="#">Suspendisse in neque</a></li>
                    <li class="last"><a href="#">Praesent et eros</a></li>
                </ul>
            </li>
            <li><a href="full-width.html">Full Width</a>
                <ul>
                    <li><a href="#">Lorem ipsum dolor</a></li>
                    <li><a href="#">Suspendisse in neque</a></li>
                    <li class="last"><a href="#">Praesent et eros</a></li>
                </ul>
            </li>
            <li><a href="#">Our Services</a></li>
            <li class="last"><a href="#">Long Link Text</a></li>
        </ul>
    </div>
</div>



