<?php if(!defined('ARCHETYPE_VERSION')){die();}

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
   class system_model extends A_model
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

            // If we don't have an existing instance of the model, try to create one
               if(empty($this->_['storage']['models'][$model]))
                  {
                     if(is_readable($location=MODELS_LOCATION."${model}.inc.php"))
                        {
                           $class="${model}_model";

                           if(!class_exists($class))
                              {
                                 require($location);
                              }

                           if(class_exists($class))
                              {
                                 $this->_['storage']['models'][$model]=new $class($this->_);
                              }
                        }

                     if(empty($this->_['storage']['models'][$model]))
                        {
                           throw new ArchetypeSystemException("Attempted to open non-existent model '${model}'");
                        }
                  }

            // Existing model found, return a reference to it
               if(!empty($this->_['storage']['models'][$model]))
                  {
                     $r=&$this->_['storage']['models'][$model];

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
                     throw new ArchetypeSystemException("Views only accept input in the form of an associative array");
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
                           throw new ArchetypeSystemException("View input tried overwriting '${$index}'");
                        }
                  }

            // If the view exists, load it up and catch the output
               if(is_readable($view_location=VIEWS_LOCATION.$view.'.inc.php'))
                  {
                  // Start an output buffer so we can catch all output
                     ob_start();

                  // Open the view
                     require($view_location);

                  // Clean the output buffer and safe its contents
                     $r=ob_get_clean();
                  }
               else
                  {
                     throw new ArchetypeSystemException("Attempted to open non-existent view '${view_location}'");
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
       */
         public function controller($controller,$method='index',$args=array())
            {
               $r=false;

               if(empty($this->_['storage']['controllers'][$controller]))
                  {
                     if(is_readable($controller_location=CONTROLLERS_LOCATION.$controller.'.inc.php'))
                        {
                           $class=$controller.'_controller';
      
                           if(!class_exists($class))
                              {
                                 require($controller_location);
                              }
      
                           if(class_exists($class))
                              {
                                 $this->_['storage']['controllers'][$controller]=new $class($this->_);
                              }
                        }

                     if(empty($this->_['storage']['controllers'][$controller]))
                        {
                           throw new ArchetypeSystemException("Attempted to open non-existent controller '${controller}'");
                        }
                  }
      
               if(is_callable($call=array(&$this->_['storage']['controllers'][$controller],$method)))
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
         public function &settings($group,&$object=false,$require=false)
            {
               $r=false;

            // If the setting group isn't loaded, try to load it
               if(empty($this->_['storage']['settings'][$group]))
                  {
                     if(is_readable($group_location=SETTINGS_LOCATION."${group}.inc.php"))
                        {
                           require($group_location);

                           if(!empty($$group))
                              {
                                 $this->_['storage']['settings'][$group]=&$$group;
                              }
                        }
                     else
                        {
                           throw new ArchetypeSystemException('Attempted to open non-existent setting group "'.$group.'"');
                        }
                  }

            // Setting group is loaded, return a reference
               if(!empty($this->_['storage']['settings'][$group]))
                  {
                     $r=&$this->_['storage']['settings'][$group];

                  // Assign the model to a parameter inside of the passed object, if one was passed
                     if(is_object($object))
                        {
                           $object->settings[$group]=&$r;
                        }
                  }

            // Check if the setting meets the requirements
               if(is_array($r)&&is_array($require))
                  {
                     $missing_settings=array();

                     foreach($require as $settings)
                        {
                           if(!isset($r[$settings]))
                              {
                                 $missing_settings[]=$settings;
                              }
                        }

                     if(!empty($missing_settings))
                        {
                           $plural='';
                           if(count($missing_settings)>1)
                              {
                                 $plural='s';
                              }

                           throw new ArchetypeSystemException("Setting group '${group}' is missing required setting${plural} '".implode("', '",$missing_settings)."'.");
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
               // $out=eval("return ${class}::\$information;");
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
       */
         public function exists($type,$name,$method=false)
            {
               $r=false;

               if($type==='model')
                  {
                     $location=MODELS_LOCATION."${name}.inc.php";
                  }
               elseif($type==='view')
                  {
                     if(is_readable(VIEWS_LOCATION.$view.'.inc.php'))
                        {
                           $r=true;
                        }
                  }
               elseif($type==='controller')
                  {
                     $location=CONTROLLERS_LOCATION."${name}.inc.php";
                  }
               elseif($type==='automator')
                  {
                     $location=AUTOMATORS_LOCATION."${name}.inc.php";
                  }
               elseif($type==='injector')
                  {
                     $location=INJECTORS_LOCATION."${name}.inc.php";
                  }

               if(!empty($location))
                  {
                     $class=$name.'_'.$type;

                  // If the class simply doesn't exist, try to load it
                     if(!class_exists($class)&&is_readable($location))
                        {
                           require($location);
                        }

                  // Since is_callable() and method_exists() don't care about visibility we'll statically discount methods we KNOW shouldn't be called externally
                  // UPDATE: When PHP fixes visibility issues, do this properly
                     $hide=array('__construct','construct','__destruct','destruct');

                  // Check if it's here and optionally test for a method
                     if(class_exists($class)&&(empty($method)||(!in_array($method,$hide)&&method_exists($class,$method))))
                        {
                           $r=true;
                        }
                  }

               return $r;
            }
      }
?>
