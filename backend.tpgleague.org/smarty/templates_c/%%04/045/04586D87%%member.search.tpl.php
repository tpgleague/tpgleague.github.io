<?php /* Smarty version 2.6.14, created on 2013-03-24 14:54:55
         compiled from member.search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'member.search.tpl', 88, false),array('modifier', 'iso_datetime', 'member.search.tpl', 96, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Member Search</h2>
					<div class="bold-border-bottom"></div>
				</div>
				
				
				<!-- Begin Siderbar -->
				<div class="four columns">


						<h3 class="headline"> Parameters</h3>

							<p>

<form method="get" action="/member.search.php">

<input type="text" onfocus="if(this.value == 'Search..') this.value = ''" onblur="if(this.value=='')this.value='Search..';" value="Search.." size="80" maxlength="255" name="search" value="<?php echo $_GET['search']; ?>
" />

<br /><label for="users_username">Username</label> <input type="checkbox" name="users_username" <?php if ($_GET['users_username']): ?>checked="checked"<?php endif; ?> id="users_username"   />
<br /><label for="users_handle">Main Handle</label> <input type="checkbox" name="users_handle" <?php if ($_GET['users_handle']): ?>checked="checked"<?php endif; ?> id="users_handle"   />
<br /><label for="users_email">E-mail Address</label> <input type="checkbox" name="users_email" <?php if ($_GET['users_email']): ?>checked="checked"<?php endif; ?>  id="users_email"   />
<br /><label for="users_firstname">First Name</label> <input type="checkbox" name="users_firstname" <?php if ($_GET['users_firstname']): ?>checked="checked"<?php endif; ?> id="users_firstname"   />
<br /><label for="users_lastname">Last Name</label> <input type="checkbox" name="users_lastname" <?php if ($_GET['users_lastname']): ?>checked="checked"<?php endif; ?> id="users_lastname"   />
<br /><label for="rosters_handle">Roster Handle</label> <input type="checkbox" name="rosters_handle" <?php if ($_GET['rosters_handle']): ?>checked="checked"<?php endif; ?> id="rosters_handle"   />
<br /><label for="rosters_gid">Game ID</label> <input type="checkbox" name="rosters_gid" <?php if ($_GET['rosters_gid']): ?>checked="checked"<?php endif; ?> id="rosters_gid"   />
<br /><label for="ip_address">IP Address</label> <input type="checkbox" name="ip_address" <?php if ($_GET['ip_address']): ?>checked="checked"<?php endif; ?> id="ip_address"   />
<br /><label for="ip_hostname">IP Hostname</label> <input type="checkbox" name="ip_hostname" <?php if ($_GET['ip_hostname']): ?>checked="checked"<?php endif; ?> id="ip_hostname"   />


<br /><input type="submit" value="Search" />

</form>
							</p>
					
				</div>
                <br />
				<!-- End Sidebar -->
				
				<!-- Begin Posts -->
				<div class="twelve columns">
					<!-- Post with image -->
					<div class="post post-page">

						<h3 class="headline">Results</h3>
						<div class="clear"></div>
						<div class="post-description">
						
							<p>


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
	<th colspan="6">User Info</th>
	<th colspan="7">Team Info</th>
</tr>
<tr>
	<th>UID</th>
	<th>Username</th>
	<th>Main Handle</th>
	<th>E-mail Address</th>
	<th>Pending E-mail</th>
	<th>First Name</th>
	<th>Last Name</th>
	<th>League</th>
	<th>Handle</th>
	<th>Join Date</th>
	<th>Leave Date</th>
	<th>Team Name</th>
	<th>Team Tag</th>
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
		<td><a href="/edit.user.php?uid=<?php echo $this->_tpl_vars['uid']; ?>
"><?php echo $this->_tpl_vars['uid']; ?>
</a></td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['users_username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['users_handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['users_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['users_pending_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['users_firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['users_lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['leagues_lgname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['rosters_handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['rosters_join_date_gmt'])) ? $this->_run_mod_handler('iso_datetime', true, $_tmp) : smarty_modifier_iso_datetime($_tmp)); ?>
</td>
		<td><?php if ($this->_tpl_vars['member']['rosters_leave_date_gmt'] == 0): ?>-<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['member']['rosters_leave_date_gmt'])) ? $this->_run_mod_handler('iso_datetime', true, $_tmp) : smarty_modifier_iso_datetime($_tmp));  endif; ?></td>
		<td><a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['member']['teams_tid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['member']['teams_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
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

							</p>
							
						</div>
					</div>
				</div>
</div>

			</div>





