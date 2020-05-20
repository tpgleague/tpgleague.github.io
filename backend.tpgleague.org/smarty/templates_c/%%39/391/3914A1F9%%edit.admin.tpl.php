<?php /* Smarty version 2.6.14, created on 2013-04-18 14:05:05
         compiled from edit.admin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.admin.tpl', 20, false),array('modifier', 'converted_timezone', 'edit.admin.tpl', 42, false),array('modifier', 'escape', 'edit.admin.tpl', 49, false),array('modifier', 'truncate', 'edit.admin.tpl', 49, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Edit TPG Admin</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">
						<h4 class="headline"> Edit Admin</h4>
						<p>
<form <?php echo $this->_tpl_vars['edit_admin_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_admin_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_admin_form'],'id' => 'fieldset_edit_admin','class' => 'qffieldset','fields' => 'uid, username, admin_name, department, seniority, admin_email, gtalk, irc_nick, superadmin, inactive','legend' => 'Edit Admin'), $this);?>

<p><?php echo $this->_tpl_vars['edit_admin_form']['submit']['html']; ?>
</p>

</form>

<br />
<hr />
<br />

<div>
<table class="clean" style="white-space: nowrap;">
<caption>Admin Action Log</caption>
<tr>
	<th>Date</th>
	<th>Table</th>
	<th>Table Key ID</th>
	<th>Field</th>
	<th>From Value</th>
	<th>To Value</th>
</tr>
<?php $_from = $this->_tpl_vars['admin_action_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['action']):
?>
<tr>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['action']['unix_timestamp_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
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
</tr>
<?php endforeach; else: ?>
<tr>
	<td colspan="6">Admin has not performed any actions</td>
</tr>
<?php endif; unset($_from); ?>
</table>
</div>


<br />
<hr />
<br />


<div>
<p>Some hyperlinks to pages that admins have visited may have actions contained within the URL itself (such as removing a player from a roster).  Chances are the action will no longer be valid even if you do click it, but still, please try to be careful what you click on.</p>
<table class="clean" style="white-space: nowrap; width: 1400px; table-layout: fixed; overflow:hidden; "  >
<caption>Admin Page View Log</caption>
<tr style="word-wrap:break-word;">
	<th style="width: 150px;">Date</th>
	<th>Page</th>
</tr>
<?php $_from = $this->_tpl_vars['admin_page_views']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
 if (strpos ( $this->_tpl_vars['page']['query'] , 'remove' ) !== FALSE):  $this->assign('link', 'FALSE');  else:  $this->assign('link', 'TRUE');  endif; ?>
<tr>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['page']['unix_timestamp_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
	<td><?php if ($this->_tpl_vars['link']): ?><a href="<?php echo $this->_tpl_vars['page']['page']; ?>
.php<?php if ($this->_tpl_vars['page']['query']): ?>?<?php endif;  echo ((is_array($_tmp=$this->_tpl_vars['page']['query'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php endif;  echo $this->_tpl_vars['page']['page']; ?>
.php<?php if ($this->_tpl_vars['page']['query']): ?>&amp;<?php endif;  echo ((is_array($_tmp=$this->_tpl_vars['page']['query'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  if ($this->_tpl_vars['link']): ?></a><?php endif; ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
</div>
						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->

