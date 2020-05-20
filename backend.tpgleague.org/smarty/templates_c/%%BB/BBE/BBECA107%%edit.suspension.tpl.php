<?php /* Smarty version 2.6.14, created on 2012-11-28 00:31:58
         compiled from edit.suspension.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.suspension.tpl', 10, false),)), $this); ?>
<div>

<form <?php echo $this->_tpl_vars['edit_suspension_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_suspension_form']['hidden']; ?>


<?php if ($this->_tpl_vars['edit_suspension_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_suspension_form'],'id' => 'fieldset_edit_suspension','class' => 'qffieldset','fields' => 'suspid, uid, handle, firstname, lastname, reason, rule_violation, type, start, end, tid, team, mid, lid, gids, stank_ticket_number, added_admin, create_date_gmt, edited_admin, last_updated_date, deleted','legend' => 'Edit Suspension Details'), $this);?>


<p><?php echo $this->_tpl_vars['edit_suspension_form']['submit']['html']; ?>
</p>
</form>

</div>