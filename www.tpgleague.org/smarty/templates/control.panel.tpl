<div class="rubberbox">
<h1 class="rubberhdr"><span>Control Panel</span></h1>
	<p style="font-style: italic">Welcome, {$cp_firstname|escape}</p>
	<ul>
	<li><a href="/edit.account.php">Account Management</a></li>
	<li><a href="/create.org.php">Create Organization</a></li>
	{if $player_manages_orgs}<li><a href="/manage.org.php">Manage Organizations</a></li>{/if}
	{if $lgname}<li><a href="{$lgname}/join/">Join Team</a></li>{else}<li><a href="/join.team.php">Join Team</a></li>{/if}
	{if $player_on_teams}<li><a href="/my.teams.php">My Teams</a></li>{/if}
	<li><a href="{$logout_URL}">Logout</a></li>
	</ul>
</div>