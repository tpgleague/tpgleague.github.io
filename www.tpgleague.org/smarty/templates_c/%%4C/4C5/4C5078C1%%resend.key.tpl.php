<?php /* Smarty version 2.6.14, created on 2012-10-09 22:39:18
         compiled from emails/resend.key.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'emails/resend.key.tpl', 1, false),)), $this); ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,

You are receiving this message because you requested to have your e-mail address validation key resent. Before you can create or join teams, you must validate your new e-maill address by either visiting the following link:

http://www.tpgleague.org/validate.email.key.php?username=<?php echo $this->_tpl_vars['resend_username']; ?>
&key=<?php echo $this->_tpl_vars['resend_email_validation_key']; ?>


Or logging into your account by visiting http://www.tpgleague.org and clicking "Account Management", then clicking "Enter e-mail validation key" and entering the key you see here:

<?php echo $this->_tpl_vars['resend_email_validation_key']; ?>


http://www.tpgleague.org/