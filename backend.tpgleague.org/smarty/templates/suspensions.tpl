<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">TPG Suspension List</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">

						<p>
<div>

<form {$add_suspension_form.attributes}>
{$add_suspension_form.hidden}

{if $add_suspension_form.errors}
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
{/if}

{quickform_fieldset form=$add_suspension_form id='fieldset_add_suspension' class='qffieldset' fields='uid, username, firstname, lastname, handle, reason, rule_violation, type, start, end, tid, team, mid, lid, gids, stank_ticket_number' legend='Add Suspension'}
<p>{$add_suspension_form.submit.html}</p>
</form>

{if $success}
<div style="color: blue;"><br /><br /><br />Suspension Added. Click Add Suspension in the menu to reset the form and add a new suspension.</div>
<br />
{/if}

</div>

<br><br>
<h3>Existing Suspensions:</h3>

<table class="clean">
<tr>
    <td>&nbsp;</td>
    <td>Handle</td>
    <td>Team Name</td>
    <td>League</td>
    <td>Rule</td>
    <td>Reason</td>
    <td>Ticket</td>
    <td>Start Date</td>
    <td>End Date</td>
    <td>Game ID(s)</td>
</tr>
{foreach item=suspension from=$existing_suspensions}
{if $suspension.end_date < $smarty.now && $suspension.deleted}<tr style="color: gray; text-decoration: line-through;">
{elseif $suspension.end_date < $smarty.now}<tr style="color: gray;">
{elseif $suspension.deleted}<tr style="text-decoration: line-through;">
{else}<tr>
{/if}
    <td><a href="edit.suspension.php?suspid={$suspension.suspid}">edit</a></td>
    <td>{$suspension.handle|escape}</td>
    <td>{if $suspension.tid}<a href="edit.team.php?tid={$suspension.tid}">{$suspension.team_name|escape}</a>{/if}</td>
    <td>{if $suspension.lid}<a href="edit.league.php?lid={$suspension.lid}">{$suspension.lgname|escape}</a>{else}All{/if}</td>
    <td>{if $suspension.lid}<a href="http://www.tpgleague.org/{$suspension.lgname}/rules/#{$suspension.rule_violation}">{/if}{$suspension.rule_violation}{if $suspension.lid}</a>{/if}</td>
    <td>{$suspension.reason|escape}</td>
    <td>{if $suspension.stank_ticket_number}<a href="http://support.tpgleague.org/ticket/admin.ticket_summary.php?ticket_id={$suspension.stank_ticket_number}">{$suspension.stank_ticket_number}</a>{/if}</td>
    <td>{$suspension.start_date|converted_timezone}</td>
    <td>{$suspension.end_date|converted_timezone}</td>
    <td>{$suspension.gid}</td>
</tr>
{/foreach}
</table>
						</p>

					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->





