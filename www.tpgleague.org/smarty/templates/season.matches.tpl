
<table id="matchlist">
<caption>Matchlist</caption>
<tr>
<th>Date</th>
<th>Time</th>
<th>Map</th>
<th>Opponent</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
</tr>
{foreach from=$season_opponents item='match'}
{if $match.away_tid == $smarty.const.TID}
	{assign var='opponent_tid' value=$match.home_tid}
{else}
	{assign var='opponent_tid' value=$match.away_tid}
{/if}
<tr>
<td>{$match.start_date_gmt|easy_day}</td>
<td>{$match.start_date_gmt|easy_time}</td>
<td>{$match.map_title|escape|default:'<i>TBA</i>'}</td>
<td>
{if $match.opponent_name}
	{if $match.away_tid == $smarty.const.TID}at {/if}
	<a href="{$lgname}/team/{$opponent_tid}/">{$match.opponent_name|escape}</a>
{else}
	<i>Bye</i>
{/if}
</td>
<td>{if $match.report_date_gmt == '0000-00-00 00:00:00'}<a href="/schedule.match.php?mid={$match.mid}&amp;tid={$smarty.get.tid}">Schedule</a>{else}<span class="greyed-out">Schedule</span>{/if}</td>
<td>{if $match.report_date_gmt == '0000-00-00 00:00:00'}<a href="/report.match.php?mid={$match.mid}&amp;tid={$smarty.get.tid}">Report</a>{else}<span class="greyed-out">Report</span>{/if}</td>
<td>{if $match.opponent_name}<a href="http://www.tpgleague.org/support/index.php?a=add&amp;catid=4&amp;mid={$match.mid}">Dispute</a>{else}<span class="greyed-out">Dispute</span>{/if}</td>
</tr>
{foreachelse}
<tr><td colspan="7" align="center">You have not been scheduled for any matches this season.</td></tr>
{/foreach}
</table>