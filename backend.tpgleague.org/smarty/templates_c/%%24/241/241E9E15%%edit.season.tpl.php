<?php /* Smarty version 2.6.14, created on 2013-03-24 17:32:21
         compiled from edit.season.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'edit.season.tpl', 46, false),array('function', 'quickform_fieldset', 'edit.season.tpl', 124, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Season Manager</h2>
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
<p>Season/Pre-season snapshots do the following: Saves the division/conference/group assignments for all teams in the league so that we have a historical archive of what the standings for each past season looked like.  The win/loss records for each individual team may still be changed at any time by updating matches in the schedule editor.</p>
<p>For this reason, you do not have to wait until all matches have been reported (say, after pre-season but before regular season has been scheduled) to take the snapshot.  You should take the snapshot immediately before doing final moveups/movedowns after preseason/regular season.</p>
<p>This form doesn't behave like a true form should... The Save button only applies to the two Roster Lock fields.</p>
</div>

<div>
<p style="color: red;"><?php echo $this->_tpl_vars['season_error']; ?>
</p>
<div>

<table class="clean">
<tr style="height: 4em;">
<th>&nbsp;</th>
<th>Number</th>
<th>Title</th>
<th>Create Date</th>
<th>Lock rosters X hours before match start time</th>
<th>Perm-Lock rosters upon conclusion of this week's match</th>
<th>&lt;--</th>
<th>Activate</th>
<th>Display Preseason</th>
<th>Preseason Snapshot</th>
<th>Reg. Season Snapshot</th>
</tr>
<?php $_from = $this->_tpl_vars['seasons_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['season']):
?>
<tr>

<td><a href="/edit.schedule.php?sid=<?php echo $this->_tpl_vars['season']['sid']; ?>
">Edit</a></td>
<td><?php echo $this->_tpl_vars['season']['season_number']; ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['season']['season_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
<td><?php echo $this->_tpl_vars['season']['create_date_gmt']; ?>
</td>

<form action="/edit.season.php?lid=<?php echo @LID; ?>
" method="post" >
<td>
	<input type="text" size="3" maxlength="3" style="width: 30px;" name="roster_lock_hours" value="<?php echo $this->_tpl_vars['season']['roster_lock_hours']; ?>
" /> hours
</td>

<td>
	<select name="roster_lock_playoffs_sch_id">
		<option value="">[None]</option>
		<?php $_from = $this->_tpl_vars['schedule_data'][$this->_tpl_vars['season']['sid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schedule']):
?>
			<option value="<?php echo $this->_tpl_vars['schedule']['sch_id']; ?>
"
			<?php if ($this->_tpl_vars['season']['roster_lock_playoffs_sch_id'] == $this->_tpl_vars['schedule']['sch_id']): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['schedule']['stg_short_desc'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	</select>
</td>

<td>
	<input type="hidden" name="sid" value="<?php echo $this->_tpl_vars['season']['sid']; ?>
" />
	<input type="submit" value="Save" style="width: 50px;" />
</td>
</form>

<td>
	<?php if (! $this->_tpl_vars['season']['active']): ?>
	<form method="post" action="/edit.season.php?lid=<?php echo $_GET['lid']; ?>
">
		<input type="hidden" name="activate_season" value="<?php echo $this->_tpl_vars['season']['sid']; ?>
" />
		<input type="submit" value="Activate" style="width: auto; height: auto; align: center; margin:0;" />
	</form>
	<?php endif; ?>
</td>

<td><?php if ($this->_tpl_vars['season']['active']): ?>
	<form method="post" action="/edit.season.php?lid=<?php echo $_GET['lid']; ?>
">
		<input type="hidden" name="toggle_preseason" value="<?php echo $this->_tpl_vars['season']['sid']; ?>
" />
		<input type="submit" name="toggle_preseason_value" value="<?php if ($this->_tpl_vars['season']['display_preseason']): ?>Turn Off<?php else: ?>Turn On<?php endif; ?>" style="width: auto; height: auto; align: center; margin:0;" />
	</form>
	<?php endif; ?>
</td>

<td><?php if ($this->_tpl_vars['season']['preseason_close_date_gmt']): ?>
		<a href="/historical.standings.php?sid=<?php echo $this->_tpl_vars['season']['sid']; ?>
&amp;preseason=1">Taken</a>
	<?php else: ?>
	<form method="post" action="/edit.season.php?lid=<?php echo $_GET['lid']; ?>
">
		<input type="hidden" name="close_preseason" value="<?php echo $this->_tpl_vars['season']['sid']; ?>
" />
		<input type="submit" value="Take Snapshot" style="width: auto; height: auto; align: center; margin:0;" />
	</form>
	<?php endif; ?>
</td>

<td><?php if ($this->_tpl_vars['season']['season_close_date_gmt']): ?>
		<a href="/historical.standings.php?sid=<?php echo $this->_tpl_vars['season']['sid']; ?>
">Taken</a>
	<?php else: ?>
	<form method="post" action="/edit.season.php?lid=<?php echo $_GET['lid']; ?>
">
		<input type="hidden" name="close_season" value="<?php echo $this->_tpl_vars['season']['sid']; ?>
" />
		<input type="submit" value="Take Snapshot" style="width: auto; height: auto; align: center; margin:0;" />
	</form>
	<?php endif; ?>
</td>

</tr>
<?php endforeach; else: ?>
<tr><td colspan="9">No seasons</td></tr>
<?php endif; unset($_from); ?>
</table>
<br /><br />
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Add Season</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>

<form <?php echo $this->_tpl_vars['add_season_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['add_season_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['add_season_form'],'id' => 'fieldset_add_season','class' => 'qffieldset','fields' => 'season_title, season_number, submit'), $this);?>

</form>

						</p>
					</div>	
				</div>
				
			</div>
</div>
</div>
<!-- End Container -->