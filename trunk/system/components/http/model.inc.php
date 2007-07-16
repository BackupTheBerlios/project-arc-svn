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
   class http_model extends Archetype_model
      {
         public function construct()
            {
               $this->cookie_config=&$this->system->config('cookie',array('prefix','expire','path','domain','https_only','header_only'));
            }

         public function location()
            {
            }

         public function refresh()
            {
            }

         public function browser()
            {
            }

         public function &cookie($name,$value=false)
            {
               $r=false;

               if(is_string($name)&&!empty($value))
                  {
                     $r=setcookie($this->cookie_config['prefix'].$name,$value,$this->cookie_config['expire'],$this->cookie_config['path'],$this->cookie_config['domain'],$this->cookie_config['https_only'],$this->cookie_config['header_only']);
                  }
               elseif(is_string($name))
                  {
                     $r=&$_COOKIE[$this->cookie_config['prefix'].$name];
                  }

               return $r;
            }
      }
?>
