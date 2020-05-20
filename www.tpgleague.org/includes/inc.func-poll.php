<?php

class poll
{
    public $nplid;
    public $uid;
    public $ip;
    public $eligibility;
    public $closeDate;
    public $lid;
    public $closed;
    public $voted;
    public $error;
    public $title;
    public $tid;
    public $resultsHidden;

    function __construct($nplid)
    {
        if (is_null($nplid)) return FALSE;
        $this->nplid = $nplid;
        $this->ip = $_SERVER['REMOTE_ADDR'];
        if (!checkNumber(UID)) $this->uid = NULL;
        else $this->uid = UID;

        global $db;
        $sql = 'SELECT UNIX_TIMESTAMP(expire_date_gmt) AS unix_expire_date_gmt, lid, title, results_hidden, eligibility FROM news_polls WHERE nplid = ? LIMIT 1';
        $pollStatus =& $db->getRow($sql, array($this->nplid));
        $this->eligibility = $pollStatus['eligibility'];
        $this->closeDate = $pollStatus['unix_expire_date_gmt'];
        $this->lid = empty($pollStatus['lid']) ? NULL : $pollStatus['lid'];
        $this->title = $pollStatus['title'];
        $this->resultsHidden = $pollStatus['results_hidden'];

    }

    function pollVoted()
    {

        global $db;

        if ($this->closeDate < gmmktime()) {
            $this->closed = TRUE;
            return TRUE;
        } elseif (!$this->uid && $this->eligibility != 'ip') {
            $this->error = 'You must be logged in to vote.';
            return FALSE;
        } elseif ($this->eligibility != 'ip') {
            $sql = 'SELECT email = "" FROM users WHERE uid = ? LIMIT 1';
            $emailNotValidated =& $db->getOne($sql, array($this->uid));
            if ($emailNotValidated) {
                $this->error = 'You must <a href="/edit.account.php">validate your email address</a> before you may vote.';
                return FALSE;
            }

            $clause = '?';
            $constraint = $this->uid;
        } else {
            $clause = 'INET_ATON(?)';
            $constraint = $this->ip;
        }
        $sql = 'SELECT TRUE FROM news_polls_votes WHERE nplid = ? AND voter = '.$clause.' LIMIT 1';
        $this->voted =& $db->getOne($sql, array($this->nplid, $constraint));
        if ($this->voted) return TRUE;

        switch ($this->eligibility) {
            case 'captains':

                if (is_null($this->lid)) {
                    $sql = 'SELECT tid FROM teams INNER JOIN leagues USING (lid) WHERE captain_uid = ? AND teams.deleted = 0 AND leagues.inactive = 0 LIMIT 1';
                    $this->tid =& $db->getOne($sql, array($this->uid));
                    if (!$this->tid) {
                        $this->error = 'Only team captains are eligible to vote in this poll.';
                        return FALSE;
                    }
                } else {
                    $sql = 'SELECT tid FROM teams INNER JOIN leagues USING (lid) WHERE captain_uid = ? AND lid = ? AND teams.deleted = 0 AND leagues.inactive = 0 LIMIT 1';
                    $this->tid =& $db->getOne($sql, array($this->uid, $this->lid));
                    if (!$this->tid) {
                        $this->error = 'Only team captains are eligible to vote in this poll.';
                        return FALSE;
                    }

                    $sql = 'SELECT TRUE FROM news_polls_votes WHERE nplid = ? AND tid = ? LIMIT 1';
                    $alreadyVoted =& $db->getOne($sql, array($this->nplid, $this->tid));
                    if ($alreadyVoted) {
                        $this->error = 'A representative has already voted on behalf of your team for this poll.';
                        return FALSE;
                    }
                }

                return FALSE;

            case 'active_team':

                if (is_null($this->lid)) {

$sql = <<<SQL
                                SELECT TRUE 
                                FROM rosters 
                                INNER JOIN teams USING (tid)
                                INNER JOIN leagues ON (teams.lid = leagues.lid)
                                INNER JOIN divisions USING (divid)
                                INNER JOIN conferences USING (cfid)
                                INNER JOIN groups USING (grpid)
                                WHERE rosters.uid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00" 
                                AND teams.approved = 1 AND teams.inactive = 0 AND teams.deleted = 0 
                                AND leagues.inactive = 0 AND divisions.inactive = 0 AND conferences.inactive = 0 AND groups.inactive = 0 
                                LIMIT 1
SQL;
                    $eligible =& $db->getOne($sql, array($this->uid));
                    if (!$eligible) {
                        $this->error = 'You must be on the roster of an active and assigned team to vote in this poll.';
                        return FALSE;
                    }
                    return FALSE;

                } else {

$sql = <<<SQL
                                SELECT TRUE 
                                FROM rosters 
                                INNER JOIN teams USING (tid)
                                INNER JOIN leagues ON (teams.lid = leagues.lid)
                                INNER JOIN divisions USING (divid)
                                INNER JOIN conferences USING (cfid)
                                INNER JOIN groups USING (grpid)
                                WHERE rosters.uid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00" AND teams.lid = ?
                                AND teams.approved = 1 AND teams.inactive = 0 AND teams.deleted = 0 
                                AND leagues.inactive = 0 AND divisions.inactive = 0 AND conferences.inactive = 0 AND groups.inactive = 0 
                                LIMIT 1
SQL;
                    $eligible =& $db->getOne($sql, array($this->uid, $this->lid));
                    if (!$eligible) {
                        $this->error = 'You must be on the roster of an active and assigned team to vote in this poll.';
                        return FALSE;
                    }
                    return FALSE;

                }
                return FALSE;

            case 'registered':
                return FALSE;

            case 'ip':
                return FALSE;

            default:
                return FALSE;
        }
    
    }

