<?php /* Smarty version 2.6.14, created on 2012-11-18 21:15:47
         compiled from org.cp.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'org.cp.tpl', 15, false),array('modifier', 'escape', 'org.cp.tpl', 48, false),)), $this); ?>
<div>

<div>
<?php if ($this->_tpl_vars['edit_org_success']): ?>
<p>Your changes were successful.</p>
<?php endif; ?>

<form <?php echo $this->_tpl_vars['edit_org_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_org_form']['hidden']; ?>


<?php if ($this->_tpl_vars['edit_org_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_org_form'],'id' => 'fieldset_edit_org','class' => 'qffieldset','fields' => 'name, website, ccode','legend' => 'Edit Organization'), $this);?>

<p><?php echo $this->_tpl_vars['edit_org_form']['submit']['html']; ?>
</p>
</form>
</div>

<div>


<form <?php echo $this->_tpl_vars['create_team_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['create_team_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['create_team_form'],'id' => 'fieldset_create_team','class' => 'qffieldset','fields' => 'lid','legend' => 'Create Team'), $this);?>

<p><?php echo $this->_tpl_vars['create_team_form']['submit']['html']; ?>
</p>
</form>
</div>

<div>
<table>
<colgroup span="4" />

<tr>
<th>&nbsp;</td>
<th>Name</th>
<th>Tag</th>
<th>Approved</th>
<th>Active</th>
<th>League</th>
<th>Division</th>
<th>Group</td>
</tr>
<?php $_from = $this->_tpl_vars['team_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['team']):
?>
<tr>
<td><a href="/team.cp.php?tid=<?php echo $this->_tpl_vars['team']['tid']; ?>
">Team Panel</a></td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['tag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php if ($this->_tpl_vars['team']['approved']): ?>Y<?php else: ?>N<?php endif; ?></td>
<td><?php if ($this->_tpl_vars['team']['inactive']): ?>N<?php else: ?>Y<?php endif; ?></td>
<td><a href="/<?php echo $this->_tpl_vars['team']['lgname']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['league_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['division_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['group_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
</tr>
<?php endforeach; else: ?>
<tr>
<td colspan="4">Your organization is not currently participating in any leagues.</td>
</tr>
<?php endif; unset($_from); ?>
</table>

</div>


</div>