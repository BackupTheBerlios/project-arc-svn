<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * HTTP model
 *
 * @package Archetype
 * @subpackage http
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.4.25
 */

/**
 * Makes interaction with HTTP easier and symmetric
 */
   class http_model extends A_model
      {
         public function construct()
            {
               $this->system->config('cookie',$this,array('prefix','expire','path','domain','https_only','header_only'));
            }

         public function location()
            {
            }

         public function refresh()
            {
            }

         public function &cookie($name,$value=false)
            {
               $r=false;

               if(is_string($name)&&is_string($value))
                  {
                     $r=setcookie($this->config['cookie']['prefix'].$name,$value,$this->config['cookie']['expire'],$this->config['cookie']['path'],$this->config['cookie']['domain'],$this->config['cookie']['https_only'],$this->config['cookie']['header_only']);
                  }
               elseif(is_string($name)&&!is_string($value)&&!empty($_COOKIE[$this->config['cookie']['prefix'].$name]))
                  {
                     $r=&$_COOKIE[$this->config['cookie']['prefix'].$name];
                  }

               return $r;
            }
      }
?>
