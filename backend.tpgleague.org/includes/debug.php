<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PHP_Debug : A simple and fast way to debug PHP code
 *
 * This  PHP debug libray offers you the ability to debug your PHP code
 * 
 * - PHP Pear integration
 * - Process time
 * - Database and query process time
 * - Dump of all type of variable in a graphical way
 * - Functionnal debug
 * - Debug queries
 * - Allow to search in all debug infos
 * - Direct links to test queries in Phpmyadmin
 * - Show globals var ($GLOBALS, $_POST, $_GET ...)
 * - Enable or disable the debug infos you want to see
 * - Check performance of chunk of php code
 * - Customize the general display of your debug info
 * - ... ( see doc for complete specification )
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Debug Tools
 * @package    PHP_Debug
 * @author     Loic Vernet, COil <qrf_coil@yahoo.fr>
 * @copyright  2003-2005 Vernet Loic
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    1.1.0
 * @link       http://phpdebug.sourceforge.net
 * @link       http://www.php-debug.com
 * @see        Pear::Var_Dump, Pear::SQL_Parser
 * @since 	   1.0BETA
 * @todo       Check TODO file or
 *             https://sourceforge.net/tracker/?group_id=95715 
 * @filesource
 */

// {{{ constants
 
/**
 * Possible version of class Debug
 */ 
define('DBG_VERSION_STANDALONE', 0);
define('DBG_VERSION_PEAR',       1);
define('DBG_VERSION_DEFAULT',    DBG_VERSION_STANDALONE);
define('DBG_VERSION',            DBG_VERSION_PEAR);
define('DBG_RELEASE',            'V1.1.0');

// }}}
// {{{ includes

/**
 * Only include Pear libraries for Pear version
 */
if (DBG_VERSION == DBG_VERSION_PEAR) {
	/** 
	 * Include Pear Library
	 */
	include_once('PEAR.php');
	
	/** 
	 * Include Pear::Var_Dump Library
	 */
	include_once('Var_Dump.php');

	/** 
	 * Include Pear::SQL_Parser Library
	 */
	include_once('SQL/Parser.php');
}

// }}}
// {{{ constants

/**
 * Eventual external constants
 */
if (!defined('STR_N')) 
    define('STR_N', "");

if (!defined('CR')) 
    define('CR', "\r\n");

/**
 * DBG_MODE Constants, define the different available debug modes.
 *
 * Here are the available modes :
 * - DBG_MODE_OFF : Debug mode is OFF
 * - DBG_MODE_USERPERF : Base debug mode,
 * - DBG_MODE_QUERY : DBG_MODE_USERPERF + queries
 * - DBG_MODE_QUERYTEMP : DBG_MODE_QUERY + included files
 * - DBG_MODE_FULL : All available debug infos ( including $GLOBALS array that is quiet big )
 * - DBG_MODE_AUTO : Mode auto take the mode of Debug Object
 */
define('DBG_MODE_OFF',       0);
define('DBG_MODE_USERPERF',  1);
define('DBG_MODE_QUERY',     2);
define('DBG_MODE_QUERYTEMP', 3);
define('DBG_MODE_FULL',      4);
define('DBG_MODE_AUTO',      5);
define('DBG_MODE_DEFAULT',   DBG_MODE_QUERYTEMP);

/**
 * This is a constant for the credits. For me :p
 */
define('DBG_CREDITS', '== PHP_Debug ['. DBG_RELEASE .'] | By COil (2005) | '.
       '<a href="mailto:qrf_coil@yahoo.fr">qrf_coil@yahoo.fr</a> | '.
       '<a href="http://phpdebug.sourceforge.net/">PHP_Debug Project Home</a>');

/**
 * These are constant for DumpArr() and DumpObj() functions.
 * 
 * - DUMP_ARR_DISP : Tell the functions to display the debug info.
 * - DUMP_ARR_STR : Tell the fonction to return the debug info as a string
 * - DBG_ARR_TABNAME : Default name of Array
 * - DBG_ARR_OBJNAME : Default name of Object
 */
define('DUMP_ARR_DISP',    1);
define('DUMP_ARR_STR',     2);
define('DUMP_ARR_TABNAME', 'Array');
define('DUMP_ARR_OBJNAME', 'Object');

/**
 * These are constants to define Super array environment variables
 */ 
define('DBG_GLOBAL_GET',     0);
define('DBG_GLOBAL_POST',    1);
define('DBG_GLOBAL_FILES',   2);
define('DBG_GLOBAL_COOKIE',  3);
define('DBG_GLOBAL_REQUEST', 4);
define('DBG_GLOBAL_SESSION', 5);
define('DBG_GLOBAL_GLOBALS', 6);

// }}}

/**
 * Global var for references to available debug objects
 */ 
$DEBUG_OBJECT = array();

/**
 * Debug : Main class that manage debug and debugline objects
 * 
 * @category   Debug Tools
 * @package    PHP_Debug
 * @author 	   COil, Loic Vernet <qrf_coil@yahoo.fr>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    V1.1.0
 * @link       http://pear.php.net/package/Easy_Debug
 * @see        Pear::Var_Dump, Pear::SQL_Parser
 * @since      Class available since Release 1.0BETA
 * 
 */
class Debug
{
	/**
	 * ID of debug object (0 = First object)
	 * 
	 * @see $DEBUG_OBJECT
	 * @since 24 Dec 2003
	 * @access public
	 */
	var $DebugObjectID = null;
	
	/**
	 * Debug Mode 
	 *
	 * @var integer
	 * @access public
	 * @see DBG_MODE constants.
	 */
	var $DebugMode = DBG_MODE_DEFAULT;

	/**
	 * This is the array where debug line are.
	 *
	 * @var array $_DebugBuffer
	 * @access private
	 * @see DebugLine
	 */
	var $_DebugBuffer = array();

	/**
	 * Enable or disable Credits in debug infos.
	 *
	 * @var integer $DisableCredits
	 * @access public
	 * @see DebugLine
	 */
	var $DisableCredits = false;

	/**
	 * Process perf status, 1 = a process is being running, 0 = no activity
	 * 
	 * @var String	$_ProcessPerfStatus
	 * @access private
	 */
	var $_ProcessPerfStatus = false;

	/**
	 * General debug start time
	 * 
	 * @var integer $_ProcessPerfStart
	 * @access private
	 */
	var $_ProcessPerfStartGen = 0; 		// Global Start Time
	
	/**
	 * Debug Start time
	 * 
	 * @var integer $_ProcessPerfStart
	 * @access private
	 */
	var $_ProcessPerfStart = 0; 		// Local Start Time

	/**
	 * Debug End time
	 * 
	 * @var integer $_ProcessPerfEnd
	 * @access private
	 */
	var $_ProcessPerfEnd = 0;

	/**
	 * Global database process time
	 * 
	 * @var integer $_DataPerfTotal
	 * @access private
	 */
	var $_DataPerfTotal = 0;

	/**
	 * Number of performed queries
	 * 
	 * @var integer $_DataPerfQry
	 * @access private
	 */
	var $_DataPerfQry = 0;

	/**
	 * Enable or disable, included and required files
	 * 
	 * @var boolean $ShowTemplates
	 * @access public
	 */ 
	var $ShowTemplates = true;

	/**
	 * Enable or disable, pattern removing in included files
	 * 
	 * @var boolean $RemoveTemplatesPattern
	 * @access public
	 */
	var $RemoveTemplatesPattern = false;

	/**
	 * Pattern list to remove in the display of included files
 	 * 
	 * @var boolean $RemoveTemplatesPattern
	 * @access private
	 */ 
	var $_TemplatesPattern = array();

	/**
	 * Enable or disable $globals var in debug
	 *   
	 * @var boolean $ShowGlobals
	 * @access public
	 */	
	var $ShowGlobals = false;

	/** 
	 * Enable or disable search in debug 
	 * 
	 * @var boolean $EnableSearch
	 * @access public
	 */	
	var $EnableSearch = true;

	/** 
	 * Enable or disable the use of $_REQUEST array instead of 
     * $_POST + _$GET + $_COOKIE + $_FILES
	 * 
	 * @var boolean $UseRequestArr
	 * @access public
	 */	
	var $UseRequestArr = false;
	
	/** 
	 * View Source script path
	 * 
	 * @var string $ViewSourceScriptPath, default : Current directory
	 * @access public
	 */	
	var $ViewSourceScriptPath = '.';
	
	/** 
	 * View Source script path
	 * 
	 * @var string $ViewSourceScripName
	 * @access public
	 */		
	var $ViewSourceScriptName = 'source.php';

