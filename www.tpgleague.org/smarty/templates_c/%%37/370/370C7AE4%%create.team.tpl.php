<?php /* Smarty version 2.6.14, created on 2012-03-23 17:57:25
         compiled from create.team.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'create.team.tpl', 22, false),)), $this); ?>
<?php if ($this->_tpl_vars['create_team_success']): ?>
<p>Your team was successfully created.</p>

<p><span class="important">Important</span>: Until your league admin approves your team for play in this league, your players will not see your team listed in the teams dropdown list in the Join Team form. To enable your players to join your team in the meantime, they can instead manually enter the team ID and team password. You should give them the following information:</p>

League: <?php echo $this->_tpl_vars['league_title']; ?>
<br />
Team Name: <?php echo $this->_tpl_vars['create_team_success']['name']; ?>
<br />
Team ID: <?php echo $this->_tpl_vars['create_team_success']['tid']; ?>
<br />
Password: <?php echo $this->_tpl_vars['create_team_success']['pw']; ?>
<br />

<p><a href="/org.cp.php?orgid=<?php echo $_GET['orgid']; ?>
">Manage my teams</a></p>

<?php else: ?>

	<form <?php echo $this->_tpl_vars['create_team_form']['attributes']; ?>
>
	<?php echo $this->_tpl_vars['create_team_form']['hidden']; ?>


	<?php if ($this->_tpl_vars['create_team_form']['errors']): ?>
	<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
	<?php endif; ?>

	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['create_team_form'],'id' => 'fieldset_create_team','class' => 'qffieldset','fields' => 'league_title, lid, name, tag, pw','legend' => 'Team Info'), $this);?>


	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['create_team_form'],'id' => 'fieldset_edit_team_roster','class' => 'qffieldset','fields' => 'add_roster, handle, gid','legend' => 'Join Roster','notes_label' => 'Join Roster','notes' => "<p>If you would additionally like to join this team's roster, checkmark the box at left. You must not be on the roster of any teams in ".($this->_tpl_vars['league_title'])." league.</p>"), $this);?>


	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['create_team_form'],'id' => 'fieldset_create_team_server','class' => 'qffieldset','fields' => 'server_ip, server_port, server_pw, server_location, server_available','legend' => 'Game Server Info','notes_label' => 'Server Availability','notes' => '<p>You must checkmark the "Server Available" box in addition to entering a valid server IP/hostname if you have a server for the upcoming week\'s match. If you do not have a server, our autoscheduler will try its best to schedule you against a team that does have a server and vice-versa. You should update this checkbox on a weekly basis before your league admin runs the autoscheduler.</p><p>N.B.: This option has NO EFFECT on whether you are listed as the home team or away team.</p>'), $this);?>


	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['create_team_form'],'id' => 'fieldset_edit_team_hltv','class' => 'qffieldset','fields' => 'hltv_ip, hltv_port, hltv_pw, hltv_public','legend' => 'HLTV Server Info','notes_label' => 'HLTV information','notes' => '<p>Checkmark the "HLTV Public" box if you would like the HLTV info displayed on the public matchlist. Leave unchecked if you only want it displayed to your opponent for that week.</p>'), $this);?>


	<p><?php echo $this->_tpl_vars['create_team_form']['submit']['html']; ?>
</p>
	</form>

<?php endif; ?>