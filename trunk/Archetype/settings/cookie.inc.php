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

   $settings['prefix']=     'Archetype_'; // Name prefix
   $settings['expire']=     time()+(60*60*24*365); // Expire (1 year default)
   $settings['path']=       '/'; // Path for cookies
   $settings['domain']=     false; // Domain for cookies
   $settings['https_only']= false; // Make cookies only accessible through HTTPS
   $settings['header_only']=false; // Make cookies only accessible through server-side
?>
