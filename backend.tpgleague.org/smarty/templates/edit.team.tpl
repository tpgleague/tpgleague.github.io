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
	<form {$edit_team_form.attributes}>
	{$edit_team_form.hidden}

	{if $edit_team_form.errors}
	<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
	{/if}

	{quickform_fieldset form=$edit_team_form id='fieldset_edit_team' class='qffieldset' fields='tid, name, tag, pw, captain_uid, irc, team_avatar_url' legend='Team Info'}

	{quickform_fieldset form=$edit_team_form id='fieldset_edit_team_server' class='qffieldset' fields='server_ip, server_port, server_pw, server_location, server_available' legend='Game Server Info'}

	{quickform_fieldset form=$edit_team_form id='fieldset_edit_team_hltv' class='qffieldset' fields='hltv_ip, hltv_port, hltv_pw, hltv_public' legend='HLTV Server Info'}

	{quickform_fieldset form=$edit_team_form id='fieldset_edit_team_admin' class='qffieldset' fields='approved, inactive, deleted, create_date_gmt, roster_lock, roster_lock_status' legend='TPG Admin Data'}

	<p>Actual Roster Lock Status: {if is_numeric($roster_lock_status)}Will lock {$roster_lock_status|converted_timezone}{else}{$roster_lock_status}{/if}</p>

	<p>{$edit_team_form.submit.html}</p>
	<br />
	</form>
</div>

{if $delete_team_form}
<p>
<br />
<p>
<br />
</p>
<br />
</p>
<hr />
<div>
<form autocomplete="off" onsubmit="this.submit.disabled = true; return true" action="/edit.team.php?tid={$smarty.const.TID}" method="post" id="delete_team_form_{$delete_form_random_number}">
	<div>To delete this team, type the following phrase into the box below exactly as it appears:
			<div id="delete_text" onselectstart="return false;" style="z-index: 0; color: red;">Yes, delete this team!</div>
	</div>
	<br /><input type="text" name="delete_team_verify_{$delete_form_random_number}" value="" size="22" maxlength="22" autocomplete="off" />
	<br /><input type="submit" name="submit_delete" value="Delete Team" />
	<br />(This cannot be undone)<br />
</form>
</div>
{/if}

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

	{foreach from=$admin_notes item='admin_note'}
	<div>
	Added by: {$admin_note.admin_name|escape}<br />
	Date: {$admin_note.unix_create_date_gmt|converted_timezone}<br />
	<p>{$admin_note.comment|escape|nl2br}</p>
	</div>
	{/foreach}

	<form {$admin_notes_form.attributes}>
	{$admin_notes_form.hidden}

	{quickform_fieldset form=$admin_notes_form id='fieldset_admin_notes' class='qffieldset' fields='comment' legend='Add Note'}
	<p>{$admin_notes_form.submit.html}</p>
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
	<form {$roster_form.attributes}>
	{$roster_form.hidden}

	{if $roster_form_error}
	<p class="error">{foreach from=$roster_form_error item='error'}{$error}<br />{/foreach}</p>
	{/if}

	<div>
	{$roster_form.captain_uid.label} {$roster_form.captain_uid.html}
	</div>

	<table id="roster_list">
	<tr>
	<th>Player</th>
	<th>Handle</th>
	<th>{$gid_name}</th>
	<th class="verticaltext">Sched. ({$team_data.max_schedulers})</th>
	<th class="verticaltext">Report ({$team_data.max_reporters})</th>
	<th>&nbsp;</th>
	</tr>
	{foreach from=$team_roster item='player_info' key='rid'}
	{assign var='player' value="player_`$rid`"}
	{assign var='handle' value="handle_`$rid`"}
	{assign var='gid' value="gid_`$rid`"}
	{assign var='scheduler' value="permission_reschedule_`$rid`"}
	{assign var='report' value="permission_report_`$rid`"}
	<tr>
	<td>[<a href="/edit.user.php?uid={$player_info.uid}">{$player_info.username|escape}</a>] {$roster_form.$player.label}{if $player_info.uid == $team_data.captain_uid} (<i>Captain</i>){/if} ({$player_info.email})</td>
	<td>{$roster_form.$handle.html}</td>
	<td>{$roster_form.$gid.error}{$roster_form.$gid.html}</td>
	<td class="checkbox">{$roster_form.$scheduler.html}</td>
	<td class="checkbox">{$roster_form.$report.html}</td>
	<td><a href="/edit.team.php?tid={$smarty.const.TID}&amp;rid={$rid}&amp;remove">Remove</a></td>
	{*
	<td>
		<form method="post" action="/edit.team.php?tid={$smarty.const.TID}" style="display:inline;">
			<input type="hidden" name="rid" value="{$rid}" />
			<input type="hidden" name="remove" value="true" />
			<input type="submit" name="submit" value="Remove" style="width:auto; height:auto;" />
		</form>
	</td>
	*}
	</tr>
	{foreachelse}
	<tr>
	<td colspan="0">Team roster is empty</td>
	</tr>
	{/foreach}
	{if $team_roster}
	<tr>
	<td colspan="4" align="center">{$roster_form.submit.html}</td>
	</tr>
	{/if}
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
	<th>{$gid_name}</th>
	<th>Join Date</th>
	<th>Leave Date</th>
	</tr>
	{foreach from=$roster_log item='roster_info'}
	<tr>
	<td>[<a href="/edit.user.php?uid={$roster_info.uid}">{$roster_info.username|escape}</a>] {$roster_info.firstname|escape} {$roster_info.lastname|escape}</td>
	<td>{$roster_info.handle|escape}</td>
	<td>{$roster_info.gid}</td>
	<td>{$roster_info.unix_join_date_gmt|converted_timezone}</td>
	<td>{if $roster_info.unix_leave_date_gmt}{$roster_info.unix_leave_date_gmt|converted_timezone}{else}--{/if}</td>
	</tr>
	{foreachelse}
	<tr>
	<td colspan="5">No roster activity.</td>
	</tr>
	{/foreach}
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
			{foreach from=$team_log item='log'}
			<tr>
				<td>{$log.field}</td>
				<td{if $log.field == 'moved to'} colspan="2"{/if}>{$log.from_value|escape}</td>
				{if $log.field != 'moved to'}<td>{$log.to_value|escape}</td>{/if}
				<td>{$log.admin_name|escape|default:'[<i>SYSTEM</i>]'}</td>
				<td>{$log.unix_timestamp_gmt|converted_timezone}</td>
			</tr>
			{foreachelse}
			<tr><td colspan="5">Nothing in team log.</td></tr>
			{/foreach}
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






