<?php /* Smarty version 2.6.14, created on 2013-04-01 19:49:32
         compiled from news.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'quickform_fieldset', 'news.tpl', 50, false),array('modifier', 'escape', 'news.tpl', 85, false),)), $this); ?>
                
<!-- Begin Container -->

			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">View + Edit News</h2>
					<div class="bold-border-bottom"></div>
						<p>
<div>


<form method="get" action="/view.news.php">
<select name="lid">
	<option value="main">Main page only (no leagues)</option>
	<option value="all">Global (main page + all leagues)</option>
<?php $_from = $this->_tpl_vars['leagues_dropdown']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['league']):
?>
	<option value="<?php echo $this->_tpl_vars['league']['lid']; ?>
"><?php echo $this->_tpl_vars['league']['league_title']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
	</select>
<br />
<br />
<br />
<input type="submit" value="View News" style="clear:both;" />
</form>
</div>

<hr  style="clear:both;"  />

<div style="clear:both;">
						</p>
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Post News</h2>
					<div class="bold-border-bottom"></div>
						<p>
<?php if ($this->_tpl_vars['success']): ?>
<div style="color: blue;">News successfully posted.</div>
<br />
<?php endif; ?>
<div>To ensure consistency between posts/leagues, ALWAYS use the default font and color, except when to emphasize the occassional word or two.</div>


<form <?php echo $this->_tpl_vars['add_news_form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['add_news_form']['hidden']; ?>


<?php echo smarty_function_quickform_fieldset(array('form' => $this->_tpl_vars['add_news_form'],'id' => 'fieldset_news_league','class' => 'qffieldset','fields' => 'title, body, lid','legend' => 'Add News Post'), $this);?>




<div>
	Enter inserts a new paragraph. Shift+Enter inserts a single line break.
</div>

<fieldset id="fieldset_news_league_poll" class="qffieldset">
<legend>Add Poll (Optional)</legend>

<p style="color: red;"><?php echo $this->_tpl_vars['poll_error']; ?>
</p>

<label for="poll_title"><?php echo $this->_tpl_vars['add_news_form']['poll_title']['label']; ?>
</label><?php echo $this->_tpl_vars['add_news_form']['poll_title']['html']; ?>

<br /><label for="poll_hidden"><?php echo $this->_tpl_vars['add_news_form']['poll_hidden']['label']; ?>
</label><?php echo $this->_tpl_vars['add_news_form']['poll_hidden']['html']; ?>
 <?php echo $this->_tpl_vars['add_news_form']['note_poll_hidden']['label']; ?>

<br /><label for="poll_close_date"><?php echo $this->_tpl_vars['add_news_form']['poll_close_date']['label']; ?>
</label><?php echo $this->_tpl_vars['add_news_form']['poll_close_date']['html']; ?>
 <?php echo $this->_tpl_vars['add_news_form']['note_poll_close_date']['label']; ?>



<br /><label for="poll_eligibility">Poll Eligibility:</label><br />
<input type="radio" name="poll_eligibility" <?php if ($_POST['poll_eligibility'] == 'ip'): ?>checked="checked"<?php endif; ?> value="ip" />Anybody (IP-restricted)&dagger;<br />
<input type="radio" name="poll_eligibility" <?php if ($_POST['poll_eligibility'] == 'registered'): ?>checked="checked"<?php endif; ?> value="registered" />Registered Members (E-mail validated)<br />
<input type="radio" name="poll_eligibility" <?php if ($_POST['poll_eligibility'] == 'active_team'): ?>checked="checked"<?php endif; ?> value="active_team" />Players on an active team*<br />
<input type="radio" name="poll_eligibility" <?php if ($_POST['poll_eligibility'] == 'captains'): ?>checked="checked"<?php endif; ?> value="captains" />Team captains*<br />
&dagger; IPs are not matched to accounts.  This means two league members in the same household sharing the same IP address will only get one vote between them both. Hence, this poll is used for very informal things.<br />
* If news post is global, they get one vote total even if on teams in several leagues.<br />



<div id="myDiv">
<label for="poll_choices">Poll Choices:</label><br />
<?php $_from = $_POST['poll_choices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['poll_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['poll_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['choice']):
        $this->_foreach['poll_loop']['iteration']++;
?>
<?php if ($this->_foreach['poll_loop']['iteration'] > 2): ?><a class="plus" onclick="removeElement('myDiv', 'my<?php echo $this->_foreach['poll_loop']['iteration']; ?>
Div';)"> [Remove] </a><?php endif; ?><input type="text" name="poll_choices[]" size="40" id="my<?php echo $this->_foreach['poll_loop']['iteration']; ?>
Div" maxlength="255" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['choice'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><br />
<?php if (($this->_foreach['poll_loop']['iteration'] == $this->_foreach['poll_loop']['total'])): ?><input type="hidden" value="<?php echo $this->_foreach['poll_loop']['total']; ?>
" id="currentValue" /><?php endif; ?>
<?php endforeach; else: ?>
<input type="text" name="poll_choices[]" size="40" id="my1Div" maxlength="255" /><br />
<input type="text" name="poll_choices[]" size="40" id="my2Div" maxlength="255" /><br />
<input type="hidden" value="2" id="currentValue" />
<?php endif; unset($_from); ?>
</div>
<a class="plus" onclick="addElement('myDiv');">[Add Another Choice]</a>

<br />

</fieldset>

<p><?php echo $this->_tpl_vars['add_news_form']['submit']['html']; ?>
</p>

</form>

</div>

<p>Before posting, read all up on how to make an <a href="http://en.wikipedia.org/wiki/Opinion_poll">Opinion Poll</a> so you don't do what Damian did and piss everybody off.</p>

<br />
<br />

						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->








