
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
							<a href="http://www.tpgleague.org/"><img src="images/logo2.gif" border="none" alt="logo"></a>
						</div>
					</div>
					<!-- End Logo -->
					



				</div>
				<!-- End Header -->  
<div>
	{$error}
</div>
</div>
