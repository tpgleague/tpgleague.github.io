<!-- Begin Container -->
			<div class="container">
				<div class="sixteen columns">
					<div class="bold-border-top"></div>
					<h2 class="headline headline-top-border healine-height">Teams Manager</h2>
					<div class="bold-border-bottom"></div>
				</div>
			</div>
			<!-- End Container -->	
			<!-- Begin Container -->
			<div class="container">
				<!-- 16 Columns -->
				<div class="sixteen columns">
					<div class="col-16">
						<h4 class="headline"> </h4>
						<p>

{if !$ACCESS}
<div>You do not have access to use this control.</div>
{/if}


<div>
<p>This page is verified to work in the following browsers: <a href="http://www.opera.com">Opera 9</a>, <a href="http://www.mozilla.com/en-US/firefox/">Firefox 2</a>, <a href="http://www.microsoft.com/windows/downloads/ie/getitnow.mspx">Internet Explorer 7</a>.</p>

<br />

<p style="font-size: 150%;">At the end of preseason/regular season, before doing moveups/movedowns/approvals (but after deleting or inactivating teams), make sure you go to the <a href="/edit.season.php?lid={$smarty.const.LID}">season manager</a> and take a snapshot of preseason/regular season. Please talk to Brian if you do not understand the reason.</p>
</div>

<div style="margin: 1em auto; padding: 0.5em; border: 1px dashed orange; ">
[Approved/Unapproved] [Active/Inactive] (PQ) Team name - Team tag (roster size/Lock Status) Create date
<br />
<br /><span style="color: blue;">Blue text</span> means roster size is equal to or greater than the required amount for this league.
<br /><span style="color: red;">Red text</span> means roster size is below the required amount.
<br />Teams in unassigned divisions/conferences/groups are slightly opaque.
<br />If a team is in Pending Queue (PQ) for any match dates, then you will not be able to move the team.
<br />If a team is scheduled (Match) for any unreported matches, then you will not be able to move the team (<b>implemented due to admin retardedness</b>).
</div>


<div>{if $smarty.get.sort == 'created'}<a href="/teams.manager.php?lid={$smarty.get.lid}">Sort alphabetically</a>{else}<a href="/teams.manager.php?lid={$smarty.get.lid}&amp;sort=created">Sort by team create date</a>{/if}</div>

{strip}
<form action="{$smarty.server.REQUEST_URI}" method="post" style="margin:0; padding:0; width: 100%;">

<div id="mover" style="border: 0px solid red; padding: 0px; width: 700px;">

