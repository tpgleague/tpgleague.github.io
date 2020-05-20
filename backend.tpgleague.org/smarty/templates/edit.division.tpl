
<form {$edit_division_form.attributes}>
{$edit_division_form.hidden}

{quickform_fieldset form=$edit_division_form id='fieldset_edit_division' class='qffieldset' fields='lid, divid, division_title, admin, sort_order, inactive, create_date_gmt, submit' legend='Edit Division'}
</form>



<table>

  <tr>
	<th></th>
	<th>Conference</th>
	<th>Create Date</th>
  </tr>

{foreach item=conference from=$conferences_list}
<tr>
  <td><a href="/edit.conference.php?cfid={$conference.cfid}">Edit</a></td>
  <td>{$conference.conference_title|escape}</td>
  <td>{$conference.create_date_gmt|iso_datetime}</td>
</tr>
{foreachelse}
<tr><td colspan="3">No conferences</td></tr>
{/foreach}

</table>
<p>Please note: if there is only one conference in a division, e.g. Central, the <i>title</i> will not be visible in the standings since it would be pointless. Unless you have more than 50 teams in one division (e.g. Lower Division has 50 teams), you should only have one conference: Central.</p>


<form {$add_conference_form.attributes}>
{$add_conference_form.hidden}
{quickform_fieldset form=$add_conference_form id='fieldset_add_conference' class='qffieldset' fields='conference_title, admin, submit' legend='Add New Conference'}
</form>
