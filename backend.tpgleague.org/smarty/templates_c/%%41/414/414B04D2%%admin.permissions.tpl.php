<?php /* Smarty version 2.6.14, created on 2013-03-24 15:12:27
         compiled from admin.permissions.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'admin.permissions.tpl', 91, false),)), $this); ?>
<?php 
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
 ?>

<script language="JavaScript" type="text/javascript">
<!--
function permToggle(permID)
{
	var curVal;
	curVal = document.getElementById('perm_' + permID).value;
	if (curVal == 1)
	{
		document.getElementById('perm_' + permID).value = 0;
		document['img_'+permID].src='/images/icons/<?php echo $this->_tpl_vars['images_links']['0']; ?>
.png';
		document['img_'+permID].alt='<?php echo $this->_tpl_vars['images_text']['0']; ?>
';
		document['img_'+permID].title='<?php echo $this->_tpl_vars['images_text']['0']; ?>
';
		return;
	}
	if (curVal == 0)
	{
		document.getElementById('perm_' + permID).value = 'NULL';
		document['img_'+permID].src='/images/icons/<?php echo $this->_tpl_vars['images_links']['NULL']; ?>
.png';
		document['img_'+permID].alt='<?php echo $this->_tpl_vars['images_text']['NULL']; ?>
';
		document['img_'+permID].title='<?php echo $this->_tpl_vars['images_text']['NULL']; ?>
';
		return;
	}
	if (curVal == 'NULL')
	{
		document.getElementById('perm_' + permID).value = 1;
		document['img_'+permID].src='/images/icons/<?php echo $this->_tpl_vars['images_links']['1']; ?>
.png';
		document['img_'+permID].alt='<?php echo $this->_tpl_vars['images_text']['1']; ?>
';
		document['img_'+permID].title='<?php echo $this->_tpl_vars['images_text']['1']; ?>
';
		return;
	}
}

function permCascade(permID)
{
	var search_term = permID+'_';
	var elems = document.getElementsByTagName('img');
	for (var i=0; i<elems.length; i++)
	{
		if ( elems[i].id.indexOf(search_term) != -1 ) {
			var el_name = document.getElementById(elems[i].id).name;
			var sub_el_name = el_name.substring(4);
			document[elems[i].id].src='/images/icons/<?php echo $this->_tpl_vars['images_links']['NULL']; ?>
.png';
			document[elems[i].id].alt='<?php echo $this->_tpl_vars['images_text']['NULL']; ?>
';
			document[elems[i].id].title='<?php echo $this->_tpl_vars['images_text']['NULL']; ?>
';
			document.getElementById('perm_'+sub_el_name).value = 'NULL';
		}

	}
}
//-->
</script>

<div id="key">Key - 
<?php $_from = $this->_tpl_vars['images_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['links']):
 echo $this->_tpl_vars['images_text'][$this->_tpl_vars['key']]; ?>
: <img src="/images/icons/<?php echo $this->_tpl_vars['images_links'][$this->_tpl_vars['key']]; ?>
.png" width="16" height="16" border="0" alt="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['key']]; ?>
" title="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['key']]; ?>
" />
<?php endforeach; endif; unset($_from); ?>
</div>

<form method="post" action="/admin.permissions.php" class="permissions_form">
<input type="hidden" name="permission_aid" value="<?php echo $this->_tpl_vars['form_permission_aid']; ?>
" />
<input type="hidden" name="permission_type" value="<?php echo $this->_tpl_vars['form_permission_type']; ?>
" />

<ul class="global">
<li>Global Access 
<input type="hidden" name="perm_Sitewide_0" id="perm_Sitewide_0" value="<?php echo $this->_tpl_vars['preset_values']['Sitewide_0']; ?>
" />
<img src="/images/icons/<?php echo $this->_tpl_vars['images_links'][$this->_tpl_vars['preset_values']['Sitewide_0']]; ?>
.png" width="16" height="16" border="0" alt="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['preset_values']['Sitewide_0']]; ?>
" title="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['preset_values']['Sitewide_0']]; ?>
" onclick="permToggle('Sitewide_0')" name="img_Sitewide_0" />

	<?php if ($this->_tpl_vars['leagues']): ?>

<img src="/images/icons/<?php echo $this->_tpl_vars['images_links']['cascade']; ?>
.png" width="16" height="16" border="0" alt="<?php echo $this->_tpl_vars['images_text']['cascade']; ?>
" title="<?php echo $this->_tpl_vars['images_text']['cascade']; ?>
" onclick="permCascade('Sitewide_0')" name="Cascade_Sitewide_0" />

	<ul class="league">
	<?php $_from = $this->_tpl_vars['leagues']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lid'] => $this->_tpl_vars['league']):
?>
	<li><?php echo ((is_array($_tmp=$this->_tpl_vars['league']['league_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 
	<?php $this->assign('input_value', "League_".($this->_tpl_vars['lid'])); ?>
	<?php $this->assign('img_link', ($this->_tpl_vars['preset_values'][$this->_tpl_vars['input_value']])); ?>
	<input type="hidden" name="perm_League_<?php echo $this->_tpl_vars['lid']; ?>
" id="perm_League_<?php echo $this->_tpl_vars['lid']; ?>
" value="<?php echo $this->_tpl_vars['preset_values'][$this->_tpl_vars['input_value']]; ?>
" />
	<img src="/images/icons/<?php echo $this->_tpl_vars['images_links'][$this->_tpl_vars['img_link']]; ?>
.png" width="16" height="16" border="0" alt="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['img_link']]; ?>
" title="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['img_link']]; ?>
" onclick="permToggle('League_<?php echo $this->_tpl_vars['lid']; ?>
')" name="img_League_<?php echo $this->_tpl_vars['lid']; ?>
" id="Sitewide_0_League_<?php echo $this->_tpl_vars['lid']; ?>
" />

		<?php if ($this->_tpl_vars['divisions'][$this->_tpl_vars['lid']]): ?>
	
	<img src="/images/icons/<?php echo $this->_tpl_vars['images_links']['cascade']; ?>
.png" width="16" height="16" border="0" alt="<?php echo $this->_tpl_vars['images_text']['cascade']; ?>
" title="<?php echo $this->_tpl_vars['images_text']['cascade']; ?>
" onclick="permCascade('League_<?php echo $this->_tpl_vars['lid']; ?>
')" name="Cascade_League_<?php echo $this->_tpl_vars['lid']; ?>
" />

		<ul class="division">
		<?php $_from = $this->_tpl_vars['divisions'][$this->_tpl_vars['lid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['division']):
?>
			<li><?php echo ((is_array($_tmp=$this->_tpl_vars['division']['division_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 
			<?php $this->assign('input_value', "Division_".($this->_tpl_vars['division']['divid'])); ?>
			<?php $this->assign('img_link', ($this->_tpl_vars['preset_values'][$this->_tpl_vars['input_value']])); ?>
			<input type="hidden" name="perm_Division_<?php echo $this->_tpl_vars['division']['divid']; ?>
" id="perm_Division_<?php echo $this->_tpl_vars['division']['divid']; ?>
" value="<?php echo $this->_tpl_vars['preset_values'][$this->_tpl_vars['input_value']]; ?>
" />
			<img src="/images/icons/<?php echo $this->_tpl_vars['images_links'][$this->_tpl_vars['img_link']]; ?>
.png" width="16" height="16" border="0" alt="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['img_link']]; ?>
" title="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['img_link']]; ?>
" onclick="permToggle('Division_<?php echo $this->_tpl_vars['division']['divid']; ?>
')" name="img_Division_<?php echo $this->_tpl_vars['division']['divid']; ?>
" id="Sitewide_0_League_<?php echo $this->_tpl_vars['lid']; ?>
_Division_<?php echo $this->_tpl_vars['division']['divid']; ?>
" />

				<?php if ($this->_tpl_vars['conferences'][$this->_tpl_vars['division']['divid']]): ?>

			<img src="/images/icons/<?php echo $this->_tpl_vars['images_links']['cascade']; ?>
.png" width="16" height="16" border="0" alt="<?php echo $this->_tpl_vars['images_text']['cascade']; ?>
" title="<?php echo $this->_tpl_vars['images_text']['cascade']; ?>
" onclick="permCascade('Division_<?php echo $this->_tpl_vars['division']['divid']; ?>
')" name="Cascade_Division_<?php echo $this->_tpl_vars['division']['divid']; ?>
" />

				<ul class="conference">
				<?php $_from = $this->_tpl_vars['conferences'][$this->_tpl_vars['division']['divid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['conference']):
?>
					<li><?php echo ((is_array($_tmp=$this->_tpl_vars['conference']['conference_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 
					<?php $this->assign('input_value', "Conference_".($this->_tpl_vars['conference']['cfid'])); ?>
					<?php $this->assign('img_link', ($this->_tpl_vars['preset_values'][$this->_tpl_vars['input_value']])); ?>
					<input type="hidden" name="perm_Conference_<?php echo $this->_tpl_vars['conference']['cfid']; ?>
" id="perm_Conference_<?php echo $this->_tpl_vars['conference']['cfid']; ?>
" value="<?php echo $this->_tpl_vars['preset_values'][$this->_tpl_vars['input_value']]; ?>
" />
					<img src="/images/icons/<?php echo $this->_tpl_vars['images_links'][$this->_tpl_vars['img_link']]; ?>
.png" width="16" height="16" border="0" alt="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['img_link']]; ?>
" title="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['img_link']]; ?>
" onclick="permToggle('Conference_<?php echo $this->_tpl_vars['conference']['cfid']; ?>
')" name="img_Conference_<?php echo $this->_tpl_vars['conference']['cfid']; ?>
" id="Sitewide_0_League_<?php echo $this->_tpl_vars['lid']; ?>
_Division_<?php echo $this->_tpl_vars['division']['divid']; ?>
_Conference_<?php echo $this->_tpl_vars['conference']['cfid']; ?>
" />

						<?php if ($this->_tpl_vars['groups'][$this->_tpl_vars['conference']['cfid']]): ?>

				<img src="/images/icons/<?php echo $this->_tpl_vars['images_links']['cascade']; ?>
.png" width="16" height="16" border="0" alt="<?php echo $this->_tpl_vars['images_text']['cascade']; ?>
" title="<?php echo $this->_tpl_vars['images_text']['cascade']; ?>
" onclick="permCascade('Conference_<?php echo $this->_tpl_vars['conference']['cfid']; ?>
')" name="Cascade_Conference_<?php echo $this->_tpl_vars['conference']['cfid']; ?>
" />

						<ul class="group">
						<?php $_from = $this->_tpl_vars['groups'][$this->_tpl_vars['conference']['cfid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group']):
?>
							<li><?php echo ((is_array($_tmp=$this->_tpl_vars['group']['group_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 
							<?php $this->assign('input_value', "Group_".($this->_tpl_vars['group']['grpid'])); ?>
							<?php $this->assign('img_link', ($this->_tpl_vars['preset_values'][$this->_tpl_vars['input_value']])); ?>
							<input type="hidden" name="perm_Group_<?php echo $this->_tpl_vars['group']['grpid']; ?>
" id="perm_Group_<?php echo $this->_tpl_vars['group']['grpid']; ?>
" value="<?php echo $this->_tpl_vars['preset_values'][$this->_tpl_vars['input_value']]; ?>
" />
							<img src="/images/icons/<?php echo $this->_tpl_vars['images_links'][$this->_tpl_vars['img_link']]; ?>
.png" width="16" height="16" border="0" alt="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['img_link']]; ?>
" title="<?php echo $this->_tpl_vars['images_text'][$this->_tpl_vars['img_link']]; ?>
" onclick="permToggle('Group_<?php echo $this->_tpl_vars['group']['grpid']; ?>
')" name="img_Group_<?php echo $this->_tpl_vars['group']['grpid']; ?>
" id="Sitewide_0_League_<?php echo $this->_tpl_vars['lid']; ?>
_Division_<?php echo $this->_tpl_vars['division']['divid']; ?>
_Conference_<?php echo $this->_tpl_vars['conference']['cfid']; ?>
_Group_<?php echo $this->_tpl_vars['group']['grpid']; ?>
" />
							</li>
						<?php endforeach; endif; unset($_from); ?>
						</ul>
						<?php endif; ?>					


					</li>
				<?php endforeach; endif; unset($_from); ?>
				</ul>
				<?php endif; ?>


			</li>
		<?php endforeach; endif; unset($_from); ?>
		</ul>
		<?php endif; ?>


	</li>
	<?php endforeach; endif; unset($_from); ?>
	</ul>
	<?php endif; ?>
</li>
</ul>

<input type="submit" value="Submit" />
</form>