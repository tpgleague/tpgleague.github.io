
{*
<ul id="maintab" class="shadetabs">
<li class="selected"><a href="#" rel="tcontent1">User Details</a></li>
<li><a href="#" rel="tcontent2">Suspensions {$tab_header.suspensions}</a></li>
<li><a href="#" rel="tcontent3">Admin Notes {$tab_header.admin_notes}</a></li>
{if $smarty.const.AID == 1}<li><a href="#" onclick="onLoad();" rel="tcontent4">Geo-Location Trace</a></li>{/if}
<li><a href="#" rel="tcontent5">Login History</a></li>
</ul>
*}

<div class="tabcontentstyle">

	<div id="tcontent1" class="tabcontent">
		<h1>User Details</h1>
		<form {$edit_user_form.attributes}>
		{$edit_user_form.hidden}

		{if $edit_user_form.errors}
		<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
		{/if}

		{quickform_fieldset form=$edit_user_form id='fieldset_edit_user' class='qffieldset' fields='uid, username, email, verified, pending_email, firstname, lastname, handle, city, state, ccode, user_comments, abuse_lock, submit' legend='Edit User Details'}
		</form>
		<br />
	</div>
{*
	<div id="tcontent2" class="tabcontent">
		<h1>Suspensions</h1>
		<table>
		<caption>Suspension History</caption>
		<tr>
		<th>&nbsp;</th>
		<th>Create Date</th>
		</tr>

		{foreach from=$suspensions item='suspension'}
		<tr>
		<td><a href="/edit.suspension.php?suspid={$suspension.suspid}">Edit</a></td>
		<td>{$suspension.create_date_gmt}</td>
		</tr>
		{foreachelse}
		<tr>
		<td>&nbsp;</td>
		<td colspan="0">No suspension history.</td>
		</tr>
		{/foreach}

		</table>

		<p>Add new suspension:</p>

		<form {$suspension_form.attributes}>
		{$suspension_form.hidden}

		{if $suspension_form.errors}
		<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
		{/if}

		{quickform_fieldset form=$suspension_form id='fieldset_suspension' class='qffieldset' fields='handle, tid, firstname, gid, suspension_completion_season_number' legend='Add Suspension'}
		</form>

		<form method="get" action="/add.suspension.php">
		<input type="hidden" name="uid" value="{$smarty.const.USER_ID}" />
		<select name="lid">
		<option value="global">Global</option>
		{foreach from=$leagues_list item='league'}
		<option value="{$league.lid}">{$league.league_title}</option>
		{/foreach}
		</select>
		<br /><input type="submit" value="Select" />
		</form>
		<br />
	</div>
*}

    <a name="notes" />
	<div id="tcontent3" class="tabcontent">
		<h1>Admin Notes</h1>
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

   
    <a name="rosters" />
	<div class="tabcontent">
        <h1>Rosters and Suspensions</h1>
		<form {$join_team_form.attributes}>
		{$join_team_form.hidden}

		{quickform_fieldset form=$join_team_form id='fieldset_join_team' class='qffieldset' fields='team_selector, handle, gid, submit' legend='Add to Roster'}
		</form>

		

		<br />

		<table>
			<tr>
				<th>League</th>
				<th>Team</th>
				<th>Handle</th>
				<th>Game ID</th>
                <th>Anti-Cheat ID</th>
				<th>Join Date</th>
				<th>&nbsp;</th>
                <th>&nbsp;</th>
			</tr>
			{foreach from=$active_teams item='roster'}
			<tr>
				<td><a href="/edit.league.php?lid={$roster.lid}">{$roster.league_title|escape}</a></td>
				<td><a href="/edit.team.php?tid={$roster.tid}">{$roster.name|escape}</a></td>
				<td>{$roster.handle|escape}</td>
				<td>{$roster.gid}</td>
                <td>{$roster.anticheatuserid}</td>
				<td>{$roster.unix_join_date_gmt|converted_timezone}</td>
				{*<td><a href="/edit.user.php?uid={$smarty.const.USER_ID}&amp;remove&amp;rid={$roster.rid}">[Remove]</a></td>*}
				<td>
					<form method="post" action="/edit.user.php?uid={$smarty.const.USER_ID}" style="display:inline;">
						<input type="hidden" name="remove_roster" value="true" />
						<input type="hidden" name="rid" value="{$roster.rid}" />
						<input type="submit" value="Remove" style="width:auto; height:auto;" />
					</form>
				</td>
                <td><a href="suspensions.php?uid={$user_id}&tid={$roster.tid}">Suspend</a> (pre-fills league id which should be removed for global suspensions)</td>
			</tr>
			{foreachelse}
			<tr><td colspan="6">Player not active on any teams.</td></tr>
			{/foreach}
		</table>
        
        <br><b>Other Suspension Options: </b><a href="suspensions.php?uid={$user_id}">Suspend From All Leagues</a>
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
    <th>Anti-Cheat ID</th>
	<th>Join Date</th>
	<th>Leave Date</th>
	<th>Removed by UserID</th>
	<th>Added By Admin</th>
	<th>Removed By Admin</th>
	</tr>
	{foreach from=$roster_log item='roster_info'}
	<tr>
	<td><a href="/edit.league.php?lid={$roster_info.lid}">{$roster_info.lgname}</a></td>
	<td><a href="/edit.team.php?tid={$roster_info.tid}">{$roster_info.team_name|escape}</a></td>
	<td>{$roster_info.handle|escape}</td>
	<td>{$roster_info.gid}</td>
    <td>{$roster_info.anticheatuserid}</td>
	<td>{$roster_info.unix_join_date_gmt|converted_timezone}</td>
	<td>{if $roster_info.unix_leave_date_gmt}{$roster_info.unix_leave_date_gmt|converted_timezone}{else}--{/if}</td>
	<td>{if $roster_info.removed_by_uid != $smarty.const.USER_ID}<a href="/edit.user.php?uid={$roster_info.removed_by_uid}">{$roster_info.removed_by_uid}</a>{/if}</td>
	<td>{$roster_info.added_by_admin|escape}</td>
	<td>{$roster_info.removed_by_admin|escape}</td>
	</tr>
	{foreachelse}
	<tr>
	<td colspan="5">No roster activity.</td>
	</tr>
	{/foreach}
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
	{foreach from=$user_action_log item='action'}
	<tr>
		<td>{$action.unix_timestamp_gmt|converted_timezone}</td>
		<td>{$action.tablename}</td>
		<td>{$action.tablepkid}</td>
		<td>{$action.field}</td>
		{if $action.type == 'insert'}
		<td colspan="2">[NEW RECORD CREATED]</td>
		{else}
		{if $action.field == 'email_validation_key'}
			<td title="[secret]">[secret]</td>
		{else}
			<td title="{$action.from_value|escape}">{$action.from_value|truncate:32:'...'|escape}</td>
		{/if}

		{if $action.field == 'email_validation_key'}
			<td title="[secret]">[secret]</td>
		{else}
			<td title="{$action.to_value|escape}">{$action.to_value|truncate:32:'...'|escape}</td>
		{/if}

		{/if}
	</tr>
	{foreachelse}
	<tr>
		<td colspan="6">User has not performed any actions</td>
	</tr>
	{/foreach}
	</table>
</div>


<br />
<hr />
<br />



{*
	<div id="tcontent4" class="tabcontent">
		<h1>Geo-Location Trace</h1>
		<p>All plots are estimates.  Coordinates updated once nightly.  For reference purposes only.</p>
		{if $geo_locations_empty}
		<i>No geo-trace available for this user.</i>
		{else}
		<table>
		  <tr>
			<td>{$google_map}</td>
			<td>{$google_map_sidebar}</td>
		  </tr>
		</table>
		{/if}
	</div>
*}

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
			{foreach from=$login_history item='login'}
			<tr>
			<td class="nowrap">{$login.unix_timestamp_gmt|converted_timezone}</td>
			<td class="nowrap">{$login.address}</td>
			<td class="nowrap">{$login.hostname}</td>
			<td>{$login.browser}</td>
			</tr>
			{/foreach}
		</tbody>
		</table>
		<br />
	</div>



</div>

<script type="text/javascript">
	initializetabcontent("maintab");
</script>