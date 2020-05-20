<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">TPG Pending List</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">
						<h4 class="headline"> Pending Approval</h4>
						<p>
<p>
All TPG admins may approve teams in any league (and are encouraged to help with this as much as possible). Approval means that the team shows up in the Join Team dropdown list and such on the frontend. Approvals are based simply on whether the team has an acceptable team name/tag. Rule 3.3 states:

	<blockquote>
	Team names including, but not limited to the following content will not be permitted: profanity, bigotry, or hate to any race, sex, or religious group; drug use, and other content deemed inappropriate by the League.
	</blockquote>
</p>

<form action="/pending.approval.php" method="post" >

<table class="clean">
<tr>
<th>Approve</th>
<th>League</th>
<th>Team Name</th>
<th>Team Tag</th>
<th>Create Date</th>
<th>Owner</th>
<th>Captain</th>
</tr>

{foreach from=$teams_pending_approval item='team'}
<tr>
	<td><input type="checkbox" name="{$team.tid}" /></td>
	<td><a href="/teams.manager.php?lid={$team.lid}">{$team.lgname}</a></td>
	<td><a href="/edit.team.php?tid={$team.tid}">{$team.name|escape}</a></td>
	<td>{$team.tag|escape}</td>
	<td>{$team.unix_create_date_gmt|converted_timezone}</td>
	<td>
		{$team_contact[$team.tid].owner.firstname|escape}
		{if $team_contact[$team.tid].owner.handle}"{$team_contact[$team.tid].owner.handle|escape}"{/if}
		{$team_contact[$team.tid].owner.lastname|escape}
		[<a href="/edit.user.php?uid={$team_contact[$team.tid].owner.uid}">{$team_contact[$team.tid].owner.username|escape}</a>]
		{$team_contact[$team.tid].owner.email}
	</td>
	<td>
		{$team_contact[$team.tid].captain.firstname|escape}
		{if $team_contact[$team.tid].captain.handle}"{$team_contact[$team.tid].captain.handle|escape}"{/if}
		{$team_contact[$team.tid].captain.lastname|escape}
		[<a href="/edit.user.php?uid={$team_contact[$team.tid].captain.uid}">{$team_contact[$team.tid].captain.username|escape}</a>]
		{$team_contact[$team.tid].captain.email}
	</td>
</tr>
{/foreach}

<tr><td colspan="5"><input type="submit" value="Approve Checkmarked Teams" /></td></tr>
</table>

</form>
						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->


