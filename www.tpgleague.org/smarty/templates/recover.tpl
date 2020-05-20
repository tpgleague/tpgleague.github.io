

{if !empty($smarty.get.recover_key)}

	{if $edit_password_form}
		You may use this form to reset your account password.
		<form {$edit_password_form.attributes}>
		{$edit_password_form.hidden}

		{if $edit_password_form.errors}
		<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
		{/if}

		{quickform_fieldset form=$edit_password_form id='fieldset_change_password' class='qffieldset' fields='password, password2'}
		<p>{$edit_password_form.submit.html}</p>
		</form>

	{else}
		<p>You have provided an incorrect key. If you requested your recover key more than 24 hours ago then you must <a href="/recover/">re-request your key</a>.</p>
	{/if}

{else}

	{if $recover_key_sent}
		<p>We have found a matching account and dispatched an e-mail that contains your password recover key. The key provided will expire automatically after 24 hours.</p>
	{else}

<form action="/recover/" method="post" id="recover_form" class="recover_form" onsubmit="this.submit.disabled = true; return true">
	{if $invalid_recover_input}
	<span style="color:red;">We could not find a matching username or e-mail address.</span><br />
	{/if}
	Enter either your username or email address.  If a matching record is found in our database then you will be e-mailed a password reset key:
	<br /><input size="40" name="recover_input" value="{if $smarty.post.recover_input}{$smarty.post.recover_input|@trim}{/if}" id="recover_input" type="text" />
	<br /><input name="submit" value="Submit" type="submit" />
</form>


	{/if}

{/if}