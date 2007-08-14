<?php if(!defined('SEBODB_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                          S e b o D B                           //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * SeboDB global file
 *
 * @package SeboDB
 * @subpackage system
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/sebodb
 * @version 2007.4.1
 */

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
 * SeboDB driver specification
 * @abstract
 * @todo Better document the driver specification - more specifically, what drivers are required to do and how they are required to act
 * @todo Finish phpDoc tags
 * @todo Add reference operators to all method declarations for driver / resource parameters
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
               $this->close($this->connection);
            }

      /**
       * If the object is serialized, close the connection
       * @access public
       * @return void
       */
         final public function __sleep()
            {
               $this->close($this->connection);
            }

      /**
       * If the object is unserialized, try to automatically resume the connection
       * @access public
       * @return void
       */
         final public function __wakeup()
            {
               $this->open($this->configuration);
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
       * @var resource
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
