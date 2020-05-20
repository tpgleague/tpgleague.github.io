<!-- Begin Container -->
			<div class="container2">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Query Parameters</h2>
					<div class="bold-border-bottom"></div>
				</div>
				
				
				<!-- Begin Siderbar -->
				<div class="four columns">


	<p>
<form method="get" action="/query.selector.php">
<input type="hidden" name="lid" value="{$smarty.const.LID}" />
<select name="query">
    <option value="joined_roster_in_last_month" {if $smarty.get.query == 'joined_roster_in_last_month'}selected="selected"{/if}>Players who joined a roster in the last month</option>
    <option value="left_roster_in_last_month" {if $smarty.get.query == 'left_roster_in_last_month'}selected="selected"{/if}>Players who left a roster in the last month</option>
	<option value="captains_inactive" {if $smarty.get.query == 'captains_inactive'}selected="selected"{/if}>Captains from inactive teams</option>
	<option value="captains_division_unassigned" {if $smarty.get.query == 'captains_division_unassigned'}selected="selected"{/if}>Captains from unassigned division</option>
	<option value="captains_active" {if $smarty.get.query == 'captains_active'}selected="selected"{/if}>Captains from active+approved+assigned teams</option>
	<option value="captains_active_no_forfeits" {if $smarty.get.query == 'captains_active_no_forfeits'}selected="selected"{/if}>Captains from active teams no forfeit losses (matches played > 0)</option>
	<option value="teams_no_captains" {if $smarty.get.query == 'teams_no_captains'}selected="selected"{/if}>Team members having no captain</option>
	<option value="players_in_unassigned_group" {if $smarty.get.query == 'players_in_unassigned_group'}selected="selected"{/if}>Players on teams who are not in a group</option>
</select>
<br /><input type="submit" value="Run Query" />
</form>
							</p>

							
					
				</div>
				<!-- End Sidebar -->
<br />	<br />				
				<!-- Begin Posts -->
				<div class="twelve columns">
					<!-- Post with image -->
					<div class="post post-page">

					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Query Results</h2>
					<div class="bold-border-bottom"></div>
						<div class="clear"></div>
						<div class="post-description">
						
						<p>
{if isset($qs_results)}


<table class="clean">
<tr>
	<th>Team Name</th>
	<th>Team Tag</th>
	<th>Team Created</th>
	<th>Org IRC</th>
	<th>Roster Size</th>
	<th>Approved</td>
	<th>Active</td>
	<th>Division</th>
	<th>Conference</th>
	<th>Group</th>
	<th>Username</th>
	<th>E-mail</th>
	<th>First Name</th>
	<th>Last Name</th>
	<th>Roster Handle</th>
</tr>
{foreach from=$qs_results item='result'}
<tr>
<td><a href="/edit.team.php?tid={$result.tid}">{$result.name|escape}</a></td>
<td>{$result.tag|escape}</td>
<td>{$result.unix_create_date_gmt|easy_day}</td>
<td>{$result.irc|escape}</td>
<td>{$result.roster_count}</td>
<td>{if $result.approved}Y{else}-{/if}</td>
<td>{if $result.inactive}I{else}A{/if}</td>
<td>{$result.division_title|escape}</td>
<td>{$result.conference_title|escape}</td>
<td>{$result.group_title|escape}</td>
<td><a href="/edit.user.php?uid={$result.uid}">{$result.username|escape}</a></td>
<td>{$result.email|escape}</td>
<td>{$result.firstname|escape}</td>
<td>{$result.lastname|escape}</td>
<td>{$result.handle|escape}</td>
</tr>
{foreachelse}
<tr>
<td colspan="14">No results to display.</td>
</tr>
{/foreach}


{/if}
</table>
							</p>
							
						</div>
					</div>
				</div>
</div>

			</div>






