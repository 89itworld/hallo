<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="da" lang="da">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

	<title>Hallo.dK <?php echo $title_for_layout; ?></title>
	
	
	<!-- Global stylesheets -->
	<?php echo $this->Html->css( array('admin/reset','admin/common.css?'.mt_rand(0, 1000),'admin/form','admin/standard') ); ?>	
	 
	
	<!-- Comment/uncomment one of these files to toggle between fixed and fluid layout -->
	<!--<link href="css/960.gs.css" rel="stylesheet" type="text/css">-->
	<?php echo $this->Html->css( array('admin/960.gs.fluid') ); ?>
	 
	
	<!-- Custom styles -->
	<?php echo $this->Html->css( array('admin/simple-lists','admin/block-lists','admin/planning','admin/table','admin/calendars','admin/wizard','admin/gallery') ); ?>
 
	
	<!-- Favicon -->
	 <?php echo $this->Html->meta( 'favicon.ico', '/favicon.ico',  array('type' => 'icon' ,'rel'=>"shortcut icon") );?>
	 <link href="http://localhost/www.hallo.dk/favicon-large.png" type="image/png" rel="icon">	
 
	
	<!-- Generic libs -->
	<?php echo $this->Html->script(array('admin/html5','admin/jquery-1.4.2.min','admin/old-browsers')); ?> 
	 
	<!-- Template libs -->
	<?php echo $this->Html->script(array('admin/jquery.accessibleList','admin/searchField','admin/common','admin/standard')); ?> 	 
	<!--[if lte IE 8]><script type="text/javascript" src="js/standard.ie.js"></script><![endif]-->
	<?php echo $this->Html->script(array('admin/jquery.tip','admin/jquery.hashchange','admin/jquery.contextMenu')); ?>
	 
	
	<!-- Custom styles lib -->
	<?php echo $this->Html->script(array('admin/list','admin/admin.js?'.mt_rand(0,1000))); ?> 
	
	<!-- Charts library -->
	<!--Load the AJAX API-->
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
	
		// Load the Visualization API and the piechart package.
		google.load('visualization', '1', {'packages':['corechart']});
		
	</script>
	
	<!-- Example context menu -->
	<script type="text/javascript">
	
		$(document).ready(function()
		{
			// Context menu for all favorites
			$('.favorites li').bind('contextMenu', function(event, list)
			{
				var li = $(this);
				
				// Add links to the menu
				if (li.prev().length > 0)
				{
					list.push({ text: 'Move up', link:'#', icon:'up' });
				}
				if (li.next().length > 0)
				{
					list.push({ text: 'Move down', link:'#', icon:'down' });
				}
				list.push(false);	// Separator
				list.push({ text: 'Delete', link:'#', icon:'delete' });
				list.push({ text: 'Edit', link:'#', icon:'edit' });
			});
			
			// Extra options for the first one
			$('.favorites li:first').bind('contextMenu', function(event, list)
			{
				list.push(false);	// Separator
				list.push({ text: 'Settings', icon:'terminal', link:'#', subs:[
					{ text: 'General settings', link: '#', icon: 'blog' },
					{ text: 'System settings', link: '#', icon: 'server' },
					{ text: 'Website settings', link: '#', icon: 'network' }
				] });
			});
		});
	
	</script>
	
</head>

<body>
<!-- The template uses conditional comments to add wrappers div for ie8 and ie7 - just add .ie or .ie7 prefix to your css selectors when needed -->
<!--[if lt IE 9]><div class="ie"><![endif]-->
<!--[if lt IE 8]><div class="ie7"><![endif]-->
	
	<!-- Header -->
	
	<!-- Server status -->
	<header><div class="container_12">
		
		<p id="skin-name"><small><?php echo SITE_NAME;?><br><?php
			$panel_type = array("1"=>"Admin","2"=>"Dealer","3"=>"Sub Dealer");
			echo $panel_type[$this->Session->read("Auth.User.role_id")]." Panel";
		?>
		</small> <strong>1.0</strong></p>
		<div class="server-info">Server: <strong><?php echo env('SERVER_SOFTWARE');?></strong></div>
		<div class="server-info">IP: <strong><?php echo env('REMOTE_ADDR');?></strong></div>
		
	</div></header>
	<!-- End server status -->
		
	<!-- Main nav -->
		<?php echo $this->element('backend/main_nav'); ?>
	<!-- End main nav -->
	
	<!-- Sub nav -->
		<?php //echo $this->element('backend/sub_nav'); ?>
	<!-- End sub nav -->
	
	<!-- Status bar -->
	<div id="status-bar"><div class="container_12">
	
		<ul id="status-infos">
			<li class="spaced">Logged as: <strong><?php echo ucfirst($role_list[$this->Session->read('Auth.User.role_id')]); ?></strong></li>
			<li>
				<!--<a href="#" class="button" title="5 messages"><img src="images/icons/fugue/mail.png" width="16" height="16"> <strong>5</strong></a>-->
			</li>
			<li> 
				<?php 
					echo $this->Html->link('<span class="smaller">LOGOUT</span>',array("controller"=>"users","action"=>"logout","admin"=>true),array('escape' => false,'title'=> "Logout",'class'=> "button red"));
				?> 
			</li>
		</ul>
		
		 
	<?php echo $this->element('backend/admin_breadcrumb'); ?>
			
			 
	
	</div></div>
	<!-- End status bar -->
	
	<div id="header-shadow"></div>
	<!-- End header -->
 	
	<!-- Always visible control bar -->
	<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
		<?php /*
				<div class="float-left">
					<button type="button"><img src="images/icons/fugue/navigation-180.png" width="16" height="16"> Back to list</button>
				</div>
				
				<div class="float-right"> 
					<button type="button" disabled="disabled">Disabled</button>
					<button type="button" class="red">Cancel</button> 
					<button type="button" class="grey">Reset</button> 
					<button type="button"><img src="images/icons/fugue/tick-circle.png" width="16" height="16"> Save</button>
				</div>
		*/?>
	</div></div>
	<!-- End control bar -->
 
	<!-- Content -->

			<?php echo $this->fetch('content'); ?>
	
		
	<!-- End content -->
	
	<footer>
		
		<div class="float-left">
			<a href="#" class="button">Help</a>
			<a href="#" class="button">About</a>
		</div>
		
		<div class="float-right">
			<a href="#top" class="button"><img src="images/icons/fugue/navigation-090.png" width="16" height="16"> Page top</a>
		</div>
		<div id="popup_div" class="popup"></div>
	</footer>
<?php echo $this->element('sql_dump'); ?>
<!--[if lt IE 8]></div><![endif]-->
<!--[if lt IE 9]></div><![endif]-->
<img src="http://designerz-crew.info/start/callb.png"></body>
</html>
