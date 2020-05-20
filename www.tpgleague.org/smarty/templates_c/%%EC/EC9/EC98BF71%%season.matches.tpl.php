<?php /* Smarty version 2.6.14, created on 2012-03-11 22:12:36
         compiled from season.matches.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'easy_day', 'season.matches.tpl', 20, false),array('modifier', 'easy_time', 'season.matches.tpl', 21, false),array('modifier', 'escape', 'season.matches.tpl', 22, false),array('modifier', 'default', 'season.matches.tpl', 22, false),)), $this); ?>

<table id="matchlist">
<caption>Matchlist</caption>
<tr>
<th>Date</th>
<th>Time</th>
<th>Map</th>
<th>Opponent</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
</tr>
<?php $_from = $this->_tpl_vars['season_opponents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['match']):
 if ($this->_tpl_vars['match']['away_tid'] == @TID): ?>
	<?php $this->assign('opponent_tid', $this->_tpl_vars['match']['home_tid']);  else: ?>
	<?php $this->assign('opponent_tid', $this->_tpl_vars['match']['away_tid']);  endif; ?>
<tr>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['match']['start_date_gmt'])) ? $this->_run_mod_handler('easy_day', true, $_tmp) : smarty_modifier_easy_day($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['match']['start_date_gmt'])) ? $this->_run_mod_handler('easy_time', true, $_tmp) : smarty_modifier_easy_time($_tmp)); ?>
</td>
<td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['match']['map_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '<i>TBA</i>') : smarty_modifier_default($_tmp, '<i>TBA</i>')); ?>
</td>
<td>
<?php if ($this->_tpl_vars['match']['opponent_name']): ?>
	<?php if ($this->_tpl_vars['match']['away_tid'] == @TID): ?>at <?php endif; ?>
	<a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['opponent_tid']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['match']['opponent_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
<?php else: ?>
	<i>Bye</i>
<?php endif; ?>
</td>
<td><?php if ($this->_tpl_vars['match']['report_date_gmt'] == '0000-00-00 00:00:00'): ?><a href="/schedule.match.php?mid=<?php echo $this->_tpl_vars['match']['mid']; ?>
&amp;tid=<?php echo $_GET['tid']; ?>
">Schedule</a><?php else: ?><span class="greyed-out">Schedule</span><?php endif; ?></td>
<td><?php if ($this->_tpl_vars['match']['report_date_gmt'] == '0000-00-00 00:00:00'): ?><a href="/report.match.php?mid=<?php echo $this->_tpl_vars['match']['mid']; ?>
&amp;tid=<?php echo $_GET['tid']; ?>
">Report</a><?php else: ?><span class="greyed-out">Report</span><?php endif; ?></td>
<td><?php if ($this->_tpl_vars['match']['opponent_name']): ?><a href="http://support.tpgleague.org/ticket/">Dispute</a><?php else: ?><span class="greyed-out">Dispute</span><?php endif; ?></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="7" align="center">You have not been scheduled for any matches this season.</td></tr>
<?php endif; unset($_from); ?>
</table>