<?php /* Smarty version 2.6.14, created on 2012-05-23 23:55:49
         compiled from edit.conference.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.conference.tpl', 7, false),array('modifier', 'escape', 'edit.conference.tpl', 22, false),array('modifier', 'iso_datetime', 'edit.conference.tpl', 23, false),)), $this); ?>



<form <?php echo $this->_tpl_vars['edit_conference_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_conference_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_conference_form'],'id' => 'fieldset_edit_conference','class' => 'qffieldset','fields' => 'lid, cfid, conference_title, description, admin, sort_order, inactive, create_date_gmt, submit','legend' => 'Edit Conference'), $this);?>

</form>



<table>
  <tr>
	<th></th>
	<th>Group</th>
	<th>Create Date</th>
  </tr>

<?php $_from = $this->_tpl_vars['groups_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group']):
?>
<tr>
  <td><a href="/edit.group.php?grpid=<?php echo $this->_tpl_vars['group']['grpid']; ?>
">Edit</a></td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['group']['group_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['group']['create_date_gmt'])) ? $this->_run_mod_handler('iso_datetime', true, $_tmp) : smarty_modifier_iso_datetime($_tmp)); ?>
</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="3">No groups</td></tr>
<?php endif; unset($_from); ?>

</table>


<form <?php echo $this->_tpl_vars['add_group_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['add_group_form']['hidden']; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['add_group_form'],'id' => 'fieldset_add_group','class' => 'qffieldset','fields' => 'lid, cfid, group_title, admin, submit','legend' => 'Add New Group'), $this);?>

</form>