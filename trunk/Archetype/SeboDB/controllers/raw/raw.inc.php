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
 * @version 2007.4.1
 */

/**
 * Raw SeboDB controller.  Identical to the driver interface except it manages connections and resources automatically.
 */
   class SeboDB_controller_raw extends SeboDB_controller
      {
      /**
       * Stores the result resource from the last query made so the class can automatically resolve the result
       * @access private
       * @var resource
       */
         private $query=false;

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param array $configuration Array of information required to connect
       * @return boolean True if success, false otherwise
       */
         public function open($configuration)
            {
               return $this->connection=$this->driver->open($configuration);
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function query($sql,$connection=false)
            {
            // Automagically resolve the connection if one wasn't specified
               if(empty($connection))
                  {
                     $connection=$this->driver->connection;
                  }

            // Default the table prefix so we still replace the tokens in the query even if one wasn't specified in the configuration for the connection
               $prefix='';
               if(!empty($this->driver->configuration['prefix']))
                  {
                     $prefix=$this->driver->configuration['prefix'];
                  }

            // Replace prefix tokens
               $sql=preg_replace('/\^(\w)/',$this->driver->configuration['prefix'].'$1',$sql);

            // Assign the query return to $this->query for autoresolution
               return $this->query=$this->driver->query($sql,$connection);
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function affected($connection=false)
            {
            // Autoresolve the query if one isn't specified
               if(empty($connection))
                  {
                     $connection=$this->driver->connection;
                  }

               return $this->driver->affected($connection);
            }

      /**
       * Returns the number of results from the last query
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function results($query=false)
            {
            // Autoresolve the query if one isn't specified
               if(empty($query))
                  {
                     $query=$this->query;
                  }

               return $this->driver->results($query);
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function insert_id($query=false)
            {
            // Autoresolve the query if one isn't specified
               if(empty($query))
                  {
                     $query=$this->query;
                  }

               return $this->driver->insert_id($query);
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function escape($string,$connection=false)
            {
            // Automagically resolve the connection if one wasn't specified
               if(empty($connection))
                  {
                     $connection=$this->driver->connection;
                  }

               return $this->driver->escape($string,$connection);
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function fetch($type=SEBODB_ASSOC,$query=false)
            {
            // Autoresolve the query if one isn't specified
               if(empty($query))
                  {
                     $query=$this->query;
                  }

               return $this->driver->fetch($query,$type);
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function free($query=false)
            {
            // Autoresolve the query if one isn't specified
               if(empty($query))
                  {
                     $query=$this->query;
                  }

               return $this->driver->free($query);
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function ping($connection=false)
            {
            // Automagically resolve the connection if one wasn't specified
               if(empty($connection))
                  {
                     $connection=$this->driver->connection;
                  }

               return $this->driver->ping($connection);
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function error($connection=false)
            {
            // Automagically resolve the connection if one wasn't specified
               if(empty($connection))
                  {
                     $connection=$this->driver->connection;
                  }

               return $this->driver->error($connection);
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function close($connection=false)
            {
            // Automagically resolve the connection if one wasn't specified
               if(empty($connection))
                  {
                     $connection=$this->driver->connection;
                  }

               return $this->driver->close($connection);
            }
      }
?>
