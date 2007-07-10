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
 * @version 2007.5.9
 */

// Audible errors because we can't have them ignored
   error_reporting(E_ALL);

// Archetype's version definition
   define('ARCHETYPE_VERSION','2007.5.9');

// System location
   if(!defined('SYSTEM_LOCATION'))
      {
         define('SYSTEM_LOCATION','./system/'); // Always have a / on both sides of a directory since it doesn't hurt to have extras but it does to not have enough
      }
// Config location
   if(!defined('CONFIG_LOCATION'))
      {
         define('CONFIG_LOCATION',SYSTEM_LOCATION.'/config/'); // Read above comment
      }
// Components location
   if(!defined('COMPONENTS_LOCATION'))
      {
         define('COMPONENTS_LOCATION',SYSTEM_LOCATION.'/components/'); // Read above comment
      }

// Universal variable passed between every object extended from Archetype
   $_=array
      (
         'lists'=>array // Store lists of stuff
            (
               'components'=>array(), // All components
               'automators'=>array // Automators
                  (
                     'construct'=>array(), // Construct priority
                     'destruct'=>array() // Destruct priority
                  ),
               'configs'=>array() // Configurations
            ),
         'automators'=>array(), // Store automator objects
         'models'=>array // Store model objects
            (
               'system'=>false // Default the system model so we have a place to point references before it's instantiated
            ),
         'marks'=>array('archetype_start'=>microtime(true)) // Shouldn't be here, but we need to be able to take an accurate measurement
      );

// Open up our system class definitions
   require(SYSTEM_LOCATION.'/global.inc.php');

// Scan the filesystem for component directories
   $_['lists']['components']=scandir(COMPONENTS_LOCATION);

// Trim . and .. off
   $_['lists']['components']=array_slice($_['lists']['components'],2);

// Execute in a sandbox so we can catch exceptions
  try
      {
      // Loop components and get automator information
         foreach($_['lists']['components'] as $component)
            {
               if(is_readable($component_location=COMPONENTS_LOCATION.'/'.$component.'/automator.inc.php'))
                  {
                     if(!class_exists($class=$component.'_automator'))
                        {
                           $construct=$destruct=0;

                           require($component_location);

                           if(class_exists($class))
                              {
                                 $_['lists']['automators']['construct'][$component]=$construct;
                                 $_['lists']['automators']['destruct'][$component]=$destruct;
                              }
                        }
                  }
            }

      // Sort constructor and destructor orders
         arsort($_['lists']['automators']['construct'],SORT_NUMERIC);
         arsort($_['lists']['automators']['destruct'],SORT_NUMERIC);

      // Run constructors
         foreach($_['lists']['automators']['construct'] as $component=>$priority)
            {
               if(class_exists($class=$component.'_automator'))
                  {
                     $_['automators'][$component]=new $class(&$_);
                  }
            }

      // Run destructors
         foreach($_['lists']['automators']['destruct'] as $component=>$priority)
            {
               if(!empty($_['automators'][$component]))
                  {
                     unset($_['automators'][$component]);
                  }
            }
      }
// If an exception was caught, finish up with a professional, helpful error
   catch(Exception $x)
      {
      // Allow components to set a new exception handler
         if(!empty($_['config']['archetype']['exception_handler']))
            {
               call_user_func($_['config']['archetype']['exception_handler'],$x);
            }
      // But default to trigger_error()
         else
            {
            // __toString() because PHP5.1 is stupid
               trigger_error($x->__toString(),E_USER_ERROR);
            }
      }
?>
