<?php /* Smarty version 2.6.14, created on 2013-03-24 15:56:50
         compiled from edit.team.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.team.tpl', 25, false),array('modifier', 'converted_timezone', 'edit.team.tpl', 33, false),array('modifier', 'escape', 'edit.team.tpl', 74, false),array('modifier', 'nl2br', 'edit.team.tpl', 76, false),array('modifier', 'default', 'edit.team.tpl', 222, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Team Information</h2>
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
<div>
	<form <?php echo $this->_tpl_vars['edit_team_form']['attributes']; ?>
>
	<?php echo $this->_tpl_vars['edit_team_form']['hidden']; ?>


	<?php if ($this->_tpl_vars['edit_team_form']['errors']): ?>
	<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
	<?php endif; ?>

	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_team_form'],'id' => 'fieldset_edit_team','class' => 'qffieldset','fields' => 'tid, name, tag, pw, captain_uid, irc, team_avatar_url','legend' => 'Team Info'), $this);?>


	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_team_form'],'id' => 'fieldset_edit_team_server','class' => 'qffieldset','fields' => 'server_ip, server_port, server_pw, server_location, server_available','legend' => 'Game Server Info'), $this);?>


	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_team_form'],'id' => 'fieldset_edit_team_hltv','class' => 'qffieldset','fields' => 'hltv_ip, hltv_port, hltv_pw, hltv_public','legend' => 'HLTV Server Info'), $this);?>


	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_team_form'],'id' => 'fieldset_edit_team_admin','class' => 'qffieldset','fields' => 'approved, inactive, deleted, create_date_gmt, roster_lock, roster_lock_status','legend' => 'TPG Admin Data'), $this);?>


	<p>Actual Roster Lock Status: <?php if (is_numeric ( $this->_tpl_vars['roster_lock_status'] )): ?>Will lock <?php echo ((is_array($_tmp=$this->_tpl_vars['roster_lock_status'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp));  else:  echo $this->_tpl_vars['roster_lock_status'];  endif; ?></p>

	<p><?php echo $this->_tpl_vars['edit_team_form']['submit']['html']; ?>
</p>
	<br />
	</form>
</div>

<?php if ($this->_tpl_vars['delete_team_form']): ?>
<p>
<br />
<p>
<br />
</p>
<br />
</p>
<hr />
<div>
<form autocomplete="off" onsubmit="this.submit.disabled = true; return true" action="/edit.team.php?tid=<?php echo @TID; ?>
" method="post" id="delete_team_form_<?php echo $this->_tpl_vars['delete_form_random_number']; ?>
">
	<div>To delete this team, type the following phrase into the box below exactly as it appears:
			<div id="delete_text" onselectstart="return false;" style="z-index: 0; color: red;">Yes, delete this team!</div>
	</div>
	<br /><input type="text" name="delete_team_verify_<?php echo $this->_tpl_vars['delete_form_random_number']; ?>
" value="" size="22" maxlength="22" autocomplete="off" />
	<br /><input type="submit" name="submit_delete" value="Delete Team" />
	<br />(This cannot be undone)<br />
</form>
</div>
<?php endif; ?>

<br />
<br />
<div>
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Admin Notes</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>

	<?php $_from = $this->_tpl_vars['admin_notes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['admin_note']):
?>
	<div>
	Added by: <?php echo ((is_array($_tmp=$this->_tpl_vars['admin_note']['admin_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br />
	Date: <?php echo ((is_array($_tmp=$this->_tpl_vars['admin_note']['unix_create_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
<br />
	<p><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['admin_note']['comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>
	</div>
	<?php endforeach; endif; unset($_from); ?>

	<form <?php echo $this->_tpl_vars['admin_notes_form']['attributes']; ?>
>
	<?php echo $this->_tpl_vars['admin_notes_form']['hidden']; ?>


	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['admin_notes_form'],'id' => 'fieldset_admin_notes','class' => 'qffieldset','fields' => 'comment','legend' => 'Add Note'), $this);?>

	<p><?php echo $this->_tpl_vars['admin_notes_form']['submit']['html']; ?>
</p>
	</form>
	<br />
</div>

<br />
<br />
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Current Roster</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>

<div>
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
	<th>Handle</th>
	<th><?php echo $this->_tpl_vars['gid_name']; ?>
</th>
	<th class="verticaltext">Sched. (<?php echo $this->_tpl_vars['team_data']['max_schedulers']; ?>
)</th>
	<th class="verticaltext">Report (<?php echo $this->_tpl_vars['team_data']['max_reporters']; ?>
)</th>
	<th>&nbsp;</th>
	</tr>
	<?php $_from = $this->_tpl_vars['team_roster']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rid'] => $this->_tpl_vars['player_info']):
?>
	<?php $this->assign('player', "player_".($this->_tpl_vars['rid'])); ?>
	<?php $this->assign('handle', "handle_".($this->_tpl_vars['rid'])); ?>
	<?php $this->assign('gid', "gid_".($this->_tpl_vars['rid'])); ?>
	<?php $this->assign('scheduler', "permission_reschedule_".($this->_tpl_vars['rid'])); ?>
	<?php $this->assign('report', "permission_report_".($this->_tpl_vars['rid'])); ?>
	<tr>
	<td>[<a href="/edit.user.php?uid=<?php echo $this->_tpl_vars['player_info']['uid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['player_info']['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>] <?php echo $this->_tpl_vars['roster_form'][$this->_tpl_vars['player']]['label'];  if ($this->_tpl_vars['player_info']['uid'] == $this->_tpl_vars['team_data']['captain_uid']): ?> (<i>Captain</i>)<?php endif; ?> (<?php echo $this->_tpl_vars['player_info']['email']; ?>
)</td>
	<td><?php echo $this->_tpl_vars['roster_form'][$this->_tpl_vars['handle']]['html']; ?>
</td>
	<td><?php echo $this->_tpl_vars['roster_form'][$this->_tpl_vars['gid']]['error'];  echo $this->_tpl_vars['roster_form'][$this->_tpl_vars['gid']]['html']; ?>
</td>
	<td class="checkbox"><?php echo $this->_tpl_vars['roster_form'][$this->_tpl_vars['scheduler']]['html']; ?>
</td>
	<td class="checkbox"><?php echo $this->_tpl_vars['roster_form'][$this->_tpl_vars['report']]['html']; ?>
</td>
	<td><a href="/edit.team.php?tid=<?php echo @TID; ?>
&amp;rid=<?php echo $this->_tpl_vars['rid']; ?>
&amp;remove">Remove</a></td>
		</tr>
	<?php endforeach; else: ?>
	<tr>
	<td colspan="0">Team roster is empty</td>
	</tr>
	<?php endif; unset($_from); ?>
	<?php if ($this->_tpl_vars['team_roster']): ?>
	<tr>
	<td colspan="4" align="center"><?php echo $this->_tpl_vars['roster_form']['submit']['html']; ?>
</td>
	</tr>
	<?php endif; ?>
	</table>

	</form>
</div>

<br />
<br />
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Roster History</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>


	<table class="clean">
	<tr>
	<th>Player</th>
	<th>Handle</th>
	<th><?php echo $this->_tpl_vars['gid_name']; ?>
</th>
	<th>Join Date</th>
	<th>Leave Date</th>
	</tr>
	<?php $_from = $this->_tpl_vars['roster_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['roster_info']):
?>
	<tr>
	<td>[<a href="/edit.user.php?uid=<?php echo $this->_tpl_vars['roster_info']['uid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>] <?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	<td><?php echo $this->_tpl_vars['roster_info']['gid']; ?>
</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['unix_join_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
	<td><?php if ($this->_tpl_vars['roster_info']['unix_leave_date_gmt']):  echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['unix_leave_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp));  else: ?>--<?php endif; ?></td>
	</tr>
	<?php endforeach; else: ?>
	<tr>
	<td colspan="5">No roster activity.</td>
	</tr>
	<?php endif; unset($_from); ?>
	</table>

</div>


<br />
<br />
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Admin Action Log</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
<div>

	<table class="clean">
		<thead>
			<tr>
				<th>Value/Action</th>
				<th>From Value</th>
				<th>To Value</th>
				<th>Admin</th>
				<th>Timestamp</th>
			</tr>
		</thead>
		<tbody>
			<?php $_from = $this->_tpl_vars['team_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['log']):
?>
			<tr>
				<td><?php echo $this->_tpl_vars['log']['field']; ?>
</td>
				<td<?php if ($this->_tpl_vars['log']['field'] == 'moved to'): ?> colspan="2"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['log']['from_value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				<?php if ($this->_tpl_vars['log']['field'] != 'moved to'): ?><td><?php echo ((is_array($_tmp=$this->_tpl_vars['log']['to_value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td><?php endif; ?>
				<td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['log']['admin_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '[<i>SYSTEM</i>]') : smarty_modifier_default($_tmp, '[<i>SYSTEM</i>]')); ?>
</td>
				<td><?php echo ((is_array($_tmp=$this->_tpl_vars['log']['unix_timestamp_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
			</tr>
			<?php endforeach; else: ?>
			<tr><td colspan="5">Nothing in team log.</td></tr>
			<?php endif; unset($_from); ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
var somediv=document.getElementById("delete_text")
disableSelection(somediv) //disable text selection within DIV with id="mydiv"
</script>
						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->





