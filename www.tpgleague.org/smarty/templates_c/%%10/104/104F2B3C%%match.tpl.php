<?php /* Smarty version 2.6.14, created on 2012-11-18 02:10:35
         compiled from match.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'match.tpl', 105, false),array('modifier', 'nl2br', 'match.tpl', 105, false),)), $this); ?>
<?php if (@lid): ?>

    <table>
    <tr>
        <td><b>Stage:</b></td>
        <td><?php echo $this->_tpl_vars['stg_short_desc']; ?>
 - <a class="tpglink" href="<?php echo $this->_tpl_vars['lgname']; ?>
/map/<?php echo $this->_tpl_vars['map_title']; ?>
/"><?php echo $this->_tpl_vars['map_title']; ?>
</a></td>
    </tr>
    <tr>
        <td><b>Match Date:</b></td>
        <td><?php echo $this->_tpl_vars['match_date']; ?>
</td>
    </tr>
    <tr>
        <td><?php if (( $this->_tpl_vars['reporting_admin_name'] != '' )): ?><b>Last Reported or Modified Date:</b> <?php else: ?><b>Report Date:</b><?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['report_date']; ?>
</td>
    </tr>
    <tr>
        <td><b>Reporting User:</b></td>
        <td><?php if ($this->_tpl_vars['reporting_user']): ?><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/user/<?php echo $this->_tpl_vars['reporting_uid']; ?>
/"><?php echo $this->_tpl_vars['reporting_user']; ?>
</a><?php endif; ?></td>
    </tr>
    <tr>
        <td><b>Reporting Team:</b></td>
        <td><?php echo $this->_tpl_vars['reporting_team']; ?>
</td>
    </tr>
    <?php if (( $this->_tpl_vars['reporting_admin_name'] != '' )): ?>
    <tr>
        <td><b>Reporting or Modifying Admin:</b></td>
        <td><?php echo $this->_tpl_vars['reporting_admin_name']; ?>
</td>
    </tr>
    <?php endif; ?>
    </table>
    
    <br />

    <?php if (( $this->_tpl_vars['forfeit_name'] != '' )): ?>
        <b><?php echo $this->_tpl_vars['forfeit_name']; ?>
 foreited the match.</b>
    <?php endif; ?>
    
    <?php if (( $this->_tpl_vars['side_selector_h1a'] != '' )): ?>
        <table id="match_table" class="spaced_table">
        <tbody>
        <tr>
        <th colspan="3" style="text-align:center;">First Half</th></tr>
        <tr>
        <th>Side</th>
        <th>Team</th>
        <th align="right">Score</th>
        </tr>
        <tr>
        <td id="h1a_side" class="sidecol"><?php if ($this->_tpl_vars['side_selector_h1a'] == 'Allies'): ?><img src="/images/allies.gif" with="15px" height="15px" /><?php elseif ($this->_tpl_vars['side_selector_h1a'] == 'Axis'): ?><img src="/images/axis.gif" with="15px" height="15px" /><?php elseif ($this->_tpl_vars['side_selector_h1a'] == 'Away Side'): ?>Side 1<?php else:  echo $this->_tpl_vars['side_selector_h1a'];  endif; ?></td>
        <td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['away_tid']; ?>
/"><?php echo $this->_tpl_vars['away_team_name']; ?>
</a></td>
        <td align="center" class="scorecol"><?php echo $this->_tpl_vars['h1a_score']; ?>
</td>
        </tr>
        <tr>
        <td id="h1h_side" class="sidecol"><?php if ($this->_tpl_vars['side_selector_h1h'] == 'Allies'): ?><img src="/images/allies.gif" with="15px" height="15px" /><?php elseif ($this->_tpl_vars['side_selector_h1h'] == 'Axis'): ?><img src="/images/axis.gif" with="15px" height="15px" /><?php elseif ($this->_tpl_vars['side_selector_h1h'] == 'Home Side'): ?>Side 2<?php else:  echo $this->_tpl_vars['side_selector_h1h'];  endif; ?></td>
        <td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['home_tid']; ?>
/"><?php echo $this->_tpl_vars['home_team_name']; ?>
</a></td>
        <td align="center" class="scorecol"><?php echo $this->_tpl_vars['h1h_score']; ?>
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
        <td id="h2a_side" class="sidecol"><?php if ($this->_tpl_vars['side_selector_h2a'] == 'Allies'): ?><img src="/images/allies.gif" with="15px" height="15px" /><?php elseif ($this->_tpl_vars['side_selector_h2a'] == 'Axis'): ?><img src="/images/axis.gif" with="15px" height="15px" /><?php elseif ($this->_tpl_vars['side_selector_h2a'] == 'Away Side'): ?>Side 2<?php else:  echo $this->_tpl_vars['side_selector_h2a'];  endif; ?></td>
        <td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['away_tid']; ?>
/"><?php echo $this->_tpl_vars['away_team_name']; ?>
</a></td>
        <td align="center" class="scorecol"><?php echo $this->_tpl_vars['h2a_score']; ?>
</td>
        </tr>
        <tr>
        <td id="h2h_side" class="sidecol"><?php if ($this->_tpl_vars['side_selector_h2h'] == 'Allies'): ?><img src="/images/allies.gif" with="15px" height="15px" /><?php elseif ($this->_tpl_vars['side_selector_h2h'] == 'Axis'): ?><img src="/images/axis.gif" with="15px" height="15px" /><?php elseif ($this->_tpl_vars['side_selector_h2h'] == 'Home Side'): ?>Side 1<?php else:  echo $this->_tpl_vars['side_selector_h2h'];  endif; ?></td>
        <td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['home_tid']; ?>
/"><?php echo $this->_tpl_vars['home_team_name']; ?>
</a></td>
        <td align="center" class="scorecol"><?php echo $this->_tpl_vars['h2h_score']; ?>
</td>
        </tr>
        </tbody>
        
        <tbody>
        <tr>
        <th colspan="3" style="text-align:center;">Final</th></tr>
        <tr>
        <th>Winner</th>
        <th>Team</th>
        <th>Score</th>
        </tr>
        <tr>
        <td id="h2a_side" class="sidecol"><?php if (( $this->_tpl_vars['away_score'] > $this->_tpl_vars['home_score'] )): ?><img src="/images/asterisk_orange.gif" with="15px" height="15px" /><?php endif; ?></td>
        <td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['away_tid']; ?>
/"><?php echo $this->_tpl_vars['away_team_name']; ?>
</a></td>
        <td align="center" class="scorecol"><?php echo $this->_tpl_vars['away_score']; ?>
</td>
        </tr>
        <tr>
        <td id="h2h_side" class="sidecol"><?php if (( $this->_tpl_vars['home_score'] > $this->_tpl_vars['away_score'] )): ?><img src="/images/asterisk_orange.gif" with="15px" height="15px" /><?php endif; ?></td>
        <td><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/team/<?php echo $this->_tpl_vars['home_tid']; ?>
/"><?php echo $this->_tpl_vars['home_team_name']; ?>
</a></td>
        <td align="center" class="scorecol"><?php echo $this->_tpl_vars['home_score']; ?>
</td>
        </tr>
        </tbody>

        </table>
        
        <br />
        
        <b>Reporting user's comments:</b>
        <p><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['match_comments'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>
    <?php endif; ?>

<?php else: ?>

<div>No match by that ID found.</div>


<?php endif; ?>