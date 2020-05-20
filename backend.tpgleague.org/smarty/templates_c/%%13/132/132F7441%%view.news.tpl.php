<?php /* Smarty version 2.6.14, created on 2012-11-12 20:52:04
         compiled from view.news.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'converted_timezone', 'view.news.tpl', 18, false),array('modifier', 'escape', 'view.news.tpl', 22, false),)), $this); ?>

<h1><?php echo $this->_tpl_vars['league_title']; ?>
</h1>


<table class="clean">
	<tr>
		<th>&nbsp;</th>
		<th>Post Date</th>
		<th>Title</th>
		<th>Admin</th>
		<th>Deleted</th>
		<th>Poll</th>
        <th>Comments Locked?</th>
	</tr>
<?php $_from = $this->_tpl_vars['news_posts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['news']):
?>
	<tr<?php if ($this->_tpl_vars['news']['deleted']): ?> style="text-decoration: line-through;"<?php endif; ?>>
		<td><a href="/edit.news.php?newsid=<?php echo $this->_tpl_vars['news']['newsid']; ?>
">Edit</a></td>
		<td nowrap="nowrap"><?php echo ((is_array($_tmp=$this->_tpl_vars['news']['create_date_gmt'])) ? $this->_run_mod_handler('converted_timezone', true, $_tmp) : smarty_modifier_converted_timezone($_tmp)); ?>
</td>
		<td><?php echo $this->_tpl_vars['news']['title']; ?>
</td>
		<td><?php echo $this->_tpl_vars['news']['admin_name']; ?>
</td>
		<td><?php if ($this->_tpl_vars['news']['deleted']): ?>Deleted<?php endif; ?></td>
		<td><?php if ($this->_tpl_vars['news']['nplid']): ?><a href="/view.poll.results.php?nplid=<?php echo $this->_tpl_vars['news']['nplid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['news']['poll_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><?php endif; ?></td>
        <td><?php if (( $this->_tpl_vars['news']['comments_locked'] )): ?>Yes<?php else: ?>No<?php endif; ?></td>
	</tr>
<?php endforeach; else: ?>
<tr><td>&nbsp;</td><td colspan="4">No news posts in this league</td></tr>
<?php endif; unset($_from); ?>
</table>