<?php if(!defined('A_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * System automator
 *
 * @package Archetype
 * @subpackage system
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.9.10
 */

   $destruct=1000;

/**
 * System automator component class.  Without this, absolutely nothing would work.
 */
   class A_system_automator extends A_automator
      {
      /**
       * Runs controllers based on arguments provided to the system
       * @access public
       * @return void
       */
         public function destruct()
            {
            // If the controller exists, run it
               if($this->system->exists('controller',$this->_['information']['input']['controller'],$this->_['information']['input']['method']))
                  {
                     $this->system->controller($this->_['information']['input']['controller'],$this->_['information']['input']['method'],$this->_['information']['input']['parameters']);
                  }
            // Otherwise run system/not_found
               else
                  {
                     $this->system->controller('system','not_found',array('controller'=>&$this->_['information']['input']['controller'],'method'=>&$this->_['information']['input']['method'],'parameters'=>&$parameters));
                  }
            }
      }
?>
