{if $winner_name}
Your match has been recorded as a win for {$winner_name|escape}.
{elseif $tie}
Your match has been recorded as a tie.
{elseif $ff_message}
{$ff_message}
{else}


<h3 style="text-align: center;font-size: 14px;">Report Forfeit Win/Loss:</h3>

<div style="width: 300px; margin-left: auto; margin-right: auto; text-align: center;">

	{if $away_can_ff}
	{if $away_tid == $smarty.const.TID}
		{assign var='report_msg_status' value='LOSE'}
	{else}
		{assign var='report_msg_status' value='WIN'}
	{/if}
	<form action="/report.match.php?mid={$smarty.get.mid}&amp;tid={$smarty.get.tid}" method="post" id="report_match_forfeit_away" onsubmit="return confirm('You {$report_msg_status} this match. Is this Correct?');" >
	<input type="hidden" name="forfeit" value="{$away_tid}" />
	<input type="submit" style="border-width: 2px; border-style: outset;" name="submit" value="{$away_name|escape} forfeits" />
	</form>
	{/if}

	{if $away_can_ff && $home_can_ff}
	<p>- OR -</p>
	{else}
	<p>You may not give your opponent a forfeit loss unless they have not responded to your <a href="/schedule.match.php?mid={$smarty.const.MID}&amp;tid={$smarty.const.TID}">TPG Scheduler</a> attempts and it is within 24 hours of the default match time.</p>
	{/if}

	{if $home_can_ff}
	{if $home_tid == $smarty.const.TID}
		{assign var='report_msg_status' value='LOSE'}
	{else}
		{assign var='report_msg_status' value='WIN'}
	{/if}
	<form action="/report.match.php?mid={$smarty.get.mid}&amp;tid={$smarty.get.tid}" method="post" id="report_match_forfeit_home" onsubmit="return confirm('You {$report_msg_status} this match. Is this Correct?');" >
	<input type="hidden" name="forfeit" value="{$home_tid}" />
	<input type="submit" style="border-width: 2px; border-style: outset;" name="submit" value="{$home_name|escape} forfeits" />
	</form>
	{/if}

</div>


<h3 style="text-align: center;font-size: 14px;">Report Played Match:</h3>

{if $match_start_time > $smarty.now}
<div>
<p>You may not report scores for this match as it is not scheduled to have been played yet.</p>
</div>
{else}
<div>

	{if $error_message}
	<div style="width: auto; margin: 5px auto; padding: 7px; border: 1px solid red;">
	{foreach from=$error_message item='errormsg'}
	<br />{$errormsg}
	{/foreach}
	</div>
	{/if}

	<form action="/report.match.php?mid={$smarty.get.mid}&amp;tid={$smarty.get.tid}" method="post" id="report_match_form">


	<table id="report_match_table">
	<tbody>
	<tr>
	<th colspan="3" style="text-align:center;">First Half</th></tr>
	<tr>
	<th>Side</th>
	<th>Team</th>
	<th align="right">Score</th>
	</tr>
	<tr>
	<td id="h1a_side" class="sidecol"><select name="side_selector_h1a" onchange="changeSides('h1a');">
	<option value="">Select Side</option>
	{foreach from=$sides item='side'}
	<option {if $smarty.post.side_selector_h1a == $side.lsid}selected{/if} value="{$side.lsid}">{$side.side}</option>
	{/foreach}</select></td>
	<td>{$away_name|escape}</td>
	<td align="center" class="scorecol"><input type="text" size="3" value="{$smarty.post.h1a_score}" maxlength="5" name="h1a_score" id="h1a_score" /></td>
	</tr>
	<tr>
	<td id="h1h_side" class="sidecol"><select name="side_selector_h1h" onchange="changeSides('h1h');">
	<option value="">Select Side</option>
	{foreach from=$sides item='side'}
	<option {if $smarty.post.side_selector_h1h == $side.lsid}selected{/if} value="{$side.lsid}">{$side.side}</option>
	{/foreach}</select></td>
	<td>{$home_name|escape}</td>
	<td align="center" class="scorecol"><input type="text" size="3" maxlength="5" name="h1h_score" value="{$smarty.post.h1h_score}" id="h1h_score" /></td>
	</tr>
	</tbody>

	<tbody>
	<tr>
	<th colspan="3" style="text-align:center;">Second Half</th></tr>
	<tr>
	<th>Side</th>
	<th>Team</th>
	<th>Score</th>
	</tr>
	<tr>
	<td id="h2a_side" class="sidecol"><select name="side_selector_h2a" onchange="changeSides('h2a');">
	<option value="">Select Side</option>
	{foreach from=$sides item='side'}
	<option {if $smarty.post.side_selector_h2a == $side.lsid}selected{/if} value="{$side.lsid}">{$side.side}</option>
	{/foreach}</select></td>
	<td>{$away_name|escape}</td>
	<td align="center" class="scorecol"><input type="text" size="3" maxlength="5" value="{$smarty.post.h2a_score}" name="h2a_score" id="h2a_score" /></td>
	</tr>
	<tr>
	<td id="h2h_side" class="sidecol"><select name="side_selector_h2h" onchange="changeSides('h2h');">
	<option value="">Select Side</option>
	{foreach from=$sides item='side'}
	<option {if $smarty.post.side_selector_h2h == $side.lsid}selected{/if} value="{$side.lsid}">{$side.side}</option>
	{/foreach}</select></td>
	<td>{$home_name|escape}</td>
	<td align="center" class="scorecol"><input type="text" size="3" maxlength="5" value="{$smarty.post.h2h_score}" name="h2h_score" id="h2h_score" /></td>
	</tr>
	</tbody>

	<tbody>
	<tr>
	<td class="sidecol">Comment:</td>
	<td colspan="2"><input type="text" style="width: 99%;" maxlength="250" value="{$smarty.post.comments|escape}" name="comments" id="comments" /></td>
	</tr>
	<tr>
	<td colspan="3" align="center"><input type="submit" style="border-width: 2px; border-style: outset;" name="submit" id="submit" value="Report Score" /></td>
	</tr>
	</tbody>
	</table>



	</form>
</div>
{/if}

{/if}


<div>
<p>For questions or concerns with reporting your match, please file a <a href="http://support.tpgleague.org/">support ticket</a>.</p>
</div>