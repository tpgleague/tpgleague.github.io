<?php /* Smarty version 2.6.14, created on 2012-10-24 23:04:06
         compiled from edit.details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.details.tpl', 10, false),)), $this); ?>
<div>

<form <?php echo $this->_tpl_vars['edit_details_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_details_form']['hidden']; ?>


<?php if ($this->_tpl_vars['edit_details_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_details_form'],'id' => 'fieldset_edit_details','class' => 'qffieldset','fields' => 'firstname, lastname, hide_lastname, handle, steam_profile_url, user_avatar_url, city, state, ccode, user_comments','legend' => 'Edit Account Details'), $this);?>

<p><?php echo $this->_tpl_vars['edit_details_form']['submit']['html']; ?>
</p>
</form>

</div>