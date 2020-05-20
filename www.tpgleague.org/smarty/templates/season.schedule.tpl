{if isset($week_schedule)}
<div>
<table id="schedule">
<colgroup>
<col />
<col />
<col align="char" char="-" />
<col />
</colgroup>
	<tr>
	<th>Time</th>
	<th>Away Team</th>
	<th>&nbsp;</th>
	<th>Home Team</th>
	</tr>
{foreach from=$week_schedule item='match'}
	<tr>
		<td>{$match.unix_start_date_gmt|custom_date:'M j, g:i'}</td>
		<td{if $match.win_tid == $match.away_tid} class="winner"{/if}>{if $match.away_tid}<a href="{$lgname}/team/{$match.away_tid}/" title="{$match.away_name|escape}">{/if}{$match.away_name|truncate:30:''|escape|default:'<i>Bye</i>'}{if $match.away_tid}</a>{/if}</td>
		<td>
		{if $match.report_date_gmt != '0000-00-00 00:00:00'}
			{if $match.forfeit_home || $match.forfeit_away}
				<a class="tpglinkalt" href="{$lgname}/match/{$match.mid}/">FF</a>
			{else}
				<a class="tpglinkalt" href="{$lgname}/match/{$match.mid}/">{$match.away_score}-{$match.home_score}</a>
			{/if}
		{else}
			&nbsp;
		{/if}
		</td>
		<td{if $match.win_tid == $match.home_tid} class="winner"{/if}>{if $match.home_tid}<a href="{$lgname}/team/{$match.home_tid}/" title="{$match.home_name|escape}">{/if}{$match.home_name|truncate:30:''|escape|default:'<i>Bye</i>'}{if $match.home_tid}</a>{/if}</td>
	</tr>
{foreachelse}
<tr>
<td colspan="4">No matches scheduled.</td></tr>
{/foreach}
{foreach from=$week_schedule_pending item='pending'}
<tr>
	<td>&nbsp;</td>
	<td><a href="{$lgname}/team/{$pending.tid}/" title="{$pending.name|escape}">{$pending.name|escape}</a></td>
	<td>&nbsp;</td>
	<td><i>Opponent Pending</i></td>
</tr>
{/foreach}
</table>
</div>
{else}
<div>
<table id="schedule">
<thead>
<caption>{$active_season_title} Schedule</caption>
</thead>
	<tr>
		<th>Date</th>
		<th>Map</th>
		<th>Week</th>
	</tr>
<tbody>
{foreach from=$season_schedule item='schedule'}
{if $schedule.display_preseason || $schedule.stg_type != 'Preseason'}
	<tr>
		<td style="white-space: nowrap;">{$schedule.unix_stg_match_date_gmt|easy_day} {$schedule.unix_stg_match_date_gmt|easy_time}</td>
		<td>{if $schedule.stg_type != 'Holiday'}     {if $schedule.map_title}<a class="tpglink" href="{$lgname}/map/{$schedule.map_title}/">{$schedule.map_title|escape|default:'<i>TBA</i>'}</a>{else}{$schedule.map_title|escape|default:'<i>TBA</i>'}{/if}        {/if}</td>
		{if $schedule.matches_scheduled}
		<td><a href="{$lgname}/schedule/{$schedule.sch_id}/">{$schedule.stg_short_desc}</a></td>
		{else}
		<td>{$schedule.stg_short_desc}</td>
		{/if}
	</tr>
{/if}
{foreachelse}
<tr><td colspan="3">No active season created.</td></tr>
{/foreach}
</tbody>
</table>
</div>
{/if}