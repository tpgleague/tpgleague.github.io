{if $standings_league_title}
<h1 class="rubberhdr"><span>{$standings_league_title}</span></h1>
<div id="standings">
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
					<colgroup span="1" width="*" align="left"></colgroup>
					<colgroup span="2" width="10" align="center"></colgroup>
					<thead>
						<tr> <th class="team">Team</th> <th>W</th> <th>L</th> </tr>
					</thead>
					<tbody>
						{foreach from=$standings_teams[$group.grpid] item=team}
						<tr class="{cycle name=$group.grpid values="zebra-odd,zebra-even"}" title="{$team.name|escape}" onclick="location.href='{$lgname}/team/{$team.tid}/'">
							<td><a href="{$lgname}/team/{$team.tid}/">{$team.tag|truncate:20:''|escape}</a></td>
							<td>{$team.wins}</td>
							<td>{$team.losses}</td>
						</tr>
						{foreachelse}
						<tr> <td colspan="3" class="teams_empty">No teams</td> </tr>
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
&nbsp;
</div>
{/if}