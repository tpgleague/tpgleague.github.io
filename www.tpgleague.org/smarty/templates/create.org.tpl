<div>

<form {$create_org_form.attributes}>
{$create_org_form.hidden}

{if $create_org_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{quickform_fieldset form=$create_org_form id='fieldset_create_org' class='qffieldset' fields='name, website, ccode' legend='Create Organization' notes_label='About Organizations' notes="<p>A TPG organization may create as many teams in as many leagues as it would like. After creating an organization, you will be asked which league(s) you would like to create teams in.</p>"}
<p>{$create_org_form.submit.html}</p>
</form>

</div>