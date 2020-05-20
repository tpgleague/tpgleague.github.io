<?php /* Smarty version 2.6.14, created on 2012-11-17 22:53:54
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'index.tpl', 7, false),array('modifier', 'easy_day', 'index.tpl', 36, false),array('modifier', 'easy_time', 'index.tpl', 36, false),array('insert', 'friendly_date', 'index.tpl', 56, false),)), $this); ?>

	<?php if ($this->_tpl_vars['news_data']): ?>

	<?php $_from = $this->_tpl_vars['news_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['news'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['news']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['news']):
        $this->_foreach['news']['iteration']++;
?>
	<div class="news_item">
				<h2 class="news_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['news']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h2>

		<div class="news_body">
				<?php echo $this->_tpl_vars['news']['body']; ?>

		</div>

		<?php if ($this->_tpl_vars['news']['nplid']): ?>
		<div class="news_poll">
			<?php if ($this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['type'] == 'options'): ?>

				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>
" class="form_poll">
					<p class="poll_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</p>
					<input type="hidden" name="poll_number" value="<?php echo $this->_tpl_vars['news']['nplid']; ?>
" />
					<table class="table_poll">
					<?php $_from = $this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['nplchid'] => $this->_tpl_vars['name']):
?>
						<tr>
							<td><input type="radio" name="poll_choice" value="<?php echo $this->_tpl_vars['nplchid']; ?>
" class="radio" /></td>
							<td style="text-align: left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
						</tr>
					<?php endforeach; endif; unset($_from); ?>
					</table>

					<?php if (! empty ( $this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['error'] )): ?>
						<?php echo $this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['error']; ?>

					<?php else: ?>
						<input type="submit" value="Vote" />
					<?php endif; ?>
					<?php if (! $this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['closed']): ?>
						<br />Poll closes <?php echo ((is_array($_tmp=$this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['close_date'])) ? $this->_run_mod_handler('easy_day', true, $_tmp) : smarty_modifier_easy_day($_tmp)); ?>
 at <?php echo ((is_array($_tmp=$this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['close_date'])) ? $this->_run_mod_handler('easy_time', true, $_tmp) : smarty_modifier_easy_time($_tmp)); ?>
.
					<?php endif; ?>
				</form>

			<?php else: ?>

				<p class="poll_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</p>

				<?php if ($this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['hidden'] == 'Closed'): ?>
					<p>The results of this poll will be available when it closes on <?php echo ((is_array($_tmp=$this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['close_date'])) ? $this->_run_mod_handler('easy_day', true, $_tmp) : smarty_modifier_easy_day($_tmp)); ?>
 at <?php echo ((is_array($_tmp=$this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['close_date'])) ? $this->_run_mod_handler('easy_time', true, $_tmp) : smarty_modifier_easy_time($_tmp)); ?>
.</p>
				<?php else: ?>
					<?php echo $this->_tpl_vars['polls'][$this->_tpl_vars['news']['nplid']]['graph']; ?>

				<?php endif; ?>

			<?php endif; ?>

		</div>
		<?php endif; ?>


		<div class="news_timestamp">Posted: <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'friendly_date', 'timestamp' => $this->_tpl_vars['news']['timestamp'])), $this); ?>
 by <?php echo ((is_array($_tmp=$this->_tpl_vars['news']['author'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 - <a class="tpglinkalt" href="<?php echo $this->_tpl_vars['lgname']; ?>
/article/<?php echo $this->_tpl_vars['news']['newsid']; ?>
/">comments(<?php echo $this->_tpl_vars['news']['number_of_comments']; ?>
)</a></div>
	</div>


	<?php if (! ($this->_foreach['news']['iteration'] == $this->_foreach['news']['total'])): ?>
	<hr />
	<?php endif; ?>

	<?php endforeach; endif; unset($_from); ?>

	<?php if ($this->_tpl_vars['news_articles_total'] > $this->_tpl_vars['news_articles_per_page']): ?>
		<div style="width: 100%;">
		<?php if (@PAGE > 1): ?>
			<p style="width: auto; float: left;"><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/news/page/<?php echo @PAGE-1; ?>
/" style="text-decoration: none;">&laquo; Newer Articles</a></p><?php endif; ?>
		<?php if ($this->_tpl_vars['news_articles_max_pages'] > 1 && @PAGE < $this->_tpl_vars['news_articles_max_pages']): ?>
			<p style="width: 50%; float: right; text-align: right;"><a href="<?php echo $this->_tpl_vars['lgname']; ?>
/news/page/<?php echo @PAGE+1; ?>
/" style="text-decoration: none;">Older Articles &raquo;</a></p><?php endif; ?>
		</div>
	<?php endif; ?>

	<?php else: ?>
	<div class="news_item">There are no news articles posted in this league.</div>
	<?php endif; ?>