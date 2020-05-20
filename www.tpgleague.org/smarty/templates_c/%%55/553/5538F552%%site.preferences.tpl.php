<?php /* Smarty version 2.6.14, created on 2012-03-04 14:25:23
         compiled from site.preferences.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'site.preferences.tpl', 10, false),)), $this); ?>
<div>

<form <?php echo $this->_tpl_vars['edit_siteprefs_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_siteprefs_form']['hidden']; ?>


<?php if ($this->_tpl_vars['edit_siteprefs_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_siteprefs_form'],'id' => 'fieldset_siteprefs','class' => 'qffieldset','fields' => 'tzid','legend' => 'Edit Account Preferences','notes_label' => 'Time zones','notes' => "<p>Changing this setting will allow the website to display times to you in your local time.</p>"), $this);?>

<p><?php echo $this->_tpl_vars['edit_siteprefs_form']['submit']['html']; ?>
</p>
</form>

</div>