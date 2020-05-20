<?php /* Smarty version 2.6.14, created on 2013-03-24 17:54:14
         compiled from maps.manager.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'maps.manager.tpl', 30, false),array('function', 'quickform_fieldset', 'maps.manager.tpl', 52, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Maps Manager</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">

						<p>
<table class="clean">
<tr>
<th align="left">&nbsp;</th>
<th align="left">Title</th>
<th align="left">File Path</th>
<th align="left">Config Path</th>
<th align="left">Overview Path</th>
<th align="left">Exploits Path</th>
<th align="left">Create Date</th>
</tr>
<?php $_from = $this->_tpl_vars['maps_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['map']):
?>
<tr>
<td><a href="/edit.map.php?mapid=<?php echo $this->_tpl_vars['map']['mapid']; ?>
&lid=<?php echo $this->_tpl_vars['map']['lid']; ?>
">Edit</a></th>
<td<?php if ($this->_tpl_vars['map']['deleted']): ?> class="deleted"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['map']['map_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td<?php if ($this->_tpl_vars['map']['deleted']): ?> class="deleted"<?php endif; ?>><?php echo $this->_tpl_vars['map']['filename']; ?>
</td>
<td<?php if ($this->_tpl_vars['map']['deleted']): ?> class="deleted"<?php endif; ?>><?php echo $this->_tpl_vars['map']['config_path']; ?>
</td>
<td<?php if ($this->_tpl_vars['map']['deleted']): ?> class="deleted"<?php endif; ?>><?php echo $this->_tpl_vars['map']['overview_path']; ?>
</td>
<td<?php if ($this->_tpl_vars['map']['deleted']): ?> class="deleted"<?php endif; ?>><?php echo $this->_tpl_vars['map']['illegal_locations_path']; ?>
</td>
<td<?php if ($this->_tpl_vars['map']['deleted']): ?> class="deleted"<?php endif; ?>><?php echo $this->_tpl_vars['map']['modify_date_gmt']; ?>
</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4">No maps</td></tr>
<?php endif; unset($_from); ?>
</table>
<br /><br />
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Add New Map</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
<form <?php echo $this->_tpl_vars['add_map_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['add_map_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['add_map_form'],'id' => 'fieldset_add_map','class' => 'qffieldset','fields' => 'map_title, filename, config_path, overview_path, illegal_locations_path, submit'), $this);?>

</form>


						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->




