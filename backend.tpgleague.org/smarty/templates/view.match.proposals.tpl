{if $proposal_list}
<div id="schedule_proposals">
{foreach from=$proposal_list item="proposal"}
	<div class="prop">
	<span style="font-weight: bold;">Status: {$proposal.status}</span><br />
	Posted: {insert name='friendly_date' timestamp=$proposal.create_date_gmt}<br />
	{if $proposal.proposed_date_gmt}Proposed Match Time: {$proposal.proposed_date_gmt|easy_day} {$proposal.proposed_date_gmt|easy_time}<br />{/if}
	<br />
	Proposing Team: <a href="/edit.team.php?tid={$proposal.proposed_tid}">{$proposal.proposed_team|escape}</a> (<a href="/edit.user.php?uid={$proposal.proposed_uid}">{$proposal.proposed_player|escape}</a>)<br />
	{if $proposal.status != 'Message'}Server Choice: 
		{if $proposal.home_server_choice == 'Home server'}
			{if $proposal.proposed_tid == $proposal.home_tid}
				{$proposal.proposed_team|escape}
			{else}
				{$proposal.reviewer_team|escape}
			{/if}
		{elseif $proposal.home_server_choice == 'Away server'}
			{if $proposal.proposed_tid == $proposal.away_tid}
				{$proposal.proposed_team|escape}
			{else}
				{$proposal.reviewer_team|escape}
			{/if}
		{else}
			No preference
		{/if}
	<br />
	{/if}
	Comment: <span style="font-style: italic;">{$proposal.comments|escape}</span><br />
	<br />
	{if $proposal.status != 'Message'}{$proposal.status} Time: {$proposal.review_date_gmt|easy_day} {$proposal.review_date_gmt|easy_time}<br />{/if}
	{if $proposal.status != 'Message' && $proposal.status != 'Deleted' && $proposal.status != 'Pending'}
		Reviewing Team: <a href="/edit.team.php?tid={$proposal.reviewer_tid}">{$proposal.reviewer_team|escape}</a> (<a href="/edit.user.php?uid={$proposal.reviewer_uid}">{$proposal.reviewer_player|escape}</a>)<br />
		Review Comment: <span style="font-style: italic;">{$proposal.review_comments|escape}</span><br />
	{/if}
	</div>
	<br />
	<br />
	<hr />
{/foreach}
</div>

{/if}

