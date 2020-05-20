<?php /* Smarty version 2.6.14, created on 2012-05-06 18:14:43
         compiled from D:%5CHosting%5C2443758%5Chtml%5Cdomains%5Ctpgleague%5Ccommon_emails%5Cforfeit.loss.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'D:\\Hosting\\2443758\\html\\domains\\tpgleague\\common_emails\\forfeit.loss.tpl', 3, false),)), $this); ?>
<?php $this->assign('notification_subject', 'TPG Match Forfeited'); ?>

<?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,

This notification is to inform you that your team has received a forfeit loss against <?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['opponent_team'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 for week <?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['week'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
. For this reason, your team has automatically been placed as inactive and will no longer be scheduled for matches.

If you believe this result was submitted in error, or if the loss was correct but you intend to keep participating in TPG League, then you must file a support ticket and request to be re-activated. You should do this as soon as possible so that you don't miss out on being scheduled for the upcoming week's matches. 

Support Tickets: http://support.tpgleague.org/ticket/

League: <?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['league_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

Your Team: <?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['your_team'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

Your Opponent: <?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['opponent_team'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

Week: <?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['week'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>