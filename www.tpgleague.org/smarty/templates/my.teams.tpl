{if $message}
<p>{$message}</p>
{/if}

{if $confirm_leave_team}

<form method="post" action="/my.teams.php?leave&amp;tid={$confirm_leave_team.tid}&amp;confirm=true">
<p>Are you sure you wish to leave team {$confirm_leave_team.name|escape}?</p>

{if $captain_player_list}
You must choose a new captain: <br />
<select name="new_captain">
{foreach from=$captain_player_list item='player' key='uid'}
<option value="{$uid}">
{$player.firstname|escape} 
{if $player.handle}"{$player.handle|escape}"{/if}
{if $player.hide_lastname}
	{$player.lastname|truncate:2:"."|escape}
{else}
	{$player.lastname|escape}
{/if}
</option>
{/foreach}
</select>
{/if}

<div>
<input class="submit" name="confirm" id="confirm" value="Leave Team" type="submit" />
</div>
</form>
<p><a href="/my.teams.php">Back to my teams</a></p>
{/if}


{if $player_on_teams}
<form {$my_teams_form.attributes}>
{$my_teams_form.hidden}

{if $my_teams_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{foreach from=$rosterids item=rid}
{assign var='team_name' value=`$rid.name`}
{capture assign='team_name'}{$team_name|escape}{/capture}
{quickform_fieldset form=$my_teams_form id="fieldset_my_team_`$rid.rid`" class='qffieldset' fields="league_`$rid.rid`, status_`$rid.rid`, handle_`$rid.rid`, gid_`$rid.rid`, leave_team_`$rid.rid`" legend="<a href=\"/`$rid.lgname`/team/`$rid.tid`/\">"|cat:$team_name|cat:"</a>"}
{/foreach}

<p>{$my_teams_form.submit.html}</p>
</form>
{else}
	<p>You are not currently on any rosters.</p>
{/if}
