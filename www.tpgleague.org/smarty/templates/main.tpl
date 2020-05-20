<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?xml version="1.0" encoding="UTF-8"?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>TPG League{if $title} - {$title|escape}{/if}</title>
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="start" href="/" />
	<link rel="help" href="/help/" />
{if $link_rel_previous}	<link rel="previous" href="{$link_rel_previous}" />{/if}
{if $link_rel_previous}	<link rel="next" href="{$link_rel_next}" />{/if}
	<link rel="stylesheet" type="text/css" href="/styles/layout.css" />
	<link rel="stylesheet" type="text/css" href="/styles/style.css" />
	<link rel="stylesheet" type="text/css" href="/styles/boxes.css" />
    <style type="text/css" title="currentStyle">
        @import "/styles/demo_table.css";
    </style>
{if $external_css}
{foreach from=$external_css item=ext_css}
	<link rel="stylesheet" type="text/css" href="/styles/{$ext_css}.css" />
{/foreach}
{/if}

{if $external_js}
{foreach from=$external_js item=ext_js}
	<script src="/js/{$ext_js}.js" type="text/javascript"></script>
{/foreach}
{/if}

<!--[if IE]>
<style type="text/css">
div#league_selector {ldelim}
  float: right;
{rdelim}
</style>
<![endif]-->

	<script src="/js/submit.once.js" type="text/javascript"></script>

{if $extra_head}
{foreach from=$extra_head item=eh}
	{$eh}
{/foreach}
{/if}

</head>

<body {if $onload}onload="{$onload}"{/if}>

<div id="wrapper">
	<div id="header-dod6">
		<div id="top-nav">
			{if $lgname == '/dod3'}<div id="tpg_logo"><a href="/"><img src="/images/Classic-TPG-3v3-Banner.gif" alt="TPG" title="TPG League" border="0" /></a></div>
            {elseif $lgname == '/hwkna'}<div id="tpg_logo"><a href="/"><img src="/images/TPGhawken.png" alt="TPG" title="TPG League" border="0" /></a></div>
			{elseif $lgname == '/dod6' || $lgname == '/draft' || $lgname == '/NightCup' || $lgname == '/TPGCup' || $lgname == '/euro6v6' || $lgname == '/classic' || $lgname == '/regions' || $lgname == '/tpg2'}<div id="tpg_logo"><a href="/"><img src="/images/Classic-TPG-6v6-Banner.gif" alt="TPG" title="TPG League" border="0" /></a></div>
			{elseif $lgname == '/dods6' || $lgname == '/cup'}<div id="tpg_logo"><a href="/"><img src="/images/Source-TPG-6v6-Banner.jpg" alt="TPG" title="TPG League" border="0" /></a></div>
            {elseif $lgname == '/csgo' || $lgname == '/csgodem'}<div id="tpg_logo"><a href="/"><img src="/images/TPGcsgoBanner.gif" alt="TPG" title="TPG League" border="0" /></a></div>
			{else}<div id="tpg_logo"><a href="/"><img src="/images/TPGgeneral.gif" alt="TPG" title="TPG League" border="0" /></a></div>
			{/if}
			<div id="league_selector">{$league_selector}</div>
		</div>
	</div>

	<div id="menu">
		<div class="rubberbox">
		<h1 class="rubberhdr"><span>League Info</span></h1>
			<ul>
			<li><a href="{$lgname}/news/">News</a></li>
            <li><a href="http://www.dodarchives.com/forum.php">Forums</a></li>
			{if !$logged_in}<li><a href="/register/">Join TPG</a></li>{/if}
			<li><a href="http://www.tpgleague.org/application.php">Admin Application</a></li>
			<li><a href="http://www.tpgleague.org/support/">Support Tickets</a></li>
            <li><a href="{$lgname}/membersearch/">Member Lookup</a></li>
			</ul>
			{if $lgname}
			<ul>
			{if $show_rules}
            <li><a href="{$lgname}/rules/">League Rules{if $new_rules}<img src="/images/new.gif" border="0" align="bottom" width="16" height="7" alt="New" title="New Rules" />{/if}</a></li>         
            {/if}
            <li><a href="{$lgname}/teams/">Team List</a></li>
            <li><a href="{$lgname}/maps/">Maps</a></li>
			{if $map_pack_url}<li><a href="{$map_pack_url}">Map Pack Download</a></li>{/if}
			{if $config_pack_url}<li><a href="{$config_pack_url}">Server Configs</a></li>{/if}
            {if $lgname == '/dod6' || $lgname == '/draft' || $lgname == '/NightCup' || $lgname == '/TPGCup' || $lgname == '/euro6v6' || $lgname == '/classic' || $lgname == '/regions' || $lgname == '/tpg2' || $lgname == '/dod3'}
            <li><a href="{$lgname}/approvedfiles/">Approved Files</a></li>{/if}
			
			<li><a href="{$lgname}/suspensions/">Suspensions</a></li>
			<li><a href="{$lgname}/pastchamps/">Past Champions</a></li>
            {if $lgname == '/dod6' || $lgname == '/dod3' || $lgname == '/draft' || $lgname == '/NightCup' || $lgname == '/TPGCup' || $lgname == '/euro6v6' || $lgname == '/classic' || $lgname == '/regions' || $lgname == '/tpg2'}<li><a href="{$lgname}/links/">Links</a></li>{/if}
			</ul>

			<ul>
			<li><a href="{$lgname}/schedule/">Schedule</a></li>
			<li><a href="{$lgname}/standings/">Standings</a></li>
			</ul>
			{/if}
		</div>
		{if $team_mini_panel}
			{$team_mini_panel}
		{/if}

		{*if $league_admins_panel}
			{$league_admins_panel}
		{/if*}

        {if !$lgname}
        <div class="rubberbox" id="inactiveleagues">
        <h1 class="rubberhdr"><span>Inactive Leagues</span></h1>
        <ul>
        <li><a href="/euro6v6/">DOD 6v6: Euro</a></li>
        <li><a href="/dods6/">DOD: Source 6v6</a></li>
        <li><a href="/dods3/">DOD: Source 3v3</a></li>
        <li><a href="/csgo/">Counter-Strike: GO</a></li>
        </ul>
        </div>
        {/if}
        
		{$login_cp}

        {if $lgname == '/dod6' || $lgname == '/dod3' || $lgname == '/draft' || $lgname == '/NightCup' || $lgname == '/TPGCup' || $lgname == '/euro6v6' || $lgname == '/classic' || $lgname == '/regions' || $lgname == '/tpg2'}
		<div class="rubberbox" id="affiliates">
		<h1 class="rubberhdr"><span>Community</span></h1>
		<a href="/affil/3/"><img src="/images/affils/1911-small.jpg" border="0" alt="nineteeneleven.org" /></a>
		</div>
        {elseif $lgname == '/dota2'}
        <div class="rubberbox" id="affiliates">
        <h1 class="rubberhdr"><span>Community</span></h1>
        <a href="/affil/4/"><img src="/images/affils/nacommunity.png" border="0" alt="nodota.com" /></a>
        </div>
        {/if}
        
	</div>

	{if $display_standings}
		{assign var='content_width' value='standings'}
	{else}
		{assign var='content_width' value='self'}
	{/if}
	<div id="content" class="content_{$content_width}">
		<h1 class="rubberhdr"><span>{$title}</span></h1>
		<div id="main-content">
		{$main_content}
		</div>
	</div>

	<div id="sub-section">
		{$standings}
	</div>

	<div id="footer" style="text-align: center;">

	</div>

</div>
</body>
</html>
