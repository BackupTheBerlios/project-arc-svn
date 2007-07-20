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
 * @version 2007.5.7
 */

/**
 * Define SeboDB's version
 */
   define('SEBODB_VERSION','2007.4.13');

/**
 * Controller location
 */
   if(!defined('SEBODB_CONTROLLERS'))
      {
         define('SEBODB_CONTROLLERS',COMPONENTS_LOCATION.'/SeboDB/controllers/');
      }
/**
 * Driver location
 */
   if(!defined('SEBODB_DRIVERS'))
      {
         define('SEBODB_DRIVERS',COMPONENTS_LOCATION.'/SeboDB/drivers/');
      }
/**
 * Global file location
 */
   if(!defined('SEBODB_GLOBAL'))
      {
         define('SEBODB_GLOBAL',COMPONENTS_LOCATION.'/SeboDB/SeboDB.global.inc.php');
      }

/**
 * Require SeboDB's core classes
 */
   require(SEBODB_GLOBAL);

/**
 * Originally the stock SeboDB class, but modified to work nicer inside Archetype.
 */
   class SeboDB_model extends Archetype_model
      {
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
               if(is_readable($controller_file=SEBODB_CONTROLLERS.$controller.'/'.$controller.'.inc.php'))
                  {
                     $controller_name='SeboDB_controller_'.$controller;

                     if(!class_exists($controller_name))
                        {
                           require($controller_file);
                        }
                  }

            // Make sure our driver class exists then create an instance of it
               if(is_string($driver)&&is_readable($driver_file=SEBODB_DRIVERS.$driver.'/'.$driver.'.inc.php'))
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
               if(is_readable($controller_file=SEBODB_CONTROLLERS.$controller.'/'.$controller.'.inc.php'))
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
      }
?>