	/** 
	 * Lables Color for DebugType : $DebugType => Color Code of text
	 * 
	 * @var array $DebugTypeLabel
	 * @access public
	 */
	var $DebugTypeLabel = array(DBGLINE_ANY	 		=> 'ALL', 
								DBGLINE_STD 		=> 'Standart', 
								DBGLINE_QUERY 		=> 'Query', 
								DBGLINE_QUERY_REL	=> 'Database Related',
								DBGLINE_ENV 		=> 'Environment',
								DBGLINE_CURRENTFILE => 'Current File',
								DBGLINE_APPERROR 	=> 'Application Error',
								DBGLINE_CREDITS 	=> 'Credits',
								DBGLINE_SEARCH 		=> 'Search mode',
								DBGLINE_OBJECT 		=> 'Object Debug',
								DBGLINE_PROCESSPERF => 'Performance analysis',
								DBGLINE_TEMPLATES 	=> 'Included and required files',
								DBGLINE_PAGEACTION 	=> 'Page main action',
								DBGLINE_ARRAY 		=> 'Array Debug',
								DBGLINE_SQLPARSE 	=> 'SQL Parse error');

	/** 
	 * Color for DebugType : $DebugType => Color Code of text
	 * 
	 * @var array $CellColors
	 * @access public
	 */
	var $CellColors = array(DBGLINE_STD 		=> '#000000', 
							DBGLINE_QUERY 		=> '#FF8C00', 
							DBGLINE_QUERY_REL 	=> '#228B22',
							DBGLINE_ENV 		=> '#FF0000',
							DBGLINE_CURRENTFILE	=> '#000000',
							DBGLINE_APPERROR 	=> '#FF0000',
							DBGLINE_CREDITS 	=> '#000000',
							DBGLINE_SEARCH 		=> '#000000',
							DBGLINE_OBJECT 		=> '#000000',
							DBGLINE_PROCESSPERF => '#000000',
							DBGLINE_TEMPLATES 	=> '#000080',
							DBGLINE_PAGEACTION 	=> '#708090',
							DBGLINE_ARRAY 		=> '#000000',
							DBGLINE_SQLPARSE 	=> '#228B22');

	/**
	* Array for other class color to generate
	* 
	* @since 26 Dec 2003
	*/ 
	var $_StyleClassColor = array('IncFiles' 			=> '#000080', 
	 							  'HighLightKeyWord'	=> '#FFA500', 
								  'SQLParseError'    	=> '#800000');
								
    /**
     * HTML Start String
     * 
     * Start string of HTML layout
     * 
     * @var string $HtmlTableStart
     * @access public
     */ 
    var $HtmlTableStart = '
    <div>
    <br /><br />
    <a name="debug" id="debug" />
    <table cellspacing="0" cellpadding="1" width="100%" class="dbgMainTable">';
        
    /**
     * HTML end string to close HTML display for debug layout
     * 
     * @var string $HtmlTableEnd
     * @access public
     */ 
    var $HtmlTableEnd = '
    </table>
    </div>';
                                                                
	/**
	* Base Internal Style sheet
	* 
	* @since 26 Dec 2003
	*/ 
	var $_BaseStyle = array(0 => '.dbgBold      { font-weight: bold; }', 
						    1 => '.dbgNormal    { font-weight: normal; color: #000000; }',
                            2 => '.dbgMainTable { border:1px solid #000080;  background-color:#F8F8FF; border-bottom: 0px; }',
                            3 => '.dbgTD        { color:black; font-family:courier new,arial; font-size:small; border-bottom: 1px solid #000080; }',
                            4 => '.dbgHR        { border: 0px; height: 1px; background-color: #000080; }'
                            );

    /**
    * Variable to tell if we must generate a XHML 1.0 Strict output
    * 
    * @since 13 May 2005
    */ 
    var $genXHTMLOutput = false; 

	/**
	 * Bold style for DebugType : $DebugType => Bold Style
	 * 
	 * @var array $CellBoldStatus
	 * @access public
	 */
	var $CellBoldStatus = array(DBGLINE_STD 		=> false,
								DBGLINE_QUERY 		=> true,
								DBGLINE_QUERY_REL 	=> false,
								DBGLINE_ENV 		=> false,
								DBGLINE_CURRENTFILE	=> true,
								DBGLINE_APPERROR 	=> true,
								DBGLINE_CREDITS 	=> true,
								DBGLINE_SEARCH 		=> false,
								DBGLINE_OBJECT 		=> false,
								DBGLINE_PROCESSPERF => false,
								DBGLINE_TEMPLATES 	=> false,
								DBGLINE_PAGEACTION 	=> true,
								DBGLINE_ARRAY 		=> false,
								DBGLINE_SQLPARSE 	=> true);

	/**
	 * Bold style for DebugType : $DebugType => Bold Style
	 * 
	 * @var array $CellBoldStatus
	 * @access public
	 */
	var $DisplayTypeInSearch = array(DBGLINE_STD 			=> false,
									 DBGLINE_QUERY 			=> false,
									 DBGLINE_QUERY_REL 		=> false,
									 DBGLINE_ENV 			=> false,
									 DBGLINE_CURRENTFILE	=> true,
									 DBGLINE_APPERROR 		=> false,
									 DBGLINE_CREDITS 		=> true,
									 DBGLINE_SEARCH 		=> true,
									 DBGLINE_OBJECT 		=> false,
									 DBGLINE_PROCESSPERF 	=> true,
									 DBGLINE_TEMPLATES 		=> false,
									 DBGLINE_PAGEACTION 	=> false,
									 DBGLINE_ARRAY 			=> false,
									 DBGLINE_SQLPARSE 		=> false);

	/** 
	 * Enable or not PhpMyAdmin direct links for queries
	 * 
	 * @var boolean $EnablePhpMyAdminLinks
	 * @access public
	 */
	var $EnablePhpMyAdminLinks = true;

	/** 
	 * Base URL of phpmyadmin	
	 * 
	 * @var string $PhpMyAdminUrl	
	 * @access public
	 */
	var $PhpMyAdminUrl = 'http://127.0.0.1/mysql';

	/** 
	 * Name of database that we are working on
	 * 
	 * @var string $CurrentDatabase	
	 * @access public
	 */
	var $DatabaseName = 'mysql';
		
	/** 
	 * Max Length of query to display on a single line
	 * 
	 * @var string $maxQueryLength
	 * @access public
	 */
	var $maxQueryLineLength = 120;

	/** 
	 * Pear::SQL_Parser object (If applicable)
	 * 
	 * @var Object $SQLParser
	 * @access private
	 */
	var $_SQLParser = null;

   	/**
	 * Debug() : Constructor of Debug object
	 *
	 * Set debugmode, credits line and search line are added at creation
	 * if they are activated.
	 * 
	 * @param integer	$debugmode
	 *
	 * @return mixed 	Debug Object
	 *
	 * @see Debug()
	 * @since 17 Oct 2003
	 * @access public
	 */	 	
	function Debug($DebugMode = DBG_MODE_DEFAULT)
	{		
		global $DEBUG_OBJECT;

		if ($DebugMode == DBG_MODE_OFF) {
			$this->DebugMode = DBG_MODE_OFF;
			return;
		}

		$this->DebugMode = $DebugMode;
		$this->_ProcessPerfStartGen = $this->getMicroTime(microtime());

		// Set DebugObjectID
		$this->DebugObjectID = (!empty($DEBUG_OBJECT) ? count($DEBUG_OBJECT) : 0);

		// Fix reference and ID of debugobject
		$this->_setDebugObjectRef($this);
		
		// Credits line
		if ($this->DisableCredits == false)
			$this->addDebug(DBG_CREDITS, DBGLINE_CREDITS);
		
		// Search line
		if ($this->EnableSearch == true)
			$this->addDebug(STR_N, DBGLINE_SEARCH);
            
		// SQL Parser if Pear is used
        if (DBG_VERSION == DBG_VERSION_PEAR)
			$this->_SQLParser = new SQL_Parser();
	}

	/**
	 * _setDebugObjectRef() : Set object ref in global var $DEBUG_OBJECT
	 * 
	 * @since 24 Dec 2003
	 * @access private
	 */ 
	function _setDebugObjectRef(& $DebugObject)
	{
		global $DEBUG_OBJECT;
		$DEBUG_OBJECT[$this->DebugObjectID] = & $DebugObject;
	}
	
	/**
	 * getDebugMode() : Return current debug mode
	 *  
	 * @see $DebugMode
	 * @since 14 Nov 2003
	 * @access public
	 */ 
	function getDebugMode()
	{
		return($this->DebugMode);
	}

