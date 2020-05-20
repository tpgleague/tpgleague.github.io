<?php /* Smarty version 2.6.14, created on 2013-03-21 22:06:34
         compiled from main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'main.tpl', 9, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo '<?xml'; ?>
 version="1.0" encoding="UTF-8"<?php echo '?>'; ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>TPG League<?php if ($this->_tpl_vars['title']): ?> - <?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?></title>
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="start" href="/" />
	<link rel="help" href="/help/" />
<?php if ($this->_tpl_vars['link_rel_previous']): ?>	<link rel="previous" href="<?php echo $this->_tpl_vars['link_rel_previous']; ?>
" /><?php endif; ?>
<?php if ($this->_tpl_vars['link_rel_previous']): ?>	<link rel="next" href="<?php echo $this->_tpl_vars['link_rel_next']; ?>
" /><?php endif; ?>
	<link rel="stylesheet" type="text/css" href="/styles/layout.css" />
	<link rel="stylesheet" type="text/css" href="/styles/style.css" />
	<link rel="stylesheet" type="text/css" href="/styles/boxes.css" />
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

<!--[if IE]>
<style type="text/css">
div#league_selector {
  float: right;
}
</style>
<![endif]-->

	<script src="/js/submit.once.js" type="text/javascript"></script>

<?php if ($this->_tpl_vars['extra_head']): ?>
<?php $_from = $this->_tpl_vars['extra_head']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['eh']):
?>
	<?php echo $this->_tpl_vars['eh']; ?>

<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>

</head>

<body <?php if ($this->_tpl_vars['onload']): ?>onload="<?php echo $this->_tpl_vars['onload']; ?>
"<?php endif; ?>>

<div id="wrapper">
	<div id="header-dod6">
		<div id="top-nav">
			<?php if ($this->_tpl_vars['lgname'] == '/dod3'): ?><div id="tpg_logo"><a href="/"><img src="/images/tpg3v3halloween.gif" alt="TPG" title="TPG League" border="0" /></a></div>
			<?php elseif ($this->_tpl_vars['lgname'] == '/dod6' || $this->_tpl_vars['lgname'] == '/draft' || $this->_tpl_vars['lgname'] == '/NightCup' || $this->_tpl_vars['lgname'] == '/euro6v6' || $this->_tpl_vars['lgname'] == '/classic' || $this->_tpl_vars['lgname'] == '/regions' || $this->_tpl_vars['lgname'] == '/tpg2'): ?><div id="tpg_logo"><a href="/"><img src="/images/Classic-TPG-6v6-Banner.gif" alt="TPG" title="TPG League" border="0" /></a></div>
			<?php elseif ($this->_tpl_vars['lgname'] == '/dods6' || $this->_tpl_vars['lgname'] == '/cup'): ?><div id="tpg_logo"><a href="/"><img src="/images/Source-TPG-6v6-Banner.jpg" alt="TPG" title="TPG League" border="0" /></a></div>
            <?php elseif ($this->_tpl_vars['lgname'] == '/csgo' || $this->_tpl_vars['lgname'] == '/csgodem'): ?><div id="tpg_logo"><a href="/"><img src="/images/TPGcsgoBanner.gif" alt="TPG" title="TPG League" border="0" /></a></div>
			<?php else: ?><div id="tpg_logo"><a href="/"><img src="/images/TPGgeneral.gif" alt="TPG" title="TPG League" border="0" /></a></div>
			<?php endif; ?>
			<div id="league_selector"><?php echo $this->_tpl_vars['league_selector']; ?>
</div>
		</div>
	</div>

	<div id="menu">
		<div class="rubberbox">
		<h1 class="rubberhdr"><span>League Info</span></h1>
			<ul>
			<li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/news/">News</a></li>
			<?php if (! $this->_tpl_vars['logged_in']): ?><li><a href="/register/">Join TPG</a></li><?php endif; ?>
			<li><a href="http://www.tpgleague.org/application.php">Admin Application</a></li>
			<li><a href="http://support.tpgleague.org/ticket/">Support Tickets</a></li>
            <li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/membersearch/">Member Lookup</a></li>
			</ul>
			<?php if ($this->_tpl_vars['lgname']): ?>
			<ul>
			<?php if ($this->_tpl_vars['show_rules']): ?>
            <li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/rules/">League Rules<?php if ($this->_tpl_vars['new_rules']): ?><img src="/images/new.gif" border="0" align="bottom" width="16" height="7" alt="New" title="New Rules" /><?php endif; ?></a></li>         
            <?php endif; ?>
            <li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/teams/">Team List</a></li>
            <li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/maps/">Maps</a></li>
			<?php if ($this->_tpl_vars['map_pack_url']): ?><li><a href="<?php echo $this->_tpl_vars['map_pack_url']; ?>
">Map Pack Download</a></li><?php endif; ?>
			<?php if ($this->_tpl_vars['config_pack_url']): ?><li><a href="<?php echo $this->_tpl_vars['config_pack_url']; ?>
">Server Configs</a></li><?php endif; ?>
            <?php if ($this->_tpl_vars['lgname'] == '/dod6' || $this->_tpl_vars['lgname'] == '/draft' || $this->_tpl_vars['lgname'] == '/NightCup' || $this->_tpl_vars['lgname'] == '/euro6v6' || $this->_tpl_vars['lgname'] == '/classic' || $this->_tpl_vars['lgname'] == '/regions' || $this->_tpl_vars['lgname'] == '/tpg2' || $this->_tpl_vars['lgname'] == '/dod3'): ?>
            <li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/approvedfiles/">Approved Files</a></li><?php endif; ?>
			
			<li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/suspensions/">Suspensions</a></li>
			<li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/pastchamps/">Past Champions</a></li>
            <?php if ($this->_tpl_vars['lgname'] == '/dod6' || $this->_tpl_vars['lgname'] == '/dod3' || $this->_tpl_vars['lgname'] == '/draft' || $this->_tpl_vars['lgname'] == '/NightCup' || $this->_tpl_vars['lgname'] == '/euro6v6' || $this->_tpl_vars['lgname'] == '/classic' || $this->_tpl_vars['lgname'] == '/regions' || $this->_tpl_vars['lgname'] == '/tpg2'): ?><li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/links/">Links</a></li><?php endif; ?>
			</ul>

			<ul>
			<li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/schedule/">Schedule</a></li>
			<li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/standings/">Standings</a></li>
			</ul>
			<?php endif; ?>
		</div>
		<?php if ($this->_tpl_vars['team_mini_panel']): ?>
			<?php echo $this->_tpl_vars['team_mini_panel']; ?>

		<?php endif; ?>

		<?php if ($this->_tpl_vars['league_admins_panel']): ?>
			<?php echo $this->_tpl_vars['league_admins_panel']; ?>

		<?php endif; ?>

        <?php if (! $this->_tpl_vars['lgname']): ?>
        <div class="rubberbox" id="inactiveleagues">
        <h1 class="rubberhdr"><span>Inactive Leagues</span></h1>
        <ul>
        <li><a href="/dod3/">Day of Defeat 3v3</a></li>
        <li><a href="/dods6/">DOD: Source 6v6</a></li>
        <li><a href="/dods3/">DOD: Source 3v3</a></li>
        <li><a href="/NightCup/">TPG Night Cup (DOD)</a></li>
        </ul>
        </div>
        <?php endif; ?>
        
		<?php echo $this->_tpl_vars['login_cp']; ?>


        <?php if ($this->_tpl_vars['lgname'] == '/dod6' || $this->_tpl_vars['lgname'] == '/dod3' || $this->_tpl_vars['lgname'] == '/draft' || $this->_tpl_vars['lgname'] == '/NightCup' || $this->_tpl_vars['lgname'] == '/euro6v6' || $this->_tpl_vars['lgname'] == '/classic' || $this->_tpl_vars['lgname'] == '/regions' || $this->_tpl_vars['lgname'] == '/tpg2'): ?>
		<div class="rubberbox" id="affiliates">
		<h1 class="rubberhdr"><span>Community</span></h1>
		<a href="/affil/3/"><img src="/images/affils/1911-small.jpg" border="0" alt="nineteeneleven.org" /></a>
		</div>
        <?php endif; ?>
	</div>

	<?php if ($this->_tpl_vars['display_standings']): ?>
		<?php $this->assign('content_width', 'standings'); ?>
	<?php else: ?>
		<?php $this->assign('content_width', 'self'); ?>
	<?php endif; ?>
	<div id="content" class="content_<?php echo $this->_tpl_vars['content_width']; ?>
">
		<h1 class="rubberhdr"><span><?php echo $this->_tpl_vars['title']; ?>
</span></h1>
		<div id="main-content">
		<?php echo $this->_tpl_vars['main_content']; ?>

		</div>
	</div>

	<div id="sub-section">
		<?php echo $this->_tpl_vars['standings']; ?>

	</div>

	<div id="footer" style="text-align: center;">

	</div>

</div>
</body>
</html>