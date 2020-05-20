<?php /* Smarty version 2.6.14, created on 2013-03-24 15:28:16
         compiled from edit.rules.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'edit.rules.tpl', 5, false),array('modifier', 'indent', 'edit.rules.tpl', 130, false),)), $this); ?>
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h3 class="headline headline-top-border healine-height"><?php echo ((is_array($_tmp=$this->_tpl_vars['league_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 Rules</h3>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">

						<p>


<div class="error">
<?php echo $this->_tpl_vars['error']; ?>

</div>

<?php if (! $this->_tpl_vars['access']): ?>
<div class="error">
You are not authorized to make changes to this page.
</div>
<?php endif; ?>

<?php if (isset ( $_GET['rlid'] )): ?>
	<p><a href="/edit.rules.php?lid=<?php echo @LID; ?>
">Back to rules tree.</a></p>

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>
" onsubmit="return checkform(this);">
				<h1><?php echo $this->_tpl_vars['rule_edit']['section']; ?>
</h1>
		<label for="title">Title</label> <input type="text" name="title" size="40" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['rule_edit']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><br />
		<label for="inactive">Inactive</label> <input type="checkbox" <?php if ($this->_tpl_vars['rule_edit']['inactive']): ?>checked="checked"<?php endif; ?> name="inactive" /><br />
		<label for="major_edit">Major&nbsp;Edit</label> <input type="checkbox" name="major_edit" /><br />
		<textarea name="body" rows="15" cols="60"><?php echo ((is_array($_tmp=$this->_tpl_vars['rule_edit']['body'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea><br />
		<?php if ($this->_tpl_vars['access']): ?><input type="submit" name="submit" value="Submit" /><?php endif; ?><br />
	</form>

	<br />

	<div>
		<ul>
			<li>Enter inserts a new paragraph. Shift+Enter inserts a single line break.</li>
			<li>A minor edit (default) is for fixing formatting (font, color, spacing, capitalization), spelling, grammar and--at most--re-wording a rule in order to resolve an ambiguity in the original wording of the rule.  Any other edit must be a <b>major edit</b>.</li>
			<li>When new rules are added or major edits are done to existing rules, players are notified via a "New" icon next to the rules.</li>
	</div>

	<br />

<?php else: ?>

	<?php if (! $this->_tpl_vars['rules']): ?>
	<?php if ($this->_tpl_vars['access']): ?>
	<div class="rule" style="margin-left: 0em;">
		<a class="plus" onclick="tinyMCE.execCommand('mceToggleEditor', false, 'textarea_0'); return overlay(this, 'add_0');">[+]</a>
		Add category
		<div class="popup" id="add_0">
			<a class="plus" onclick="tinyMCE.execCommand('mceToggleEditor', false, 'textarea_0'); overlayclose('add_0'); return false">Close</a><br />
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>
" onsubmit="return checkform(this);">
				<input type="hidden" name="parent_rlid" value="0" />
								<label for="title">Title</label> <input type="text" name="title" size="40" /><br />
				<label for="inactive">Inactive</label> <input type="checkbox" name="inactive" /><br />
				<textarea name="body" id="textarea_0" rows="15" cols="60"></textarea><br />
				<input type="submit" value="Submit" />
			</form>
			<br />

			<div>
				Enter inserts a new paragraph. Shift+Enter inserts a single line break.
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php else: ?>

	<?php $_from = $this->_tpl_vars['rules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rule']):
?>
	<?php if ($this->_tpl_vars['inactive_until'] <= $this->_tpl_vars['rule']['lft']): ?>
		<?php $this->assign('inactive_until', 0); ?>
		<?php $this->assign('inactive', 0); ?>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['rule']['inactive'] && ! $this->_tpl_vars['inactive_until']): ?>
		<?php $this->assign('inactive_until', $this->_tpl_vars['rule']['rgt']+1); ?>
		<?php $this->assign('inactive', 1); ?>
	<?php endif; ?>

	<div class="rule<?php if ($this->_tpl_vars['inactive']): ?> inactive<?php endif; ?>" style="margin-left: <?php echo $this->_tpl_vars['rule']['depth']*5; ?>
em;">
		<?php if ($this->_tpl_vars['access']): ?><a class="plus" title="Insert" onclick="tinyMCE.execCommand('mceToggleEditor', false, 'textarea_<?php echo $this->_tpl_vars['rule']['rlid']; ?>
'); return overlay(this, 'add_<?php echo $this->_tpl_vars['rule']['rlid']; ?>
');">[+]</a><?php endif; ?>
		<b>&#167; <?php echo ((is_array($_tmp=$this->_tpl_vars['rule']['section'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</b> 
		<a href="/edit.rules.php?lid=<?php echo @LID; ?>
&amp;rlid=<?php echo $this->_tpl_vars['rule']['rlid']; ?>
" class="rule" title="Edit"><?php echo ((is_array($_tmp=$this->_tpl_vars['rule']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
		<?php if ($this->_tpl_vars['access']): ?><a class="plus" title="Move" onclick="return overlay(this, 'move_<?php echo $this->_tpl_vars['rule']['rlid']; ?>
');">[&#187;]</a><?php endif; ?>

		<div class="popup_move" id="move_<?php echo $this->_tpl_vars['rule']['rlid']; ?>
">
			<a class="plus" onclick="overlayclose('move_<?php echo $this->_tpl_vars['rule']['rlid']; ?>
'); return false">Close</a><br />
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>
" onsubmit="return check_move_form(this);">
				<input type="hidden" name="move_rlid" value="<?php echo $this->_tpl_vars['rule']['rlid']; ?>
" />
				<input type="radio" class="chkbox" name="placement" value="before" />Move <?php echo ((is_array($_tmp=$this->_tpl_vars['rule']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 to before:<br />
				<input type="radio" class="chkbox" name="placement" value="after" />Move <?php echo ((is_array($_tmp=$this->_tpl_vars['rule']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 to after:<br />
				<input type="radio" class="chkbox" name="placement" value="sub" />Move <?php echo ((is_array($_tmp=$this->_tpl_vars['rule']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 as subsection of:<br />
				<select name="move_destination">
				<?php $_from = $this->_tpl_vars['rules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rule_move']):
?>
					<option value="<?php echo $this->_tpl_vars['rule_move']['rlid']; ?>
"<?php if ($this->_tpl_vars['rule_move']['rlid'] == $this->_tpl_vars['rule']['rlid']): ?> selected="selected"<?php endif; ?>>
					<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('indent', true, $_tmp, $this->_tpl_vars['rule_move']['depth'], '&nbsp;') : smarty_modifier_indent($_tmp, $this->_tpl_vars['rule_move']['depth'], '&nbsp;')); ?>
&#167; <?php echo ((is_array($_tmp=$this->_tpl_vars['rule_move']['section'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['rule_move']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

					</option>
				<?php endforeach; endif; unset($_from); ?>
				</select><br />
				<input type="submit" value="Submit" />
			</form>
		</div>

		<div class="popup" id="add_<?php echo $this->_tpl_vars['rule']['rlid']; ?>
">
			<a class="plus" onclick="tinyMCE.execCommand('mceToggleEditor', false, 'textarea_<?php echo $this->_tpl_vars['rule']['rlid']; ?>
'); overlayclose('add_<?php echo $this->_tpl_vars['rule']['rlid']; ?>
'); return false">Close</a><br />
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>
" onsubmit="return checkform(this);">
				<input type="hidden" name="parent_rlid" value="<?php echo $this->_tpl_vars['rule']['rlid']; ?>
" />
								<label for="title">Title</label> <input type="text" name="title" size="40" /><br />
				<label for="inactive">Inactive</label> <input type="checkbox" name="inactive" /><br />
				<textarea name="body" id="textarea_<?php echo $this->_tpl_vars['rule']['rlid']; ?>
" rows="15" cols="60"></textarea><br />

				<input type="radio" class="chkbox" name="placement" value="before" />Add before <?php echo ((is_array($_tmp=$this->_tpl_vars['rule']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.<br />
				<input type="radio" class="chkbox" name="placement" value="after" />Add after <?php echo ((is_array($_tmp=$this->_tpl_vars['rule']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.<br />
				<input type="radio" class="chkbox" name="placement" value="sub" />Add as subsection of <?php echo ((is_array($_tmp=$this->_tpl_vars['rule']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.<br />

				<input type="submit" value="Submit" />
			</form>

			<br />

			<div>
				Enter inserts a new paragraph. Shift+Enter inserts a single line break.
			</div>

		</div>
	</div>
	<?php endforeach; endif; unset($_from); ?>

	<?php endif; ?>

<?php endif; ?>
						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->


