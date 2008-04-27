<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * System model
 * @todo reverse conditions so exceptions are at the top of the methods
 *
 * @package Archetype
 * @subpackage system
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.9.10
 */

/**
 * Archetype's system model for providing support to other components
 */
   class Archetype_system_library extends A_library
      {
      /**
       * The types of resources the system can handle
       * @access private
       * @type array
       */
         private $resource_types=array
            (
               'automators'=>  'automator',
               'models'=>      'model',
               'views'=>       'view',
               'controllers'=> 'controller',
               'libraries'=>   'library',
               'settings'=>    'setting'
            );

      /**
       * @todo where's the view super-object?
       * @access public
       * @return void
       */
         public function construct__()
            {
               $this->import('automators'); // debug junk :!
            }

      /**
       * A way to open components that automatically resolves files and classes based on path location
       * @todo add settings after we figure out what to do with them storage wise
       * @todo add support for $only so this can be used for stuff other than just automators :)
       * @access public
       * @param string $type The type of components to import
       * @param string|array $only Specifically only import these components
       * @return boolean|array An associative array in the form of $name=>$class if successful, false otherwise
       */
// on extending: libraries should extend the core like so: class A_ExtensionName_LibraryName_library extends Archetype_LibraryName_library
// application classes can extend extensions or core.  extending extension: A_SomeName_library extends A_ExtensionName_LibraryName_library | to extend core, same but ExtensionName_LibraryName_library is Archetype_library
// maybe something to figure out dependencies?
// in addition to line-based scanner when there's a conflict and we're trying to figure out which direction to try to extend, also look for redefinitions of system classnames.  if found, open it before the system version and disregard the system's.
// classes should be able to inject themselves over a local copy already loaded.  maybe $object->overwrite=true?  or $overwrite=true in the style you do automator priority?
// don't do settings for now, but do them later when you figure out if you want array settings or an object

         // $system->import('model','account'); RETURNS array('account'=>'A_account_model')
         // $system->import('libraries',array('SeboDB','string','feeds')); RETURNS array('SeboDB'=>'A_SeboDB_SeboDB_library','string'=>'Archetype_string_library','feeds'=>'A_feeds_library')
         // $system->import('automators');

         // SITUATION!
         // application/libraries/example.inc.php - class A_example_library extends A_library

         private function import($type,$components=array())
            {
               $r=false;

            // If the type doesn't match anything in $accept_types, throw an exception
               if(empty($this->resource_types[$type])&&)
                  {
                     if(!array_search($type,$this->resource_types))
                        {
                           throw new A_Exception("Can not import component of type '{$this->resource_types[$type]}'");
                        }
                     else
                        {
                        }
                  }

            // Allows opening one component at a time
               if(is_string($components)) { $components=array($components); }

            // If input isn't an array at this point, throw an exception
               if(!is_array($components)) { throw new A_Exception("Components must be specified either as an array or string"); }

               if(!empty($components))
                  {
                     $classes=array();

                     foreach($components as $component)
                        {
                           $component=$this->alphanumeric($component,'_');

                           if(is_readable($file="{$this->_['Archetype']->SYSTEM_PATH}{$type}/{$component}.inc.php"))
                              {
                                 $classes[$component][]="Archetype_{$component}_{$type}";
                              }

                           if(is_readable($file="{$this->_['Archetype']->APPLICATION_PATH}{$type}/{$component}.inc.php"))
                              {
                                 $classes[$component][]="A_{$component}_{$type}";
                              }
                        }
                  }

               return $r;
            }

      /**
       * Useful for stripping all characters from a string except those that are alphanumeric or specified as parameters
       * @access private
       * @param $input string String you want to strip
       * @return string Stripped version of $input
       */
         private function alphanumeric($input,$exceptions='')
            {
               return preg_replace('/[^\w'.preg_quote($exceptions).']/','',$input);
            }

      /**
       * Open models and optionally assign them to the object passed
       * @access public
       * @param string $model String name of the model to open
       * @param mixed $object Optional object for the model to be automatically inserted into
       * @return mixed Returns a reference to the model on success, false on failure
       */
         public function &model($model,&$object=false)
            {
               $r=false;

               $class='A_'.str_replace('/','_',trim($model,'/')).'_model';

            // If we don't have an existing instance of the model, try to create one
               if(empty($this->_['objects']['models'][$model]))
                  {
                     if(is_readable($location=A_MODELS_LOCATION.str_replace('.','',trim($model,'/')).'.inc.php'))
                        {
                        // No class found, try to open the file that should contain it
                           if(!class_exists($class))
                              {
                                 require($location);
                              }

                        // Class found, instantiate an object
                           if(class_exists($class))
                              {
                                 $this->_['objects']['models'][$model]=new $class($this->_);
                              }
                        }

                  // Bad request, throw exception
                     if(empty($this->_['objects']['models'][$model]))
                        {
                           throw new A_Exception("Attempted to open non-existent model '${location}'");
                        }
                  }

            // Existing model found, return a reference to it
               if(!empty($this->_['objects']['models'][$model]))
                  {
                     $r=&$this->_['objects']['models'][$model];

                  // Assign the model to a parameter inside of the passed object, if one was passed
                     if(is_object($object))
                        {
                           $object->$model=&$r;
                        }
                  }

               return $r;
            }

      /**
       * Load and return a view
       * @access public
       * @todo Maybe run views inside of an object that can be stored somewhere?  like a view sandbox?  that way all the stuff the view opens remains in that, instead of this
       * @param string $view String name of the view to open
       * @param array $input Reference to an associative array of variables to provide to the view
       * @return mixed Returns a reference to the model on success, false on failure
       */
         public function view($view,&$input=false)
            {
               $r=false;

            // If the view exists, load it up and catch the output
               if(is_readable($location=A_VIEWS_LOCATION.str_replace('.','',trim($view,'/')).'.inc.php'))
                  {
                  // Merge global and local values, local overwriting global
                     if(is_array($input))
                        {
                           $input=array_merge($this->settings['views']['global'],$input);
                        }
                  // Just link the two because there was no input array
                     elseif(empty($input))
                        {
                           $input=&$this->settings['views']['global'];
                        }
                  // Throw an exception because someone tried feeding it the wrong kind of food
                     else
                        {
                           throw new A_Exception("Views only accept input in the form of an associative array");
                        }

                  // Convert $input into a bunch of variables for the view
                     foreach($input as $index=>&$value)
                        {
                           if(!isset($$index))
                              {
                                 $$index=&$value;
                              }
                           else
                              {
                                 throw new A_Exception("View input tried overwriting '${$index}'");
                              }
                        }

                  // Start an output buffer so we can catch all output
                     ob_start();

                  // Open the view
                     require($location);

                  // Clean the output buffer and safe its contents
                     $r=ob_get_clean();
                  }
               else
                  {
                     throw new A_Exception("Attempted to open non-existent view '${location}'");
                  }

               return $r;
            }

      /**
       * Loads controllers
       * @access public
       * @param $controller The controller name to load
       * @param $method The method inside of the controller class to run
       * @param $args The parameters to feed to the method's call
       * @return mixed String on success, false on failure
       * @todo Make it controller($input) and input something like user/open/34
       */
         public function controller($controller,$method='index',$args=array())
            {
               $r=false;

               if(empty($this->_['objects']['controllers'][$controller]))
                  {
                     if(is_readable($location=A_CONTROLLERS_LOCATION.str_replace('.','',trim($controller,'/')).'.inc.php'))
                        {
                           $class="A_${controller}_controller";
      
                           if(!class_exists($class))
                              {
                                 require($location);
                              }
      
                           if(class_exists($class))
                              {
                                 $this->_['objects']['controllers'][$controller]=new $class($this->_);
                              }
                        }

                     if(empty($this->_['objects']['controllers'][$controller]))
                        {
                           throw new A_Exception("Attempted to open non-existent controller '${location}'");
                        }
                  }
      
               if(is_callable($call=array(&$this->_['objects']['controllers'][$controller],$method)))
                  {
                     call_user_func_array($call,$args);

                     $r=true;
                  }

               return $r;
            }

      /**
       * Loads and returns settings, optionally providing a requirement check on content
       * @access public
       * @param $group The setting group's name to load
       * @param $require Optionally checks the setting group for the settings provided in this array
       * @return mixed A reference to either the setting group specified or the key specified if true, false otherwise
       */
         public function &settings($group,&$object=false,$overwrite=array())
            {
               $r=false;

            // If the setting group isn't loaded, try to load it
               if(empty($this->_['information']['settings'][$group]))
                  {
                     if(is_readable($location=A_SETTINGS_LOCATION.str_replace('.','',trim($group,'/')).'.inc.php'))
                        {
                           require($location);

                           if(!empty($settings))
                              {
                                 $this->_['information']['settings'][$group]=$settings;
                              }
                        }
                     else
                        {
                           throw new A_Exception('Attempted to open non-existent setting group "'.$group.'"');
                        }
                  }

            // Setting group is loaded, return a reference
               if(!empty($this->_['information']['settings'][$group]))
                  {
                     $r=&$this->_['information']['settings'][$group];

                  // Allow you to inject custom settings, but localize it first
                     if(!empty($overwrite)&&is_array($overwrite))
                        {
                           self::array_overwrite($overwrite,$r);

                           $r=&$overwrite;
                        }

                  // Assign the model to a parameter inside of the passed object, if one was passed
                     if(is_object($object))
                        {
                           $object->settings[$group]=&$r;
                        }
                  }

               return $r;
            }

      /**
       * Used to allow components a reliable way to warn of conflicts
       * @access public
       * @param $type Either model, controller or automator
       * @param $component Component name
       * @param $key The component's info key
       * @param $regex A PREG that will be required to evaluate true on the $key
       * @return mixed A reference to either the config specified or the key specified if true, false otherwise
       * @todo Finish
       */
         public function depend($type,$component,$key=false,$regex=false)
            {
               // must figure out a neat and clean information storage mechanism for each component
               // Open the type of component by type
               // Grab module info, parse with preg
               // If false, dump an exception
            }

      /**
       * Used to test whether or not a component (and sometimes component method) exists
       * @access public
       * @param $type Can be one of: model, view, controller, automator, injector
       * @param $name Name of component
       * @param $method Name of method (if checking something class-based)
       * @return boolean True if the specified component exists, false if not
       * @todo rewrite to work for sub/directory/components
       */
         public function exists($type,$name,$method=false)
            {
               $r=false;

               if($type==='model')
                  {
                     $location=A_MODELS_LOCATION.str_replace('.','',trim($name,'/')).'.inc.php';
                  }
               elseif($type==='view')
                  {
                     if(is_readable(A_VIEWS_LOCATION.$view.'.inc.php'))
                        {
                           $r=true;
                        }
                  }
               elseif($type==='controller')
                  {
                     $location=A_CONTROLLERS_LOCATION.str_replace('.','',trim($name,'/')).'.inc.php';
                  }
               elseif($type==='automator')
                  {
                     $location=A_AUTOMATORS_LOCATION.str_replace('.','',trim($name,'/')).'.inc.php';
                  }
               elseif($type==='injector')
                  {
                     $location=A_INJECTORS_LOCATION.str_replace('.','',trim($name,'/')).'.inc.php';
                  }

               if(!empty($location))
                  {
                     $class="A_${name}_${type}";

                  // If the class simply doesn't exist, try to load it
                     if(!class_exists($class)&&is_readable($location))
                        {
                           require($location);
                        }

                  // Since is_callable() and method_exists() don't care about visibility we'll statically discount methods we KNOW shouldn't be called externally
                     $hide=array('__construct','construct','__destruct','destruct');

                  // Check if it's here and optionally test for a method
                     if(class_exists($class)&&(empty($method)||(!in_array($method,$hide)&&method_exists($class,$method))))
                        {
                           $r=true;
                        }
                  }

               return $r;
            }

      /**
       * Recursively fills $overwrite with stuff from $original when it doesn't already exist in $overwrite
       * @access public
       * @param mixed $original
       * @param mixed $overwrite
       * @return mixed
       */
         static private function array_overwrite(&$original,&$overwrite)
            {
               if(is_array($overwrite)&&is_array($original))
                  {
                     foreach($original as $key=>&$value)
                        {
                           if(empty($overwrite[$key]))
                              {
                                 $overwrite[$key]=$value;
                              }
                           else
                              {
                                 self::array_overwrite($overwrite[$key],$original[$key]);
                              }
                        }
                  }
            }
      }
?>
