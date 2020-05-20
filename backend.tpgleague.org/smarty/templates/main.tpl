

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
					

					<div class="eight columns">
						<!-- Social Icons -->
						<ul class="social-icons" >
							<li><a href="/?logout"><img src="images/buttons/logout.gif" border="none"></a></li>
													</ul>
						<div class="clear"></div>
						
					</div>

				</div>
				<!-- End Header -->  
	<!-- Begin Navigation -->
				<div class="sixteen columns"/>
					<div id="navigation"/>
						<ul id="nav">

							<li><a href="http://www.tpgleague.org/" style="color: black">Home</a></li>
							<li><a href="/admins.php" style="color: black">Admins</a>
								<ul>
									<li><a href="admins.php" style="color: black">Admin List</a></li>
									{if $smarty.const.SUPERADMIN}<li><a href="/admins.action.log.php" style="color: black">Admin Logs</a></li>{/if}
								</ul>
							</li>
							<li><a href="/news.php" style="color: black">News</a>

							</li>
							<li><a href="/member.search.php" style="color: black">Members</a></li>
							<li><a href="/leagues.php" style="color: black">Leagues</a></li>
 							{if $smarty.const.SUPERADMIN}<li><a href="/suspensions.php" style="color: black">Suspensions</a></li>{/if}							
 							<li><a href="/pending.approval.php" style="color: black">Pending <span style="color: {if $teams_pending_approval_count}red{else}blue{/if};">({$teams_pending_approval_count})</span></a></li>
							
							<li><a href="#" style="color: black">Links</a>	
								<ul>						
									<li><a href="http://support.tpgleague.org/ticket/admin.login.php" style="color: black">Support Tickets</a></li>
									<li><a href="http://mail.tpgleague.org:8000/" style="color: black">Webmail</a></li>
									<li><a href="http://www.billkamm.net/sp3c/configmaking.html" style="color: black">Config Making</a></li>

								</ul>
							</li>
					

					</div>
					<div class="clear"></div>
					
				</div>
				<!-- End Navigation -->
			</div>
			<!-- End Container -->                
                
<div id="midsection">
<br />
<div>
Recent Places: {foreach from=$page_history item='page' name='pages_loop'}
      <a href="{$page}" title="{$page|escape}" style="text-decoration: none;">{$page|truncate:40:'...'|escape}</a> {if !$smarty.foreach.pages_loop.last} » {/if}
{/foreach}
</div>

<br />
</div>





