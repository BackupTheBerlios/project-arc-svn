<?php

   ////////////////////////////////////////////////////////////////////
   //                          S e b o D B                           //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * SeboDB Controller: Raw
 *
 * @package SeboDB
 * @subpackage controllers
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/sebodb
 * @version 2007.11.8
 */

/**
 * Raw SeboDB controller.  Identical to the driver interface
 */
   class SeboDB_controller_raw extends SeboDB_controller
      {
      /**
       * 
       * @access public
       * @param 
       * @return 
       */
         public function open($configuration)
            {
               return $this->driver->open($configuration);
            }

      /**
       * 
       * @access public
       * @param 
       * @return 
       */
         public function query($sql,$connection)
            {
               return $this->driver->query($sql,$connection);
            }

      /**
       * 
       * @access public
       * @param 
       * @return 
       */
         public function affected($connection)
            {
               return $this->driver->affected($connection);
            }

      /**
       * 
       * @access public
       * @param 
       * @return 
       */
         public function results($query)
            {
               return $this->driver->results($query);
            }

      /**
       * 
       * @access public
       * @param 
       * @return 
       */
         public function insert_id($query)
            {
               return $this->driver->insert_id($query);
            }

      /**
       * 
       * @access public
       * @param 
       * @return 
       */
         public function escape($string,$connection)
            {
               return $this->driver->escape($string,$connection);
            }

      /**
       * 
       * @access public
       * @param 
       * @return 
       */
         public function fetch($query,$type=SEBODB_ASSOC)
            {
               return $this->driver->fetch($query,$type);
            }

      /**
       * 
       * @access public
       * @param 
       * @return 
       */
         public function free($query)
            {
               return $this->driver->free($query);
            }

      /**
       * 
       * @access public
       * @param 
       * @return 
       */
         public function ping($connection)
            {
               return $this->driver->ping($connection);
            }

      /**
       * 
       * @access public
       * @param 
       * @return 
       */
         public function error($connection)
            {
               return $this->driver->error($connection);
            }

      /**
       * 
       * @access public
       * @param 
       * @return 
       */
         public function close($connection)
            {
               return $this->driver->close($connection);
            }
      }
?>
