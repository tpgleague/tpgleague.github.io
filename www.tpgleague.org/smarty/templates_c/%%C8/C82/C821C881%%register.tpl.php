<?php /* Smarty version 2.6.14, created on 2013-04-29 23:51:21
         compiled from register.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'register.tpl', 9, false),)), $this); ?>

<form <?php echo $this->_tpl_vars['register_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['register_form']['hidden']; ?>


<?php if ($this->_tpl_vars['register_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['register_form'],'id' => 'fieldset_required','class' => 'qffieldset','fields' => 'username, password, password2, email, firstname, lastname, hide_lastname, dob','legend' => 'Required Information','notes_label' => 'Tip','notes' => "<p>To ensure that the validation e-mail we send to you is not marked as SPAM or blocked by your e-mail server, please add support@tpgleague.org to your address book or filter whitelist.</p>"), $this);?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['register_form'],'id' => 'fieldset_optional','class' => 'qffieldset','fields' => 'handle, city, state, ccode, tzid, user_comments','legend' => 'Optional Information'), $this);?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['register_form'],'id' => 'fieldset_captcha','class' => 'qffieldset','fields' => 'captcha, captcha_code','legend' => 'Image Verification'), $this);?>


<p><?php echo $this->_tpl_vars['register_form']['submit']['html']; ?>
</p>
</form>