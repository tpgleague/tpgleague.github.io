<?php /* Smarty version 2.6.14, created on 2012-03-01 17:44:12
         compiled from team.mini-panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'team.mini-panel.tpl', 4, false),array('modifier', 'easy_day', 'team.mini-panel.tpl', 12, false),array('modifier', 'easy_time', 'team.mini-panel.tpl', 12, false),)), $this); ?>
<div class="rubberbox">
<h1 class="rubberhdr"><span>Team Panel</span></h1>
	<ul>
	<li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['mp_data']['tid']; ?>
/"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['mp_data']['team_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</b></a></li>
	<li>Team Status: <?php if ($this->_tpl_vars['mp_data']['team_inactive']): ?><span style="color: red;">Inactive</span><?php else: ?>Active<?php endif; ?></li>
	<li>Roster Lock: 
		<?php if ($this->_tpl_vars['mp_team_roster_lock_status'] == 'locked'): ?>
			<span style="color: red">Locked</span>
		<?php elseif ($this->_tpl_vars['mp_team_roster_lock_status'] == 'unlocked'): ?>
			<span style="color: green">Unlocked</span>
		<?php else: ?>
			<span style="color: green"><?php echo ((is_array($_tmp=$this->_tpl_vars['mp_team_roster_lock_status'])) ? $this->_run_mod_handler('easy_day', true, $_tmp) : smarty_modifier_easy_day($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['mp_team_roster_lock_status'])) ? $this->_run_mod_handler('easy_time', true, $_tmp) : smarty_modifier_easy_time($_tmp)); ?>
</span>
		<?php endif; ?>
	</li>
	</ul>

	<ul>
	<li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['mp_data']['tid']; ?>
/">View Team Profile</a></li>
	<?php if ($this->_tpl_vars['mp_captain'] || $this->_tpl_vars['mp_owner'] || $this->_tpl_vars['mp_data']['permission_reschedule'] || $this->_tpl_vars['mp_data']['permission_report']): ?>
	<li><a href="/season.matches.php?tid=<?php echo $this->_tpl_vars['mp_data']['tid']; ?>
">Season Matches</a></li>
	<li><a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['mp_data']['tid']; ?>
">Edit Team Properties</a></li>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['mp_captain'] || $this->_tpl_vars['mp_owner']): ?>
	<li><a href="/manage.roster.php?tid=<?php echo $this->_tpl_vars['mp_data']['tid']; ?>
">Manage Team Roster</a></li>
	<?php endif; ?>
	</ul>
</div>