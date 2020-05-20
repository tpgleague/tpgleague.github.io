<?php /* Smarty version 2.6.14, created on 2013-03-26 16:08:12
         compiled from admins.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'admins.tpl', 35, false),array('function', 'quickform_fieldset', 'admins.tpl', 60, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Admin List</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16" >

						<p >
						<table>
<tr>
<th></th>
<th>Admin Name</th>
<th>Admin E-mail</th>
<th>Username</th>
<th>First Name</th>
<th>Last Name</th>
<th>Department</th>
<th>Seniority</th>
<th>IRC Nick</th>
<th>Google Talk</th>
</tr>


<?php $_from = $this->_tpl_vars['admins_table']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['admin']):
?>
<tr<?php if ($this->_tpl_vars['admin']['inactive']): ?> style="color: grey;"<?php endif; ?>>
<td><?php if (@superadmin): ?><a href="/edit.admin.php?aid=<?php echo $this->_tpl_vars['admin']['aid']; ?>
">Edit</a><?php else: ?>&nbsp;<?php endif; ?></td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['admin_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['admin_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><a href="/edit.user.php?uid=<?php echo $this->_tpl_vars['admin']['uid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['department'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['seniority'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['irc_nick'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['admin']['gtalk'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>

</table>
<br /><br />
<?php if ($this->_tpl_vars['add_admin_form']): ?>
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Add Admin</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
<form <?php echo $this->_tpl_vars['add_admin_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['add_admin_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['add_admin_form'],'id' => 'fieldset_add_admin','class' => 'qffieldset','fields' => 'username, admin_name, department, seniority, admin_email, gtalk, irc_nick, superadmin'), $this);?>

<p><?php echo $this->_tpl_vars['add_admin_form']['submit']['html']; ?>
</p>

</form>
<?php endif; ?>
						</p>

					</div>	
				</div>
				
			</div>
            			</div>

<!-- End Container -->
			
		<!-- End Wrapper -->

