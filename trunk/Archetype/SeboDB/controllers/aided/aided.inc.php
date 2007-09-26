<?php

   ////////////////////////////////////////////////////////////////////
   //                          S e b o D B                           //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * SeboDB Controller: Aided
 *
 * @package SeboDB
 * @subpackage controllers
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/sebodb
 * @version 2007.7.29
 */

/**
 * Aided SeboDB controller.  Basically a raw interface with a few really useful perks
 */
   class SeboDB_controller_aided extends SeboDB_controller
      {
      /**
       * Stores the result resource from the last query made so the class can automatically resolve the result
       * @access private
       * @var resource
       */
         private $query=false;

      /**
       * Information intentionally chosen to be sticky gets stored here to be updated when the class destructs
       * @access public
       * @var array
       * @TODO For the love of God, finish this feature.  It will be so awesome.
       */
         public $sticky=array();

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param array $config Reference to an Array of information required to connect
       * @return mixed Returns a reference to the connection on success and false on failure
       */
         public function open(&$config)
            {
               return $this->driver->open($config);
            }

      /**
       * Runs specified query and stores the resulting resource
       * @access public
       * @param string $sql SQL code to run
       * @param string $connection Optionally reference a specific connection to use
       * @return mixed Returns a reference to the query resource on success and false on failure
       */
         public function query($sql,$input=false)
            {
            // Really nifty idea Jacob showed me - it makes writing dynamic SQL a lot cleaner in most cases
               if(is_array($input))
                  {
                     foreach($input as &$element)
                        {
                           $element=$this->escape($element);
                        }

                     array_unshift($input,$sql);

                     $sql=call_user_func_array('sprintf',$input);
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
               return $this->query=$this->driver->query($sql,$this->driver->connection);
            }

      /**
       * Returns the amount of rows affected by a query
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function affected()
            {
               return $this->driver->affected($this->driver->connection);
            }

      /**
       * Returns the number of results from a query
       * @access public
       * @param resource $query Optionally provide the query resource to use
       * @return boolean True if success, false otherwise
       */
         public function results(&$query=false)
            {
            // Autoresolve the query if one isn't specified
               if(empty($query))
                  {
                     $query=$this->query;
                  }

               return $this->driver->results($query);
            }

      /**
       * ID of last insert
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function insert_id()
            {
               return $this->driver->insert_id($this->driver->connection);
            }

      /**
       * Escape a string to be inserted into SQL, making it secure
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function escape(&$input)
            {
               $r=false;

            // If it's an array, loop it
               if(is_array($input)&&!empty($input))
                  {
                     $r=true;

                     foreach($input as &$element)
                        {
                           if(is_array($element))
                              {
                                 $this->escape($element);
                              }
                           else
                              {
                                 $element=$this->escape($element);
                              }
                        }
                  }
            // If it's a string, trim it
               elseif(is_string($input)&&!empty($input))
                  {
                     $r=$this->driver->escape($input,$this->driver->connection);
                  }

               return $r;
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @param string $name Database name
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function fetch($shrink=true,$type=SEBODB_ASSOC,$query=false)
            {
               $r=false;

            // Autoresolve the query if one isn't specified
               if(empty($query))
                  {
                     $query=$this->query;
                  }


               if($results=$this->driver->results($query))
                  {
                     $r=array();

                     while($row=$this->driver->fetch($query,$type))
                        {
                           $r[]=$row;
                        }
                  }

            // If only one record is returned, don't put it inside another array
               if($shrink&&$results===1)
                  {
                     $r=&$r[0];
                  }

               return $r;
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function sticky()
            {
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function commit_sticky()
            {
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function free(&$query=false)
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
         public function ping()
            {
               return $this->driver->ping($this->driver->connection);
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       */
         public function error()
            {
               return $this->driver->error($this->driver->connection);
            }

      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param string $name Database name
       * @return boolean True if success, false otherwise
       * @todo add sticky commit before shutdown
       */
         public function close()
            {
               return $this->driver->close($this->driver->connection);
            }
      }
?>
