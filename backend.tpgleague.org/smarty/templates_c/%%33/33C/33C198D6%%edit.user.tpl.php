<?php /* Smarty version 2.6.14, created on 2012-11-26 00:30:06
         compiled from edit.user.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.user.tpl', 23, false),array('modifier', 'escape', 'edit.user.tpl', 82, false),array('modifier', 'converted_timezone', 'edit.user.tpl', 83, false),array('modifier', 'nl2br', 'edit.user.tpl', 84, false),array('modifier', 'truncate', 'edit.user.tpl', 211, false),)), $this); ?>


<div class="tabcontentstyle">

	<div id="tcontent1" class="tabcontent">
		<h1>User Details</h1>
		<form <?php echo $this->_tpl_vars['edit_user_form']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['edit_user_form']['hidden']; ?>


		<?php if ($this->_tpl_vars['edit_user_form']['errors']): ?>
		<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
		<?php endif; ?>

		<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_user_form'],'id' => 'fieldset_edit_user','class' => 'qffieldset','fields' => 'uid, username, email, verified, pending_email, firstname, lastname, handle, city, state, ccode, user_comments, abuse_lock, submit','legend' => 'Edit User Details'), $this);?>

		</form>
		<br />
	</div>

    <a name="notes" />
	<div id="tcontent3" class="tabcontent">
		<h1>Admin Notes</h1>
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

   
    <a name="rosters" />
	<div class="tabcontent">
        <h1>Rosters and Suspensions</h1>
		<form <?php echo $this->_tpl_vars['join_team_form']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['join_team_form']['hidden']; ?>


		<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['join_team_form'],'id' => 'fieldset_join_team','class' => 'qffieldset','fields' => 'team_selector, handle, gid, submit','legend' => 'Add to Roster'), $this);?>

		</form>

		

		<br />

		<table>
			<tr>
				<th>League</th>
				<th>Team</th>
				<th>Handle</th>
				<th>Game ID</th>
				<th>Join Date</th>
				<th>&nbsp;</th>
                <th>&nbsp;</th>
			</tr>
			<?php $_from = $this->_tpl_vars['active_teams']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['roster']):
?>
			<tr>
				<td><a href="/edit.league.php?lid=<?php echo $this->_tpl_vars['roster']['lid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['roster']['league_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
				<td><a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['roster']['tid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['roster']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
				<td><?php echo ((is_array($_tmp=$this->_tpl_vars['roster']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				<td><?php echo $this->_tpl_vars['roster']['gid']; ?>
</td>
				<td><?php echo ((is_array($_tmp=$this->_tpl_vars['roster']['unix_join_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
								<td>
					<form method="post" action="/edit.user.php?uid=<?php echo @USER_ID; ?>
" style="display:inline;">
						<input type="hidden" name="remove_roster" value="true" />
						<input type="hidden" name="rid" value="<?php echo $this->_tpl_vars['roster']['rid']; ?>
" />
						<input type="submit" value="Remove" style="width:auto; height:auto;" />
					</form>
				</td>
                <td><a href="suspensions.php?uid=<?php echo $this->_tpl_vars['user_id']; ?>
&tid=<?php echo $this->_tpl_vars['roster']['tid']; ?>
">Suspend</a> (pre-fills league id which should be removed for global suspensions)</td>
			</tr>
			<?php endforeach; else: ?>
			<tr><td colspan="6">Player not active on any teams.</td></tr>
			<?php endif; unset($_from); ?>
		</table>
        
        <br><b>Other Suspension Options: </b><a href="suspensions.php?uid=<?php echo $this->_tpl_vars['user_id']; ?>
">Suspend From All Leagues</a>
	</div>

    <a name="history" />
<div>
    <h1>Roster History</h1>
	<table class="clean">
	<tr>
	<th>League</th>
	<th>Team</th>
	<th>Handle</th>
	<th>GID</th>
	<th>Join Date</th>
	<th>Leave Date</th>
	<th>Removed by UserID</th>
	<th>Added By Admin</th>
	<th>Removed By Admin</th>
	</tr>
	<?php $_from = $this->_tpl_vars['roster_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['roster_info']):
?>
	<tr>
	<td><a href="/edit.league.php?lid=<?php echo $this->_tpl_vars['roster_info']['lid']; ?>
"><?php echo $this->_tpl_vars['roster_info']['lgname']; ?>
</a></td>
	<td><a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['roster_info']['tid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['team_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	<td><?php echo $this->_tpl_vars['roster_info']['gid']; ?>
</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['unix_join_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
	<td><?php if ($this->_tpl_vars['roster_info']['unix_leave_date_gmt']):  echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['unix_leave_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp));  else: ?>--<?php endif; ?></td>
	<td><?php if ($this->_tpl_vars['roster_info']['removed_by_uid'] != @USER_ID): ?><a href="/edit.user.php?uid=<?php echo $this->_tpl_vars['roster_info']['removed_by_uid']; ?>
"><?php echo $this->_tpl_vars['roster_info']['removed_by_uid']; ?>
</a><?php endif; ?></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['added_by_admin'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info']['removed_by_admin'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<?php endforeach; else: ?>
	<tr>
	<td colspan="5">No roster activity.</td>
	</tr>
	<?php endif; unset($_from); ?>
	</table>
</div>


<hr />
<br />

<a name="actions" />
<div>
	<h1>User Action Log</h1>
	<table class="clean" style="white-space: nowrap;">
	<caption>Self-performed actions. Some actions are not logged.</caption>
	<tr>
		<th>Date</th>
		<th>Table</th>
		<th>Table Key ID</th>
		<th>Field</th>
		<th>From Value</th>
		<th>To Value</th>
	</tr>
	<?php $_from = $this->_tpl_vars['user_action_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
		<?php if ($this->_tpl_vars['action']['field'] == 'email_validation_key'): ?>
			<td title="[secret]">[secret]</td>
		<?php else: ?>
			<td title="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['from_value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['action']['from_value'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 32, '...') : smarty_modifier_truncate($_tmp, 32, '...')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<?php endif; ?>

		<?php if ($this->_tpl_vars['action']['field'] == 'email_validation_key'): ?>
			<td title="[secret]">[secret]</td>
		<?php else: ?>
			<td title="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['to_value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['action']['to_value'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 32, '...') : smarty_modifier_truncate($_tmp, 32, '...')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<?php endif; ?>

		<?php endif; ?>
	</tr>
	<?php endforeach; else: ?>
	<tr>
		<td colspan="6">User has not performed any actions</td>
	</tr>
	<?php endif; unset($_from); ?>
	</table>
</div>


<br />
<hr />
<br />




<a name="logins" />
	<div id="tcontent5" class="tabcontent">
		<h1>Login History</h1>
		<p>I don't know what good logging their browser will do us at this point, but I know it only does us good if nobody knows that we're logging it.</p>
		<table id="logins">
		<thead>
			<tr>
			<th>Timestamp</th>
			<th>IP Address</th>
			<th>Hostname</th>
			<th>Browser</th>
			</tr>
		</thead>
		<tbody>
			<?php $_from = $this->_tpl_vars['login_history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['login']):
?>
			<tr>
			<td class="nowrap"><?php echo ((is_array($_tmp=$this->_tpl_vars['login']['unix_timestamp_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
			<td class="nowrap"><?php echo $this->_tpl_vars['login']['address']; ?>
</td>
			<td class="nowrap"><?php echo $this->_tpl_vars['login']['hostname']; ?>
</td>
			<td><?php echo $this->_tpl_vars['login']['browser']; ?>
</td>
			</tr>
			<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
		<br />
	</div>



</div>

<script type="text/javascript">
	initializetabcontent("maintab");
</script>