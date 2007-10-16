<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Router
 *
 * @package Archetype
 * @subpackage system
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.5.8
 */

   $construct=750;

/**
 * Routers - only works for HTTP for now.  Archetype needs some sort of imput abstraction before it can route for anything else.
 */
   class A_router_automator extends A_automator
      {
         public function construct()
            {
            // Pop open the router model
               $this->system->model('router',$this);

            // Set our default route if none was specified so even it is subject to aliasing
               if(empty($_GET['a']))
                  {
                     $_GET['a']=$this->router->settings['routes']['system']['default'];
                  }
               else
                  {
                  // Assign potential aliases
                     $this->router->alias($_GET['a']);
                  }
            }
      }
?>
