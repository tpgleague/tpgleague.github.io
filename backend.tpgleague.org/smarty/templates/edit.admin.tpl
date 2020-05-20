<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Edit TPG Admin</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">
						<h4 class="headline"> Edit Admin</h4>
						<p>
<form {$edit_admin_form.attributes}>
{$edit_admin_form.hidden}

{quickform_fieldset form=$edit_admin_form id='fieldset_edit_admin' class='qffieldset' fields='uid, username, admin_name, department, seniority, admin_email, gtalk, irc_nick, superadmin, inactive' legend='Edit Admin'}
<p>{$edit_admin_form.submit.html}</p>

</form>

<br />
<hr />
<br />

<div>
<table class="clean" style="white-space: nowrap;">
<caption>Admin Action Log</caption>
<tr>
	<th>Date</th>
	<th>Table</th>
	<th>Table Key ID</th>
	<th>Field</th>
	<th>From Value</th>
	<th>To Value</th>
</tr>
{foreach from=$admin_action_log item='action'}
<tr>
	<td>{$action.unix_timestamp_gmt|converted_timezone}</td>
	<td>{$action.tablename}</td>
	<td>{$action.tablepkid}</td>
	<td>{$action.field}</td>
	{if $action.type == 'insert'}
	<td colspan="2">[NEW RECORD CREATED]</td>
	{else}
	<td title="{$action.from_value|escape}">{$action.from_value|truncate:32:'...'|escape}</td>
	<td title="{$action.to_value|escape}">{$action.to_value|truncate:32:'...'|escape}</td>
	{/if}
</tr>
{foreachelse}
<tr>
	<td colspan="6">Admin has not performed any actions</td>
</tr>
{/foreach}
</table>
</div>


<br />
<hr />
<br />


<div>
<p>Some hyperlinks to pages that admins have visited may have actions contained within the URL itself (such as removing a player from a roster).  Chances are the action will no longer be valid even if you do click it, but still, please try to be careful what you click on.</p>
<table class="clean" style="white-space: nowrap; width: 1400px; table-layout: fixed; overflow:hidden; "  >
<caption>Admin Page View Log</caption>
<tr style="word-wrap:break-word;">
	<th style="width: 150px;">Date</th>
	<th>Page</th>
</tr>
{foreach from=$admin_page_views item='page'}
{if strpos($page.query, 'remove') !== FALSE}{assign var='link' value=FALSE}{else}{assign var='link' value=TRUE}{/if}
<tr>
	<td>{$page.unix_timestamp_gmt|converted_timezone}</td>
	<td>{if $link}<a href="{$page.page}.php{if $page.query}?{/if}{$page.query|escape}">{/if}{$page.page}.php{if $page.query}&amp;{/if}{$page.query|escape:'html'}{if $link}</a>{/if}</td>
</tr>
{/foreach}
</table>
</div>
						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->


