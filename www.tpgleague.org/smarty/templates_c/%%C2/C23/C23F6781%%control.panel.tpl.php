<?php /* Smarty version 2.6.14, created on 2013-05-16 23:45:30
         compiled from control.panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'control.panel.tpl', 3, false),)), $this); ?>
<div class="rubberbox">
<h1 class="rubberhdr"><span>Control Panel</span></h1>
	<p style="font-style: italic">Welcome, <?php echo ((is_array($_tmp=$this->_tpl_vars['cp_firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</p>
	<ul>
	<li><a href="/edit.account.php">Account Management</a></li>
	<li><a href="/create.org.php">Create Organization</a></li>
	<?php if ($this->_tpl_vars['player_manages_orgs']): ?><li><a href="/manage.org.php">Manage Organizations</a></li><?php endif; ?>
	<?php if ($this->_tpl_vars['lgname']): ?><li><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/join/">Join Team</a></li><?php else: ?><li><a href="/join.team.php">Join Team</a></li><?php endif; ?>
	<?php if ($this->_tpl_vars['player_on_teams']): ?><li><a href="/my.teams.php">My Teams</a></li><?php endif; ?>
	<li><a href="<?php echo $this->_tpl_vars['logout_URL']; ?>
">Logout</a></li>
	</ul>
</div>