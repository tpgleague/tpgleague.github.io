<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">TPG League List</h2>
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

<table class="clean">
  <tr>
	<th></th>
	<th align="left">League</th>
    <th align="left">Short Name</th> 
    <th align="left">Description</th>
    {*<th align="left">Game</th>*}
    <th align="left">Current Season</th>
    <th align="left">League ID</th>
    <th align="left">Active</th>
    <th align="left">Rosters Lock</th>
    <th align="left">Linked To</th>
    <th align="left">Show Rules</th>
    <th align="left">Rules Last Updated</th>
	<th align="left">Create Date</th>
  </tr>

{foreach item=league from=$leagues_list}
<tr>
  <td><a href="/edit.league.php?lid={$league.lid}">Edit</a></td>
  <td>{$league.league_title|escape}</td>
  <td><a href="http://www.tpgleague.org/{$league.lgname}/">{$league.lgname}</a></td> 
  <td>{$league.description}</td>
  {*<td>{$league.game_name}</td>*}
  <td>{if $league.sid != ''}<a href="http://backend.tpgleague.org/edit.schedule.php?sid={$league.sid}">({$league.season_number}) {$league.season_title}</a>{/if}</td>
  <td>{$league.lid}</td>
  <td>{if ($league.inactive)}Inactive{else}Active{/if}</td>
  <td><a href="http://backend.tpgleague.org/edit.roster.lock.php?lid={$league.lid}">{$league.roster_lock}</a></td>
  <td><a href="http://backend.tpgleague.org/edit.league.php?lid={$league.linked_lid}">{$league.linked_lid}</a></td>
  <td><a href="http://backend.tpgleague.org/edit.rules.php?lid={$league.lid}">{if ($league.show_rules)}Yes{else}No{/if}</a></td>
  <td>{if $league.last_rule_update_gmt}{$league.last_rule_update_gmt|converted_timezone}{/if}</td>
  <td>{$league.create_date_gmt|converted_timezone}</td>
</tr>
{/foreach}

</table>
<br/>
{if $add_league_form}
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Add New League</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
<form {$add_league_form.attributes}>
{$add_league_form.hidden}

{quickform_fieldset form=$add_league_form id='fieldset_add_league' class='qffieldset' fields='league_title, lgname, gid_type, gid_name, inactive, admin, max_schedulers, max_reporters, side_one, side_two, submit' }
</form>
{/if}
						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->


