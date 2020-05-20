<?php /* Smarty version 2.6.14, created on 2012-03-04 14:25:26
         compiled from change.password.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'change.password.tpl', 10, false),)), $this); ?>
<div>

<form <?php echo $this->_tpl_vars['edit_password_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_password_form']['hidden']; ?>


<?php if ($this->_tpl_vars['edit_password_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_password_form'],'id' => 'fieldset_change_password','class' => 'qffieldset','fields' => 'passwordold, password, password2','legend' => 'Edit Account Password'), $this);?>

<p><?php echo $this->_tpl_vars['edit_password_form']['submit']['html']; ?>
</p>
</form>


</div>