<?php /* Smarty version 2.6.14, created on 2012-03-04 14:25:09
         compiled from suspensions.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'suspensions.tpl', 5, false),array('modifier', 'default', 'suspensions.tpl', 7, false),array('modifier', 'simple_date', 'suspensions.tpl', 8, false),)), $this); ?>
<h2>Active Suspensions</h2>

<?php $_from = $this->_tpl_vars['activeSuspensions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['activeSuspension']):
?>
<div>
Handle: <?php echo ((is_array($_tmp=$this->_tpl_vars['activeSuspension']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <br />
<?php if ($this->_tpl_vars['activeSuspension']['team_name']): ?>Team: <a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['activeSuspension']['tid']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['activeSuspension']['team_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a> <br /><?php endif;  if ($this->_tpl_vars['activeSuspension']['gid']):  echo ((is_array($_tmp=@$this->_tpl_vars['activeSuspension']['gid_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Steam ID') : smarty_modifier_default($_tmp, 'Steam ID')); ?>
: <?php echo $this->_tpl_vars['activeSuspension']['gid']; ?>
 <br /><?php endif; ?>
Start Date: <?php echo ((is_array($_tmp=$this->_tpl_vars['activeSuspension']['start_date'])) ? $this->_run_mod_handler('simple_date', true, $_tmp) : smarty_modifier_simple_date($_tmp)); ?>
 <br />
End Date: <?php echo ((is_array($_tmp=$this->_tpl_vars['activeSuspension']['end_date'])) ? $this->_run_mod_handler('simple_date', true, $_tmp) : smarty_modifier_simple_date($_tmp)); ?>
 <br />
Rule Violation: <a href="<?php echo $this->_tpl_vars['lgname']; ?>
/rules/#<?php echo $this->_tpl_vars['activeSuspension']['rule_violation']; ?>
"><?php echo $this->_tpl_vars['activeSuspension']['rule_violation']; ?>
</a> <br />
Reason: <?php echo ((is_array($_tmp=$this->_tpl_vars['activeSuspension']['reason'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

</div>
<br />
<?php endforeach; else: ?>
<div>
No active suspensions posted.
</div>
<?php endif; unset($_from); ?>

<h2>Past Suspensions</h2>

<?php $_from = $this->_tpl_vars['inactiveSuspensions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['inactiveSuspension']):
?>
<div>
Handle: <?php echo ((is_array($_tmp=$this->_tpl_vars['inactiveSuspension']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <br />
<?php if ($this->_tpl_vars['inactiveSuspension']['team_name']): ?>Team: <a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['inactiveSuspension']['tid']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['inactiveSuspension']['team_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a> <br /><?php endif;  if ($this->_tpl_vars['inactiveSuspension']['gid']):  echo ((is_array($_tmp=@$this->_tpl_vars['inactiveSuspension']['gid_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Steam ID') : smarty_modifier_default($_tmp, 'Steam ID')); ?>
: <?php echo $this->_tpl_vars['inactiveSuspension']['gid']; ?>
 <br /><?php endif; ?>
Start Date: <?php echo ((is_array($_tmp=$this->_tpl_vars['inactiveSuspension']['start_date'])) ? $this->_run_mod_handler('simple_date', true, $_tmp) : smarty_modifier_simple_date($_tmp)); ?>
 <br />
End Date: <?php echo ((is_array($_tmp=$this->_tpl_vars['inactiveSuspension']['end_date'])) ? $this->_run_mod_handler('simple_date', true, $_tmp) : smarty_modifier_simple_date($_tmp)); ?>
 <br />
Rule Violation: <a href="<?php echo $this->_tpl_vars['lgname']; ?>
/rules/#<?php echo $this->_tpl_vars['inactiveSuspension']['rule_violation']; ?>
"><?php echo $this->_tpl_vars['inactiveSuspension']['rule_violation']; ?>
</a> <br />
Reason: <?php echo ((is_array($_tmp=$this->_tpl_vars['inactiveSuspension']['reason'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

</div>
<br />
<?php endforeach; else: ?>
<div>
No past suspensions posted.
</div>
<?php endif; unset($_from); ?>
