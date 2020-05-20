<h2>Active Suspensions</h2>

{foreach from=$activeSuspensions item='activeSuspension'}
<div>
Handle: {$activeSuspension.handle|escape} <br />
{if $activeSuspension.team_name}Team: <a href="{$lgname}/team/{$activeSuspension.tid}/">{$activeSuspension.team_name|escape}</a> <br />{/if}
{if $activeSuspension.gid}{$activeSuspension.gid_name|default:'Steam ID'}: {$activeSuspension.gid} <br />{/if}
Start Date: {$activeSuspension.start_date|simple_date} <br />
End Date: {$activeSuspension.end_date|simple_date} <br />
Rule Violation: <a href="{$lgname}/rules/#{$activeSuspension.rule_violation}">{$activeSuspension.rule_violation}</a> <br />
Reason: {$activeSuspension.reason|escape}
</div>
<br />
{foreachelse}
<div>
No active suspensions posted.
</div>
{/foreach}

<h2>Past Suspensions</h2>

{foreach from=$inactiveSuspensions item='inactiveSuspension'}
<div>
Handle: {$inactiveSuspension.handle|escape} <br />
{if $inactiveSuspension.team_name}Team: <a href="{$lgname}/team/{$inactiveSuspension.tid}/">{$inactiveSuspension.team_name|escape}</a> <br />{/if}
{if $inactiveSuspension.gid}{$inactiveSuspension.gid_name|default:'Steam ID'}: {$inactiveSuspension.gid} <br />{/if}
Start Date: {$inactiveSuspension.start_date|simple_date} <br />
End Date: {$inactiveSuspension.end_date|simple_date} <br />
Rule Violation: <a href="{$lgname}/rules/#{$inactiveSuspension.rule_violation}">{$inactiveSuspension.rule_violation}</a> <br />
Reason: {$inactiveSuspension.reason|escape}
</div>
<br />
{foreachelse}
<div>
No past suspensions posted.
</div>
{/foreach}

