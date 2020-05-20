<?php
/*******************************************************************************
*  Title: Help Desk Software HESK
*  Version: 2.5.2 from 13th October 2013
*  Author: Klemen Stirn
*  Website: http://www.hesk.com
********************************************************************************
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2005-2013 Klemen Stirn. All Rights Reserved.
*  HESK is a registered trademark of Klemen Stirn.

*  The HESK may be used and modified free of charge by anyone
*  AS LONG AS COPYRIGHT NOTICES AND ALL THE COMMENTS REMAIN INTACT.
*  By using this code you agree to indemnify Klemen Stirn from any
*  liability that might arise from it's use.

*  Selling the code for this program, in part or full, without prior
*  written consent is expressly forbidden.

*  Using this code, in part or full, to create derivate work,
*  new scripts or products is expressly forbidden. Obtain permission
*  before redistributing this software over the Internet or in
*  any other medium. In all cases copyright and header must remain intact.
*  This Copyright is in full effect in any country that has International
*  Trade Agreements with the United States of America or
*  with the European Union.

*  Removing any of the copyright notices without purchasing a license
*  is expressly forbidden. To remove HESK copyright notice you must purchase
*  a license for this script. For more information on how to obtain
*  a license please visit the page below:
*  https://www.hesk.com/buy.php
*******************************************************************************/
define('IN_SCRIPT',1);
define('HESK_PATH','../');

/* Get all the required files and functions */
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
require(HESK_PATH . 'inc/admin_functions.inc.php');
require(HESK_PATH . 'inc/reporting_functions.inc.php');
hesk_load_database_functions();

hesk_session_start();
hesk_dbConnect();
hesk_isLoggedIn();

// Check permissions for this feature
hesk_checkPermission('can_export');

// Set default values
define('CALENDAR',1);
define('MAIN_PAGE',1);
define('LOAD_TABS',1);

$selected = array(
	'w'    => array(0=>'',1=>''),
	'time' => array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>''),
);
$is_all_time = 0;

// Default this month to date
$date_from = date('Y-m-d',mktime(0, 0, 0, date("m"), 1, date("Y")));
$date_to = date('Y-m-d');
$input_datefrom = date('m/d/Y', strtotime('last month'));
$input_dateto = date('m/d/Y');

