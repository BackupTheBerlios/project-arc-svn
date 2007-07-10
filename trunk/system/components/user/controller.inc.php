<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * User controller
 *
 * @package Archetype
 * @subpackage user
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.4.1
 */

/**
 * User output class
 */
   class user_controller extends Archetype_controller
      {
         public function index($id=false)
            {
               $this->settings();
            }

         public function settings()
            {
               $this->system->model('Smarty',&$this);

               echo($this->Smarty->fetch('user/index.t'));
            }

         public function profile($id=false)
            {
            }

         public function validate()
            {
            }

         public function register()
            {
            }
      }
?>
