
<p><a href="/edit.schedule.php?sid={$smarty.const.SID}">Return to calendar.</a></p>

{if $schedule_error}
<div style="border: 3px solid red; padding: 5px; margin: 5px;">
	<h4>Scheduling Error:</h4>
	<ul>
		{foreach from=$schedule_error item='err'}
		<li>{$err}</li>
		{/foreach}
	</ul>
</div>
<hr />
{/if}


<div>

<form {$edit_schedule_form.attributes}>
{$edit_schedule_form.hidden}

{quickform_fieldset form=$edit_schedule_form id='fieldset_edit_schedule' class='qffieldset' fields='sch_id, mapid, stg_type, stg_number, stg_short_desc, stg_match_date_gmt, stg_latest_match_date_gmt, deleted, submit' legend='Edit Schedule'}

</form>


</div>

<br />

<hr />

<div>
	<h1>Schedule Notes</h1>
	{foreach from=$schedule_notes item='note' name='note'}
	<div>
	Added by: {$note.admin_name|escape}<br />
	Date: {$note.unix_create_date_gmt|converted_timezone}<br />
	{$note.comment|escape|nl2br}
	{if !$smarty.foreach.note.last}<hr style="width: 200px; text-align: left; margin: 5px; auto 5px 0;" />{/if}
	</div>
	<br />
	{/foreach}

	<form {$schedule_notes_form.attributes}>
	{$schedule_notes_form.hidden}

	{quickform_fieldset form=$schedule_notes_form id='fieldset_schedule_notes' class='qffieldset' fields='comment' legend='Add Note'}
	<p>{$schedule_notes_form.submit.html}</p>

	</form>
	<br />
</div>

<hr />

<br />


<div>
<table>
<caption>Scheduled Matches</caption>

<tr>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>Away Team</th>
<th>Home Team</th>
<th>Match Time</th>
<th>Proposals</th>
</tr>
{foreach from=$scheduled item='match'}
<tr{if $match.deleted} style="text-decoration: line-through;"{/if}>
<td>{if $match.deleted}DELETED!{else}&nbsp;{/if}</td>
<td><a href="/edit.match.php?mid={$match.mid}">Edit</a></td>
<td {if $match.away_messages || $match.start_date_gmt}style="font-weight: bold;"{/if}>{if $match.away_tid}<a href="/edit.team.php?tid={$match.away_tid}" style="color: black;">{/if}{$match.away_name|escape|default:'<i>Bye</i>'}{if $match.away_tid}</a>{/if}</td>
<td {if $match.home_messages || $match.start_date_gmt}style="font-weight: bold;"{/if}>{if $match.home_tid}<a href="/edit.team.php?tid={$match.home_tid}" style="color: black;">{/if}{$match.home_name|escape|default:'<i>Bye</i>'}{if $match.home_tid}</a>{/if}</td>
<td>{$match.start_date_gmt|converted_timezone}</td>
<td>{if $match.proposals_exist}<a href="/view.match.proposals.php?mid={$match.mid}">View</a>{/if}</td>
</tr>
{foreachelse}
No matches currently scheduled.
{/foreach}
</table>
</div>

<br />
<hr />

	<div>
	<table>
	<caption>Pending Queue</caption>

	<tr>
	<th>Division</th>
	<th>Conference</th>
	<th>Team</th>
	<th>&nbsp;</th>
	</tr>

	{foreach from=$listings_divisions key='divid' item='division'}
	{foreach from=$listings_conferences[$divid] item='conference'}
	<tr>
		<td>{$division.division_title|escape}</td>
		<td>{$conference.conference_title|escape}</td>
		{foreach from=$listings_pending key='pending_tid' item='pending'}
		{if $pending.cfid == $conference.cfid}
			<td><a href="/edit.team.php?tid={$pending_tid}">{$pending.name|escape}</a></td>
			<td>
				<form method="post" action="/edit.matches.php?sch_id={$smarty.const.SCH_ID}" style="display:inline;">
					<input type="hidden" name="mpnid" value="{$pending.mpnid}" />
					<input type="hidden" name="remove_pending" value="true" />
					<input type="submit" value="Dequeue" style="width:auto; height:auto;" />
				</form>
			</td>
		{/if}
		{/foreach}
	</tr>
	{/foreach}
	{/foreach}
	</table>
	</div>

<br />
<hr />

