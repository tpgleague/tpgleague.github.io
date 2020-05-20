<div>

<form {$edit_siteprefs_form.attributes}>
{$edit_siteprefs_form.hidden}

{if $edit_siteprefs_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{quickform_fieldset form=$edit_siteprefs_form id='fieldset_siteprefs' class='qffieldset' fields='tzid' legend='Edit Account Preferences' notes_label='Time zones' notes="<p>Changing this setting will allow the website to display times to you in your local time.</p>"}
<p>{$edit_siteprefs_form.submit.html}</p>
</form>

</div>