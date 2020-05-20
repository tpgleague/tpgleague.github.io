


<form {$edit_conference_form.attributes}>
{$edit_conference_form.hidden}

{quickform_fieldset form=$edit_conference_form id='fieldset_edit_conference' class='qffieldset' fields='lid, cfid, conference_title, description, admin, sort_order, inactive, create_date_gmt, submit' legend='Edit Conference'}
</form>



<table>
  <tr>
	<th></th>
	<th>Group</th>
	<th>Create Date</th>
  </tr>

{foreach item=group from=$groups_list}
<tr>
  <td><a href="/edit.group.php?grpid={$group.grpid}">Edit</a></td>
  <td>{$group.group_title|escape}</td>
  <td>{$group.create_date_gmt|iso_datetime}</td>
</tr>
{foreachelse}
<tr><td colspan="3">No groups</td></tr>
{/foreach}

</table>


<form {$add_group_form.attributes}>
{$add_group_form.hidden}
{quickform_fieldset form=$add_group_form id='fieldset_add_group' class='qffieldset' fields='lid, cfid, group_title, admin, submit' legend='Add New Group'}
</form>
