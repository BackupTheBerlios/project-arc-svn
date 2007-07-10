<?php

   ////////////////////////////////////////////////////////////////////
   //                          S e b o D B                           //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * SeboDB Driver: SQLite
 *
 * @package SeboDB
 * @subpackage drivers
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/sebodb
 * @version 2007.4.1
 * @todo Finish the driver (commented parts do not work)
 */

   class SeboDB_sqlite_driver extends SeboDB_driver
      {
         public function open($info)
            {
               return sqlite_open($info['name']);
            }

         public function query($sql,$connection)
            {
               return sqlite_query($sql,$connection);
            }

         public function affected($connection)
            {
               return sqlite_changes($connection);
            }

         public function insert_id($connection)
            {
               return sqlite_last_insert_rowid($connection);
            }

         public function escape($string,$connection)
            {
               return sqlite_escape_string($string);
            }

         public function fetch($query,$type=SEBODB_ASSOC)
            {
               $r=false;

               if(!is_resource($query))
                  {
                     trigger_error('SeboDB:SQLite argument provided is not a valid result resource',E_USER_WARNING);
                  }

               if($type===SEBODB_ARRAY)
                  {
                     $r=sqlite_fetch_array($query,SQLITE_NUM);
                  }
               elseif($type===SEBODB_ASSOC)
                  {
                     $r=sqlite_fetch_array($query,SQLITE_ASSOC);
                  }
               elseif($type===SEBODB_OBJECT)
                  {
                     $r=sqlite_fetch_object($query);
                  }
               elseif($type===SEBODB_FIELD)
                  {
                  // FINISH ME FINISH ME FINISH ME
                     $r=sqlite_fetch_array($query);
                  }
               elseif($type===SEBODB_LENGTHS)
                  {
                     $r=mysql_fetch_lengths($query);
                  }
               else
                  {
                     trigger_error('SeboDB:SQLite invalid type argument',E_USER_WARNING);
                  }

               return $r;
            }

      // FINISH ME FINISH ME FINISH ME
         public function free($query)
            {
            }

      // FINISH ME FINISH ME FINISH ME
         public function ping($connection)
            {
            }

         public function error($connection)
            {
               return sqlite_error_string(sqlite_last_error($connection));
            }

         public function close($connection)
            {
               return sqlite_close($connection);
            }
      }
?>
