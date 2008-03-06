<?php if(!defined('A_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Language model
 *
 * @package Archetype
 * @subpackage language
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.4.1
 */

   if(!defined('A_LANGUAGES_LOCATION')) { define('A_LANGUAGES_LOCATION',A_SYSTEM_LOCATION.'languages/'); }

/**
 * Makes language abstraction easier and more organized
 */
   class A_language_model extends Archetype_model
      {
         public $records=array();

      /**
       * Fetches a language record, stores it internally for future reference
       */
         public function &fetch($record,$inject=false)
            {
               $r=false;

               if(!empty($this->records[$record]))
                  {
                     $r=&$this->records[$record];
                  }
               else
                  {
                     // open language dir / file combination, parse variables, store internally
                  }

            // Allow easy string injection
               if(!empty($inject)&&is_array($inject))
                  {
                     array_unshift($inject,$r);

                     $r=call_user_func_array('sprintf',$inject);
                  }

               return $r;
            }
      }
?>
