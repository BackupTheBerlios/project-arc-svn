<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * An extension with random handy functions
 *
 * @package Archetype
 * @subpackage oddlib
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.4.6
 */

/**
 * Useful random junk
 */
   class oddlib_model extends Archetype_model
      {
      /**
       * Created specifically for wiping out magic_quotes but probably has uses for other things too
       * @access public
       * @param mixed $input A reference to a string or array
       * @return void Modifies the parameter supplied
       */
         public function stripslashes(&$input)
            {
               if(is_array($input))
                  {
                     foreach($input as &$element)
                        {
                           $this->stripslashes(&$element);
                        }
                  }
               elseif(is_string($input)&&!empty($input))
                  {
                     $input=stripslashes($input);
                  }
            }

      /**
       * Can be extremely useful for the occasional easter egg
       * @access public
       * @return bool True if today is the first of April, false if it's not
       */
         public function is_april_fools()
            {
               $r=false;

               if(date('nj')==='41')
                  {
                     $r=true;
                  }

               return $r;
            }

      /**
       * Useful for stripping all characters from a string except those that are alphanumeric or specified as parameters
       * @access public
       * @param $input string String you want to strip
       * @return string Stripped version of $input
       */
         public function alphanumeric($input,$exceptions='')
            {
               return preg_replace('/[^A-Za-z0-9'.preg_quote($exceptions).']/','',$input);
            }
      }
?>
