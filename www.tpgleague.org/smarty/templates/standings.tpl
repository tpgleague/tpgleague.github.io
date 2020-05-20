{if $standings_league_title}
<div id="standings">
<h2 style="text-align: center;">{$standings_season_title} Standings</h2>
{foreach from=$standings_divisions key=divid item=division}
<div class="division">
<h2 class="division_title">{$division.division_title|escape}</h2>


		<div class="conference">
		{foreach from=$standings_conferences[$divid] item=conference name=conference}
			{if $smarty.foreach.conference.total > 1}
				<h3 class="conference_title">{$conference.conference_title|escape}</h3>
			{/if}

			<div class="group">
			{foreach from=$standings_groups[$conference.cfid] item=group}
				<table summary="{$group.group_title|escape} group standings." class="group">
					<caption>{$group.group_title|escape}</caption>
					<colgroup span="1" style="width: 30px;" align="left"></colgroup>
					<colgroup span="1" class="team_name" align="left"></colgroup>
					<colgroup span="1" class="team_tag" align="left"></colgroup>
					<colgroup span="3" class="rem_cols" style="width: 20px;" align="right"></colgroup>
					<colgroup span="1" class="rem_cols" style="width: 40px;" align="right"></colgroup>
					<colgroup span="2" class="rem_cols" style="width: 20px;" align="right"></colgroup>
					<colgroup span="3" class="rem_cols" style="width: 40px;" align="right"></colgroup>
					<thead>
						<tr> 
							<th title="Rank" style="text-align: left;">#</th>
							<th title="Team Name" style="text-align: left;">Team</th>
							<th title="Team Tag" style="text-align: left;">Tag</th>
							<th title="Wins" style="text-align: right;">W</th>
							<th title="Losses" style="text-align: right;">L</th>
							<th title="Ties" style="text-align: right;">T</th>
							<th title="Win Percentage" style="text-align: right;">Pct</th>
							<th title="Forfeit Wins" style="text-align: right;">FFW</th>
							<th title="Forfeit Losses" style="text-align: right;">FFL</th>
							<th title="Points For" style="text-align: right;">PF</th>
							<th title="Points Against" style="text-align: right;">PA</th>
							<th title="Points Difference" style="text-align: right;">PD</th>
						</tr>
					</thead>
					<tbody>
						{assign var='rankno' value=1}
						{assign var='loopno' value=1}
						{foreach from=$standings_teams[$group.grpid] item=team}
						<tr class="{cycle name=$group.grpid values="zebra-odd,zebra-even"}" title="{$team.name|escape}" onclick="location.href='{$lgname}/team/{$team.tid}/'">
							{if ($team.wins != $last_wins) || ($team.losses != $last_losses) || ($team.ties != $last_ties)}
								{assign var='rankno' value=$loopno}
							{/if}
							{assign var='loopno' value=$loopno+1}
							{assign var='last_wins' value=$team.wins}
							{assign var='last_losses' value=$team.losses}
							{assign var='last_ties' value=$team.ties}
							<td>{if $team.matches_played}{$rankno}{else}-{/if}</td>
							<td><a href="{$lgname}/team/{$team.tid}/">{$team.name|truncate:60:''|escape}</a></td>
							<td>{$team.tag|truncate:30:''|escape}</td>
							<td style="text-align: right;">{if $team.matches_played}{$team.wins}{else}-{/if}</td>
							<td style="text-align: right;">{if $team.matches_played}{$team.losses}{else}-{/if}</td>
							<td style="text-align: right;">{if $team.matches_played}{$team.ties}{else}-{/if}</td>
							<td style="text-align: right;">
								{if $team.matches_played && ($team.wins || $team.losses)}
									{if !$team.losses}
										1.000
									{else}
										{math assign='win_pct' equation='wins/(wins+losses)' wins=$team.wins losses=$team.losses}
										{$win_pct|string_format:'%0.3f'|substr:1:4}
									{/if}
								{else}
									-
								{/if}
							</td>
							<td style="text-align: right;">{if $team.forfeit_wins}{$team.forfeit_wins}{else}-{/if}</td>
							<td style="text-align: right;">{if $team.forfeit_losses}{$team.forfeit_losses}{else}-{/if}</td>
							<td style="text-align: right;">{if $team.matches_played}{$team.points_for|default:'0'}{else}-{/if}</td>
							<td style="text-align: right;">{if $team.matches_played}{$team.points_against|default:'0'}{else}-{/if}</td>
							<td style="text-align: right;">{if $team.matches_played}{$team.points_difference|default:'0'}{else}-{/if}</td>
						</tr>
						{foreachelse}
						<tr> <td colspan="11" class="teams_empty">No teams</td> </tr>
						{/foreach}
					</tbody>
				</table>
			{foreachelse}
			No groups
			{/foreach}
			</div>

		{foreachelse}
		No conferences
		{/foreach}
		</div>

</div>

{foreachelse}
No divisions
{/foreach}


</div>
{else}
<div id="standings" style="width: 165px;">
League not found.
</div>
{/if}
