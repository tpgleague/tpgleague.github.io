<div>
{if $validated_message}
You have successfully validated your e-mail address.  You may now <a href="/join.team.php">join</a> and <a href="/create.org.php">create teams</a>.
{else}
You have supplied an incorrect e-mail validation key.  Please check the message that was sent to your e-mail address to obtain the correct key and enter it into your <a href="/edit.account.php?actedit=enteremailkey">Account Management</a> control panel.
{/if}
</div>