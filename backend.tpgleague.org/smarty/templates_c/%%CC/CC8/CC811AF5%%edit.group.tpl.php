<?php /* Smarty version 2.6.14, created on 2012-03-07 13:23:26
         compiled from edit.group.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.group.tpl', 5, false),)), $this); ?>

<form <?php echo $this->_tpl_vars['edit_group_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_group_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_group_form'],'id' => 'fieldset_edit_group','class' => 'qffieldset','fields' => 'lid, grpid, group_title, admin, sort_order, inactive, create_date_gmt, submit','legend' => 'Edit Group'), $this);?>

</form>


