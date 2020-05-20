<?php /* Smarty version 2.6.14, created on 2013-03-24 13:45:00
         compiled from suspensions.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'suspensions.tpl', 26, false),array('modifier', 'escape', 'suspensions.tpl', 60, false),array('modifier', 'converted_timezone', 'suspensions.tpl', 66, false),)), $this); ?>
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

<form <?php echo $this->_tpl_vars['add_suspension_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['add_suspension_form']['hidden']; ?>


<?php if ($this->_tpl_vars['add_suspension_form']['errors']): ?>
<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
<?php endif; ?>

<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['add_suspension_form'],'id' => 'fieldset_add_suspension','class' => 'qffieldset','fields' => 'uid, username, firstname, lastname, handle, reason, rule_violation, type, start, end, tid, team, mid, lid, gids, stank_ticket_number','legend' => 'Add Suspension'), $this);?>

<p><?php echo $this->_tpl_vars['add_suspension_form']['submit']['html']; ?>
</p>
</form>

<?php if ($this->_tpl_vars['success']): ?>
<div style="color: blue;"><br /><br /><br />Suspension Added. Click Add Suspension in the menu to reset the form and add a new suspension.</div>
<br />
<?php endif; ?>

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
<?php $_from = $this->_tpl_vars['existing_suspensions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['suspension']):
?>
<?php if ($this->_tpl_vars['suspension']['end_date'] < time() && $this->_tpl_vars['suspension']['deleted']): ?><tr style="color: gray; text-decoration: line-through;">
<?php elseif ($this->_tpl_vars['suspension']['end_date'] < time()): ?><tr style="color: gray;">
<?php elseif ($this->_tpl_vars['suspension']['deleted']): ?><tr style="text-decoration: line-through;">
<?php else: ?><tr>
<?php endif; ?>
    <td><a href="edit.suspension.php?suspid=<?php echo $this->_tpl_vars['suspension']['suspid']; ?>
">edit</a></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['suspension']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
    <td><?php if ($this->_tpl_vars['suspension']['tid']): ?><a href="edit.team.php?tid=<?php echo $this->_tpl_vars['suspension']['tid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['suspension']['team_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><?php endif; ?></td>
    <td><?php if ($this->_tpl_vars['suspension']['lid']): ?><a href="edit.league.php?lid=<?php echo $this->_tpl_vars['suspension']['lid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['suspension']['lgname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><?php else: ?>All<?php endif; ?></td>
    <td><?php if ($this->_tpl_vars['suspension']['lid']): ?><a href="http://www.tpgleague.org/<?php echo $this->_tpl_vars['suspension']['lgname']; ?>
/rules/#<?php echo $this->_tpl_vars['suspension']['rule_violation']; ?>
"><?php endif;  echo $this->_tpl_vars['suspension']['rule_violation'];  if ($this->_tpl_vars['suspension']['lid']): ?></a><?php endif; ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['suspension']['reason'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
    <td><?php if ($this->_tpl_vars['suspension']['stank_ticket_number']): ?><a href="http://support.tpgleague.org/ticket/admin.ticket_summary.php?ticket_id=<?php echo $this->_tpl_vars['suspension']['stank_ticket_number']; ?>
"><?php echo $this->_tpl_vars['suspension']['stank_ticket_number']; ?>
</a><?php endif; ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['suspension']['start_date'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['suspension']['end_date'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
    <td><?php echo $this->_tpl_vars['suspension']['gid']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
						</p>

					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->




