<?php

   ////////////////////////////////////////////////////////////////////
   //                          S e b o D B                           //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * MySQL driver for SeboDB
 *
 * @package SeboDB
 * @subpackage drivers
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/sebodb
 * @version 2007.7.29
 */

/**
 * MySQL driver for SeboDB
 */
   class SeboDB_driver_mysql extends SeboDB_driver
      {
      /**
       * Check for the PHP MySQL extension
       * @access public
       * @return void
       */
         public function __construct()
            {
               if(!function_exists('mysql_connect'))
                  {
                     throw new SeboDBDriverException('MySQL driver requires PHP to have the MySQL extension installed functioning');
                  }
            }

      /**
       * Attempts to open a connection to a MySQL database with the information provided.
       * Requires controller, driver, host, user, pass, name and optionally allows persistent, port, socket, prefix 
       * @access public
       * @param array $config Associative array of information required to connect
       * @return mixed Returns a connection resource on success, false on failure
       */
         public function open(&$config)
            {
               $r=false;

            // Store the configuration
               $this->configuration=&$config;

            // If configuration is valid, try to connect
               if($this->check_config($config))
                  {
                  // Decide our port, default if none specified
                     $port=3306;
                     if(empty($config['port']))
                        {
                           $port=&$config['port'];
                        }

                  // Default a prefix if one isn't specified
                     if(empty($config['prefix']))
                        {
                           $config['prefix']='';
                        }

                  // Connect and assign $this->connection
                     if(empty($config['persistent']))
                        {
                           $r=@mysql_connect($config['host'].':'.$port,$config['user'],$config['pass']);
                        }
                     else
                        {
                           $r=@mysql_pconnect($config['host'].':'.$port,$config['user'],$config['pass']);
                        }

                  // Gracefully inform that an error has happened while trying to connect
                     if(!is_resource($r))
                        {
                           throw new SeboDBDriverException('Could not connect to "'.$config['host'].'", MySQL says: '.mysql_error());
                        }
                  // Select the database we'll want to work with
                     else
                        {
                        // Save a reference to our connection so it's easy to transport
                           $this->connection=&$r;

                           if(!mysql_select_db($config['name'],$this->connection))
                              {
                                 throw new SeboDBDriverException('Could not select database "'.$config['name'].'"');
                              }
                        }
                  }

               return $r;
            }

      /**
       * Times, records and runs a query
       * @access public
       * @param string $sql SQL string to run
       * @param resource $connection Connection resource
       * @return bool True if successful false otherwise
       */
         public function query($sql,&$connection)
            {
               $r=false;

            // Start the query timer
               $time=microtime(true);

            // Run the query and assign outcome, throw an exception if it failed
               if(!$r=@mysql_query($sql,$connection))
                  {
                     throw new SeboDBDriverException('Query "'.$sql.'" failed, MySQL says: '.mysql_error());
                  }
               else
                  {
                  // Record the query
                     $this->history[]=array('query'=>$sql,'execution_time'=>microtime(true)-$time);
                  }

               return $r;
            }

      /**
       * Retrieves the amount of affected rows by the last query
       * @access public
       * @param resource $connection Connection resource
       * @return bool Numeric value representing affected rows if successful, false otherwise
       */
         public function affected(&$connection)
            {
               $r=false;

               if(($r=@mysql_affected_rows($connection))===false)
                  {
                     $this->connection_exception();
                  }

               return $r;
            }

      /**
       * Retrieves the amount of results returned by the last query
       * @access public
       * @param resource $query Query resource
       * @return bool Numeric value representing the amount of rows returned if successful, false otherwise
       */
         public function results(&$query)
            {
               $r=false;

               if(($r=@mysql_num_rows($query))===false)
                  {
                     $this->query_exception();
                  }

               return $r;
            }

      /**
       * Retrieves the last insert id
       * @access public
       * @param resource $connection Connection resource
       * @return bool Numeric value of last insert id if successful, false otherwise
       */
         public function insert_id(&$connection)
            {
               $r=false;

               if(($r=@mysql_insert_id($connection))===false)
                  {
                     $this->connection_exception();
                  }

               return $r;
            }

      /**
       * Escapes a string for insertion into a database
       * @access public
       * @param string $string String to be escaped
       * @param resource $connection Connection resource
       * @return mixed Returns an escaped string on success, false otherwise
       */
         public function escape(&$string,&$connection)
            {
               $r=false;

               if(($r=@mysql_real_escape_string($string,$connection))===false)
                  {
                     $this->connection_exception();
                  }

               return $r;
            }

      /**
       * Fetches a row from the query resource provided
       * @access public
       * @param resource $query Query resource
       * @param integer $type Use a SEBODB_ constant to have the function fetch different types of data
       * @return mixed Returns the type of data specified by $type, an associative array by default, if successful, false otherwise
       */
         public function fetch(&$query,$type=SEBODB_ASSOC)
            {
               $r=false;

            // Return what $type asks for
               if($type===SEBODB_ARRAY)
                  {
                     $r=@mysql_fetch_row($query);
                  }
               elseif($type===SEBODB_ASSOC)
                  {
                     $r=@mysql_fetch_assoc($query);
                  }
               elseif($type===SEBODB_OBJECT)
                  {
                     $r=@mysql_fetch_object($query);
                  }
               elseif($type===SEBODB_FIELD)
                  {
                     $r=@mysql_fetch_field($query);
                  }
               elseif($type===SEBODB_LENGTHS)
                  {
                     $r=@mysql_fetch_lengths($query);
                  }
               else
                  {
                     throw new SeboDBDriverException('Return type specified for fetch() is invalid');
                  }

               return $r;
            }

      /**
       * Frees the memory associated with a result
       * @access public
       * @param resource $query Query resource
       * @return bool True on success false otherwise
       */
         public function free(&$query)
            {
               $r=false;

               if(!$r=@mysql_free_result($query))
                  {
                     $this->query_exception();
                  }

               return $r;
            }

      /**
       * Ping a server connection or reconnect if there is no connection
       * @access public
       * @param resource $connection Connection resource
       * @return bool True if success false otherwise
       */
         public function ping(&$connection)
            {
               $r=false;

               if(!$r=@mysql_ping($connection))
                  {
                     $this->connection_exception();
                  }

               return $r;
            }

      /**
       * Returns the last error from the driver
       * @access public
       * @param resource $connection Connection resource
       * @return string Returns the error if there was one or an empty string if there wasn't
       */
         public function error(&$connection)
            {
               $r=false;

               if(!$r=@mysql_error($connection))
                  {
                     $this->connection_exception();
                  }

               return $r;
            }

      /**
       * Close the connection to the database
       * @access public
       * @param resource $connection Connection resource
       * @return bool True on success false otherwise
       */
         public function close(&$connection)
            {
               $r=false;

               if(!$r=@mysql_close($connection))
                  {
                     $this->connection_exception();
                  }

               return $r;
            }

      /**
       * Check the config and act accordingly
       * @access private
       * @param array $info Array of information required to connect
       * @return bool True if the config has the correct information in it false otherwise
       */
         private function check_config(&$config)
            {
               $r=true;
               $required=array('controller','driver','host','user','pass','name');

               foreach($required as $value)
                  {
                     if(!isset($config[$value]))
                        {
                           throw new SeboDBDriverException('Configuration directive "'.$value.'" is required to connect');
                           $r=false;
                        }
                  }

               return $r;
            }

      /**
       * Throw a bad-connection exception
       * @access private
       * @return void
       */
         private function connection_exception()
            {
               throw new SeboDBDriverException('Connection resource provided is invalid');
            }

      /**
       * Throw a bad-query exception
       * @access private
       * @return void
       */
         private function query_exception()
            {
               throw new SeboDBDriverException('Query resource provided is invalid');
            }
      }
?>
