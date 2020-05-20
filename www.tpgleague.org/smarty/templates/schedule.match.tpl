{if $proposal_list}
{if $pending_exists_error}<p class="error">You must accept, decline, or delete any existing proposals before submitting new comments or proposals.</p>{/if}
{if $schedule_match_form.errors}<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>{/if}
<div id="schedule_proposals">
{foreach from=$proposal_list item="proposal"}
	<div class="comments_box">
		<div class="comments_text">

			<form method="post" action="/schedule.match.php?mid={$smarty.get.mid}&amp;tid={$smarty.get.tid}" >
			<input type="hidden" name="mpid" value="{$proposal.mpid}" />
			Posted: {insert name='friendly_date' timestamp=$proposal.create_date_gmt}<br />
			Status: <span 
			{if ($proposal.status == 'Accepted' && !$already_accepted) || 
				($proposal.status == 'Declined' && !$already_declined) ||
				($proposal.status == 'Pending' && !$already_pending)
			  }
				class="status_{$proposal.status}"
			{/if}>{$proposal.status}</span> 
			{*
			{if $proposal.review_comments && $proposal.status == 'Deleted'}
				(<span class="comment">{$proposal.review_comments|escape}</span>)
			{/if}
			*}
			<br />
			{if $proposal.status == 'Accepted'}
				{if !$already_accepted}
					{if $proposal.home_server_choice == 'Home server'}
						{capture assign='server_info_table'}
						<table>
							<colgroup span="1" align="right"></colgroup>
							{if $home_server_info.server_ip}
								{capture assign='server_connect_line'}
									connect {$home_server_info.server_ip}{if $home_server_info.server_port}:{$home_server_info.server_port}{/if}{if $home_server_info.server_pw}; password {$home_server_info.server_pw}{/if}
								{/capture}
								<tr><td>Connect Line:</td><td>{$server_connect_line|@trim}</td></tr>
								<tr><td>Server IP:</td><td>{$home_server_info.server_ip}</td></tr>
								<tr><td>Server Port:</td><td>{$home_server_info.server_port}</td></tr>
								<tr><td>Server Pass:</td><td>{$home_server_info.server_pw}</td></tr>
							{/if}
							{if $home_server_info.hltv_ip}
								{capture assign='hltv_connect_line'}
									connect {$home_server_info.hltv_ip}{if $home_server_info.hltv_port}:{$home_server_info.hltv_port}{/if}{if $home_server_info.hltv_pw}; password {$home_server_info.hltv_pw}{/if}
								{/capture}
							<tr><td>HLTV Connect Line:</td><td>{$hltv_connect_line|@trim}</td></tr>
							<tr><td>HLTV IP:</td><td>{$home_server_info.hltv_ip}</td></tr>
							<tr><td>HLTV Port:</td><td>{$home_server_info.hltv_port}</td></tr>
							<tr><td>HLTV Pass:</td><td>{$home_server_info.hltv_pw}</td></tr>
							{/if}
						</table>
						{/capture}
					{elseif $proposal.home_server_choice == 'Away server'}
						{capture assign='server_info_table'}
						<table>
							<colgroup span="1" align="right"></colgroup>
							{if $away_server_info.server_ip}
								{capture assign='server_connect_line'}
									connect {$away_server_info.server_ip}{if $away_server_info.server_port}:{$away_server_info.server_port}{/if}{if $away_server_info.server_pw}; password {$away_server_info.server_pw}{/if}
								{/capture}
								<tr><td>Connect Line:</td><td>{$server_connect_line|@trim}</td></tr>
								<tr><td>Server IP:</td><td>{$away_server_info.server_ip}</td></tr>
								<tr><td>Server Port:</td><td>{$away_server_info.server_port}</td></tr>
								<tr><td>Server Pass:</td><td>{$away_server_info.server_pw}</td></tr>
							{/if}
							{if $away_server_info.hltv_ip}
								{capture assign='hltv_connect_line'}
									connect {$away_server_info.hltv_ip}{if $away_server_info.hltv_port}:{$away_server_info.hltv_port}{/if}{if $away_server_info.hltv_pw}; password {$away_server_info.hltv_pw}{/if}
								{/capture}
							<tr><td>HLTV Connect Line:</td><td>{$hltv_connect_line|@trim}</td></tr>
							<tr><td>HLTV IP:</td><td>{$away_server_info.hltv_ip}</td></tr>
							<tr><td>HLTV Port:</td><td>{$away_server_info.hltv_port}</td></tr>
							<tr><td>HLTV Pass:</td><td>{$away_server_info.hltv_pw}</td></tr>
							{/if}
						</table>
						{/capture}
					{/if}
				{/if}
				{assign var='already_accepted' value=TRUE}
			{elseif $proposal.status == 'Declined'}{assign var='already_declined' value=TRUE}
			{elseif $proposal.status == 'Pending'}{assign var='already_pending' value=TRUE}
			{/if}
			Request Team: {$proposal.proposed_name|escape}<br />
			{if $proposal.proposed_date_gmt}Proposed Match Time: {$proposal.proposed_date_gmt|easy_day} {$proposal.proposed_date_gmt|easy_time}<br />{/if}
			{if $proposal.comments}Comment: <span class="comment">{$proposal.comments|escape}</span><br />{/if}
			{*
			{if $proposal.review_comments && $proposal.status != 'Deleted'}
				Review Comment: <span class="comment">{$proposal.review_comments|escape}</span><br />
			{/if}
			*}
			{if $proposal.status != 'Message' && ($proposal.home_server_choice || $matchData.home_tid == $smarty.const.TID)}Server Choice: 
				{if $proposal.status == 'Pending' && ((($proposal.home_server_choice != 'Home server' && $proposal.home_server_choice != 'Away server')) && $proposal.reviewer_tid == $smarty.const.TID)}
				<select name="server_preference">
					<option value="No preference">No preference</option>
					<option value="Home server">{$matchData.home_name|escape}'s server ({$home_server_info.server_location|escape|default:'Location unknown'}, {if $home_server_info.server_available}available{else}unavailable{/if})</option>
					<option value="Away server">{$matchData.away_name|escape}'s server ({$away_server_info.server_location|escape|default:'Location unknown'}, {if $away_server_info.server_available}available{else}unavailable{/if})</option>
				</select>
				{else}
					{if $proposal.home_server_choice == 'Home server'}
						{$matchData.home_name|escape}'s server ({$home_server_info.server_location|escape|default:'Location unknown'}, 
						{if $home_server_info.server_available}available{else}unavailable{/if})
					{elseif $proposal.home_server_choice == 'Away server'}
						{$matchData.away_name|escape}'s server ({$away_server_info.server_location|escape|default:'Location unknown'}, 
						{if $away_server_info.server_available}available{else}unavailable{/if})
					{else}No preference
					{/if}
				{/if}
				<br />
			{/if}
			{if $proposal.status == 'Pending' && $proposal.reviewer_tid == $smarty.const.TID}
			{*		Review Comment (optional): <input name="review_comments" type="text" size="60" maxlength="255" value="" /> *}
					<br /><input name="submit" class="review_button" type="submit" value="Accept this match time" style="margin-right:2em;" />
					<input name="submit" class="review_button" type="submit" value="Decline this match time" /><br />

			{elseif $proposal.status == 'Pending' && $proposal.proposed_tid == $smarty.const.TID}
					<br /><input name="submit" class="review_button" type="submit" value="Delete this proposal" style="margin-right:2em;" /><br />
			{/if}
			</form>

			{$server_info_table}
			{assign var='server_info_table' value=''}

		</div>
	</div>
{/foreach}
</div>

