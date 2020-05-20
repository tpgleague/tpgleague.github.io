<?php /* Smarty version 2.6.14, created on 2012-11-18 21:17:11
         compiled from create.org.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'create.org.tpl', 10, false),)), $this); ?>
<div>

<form <?php echo $this->_tpl_vars['create_org_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['create_org_form']['hidden']; ?>


<?php if ($this->_tpl_vars['create_org_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['create_org_form'],'id' => 'fieldset_create_org','class' => 'qffieldset','fields' => 'name, website, ccode','legend' => 'Create Organization','notes_label' => 'About Organizations','notes' => "<p>A TPG organization may create as many teams in as many leagues as it would like. After creating an organization, you will be asked which league(s) you would like to create teams in.</p>"), $this);?>

<p><?php echo $this->_tpl_vars['create_org_form']['submit']['html']; ?>
</p>
</form>

</div>