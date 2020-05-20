<div>

<form {$edit_suspension_form.attributes}>
{$edit_suspension_form.hidden}

{if $edit_suspension_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{quickform_fieldset form=$edit_suspension_form id='fieldset_edit_suspension' class='qffieldset' fields='suspid, uid, handle, firstname, lastname, reason, rule_violation, type, start, end, tid, team, mid, lid, gids, stank_ticket_number, added_admin, create_date_gmt, edited_admin, last_updated_date, deleted' legend='Edit Suspension Details'}

<p>{$edit_suspension_form.submit.html}</p>
</form>

</div>
