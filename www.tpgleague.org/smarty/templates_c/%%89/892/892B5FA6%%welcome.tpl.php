<?php /* Smarty version 2.6.14, created on 2012-10-09 21:59:03
         compiled from emails/welcome.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'emails/welcome.tpl', 1, false),)), $this); ?>
Thank you for joining TPG League, <?php echo ((is_array($_tmp=$this->_tpl_vars['registered_firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
!

Your username is <?php echo $this->_tpl_vars['registered_username']; ?>
.  Before you can create or join teams, you must validate this e-mail address by either visiting the following link:

http://www.tpgleague.org/validate.email.key.php?username=<?php echo $this->_tpl_vars['registered_username']; ?>
&key=<?php echo $this->_tpl_vars['registered_email_validation_key']; ?>


Or logging into your account by visiting http://www.tpgleague.org and clicking "Account Management", then clicking "Enter e-mail validation key" and entering the key you see here:

<?php echo $this->_tpl_vars['registered_email_validation_key']; ?>


Again, thank you for joining TPG.  We hope you enjoy your experience with us.

http://www.tpgleague.org/