	/**
	 * setDebugMode() : Set debug mode of Debug Object
	 *  
	 * @param integer	$debugmode
	 * 
	 * @see $DebugMode
	 * @since 14 Nov 2003
	 * @access public
	 */ 
	function setDebugMode($debugmode)
	{
		$this->DebugMode = $debugmode;
	}

   	/**
	 * getColorCodeType() : Retrieve color code of the debug cell
	 *
	 * @return string
	 * 
	 * @see CellColors
	 * @since 25 Oct 2003
	 * @access public
	 */
	function getColorCodeType($DebugLineType)
	{		 
		return $this->CellColors[$DebugLineType];
	}

   	/**
	 * getColorCodeClass() : Retrieve color code class from a color
	 *
	 * @return string
	 * 
	 * @see CellColors
	 * @since 25 Oct 2003
	 * @access public
	 */
	function getColorCodeClass($DebugLineType)
	{		 
		return 'style'. substr($this->getColorCodeType($DebugLineType), 1);
	}
	
   	/**
	 * setColorCodeType() : Set color code of the debug cell type
	 *
	 * @param Integer	$DebugLineType	Type of debug line
	 * @param String	$Color			Color of cell
	 * 
	 * @since 18 Dec 2003
	 * @access public
	 */
	function setColorCodeType($DebugLineType, $color)
	{		 
		$this->CellColors[$DebugLineType] = $color;
	}		

   	/**
	 * getBoldCodeType() : Retrieve Bold cell status of the debug cell
	 *
	 * @return boolean
	 * 
	 * @since 25 Oct 2003
	 * @access public
	 */
	function getBoldCodeType($DebugLineType)
	{
		return $this->CellBoldStatus[$DebugLineType];
	}

   	/**
	 * setBoldCodeType() : Set Bold cell status of a debug type
	 *
	 * @param Integer	$DebugLineType	Type of debug line
	 * @param boolean	$BoldStatus		Status of cell true or false
	 * 
	 * @since 25 Oct 2003
	 * @access public
	 */
	function setBoldCodeType($DebugLineType, $BoldStatus)
	{
		$this->CellBoldStatus[$DebugLineType] = $BoldStatus;
	}

   	/**
	 * getTypeInSearch() : Retrieve if debug type is displayed in search mode
	 *
	 * @return boolean
	 * 
	 * @since 25 Oct 2003
	 * @access public
	 */
	function getTypeInSearch($DebugLineType)
	{
		return $this->DisplayTypeInSearch[$DebugLineType];
	}

   	/**
	 * setTypeInSearch() : Set if debug type is displayed in search mode
	 *
	 * @param Integer	$DebugLineType	Type of debug line
	 * @param boolean	$BoldStatus		Status of cell true or false
	 * 
	 * @since 25 Oct 2003
	 * @access public
	 */
	function setTypeInSearch($DebugLineType, $Status)
	{
		$this->DisplayTypeInSearch[$DebugLineType] = $Status;
	}

   	/**
	 * getPhpMyAdminUrl() : Get the url of PhpmyAdmin
	 *
	 * @see PhpMyAdminUrl
	 * @since 14 Nov 2003
	 * @access public
	 */
	function getPhpMyAdminUrl()
	{			
		return $this->PhpMyAdminUrl;
	}

   	/**
	 * setPhpMyAdminUrl() : Set the url of PhpmyAdmin
	 *
	 * @param string URL OF phpmyadmin
	 * 
	 * @see PhpMyAdminUrl
	 * @since 14 Nov 2003
	 * @access public
	 */
	function setPhpMyAdminUrl($PhpMyadminUrl)
	{			
		$this->PhpMyAdminUrl = $PhpMyadminUrl;
	}

   	/**
	 * getPhpMyAdminStatus() : Get PhpmyAdmin status
	 *
	 * @see EnablePhpMyAdminLinks
	 * @since 25 Dec 2003
	 * @access public
	 */
	function getPhpMyAdminStatus()
	{			
		return $this->EnablePhpMyAdminLinks;
	}

   	/**
	 * setPhpMyAdminStatus($status) : Set status of phpmyadmin
	 *
	 * @param boolean True or false
	 * 
	 * @see EnablePhpMyAdminLinks
	 * @since 25 Dec 2003
	 * @access public
	 */
	function setPhpMyAdminStatus($status)
	{			
		$this->EnablePhpMyAdminLinks = $status;
	}

   	/**
	 * getDatabaseName() : Get the name of database
	 *
	 * @see DatabaseName
	 * @since 25 Dec 2003
	 * @access public
	 */
	function getDatabaseName()
	{			
		return $this->DatabaseName;
	}

   	/**
	 * setDatabaseName() : Set database name for phpmyadmin links
	 *
	 * @param string Name of database
	 * 
	 * @see DatabaseName
	 * @since 25 Dec 2003
	 * @access public
	 */
	function setDatabaseName($DataBaseName)
	{			
		$this->DatabaseName = $DataBaseName;
	}

	/**
	 * getMaxQueryLineLength() : Get max query line length
	 * 
	 * @see maxQueryLineLength
	 * @since 26 Dec 2003
	 * @access public
	 */ 	
	function getMaxQueryLineLength()
	{
		return $this->maxQueryLineLength;
	}
	
	/**
	 * setMaxQueryLineLength() : Set max query line length
	 * 
	 * @see maxQueryLineLength
	 * @since 26 Dec 2003
	 * @access public
	 */ 	
	function setMaxQueryLineLength($length)
	{
		$this->maxQueryLineLength = $length;
	}

	/**
	 * getMicroTime() : Return micotime from a timestamp
	 *   
	 * @param $time 	Timestamp to retrieve micro time
	 * @return numeric 	Micotime of timestamp param
	 * 
 	 * @see $DebugMode
	 * @since 14 Nov 2003
	 * @static
	 * @access public
	 */ 
	function getMicroTime($time)
	{ 	
		list($usec, $sec) = explode(' ', $time);
		return ((float)$usec + (float)$sec); 
	}

	/**
	 * getElapsedTime() : get elapsed time between 2 timestamp
	 *   
	 * @param $timeStart 	Start time ref
	 * @param $timeEnd 		End time ref
	 * @return numeric difference between the two time ref
	 * 
	 * @see getProcessTime()
	 * @since 20 Oct 2003
	 * @static
	 * @access public
	 */ 
	function getElapsedTime($timeStart, $timeEnd)
	{			
		return round($timeEnd - $timeStart, 4);
	}
	
	/**
	 * getProcessTime() : Get global process time
	 * 
	 * @return	numeric		Elapsed time between the start and end time
	 * 
	 * @see getElapsedTime()
	 * @since 20 Oct 2003
	 * @access public
	 */ 
	function getProcessTime()
	{
		return ($this->getElapsedTime($this->_ProcessPerfStartGen, 
                                      $this->_ProcessPerfEnd));
	}

	/**
	 * _StopProcessTime() : Fix the end time of process
	 * 
	 * @since 17 Novt 2003
	 * @access private
	 */ 
	function _StopProcessTime()
	{
		$this->_ProcessPerfEnd = $this->getMicroTime(microtime());
	}
	
	/**
	 * DumpArr() : Display all content of an array
	 * 
	 * Mode DUMP_ARR_DISP display the array
	 * Mode DUMP_ARR_STR return the infos as a string
	 * 
	 * @param 	array 	 	$arr		array	Array to debug
	 * @param 	string	 	$varname	Name of the variable
	 * @param	integer 	$mode		Mode of function
	 * @return 	mixed 					Nothing or string depending on the mode
	 * 
	 * @since 20 Oct 2003
	 * @static
	 * @access public
	 */ 
	function DumpArr($arr, $varname = DUMP_ARR_TABNAME, $mode = DUMP_ARR_DISP)
	{
		ob_start();
		print_r($arr);		
		$dbg_arrbuffer = htmlentities(ob_get_contents());
		ob_end_clean();
		
		$dbg_arrbuffer = "<br /><pre><b>$varname</b> :". 
                         CR. $dbg_arrbuffer. '</pre>';

		switch ($mode) {
			default:

			case DUMP_ARR_DISP:
				print($dbg_arrbuffer);
				break;

			case DUMP_ARR_STR:
				return($dbg_arrbuffer);
				break;
		}
	}

