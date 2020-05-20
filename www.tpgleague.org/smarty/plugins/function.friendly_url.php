<?php
/*
* Friendly URL plugin
* -------------------------------------------------------------
* File:     function.friendly_url.php
* Type:     function
* Name:     Friendly URL
* Purpose:  Change a parameterized URL query string into a human-friendly link
* Examples: 
* {friendly_url }
* {url src="images/hello.png" alt="hello" status="Text for the status bar"} for image links
* -------------------------------------------------------------
*/
function smarty_function_url($params, &$smarty)
{
    $url = $params["url"];
    $status = $params["status"];
    if ($params["img"]) {
        $src = $params["src"];
        $alt = $params["alt"];
        $name = "<img src=\"{$src}\" alt=\"{$alt}\" border=\"0\" />";
    } else {
        $name = $params["name"];
    }
    if($url) {
       $html = "<a href=\"{$href}\" onmouseover=\"window.status='{$status}'; return true\" onMouseout=\"window.status=' '; return true\" title=\"{$name}\">{$name}</a>";
    } else {
       $html = $name;
    }
    return $html;
}
?>