<?php /* Smarty version 2.6.14, created on 2012-11-12 20:55:20
         compiled from edit.news.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'edit.news.tpl', 10, false),)), $this); ?>
<?php if ($this->_tpl_vars['success']): ?>
<div style="color: blue;">>News successfully edited.</div>
<?php endif; ?>

<div>To ensure consistency between posts/leagues, ALWAYS use the default font and color, except when to emphasize the occassional word or two.</div>
<form <?php echo $this->_tpl_vars['edit_news_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['edit_news_form']['hidden']; ?>



<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['edit_news_form'],'id' => 'fieldset_news_league','class' => 'qffieldset','fields' => 'title, body, admin_name, lid, deleted, comments_locked, submit','legend' => 'Edit News Post'), $this);?>

</form>

<br />

<div>
	Enter inserts a new paragraph. Shift+Enter inserts a single line break.
</div>