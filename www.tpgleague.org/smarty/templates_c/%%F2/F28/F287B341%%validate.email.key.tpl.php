<?php /* Smarty version 2.6.14, created on 2012-04-29 11:56:39
         compiled from validate.email.key.tpl */ ?>
<div>
<?php if ($this->_tpl_vars['validated_message']): ?>
You have successfully validated your e-mail address.  You may now <a href="/join.team.php">join</a> and <a href="/create.org.php">create teams</a>.
<?php else: ?>
You have supplied an incorrect e-mail validation key.  Please check the message that was sent to your e-mail address to obtain the correct key and enter it into your <a href="/edit.account.php?actedit=enteremailkey">Account Management</a> control panel.
<?php endif; ?>
</div>