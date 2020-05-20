<?php

/**
 * Smarty Addon
 * @package Smarty
 */

/**
 * Smarty_ClipCache (Smarty)
 *
 * Extended Smarty class to manage {clipache} plugin functionality.
 *
 * It is important to configure the Smarty instance before calling register_clipcache().
 * A few caveats:
 *     - resources are not supported
 *     - multiple template dirs are not supported
 *     - absolute template paths are only supported by using the helper method
 *       absolute2relative() to first find the relative path for the template.
 *       This feature only works so long as the template_dir is given as an
 *       absolute path and that the given absolute template path is in fact
 *       inside the template_dir's path.       
 *
 * @file        Smarty_ClipCache.class.php
 * @version     0.1.7 2006-May-11
 * @since       2005-APR-08
 *
 * @author      boots {jayboots ~ yahoo com}
 * @copyright   brainpower, boots, 2004-2006
 * @license     LGPL 2.1
 * @link        http://www.phpinsider.com/smarty-forum/viewtopic.php?p=19733#19733
 */
class Smarty_ClipCache extends Smarty
{
    function Smarty_ClipCache()
    {
        $this->Smarty();
    }

   
    function register_clipcache()
    {
        $this->load_filter( 'pre', 'clipcache' );
        require_once $this->_get_plugin_filepath( 'function', 'include_clipcache' );
        $this->register_function( 'include_clipcache', 'smarty_function_include_clipcache', false );
        $write_path = rtrim( $this->compile_dir, "/\\" ) . DIRECTORY_SEPARATOR . 'clipcache' . DIRECTORY_SEPARATOR;
        $this->template_dir = (array)$this->template_dir;

        if ( !in_array( $write_path, $this->template_dir ) ) {
            $this->template_dir[] = $write_path;
        }
    } 

   
    function unregister_clipcache()
    {
        $this->template_dir = $this->template_dir[0];
        $this->unregister_prefilter( 'clipcache' );
        $this->unregister_function( 'include_clipcache' );
    }


    function absolute2relative( $path )
    {
        $template_dir = ( is_array( $this->template_dir ) )
            ? $this->template_dir[0]
            : $this->template_dir;

        if ( !in_array( substr( $template_dir, 0, 1 ), array( '/', '\\' ) ) ) {
            // if template_dir path is already relative then we throw a fatal error
            // since we can not discover the base path with certainty
            $this->trigger_error( "The template_dir path '{$template_dir}' must be an absolute path. ", E_USER_ERROR );
        }

        if ( in_array( substr( $path, 0, 1 ), array( '/', '\\' ) ) ) {

            if ( substr( $path, 0, strlen( $template_dir ) == $template_dir ) ) {
                $result = substr( $path, strlen( $template_dir ) );

            } else {
                // we don't know this template path so we throw a fatal error
                $this->trigger_error( "The path for '{$path}' is not in the template path ('{$template_dir}'). ", E_USER_ERROR );
            }

        } else {
            // if recieved path is already relative, do nothing
            $result = $path;
        }

        return $result;
    }
}
?>