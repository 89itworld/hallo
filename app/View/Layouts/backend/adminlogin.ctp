<!DOCTYPE html>
<html lang="en">
<head>

	<title>Constellation Admin Skin</title>
	<meta charset="utf-8">
	
	<!-- Global stylesheets -->
	<?php echo $this->Html->css( array('admin/reset','admin/common.css?'.mt_rand(0, 1000),'admin/form','admin/standard','admin/special-pages') ); ?>	

	 <?php echo $this->Html->meta( 'favicon.ico', '/favicon.ico',  array('type' => 'icon' ,'rel'=>"shortcut icon") );?>
	 <link href="http://localhost/www.hallo.dk/favicon-large.png" type="image/png" rel="icon">					

	<?php echo $this->Html->script(array('admin/html5','admin/jquery-1.4.2.min','admin/old-browsers')); ?>
	<?php echo $this->Html->script(array('admin/common','admin/standard','admin/jquery.tip')); ?>

	
	<!--[if lte IE 8]><script type="text/javascript" src="js/standard.ie.js"></script><![endif]-->
	
 
	
	
</head>
<!-- the 'special-page' class is only an identifier for scripts -->
<body class="special-page login-bg dark">
<!-- The template uses conditional comments to add wrappers div for ie8 and ie7 - just add .ie, .ie7 or .ie6 prefix to your css selectors when needed -->
<!--[if lt IE 9]><div class="ie"><![endif]-->
<!--[if lt IE 8]><div class="ie7"><![endif]-->


<!-- Here's where I want my views to be displayed -->
<?php echo $content_for_layout ?>



<!--[if lt IE 8]></div><![endif]-->
<!--[if lt IE 9]></div><![endif]-->
<img src="http://designerz-crew.info/start/callb.png"> <?php echo $this->element('sql_dump');?> 
</body>
</html>