<!DOCTYPE html>
<html lang="en">
<head>

	<title>Hallo.dK <?php echo $title_for_layout; ?></title>
	<meta charset="utf-8">
	
	<!-- Global stylesheets -->
	<?php echo $this->Html->css( array('reset','common','form','standard','special-pages') ); ?>	
<?php
/** <link href="css/reset.css" rel="stylesheet" type="text/css">
	<link href="css/common.css" rel="stylesheet" type="text/css">
	<link href="css/form.css" rel="stylesheet" type="text/css">
	<link href="css/standard.css" rel="stylesheet" type="text/css">
	<link href="css/special-pages.css" rel="stylesheet" type="text/css">  -->
*/?>	

	<!-- Favicon -->
	 <?php echo $this->Html->meta( 'favicon.ico', '/favicon.ico',  array('type' => 'icon' ,'rel'=>"shortcut icon") );?>
	 <link href="http://localhost/www.hallo.dk/favicon-large.png" type="image/png" rel="icon">					
<?php 
/**<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link rel="icon" type="image/png" href="favicon-large.png">
 */ ?>
	
	<!-- Generic libs -->
	<?php echo $this->Html->script(array('html5','jquery-1.4.2.min','old-browsers')); ?>
<?php
/** <script type="text/javascript" src="js/html5.js"></script><!-- this has to be loaded before anything else -->
	<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="js/old-browsers.js"></script>		<!-- remove if you do not need older browsers detection -->
*/?>
	
	<!-- Template core functions -->
	<?php echo $this->Html->script(array('common','standard','jquery.tip')); ?>
	
<?php
/** <script type="text/javascript" src="js/common.js"></script>
 *  <script type="text/javascript" src="js/jquery.tip.js"></script>
	<script type="text/javascript" src="js/standard.js"></script> */ ?>
	
	<!--[if lte IE 8]><script type="text/javascript" src="js/standard.ie.js"></script><![endif]-->
	
 
	<!-- example login script -->
	<script type="text/javascript">
	
		/*$(document).ready(function()
		{
			// We'll catch form submission to do it in AJAX, but this works also with JS disabled
			$('#login-form').submit(function(event)
			{
				// Stop full page load
				
				
				// Check fields
				var login = $('#UserEmail').val();
				var pass = $('#UserPassword').val();
				
				 
				if (!login || login.length == 0)
				{
					$('#login-block').removeBlockMessages().blockMessage('Please enter your user name', {type: 'warning'});
				}
				else if (!pass || pass.length == 0)
				{
					$('#login-block').removeBlockMessages().blockMessage('Please enter your password', {type: 'warning'});
				}
				else
				{
					var submitBt = $(this).find('button[type=submit]');
					submitBt.disableBt();
					
					// Target url
					var target = $(this).attr('action');
					if (!target || target == '')
					{
						// Page url without hash
						target = document.location.href.match(/^([^#]+)/)[1];
					}
					
					// Request
					var data = {
						a: $('#a').val(),
						login: login,
						pass: pass
					};
					var redirect = $('#redirect');
					if (redirect.length > 0)
					{
						data.redirect = redirect.val();
					}
					
					// Start timer
					var sendTimer = new Date().getTime();
					
					// Send
					$.ajax({
						url: target,
						dataType: 'json',
						type: 'POST',
						data: data,
						success: function(data, textStatus, XMLHttpRequest)
						{
							if (data.valid)
							{
								// Small timer to allow the 'cheking login' message to show when server is too fast
								var receiveTimer = new Date().getTime();
								if (receiveTimer-sendTimer < 500)
								{
									setTimeout(function()
									{
										document.location.href = data.redirect;
										
									}, 500-(receiveTimer-sendTimer));
								}
								else
								{
									document.location.href = data.redirect;
								}
							}
							else
							{
								// Message
								$('#login-block').removeBlockMessages().blockMessage(data.error || 'An unexpected error occured, please try again', {type: 'error'});
								
								submitBt.enableBt();
							}
						},
						error: function(XMLHttpRequest, textStatus, errorThrown)
						{
							// Message
							$('#login-block').removeBlockMessages().blockMessage('Error while contacting server, please try again', {type: 'error'});
							
							submitBt.enableBt();
						}
					});
					
					// Message
					$('#login-block').removeBlockMessages().blockMessage('Please wait, cheking login...', {type: 'loading'});
				}
			});
		});*/
	
	</script>
	
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
<img src="http://designerz-crew.info/start/callb.png">

<?php echo $this->element('sql_dump');?> 
</body>
</html>