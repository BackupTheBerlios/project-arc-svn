<?php if(!defined('A_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                       www.SeboCorp.com                         //
   ////////////////////////////////////////////////////////////////////

/**
 * Router model
 *
 * @package Archetype
 * @subpackage router
 * @author Justin Krueger <justin@sebocorp.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://sebocorp.com/archetype
 * @version 2007.4.1
 */

/**
 * Created to separate the router functionality from the actual router so other components can use it
 * @todo Change alias to return a string and create overwrite that overwrites the input reference
 */
   class A_router_model extends A_model
      {
      /**
       * Construct
       * @access public
       * @return void
       */
         public function construct()
            {
            // Assign our settings to the class so we don't have to fiddle with it a bunch later
               $this->system->settings('routes',$this);
            }

      /**
       * Modifies the referenced $input string according to aliases in the routes settings
       * @param string $input Reference to a string you want to filter
       * @access public
       * @return void
       */
         public function alias(&$input)
            {
            // If there's aliases to be assigned, do it
               if(!empty($this->settings['routes']['aliases']))
                  {
                     foreach($this->settings['routes']['aliases'] as $index=>&$value)
                        {
                           if(!empty($index)&&!empty($value)&&(!$input=@preg_replace($index,$value,$input)))
                              {
                                 throw new A_Exception("Route '${index}'=>'${value}' is an invalid regular expression");
                              }
                        }
                  }
            }
      }
?>
