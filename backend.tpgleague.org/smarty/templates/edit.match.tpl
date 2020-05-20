<p><a href="/edit.matches.php?sch_id={$smarty.const.SCH_ID}">Return to matches scheduler</a></p>

{if isset($smarty.get.delete)}

	<div style="align: center;">
	Are you sure you wish to delete this match?

	<p><a href="/edit.match.php?mid={$smarty.get.mid}&amp;delete=confirm">DELETE</a> ----- <a href="/edit.match.php?mid={$smarty.get.mid}">Go back</a></p>

	</div>

{elseif $smarty.const.lid}


<div>
	<h1>Match Notes</h1>
	{foreach from=$admin_notes item='note' name='note'}
	<div>
	Added by: {$note.admin_name|escape}<br />
	Date: {$note.unix_create_date_gmt|converted_timezone}<br />
	{$note.comment|escape|nl2br}
	{if !$smarty.foreach.note.last}<hr style="width: 200px; text-align: left; margin: 5px; auto 5px 0;" />{/if}
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

<hr />


<div>
{if $success}
Your changes were successful.
{/if}
</div>

<div style="color: red; font-weight: bold;">
{if $match_scores_failure}
<p>There was a problem with the scores form you submitted below.</p>
<hr />
{/if}
</div>


<div>
{$unreport_form}
</div>

<br />

<form {$edit_match_form.attributes}>
{$edit_match_form.hidden}

{quickform_fieldset form=$edit_match_form id='fieldset_edit_match' class='qffieldset' fields='mid, away_tid, home_tid, confirmed_mpid, start_date_gmt, report_date, reporting_user, reporting_admin_name, match_comments, forfeit_away, forfeit_home, deleted, important_note, submit' legend='Edit Match'}

</form>

<br />



<form {$report_match_form.attributes}>
{$report_match_form.hidden}
<table id="report_match_table">
<tbody>
<tr>
<th colspan="3" style="text-align:center;">First Half</th></tr>
<tr>
<th>Side</th>
<th>Team</th>
<th align="right">Score</th>
</tr>
<tr>
<td id="h1a_side" class="sidecol">{$report_match_form.side_selector_h1a.html}</td>
<td>{$report_match_form.away_team_name.html}</td>
<td align="center" class="scorecol">{$report_match_form.h1a_score.html}</td>
</tr>
<tr>
<td id="h1h_side" class="sidecol">{$report_match_form.side_selector_h1h.html}</td>
<td>{$report_match_form.home_team_name.html}</td>
<td align="center" class="scorecol">{$report_match_form.h1h_score.html}</td>
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
<td id="h2a_side" class="sidecol">{$report_match_form.side_selector_h2a.html}</td>
<td>{$report_match_form.away_team_name.html}</td>
<td align="center" class="scorecol">{$report_match_form.h2a_score.html}</td>
</tr>
<tr>
<td id="h2h_side" class="sidecol">{$report_match_form.side_selector_h2h.html}</td>
<td>{$report_match_form.home_team_name.html}</td>
<td align="center" class="scorecol">{$report_match_form.h2h_score.html}</td>
</tr>
</tbody>

<tbody>
<tr>
<td>{$report_match_form.submit.html}</td>
</tr>
</tbody>
</table>
</form>






{else}

<div>No match by that ID found.</div>


{/if}