<hr />
{/if}

{if $pending_proposals_exist}
	<p class="error">You must accept, decline, or delete any existing proposals (above) before submitting new comments or proposals.</p>
{else}

	<form {$schedule_match_form.attributes}>
	{$schedule_match_form.hidden}

	{quickform_fieldset form=$schedule_match_form id='fieldset_schedule_match' class='qffieldset' fields='mid, away_team, home_team, comments_hide, start_hide, date, server_preference, end_hide, comments' legend='Schedule Match' notes_label='Time Zones' notes='<p>You should never write times into the comments box because you never know what time zone your opponent is in. By selecting a match time using the boxes at left, the website will automagically convert the time from your local time to your opponent\'s local time.</p><p>You can change your time zone display preferences at any time by visiting your <a href="/edit.account.php?actedit=siteprefs">Account Preferences</a>.</p>'}

	<p>{$schedule_match_form.submit.html}</p>
	
	<p>Please ensure that your server info is always up to date by visiting your <a href="/edit.team.php?tid={$smarty.const.TID}">Team Properties page</a>. Your opponent for this week will automatically receive your server information in their team panels, so you do not need to re-copy it into the comments box.</p>

	{if $pending_exists_error}<p class="error">You must accept, decline, or delete any existing proposals (above) before submitting new comments or proposals.</p>{/if}

	</form>

{/if}
