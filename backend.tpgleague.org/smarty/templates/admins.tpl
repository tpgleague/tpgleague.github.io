<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Admin List</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16" >

						<p >
						<table>
<tr>
<th></th>
<th>Admin Name</th>
<th>Admin E-mail</th>
<th>Username</th>
<th>First Name</th>
<th>Last Name</th>
<th>Department</th>
<th>Seniority</th>
<th>IRC Nick</th>
<th>Google Talk</th>
</tr>


{foreach item=admin from=$admins_table}
<tr{if $admin.inactive} style="color: grey;"{/if}>
<td>{if $smarty.const.superadmin}<a href="/edit.admin.php?aid={$admin.aid}">Edit</a>{else}&nbsp;{/if}</td>
<td>{$admin.admin_name|escape}</td>
<td>{$admin.admin_email|escape}</td>
<td><a href="/edit.user.php?uid={$admin.uid}">{$admin.username|escape}</a></td>
<td>{$admin.firstname|escape}</td>
<td>{$admin.lastname|escape}</td>
<td>{$admin.department|escape}</td>
<td>{$admin.seniority|escape}</td>
<td>{$admin.irc_nick|escape}</td>
<td>{$admin.gtalk|escape}</td>
</tr>
{/foreach}

</table>
<br /><br />
{if $add_admin_form}
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Add Admin</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
<form {$add_admin_form.attributes}>
{$add_admin_form.hidden}

{quickform_fieldset form=$add_admin_form id='fieldset_add_admin' class='qffieldset' fields='username, admin_name, department, seniority, admin_email, gtalk, irc_nick, superadmin' }
<p>{$add_admin_form.submit.html}</p>

</form>
{/if}
						</p>

					</div>	
				</div>
				
			</div>
            			</div>

<!-- End Container -->
			
		<!-- End Wrapper -->


