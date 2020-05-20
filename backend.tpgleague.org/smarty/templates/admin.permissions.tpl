{php}
// Images Key:
global $tpl;
$imagesLinks = array('1'       => 'tick',
					 '0'       => 'cross',
					 'NULL'    => 'picture_empty',
					 'cascade' => 'link_go'
					);
$imagesText =  array('1'       => 'Allowed',
					 '0'       => 'Denied',
					 'NULL'    => 'Default',
					 'cascade' => 'Cascade Permission'
					);
$tpl->assign('images_links', $imagesLinks);
$tpl->assign('images_text', $imagesText);
{/php}

<script language="JavaScript" type="text/javascript">
<!--
function permToggle(permID)
{ldelim}
	var curVal;
	curVal = document.getElementById('perm_' + permID).value;
	if (curVal == 1)
	{ldelim}
		document.getElementById('perm_' + permID).value = 0;
		document['img_'+permID].src='/images/icons/{$images_links.0}.png';
		document['img_'+permID].alt='{$images_text.0}';
		document['img_'+permID].title='{$images_text.0}';
		return;
	{rdelim}
	if (curVal == 0)
	{ldelim}
		document.getElementById('perm_' + permID).value = 'NULL';
		document['img_'+permID].src='/images/icons/{$images_links.NULL}.png';
		document['img_'+permID].alt='{$images_text.NULL}';
		document['img_'+permID].title='{$images_text.NULL}';
		return;
	{rdelim}
	if (curVal == 'NULL')
	{ldelim}
		document.getElementById('perm_' + permID).value = 1;
		document['img_'+permID].src='/images/icons/{$images_links.1}.png';
		document['img_'+permID].alt='{$images_text.1}';
		document['img_'+permID].title='{$images_text.1}';
		return;
	{rdelim}
{rdelim}

function permCascade(permID)
{ldelim}
	var search_term = permID+'_';
	var elems = document.getElementsByTagName('img');
	for (var i=0; i<elems.length; i++)
	{ldelim}
		if ( elems[i].id.indexOf(search_term) != -1 ) {ldelim}
			var el_name = document.getElementById(elems[i].id).name;
			var sub_el_name = el_name.substring(4);
			document[elems[i].id].src='/images/icons/{$images_links.NULL}.png';
			document[elems[i].id].alt='{$images_text.NULL}';
			document[elems[i].id].title='{$images_text.NULL}';
			document.getElementById('perm_'+sub_el_name).value = 'NULL';
		{rdelim}

	{rdelim}
{rdelim}
//-->
</script>

<div id="key">Key - 
{foreach from=$images_links key=key item=links}
{$images_text.$key}: <img src="/images/icons/{$images_links.$key}.png" width="16" height="16" border="0" alt="{$images_text.$key}" title="{$images_text.$key}" />
{/foreach}
</div>

<form method="post" action="/admin.permissions.php" class="permissions_form">
<input type="hidden" name="permission_aid" value="{$form_permission_aid}" />
<input type="hidden" name="permission_type" value="{$form_permission_type}" />

<ul class="global">
<li>Global Access 
<input type="hidden" name="perm_Sitewide_0" id="perm_Sitewide_0" value="{$preset_values.Sitewide_0}" />
<img src="/images/icons/{$images_links[$preset_values.Sitewide_0]}.png" width="16" height="16" border="0" alt="{$images_text[$preset_values.Sitewide_0]}" title="{$images_text[$preset_values.Sitewide_0]}" onclick="permToggle('Sitewide_0')" name="img_Sitewide_0" />

	{if $leagues}