{assign var='grayedout' value=' opacity:.50; filter: alpha(opacity=50); -moz-opacity: 0.50;'}

<div><p>Teams that aren't explicitly assigned to a specific group won't show up here. They must also be approved. You can modify groups via the <a href="/teams.manager.php?lid={$smarty.const.LID}">teams manager</a>. The autoscheduler will ignore any teams that are inside inactive groups or inactive conferences.</p>
<p>The autoscheduler will not schedule teams that are inactive. You can schedule them manually.  Usually, the only reason to do so would be to give that team a bye loss, because the opponent they forfeited to found another team to do a makeup match with.</p></div>

<hr />

<div>
<h2>Advanced Scheduler</h2>

<form method="post" action="/edit.matches.php?sch_id={$smarty.const.SCH_ID}" id="report_match_form" >

<br />Notify teams via e-mail that they have been scheduled together (N/A to byes or forfeits) <input type="checkbox" name="notify" {if ($smarty.post.submit != 'Schedule') || $smarty.post.notify}checked="checked"{/if} />

<br />
<br />

<table border="0">
<tr><td>Team 1:</td><td>Team 2:</td></tr>
<tr><td>
<select name="team1">
<option>Select Team 1</option>
<option {if $smarty.post.team1 == 'pending'}selected="selected"{/if} value="pending">Opponent Pending</option>
<option {if $smarty.post.team1 == 'bye_win'}selected="selected"{/if} value="bye_win">Bye Win</option>
<option {if $smarty.post.team1 == 'bye_win_ff'}selected="selected"{/if} value="bye_win_ff">Bye Win (Forfeit)</option>
<option {if $smarty.post.team1 == 'bye_loss'}selected="selected"{/if} value="bye_loss">Bye Loss</option>
<option {if $smarty.post.team1 == 'bye_loss_ff'}selected="selected"{/if} value="bye_loss_ff">Bye Loss (Forfeit)</option>
{foreach from=$listings_divisions key='divid' item='division'}
	<optgroup label="{$division.division_title|upper|escape}">
	{foreach from=$team_list item='dropdown'}
	{if $dropdown.divid == $divid}
		<option value="{$dropdown.tid}"
			{if $smarty.post.team2 == $dropdown.tid}selected="selected"{/if} 
			{if $scheduled_teams_list[$dropdown.tid]}style="color: gray;"{/if}
		>{$dropdown.name|escape}{if $dropdown.inactive} [I]{/if}
		</option>
	{/if}
	{/foreach}
	</optgroup>
{/foreach}
</select>
</td>

<td>
<select name="team2">
<option>Select Team 2</option>
<option {if $smarty.post.team2 == 'pending'}selected="selected"{/if} value="pending">Opponent Pending</option>
<option {if $smarty.post.team2 == 'bye_win'}selected="selected"{/if} value="bye_win">Bye Win</option>
<option {if $smarty.post.team2 == 'bye_win_ff'}selected="selected"{/if} value="bye_win_ff">Bye Win (Forfeit)</option>
<option {if $smarty.post.team2 == 'bye_loss'}selected="selected"{/if} value="bye_loss">Bye Loss</option>
<option {if $smarty.post.team2 == 'bye_loss_ff'}selected="selected"{/if} value="bye_loss_ff">Bye Loss (Forfeit)</option>
{foreach from=$listings_divisions key='divid' item='division'}
	<optgroup label="{$division.division_title|upper|escape}">
	{foreach from=$team_list item='dropdown'}
	{if $dropdown.divid == $divid}
		<option value="{$dropdown.tid}"
			{if $smarty.post.team2 == $dropdown.tid}selected="selected"{/if} 
			{if $scheduled_teams_list[$dropdown.tid]}style="color: gray;"{/if}
		>{$dropdown.name|escape}{if $dropdown.inactive} [I]{/if}
		</option>
	{/if}
	{/foreach}
	</optgroup>
{/foreach}
</select>
</td></tr>

<tr>
	<td>
	Team 1 (left side) is the:
	<select name="court">
		<option {if $smarty.post.court == 'auto'}selected="selected"{/if} value="auto">[Auto]</option>
		<option {if $smarty.post.court == 'home'}selected="selected"{/if} value="home">Home team</option>
		<option {if $smarty.post.court == 'away'}selected="selected"{/if} value="away">Away team</option>
	</select>
	</td>

	<td>Key:
	<br /><span style="">[I] Inactive</span>
	<br /><span style="color: gray;">[S] Scheduled</span>
	</td>
