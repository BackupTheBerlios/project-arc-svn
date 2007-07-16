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
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.7.15
 */

   class user_model extends Archetype_model
      {
         public $users=array(0=>false);

         public function construct()
            {
               $this->system->depend('model','SeboDB');
               $this->system->depend('model','http');
            }

         public function create()
            {
            }

         public function modify()
            {
            }

         public function delete()
            {
            }

         public function open()
            {
            }

         public function stamp()
            {
            }

         public function close()
            {
            }

         public function password_hash()
            {
            }
      }
?>
