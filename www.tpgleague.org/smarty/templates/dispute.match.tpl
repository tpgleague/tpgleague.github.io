
<p>Choose the type of dispute you wish to file:</p>

<p><b>Cheating Dispute</b>: When you suspect a player on the other team has used an illegal file or modification to his client that gives him an unfair advantage.  This is limited to the following: Wallhacks, aimbots and illegal .cfg scripts.</p>

<p><b>General Dispute</b>: For all other concerns not listed under Cheating Dispute. This includes match forfeits and scoring issues, wrong server config or server settings, ineligible player(s), unsportsmanlike conduct, map exploits, illegal rates and violations of weapon class limits.</p>



<form action="{$smarty.server.REQUEST_URI}" method="post" name="dispute">

    <select name="dispute_type" onchange="displayForm();">
		<option value="">Select Dispute Type</option>
		<option value="Cheating"{if $smarty.post.dispute_type == 'Cheating'} selected="selected"{/if}>Cheating</option>
		<option value="General"{if $smarty.post.dispute_type == 'General'} selected="selected"{/if}>General</option>
	</select>

	<div id="cheating" style="display: none;">
		<select name="cheating_player">
				<option value="">Select Disputed Player</option>
			{foreach from=$roster item='player' key='uid'}
				<option value="{$uid}">{$player.firstname|escape} {if $player.handle}"{$player.handle|escape}"{/if} {if $player.hide_lastname}{$player.lastname|truncate:2:"."|escape}{else}{$player.lastname|escape}{/if} {if $player.gid}({$player.gid}){/if}</option>
			{/foreach}
		</select>
		<br />
		<br />
	</div>

	<div id="comment" style="display: none;">
		<span id="comment_desc"></span>
		<br /><textarea name="comment" rows="4" cols="60">{$smarty.post.comment|escape}</textarea>
		<br /><input type="submit" value="File Dispute" />
	</div>

	{if $dispute_error}
	<div style="color: red;">{$dispute_error}</div>
	{/if}

</form>