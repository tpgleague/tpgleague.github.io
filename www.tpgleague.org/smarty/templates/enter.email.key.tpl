<div>

<form {$enter_key_form.attributes}>
{$enter_key_form.hidden}

{if $enter_key_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{quickform_fieldset form=$enter_key_form id='fieldset_enter_key' class='qffieldset' fields='key' legend='Enter E-mail Validation Key' notes_label='Tip' notes='<p>If you do not see a registration confirmation e-mail from TPG in your inbox, try checking your bulk mail or SPAM folder. If you still can\'t find it, please visit the <a href="/edit.account.php?actedit=resendemail">validation e-mail re-request page</a>.</p>'}
<p>{$enter_key_form.submit.html}</p>
</form>

</div>