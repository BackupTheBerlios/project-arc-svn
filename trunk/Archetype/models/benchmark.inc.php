<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Benchmark Library
 *
 * @package Archetype
 * @subpackage benchmark
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.4.1
 * @todo Sometime in the future add the functionality present in phpSeBench (function/method profiling)
 */

/**
 * A simple benchmark utility
 */
   class A_benchmark_model extends A_model
      {
      /**
       * Marks a point to be measured later with $this->measure()
       * @access public
       * @param string $name The name under which the method will record the current timestamp
       * @return void
       */
         public function mark($name)
            {
               if(!empty($this->_['marks'][$name]))
                  {
                     throw new ArchetypeException("Can not re-declare benchmark point '${name}'");
                  }

               $this->_['marks'][$name]=microtime(true);
            }

      /**
       * Measure between two points in time and return a float
       * @access public
       * @param string $one One of the two times to measure between
       * @param string $two Optional - if not specified, the current time will be substituted
       * @return mixed False on failure, float on success
       * @todo Add a parameter that makes the method automatically record the measurement for the automator to report
       */
         public function measure($one,$two=false)
            {
               $r=false;

               if(!empty($this->_['marks'][$one]))
                  {
                     $one=&$this->_['marks'][$one];

                  // Default $two if it's not specified
                     if(!empty($two)&&!empty($this->_['marks'][$two]))
                        {
                           $two=&$this->_['marks'][$two];
                        }
                     else
                        {
                           $two=microtime(true);
                        }

                  // Always deduct the smaller from the larger so you can feed them in random orders and achieve the same result
                     if($one>$two)
                        {
                           $r=$one-$two;
                        }
                     else
                        {
                           $r=$two-$one;
                        }
                  }

               return $r;
            }
      }
?>
