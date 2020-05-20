                
<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">TPG News</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">
						<h4 class="headline"> Post News</h4>
						<p>
<h1>Post League News</h1>

{if $success}
<div style="color: blue;">News successfully posted.</div>
<br />
{/if}
<div>To ensure consistency between posts/leagues, ALWAYS use the default font and color, except when to emphasize the occassional word or two.</div>
<form {$add_news_form.attributes}>
{$add_news_form.hidden}

{quickform_fieldset form=$add_news_form id='fieldset_news_league' class='qffieldset' fields='title, body, lid' legend='Add News Post'}

<br />

<div>
	Enter inserts a new paragraph. Shift+Enter inserts a single line break.
</div>

<fieldset id="fieldset_news_league_poll" class="qffieldset">
<legend>Add Poll (Optional)</legend>

<p style="color: red;">{$poll_error}</p>

<label for="poll_title">{$add_news_form.poll_title.label}</label>{$add_news_form.poll_title.html}
<br /><label for="poll_hidden">{$add_news_form.poll_hidden.label}</label>{$add_news_form.poll_hidden.html} {$add_news_form.note_poll_hidden.label}
<br /><label for="poll_close_date">{$add_news_form.poll_close_date.label}</label>{$add_news_form.poll_close_date.html} {$add_news_form.note_poll_close_date.label}

{*
<label for="poll_title">Poll Question</label><input type="text" name="poll_title" size="80" maxlength="255" /><br />
<input type="checkbox" name="poll_hidden" />
*}

<br /><label for="poll_eligibility">Poll Eligibility:</label><br />
<input type="radio" name="poll_eligibility" {if $smarty.post.poll_eligibility=='ip'}checked="checked"{/if} value="ip" />Anybody (IP-restricted)&dagger;<br />
<input type="radio" name="poll_eligibility" {if $smarty.post.poll_eligibility=='registered'}checked="checked"{/if} value="registered" />Registered Members (E-mail validated)<br />
<input type="radio" name="poll_eligibility" {if $smarty.post.poll_eligibility=='active_team'}checked="checked"{/if} value="active_team" />Players on an active team*<br />
<input type="radio" name="poll_eligibility" {if $smarty.post.poll_eligibility=='captains'}checked="checked"{/if} value="captains" />Team captains*<br />
&dagger; IPs are not matched to accounts.  This means two league members in the same household sharing the same IP address will only get one vote between them both. Hence, this poll is used for very informal things.<br />
* If news post is global, they get one vote total even if on teams in several leagues.<br />



<div id="myDiv">
<label for="poll_choices">Poll Choices:</label><br />
{foreach from=$smarty.post.poll_choices item='choice' name='poll_loop'}
{if $smarty.foreach.poll_loop.iteration > 2}<a class="plus" onclick="removeElement('myDiv', 'my{$smarty.foreach.poll_loop.iteration}Div';)"> [Remove] </a>{/if}<input type="text" name="poll_choices[]" size="40" id="my{$smarty.foreach.poll_loop.iteration}Div" maxlength="255" value="{$choice|escape}" /><br />
{if $smarty.foreach.poll_loop.last}<input type="hidden" value="{$smarty.foreach.poll_loop.total}" id="currentValue" />{/if}
{foreachelse}
<input type="text" name="poll_choices[]" size="40" id="my1Div" maxlength="255" /><br />
<input type="text" name="poll_choices[]" size="40" id="my2Div" maxlength="255" /><br />
<input type="hidden" value="2" id="currentValue" />
{/foreach}
</div>
<a class="plus" onclick="addElement('myDiv');">[Add Another Choice]</a>

<br />

</fieldset>

<p>{$add_news_form.submit.html}</p>

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



