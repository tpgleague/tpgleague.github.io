<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>TPG League - Processing {$processing_title|escape}</title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="refresh" content="3;URL={$redirect}">
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="start" href="/" />
	<link rel="help" href="/help/" />

{literal}
<style type="text/css">
div.container {
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  position: fixed;
  display: table;
}
p {
  display: table-cell;
  text-align: center;
  vertical-align: middle;
}
img.displayed {
  display: block;
  margin: 1em auto;
}
</style>
{/literal}

</head>

<body>

<div class="container">
    <p>
    {if $processing_title == 'Registration'}
    Thank you for registering, {$firstname|escape}!
    <br>You will now be taken to the <a href="/new-user/">new user page</a>.
    {else}
    Welcome back, {$firstname|escape}!
    <br>You will now be taken back to the <a href="{$redirect}">TPG website</a>.
    {/if}
    </p>
</div>

</body>
</html>
