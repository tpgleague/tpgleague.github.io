<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">{$league}League Information</h2>
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
<div class="sixteen columns"/>
					<div id="navigation" />
						<ul id="nav">

<li><a href="/teams.manager.php?lid={$smarty.get.lid}"><img src="images/buttons/teams.gif" border="none" alt="logo"></a></li>
<li><a href="/edit.season.php?lid={$smarty.get.lid}"><img src="images/buttons/seasons.gif" border="none" alt="logo"></a></li>
<li><a href="/maps.manager.php?lid={$smarty.get.lid}"><img src="images/buttons/maps.gif" border="none" alt="logo"></a></li>
<li><a href="/edit.rules.php?lid={$smarty.get.lid}"><img src="images/buttons/rules.gif" border="none" alt="logo"></a></li>
<li><a href="/query.selector.php?lid={$smarty.get.lid}"><img src="images/buttons/query.gif" border="none" alt="logo"></a></li>
<li><a href="/edit.roster.lock.php?lid={$smarty.get.lid}"><img src="images/buttons/rosterlock.gif" border="none" alt="logo">({$roster_lock|capitalize})</a></li>
</ul>
</div>
</div>
					
<br />



<form {$edit_league_form.attributes}>
{$edit_league_form.hidden}

{quickform_fieldset form=$edit_league_form id='fieldset_edit_league' class='qffieldset' fields='lid, league_title, lgname, description, format, admin, sort_order, gid_type, gid_name, tzid, default_start_time, default_match_days, roster_lock_hours, roster_lock_playoff_matches, disputes_per_season, inactive, show_rules, create_date_gmt, scoring_description, league_type, max_schedulers, max_reporters, map_pack_download_url, config_pack_download_url, linked_lid, submit' legend='Edit League'}
</form>

<br />
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Divisions</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
<table>
  <tr>
	<th></th>
	<th>Division</th>
	<th>Create Date</th>
  </tr>

{foreach item=division from=$divisions_list}
<tr>
  <td><a href="/edit.division.php?divid={$division.divid}">Edit</a></td>
  <td>{$division.division_title|escape}</td>
  <td>{$division.create_date_gmt|date_format:'%Y-%m-%d %H:%M:%S %Z'}</td>
</tr>
{foreachelse}
<tr><td colspan="3">No divisions</td></tr>
{/foreach}

</table>
<br />
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Add New Division</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>

<form {$add_division_form.attributes}>
{$add_division_form.hidden}
{quickform_fieldset form=$add_division_form id='fieldset_add_division' class='qffieldset' fields='division_title, admin, submit' }
</form>

						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->


