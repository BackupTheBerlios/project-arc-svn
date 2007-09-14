<?php

   ////////////////////////////////////////////////////////////////////
   //                          S e b o D B                           //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * SeboDB Controller: Simple
 *
 * @package SeboDB
 * @subpackage controllers
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/sebodb
 * @version 2007.4.29
 */

/**
 * Simple SeboDB controller.  Designed to make data handling extremely easy and fast.
 * @todo Completely remove all interactions with raw SQL and replace them with simple array-based mechanisms
 */
   class SeboDB_controller_simple extends SeboDB_controller
      {
      /**
       * Opens a connection to a data source through the driver.
       * @access public
       * @param array $configuration Array containing the connection configuration
       * @return boolean True if success, false otherwise
       */
         public function open($configuration)
            {
               return $this->driver->open($configuration);
            }

      /**
       * Insert data.
       * @access public
       * @param string $table Name of the table you want to insert into
       * @param array $map Array containing a column/value map using the array's keys as column names and values as row values
       * @return boolean True if success, false otherwise
       */
         public function insert($table,$map)
            {
               $r=false;

               $left='INSERT INTO '.$this->driver->configuration['prefix'].$table.' (';
               $right=') VALUES (';

               $x=1;
               $y=count($map);
               foreach($map as $index=>$value)
                  {
                     $left.='`'.$index.'`';
                     $right.="'".$this->driver->escape($value,$this->driver->connection)."'";

                     if($x<$y)
                        {
                           $left.=',';
                           $right.=',';
                        }

                     ++$x;
                  }

               $right.=')';

               if($this->driver->query($left.$right,$this->driver->connection))
                  {
                     $r=$this->driver->insert_id($this->driver->connection);
                  }

               return $r;
            }

      /**
       * Update data.
       * @access public
       * @param string $table Name of the table you want to update
       * @param array $map Array containing a column/value map using the array's keys as column names and values as row values
       * @param string $where Optional, syntactically valid SQL WHERE clause
       * @return boolean True if success, false otherwise
       */
         public function update($table,$map,$where=false)
            {
               $sql='UPDATE '.$this->driver->configuration['prefix'].$table.' SET ';

               $x=1;
               $y=count($map);
               foreach($map as $index=>$value)
                  {
                     $sql.='`'.$index.'`="'.$this->driver->escape($value,$this->driver->connection).'"';

                     if($x<$y)
                        {
                           $sql.=',';
                        }

                     $x++;
                  }

            // Add an automatic WHERE to the where clause for convenience
               if(!empty($where))
                  {
                     $sql.=' WHERE '.$where;
                  }

               return $this->driver->query($sql,$this->driver->connection);
            }

      /**
       * Delete data.
       * @access public
       * @param string $table Name of the table that contains data you want to delete
       * @param array $map Associative array of columns to match
       * @return boolean True if success, false otherwise
       */
         public function delete($table,$where=array())
            {
               $r=false;

            // Default the string
               $sql='DELETE FROM '.$this->driver->configuration['prefix'].$table.' WHERE ';

            // Map the $where parameter to the end of the $sql string
               if(is_array($where))
                  {
                     $x=1;
                     $y=count($where);
                     foreach($where as $index=>$value)
                        {
                           $sql.='`'.$index.'`="'.$this->driver->escape($value,$this->driver->connection).'"';

                           if($x<$y)
                              {
                                 $sql.=' AND ';
                              }

                           $x++;
                        }
                  }

            // Run the query and assign a return
               if($this->driver->query($sql,$this->driver->connection))
                  {
                     $r=true;
                  }

               return $r;
            }

      /**
       * Fetch data.
       * @access public
       * @param string $table Name of the table you want to insert into
       * @param mixed $columns
       * @param array $where Optional column=>value associative array
       * @return mixed Returns whatever specified by $type (SEBODB_ASSOC,SEBODB_ARRAY,SEBODB_OBJECT,SEBODB_FIELD,SEBODB_LENGTHS)
       * @todo finish the where conversion
       */
         public function fetch($table,$columns=false,$where=array(),$limit=false,$type=SEBODB_ASSOC)
            {
               $r=false;

            // If $columns is an array, loop it and create a string of columns
               if(is_array($columns))
                  {
                     $column_sql='';
                     $x=1;
                     $y=count($columns);
                     foreach($columns as $column)
                        {

                           $column_sql.='`'.$column.'`';

                           if($x<$y)
                              {
                                 $column_sql.=',';
                              }

                           $x++;
                        }
                  }
            // If $columns is a literal string, just carry it over
               elseif(is_string($columns))
                  {
                     $column_sql='`'.$columns.'`';
                  }

            // Limit
               if(!empty($limit))
                  {
                     if(is_int($limit))
                        {
                           $limit=' LIMIT '.$limit;
                        }
                     elseif(is_array($limit))
                        {
                           sort($limit);
                           $limit_sql=' LIMIT '.(int)$limit[0].','.(int)$limit[1];
                        }
                  }

            // If there are no columns specified, grab them all
               if(empty($column_sql))
                  {
                     $column_sql='*';
                  }

            // Default the where
               if(empty($where_sql))
                  {
                     $where_sql='';
                  }
            // Or add a WHERE clause to the front
               else
                  {
                     $where_sql='WHERE '.$where_sql;
                  }

            // Do the query
               $x=$this->driver->query('SELECT '.$column_sql.' FROM '.$this->driver->configuration['prefix'].$table.$where_sql.$limit_sql,$this->driver->connection);

               if($x)
                  {
                     $r=array();
                     while($row=$this->driver->fetch($x,$type))
                        {
                           $r[]=$row;
                        }
                  }
               else
                  {
                     throw new SeboDBControllerException('SeboDB:Simple Query failed because of the following reason: '.$this->driver->error($this->driver->connection),E_USER_WARNING);
                  }

               return $r;
            }

      /**
       * Close the driver's connection to the data source.
       * @access public
       * @return boolean True if success, false otherwise
       */
         public function close()
            {
               return $this->driver->close($this->driver->connection);
            }
      }
?>
