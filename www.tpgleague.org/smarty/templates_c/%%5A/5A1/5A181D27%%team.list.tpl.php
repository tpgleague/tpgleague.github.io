<?php /* Smarty version 2.6.14, created on 2012-10-06 18:27:44
         compiled from team.list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'team.list.tpl', 12, false),array('modifier', 'default', 'team.list.tpl', 14, false),)), $this); ?>
<div style="margin-left: 10%; margin-right: 10%;">

<table>
<tr>
<th>Name</th>
<th>Tag</th>
<th>Division</th>
<th>Group</th>
</tr>
<?php $_from = $this->_tpl_vars['team_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['team']):
?>
<tr>
<td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['team']['tid']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
<td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['team']['tid']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['tag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
<td><?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['team']['division_title'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['team']['group_title'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="3">No teams</td></tr>
<?php endif; unset($_from); ?>
</table>

</div>