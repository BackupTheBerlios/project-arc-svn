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
 * @version 2007.4.1
 * @todo Compound layered if/else where possible
 */

/**
 * Archetype's system model for providing support to other components
 */
   class system_model extends Archetype_model
      {
      /**
       * Stores the views configuration
       * @access public
       * @var array
       */
         public $views_config=array();

      /**
       * Stores the system configuration
       * @access public
       * @var array
       */
         public $system_config=array();

      /**
       * Upon construct, assign a few important things to the object
       */
         protected function construct()
            {
            // Assign global view values
               $this->views_config=&$this->config('views');
               $this->system_config=&$this->config('system');
            }

      /**
       * Open libraries and optionally assign them to the object passed
       * @access public
       * @param string $model String name of the model to open
       * @param mixed $object Optional object for the model to be automatically inserted into
       * @return mixed Returns a reference to the model on success, false on failure
       * @todo Possibly add the ability to specify $model as a numeric array of models to open
       */
         public function &model($model,$object=false)
            {
               $r=false;

            // If we don't have an existing instance of the model, try to create one
               if(empty($this->_['models'][$model]))
                  {
                     if(is_readable($model_location=COMPONENTS_LOCATION."/${model}/model.inc.php"))
                        {
                           if(!class_exists($model.'_model'))
                              {
                                 require($model_location);
                              }

                           $class=$model.'_model';

                           if(class_exists($class))
                              {
                                 $this->_['models'][$model]=new $class($this->_);
                                 $r=&$this->_['models'][$model];
                              }
                        }
                     else
                        {
                           throw new ArchetypeSystemException("Attempted to open non-existent model '${model}'");
                        }
                  }
            // Existing model found, return a reference to it
               else
                  {
                     $r=&$this->_['models'][$model];
                  }

            // Assign the model to a parameter inside of the passed object, if one was passed
               if(!empty($object))
                  {
                     $object->$model=&$r;
                  }

               return $r;
            }

      /**
       * Load and return a view
       * @access public
       * @param string $view String name of the view to open
       * @param array $input Associative array of variables to provide to the view
       * @return mixed Returns a reference to the model on success, false on failure
       */
         public function view($view,$input=false)
            {
               $r=false;

            // Merge global and local values, local overwriting global
               if(is_array($input))
                  {
                     $input=array_merge($input,$this->views_config['global']);
                  }
            // Just link the two because there was no input array
               else
                  {
                     $input=&$this->views_config['global'];
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
                           throw new ArchetypeSystemException("View input tried overwriting '${$index}' which is not allowed");
                        }
                  }

            // Makes supporting subdirectories easy
               $view=explode('/',$view);

            // If the view exists, load it up and catch the output
               $component=array_shift($view);
               if(is_readable($view_location=COMPONENTS_LOCATION."/${component}/views/".implode('/',$view).'.inc.php'))
                  {
                     ob_start();
                     require($view_location);
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
       * @todo Add a numeric return system for error codes
       * @todo Store created objects so if we get another call to the same controller but a different method we don't use more ram
       */
         public function controller($controller,$method='index',$args=array())
            {
               $r=false;

            // Find, load, run controllers
               if(is_readable($controller_location=COMPONENTS_LOCATION."/${controller}/controller.inc.php"))
                  {
                     $class=$controller.'_controller';

                     if(!class_exists($class))
                        {
                           require($controller_location);
                        }

                     if(class_exists($class))
                        {
                           $object=new $class($this->_);

                           if(method_exists($object,$method)&&is_callable(array(&$object,$method)))
                              {
                                 call_user_func_array(array(&$object,$method),$args);
                                 $r=true;
                              }
                        }
                  }
               else
                  {
                     throw new ArchetypeSystemException("Attempted to open non-existent controller '${controller}'");
                  }

               return $r;
            }

      /**
       * Loads and returns configuration files, optionally providing a requirement check on content
       * @access public
       * @param $config The configuration's name to load
       * @param $require Optionally checks the config for the keys provided in this array
       * @return mixed A reference to either the config specified or the key specified if true, false otherwise
       */
         public function &config($config,$require=false)
            {
               $r=false;

            // If the config isn't loaded, try to load it
               if(empty($this->_['config'][$config]))
                  {
                     if(is_readable($config_location=CONFIG_LOCATION."/${config}.inc.php"))
                        {
                           require($config_location);

                           if(!empty($$config))
                              {
                                 $this->_['config'][$config]=&$$config;

                                 $r=&$this->_['config'][$config];
                              }
                        }
                     else
                        {
                           throw new ArchetypeSystemException('Attempted to open non-existent configuration "'.$config.'"');
                        }
                  }
            // Config is loaded, just return a reference
               else
                  {
                     $r=&$this->_['config'][$config];
                  }

            // Check if the config meets the requirements
               if(is_array($r)&&is_array($require))
                  {
                     $missing_keys=array();

                     foreach($require as $key)
                        {
                           if(!isset($r[$key]))
                              {
                                 $missing_keys[]=$key;
                              }
                        }

                     if(!empty($missing_keys))
                        {
                           $plural='';
                           if(count($missing_keys)>1)
                              {
                                 $plural='s';
                              }

                           throw new ArchetypeSystemException("Configuration for '${config}' is missing required key${plural} '".implode("', '",$missing_keys)."'.");
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
         public function depend($type=false,$component=false,$key=false,$regex=false)
            {
               // $out=eval("return ${class}::\$information;");
               // Open the type of component by type
               // Grab module info, parse with preg
               // If false, dump an exception
            }

      /**
       * Used to test whether or not a component exists
       * @access public
       * @param $type Either model, view, controller or automator
       * @param $component Component name
       * @param $component Optional method name
       * @return bool True if the specified component exists, false if not
       */
         public function exists($type,$component,$method=false)
            {
               $r=false;

               $types=array('model','view','controller','automator');

               if($type!=='view'&&in_array(strtolower($type),$types,true))
                  {
                     $class=$component.'_'.$type;

                  // If the class simply doesn't exist, try to load it
                     if(!class_exists($class)&&is_readable($component_location=COMPONENTS_LOCATION."/${component}/${type}.inc.php"))
                        {
                           require($component_location);
                        }

                  // Since is_callable() and method_exists() don't care about visibility we'll statically discount methods we KNOW shouldn't be called externally
                     $hide=array('__construct','construct','__destruct','destruct');

                  // Check if it's here and optionally test for a method
                     if(class_exists($class)&&(empty($method)||(!in_array($method,$hide)&&method_exists($class,$method))))
                        {
                           $r=true;
                        }
                  }
            // We have to check views in another way since they're not class-based
               elseif($type==='view')
                  {
                  // Support sub directories easily
                     $view=explode('/',$component);

                     $component=array_shift($view);

                     if(is_readable(COMPONENTS_LOCATION."/${component}/views/".implode('/',$view).'.inc.php'))
                        {
                           $r=true;
                        }
                  }

               return $r;
            }
      }
?>
