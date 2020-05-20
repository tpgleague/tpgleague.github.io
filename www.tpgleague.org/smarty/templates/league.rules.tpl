{if empty($rules)}
<p>No rules defined for this league.</p>
{else}

{if $logged_in}
	{if empty($rules_user_last_view)}
	<div class="rules_notice">
	<div align="center"><img src="/images/information.gif" alt="Information" title="Information" width="16" height="16" /></div>
	This appears to be your first time viewing the rules for this league.  Please familiarize yourself with all rules thoroughly.</div>
	{elseif $rules_user_last_view <= $smarty.const.LEAGUE_RULE_LAST_UPDATE}
	<div class="rules_notice">
	<div align="center"><img src="/images/information.gif" alt="Information" title="Information" width="16" height="16" /></div>
	Some rules have changed since your last viewing. They will be highlighted below for the next 24 hours. Please make sure to familiarize yourself with these updated rules.</div>
	{/if}
{else}
	<div class="rules_notice">
	<div align="center"><img src="/images/information.gif" alt="Information" title="Information" width="16" height="16" /></div>
	Users who are logged in while viewing the rules for each league will have a special feature applied to their account: The website will track each users' last viewing of the rules.  If any rules have changed within 24 hours of the users' last viewing then they will be highlighted below, allowing you to quickly learn the new rules without having to re-read the entire rules. Simply log in to take advantage of this feature.</div>
{/if}

{foreach from=$rules item='rule'}
{if $rule.depth == 0}<br />{/if}
<div class="rule_toc" style="margin-left: {$rule.depth*10}px">{if $logged_in && !empty($rules_user_last_view) && $rules_user_last_view <= $rule.unix_modify_date_gmt}<span class="updated_star">*</span>{/if}<a href="#{$rule.section}">{$rule.section} {$rule.title|escape}</a></div>
{/foreach}

<br />
<hr />

<div class="rules_body">
{foreach from=$rules item='rule'}
{if $logged_in && !empty($rules_user_last_view) && $rules_user_last_view <= $rule.unix_modify_date_gmt && !empty($rule.body)}
	{assign var='rule_updated' value=1}
{else}
	{assign var='rule_updated' value=0}
{/if}
<div class="rule{if $rule_updated} updated{/if}" style="margin-left: {if $rule.depth > 0}3em{else}0{/if};"><h1 class="rule_header"><a name="{$rule.section}">{if $rule_updated}<img src="/images/asterisk_orange.gif" alt="*" title="Rule Changed" width="16" height="16" /> {/if}&sect;{$rule.section}</a> {$rule.title|escape}</h1>
<div>
{$rule.body}
</div>
</div>
{/foreach}
</div>

{/if}

