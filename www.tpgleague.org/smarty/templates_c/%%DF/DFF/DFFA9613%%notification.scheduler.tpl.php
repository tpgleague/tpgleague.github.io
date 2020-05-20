<?php /* Smarty version 2.6.14, created on 2012-05-05 16:07:14
         compiled from D:%5CHosting%5C2443758%5Chtml%5Cdomains%5Ctpgleague%5Ccommon_emails%5Cnotification.scheduler.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'D:\\Hosting\\2443758\\html\\domains\\tpgleague\\common_emails\\notification.scheduler.tpl', 3, false),)), $this); ?>
<?php $this->assign('notification_subject', 'TPG Scheduler Notification'); ?>

<?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,

An update has been posted to your TPG Scheduler panel. You can review this update by visiting the following link:

<?php echo $this->_tpl_vars['notification']['url']; ?>


Or by logging into http://www.tpgleague.org/<?php echo $this->_tpl_vars['notification']['lgname']; ?>
 and clicking "Season Matches" under your Team Panel.

League: <?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['league_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

Your team: <?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['your_team'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

Your Opponent: <?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['opponent_team'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

Week: <?php echo ((is_array($_tmp=$this->_tpl_vars['notification']['week'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>