<?php /* Smarty version 2.6.14, created on 2012-03-01 19:56:35
         compiled from edit.matches.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.matches.tpl', 22, false),array('modifier', 'escape', 'edit.matches.tpl', 37, false),array('modifier', 'converted_timezone', 'edit.matches.tpl', 38, false),array('modifier', 'nl2br', 'edit.matches.tpl', 39, false),array('modifier', 'default', 'edit.matches.tpl', 76, false),array('modifier', 'upper', 'edit.matches.tpl', 155, false),)), $this); ?>

<p><a href="/edit.schedule.php?sid=<?php echo @SID; ?>
">Return to calendar.</a></p>

<?php if ($this->_tpl_vars['schedule_error']): ?>
<div style="border: 3px solid red; padding: 5px; margin: 5px;">
	<h4>Scheduling Error:</h4>
	<ul>
		<?php $_from = $this->_tpl_vars['schedule_error']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['err']):
?>
		<li><?php echo $this->_tpl_vars['err']; ?>
</li>
		<?php endforeach; endif; unset($_from); ?>
	</ul>
</div>
<hr />
<?php endif; ?>


<div>

<form <?php echo $this->_tpl_vars['edit_schedule_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_schedule_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_schedule_form'],'id' => 'fieldset_edit_schedule','class' => 'qffieldset','fields' => 'sch_id, mapid, stg_type, stg_number, stg_short_desc, stg_match_date_gmt, stg_latest_match_date_gmt, deleted, submit','legend' => 'Edit Schedule'), $this);?>


</form>


</div>

<br />

<hr />

<div>
	<h1>Schedule Notes</h1>
	<?php $_from = $this->_tpl_vars['schedule_notes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['note'] = array('total' => count($_from), 'iteration' => 0);
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
	<br />
	<?php endforeach; endif; unset($_from); ?>

	<form <?php echo $this->_tpl_vars['schedule_notes_form']['attributes']; ?>
>
	<?php echo $this->_tpl_vars['schedule_notes_form']['hidden']; ?>


	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['schedule_notes_form'],'id' => 'fieldset_schedule_notes','class' => 'qffieldset','fields' => 'comment','legend' => 'Add Note'), $this);?>

	<p><?php echo $this->_tpl_vars['schedule_notes_form']['submit']['html']; ?>
</p>

	</form>
	<br />
</div>

<hr />

<br />


<div>
<table>
<caption>Scheduled Matches</caption>

<tr>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>Away Team</th>
<th>Home Team</th>
<th>Match Time</th>
<th>Proposals</th>
</tr>
<?php $_from = $this->_tpl_vars['scheduled']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['match']):
?>
<tr<?php if ($this->_tpl_vars['match']['deleted']): ?> style="text-decoration: line-through;"<?php endif; ?>>
<td><?php if ($this->_tpl_vars['match']['deleted']): ?>DELETED!<?php else: ?>&nbsp;<?php endif; ?></td>
<td><a href="/edit.match.php?mid=<?php echo $this->_tpl_vars['match']['mid']; ?>
">Edit</a></td>
<td <?php if ($this->_tpl_vars['match']['away_messages'] || $this->_tpl_vars['match']['start_date_gmt']): ?>style="font-weight: bold;"<?php endif; ?>><?php if ($this->_tpl_vars['match']['away_tid']): ?><a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['match']['away_tid']; ?>
" style="color: black;"><?php endif;  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['match']['away_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '<i>Bye</i>') : smarty_modifier_default($_tmp, '<i>Bye</i>'));  if ($this->_tpl_vars['match']['away_tid']): ?></a><?php endif; ?></td>
<td <?php if ($this->_tpl_vars['match']['home_messages'] || $this->_tpl_vars['match']['start_date_gmt']): ?>style="font-weight: bold;"<?php endif; ?>><?php if ($this->_tpl_vars['match']['home_tid']): ?><a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['match']['home_tid']; ?>
" style="color: black;"><?php endif;  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['match']['home_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '<i>Bye</i>') : smarty_modifier_default($_tmp, '<i>Bye</i>'));  if ($this->_tpl_vars['match']['home_tid']): ?></a><?php endif; ?></td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['match']['start_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
<td><?php if ($this->_tpl_vars['match']['proposals_exist']): ?><a href="/view.match.proposals.php?mid=<?php echo $this->_tpl_vars['match']['mid']; ?>
">View</a><?php endif; ?></td>
</tr>
<?php endforeach; else: ?>
No matches currently scheduled.
<?php endif; unset($_from); ?>
</table>
</div>

<br />
<hr />

	<div>
	<table>
	<caption>Pending Queue</caption>

	<tr>
	<th>Division</th>
	<th>Conference</th>
	<th>Team</th>
	<th>&nbsp;</th>
	</tr>

	<?php $_from = $this->_tpl_vars['listings_divisions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['divid'] => $this->_tpl_vars['division']):
?>
	<?php $_from = $this->_tpl_vars['listings_conferences'][$this->_tpl_vars['divid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['conference']):
?>
	<tr>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['division']['division_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['conference']['conference_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<?php $_from = $this->_tpl_vars['listings_pending']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pending_tid'] => $this->_tpl_vars['pending']):
?>
		<?php if ($this->_tpl_vars['pending']['cfid'] == $this->_tpl_vars['conference']['cfid']): ?>
			<td><a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['pending_tid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['pending']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
			<td>
				<form method="post" action="/edit.matches.php?sch_id=<?php echo @SCH_ID; ?>
" style="display:inline;">
					<input type="hidden" name="mpnid" value="<?php echo $this->_tpl_vars['pending']['mpnid']; ?>
" />
					<input type="hidden" name="remove_pending" value="true" />
					<input type="submit" value="Dequeue" style="width:auto; height:auto;" />
				</form>
			</td>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>
	</table>
	</div>

<br />
<hr />

<?php $this->assign('grayedout', ' opacity:.50; filter: alpha(opacity=50); -moz-opacity: 0.50;'); ?>

<div><p>Teams that aren't explicitly assigned to a specific group won't show up here. They must also be approved. You can modify groups via the <a href="/teams.manager.php?lid=<?php echo @LID; ?>
">teams manager</a>. The autoscheduler will ignore any teams that are inside inactive groups or inactive conferences.</p>
<p>The autoscheduler will not schedule teams that are inactive. You can schedule them manually.  Usually, the only reason to do so would be to give that team a bye loss, because the opponent they forfeited to found another team to do a makeup match with.</p></div>

<hr />

<div>
<h2>Advanced Scheduler</h2>

<form method="post" action="/edit.matches.php?sch_id=<?php echo @SCH_ID; ?>
" id="report_match_form" >

<br />Notify teams via e-mail that they have been scheduled together (N/A to byes or forfeits) <input type="checkbox" name="notify" <?php if (( $_POST['submit'] != 'Schedule' ) || $_POST['notify']): ?>checked="checked"<?php endif; ?> />

<br />
<br />

<table border="0">
<tr><td>Team 1:</td><td>Team 2:</td></tr>
<tr><td>
<select name="team1">
<option>Select Team 1</option>
<option <?php if ($_POST['team1'] == 'pending'): ?>selected="selected"<?php endif; ?> value="pending">Opponent Pending</option>
<option <?php if ($_POST['team1'] == 'bye_win'): ?>selected="selected"<?php endif; ?> value="bye_win">Bye Win</option>
<option <?php if ($_POST['team1'] == 'bye_win_ff'): ?>selected="selected"<?php endif; ?> value="bye_win_ff">Bye Win (Forfeit)</option>
<option <?php if ($_POST['team1'] == 'bye_loss'): ?>selected="selected"<?php endif; ?> value="bye_loss">Bye Loss</option>
<option <?php if ($_POST['team1'] == 'bye_loss_ff'): ?>selected="selected"<?php endif; ?> value="bye_loss_ff">Bye Loss (Forfeit)</option>
<?php $_from = $this->_tpl_vars['listings_divisions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['divid'] => $this->_tpl_vars['division']):
?>
	<optgroup label="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['division']['division_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
	<?php $_from = $this->_tpl_vars['team_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dropdown']):
?>
	<?php if ($this->_tpl_vars['dropdown']['divid'] == $this->_tpl_vars['divid']): ?>
		<option value="<?php echo $this->_tpl_vars['dropdown']['tid']; ?>
"
			<?php if ($_POST['team2'] == $this->_tpl_vars['dropdown']['tid']): ?>selected="selected"<?php endif; ?> 
			<?php if ($this->_tpl_vars['scheduled_teams_list'][$this->_tpl_vars['dropdown']['tid']]): ?>style="color: gray;"<?php endif; ?>
		><?php echo ((is_array($_tmp=$this->_tpl_vars['dropdown']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  if ($this->_tpl_vars['dropdown']['inactive']): ?> [I]<?php endif; ?>
		</option>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	</optgroup>
<?php endforeach; endif; unset($_from); ?>
</select>
</td>

<td>
<select name="team2">
<option>Select Team 2</option>
<option <?php if ($_POST['team2'] == 'pending'): ?>selected="selected"<?php endif; ?> value="pending">Opponent Pending</option>
<option <?php if ($_POST['team2'] == 'bye_win'): ?>selected="selected"<?php endif; ?> value="bye_win">Bye Win</option>
<option <?php if ($_POST['team2'] == 'bye_win_ff'): ?>selected="selected"<?php endif; ?> value="bye_win_ff">Bye Win (Forfeit)</option>
<option <?php if ($_POST['team2'] == 'bye_loss'): ?>selected="selected"<?php endif; ?> value="bye_loss">Bye Loss</option>
<option <?php if ($_POST['team2'] == 'bye_loss_ff'): ?>selected="selected"<?php endif; ?> value="bye_loss_ff">Bye Loss (Forfeit)</option>
<?php $_from = $this->_tpl_vars['listings_divisions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['divid'] => $this->_tpl_vars['division']):
?>
	<optgroup label="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['division']['division_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
	<?php $_from = $this->_tpl_vars['team_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dropdown']):
?>
	<?php if ($this->_tpl_vars['dropdown']['divid'] == $this->_tpl_vars['divid']): ?>
		<option value="<?php echo $this->_tpl_vars['dropdown']['tid']; ?>
"
			<?php if ($_POST['team2'] == $this->_tpl_vars['dropdown']['tid']): ?>selected="selected"<?php endif; ?> 
			<?php if ($this->_tpl_vars['scheduled_teams_list'][$this->_tpl_vars['dropdown']['tid']]): ?>style="color: gray;"<?php endif; ?>
		><?php echo ((is_array($_tmp=$this->_tpl_vars['dropdown']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  if ($this->_tpl_vars['dropdown']['inactive']): ?> [I]<?php endif; ?>
		</option>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	</optgroup>
<?php endforeach; endif; unset($_from); ?>
</select>
</td></tr>

<tr>
	<td>
	Team 1 (left side) is the:
	<select name="court">
		<option <?php if ($_POST['court'] == 'auto'): ?>selected="selected"<?php endif; ?> value="auto">[Auto]</option>
		<option <?php if ($_POST['court'] == 'home'): ?>selected="selected"<?php endif; ?> value="home">Home team</option>
		<option <?php if ($_POST['court'] == 'away'): ?>selected="selected"<?php endif; ?> value="away">Away team</option>
	</select>
	</td>

	<td>Key:
	<br /><span style="">[I] Inactive</span>
	<br /><span style="color: gray;">[S] Scheduled</span>
	</td>
</tr>
</table>

<div>
Admin Note (Optional):
<br /><textarea rows="5" cols="50" name="match_admin_note" ><?php echo ((is_array($_tmp=$_POST['match_admin_note'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
</div>

<br />Override any previously scheduled matches for these two teams (award old opponents forfeit losses. Used for makeup matches.) Use with care!!: 
	<input type="checkbox" <?php if ($_POST['override_makeup_match']): ?>checked="checked"<?php endif; ?> name="override_makeup_match" />
<br />

<br /><input type="checkbox" name="team1_ff" <?php if ($_POST['team1_ff']): ?>selected="selected"<?php endif; ?> /> Team 1 forfeits.
<br /><input type="checkbox" name="team2_ff" <?php if ($_POST['team2_ff']): ?>selected="selected"<?php endif; ?> /> Team 2 forfeits.

<br />

<table id="report_match_table">
	<caption>Report match (Optional. Just fill out all fields with values):</caption>
	<tbody>
	<tr>
	<th colspan="3" style="text-align:center;">First Half</th></tr>
	<tr>
	<th>Side</th>
	<th>Team</th>
	<th align="right">Score</th>
	</tr>
	<tr>
	<td id="h1a_side" class="sidecol"><select name="side_selector_h1a" onchange="changeSides('h1a');">
		<option value=""></option>
		<?php $_from = $this->_tpl_vars['league_sides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['side_key'] => $this->_tpl_vars['side']):
?>
		<option <?php if ($_POST['side_selector_h1a'] == $this->_tpl_vars['side_key']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['side_key']; ?>
"><?php echo $this->_tpl_vars['side']; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	</select></td>
	<td>Team 1</td>
	<td align="center" class="scorecol"><input name="h1a_score" type="text" value="<?php echo ((is_array($_tmp=$_POST['h1a_score'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
	</tr>
	<tr>
	<td id="h1h_side" class="sidecol"><select name="side_selector_h1h" onchange="changeSides('h1h');">
		<option value=""></option>
		<?php $_from = $this->_tpl_vars['league_sides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['side_key'] => $this->_tpl_vars['side']):
?>
		<option <?php if ($_POST['side_selector_h1h'] == $this->_tpl_vars['side_key']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['side_key']; ?>
"><?php echo $this->_tpl_vars['side']; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	</select></td>
	<td>Team 2</td>
	<td align="center" class="scorecol"><input name="h1h_score" type="text" value="<?php echo ((is_array($_tmp=$_POST['h1h_score'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
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
	<td id="h2a_side" class="sidecol"><select name="side_selector_h2a" onchange="changeSides('h2a');">
		<option value=""></option>
		<?php $_from = $this->_tpl_vars['league_sides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['side_key'] => $this->_tpl_vars['side']):
?>
		<option <?php if ($_POST['side_selector_h2a'] == $this->_tpl_vars['side_key']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['side_key']; ?>
"><?php echo $this->_tpl_vars['side']; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	</select></td>
	<td>Team 1</td>
	<td align="center" class="scorecol"><input name="h2a_score" type="text" value="<?php echo ((is_array($_tmp=$_POST['h2a_score'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
	</tr>
	<tr>
	<td id="h2h_side" class="sidecol"><select name="side_selector_h2h" onchange="changeSides('h2h');">
		<option value=""></option>
		<?php $_from = $this->_tpl_vars['league_sides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['side_key'] => $this->_tpl_vars['side']):
?>
		<option <?php if ($_POST['side_selector_h2h'] == $this->_tpl_vars['side_key']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['side_key']; ?>
"><?php echo $this->_tpl_vars['side']; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	</select></td>
	<td>Team 2</td>
	<td align="center" class="scorecol"><input name="h2h_score" type="text" value="<?php echo ((is_array($_tmp=$_POST['h2h_score'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
	</tr>
	</tbody>

</table>

<br /><input type="submit" name="submit" value="Schedule" />
<br />
<br />
</form>

</div>

<hr />

<div style="width:40%;">

	<?php $_from = $this->_tpl_vars['listings_divisions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['group']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['divid'] => $this->_tpl_vars['division']):
        $this->_foreach['group']['iteration']++;
?>
		<div class="division" id="divid_<?php echo $this->_tpl_vars['divid']; ?>
" <?php if ($this->_tpl_vars['division']['inactive']): ?>style="<?php echo $this->_tpl_vars['grayedout']; ?>
"<?php endif; ?>>
			<h3>Division: <?php echo ((is_array($_tmp=$this->_tpl_vars['division']['division_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h3>

			<?php $_from = $this->_tpl_vars['listings_conferences'][$this->_tpl_vars['divid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['conference'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['conference']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['conference']):
        $this->_foreach['conference']['iteration']++;
?>
				<div class="conference" id="cfid_<?php echo $this->_tpl_vars['conference']['cfid']; ?>
" style="margin-top: 2px; margin-bottom: 6px; <?php if ($this->_tpl_vars['conference']['inactive']):  echo $this->_tpl_vars['grayedout'];  endif; ?>">
					<h3>Conference: <?php echo ((is_array($_tmp=$this->_tpl_vars['conference']['conference_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <span class="auto_scheduler">(<a href="/auto-scheduler.php?sch_id=<?php echo @SCH_ID; ?>
&amp;cfid=<?php echo $this->_tpl_vars['conference']['cfid']; ?>
">run auto-scheduler</a>)</span></h3>

					<?php $_from = $this->_tpl_vars['listings_groups'][$this->_tpl_vars['conference']['cfid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['group']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['group']):
        $this->_foreach['group']['iteration']++;
?>
						<div class="section" id="group_<?php echo $this->_tpl_vars['divid']; ?>
-<?php echo $this->_tpl_vars['conference']['cfid']; ?>
-<?php echo $this->_tpl_vars['group']['grpid']; ?>
" style="background-color: #CCFFFF; border-color: #99FFFF; margin-top: 1px; margin-bottom: 2px; <?php if ($this->_tpl_vars['group']['inactive']):  echo $this->_tpl_vars['grayedout'];  endif; ?>">
							<h3 class="handle" style="margin-bottom: 0px; background-color: #99FFFF; padding-top: 0px; padding-bottom: 0px;">Group: <?php echo ((is_array($_tmp=$this->_tpl_vars['group']['group_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h3>

									<table cellspacing="0" style="width: 100%;">
									<thead>
									<tr>
									<th style="text-align: left;">Team</th>
									<th style="text-align: right;">Opponent</th>
									</tr>
									</thead>
									<?php $_from = $this->_tpl_vars['listings_teams'][$this->_tpl_vars['group']['grpid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['team'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['team']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['team']):
        $this->_foreach['team']['iteration']++;
?>
									<tr style="border: 1px solid black; padding: 3px;">
									<td style="margin: 0; padding: 3px; border-top: 1px solid black; text-align: left;">
										<a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['team']['tid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><?php if ($this->_tpl_vars['team']['inactive']): ?> [<i>Inactive</i>]<?php endif; ?>
									</td>

									<td style="margin: 0; padding: 3px; border-top: 1px solid black; text-align: right;">

									<?php if ($this->_tpl_vars['team']['opponent_name']): ?>
										<?php echo $this->_tpl_vars['team']['opponent_name'];  if ($this->_tpl_vars['team']['opponent_inactive']): ?> [<i>Inactive</i>]<?php endif; ?>
									<?php elseif (isset ( $this->_tpl_vars['listings_pending'][$this->_tpl_vars['team']['tid']] )): ?>
										<i>Pending Queue</i> 										<form method="post" action="/edit.matches.php?sch_id=<?php echo @SCH_ID; ?>
" style="display:inline;">
											<input type="hidden" name="mpnid" value="<?php echo $this->_tpl_vars['listings_pending'][$this->_tpl_vars['team']['tid']]['mpnid']; ?>
" />
											<input type="hidden" name="remove_pending" value="true" />
											<input type="submit" value="Dequeue" style="width:auto; height:auto;" />
										</form>
									<?php else: ?>
										<a class="plus" onclick="return overlay(this, 'popup_<?php echo $this->_tpl_vars['team']['tid']; ?>
')">[+]</a>
									<?php endif; ?>

									<div id="popup_<?php echo $this->_tpl_vars['team']['tid']; ?>
" class="popup" style="position:absolute; display:none">
										<div class="popup_close"><a class="close" onclick="overlayclose('popup_<?php echo $this->_tpl_vars['team']['tid']; ?>
'); return false">Close</a></div>
										<br />Schedule <?php echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 against:
										<br />
										<form method="post" action="/edit.matches.php?sch_id=<?php echo @SCH_ID; ?>
">
											<input type="hidden" name="team1" value="<?php echo $this->_tpl_vars['team']['tid']; ?>
" />
											<select name="team2">
											<?php if (! $this->_tpl_vars['team']['inactive']): ?>
											<option value="pending">Opponent Pending</option>
											<option value="bye_win">Bye Win</option>
											<option value="bye_win_ff">Bye Win (Forfeit)</option>
											<option value="bye_loss">Bye Loss</option>
											<?php endif; ?>
											<option value="bye_loss_ff">Bye Loss (Forfeit)</option>
											<?php if (! $this->_tpl_vars['team']['inactive']): ?>
											<?php $_from = $this->_tpl_vars['team_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dropdown']):
 if (( $this->_tpl_vars['dropdown']['tid'] != $this->_tpl_vars['team']['tid'] ) && ( $this->_tpl_vars['dropdown']['divid'] == $this->_tpl_vars['divid'] ) && ! $this->_tpl_vars['scheduled_teams_list'][$this->_tpl_vars['dropdown']['tid']] && ! $this->_tpl_vars['dropdown']['inactive']): ?><option value="<?php echo $this->_tpl_vars['dropdown']['tid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['dropdown']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  if ($this->_tpl_vars['dropdown']['inactive']): ?> [Inactive]<?php endif; ?></option><?php endif;  endforeach; endif; unset($_from); ?>
											<?php endif; ?>
											</select>
											<br /><?php echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 is the: <select name="court">
																				<option value="auto">[Auto]</option>
																				<option value="home">Home team</option>
																				<option value="away">Away team</option>
																			</select>
											<br />Notify teams via e-mail: 
											<?php if (! $this->_tpl_vars['team']['inactive']): ?>
												<input type="checkbox" name="notify" checked="checked" />
											<?php else: ?>
												N/A
											<?php endif; ?>
											<br /><input value="Schedule" name="submit" type="submit" />
										</form>
									</div>

									</td>
									</tr>
									<?php endforeach; endif; unset($_from); ?>
									</table>

						</div>
					<?php endforeach; endif; unset($_from); ?>

				</div>
			<?php endforeach; endif; unset($_from); ?>

		</div>

	<?php endforeach; endif; unset($_from); ?>

</div>


<div>
<p>
<br />
</p>
</div>


<div>
<p>
<br />
</p>
</div>


<div>
<p>
<br />
</p>
</div>


<div>
<p>
<br />
</p>
</div>


<div>
<p>
<br />
</p>
</div>