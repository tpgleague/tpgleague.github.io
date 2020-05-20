<div>
Current Status: {$roster_lock|capitalize}
</div>

<form method="post" action="/edit.roster.lock.php?lid={$smarty.const.LID}">

	<select name="roster_lock">
		<option value="auto" {if $roster_lock == 'auto'}selected="selected"{/if}>Auto</option>
		<option value="unlocked" {if $roster_lock == 'unlocked'}selected="selected"{/if}>Unlocked</option>
		<option value="locked" {if $roster_lock == 'locked'}selected="selected"{/if}>Locked</option>
	</select>

	<br />
	<input type="submit" value="Save" />

</form>