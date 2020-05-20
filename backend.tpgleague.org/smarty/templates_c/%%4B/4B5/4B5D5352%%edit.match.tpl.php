<?php /* Smarty version 2.6.14, created on 2012-03-02 20:07:21
         compiled from edit.match.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'edit.match.tpl', 19, false),array('modifier', 'converted_timezone', 'edit.match.tpl', 20, false),array('modifier', 'nl2br', 'edit.match.tpl', 21, false),array('function', 'quickform_fieldset', 'edit.match.tpl', 29, false),)), $this); ?>
<p><a href="/edit.matches.php?sch_id=<?php echo @SCH_ID; ?>
">Return to matches scheduler</a></p>

<?php if (isset ( $_GET['delete'] )): ?>

	<div style="align: center;">
	Are you sure you wish to delete this match?

	<p><a href="/edit.match.php?mid=<?php echo $_GET['mid']; ?>
&amp;delete=confirm">DELETE</a> ----- <a href="/edit.match.php?mid=<?php echo $_GET['mid']; ?>
">Go back</a></p>

	</div>

<?php elseif (@lid): ?>


<div>
	<h1>Match Notes</h1>
	<?php $_from = $this->_tpl_vars['admin_notes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['note'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['note']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['note']):
        $this->_foreach['note']['iteration']++;
?>
	<div>
	Added by: <?php echo ((is_array($_tmp=$this->_tpl_vars['note']['admin_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br />
	Date: <?php echo ((is_array($_tmp=$this->_tpl_vars['note']['unix_create_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
<br />
	<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['note']['comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

	<?php if (! ($this->_foreach['note']['iteration'] == $this->_foreach['note']['total'])): ?><hr style="width: 200px; text-align: left; margin: 5px; auto 5px 0;" /><?php endif; ?>
	</div>
	<?php endforeach; endif; unset($_from); ?>

	<form <?php echo $this->_tpl_vars['admin_notes_form']['attributes']; ?>
>
	<?php echo $this->_tpl_vars['admin_notes_form']['hidden']; ?>


	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['admin_notes_form'],'id' => 'fieldset_admin_notes','class' => 'qffieldset','fields' => 'comment','legend' => 'Add Note'), $this);?>

	<p><?php echo $this->_tpl_vars['admin_notes_form']['submit']['html']; ?>
</p>

	</form>
	<br />
</div>

<br />

<hr />


<div>
<?php if ($this->_tpl_vars['success']): ?>
Your changes were successful.
<?php endif; ?>
</div>

<div style="color: red; font-weight: bold;">
<?php if ($this->_tpl_vars['match_scores_failure']): ?>
<p>There was a problem with the scores form you submitted below.</p>
<hr />
<?php endif; ?>
</div>


<div>
<?php echo $this->_tpl_vars['unreport_form']; ?>

</div>

<br />

<form <?php echo $this->_tpl_vars['edit_match_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_match_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_match_form'],'id' => 'fieldset_edit_match','class' => 'qffieldset','fields' => 'mid, away_tid, home_tid, confirmed_mpid, start_date_gmt, report_date, reporting_user, reporting_admin_name, match_comments, forfeit_away, forfeit_home, deleted, important_note, submit','legend' => 'Edit Match'), $this);?>


</form>

<br />



<form <?php echo $this->_tpl_vars['report_match_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['report_match_form']['hidden']; ?>

<table id="report_match_table">
<tbody>
<tr>
<th colspan="3" style="text-align:center;">First Half</th></tr>
<tr>
<th>Side</th>
<th>Team</th>
<th align="right">Score</th>
</tr>
<tr>
<td id="h1a_side" class="sidecol"><?php echo $this->_tpl_vars['report_match_form']['side_selector_h1a']['html']; ?>
</td>
<td><?php echo $this->_tpl_vars['report_match_form']['away_team_name']['html']; ?>
</td>
<td align="center" class="scorecol"><?php echo $this->_tpl_vars['report_match_form']['h1a_score']['html']; ?>
</td>
</tr>
<tr>
<td id="h1h_side" class="sidecol"><?php echo $this->_tpl_vars['report_match_form']['side_selector_h1h']['html']; ?>
</td>
<td><?php echo $this->_tpl_vars['report_match_form']['home_team_name']['html']; ?>
</td>
<td align="center" class="scorecol"><?php echo $this->_tpl_vars['report_match_form']['h1h_score']['html']; ?>
</td>
</tr>
</tbody>

<tbody>
<tr>
<th colspan="3" style="text-align:center;">Second Half</th></tr>
<tr>
<th>Side</th>
<th>Team</th>
<th>Score</th>
</tr>
<tr>
<td id="h2a_side" class="sidecol"><?php echo $this->_tpl_vars['report_match_form']['side_selector_h2a']['html']; ?>
</td>
<td><?php echo $this->_tpl_vars['report_match_form']['away_team_name']['html']; ?>
</td>
<td align="center" class="scorecol"><?php echo $this->_tpl_vars['report_match_form']['h2a_score']['html']; ?>
</td>
</tr>
<tr>
<td id="h2h_side" class="sidecol"><?php echo $this->_tpl_vars['report_match_form']['side_selector_h2h']['html']; ?>
</td>
<td><?php echo $this->_tpl_vars['report_match_form']['home_team_name']['html']; ?>
</td>
<td align="center" class="scorecol"><?php echo $this->_tpl_vars['report_match_form']['h2h_score']['html']; ?>
</td>
</tr>
</tbody>

<tbody>
<tr>
<td><?php echo $this->_tpl_vars['report_match_form']['submit']['html']; ?>
</td>
</tr>
</tbody>
</table>
</form>






<?php else: ?>

<div>No match by that ID found.</div>


<?php endif; ?>