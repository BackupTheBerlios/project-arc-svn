<?php if(!defined('ARCHETYPE_VERSION')){die();}

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
 * @version 2007.5.6
 */

   $construct=1000;
   $destruct=1000;

/**
 * System automator component class.  Without this absolutely nothing would work.
 */
   class system_automator extends Archetype_automator
      {
      /**
       * Injects Archetype's system model into storage so other components may take advantage of it and sets up the environment for other components
       * @access public
       * @return void
       */
         public function construct()
            {
            // Statically require Archetype's system model
               require(COMPONENTS_LOCATION.'/system/model.inc.php');

            // Make a new instance of the system model and put it where it woud normally go in the universal array
               $this->_['models']['system']=new system_model(&$this->_);

            // Link to this object for convenience
               $this->system=&$this->_['models']['system'];

            // Initialize the event layer
               $this->system->model('event');

            // I can ask gently and they'll go in this case...
               ini_set('magic_quotes_runtime','0');

            // But in this one it takes a hate-powered flamethrower
               if(ini_get('magic_quotes_gpc'))
                  {
                     $this->system->model('oddlib',&$this);

                     $this->oddlib->stripslashes(&$_GET);
                     $this->oddlib->stripslashes(&$_POST);
                     $this->oddlib->stripslashes(&$_COOKIE);
                     $this->oddlib->stripslashes(&$_FILES);
                  }

            // Start a session
               session_start();
            }

      /**
       * Runs controllers based on arguments provided to the system
       * @access public
       * @return void
       */
         public function destruct()
            {
            // Yank the GET variable x
               $parameters=explode('/',$_GET['x']);

            // Shave off and store the component and method parameters if possible and link arguments to what's left
               $controller=array_shift($parameters);
               $method=array_shift($parameters);
               $args=&$parameters;

            // If the method is empty, default to index
               if(empty($method))
                  {
                     $method='index';
                  }

            // If the controller exists, run it
               if($this->system->exists('controller',$controller,$method))
                  {
                     $this->system->controller($controller,$method,$args);
                  }
            // Otherwise run system/not_found
               else
                  {
                     $this->system->controller('system','not_found',array('controller'=>&$controller,'method'=>&$method,'args'=>&$args));
                  }
            }
      }
?>
