{if isset($smarty.get.remove)}
<form method="post" action="/manage.roster.php?tid={$smarty.const.TID}&amp;rid={$smarty.get.rid}&amp;remove=confirm">
<p>Are you sure you wish to remove player {$remove_player_info.firstname|escape}
{if $remove_player_info.handle}"{$remove_player_info.handle|escape}"{/if}
{if $remove_player_info.hide_lastname}
	{$remove_player_info.lastname|truncate:2:"."|escape}
{else}
	{$remove_player_info.lastname|escape}
{/if}
?</p>

{if $captain_player_list}
You must choose a new captain: <br />
<select name="new_captain">
{foreach from=$captain_player_list item='player'}
<option value="{$player.uid}">
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
<input name="confirm" id="confirm" value="Remove Player" type="submit" />
</div>
</form>
<p><a href="/manage.roster.php?tid={$smarty.const.TID}">Back to roster management.</a></p>

{else}


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
<th>{$gid_name}</th>
<th class="verticaltext">{if $team_data.max_schedulers}Scheduler <br />(Max {$team_data.max_schedulers}){else}&nbsp;{/if}</th>
<th class="verticaltext">{if $team_data.max_reporters}Report Matches <br />(Max {$team_data.max_reporters}){else}&nbsp;{/if}</th>
<th>&nbsp;</th>
</tr>
{foreach from=$team_roster item='player_info' key='rid'}
{assign var='player' value="player_`$rid`"}
{assign var='gid' value="gid_`$rid`"}
{assign var='scheduler' value="permission_reschedule_`$rid`"}
{assign var='report' value="permission_report_`$rid`"}
<tr>
<td>{$roster_form.$player.label}</td>
<td>{$roster_form.$gid.label}</td>
<td class="checkbox">{$roster_form.$scheduler.html}</td>
<td class="checkbox">{$roster_form.$report.html}</td>
<td><a href="/manage.roster.php?tid={$smarty.const.TID}&amp;rid={$rid}&amp;remove">Remove</a></td>
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

<p>The team captain/owner always has full access to all team functions.</p>

{/if}