<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Maps Manager</h2>
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
<table class="clean">
<tr>
<th align="left">&nbsp;</th>
<th align="left">Title</th>
<th align="left">File Path</th>
<th align="left">Config Path</th>
<th align="left">Overview Path</th>
<th align="left">Exploits Path</th>
<th align="left">Create Date</th>
</tr>
{foreach from=$maps_list item=map}
<tr>
<td><a href="/edit.map.php?mapid={$map.mapid}&lid={$map.lid}">Edit</a></th>
<td{if $map.deleted} class="deleted"{/if}>{$map.map_title|escape}</td>
<td{if $map.deleted} class="deleted"{/if}>{$map.filename}</td>
<td{if $map.deleted} class="deleted"{/if}>{$map.config_path}</td>
<td{if $map.deleted} class="deleted"{/if}>{$map.overview_path}</td>
<td{if $map.deleted} class="deleted"{/if}>{$map.illegal_locations_path}</td>
<td{if $map.deleted} class="deleted"{/if}>{$map.modify_date_gmt}</td>
</tr>
{foreachelse}
<tr><td colspan="4">No maps</td></tr>
{/foreach}
</table>
<br /><br />
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Add New Map</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
<form {$add_map_form.attributes}>
{$add_map_form.hidden}

{quickform_fieldset form=$add_map_form id='fieldset_add_map' class='qffieldset' fields='map_title, filename, config_path, overview_path, illegal_locations_path, submit' }
</form>


						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->





