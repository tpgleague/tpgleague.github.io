<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors:   Carsten Lucke <luckec@tool-garage.de>                     |
// +----------------------------------------------------------------------+
//
//    $Id: Blacklist.php,v 1.4 2005/02/13 21:51:47 luckec Exp $

/**
 * Class that represents a filter which has knowledge about the
 * holidays that must be excluded from driver-calculations.
 *
 * @category    Date
 * @package     Date_Holidays
 * @subpackage  Filter
 * @version     $Id: Blacklist.php,v 1.4 2005/02/13 21:51:47 luckec Exp $
 * @author      Carsten Lucke <luckec@tool-garage.de>
 */
class Date_Holidays_Filter_Blacklist extends Date_Holidays_Filter 
{
    /**
     * Constructor.
     *
     * Creates a filter which has knowledge about the
     * holidays that must be excluded from driver-calculations.
     * 
     * @param   array   numerical array that contains internal names of holidays
     */
    function __construct($holidays) 
    {
        parent::__construct($holidays);
    }
    
    /**
     * Constructor.
     * 
     * @param   array   numerical array that contains internal names of holidays
     */
    function Date_Holidays_Filter_Blacklist($holidays)
    {
        $this->__construct($holidays);
    }
    
   /**
    * Lets the filter decide whether a holiday shall be processed or not.
    * 
    * @param    string  a holidays' internal name
    * @return   boolean true, if a holidays shall be processed, false otherwise
    */
    function accept($holiday) 
    {
        return !(in_array($holiday, $this->_internalNames));
    }
}
?>
