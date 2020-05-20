<?php

header("Cache-Control: no-cache");
header("Pragma: nocache");



$extra_head[] = <<<EOT

        <script type="text/javascript">

        /***********************************************
        * Overlapping Content link- ? Dynamic Drive (www.dynamicdrive.com)
        * This notice must stay intact for legal use.
        * Visit http://www.dynamicdrive.com/ for full source code
        ***********************************************/

        function getposOffset(overlay, offsettype){
            var totaloffset=(offsettype=="left")? overlay.offsetLeft : overlay.offsetTop;
            var parentEl=overlay.offsetParent;
            while (parentEl!=null){
                totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
                parentEl=parentEl.offsetParent;
            }
            return totaloffset;
        }

        function overlay(curobj, subobj){
            if (document.getElementById){
                var subobj=document.getElementById(subobj)
                subobj.style.left=getposOffset(curobj, "left")+"px"
                subobj.style.top=getposOffset(curobj, "top")+"px"
                subobj.style.display="block"
                return false
            }
            else
            return true
        }

        function overlayclose(subobj){
            document.getElementById(subobj).style.display="none";
            document.getElementById('er_'+subobj).innerHTML = '';
        }

        </script>

        <script type="text/javascript">
        /***********************************************
        * http://rajshekhar.net/blog/archives/85-Rasmus-30-second-AJAX-Tutorial.html
        ***********************************************/

        function createRequestObject() {
            var ro;
            var browser = navigator.appName;
            
            if(browser == "Microsoft Internet Explorer"){
                ro = new ActiveXObject("Microsoft.XMLHTTP");
            }else{
                ro = new XMLHttpRequest();
            }
            return ro;
        }

        var http = createRequestObject();

        function sndReq(action) {
EOT;

$extra_head[] = "http.open('get', '/edit.schedule.php?sid=" .$_GET['sid'] . "&'+action);";

$extra_head[] = <<<EOT

            http.onreadystatechange = handleResponse;
            http.send(null);
        }

        function handleResponse() {
            if(http.readyState == 4){
                var response = http.responseText;
                var update = new Array();

                if(response.indexOf('|') != -1) {
                    update = response.split('|');
                    if(update[1] == "success") {
                        var existingText;
                        existingText = document.getElementById('res_'+update[0]).innerHTML;
                        document.getElementById('res_'+update[0]).innerHTML = existingText + ' ' + update[2];
                        overlayclose(update[0]);
                    }
                    else {
                        document.getElementById('er_'+update[0]).innerHTML = 'Error: ' + update[2];
                    }
                }
            }
        }

        function popupHelp(help) {
            if (help == 'stg_number') {
                alert('STAGE NUMBER: The stage number. Also known as a week number (except when there are two matches per week, obviously).  Pre-season starts at stage 1, then Regular season around stage 3, and playoffs around stage 11.  This number is important for per-match suspensions and roster auto-locking purposes. This must be a NUMBER.');
                return false;
            }
            if (help == 'stg_type') {
                alert('STAGE TYPE: What type of matches are played on this date? Types are Holiday, All-Stars, Preseason, Regular and Playoffs.  All show up on the season schedule page. Holiday means no matches are played, but it must still get a week number. Also affects roster auto-locking. (They are called "stages" to the internal system instead of "weeks" because we could do more than one match a week if we wanted.)');
                return false;
            }
            if (help == 'stg_desc') {
                alert('STAGE DESCRIPTION: For use in the season calendar, eg: "Pre Wk 1", "Reg 1:2" (Week 1, Match 2, in case we want to do more than one match per week like CEVO-P). (They are called "stages" to the internal system instead of "weeks" because we could do more than one match a week if we wanted.)');
                return false;
            }
            if (help == 'map') {
                alert('STAGE MAP: The map to play, duh.');
                return false;
            }
            if (help == 'time') {
                alert('MATCH TIME: The default time the matches will start (HH:MM). Must be in 24-hour time, EASTERN time zone. "9:00PM" is "21:00".');
                return false;
            }
        }

        </script>


        <style>
            a {text-decoration: none; }
            a:link { color: blue; }
            a:visited { color: blue; }


