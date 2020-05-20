<div>

<form {$edit_password_form.attributes}>
{$edit_password_form.hidden}

{if $edit_password_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{quickform_fieldset form=$edit_password_form id='fieldset_change_password' class='qffieldset' fields='passwordold, password, password2' legend='Edit Account Password'}
<p>{$edit_password_form.submit.html}</p>
</form>


</div>
