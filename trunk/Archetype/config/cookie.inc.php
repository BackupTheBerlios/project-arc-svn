<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Cookie config
 *
 * @package Archetype
 * @subpackage config
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.com/archetype
 * @version 2007.4.25
 */

   $cookie['prefix']=     'Archetype_'; // Name prefix
   $cookie['expire']=     time()+(60*60*24*365); // Expire (1 year default)
   $cookie['path']=       '/'; // Path for cookies
   $cookie['domain']=     $_SERVER['HTTP_HOST']; // Domain for cookies
   $cookie['https_only']= false; // Make cookies only accessible through HTTPS
   $cookie['header_only']=false; // Make cookies only accessible through server-side
?>
