
<form {$register_form.attributes}>
{$register_form.hidden}

{if $register_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{quickform_fieldset form=$register_form id='fieldset_required' class='qffieldset' fields='username, password, password2, email, firstname, lastname, hide_lastname, dob' legend='Required Information' notes_label='Tip' notes="<p>To ensure that the validation e-mail we send to you is not marked as SPAM or blocked by your e-mail server, please add support@tpgleague.org to your address book or filter whitelist.</p>"}

{quickform_fieldset form=$register_form id='fieldset_optional' class='qffieldset' fields='handle, city, state, ccode, tzid, user_comments' legend='Optional Information'}

{quickform_fieldset form=$register_form id='fieldset_captcha' class='qffieldset' fields='captcha, captcha_code' legend='Image Verification'}

<p>{$register_form.submit.html}</p>
</form>
