<?php
  header("Cache-control: private");
  require_once("Sajax.php");
  require_once("JSON.php");


/////////////////////////////////////////////////////////////
// SANJER (SAjax aNd Json wrappER) is a wrapper class
// that brings together SAJAX (the simple ajax toolkit,
// http://www.modernmethod.com/sajax/) and JSON (JavaScript
// Object Notation, http://www.json.org/). These two, when
// combined, allow a simple yet powerfull way to use AJAX.
// The SANJER wrapper class supplies for a simple and convenient
// way to put the two to work together.
// 
// Important note: During work I changed Sajax into a class cause it
//  made much more sense this way. Don't know why it wasn't that way
//  to begin with), so you cannot use the original sajax.php file together
//  with SANJER, only the one supplied in this package.
//  Also, JSON.js was depracated. It's code is now shown by the show_javascript() function,
//  so there is no need to include it anywhere.
//
// Usage: 
//  see usage example in oneFile.php and twoFiles1.php & twoFiles2.php
//
// License Information:
// Do whatever you want with it, just do good.
// oh, and it comes with no warranties.
//
//
// Author: Omer Yariv       omm@users.berlios.de
//
/////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////// 
//
// This part is here to take some of the JS out of the source
// of the rendered HTML file. Instead of dumping all the JS
// onto the header and cluttering the source, show_javascript() will
// create a <link> tag pointing back to this file, with the
// "show_common_javascript" addition.
//
///////////////////////////////////////////////////////////// 
if (isset($_REQUEST["show_common_javascript"])) {
    $sajax = new SAJAX($_GET["request_type"], $_GET["remote_uri"]);
    $sajax->sajax_debug_mode = $_GET["debug_mode"];
    $sajax->sajax_show_javascript();
    
    ob_start();
?>
/////////////////////////////////////////////////////
///         Start SANJER automatically created JS       
/////////////////////////////////////////////////////  
function SANJER(){
    
    this.object2json = function (theObject){    
        return this.JSON.stringify(theObject);
    };

    this.json2object = function (jsonString){
        return this.JSON.parse(jsonString);
    };
    
    this.call_function = function (functionToCall){

        var args = new Array();
        for(i=1;i < this.call_function.arguments.length;++i) {
          args.push(this.call_function.arguments[i]);
        }
        var callString = "sajax_do_call(this.call_function.arguments[0], args)";
        eval(callString);
    };
      
/*
JSON code taken originally from json.js on http://www.json.org/
SAJAX code by sajax.php on http://www.modernmethod.com/sajax/  
*/

    Array.prototype.______array = '______array';    

    this.JSON =  {
        org: 'http://www.JSON.org',
        copyright: '(c)2005 JSON.org',
        license: 'http://www.crockford.com/JSON/license.html',

        stringify: function (arg) {
            var c, i, l, s = '', v;

            switch (typeof arg) {
            case 'object':
                if (arg) {
                    if (arg.______array == '______array') {
                        for (i = 0; i < arg.length; ++i) {
                            v = this.stringify(arg[i]);
                            if (s) {
                                s += ',';
                            }
                            s += v;
                        }
                        return '[' + s + ']';
                    } else if (typeof arg.toString != 'undefined') {
                        for (i in arg) {
                            v = arg[i];
                            if (typeof v != 'undefined' && typeof v != 'function') {
                                v = this.stringify(v);
                                if (s) {
                                    s += ',';
                                }
                                s += this.stringify(i) + ':' + v;
                            }
                        }
                        return '{' + s + '}';
                    }
                }
                return 'null';
            case 'number':
                return isFinite(arg) ? String(arg) : 'null';
            case 'string':
                l = arg.length;
                s = '"';
                for (i = 0; i < l; i += 1) {
                    c = arg.charAt(i);
                    if (c >= ' ') {
                        if (c == '\\' || c == '\"') {
                            s += '\\';
                        }
                        s += c;
                    } else {
                        switch (c) {
                            case '\b':
                                s += '\\b';
                                break;
                            case '\f':
                                s += '\\f';
                                break;
                            case '\n':
                                s += '\\n';
                                break;
                            case '\r':
                                s += '\\r';
                                break;
                            case '\t':
                                s += '\\t';
                                break;
                            default:
                                c = c.charCodeAt();
                                s += '\\u00' + Math.floor(c / 16).toString(16) +
                                    (c % 16).toString(16);
                        }
                    }
                }
                return s + '"';
            case 'boolean':
                return String(arg);
            default:
                return 'null';
            }
        },
        parse: function (text) {
            var at = 0;
            var ch = ' ';

            function error(m) {
                throw {
                    name: 'JSONError',
                    message: m,
                    at: at - 1,
                    text: text
                };
            }

            function next() {
                ch = text.charAt(at);
                at += 1;
                return ch;
            }

            function white() {
                while (ch != '' && ch <= ' ') {
                    next();
                }
            }

            function str() {
                var i, s = '', t, u;

                if (ch == '"') {
    outer:          while (next()) {
                        if (ch == '"') {
                            next();
                            return s;
                        } else if (ch == '\\') {
                            switch (next()) {
                            case 'b':
                                s += '\b';
                                break;
                            case 'f':
                                s += '\f';
                                break;
                            case 'n':
                                s += '\n';
                                break;
                            case 'r':
                                s += '\r';
                                break;
                            case 't':
                                s += '\t';
                                break;
                            case 'u':
                                u = 0;
                                for (i = 0; i < 4; i += 1) {
                                    t = parseInt(next(), 16);
                                    if (!isFinite(t)) {
                                        break outer;
                                    }
                                    u = u * 16 + t;
                                }
                                s += String.fromCharCode(u);
                                break;
                            default:
                                s += ch;
                            }
                        } else {
                            s += ch;
                        }
                    }
                }
                error("Bad string");
            }

            function arr() {
                var a = [];

                if (ch == '[') {
                    next();
                    white();
                    if (ch == ']') {
                        next();
                        return a;
                    }
                    while (ch) {
                        a.push(val());
                        white();
                        if (ch == ']') {
                            next();
                            return a;
                        } else if (ch != ',') {
                            break;
                        }
                        next();
                        white();
                    }
                }
                error("Bad array");
            }

            function obj() {
                var k, o = {};

                if (ch == '{') {
                    next();
                    white();
                    if (ch == '}') {
                        next();
                        return o;
                    }
                    while (ch) {
                        k = str();
                        white();
                        if (ch != ':') {
                            break;
                        }
                        next();
                        o[k] = val();
                        white();
                        if (ch == '}') {
                            next();
                            return o;
                        } else if (ch != ',') {
                            break;
                        }
                        next();
                        white();
                    }
                }
                error("Bad object");
            }

            function num() {
                var n = '', v;
                if (ch == '-') {
                    n = '-';
                    next();
                }
                while (ch >= '0' && ch <= '9') {
                    n += ch;
                    next();
                }
                if (ch == '.') {
                    n += '.';
                    while (next() && ch >= '0' && ch <= '9') {
                        n += ch;
                    }
                }
                if (ch == 'e' || ch == 'E') {
                    n += 'e';
                    next();
                    if (ch == '-' || ch == '+') {
                        n += ch;
                        next();
                    }
                    while (ch >= '0' && ch <= '9') {
                        n += ch;
                        next();
                    }
                }
                v = +n;
                if (!isFinite(v)) {
                    error("Bad number");
                } else {
                    return v;
                }
            }

            function word() {
                switch (ch) {
                    case 't':
                        if (next() == 'r' && next() == 'u' && next() == 'e') {
                            next();
                            return true;
                        }
                        break;
                    case 'f':
                        if (next() == 'a' && next() == 'l' && next() == 's' &&
                                next() == 'e') {
                            next();
                            return false;
                        }
                        break;
                    case 'n':
                        if (next() == 'u' && next() == 'l' && next() == 'l') {
                            next();
                            return null;
                        }
                        break;
                }
                error("Syntax error");
            }

            function val() {
                white();
                switch (ch) {
                    case '{':
                        return obj();
                    case '[':
                        return arr();
                    case '"':
                        return str();
                    case '-':
                        return num();
                    default:
                        return ch >= '0' && ch <= '9' ? num() : word();
                }
            }

            return val();
        }    
    }  
    
};

    // This is the global JS SANJER object, and it's all you'll need.
    var sanjer = new SANJER();

<?PHP
        $jsText = ob_get_contents();  
        ob_end_clean();
        echo $jsText;

        ob_start();
?>

/////////////////////////////////////////////////////
///         End SANJER automatically created JS       
/////////////////////////////////////////////////////  

<?PHP
        $jsText = ob_get_contents();  
        ob_end_clean();
        echo $jsText;  
         		
		exit;
	}



