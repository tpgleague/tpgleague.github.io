<?php

$scriptMonth = $_GET['month'];
$scriptYear  = $_GET['year'];

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
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="start" href="" />
	<link rel="help" href="" />
	<link rel="previous" href="" />
	<link rel="next" href="" />
	<link href="default.css" type="text/css" rel="stylesheet" />
<style type="text/css">
	body { }
</style>
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
    document.getElementById(subobj).style.display="none"
}

</script>

</head>

<body>

<table cellspacing="0" cellpadding="2" border="1" style="font-family:Gill, Helvetica, sans-serif;font-size:x-small;">
<caption><?php echo date('F Y',strtotime("$scriptYear-$scriptMonth-01")); ?></caption>
<thead><tr><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th></tr></thead>
<?php

while ($Day = $Month->fetch()) {
	$isoDate = sprintf("%04d-%02d-%02d",$scriptYear, $scriptMonth, $Day->thisDay());
	$isholiday =& $composite->getHolidayForDate($isoDate,null,TRUE);
	$holidaycheck = "";
	if ($isholiday != null) {
		foreach($isholiday as $holidayname) {
			$holidayarray = $holidayname->toArray();
			$holidaycheck .= '&middot;'.$holidayarray[title].'<br />';
		}
	}

    if ($Day->isFirst()) {
        echo "<tr>\n";
    }

    if ($Day->isEmpty()) {
        echo '<td width="100" height="80" valign="top">&nbsp;</td>';
    } else {
        echo '<td width="100" height="80" valign="top"></b>'.$Day->thisDay().'</b><br />'.$holidaycheck; ?>
<a href="scheduleadd.php" title="Add Match" onClick="return overlay(this, 'subcontent2')">[+]</a></b><br />
<div id="subcontent2" style="position:absolute; display:none">
	<div style="border: 3px solid black; background-color: lightyellow; width: 200px; height: 100px; padding: 5px">
	<div align="left"><a href="#" onClick="overlayclose('subcontent2'); return false">Close</a></div>
	Add new date to season:
	<br>Map:<input type="text" name="z" id="z" value="" size="20">
	<br>Type:<SELECT name="weektype">
      <OPTION>Preseason</OPTION>
      <OPTION>Regular</OPTION>
      <OPTION>Playoffs</OPTION>
   </SELECT>

	</div>
</div>
		<?php echo "</td>\n";
    }

    if ($Day->isLast()) {
        echo "</tr>\n";
    }

}

echo "</table>\n";



?>