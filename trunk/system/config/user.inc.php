<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * User config
 *
 * @package Archetype
 * @subpackage config
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.com/archetype
 * @version 2007.8.19
 */

// Salt by which user passwords will be encrypted - do not change once users exist
   $user['hash_salt']='3j.^r9%#';

// Enable / disable user login
   $user['login_enabled']=true;

// Enable / disable user registration
   $user['registration_enabled']=true;

// Not implemented
   $user['validation_enabled']=false;

// Not implemented
   $user['captcha_enabled']=false;
?>
