{assign var='notification_subject' value='TPG Match Scheduled'}

{$notification.firstname|escape},

This notification is to inform you that your team has been scheduled against {$notification.opponent_team|escape} for week {$notification.week|escape}.

You can begin scheduling this match by visiting the following link:

{$notification.url}

Or by logging into http://www.tpgleague.org/{$notification.lgname}/ and clicking "Season Matches" under your Team Panel.

League: {$notification.league_title|escape}
Your team: {$notification.your_team|escape}
Your Opponent: {$notification.opponent_team|escape}
Week: {$notification.week|escape}