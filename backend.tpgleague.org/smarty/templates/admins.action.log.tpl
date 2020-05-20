<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">TPG Admin Logs</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">
						<h4 class="headline"> Admin Log Information</h4>
						<p>

<form {$select_date_form.attributes}>
{$select_date_form.hidden}

{quickform_fieldset form=$select_date_form id='fieldset_select_date' class='qffieldset' fields='start_date, end_date, submit' legend='Select Date Range'}


</form>

{if $admin_log_form_submitted}
<div>
<table class="clean" style="white-space: nowrap;">
<caption>Admin Action Log</caption>
<tr>
	<th>Date</th>
	<th>Admin</th>
	<th>Table</th>
	<th>Table Key ID</th>
	<th>Field</th>
	<th>From Value</th>
	<th>To Value</th>
	<th>Description</th>
</tr>
{foreach from=$admin_action_log item='action'}
<tr>
	<td>{$action.unix_timestamp_gmt|converted_timezone}</td>
	<td><a href="/edit.admin.php?aid={$action.aid}">{$action.admin_name|escape}</a></td>
	<td>{$action.tablename}</td>
	<td>{$action.tablepkid}</td>
	<td>{$action.field}</td>
	{if $action.type == 'insert'}
	<td colspan="2">[NEW RECORD CREATED]</td>
	{else}
	<td title="{$action.from_value|escape}">{$action.from_value|truncate:32:'...'|escape}</td>
	<td title="{$action.to_value|escape}">{$action.to_value|truncate:32:'...'|escape}</td>
	{/if}
	<td>{$action.linked_descriptor|escape}</td>
</tr>
{foreachelse}
<tr>
	<td colspan="8">No actions found for date range.</td>
</tr>
{/foreach}
</table>
</div>
{/if}
						</p>

					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->

    <?php include('footer.html') ?>

</div>

