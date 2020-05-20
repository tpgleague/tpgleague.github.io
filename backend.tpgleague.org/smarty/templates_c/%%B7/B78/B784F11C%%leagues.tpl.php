<?php /* Smarty version 2.6.14, created on 2013-03-24 17:52:11
         compiled from leagues.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'leagues.tpl', 38, false),array('modifier', 'converted_timezone', 'leagues.tpl', 48, false),array('function', 'quickform_fieldset', 'leagues.tpl', 66, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">TPG League List</h2>
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

<table class="clean">
  <tr>
	<th></th>
	<th align="left">League</th>
    <th align="left">Short Name</th> 
    <th align="left">Description</th>
        <th align="left">Current Season</th>
    <th align="left">League ID</th>
    <th align="left">Active</th>
    <th align="left">Rosters Lock</th>
    <th align="left">Linked To</th>
    <th align="left">Show Rules</th>
    <th align="left">Rules Last Updated</th>
	<th align="left">Create Date</th>
  </tr>

<?php $_from = $this->_tpl_vars['leagues_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['league']):
?>
<tr>
  <td><a href="/edit.league.php?lid=<?php echo $this->_tpl_vars['league']['lid']; ?>
">Edit</a></td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['league']['league_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  <td><a href="http://www.tpgleague.org/<?php echo $this->_tpl_vars['league']['lgname']; ?>
/"><?php echo $this->_tpl_vars['league']['lgname']; ?>
</a></td> 
  <td><?php echo $this->_tpl_vars['league']['description']; ?>
</td>
    <td><?php if ($this->_tpl_vars['league']['sid'] != ''): ?><a href="http://backend.tpgleague.org/edit.schedule.php?sid=<?php echo $this->_tpl_vars['league']['sid']; ?>
">(<?php echo $this->_tpl_vars['league']['season_number']; ?>
) <?php echo $this->_tpl_vars['league']['season_title']; ?>
</a><?php endif; ?></td>
  <td><?php echo $this->_tpl_vars['league']['lid']; ?>
</td>
  <td><?php if (( $this->_tpl_vars['league']['inactive'] )): ?>Inactive<?php else: ?>Active<?php endif; ?></td>
  <td><a href="http://backend.tpgleague.org/edit.roster.lock.php?lid=<?php echo $this->_tpl_vars['league']['lid']; ?>
"><?php echo $this->_tpl_vars['league']['roster_lock']; ?>
</a></td>
  <td><a href="http://backend.tpgleague.org/edit.league.php?lid=<?php echo $this->_tpl_vars['league']['linked_lid']; ?>
"><?php echo $this->_tpl_vars['league']['linked_lid']; ?>
</a></td>
  <td><a href="http://backend.tpgleague.org/edit.rules.php?lid=<?php echo $this->_tpl_vars['league']['lid']; ?>
"><?php if (( $this->_tpl_vars['league']['show_rules'] )): ?>Yes<?php else: ?>No<?php endif; ?></a></td>
  <td><?php if ($this->_tpl_vars['league']['last_rule_update_gmt']):  echo ((is_array($_tmp=$this->_tpl_vars['league']['last_rule_update_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp));  endif; ?></td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['league']['create_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>

</table>
<br/>
<?php if ($this->_tpl_vars['add_league_form']): ?>
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Add New League</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
<form <?php echo $this->_tpl_vars['add_league_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['add_league_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['add_league_form'],'id' => 'fieldset_add_league','class' => 'qffieldset','fields' => 'league_title, lgname, gid_type, gid_name, inactive, admin, max_schedulers, max_reporters, side_one, side_two, submit'), $this);?>

</form>
<?php endif; ?>
						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->

