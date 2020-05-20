<?php /* Smarty version 2.6.14, created on 2012-02-13 01:42:27
         compiled from login.panel.tpl */ ?>
<div class="rubberbox">
<h1 class="rubberhdr"><span>Login</span></h1>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>
" method="post" id="login_form" class="login_form" onsubmit="this.submit.disabled = true; return true">
	<?php if ($this->_tpl_vars['invalid_login']): ?>
	<span style="color:red;">Invalid login</span><br />
	<?php endif; ?>
	<label for="login_username">Username:</label><br /><input size="16" maxlength="32" name="login_username" value="<?php if ($_POST['login_username']):  echo $_POST['login_username'];  endif; ?>" id="login_username" type="text" />
	<br /><label for="login_password">Password:</label><br /><input name="login_password" size="16" value="" id="login_password" type="password" />
	<br /><input name="submit" value="Login" type="submit" />
	<br /><a href="/recover/">Lost Password</a>
	<br /><a href="/register/">Register</a>
</form>
</div>