<div class="rubberbox">
<h1 class="rubberhdr"><span>Team Panel</span></h1>
	<ul>
	<li><a href="{$lgname}/team/{$mp_data.tid}/"><b>{$mp_data.team_name|escape}</b></a></li>
	<li>Team Status: {if $mp_data.team_inactive}<span style="color: red;">Inactive</span>{else}Active{/if}</li>
	<li>Roster Lock: 
		{if $mp_team_roster_lock_status == 'locked'}
			<span style="color: red">Locked</span>
		{elseif $mp_team_roster_lock_status == 'unlocked'}
			<span style="color: green">Unlocked</span>
		{else}
			<span style="color: green">{$mp_team_roster_lock_status|easy_day} {$mp_team_roster_lock_status|easy_time}</span>
		{/if}
	</li>
	</ul>

	<ul>
	<li><a href="{$lgname}/team/{$mp_data.tid}/">View Team Profile</a></li>
	{if $mp_captain || $mp_owner || $mp_data.permission_reschedule || $mp_data.permission_report}
	<li><a href="/season.matches.php?tid={$mp_data.tid}">Season Matches</a></li>
	<li><a href="/edit.team.php?tid={$mp_data.tid}">Edit Team Properties</a></li>
	{/if}
	{if $mp_captain || $mp_owner}
	<li><a href="/manage.roster.php?tid={$mp_data.tid}">Manage Team Roster</a></li>
	{/if}
	</ul>
</div>