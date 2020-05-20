<?php 
/** 
 * HTML_QuickForm_CAPTCHA example - Image generator 
 * 
 * @category   HTML 
 * @version    CVS: $Id$ 
 * @author     Philippe Jausions <Philippe.Jausions@11abacus.com> 
 * @package    HTML_QuickForm_CAPTCHA 
 * @subpackage Examples 
 * @copyright  2006 by 11abacus 
 * @license    LGPL 
 * @filesource 
 * @see        qfcaptcha_form.php 
 * @link       http://pear.php.net/package/HTML_QuickForm_CAPTCHA 
 */ 

// Require the class before opening the session 
// so the instance unserialize properly 
require_once 'Text/CAPTCHA/Driver/Image.php'; 

session_start(); 

header('Content-Type: image/jpeg'); 

$sessionVar = (empty($_REQUEST['var'])) 
              ? '_HTML_QuickForm_CAPTCHA' 
              : $_REQUEST['var']; 

// Force a new CAPTCHA for each one displayed 
$_SESSION[$sessionVar]->setPhrase(); 

echo $_SESSION[$sessionVar]->getCAPTCHAAsJPEG(); 

?>
