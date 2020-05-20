<?php /* Smarty version 2.6.14, created on 2012-02-26 21:35:09
         compiled from inc.league_admins-panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'inc.league_admins-panel.tpl', 5, false),)), $this); ?>
<div class="rubberbox">
<h1 class="rubberhdr"><span>League Admins</span></h1>
	<?php if (count ( $this->_tpl_vars['league_head_admins'] ) == 1): ?>
		<u>Head Admin</u> <br />
		<?php echo ((is_array($_tmp=$this->_tpl_vars['league_head_admins']['0'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		<br />
	<?php elseif (count ( $this->_tpl_vars['league_head_admins'] ) > 1): ?>
		<p><u>Head Admins</u><br />
		<?php $_from = $this->_tpl_vars['league_head_admins']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['head_admins_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['head_admins_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['admin']):
        $this->_foreach['head_admins_loop']['iteration']++;
?>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['admin'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <br />
		<?php endforeach; endif; unset($_from); ?>
		<br />
	<?php endif; ?>

	<?php if (! empty ( $this->_tpl_vars['league_head_admins'] ) && ! empty ( $this->_tpl_vars['league_admins'] )): ?><br /><?php endif; ?>

	<?php $_from = $this->_tpl_vars['league_admins']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['admins_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['admins_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['section'] => $this->_tpl_vars['admin_array']):
        $this->_foreach['admins_loop']['iteration']++;
?>
			<u><?php echo ((is_array($_tmp=$this->_tpl_vars['section'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</u><br />
			<?php $_from = $this->_tpl_vars['admin_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['admins_sub_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['admins_sub_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['admin']):
        $this->_foreach['admins_sub_loop']['iteration']++;
?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['admin'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br />
				<?php if (($this->_foreach['admins_sub_loop']['iteration'] == $this->_foreach['admins_sub_loop']['total']) && ! ($this->_foreach['admins_loop']['iteration'] == $this->_foreach['admins_loop']['total'])): ?><br /><?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>

</div>