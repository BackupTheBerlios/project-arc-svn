<?php

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Welcome to Archetype.  The water fountain is around the corner.
 *
 * @todo Change all mixed return types to something|something, I didn't know you could do that :(
 * @todo change all instances of "${variable}" to "{$variable}" because it's more flexible
 * @package Archetype
 * @subpackage system
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2005-2008 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.com/archetype
 * @version 2008.3.23
 */

// By default, show no errors.  If and when the debug component is activated, it will bring error reporting its most verbose level.
   error_reporting(E_ALL); // set to 0 once development of core and debug component is done TODO TODO

// The version of the current distribution.
   define('ARCHETYPE_VERSION','2008.3.23');

/**
 * This class describes the absolute root / parent of the entire system.
 */
   class Archetype
      {
      /**
       * Universal variable passed between every object extended from and inside Archetype
       * @todo Come back here and describe where each of these are initialized
       * @var array
       * @access public
       */
         public $_=array
            (
               'Archetype'=>true, // Gets set in the constructor and sets a reference to this object
               'timings'=>array(), // Benchmark timings and the system's begin time
               'input'=>array
                  (
                     'controller'=>  '',
                     'method'=>      '',
                     'parameters'=>  array(),
                     'switches'=>    array()
                  ),
               'components'=>array // The index to all of the components open in the system at any given time, stored as $name=>$object unless noted otherwise
                  (
                     'automators'=>  array(), // Priority in $object->construct_order and $object->destruct_order
                     'libraries'=>   array(),
                     'models'=>      array(),
                     'views'=>       array(), // Names of views branched as they were opened
                     'controllers'=> array(),
                     'settings'=>    array()
                  )
            );

      /**
       * The path we should consider ourselves at on the filesystem
       * @access public
       * @var string
       */
         public $PATH;

      /**
       * The path where you should be able to find system components
       * @access public
       * @var string
       */
         public $SYSTEM_PATH;

      /**
       * The path where you should be able to find application components
       * @access public
       * @var string
       */
         public $APPLICATION_PATH;

      /**
       * The path where you should be able to find extension components
       * @access public
       * @var string
       */
         public $EXTENSION_PATH;

      /**
       * Constructor does some preliminary work before the rest of the system loads
       * @access public
       * @return void
       */
         public function __construct()
            {
            // Record the beginning time of the build
               $this->_['timings']['archetype|start']=microtime(true);

            // Drop a reference to this object in the universal variable so any component can access the absolute root of the system
               $this->_['Archetype']=&$this;

            // By default, our resources should be relative to the location of this particular file
               $this->PATH=dirname(__FILE__).'/';

            // Storage of system files that shouldn't need to be modified for most purposes and can often be shared between projects
               $this->SYSTEM_PATH=$this->PATH.'system/';

            // Extensions are essentially applications, but intended to extend or add to the core system and can often be shared as well
               $this->EXTENSION_PATH=$this->SYSTEM_PATH.'extensions/';

            // Storage of all files custom to an application
               $this->APPLICATION_PATH=$this->PATH.'application/';
            }

      /**
       * Loop through the automators, run them, destroy them
       * @access public
       * @return void
       */
         public function run()
            {
            // Statically require Archetype's system library
               require($this->SYSTEM_PATH.'libraries/system.inc.php');

            // Make a new instance of the system library and put it where it would normally go in the universal array
               try
                  {
                     $this->_['components']['system']=new Archetype_system_library($this->_);
                  }
               catch(Exception $e) // TODO remove this once you're done testing the system library and you're ready to finish the automator runner
                  {
                     echo $e;
                  }

            /* // Execute in a sandbox so we can catch exceptions
               try
                  {
                  // Open automators
                     foreach($this->_['information']['automators'] as $key=>$automator)
                        {
                           if($automator{0}==='.')
                              {
                              // Hide files and directories that should be hidden from the system
                                 unset($_['information']['lists']['automators'][$key]);
                              }
                           else
                              {
                                 $automator=str_replace('.inc.php','',$automator);

                                 $construct=$destruct=0;

                                 require(A_AUTOMATORS_LOCATION.$automator.'.inc.php');

                                 $class='A_'.$automator.'_automator';

                              // If the class was found, record the construct and destruct orders
                                 if(class_exists($class))
                                    {
                                       $_['information']['priority']['construct'][$automator]=$construct;
                                       $_['information']['priority']['destruct'][$automator]=$destruct;
                                    }
                              }
                        }

                  // Sort constructor and destructor orders
                     arsort($_['information']['priority']['construct'],SORT_NUMERIC);
                     arsort($_['information']['priority']['destruct'],SORT_NUMERIC);

                  // Run constructors
                     foreach($_['information']['priority']['construct'] as $automator=>$priority)
                        {
                           if(class_exists($class='A_'.$automator.'_automator'))
                              {
                                 $_['objects']['automators'][$automator]=new $class($_);
                              }
                        }

                  // Run destructors
                     foreach($_['information']['priority']['destruct'] as $automator=>$priority)
                        {
                           if(!empty($_['objects']['automators'][$automator]))
                              {
                                 unset($_['objects']['automators'][$automator]);
                              }
                        }
                  }
            // If an exception was caught, finish up with a professional, helpful error
               catch(Exception $x)
                  {
                  // Try to throw a pretty error if we can
                     if(!empty($_['objects']['models']['system']))
                        {
                           $_['objects']['models']['system']->controller('system','exception',array($x));
                        }
                  // But default to trigger_error()
                     else
                        {
                           trigger_error($x->__toString(),E_USER_ERROR); // __toString() because PHP5.1 is stupid and because magic methods are sluggish
                        }
                  } */
            }

      /**
       * Destructor will run the system if it hasn't already been done
       * @access public
       * @return void
       */
         public function __destruct()
            {
               if(empty($this->_['objects']['automators']))
                  {
                     $this->run();
                  }
            }
      }

/**
 * Archetype's exception class
 */
   class A_Exception extends Exception {} // Add something in here to log any exception thrown

/**
 * Base class for Archetype.  It should be extended in some form by every other class in the system.
 */
   class A_base
      {
      /**
       * Assigned a reference to $_ (the universal variable) in the constructor
       * @access public
       * @var mixed
       */
         public $_=false;

      /**
       * Assigned a reference to $_['objects']['libraries']['system'] in the constructor and inherited by every class in the system
       * @access public
       * @var mixed
       */
         public $system=false;

      /**
       * Dummy constructor
       * @access public
       * @return void
       */
         public function construct__() {}

      /**
       * Dummy destructor
       * @access public
       * @return void
       */
         public function destruct__() {}

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

                     $this->construct__();

                  // Loop post_construct()
                     foreach($this->_['objects']['injectors'] as &$injector)
                        {
                           $injector->post_construct($this);
                        }
                  }
               else
                  {
                     $this->construct__();
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

                     $this->destruct__();

                  // Loop post_destruct()
                     foreach($this->_['objects']['injectors'] as &$injector)
                        {
                           $injector->post_destruct($this);
                        }
                  }
               else
                  {
                     $this->destruct__();
                  }
            }
      }

/**
 * Provide a base class for models
 */
   class A_model extends A_base
      {
      // Figure out a better way to do a voluntary cleanup when the model's object is passed to a new supervisor
      }

   class A_library extends A_base {} // Read the above comment in the Model class and figure it out here as well TODO <- so I remember this later.

/**
 * Provide a base class for controllers
 */
   class A_controller extends A_base {}

/**
 * Provide a base class for automators
 */
   class A_automator extends A_base {}
?>
