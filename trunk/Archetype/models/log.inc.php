<?php if(!defined('A_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Log model
 *
 * @package Archetype
 * @subpackage log
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.4.1
 * @TODO Put the log config loader in a constructor
 */

   define('LOG_LEVEL_NOTICE',0);
   define('LOG_LEVEL_WARNING',1);
   define('LOG_LEVEL_ERROR',2);

   if(!defined('LOG_LOCATION'))
      {
         define('LOG_LOCATION',A_SYSTEM_LOCATION.'/logs/');
      }

/**
 * Logging mechanism
 */
   class A_log_model extends Archetype_model
      {
      /**
       * Store the buffered logs until they're ready to be written
       * @access public
       * @var $buffer array
       */
         public $buffer=array();

      /**
       * Store references to file handles so writing logs is faster
       */
         public $logs=array();

      /**
       * Checks if the log exists and is ready to be used
       * @access public
       * @param string $log String name of the log to check
       * @return boolean True on success false otherwise
       */
         public function exists($log)
            {
               $r=false;

               $log_file=LOG_LOCATION.'/'.$log.'.log';

               if(is_readable($log_file)&&is_writable($log_file))
                  {
                     $r=true;
                  }

               return $r;
            }

      /**
       * Creates a log to be used by the rest of the class
       * @access public
       * @param string $log String name of the log to create
       * @return boolean True on success false otherwise
       */
         public function create($log)
            {
               $r=false;

               $log_file=LOG_LOCATION.'/'.$log.'.log';

               if(is_writable(LOG_LOCATION)&&touch($log_file)&&chmod($log_file,755)&&is_writable($log_file))
                  {
                     $r=true;
                  }

               return $r;
            }

      /**
       * Appends a message to a log buffer, to be written later in heaps with $this->write()
       * @access public
       * @param string $message String that you want to append to a log buffer
       * @param string $log array Log name
       * @param int $level Optional, one of the following three: LOG_LEVEL_NOTICE, LOG_LEVEL_WARNING, LOG_LEVEL_ERROR
       * @return boolean True if log is ready to be written to and the message was buffered, false otherwise
       */
         public function buffer($message,$log,$level=LOG_LEVEL_NOTICE)
            {
               $r=false;

               $log_file=LOG_LOCATION.'/'.$log.'.log';

            // Have it check local because if we've already opened it it's faster to not read the disk
               if(!empty($this->logs[$log])||$this->exists($log))
                  {
                     $this->buffer[]=array('message'=>$message,'log'=>$log,'level'=>$level);

                     $r=true;
                  }

               return $r;
            }

      /**
       * Write buffered logs to disk
       * @access public
       * @return void It'll throw an exception if it tries to write to a log that doesn't exist
       * @todo See if it can't be cleaned up, it's pretty thrown together and ugly
       */
         public function write()
            {
               $r=false;
               
               if(!empty($this->buffer))
                  {
                     foreach($this->buffer as $entry)
                        {
                           if(empty($this->logs[$entry['log']]))
                              {
                                 $log_file=LOG_LOCATION.'/'.$entry['log'].'.log';

                                 $this->logs[$entry['log']]=fopen($log_file,'a');
                              }

                           if($entry['level']==LOG_LEVEL_ERROR)
                              {
                                 $level_string='ERROR';
                              }
                           elseif($entry['level']==LOG_LEVEL_WARNING)
                              {
                                 $level_string='WARNING';
                              }
                           elseif($entry['level']==LOG_LEVEL_NOTICE)
                              {
                                 $level_string='NOTICE';
                              }
                           else
                              {
                                 $level_string='UNKNOWN';
                              }

                           $log_config=&$this->system->config('log');

                           $out=date($log_config['timestamp_format']).'|'.$level_string.' - '.$entry['message']."\n";

                           if(!empty($this->logs[$entry['log']])&&fwrite($this->logs[$entry['log']],$out))
                              {
                                 $r=true;
                              }

                           if(!$r)
                              {
                                 throw new A_Exception('Could not write to file "'.$log_file.'"');
                              }
                        }

                     if($r)
                        {
                           $this->buffer=array();
                        }
                  }
            }

      /**
       * Flush the log buffer before it's written
       * @access public
       * @return void
       */
         public function flush()
            {
               unset($this->buffer);
               $this->buffer=array();
            }

      /**
       * Destructor cleans up
       * @access public
       * @return void
       */
         public function destruct()
            {
            // If we have unwritten buffers, write them
               if(!empty($this->buffer))
                  {
                     $this->write();
                  }

            // Clean up all file handles used by write
               if(!empty($this->logs))
                  {
                     foreach($this->logs as &$log)
                        {
                           fclose($log);
                        }
                  }
            }
      }

?>
