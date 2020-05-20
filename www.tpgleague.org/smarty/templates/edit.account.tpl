
<div>

{if $edit_form_success}
<p>Modifications to account profile successful.</p>
{/if}

<a href="/edit.account.php?actedit=details">Edit account details</a><br />
<a href="/edit.account.php?actedit=siteprefs">Change website display preferences</a><br />
{*<a href="/edit.account.php?actedit=notifyprefs">Change notification preferences</a><br />*}
<a href="/edit.account.php?actedit=password">Change password</a><br />
<a href="/edit.account.php?actedit=changeemail">Change e-mail address</a> {if empty($current_active_email)}(Not validated){else}({$current_active_email}){/if}<br />
{*<a href="/edit.account.php?actedit=uploadphoto">Upload user picture</a><br />*}
{if $email_not_validated}
<a href="/edit.account.php?actedit=resendemail">Resend e-mail validation key</a><br />
<a href="/edit.account.php?actedit=enteremailkey">Enter e-mail validation key</a><br />
{/if}
</div>
