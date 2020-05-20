{if strpos($smarty.server.HTTP_USER_AGENT, 'MSIE 6.0') !== FALSE}
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?xml version="1.0" encoding="UTF-8"?>{else}<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{/if}<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>TPG Admin Backend</title>
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="start" href="/index.php" />
	<link rel="help" href="/help.php" />
{if $link_rel_previous}	<link rel="previous" href="{$link_rel_previous}" />{/if}
{if $link_rel_previous}	<link rel="next" href="{$link_rel_next}" />{/if}
	
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=PT+Sans|PT+Sans+Narrow">
    
    <link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css?v=2.1.1" media="screen" />
	<link rel="stylesheet" type="text/css" href="/styles/layout.css" />
	<link rel="stylesheet" type="text/css" href="/styles/style.css" />
	<link rel="stylesheet" type="text/css" href="/styles/forms.css" />
	<link rel="stylesheet" type="text/css" href="/styles/table.css" />
{if $external_css}
{foreach from=$external_css item=ext_css}
	<link rel="stylesheet" type="text/css" href="/styles/{$ext_css}.css" />
{/foreach}
{/if}

{if $external_js}
{foreach from=$external_js item=ext_js}
	<script src="/js/{$ext_js}.js" type="text/javascript"></script>
{/foreach}
{/if}

	<script src="/js/submit.once.js" type="text/javascript"></script>


{if $extra_head}
{foreach from=$extra_head item=eh}
	{$eh}
{/foreach}
{/if}

<!-- JS -->
		<script src="js/jquery.min.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/ender.min.js"></script>
		<script src="js/selectnav.js"></script>
		<script src="js/jquery.flexslider.js"></script>
		<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.1"></script>
		<script type="text/javascript" src="js/jquery.jcarousel.min.js"></script>
		<script type="text/javascript" src="js/jquery.tweet.js"></script>
		<script>
			jQuery(document).ready(function() {ldelim}
				/* Flex Slider Call For home page 01*/
				$('.flexslider').flexslider({ldelim}
					directionNav: false,
					pauseOnAction: false
				{rdelim});
				
				/* Jcarousel Call */	
				jQuery('#testimonials-carousel').jcarousel({ldelim}
					auto: 6,
					wrap: 'last',
					scroll: 1,
					visible:1,
					initCallback: mycarousel_initCallback
				{rdelim});
				function mycarousel_initCallback(carousel) {ldelim}
					// Disable autoscrolling if the user clicks the prev or next button.
					carousel.buttonNext.bind('click', function() {ldelim}
						carousel.startAuto(0);
					{rdelim});
					carousel.buttonPrev.bind('click', function() {ldelim}
						carousel.startAuto(0);
					{rdelim});
					// Pause autoscrolling if the user moves with the cursor over the clip.
					carousel.clip.hover(function() {ldelim}
						carousel.stopAuto();
					{rdelim}, function() {ldelim}
						carousel.startAuto();
					{rdelim});
				{rdelim};
			{rdelim});
		</script>

</head>

<body {$extra_body_attr}>


		<!-- Begin Wrapper -->
		<div id="wrapper">
			<!-- Begin Container -->
			<div class="container">
				<!-- Begin Header -->
				<div id="header">
					<!-- Begin Logo -->
					<div class="eight columns">
						<div id="logo">
							<a href="http://www.tpgleague.org/"><img src="images/logo.jpg" alt="logo"></a>
						</div>
					</div>
					<!-- End Logo -->
					

					<div class="eight columns">
						<!-- Social Icons -->
						<ul class="social-icons">
							<li>Backend</li>
													</ul>
						<div class="clear"></div>
						
					</div>

				</div>
				<!-- End Header -->  

<body>

<br />
<br />
<br />
<form action="{$smarty.server.REQUEST_URI}" method="post" id="login_form" class="login_form" onsubmit="this.submit.disabled = true; return true" >

{if $invalid_login}
<p style="color:red;">Invalid login</p>
{/if}
<fieldset>
    <legend>Login</legend>
    <label for="login_username">Username:</label><br /><input size="16" maxlength="32" name="login_username" value="{if $smarty.post.login_username}{$smarty.post.login_username}{/if}" id="login_username" type="text" />
    <br /><label for="login_password">Password:</label><br /><input name="login_password" size="16" value="" id="login_password" type="password" />
    {* <br /><label for="login_remember">Remember&nbsp;me:</label><input name="login_remember" id="login_remember" type="checkbox" {if $smarty.post.login_remember}checked{/if} /> *}
    <br /><input value="Login" type="submit" />
</fieldset>
</form>

</body>
</html>
</div>
</div>
<!-- Begin Footer -->
		<div id="footer">
				
				
			<!-- Begin Footer Bottom -->
			<div id="footer-bottom">
				<!-- Begin Container -->
					<div class="container">
					
						<div class="eight columns">
							<div id="copyright">&#169; Copyright 2013 by <span>TPG</span>. All Rights Reserved.</div>
						</div>
						
						<div class="eight columns">
							<ul class="social-icons" >
							<li><h7><h7></li>
						</ul>
						</div>
					</div>
				<!-- End Container -->
			</div>
			<!-- End Footer Bottom -->