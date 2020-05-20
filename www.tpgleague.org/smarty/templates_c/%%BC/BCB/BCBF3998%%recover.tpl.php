<?php /* Smarty version 2.6.14, created on 2012-10-09 19:33:05
         compiled from emails/recover.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'emails/recover.tpl', 1, false),)), $this); ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['recover_firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,

You are receiving this message because someone requested to have your account password reset. If this was not you then it is safe to ignore this message. If you did request to have your password reset then please visit the following link to complete this procedure:

http://www.tpgleague.org/recover/?recover_key=<?php echo $this->_tpl_vars['recover_key']; ?>


This recover key will only be valid for the next 24 hours, after which time it will expire.  If you repeat the password recover procedure then this key will automatically expire.

http://www.tpgleague.org/