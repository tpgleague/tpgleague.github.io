<?php 
/* 
* Smarty plugin 
* ------------------------------------------------------------- 
* File:     outputfilter.tidyrepairhtml.php 
* Type:     outputfilter 
* Name:     tidyrepairhtml 
* Version:  1.0 
* Date:     Sept. 20, 2003 
* Purpose:  Uses the tidy extension to repair a mailformed HTML 
*           template before displaying it 
* Install:  Drop into the plugin directory, call 
*           $smarty->load_filter('output','tidyrepairhtml'); 
*           from application. 
* Author:   John Coggeshall <john@php.net> 
* ------------------------------------------------------------- 
*/ 

function smarty_outputfilter_tidyrepairhtml($source, &$smarty)
{
    if (extension_loaded('tidy')) {
        $config = array('indent' => TRUE,
                        'doctype' => 'omit',
                        'show-body-only' => FALSE,
                        'markup' => TRUE,
                        'force-output' => TRUE,
                        'output-xhtml' => FALSE,
                        'wrap' => FALSE
                       );
        $tidy = tidy_parse_string($source, $config);
        tidy_clean_repair($tidy);
        return tidy_get_output($tidy);
    } 
    return $source;
}

?>
