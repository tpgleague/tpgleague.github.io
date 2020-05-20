<?php /* Smarty version 2.6.14, created on 2012-05-24 00:05:26
         compiled from view.poll.results.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'view.poll.results.tpl', 4, false),array('modifier', 'converted_timezone', 'view.poll.results.tpl', 4, false),array('modifier', 'round', 'view.poll.results.tpl', 16, false),)), $this); ?>
<?php if ($this->_tpl_vars['poll_info']): ?>

<table class="clean">
<caption><?php echo ((is_array($_tmp=$this->_tpl_vars['poll_info']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 (Closes: <?php echo ((is_array($_tmp=$this->_tpl_vars['poll_info']['expire_date_gmt_unix'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
)</caption>
<thead>
	<tr>
		<th>Choice</th>
		<th>Votes</th>
		<th>%</th>
	</tr>
</thead>
<?php $_from = $this->_tpl_vars['poll_results']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['result']):
?>
	<tr>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td><?php echo $this->_tpl_vars['result']['votes']; ?>
</td>
		<td><?php if ($this->_tpl_vars['poll_votes']):  echo ((is_array($_tmp=$this->_tpl_vars['result']['votes']/$this->_tpl_vars['poll_votes']*100)) ? $this->_run_mod_handler('round', true, $_tmp) : round($_tmp)); ?>
%<?php endif; ?></td>
	</tr>
<?php endforeach; endif; unset($_from); ?>
	<tr>
		<td align="right">Total:</td>
		<td><?php echo $this->_tpl_vars['poll_votes']; ?>
</td>
		<td>&nbsp;</td>
	</tr>
</table>

<?php else: ?>
	<p>No poll with that ID found.</p>
<?php endif; ?>