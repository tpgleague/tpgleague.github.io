<div>



{if $join_team_form}
	<form {$join_team_form.attributes}>
	{$join_team_form.hidden}

	{if $join_team_form.errors}
	<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
	{/if}
	{if $roster_locked}<p class="error">The roster for this team is currently locked.</p>{/if}

	{quickform_fieldset form=$join_team_form id='fieldset_join_team' class='qffieldset' fields='note_lid, teamname, teamid, pw, gid, handle' legend='Select Team'  notes_label='Team not listed?' notes="<p>If you don't see your team listed, then it is because your team has not been approved yet. You can join the team by entering the team ID, which your captain should have given you.</p>"}

	<p>{$join_team_form.submit.html}</p>

	</form>
{elseif $select_league_form}
	<form {$select_league_form.attributes}>
	{$select_league_form.hidden}

	{quickform_fieldset form=$select_league_form id='fieldset_select_league' class='qffieldset' fields='select_lid' legend='Select League'}

	<p>{$select_league_form.submit.html}</p>
	</form>
{elseif $success_team_info}
	<p>You have successfully joined team <a href="/{$success_team_info.lgname}/team/{$success_team_info.tid}/">{$success_team_info.name|escape}</a>.</p>
{/if}

</div>