<?php /* Smarty version 2.6.14, created on 2012-05-23 23:55:46
         compiled from edit.division.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.division.tpl', 5, false),array('modifier', 'escape', 'edit.division.tpl', 21, false),array('modifier', 'iso_datetime', 'edit.division.tpl', 22, false),)), $this); ?>

<form <?php echo $this->_tpl_vars['edit_division_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_division_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_division_form'],'id' => 'fieldset_edit_division','class' => 'qffieldset','fields' => 'lid, divid, division_title, admin, sort_order, inactive, create_date_gmt, submit','legend' => 'Edit Division'), $this);?>

</form>



<table>

  <tr>
	<th></th>
	<th>Conference</th>
	<th>Create Date</th>
  </tr>

<?php $_from = $this->_tpl_vars['conferences_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['conference']):
?>
<tr>
  <td><a href="/edit.conference.php?cfid=<?php echo $this->_tpl_vars['conference']['cfid']; ?>
">Edit</a></td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['conference']['conference_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['conference']['create_date_gmt'])) ? $this->_run_mod_handler('iso_datetime', true, $_tmp) : smarty_modifier_iso_datetime($_tmp)); ?>
</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="3">No conferences</td></tr>
<?php endif; unset($_from); ?>

</table>
<p>Please note: if there is only one conference in a division, e.g. Central, the <i>title</i> will not be visible in the standings since it would be pointless. Unless you have more than 50 teams in one division (e.g. Lower Division has 50 teams), you should only have one conference: Central.</p>


<form <?php echo $this->_tpl_vars['add_conference_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['add_conference_form']['hidden']; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['add_conference_form'],'id' => 'fieldset_add_conference','class' => 'qffieldset','fields' => 'conference_title, admin, submit','legend' => 'Add New Conference'), $this);?>

</form>