<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="da" lang="da">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		
		<title>Hallo.dK <?php echo $title_for_layout; ?></title>
		<?php echo $this->Html->css( array("front_end/layout.css")); ?>
		<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>-->
		<!-- html5.js for IE less than 9 -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body class="home">
		<!-- wrapper Starts -->
		<section class="wrapper">
			<!-- Header Starts -->
			<?php echo $this->element("front_end/header");?>
			<?php echo $this->fetch('content');?>
			
		</section>
		<!-- wrapper Ends -->
		<?php echo $this->element("front_end/footer");
		echo $this->element("sql_dump");?>
	</body>
</html>
