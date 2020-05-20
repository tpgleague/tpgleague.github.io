<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h3 class="headline headline-top-border healine-height">{$league_title|escape} Rules</h3>
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
{$error}
</div>

{if !$access}
<div class="error">
You are not authorized to make changes to this page.
</div>
{/if}

{if isset($smarty.get.rlid)}
	<p><a href="/edit.rules.php?lid={$smarty.const.LID}">Back to rules tree.</a></p>

	<form method="post" action="{$smarty.server.REQUEST_URI}" onsubmit="return checkform(this);">
		{* <label for="section">&#167;</label> <input type="text" name="section" value="{$rule_edit.section|escape}" /><br /> *}
		<h1>{$rule_edit.section}</h1>
		<label for="title">Title</label> <input type="text" name="title" size="40" value="{$rule_edit.title|escape}" /><br />
		<label for="inactive">Inactive</label> <input type="checkbox" {if $rule_edit.inactive}checked="checked"{/if} name="inactive" /><br />
		<label for="major_edit">Major&nbsp;Edit</label> <input type="checkbox" name="major_edit" /><br />
		<textarea name="body" rows="15" cols="60">{$rule_edit.body|escape}</textarea><br />
		{if $access}<input type="submit" name="submit" value="Submit" />{/if}<br />
	</form>

	<br />

	<div>
		<ul>
			<li>Enter inserts a new paragraph. Shift+Enter inserts a single line break.</li>
			<li>A minor edit (default) is for fixing formatting (font, color, spacing, capitalization), spelling, grammar and--at most--re-wording a rule in order to resolve an ambiguity in the original wording of the rule.  Any other edit must be a <b>major edit</b>.</li>
			<li>When new rules are added or major edits are done to existing rules, players are notified via a "New" icon next to the rules.</li>
	</div>

	<br />
{*
	<div><p>You may change the following properties about a rule without it being marked as "edited" on the frontend (consequently informing the user that the rules have been updated):</p>
	
	<ul>
		<li>All HTML formatting and style</li>
		<li>Any amount of whitespace (linebreaks, spaces, paragraphs), even associate HTML tags</li>
		<li>Capitalization of any letters</li>
		<li>The title may be changed completely</li>
		<li>Moving a rule (from the tree view) does not mark a rule as changed</li>
		<li>Inactivating a rule does not mark the rules as changed</li>
	</ul>

	<p>The following actions WILL mark a rule as being "edited":</p>

	<ul>
		<li>Adding text to a previously blank rule (such as Introduction)</li>
		<li>Re-arranging the order of paragraphs, lines, words, letters</li>
		<li>Fixing spelling (obviously)</li>
		<li>Activating an inactive rule will mark the rules as having changed</li>
	<ul>
	
	</div>
*}

{else}

	{if !$rules}
	{if $access}
	<div class="rule" style="margin-left: 0em;">
		<a class="plus" onclick="tinyMCE.execCommand('mceToggleEditor', false, 'textarea_0'); return overlay(this, 'add_0');">[+]</a>
		Add category
		<div class="popup" id="add_0">
			<a class="plus" onclick="tinyMCE.execCommand('mceToggleEditor', false, 'textarea_0'); overlayclose('add_0'); return false">Close</a><br />
			<form method="post" action="{$smarty.server.REQUEST_URI}" onsubmit="return checkform(this);">
				<input type="hidden" name="parent_rlid" value="0" />
				{* <label for="section">&#167;</label> <input type="text" name="section" /><br /> *}
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
	{/if}

	{else}

	{foreach from=$rules item='rule'}
	{if $inactive_until <= $rule.lft}
		{assign var='inactive_until' value=0}
		{assign var='inactive' value=0}
	{/if}
	{if $rule.inactive && !$inactive_until}
		{assign var='inactive_until' value=$rule.rgt+1}
		{assign var='inactive' value=1}
	{/if}

	<div class="rule{if $inactive} inactive{/if}" style="margin-left: {$rule.depth*5}em;">
		{if $access}<a class="plus" title="Insert" onclick="tinyMCE.execCommand('mceToggleEditor', false, 'textarea_{$rule.rlid}'); return overlay(this, 'add_{$rule.rlid}');">[+]</a>{/if}
		<b>&#167; {$rule.section|escape}</b> 
		<a href="/edit.rules.php?lid={$smarty.const.LID}&amp;rlid={$rule.rlid}" class="rule" title="Edit">{$rule.title|escape}</a>
		{if $access}<a class="plus" title="Move" onclick="return overlay(this, 'move_{$rule.rlid}');">[&#187;]</a>{/if}

		<div class="popup_move" id="move_{$rule.rlid}">
			<a class="plus" onclick="overlayclose('move_{$rule.rlid}'); return false">Close</a><br />
			<form method="post" action="{$smarty.server.REQUEST_URI}" onsubmit="return check_move_form(this);">
				<input type="hidden" name="move_rlid" value="{$rule.rlid}" />
				<input type="radio" class="chkbox" name="placement" value="before" />Move {$rule.title|escape} to before:<br />
				<input type="radio" class="chkbox" name="placement" value="after" />Move {$rule.title|escape} to after:<br />
				<input type="radio" class="chkbox" name="placement" value="sub" />Move {$rule.title|escape} as subsection of:<br />
				<select name="move_destination">
				{foreach from=$rules item='rule_move'}
					<option value="{$rule_move.rlid}"{if $rule_move.rlid == $rule.rlid} selected="selected"{/if}>
					{''|indent:$rule_move.depth:'&nbsp;'}&#167; {$rule_move.section|escape} {$rule_move.title|escape}
					</option>
				{/foreach}
				</select><br />
				<input type="submit" value="Submit" />
			</form>
		</div>

		<div class="popup" id="add_{$rule.rlid}">
			<a class="plus" onclick="tinyMCE.execCommand('mceToggleEditor', false, 'textarea_{$rule.rlid}'); overlayclose('add_{$rule.rlid}'); return false">Close</a><br />
			<form method="post" action="{$smarty.server.REQUEST_URI}" onsubmit="return checkform(this);">
				<input type="hidden" name="parent_rlid" value="{$rule.rlid}" />
				{* <label for="section">&#167;</label> <input type="text" name="section" /><br /> *}
				<label for="title">Title</label> <input type="text" name="title" size="40" /><br />
				<label for="inactive">Inactive</label> <input type="checkbox" name="inactive" /><br />
				<textarea name="body" id="textarea_{$rule.rlid}" rows="15" cols="60"></textarea><br />

				<input type="radio" class="chkbox" name="placement" value="before" />Add before {$rule.title|escape}.<br />
				<input type="radio" class="chkbox" name="placement" value="after" />Add after {$rule.title|escape}.<br />
				<input type="radio" class="chkbox" name="placement" value="sub" />Add as subsection of {$rule.title|escape}.<br />

				<input type="submit" value="Submit" />
			</form>

			<br />

			<div>
				Enter inserts a new paragraph. Shift+Enter inserts a single line break.
			</div>

		</div>
	</div>
	{/foreach}

	{/if}

{/if}
						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->



