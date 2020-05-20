<?php /* Smarty version 2.6.14, created on 2012-02-13 01:42:27
         compiled from inc.standings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'inc.standings.tpl', 6, false),array('modifier', 'truncate', 'inc.standings.tpl', 27, false),array('function', 'cycle', 'inc.standings.tpl', 26, false),)), $this); ?>
<?php if ($this->_tpl_vars['standings_league_title']): ?>
<h1 class="rubberhdr"><span><?php echo $this->_tpl_vars['standings_league_title']; ?>
</span></h1>
<div id="standings">
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
					<colgroup span="1" width="*" align="left"></colgroup>
					<colgroup span="2" width="10" align="center"></colgroup>
					<thead>
						<tr> <th class="team">Team</th> <th>W</th> <th>L</th> </tr>
					</thead>
					<tbody>
						<?php $_from = $this->_tpl_vars['standings_teams'][$this->_tpl_vars['group']['grpid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['team']):
?>
						<tr class="<?php echo smarty_function_cycle(array('name' => $this->_tpl_vars['group']['grpid'],'values' => "zebra-odd,zebra-even"), $this);?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="location.href='<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['team']['tid']; ?>
/'">
							<td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['team']['tid']; ?>
/"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['team']['tag'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, '') : smarty_modifier_truncate($_tmp, 20, '')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
							<td><?php echo $this->_tpl_vars['team']['wins']; ?>
</td>
							<td><?php echo $this->_tpl_vars['team']['losses']; ?>
</td>
						</tr>
						<?php endforeach; else: ?>
						<tr> <td colspan="3" class="teams_empty">No teams</td> </tr>
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
&nbsp;
</div>
<?php endif; ?>