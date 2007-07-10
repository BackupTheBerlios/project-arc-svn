<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Routes config.  For specifying system routes and overwriting and
 * aliasing routes.  Applies only to controllers.
 *
 * @package Archetype
 * @subpackage config
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.com/archetype
 * @version 2007.4.1
 */


// System routes
   $routes['default']=  'system/welcome';
   $routes['error']=    'system/error';
   $routes['not_found']='system/not_found';
   $routes['forbidden']='system/forbidden';

// Aliases
   $routes['aliases']['/^router.*$/']=&$routes['forbidden']; // We don't want people directly accessing the router controller
?>
