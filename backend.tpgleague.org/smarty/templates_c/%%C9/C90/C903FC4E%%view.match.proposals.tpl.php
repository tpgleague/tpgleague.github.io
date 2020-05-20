<?php /* Smarty version 2.6.14, created on 2012-03-04 14:42:01
         compiled from view.match.proposals.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('insert', 'friendly_date', 'view.match.proposals.tpl', 6, false),array('modifier', 'easy_day', 'view.match.proposals.tpl', 7, false),array('modifier', 'easy_time', 'view.match.proposals.tpl', 7, false),array('modifier', 'escape', 'view.match.proposals.tpl', 9, false),)), $this); ?>
<?php if ($this->_tpl_vars['proposal_list']): ?>
<div id="schedule_proposals">
<?php $_from = $this->_tpl_vars['proposal_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['proposal']):
?>
	<div class="prop">
	<span style="font-weight: bold;">Status: <?php echo $this->_tpl_vars['proposal']['status']; ?>
</span><br />
	Posted: <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'friendly_date', 'timestamp' => $this->_tpl_vars['proposal']['create_date_gmt'])), $this); ?>
<br />
	<?php if ($this->_tpl_vars['proposal']['proposed_date_gmt']): ?>Proposed Match Time: <?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['proposed_date_gmt'])) ? $this->_run_mod_handler('easy_day', true, $_tmp) : smarty_modifier_easy_day($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['proposed_date_gmt'])) ? $this->_run_mod_handler('easy_time', true, $_tmp) : smarty_modifier_easy_time($_tmp)); ?>
<br /><?php endif; ?>
	<br />
	Proposing Team: <a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['proposal']['proposed_tid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['proposed_team'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a> (<a href="/edit.user.php?uid=<?php echo $this->_tpl_vars['proposal']['proposed_uid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['proposed_player'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>)<br />
	<?php if ($this->_tpl_vars['proposal']['status'] != 'Message'): ?>Server Choice: 
		<?php if ($this->_tpl_vars['proposal']['home_server_choice'] == 'Home server'): ?>
			<?php if ($this->_tpl_vars['proposal']['proposed_tid'] == $this->_tpl_vars['proposal']['home_tid']): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['proposed_team'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php else: ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['reviewer_team'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php endif; ?>
		<?php elseif ($this->_tpl_vars['proposal']['home_server_choice'] == 'Away server'): ?>
			<?php if ($this->_tpl_vars['proposal']['proposed_tid'] == $this->_tpl_vars['proposal']['away_tid']): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['proposed_team'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php else: ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['reviewer_team'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			<?php endif; ?>
		<?php else: ?>
			No preference
		<?php endif; ?>
	<br />
	<?php endif; ?>
	Comment: <span style="font-style: italic;"><?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['comments'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><br />
	<br />
	<?php if ($this->_tpl_vars['proposal']['status'] != 'Message'):  echo $this->_tpl_vars['proposal']['status']; ?>
 Time: <?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['review_date_gmt'])) ? $this->_run_mod_handler('easy_day', true, $_tmp) : smarty_modifier_easy_day($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['review_date_gmt'])) ? $this->_run_mod_handler('easy_time', true, $_tmp) : smarty_modifier_easy_time($_tmp)); ?>
<br /><?php endif; ?>
	<?php if ($this->_tpl_vars['proposal']['status'] != 'Message' && $this->_tpl_vars['proposal']['status'] != 'Deleted' && $this->_tpl_vars['proposal']['status'] != 'Pending'): ?>
		Reviewing Team: <a href="/edit.team.php?tid=<?php echo $this->_tpl_vars['proposal']['reviewer_tid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['reviewer_team'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a> (<a href="/edit.user.php?uid=<?php echo $this->_tpl_vars['proposal']['reviewer_uid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['reviewer_player'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>)<br />
		Review Comment: <span style="font-style: italic;"><?php echo ((is_array($_tmp=$this->_tpl_vars['proposal']['review_comments'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><br />
	<?php endif; ?>
	</div>
	<br />
	<br />
	<hr />
<?php endforeach; endif; unset($_from); ?>
</div>

<?php endif; ?>
