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
 * @copyright � 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.com/archetype
 * @version 2007.4.13
 */

   $views['global']['webroot']='http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,(strlen($_SERVER['PHP_SELF'])-strlen('index.php')));
   $views['global']['archetype_version']=ARCHETYPE_VERSION;
   $views['global']['server_ip']=$_SERVER['SERVER_ADDR'];
   $views['global']['client_ip']=$_SERVER['REMOTE_ADDR'];
?>
