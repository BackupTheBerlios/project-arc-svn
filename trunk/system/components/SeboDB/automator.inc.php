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
   class SeboDB_automator extends Archetype_automator
      {
      /**
       * Stores Archetype's database configuration file so our destructor knows what to look for
       * @access public
       * @var array
       */
         public $config=array();

      /**
       * Constructor.  Open our configuration, auto-generate (potentially) a bunch of data sources.
       * @access public
       * @return void
       */
         public function construct()
            {
               if($this->config=&$this->system->config('database'))
                  {
                     $this->system->model('SeboDB',&$this);

                     foreach($this->config as $index=>$value)
                        {
                           if(!empty($this->config[$index]['link']))
                              {
                                 $link_name=&$this->config[$index]['link'];
                                 $this->SeboDB->create($this->config[$index]['controller'],$this->SeboDB->$link_name->driver,$index);
                              }
                           else
                              {
                                 $this->SeboDB->create($this->config[$index]['controller'],$this->config[$index]['driver'],$index);
                                 $this->SeboDB->$index->open($this->config[$index]);
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
               if(!empty($this->config))
                  {
                     foreach($this->config as $index=>$value)
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
