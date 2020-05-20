<?php /* Smarty version 2.6.14, created on 2013-03-21 22:06:32
         compiled from team.info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'team.info.tpl', 10, false),array('modifier', 'truncate', 'team.info.tpl', 18, false),array('modifier', 'default', 'team.info.tpl', 26, false),)), $this); ?>
<div id="team_page">

<?php if (! empty ( $this->_tpl_vars['team_info'] )): ?>
<table style="width: 610px; marign: 0; padding: 0;" border="0">
<tr>
<td align="left">

	<table style="width: 500px">
<!--	<colgroup span="1" align="right"></colgroup>-->
	<tr><td style="width: 100px">Team Name:</td><td><?php echo ((is_array($_tmp=$this->_tpl_vars['team_info']['team_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
	<tr><td style="width: 100px">Team Tag:</td><td><?php echo ((is_array($_tmp=$this->_tpl_vars['team_info']['tag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
    	<tr><td style="width: 100px">Captain:</td><td><?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info'][$this->_tpl_vars['team_info']['captain_uid']]['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 
	<?php if ($this->_tpl_vars['roster_info'][$this->_tpl_vars['team_info']['captain_uid']]['handle']): ?>
	"<?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info'][$this->_tpl_vars['team_info']['captain_uid']]['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"
	<?php endif; ?>
	<?php if ($this->_tpl_vars['roster_info'][$this->_tpl_vars['team_info']['captain_uid']]['hide_lastname']): ?>
		<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['roster_info'][$this->_tpl_vars['team_info']['captain_uid']]['lastname'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 2, ".") : smarty_modifier_truncate($_tmp, 2, ".")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

	<?php else: ?>
		<?php echo ((is_array($_tmp=$this->_tpl_vars['roster_info'][$this->_tpl_vars['team_info']['captain_uid']]['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

	<?php endif; ?>
	</td></tr>
	<tr><td style="width: 100px">Division:</td><td><?php echo ((is_array($_tmp=$this->_tpl_vars['team_info']['division_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
	<tr><td style="width: 100px">Group:</td><td><?php echo ((is_array($_tmp=$this->_tpl_vars['team_info']['group_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
	<tr><td style="width: 100px">Record:</td><td><?php echo ((is_array($_tmp=@$this->_tpl_vars['season_record']['wins'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
-<?php echo ((is_array($_tmp=@$this->_tpl_vars['season_record']['losses'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
-<?php echo ((is_array($_tmp=@$this->_tpl_vars['season_record']['ties'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</td></tr>
	
	</table>
    </td>
<td align="right" valign="top">
    <?php if ($this->_tpl_vars['team_info']['team_avatar_url']): ?><img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['team_info']['team_avatar_url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" width="100px" height="56px"><?php endif; ?>
</td>
</tr>
</table>
<br>

<div>
	<table id="team_roster">
			<tr>
		<th>&nbsp;</th>
		<th>Name</th>
		<th>Handle</th>
		<th><?php echo $this->_tpl_vars['team_info']['gid_name']; ?>
</th>
	</tr>
	<?php $_from = $this->_tpl_vars['roster_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['uid'] => $this->_tpl_vars['player']):
?>
	<tr>
		<td>
		<?php if ($this->_tpl_vars['player']['ccode']): ?>
		<img src="/images/flags/<?php echo $this->_tpl_vars['player']['ccode']; ?>
.png" width="16" height="11" alt="<?php echo $this->_tpl_vars['player']['ccode']; ?>
" title="<?php echo $this->_tpl_vars['player']['country']; ?>
" /> 
		<?php else: ?>
		&nbsp;
		<?php endif; ?>
		</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['player']['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php if ($this->_tpl_vars['player']['hide_lastname']):  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['player']['lastname'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 2, ".") : smarty_modifier_truncate($_tmp, 2, ".")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  else:  echo ((is_array($_tmp=$this->_tpl_vars['player']['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?></td>
		<td><a class="gidlink" href="<?php echo $this->_tpl_vars['lgname']; ?>
/user/<?php echo $this->_tpl_vars['uid']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['player']['handle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
		<td<?php if ($this->_tpl_vars['player']['suspended']): ?> class="suspended"<?php endif; ?>><a class="gidlink" href="<?php echo $this->_tpl_vars['lgname']; ?>
/membersearch/?search=<?php echo ((is_array($_tmp=$this->_tpl_vars['player']['gid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&rosters_gid=on"><?php echo ((is_array($_tmp=$this->_tpl_vars['player']['gid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	</table>
</div>


<div>


<table id="team_schedule">

<caption><?php echo $this->_tpl_vars['season_title']; ?>
</caption>
<tr>
<th>Week</th>
<th>Map</th>
<th>Away Team</th>
<th>Score</th>
<th>Home Team</th>
</tr>
<?php $_from = $this->_tpl_vars['schedule_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['match']):
 if (empty ( $this->_tpl_vars['match']['mid'] ) && in_array ( $this->_tpl_vars['match']['sch_id'] , $this->_tpl_vars['pending_info'] )): ?>
	<tr>
		<td><?php echo $this->_tpl_vars['match']['stg_short_desc']; ?>
</td>
		<td><?php if ($this->_tpl_vars['match']['map_title']): ?><a class="tpglink" href="<?php echo $this->_tpl_vars['lgname']; ?>
/map/<?php echo $this->_tpl_vars['match']['map_title']; ?>
/"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['match']['map_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '<i>TBA</i>') : smarty_modifier_default($_tmp, '<i>TBA</i>')); ?>
</a><?php else:  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['match']['map_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '<i>TBA</i>') : smarty_modifier_default($_tmp, '<i>TBA</i>'));  endif; ?></td>
		<td colspan="3" align="center"><i>Opponent Pending</i></td>
	</tr>
<?php elseif (! empty ( $this->_tpl_vars['match']['mid'] )): ?>
	<tr<?php if (! $this->_tpl_vars['match']['divisional_match']): ?> class="non-divisional"<?php endif; ?>>
		<td><?php echo $this->_tpl_vars['match']['stg_short_desc']; ?>
</td>
		<td><?php if ($this->_tpl_vars['match']['map_title']): ?><a class="tpglink" href="<?php echo $this->_tpl_vars['lgname']; ?>
/map/<?php echo $this->_tpl_vars['match']['map_title']; ?>
/"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['match']['map_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '<i>TBA</i>') : smarty_modifier_default($_tmp, '<i>TBA</i>')); ?>
</a><?php else:  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['match']['map_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '<i>TBA</i>') : smarty_modifier_default($_tmp, '<i>TBA</i>'));  endif; ?></td>
		<td>
		<?php if ($this->_tpl_vars['match']['away_tid'] == 0): ?>
			<i>Bye</i>
		<?php else: ?>
			<?php if ($this->_tpl_vars['match']['away_tid'] != $_GET['tid']): ?><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['match']['away_tid']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['match']['away_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><?php else:  echo ((is_array($_tmp=$this->_tpl_vars['match']['away_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?>
		<?php endif; ?>
		</td>
		<td>
		<?php if ($this->_tpl_vars['match']['forfeit_loss']): ?>
		<a class="tpglink" href="<?php echo $this->_tpl_vars['lgname']; ?>
/match/<?php echo $this->_tpl_vars['match']['mid']; ?>
/">FF Loss</a>
		<?php elseif ($this->_tpl_vars['match']['forfeit_win']): ?>
		<a class="tpglink" href="<?php echo $this->_tpl_vars['lgname']; ?>
/match/<?php echo $this->_tpl_vars['match']['mid']; ?>
/">FF Win</a>
		<?php elseif (( $this->_tpl_vars['match']['win_tid'] == @TID ) && ( $this->_tpl_vars['match']['away_tid'] == 0 || $this->_tpl_vars['match']['home_tid'] == 0 )): ?>
		Win
		<?php else: ?>
        <?php $this->assign('scorefound', ''); ?>
		<?php $_from = $this->_tpl_vars['match_scores']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['matchscoreid'] => $this->_tpl_vars['scores']):
 if ($this->_tpl_vars['matchscoreid'] == $this->_tpl_vars['match']['mid'] && $this->_tpl_vars['scores']['home_score'] != NULL):  $this->assign('scorefound', 'yes');  if ($this->_tpl_vars['scores']['win']): ?>W<?php else: ?>L<?php endif; ?><a class="tpglink" href="<?php echo $this->_tpl_vars['lgname']; ?>
/match/<?php echo $this->_tpl_vars['match']['mid']; ?>
/"><?php echo $this->_tpl_vars['scores']['away_score']; ?>
-<?php echo $this->_tpl_vars['scores']['home_score']; ?>
</a><?php endif;  endforeach; endif; unset($_from); ?>
		<?php if ($this->_tpl_vars['scorefound'] == ''): ?><span style="font-size: smaller">Match# <?php echo $this->_tpl_vars['match']['mid']; ?>
</span><?php endif; ?>
        <?php endif; ?>
		</td>
		<td>
		<?php if ($this->_tpl_vars['match']['home_tid'] == 0): ?>
			<i>Bye</i>
		<?php else: ?>
			<?php if ($this->_tpl_vars['match']['home_tid'] != $_GET['tid']): ?><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['match']['home_tid']; ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['match']['home_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><?php else:  echo ((is_array($_tmp=$this->_tpl_vars['match']['home_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?>
		<?php endif; ?>
		</td>
	</tr>
<?php endif;  endforeach; else: ?>
<tr>
<td colspan="5" align="center">No matches scheduled for this season.</td>
</tr>
<?php endif; unset($_from); ?>
</table>

</div>






<?php else: ?>
<p>Team does not exist or has been removed.</p>
<?php endif; ?>

</div>