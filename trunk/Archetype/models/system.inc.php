<?php if(!defined('A_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * System model
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
   class A_system_model extends A_model
      {
      /**
       * Upon construct, assign a few important things to the object
       * @access public
       * @return void
       */
         public function construct()
            {
            // Assign global view values
               $this->settings('views',$this);
               $this->settings('system',$this);
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

               $model=str_replace('/','_',trim($model,'/'));

            // If we don't have an existing instance of the model, try to create one
               if(empty($this->_['objects']['models'][$model]))
                  {
                     if(is_readable($location=A_MODELS_LOCATION.str_replace('.','',trim($model,'/')).'.inc.php'))
                        {
                        // Trim off the chunk of the string that we'll use to identify the class if we need to
                           if(strpos($model,'/'))
                              {
                                 $model=array_slice(explode('/',$model),-1,1);
                              }

                           $class="A_${model}_model";

                           if(!class_exists($class))
                              {
                                 require($location);
                              }

                           if(class_exists($class))
                              {
                                 $this->_['objects']['models'][$model]=new $class($this->_);
                              }
                        }

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
                           $input=array_merge($input,$this->settings['views']['global']);
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

               $controller=str_replace('/','_',trim($controller,'/'));

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
       * @return bool True if the specified component exists, false if not
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
       * Creates a new associative array based on $original but with the specified keys in $overwrite written with their respective data
       * @access public
       * @param mixed $original
       * @param mixed $overwrite
       * @return mixed
       */
         static private function array_overwrite(&$overwrite,&$original)
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
