<?php /* Smarty version 2.6.14, created on 2012-03-04 14:25:42
         compiled from my.teams.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'my.teams.tpl', 8, false),array('modifier', 'truncate', 'my.teams.tpl', 18, false),array('modifier', 'cat', 'my.teams.tpl', 46, false),array('function', 'quickform_fieldset', 'my.teams.tpl', 46, false),)), $this); ?>
<?php if ($this->_tpl_vars['message']): ?>
<p><?php echo $this->_tpl_vars['message']; ?>
</p>
<?php endif; ?>

<?php if ($this->_tpl_vars['confirm_leave_team']): ?>

<form method="post" action="/my.teams.php?leave&amp;tid=<?php echo $this->_tpl_vars['confirm_leave_team']['tid']; ?>
&amp;confirm=true">
<p>Are you sure you wish to leave team <?php echo ((is_array($_tmp=$this->_tpl_vars['confirm_leave_team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
?</p>

<?php if ($this->_tpl_vars['captain_player_list']): ?>
You must choose a new captain: <br />
<select name="new_captain">
<?php $_from = $this->_tpl_vars['captain_player_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['uid'] => $this->_tpl_vars['player']):
?>
<option value="<?php echo $this->_tpl_vars['uid']; ?>
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
<input class="submit" name="confirm" id="confirm" value="Leave Team" type="submit" />
</div>
</form>
<p><a href="/my.teams.php">Back to my teams</a></p>
<?php endif; ?>


<?php if ($this->_tpl_vars['player_on_teams']): ?>
<form <?php echo $this->_tpl_vars['my_teams_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['my_teams_form']['hidden']; ?>


<?php if ($this->_tpl_vars['my_teams_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php $_from = $this->_tpl_vars['rosterids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rid']):
 $this->assign('team_name', ($this->_tpl_vars['rid']['name']));  ob_start();  echo ((is_array($_tmp=$this->_tpl_vars['team_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('team_name', ob_get_contents());ob_end_clean();  echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['my_teams_form'],'id' => "fieldset_my_team_".($this->_tpl_vars['rid']['rid']),'class' => 'qffieldset','fields' => "league_".($this->_tpl_vars['rid']['rid']).", status_".($this->_tpl_vars['rid']['rid']).", handle_".($this->_tpl_vars['rid']['rid']).", gid_".($this->_tpl_vars['rid']['rid']).", leave_team_".($this->_tpl_vars['rid']['rid']),'legend' => ((is_array($_tmp=((is_array($_tmp="<a href=\"/".($this->_tpl_vars['rid']['lgname'])."/team/".($this->_tpl_vars['rid']['tid'])."/\">")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['team_name']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['team_name'])))) ? $this->_run_mod_handler('cat', true, $_tmp, "</a>") : smarty_modifier_cat($_tmp, "</a>"))), $this);?>

<?php endforeach; endif; unset($_from); ?>

<p><?php echo $this->_tpl_vars['my_teams_form']['submit']['html']; ?>
</p>
</form>
<?php else: ?>
	<p>You are not currently on any rosters.</p>
<?php endif; ?>