	/**
	 * DumpObj() : Debug an object or array with Var_Dump pear package
	 * 
	 * ( Not useable with standalone version )
	 * Mode DUMP_ARR_DISP display the array
	 * Mode DUMP_ARR_STR return the infos as a string
	 * 
	 * @param 	array 	 	$obj		Object to debug
	 * @param 	string	 	$varname	Name of the variable
	 * @param	integer 	$mode		Mode of function
	 * @return 	mixed 					Nothing or string depending on the mode
	 * 
	 * @since 10 Nov 2003
	 * @static
	 * @access public
	 */ 
	function DumpObj($obj, $varname = DUMP_ARR_OBJNAME, $mode = DUMP_ARR_DISP)
	{
		// Check Pear Activation
		if (DBG_VERSION == DBG_VERSION_STANDALONE) 
			return Debug::DumpArr($obj, $varname, $mode);
	
		ob_start();
		Var_Dump::display($obj);
		$dbg_arrbuffer = ob_get_contents();
		ob_end_clean();	

		if (empty($varname))
			$varname = DUMP_ARR_OBJNAME;

		$dbg_arrbuffer = "<br /><pre><b>$varname</b> :". 
                          CR. $dbg_arrbuffer. '</pre>';

		switch ($mode) {
			default:
			
			case DUMP_ARR_DISP:
				print($dbg_arrbuffer);
				break;

			case DUMP_ARR_STR:
				return($dbg_arrbuffer);
				break;
		}
	}

	/**
	 * addDebug() : Build a new debug line info.
	 * 
	 * If $str is a String or an object we switch automatically to the corresponding
	 * debug info type. If debug mode is OFF does not do anything and return.
	 * Debug line is build, then it is added in the DebugLine array.
	 * 
	 * @param 	string		$str		Debug string/object
	 * @param 	integer 	$typeDebug	Debug type of line 
     *                                  (Optional, Default = DBGLINE_STD)
	 * @param	string 		$file		File of debug info 
     *                                  (Optional, Default = "")
	 * @param	string 		$line		Line of debug info 
     *                                  (Optional, Default = "")
	 * @param 	string 		$title		Title of variable if applicable 
     *                                  (Optional, Default = "")
	 * 
	 * @since 10 Nov 2003
	 * @access public
	 */ 
	function addDebug($str, $typeDebug = DBGLINE_STD, $file = STR_N, $line = STR_N, 
                      $title = STR_N)
	{
		if ($this->DebugMode == DBG_MODE_OFF)
			return;

		// If argument is an array change debug type
		if (is_array($str)  && $typeDebug == DBGLINE_STD)
			$typeDebug = DBGLINE_ARRAY;

		// If argument is an object change debug type
		if (is_object($str) && $typeDebug == DBGLINE_STD)
			$typeDebug = DBGLINE_OBJECT;

		// Add Object
		$this->_DebugBuffer[] = new DebugLine($this->DebugObjectID, $str, 
                                              $typeDebug, $file , $line, $title);
	}

	/**
	 * DebugPerf() : Get process time and stats about database processing.
	 * 
	 * If $processtype is DBG_PERF_QRY then a query has been run, otherwise it
	 * is another database process. The start and end time is computed, and the
	 * global time is updated.
	 * 
	 * @param 	integer 	$processtype	Type of database debug query or 
     *                                      database related.
	 * 
	 * @since 20 Oct 2003
	 * @access public
	 */ 
	function DebugPerf($processtype = DBGLINE_QUERY)
	{
		if ($this->DebugMode == DBG_MODE_OFF)
			return;

		// Lang
		$txtPHP = 'PHP';
		$txtSQL = 'SQL';				
		$txtSECOND = 's';

		switch ($this->_ProcessPerfStatus) {

			// Start Timer
			default:

			case false:
				$this->_ProcessPerfStart = $this->getMicroTime(microtime());
				$this->_ProcessPerfStatus = true;
				
				// Additional processing depending of dataperf type request				
				switch ($processtype) {

					case(DBGLINE_QUERY):
						$this->_DataPerfQry++;
						break;				

					default:
						break;
				}
				
				break;
			
			// Stop Timer and add to database perf total
			case true;
				$this->_ProcessPerfEnd = $this->getMicroTime(microtime());
				$qry_time = $this->getElapsedTime($this->_ProcessPerfStart, 
                                                  $this->_ProcessPerfEnd);

				$this->_ProcessPerfStart = $this->_ProcessPerfEnd = 0;
				$this->_ProcessPerfStatus = false;
				
				// Additional processing depending of dataperf type request
				switch ($processtype) {

					default:

					case(DBGLINE_STD);
						$this->_DebugBuffer[$this->_getLastDebugLineID($processtype)]->setProcessTime(" <b>"  . DebugLine::colorizeFont("[ $txtPHP : ". $qry_time ."$txtSECOND ]", '#000000'). "</b>");
						break;

					case(DBGLINE_QUERY_REL):						

					case(DBGLINE_QUERY):
						//Now set the Time for the query in the DebugLine info
						$this->_DebugBuffer[$this->_getLastDebugLineID($processtype)]->setProcessTime(" <b>" .DebugLine::colorizeFont("[ $txtSQL+$txtPHP : ". $qry_time ."$txtSECOND ]", '#000000') ."</b>");

						// Global database perf
						$this->_DataPerfTotal += $qry_time;
						break;
				}
				break;
		}
	}

	/**
	 * CancelPerf() : Cancel a process time monitoring, error or misc exception
	 * 
	 * @param Integer	$processtype	Type of the process to cancel
	 * 
	 * @since 13 Dec 2003
	 * @access public
	 */ 
	function CancelPerf($processtype)
	{
		if ($this->DebugMode == DBG_MODE_OFF)
			return;

		$this->_ProcessPerfStart = $this->_ProcessPerfEnd = 0;
		$this->_ProcessPerfStatus = false;
		
		switch ($processtype) {
			case(DBGLINE_QUERY):
				$this->_DataPerfQry--;
				break;				

			default:
				break;
		}
	}
	
	/**
	 * getLastDebugLineID : Retrieve the ID of last debugline type in 
     *                      _DebugBuffer array
	 * 
	 * @param integer 	$debugtype		Type of debug we want to get the last 
     *                                  index
	 * 
	 * @see DebugPerf(), _DebugBuffer
  	 * @since 20 Nov 2003
	 * @access private
	 */ 
	function _getLastDebugLineID($debugtype)
	{
		$tmparr = $this->_DebugBuffer;
		krsort($tmparr);

		foreach ( $tmparr as $lkey => $lvalue )
		{
			if ($lvalue->DebugType == $debugtype)
				return $lkey;
		}
	}

	/**
	 * _IncludeRequiredFiles() : Build debug line with all included or required
     *                           files for current file.
	 * 
	 * Use the get_required_files() function, then build the formatted string with
	 * links to edit and to view source of each files. Debug info line is added in
	 * current debug object.
	 * 
	 * @since 20 Oct 2003
	 * @access private
	 */ 
	function _IncludeRequiredFiles()
	{
		// Lang
		$txtViewSource = 'View Source';
		$txtEditSource = 'Edit';
		$txtIncRecFiles = 'Included/Required files';

		$l_reqfiles = get_required_files();		
		$l_strinc = "<b>== $txtIncRecFiles (". count($l_reqfiles). ') :</b><br />'. CR;

		foreach( $l_reqfiles as $f_file ) {
			$view_source_link = $edit_link = $f_file;

			// Pattern deletion
			if ($this->RemoveTemplatesPattern == true && count($this->_TemplatesPattern))
				$f_file = strtr($f_file, $this->_TemplatesPattern);

			$view_source_link = ' [ <a href="'. $_SERVER['SCRIPT_NAME'] . 
                                '?highlight">'. $txtViewSource. '</a> ]';

			$edit_link = ' [ <a href="'. $edit_link. '">'. $txtEditSource. '</a> ]';

			$l_strinc .= $f_file. '<br />'. CR;
		}

		$this->addDebug($l_strinc, DBGLINE_TEMPLATES);
	}

	/**
	 * addRequiredFilesPattern() : Add a remove pattern to remove pattern array.
	 * 
	 * @param string 	$pattern	Pattern to add
	 * 
	 * @since 20 Oct 2003
	 * @access public
	 */ 
	function addRequiredFilesPattern($pattern, $replace_str = STR_N)
	{
		if ($this->DebugMode == DBG_MODE_OFF)
			return;

		$this->_TemplatesPattern[$pattern] = $replace_str;
	}

	/**
	 * delRequiredFilesPattern() : Del a remove pattern from remove pattern array.
	 * 
	 * @param string 	$pattern	Pattern to remove
	 * 
	 * @since 20 Oct 2003
	 * @access public
	 */ 
	function delRequiredFilesPattern($pattern)
	{
		if ($this->DebugMode == DBG_MODE_OFF)
			return;

		unset($this->_TemplatesPattern[$pattern]);
	}

