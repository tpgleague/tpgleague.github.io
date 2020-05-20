<form method="get" action="/">
<select onchange="javascript:window.location=this.value">
<option value="/">Select League</option>
{foreach from=$leagues_list item=league_list}
{if $LEAGUE_SELECTOR_LID == $league_list.lid}
{assign var='league_list_selected' value='selected="selected"'}
{else}
{assign var='league_list_selected' value=''}
{/if}
<option value="/{$league_list.lgname}/" {$league_list_selected}>{$league_list.league_title}</option>
{/foreach}
</select>
</form>