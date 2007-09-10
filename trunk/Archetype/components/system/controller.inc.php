<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * System controller
 *
 * @package Archetype
 * @subpackage system
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.4.1
 */

/**
 * System controller class - Never intended to be run from users - only to be run internally
 * as a result. The .htaccess file blocks it, but if you aren't using apache or don't have
 * .htaccess files enabled users will be able to toy around with these pages ... not that they'll
 * get anywhere.  It just looks less professional.
 */
   class system_controller extends A_controller
      {
         public function error($error)
            {
               echo($this->system->view('system/error',array('error'=>$error)));
            }

         public function info()
            {
               echo($this->system->view('system/info'));
            }

         public function not_found($controller,$method,$parameters)
            {
               $input=array('controller'=>$controller,
                            'method'=>$method,
                            'parameters'=>$parameters);

               echo($this->system->view('system/not_found',$input));
            }

         public function forbidden()
            {
               echo($this->system->view('system/forbidden'));
            }

         public function welcome()
            {
               $this->system->model('http',$this);
               echo($this->system->view('system/welcome'));
            }
      }
?>
