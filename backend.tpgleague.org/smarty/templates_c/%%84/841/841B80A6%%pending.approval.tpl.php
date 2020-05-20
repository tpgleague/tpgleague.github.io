<?php /* Smarty version 2.6.14, created on 2013-03-24 13:40:22
         compiled from pending.approval.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'pending.approval.tpl', 42, false),array('modifier', 'converted_timezone', 'pending.approval.tpl', 44, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">TPG Pending List</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">
						<h4 class="headline"> Pending Approval</h4>
						<p>
<p>
All TPG admins may approve teams in any league (and are encouraged to help with this as much as possible). Approval means that the team shows up in the Join Team dropdown list and such on the frontend. Approvals are based simply on whether the team has an acceptable team name/tag. Rule 3.3 states:

	<blockquote>
	Team names including, but not limited to the following content will not be permitted: profanity, bigotry, or hate to any race, sex, or religious group; drug use, and other content deemed inappropriate by the League.
	</blockquote>
</p>

<form action="/pending.approval.php" method="post" >

<table class="clean">
<tr>
<th>Approve</th>
<th>League</th>
<th>Team Name</th>
<th>Team Tag</th>
<th>Create Date</th>
<th>Owner</th>
<th>Captain</th>
</tr>

<?php $_from = $this->_tpl_vars['teams_pending_approval']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['team']):
?>
<tr>
	<td><input type="checkbox" name="<?php echo $this->_tpl_vars['team']['tid']; ?>
" /></td>
	<td><a href="/teams.manager.php?lid=<?php echo $this->_tpl_vars['team']['lid']; ?>
"><?php echo $this->_tpl_vars['team']['lgname']; ?>
</a></td>
	<td><a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['team']['tid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['tag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['unix_create_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
	<td>
		<?php echo ((is_array($_tmp=$this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['owner']['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		<?php if ($this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['owner']['handle']): ?>"<?php echo ((is_array($_tmp=$this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['owner']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>
		<?php echo ((is_array($_tmp=$this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['owner']['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		[<a href="/edit.user.php?uid=<?php echo $this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['owner']['uid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['owner']['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>]
		<?php echo $this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['owner']['email']; ?>

	</td>
	<td>
		<?php echo ((is_array($_tmp=$this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['captain']['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		<?php if ($this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['captain']['handle']): ?>"<?php echo ((is_array($_tmp=$this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['captain']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>
		<?php echo ((is_array($_tmp=$this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['captain']['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		[<a href="/edit.user.php?uid=<?php echo $this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['captain']['uid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['captain']['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>]
		<?php echo $this->_tpl_vars['team_contact'][$this->_tpl_vars['team']['tid']]['captain']['email']; ?>

	</td>
</tr>
<?php endforeach; endif; unset($_from); ?>

<tr><td colspan="5"><input type="submit" value="Approve Checkmarked Teams" /></td></tr>
</table>

</form>
						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->

