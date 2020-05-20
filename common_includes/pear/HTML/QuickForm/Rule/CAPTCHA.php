<?php 
/* vim: set expandtab tabstop=4 shiftwidth=4: */ 

/** 
 * Rule for HTML_QuickForm to display a CAPTCHA image 
 * 
 * This package requires the use of a PHP session. 
 * 
 * PHP versions 4 and 5 
 * 
 * @category   HTML 
 * @package    HTML_QuickForm_CAPTCHA 
 * @author     Philippe Jausions <Philippe.Jausions@11abacus.com> 
 * @copyright  2006 by 11abacus 
 * @license    LGPL 
 * @version    CVS: $Id$ 
 * @link       http://pear.php.net/package/HTML_QuickForm_CAPTCHA 
 */ 

require_once 'HTML/QuickForm/Rule.php'; 

/** 
 * Rule to compare a field with a CAPTCHA image 
 * 
 * @access public 
 * @package HTML_QuickForm_CAPTCHA 
 * @version $Revision:$ 
 */ 
class HTML_QuickForm_Rule_CAPTCHA extends HTML_QuickForm_Rule 
{ 
    /** 
     * Validates the data enter matches the CAPTCHA image that was 
     * displayed 
     * 
     * @param string $value data to validate 
     * @param HTML_QuickForm_CAPTCHA $captcha_element to check against 
     */ 
    function validate($value, $captcha_element) 
    { 
        return ($value == $captcha_element->getValue()); 
    } 

} 

HTML_QuickForm::registerRule('CAPTCHA', 'rule', 'HTML_QuickForm_Rule_CAPTCHA', 'HTML/QuickForm/Rule/CAPTCHA.php'); 

?>
