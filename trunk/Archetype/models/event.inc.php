<?php if(!defined('A_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Event layer for Archetype
 *
 * @package Archetype
 * @subpackage event
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://sebocorp.com/archetype
 * @version 2007.11.8
 */

/**
 * A small, fast OOP event library
 */
   class A_event_model extends A_model
      {
      /**
       * Store events that haven't been triggered
       * @access private
       * @var array
       */
         private $events=array();

      /**
       * Register a callback to be run when an event is triggered
       * @access public
       * @param $event string Event name to trigger handler
       * @param $handler array Numeric array in array(&$object,'method') format
       * @return void
       */
         public function register($event,$handler)
            {
               if(is_array($handler)&&(is_object($handler[0])&&is_string($handler[1])))
                  {
                     if(empty($this->events[$event]))
                        {
                           $this->events[$event]=array();
                        }

                     $this->events[$event][]=&$handler;
                  }
               else
                  {
                     throw new A_Exception('Handler callback provided was not in array(&$object,"method") format');
                  }
            }

      /**
       * Register handlers to be run when an event is triggered
       * @access public
       * @param $event string Event name to trigger
       * @return void
       */
         public function trigger($event,$parameters=array())
            {
               if(!empty($this->events[$event]))
                  {
                     foreach($this->events[$event] as &$handler)
                        {
                           if(method_exists($handler[0],$handler[1]))
                              {
                                 call_user_func_array($handler,$parameters);
                              }
                        }
                  }
            }
      }
?>
