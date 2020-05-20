<div class="rubberbox">
<h1 class="rubberhdr"><span>League Admins</span></h1>
	{if count($league_head_admins) == 1}
		<u>Head Admin</u> <br />
		{$league_head_admins.0|escape}
		<br />
	{elseif count($league_head_admins) > 1}
		<p><u>Head Admins</u><br />
		{foreach from=$league_head_admins name='head_admins_loop' item='admin'}
			{$admin|escape} <br />
		{/foreach}
		<br />
	{/if}

	{if !empty($league_head_admins) && !empty($league_admins)}<br />{/if}

	{foreach from=$league_admins name='admins_loop' key='section' item='admin_array'}
			<u>{$section|escape}</u><br />
			{foreach from=$admin_array name='admins_sub_loop' item='admin}
				{$admin|escape}<br />
				{if $smarty.foreach.admins_sub_loop.last && !$smarty.foreach.admins_loop.last}<br />{/if}
			{/foreach}
	{/foreach}

</div>