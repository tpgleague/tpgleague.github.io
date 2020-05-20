<?php /* Smarty version 2.6.14, created on 2012-04-11 17:53:07
         compiled from recover.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'recover.tpl', 14, false),array('modifier', 'trim', 'recover.tpl', 33, false),)), $this); ?>


<?php if (! empty ( $_GET['recover_key'] )): ?>

	<?php if ($this->_tpl_vars['edit_password_form']): ?>
		You may use this form to reset your account password.
		<form <?php echo $this->_tpl_vars['edit_password_form']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['edit_password_form']['hidden']; ?>


		<?php if ($this->_tpl_vars['edit_password_form']['errors']): ?>
		<p class="error">We encountered errors in the information you submitted.  Please check the fields marked below and try again.</p>
		<?php endif; ?>

		<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_password_form'],'id' => 'fieldset_change_password','class' => 'qffieldset','fields' => 'password, password2'), $this);?>

		<p><?php echo $this->_tpl_vars['edit_password_form']['submit']['html']; ?>
</p>
		</form>

	<?php else: ?>
		<p>You have provided an incorrect key. If you requested your recover key more than 24 hours ago then you must <a href="/recover/">re-request your key</a>.</p>
	<?php endif; ?>

<?php else: ?>

	<?php if ($this->_tpl_vars['recover_key_sent']): ?>
		<p>We have found a matching account and dispatched an e-mail that contains your password recover key. The key provided will expire automatically after 24 hours.</p>
	<?php else: ?>

<form action="/recover/" method="post" id="recover_form" class="recover_form" onsubmit="this.submit.disabled = true; return true">
	<?php if ($this->_tpl_vars['invalid_recover_input']): ?>
	<span style="color:red;">We could not find a matching username or e-mail address.</span><br />
	<?php endif; ?>
	Enter either your username or email address.  If a matching record is found in our database then you will be e-mailed a password reset key:
	<br /><input size="40" name="recover_input" value="<?php if ($_POST['recover_input']):  echo trim($_POST['recover_input']);  endif; ?>" id="recover_input" type="text" />
	<br /><input name="submit" value="Submit" type="submit" />
</form>


	<?php endif; ?>

<?php endif; ?>