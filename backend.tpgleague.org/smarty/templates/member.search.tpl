<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Member Search</h2>
					<div class="bold-border-bottom"></div>
				</div>
				
				
				<!-- Begin Siderbar -->
				<div class="four columns">


						<h3 class="headline"> Parameters</h3>

							<p>

<form method="get" action="/member.search.php">

<input type="text" onfocus="if(this.value == 'Search..') this.value = ''" onblur="if(this.value=='')this.value='Search..';" value="Search.." size="80" maxlength="255" name="search" value="{$smarty.get.search}" />

<br /><label for="users_username">Username</label> <input type="checkbox" name="users_username" {if $smarty.get.users_username}checked="checked"{/if} id="users_username"   />
<br /><label for="users_handle">Main Handle</label> <input type="checkbox" name="users_handle" {if $smarty.get.users_handle}checked="checked"{/if} id="users_handle"   />
<br /><label for="users_email">E-mail Address</label> <input type="checkbox" name="users_email" {if $smarty.get.users_email}checked="checked"{/if}  id="users_email"   />
<br /><label for="users_firstname">First Name</label> <input type="checkbox" name="users_firstname" {if $smarty.get.users_firstname}checked="checked"{/if} id="users_firstname"   />
<br /><label for="users_lastname">Last Name</label> <input type="checkbox" name="users_lastname" {if $smarty.get.users_lastname}checked="checked"{/if} id="users_lastname"   />
<br /><label for="rosters_handle">Roster Handle</label> <input type="checkbox" name="rosters_handle" {if $smarty.get.rosters_handle}checked="checked"{/if} id="rosters_handle"   />
<br /><label for="rosters_gid">Game ID</label> <input type="checkbox" name="rosters_gid" {if $smarty.get.rosters_gid}checked="checked"{/if} id="rosters_gid"   />
<br /><label for="ip_address">IP Address</label> <input type="checkbox" name="ip_address" {if $smarty.get.ip_address}checked="checked"{/if} id="ip_address"   />
<br /><label for="ip_hostname">IP Hostname</label> <input type="checkbox" name="ip_hostname" {if $smarty.get.ip_hostname}checked="checked"{/if} id="ip_hostname"   />


<br /><input type="submit" value="Search" />

</form>
							</p>
					
				</div>
                <br />
				<!-- End Sidebar -->
				
				<!-- Begin Posts -->
				<div class="twelve columns">
					<!-- Post with image -->
					<div class="post post-page">

						<h3 class="headline">Results</h3>
						<div class="clear"></div>
						<div class="post-description">
						
							<p>


<br />
{if $search_error}
<p>{$search_error}</p>

{elseif $smarty.get.search}

{foreach from=$search_results name='result' item='result' key='uid'}
<table class="search_results">
<thead>
<tr>
	<th colspan="6">User Info</th>
	<th colspan="7">Team Info</th>
</tr>
<tr>
	<th>UID</th>
	<th>Username</th>
	<th>Main Handle</th>
	<th>E-mail Address</th>
	<th>Pending E-mail</th>
	<th>First Name</th>
	<th>Last Name</th>
	<th>League</th>
	<th>Handle</th>
	<th>Join Date</th>
	<th>Leave Date</th>
	<th>Team Name</th>
	<th>Team Tag</th>
	<th>Game ID</th>
</tr>
</thead>
<tbody>
	{foreach from=$result name='member' item='member'}
	<tr>
		<td><a href="/edit.user.php?uid={$uid}">{$uid}</a></td>
		<td>{$member.users_username|escape}</td>
		<td>{$member.users_handle|escape}</td>
		<td>{$member.users_email|escape}</td>
		<td>{$member.users_pending_email|escape}</td>
		<td>{$member.users_firstname|escape}</td>
		<td>{$member.users_lastname|escape}</td>
		<td>{$member.leagues_lgname|escape}</td>
		<td>{$member.rosters_handle|escape}</td>
		<td>{$member.rosters_join_date_gmt|iso_datetime}</td>
		<td>{if $member.rosters_leave_date_gmt == 0}-{else}{$member.rosters_leave_date_gmt|iso_datetime}{/if}</td>
		<td><a href="/edit.team.php?tid={$member.teams_tid}">{$member.teams_name|escape}</a></td>
		<td>{$member.teams_tag|escape}</td>
		<td>{$member.rosters_gid}</td>
	</tr>
	{/foreach}
</tbody>
</table>
<br />
{foreachelse}
<p>No results found.</p>
{/foreach}
{/if}

							</p>
							
						</div>
					</div>
				</div>
</div>

			</div>






