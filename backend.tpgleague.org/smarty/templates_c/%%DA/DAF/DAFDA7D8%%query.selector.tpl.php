<?php /* Smarty version 2.6.14, created on 2013-03-24 18:15:40
         compiled from query.selector.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'query.selector.tpl', 71, false),array('modifier', 'easy_day', 'query.selector.tpl', 73, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container2">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Query Parameters</h2>
					<div class="bold-border-bottom"></div>
				</div>
				
				
				<!-- Begin Siderbar -->
				<div class="four columns">


	<p>
<form method="get" action="/query.selector.php">
<input type="hidden" name="lid" value="<?php echo @LID; ?>
" />
<select name="query">
    <option value="joined_roster_in_last_month" <?php if ($_GET['query'] == 'joined_roster_in_last_month'): ?>selected="selected"<?php endif; ?>>Players who joined a roster in the last month</option>
    <option value="left_roster_in_last_month" <?php if ($_GET['query'] == 'left_roster_in_last_month'): ?>selected="selected"<?php endif; ?>>Players who left a roster in the last month</option>
	<option value="captains_inactive" <?php if ($_GET['query'] == 'captains_inactive'): ?>selected="selected"<?php endif; ?>>Captains from inactive teams</option>
	<option value="captains_division_unassigned" <?php if ($_GET['query'] == 'captains_division_unassigned'): ?>selected="selected"<?php endif; ?>>Captains from unassigned division</option>
	<option value="captains_active" <?php if ($_GET['query'] == 'captains_active'): ?>selected="selected"<?php endif; ?>>Captains from active+approved+assigned teams</option>
	<option value="captains_active_no_forfeits" <?php if ($_GET['query'] == 'captains_active_no_forfeits'): ?>selected="selected"<?php endif; ?>>Captains from active teams no forfeit losses (matches played > 0)</option>
	<option value="teams_no_captains" <?php if ($_GET['query'] == 'teams_no_captains'): ?>selected="selected"<?php endif; ?>>Team members having no captain</option>
	<option value="players_in_unassigned_group" <?php if ($_GET['query'] == 'players_in_unassigned_group'): ?>selected="selected"<?php endif; ?>>Players on teams who are not in a group</option>
</select>
<br /><input type="submit" value="Run Query" />
</form>
							</p>

							
					
				</div>
				<!-- End Sidebar -->
<br />	<br />				
				<!-- Begin Posts -->
				<div class="twelve columns">
					<!-- Post with image -->
					<div class="post post-page">

					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Query Results</h2>
					<div class="bold-border-bottom"></div>
						<div class="clear"></div>
						<div class="post-description">
						
						<p>
<?php if (isset ( $this->_tpl_vars['qs_results'] )): ?>


<table class="clean">
<tr>
	<th>Team Name</th>
	<th>Team Tag</th>
	<th>Team Created</th>
	<th>Org IRC</th>
	<th>Roster Size</th>
	<th>Approved</td>
	<th>Active</td>
	<th>Division</th>
	<th>Conference</th>
	<th>Group</th>
	<th>Username</th>
	<th>E-mail</th>
	<th>First Name</th>
	<th>Last Name</th>
	<th>Roster Handle</th>
</tr>
<?php $_from = $this->_tpl_vars['qs_results']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['result']):
?>
<tr>
<td><a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['result']['tid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['tag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['unix_create_date_gmt'])) ? $this->_run_mod_handler('easy_day', true, $_tmp) : smarty_modifier_easy_day($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['irc'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo $this->_tpl_vars['result']['roster_count']; ?>
</td>
<td><?php if ($this->_tpl_vars['result']['approved']): ?>Y<?php else: ?>-<?php endif; ?></td>
<td><?php if ($this->_tpl_vars['result']['inactive']): ?>I<?php else: ?>A<?php endif; ?></td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['division_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['conference_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['group_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><a href="/edit.user.php?uid=<?php echo $this->_tpl_vars['result']['uid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
</tr>
<?php endforeach; else: ?>
<tr>
<td colspan="14">No results to display.</td>
</tr>
<?php endif; unset($_from); ?>


<?php endif; ?>
</table>
							</p>
							
						</div>
					</div>
				</div>
</div>

			</div>





