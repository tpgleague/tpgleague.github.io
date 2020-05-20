<?php /* Smarty version 2.6.14, created on 2012-11-30 00:13:42
         compiled from manage.roster.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manage.roster.tpl', 3, false),array('modifier', 'truncate', 'manage.roster.tpl', 6, false),)), $this); ?>
<?php if (isset ( $_GET['remove'] )): ?>
<form method="post" action="/manage.roster.php?tid=<?php echo @TID; ?>
&amp;rid=<?php echo $_GET['rid']; ?>
&amp;remove=confirm">
<p>Are you sure you wish to remove player <?php echo ((is_array($_tmp=$this->_tpl_vars['remove_player_info']['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

<?php if ($this->_tpl_vars['remove_player_info']['handle']): ?>"<?php echo ((is_array($_tmp=$this->_tpl_vars['remove_player_info']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif;  if ($this->_tpl_vars['remove_player_info']['hide_lastname']): ?>
	<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['remove_player_info']['lastname'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 2, ".") : smarty_modifier_truncate($_tmp, 2, ".")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

<?php else: ?>
	<?php echo ((is_array($_tmp=$this->_tpl_vars['remove_player_info']['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

<?php endif; ?>
?</p>

<?php if ($this->_tpl_vars['captain_player_list']): ?>
You must choose a new captain: <br />
<select name="new_captain">
<?php $_from = $this->_tpl_vars['captain_player_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['player']):
?>
<option value="<?php echo $this->_tpl_vars['player']['uid']; ?>
">
<?php echo ((is_array($_tmp=$this->_tpl_vars['player']['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 
<?php if ($this->_tpl_vars['player']['handle']): ?>"<?php echo ((is_array($_tmp=$this->_tpl_vars['player']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif;  if ($this->_tpl_vars['player']['hide_lastname']): ?>
	<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['player']['lastname'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 2, ".") : smarty_modifier_truncate($_tmp, 2, ".")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

<?php else: ?>
	<?php echo ((is_array($_tmp=$this->_tpl_vars['player']['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

<?php endif; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<?php endif; ?>

<div>
<input name="confirm" id="confirm" value="Remove Player" type="submit" />
</div>
</form>
<p><a href="/manage.roster.php?tid=<?php echo @TID; ?>
">Back to roster management.</a></p>

<?php else: ?>


<form <?php echo $this->_tpl_vars['roster_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['roster_form']['hidden']; ?>


<?php if ($this->_tpl_vars['roster_form_error']): ?>
<p class="error"><?php $_from = $this->_tpl_vars['roster_form_error']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['error']):
 echo $this->_tpl_vars['error']; ?>
<br /><?php endforeach; endif; unset($_from); ?></p>
<?php endif; ?>

<div>
<?php echo $this->_tpl_vars['roster_form']['captain_uid']['label']; ?>
 <?php echo $this->_tpl_vars['roster_form']['captain_uid']['html']; ?>

</div>

<table id="roster_list">
<tr>
<th>Player</th>
<th><?php echo $this->_tpl_vars['gid_name']; ?>
</th>
<th class="verticaltext"><?php if ($this->_tpl_vars['team_data']['max_schedulers']): ?>Scheduler <br />(Max <?php echo $this->_tpl_vars['team_data']['max_schedulers']; ?>
)<?php else: ?>&nbsp;<?php endif; ?></th>
<th class="verticaltext"><?php if ($this->_tpl_vars['team_data']['max_reporters']): ?>Report Matches <br />(Max <?php echo $this->_tpl_vars['team_data']['max_reporters']; ?>
)<?php else: ?>&nbsp;<?php endif; ?></th>
<th>&nbsp;</th>
</tr>
<?php $_from = $this->_tpl_vars['team_roster']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rid'] => $this->_tpl_vars['player_info']):
 $this->assign('player', "player_".($this->_tpl_vars['rid']));  $this->assign('gid', "gid_".($this->_tpl_vars['rid']));  $this->assign('scheduler', "permission_reschedule_".($this->_tpl_vars['rid']));  $this->assign('report', "permission_report_".($this->_tpl_vars['rid'])); ?>
<tr>
<td><?php echo $this->_tpl_vars['roster_form'][$this->_tpl_vars['player']]['label']; ?>
</td>
<td><?php echo $this->_tpl_vars['roster_form'][$this->_tpl_vars['gid']]['label']; ?>
</td>
<td class="checkbox"><?php echo $this->_tpl_vars['roster_form'][$this->_tpl_vars['scheduler']]['html']; ?>
</td>
<td class="checkbox"><?php echo $this->_tpl_vars['roster_form'][$this->_tpl_vars['report']]['html']; ?>
</td>
<td><a href="/manage.roster.php?tid=<?php echo @TID; ?>
&amp;rid=<?php echo $this->_tpl_vars['rid']; ?>
&amp;remove">Remove</a></td>
</tr>
<?php endforeach; else: ?>
<tr>
<td colspan="0">Team roster is empty</td>
</tr>
<?php endif; unset($_from);  if ($this->_tpl_vars['team_roster']): ?>
<tr>
<td colspan="4" align="center"><?php echo $this->_tpl_vars['roster_form']['submit']['html']; ?>
</td>
</tr>
<?php endif; ?>
</table>

</form>

<p>The team captain/owner always has full access to all team functions.</p>

<?php endif; ?>