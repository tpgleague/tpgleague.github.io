<?php /* Smarty version 2.6.14, created on 2012-03-13 21:21:00
         compiled from schedule.match.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('insert', 'friendly_date', 'schedule.match.tpl', 11, false),array('modifier', 'trim', 'schedule.match.tpl', 34, false),array('modifier', 'escape', 'schedule.match.tpl', 80, false),array('modifier', 'easy_day', 'schedule.match.tpl', 81, false),array('modifier', 'easy_time', 'schedule.match.tpl', 81, false),array('modifier', 'default', 'schedule.match.tpl', 92, false),array('function', 'quickform_fieldset', 'schedule.match.tpl', 135, false),)), $this); ?>
<?php if ($this->_tpl_vars['proposal_list']):  if ($this->_tpl_vars['pending_exists_error']): ?><p class="error">You must accept, decline, or delete any existing proposals before submitting new comments or proposals.</p><?php endif;  if ($this->_tpl_vars['schedule_match_form']['errors']): ?><p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p><?php endif; ?>
<div id="schedule_proposals">
<?php $_from = $this->_tpl_vars['proposal_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['proposal']):
?>
	<div class="comments_box">
		<div class="comments_text">

			<form method="post" action="/schedule.match.php?mid=<?php echo $_GET['mid']; ?>
&amp;tid=<?php echo $_GET['tid']; ?>
" >
			<input type="hidden" name="mpid" value="<?php echo $this->_tpl_vars['proposal']['mpid']; ?>
" />
			Posted: <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'friendly_date', 'timestamp' => $this->_tpl_vars['proposal']['create_date_gmt'])), $this); ?>
<br />
			Status: <span 
			<?php if (( $this->_tpl_vars['proposal']['status'] == 'Accepted' && ! $this->_tpl_vars['already_accepted'] ) || ( $this->_tpl_vars['proposal']['status'] == 'Declined' && ! $this->_tpl_vars['already_declined'] ) || ( $this->_tpl_vars['proposal']['status'] == 'Pending' && ! $this->_tpl_vars['already_pending'] )): ?>
				class="status_<?php echo $this->_tpl_vars['proposal']['status']; ?>
"
			<?php endif; ?>><?php echo $this->_tpl_vars['proposal']['status']; ?>
</span> 
						<br />
			<?php if ($this->_tpl_vars['proposal']['status'] == 'Accepted'): ?>
				<?php if (! $this->_tpl_vars['already_accepted']): ?>
					<?php if ($this->_tpl_vars['proposal']['home_server_choice'] == 'Home server'): ?>
						<?php ob_start(); ?>
						<table>
							<colgroup span="1" align="right"></colgroup>
							<?php if ($this->_tpl_vars['home_server_info']['server_ip']): ?>
								<?php ob_start(); ?>
									connect <?php echo $this->_tpl_vars['home_server_info']['server_ip'];  if ($this->_tpl_vars['home_server_info']['server_port']): ?>:<?php echo $this->_tpl_vars['home_server_info']['server_port'];  endif;  if ($this->_tpl_vars['home_server_info']['server_pw']): ?>; password <?php echo $this->_tpl_vars['home_server_info']['server_pw'];  endif; ?>
								<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('server_connect_line', ob_get_contents());ob_end_clean(); ?>
								<tr><td>Connect Line:</td><td><?php echo trim($this->_tpl_vars['server_connect_line']); ?>
</td></tr>
								<tr><td>Server IP:</td><td><?php echo $this->_tpl_vars['home_server_info']['server_ip']; ?>
</td></tr>
								<tr><td>Server Port:</td><td><?php echo $this->_tpl_vars['home_server_info']['server_port']; ?>
</td></tr>
								<tr><td>Server Pass:</td><td><?php echo $this->_tpl_vars['home_server_info']['server_pw']; ?>
</td></tr>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['home_server_info']['hltv_ip']): ?>
								<?php ob_start(); ?>
									connect <?php echo $this->_tpl_vars['home_server_info']['hltv_ip'];  if ($this->_tpl_vars['home_server_info']['hltv_port']): ?>:<?php echo $this->_tpl_vars['home_server_info']['hltv_port'];  endif;  if ($this->_tpl_vars['home_server_info']['hltv_pw']): ?>; password <?php echo $this->_tpl_vars['home_server_info']['hltv_pw'];  endif; ?>
								<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('hltv_connect_line', ob_get_contents());ob_end_clean(); ?>
							<tr><td>HLTV Connect Line:</td><td><?php echo trim($this->_tpl_vars['hltv_connect_line']); ?>
</td></tr>
							<tr><td>HLTV IP:</td><td><?php echo $this->_tpl_vars['home_server_info']['hltv_ip']; ?>
</td></tr>
							<tr><td>HLTV Port:</td><td><?php echo $this->_tpl_vars['home_server_info']['hltv_port']; ?>
</td></tr>
							<tr><td>HLTV Pass:</td><td><?php echo $this->_tpl_vars['home_server_info']['hltv_pw']; ?>
</td></tr>
							<?php endif; ?>
						</table>
						<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('server_info_table', ob_get_contents());ob_end_clean(); ?>
					<?php elseif ($this->_tpl_vars['proposal']['home_server_choice'] == 'Away server'): ?>
						<?php ob_start(); ?>
						<table>
							<colgroup span="1" align="right"></colgroup>
							<?php if ($this->_tpl_vars['away_server_info']['server_ip']): ?>
								<?php ob_start(); ?>
									connect <?php echo $this->_tpl_vars['away_server_info']['server_ip'];  if ($this->_tpl_vars['away_server_info']['server_port']): ?>:<?php echo $this->_tpl_vars['away_server_info']['server_port'];  endif;  if ($this->_tpl_vars['away_server_info']['server_pw']): ?>; password <?php echo $this->_tpl_vars['away_server_info']['server_pw'];  endif; ?>
								<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('server_connect_line', ob_get_contents());ob_end_clean(); ?>
								<tr><td>Connect Line:</td><td><?php echo trim($this->_tpl_vars['server_connect_line']); ?>
</td></tr>
								<tr><td>Server IP:</td><td><?php echo $this->_tpl_vars['away_server_info']['server_ip']; ?>
</td></tr>
								<tr><td>Server Port:</td><td><?php echo $this->_tpl_vars['away_server_info']['server_port']; ?>
</td></tr>
								<tr><td>Server Pass:</td><td><?php echo $this->_tpl_vars['away_server_info']['server_pw']; ?>
</td></tr>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['away_server_info']['hltv_ip']): ?>
								<?php ob_start(); ?>
									connect <?php echo $this->_tpl_vars['away_server_info']['hltv_ip'];  if ($this->_tpl_vars['away_server_info']['hltv_port']): ?>:<?php echo $this->_tpl_vars['away_server_info']['hltv_port'];  endif;  if ($this->_tpl_vars['away_server_info']['hltv_pw']): ?>; password <?php echo $this->_tpl_vars['away_server_info']['hltv_pw'];  endif; ?>
								<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('hltv_connect_line', ob_get_contents());ob_end_clean(); ?>
							<tr><td>HLTV Connect Line:</td><td><?php echo trim($this->_tpl_vars['hltv_connect_line']); ?>
</td></tr>
							<tr><td>HLTV IP:</td><td><?php echo $this->_tpl_vars['away_server_info']['hltv_ip']; ?>
</td></tr>
							<tr><td>HLTV Port:</td><td><?php echo $this->_tpl_vars['away_server_info']['hltv_port']; ?>
</td></tr>
							<tr><td>HLTV Pass:</td><td><?php echo $this->_tpl_vars['away_server_info']['hltv_pw']; ?>
</td></tr>
							<?php endif; ?>
						</table>
						<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('server_info_table', ob_get_contents());ob_end_clean(); ?>
					<?php endif; ?>
				<?php endif; ?>
				<?php $this->assign('already_accepted', 'TRUE'); ?>
			<?php elseif ($this->_tpl_vars['proposal']['status'] == 'Declined'):  $this->assign('already_declined', 'TRUE'); ?>
			<?php elseif ($this->_tpl_vars['proposal']['status'] == 'Pending'):  $this->assign('already_pending', 'TRUE'); ?>
			<?php endif; ?>
			Request Team: <?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['proposed_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br />
			<?php if ($this->_tpl_vars['proposal']['proposed_date_gmt']): ?>Proposed Match Time: <?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['proposed_date_gmt'])) ? $this->_run_mod_handler('easy_day', true, $_tmp) : smarty_modifier_easy_day($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['proposed_date_gmt'])) ? $this->_run_mod_handler('easy_time', true, $_tmp) : smarty_modifier_easy_time($_tmp)); ?>
<br /><?php endif; ?>
			<?php if ($this->_tpl_vars['proposal']['comments']): ?>Comment: <span class="comment"><?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['comments'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><br /><?php endif; ?>
						<?php if ($this->_tpl_vars['proposal']['status'] != 'Message' && ( $this->_tpl_vars['proposal']['home_server_choice'] || $this->_tpl_vars['matchData']['home_tid'] == @TID )): ?>Server Choice: 
				<?php if ($this->_tpl_vars['proposal']['status'] == 'Pending' && ( ( ( $this->_tpl_vars['proposal']['home_server_choice'] != 'Home server' && $this->_tpl_vars['proposal']['home_server_choice'] != 'Away server' ) ) && $this->_tpl_vars['proposal']['reviewer_tid'] == @TID )): ?>
				<select name="server_preference">
					<option value="No preference">No preference</option>
					<option value="Home server"><?php echo ((is_array($_tmp=$this->_tpl_vars['matchData']['home_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
's server (<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['home_server_info']['server_location'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 'Location unknown') : smarty_modifier_default($_tmp, 'Location unknown')); ?>
, <?php if ($this->_tpl_vars['home_server_info']['server_available']): ?>available<?php else: ?>unavailable<?php endif; ?>)</option>
					<option value="Away server"><?php echo ((is_array($_tmp=$this->_tpl_vars['matchData']['away_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
's server (<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['away_server_info']['server_location'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 'Location unknown') : smarty_modifier_default($_tmp, 'Location unknown')); ?>
, <?php if ($this->_tpl_vars['away_server_info']['server_available']): ?>available<?php else: ?>unavailable<?php endif; ?>)</option>
				</select>
				<?php else: ?>
					<?php if ($this->_tpl_vars['proposal']['home_server_choice'] == 'Home server'): ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['matchData']['home_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
's server (<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['home_server_info']['server_location'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 'Location unknown') : smarty_modifier_default($_tmp, 'Location unknown')); ?>
, 
						<?php if ($this->_tpl_vars['home_server_info']['server_available']): ?>available<?php else: ?>unavailable<?php endif; ?>)
					<?php elseif ($this->_tpl_vars['proposal']['home_server_choice'] == 'Away server'): ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['matchData']['away_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
's server (<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['away_server_info']['server_location'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 'Location unknown') : smarty_modifier_default($_tmp, 'Location unknown')); ?>
, 
						<?php if ($this->_tpl_vars['away_server_info']['server_available']): ?>available<?php else: ?>unavailable<?php endif; ?>)
					<?php else: ?>No preference
					<?php endif; ?>
				<?php endif; ?>
				<br />
			<?php endif; ?>
			<?php if ($this->_tpl_vars['proposal']['status'] == 'Pending' && $this->_tpl_vars['proposal']['reviewer_tid'] == @TID): ?>
								<br /><input name="submit" class="review_button" type="submit" value="Accept this match time" style="margin-right:2em;" />
					<input name="submit" class="review_button" type="submit" value="Decline this match time" /><br />

			<?php elseif ($this->_tpl_vars['proposal']['status'] == 'Pending' && $this->_tpl_vars['proposal']['proposed_tid'] == @TID): ?>
					<br /><input name="submit" class="review_button" type="submit" value="Delete this proposal" style="margin-right:2em;" /><br />
			<?php endif; ?>
			</form>

			<?php echo $this->_tpl_vars['server_info_table']; ?>

			<?php $this->assign('server_info_table', ''); ?>

		</div>
	</div>
<?php endforeach; endif; unset($_from); ?>
</div>

<hr />
<?php endif; ?>

<?php if ($this->_tpl_vars['pending_proposals_exist']): ?>
	<p class="error">You must accept, decline, or delete any existing proposals (above) before submitting new comments or proposals.</p>
<?php else: ?>

	<form <?php echo $this->_tpl_vars['schedule_match_form']['attributes']; ?>
>
	<?php echo $this->_tpl_vars['schedule_match_form']['hidden']; ?>


	<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['schedule_match_form'],'id' => 'fieldset_schedule_match','class' => 'qffieldset','fields' => 'mid, away_team, home_team, comments_hide, start_hide, date, server_preference, end_hide, comments','legend' => 'Schedule Match','notes_label' => 'Time Zones','notes' => '<p>You should never write times into the comments box because you never know what time zone your opponent is in. By selecting a match time using the boxes at left, the website will automagically convert the time from your local time to your opponent\'s local time.</p><p>You can change your time zone display preferences at any time by visiting your <a href="/edit.account.php?actedit=siteprefs">Account Preferences</a>.</p>'), $this);?>


	<p><?php echo $this->_tpl_vars['schedule_match_form']['submit']['html']; ?>
</p>
	
	<p>Please ensure that your server info is always up to date by visiting your <a href="/edit.team.php?tid=<?php echo @TID; ?>
">Team Properties page</a>. Your opponent for this week will automatically receive your server information in their team panels, so you do not need to re-copy it into the comments box.</p>

	<?php if ($this->_tpl_vars['pending_exists_error']): ?><p class="error">You must accept, decline, or delete any existing proposals (above) before submitting new comments or proposals.</p><?php endif; ?>

	</form>

<?php endif; ?>