table.calendar {
	border-width: 1px;
	border-spacing: 0px;
	border-style: solid;
	border-color: #808080;
	border-collapse: separate;
	background-color: #ffffff;
    clear: both;
}
table.calendar th {
	border-width: 1px;
	padding: 2px;
	border-style: groove;
	border-color: #808080;
	background-color: #ffffff;
	-moz-border-radius: ;
}
table.calendar td {
	border-width: 1px;
	padding: 2px;
	border-style: groove;
	border-color: #808080;
	background-color: #ffffff;
	-moz-border-radius: ;
    width: 110px;
    height: 90px;
    vertical-align: top;
    font-size: 0.8em;
}

div.popup {
    border: 3px solid black;
    background-color: lightyellow;
    width: 220px;
    height: 220px;
    padding: 2px
}

div.popup_close {
    text-align: left;
}

div.date {
 position:absolute;
 display:none
}

.subbtn {
    width: 100px;
    margin-top: 2px;
}

.plus, .qm {
    color: blue;
    cursor: pointer;
}
.qm {
    font-size: 0.8em;
    vertical-align: super;
}

.close {
    color: blue;
    cursor: pointer;
}
.pop_er {
    color: red;
}

.popup input {
    width: 100px;
}
div.wtf {
    float: left;
}
        </style>

EOT;

require_once '../includes/inc.initialization.support.php';

ini_set( "display_errors", 0);

if (!checkNumber(@$_GET['sid'])) { displayError('Error: Schedule ID not specified.'); }
else { @define('SID', @$_GET['sid']); }

$sql = 'SELECT lid FROM seasons WHERE sid = ' . $db->quoteSmart(SID);
$lid =& $db->getOne($sql);
define('LID', $lid);

$ACCESS = checkPermission('Edit League', 'League', LID);


//*** Populate upcoming matches ($calendarMatches)
$sql = 'SELECT sch_id, stg_number, stg_short_desc, stg_type, stg_match_date_gmt, map_title, schedules.deleted FROM schedules LEFT JOIN maps USING (mapid) WHERE sid = '. $db->quoteSmart(SID);
$matches =& $db->getAll($sql);
$calendarMatches = array();
foreach ($matches as $match) {
    $localDateTime = mysqlGMTtoLocal($match['stg_match_date_gmt']);
	//echo  $localDateTime;
	//echo $match['stg_match_date_gmt'];

    $localParts = explode(' ', $localDateTime);
    $localDate = $localParts[0];
    $localTime = $localParts[1];

    $localDateParts = explode('-', $localDate);
    $localDateYear = $localDateParts[0]+0;
    $localDateMonth = $localDateParts[1]+0;
    $localDateDay = $localDateParts[2]+0;
    $localDate = $localDateYear .'-'. $localDateMonth .'-'.  $localDateDay;

    $localTimeParts = explode(':', $localTime);
    $localTimeHour = $localTimeParts[0];
    $localTimeMinute = $localTimeParts[1];

    $calendarMatches[$localDate][] = array(
                                            'sch_id' => $match['sch_id'],
                                            'stg_number' => $match['stg_number'],
                                            'stg_short_desc' => $match['stg_short_desc'],
                                            'stg_type' => $match['stg_type'],
                                            'map_title' => $match['map_title'],
                                            'match_time' => $localTimeHour .':'. $localTimeMinute,
                                            'stg_deleted' => $match['deleted']
                                          );
}
//***



