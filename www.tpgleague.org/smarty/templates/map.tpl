    {*<h2>{$map.map_title}</h2>*}
    {if $map.overview_path}<img width="320px" height="240px" src="http://files.tpgleague.org{$map.overview_path}"><br/><br/>{/if}
    {if $map.filename}<a href="{$map.filename}">Download The Map</a>{else}<i>This is a stock map. No download is available.</i>{/if}<br/>
    {if $map.config_path}<a href="{$map.config_path}">Config Download</a>{else}<i>No individual config file is available for this map.  Please download the entire config pack.</i>{/if}<br/><br/>
    
    {if $times_played || $scoringStats}
    <b>Since season {$earliest_season}:</b>{if $earliest_season > 1} <i>(previous seasons were on a different website)</i>{/if}
    <table>
    {if $times_played}
    {foreach from=$times_played item='stage_type'}
    <tr>
        <td><b>{$stage_type.stg_type} Uses:</b></td>
        <td>{$stage_type.used}</td>
    </tr>
    {/foreach}
    {/if}
    {if $scoring_stats && $scoring_stats.avg_allies_score}
    <tr>
        <td><b>Avg Allies Score:</b></td>
        <td>{$scoring_stats.avg_allies_score|string_format:"%.2f"}</td>
    </tr>
    <tr>
        <td><b>Avg Axis Score:</b></td>
        <td>{$scoring_stats.avg_axis_score|string_format:"%.2f"}</td>
    </tr>
    <tr>
        <td><b>Max Allies Score:</b></td>
        <td>{$scoring_stats.max_allies_score}</td>
    </tr>
    <tr>
        <td><b>Max Axis Score:</b></td>
        <td>{$scoring_stats.max_axis_score}</td>
    </tr>
    {/if}
    </table>
    
    <br />
    <br />
    {/if}
    
    {if $exploits}
    
    The following areas are known map exploitable areas and are illegal to use in a match.
    This is just the known areas. Other areas may still exist that you are not permitted to use.
    Areas you can only access by boosting are not displayed below, because they are illegal per rule.
    <br/><br/>
    {foreach item='location' from=$exploits}
    <img src="http://files.tpgleague.org{$map.illegal_locations_path}{$location}">
    {/foreach}
    {/if}
    
    <br />
    <br />
    