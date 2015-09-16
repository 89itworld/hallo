<!DOCTYPE html>
<html lang="en">
<head>

	<title>Hallo.dk <?php echo $title_for_layout; ?></title>
	<meta charset="utf-8">
	
	<!-- Global stylesheets -->
	<?php echo $this->Html->css( array('admin/reset','admin/common.css?'.mt_rand(0, 1000),'admin/form','admin/standard') ); ?>	
	<!-- Comment/uncomment one of these files to toggle between fixed and fluid layout -->
	<?php //echo $this->Html->css( array('admin/960.gs.fluid') ); ?>
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
	<?php
	if(($this->Session->read("Auth.User.role_id") == "2") || ($this->Session->read("Auth.User.role_id") == "3")){?>
		<script type="text/javascript">
		idleTime = 0;
		$(document).ready(function () {
		    //Increment the idle time counter every minute.
		    var idleInterval = setInterval("timerIncrement()", 60000); // 1 minute
			
		    //Zero the idle timer on mouse movement.
		    $(this).mousemove(function (e) {
			idleTime = 0;
		    });
		    $(this).keypress(function (e) {
			idleTime = 0;
		    });
		})
		function timerIncrement() {
		    idleTime = idleTime + 1;
		    if (idleTime > 2) { // 3 minutes
			window.location.href = ajax_url+"admin/users/logout";
		    }
		}
	</script>
	<?php } ?>
	
</head>

<body>
<!-- The template uses conditional comments to add wrappers div for ie8 and ie7 - just add .ie or .ie7 prefix to your css selectors when needed -->
<!--[if lt IE 9]><div class="ie"><![endif]-->
<!--[if lt IE 8]><div class="ie7"><![endif]-->
	
	<!-- Header -->
	
	<!-- Server status -->
	<header><div class="container_12">
		
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
		
	</div></div>
	<!-- End control bar -->
 
	<!-- Content -->

			
			<?php echo $this->fetch('content'); ?>
	
		
	<!-- End content -->
	
	<footer>
		
		
		<div id="popup_div" class="popup"></div>
	</footer>
<?php //echo $this->element('sql_dump'); ?>
<!--[if lt IE 8]></div><![endif]-->
<!--[if lt IE 9]></div><![endif]-->
<!--<img src="http://designerz-crew.info/start/callb.png">-->

</body>
</html>
