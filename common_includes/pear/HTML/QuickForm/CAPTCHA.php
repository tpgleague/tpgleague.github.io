<?php 

/* vim: set expandtab tabstop=4 shiftwidth=4: */ 

/** 
 * Element for HTML_QuickForm to display a CAPTCHA image 
 * 
 * The HTML_QuickForm_CAPTCHA package adds an element to the 
 * HTML_QuickForm package to display a CAPTCHA image. 
 * 
 * This package uses a PHP session. 
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

/** 
 * Required packages 
 */ 
require_once 'HTML/QuickForm/input.php'; 
require_once 'Text/CAPTCHA/Driver/Image.php'; 

/** 
 * Element for HTML_QuickForm to display a CAPTCHA image 
 * 
 * The HTML_QuickForm_CAPTCHA package adds an element to the 
 * HTML_QuickForm package to display a CAPTCHA image. 
 * 
 * This package uses a PHP session. 
 * 
 * PHP versions 4 and 5 
 * 
 * @category   HTML 
 * @package    HTML_QuickForm_CAPTCHA 
 * @author     Philippe Jausions <Philippe.Jausions@11abacus.com> 
 * @copyright  2006 by 11abacus 
 * @license    LGPL 
 * @version    Release: 0.1.0 
 * @link       http://pear.php.net/package/HTML_QuickForm_CAPTCHA 
 */ 
class HTML_QuickForm_CAPTCHA extends HTML_QuickForm_input 
{ 
    /** 
     * Default options 
     * 
     * @var array 
     * @access protected 
     */ 
    var $_options = array( 
            'sessionVar' => '_HTML_QuickForm_CAPTCHA', 
            'width'      => '200', 
            'height'     => '80', 
            'alt'        => 'Click to view another image', 
            'callback'   => '', 
            'Image_Text' => null, 
            'phrase'     => null, 
            ); 

    /** 
     * Class constructor 
     * 
     * @param      string    Name 
     * @param      mixed     Label for the CAPTCHA 
     * @param      array     Options for the Text_CAPTCHA package 
     * <ul> 
     *  <li>'width'      (integer) width of the image,</li> 
     *  <li>'height'     (integer) height of the image,</li> 
     *  <li>'Image_Text' (array)   options passed to the Image_Text 
     *                             constructor,</li> 
     *  <li>'callback'   (string)  URL of callback script that will generate 
     *                             and output the image itself,</li> 
     *  <li>'alt'        (string)  the alt text for the image,</li> 
     *  <li>'sessionVar' (string)  name of session variable containing 
     *                             the Text_CAPTCHA instance (defaults to 
     *                             _HTML_QuickForm_CAPTCHA.)</li> 
     * </ul> 
     * @param      mixed     HTML Attributes for the <a> tag surrounding the 
     *                       image. Can be a string or array. 
     * 
     * @access     public 
     * @return     void 
     * @see        Image_Text::set() 
     */ 
    function HTML_QuickForm_CAPTCHA($elementName = null, $elementLabel = null, 
                                    $options = null, $attributes = null) 
    { 
        HTML_QuickForm_input::HTML_QuickForm_input($elementName, $elementLabel, $attributes); 
        $this->setType('CAPTCHA'); 

        if (is_array($options)) { 
            $this->_options = array_merge($this->_options, $options); 
        } 
    } 

    /** 
     * Returns the HTML for the CAPTCHA image 
     * 
     * @access     public 
     * @return     string 
     */ 
    function toHtml() 
    { 
        if ($this->_flagFrozen) { 
            return ''; 
        } 

        extract($this->_options); 

        if (empty($_SESSION[$sessionVar])) { 
            $_SESSION[$sessionVar] =& Text_CAPTCHA::factory('Image'); 
            if (PEAR::isError($_SESSION[$sessionVar])) { 
                return $_SESSION[$sessionVar]; 
            } 
            $result = $_SESSION[$sessionVar]->init( 
                (int)$width, 
                (int)$height, 
                $phrase, 
                $Image_Text); 
            if (PEAR::isError($result)) { 
                return $result; 
            } 
        } 

        $html = ''; 

        $tabs = $this->_getTabs(); 
        $inputName = $this->getName(); 
        $imgName = 'QF_CAPTCHA_' . $inputName; 

        if ($this->getComment() != '') { 
            $html .= $tabs . '<!-- ' . $this->getComment() . ' // -->'; 
        } 

        $html = $tabs . '<a href="' . $callback . '" target="_blank" ' . $this->_getAttrString($this->_attributes) . ' onclick="var cancelClick = false; ' 
. $this->getOnclickJs($imgName) . ' return !cancelClick;"><img src="' . $callback . '" name="' . $imgName . '" id="' . $imgName . '" width="' . $width . 
'" height="' . $height . '" title="' . htmlspecialchars($alt, ENT_COMPAT, HTML_Common::charset()) . '" /></a>'; 

        return $html; 
    } 

    /** 
     * Create the javascript for the onclick event which will 
     * reload a new CAPTCHA image 
     * 
     * @param     string    $imageName    The image name/id 
     * 
     * @access public 
     * @return string 
     */ 
    function getOnclickJs($imageName) 
    { 
        $onclickJs = 'if (document.images) {var img = new Image(); var d = new Date(); img.src = this.href + ((this.href.indexOf(\'?\') == -1) ? \'?\' : 
\'&\') + d.getTime(); document.images[\'' . addslashes($imageName) . '\'].src = img.src; cancelClick = true;}'; 
        return $onclickJs; 
    } 

    /** 
     * Returns the phrase of the CAPTCHA 
     * 
     * @return    string 
     * @access    private 
     */ 
    function _findValue(&$values) 
    { 
        return $this->getValue(); 
    } 

    /** 
     * Returns the phrase of the CAPTCHA 
     * 
     * @return    string 
     * @access    public 
     */ 
    function getValue() 
    { 
        $sessionVar = $this->_options['sessionVar']; 

        return (!empty($_SESSION[$sessionVar])) 
                 ? $_SESSION[$sessionVar]->getPhrase() 
                 : null; 
    } 

    /** 
     * Returns the phrase of the CAPTCHA 
     * 
     * @return    string 
     * @access    public 
     */ 
    function exportValue(&$submitValues, $assoc = false) 
    { 
        return ($assoc) 
               ? array($this->getName() => $this->getValue()) 
               : $this->getValue(); 
    } 

    /** 
     * Sets the CAPTCHA phrase 
     * 
     * Pass NULL or no argument for a random phrase to be generated 
     * 
     * @param string $phrase 
     * @access public 
     */ 
    function setPhrase($phrase = null) 
    { 
        $this->_options['phrase'] = $phrase; 

        if (!empty($_SESSION[$this->_options['sessionVar']])) { 
            $_SESSION[$this->_options['sessionVar']]->setPhrase($phrase); 
        } 
    } 
} 

/** 
 * Register the class with QuickForm 
 */ 
if (class_exists('HTML_QuickForm')) { 
    HTML_QuickForm::registerElementType('CAPTCHA', 'HTML/QuickForm/CAPTCHA.php', 'HTML_QuickForm_CAPTCHA'); 
} 

/** 
 * Register the rule with QuickForm 
 */ 
require_once 'HTML/QuickForm/Rule/CAPTCHA.php'; 

?>
