<?php if(!defined('A_VERSION')){die();}

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
 * @version 2007.7.16
 */

   $information=array('version'=>A_VERSION,
                      'author' =>'Justin Krueger <fuzzywoodlandcreature@gmail.com>');

/**
 * Useful random junk
 */
   class A_oddlib_model extends A_model
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
                           self::stripslashes($element);
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
       * @return boolean True if today is the first of April, false if it's not
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
               return preg_replace('/[^\w'.preg_quote($exceptions).']/','',$input);
            }

      /**
       * Creates a new associative array based on $original but with the specified keys in $overwrite written with their respective data
       * @access public
       * @param mixed $original
       * @param mixed $overwrite
       * @return mixed
       */
         public function array_overwrite(&$overwrite,&$original)
            {
               if(is_array($overwrite)&&is_array($original))
                  {
                     foreach($original as $key=>&$value)
                        {
                           if(empty($overwrite[$key]))
                              {
                                 $overwrite[$key]=$value;
                              }
                           else
                              {
                                 $this->array_overwrite($overwrite[$key],$original[$key]);
                              }
                        }
                  }
            }
      }
?>
