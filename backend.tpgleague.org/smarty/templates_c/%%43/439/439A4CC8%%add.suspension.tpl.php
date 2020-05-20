<?php /* Smarty version 2.6.14, created on 2012-03-01 22:43:07
         compiled from add.suspension.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'add.suspension.tpl', 10, false),)), $this); ?>
<div>

<form <?php echo $this->_tpl_vars['add_suspension_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['add_suspension_form']['hidden']; ?>


<?php if ($this->_tpl_vars['add_suspension_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['add_suspension_form'],'id' => 'fieldset_add_suspension','class' => 'qffieldset','fields' => 'uid, username, firstname, lastname, handle, reason, rule_violation, type, start, end, tid, team, mid, lid, gids','legend' => 'Add Suspension'), $this);?>

<p><?php echo $this->_tpl_vars['add_suspension_form']['submit']['html']; ?>
</p>
</form>

<?php if ($this->_tpl_vars['success']): ?>
<div style="color: blue;">Suspension Added.</div>
<br />
<?php endif; ?>

</div>