/* Date */
if (!empty($_GET['w']))
{
	$df = preg_replace('/[^0-9]/','', hesk_GET('datefrom') );
    if (strlen($df) == 8)
    {
    	$date_from = substr($df,4,4) . '-' . substr($df,0,2) . '-' . substr($df,2,2);
        $input_datefrom = substr($df,0,2) . '/' . substr($df,2,2) . '/' . substr($df,4,4);
    }
    else
    {
    	$date_from = date('Y-m-d', strtotime('last month') );
    }

	$dt = preg_replace('/[^0-9]/','', hesk_GET('dateto') );
    if (strlen($dt) == 8)
    {
    	$date_to = substr($dt,4,4) . '-' . substr($dt,0,2) . '-' . substr($dt,2,2);
        $input_dateto = substr($dt,0,2) . '/' . substr($dt,2,2) . '/' . substr($dt,4,4);
    }
    else
    {
    	$date_to = date('Y-m-d');
    }

    if ($date_from > $date_to)
    {
        $tmp = $date_from;
        $tmp2 = $input_datefrom;

        $date_from = $date_to;
        $input_datefrom = $input_dateto;

        $date_to = $tmp;
        $input_dateto = $tmp2;

        $note_buffer = $hesklang['datetofrom'];
    }

    if ($date_to > date('Y-m-d'))
    {
    	$date_to = date('Y-m-d');
        $input_dateto = date('m/d/Y');
    }

	$selected['w'][1]='checked="checked"';
    $selected['time'][3]='selected="selected"';
}
else
{
	$selected['w'][0]='checked="checked"';
	$_GET['time'] = intval( hesk_GET('time', 3) );

    switch ($_GET['time'])
    {
    	case 1:
			/* Today */
			$date_from = date('Y-m-d');
			$date_to = $date_from;
			$selected['time'][1]='selected="selected"';
            $is_all_time = 1;
        break;

    	case 2:
			/* Yesterday */
			$date_from = date('Y-m-d',mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
			$date_to = $date_from;
			$selected['time'][2]='selected="selected"';
            $is_all_time = 1;
        break;

    	case 4:
			/* Last month */
			$date_from = date('Y-m-d',mktime(0, 0, 0, date("m")-1, 1, date("Y")));
			$date_to = date('Y-m-d',mktime(0, 0, 0, date("m"), 0, date("Y")));
			$selected['time'][4]='selected="selected"';
        break;

    	case 5:
			/* Last 30 days */
			$date_from = date('Y-m-d',mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
			$date_to = date('Y-m-d');
			$selected['time'][5]='selected="selected"';
        break;

    	case 6:
			/* This week */
			list($date_from,$date_to)=dateweek(0);
            $date_to = date('Y-m-d');
			$selected['time'][6]='selected="selected"';
        break;

    	case 7:
			/* Last week */
			list($date_from,$date_to)=dateweek(-1);
			$selected['time'][7]='selected="selected"';
        break;

    	case 8:
			/* This business week */
			list($date_from,$date_to)=dateweek(0,1);
            $date_to = date('Y-m-d');
			$selected['time'][8]='selected="selected"';
        break;

    	case 9:
			/* Last business week */
			list($date_from,$date_to)=dateweek(-1,1);
			$selected['time'][9]='selected="selected"';
        break;

    	case 10:
			/* This year */
			$date_from = date('Y').'-01-01';
			$date_to = date('Y-m-d');
			$selected['time'][10]='selected="selected"';
        break;

    	case 11:
			/* Last year */
			$date_from = date('Y')-1 . '-01-01';
			$date_to = date('Y')-1 . '-12-31';
			$selected['time'][11]='selected="selected"';
        break;

    	case 12:
			/* All time */
			$date_from = hesk_getOldestDate();
			$date_to = date('Y-m-d');
			$selected['time'][12]='selected="selected"';
            $is_all_time = 1;
        break;

        default:
        	$_GET['time'] = 3;
			$selected['time'][3]='selected="selected"';
    }

}

unset($tmp);

// Start SQL statement for selecting tickets
$sql = "SELECT * FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."tickets` WHERE ";

// Some default settings
$archive = array(1=>0,2=>0);
$s_my = array(1=>1,2=>1);
$s_ot = array(1=>1,2=>1);
$s_un = array(1=>1,2=>1);

// --> TICKET CATEGORY
$category = intval( hesk_GET('category', 0) );

// Make sure user has access to this category
if ($category && hesk_okCategory($category, 0) )
{
	$sql .= " `category`='{$category}' ";
}
// No category selected, show only allowed categories
else
{
	$sql .= hesk_myCategories();
}

// Show only tagged tickets?
if ( ! empty($_GET['archive']) )
{
	$archive[1]=1;
	$sql .= " AND `archive`='1' ";
}

// Ticket owner preferences
$fid = 1;
require(HESK_PATH . 'inc/assignment_search.inc.php');

// --> TICKET STATUS
$possible_status = array(
0 => 'NEW',
1 => 'WAITING REPLY',
2 => 'REPLIED',
3 => 'RESOLVED (CLOSED)',
4 => 'IN PROGRESS',
5 => 'ON HOLD',
);

$status = $possible_status;

foreach ($status as $k => $v)
{
	if (empty($_GET['s'.$k]))
    {
    	unset($status[$k]);
    }
}

// How many statuses are we pulling out of the database?
$tmp = count($status);

// Do we need to search by status?
if ( $tmp < 6 )
{
	// If no statuses selected, show all
	if ($tmp == 0)
	{
		$status = $possible_status;
	}
	else
	{
		// Add to the SQL
		$sql .= " AND `status` IN ('" . implode("','", array_keys($status) ) . "') ";
	}
}

// --> TICKET PRIORITY
$possible_priority = array(
0 => 'CRITICAL',
1 => 'HIGH',
2 => 'MEDIUM',
3 => 'LOW',
);

$priority = $possible_priority;

foreach ($priority as $k => $v)
{
	if (empty($_GET['p'.$k]))
    {
    	unset($priority[$k]);
    }
}

// How many priorities are we pulling out of the database?
$tmp = count($priority);

// Create the SQL based on the number of priorities we need
if ($tmp == 0 || $tmp == 4)
{
	// Nothing or all selected, no need to modify the SQL code
    $priority = $possible_priority;
}
else
{
	// A custom selection of priorities
	$sql .= " AND `priority` IN ('" . implode("','", array_keys($priority) ) . "') ";
}

// Prepare variables used in search and forms
require_once(HESK_PATH . 'inc/prepare_ticket_export.inc.php');

////////////////////////////////////////////////////////////////////////////////

// Can view tickets that are unassigned or assigned to others?
$can_view_ass_others = hesk_checkPermission('can_view_ass_others',0);
$can_view_unassigned = hesk_checkPermission('can_view_unassigned',0);

// Category options
$category_options = '';
$my_cat = array();
$res2 = hesk_dbQuery("SELECT `id`, `name` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."categories` WHERE " . hesk_myCategories('id') . " ORDER BY `cat_order` ASC");
while ($row=hesk_dbFetchAssoc($res2))
{
	$my_cat[$row['id']] = hesk_msgToPlain($row['name'], 1);
	$row['name'] = (strlen($row['name']) > 50) ? substr($row['name'],0,50) . '...' : $row['name'];
	$cat_selected = ($row['id'] == $category) ? 'selected="selected"' : '';
	$category_options .= '<option value="'.$row['id'].'" '.$cat_selected.'>'.$row['name'].'</option>';
}

// Generate export file
if (isset($_GET['w']))
{
	// We'll need HH:MM:SS format for hesk_date() here
	$hesk_settings['timeformat'] = 'H:i:s';

	// Get staff names
	$admins = array();
	$result = hesk_dbQuery("SELECT `id`,`name` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."users` ORDER BY `id` ASC");
	while ($row=hesk_dbFetchAssoc($result))
	{
		$admins[$row['id']]=$row['name'];
	}

	// This will be the export directory
	$export_dir = HESK_PATH.$hesk_settings['attach_dir'].'/export/';

	// This will be the name of the export and the XML file
    $export_name = 'hesk_export_'.date('Y-m-d_H-i-s').'_'.mt_rand(10000,99999);
    $save_to = $export_dir . $export_name . '.xml';

	// Do we have the export directory?
	if ( is_dir($export_dir) || ( @mkdir($export_dir, 0777) && is_writable($export_dir) ) )
    {
		// Cleanup old files
		$files = glob($export_dir.'*', GLOB_NOSORT);
		if ( is_array($files) && count($files) )
		{
			foreach ($files as $file)
			{
				hesk_unlink($file, 86400);
			}
		}
    }
    else
    {
    	hesk_error($hesklang['ede']);
    }

	// Make sure the file can be saved and written to
	@file_put_contents($save_to, '');
	if ( ! file_exists($save_to) )
	{
		hesk_error($hesklang['eef']);
	}

	// Start generating the report message and generating the export
	$flush_me = '<br /><br />';
	$flush_me .= hesk_date() . " | {$hesklang['inite']} ";

	if ($date_from == $date_to)
	{
		$flush_me .= "(" . hesk_dateToString($date_from,0) . ")<br />\n";
	}
	else
	{
    	$flush_me .= "(" . hesk_dateToString($date_from,0) . " - " . hesk_dateToString($date_to,0) . ")<br />\n";
	}

	// Start generating file contents
    $tmp = '<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
  <AllowPNG/>
 </OfficeDocumentSettings>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>8250</WindowHeight>
  <WindowWidth>16275</WindowWidth>
  <WindowTopX>360</WindowTopX>
  <WindowTopY>90</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:CharSet="238" x:Family="Swiss" ss:Size="11"
    ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s62">
   <NumberFormat ss:Format="General Date"/>
  </Style>
  <Style ss:ID="s65">
   <NumberFormat ss:Format="[h]:mm:ss"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Sheet1">
  <Table>
';

	// Define column width
	$tmp .= '
	<Column ss:AutoFitWidth="0" ss:Width="50"/>
	<Column ss:AutoFitWidth="0" ss:Width="84" ss:Span="1"/>
	<Column ss:AutoFitWidth="0" ss:Width="110"/>
	<Column ss:AutoFitWidth="0" ss:Width="110"/>
	<Column ss:AutoFitWidth="0" ss:Width="90"/>
	<Column ss:AutoFitWidth="0" ss:Width="90"/>
	<Column ss:AutoFitWidth="0" ss:Width="87"/>
	<Column ss:AutoFitWidth="0" ss:Width="57.75"/>
	<Column ss:AutoFitWidth="0" ss:Width="57.75"/>
	<Column ss:AutoFitWidth="0" ss:Width="100"/>
	<Column ss:AutoFitWidth="0" ss:Width="100"/>
	<Column ss:AutoFitWidth="0" ss:Width="80"/>
	<Column ss:AutoFitWidth="0" ss:Width="80"/>
	';

	foreach ($hesk_settings['custom_fields'] as $k=>$v)
	{
		if ($v['use'])
		{
			$tmp .= '<Column ss:AutoFitWidth="0" ss:Width="80"/>' . "\n";
		}
	}

	// Define first row (header)
	$tmp .= '
	<Row>
	<Cell><Data ss:Type="String">#</Data></Cell>
	<Cell><Data ss:Type="String">'.$hesklang['trackID'].'</Data></Cell>
	<Cell><Data ss:Type="String">'.$hesklang['date'].'</Data></Cell>
    <Cell><Data ss:Type="String">'.$hesklang['last_update'].'</Data></Cell>
	<Cell><Data ss:Type="String">'.$hesklang['name'].'</Data></Cell>
	<Cell><Data ss:Type="String">'.$hesklang['email'].'</Data></Cell>
	<Cell><Data ss:Type="String">'.$hesklang['category'].'</Data></Cell>
	<Cell><Data ss:Type="String">'.$hesklang['priority'].'</Data></Cell>
	<Cell><Data ss:Type="String">'.$hesklang['status'].'</Data></Cell>
	<Cell><Data ss:Type="String">'.$hesklang['subject'].'</Data></Cell>
	<Cell><Data ss:Type="String">'.$hesklang['message'].'</Data></Cell>
	<Cell><Data ss:Type="String">'.$hesklang['owner'].'</Data></Cell>
	<Cell><Data ss:Type="String">'.$hesklang['ts'].'</Data></Cell>
	';

	foreach ($hesk_settings['custom_fields'] as $k=>$v)
	{
		if ($v['use'])
		{
			$tmp .= '<Cell><Data ss:Type="String">'.$v['name'].'</Data></Cell>' . "\n";
		}
	}

	$tmp .= "</Row>\n";

	// Write what we have by now into the XML file
	file_put_contents($save_to, $tmp, FILE_APPEND);
	$flush_me .= hesk_date() . " | {$hesklang['gXML']}<br />\n";

	// OK, now start dumping data and writing it into the file
    $tickets_exported = 0;
	$save_after = 100;
    $this_round = 0;
    $tmp = '';

    $result = hesk_dbQuery($sql);
	while ($ticket=hesk_dbFetchAssoc($result))
	{

		switch ($ticket['status'])
		{
			case 0:
				$ticket['status']=$hesklang['open'];
				break;
			case 1:
				$ticket['status']=$hesklang['wait_reply'];
				break;
			case 2:
				$ticket['status']=$hesklang['replied'];
				break;
			case 4:
				$ticket['status']=$hesklang['in_progress'];
				break;
			case 5:
				$ticket['status']=$hesklang['on_hold'];
				break;
			default:
				$ticket['status']=$hesklang['closed'];
		}

		switch ($ticket['priority'])
		{
			case 0:
				$ticket['priority']=$hesklang['critical'];
				break;
			case 1:
				$ticket['priority']=$hesklang['high'];
				break;
			case 2:
				$ticket['priority']=$hesklang['medium'];
				break;
			default:
				$ticket['priority']=$hesklang['low'];
		}

		$ticket['archive'] = !($ticket['archive']) ? $hesklang['no'] : $hesklang['yes'];
		$ticket['message'] = hesk_msgToPlain($ticket['message'], 1);
        $ticket['subject'] = hesk_msgToPlain($ticket['subject'], 1);
        $ticket['owner'] = isset($admins[$ticket['owner']]) ? $admins[$ticket['owner']] : '';
        $ticket['dt'] = date("Y-m-d\TH:i:s\.000", strtotime($ticket['dt']));
        $ticket['lastchange'] = date("Y-m-d\TH:i:s\.000", strtotime($ticket['lastchange']));

		// Create row for the XML file
		$tmp .= '
<Row>
<Cell><Data ss:Type="Number">'.$ticket['id'].'</Data></Cell>
<Cell><Data ss:Type="String"><![CDATA['.$ticket['trackid'].']]></Data></Cell>
<Cell ss:StyleID="s62"><Data ss:Type="DateTime">'.$ticket['dt'].'</Data></Cell>
<Cell ss:StyleID="s62"><Data ss:Type="DateTime">'.$ticket['lastchange'].'</Data></Cell>
<Cell><Data ss:Type="String"><![CDATA['.hesk_msgToPlain($ticket['name'], 1).']]></Data></Cell>
<Cell><Data ss:Type="String"><![CDATA['.$ticket['email'].']]></Data></Cell>
<Cell><Data ss:Type="String"><![CDATA['.$my_cat[$ticket['category']].']]></Data></Cell>
<Cell><Data ss:Type="String"><![CDATA['.$ticket['priority'].']]></Data></Cell>
<Cell><Data ss:Type="String"><![CDATA['.$ticket['status'].']]></Data></Cell>
<Cell><Data ss:Type="String"><![CDATA['.$ticket['subject'].']]></Data></Cell>
<Cell><Data ss:Type="String"><![CDATA['.$ticket['message'].']]></Data></Cell>
<Cell><Data ss:Type="String"><![CDATA['.$ticket['owner'].']]></Data></Cell>
<Cell><Data ss:Type="String"><![CDATA['.$ticket['time_worked'].']]></Data></Cell>
';

		// Add custom fields
		foreach ($hesk_settings['custom_fields'] as $k=>$v)
		{
			if ($v['use'])
			{
				$tmp .= '<Cell><Data ss:Type="String"><![CDATA['.hesk_msgToPlain($ticket[$k], 1).']]></Data></Cell>  ' . "\n";
			}
		}

		$tmp .= "</Row>\n";

		// Write every 100 rows into the file
		if ($this_round >= $save_after)
		{
			file_put_contents($save_to, $tmp, FILE_APPEND);
			$this_round = 0;
			$tmp = '';
			usleep(1);
		}

        $tickets_exported++;
        $this_round++;
	} // End of while loop

	// Append any remaining rows into the file
	if ($this_round > 0)
	{
		file_put_contents($save_to, $tmp, FILE_APPEND);
	}

	// If any tickets were exported, continue, otherwise cleanup
	if ($tickets_exported > 0)
	{
		// Finish the XML file
	    $tmp = '
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.3"/>
    <Footer x:Margin="0.3"/>
    <PageMargins x:Bottom="0.75" x:Left="0.7" x:Right="0.7" x:Top="0.75"/>
   </PageSetup>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>4</ActiveRow>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
 <Worksheet ss:Name="Sheet2">
  <Table ss:ExpandedColumnCount="1" ss:ExpandedRowCount="1" x:FullColumns="1"
   x:FullRows="1" ss:DefaultRowHeight="15">
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.3"/>
    <Footer x:Margin="0.3"/>
    <PageMargins x:Bottom="0.75" x:Left="0.7" x:Right="0.7" x:Top="0.75"/>
   </PageSetup>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
 <Worksheet ss:Name="Sheet3">
  <Table ss:ExpandedColumnCount="1" ss:ExpandedRowCount="1" x:FullColumns="1"
   x:FullRows="1" ss:DefaultRowHeight="15">
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.3"/>
    <Footer x:Margin="0.3"/>
    <PageMargins x:Bottom="0.75" x:Left="0.7" x:Right="0.7" x:Top="0.75"/>
   </PageSetup>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>
';
		file_put_contents($save_to, $tmp, FILE_APPEND);

		// Log how many rows we exported
		$flush_me .= hesk_date() . " | " . sprintf($hesklang['nrow'], $tickets_exported) . "<br />\n";

		// We will convert XML to Zip to save a lot of space
		$save_to_zip = $export_dir.$export_name.'.zip';

		// Log start of Zip creation
		$flush_me .= hesk_date() . " | {$hesklang['cZIP']}<br />\n";

		// Preferrably use the zip extension
	    if (extension_loaded('zip'))
	    {
		    $save_to_zip = $export_dir.$export_name.'.zip';

			$zip = new ZipArchive;
			$res = $zip->open($save_to_zip, ZipArchive::CREATE);
			if ($res === TRUE)
			{
				$zip->addFile($save_to, "{$export_name}.xml");
				$zip->close();
			}
			else
			{
				die("{$hesklang['eZIP']} <$save_to_zip>\n");
			}

	    }
		// Some servers have ZipArchive class enabled anyway - can we use it?
		elseif ( class_exists('ZipArchive') )
		{
			require(HESK_PATH . 'inc/zip/Zip.php');
			$zip = new Zip();
			$zip->addLargeFile($save_to, "{$export_name}.xml");
			$zip->finalize();
			$zip->setZipFile($save_to_zip);
		}
		// If not available, use a 3rd party Zip class included with HESK
		else
		{
			require(HESK_PATH . 'inc/zip/pclzip.lib.php');
			$zip = new PclZip($save_to_zip);
			$zip->add($save_to, PCLZIP_OPT_REMOVE_ALL_PATH);
		}

		// Delete XML, just leave the Zip archive
		hesk_unlink($save_to);

		// Echo memory peak usage
		$flush_me .= hesk_date() . " | " . sprintf($hesklang['pmem'], (@memory_get_peak_usage(true) / 1048576)) . "<br />\r\n";

		// We're done!
		$flush_me .= hesk_date() . " | {$hesklang['fZIP']}<br /><br />";
	    $flush_me .= '<a href="' . $save_to_zip . '">' . $hesklang['ch2d'] . "</a>\n";
	}
    // No tickets exported, cleanup
    else
    {
		hesk_unlink($save_to);
    }
}

/* Print header */
require_once(HESK_PATH . 'inc/header.inc.php');

/* Print main manage users page */
require_once(HESK_PATH . 'inc/show_admin_nav.inc.php');
?>

</td>
</tr>
<tr>
<td>

<?php
/* This will handle error, success and notice messages */
hesk_handle_messages();

// If an export was generated, show the link to download
if (isset($flush_me))
{
	if ($tickets_exported > 0)
	{
		hesk_show_success($flush_me);
	}
	else
	{
		hesk_show_notice($hesklang['n2ex']);
	}
}
?>

<!-- TABS -->
<div id="tab1" class="tabberlive" style="margin-top:0px">

	<ul class="tabbernav">
		<?php
		// Show a link to reports.php if user has permission to do so
		if ( hesk_checkPermission('can_run_reports',0) )
		{
			echo '<li class=""><a title="' . $hesklang['reports_tab'] . '" href="reports.php">' . $hesklang['reports_tab'] . ' [+]</a></li>';
		}
		?>
		<li class="tabberactive"><a title="<?php echo $hesklang['export']; ?>" href="javascript:void(null);" onclick="javascript:alert('<?php echo $hesklang['export_intro']; ?>')"><?php echo $hesklang['export']; ?> [?]</a></li>
	</ul>

	<div class="tabbertab">

	&nbsp;

	<!-- ** START EXPORT FORM ** -->

<form name="showt" action="export.php" method="get">

<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td width="20%" class="alignTop"><b><?php echo $hesklang['dtrg']; ?></b>: &nbsp; </td>
<td width="80%">
    <!-- START DATE -->
        <input type="radio" name="w" value="0" id="w0" <?php echo $selected['w'][0]; ?> />
		<select name="time" onclick="document.getElementById('w0').checked = true" onfocus="document.getElementById('w0').checked = true" style="margin-top:5px;margin-bottom:5px;">
			<option value="1" <?php echo $selected['time'][1]; ?>><?php echo $hesklang['r1']; ?> (<?php echo $hesklang['d'.date('w')]; ?>)</option>
			<option value="2" <?php echo $selected['time'][2]; ?>><?php echo $hesklang['r2']; ?> (<?php echo $hesklang['d'.date('w',mktime(0, 0, 0, date('m'), date('d')-1, date('Y')))]; ?>)</option>
			<option value="3" <?php echo $selected['time'][3]; ?>><?php echo $hesklang['r3']; ?> (<?php echo $hesklang['m'.date('n')]; ?>)</option>
			<option value="4" <?php echo $selected['time'][4]; ?>><?php echo $hesklang['r4']; ?> (<?php echo $hesklang['m'.date('n',mktime(0, 0, 0, date('m')-1, date('d'), date('Y')))]; ?>)</option>
			<option value="5" <?php echo $selected['time'][5]; ?>><?php echo $hesklang['r5']; ?></option>
			<option value="6" <?php echo $selected['time'][6]; ?>><?php echo $hesklang['r6']; ?></option>
			<option value="7" <?php echo $selected['time'][7]; ?>><?php echo $hesklang['r7']; ?></option>
			<option value="8" <?php echo $selected['time'][8]; ?>><?php echo $hesklang['r8']; ?></option>
			<option value="9" <?php echo $selected['time'][9]; ?>><?php echo $hesklang['r9']; ?></option>
			<option value="10" <?php echo $selected['time'][10]; ?>><?php echo $hesklang['r10']; ?> (<?php echo date('Y'); ?>)</option>
			<option value="11" <?php echo $selected['time'][11]; ?>><?php echo $hesklang['r11']; ?> (<?php echo date('Y',mktime(0, 0, 0, date('m'), date('d'), date('Y')-1)); ?>)</option>
			<option value="12" <?php echo $selected['time'][12]; ?>><?php echo $hesklang['r12']; ?></option>
		</select>

        <br />

        <input type="radio" name="w" value="1" id="w1" <?php echo $selected['w'][1]; ?> />
		<?php echo $hesklang['from']; ?> <input type="text" name="datefrom" value="<?php echo $input_datefrom; ?>" id="datefrom" class="tcal" size="10" onclick="document.getElementById('w1').checked = true" onfocus="document.getElementById('w1').checked = true;this.focus;" />
        <?php echo $hesklang['to']; ?> <input type="text" name="dateto" value="<?php echo $input_dateto; ?>" id="dateto" class="tcal" size="10" onclick="document.getElementById('w1').checked = true" onfocus="document.getElementById('w1').checked = true; this.focus;" />
    <!-- END DATE -->
</td>
</tr>

<td width="20%" class="borderTop alignTop"><b><?php echo $hesklang['status']; ?></b>: &nbsp; </td>
<td width="80%" class="borderTop">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="34%"><label><input type="checkbox" name="s0" value="1" <?php if (isset($status[0])) {echo 'checked="checked"';} ?> /> <span class="open"><?php echo $hesklang['open']; ?></span></label></td>
<td width="33%"><label><input type="checkbox" name="s2" value="1" <?php if (isset($status[2])) {echo 'checked="checked"';} ?> /> <span class="replied"><?php echo $hesklang['replied']; ?></span></label></td>
<td width="33%"><label><input type="checkbox" name="s4" value="1" <?php if (isset($status[4])) {echo 'checked="checked"';} ?> /> <span class="inprogress"><?php echo $hesklang['in_progress']; ?></span></label></td>
</tr>
<tr>
<td width="34%"><label><input type="checkbox" name="s1" value="1" <?php if (isset($status[1])) {echo 'checked="checked"';} ?> /> <span class="waitingreply"><?php echo $hesklang['wait_reply']; ?></span></label></td>
<td width="33%"><label><input type="checkbox" name="s3" value="1" <?php if (isset($status[3])) {echo 'checked="checked"';} ?> /> <span class="resolved"><?php echo $hesklang['closed']; ?></span></label></td>
<td width="33%"><label><input type="checkbox" name="s5" value="1" <?php if (isset($status[5])) {echo 'checked="checked"';} ?>  /> <span class="onhold"><?php echo $hesklang['on_hold']; ?></span></td>
</tr>
</table>

</td>
</tr>
</table>

<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td width="20%" class="borderTop alignTop"><b><?php echo $hesklang['priority']; ?></b>: &nbsp; </td>
<td width="80%" class="borderTop alignTop">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="34%"><label><input type="checkbox" name="p0" value="1" <?php if (isset($priority[0])) {echo 'checked="checked"';} ?> /> <span class="critical"><?php echo $hesklang['critical']; ?></span></label></td>
<td width="33%"><label><input type="checkbox" name="p2" value="1" <?php if (isset($priority[2])) {echo 'checked="checked"';} ?> /> <span class="medium"><?php echo $hesklang['medium']; ?></span></label></td>
<td width="33%">&nbsp;</td>
</tr>
<tr>
<td width="34%"><label><input type="checkbox" name="p1" value="1" <?php if (isset($priority[1])) {echo 'checked="checked"';} ?> /> <span class="important"><?php echo $hesklang['high']; ?></span></label></td>
<td width="33%"><label><input type="checkbox" name="p3" value="1" <?php if (isset($priority[3])) {echo 'checked="checked"';} ?> /> <span class="normal"><?php echo $hesklang['low']; ?></span></label></td>
<td width="33%">&nbsp;</td>
</tr>
</table>

</td>
</tr>

<tr>
<td class="borderTop alignTop"><b><?php echo $hesklang['show']; ?></b>: &nbsp; </td>
<td class="borderTop">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="34%" class="alignTop">
<label><input type="checkbox" name="s_my" value="1" <?php if ($s_my[1]) echo 'checked="checked"'; ?> /> <?php echo $hesklang['s_my']; ?></label>
<?php
if ($can_view_unassigned)
{
	?>
    <br />
	<label><input type="checkbox" name="s_un" value="1" <?php if ($s_un[1]) echo 'checked="checked"'; ?> /> <?php echo $hesklang['s_un']; ?></label>
	<?php
}
?>
</td>
<td width="33%" class="alignTop">
<?php
if ($can_view_ass_others)
{
	?>
	<label><input type="checkbox" name="s_ot" value="1" <?php if ($s_ot[1]) echo 'checked="checked"'; ?> /> <?php echo $hesklang['s_ot']; ?></label>
    <br />
	<?php
}
?>
<label><input type="checkbox" name="archive" value="1" <?php if ($archive[1]) echo 'checked="checked"'; ?> /> <?php echo $hesklang['disp_only_archived']; ?></label></td>
<td width="33%">&nbsp;</td>
</tr>
</table>

</td>
</tr>

<tr>
<td class="borderTop alignTop"><b><?php echo $hesklang['sort_by']; ?></b>: &nbsp; </td>
<td class="borderTop">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="34%"><label><input type="radio" name="sort" value="priority"  <?php if ($sort == 'priority') {echo 'checked="checked"';} ?> /> <?php echo $hesklang['priority']; ?></label></td>
<td width="33%"><label><input type="radio" name="sort" value="lastchange" <?php if ($sort == 'lastchange') {echo 'checked="checked"';} ?> /> <?php echo $hesklang['last_update']; ?></label></td>
<td width="33%"><label><input type="radio" name="sort" value="name" <?php if ($sort == 'name') {echo 'checked="checked"';} ?> /> <?php echo $hesklang['name']; ?></label></td>
</tr>
<tr>
<td width="34%"><label><input type="radio" name="sort" value="subject" <?php if ($sort == 'subject') {echo 'checked="checked"';} ?> /> <?php echo $hesklang['subject']; ?></label></td>
<td width="33%"><label><input type="radio" name="sort" value="status" <?php if ($sort == 'status') {echo 'checked="checked"';} ?> /> <?php echo $hesklang['status']; ?></label></td>
<td width="33%"><label><input type="radio" name="sort" value="id" <?php if ($sort == 'id') {echo 'checked="checked"';} ?> /> <?php echo $hesklang['sequentially']; ?></label></td>
</tr>
</table>

</td>
</tr>

<tr>
<td class="borderTop alignMiddle"><b><?php echo $hesklang['category']; ?></b>: &nbsp; </td>
<td class="borderTop alignMiddle">
<select name="category">
<option value="0" ><?php echo $hesklang['any_cat']; ?></option>
<?php echo $category_options; ?>
</select>
</td>
</tr>

<tr>
<td class="borderTop alignMiddle"><b><?php echo $hesklang['order']; ?></b>: &nbsp; </td>
<td class="borderTop alignMiddle">
<label><input type="radio" name="asc" value="1" <?php if ($asc) {echo 'checked="checked"';} ?> /> <?php echo $hesklang['ascending']; ?></label>
|
<label><input type="radio" name="asc" value="0" <?php if (!$asc) {echo 'checked="checked"';} ?> /> <?php echo $hesklang['descending']; ?></label></td>
</tr>

</table>

<p><input type="submit" value="<?php echo $hesklang['export_btn']; ?>" class="orangebutton" onmouseover="hesk_btn(this,'orangebuttonover');" onmouseout="hesk_btn(this,'orangebutton');" />
<input type="hidden" name="cot" value="1" /></p>

</form>

	<!-- ** END EXPORT FORM ** -->

	</div>

</div>
<!-- TABS -->

<?php
require_once(HESK_PATH . 'inc/footer.inc.php');
exit();
?>
