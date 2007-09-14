<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                         S e b o  D B                           //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Modified version of SeboDB to work better with Archetype
 *
 * @package SeboDB
 * @subpackage system
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/sebodb
 * @version 2007.9.10
 */

/**
 * Define SeboDB's version
 */
   define('SEBODB_VERSION','2007.9.10');

/**
 * Controller location
 */
   if(!defined('SEBODB_CONTROLLERS_LOCATION'))
      {
         define('SEBODB_CONTROLLERS_LOCATION',SYSTEM_LOCATION.'SeboDB/controllers/');
      }
/**
 * Driver location
 */
   if(!defined('SEBODB_DRIVERS_LOCATION'))
      {
         define('SEBODB_DRIVERS_LOCATION',SYSTEM_LOCATION.'SeboDB/drivers/');
      }
/**
 * Global file location
 */
   if(!defined('SEBODB_GLOBAL_LOCATION'))
      {
         define('SEBODB_GLOBAL_LOCATION',SYSTEM_LOCATION.'SeboDB/SeboDB.global.inc.php');
      }

/**
 * Require SeboDB's core classes
 */
   require(SEBODB_GLOBAL_LOCATION);

/**
 * Originally the stock SeboDB class, but modified to work nicer inside Archetype.
 */
   class SeboDB_model extends A_model
      {
         public function construct()
            {
               if($this->system->settings('database',$this))
                  {
                     foreach($this->settings['database'] as $index=>$value)
                        {
                           if(!empty($this->settings['database'][$index]['link']))
                              {
                                 $link_name=&$this->settings['database'][$index]['link'];
                                 $this->create($this->settings['database'][$index]['controller'],$this->$link_name->driver,$index);
                              }
                           else
                              {
                                 $this->create($this->settings['database'][$index]['controller'],$this->settings['database'][$index]['driver'],$index);
                                 $this->$index->open($this->settings['database'][$index]);
                              }
                        }
                  }
            }
      /**
       * Create a new Linked Data Object(LDO), store a reference to it locally, then return it.
       * Optionally, should you pass a working instance of a driver as $driver, create a new LDO with that active driver.
       * @access public
       * @param string $controller String name of controller
       * @param mixed $driver Pass either a string name of driver or already-active driver
       * @param string $id Used to store LDOs as $this->$id
       * @return mixed Returns an LDO on success, false on failure
       */
         public function create($controller,$driver,$id)
            {
               $r=false;

            // Make sure our controller class exists
               if(is_readable($controller_file=SEBODB_CONTROLLERS_LOCATION.$controller.'/'.$controller.'.inc.php'))
                  {
                     $controller_name='SeboDB_controller_'.$controller;

                     if(!class_exists($controller_name))
                        {
                           require($controller_file);
                        }
                  }

            // Make sure our driver class exists then create an instance of it
               if(is_string($driver)&&is_readable($driver_file=SEBODB_DRIVERS_LOCATION.$driver.'/'.$driver.'.inc.php'))
                  {
                     $driver_name='SeboDB_driver_'.$driver;

                     if(!class_exists($driver_name))
                        {
                           require($driver_file);
                        }

                     if(class_exists($driver_name))
                        {
                           $driver_object=new $driver_name;
                        }
                  }
               elseif(is_object($driver))
                  {
                     $driver_object=&$driver;
                  }

            // Check if our controller class exists and if our driver is working then link them
               if(empty($this->$id)&&class_exists($controller_name)&&($driver_object instanceof SeboDB_driver))
                  {
                     $r=new $controller_name($driver_object);

                     $this->$id=&$r;
                  }

               return $r;
            }

      /**
       * Alters the controller of an existing LDO.
       * @access public
       * @param mixed $instance A reference to an existing LDO
       * @param string $controller String name of controller
       * @return bool Returns true on success, false on failure
       */
         public function alter(&$instance,$controller)
            {
               $r=false;

            // Make sure our controller class exists
               if(is_readable($controller_file=SEBODB_CONTROLLERS_LOCATION.$controller.'/'.$controller.'.inc.php'))
                  {
                     if(!class_exists($controller))
                        {
                           require($controller_file);
                        }
                  }

            // Make sure $instance isn't being altered to its current controller then check if our controller class exists and if our driver is working then link them
               if(!($instance instanceof $controller)&&class_exists($controller)&&($instance->driver instanceof SeboDB_driver))
                  {
                     $controller_name='SeboDB_'.$controller.'_controller';

                     $instance=new $controller_name($instance->driver);

                     $r=true;
                  }

               return $r;
            }

      /**
       * Destroys an LDO.
       * @access public
       * @param mixed $instance An instance of an existing LDO or a string representation of a local LDO
       * @return mixed Returns an LDO on success, false on failure
       */
         public function destroy(&$instance)
            {
               $r=false;

            // Check if our instance is a string, in which case assume it's coming from $this->$instance
               if(is_string($instance))
                  {
                     $instance=&$this->$instance;
                  }

               unset($instance);

               return $r;
            }

         public function destruct()
            {
               if(!empty($this->settings['database']))
                  {
                     foreach($this->settings['database'] as $index=>$value)
                        {
                           if(!empty($this->$index->connection))
                              {
                                 $this->$index->close();
                              }

                           $this->destroy($index);
                        }
                  }
            }
      }
?>