	/**
	 * addSuperArray() : Add a super array to the debug informations
	 * 
	 * @see DBG_GLOBAL, DebugDisplay()
	 * @since 12 Dec 2003
	 * @access private
	 */ 
	function _addSuperArray($SuperArrayType)	
	{
		// Lang
		$txtVariable   = "Var";
		$txtNoVariable = "NO VARIABLE";
		$NoVariable    =  " -- $txtNoVariable -- ";

		switch ($SuperArrayType) {

			case(DBG_GLOBAL_GET):
				$SuperArray = $_GET;
				$ArrayTitle = '_GET';
				$Title = "$ArrayTitle $txtVariable";
				break;

			case(DBG_GLOBAL_POST):
				$SuperArray = $_POST;
				$ArrayTitle = '_POST';
				$Title = "$ArrayTitle $txtVariable";
				break;

			case(DBG_GLOBAL_FILES):
				$SuperArray = $_FILES;
				$ArrayTitle = '_FILES';
				$Title = "$ArrayTitle $txtVariable";
				break;

			case(DBG_GLOBAL_COOKIE):
				$SuperArray = $_COOKIE;
				$ArrayTitle = '_COOKIE';
				$Title = "$ArrayTitle $txtVariable";
				break;

			case(DBG_GLOBAL_REQUEST):
				$SuperArray = $_REQUEST;
				$ArrayTitle = '_REQUEST';
				$Title = "$ArrayTitle $txtVariable (_GET + _POST + _FILES + _COOKIE)";
				break;

			case(DBG_GLOBAL_SESSION):
				$SuperArray = $_SESSION;
				$ArrayTitle = '_SESSION';
				$Title = "$ArrayTitle $txtVariable";
				break;
			
			case(DBG_GLOBAL_GLOBALS):
				$SuperArray = $GLOBALS;
				$ArrayTitle = 'GLOBALS';
				$Title = "$ArrayTitle $txtVariable";
				break;

			default:
				break;
		}
	
		$SectionBasetitle = "<b>== $Title (". count($SuperArray). ') :';

		if (count($SuperArray))
			$this->addDebug($SectionBasetitle. '</b>'. 
            $this->DumpArr($SuperArray, $ArrayTitle, DUMP_ARR_STR), DBGLINE_ENV);
		else
			$this->addDebug($SectionBasetitle. "$NoVariable</b>", DBGLINE_ENV);
	}	

	/**
	 * _addProcessTime() : Add the process time information to the debug infos
	 * 
	 * @see DBG_GLOBAL, DebugDisplay()
	 * @since 12 Dec 2003
	 * @access private
	 */ 
	function _addProcessTime()
	{
		// Lang
		$txtExecutionTime = 'Execution Time Global';
		$txtPHP           = 'PHP';
		$txtSQL           = 'SQL';				
		$txtSECOND        = 's';
		$txtOneQry        = 'Query';
		$txtMultQry       = 'Queries';
		$txtQuery         = $this->_DataPerfQry > 1 ? $txtMultQry : $txtOneQry;

		// Performance Debug
		$ProcessTime = $this->getProcessTime();
		$php_time = $ProcessTime - $this->_DataPerfTotal;
		$sql_time = $this->_DataPerfTotal;
	
		$php_percent = round(($php_time / $ProcessTime) * 100, 2);
		$sql_percent = round(($sql_time / $ProcessTime) * 100, 2);								
	
		$this->addDebug("<b>== $txtExecutionTime : " .
				 $ProcessTime . "$txtSECOND [ $txtPHP, ". $php_time ."$txtSECOND, ". 
                 $php_percent  .'% ] - '. "[ $txtSQL, ". $sql_time ."$txtSECOND, ". 
                 $sql_percent .'%, '. $this->_DataPerfQry.
                 " $txtQuery ]</b>", DBGLINE_PROCESSPERF);
	}
	
	/**
	 *  _HighLightKeyWords : Highligth a keyword in the debug info
	 */ 
	 function _HighLightKeyWords($SearchStr, $SearchCaseSensitiveType = false)
	 {
	 	if (!empty($SearchStr) && !empty($this->_DebugBuffer)) {
			for($i = 0 ; $i < count($this->_DebugBuffer) ; $i++) {
				if ($this->DisplayTypeInSearch[$this->_DebugBuffer[$i]->DebugType] == false) {
					if (!is_array($this->_DebugBuffer[$i]->_DebugString) && !is_object($this->_DebugBuffer[$i]->_DebugString)) {
						if ( $SearchCaseSensitiveType == true)
							$this->_DebugBuffer[$i]->_DebugString = ereg_replace("$SearchStr", DebugLine::colorizeFont("<b>$SearchStr</b>", '#FFA500'), $this->_DebugBuffer[$i]->_DebugString);
						else
							$this->_DebugBuffer[$i]->_DebugString = eregi_replace("$SearchStr", DebugLine::colorizeFont("<b>$SearchStr</b>", '#FFA500'), $this->_DebugBuffer[$i]->_DebugString);
						
						$this->_DebugBuffer[$i]->_BuildDisplayString();
					}
				}
			}
	 	}	 	
	 }

	/**
	 * parseSQL() : Function that parse a SQL String add a debug info if it is invalid
	 * 
	 * @param string	$sql	SQL Query to parse
	 * 
	 * @since 22 Dec 2003 
	 * @access public
	 */ 
	function parseSQL($sql)
	{		
		if ($this->DebugMode == DBG_MODE_OFF)
			return false;

        // First Test Query Syntax (PEAR VERSION)
        if (DBG_VERSION == DBG_VERSION_PEAR) {
			$parseRes = $this->_SQLParser->parse($sql);
            if (PEAR::isError($parseRes)) {
            	return $parseRes->getMessage();
			}
		}
		else 
			return false;
	}

	/**
	 * generateStyleSheet()
	 */
	 function generateStyleSheet()
	 {
		$ArrTemp = array_unique(array_merge($this->CellColors, $this->_StyleClassColor));
		
		$StyleSheet = '<style type="text/css" id="phpdebugStyleSheet">';
		
        foreach ($this->_BaseStyle as $lkey => $lvalue) {
            $StyleSheet .= CR. $lvalue;
        }       
        
		foreach ($ArrTemp as $lkey => $lvalue) {
			$StyleSheet .= CR. ".style". substr($lvalue, 1) ."  { color: $lvalue; }";
		}		
		$StyleSheet .= CR. '</style>';

		return $StyleSheet;
	 }
	
