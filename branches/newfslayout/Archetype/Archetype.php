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
 * @subpackage Core
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2005-2008 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://kroogs.com/Archetype
 * @version 2008.6.26
 */

// By default, be very quiet
   error_reporting(E_ALL); // set to 0 once development of core and debug component is done TODO TODO

/**
 * Archetype's primary class.  It initializes a storage space and loads the system library.
 */
   class Archetype
      {
      /**
       * For any random data not stored in a namespace or one of its children
       * @var array
       * @access public
       */
         public $registry=array('system.version'=>'2007.6.26');

      /**
       * 
       * @var array
       * @access public
       */
         public $namespaces=array();

      /**
       * Constructor does some preliminary work before the rest of the system loads
       * @param
       * @access public
       * @return void
       */
         public function __construct($namespaces=array())
            {
            // Open up a few spaces we expect we'll find
               foreach(array_merge(array(dirname(__FILE__).'/system/'),glob(dirname(__FILE__).'/extensions/*/'),$namespaces) as $space)
                  {
                     $this->namespace($space);
                  }
            }

      /**
       * A straight forward way to add namespaces to the system as it runs
       * @access public
       * @return void
       */
         public function namespace($path,$extensions='.inc.php')
            {
               $this->namespaces[basename($path)]=new ANamespace($this->namespaces,$path,$extensions);
            }

      /**
       * Loop through the automators, run them, destroy them
       * @access public
       * @return void
       */
         public function run()
            {
            // Record the beginning time of the build
               $this->registry['timings']['system.start']=microtime(true);

               $observer=$this->namespaces['system']->import('library.observer','system');

               echo 'Archetype.run()';

               return;

               $this->system=$this->_A->namespaces['system']->import('library.system',$this);

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
               if(empty($this->registry['timings']['system.start']))
                  {
                     $this->run();
                  }
            }
      }

/**
 * Archetype's exception class
 */
   class AException extends Exception {} // Add something in here to log any exception thrown TODO

/**
 * A basic container that opens files/classes, indexes them, then provides tools to access them from each other.
 */
   class ANamespace
      {
      /**
       * Gets a reference to the array that contains all opened namespace objects
       * @access public
       * @var
       */
         public $top;

      /**
       * The root path of this namespace
       * @access public
       * @var string
       */
         public $path;

      /**
       * File extensions for this namespace
       * @access public
       * @var string
       */
         public $extensions;

      /**
       * An index of class names (key) and instantiated objects (reference)
       * @access public
       * @var array
       */
         public $contents=array();

      /**
       * Constructor, store the path and pattern internally and run our parent's constructor
       * @access public
       * @param
       * @param
       * @param
       * @return
       */
         public function __construct(&$top,$path,$extensions='.inc.php')
            {
               $this->top=&$top;

               $this->extensions=$extensions;

            // Do what we can to prevent namespace collisions
               if(!empty($top[($name=dirname($path))]))
                  {
                     throw new AException("Can not reconstruct namespace '{$name}'");
                  }

            // Only open valid paths
               if(is_dir($path)&&!is_readable($path))
                  {
                     throw new AException("Could not open '{$path}' as namespace");
                  }

               $this->path=$path;
            }

      /**
       * 
       * @access public
       * @param
       * @param
       * @todo make it show you the keys too so you can figure out WHY something was unmet if it is actually there
       * @return void
       */
         public function depend($lookup,$keys=array())
            {
               if($obj=$this->import($lookup))
                  {
                     $r=true;

                     if(!empty($keys))
                        {
                           foreach($keys as $label=>$value)
                              {
                                 if(!preg_match($value,$obj->info[$label]))
                                    {
                                       $r=false;
                                    }
                              }
                        }
                  }

               if(!$r)
                  {
                     throw new AException("Encountered an unmet dependency: '{$lookup}'");
                  }
            }

      /**
       * 
       * @access public
       * @param
       * @param
       * @return
       */
         public function &import($lookup,$from=false)
            {
               $r=false;

               $this->import->view();

            // Map the namespace off $from if it's an object previously imported
               if(is_a($from,'ACore')) { $from=$from->__namespace; }

            // Make sure we start with a clean buffer
               if(ob_get_length()) { ob_clean(); }

               // if no $from and no space in $lookup and only one match found for $lookup, return it
               // but if multiples found, throw exception

               // $relative can be either an object, in which case it operates on that object's namespace
               // or it can be a string to a namespace for it to pull from
               // but if it's an object, the resulting imported object will be inserted into it forcefully

            // If the buffer size changed, write it to the object
               if(ob_get_length()) { $r->__output_buffer=ob_get_clean(); }

               return $r;
            }
      }

/**
 * Almost everything in the system at any given point will be a descendant of this class
 */
   class ACore
      {
      /**
       * 
       * @access private
       * @var
       */
         private $_A;

      /**
       * 
       * @access public
       * @var
       */
         public $import;

      /**
       * 
       * @access public
       * @param
       * @return void
       */
         public function __construct(&$_A)
            {
            // Set the reference to the root object
               $this->_A=&$_A;

            // Sets a reference to the system's import library
               $this->import=&$this->_A->namespaces['system']->import('library.import',$this);
            }
      }

/**
 * 
 */
   class ALibrary extends ACore {} 

/**
 * 
 */
   class AModel extends ALibrary {}

/**
 * 
 */
   class AView extends ACore
      {
         public $__buffer='';

         public function __toString()
            {
               return $this->__buffer;
            }
      }

/**
 * 
 */
   class AController extends ACore {}

/**
 * 
 */
   class ASetting extends ACore {}
?>