// ** AJAX
if (!empty($_GET['wt'])) {

    ini_set('display_errors', '0');
    $db->setErrorHandling(PEAR_ERROR_CALLBACK, 'ajaxShortError');


    $date = $_GET['date'];
    $time = $_GET['time'];
    $oldTime = $time;

    $dateNew = explode('-', $date);
    $time = explode(':', $time);

    $dateNewGMT = gmstrftime('%Y-%m-%d %H:%M:%S', mktime($time[0], $time[1], 0, $dateNew[1], $dateNew[2], $dateNew[0]));
    $latestDateNewGMT = gmstrftime('%Y-%m-%d %H:%M:%S', strtotime('+125 hours', mktime($time[0], $time[1], 0, $dateNew[1], $dateNew[2], $dateNew[0])));

    $stg_type = $_GET['wt'];
    if ($stg_type == 'Holiday' || $stg_type == 'All-Stars') $stg_number = NULL;

    $map_id = $_GET['map_id'];
    if ($map_id == '[TBA]') $map_id = NULL;
    if (empty($stg_number)) $stg_number = NULL;
    $stg_number = $_GET['stg_number'];
    $stg_desc = trim($_GET['stg_desc']);

    if (!$ACCESS) {
        echo $date .'|error|You do not have access to this function.';
        exit;
    }

    if (strlen($stg_desc) > 12) {
        echo $date .'|error|Stage description cannot exceed 12 characters.';
        exit;
    }

    if (strlen($stg_desc) < 1) {
        echo $date .'|error|Stage description cannot be left empty.';
        exit;
    }

    if (empty($time)) {
        echo $date .'|error|You must enter a time.';
        exit;
    }

    if (($stg_type == 'Preseason' || $stg_type == 'Regular' || $stg_type != 'Playoffs') && (!checkNumber($stg_number) || $stg_number == 0)) {
        echo $date .'|error|You must enter a numeric for stage number.';
        exit;
    }

/*
    if ((!checkNumber($map_id) || $map_id == 0) && $stg_type != 'Holiday') {
        echo $date .'|error|You must select a map (or define one using the <a href="/maps.manager.php?lid='.LID.'">maps manager</a>).';
        exit;
    }
*/

/*
    $sql = 'SELECT TRUE FROM schedules WHERE sid = '. $db->quoteSmart(SID) .' AND stg_match_date_gmt = '. $db->quoteSmart($dateNew);
    $matchTimeExists =& $db->getOne($sql);
    if ($matchTimeExists) {
        echo $date .'|error|A match is already scheduled for this date and time.';
        exit;
    }

    $sql = 'SELECT TRUE FROM schedules WHERE sid = '. $db->quoteSmart(SID) .' AND stg_number = '. $db->quoteSmart($stg_number);
    $stageNumberExists =& $db->getOne($sql);
    if ($stageNumberExists) {
        echo $date .'|error|This stage number already exists for this season.';
        exit;
    }
*/
/*
    if (!checkNumber($map_id) || $map_id == 0) {
        echo $date .'|error|You must enter a numeric for stage number.';
        exit;
    }
*/
    $insertValues = array(
                         'sid' => SID,
                         'mapid' => $map_id,
                         'stg_type' => $stg_type,
                         'stg_short_desc' => $stg_desc,
                         'stg_number' => $stg_number,
                         'stg_match_date_gmt' => $dateNewGMT,
                         'stg_latest_match_date_gmt' => $latestDateNewGMT,
                         'lid' => LID
                         );
    $insertRecord = new InsertRecord();
    $insertRecord->insertData('schedules', $insertValues);
    $sch_id = $insertRecord->lastInsertId();

    //$res = $db->autoExecute('schedules', $insertValues, DB_AUTOQUERY_INSERT);
    //$sql = 'INSERT INTO schedules (sid, map_id, stg_type, stg_number, stg_match_date, stg_desc, lid) VALUES (?, ?, ?, ?, ?, ?, ?)';
    //$insert =& $db->query($sql, $insertValues)
    //$sch_id =& $db->getOne('SELECT LAST_INSERT_ID()');

    $sql = 'SELECT map_title FROM maps WHERE mapid = '. $db->quoteSmart($map_id);
    $mapname =& $db->getOne($sql);
    echo $date . '|success|<a href="/edit.matches.php?sch_id='.$sch_id.'">('.$stg_number.') '. $stg_type .' <br />'. escape($mapname) .' '. $oldTime .'</a> <br /> ';
    exit;
}
// ** AJAX




require_once 'inc.initialization.display.php';


$sql = 'SELECT mapid, map_title FROM maps WHERE lid = '. $db->quoteSmart(LID);
$map_list =& $db->getAssoc($sql);

$mapOption = '<option value="[TBA]">[TBA]</option>'."\n";
foreach ($map_list as $mapid => $map_title) {
    $mapOption .= '<option value="'.$mapid.'">'. escape($map_title) .'</option>'."\n";
}
$mapOption .= '</select>';

if (!$ACCESS) echo '<div class="error"><p class="error">You are not authorized to use this control.</p></div>';
?>

<span style="color: red"><strong>New (10/4/12):</strong></span> The calendar is fixed, the warnings are hidden, and there is a new feature at the bottom of the page to display all of the season's matches in a list format.

<div style=" width: 85%; margin-top: 2em; margin-bottom: 2em;">
Notes: <ul>
    <li>Click the blue plus signs to open the popup, then fill out the form.</li>
    <li>Once you click submit, the map is added to the schedule on the backend. There is no "save" button.</li>
    <li>This page does NOT work in Internet Explorer 6, or possibly any version of IE. Go download <a href="http://www.opera.com">Opera</a> or <a href="http://www.mozilla.com/en-US/firefox/">Firefox</a>, you tard.</li>
    <li>Click the match link to schedule matches for that date.</li>