</tr>
</table>

<div>
Admin Note (Optional):
<br /><textarea rows="5" cols="50" name="match_admin_note" >{$smarty.post.match_admin_note|escape}</textarea>
</div>

<br />Override any previously scheduled matches for these two teams (award old opponents forfeit losses. Used for makeup matches.) Use with care!!: 
	<input type="checkbox" {if $smarty.post.override_makeup_match}checked="checked"{/if} name="override_makeup_match" />
<br />

<br /><input type="checkbox" name="team1_ff" {if $smarty.post.team1_ff}selected="selected"{/if} /> Team 1 forfeits.
<br /><input type="checkbox" name="team2_ff" {if $smarty.post.team2_ff}selected="selected"{/if} /> Team 2 forfeits.

<br />

<table id="report_match_table">
	<caption>Report match (Optional. Just fill out all fields with values):</caption>
	<tbody>
	<tr>
	<th colspan="3" style="text-align:center;">First Half</th></tr>
	<tr>
	<th>Side</th>
	<th>Team</th>
	<th align="right">Score</th>
	</tr>
	<tr>
	<td id="h1a_side" class="sidecol"><select name="side_selector_h1a" onchange="changeSides('h1a');">
		<option value=""></option>
		{foreach from=$league_sides key='side_key' item='side'}
		<option {if $smarty.post.side_selector_h1a == $side_key}selected="selected"{/if} value="{$side_key}">{$side}</option>
		{/foreach}
	</select></td>
	<td>Team 1</td>
	<td align="center" class="scorecol"><input name="h1a_score" type="text" value="{$smarty.post.h1a_score|escape}" /></td>
	</tr>
	<tr>
	<td id="h1h_side" class="sidecol"><select name="side_selector_h1h" onchange="changeSides('h1h');">
		<option value=""></option>
		{foreach from=$league_sides key='side_key' item='side'}
		<option {if $smarty.post.side_selector_h1h == $side_key}selected="selected"{/if} value="{$side_key}">{$side}</option>
		{/foreach}
	</select></td>
	<td>Team 2</td>
	<td align="center" class="scorecol"><input name="h1h_score" type="text" value="{$smarty.post.h1h_score|escape}" /></td>
	</tr>
	</tbody>

	<tbody>
	<tr>
	<th colspan="3" style="text-align:center;">Second Half</th></tr>
	<tr>
	<th>Side</th>
	<th>Team</th>
	<th>Score</th>
	</tr>
	<tr>
	<td id="h2a_side" class="sidecol"><select name="side_selector_h2a" onchange="changeSides('h2a');">
		<option value=""></option>
		{foreach from=$league_sides key='side_key' item='side'}
		<option {if $smarty.post.side_selector_h2a == $side_key}selected="selected"{/if} value="{$side_key}">{$side}</option>
		{/foreach}
	</select></td>
	<td>Team 1</td>
	<td align="center" class="scorecol"><input name="h2a_score" type="text" value="{$smarty.post.h2a_score|escape}" /></td>
	</tr>
	<tr>
	<td id="h2h_side" class="sidecol"><select name="side_selector_h2h" onchange="changeSides('h2h');">
		<option value=""></option>
		{foreach from=$league_sides key='side_key' item='side'}
		<option {if $smarty.post.side_selector_h2h == $side_key}selected="selected"{/if} value="{$side_key}">{$side}</option>
		{/foreach}
	</select></td>
	<td>Team 2</td>
	<td align="center" class="scorecol"><input name="h2h_score" type="text" value="{$smarty.post.h2h_score|escape}" /></td>
	</tr>
	</tbody>

</table>

<br /><input type="submit" name="submit" value="Schedule" />
<br />
<br />
</form>

</div>

<hr />

