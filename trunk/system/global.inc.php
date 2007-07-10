<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Archetype's global file
 *
 * @package Archetype
 * @subpackage system
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.com/archetype
 * @version 2007.5.6
 */

/**
 * Exception class for generic component exceptions
 */
   class ArchetypeComponentException extends Exception {}

/**
 * Exception class for generic system exceptions
 */
   class ArchetypeSystemException extends Exception {}

/**
 * Primary class for Archetype.  It should be extended in some form by every other class in the system.
 */
   class Archetype
      {
      /**
       * Stores information for extended classes
       * @access public
       * @var mixed
       * @static
       */
         public static $information=false;

      /**
       * Assigned a reference to $_ (the universal variable) in the constructor
       * @access public
       * @var mixed
       */
         public $_=false;

      /**
       * Assigned a reference to $_['models']['system'] in the constructor and inherited by every class in the system
       * @access public
       * @var mixed
       */
         public $system=false;

      /**
       * Dummy constructor
       * @access public
       * @return void
       */
         protected function construct(){}

      /**
       * Dummy destructor
       * @access public
       * @return void
       */
         protected function destruct(){}

      /**
       * Constructor that runs in every descendant
       * @access public
       * @param array $_ Reference to Archetype's universal variable
       * @return void
       */
         public function __construct(&$_)
            {
            // Set the reference to the universal array
               $this->_=&$_;

            // Load the system model
               $this->system=&$this->_['models']['system'];

            // Assign the class so we don't have to use get_class() multiple times
               $class=get_class($this);

            // Load the system config
               if(!empty($this->system))
                  {
                     $this->system->config('system');
                  }

            // Trigger callbacks before construction if the event component exists
               if(!empty($this->_['models']['event']))
                  {
                     $this->_['models']['event']->trigger($class.'_pre_construct',array(&$this));
                  }

            // Mark the time before construction if debug mode is enabled and the benchmark component exists
               if(!empty($this->_['config']['system']['debug'])&&!empty($this->_['models']['benchmark']))
                  {
                     $this->_['models']['benchmark']->mark($class.'_construct_start');
                  }

               $this->construct();

            // Mark the time after construction if debug mode is enabled and the benchmark component exists
               if(!empty($this->_['config']['system']['debug'])&&!empty($this->_['models']['benchmark']))
                  {
                     $this->_['models']['benchmark']->mark($class.'_construct_end');
                  }

            // Trigger callbacks after construction if the event component exists
               if(!empty($this->_['models']['event']))
                  {
                     $this->_['models']['event']->trigger($class.'_post_construct',array(&$this));
                  }
            }

      /**
       * Destructor that runs in every descendant
       * @access public
       * @return void
       */
         public function __destruct()
            {
            // Assign the class so we don't have to use get_class() multiple times
               $class=get_class($this);

            // Trigger callbacks before destruction if the event component exists
               if(!empty($this->_['models']['event']))
                  {
                     $this->_['models']['event']->trigger($class.'_pre_destruct',array(&$this));
                  }

            // Mark the time before destruction if debug mode is enabled and the benchmark component exists
               if(!empty($this->_['config']['system']['debug'])&&!empty($this->_['models']['benchmark']))
                  {
                     $this->_['models']['benchmark']->mark($class.'_destruct_start');
                  }

               $this->destruct();

            // Mark the time after destruction if debug mode is enabled and the benchmark component exists
               if(!empty($this->_['config']['system']['debug'])&&!empty($this->_['models']['benchmark']))
                  {
                     $this->_['models']['benchmark']->mark($class.'_destruct_end');
                  }

            // Trigger callbacks after destruction if the event component exists
               if(!empty($this->_['models']['event']))
                  {
                     $this->_['models']['event']->trigger($class.'_post_destruct',array(&$this));
                  }
            }
      }

/**
 * Provide a base class for models
 */
   class Archetype_model extends Archetype {}

/**
 * Provide a base class for controllers
 */
   class Archetype_controller extends Archetype {}

/**
 * Provide a base class for automators
 */
   class Archetype_automator extends Archetype {}
?>
