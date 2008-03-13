<?php
// TODO Remove injectors, merge global and Archetype files, make Archetype class and put root program in it TODO

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Archetype's primary file
 *
 * @package Archetype
 * @subpackage system
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.com/archetype
 * @version 2008.3.13
 */

// Audible errors because we can't have them ignored for development purposes (feel free to silence a production application if you're sure it's safe)
   error_reporting(E_ALL);

/**
 * Archetype's version
 */define('A_VERSION','2008.3.13');

   if(!defined('A_ARCHETYPE_LOCATION'))   { define('A_ARCHETYPE_LOCATION',   dirname(__FILE__).'/'); }

   if(!defined('A_SYSTEM_LOCATION'))      { define('A_SYSTEM_LOCATION',      A_ARCHETYPE_LOCATION.'system/'); }
   if(!defined('A_APPLICATION_LOCATION')) { define('A_APPLICATION_LOCATION', A_ARCHETYPE_LOCATION.'application/'); }

   if(!defined('A_AUTOMATORS_ID'))  { define('A_AUTOMATORS_ID',  A_ARCHETYPE_ID.''); }
   if(!defined('A_SETTINGS_ID'))    { define('A_SETTINGS_ID',    A_ARCHETYPE_ID.''); }
   if(!defined('A_GLOBAL_ID'))      { define('A_GLOBAL_ID',      A_ARCHETYPE_ID.''); }
   if(!defined('A_MODELS_ID'))      { define('A_MODELS_ID',      A_ARCHETYPE_ID.''); }
   if(!defined('A_VIEWS_ID'))       { define('A_VIEWS_ID',       A_ARCHETYPE_ID.''); }
   if(!defined('A_CONTROLLERS_ID')) { define('A_CONTROLLERS_ID', A_ARCHETYPE_ID.''); }

   die(A_GLOBAL_LOCATION);

/**
 * Universal variable passed between every object extended from Archetype
 * @var array
 */
   $_=array
      (
         'information'=>array // Storage of miscellaneous information
            (
               'settings'=>array(),
               'priority'=>array('construct'=>array(),'destruct'=>array()), // Automator priority
               'timings'=>array('archetype|start'=>microtime(true)), // Benchmark timings and the system's begin time
               'lists'=>array('automators'=>array(),'injectors'=>array()), // Lists of component types that require to be opened all at once
               'input'=>array
                  (
                     'controller'=>'',
                     'method'=>'',
                     'parameters'=>array()
                  )
            ),
         'objects'=>array // Storage of live objects
            (
               'automators'=>array(),
               'injectors'=>array(),
               'models'=>array('system'=>false),
               'controllers'=>array('system'=>false)
            ),
      );

// Scan the filesystem for injectors
   $_['information']['lists']['injectors']=array_slice(scandir(A_INJECTORS_LOCATION),2);

// Scan the filesystem for automators
   $_['information']['lists']['automators']=array_slice(scandir(A_AUTOMATORS_LOCATION),2);

// Execute in a sandbox so we can catch exceptions
   try
      {
      // Open injectors
         foreach($_['information']['lists']['injectors'] as $key=>$injector)
            {
               if($injector{0}==='.')
                  {
                  // Hide files and directories that should be hidden from the system
                     unset($_['information']['lists']['automators'][$key]);
                  }
               else
                  {
                     $injector=str_replace('.inc.php','',$injector);

                     require(A_INJECTORS_LOCATION.$injector.'.inc.php');

                     $class='A_'.$injector.'_injector';

                     if(class_exists($class))
                        {
                           $_['objects']['injectors'][$injector]=new $class($_);
                        }
                  }
            }

      // Open automators
         foreach($_['information']['lists']['automators'] as $key=>$automator)
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
      }

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
   class A_model extends A
      {
      /**
       * Switch to toggle system::model()'s use of self::cleanup() each time the model is requested
       * @access public
       * @var boolean
       */
         public $cleanup=false;

      /**
       * Dummy method, extend and overload to make use of it (and read above comment)
       * @access public
       * @return void
       */
         public function cleanup() {}
      }

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
