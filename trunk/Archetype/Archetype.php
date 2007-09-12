<?php

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
 * @version 2007.9.10
 */

// Audible errors because we can't have them ignored for development purposes (feel free to silence a production application if you're sure it's safe)
   error_reporting(E_ALL);

/**
 * Archetype's version
 */define('ARCHETYPE_VERSION','2007.9.10');

/**
 * Location of the system directory
 */if(!defined('SYSTEM_LOCATION')) { define('SYSTEM_LOCATION','Archetype/'); }
/**
 * Location of automator storage
 */if(!defined('AUTOMATORS_LOCATION')) { define('AUTOMATORS_LOCATION',SYSTEM_LOCATION.'automators/'); }
/**
 * Location of injector storage
 */if(!defined('INJECTORS_LOCATION')) { define('INJECTORS_LOCATION',SYSTEM_LOCATION.'injectors/'); }
/**
 * Location of config storage
 */if(!defined('SETTINGS_LOCATION')) { define('SETTINGS_LOCATION',SYSTEM_LOCATION.'settings/'); }
/**
 * Location of model storage
 */if(!defined('MODELS_LOCATION')) { define('MODELS_LOCATION',SYSTEM_LOCATION.'models/'); }
/**
 * Location of view storage
 */if(!defined('VIEWS_LOCATION')) { define('VIEWS_LOCATION',SYSTEM_LOCATION.'views/'); }
/**
 * Location of controller storage
 */if(!defined('CONTROLLERS_LOCATION')) { define('CONTROLLERS_LOCATION',SYSTEM_LOCATION.'controllers/'); }
/**
 * Location of global classes
 */if(!defined('GLOBAL_LOCATION')) { define('GLOBAL_LOCATION',SYSTEM_LOCATION.'global.inc.php'); }

/**
 * Universal variable passed between every object extended from Archetype
 * @var array
 */
   $_=array
      (
         'information'=>array // Storage of miscellaneous information
            (
               'priority'=>array('construct'=>array(),'destruct'=>array()), // Automator priority
               'timings'=>array('archetype|start'=>microtime(true)), // Benchmark timings and the system's begin time
               'lists'=>array('automators'=>array(),'injectors'=>array()) // Lists of component types that require to be opened all at once
            ),
         'storage'=>array // Storage of Archetype-derived objects
            (
               'settings'=>array(),
               'automators'=>array(),
               'injectors'=>array(),
               'models'=>array('system'=>false),
               'controllers'=>array()
            ),
      );

// Open up our global class definitions
   require(GLOBAL_LOCATION);

// Scan the filesystem for component directories
   $_['information']['lists']['automators']=array_slice(scandir(AUTOMATORS_LOCATION),2);

// Execute in a sandbox so we can catch exceptions
   try
      {
      // Open up our automators
         foreach($_['information']['lists']['automators'] as &$automator)
            {
               $automator=str_replace('.inc.php','',$automator);

               if(is_file($location=AUTOMATORS_LOCATION.$automator.'.inc.php')&&is_readable($location)&&$automator{1}!='.')
                  {
                     $class=$automator.'_automator';

                     require($location);

                     if(class_exists($class))
                        {
                           $_['information']['priority']['construct'][$automator]=eval("return ${class}::\$construct;");
                           $_['information']['priority']['destruct'][$automator]=eval("return ${class}::\$destruct;");
                        }
                  }
            }

      // Sort constructor and destructor orders
         arsort($_['information']['priority']['construct'],SORT_NUMERIC);
         arsort($_['information']['priority']['destruct'],SORT_NUMERIC);

      // Run constructors
         foreach($_['information']['priority']['construct'] as $automator=>$priority)
            {
               if(class_exists($class=$automator.'_automator'))
                  {
                     $_['storage']['automators'][$automator]=new $class($_);
                  }
            }

      // Run destructors
         foreach($_['information']['priority']['destruct'] as $automator=>$priority)
            {
               if(!empty($_['storage']['automators'][$automator]))
                  {
                     unset($_['storage']['automators'][$automator]);
                  }
            }
      }
// If an exception was caught, finish up with a professional, helpful error
   catch(Exception $x)
      {
      // Allow components to set a new exception handler
         if(!empty($_['information']['settings']['system']['exception_handler']))
            {
               call_user_func($_['information']['settings']['system']['exception_handler'],$x);
            }
      // But default to trigger_error()
         else
            {
               trigger_error($x->__toString(),E_USER_ERROR); // __toString() because PHP5.1 is stupid
            }
      }
?>
