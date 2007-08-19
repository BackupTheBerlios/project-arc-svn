<?php if(!defined('ARCHETYPE_VERSION')){die();}

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
 */
   class router_model extends A_model
      {
      /**
       * Construct
       * @access public
       * @return void
       */
         public function construct()
            {
            // Assign our config to the class so we don't have to fiddle with it a bunch later
               $this->system->config('routes',$this);
            }

      /**
       * Modifies the referenced $input string according to aliases in the routes config
       * @param string $input Reference to a string you want to filter
       * @access public
       * @return void
       */
         public function alias(&$input)
            {
            // If there's aliases to be assigned, do it
               if(!empty($this->config['routes']['aliases']))
                  {
                     foreach($this->config['routes']['aliases'] as $index=>&$value)
                        {
                           if(!empty($index)&&!empty($value)&&(!$input=@preg_replace($index,$value,$input)))
                              {
                                 throw new ArchetypeSystemException("Route '${index}'=>'${value}' is an invalid regular expression");
                              }
                        }
                  }
            }
      }
?>