<div style="width:40%;">

	{foreach from=$listings_divisions key='divid' item='division' name='group'}
		<div class="division" id="divid_{$divid}" {if $division.inactive}style="{$grayedout}"{/if}>
			<h3>Division: {$division.division_title|escape}</h3>

			{foreach from=$listings_conferences[$divid] item='conference' name='conference'}
				<div class="conference" id="cfid_{$conference.cfid}" style="margin-top: 2px; margin-bottom: 6px; {if $conference.inactive}{$grayedout}{/if}">
					<h3>Conference: {$conference.conference_title|escape} <span class="auto_scheduler">(<a href="/auto-scheduler.php?sch_id={$smarty.const.SCH_ID}&amp;cfid={$conference.cfid}">run auto-scheduler</a>)</span></h3>

					{foreach from=$listings_groups[$conference.cfid] item='group' name='group'}
						<div class="section" id="group_{$divid}-{$conference.cfid}-{$group.grpid}" style="background-color: #CCFFFF; border-color: #99FFFF; margin-top: 1px; margin-bottom: 2px; {if $group.inactive}{$grayedout}{/if}">
							<h3 class="handle" style="margin-bottom: 0px; background-color: #99FFFF; padding-top: 0px; padding-bottom: 0px;">Group: {$group.group_title|escape}</h3>

									<table cellspacing="0" style="width: 100%;">
									<thead>
									<tr>
									<th style="text-align: left;">Team</th>
									<th style="text-align: right;">Opponent</th>
									</tr>
									</thead>
									{foreach from=$listings_teams[$group.grpid] item='team' name='team'}
									<tr style="border: 1px solid black; padding: 3px;">
									<td style="margin: 0; padding: 3px; border-top: 1px solid black; text-align: left;">
										<a href="/edit.team.php?tid={$team.tid}">{$team.name|escape}</a>{if $team.inactive} [<i>Inactive</i>]{/if}
									</td>

									<td style="margin: 0; padding: 3px; border-top: 1px solid black; text-align: right;">

									{if $team.opponent_name}
										{$team.opponent_name}{if $team.opponent_inactive} [<i>Inactive</i>]{/if}
									{elseif isset($listings_pending[$team.tid])}
										<i>Pending Queue</i> {*(<a href="/edit.matches.php?sch_id={$smarty.get.sch_id}&amp;mpnid={$listings_pending[$team.tid].mpnid}&amp;remove_pending">Dequeue</a>)*}
										<form method="post" action="/edit.matches.php?sch_id={$smarty.const.SCH_ID}" style="display:inline;">
											<input type="hidden" name="mpnid" value="{$listings_pending[$team.tid].mpnid}" />
											<input type="hidden" name="remove_pending" value="true" />
											<input type="submit" value="Dequeue" style="width:auto; height:auto;" />
										</form>
									{else}
										<a class="plus" onclick="return overlay(this, 'popup_{$team.tid}')">[+]</a>
									{/if}

									<div id="popup_{$team.tid}" class="popup" style="position:absolute; display:none">
										<div class="popup_close"><a class="close" onclick="overlayclose('popup_{$team.tid}'); return false">Close</a></div>
										<br />Schedule {$team.name|escape} against:
										<br />
										<form method="post" action="/edit.matches.php?sch_id={$smarty.const.SCH_ID}">
											<input type="hidden" name="team1" value="{$team.tid}" />
											<select name="team2">
											{if !$team.inactive}
											<option value="pending">Opponent Pending</option>
											<option value="bye_win">Bye Win</option>
											<option value="bye_win_ff">Bye Win (Forfeit)</option>
											<option value="bye_loss">Bye Loss</option>
											{/if}
											<option value="bye_loss_ff">Bye Loss (Forfeit)</option>
											{if !$team.inactive}
											{foreach from=$team_list item='dropdown'}{if ($dropdown.tid != $team.tid) && ($dropdown.divid == $divid) && !$scheduled_teams_list[$dropdown.tid] && !$dropdown.inactive}<option value="{$dropdown.tid}">{$dropdown.name|escape}{if $dropdown.inactive} [Inactive]{/if}</option>{/if}{/foreach}
											{/if}
											</select>
											<br />{$team.name|escape} is the: <select name="court">
																				<option value="auto">[Auto]</option>
																				<option value="home">Home team</option>
																				<option value="away">Away team</option>
																			</select>
											<br />Notify teams via e-mail: 
											{if !$team.inactive}
												<input type="checkbox" name="notify" checked="checked" />
											{else}
												N/A
											{/if}
											<br /><input value="Schedule" name="submit" type="submit" />
										</form>
									</div>

									</td>
									</tr>
									{/foreach}
									</table>

						</div>
					{/foreach}

				</div>
			{/foreach}

		</div>

	{/foreach}

</div>


<div>
<p>
<br />
</p>
</div>


<div>
<p>
<br />
</p>
</div>


<div>
<p>
<br />
</p>
</div>


<div>
<p>
<br />
</p>
</div>


<div>
<p>
<br />
</p>
</div>