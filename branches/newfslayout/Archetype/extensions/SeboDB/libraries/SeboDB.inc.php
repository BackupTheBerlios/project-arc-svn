<?php if(!defined('A_VERSION')){die();}

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
 * @version 2007.11.9
 */

/**
 * Define SeboDB's version
 */
   define('SEBODB_VERSION','2007.11.9');

/**
 * Controller location
 */
   if(!defined('SEBODB_CONTROLLERS_LOCATION'))
      {
         define('SEBODB_CONTROLLERS_LOCATION',A_SYSTEM_LOCATION.'SeboDB/controllers/');
      }
/**
 * Driver location
 */
   if(!defined('SEBODB_DRIVERS_LOCATION'))
      {
         define('SEBODB_DRIVERS_LOCATION',A_SYSTEM_LOCATION.'SeboDB/drivers/');
      }
/**
 * Global file location
 */
   if(!defined('SEBODB_GLOBAL_LOCATION'))
      {
         define('SEBODB_GLOBAL_LOCATION',A_SYSTEM_LOCATION.'SeboDB/SeboDB.global.inc.php');
      }

/**
 * Constant for fetching numeric arrays
 */define('SEBODB_ARRAY'  ,0);
/**
 * Constrant for fetching associative arrays
 */define('SEBODB_ASSOC'  ,1);
/**
 * Constant for fetching objects
 */define('SEBODB_OBJECT' ,2);
/**
 * Constant for fetching fields
 */define('SEBODB_FIELD'  ,3);
/**
 * Constant for fetching lengths
 */define('SEBODB_LENGTHS',4);

/**
 * Exception class for system
 */class SeboDBSystemException extends Exception {}
/**
 * Exception class for drivers to use
 */class SeboDBDriverException extends Exception {}
/**
 * Exception class for controllers to use
 */class SeboDBControllerException extends Exception {}

