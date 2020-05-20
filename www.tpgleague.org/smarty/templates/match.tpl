{if $smarty.const.lid}

    <table>
    <tr>
        <td><b>Stage:</b></td>
        <td>{$stg_short_desc} - <a class="tpglink" href="{$lgname}/map/{$map_title}/">{$map_title}</a></td>
    </tr>
    <tr>
        <td><b>Match Date:</b></td>
        <td>{$match_date}</td>
    </tr>
    <tr>
        <td>{if ($reporting_admin_name != '')}<b>Last Reported or Modified Date:</b> {else}<b>Report Date:</b>{/if}</td>
        <td>{$report_date}</td>
    </tr>
    <tr>
        <td><b>Reporting User:</b></td>
        <td>{if $reporting_user}<a href="{$lgname}/user/{$reporting_uid}/">{$reporting_user}</a>{/if}</td>
    </tr>
    <tr>
        <td><b>Reporting Team:</b></td>
        <td>{$reporting_team}</td>
    </tr>
    {if ($reporting_admin_name != '')}
    <tr>
        <td><b>Reporting or Modifying Admin:</b></td>
        <td>{$reporting_admin_name}</td>
    </tr>
    {/if}
    </table>
    
    <br />

    {if ($forfeit_name != '')}
        <b>{$forfeit_name} foreited the match.</b>
    {/if}
    
    {if ($side_selector_h1a != '')}
        <table id="match_table" class="spaced_table">
        <tbody>
        <tr>
        <th colspan="3" style="text-align:center;">First Half</th></tr>
        <tr>
        <th>Side</th>
        <th>Team</th>
        <th align="right">Score</th>
        </tr>
        <tr>
        <td id="h1a_side" class="sidecol">{if $side_selector_h1a == 'Allies'}<img src="/images/allies.gif" with="15px" height="15px" />{elseif $side_selector_h1a == 'Axis'}<img src="/images/axis.gif" with="15px" height="15px" />{elseif $side_selector_h1a == 'Away Side'}Side 1{else}{$side_selector_h1a}{/if}</td>
        <td><a href="{$lgname}/team/{$away_tid}/">{$away_team_name}</a></td>
        <td align="center" class="scorecol">{$h1a_score}</td>
        </tr>
        <tr>
        <td id="h1h_side" class="sidecol">{if $side_selector_h1h == 'Allies'}<img src="/images/allies.gif" with="15px" height="15px" />{elseif $side_selector_h1h == 'Axis'}<img src="/images/axis.gif" with="15px" height="15px" />{elseif $side_selector_h1h == 'Home Side'}Side 2{else}{$side_selector_h1h}{/if}</td>
        <td><a href="{$lgname}/team/{$home_tid}/">{$home_team_name}</a></td>
        <td align="center" class="scorecol">{$h1h_score}</td>
        </tr>
        </tbody>

        <tbody>
        <tr>
        <th colspan="3" style="text-align:center;">Second Half</th></tr>
        <tr>
        <th>Side</th>
        <th>Team</th>
        <th>Score</th>
        </tr>
        <tr>
        <td id="h2a_side" class="sidecol">{if $side_selector_h2a == 'Allies'}<img src="/images/allies.gif" with="15px" height="15px" />{elseif $side_selector_h2a == 'Axis'}<img src="/images/axis.gif" with="15px" height="15px" />{elseif $side_selector_h2a == 'Away Side'}Side 2{else}{$side_selector_h2a}{/if}</td>
        <td><a href="{$lgname}/team/{$away_tid}/">{$away_team_name}</a></td>
        <td align="center" class="scorecol">{$h2a_score}</td>
        </tr>
        <tr>
        <td id="h2h_side" class="sidecol">{if $side_selector_h2h == 'Allies'}<img src="/images/allies.gif" with="15px" height="15px" />{elseif $side_selector_h2h == 'Axis'}<img src="/images/axis.gif" with="15px" height="15px" />{elseif $side_selector_h2h == 'Home Side'}Side 1{else}{$side_selector_h2h}{/if}</td>
        <td><a href="{$lgname}/team/{$home_tid}/">{$home_team_name}</a></td>
        <td align="center" class="scorecol">{$h2h_score}</td>
        </tr>
        </tbody>
        
        <tbody>
        <tr>
        <th colspan="3" style="text-align:center;">Final</th></tr>
        <tr>
        <th>Winner</th>
        <th>Team</th>
        <th>Score</th>
        </tr>
        <tr>
        <td id="h2a_side" class="sidecol">{if ($away_score > $home_score)}<img src="/images/asterisk_orange.gif" with="15px" height="15px" />{/if}</td>
        <td><a href="{$lgname}/team/{$away_tid}/">{$away_team_name}</a></td>
        <td align="center" class="scorecol">{$away_score}</td>
        </tr>
        <tr>
        <td id="h2h_side" class="sidecol">{if ($home_score > $away_score)}<img src="/images/asterisk_orange.gif" with="15px" height="15px" />{/if}</td>
        <td><a href="{$lgname}/team/{$home_tid}/">{$home_team_name}</a></td>
        <td align="center" class="scorecol">{$home_score}</td>
        </tr>
        </tbody>

        </table>
        
        <br />
        
        <b>Reporting user's comments:</b>
        <p>{$match_comments|escape|nl2br}</p>
    {/if}

{else}

<div>No match by that ID found.</div>


{/if}
