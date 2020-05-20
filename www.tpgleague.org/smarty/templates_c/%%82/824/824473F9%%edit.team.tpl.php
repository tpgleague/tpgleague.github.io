<?php /* Smarty version 2.6.14, created on 2012-11-18 21:03:16
         compiled from edit.team.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.team.tpl', 14, false),)), $this); ?>
<div>

<?php if ($this->_tpl_vars['edit_team_success']): ?>
<p>Your changes were successful.</p>
<?php endif; ?>

<form <?php echo $this->_tpl_vars['edit_team_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_team_form']['hidden']; ?>


<?php if ($this->_tpl_vars['edit_team_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_team_form'],'id' => 'fieldset_edit_team','class' => 'qffieldset','fields' => 'tid, name, tag, pw, team_avatar_url','legend' => 'Edit Team Info'), $this);?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_team_form'],'id' => 'fieldset_edit_team_server','class' => 'qffieldset','fields' => 'server_ip, server_port, server_pw, server_location, server_available','legend' => 'Game Server Info','notes_label' => 'Server Availability','notes' => '<p>You must checkmark the "Server Available" box in addition to entering a valid server IP/hostname if you have a server for the upcoming week\'s match. If you do not have a server, our autoscheduler will try its best to schedule you against a team that does have a server and vice-versa. You should update this checkbox on a weekly basis before your league admin runs the autoscheduler.</p><p>N.B.: This option has NO EFFECT on whether you are listed as the home team or away team.</p>'), $this);?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_team_form'],'id' => 'fieldset_edit_team_hltv','class' => 'qffieldset','fields' => 'hltv_ip, hltv_port, hltv_pw, hltv_public','legend' => 'HLTV Server Info','notes_label' => 'HLTV information','notes' => '<p>Checkmark the "HLTV Public" box if you would like the HLTV info displayed on the public matchlist. Leave unchecked if you only want it displayed to your opponent for that week.</p>'), $this);?>


<p><?php echo $this->_tpl_vars['edit_team_form']['submit']['html']; ?>
</p>
</form>

</div>