<form method="get" action="{$lgname}/membersearch/">

<input type="text" size="80" maxlength="255" name="search" value="{$smarty.get.search}" />

<br /><label for="rosters_handle">Roster Handle</label> <input type="checkbox" name="rosters_handle" {if $smarty.get.rosters_handle}checked="checked"{/if} id="rosters_handle"   />
<br /><label for="users_firstname">First Name</label> <input type="checkbox" name="users_firstname" {if $smarty.get.users_firstname}checked="checked"{/if} id="users_firstname"   />
<br /><label for="users_lastname">Last Name</label> <input type="checkbox" name="users_lastname" {if $smarty.get.users_lastname}checked="checked"{/if} id="users_lastname"   />
<br /><label for="rosters_gid">Game ID</label> <input type="checkbox" name="rosters_gid" {if $smarty.get.rosters_gid}checked="checked"{/if} id="rosters_gid"   />

<br /><input type="submit" value="Search" />


</form>

<br />

{if $search_error}
<p>{$search_error}</p>

{elseif $smarty.get.search}
{*
<table id="search_results" cellspacing="0">

<thead>

<tr>
	<th>First Name</th>
	<th>Last Name</th>
	<th>League</th>
	<th>Handle</th>
	<th>Team Name</th>
	<th>Team Tag</th>
	<th>Game ID</th>
</tr>
</thead>
*}
{*
{foreach from=$search_results item='result' key='uid'}
<tbody class="{cycle values="odd,even"}">
	<tr>
	{foreach from=$result item='member'}
	{if $last_uid != $uid}
		<td>{$member.users_firstname|escape}</td>
		<td>{$member.users_lastname|escape}</td>
		<td colspan="5">
		<table>
	{/if}
		{if $member.teams_tid}
			<tr>
			<td>{$member.leagues_lgname|escape}</td>
			<td>{$member.rosters_handle|escape}</td>
			<td>{$member.teams_name|escape}</td>
			<td>{$member.teams_tag|escape}</td>
			<td>{$member.rosters_gid}</td>
			</tr>
		{/if}
	{assign var='last_uid' value=$uid}
	{/foreach}
		</table>

	</td>
	</tr>
</tbody>
{foreachelse}
*}

{foreach from=$search_results name='result' item='result' key='uid'}
<table class="search_results">
<thead>
<tr>
	<th>Name</th>
	<th>League</th>
	<th>Handle</th>
	<th>Joined</th>
	<th>Left</th>
	<th>Team Name</th>
	<th>Tag</th>
	<th>Game ID</th>
</tr>
</thead>
<tbody>
	{foreach from=$result name='member' item='member'}
	<tr>
		<td><a class="tpglink" href="{$lgname}/user/{$uid}/">{$member.users_firstname|escape}{if $member.hide_lastname == 0} {$member.users_lastname|escape}{/if}</a></td>
		<td>{$member.leagues_lgname|escape}</td>
		<td>{$member.rosters_handle|escape}</td>
		<td>{$member.rosters_join_date_gmt|date_format:"%D"}</td>
		<td>{if $member.rosters_leave_date_gmt == 0}-{else}{$member.rosters_leave_date_gmt|date_format:"%D"}{/if}</td>
		<td>{$member.teams_name|escape}</td>
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

