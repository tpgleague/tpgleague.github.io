{if $user_data.deleted}
    <b>This user's account has been deleted.</b>
{else}
    <table style="width: 610px; marign: 0; padding: 0;" border="0">
    <tr>
    <td align="left">
        <table>        
        <tr>
            <td><b>Name:</b></td>
            <td>{$user_data.firstname|escape} {if $user_data.hide_lastname}{$user_data.lastname|truncate:2:"."|escape}{else}{$user_data.lastname|escape}{/if}</td>
        </tr>
        {if $user_data.country}
        <tr>
            <td><b>Location:</b></td>
            <td>{if $user_data.ccode}<img src="/images/flags/{$user_data.ccode}.png" width="16" height="11" alt="{$user_data.ccode}" title="{$user_data.country}" /> {/if}
            {if $user_data.city}{$user_data.city|escape}{/if}{if $user_data.city && $user_data.state},&nbsp;{elseif !$user_data.city && !$user_data.state}{$user_data.country}{/if}{if $user_data.state}{$user_data.state|escape}{/if}</td>
        </tr>
        {/if}
        <tr>
            <td><b>Joined TPG:</b></td>
            <td>{$user_data.join_date|converted_timezone}</td>
        </tr>
        </table>
    </td>
    <td align="right">
        {if $user_data.user_avatar_url && !$user_data.abuse_lock}<img src="{$user_data.user_avatar_url|escape}" width="100px" height="56px">{/if}
    </td>
    </tr>
    </table>

    {if $user_data.steam_profile_url}
    <br>
    <a href="{$user_data.steam_profile_url|replace:'@':'at'|escape}"><img src="/images/steam.png" title="Steam Community Profile" border="0"></a>
    {/if}
    
    {if $user_data.user_comments && !$user_data.abuse_lock}
    <br><br>
    <b>User Comments:</b>
    <p>{$user_data.user_comments|escape|nl2br}</p>
    {/if}    
    
    {if $roster_data}
    <br><br>
    
    <b>Current Teams:</b><br><br>
    <table class="tpg_results">
    <thead>

    <tr>
        <th>League</th>
        <th>Team Name</th>
        <th>Tag</th>
        <th>Handle</th>
        <th>Game ID</th>
        <th>Joined</th>
    </tr>
    </thead>
    <tbody>
        {foreach from=$roster_data name='member' item='member'}
        <tr>
            <td>{$member.league_title|escape}</td>
            <td><a href="http://www.tpgleague.org/{$member.leagues_lgname|escape}/team/{$member.teams_tid}/">{$member.teams_name|escape}</a></td>
            <td><a href="http://www.tpgleague.org/{$member.leagues_lgname|escape}/team/{$member.teams_tid}/">{$member.teams_tag|escape}</a></td>
            <td>{$member.rosters_handle|escape}</td>
            <td>{$member.rosters_gid}</td>
            <td>{$member.rosters_join_date_gmt|date_format:"%D"}</td>
        </tr>
        {/foreach}
    </tbody>
    </table>
    <br />
    {/if}
    
    {if $game_ids_data}
    <br><br>
    
    <b>Game IDs Used:</b>{if count($game_ids_data) > 1} (Note: This is every ID the user has ever entered into the system.  It is possible some of these might have been entered in error){/if}<br><br>

    {foreach from=$game_ids_data name='gameids' item='gameid'}
    <a class="gidlink" href="{$lgname}/membersearch/?search={$gameid.gid|escape}&rosters_gid=on">{$gameid.gid|escape}</a><br>
    {/foreach}

    <br />
    {/if}
    
{/if}
<br><br>