</ul>
</div>

<?php


$loopMonth = @$_GET['month'];
$loopYear = @$_GET['year'];

if (empty($loopMonth)) {
    $loopMonth = date('n');
    $loopYear = date('Y');
}

/*
$endMonth = date('n', strtotime('+1 months'));
$endYear = date('Y', strtotime('+1 months'));

while (($loopMonth != $endMonth) || ($loopYear != $endYear)) {
*/
        $scriptMonth = $loopMonth;
        $scriptYear  = $loopYear;
        $curMonth = mktime(0,0,0, $scriptMonth, 1, $scriptYear);
        $nextMonth = strtotime('+1 months', $curMonth);
        $lastMonth = strtotime('-1 months', $curMonth);
        $lastMonthSmall = date('n', strtotime('-1 months', $curMonth));

        $nextMonthSmall = date('n', strtotime('+1 months', $curMonth));
        $lastYear = date('Y', strtotime('-1 months', $curMonth));
        $nextYear = date('Y', strtotime('+1 months', $curMonth));

        require_once 'Calendar/Month/Weekdays.php';
        require_once 'Date/Holidays.php';

        $Month = new Calendar_Month_Weekdays($scriptYear, $scriptMonth, 0);
        $Month->build();

        $composite         = Date_Holidays::factory('Composite');
        $custom            = Date_Holidays::factory('Custom',$scriptYear);
        $customNextYear    = Date_Holidays::factory('Custom',$scriptYear+1);
        $composite->addDriver($custom);
        $composite->addDriver($customNextYear);
?>
        <div>
        <div>
        <div style="float:left"><a href="/edit.schedule.php?sid=<?php echo SID; ?>&amp;year=<?php echo $lastYear; ?>&amp;month=<?php echo $lastMonthSmall; ?>">&lt;&lt; <?php echo date('F Y',$lastMonth);?></a></div>
        <div style="float:right"><a href="/edit.schedule.php?sid=<?php echo SID; ?>&amp;year=<?php echo $nextYear; ?>&amp;month=<?php echo $nextMonthSmall; ?>"><?php echo date('F Y',$nextMonth);?> &gt;&gt;</a></div>
        </div>
        <table class="calendar">
        <caption style="font-weight: bold;"><?php echo date('F Y',$curMonth);?></caption>
        <thead><tr><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th></tr></thead>
