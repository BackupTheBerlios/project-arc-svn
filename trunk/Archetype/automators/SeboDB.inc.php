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
 * @version 2007.4.1
 */

   $construct=900;
   $destruct=-900;

/**
 * Make connections automatically based on the configuration file
 */
   class SeboDB_automator extends A_automator
      {
      /**
       * Constructor.  Open our configuration, auto-generate (potentially) a bunch of data sources.
       * @access public
       * @return void
       */
         public function construct()
            {
               if($this->system->config('database',$this))
                  {
                     $this->system->model('SeboDB',$this);

                     foreach($this->config['database'] as $index=>$value)
                        {
                           if(!empty($this->config['SeboDB'][$index]['link']))
                              {
                                 $link_name=&$this->config['SeboDB'][$index]['link'];
                                 $this->SeboDB->create($this->config['database'][$index]['controller'],$this->SeboDB->$link_name->driver,$index);
                              }
                           else
                              {
                                 $this->SeboDB->create($this->config['database'][$index]['controller'],$this->config['database'][$index]['driver'],$index);
                                 $this->SeboDB->$index->open($this->config['database'][$index]);
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
               if(!empty($this->config['SeboDB']))
                  {
                     foreach($this->config['SeboDB'] as $index=>$value)
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
