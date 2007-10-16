<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Global information automatically available to any and all views
 *
 * @package Archetype
 * @subpackage config
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.com/archetype
 * @version 2007.9.12
 */

   $settings['global']['webroot']='http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,-strlen('index.php'));

// Should probably figure out where the first request for this config is and somehow get it behind the declaration of $_GET['a'] so I can kill the logic
   if(!empty($_GET))
      {
         $settings['global']['self']=$settings['global']['webroot'].$_GET['a'];
      }
   else
      {
         $settings['global']['self']=&$settings['global']['webroot'];
      }
?>
