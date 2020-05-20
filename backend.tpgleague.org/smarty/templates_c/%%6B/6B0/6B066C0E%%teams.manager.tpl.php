<?php /* Smarty version 2.6.14, created on 2013-03-24 20:03:54
         compiled from teams.manager.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'teams.manager.tpl', 53, false),array('modifier', 'capitalize', 'teams.manager.tpl', 83, false),array('modifier', 'easy_date', 'teams.manager.tpl', 87, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Teams Manager</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">
						<h4 class="headline"> </h4>
						<p>

<?php if (! $this->_tpl_vars['ACCESS']): ?>
<div>You do not have access to use this control.</div>
<?php endif; ?>


<div>
<p>This page is verified to work in the following browsers: <a href="http://www.opera.com">Opera 9</a>, <a href="http://www.mozilla.com/en-US/firefox/">Firefox 2</a>, <a href="http://www.microsoft.com/windows/downloads/ie/getitnow.mspx">Internet Explorer 7</a>.</p>

<br />

<p style="font-size: 150%;">At the end of preseason/regular season, before doing moveups/movedowns/approvals (but after deleting or inactivating teams), make sure you go to the <a href="/edit.season.php?lid=<?php echo @LID; ?>
">season manager</a> and take a snapshot of preseason/regular season. Please talk to Brian if you do not understand the reason.</p>
</div>

<div style="margin: 1em auto; padding: 0.5em; border: 1px dashed orange; ">
[Approved/Unapproved] [Active/Inactive] (PQ) Team name - Team tag (roster size/Lock Status) Create date
<br />
<br /><span style="color: blue;">Blue text</span> means roster size is equal to or greater than the required amount for this league.
<br /><span style="color: red;">Red text</span> means roster size is below the required amount.
<br />Teams in unassigned divisions/conferences/groups are slightly opaque.
<br />If a team is in Pending Queue (PQ) for any match dates, then you will not be able to move the team.
<br />If a team is scheduled (Match) for any unreported matches, then you will not be able to move the team (<b>implemented due to admin retardedness</b>).
</div>


<div><?php if ($_GET['sort'] == 'created'): ?><a href="/teams.manager.php?lid=<?php echo $_GET['lid']; ?>
">Sort alphabetically</a><?php else: ?><a href="/teams.manager.php?lid=<?php echo $_GET['lid']; ?>
&amp;sort=created">Sort by team create date</a><?php endif; ?></div>

<?php echo '<form action="';  echo $_SERVER['REQUEST_URI'];  echo '" method="post" style="margin:0; padding:0; width: 100%;"><div id="mover" style="border: 0px solid red; padding: 0px; width: 700px;">';  $this->assign('greyedout', ' opacity:.50; filter: alpha(opacity=50); -moz-opacity: 0.50;');  echo '';  $_from = $this->_tpl_vars['standings_divisions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['group']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['divid'] => $this->_tpl_vars['division']):
        $this->_foreach['group']['iteration']++;
 echo '<div class="division" style="width: 690px; ';  if ($this->_tpl_vars['division']['inactive']):  echo '';  echo $this->_tpl_vars['greyedout'];  echo '';  endif;  echo '" id="divid_';  echo $this->_tpl_vars['divid'];  echo '"><h3>Division: ';  echo ((is_array($_tmp=$this->_tpl_vars['division']['division_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo ' [';  if ($this->_tpl_vars['division']['inactive']):  echo 'Inactive';  else:  echo 'Active';  endif;  echo ']</h3>';  $_from = $this->_tpl_vars['standings_conferences'][$this->_tpl_vars['divid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['conference'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['conference']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['conference']):
        $this->_foreach['conference']['iteration']++;
 echo '<div class="conference" id="cfid_';  echo $this->_tpl_vars['conference']['cfid'];  echo '" style="margin-top: 2px; margin-bottom: 6px; width: 642px; ';  if ($this->_tpl_vars['conference']['inactive']):  echo '';  echo $this->_tpl_vars['greyedout'];  echo '';  endif;  echo '"><h3>Conference: ';  echo ((is_array($_tmp=$this->_tpl_vars['conference']['conference_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo ' [';  if ($this->_tpl_vars['conference']['inactive']):  echo 'Inactive';  else:  echo 'Active';  endif;  echo ']</h3>';  $_from = $this->_tpl_vars['standings_groups'][$this->_tpl_vars['conference']['cfid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['group']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['group']):
        $this->_foreach['group']['iteration']++;
 echo '<div class="section" id="group_';  echo $this->_tpl_vars['divid'];  echo '-';  echo $this->_tpl_vars['conference']['cfid'];  echo '-';  echo $this->_tpl_vars['group']['grpid'];  echo '" style="background-color: #CCFFFF; border-color: #99FFFF; margin-top: 1px; margin-bottom: 2px; width: 630px; ';  if ($this->_tpl_vars['group']['inactive']):  echo '';  echo $this->_tpl_vars['greyedout'];  echo '';  endif;  echo '"><h3 class="handle" style="background-color: #99FFFF; padding-top: 0px; padding-bottom: 0px;">Group: ';  echo ((is_array($_tmp=$this->_tpl_vars['group']['group_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo ' [';  if ($this->_tpl_vars['group']['inactive']):  echo 'Inactive';  else:  echo 'Active';  endif;  echo ']</h3>';  $_from = $this->_tpl_vars['standings_teams'][$this->_tpl_vars['group']['grpid']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['team'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['team']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['team']):
        $this->_foreach['team']['iteration']++;
 echo '<div id="item_';  echo $this->_tpl_vars['team']['tid'];  echo '" class="';  if ($this->_tpl_vars['team']['pq_sch_id'] || $this->_tpl_vars['team']['match_id']):  echo 'lineitem_no_move';  else:  echo 'lineitem';  endif;  echo '"><span id="div_appr_';  echo $this->_tpl_vars['team']['tid'];  echo '" class="approve" >[';  if ($this->_tpl_vars['team']['approved']):  echo 'A';  else:  echo 'U';  endif;  echo '][';  if ($this->_tpl_vars['team']['inactive']):  echo 'I';  else:  echo 'A';  endif;  echo ']</span><span class="match_pq">';  if ($this->_tpl_vars['team']['pq_sch_id']):  echo '(<a href="/edit.matches.php?sch_id=';  echo $this->_tpl_vars['team']['pq_sch_id'];  echo '">PQ</a>)';  elseif ($this->_tpl_vars['team']['match_id']):  echo '(<a href="/edit.match.php?mid=';  echo $this->_tpl_vars['team']['match_id'];  echo '">Match</a>)';  endif;  echo '</span><span class="name_tag"><a href="/edit.team.php?tid=';  echo $this->_tpl_vars['team']['tid'];  echo '">';  echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo ' - ';  echo ((is_array($_tmp=$this->_tpl_vars['team']['tag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo '</a></span><span class="roster_status ';  if ($this->_tpl_vars['team']['roster_count'] < $this->_tpl_vars['league_format'] && $this->_tpl_vars['league_format'] > 0):  echo 'rosterbelowmin';  endif;  echo ' ';  if ($this->_tpl_vars['team']['roster_lock'] != 'auto'):  echo 'blink';  endif;  echo '">(';  echo $this->_tpl_vars['team']['roster_count'];  echo ' / ';  echo ((is_array($_tmp=$this->_tpl_vars['team']['roster_lock'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp));  echo ')</span><span class="date">';  echo ((is_array($_tmp=$this->_tpl_vars['team']['unix_create_date_gmt'])) ? $this->_run_mod_handler('easy_date', true, $_tmp) : smarty_modifier_easy_date($_tmp));  echo '</span></div>';  endforeach; endif; unset($_from);  echo '</div>';  endforeach; endif; unset($_from);  echo '<div class="section" id="group_';  echo $this->_tpl_vars['divid'];  echo '-';  echo $this->_tpl_vars['conference']['cfid'];  echo '-0" style="background-color: #CCFFFF; border-color: #99FFFF; margin-top: 1px; margin-bottom: 2px; width: 630px; ';  echo $this->_tpl_vars['greyedout'];  echo '"><h3 class="handle" style="background-color: #99FFFF; padding-top: 0px; padding-bottom: 0px;">Group: Unassigned</h3>';  $_from = $this->_tpl_vars['standings_teams'][0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['team'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['team']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['team']):
        $this->_foreach['team']['iteration']++;
 echo '';  if (( ( $this->_tpl_vars['team']['cfid'] == $this->_tpl_vars['conference']['cfid'] ) && ( $this->_tpl_vars['team']['divid'] == $this->_tpl_vars['divid'] ) )):  echo '<div id="item_';  echo $this->_tpl_vars['team']['tid'];  echo '" class="';  if ($this->_tpl_vars['team']['pq_sch_id'] || $this->_tpl_vars['team']['match_id']):  echo 'lineitem_no_move';  else:  echo 'lineitem';  endif;  echo '"><span id="div_appr_';  echo $this->_tpl_vars['team']['tid'];  echo '" class="approve" >[';  if ($this->_tpl_vars['team']['approved']):  echo 'A';  else:  echo 'U';  endif;  echo '][';  if ($this->_tpl_vars['team']['inactive']):  echo 'I';  else:  echo 'A';  endif;  echo ']</span><span class="match_pq">';  if ($this->_tpl_vars['team']['pq_sch_id']):  echo '(<a href="/edit.matches.php?sch_id=';  echo $this->_tpl_vars['team']['pq_sch_id'];  echo '">PQ</a>)';  elseif ($this->_tpl_vars['team']['match_id']):  echo '(<a href="/edit.match.php?mid=';  echo $this->_tpl_vars['team']['match_id'];  echo '">Match</a>)';  endif;  echo '</span><span class="name_tag"><a href="/edit.team.php?tid=';  echo $this->_tpl_vars['team']['tid'];  echo '">';  echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo ' - ';  echo ((is_array($_tmp=$this->_tpl_vars['team']['tag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo '</a></span><span class="roster_status ';  if ($this->_tpl_vars['team']['roster_count'] < $this->_tpl_vars['league_format'] && $this->_tpl_vars['league_format'] > 0):  echo 'rosterbelowmin';  endif;  echo ' ';  if ($this->_tpl_vars['team']['roster_lock'] != 'auto'):  echo 'blink';  endif;  echo '">(';  echo $this->_tpl_vars['team']['roster_count'];  echo ' / ';  echo ((is_array($_tmp=$this->_tpl_vars['team']['roster_lock'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp));  echo ')</span><span class="date">';  echo ((is_array($_tmp=$this->_tpl_vars['team']['unix_create_date_gmt'])) ? $this->_run_mod_handler('easy_date', true, $_tmp) : smarty_modifier_easy_date($_tmp));  echo '</span></div>';  endif;  echo '';  endforeach; endif; unset($_from);  echo '</div></div>';  endforeach; endif; unset($_from);  echo '<div class="section" id="group_';  echo $this->_tpl_vars['divid'];  echo '-0-0" style="margin: 2px 5px 6px; padding: 0px 0px 10px 0px; background-color: #CCF; width: 642px; ';  echo $this->_tpl_vars['greyedout'];  echo '"><h3 class="handle" style="padding: 2px 5px; margin: 0 0 10px 0; display: block; background-color: #99F;">Conference: Unassigned</h3>';  $_from = $this->_tpl_vars['standings_teams'][0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['team'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['team']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['team']):
        $this->_foreach['team']['iteration']++;
 echo '';  if (( ( $this->_tpl_vars['team']['cfid'] == 0 ) && ( $this->_tpl_vars['team']['divid'] == $this->_tpl_vars['divid'] ) )):  echo '<div id="item_';  echo $this->_tpl_vars['team']['tid'];  echo '" class="';  if ($this->_tpl_vars['team']['pq_sch_id'] || $this->_tpl_vars['team']['match_id']):  echo 'lineitem_no_move';  else:  echo 'lineitem';  endif;  echo '"><span id="div_appr_';  echo $this->_tpl_vars['team']['tid'];  echo '" class="approve" >[';  if ($this->_tpl_vars['team']['approved']):  echo 'A';  else:  echo 'U';  endif;  echo '][';  if ($this->_tpl_vars['team']['inactive']):  echo 'I';  else:  echo 'A';  endif;  echo ']</span><span class="match_pq">';  if ($this->_tpl_vars['team']['pq_sch_id']):  echo '(<a href="/edit.matches.php?sch_id=';  echo $this->_tpl_vars['team']['pq_sch_id'];  echo '">PQ</a>)';  elseif ($this->_tpl_vars['team']['match_id']):  echo '(<a href="/edit.match.php?mid=';  echo $this->_tpl_vars['team']['match_id'];  echo '">Match</a>)';  endif;  echo '</span><span class="name_tag"><a href="/edit.team.php?tid=';  echo $this->_tpl_vars['team']['tid'];  echo '">';  echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo ' - ';  echo ((is_array($_tmp=$this->_tpl_vars['team']['tag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo '</a></span><span class="roster_status ';  if ($this->_tpl_vars['team']['roster_count'] < $this->_tpl_vars['league_format'] && $this->_tpl_vars['league_format'] > 0):  echo 'rosterbelowmin';  endif;  echo ' ';  if ($this->_tpl_vars['team']['roster_lock'] != 'auto'):  echo 'blink';  endif;  echo '">(';  echo $this->_tpl_vars['team']['roster_count'];  echo ' / ';  echo ((is_array($_tmp=$this->_tpl_vars['team']['roster_lock'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp));  echo ')</span><span class="date">';  echo ((is_array($_tmp=$this->_tpl_vars['team']['unix_create_date_gmt'])) ? $this->_run_mod_handler('easy_date', true, $_tmp) : smarty_modifier_easy_date($_tmp));  echo '</span></div>';  endif;  echo '';  endforeach; endif; unset($_from);  echo '</div></div>';  endforeach; endif; unset($_from);  echo '<div class="section" id="group_0-0-0" style="border: 1px solid #CCCCCC; margin: 30px 0px; padding: 0px 0px 10px 0px; background-color: #EFEFEF; width: 690px;';  echo $this->_tpl_vars['greyedout'];  echo '"><h3 class="handle" style="font-size: 14px; padding: 2px 5px; margin: 0 0 10px 0; background-color: #CCCCCC; display: block;">Division: Unassigned</h3>';  $_from = $this->_tpl_vars['standings_teams'][0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['team'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['team']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['team']):
        $this->_foreach['team']['iteration']++;
 echo '';  if (( ( $this->_tpl_vars['team']['divid'] == 0 ) )):  echo '<div id="item_';  echo $this->_tpl_vars['team']['tid'];  echo '" class="';  if ($this->_tpl_vars['team']['pq_sch_id'] || $this->_tpl_vars['team']['match_id']):  echo 'lineitem_no_move';  else:  echo 'lineitem';  endif;  echo '"><span id="div_appr_';  echo $this->_tpl_vars['team']['tid'];  echo '" class="approve" >[';  if ($this->_tpl_vars['team']['approved']):  echo 'A';  else:  echo 'U';  endif;  echo '][';  if ($this->_tpl_vars['team']['inactive']):  echo 'I';  else:  echo 'A';  endif;  echo ']</span><span class="match_pq">';  if ($this->_tpl_vars['team']['pq_sch_id']):  echo '(<a href="/edit.matches.php?sch_id=';  echo $this->_tpl_vars['team']['pq_sch_id'];  echo '">PQ</a>)';  elseif ($this->_tpl_vars['team']['match_id']):  echo '(<a href="/edit.match.php?mid=';  echo $this->_tpl_vars['team']['match_id'];  echo '">Match</a>)';  endif;  echo '</span><span class="name_tag"><a href="/edit.team.php?tid=';  echo $this->_tpl_vars['team']['tid'];  echo '">';  echo ((is_array($_tmp=$this->_tpl_vars['team']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo ' - ';  echo ((is_array($_tmp=$this->_tpl_vars['team']['tag'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo '</a></span><span class="roster_status ';  if ($this->_tpl_vars['team']['roster_count'] < $this->_tpl_vars['league_format'] && $this->_tpl_vars['league_format'] > 0):  echo 'rosterbelowmin';  endif;  echo ' ';  if ($this->_tpl_vars['team']['roster_lock'] != 'auto'):  echo 'blink';  endif;  echo '">(';  echo $this->_tpl_vars['team']['roster_count'];  echo ' / ';  echo ((is_array($_tmp=$this->_tpl_vars['team']['roster_lock'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp));  echo ')</span><span class="date">';  echo ((is_array($_tmp=$this->_tpl_vars['team']['unix_create_date_gmt'])) ? $this->_run_mod_handler('easy_date', true, $_tmp) : smarty_modifier_easy_date($_tmp));  echo '</span></div>';  endif;  echo '';  endforeach; endif; unset($_from);  echo '</div></div><input type="hidden" name="order" id="order" value="" />';  if ($this->_tpl_vars['ACCESS']):  echo '<input type="submit" onclick="getGroupOrder()" value="Save Changes" />';  endif;  echo '</form>'; ?>


<p>
<br />
<br />
</p>

<?php echo $this->_tpl_vars['cdata']; ?>

						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->




