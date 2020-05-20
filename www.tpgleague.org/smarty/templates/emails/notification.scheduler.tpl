{assign var='notification_subject' value='TPG Scheduler Notification'}

{$notification.firstname|escape},

An update has been posted to your TPG Scheduler panel. You can review this update by visiting the following link:

{$notification.url}

Or by logging into http://www.tpgleague.org/{$notification.lgname} and clicking "Season Matches" under your Team Panel.

League: {$notification.league_title|escape}
Your team: {$notification.your_team|escape}
Your Opponent: {$notification.opponent_team|escape}
Week: {$notification.week|escape}