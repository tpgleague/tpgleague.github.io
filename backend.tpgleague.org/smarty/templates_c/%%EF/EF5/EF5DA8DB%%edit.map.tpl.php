<?php /* Smarty version 2.6.14, created on 2012-10-15 01:28:29
         compiled from edit.map.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.map.tpl', 4, false),)), $this); ?>
<form <?php echo $this->_tpl_vars['edit_map_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_map_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_map_form'],'id' => 'fieldset_edit_map','class' => 'qffieldset','fields' => 'map_title, filename, config_path, overview_path, illegal_locations_path, deleted','legend' => 'Edit Map'), $this);?>

<p><?php echo $this->_tpl_vars['edit_map_form']['submit']['html']; ?>
</p>

</form>

<?php if ($this->_tpl_vars['success']): ?>
<br /><br /><div style="color: blue;">Map changes successful.</div>
<?php endif; ?>

<br />
<hr />
<br />