<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Season Manager</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">

						<p>
<div>
<p>Season/Pre-season snapshots do the following: Saves the division/conference/group assignments for all teams in the league so that we have a historical archive of what the standings for each past season looked like.  The win/loss records for each individual team may still be changed at any time by updating matches in the schedule editor.</p>
<p>For this reason, you do not have to wait until all matches have been reported (say, after pre-season but before regular season has been scheduled) to take the snapshot.  You should take the snapshot immediately before doing final moveups/movedowns after preseason/regular season.</p>
<p>This form doesn't behave like a true form should... The Save button only applies to the two Roster Lock fields.</p>
</div>

<div>
<p style="color: red;">{$season_error}</p>
<div>

<table class="clean">
<tr style="height: 4em;">
<th>&nbsp;</th>
<th>Number</th>
<th>Title</th>
<th>Create Date</th>
<th>Lock rosters X hours before match start time</th>
<th>Perm-Lock rosters upon conclusion of this week's match</th>
<th>&lt;--</th>
<th>Activate</th>
<th>Display Preseason</th>
<th>Preseason Snapshot</th>
<th>Reg. Season Snapshot</th>
</tr>
{foreach from=$seasons_list item='season'}
<tr>

<td><a href="/edit.schedule.php?sid={$season.sid}">Edit</a></td>
<td>{$season.season_number}</td>
<td>{$season.season_title|escape}</td>
<td>{$season.create_date_gmt}</td>

<form action="/edit.season.php?lid={$smarty.const.LID}" method="post" >
<td>
	<input type="text" size="3" maxlength="3" style="width: 30px;" name="roster_lock_hours" value="{$season.roster_lock_hours}" /> hours
</td>

<td>
	<select name="roster_lock_playoffs_sch_id">
		<option value="">[None]</option>
		{foreach from=$schedule_data[$season.sid] item='schedule'}
			<option value="{$schedule.sch_id}"
			{if $season.roster_lock_playoffs_sch_id == $schedule.sch_id}selected="selected"{/if}>{$schedule.stg_short_desc|escape}</option>
		{/foreach}
	</select>
</td>

<td>
	<input type="hidden" name="sid" value="{$season.sid}" />
	<input type="submit" value="Save" style="width: 50px;" />
</td>
</form>

<td>
	{if !$season.active}
	<form method="post" action="/edit.season.php?lid={$smarty.get.lid}">
		<input type="hidden" name="activate_season" value="{$season.sid}" />
		<input type="submit" value="Activate" style="width: auto; height: auto; align: center; margin:0;" />
	</form>
	{/if}
</td>

<td>{if $season.active}
	<form method="post" action="/edit.season.php?lid={$smarty.get.lid}">
		<input type="hidden" name="toggle_preseason" value="{$season.sid}" />
		<input type="submit" name="toggle_preseason_value" value="{if $season.display_preseason}Turn Off{else}Turn On{/if}" style="width: auto; height: auto; align: center; margin:0;" />
	</form>
	{/if}
</td>

<td>{if $season.preseason_close_date_gmt}
		<a href="/historical.standings.php?sid={$season.sid}&amp;preseason=1">Taken</a>
	{else}
	<form method="post" action="/edit.season.php?lid={$smarty.get.lid}">
		<input type="hidden" name="close_preseason" value="{$season.sid}" />
		<input type="submit" value="Take Snapshot" style="width: auto; height: auto; align: center; margin:0;" />
	</form>
	{/if}
</td>

<td>{if $season.season_close_date_gmt}
		<a href="/historical.standings.php?sid={$season.sid}">Taken</a>
	{else}
	<form method="post" action="/edit.season.php?lid={$smarty.get.lid}">
		<input type="hidden" name="close_season" value="{$season.sid}" />
		<input type="submit" value="Take Snapshot" style="width: auto; height: auto; align: center; margin:0;" />
	</form>
	{/if}
</td>

</tr>
{foreachelse}
<tr><td colspan="9">No seasons</td></tr>
{/foreach}
</table>
<br /><br />
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Add Season</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>

<form {$add_season_form.attributes}>
{$add_season_form.hidden}

{quickform_fieldset form=$add_season_form id='fieldset_add_season' class='qffieldset' fields='season_title, season_number, submit' }
</form>

						</p>
					</div>	
				</div>
				
			</div>
</div>
</div>
<!-- End Container -->
