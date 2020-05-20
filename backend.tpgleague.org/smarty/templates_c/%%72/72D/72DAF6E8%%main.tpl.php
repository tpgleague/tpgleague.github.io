<?php /* Smarty version 2.6.14, created on 2013-03-28 12:35:59
         compiled from main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'main.tpl', 170, false),array('modifier', 'truncate', 'main.tpl', 170, false),)), $this); ?>


<?php if (strpos ( $_SERVER['HTTP_USER_AGENT'] , 'MSIE 6.0' ) !== FALSE): ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo '<?xml'; ?>
 version="1.0" encoding="UTF-8"<?php echo '?>';  else:  echo '<?xml'; ?>
 version="1.0" encoding="UTF-8"<?php echo '?>'; ?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php endif; ?><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>TPG Admin Backend</title>
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="start" href="/index.php" />
	<link rel="help" href="/help.php" />
<?php if ($this->_tpl_vars['link_rel_previous']): ?>	<link rel="previous" href="<?php echo $this->_tpl_vars['link_rel_previous']; ?>
" /><?php endif; ?>
<?php if ($this->_tpl_vars['link_rel_previous']): ?>	<link rel="next" href="<?php echo $this->_tpl_vars['link_rel_next']; ?>
" /><?php endif; ?>
	
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=PT+Sans|PT+Sans+Narrow">
    
    <link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css?v=2.1.1" media="screen" />
	<link rel="stylesheet" type="text/css" href="/styles/layout.css" />
	<link rel="stylesheet" type="text/css" href="/styles/style.css" />
	<link rel="stylesheet" type="text/css" href="/styles/forms.css" />
	<link rel="stylesheet" type="text/css" href="/styles/table.css" />
<?php if ($this->_tpl_vars['external_css']): ?>
<?php $_from = $this->_tpl_vars['external_css']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ext_css']):
?>
	<link rel="stylesheet" type="text/css" href="/styles/<?php echo $this->_tpl_vars['ext_css']; ?>
.css" />
<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['external_js']): ?>
<?php $_from = $this->_tpl_vars['external_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ext_js']):
?>
	<script src="/js/<?php echo $this->_tpl_vars['ext_js']; ?>
.js" type="text/javascript"></script>
<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>

	<script src="/js/submit.once.js" type="text/javascript"></script>


<?php if ($this->_tpl_vars['extra_head']): ?>
<?php $_from = $this->_tpl_vars['extra_head']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['eh']):
?>
	<?php echo $this->_tpl_vars['eh']; ?>

<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>

<!-- JS -->

		<script src="js/custom.js"></script>
		<script src="js/ender.min.js"></script>
		<script src="js/selectnav.js"></script>
		<script src="js/jquery.flexslider.js"></script>
		<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.1"></script>
		<script type="text/javascript" src="js/jquery.jcarousel.min.js"></script>
		<script type="text/javascript" src="js/jquery.tweet.js"></script>
		<script>
			jQuery(document).ready(function() {
				/* Flex Slider Call For home page 01*/
				$('.flexslider').flexslider({
					directionNav: false,
					pauseOnAction: false
				});
				
				/* Jcarousel Call */	
				jQuery('#testimonials-carousel').jcarousel({
					auto: 6,
					wrap: 'last',
					scroll: 1,
					visible:1,
					initCallback: mycarousel_initCallback
				});
				function mycarousel_initCallback(carousel) {
					// Disable autoscrolling if the user clicks the prev or next button.
					carousel.buttonNext.bind('click', function() {
						carousel.startAuto(0);
					});
					carousel.buttonPrev.bind('click', function() {
						carousel.startAuto(0);
					});
					// Pause autoscrolling if the user moves with the cursor over the clip.
					carousel.clip.hover(function() {
						carousel.stopAuto();
					}, function() {
						carousel.startAuto();
					});
				};
			});
		</script>

</head>

<body <?php echo $this->_tpl_vars['extra_body_attr']; ?>
>


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
									<?php if (@SUPERADMIN): ?><li><a href="/admins.action.log.php" style="color: black">Admin Logs</a></li><?php endif; ?>
								</ul>
							</li>
							<li><a href="/news.php" style="color: black">News</a>

							</li>
							<li><a href="/member.search.php" style="color: black">Members</a></li>
							<li><a href="/leagues.php" style="color: black">Leagues</a></li>
 							<?php if (@SUPERADMIN): ?><li><a href="/suspensions.php" style="color: black">Suspensions</a></li><?php endif; ?>							
 							<li><a href="/pending.approval.php" style="color: black">Pending <span style="color: <?php if ($this->_tpl_vars['teams_pending_approval_count']): ?>red<?php else: ?>blue<?php endif; ?>;">(<?php echo $this->_tpl_vars['teams_pending_approval_count']; ?>
)</span></a></li>
							
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
Recent Places: <?php $_from = $this->_tpl_vars['page_history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['pages_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['pages_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['page']):
        $this->_foreach['pages_loop']['iteration']++;
?>
      <a href="<?php echo $this->_tpl_vars['page']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="text-decoration: none;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 40, '...') : smarty_modifier_truncate($_tmp, 40, '...')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a> <?php if (! ($this->_foreach['pages_loop']['iteration'] == $this->_foreach['pages_loop']['total'])): ?> » <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</div>

<br />
</div>




