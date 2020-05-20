<div id="team_page">

{if !empty($team_info)}
<table style="width: 610px; marign: 0; padding: 0;" border="0">
<tr>
<td align="left">

	<table style="width: 500px">
<!--	<colgroup span="1" align="right"></colgroup>-->
	<tr><td style="width: 100px">Team Name:</td><td>{$team_info.team_name|escape}</td></tr>
	<tr><td style="width: 100px">Team Tag:</td><td>{$team_info.tag|escape}</td></tr>
    {*if $team_info.website}<tr><td>Website:</td><td><a class="tpglink" href="{$team_info.website|escape}">{$team_info.website|escape}</a></td></tr>{/if*}
	<tr><td style="width: 100px">Captain:</td><td>{$roster_info[$team_info.captain_uid].firstname|escape} 
	{if $roster_info[$team_info.captain_uid].handle}
	"{$roster_info[$team_info.captain_uid].handle|escape}"
	{/if}
	{if $roster_info[$team_info.captain_uid].hide_lastname}
		{$roster_info[$team_info.captain_uid].lastname|truncate:2:"."|escape}
	{else}
		{$roster_info[$team_info.captain_uid].lastname|escape}
	{/if}
	</td></tr>
{*	<tr><td>IRC:</td><td><a href="irc://irc.gamesurge.net/{$team_info.irc|replace:'#':''|escape:'urlpathinfo'}">{$team_info.irc|escape}</a></td></tr>*}
	<tr><td style="width: 100px">Division:</td><td>{$team_info.division_title|escape}</td></tr>
	<tr><td style="width: 100px">Group:</td><td>{$team_info.group_title|escape}</td></tr>
	<tr><td style="width: 100px">Record:</td><td>{$season_record.wins|default:'0'}-{$season_record.losses|default:'0'}-{$season_record.ties|default:'0'}</td></tr>
    {if !$team_mini_panel}
    <tr><td style="width: 100px"><a class="gidlink" href="{$lgname}/join/{$smarty.get.tid}/">Join This Team</a></td></tr>
    {/if}
{*	<tr><td></td><td>{$team_info.organization_name|escape}</td></tr> *}
	
{*	<tr><td></td><td>{$team_info.ccode|escape}</td></tr>
	<tr><td></td><td>{$team_info.league_title|escape}</td></tr>
	<tr><td></td><td>{$team_info.conference_title|escape}</td></tr> *}
	</table>
    </td>
<td align="right" valign="top">
    {if $team_info.team_avatar_url}<img src="{$team_info.team_avatar_url|escape}" width="100px" height="56px">{/if}
</td>
</tr>
</table>
<br>

<div style="width:600px; overflow-x:auto ; overflow-y: hidden; padding-bottom:10px;">
	<table id="team_roster">
    <thead>
    <tr>
		<th>&nbsp;</th>
		<th>Name</th>
		<th>Handle</th>
		<th>{$team_info.gid_name}</th>
        <th>Join Date</th>
	</tr>
    </thead>
    <tbody>
	{foreach from=$roster_info key=uid item=player}
	<tr>
		<td>
		{if $player.ccode}
		<img src="/images/flags/{$player.ccode}.png" width="16" height="11" alt="{$player.ccode}" title="{$player.country}" /> 
		{else}
		&nbsp;
		{/if}
		</td>
		<td>{$player.firstname|escape} {if $player.hide_lastname}{$player.lastname|truncate:2:"."|escape}{else}{$player.lastname|escape}{/if}</td>
		<td><a class="gidlink" href="{$lgname}/user/{$uid}/">{$player.handle|escape}</a></td>
		<td{if $player.suspended} class="suspended"{/if}><a class="gidlink" href="{$lgname}/membersearch/?search={$player.gid|escape}&rosters_gid=on">{$player.gid|escape}</a></td>
        <td>{$player.join_date_gmt|date_format:"%D"}</td>
	</tr>
	{/foreach}
    </tbody>
	</table>
</div>


<div>


<table id="team_schedule">

<caption>{$season_title}</caption>
<tr>
<th>Week</th>
<th>Map</th>
<th>Away Team</th>
<th>Score</th>
<th>Home Team</th>
</tr>
{foreach from=$schedule_info item='match'}
{if empty($match.mid) && in_array($match.sch_id, $pending_info)}
	<tr>
		<td>{$match.stg_short_desc}</td>
		<td>{if $match.map_title}<a class="tpglink" href="{$lgname}/map/{$match.map_title}/">{$match.map_title|escape|default:'<i>TBA</i>'}</a>{else}{$match.map_title|escape|default:'<i>TBA</i>'}{/if}</td>
		<td colspan="3" align="center"><i>Opponent Pending</i></td>
	</tr>
{elseif !empty($match.mid)}
	<tr{if !$match.divisional_match} class="non-divisional"{/if}>
		<td>{$match.stg_short_desc}</td>
		<td>{if $match.map_title}<a class="tpglink" href="{$lgname}/map/{$match.map_title}/">{$match.map_title|escape|default:'<i>TBA</i>'}</a>{else}{$match.map_title|escape|default:'<i>TBA</i>'}{/if}</td>
		<td>
		{if $match.away_tid == 0}
			<i>Bye</i>
		{else}
			{if $match.away_tid != $smarty.get.tid}<a href="{$lgname}/team/{$match.away_tid}/">{$match.away_name|escape}</a>{else}{$match.away_name|escape}{/if}
		{/if}
		</td>
		<td>
		{if $match.forfeit_loss}
		<a class="tpglink" href="{$lgname}/match/{$match.mid}/" style="color:red">FF Loss</a>
		{elseif $match.forfeit_win}
		<a class="tpglink" href="{$lgname}/match/{$match.mid}/" style="color:blue">FF Win</a>
		{elseif ($match.win_tid == $smarty.const.TID) && ($match.away_tid == 0 || $match.home_tid == 0)}
		Win
		{else}
        {assign var=scorefound value=''}
		{foreach from=$match_scores item='scores' key='matchscoreid'}{if $matchscoreid == $match.mid && $scores.home_score != NULL}{assign var=scorefound value='yes'}{if $scores.win}<span style="color:blue">W</span>&nbsp;{else}<span style="color:red">L</span>&nbsp;{/if}<a class="tpglink" href="{$lgname}/match/{$match.mid}/">{$scores.away_score}-{$scores.home_score}</a>{/if}{/foreach}
		{if $scorefound == ''}<span style="font-size: smaller">Match# {$match.mid}</span>{/if}
        {/if}
		</td>
		<td>
		{if $match.home_tid == 0}
			<i>Bye</i>
		{else}
			{if $match.home_tid != $smarty.get.tid}<a href="{$lgname}/team/{$match.home_tid}/">{$match.home_name|escape}</a>{else}{$match.home_name|escape}{/if}
		{/if}
		</td>
	</tr>
{/if}
{foreachelse}
<tr>
<td colspan="5" align="center">No matches scheduled for this season.</td>
</tr>
{/foreach}
</table>

</div>






{else}
<p>Team does not exist or has been removed.</p>
{/if}

</div>