{assign var='greyedout' value=' opacity:.50; filter: alpha(opacity=50); -moz-opacity: 0.50;'}

	{foreach from=$standings_divisions key=divid item=division name=group}
		<div class="division" style="width: 690px; {if $division.inactive}{$greyedout}{/if}" id="divid_{$divid}">
			<h3>Division: {$division.division_title|escape} [{if $division.inactive}Inactive{else}Active{/if}]</h3>

			{foreach from=$standings_conferences[$divid] item=conference name=conference}
				<div class="conference" id="cfid_{$conference.cfid}" style="margin-top: 2px; margin-bottom: 6px; width: 642px; {if $conference.inactive}{$greyedout}{/if}">
					<h3>Conference: {$conference.conference_title|escape} [{if $conference.inactive}Inactive{else}Active{/if}]</h3>

					{foreach from=$standings_groups[$conference.cfid] item=group name=group}
						<div class="section" id="group_{$divid}-{$conference.cfid}-{$group.grpid}" style="background-color: #CCFFFF; border-color: #99FFFF; margin-top: 1px; margin-bottom: 2px; width: 630px; {if $group.inactive}{$greyedout}{/if}">
							<h3 class="handle" style="background-color: #99FFFF; padding-top: 0px; padding-bottom: 0px;">Group: {$group.group_title|escape} [{if $group.inactive}Inactive{else}Active{/if}]</h3>

									{foreach from=$standings_teams[$group.grpid] item=team name=team}
									<div id="item_{$team.tid}" class="{if $team.pq_sch_id || $team.match_id}lineitem_no_move{else}lineitem{/if}">

										<span id="div_appr_{$team.tid}" class="approve" >
											[{if $team.approved}A{else}U{/if}][{if $team.inactive}I{else}A{/if}]
										</span>

										<span class="match_pq">
										{if $team.pq_sch_id}
											(<a href="/edit.matches.php?sch_id={$team.pq_sch_id}">PQ</a>)
										{elseif $team.match_id}
											(<a href="/edit.match.php?mid={$team.match_id}">Match</a>)
										{/if}
										</span>

										<span class="name_tag">
											<a href="/edit.team.php?tid={$team.tid}">{$team.name|escape} - {$team.tag|escape}</a>
										</span>

										<span class="roster_status {if $team.roster_count < $league_format && $league_format > 0}rosterbelowmin{/if} {if $team.roster_lock != 'auto'}blink{/if}">
											({$team.roster_count} / {$team.roster_lock|capitalize})
										</span>

										<span class="date">
											{$team.unix_create_date_gmt|easy_date}
										</span>

									</div>
									{/foreach}

						</div>
					{/foreach}
						<div class="section" id="group_{$divid}-{$conference.cfid}-0" style="background-color: #CCFFFF; border-color: #99FFFF; margin-top: 1px; margin-bottom: 2px; width: 630px; {$greyedout}">
							<h3 class="handle" style="background-color: #99FFFF; padding-top: 0px; padding-bottom: 0px;">Group: Unassigned</h3>
									{foreach from=$standings_teams[0] item=team name=team}
									{if (($team.cfid == $conference.cfid) && ($team.divid == $divid))}
									<div id="item_{$team.tid}" class="{if $team.pq_sch_id || $team.match_id}lineitem_no_move{else}lineitem{/if}">

										<span id="div_appr_{$team.tid}" class="approve" >
											[{if $team.approved}A{else}U{/if}][{if $team.inactive}I{else}A{/if}]
										</span>

										<span class="match_pq">
										{if $team.pq_sch_id}
											(<a href="/edit.matches.php?sch_id={$team.pq_sch_id}">PQ</a>)
										{elseif $team.match_id}
											(<a href="/edit.match.php?mid={$team.match_id}">Match</a>)
										{/if}
										</span>

										<span class="name_tag">
											<a href="/edit.team.php?tid={$team.tid}">{$team.name|escape} - {$team.tag|escape}</a>
										</span>

										<span class="roster_status {if $team.roster_count < $league_format && $league_format > 0}rosterbelowmin{/if} {if $team.roster_lock != 'auto'}blink{/if}">
											({$team.roster_count} / {$team.roster_lock|capitalize})
										</span>

										<span class="date">
											{$team.unix_create_date_gmt|easy_date}
										</span>

									</div>
									{/if}
									{/foreach}
						</div>

				</div>
			{/foreach}
						<div class="section" id="group_{$divid}-0-0" style="margin: 2px 5px 6px; padding: 0px 0px 10px 0px; background-color: #CCF; width: 642px; {$greyedout}">
							<h3 class="handle" style="padding: 2px 5px; margin: 0 0 10px 0; display: block; background-color: #99F;">Conference: Unassigned</h3>
									{foreach from=$standings_teams[0] item=team name=team}
									{if (($team.cfid == 0) && ($team.divid == $divid))}
									<div id="item_{$team.tid}" class="{if $team.pq_sch_id || $team.match_id}lineitem_no_move{else}lineitem{/if}">

										<span id="div_appr_{$team.tid}" class="approve" >
											[{if $team.approved}A{else}U{/if}][{if $team.inactive}I{else}A{/if}]
										</span>

										<span class="match_pq">
										{if $team.pq_sch_id}
											(<a href="/edit.matches.php?sch_id={$team.pq_sch_id}">PQ</a>)
										{elseif $team.match_id}
											(<a href="/edit.match.php?mid={$team.match_id}">Match</a>)
										{/if}
										</span>

										<span class="name_tag">
											<a href="/edit.team.php?tid={$team.tid}">{$team.name|escape} - {$team.tag|escape}</a>
										</span>

										<span class="roster_status {if $team.roster_count < $league_format && $league_format > 0}rosterbelowmin{/if} {if $team.roster_lock != 'auto'}blink{/if}">
											({$team.roster_count} / {$team.roster_lock|capitalize})
										</span>

										<span class="date">
											{$team.unix_create_date_gmt|easy_date}
										</span>

									</div>
									{/if}
									{/foreach}
						</div>

		</div>
	{/foreach}
						<div class="section" id="group_0-0-0" style="border: 1px solid #CCCCCC; margin: 30px 0px; padding: 0px 0px 10px 0px; background-color: #EFEFEF; width: 690px;{$greyedout}">
							<h3 class="handle" style="font-size: 14px; padding: 2px 5px; margin: 0 0 10px 0; background-color: #CCCCCC; display: block;">Division: Unassigned</h3>
									{foreach from=$standings_teams[0] item=team name=team}
									{if (($team.divid == 0))}
									<div id="item_{$team.tid}" class="{if $team.pq_sch_id || $team.match_id}lineitem_no_move{else}lineitem{/if}">

										<span id="div_appr_{$team.tid}" class="approve" >
											[{if $team.approved}A{else}U{/if}][{if $team.inactive}I{else}A{/if}]
										</span>

										<span class="match_pq">
										{if $team.pq_sch_id}
											(<a href="/edit.matches.php?sch_id={$team.pq_sch_id}">PQ</a>)
										{elseif $team.match_id}
											(<a href="/edit.match.php?mid={$team.match_id}">Match</a>)
										{/if}
										</span>

										<span class="name_tag">
											<a href="/edit.team.php?tid={$team.tid}">{$team.name|escape} - {$team.tag|escape}</a>
										</span>

										<span class="roster_status {if $team.roster_count < $league_format && $league_format > 0}rosterbelowmin{/if} {if $team.roster_lock != 'auto'}blink{/if}">
											({$team.roster_count} / {$team.roster_lock|capitalize})
										</span>

										<span class="date">
											{$team.unix_create_date_gmt|easy_date}
										</span>

									</div>
									{/if}
									{/foreach}
						</div>



</div>

  <input type="hidden" name="order" id="order" value="" />
  {if $ACCESS}<input type="submit" onclick="getGroupOrder()" value="Save Changes" />{/if}

</form>
{/strip}

<p>
<br />
<br />
</p>

{$cdata}
						</p>
					</div>	
				</div>
				
			</div>
</div>
<!-- End Container -->
			
		<!-- End Wrapper -->





