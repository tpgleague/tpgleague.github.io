<p><a href="/edit.league.php?lid={$smarty.const.LID}">Return to {$league_info.league_title|escape} league management.</a></p>

<table id="disputes">

	<tr>
		<th>DisputeID</th>
		<th>MatchID</th>
		<th>Away Team</th>
		<th>Home Team</th>
		<th>Dispute Type</th>
		<th>Disputed Player</th>
		<th>Create Date</th>
		<th>Create Team</th>
		<th>Status</th>
	</tr>

{foreach from=$disputes item='dispute'}
	<tr>
		<td><a href="/view.dispute.php?did={$dispute.did}">{$dispute.did}</a></td>
		<td>{$dispute.mid}</td>
		<td><a href="/edit.team.php?tid={$dispute.away_tid}">{$dispute.away_name}</a></td>
		<td><a href="/edit.team.php?tid={$dispute.home_tid}">{$dispute.home_name}</a></td>
		<td>{$dispute.dispute_type}</td>
		<td>{$dispute.disputed_uid}</td>
		<td>{$dispute.unix_create_date_gmt}</td>
		<td>{$dispute.create_team_name}</td>
		<td>{$dispute.dispute_outcome}</td>
	</tr>
{foreachelse}
<tr><td colspan="9">No disputes to list.</td></tr>
{/foreach}

</table>

