<div>

{if $edit_team_success}
<p>Your changes were successful.</p>
{/if}

<form {$edit_team_form.attributes}>
{$edit_team_form.hidden}

{if $edit_team_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{quickform_fieldset form=$edit_team_form id='fieldset_edit_team' class='qffieldset' fields='tid, name, tag, pw, team_avatar_url' legend='Edit Team Info'}

{quickform_fieldset form=$edit_team_form id='fieldset_edit_team_server' class='qffieldset' fields='server_ip, server_port, server_pw, server_location, server_available' legend='Game Server Info' notes_label='Server Availability' notes='<p>You must checkmark the "Server Available" box in addition to entering a valid server IP/hostname if you have a server for the upcoming week\'s match. If you do not have a server, our autoscheduler will try its best to schedule you against a team that does have a server and vice-versa. You should update this checkbox on a weekly basis before your league admin runs the autoscheduler.</p><p>N.B.: This option has NO EFFECT on whether you are listed as the home team or away team.</p>'}

{quickform_fieldset form=$edit_team_form id='fieldset_edit_team_hltv' class='qffieldset' fields='hltv_ip, hltv_port, hltv_pw, hltv_public' legend='HLTV Server Info' notes_label='HLTV information' notes='<p>Checkmark the "HLTV Public" box if you would like the HLTV info displayed on the public matchlist. Leave unchecked if you only want it displayed to your opponent for that week.</p>'}

<p>{$edit_team_form.submit.html}</p>
</form>

</div>