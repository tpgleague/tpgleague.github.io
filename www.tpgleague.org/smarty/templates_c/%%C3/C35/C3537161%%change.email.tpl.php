<?php /* Smarty version 2.6.14, created on 2012-03-04 14:25:28
         compiled from change.email.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'change.email.tpl', 17, false),)), $this); ?>
<div>
<?php if ($this->_tpl_vars['success']): ?>
    Before we can change your email address, you must check your new e-mail address to obtain your new e-mail validation key. Your old email address will remain in effect until your new email is validated.
    <p>Return to the <a href="/edit.account.php">account management page</a>.</p>
<?php elseif ($this->_tpl_vars['reverted_email']): ?>
	E-mail address reverted to previously verified address.
	<p>Return to the <a href="/edit.account.php">account management page</a>.</p>
<?php else: ?>

<form <?php echo $this->_tpl_vars['change_email_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['change_email_form']['hidden']; ?>


<?php if ($this->_tpl_vars['change_email_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['change_email_form'],'id' => 'fieldset_change_email','class' => 'qffieldset','fields' => 'password, email','legend' => 'Change E-mail Address','notes_label' => 'Tip','notes' => "<p>To ensure that the validation e-mail we send to you is not marked as SPAM or blocked by your e-mail server, please add postmaster@tpgleague.org to your address book or filter whitelist.</p>"), $this);?>

<p><?php echo $this->_tpl_vars['change_email_form']['submit']['html']; ?>
</p>
</form>

<?php endif; ?>
</div>