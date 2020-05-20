<?php /* Smarty version 2.6.14, created on 2012-04-05 19:43:30
         compiled from enter.email.key.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'enter.email.key.tpl', 10, false),)), $this); ?>
<div>

<form <?php echo $this->_tpl_vars['enter_key_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['enter_key_form']['hidden']; ?>


<?php if ($this->_tpl_vars['enter_key_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['enter_key_form'],'id' => 'fieldset_enter_key','class' => 'qffieldset','fields' => 'key','legend' => 'Enter E-mail Validation Key','notes_label' => 'Tip','notes' => '<p>If you do not see a registration confirmation e-mail from TPG in your inbox, try checking your bulk mail or SPAM folder. If you still can\'t find it, please visit the <a href="/edit.account.php?actedit=resendemail">validation e-mail re-request page</a>.</p>'), $this);?>

<p><?php echo $this->_tpl_vars['enter_key_form']['submit']['html']; ?>
</p>
</form>

</div>