	/**
	 * DebugDisplay() : This is the function to display debug infos
	 * 
	 * @param string 		$search_str		Search string	( Optional, default = "" )
	 * @param integer		$display_mode	Mode of display ( DBG_MODE_AUTO )
	 * 
	 * @since 20 Oct 2003
	 * @access public
	 */ 
	function DebugDisplay($display_mod = DBG_MODE_AUTO)
	{
		if ($this->DebugMode == DBG_MODE_OFF)
			return;
		// Fix display mode
		elseif ($display_mod == DBG_MODE_AUTO)
		 	$display_mod = $this->DebugMode;

        // Fix end time process the sooner possible
        $this->_StopProcessTime();
						
		// Generate other style
        if ($this->genXHTMLOutput == false)
            print($this->generateStyleSheet());
						
		// HTML START
		print($this->HtmlTableStart);

        // Process time debug informations
        $this->_addProcessTime();

		// Set variables depending on the current debug mode
		switch ($display_mod) {

            case DBG_MODE_OFF:
                // We should never go here
                return;
                break;

            case DBG_MODE_USERPERF:
                $testShowTemplates = false;
                $testShowSuperArray = true;
                $testShowGlobals = false;
                $testShowQueries = false;
                break;

            case DBG_MODE_QUERY:
                $testShowTemplates = false;
                $testShowSuperArray = true;
                $testShowGlobals = false;
                $testShowQueries = true;
                break;

            case DBG_MODE_QUERYTEMP:
                $testShowSuperArray = true;
                $testShowTemplates = true;
                $testShowGlobals = false;
                $testShowQueries = true;
                break;

            case DBG_MODE_FULL:
                $testShowTemplates = true;
                $testShowSuperArray = true;
                $testShowGlobals = true;
                $testShowQueries = true;
                break;

			default:
            break;
        }

        // Include templates =================================================== 
        if ($testShowTemplates == true)
        {
            // Include debug of included files
            if ($this->ShowTemplates == true)
                $this->_IncludeRequiredFiles();
        }
    
        // Include super array ================================================= 
        if ($testShowSuperArray == true)
        {    
            // Divide Request tab
            if ($this->UseRequestArr == false) {
                // Include Post Var
                $this->_addSuperArray(DBG_GLOBAL_POST);
    
                // Include Get Var
                $this->_addSuperArray(DBG_GLOBAL_GET);
    
                // Include File Var
                $this->_addSuperArray(DBG_GLOBAL_FILES);
                
                // Include Cookie Var
                $this->_addSuperArray(DBG_GLOBAL_COOKIE);
            }
            else
                // Only display Request Tab
                $this->_addSuperArray(DBG_GLOBAL_REQUEST);
    
            // Include Sessions Var :Check if we have Session variables
            if (!empty($_SESSION))
                $this->_addSuperArray(DBG_GLOBAL_SESSION);

        }

        // Include Globals Var =================================================
        if ($testShowGlobals == true || $this->ShowGlobals == true)
        {
            $this->_addSuperArray(DBG_GLOBAL_GLOBALS);
        }

        // Search ==============================================================
        if ( !empty($_GET['DBG_SEARCH']) ) {
            $SearchSTR = trim($_GET['DBG_SEARCH']);
        }
        else
            $SearchSTR = '';

        if ( !empty($_GET['DBG_SEARCH_TYPE']) ) {
            $SearchType = $_GET['DBG_SEARCH_TYPE'];
        }
        else
            $SearchType = 0;
            
        $SearchCaseSensitiveType = !empty($_GET['DBG_SEARCH_CASESENSITIVE']) 
                                    ? true : false;

        // Highlight Keywords
        if (!empty($SearchSTR))
            $this->_HighLightKeyWords($SearchSTR, $SearchCaseSensitiveType);
        
        // Display Debug cells =================================================
        foreach ($this->_DebugBuffer as $lkey =>$lvalue)
        {
            $bufstr = $lvalue->getDebugLineString();
            
            // Display only cell that contains the search string or in force display array
            $ShowDebugLine = false;

            if (!empty($SearchSTR)) {
                // Check if data is not an object or array
                $searchInto = (is_array($lvalue->_DebugString) || 
                               is_object($lvalue->_DebugString) ? 
                               $this->DumpArr($lvalue->_DebugString, STR_N, DUMP_ARR_STR) : 
                               $lvalue->_DebugString);
                
                // Search string found
                if ( $SearchCaseSensitiveType == true ) {
                    if (strstr($searchInto, $SearchSTR) && $lvalue->DebugType)
                        $ShowDebugLine = true;                      
                } else {
                    if (stristr($searchInto, $SearchSTR) && $lvalue->DebugType)
                        $ShowDebugLine = true;                      
                }
            }
            else
                $ShowDebugLine = true;

            // Display only a type of debug info if applicable
            if ($SearchType != 0) {
                if ($lvalue->DebugType != $SearchType) {
                    $ShowDebugLine = false;
                }
            }

            // Don't display queries if defined in mode
            if ($testShowQueries == false && $lvalue->DebugType == DBGLINE_QUERY)
            {
                 $ShowDebugLine = false;
            }

            // Forced debugline in search mode
            if ($this->DisplayTypeInSearch[$lvalue->DebugType] == true)
                $ShowDebugLine = true;
            
            if ($ShowDebugLine == true)
                print($bufstr);
        }

		// Close HTML Table
		print($this->HtmlTableEnd);
	}
	
	/**
	 * UniTtests() : Make the unit tests of the debug class
	 * 
	 * @since 22 Nov 2003
	 * @access public
	 */ 
	function runUnitTests($fullmode = false)
	{
		$ClassName = get_class($this);		
		$txtTitle  = "Class $ClassName Unit Tests (debug.php)";
		$Title     = "======== $txtTitle";

		print('<pre><br /><br />');
		print('<a name=\"'. $ClassName .'\"></a>');
		print($Title);
		if ($fullmode == true) 
			Debug::DumpObj($this, $ClassName, DUMP_ARR_DISP);
		print('<br /><br /></pre>');
	}
}

// {{{ constants

/**
 * DEBUG LINE Types
 * 
 * - DBGLINE_STD        : Standart debug, fonctionnal or other
 * - DBGLINE_QUERY      : Query debug
 * - DBGLINE_QUERY_REL  : Database related debug
 * - DBGLINE_ENV        : Environment debug ( $GLOBALS... )
 * - DBGLINE_CURRENTFILE: Output current file that is debugged
 * - DBGLINE_APPERROR   : Debug Error 
 * - DBGLINE_CREDITS    : Credits
 * - DBGLINE_SEARCH     : Search mode in debug
 * - DBGLINE_OBJECT     : Debug object mode
 * - DBGLINE_PROCESSPERF: Performance analysys
 * - DBGLINE_TEMPLATES	: Debug included templates
 * - DBGLINE_PAGEACTION	: Debug main page action 
 * - DBGLINE_ARRAY	    : Debug array mode
 * - DBGLINE_SQLPARSE   : Debug SQL Parse error
 * 
 * @category DebugLine
 */
define('DBGLINE_ANY',          0);
define('DBGLINE_STD',          1);
define('DBGLINE_QUERY',        2);
define('DBGLINE_QUERY_REL',    3);
define('DBGLINE_ENV',          4);
define('DBGLINE_CURRENTFILE',  5);
define('DBGLINE_APPERROR',     6);
define('DBGLINE_CREDITS',      7);
define('DBGLINE_SEARCH',       8);
define('DBGLINE_OBJECT',       9);
define('DBGLINE_PROCESSPERF', 10);
define('DBGLINE_TEMPLATES',   11);
define('DBGLINE_PAGEACTION',  12);
define('DBGLINE_ARRAY',       13);
define('DBGLINE_SQLPARSE',    14);
define('DBGLINE_DEFAULT',     DBGLINE_STD);

/**
 * DBGLINE_ERRORALERT, default error message for DBGLINE_APPERROR debug line type
 */
define('DBGLINE_ERRORALERT', "/!\\");

/**
 * DBGLINE_BOLD, constants for bold status function
 */
define('DEBUGLINE_BOLD_CLOSE', 0);
define('DEBUGLINE_BOLD_OPEN',  1);

// }}}

/**
 * DebugLine : Class that describe debug line informations
 *
 * Describe all infos and methods for a debug line, file location, color, type
 * of debug, debug buffer, formatted debug buffer title of debug variable if
 * applicable...
 * 
 * @package PHP_Debug
 * @author COil, Loic Vernet <qrf_coil@yahoo.fr>
 * @version V1.1.0
 * @since BETA1.0
 */
class DebugLine
{
	/**
	 * ID of parent Debug Object ID
	 * 
	 * @var string $ProcessTimeString
	 * @access private
	 * @since 24 Dec 2003
	 */ 

	var $_DebugObjectID = null;
	/** 
	 * File of debug info
	 * 
	 * @var integer $_Fine			
	 * @access private
	 */
	var $_File = '';

	/** 
	 * Line of debug info
	 * 
	 * @var integer $_Line			
	 * @access private
	 */
	var $_Line = '';
		
	/** 
	 * Complete Location ( formatted ) of debug infos ( Line + File )
	 * 
	 * @var integer $_Location 
	 * @access private
	 */
	var $_Location = '';

	/** 
	 * Title of debug line ( Object var ) 
	 * 
	 * @var String $_Linetitle 
	 * @see DumpObj()
	 * @access private
	 */
	var $_LineTitle = '';

	/** 
	 * String that store non formatted debug info 
	 * 
	 * @var string $_DebugString		
	 * @access private
	 */
	var $_DebugString = '';

	/**
	 * Formatted Debug info 
	 * 
	 * @var string $_DebugString 
	 * @access public
 	 */
	var $DebugDisplayString = '';

	/**
	 * Debug Type 
	 * 
	 * @var integer $DebugType 
	 * @see DBGLINE contants
	 * @access public
	 */
	var $DebugType = DBGLINE_DEFAULT;
	
	/** 
	 * Background Color for debug info cell
	 * 
	 * @var array $CellColor
	 * @access public
	 */
	var $CellColor = '';

	/** 
	 * Base URL of phpmyadmin	
	 * 
	 * @var string $PhpMyAdminUrl	
	 * @access public
	 */
	var $PhpMyAdminUrl = '';

	/** 
	 * Name of database that we are working on
	 * 
	 * @var string $CurrentDatabase	
	 * @access public
	 */
	var $DatabaseName = '';
	
	/**
	 * Bold style for debug info cell
	 * 
	 * @var array $CellBoldStatus
	 * @access public
	 */
	var $CellBoldStatus = false;
	
	/**
	 * Default Backgourd cell color
	 * 
	 * @var string $DefaultCellBackColor
	 * @access public
	 */ 
	var $DefaultCellBackColor = '#F8F8FF';
	