<img src="/images/icons/{$images_links.cascade}.png" width="16" height="16" border="0" alt="{$images_text.cascade}" title="{$images_text.cascade}" onclick="permCascade('Sitewide_0')" name="Cascade_Sitewide_0" />

	<ul class="league">
	{foreach from=$leagues key=lid item=league}
	<li>{$league.league_title|escape} 
	{assign var="input_value" value="League_`$lid`"}
	{assign var="img_link" value="`$preset_values.$input_value`"}
	<input type="hidden" name="perm_League_{$lid}" id="perm_League_{$lid}" value="{$preset_values.$input_value}" />
	<img src="/images/icons/{$images_links.$img_link}.png" width="16" height="16" border="0" alt="{$images_text.$img_link}" title="{$images_text.$img_link}" onclick="permToggle('League_{$lid}')" name="img_League_{$lid}" id="Sitewide_0_League_{$lid}" />

		{if $divisions[$lid]}
	
	<img src="/images/icons/{$images_links.cascade}.png" width="16" height="16" border="0" alt="{$images_text.cascade}" title="{$images_text.cascade}" onclick="permCascade('League_{$lid}')" name="Cascade_League_{$lid}" />

		<ul class="division">
		{foreach from=$divisions[$lid] item=division}
			<li>{$division.division_title|escape} 
			{assign var="input_value" value="Division_`$division.divid`"}
			{assign var="img_link" value="`$preset_values.$input_value`"}
			<input type="hidden" name="perm_Division_{$division.divid}" id="perm_Division_{$division.divid}" value="{$preset_values.$input_value}" />
			<img src="/images/icons/{$images_links.$img_link}.png" width="16" height="16" border="0" alt="{$images_text.$img_link}" title="{$images_text.$img_link}" onclick="permToggle('Division_{$division.divid}')" name="img_Division_{$division.divid}" id="Sitewide_0_League_{$lid}_Division_{$division.divid}" />

				{if $conferences[$division.divid]}

			<img src="/images/icons/{$images_links.cascade}.png" width="16" height="16" border="0" alt="{$images_text.cascade}" title="{$images_text.cascade}" onclick="permCascade('Division_{$division.divid}')" name="Cascade_Division_{$division.divid}" />

				<ul class="conference">
				{foreach from=$conferences[$division.divid] item=conference}
					<li>{$conference.conference_title|escape} 
					{assign var="input_value" value="Conference_`$conference.cfid`"}
					{assign var="img_link" value="`$preset_values.$input_value`"}
					<input type="hidden" name="perm_Conference_{$conference.cfid}" id="perm_Conference_{$conference.cfid}" value="{$preset_values.$input_value}" />
					<img src="/images/icons/{$images_links.$img_link}.png" width="16" height="16" border="0" alt="{$images_text.$img_link}" title="{$images_text.$img_link}" onclick="permToggle('Conference_{$conference.cfid}')" name="img_Conference_{$conference.cfid}" id="Sitewide_0_League_{$lid}_Division_{$division.divid}_Conference_{$conference.cfid}" />

						{if $groups[$conference.cfid]}

				<img src="/images/icons/{$images_links.cascade}.png" width="16" height="16" border="0" alt="{$images_text.cascade}" title="{$images_text.cascade}" onclick="permCascade('Conference_{$conference.cfid}')" name="Cascade_Conference_{$conference.cfid}" />

						<ul class="group">
						{foreach from=$groups[$conference.cfid] item=group}
							<li>{$group.group_title|escape} 
							{assign var="input_value" value="Group_`$group.grpid`"}
							{assign var="img_link" value="`$preset_values.$input_value`"}
							<input type="hidden" name="perm_Group_{$group.grpid}" id="perm_Group_{$group.grpid}" value="{$preset_values.$input_value}" />
							<img src="/images/icons/{$images_links.$img_link}.png" width="16" height="16" border="0" alt="{$images_text.$img_link}" title="{$images_text.$img_link}" onclick="permToggle('Group_{$group.grpid}')" name="img_Group_{$group.grpid}" id="Sitewide_0_League_{$lid}_Division_{$division.divid}_Conference_{$conference.cfid}_Group_{$group.grpid}" />
							</li>
						{/foreach}
						</ul>
						{/if}					


					</li>
				{/foreach}
				</ul>
				{/if}


			</li>
		{/foreach}
		</ul>
		{/if}


	</li>
	{/foreach}
	</ul>
	{/if}
</li>
</ul>

<input type="submit" value="Submit" />
</form>
