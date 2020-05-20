<?php /* Smarty version 2.6.14, created on 2012-11-18 03:21:42
         compiled from member.search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'member.search.tpl', 85, false),array('modifier', 'date_format', 'member.search.tpl', 88, false),)), $this); ?>
<form method="get" action="<?php echo $this->_tpl_vars['lgname']; ?>
/membersearch/">

<input type="text" size="80" maxlength="255" name="search" value="<?php echo $_GET['search']; ?>
" />

<br /><label for="rosters_handle">Roster Handle</label> <input type="checkbox" name="rosters_handle" <?php if ($_GET['rosters_handle']): ?>checked="checked"<?php endif; ?> id="rosters_handle"   />
<br /><label for="users_firstname">First Name</label> <input type="checkbox" name="users_firstname" <?php if ($_GET['users_firstname']): ?>checked="checked"<?php endif; ?> id="users_firstname"   />
<br /><label for="users_lastname">Last Name</label> <input type="checkbox" name="users_lastname" <?php if ($_GET['users_lastname']): ?>checked="checked"<?php endif; ?> id="users_lastname"   />
<br /><label for="rosters_gid">Game ID</label> <input type="checkbox" name="rosters_gid" <?php if ($_GET['rosters_gid']): ?>checked="checked"<?php endif; ?> id="rosters_gid"   />

<br /><input type="submit" value="Search" />


</form>

<br />

<?php if ($this->_tpl_vars['search_error']): ?>
<p><?php echo $this->_tpl_vars['search_error']; ?>
</p>

<?php elseif ($_GET['search']): ?>

<?php $_from = $this->_tpl_vars['search_results']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['result'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['result']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['uid'] => $this->_tpl_vars['result']):
        $this->_foreach['result']['iteration']++;
?>
<table class="search_results">
<thead>

<tr>
	<th>Name</th>
	<th>League</th>
	<th>Handle</th>
	<th>Joined</th>
	<th>Left</th>
	<th>Team Name</th>
	<th>Tag</th>
	<th>Game ID</th>
</tr>
</thead>
<tbody>
	<?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['member'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['member']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['member']):
        $this->_foreach['member']['iteration']++;
?>
	<tr>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['users_firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  if ($this->_tpl_vars['member']['hide_lastname'] == 0): ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['member']['users_lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?></td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['leagues_lgname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['rosters_handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['rosters_join_date_gmt'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D") : smarty_modifier_date_format($_tmp, "%D")); ?>
</td>
		<td><?php if ($this->_tpl_vars['member']['rosters_leave_date_gmt'] == 0): ?>-<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['member']['rosters_leave_date_gmt'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D") : smarty_modifier_date_format($_tmp, "%D"));  endif; ?></td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['teams_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['teams_tag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo $this->_tpl_vars['member']['rosters_gid']; ?>
</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
<br />
<?php endforeach; else: ?>
<p>No results found.</p>
<?php endif; unset($_from);  endif; ?>