	/**
	 * HTML Cell start code 
	 * 
	 * @var string $HtmlPreCell	
	 * @access public
	 */
	var $HtmlPreCell = '<tr>
                          <td class="dbgTD">';

	/** 
	 * HTML Cell end code
	 * 
	 * @var string $HtmlPostCell
	 * @access public
	 */
	var $HtmlPostCell = ' </td>  
                        </tr>';
	
	/** 
	 * Process time infos if applicable
	 * 
	 * @var string $ProcessTimeString
	 * @access public
	 * @since 19 Dec 2003
	 */
	var $_ProcessTimeString = '';

    /** 
     * Most of the time we will display a global span style for the debug
     * cell but we must be able to desactive it in order to preserve XHTML
     * compliance (DBGLINE_SEARCH for exemple)
     * 
     * @var string $applySpanStyle
     * @access public
     * @since 13 May 2005
     */
    var $applySpanStyle = true;
	
   	/**
	 * DebugLine() Constructor of class
	 *
	 * _Location is Automatically created at object instantation.
	 * Then the formatted debug HTML row is created.
	 *
	 * @param string		$str			Debug Information to store
	 * @param integer		$DebugType		Type of debug information
	 * @param string		$file			File of debug information
	 * @param string		$line			Debug of debug information
	 * @param string		$title			Title of debuged var
	 *
	 * @return mixed 	DebugLine Object
	 *
	 * @see _BuildDebugLineLocation()
	 * @since 17 Oct 2003
	 * @access public
	 */
	function DebugLine($DebugObjectID, $str, $DebugType, $file, $line, $title)
	{	
		$this->_DebugObjectID = $DebugObjectID;
		$this->_DebugString   = $str;
		$this->DebugType      = $DebugType;	
		$this->_File          = $file;
		$this->_Line          = $line;
		$this->_LineTitle     = $title;
        
        // Don't apply global style for search cell.
        if ($DebugType == DBGLINE_SEARCH)
            $this->applySpanStyle = false;           
	}
	
	/**
	* _buildDebugLine() : Fucntions that build formatted datas of the debug cell
	* 
	* @since 19 Dec 2003
	*/ 
	function _buildDebugLine()
	{
		$this->_Location = $this->_BuildDebugLineLocation($this->_File, 
                                                          $this->_Line);
		$this->_BuildHtmlPreCell();
		$this->_BuildDisplayString();
	}

   	/**
	 * getPhpMyAdminUrl() : Return url of PhpmyAdmin
	 *
	 * @return string PhpMyAdminUrl
	 * 
	 * @see PhpMyAdminUrl
	 * @since 25 Oct 2003
	 * @access public
	 */
	function getPhpMyAdminUrl()
	{			
		global $DEBUG_OBJECT;
		return $DEBUG_OBJECT[$this->_DebugObjectID]->getPhpMyAdminUrl();
	}
	
	/**
	 * setProcessTime() : Process time string assignment function
	 * 
	 * @since 19 Dec 2003
	 * @access public
	 */
	function setProcessTime($Str)
	{
		$this->_ProcessTimeString = $Str;
	}

	/**
	 * _getCellColor() : Retrieve general HTML font color coding of debug cell
	 * 
	 * @since 19 Dec 2003
	 * @access private
	 */ 
	function _openCellColor()
	{
        if ($this->applySpanStyle == false)
            return STR_N;
        
		global $DEBUG_OBJECT;
		return '<span class="'. 
                $DEBUG_OBJECT[$this->_DebugObjectID]->getColorCodeClass($this->DebugType).
                '">';
	}
	
	/**
	 * _closeCellColor() : Close bold status of cell
	 * 
	 * @since 19 Dec 2003
	 * @access private
	 */ 
	function _closeCellColor()
	{
        if ($this->applySpanStyle == false)
            return STR_N;

		return '</span>';
	}

	/**
	 * applyTextStyle() : Apply a style to a string
	 * 
	 * @since 26 Dec 2003
	 * @access public
	 * @static
	 */ 
	function applyTextStyle($str, $style)
	{		 
		return('<span class="'. $style. '">'. $str. '</span>');
	}

	/**
	 * _getCellBoldStatus() : Get gen html open bold code status of debug cell
	 * 
	 * @since 19 Dec 2003
	 * @access private
	 */ 
	function _openBoldStatus()
	{
		return $this->_getBoldStatus(DEBUGLINE_BOLD_OPEN);
	}

	/**
	 * _getCellBoldStatus() : Get gen html close bold code status of debug cell
	 * 
	 * @since 19 Dec 2003
	 * @access private
	 */ 
	function _closeBoldStatus()
	{
		return $this->_getBoldStatus(DEBUGLINE_BOLD_CLOSE);
	}

	/**
	 * _getBoldStatus() : Get open or close bold status of a debug cell
	 * 
	 * @since 19 Dec 2003
	 * @access private
	 */ 
	function _getBoldStatus($mode = DEBUGLINE_BOLD_OPEN)
	{
		global $DEBUG_OBJECT;

		switch ($mode) {
		
		case (DEBUGLINE_BOLD_OPEN):
			return ($DEBUG_OBJECT[$this->_DebugObjectID]->getBoldCodeType($this->DebugType) ? '<b>' : STR_N );
			break;
			
		case (DEBUGLINE_BOLD_CLOSE):
			return ($DEBUG_OBJECT[$this->_DebugObjectID]->getBoldCodeType($this->DebugType) ? '</b>' : STR_N );
			break;
		
		default:
			break;
		}
	}

	/**
	 * colorizeFont() : Function that colorize a string with a given color
	 * 
	 * @since 26 Dec 2003
	 * @access public
	 * @static
	 */
	function colorizeFont($str, $color)
	{
		return '<span class="style'. substr($color, 1). '">'. $str. '</span>';
	}
	
