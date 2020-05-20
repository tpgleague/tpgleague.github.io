<?php /* Smarty version 2.6.14, created on 2012-03-01 22:43:40
         compiled from edit.roster.lock.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'capitalize', 'edit.roster.lock.tpl', 2, false),)), $this); ?>
<div>
Current Status: <?php echo ((is_array($_tmp=$this->_tpl_vars['roster_lock'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>

</div>

<form method="post" action="/edit.roster.lock.php?lid=<?php echo @LID; ?>
">

	<select name="roster_lock">
		<option value="auto" <?php if ($this->_tpl_vars['roster_lock'] == 'auto'): ?>selected="selected"<?php endif; ?>>Auto</option>
		<option value="unlocked" <?php if ($this->_tpl_vars['roster_lock'] == 'unlocked'): ?>selected="selected"<?php endif; ?>>Unlocked</option>
		<option value="locked" <?php if ($this->_tpl_vars['roster_lock'] == 'locked'): ?>selected="selected"<?php endif; ?>>Locked</option>
	</select>

	<br />
	<input type="submit" value="Save" />

</form>