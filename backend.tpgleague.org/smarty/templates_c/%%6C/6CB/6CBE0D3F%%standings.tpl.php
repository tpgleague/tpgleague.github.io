<?php /* Smarty version 2.6.14, created on 2012-05-23 18:10:40
         compiled from ../../www.tpgleague.org/smarty/templates/standings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '../../www.tpgleague.org/smarty/templates/standings.tpl', 6, false),array('modifier', 'truncate', '../../www.tpgleague.org/smarty/templates/standings.tpl', 55, false),array('modifier', 'string_format', '../../www.tpgleague.org/smarty/templates/standings.tpl', 66, false),array('modifier', 'substr', '../../www.tpgleague.org/smarty/templates/standings.tpl', 66, false),array('modifier', 'default', '../../www.tpgleague.org/smarty/templates/standings.tpl', 74, false),array('function', 'cycle', '../../www.tpgleague.org/smarty/templates/standings.tpl', 46, false),array('function', 'math', '../../www.tpgleague.org/smarty/templates/standings.tpl', 65, false),)), $this); ?>
<?php if ($this->_tpl_vars['standings_league_title']): ?>
<div id="standings">
<h2 style="text-align: center;"><?php echo $this->_tpl_vars['standings_season_title']; ?>
 Standings</h2>
<?php $_from = $this->_tpl_vars['standings_divisions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['divid'] => $this->_tpl_vars['division']):
?>
<div class="division">
<h2 class="division_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['division']['division_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h2>


		<div class="conference">
		<?php $_from = $this->_tpl_vars['standings_conferences'][$this->_tpl_vars['divid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['conference'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['conference']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['conference']):
        $this->_foreach['conference']['iteration']++;
?>
			<?php if ($this->_foreach['conference']['total'] > 1): ?>
				<h3 class="conference_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['conference']['conference_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h3>
			<?php endif; ?>

			<div class="group">
			<?php $_from = $this->_tpl_vars['standings_groups'][$this->_tpl_vars['conference']['cfid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group']):
?>
				<table summary="<?php echo ((is_array($_tmp=$this->_tpl_vars['group']['group_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 group standings." class="group">
					<caption><?php echo ((is_array($_tmp=$this->_tpl_vars['group']['group_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</caption>
					<colgroup span="1" style="width: 30px;" align="left"></colgroup>
					<colgroup span="1" class="team_name" align="left"></colgroup>
					<colgroup span="1" class="team_tag" align="left"></colgroup>
					<colgroup span="3" class="rem_cols" style="width: 20px;" align="right"></colgroup>
					<colgroup span="1" class="rem_cols" style="width: 40px;" align="right"></colgroup>
					<colgroup span="2" class="rem_cols" style="width: 20px;" align="right"></colgroup>
					<colgroup span="3" class="rem_cols" style="width: 40px;" align="right"></colgroup>
					<thead>
						<tr> 
							<th title="Rank" style="text-align: left;">#</th>
							<th title="Team Name" style="text-align: left;">Team</th>
							<th title="Team Tag" style="text-align: left;">Tag</th>
							<th title="Wins" style="text-align: right;">W</th>
							<th title="Losses" style="text-align: right;">L</th>
							<th title="Ties" style="text-align: right;">T</th>
							<th title="Win Percentage" style="text-align: right;">Pct</th>
							<th title="Forfeit Wins" style="text-align: right;">FFW</th>
							<th title="Forfeit Losses" style="text-align: right;">FFL</th>
							<th title="Points For" style="text-align: right;">PF</th>
							<th title="Points Against" style="text-align: right;">PA</th>
							<th title="Points Difference" style="text-align: right;">PD</th>
						</tr>
					</thead>
					<tbody>
						<?php $this->assign('rankno', 1); ?>
						<?php $this->assign('loopno', 1); ?>
						<?php $_from = $this->_tpl_vars['standings_teams'][$this->_tpl_vars['group']['grpid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['team']):
?>
						<tr class="<?php echo smarty_function_cycle(array('name' => $this->_tpl_vars['group']['grpid'],'values' => "zebra-odd,zebra-even"), $this);?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="location.href='<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['team']['tid']; ?>
/'">
							<?php if (( $this->_tpl_vars['team']['wins'] != $this->_tpl_vars['last_wins'] ) || ( $this->_tpl_vars['team']['losses'] != $this->_tpl_vars['last_losses'] ) || ( $this->_tpl_vars['team']['ties'] != $this->_tpl_vars['last_ties'] )): ?>
								<?php $this->assign('rankno', $this->_tpl_vars['loopno']); ?>
							<?php endif; ?>
							<?php $this->assign('loopno', $this->_tpl_vars['loopno']+1); ?>
							<?php $this->assign('last_wins', $this->_tpl_vars['team']['wins']); ?>
							<?php $this->assign('last_losses', $this->_tpl_vars['team']['losses']); ?>
							<?php $this->assign('last_ties', $this->_tpl_vars['team']['ties']); ?>
							<td><?php if ($this->_tpl_vars['team']['matches_played']):  echo $this->_tpl_vars['rankno'];  else: ?>-<?php endif; ?></td>
							<td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['team']['tid']; ?>
/"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 60, '') : smarty_modifier_truncate($_tmp, 60, '')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
							<td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['team']['tag'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 30, '') : smarty_modifier_truncate($_tmp, 30, '')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
							<td style="text-align: right;"><?php if ($this->_tpl_vars['team']['matches_played']):  echo $this->_tpl_vars['team']['wins'];  else: ?>-<?php endif; ?></td>
							<td style="text-align: right;"><?php if ($this->_tpl_vars['team']['matches_played']):  echo $this->_tpl_vars['team']['losses'];  else: ?>-<?php endif; ?></td>
							<td style="text-align: right;"><?php if ($this->_tpl_vars['team']['matches_played']):  echo $this->_tpl_vars['team']['ties'];  else: ?>-<?php endif; ?></td>
							<td style="text-align: right;">
								<?php if ($this->_tpl_vars['team']['matches_played'] && ( $this->_tpl_vars['team']['wins'] || $this->_tpl_vars['team']['losses'] )): ?>
									<?php if (! $this->_tpl_vars['team']['losses']): ?>
										1.000
									<?php else: ?>
										<?php echo smarty_function_math(array('assign' => 'win_pct','equation' => 'wins/(wins+losses)','wins' => $this->_tpl_vars['team']['wins'],'losses' => $this->_tpl_vars['team']['losses']), $this);?>

										<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['win_pct'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%0.3f') : smarty_modifier_string_format($_tmp, '%0.3f')))) ? $this->_run_mod_handler('substr', true, $_tmp, 1, 4) : substr($_tmp, 1, 4)); ?>

									<?php endif; ?>
								<?php else: ?>
									-
								<?php endif; ?>
							</td>
							<td style="text-align: right;"><?php if ($this->_tpl_vars['team']['forfeit_wins']):  echo $this->_tpl_vars['team']['forfeit_wins'];  else: ?>-<?php endif; ?></td>
							<td style="text-align: right;"><?php if ($this->_tpl_vars['team']['forfeit_losses']):  echo $this->_tpl_vars['team']['forfeit_losses'];  else: ?>-<?php endif; ?></td>
							<td style="text-align: right;"><?php if ($this->_tpl_vars['team']['matches_played']):  echo ((is_array($_tmp=@$this->_tpl_vars['team']['points_for'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0'));  else: ?>-<?php endif; ?></td>
							<td style="text-align: right;"><?php if ($this->_tpl_vars['team']['matches_played']):  echo ((is_array($_tmp=@$this->_tpl_vars['team']['points_against'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0'));  else: ?>-<?php endif; ?></td>
							<td style="text-align: right;"><?php if ($this->_tpl_vars['team']['matches_played']):  echo ((is_array($_tmp=@$this->_tpl_vars['team']['points_difference'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0'));  else: ?>-<?php endif; ?></td>
						</tr>
						<?php endforeach; else: ?>
						<tr> <td colspan="11" class="teams_empty">No teams</td> </tr>
						<?php endif; unset($_from); ?>
					</tbody>
				</table>
			<?php endforeach; else: ?>
			No groups
			<?php endif; unset($_from); ?>
			</div>

		<?php endforeach; else: ?>
		No conferences
		<?php endif; unset($_from); ?>
		</div>

</div>

<?php endforeach; else: ?>
No divisions
<?php endif; unset($_from); ?>


</div>
<?php else: ?>
<div id="standings" style="width: 165px;">
League not found.
</div>
<?php endif; ?>