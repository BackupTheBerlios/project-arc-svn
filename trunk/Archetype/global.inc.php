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
   class A
      {
      /**
       * Stores information for extended classes
       * @access public
       * @var mixed
       * @static
       */
         public static $info=false;

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
         public function construct(){}

      /**
       * Dummy destructor
       * @access public
       * @return void
       */
         public function destruct(){}

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
               $this->system=&$this->_['storage']['models']['system'];

               $this->construct();
            }

      /**
       * Destructor that runs in every descendant
       * @access public
       * @return void
       */
         public function __destruct()
            {
               $this->destruct();
            }
      }

/**
 * Provide a base class for models
 */
   class A_model extends A {}

/**
 * Provide a base class for controllers
 */
   class A_controller extends A {}

/**
 * Provide a base class for automators
 */
   class A_automator extends A
      {
         public static $construct=0;
         public static $destruct=0;
      }

/**
 * Provide a base class for injectors
 */
   class A_injector extends A
      {
         public function pre_construct()
            {
            }

         public function post_construct()
            {
            }

         public function pre_destruct()
            {
            }

         public function post_destruct()
            {
            }
      }
?>
