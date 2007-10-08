<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Input automator
 *
 * @package Archetype
 * @subpackage system
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.9.10
 */

   $construct=500;

/**
 * 
 */
   class input_automator extends A_automator
      {
         public function construct()
            {
            // I can ask gently and they'll go in this case...
               ini_set('magic_quotes_runtime','0');

            // But in this one it takes a hate-powered flamethrower
               if(ini_get('magic_quotes_gpc'))
                  {
                     $oddlib=&$this->system->model('oddlib');

                     if(!empty($_GET))
                        {
                           $oddlib->stripslashes($_GET);
                        }
                     if(!empty($_POST))
                        {
                           $oddlib->stripslashes($_POST);
                        }
                     if(!empty($_COOKIE))
                        {
                           $oddlib->stripslashes($_COOKIE);
                        }
                     if(!empty($_FILES))
                        {
                           $oddlib->stripslashes($_FILES);
                        }
                     if(!empty($_SESSION))
                        {
                           $oddlib->stripslashes($_SESSION);
                        }
                  }

            // Yank the GET variable a
               $input=explode('/',trim($_GET['a'],'/'));

            // Shave off necessary components and store them inside the system for reference
               $this->_['information']['input']['controller']=array_shift($input);
               $this->_['information']['input']['method']=array_shift($input);
               $this->_['information']['input']['parameters']=&$input;

            // If the method is null, default to index
               if(is_null($this->_['information']['input']['method']))
                  {
                     $this->_['information']['input']['method']='index';
                  }

            // If the parameters are null, default to blank array
               if(is_null($this->_['information']['input']['parameters']))
                  {
                     $this->_['information']['input']['parameters']=array();
                  }
            }
      }
?>
