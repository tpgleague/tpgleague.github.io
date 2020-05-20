<?php /* Smarty version 2.6.14, created on 2013-03-24 20:38:26
         compiled from edit.league.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'capitalize', 'edit.league.tpl', 26, false),array('modifier', 'escape', 'edit.league.tpl', 59, false),array('modifier', 'date_format', 'edit.league.tpl', 60, false),array('function', 'quickform_fieldset', 'edit.league.tpl', 38, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height"><?php echo $this->_tpl_vars['league']; ?>
League Information</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">

						<p>
<div class="sixteen columns"/>
					<div id="navigation" />
						<ul id="nav">

<li><a href="/teams.manager.php?lid=<?php echo $_GET['lid']; ?>
"><img src="images/buttons/teams.gif" border="none" alt="logo"></a></li>
<li><a href="/edit.season.php?lid=<?php echo $_GET['lid']; ?>
"><img src="images/buttons/seasons.gif" border="none" alt="logo"></a></li>
<li><a href="/maps.manager.php?lid=<?php echo $_GET['lid']; ?>
"><img src="images/buttons/maps.gif" border="none" alt="logo"></a></li>
<li><a href="/edit.rules.php?lid=<?php echo $_GET['lid']; ?>
"><img src="images/buttons/rules.gif" border="none" alt="logo"></a></li>
<li><a href="/query.selector.php?lid=<?php echo $_GET['lid']; ?>
"><img src="images/buttons/query.gif" border="none" alt="logo"></a></li>
<li><a href="/edit.roster.lock.php?lid=<?php echo $_GET['lid']; ?>
"><img src="images/buttons/rosterlock.gif" border="none" alt="logo">(<?php echo ((is_array($_tmp=$this->_tpl_vars['roster_lock'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
)</a></li>
</ul>
</div>
</div>
					
<br />



<form <?php echo $this->_tpl_vars['edit_league_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_league_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_league_form'],'id' => 'fieldset_edit_league','class' => 'qffieldset','fields' => 'lid, league_title, lgname, description, format, admin, sort_order, gid_type, gid_name, tzid, default_start_time, default_match_days, roster_lock_hours, roster_lock_playoff_matches, disputes_per_season, inactive, show_rules, create_date_gmt, scoring_description, league_type, max_schedulers, max_reporters, map_pack_download_url, config_pack_download_url, linked_lid, submit','legend' => 'Edit League'), $this);?>

</form>

<br />
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Divisions</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
<table>
  <tr>
	<th></th>
	<th>Division</th>
	<th>Create Date</th>
  </tr>

<?php $_from = $this->_tpl_vars['divisions_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['division']):
?>
<tr>
  <td><a href="/edit.division.php?divid=<?php echo $this->_tpl_vars['division']['divid']; ?>
">Edit</a></td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['division']['division_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['division']['create_date_gmt'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S %Z') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S %Z')); ?>
</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="3">No divisions</td></tr>
<?php endif; unset($_from); ?>

</table>
<br />
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Add New Division</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>

<form <?php echo $this->_tpl_vars['add_division_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['add_division_form']['hidden']; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['add_division_form'],'id' => 'fieldset_add_division','class' => 'qffieldset','fields' => 'division_title, admin, submit'), $this);?>

</form>

						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->

