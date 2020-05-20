<div>

<form {$edit_details_form.attributes}>
{$edit_details_form.hidden}

{if $edit_details_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{quickform_fieldset form=$edit_details_form id='fieldset_edit_details' class='qffieldset' fields='firstname, lastname, hide_lastname, handle, steam_profile_url, user_avatar_url, city, state, ccode, user_comments' legend='Edit Account Details'}
<p>{$edit_details_form.submit.html}</p>
</form>

</div>