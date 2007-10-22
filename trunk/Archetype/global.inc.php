<?php if(!defined('A_VERSION')){die();}

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
 * Archetype's exception class
 */
   class A_Exception extends Exception {}

/**
 * Primary class for Archetype.  It should be extended in some form by every other class in the system.
 */
   class A
      {
      /**
       * Assigned a reference to $_ (the universal variable) in the constructor
       * @access public
       * @var mixed
       */
         public $_=false;

      /**
       * Assigned a reference to $_['objects']['models']['system'] in the constructor and inherited by every class in the system
       * @access public
       * @var mixed
       */
         public $system=false;

      /**
       * Dummy constructor
       * @access public
       * @return void
       */
         public function construct() {}

      /**
       * Dummy destructor
       * @access public
       * @return void
       */
         public function destruct() {}

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
               $this->system=&$this->_['objects']['models']['system'];

               if(!empty($this->_['objects']['injectors']))
                  {
                  // Loop pre_construct()
                     foreach($this->_['objects']['injectors'] as &$injector)
                        {
                           $injector->pre_construct($this);
                        }

                     $this->construct();

                  // Loop post_construct()
                     foreach($this->_['objects']['injectors'] as &$injector)
                        {
                           $injector->post_construct($this);
                        }
                  }
               else
                  {
                     $this->construct();
                  }
            }

      /**
       * Destructor that runs in every descendant
       * @access public
       * @return void
       */
         public function __destruct()
            {
               if(!empty($this->_['objects']['injectors']))
                  {
                  // Loop pre_destruct()
                     foreach($this->_['objects']['injectors'] as &$injector)
                        {
                           $injector->pre_destruct($this);
                        }

                     $this->destruct();

                  // Loop post_destruct()
                     foreach($this->_['objects']['injectors'] as &$injector)
                        {
                           $injector->post_destruct($this);
                        }
                  }
               else
                  {
                     $this->destruct();
                  }
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
   class A_automator extends A {}

/**
 * Provide a base class for injectors
 */
   class A_injector extends A
      {
      /**
       * Simplify the constructor from the parent since we don't want to run injectors inside themselves (infinite loop)
       * @access public
       * @return void
       */
         public function __construct(&$_)
            {
            // Set the reference to the universal array
               $this->_=&$_;

            // Load the system model
               $this->system=&$this->_['objects']['models']['system'];

            // Run our user defined constructor
               $this->construct();
            }

      /**
       * Runs before every object's construction
       * @access public
       * @return void
       */
         public function pre_construct(&$object) {}

      /**
       * Runs after every object's construction
       * @access public
       * @return void
       */
         public function post_construct(&$object) {}

      /**
       * Runs before every object's destruction
       * @access public
       * @return void
       */
         public function pre_destruct(&$object) {}

      /**
       * Runs after every object's destruction
       * @access public
       * @return void
       */
         public function post_destruct(&$object) {}

      /**
       * Simplify the destructor from the parent since we don't want to run injectors inside themselves (infinite loop)
       * @access public
       * @return void
       */
         public function __destruct()
            {
               $this->destruct();
            }
      }
?>
