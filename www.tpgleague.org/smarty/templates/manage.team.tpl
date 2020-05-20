{foreach from=$captains_teams item='team'}
<br />{$team.name|escape} (<a href="/team.cp.php?tid={$team.tid}">edit</a>)
{foreachelse}
<p>You are not the captain of any teams.</p>
{/foreach}

