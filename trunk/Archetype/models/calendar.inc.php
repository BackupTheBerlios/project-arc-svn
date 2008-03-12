<?php if(!defined('A_VERSION')){die();}

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
 * @todo Finish the language component and integrate it into this for day names, month names, etc.
 */
   class A_calendar_model extends Archetype_model
      {
      /**
       * Gets all of the days of a Gregorian year and formats them
       */
         public function gregorian_year($timestamp=false,$format=A_CALENDAR_MONTHS)
            {
            }

      /**
       * Gets an array of the days in a month based on the Gregorian calendar
       */
         public function gregorian_month($timestamp=false,$zero_pad=false,$format=A_CALENDAR_PREPOST)
            {
            }

      /**
       * Gets an array of the hours in a given day based on the Gregorian calendar
       */
         public function gregorian_day($timestamp=false,$format=A_CALENDAR_24HOUR)
            {
            }
      }
?>
