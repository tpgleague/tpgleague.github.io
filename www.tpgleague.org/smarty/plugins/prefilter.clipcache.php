<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty clipcache prefilter plugin
 *
 * Clip template source from {clipcache} blocks into a new file and replace
 * them with {include_clipcache} tags that permit isolated caching and private
 * delimiters 
 *
 * @file        prefilter.clipcache.php
 * @version     0.1.7 2006-May-11
 * @since       2005-APR-08
 *
 * @author      boots {jayboots ~ yahoo com}
 * @copyright   brainpower, boots, 2004-2006
 * @license     LGPL 2.1
 * @link        http://www.phpinsider.com/smarty-forum/viewtopic.php?p=19733#19733
 *
 * @param string $source
 * @param Smarty_Compiler $compiler
 *
 * This filter observes the following tag attributes on {clipcache} blocks:
 *
 * #param id required unique id of clipcache block within template
 * #param group required specify cache build group
 * #param ttl required time to live for template part/group
 * #param ldelim optional specify the left delimiter to use for included content
 * #param rdelim optional specify the right delimiter to use for included content
 */
function smarty_prefilter_clipcache($source, &$compiler)
{
    // setup
    require_once $compiler->_get_plugin_filepath( 'outputfilter', 'trimwhitespace' );
    $ld = $compiler->left_delimiter;
    $rd = $compiler->right_delimiter;
    $search = "{$ld}\s*clipcache\s+(.*?)\s*{$rd}(.*){$ld}\s*/clipcache\s+\\1{$rd}";

    // Pull out the clip blocks
    preg_match_all( "!$search!is", $source, $clip_blocks );
    $i=0;
    $replacements = array();

    foreach ( $clip_blocks[1] as $tag_attrs ) {
        $params = $compiler->_parse_attrs( $tag_attrs );

        foreach ( array( 'group'=>'cache_id', 'id'=>'id', 'ttl'=>'cache_lifetime' ) as $required=>$mapto ) {

            if ( !array_key_exists( $required, $params ) ) {
                $compiler->_syntax_error( "clipcache: '$required' param missing. Aborted.", E_USER_WARNING );

                return;

            } else {
                $$mapto = $params[$required];

                if ( substr( $$mapto, 0, 1 ) == "'" ) {
                    $$mapto = substr( $$mapto, 1, strlen( $$mapto ) - 2 );
                }
            }
        }

        foreach ( array( 'rdelim'=>$rd, 'ldelim'=>$ld ) as $optional=>$default ) {
            ${"_{$optional}"} = $default;
            $$optional = ( array_key_exists( $optional, $params ) )
                ? substr( $params[$optional], 1, strlen( $params[$optional] ) - 2 )
                : $default;
        }

    	// write the clip block file source template
        $write_path = rtrim( $compiler->compile_dir, "/\\" ) . DIRECTORY_SEPARATOR; 

        $file_name = $compiler->_current_file.'#' . $id;
        require_once SMARTY_CORE_DIR . 'core.write_file.php';
        smarty_core_write_file( array( 'filename'=>$write_path . 'clipcache' . DIRECTORY_SEPARATOR . $file_name, 'contents'=>$clip_blocks[2][$i++], 'create_dirs'=>true ), $compiler );

	    // prepare replacement source for the clip block
	    if ( $ldelim == $ld && $rdelim == $rd ) {
            $replacements[] = $ld . 'include_clipcache file="' . $file_name . '" cache_id="' . $cache_id . '" cache_lifetime=' . $cache_lifetime . $rd;

        } else {
            $replacements[] = $ld . 'include_clipcache file="' . $file_name . '" cache_id="' . $cache_id . '" cache_lifetime=' . $cache_lifetime . ' ldelim="' . $ldelim . '" rdelim="' . $rdelim.'"' . $rd;
        }
    }

    // replace clip blocks
    $source = preg_replace( "!$search!is", '@@@SMARTY:CLIPCACHE@@@', $source );
    smarty_outputfilter_trimwhitespace_replace( "@@@SMARTY:CLIPCACHE@@@", $replacements, $source );

    return $source;
}
?>