<?php

        while ($Day = $Month->fetch()) {
            $isoDate = sprintf("%04d-%02d-%02d",$scriptYear, $scriptMonth, $Day->thisDay());
            $isholiday =& $composite->getHolidayForDate($isoDate,null,TRUE);
            $holidaycheck = "";
            if ($isholiday != null) {
                foreach($isholiday as $holidayname) {
                    $holidayarray = $holidayname->toArray();
                    $holidaycheck .= '&middot;'.$holidayarray['title'].'<br />';
                }
            }

            if ($Day->isFirst()) {
                echo "<tr>\n";
            }

            if ($Day->isEmpty()) {
                echo '<td>&nbsp;</td>';
            } else {
                $curDate = $loopYear.'-'.$loopMonth.'-'.$Day->thisDay();

                // look for scheduled matches
                $schedMatch = '';
				//$curDate = 2012-10-5
				//$calendarMatches defaulting all to 1969-12-31
                if (array_key_exists($curDate, $calendarMatches)) {
					$schedMatch = '';
                    foreach ($calendarMatches[$curDate] as $match) {
                        if ($match['stg_deleted']) $strikestyle='style="text-decoration: line-through;"';
                        else $strikestyle = '';
                        $schedMatch .= '<div '.$strikestyle.'>';
                        $schedMatch .= '<a href="/edit.matches.php?sch_id='.$match['sch_id'].'">';
                        if (!empty($match['stg_number'])) $schedMatch .= '('. $match['stg_number'] .') ';
                        $schedMatch .= $match['stg_type'] .' <br />';
                        if (!empty($match['map_title'])) $schedMatch .= escape($match['map_title']) .' <br />';
                        $schedMatch .= escape($match['stg_short_desc']) .' ';
                        $schedMatch .= $match['match_time'] .'</a></div> ';
                    }
                }
                //var_dump($Day->thisDay(), $loopMonth, $loopYear);
                if ($Day->thisDay() == date('j') && $loopMonth == date('n') && $loopYear == date('Y')) $dayIsToday = ' style="background-color: #FFCC99;"';
                else $dayIsToday = '';
                echo '<td'.$dayIsToday.'>'.$Day->thisDay();
?>
 <a class="plus" title="Add Match" onclick="return overlay(this, '<?php echo $curDate; ?>')">[+]</a></b><br />
<?php
                echo $holidaycheck . $schedMatch;

?>
<div id="res_<?php echo $curDate; ?>"></div>
<div class="date" id="<?php echo $curDate; ?>">
    <div class="popup">
        <div class="popup_close"><a class="close" onclick="overlayclose('<?php echo $curDate; ?>'); return false">Close</a></div>
        Add new date to season (<?php echo $curDate; ?>):
        <br />Stage Map<a class="qm" onclick="javascript: popupHelp('map')">(?)</a>: <select name="map_id" id="map_id-<?php echo $curDate; ?>"><?php echo $mapOption; ?>
        <br />Stage Type<a class="qm" onclick="javascript: popupHelp('stg_type')">(?)</a>: <select name="week_type" id="wt-<?php echo $curDate; ?>">
          <option>Preseason</option>
          <option>Regular</option>
          <option>Playoffs</option>
          <option>Holiday</option>
          <option>All-Stars</option>
       </select>
        <br /><div class="wtf">Match Time<a class="qm" onclick="javascript: popupHelp('time')">(?)</a>:</div> <input type="text" name="match_time" size="5" maxlength="5" value="21:00" id="match_time-<?php echo $curDate; ?>" />
        <br /><div class="wtf">Stage Number<a class="qm" onclick="javascript: popupHelp('stg_number')">(?)</a>:</div> <input type="text" name="stg_number" size="2" maxlength="2" id="stg_number-<?php echo $curDate; ?>" />
        <br /><div class="wtf">Stage Desc.<a class="qm" onclick="javascript: popupHelp('stg_desc')">(?)</a>:</div> <input type="text" size="10" maxlength="12" name="stg_desc" id="stg_desc-<?php echo $curDate; ?>" />

        <br /><input class="subbtn" type="submit" name="submit" value="Submit" onclick="javascript:sndReq('date=<?php echo $curDate; ?>&wt=' + escape(document.getElementById('wt-<?php echo $curDate; ?>').value) + '&map_id=' + escape(document.getElementById('map_id-<?php echo $curDate; ?>').value) + '&stg_number=' + escape(document.getElementById('stg_number-<?php echo $curDate; ?>').value) + '&stg_desc=' + escape(document.getElementById('stg_desc-<?php echo $curDate; ?>').value) + '&time=' + escape(document.getElementById('match_time-<?php echo $curDate; ?>').value))" />
        <br /><div class="pop_er" id="er_<?php echo $curDate; ?>"></div>
    </div>
</div>
<?php           echo "</td>\n";
            }

            if ($Day->isLast()) {
                echo "</tr>\n";
            }

        }

        echo "</table></div><br />\n";


/*
    if ($loopMonth == 12) {
        $loopMonth = 1;
        ++$loopYear;
    } else {
        ++$loopMonth;
    }

}
*/
?>

<br />

<h2>All Matches This Season:</h2>

<?php
$schedMatches = '';
$strikestyle = '';
foreach ($matches as $match)
{
    if ($match['deleted']) $strikestyle='style="text-decoration: line-through;"';
    else $strikestyle = '';
    
    $schedMatches .= '<div '.$strikestyle.'>';
    $schedMatches .= '<a href="/edit.matches.php?sch_id='.$match['sch_id'].'">';
    
    if (!empty($match['stg_number'])) $schedMatches .= '('. $match['stg_number'] .') ';
    
    //$schedMatches .= $match['stg_type'] .' <br />';
    
    $schedMatches .= escape($match['stg_short_desc']) .' ';
    
    if (!empty($match['map_title'])) $schedMatches .= escape($match['map_title']) .' <br />';
    
    $schedMatches .= '</a></div><br />';
}
    //echo '<a href="/edit.matches.php?sch_id='.$match['sch_id'].'">'.$match['stg_number'].' - '.$match['map_title'].'</a><br/>';
    echo $schedMatches;
?>


<p>&nbsp;</p>
<br />
<p>&nbsp;</p>

<?php
//displayTemplate('edit.schedule');
