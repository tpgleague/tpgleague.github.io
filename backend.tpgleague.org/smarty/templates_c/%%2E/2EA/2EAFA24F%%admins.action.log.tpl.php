<?php /* Smarty version 2.6.14, created on 2013-03-23 23:39:06
         compiled from admins.action.log.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'admins.action.log.tpl', 21, false),array('modifier', 'converted_timezone', 'admins.action.log.tpl', 42, false),array('modifier', 'escape', 'admins.action.log.tpl', 43, false),array('modifier', 'truncate', 'admins.action.log.tpl', 50, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">TPG Admin Logs</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">
						<h4 class="headline"> Admin Log Information</h4>
						<p>

<form <?php echo $this->_tpl_vars['select_date_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['select_date_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['select_date_form'],'id' => 'fieldset_select_date','class' => 'qffieldset','fields' => 'start_date, end_date, submit','legend' => 'Select Date Range'), $this);?>



</form>

<?php if ($this->_tpl_vars['admin_log_form_submitted']): ?>
<div>
<table class="clean" style="white-space: nowrap;">
<caption>Admin Action Log</caption>
<tr>
	<th>Date</th>
	<th>Admin</th>
	<th>Table</th>
	<th>Table Key ID</th>
	<th>Field</th>
	<th>From Value</th>
	<th>To Value</th>
	<th>Description</th>
</tr>
<?php $_from = $this->_tpl_vars['admin_action_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['action']):
?>
<tr>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['action']['unix_timestamp_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
	<td><a href="/edit.admin.php?aid=<?php echo $this->_tpl_vars['action']['aid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['action']['admin_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
	<td><?php echo $this->_tpl_vars['action']['tablename']; ?>
</td>
	<td><?php echo $this->_tpl_vars['action']['tablepkid']; ?>
</td>
	<td><?php echo $this->_tpl_vars['action']['field']; ?>
</td>
	<?php if ($this->_tpl_vars['action']['type'] == 'insert'): ?>
	<td colspan="2">[NEW RECORD CREATED]</td>
	<?php else: ?>
	<td title="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['from_value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['action']['from_value'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 32, '...') : smarty_modifier_truncate($_tmp, 32, '...')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	<td title="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['to_value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['action']['to_value'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 32, '...') : smarty_modifier_truncate($_tmp, 32, '...')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	<?php endif; ?>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['action']['linked_descriptor'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
</tr>
<?php endforeach; else: ?>
<tr>
	<td colspan="8">No actions found for date range.</td>
</tr>
<?php endif; unset($_from); ?>
</table>
</div>
<?php endif; ?>
						</p>

					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->

    <?php echo '<?php'; ?>
 include('footer.html') <?php echo '?>'; ?>


</div>
