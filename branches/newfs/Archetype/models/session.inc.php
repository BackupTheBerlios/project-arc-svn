<?php if(!defined('A_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Session interaction is abstracted so we can change it later if need
 * be.
 *
 * @package Archetype
 * @subpackage session
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/sebodb
 * @version 2007.11.9
 */

/**
 * Simple session abstraction so it's cleaner to access in Archetype
 */
   class A_session_model extends A_model
      {
      /**
       * Constructor creates the session, converts session data to live data stored in this class
       * @access public
       * @return void
       */
         public function construct()
            {
               session_start();

               foreach($_SESSION as $key=>$value)
                  {
                     if(!isset($this->$key))
                        {
                           $this->$key=$value;
                        }
                  }
            }

      /**
       * Resets the session, destroying all data stored in it
       * @access public
       * @return boolean Always returns true
       */
         public function clean()
            {
            // Destroy PHP's session
               session_destroy();

            // Remove current instances
               foreach($this->fetch_all() as $key=>$value)
                  {
                     unset($this->$key);
                  }

               return true;
            }

      /**
       * Returns all data stored in this current session
       * @access public
       * @return array Contents stored inside the current session
       */
         public function fetch_all()
            {
               $r=get_object_vars($this);

            // Get rid of stuff Archetype uses inside of this class
               unset($r['_']);
               unset($r['system']);
               unset($r['cleanup']);

               return $r;
            }

      /**
       * Writes data stored in the session to a storage medium
       * @access public
       * @return void
       */
         public function destruct()
            {
               foreach($this->fetch_all() as $key=>$value)
                  {
                     if($key!=='_'&&$key!=='system'&&$key!=='cleanup')
                        {
                           $_SESSION[$key]=$value;
                        }
                  }
            }
      }
?>
