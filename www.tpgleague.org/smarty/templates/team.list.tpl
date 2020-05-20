<div style="margin-left: 10%; margin-right: 10%;">

<table id="tblTeamList" class="display">
<thead>
<tr>
<th>Name</th>
<th>Tag</th>
<th>Division</th>
<th>Group</th>
</tr>
</thead>
<tbody>
{foreach from=$team_list item=team}
<tr>
<td><a href="{$lgname}/team/{$team.tid}/">{$team.name|escape}</a></td>
<td><a href="{$lgname}/team/{$team.tid}/">{$team.tag|escape}</a></td>
<td>{$team.division_title|default:'Unassigned'|escape}</td>
<td>{$team.group_title|default:''|escape}</td>
</tr>
{foreachelse}
<tr><td colspan="3">No teams</td></tr>
{/foreach}
</tbody>
</table>

</div>

