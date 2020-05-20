<?php /* Smarty version 2.6.14, created on 2012-04-06 13:36:30
         compiled from resend.email.tpl */ ?>
<div>
<?php if ($this->_tpl_vars['email_already_validated']): ?>
<p>Your e-mail address is already validated.</p>
<?php else: ?>

<form action="/edit.account.php?actedit=resendemail" method="get" id="resend_email" onsubmit="this.submit.disabled = true; return true">

<fieldset>
    <legend>Resend Validation E-mail</legend>
	<input name="actedit" value="resendemail" type="hidden" />
	<input name="send" value="true" type="hidden" />
    <br /><input name="submit" value="Resend" type="submit" />
</fieldset>

</form>
<?php endif; ?>

</div>