<?php /* Smarty version 2.6.14, created on 2012-10-17 21:14:42
         compiled from league.rules.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'league.rules.tpl', 23, false),)), $this); ?>
<?php if (empty ( $this->_tpl_vars['rules'] )): ?>
<p>No rules defined for this league.</p>
<?php else: ?>

<?php if ($this->_tpl_vars['logged_in']): ?>
	<?php if (empty ( $this->_tpl_vars['rules_user_last_view'] )): ?>
	<div class="rules_notice">
	<div align="center"><img src="/images/information.gif" alt="Information" title="Information" width="16" height="16" /></div>
	This appears to be your first time viewing the rules for this league.  Please familiarize yourself with all rules thoroughly.</div>
	<?php elseif ($this->_tpl_vars['rules_user_last_view'] <= @LEAGUE_RULE_LAST_UPDATE): ?>
	<div class="rules_notice">
	<div align="center"><img src="/images/information.gif" alt="Information" title="Information" width="16" height="16" /></div>
	Some rules have changed since your last viewing. They will be highlighted below for the next 24 hours. Please make sure to familiarize yourself with these updated rules.</div>
	<?php endif;  else: ?>
	<div class="rules_notice">
	<div align="center"><img src="/images/information.gif" alt="Information" title="Information" width="16" height="16" /></div>
	Users who are logged in while viewing the rules for each league will have a special feature applied to their account: The website will track each users' last viewing of the rules.  If any rules have changed within 24 hours of the users' last viewing then they will be highlighted below, allowing you to quickly learn the new rules without having to re-read the entire rules. Simply log in to take advantage of this feature.</div>
<?php endif; ?>

<?php $_from = $this->_tpl_vars['rules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rule']):
 if ($this->_tpl_vars['rule']['depth'] == 0): ?><br /><?php endif; ?>
<div class="rule_toc" style="margin-left: <?php echo $this->_tpl_vars['rule']['depth']*10; ?>
px"><?php if ($this->_tpl_vars['logged_in'] && ! empty ( $this->_tpl_vars['rules_user_last_view'] ) && $this->_tpl_vars['rules_user_last_view'] <= $this->_tpl_vars['rule']['unix_modify_date_gmt']): ?><span class="updated_star">*</span><?php endif; ?><a href="#<?php echo $this->_tpl_vars['rule']['section']; ?>
"><?php echo $this->_tpl_vars['rule']['section']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['rule']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></div>
<?php endforeach; endif; unset($_from); ?>

<br />
<hr />

<div class="rules_body">
<?php $_from = $this->_tpl_vars['rules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rule']):
 if ($this->_tpl_vars['logged_in'] && ! empty ( $this->_tpl_vars['rules_user_last_view'] ) && $this->_tpl_vars['rules_user_last_view'] <= $this->_tpl_vars['rule']['unix_modify_date_gmt'] && ! empty ( $this->_tpl_vars['rule']['body'] )): ?>
	<?php $this->assign('rule_updated', 1);  else: ?>
	<?php $this->assign('rule_updated', 0);  endif; ?>
<div class="rule<?php if ($this->_tpl_vars['rule_updated']): ?> updated<?php endif; ?>" style="margin-left: <?php if ($this->_tpl_vars['rule']['depth'] > 0): ?>3em<?php else: ?>0<?php endif; ?>;"><h1 class="rule_header"><a name="<?php echo $this->_tpl_vars['rule']['section']; ?>
"><?php if ($this->_tpl_vars['rule_updated']): ?><img src="/images/asterisk_orange.gif" alt="*" title="Rule Changed" width="16" height="16" /> <?php endif; ?>&sect;<?php echo $this->_tpl_vars['rule']['section']; ?>
</a> <?php echo ((is_array($_tmp=$this->_tpl_vars['rule']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1>
<div>
<?php echo $this->_tpl_vars['rule']['body']; ?>

</div>
</div>
<?php endforeach; endif; unset($_from); ?>
</div>

<?php endif; ?>
