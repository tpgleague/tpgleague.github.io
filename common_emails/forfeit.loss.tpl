{assign var='notification_subject' value='TPG Match Forfeited'}

{$notification.firstname|escape},

This notification is to inform you that your team has received a forfeit loss against {$notification.opponent_team|escape} for week {$notification.week|escape}. For this reason, your team has automatically been placed as inactive and will no longer be scheduled for matches.

If you believe this result was submitted in error, or if the loss was correct but you intend to keep participating in TPG League, then you must file a support ticket and request to be re-activated. You should do this as soon as possible so that you don't miss out on being scheduled for the upcoming week's matches. 

Support Tickets: http://support.tpgleague.org/ticket/

League: {$notification.league_title|escape}
Your Team: {$notification.your_team|escape}
Your Opponent: {$notification.opponent_team|escape}
Week: {$notification.week|escape}