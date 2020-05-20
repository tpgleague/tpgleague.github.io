<div>
{if $success}
    Before we can change your email address, you must check your new e-mail address to obtain your new e-mail validation key. Your old email address will remain in effect until your new email is validated.
    <p>Return to the <a href="/edit.account.php">account management page</a>.</p>
{elseif $reverted_email}
	E-mail address reverted to previously verified address.
	<p>Return to the <a href="/edit.account.php">account management page</a>.</p>
{else}

<form {$change_email_form.attributes}>
{$change_email_form.hidden}

{if $change_email_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{quickform_fieldset form=$change_email_form id='fieldset_change_email' class='qffieldset' fields='password, email' legend='Change E-mail Address' notes_label='Tip' notes="<p>To ensure that the validation e-mail we send to you is not marked as SPAM or blocked by your e-mail server, please add postmaster@tpgleague.org to your address book or filter whitelist.</p>"}
<p>{$change_email_form.submit.html}</p>
</form>

{/if}
</div>