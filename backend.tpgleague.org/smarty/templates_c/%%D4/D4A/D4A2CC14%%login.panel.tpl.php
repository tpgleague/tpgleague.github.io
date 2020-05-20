<?php /* Smarty version 2.6.14, created on 2013-03-24 17:15:20
         compiled from login.panel.tpl */ ?>
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
" /><?php endif;  if ($this->_tpl_vars['link_rel_previous']): ?>	<link rel="next" href="<?php echo $this->_tpl_vars['link_rel_next']; ?>
" /><?php endif; ?>
	
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=PT+Sans|PT+Sans+Narrow">
    
    <link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css?v=2.1.1" media="screen" />
	<link rel="stylesheet" type="text/css" href="/styles/layout.css" />
	<link rel="stylesheet" type="text/css" href="/styles/style.css" />
	<link rel="stylesheet" type="text/css" href="/styles/forms.css" />
	<link rel="stylesheet" type="text/css" href="/styles/table.css" />
<?php if ($this->_tpl_vars['external_css']):  $_from = $this->_tpl_vars['external_css']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ext_css']):
?>
	<link rel="stylesheet" type="text/css" href="/styles/<?php echo $this->_tpl_vars['ext_css']; ?>
.css" />
<?php endforeach; endif; unset($_from);  endif; ?>

<?php if ($this->_tpl_vars['external_js']):  $_from = $this->_tpl_vars['external_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ext_js']):
?>
	<script src="/js/<?php echo $this->_tpl_vars['ext_js']; ?>
.js" type="text/javascript"></script>
<?php endforeach; endif; unset($_from);  endif; ?>

	<script src="/js/submit.once.js" type="text/javascript"></script>


<?php if ($this->_tpl_vars['extra_head']):  $_from = $this->_tpl_vars['extra_head']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['eh']):
?>
	<?php echo $this->_tpl_vars['eh']; ?>

<?php endforeach; endif; unset($_from);  endif; ?>

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
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>
" method="post" id="login_form" class="login_form" onsubmit="this.submit.disabled = true; return true" >

<?php if ($this->_tpl_vars['invalid_login']): ?>
<p style="color:red;">Invalid login</p>
<?php endif; ?>
<fieldset>
    <legend>Login</legend>
    <label for="login_username">Username:</label><br /><input size="16" maxlength="32" name="login_username" value="<?php if ($_POST['login_username']):  echo $_POST['login_username'];  endif; ?>" id="login_username" type="text" />
    <br /><label for="login_password">Password:</label><br /><input name="login_password" size="16" value="" id="login_password" type="password" />
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