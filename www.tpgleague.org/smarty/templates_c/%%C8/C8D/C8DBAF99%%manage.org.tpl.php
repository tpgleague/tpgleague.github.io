<?php /* Smarty version 2.6.14, created on 2012-03-04 14:25:35
         compiled from manage.org.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manage.org.tpl', 5, false),)), $this); ?>
<div>


<?php $_from = $this->_tpl_vars['organizations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['org']):
?>
<br /><?php echo ((is_array($_tmp=$this->_tpl_vars['org']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 (<a href="/org.cp.php?orgid=<?php echo $this->_tpl_vars['org']['orgid']; ?>
">edit</a>)
<?php endforeach; else: ?>
<p>You are not the owner of any organizations.</p>
<?php endif; unset($_from); ?>

</div>