class SANJER{

  var $json;
  var $sajax;
  var $isListening;

///////////////////////////////////////////////////////////////
//
//  function SANJER()
//  description:
//      constructor.
//  accepts:
//      $requestType:   string, optional, default: "GET". The HTTP Type of request.
//      $functionsArr:  array of strings, optional, default:NULL. the names of the functions to register with sajax
//      $remoteUri:     string, optional, default: empty. For use in case the functions AJAX calls are not on the same file as the callers.
//  returns:
//      pointer to object.
//
// Important note: if the functions to register are not given in the constructor,
//                  then first register() and then start_listening() must be called, manually,
//                  in order for the sajax to work properly.
///////////////////////////////////////////////////////////////
  function SANJER($requestType="GET", $functionsArr=NULL, $remoteUri=""){
    $this->isListening = false;
    $this->json = new JSON();
    if(!$this->json){
        die("could not create JSON object");
    }

    $this->sajax = new SAJAX($requestType, $remoteUri);
    $this->sajax->sajax_js_has_been_shown = 1;
    $this->sajax->sajax_init();
    
    if($functionsArr){
      foreach($functionsArr as $functionName){
        $this->register($functionName);
      }
    }

  }


///////////////////////////////////////////////////////////////
//
//  function restart()
//  description: creates new sajax and json objects,
//               and switches the isListening flag off.
//
//  accepts:
//      $requestType:   string, optional, default: "GET". The HTTP Type of request.
//      $functionsArr:  array of strings, optional, default:NULL. the names of the functions to register with sajax
//      $remoteUri:     string, optional, default: empty. For use in case the functions AJAX calls are not on the same file as the callers.
//  returns:
//      nothing.
///////////////////////////////////////////////////////////////
  function restart($requestType="GET", $functionsArr=NULL, $remoteUri=""){
    if(isset($this->sajax)) unset($this->sajax);
    if(isset($this->json)) unset($this->json);

    $this->isListening = false;
    $this->json = new JSON();
    if(!$this->json){
        die("could not create JSON object");
    }

    $this->sajax = new SAJAX($requestType, $remoteUri);
    $this->sajax->sajax_init();

    if($functionsArr){
      foreach($functionsArr as $functionName){
        $this->register($functionName);
      }
    }
    
  }


    
///////////////////////////////////////////////////////////////
//
//  function set_debug_mode()
//  description: sets the sajax debug mode.
//               JSON doesn't have one.
//
//  accepts:
//      $mode:  boolean, optional, default: true. the debug mode: true/false -> on/off.
//
//  returns:
//      nothing.
///////////////////////////////////////////////////////////////
  function set_debug_mode($mode=true){
      $this->sajax->sajax_debug_mode = $mode ? 1 : 0;
  }


///////////////////////////////////////////////////////////////
//
//  function register()
//  description:
//      registers the functions that will work with sajax.
//      a function must be registered to be called by a HTTPRequest.
//
//  accepts:
//      $functionName:  string, mandatory. Name of function to be registered.
//
//  returns:
//      boolean signifying success of registration
///////////////////////////////////////////////////////////////
  function register($functionName){
    $this->sajax->sajax_export($functionName);
  }

///////////////////////////////////////////////////////////////
//
//  function start_listening()
//  description:
//      gets the Sajax to handle HTTPRequests
//  accepts:
//
//  returns:
//
///////////////////////////////////////////////////////////////
  function start_listening(){
    if(!$this->isListening){
        $this->sajax->sajax_handle_client_request();
        $this->isListening = true;
    } 
  }

///////////////////////////////////////////////////////////////
//
//  function json2object()
//  description:
//      takes JSON encoded text and decodes it into a PHP object.
//
//  accepts:
//      $encodedData:   string, mandatory. The JSON encoding of the data.
//      $stripSlashes:  boolean, optional, default: true. Whether to data string requires slash stripping.
//  returns:
//      PHP object to match the object encoded in JSON string.
///////////////////////////////////////////////////////////////
  function json2object($encodedData, $stripSlashes=true){

    $decodedObject = NULL;

    if($stripSlashes){
        $decodedObject = $this->json->decode(stripslashes($encodedData));
    } else {
        $decodedObject = $this->json->decode($encodedData);
    }



    return $decodedObject;

  }

///////////////////////////////////////////////////////////////
//
//  function object2json()
//  description:
//      encodes a given PHP object into a JSON compatible string.
//  accepts:
//      $phpObject: object, mandatory. object to be encoded.
//  returns:
//      string encoding of the object.
///////////////////////////////////////////////////////////////
  function object2json($phpObject){
      $inJson = $this->json->encode($phpObject);   
    return $inJson;
  }


///////////////////////////////////////////////////////////////
//
//  function show_javascript()
//  description:
//      wrapper for Sajax's sajax_show_javascript function
//  accepts:
//      
//  returns:
//      javascript preparing sajax for work
///////////////////////////////////////////////////////////////
  function show_javascript(){


    ob_start();
?>
    <script type="text/javascript" src="<?= basename($_SERVER['SCRIPT_NAME']); ?>?show_common_javascript=1&request_type=<?= $this->sajax->sajax_request_type?>&debug_mode=<?= $this->sajax->sajax_debug_mode?>&remote_uri=<?= $this->sajax->sajax_remote_uri?>"></script>
    <script type="text/javascript">
<?PHP
    $jsText = ob_get_contents();
    ob_end_clean();
    echo $jsText;

//    $this->sajax->sajax_show_javascript();
    echo "</script>\n";
    return;
  }

///////////////////////////////////////////////////////////////
//  request type setter/getter
//  function set_request_type($requestType)
//          $requestType:   string, mandatory, "POST"/"GET".
//  function get_request_type()
///////////////////////////////////////////////////////////////
    function set_request_type($requestType){
      $this->sajax->sajax_request_type = $requestType;
    }
    
    function get_request_type(){
      return $this->sajax->sajax_request_type;
    }
    
///////////////////////////////////////////////////////////////
//  remote URI setter/getter
//  function set_remote_uri($remoteURI)
//          $requestType:   string, optional, default: "".
//  function get_remote_uri()
///////////////////////////////////////////////////////////////
    function set_remote_uri($remoteURI){
      $this->sajax->sajax_remote_uri = $remoteURI;
    }

    function get_remote_uri(){
      return $this->sajax->sajax_remote_uri;
    }
    
}

?>
