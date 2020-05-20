{if $poll_info}

<table class="clean">
<caption>{$poll_info.title|escape} (Closes: {$poll_info.expire_date_gmt_unix|converted_timezone})</caption>
<thead>
	<tr>
		<th>Choice</th>
		<th>Votes</th>
		<th>%</th>
	</tr>
</thead>
{foreach from=$poll_results item='result'}
	<tr>
		<td>{$result.name|escape}</td>
		<td>{$result.votes}</td>
		<td>{if $poll_votes}{$result.votes/$poll_votes*100|round}%{/if}</td>
	</tr>
{/foreach}
	<tr>
		<td align="right">Total:</td>
		<td>{$poll_votes}</td>
		<td>&nbsp;</td>
	</tr>
</table>

{else}
	<p>No poll with that ID found.</p>
{/if}