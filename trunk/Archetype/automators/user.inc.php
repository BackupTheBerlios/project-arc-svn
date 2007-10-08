<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * User automator
 *
 * @package Archetype
 * @subpackage user
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.9.10
 */

   $construct=100;

/**
 * Handles user information automatically
 */
   class user_automator extends A_automator
      {
      /**
       * Checks user credentials and loads up an account if they're good
       * @access public
       * @return void
       */
         public function construct()
            {
               $this->system->model('user',$this);
               $this->system->model('http',$this);

            // Session > cookies in this case
               if(!empty($_SESSION['email'])&&!empty($_SESSION['password_hash']))
                  {
                     $email=$_SESSION['email'];
                     $password_hash=$_SESSION['password_hash'];
                  }
               else
                  {
                     $email=$this->http->cookie('email');
                     $password_hash=$this->http->cookie('password_hash');
                  }

            // Let's try opening an account with the provided information
               if(!empty($email)&&!empty($password_hash))
                  {
                  // Unstamp cookie and session information if it is incorrect
                     if(!$this->user->open($email,$password_hash,true))
                        {
                           $this->user->unstamp();
                        }
                  }
            }
      }
?>
