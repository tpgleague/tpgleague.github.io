<?php /* Smarty version 2.6.14, created on 2012-10-06 15:09:21
         compiled from inc.league.selector.tpl */ ?>
<form method="get" action="/">
<select onchange="javascript:window.location=this.value">
<option value="/">Select League</option>
<?php $_from = $this->_tpl_vars['leagues_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['league_list']):
?>
<?php if ($this->_tpl_vars['LEAGUE_SELECTOR_LID'] == $this->_tpl_vars['league_list']['lid']): ?>
<?php $this->assign('league_list_selected', 'selected="selected"'); ?>
<?php else: ?>
<?php $this->assign('league_list_selected', ''); ?>
<?php endif; ?>
<option value="/<?php echo $this->_tpl_vars['league_list']['lgname']; ?>
/" <?php echo $this->_tpl_vars['league_list_selected']; ?>
><?php echo $this->_tpl_vars['league_list']['league_title']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
</form>