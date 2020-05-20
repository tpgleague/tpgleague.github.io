<?php /* Smarty version 2.6.14, created on 2012-10-21 00:30:16
         compiled from season.schedule.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'custom_date', 'season.schedule.tpl', 18, false),array('modifier', 'escape', 'season.schedule.tpl', 19, false),array('modifier', 'truncate', 'season.schedule.tpl', 19, false),array('modifier', 'default', 'season.schedule.tpl', 19, false),array('modifier', 'easy_day', 'season.schedule.tpl', 62, false),array('modifier', 'easy_time', 'season.schedule.tpl', 62, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['week_schedule'] )): ?>
<div>
<table id="schedule">
<colgroup>
<col />
<col />
<col align="char" char="-" />
<col />
</colgroup>
	<tr>
	<th>Time</th>
	<th>Away Team</th>
	<th>&nbsp;</th>
	<th>Home Team</th>
	</tr>
<?php $_from = $this->_tpl_vars['week_schedule']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['match']):
?>
	<tr>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['match']['unix_start_date_gmt'])) ? $this->_run_mod_handler('custom_date', true, $_tmp, 'M j, g:i') : smarty_modifier_custom_date($_tmp, 'M j, g:i')); ?>
</td>
		<td<?php if ($this->_tpl_vars['match']['win_tid'] == $this->_tpl_vars['match']['away_tid']): ?> class="winner"<?php endif; ?>><?php if ($this->_tpl_vars['match']['away_tid']): ?><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['match']['away_tid']; ?>
/" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['match']['away_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php endif;  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['match']['away_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 30, '') : smarty_modifier_truncate($_tmp, 30, '')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '<i>Bye</i>') : smarty_modifier_default($_tmp, '<i>Bye</i>'));  if ($this->_tpl_vars['match']['away_tid']): ?></a><?php endif; ?></td>
		<td>
		<?php if ($this->_tpl_vars['match']['report_date_gmt'] != '0000-00-00 00:00:00'): ?>
			<?php if ($this->_tpl_vars['match']['forfeit_home'] || $this->_tpl_vars['match']['forfeit_away']): ?>
				<a class="tpglinkalt" href="<?php echo $this->_tpl_vars['lgname']; ?>
/match/<?php echo $this->_tpl_vars['match']['mid']; ?>
/">FF</a>
			<?php else: ?>
				<a class="tpglinkalt" href="<?php echo $this->_tpl_vars['lgname']; ?>
/match/<?php echo $this->_tpl_vars['match']['mid']; ?>
/"><?php echo $this->_tpl_vars['match']['away_score']; ?>
-<?php echo $this->_tpl_vars['match']['home_score']; ?>
</a>
			<?php endif; ?>
		<?php else: ?>
			&nbsp;
		<?php endif; ?>
		</td>
		<td<?php if ($this->_tpl_vars['match']['win_tid'] == $this->_tpl_vars['match']['home_tid']): ?> class="winner"<?php endif; ?>><?php if ($this->_tpl_vars['match']['home_tid']): ?><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['match']['home_tid']; ?>
/" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['match']['home_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php endif;  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['match']['home_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 30, '') : smarty_modifier_truncate($_tmp, 30, '')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '<i>Bye</i>') : smarty_modifier_default($_tmp, '<i>Bye</i>'));  if ($this->_tpl_vars['match']['home_tid']): ?></a><?php endif; ?></td>
	</tr>
<?php endforeach; else: ?>
<tr>
<td colspan="4">No matches scheduled.</td></tr>
<?php endif; unset($_from);  $_from = $this->_tpl_vars['week_schedule_pending']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pending']):
?>
<tr>
	<td>&nbsp;</td>
	<td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['pending']['tid']; ?>
/" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['pending']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['pending']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
	<td>&nbsp;</td>
	<td><i>Opponent Pending</i></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
</div>
<?php else: ?>
<div>
<table id="schedule">
<thead>
<caption><?php echo $this->_tpl_vars['active_season_title']; ?>
 Schedule</caption>
</thead>
	<tr>
		<th>Date</th>
		<th>Map</th>
		<th>Week</th>
	</tr>
<tbody>
<?php $_from = $this->_tpl_vars['season_schedule']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schedule']):
 if ($this->_tpl_vars['schedule']['display_preseason'] || $this->_tpl_vars['schedule']['stg_type'] != 'Preseason'): ?>
	<tr>
		<td style="white-space: nowrap;"><?php echo ((is_array($_tmp=$this->_tpl_vars['schedule']['unix_stg_match_date_gmt'])) ? $this->_run_mod_handler('easy_day', true, $_tmp) : smarty_modifier_easy_day($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['schedule']['unix_stg_match_date_gmt'])) ? $this->_run_mod_handler('easy_time', true, $_tmp) : smarty_modifier_easy_time($_tmp)); ?>
</td>
		<td><?php if ($this->_tpl_vars['schedule']['stg_type'] != 'Holiday'): ?>     <?php if ($this->_tpl_vars['schedule']['map_title']): ?><a class="tpglink" href="<?php echo $this->_tpl_vars['lgname']; ?>
/map/<?php echo $this->_tpl_vars['schedule']['map_title']; ?>
/"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['schedule']['map_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '<i>TBA</i>') : smarty_modifier_default($_tmp, '<i>TBA</i>')); ?>
</a><?php else:  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['schedule']['map_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '<i>TBA</i>') : smarty_modifier_default($_tmp, '<i>TBA</i>'));  endif; ?>        <?php endif; ?></td>
		<?php if ($this->_tpl_vars['schedule']['matches_scheduled']): ?>
		<td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/schedule/<?php echo $this->_tpl_vars['schedule']['sch_id']; ?>
/"><?php echo $this->_tpl_vars['schedule']['stg_short_desc']; ?>
</a></td>
		<?php else: ?>
		<td><?php echo $this->_tpl_vars['schedule']['stg_short_desc']; ?>
</td>
		<?php endif; ?>
	</tr>
<?php endif;  endforeach; else: ?>
<tr><td colspan="3">No active season created.</td></tr>
<?php endif; unset($_from); ?>
</tbody>
</table>
</div>
<?php endif; ?>