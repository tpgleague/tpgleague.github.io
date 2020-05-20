<?php /* Smarty version 2.6.14, created on 2013-05-16 23:45:30
         compiled from join.team.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'join.team.tpl', 14, false),array('modifier', 'escape', 'join.team.tpl', 28, false),)), $this); ?>
<div>



<?php if ($this->_tpl_vars['join_team_form']): ?>
	<form <?php echo $this->_tpl_vars['join_team_form']['attributes']; ?>
>
	<?php echo $this->_tpl_vars['join_team_form']['hidden']; ?>


	<?php if ($this->_tpl_vars['join_team_form']['errors']): ?>
	<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['roster_locked']): ?><p class="error">The roster for this team is currently locked.</p><?php endif; ?>

	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['join_team_form'],'id' => 'fieldset_join_team','class' => 'qffieldset','fields' => 'note_lid, teamname, teamid, pw, gid, handle','legend' => 'Select Team','notes_label' => 'Team not listed?','notes' => "<p>If you don't see your team listed, then it is because your team has not been approved yet. You can join the team by entering the team ID, which your captain should have given you.</p>"), $this);?>


	<p><?php echo $this->_tpl_vars['join_team_form']['submit']['html']; ?>
</p>

	</form>
<?php elseif ($this->_tpl_vars['select_league_form']): ?>
	<form <?php echo $this->_tpl_vars['select_league_form']['attributes']; ?>
>
	<?php echo $this->_tpl_vars['select_league_form']['hidden']; ?>


	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['select_league_form'],'id' => 'fieldset_select_league','class' => 'qffieldset','fields' => 'select_lid','legend' => 'Select League'), $this);?>


	<p><?php echo $this->_tpl_vars['select_league_form']['submit']['html']; ?>
</p>
	</form>
<?php elseif ($this->_tpl_vars['success_team_info']): ?>
	<p>You have successfully joined team <a href="/<?php echo $this->_tpl_vars['success_team_info']['lgname']; ?>
/team/<?php echo $this->_tpl_vars['success_team_info']['tid']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['success_team_info']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>.</p>
<?php endif; ?>

</div>