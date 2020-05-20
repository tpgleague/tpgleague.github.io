<?php

ini_set('include_path',
        implode(PATH_SEPARATOR,
                array(realpath(dirname(__FILE__)),
                      realpath('../../common_includes'),
                      realpath('../../common_includes/pear'),
                      realpath('../../common_includes/Smarty'),
                      realpath('../../../common_includes'),
                      realpath('../../../common_includes/pear'),
                      realpath('../../../common_includes/Smarty'),
                      ini_get('include_path')
                     )
               )
       );
       
