<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                         S e b o  D B                           //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * SeboDB Automator
 *
 * @package Archetype
 * @subpackage SeboDB
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.9.10
 */

/**
 * Make connections automatically based on the database settings
 */
   class SeboDB_automator extends A_automator
      {
         public static $construct=900;
         public static $destruct=-900;
      /**
       * Constructor.  Open our configuration, auto-generate (potentially) a bunch of data sources.
       * @access public
       * @return void
       */
         public function construct()
            {
               if($this->system->settings('database',$this))
                  {
                     $this->system->model('SeboDB',$this);

                     foreach($this->settings['database'] as $index=>$value)
                        {
                           if(!empty($this->settings['SeboDB'][$index]['link']))
                              {
                                 $link_name=&$this->settings['SeboDB'][$index]['link'];
                                 $this->SeboDB->create($this->settings['database'][$index]['controller'],$this->SeboDB->$link_name->driver,$index);
                              }
                           else
                              {
                                 $this->SeboDB->create($this->settings['database'][$index]['controller'],$this->settings['database'][$index]['driver'],$index);
                                 $this->SeboDB->$index->open($this->settings['database'][$index]);
                              }
                        }
                  }
            }

      /**
       * Destructor.  Close all of our data sources in a friendly manner.
       * @access public
       * @return void
       */
         public function destruct()
            {
               if(!empty($this->settings['SeboDB']))
                  {
                     foreach($this->settings['SeboDB'] as $index=>$value)
                        {
                           if(!empty($this->SeboDB->$index->connection))
                              {
                                 $this->SeboDB->$index->close();
                              }

                           $this->SeboDB->destroy($index);
                        }
                  }
            }
      }
?>
