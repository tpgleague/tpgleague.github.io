<?php /* Smarty version 2.6.14, created on 2012-03-04 14:25:18
         compiled from edit.account.tpl */ ?>

<div>

<?php if ($this->_tpl_vars['edit_form_success']): ?>
<p>Modifications to account profile successful.</p>
<?php endif; ?>

<a href="/edit.account.php?actedit=details">Edit account details</a><br />
<a href="/edit.account.php?actedit=siteprefs">Change website display preferences</a><br />
<a href="/edit.account.php?actedit=password">Change password</a><br />
<a href="/edit.account.php?actedit=changeemail">Change e-mail address</a> <?php if (empty ( $this->_tpl_vars['current_active_email'] )): ?>(Not validated)<?php else: ?>(<?php echo $this->_tpl_vars['current_active_email']; ?>
)<?php endif; ?><br />
<?php if ($this->_tpl_vars['email_not_validated']): ?>
<a href="/edit.account.php?actedit=resendemail">Resend e-mail validation key</a><br />
<a href="/edit.account.php?actedit=enteremailkey">Enter e-mail validation key</a><br />
<?php endif; ?>
</div>