/**
 * Originally the stock SeboDB class, but modified to work nicer inside Archetype.
 * @todo Move the linking logic into create() and prepare a separation of construct() when it's not inside Archetype
 */
   class A_SeboDB_model extends A_model
      {
      /**
       * Stores the objects, ready-to-go
       */
         private $linked_data_objects=array();
      /**
       * Constructor tries to automagically open up the associated settings and coordinate DB setup
       * @access public
       * @return void
       */
         public function construct()
            {
               if($this->system->settings('database',$this))
                  {
                  // Loop database configurations
                     foreach($this->settings['database'] as $index=>$value)
                        {
                        // This logic is specifically for creating links from one driver to multiple controllers
                           if(!empty($this->settings['database'][$index]['link']))
                              {
                              // Figure out what we're linking to
                                 $link=&$this->settings['database'][$index]['link'];

                              // Create a new LDO but use the link's driver if it exists
                                 if(!empty($this->$link->driver))
                                    {
                                       $this->create($this->settings['database'][$index]['controller'],$this->$link->driver,$index);
                                    }
                              }
                           else
                              {
                              // Create a new LDO
                                 $this->create($this->settings['database'][$index]['controller'],$this->settings['database'][$index]['driver'],$index);

                              // Open the connection
                                 $this->linked_data_objects[$index]->open($this->settings['database'][$index]);
                              }
                        }
                  }

            // If the class was opened but we have no connections open at this point or if we failed to open a connection, throw an exception
               if(empty($this->linked_data_objects))
                  {
                     throw new SeboDBSystemException('Could not find any connections to open');
                  }
               else
                  {
                     foreach($this->linked_data_objects as $key=>&$value)
                        {
                           if(empty($value->driver->connection))
                              {
                                 throw new SeboDBSystemException("Could not open connection for configuration '%{key}'");
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
                     $controller_class='SeboDB_controller_'.$controller;

                     if(!class_exists($controller_class))
                        {
                           require($controller_file);
                        }
                  }

            // Make sure our driver class exists then create an instance of it
               if(is_string($driver)&&is_readable($driver_file=SEBODB_DRIVERS_LOCATION.$driver.'/'.$driver.'.inc.php'))
                  {
                     $driver_class='SeboDB_driver_'.$driver;

                     if(!class_exists($driver_class))
                        {
                           require($driver_file);
                        }

                     if(class_exists($driver_class))
                        {
                           $driver_object=new $driver_class;
                        }
                  }
            // Insert an existing driver object instead
               elseif(is_object($driver))
                  {
                     $driver_object=&$driver;
                  }

            // Check if our controller class exists and if our driver is working then link them
               if(empty($this->$id)&&class_exists($controller_class)&&($driver_object instanceof SeboDB_driver))
                  {
                     $r=new $controller_class($driver_object);

                     $this->linked_data_objects[$id]=&$r;
                  }

               return $r;
            }

      /**
       * Returns a linked data object
       * @access public
       * @param string $linked_data_object Name of data object to return (as known by unique identifier)
       * @return boolean False on failure, working linked data object on success
       */
         public function &get($linked_data_object)
            {
               $r=false;

               if(!empty($this->linked_data_objects[$linked_data_object]))
                  {
                     $r=&$this->linked_data_objects[$linked_data_object];
                  }

               return $r;
            }

         public function is_driver($driver)
            {
               $r=false;

               if(is_string($driver)&&is_readable($driver_file=SEBODB_DRIVERS_LOCATION.$driver.'/'.$driver.'.inc.php'))
                  {
                     $driver_class='SeboDB_driver_'.$driver;

                     if(!class_exists($driver_class))
                        {
                           require($driver_file);
                        }

                     if(class_exists($driver_class))
                        {
                           $r=true;
                        }
                  }

               return $r;
            }

         public function is_controller($controller)
            {
               $r=false;

               if(is_string($controller)&&is_readable($controller_file=SEBODB_DRIVERS_LOCATION.$controller.'/'.$controller.'.inc.php'))
                  {
                     $controller_class='SeboDB_controller_'.$controller;

                     if(!class_exists($controller_class))
                        {
                           require($controller_file);
                        }

                     if(class_exists($controller_class))
                        {
                           $r=true;
                        }
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
                     $instance=&$this->linked_data_objects[$instance];
                  }

               unset($instance); // TODO: unset(&$instance) ?

               return $r;
            }

      /**
       * Destructor tries to close any open connections which is purely for cleanliness, since it will happen regardless of our encouragement
       * @access public
       * @return void
       */
         public function destruct()
            {
               if(!empty($this->settings['database']))
                  {
                     foreach($this->settings['database'] as $index=>$value)
                        {
                           $this->destroy($index);
                        }
                  }
            }
      }

/**
 * SeboDB driver specification
 * @abstract
 * @todo Better document the driver specification - more specifically, what drivers are required to do and how they are required to act
 * @todo Finish phpDoc tags
 */
   abstract class SeboDB_driver
      {
      /**
       * Stores the driver's existing connection for convenience
       * @access public
       * @var mixed
       */public $connection=false;
      /**
       * Stores the driver's connect information
       * @access private
       * @var array
       */private $configuration=array();
      /**
       * Stores the driver's operation history for debugging and informational purposes
       * @access public
       * @var array
       */public $history=array();

      /**
       * The driver's open() method definition
       * @access public
       * @abstract
       * @param array $info Associative array containing information required for the connection but pertinent to the selected driver type
       * @return void
       */
         abstract public function open(&$config);

      /**
       * The driver's query() method definition
       * @access public
       * @abstract
       * @param string $name Database name
       * @return void
       */
         abstract public function query($sql,&$connection);

      /**
       * The driver's affected() method definition
       * @access public
       * @abstract
       * @param string $name Database name
       * @return void
       */
         abstract public function affected(&$query);

      /**
       * The driver's results() method definition
       * @access public
       * @abstract
       * @param string $name Database name
       * @return void
       */
         abstract public function results(&$query);

      /**
       * The driver's insert_id() method definition
       * @access public
       * @abstract
       * @param string $name Database name
       * @return void
       */
         abstract public function insert_id(&$query);

      /**
       * The driver's escape() method definition
       * @access public
       * @abstract
       * @param string $name Database name
       * @return void
       */
         abstract public function escape(&$string,&$connection);

      /**
       * The driver's fetch() method definition
       * @access public
       * @abstract
       * @param string $name Database name
       * @return void
       */
         abstract public function fetch(&$query,$type=0);

      /**
       * The driver's free() method definition
       * @access public
       * @abstract
       * @param string $name Database name
       * @return void
       */
         abstract public function free(&$query);

      /**
       * The driver's ping() method definition
       * @access public
       * @abstract
       * @param string $name Database name
       * @return void
       */
         abstract public function ping(&$connection);

      /**
       * The driver's error() method definition
       * @access public
       * @abstract
       * @param string $name Database name
       * @return void
       */
         abstract public function error(&$connection);

      /**
       * The driver's close() method definition
       * @access public
       * @abstract
       * @param string $name Database name
       * @return void
       */
         abstract public function close(&$connection);

      /**
       * If the connection isn't properly closed by the time the object is being removed from memory, automatically do it
       * @access public
       * @return void
       */
         final public function __destruct()
            {
               if(!empty($this->connection))
                  {
                     $this->close($this->connection);
                  }
            }

      /**
       * If the object is serialized, close the connection
       * @access public
       * @return void
       */
         final public function __sleep()
            {
               if(!empty($this->connection))
                  {
                     $this->close($this->connection);
                  }
            }

      /**
       * If the object is unserialized, try to automatically resume the connection
       * @access public
       * @return void
       */
         final public function __wakeup()
            {
               if(!empty($this->configuration))
                  {
                     $this->open($this->configuration);
                  }
            }
      }

/**
 * SeboDB controller parent class which automates driver loading for children controllers
 */
   class SeboDB_controller
      {
      /**
       * Stores a reference to the driver
       * @access public
       * @var object
       */
         public $driver=false;

      /**
       * Constructor automates insertion of drivers into controllers
       * @access public
       * @param object $driver Instance of an active driver
       * @return void
       */
         public function __construct(&$driver)
            {
               $this->driver=&$driver;
            }
      }
?>
