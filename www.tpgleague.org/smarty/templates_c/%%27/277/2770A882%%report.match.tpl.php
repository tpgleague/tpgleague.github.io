<?php /* Smarty version 2.6.14, created on 2012-10-07 21:42:07
         compiled from report.match.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'report.match.tpl', 2, false),)), $this); ?>
<?php if ($this->_tpl_vars['winner_name']): ?>
Your match has been recorded as a win for <?php echo ((is_array($_tmp=$this->_tpl_vars['winner_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.
<?php elseif ($this->_tpl_vars['tie']): ?>
Your match has been recorded as a tie.
<?php elseif ($this->_tpl_vars['ff_message']):  echo $this->_tpl_vars['ff_message']; ?>

<?php else: ?>


<h3 style="text-align: center;font-size: 14px;">Report Forfeit Win/Loss:</h3>

<div style="width: 300px; margin-left: auto; margin-right: auto; text-align: center;">

	<?php if ($this->_tpl_vars['away_can_ff']): ?>
	<?php if ($this->_tpl_vars['away_tid'] == @TID): ?>
		<?php $this->assign('report_msg_status', 'LOSE'); ?>
	<?php else: ?>
		<?php $this->assign('report_msg_status', 'WIN'); ?>
	<?php endif; ?>
	<form action="/report.match.php?mid=<?php echo $_GET['mid']; ?>
&amp;tid=<?php echo $_GET['tid']; ?>
" method="post" id="report_match_forfeit_away" onsubmit="return confirm('You <?php echo $this->_tpl_vars['report_msg_status']; ?>
 this match. Is this Correct?');" >
	<input type="hidden" name="forfeit" value="<?php echo $this->_tpl_vars['away_tid']; ?>
" />
	<input type="submit" style="border-width: 2px; border-style: outset;" name="submit" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['away_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 forfeits" />
	</form>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['away_can_ff'] && $this->_tpl_vars['home_can_ff']): ?>
	<p>- OR -</p>
	<?php else: ?>
	<p>You may not give your opponent a forfeit loss unless they have not responded to your <a href="/schedule.match.php?mid=<?php echo @MID; ?>
&amp;tid=<?php echo @TID; ?>
">TPG Scheduler</a> attempts and it is within 24 hours of the default match time.</p>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['home_can_ff']): ?>
	<?php if ($this->_tpl_vars['home_tid'] == @TID): ?>
		<?php $this->assign('report_msg_status', 'LOSE'); ?>
	<?php else: ?>
		<?php $this->assign('report_msg_status', 'WIN'); ?>
	<?php endif; ?>
	<form action="/report.match.php?mid=<?php echo $_GET['mid']; ?>
&amp;tid=<?php echo $_GET['tid']; ?>
" method="post" id="report_match_forfeit_home" onsubmit="return confirm('You <?php echo $this->_tpl_vars['report_msg_status']; ?>
 this match. Is this Correct?');" >
	<input type="hidden" name="forfeit" value="<?php echo $this->_tpl_vars['home_tid']; ?>
" />
	<input type="submit" style="border-width: 2px; border-style: outset;" name="submit" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['home_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 forfeits" />
	</form>
	<?php endif; ?>

</div>


<h3 style="text-align: center;font-size: 14px;">Report Played Match:</h3>

<?php if ($this->_tpl_vars['match_start_time'] > time()): ?>
<div>
<p>You may not report scores for this match as it is not scheduled to have been played yet.</p>
</div>
<?php else: ?>
<div>

	<?php if ($this->_tpl_vars['error_message']): ?>
	<div style="width: auto; margin: 5px auto; padding: 7px; border: 1px solid red;">
	<?php $_from = $this->_tpl_vars['error_message']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['errormsg']):
?>
	<br /><?php echo $this->_tpl_vars['errormsg']; ?>

	<?php endforeach; endif; unset($_from); ?>
	</div>
	<?php endif; ?>

	<form action="/report.match.php?mid=<?php echo $_GET['mid']; ?>
&amp;tid=<?php echo $_GET['tid']; ?>
" method="post" id="report_match_form">


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
	<td id="h1a_side" class="sidecol"><select name="side_selector_h1a" onchange="changeSides('h1a');">
	<option value="">Select Side</option>
	<?php $_from = $this->_tpl_vars['sides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['side']):
?>
	<option <?php if ($_POST['side_selector_h1a'] == $this->_tpl_vars['side']['lsid']): ?>selected<?php endif; ?> value="<?php echo $this->_tpl_vars['side']['lsid']; ?>
"><?php echo $this->_tpl_vars['side']['side']; ?>
</option>
	<?php endforeach; endif; unset($_from); ?></select></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['away_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	<td align="center" class="scorecol"><input type="text" size="3" value="<?php echo $_POST['h1a_score']; ?>
" maxlength="5" name="h1a_score" id="h1a_score" /></td>
	</tr>
	<tr>
	<td id="h1h_side" class="sidecol"><select name="side_selector_h1h" onchange="changeSides('h1h');">
	<option value="">Select Side</option>
	<?php $_from = $this->_tpl_vars['sides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['side']):
?>
	<option <?php if ($_POST['side_selector_h1h'] == $this->_tpl_vars['side']['lsid']): ?>selected<?php endif; ?> value="<?php echo $this->_tpl_vars['side']['lsid']; ?>
"><?php echo $this->_tpl_vars['side']['side']; ?>
</option>
	<?php endforeach; endif; unset($_from); ?></select></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['home_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	<td align="center" class="scorecol"><input type="text" size="3" maxlength="5" name="h1h_score" value="<?php echo $_POST['h1h_score']; ?>
" id="h1h_score" /></td>
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
	<option value="">Select Side</option>
	<?php $_from = $this->_tpl_vars['sides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['side']):
?>
	<option <?php if ($_POST['side_selector_h2a'] == $this->_tpl_vars['side']['lsid']): ?>selected<?php endif; ?> value="<?php echo $this->_tpl_vars['side']['lsid']; ?>
"><?php echo $this->_tpl_vars['side']['side']; ?>
</option>
	<?php endforeach; endif; unset($_from); ?></select></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['away_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	<td align="center" class="scorecol"><input type="text" size="3" maxlength="5" value="<?php echo $_POST['h2a_score']; ?>
" name="h2a_score" id="h2a_score" /></td>
	</tr>
	<tr>
	<td id="h2h_side" class="sidecol"><select name="side_selector_h2h" onchange="changeSides('h2h');">
	<option value="">Select Side</option>
	<?php $_from = $this->_tpl_vars['sides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['side']):
?>
	<option <?php if ($_POST['side_selector_h2h'] == $this->_tpl_vars['side']['lsid']): ?>selected<?php endif; ?> value="<?php echo $this->_tpl_vars['side']['lsid']; ?>
"><?php echo $this->_tpl_vars['side']['side']; ?>
</option>
	<?php endforeach; endif; unset($_from); ?></select></td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['home_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	<td align="center" class="scorecol"><input type="text" size="3" maxlength="5" value="<?php echo $_POST['h2h_score']; ?>
" name="h2h_score" id="h2h_score" /></td>
	</tr>
	</tbody>

	<tbody>
	<tr>
	<td class="sidecol">Comment:</td>
	<td colspan="2"><input type="text" style="width: 99%;" maxlength="250" value="<?php echo ((is_array($_tmp=$_POST['comments'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="comments" id="comments" /></td>
	</tr>
	<tr>
	<td colspan="3" align="center"><input type="submit" style="border-width: 2px; border-style: outset;" name="submit" id="submit" value="Report Score" /></td>
	</tr>
	</tbody>
	</table>



	</form>
</div>
<?php endif; ?>

<?php endif; ?>


<div>
<p>For questions or concerns with reporting your match, please file a <a href="http://support.tpgleague.org/">support ticket</a>.</p>
</div>