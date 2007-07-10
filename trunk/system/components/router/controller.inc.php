<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                       www.SeboCorp.com                         //
   ////////////////////////////////////////////////////////////////////

/**
 * Router controller
 *
 * @package Archetype
 * @subpackage system
 * @author Justin Krueger <justin@sebocorp.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://sebocorp.com/archetype
 * @version 2007.4.1
 */

/**
 * Shouldn't be externally available (blocked by default in the routes config)
 */
   class system_controller extends Archetype_controller
      {
         public function redirect($url)
            {
            // We need a http model or something to handle redirects and http headers and blahb lah
            }
      }
?>
