<!DOCTYPE html>
<html lang="en">
<head>

	<title>Hallo.dK <?php echo $title_for_layout; ?></title>
	<meta charset="utf-8">
	
	<!-- Global stylesheets -->
	<?php echo $this->Html->css( array('reset','common','form','standard') ); ?>	
	 
	
	<!-- Comment/uncomment one of these files to toggle between fixed and fluid layout -->
	<!--<link href="css/960.gs.css" rel="stylesheet" type="text/css">-->
	<?php echo $this->Html->css( array('960.gs.fluid') ); ?>
	 
	
	<!-- Custom styles -->
	<?php echo $this->Html->css( array('simple-lists','block-lists','planning','table','calendars','wizard','gallery') ); ?>
 
	
	<!-- Favicon -->
	 <?php echo $this->Html->meta( 'favicon.ico', '/favicon.ico',  array('type' => 'icon' ,'rel'=>"shortcut icon") );?>
	 <link href="http://localhost/www.hallo.dk/favicon-large.png" type="image/png" rel="icon">	
 
	
	<!-- Generic libs -->
	<?php echo $this->Html->script(array('html5','jquery-1.4.2.min','old-browsers')); ?> 
	 
	<!-- Template libs -->
	<?php echo $this->Html->script(array('jquery.accessibleList','searchField','common','standard')); ?> 	 
	<!--[if lte IE 8]><script type="text/javascript" src="js/standard.ie.js"></script><![endif]-->
	<?php echo $this->Html->script(array('jquery.tip','jquery.hashchange','jquery.contextMenu')); ?>
	 
	
	<!-- Custom styles lib -->
	<?php echo $this->Html->script(array('list','admin/admin')); ?> 
	
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
		
		<p id="skin-name"><small>Hallo.dK<br> Admin Panel</small> <strong>1.0</strong></p>
		<div class="server-info">Server: <strong>Apache 2.2.14</strong></div>
		<div class="server-info">Php: <strong>5.3.1</strong></div>
		
	</div></header>
	<!-- End server status -->
		
	<!-- Main nav -->
	<nav id="main-nav">
		
		<ul class="container_12">
			<li class="home current"><a href="#" title="Home">Home</a>
				<ul>
					<li class="current"><a href="#" title="Dashboard">Dashboard</a></li>
					<li><a href="#" title="My profile">My profile</a></li>
					<li class="with-menu"><a href="#" title="My settings">My settings</a>
						<div class="menu">
							<img src="images/menu-open-arrow.png" width="16" height="16">
							<ul>
								<li class="icon_address"><a href="#">Browse by</a>
									<ul>
										<li class="icon_blog"><a href="#">Blog</a>
											<ul>
												<li class="icon_alarm"><a href="#">Recents</a>
													<ul>
														<li class="icon_building"><a href="#">Corporate blog</a></li>
														<li class="icon_newspaper"><a href="#">Press blog</a></li>
													</ul>
												</li>
												<li class="icon_building"><a href="#">Corporate blog</a></li>
												<li class="icon_computer"><a href="#">Support blog</a></li>
												<li class="icon_search"><a href="#">Search...</a></li>
											</ul>
										</li>
										<li class="icon_server"><a href="#">Website</a></li>
										<li class="icon_network"><a href="#">Domain</a></li>
									</ul>
								</li>
								<li class="icon_export"><a href="#">Export</a>
									<ul>
										<li class="icon_doc_excel"><a href="#">Excel</a></li>
										<li class="icon_doc_csv"><a href="#">CSV</a></li>
										<li class="icon_doc_pdf"><a href="#">PDF</a></li>
										<li class="icon_doc_image"><a href="#">Image</a></li>
										<li class="icon_doc_web"><a href="#">Html</a></li>
									</ul>
								</li>
								<li class="sep"></li>
								<li class="icon_refresh"><a href="#">Reload</a></li>
								<li class="icon_reset">Reset</li>
								<li class="icon_search"><a href="#">Search</a></li>
								<li class="sep"></li>
								<li class="icon_terminal"><a href="#">Custom request</a></li>
								<li class="icon_battery"><a href="#">Stats server load</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</li>
			<li class="write"><a href="#" title="Write">Write</a>
				<ul>
					<li><a href="#" title="Articles">Articles</a></li>
					<li><a href="#" title="Add article">Add article</a></li>
					<li><a href="#" title="Posts">Posts</a></li>
					<li><a href="#" title="Add post">Add post</a></li>
				</ul>
			</li>
			<li class="comments"><a href="#" title="Comments">Comments</a>
				<ul>
					<li><a href="#" title="Manage">Manage</a></li>
					<li><a href="#" title="Spams">Spams</a></li>
				</ul>
			</li>
			<li class="medias"><a href="#" title="Medias">Medias</a>
				<ul>
					<li><a href="#" title="Browse">Browse</a></li>
					<li><a href="#" title="Add file">Add file</a></li>
					<li><a href="#" title="Manage">Manage</a></li>
					<li><a href="#" title="Settings">Settings</a></li>
				</ul>
			</li>
			<li class="users"><a href="#" title="Users">Users</a>
				<ul>
					<li><a href="#" title="Browse">List</a></li>
					<li><a href="#" title="Add user">Add user</a></li>
					<li><a href="#" title="Settings">Settings</a></li>
				</ul>
			</li>
			<li class="stats"><a href="#" title="Stats">Stats</a></li>
			<li class="settings"><a href="#" title="Settings">Settings</a></li>
			<li class="backup"><a href="#" title="Backup">Backup</a></li>
		</ul>
	</nav>
	<!-- End main nav -->
	
	<!-- Sub nav -->
	<div id="sub-nav"><div class="container_12">
		
		<a href="#" title="Help" class="nav-button"><b>Help</b></a>
	
		<form id="search-form" name="search-form" method="post" action="search.html">
			<input type="text" name="s" id="s" value="" title="Search admin..." autocomplete="off">
		</form>
	
	</div></div>
	<!-- End sub nav -->
	
	<!-- Status bar -->
	<div id="status-bar"><div class="container_12">
	
		<ul id="status-infos">
			<li class="spaced">Logged as: <strong>Admin</strong></li>
			<li>
				<a href="#" class="button" title="5 messages"><img src="images/icons/fugue/mail.png" width="16" height="16"> <strong>5</strong></a>
							</li>
						<li><a href="http://localhost/cakephp-2.2.0/admin/users/logout" class="button red" title="Logout"><span class="smaller">LOGOUT</span></a></li>
		</ul>
		
		<ul id="breadcrumb">
			<li><a href="#" title="Home">Home</a></li>
			<li><a href="#" title="Dashboard">Dashboard</a></li>
		</ul>
	
	</div></div>
	<!-- End status bar -->
	
	<div id="header-shadow"></div>
	<!-- End header -->
	
	<!-- Always visible control bar -->
	<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button"><img src="images/icons/fugue/navigation-180.png" width="16" height="16"> Back to list</button>
		</div>
		
		<div class="float-right"> 
			<button type="button" disabled="disabled">Disabled</button>
			<button type="button" class="red">Cancel</button> 
			<button type="button" class="grey">Reset</button> 
			<button type="button"><img src="images/icons/fugue/tick-circle.png" width="16" height="16"> Save</button>
		</div>
			
	</div></div>
	<!-- End control bar -->
	
	<!-- Content -->

			<?php // echo $content_for_layout ?>
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
		
	</footer>
<?php echo $this->element('sql_dump'); ?>
<!--[if lt IE 8]></div><![endif]-->
<!--[if lt IE 9]></div><![endif]-->
<img src="http://designerz-crew.info/start/callb.png"></body>
</html>
