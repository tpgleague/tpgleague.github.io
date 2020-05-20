<div class="rubberbox">
<h1 class="rubberhdr"><span>Login</span></h1>
<form action="{$smarty.server.REQUEST_URI}" method="post" id="login_form" class="login_form" onsubmit="this.submit.disabled = true; return true">
	{if $invalid_login}
	<span style="color:red;">Invalid login</span><br />
	{/if}
	<label for="login_username">Username:</label><br /><input size="16" maxlength="32" name="login_username" value="{if $smarty.post.login_username}{$smarty.post.login_username}{/if}" id="login_username" type="text" />
	<br /><label for="login_password">Password:</label><br /><input name="login_password" size="16" value="" id="login_password" type="password" />
	<br /><input name="submit" value="Login" type="submit" />
	<br /><a href="/recover/">Lost Password</a>
	<br /><a href="/register/">Register</a>
</form>
</div>