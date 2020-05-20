<form {$edit_map_form.attributes}>
{$edit_map_form.hidden}

{quickform_fieldset form=$edit_map_form id='fieldset_edit_map' class='qffieldset' fields='map_title, filename, config_path, overview_path, illegal_locations_path, deleted' legend='Edit Map'}
<p>{$edit_map_form.submit.html}</p>

</form>

{if $success}
<br /><br /><div style="color: blue;">Map changes successful.</div>
{/if}

<br />
<hr />
<br />