    function pollVote($choiceID)
    {

        if ($this->voted) {
            $this->error = 'You have already voted in this poll.';
            return FALSE;
        }

        if ($this->closed) {
            $this->error = 'This poll is already closed.';
            return FALSE;
        } elseif (!$this->uid && $this->eligibility != 'ip') {
            $this->error = 'You must be logged in to vote';
            return FALSE;
        }

        global $db;
        $sql = 'SELECT TRUE FROM news_polls_choices WHERE nplchid = ? AND nplid = ? LIMIT 1';
        $choiceValid =& $db->getOne($sql, array($choiceID, $this->nplid));
        if (!$choiceValid) {
            $this->error = 'You have selected an invalid poll choice.';
            return FALSE;
        }

        if (!empty($this->error)) {
            return FALSE;
        }

        switch ($this->eligibility) {
            case 'active_team':
                $sql = 'INSERT INTO news_polls_votes (nplid, voter, nplchid) '
                     . 'VALUES (?, ?, ?)';
                $db->query($sql, array($this->nplid, $this->uid, $choiceID));
                return TRUE;

            case 'captains':
                if (is_null($this->lid)) {
                    $sql = 'INSERT INTO news_polls_votes (nplid, voter, nplchid) '
                         . 'VALUES (?, ?, ?)';
                    $db->query($sql, array($this->nplid, $this->uid, $choiceID));
                } else {
                    $sql = 'INSERT INTO news_polls_votes (nplid, voter, nplchid, tid) '
                         . 'VALUES (?, ?, ?, ?)';
                    $db->query($sql, array($this->nplid, $this->uid, $choiceID, $this->tid));
                }
                return TRUE;

            case 'registered':
                $sql = 'INSERT INTO news_polls_votes (nplid, voter, nplchid) '
                     . 'VALUES (?, ?, ?)';
                $db->query($sql, array($this->nplid, $this->uid, $choiceID));
                return TRUE;

            case 'ip':
                $sql = 'INSERT INTO news_polls_votes (nplid, voter, nplchid) '
                     . 'VALUES (?, INET_ATON(?), ?)';
                $db->query($sql, array($this->nplid, $this->ip, $choiceID));
                return TRUE;

            default:
                return FALSE;
        }
    }

    function pollGraph()
    {
        global $db, $tpl;
        include_once 'graphs.inc.php';

        if ($this->resultsHidden != 'Closed') {
$sql = <<<SQL
            SELECT name, COUNT(news_polls_votes.nplchid) AS votes
            FROM news_polls_choices 
            LEFT JOIN news_polls_votes USING (nplchid) 
            WHERE news_polls_choices.nplid = ? 
            GROUP BY name
            ORDER BY news_polls_choices.nplchid ASC
SQL;
            $votes =& $db->getAll($sql, array($this->nplid));
            foreach ($votes as $array) {
                $names[] = escape($array['name']);
                $values[] = $array['votes'];
            }
            $graph = new BAR_GRAPH('hBar');
            $graph->values = $values;
            $graph->labels = $names;

            $graph->barColors = '#cc6600';
            $graph->labelBGColor = '';
            $graph->labelBorder = '0';
        }

        $tpl->append('polls', array($this->nplid => array(
                                                            'type'   => 'graph',
                                                            'error'  => $this->error,
                                                            'title'  => $this->title,
                                                            'graph'  => $graph->create(),
                                                            'closed' => $this->closed,
                                                            'hidden' => $this->resultsHidden,
                                                            'close_date'  => $this->closeDate
                                                         )), TRUE);
    }

    function pollOptions()
    {
        global $db, $tpl;
        $sql = 'SELECT nplchid, name FROM news_polls_choices WHERE nplid = ?';
        $options =& $db->getAssoc($sql, NULL, array($this->nplid));
        $tpl->append('polls', array($this->nplid => array(
                                                            'type'   => 'options',
                                                            'error'  => $this->error,
                                                            'title'  => $this->title,
                                                            'options'=> $options,
                                                            'closed' => $this->closed,
                                                            'hidden' => $this->resultsHidden,
                                                            'close_date'  => $this->closeDate
                                                         )), TRUE);
    }
}
