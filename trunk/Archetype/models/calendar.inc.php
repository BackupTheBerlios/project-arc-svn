<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Calendar model
 *
 * @package Archetype
 * @subpackage calendar
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.4.13
 */

/**
 * A useful calendar class.  Supports only Gregorian for now, but if
 * someone makes additions and emails them to me I'll add them.
 */
   class A_calendar_model extends Archetype_model
      {
      /**
       * Gets all of the days of a Gregorian year and formats them
       */
         public function get_gregorian_year($timestamp=false)
            {
            }

      /**
       * Gets an array of the days in a month based on the Gregorian calendar
       */
         public function get_gregorian_month($timestamp=false,$pad=false)
            {
            }

      /**
       * Gets an array of the hours in a given day based on the Gregorian calendar
       */
         public function get_gregorian_day($timestamp=false)
            {
            }
      }
?>
