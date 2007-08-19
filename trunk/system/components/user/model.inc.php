<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * User model
 *
 * @package Archetype
 * @subpackage user
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright � 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.7.15
 */

   class user_model extends A_model
      {
         public $users=array(0=>false);

         public function construct()
            {
               $this->system->config('user',$this,array('hash'));
            }

         public function create_user()
            {
            }

         public function modify_user()
            {
            }

         public function delete_user()
            {
            }

         public function create_group()
            {
            }

         public function modify_group()
            {
            }

         public function delete_group()
            {
            }

         public function open()
            {
            }

         public function stamp()
            {
            }

         public function halfstamp()
            {
            }

         public function unstamp()
            {
            }

         public function close()
            {
            }
      }
?>
