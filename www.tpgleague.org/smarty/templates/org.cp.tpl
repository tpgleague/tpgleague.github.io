<div>

<div>
{if $edit_org_success}
<p>Your changes were successful.</p>
{/if}

<form {$edit_org_form.attributes}>
{$edit_org_form.hidden}

{if $edit_org_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{quickform_fieldset form=$edit_org_form id='fieldset_edit_org' class='qffieldset' fields='name, website, ccode' legend='Edit Organization'}
<p>{$edit_org_form.submit.html}</p>
</form>
</div>

<div>


<form {$create_team_form.attributes}>
{$create_team_form.hidden}

{quickform_fieldset form=$create_team_form id='fieldset_create_team' class='qffieldset' fields='lid' legend='Create Team'}
<p>{$create_team_form.submit.html}</p>
</form>
</div>

<div>
<table>
<colgroup span="4" />

<tr>
<th>&nbsp;</td>
<th>Name</th>
<th>Tag</th>
<th>Approved</th>
<th>Active</th>
<th>League</th>
<th>Division</th>
<th>Group</td>
</tr>
{foreach from=$team_list item=team}
<tr>
<td><a href="/team.cp.php?tid={$team.tid}">Team Panel</a></td>
<td>{$team.name|escape}</td>
<td>{$team.tag|escape}</td>
<td>{if $team.approved}Y{else}N{/if}</td>
<td>{if $team.inactive}N{else}Y{/if}</td>
<td><a href="/{$team.lgname}/">{$team.league_title|escape}</a></td>
<td>{$team.division_title|escape}</td>
<td>{$team.group_title|escape}</td>
</tr>
{foreachelse}
<tr>
<td colspan="4">Your organization is not currently participating in any leagues.</td>
</tr>
{/foreach}
</table>

</div>


</div>