   	/**
	 * _BuildDisplayString() : Builds the formatted debug line
	 *
	 * Depending on the DebugType the formatted debug line is build.
	 * DebugDisplayString is built.
	 * One case by debug type.
	 *
	 * @see DebugType
	 * @since 20 Oct 2003
	 * @access private
	 */	 
	function _BuildDisplayString()
	{
		global $DEBUG_OBJECT;

		switch ($this->DebugType) {

			// Standart output
			case DBGLINE_STD:
				$this->DebugDisplayString = $this->_DebugString;
			break;

			// Query
			case DBGLINE_QUERY:
				$txtExplain = 'Explain';
				$txtQuery   = 'Query';
				
				// Format Query
				$this->DebugDisplayString = $this->_FormatQueryString($this->_DebugString);
								
				if ($DEBUG_OBJECT[$this->_DebugObjectID]->getPhpMyAdminStatus()) {
                    
	    			$basehtml    = ' [ <a href="';

					$url_query   = $DEBUG_OBJECT[$this->_DebugObjectID]->getPhpMyAdminUrl() 
                                    .'/read_dump.php';

					$url_query   .= '?debug=full&is_js_confirmed=0&amp;server=1&amp;db='. 
                        $DEBUG_OBJECT[$this->_DebugObjectID]->getDatabaseName(). 
                        '&amp;pos=0&amp;goto=db_details.php&amp;zero_rows=&prev_sql_query=&amp;sql_file=&amp;sql_query=';

					$url_explain = $url_query. 'explain '. urlencode($this->_DebugString);
					$url_query   = $url_query. urlencode($this->_DebugString);

					$this->DebugDisplayString = $this->_FormatQueryString($this->_DebugString);
					$this->DebugDisplayString = preg_replace('/\s+/', ' ', $this->DebugDisplayString);
	
					// Parse SQL
			        if (DBG_VERSION == DBG_VERSION_PEAR) {					    
						$parseRes = $DEBUG_OBJECT[$this->_DebugObjectID]->parseSQL($this->_DebugString);
						if ($parseRes) {
							$this->DebugDisplayString .= $this->colorizeFont('<pre>('. $parseRes .')</pre>', '#800000');
						}
					}	

					// Explain Link only for select Queries.
					if (stristr($this->_DebugString, 'select'))
						$this->DebugDisplayString .= $this->applyTextStyle($basehtml. $url_explain ."\">$txtExplain</a> ]", 'dbgNormal');
	
					// Query Link
					$this->DebugDisplayString .= $this->applyTextStyle($basehtml. $url_query. "\">$txtQuery</a> ]", 'dbgNormal');;
				}
					
				break;				    

			// Database Related
			case DBGLINE_QUERY_REL:
				$this->DebugDisplayString = $this->_DebugString;
				break;

			// Environnment Related
			case DBGLINE_ENV:
				$this->DebugDisplayString = $this->_DebugString;
				break;

			// Current File
			case DBGLINE_CURRENTFILE:
				$txtCurrentFile = 'Current File';
				$this->DebugDisplayString = "&laquo; $txtCurrentFile";
				break;

			// App Error
			case DBGLINE_APPERROR:
				$this->DebugDisplayString = DBGLINE_ERRORALERT. ' '. $this->_DebugString. ' '. DBGLINE_ERRORALERT;
			break;

			// Credits
			case DBGLINE_CREDITS:
				$this->DebugDisplayString = $this->_DebugString;
			break;

			// Search Debug
			case DBGLINE_SEARCH:
				// Repost all posted data
				$txtSearchInDebug  = 'Search in Debug Infos';
				$txtGo   		   = 'Go !';
				$txtStringToSearch = 'Search for';
				$txtCaseSensitive  = 'Case sensitive';
				$txtSelectByType   = 'Select only info of type';
				$txtAny            = '-- Any --';
				
				$dbg_search_value = isset($_REQUEST["DBG_SEARCH"]) ? trim($_REQUEST["DBG_SEARCH"]) : '';
				$dbg_search_casesensitive_value = isset($_REQUEST["DBG_SEARCH_CASESENSITIVE"])  ? ' checked="checked"' : '';
                
				
				$this->DebugDisplayString = 
					'== <b>'. $txtSearchInDebug .'</b> :
                       <form id="debugForm" action="'. $_SERVER['SCRIPT_NAME']. '">
                           <input type="hidden" name="debug" value="full" />
                       <table>
					   <tr>
					     <td>'. $txtStringToSearch .'</td>
						 <td>:</td>
						 <td>
						   <input type="text" name="DBG_SEARCH" value="'. $dbg_search_value. '" />
						 </td>
						 <td>'. $txtCaseSensitive .'</td>
						 <td>:</td>
						 <td>
						   <input type="checkbox" name="DBG_SEARCH_CASESENSITIVE" '. $dbg_search_casesensitive_value .' />
						 </td>
						 <td>&nbsp;</td>
						 <td>'. $txtSelectByType. '</td>
						 <td>:</td>
						 <td>
						   <select name="DBG_SEARCH_TYPE">';
							foreach ($DEBUG_OBJECT[$this->_DebugObjectID]->DebugTypeLabel as $lkey => $lvalue) {
								$dbg_search_type_value = (!empty($_REQUEST["DBG_SEARCH_TYPE"]) && $lkey == $_REQUEST["DBG_SEARCH_TYPE"]) ? ' selected="selected"' : '';
								$this->DebugDisplayString .= "<option value=\"$lkey\"$dbg_search_type_value>&raquo; $lvalue</option>". CR;
							}									
							$this->DebugDisplayString .= '
							</select>
							<input type="submit" value="'. $txtGo. '" />
						</td>
					  </tr>
				    </table></form>';
				break;
                     
			// Object Debug
			case DBGLINE_OBJECT:
				$obj_title = empty($this->_LineTitle) ? get_class($this->_DebugString) : $this->_LineTitle;
				$this->DebugDisplayString = Debug::DumpObj($this->_DebugString, $obj_title, DUMP_ARR_STR);
				break;

			// Process Perf
			case DBGLINE_PROCESSPERF;
				$this->DebugDisplayString = $this->_DebugString;
				break;

			// Temlates
			case DBGLINE_TEMPLATES;
				$this->DebugDisplayString = $this->_DebugString;
				break;

			// Main Page Action
			case DBGLINE_PAGEACTION;
				$txtPageAction = 'Page Action';
				$this->DebugDisplayString = " [ $txtPageAction : ". $this->_DebugString .' ]';
				break;

			// Array Debug
			case DBGLINE_ARRAY:
				$this->DebugDisplayString = Debug::DumpArr($this->_DebugString, $this->_LineTitle, DUMP_ARR_STR);
				break;

			// SQL Parse error
			case DBGLINE_SQLPARSE:
				$txtSQLParseError = 'SQL_Parser :';
				$this->DebugDisplayString = DBGLINE_ERRORALERT. ' '. $txtSQLParseError. $this->_DebugString. ' '. DBGLINE_ERRORALERT;
				break;
		}
	}

	/**
	* _FormatQueryString() : Break a big query to display it on several lines
	* 
	* @since 18 Dec 2003
	* @see $maxQueryLength
	* @access private
	*/ 	
	function _FormatQueryString($sql)
	{
		global $DEBUG_OBJECT;
		$sql_arr = array();
		
		$NewLineSqlKeyWords = array('SELECT', 'FROM', 'WHERE', 'AND', 'OR', 'ORDER', 'GROUP BY', 'UPDATE', 'SET', 'HAVING');

		foreach($NewLineSqlKeyWords as $lkey => $lvalue) {
			$sql = eregi_replace("$lvalue ", "<br />$lvalue " , $sql);
		}

		// Make an array with exploding by <br />, then split each string
		$StrDecomp = explode('<br />', $sql);
		
		// Parse each item and check the size
		foreach ($StrDecomp as $lkey => $lvalue) {
			if (strlen($lvalue) > $DEBUG_OBJECT[$this->_DebugObjectID]->getmaxQueryLineLength()) {
				$sql_arr[] = $this->breakString($lvalue, $DEBUG_OBJECT[$this->_DebugObjectID]->getmaxQueryLineLength());
			}
			else
				$sql_arr[] = $lvalue;
		}
	
		// Implode the array with <br />
		$sql = implode('<br />', $sql_arr);
	
		// $maxQueryLength
		return $sql;
	}
	
	/**
	 * breakString() : Add Carriage return to a string
	 * 
	 * @param String  $Str			String to break
	 * @param Integer $Length		Length after we must break the string
	 * @param String  $Separator	Separator used to break string
	 * 
	 * @access public 
	 * @since 26 Dec 2003
	 */ 
	function breakString($Str, $Length, $Separator = '<br />')
	{
		$FormatedStr = array();
		
		for ($Idx = 0 ; $Idx < strlen($Str) ; $Idx++)
		{
			$FormatedStr[$Idx] = $Str{$Idx};
			if ($Idx % $Length == 0 && $Idx != 0) {
				$FormatedStr[$Idx] = $FormatedStr[$Idx]. $Separator;
			}
		}		
		return implode('',$FormatedStr);
	}
	
	/**
	* _BuildHtmlPreCell() : Build HTML pre cell with backgroud attributes
	* 
	* @since 11 Dec 2003
	* @see DefaultCellBackColor, HtmlPreCell
	* @access private
	*/ 
	function _BuildHtmlPreCell()
	{	
		$this->HtmlPreCell = sprintf($this->HtmlPreCell, $this->DefaultCellBackColor);
	}		
	
   	/**
	 * getDebugLineString() : Return Formated debug infos
	 *
 	 * @return string The formatted string
	 * 
	 * @since 25 Oct 2003
	 * @access public
	 */
	function getDebugLineString()
	{
		// Build formatted datas
		$this->_buildDebugLine();
		
		return $this->HtmlPreCell.
			   $this->_Location.
			   $this->_openCellColor().
			   $this->_openBoldStatus().
			   $this->DebugDisplayString. $this->_ProcessTimeString.
			   $this->_closeBoldStatus().
			   $this->_closeCellColor().
			   $this->HtmlPostCell. CR;
	}
	
   	/**
	 * _BuildDebugLineLocation() :  Retrieve Localisation of debug info
	 *
	 * Check is $file and $line, build the location with available
	 * datas, if nothing return a default Info message.
     *
 	 * @param string $file 	File of debug info
	 * @param string $line 	Line number of debug info
	 * 
 	 * @return string The formatted location [file,line]
	 * 
	 * @since 25 Oct 2003
	 * @access private
	 */
	function _BuildDebugLineLocation($file, $line)
	{
		// Lang
		$txtNoLocation = 'NO LOC';
		$l_dbgloc      = '';
		
		if (!empty($file))
			$l_dbgloc .= basename($file);
		
		if (!empty($line)) {
			if (!empty($l_dbgloc))
				$l_dbgloc .= ',';
				
			$l_dbgloc .= $line;
		}

		if (!empty($l_dbgloc))
			$l_dbgloc = '['. $l_dbgloc. ']';
		else {
			if ($this->DebugType != DBGLINE_CREDITS && 
				$this->DebugType != DBGLINE_SEARCH && 
				$this->DebugType != DBGLINE_ENV && 
				$this->DebugType != DBGLINE_PROCESSPERF &&
				$this->DebugType != DBGLINE_TEMPLATES)
				$l_dbgloc = "[-$txtNoLocation-]";
		}				
		return $l_dbgloc;
	}
}
?>