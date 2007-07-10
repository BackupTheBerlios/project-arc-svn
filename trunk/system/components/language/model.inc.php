<?php if(!defined('ARCHETYPE_VERSION')){die();}

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

/**
 * Makes language abstraction easier and more organized
 */
   class language_model extends Archetype_model
      {
      /**
       * Loads and returns configuration files, optionally providing a requirement check on content
       * @access public
       * @param $config The configuration's name to load
       * @param $require Optionally checks the config for the keys provided in this array
       * @return mixed A reference to either the config specified or the key specified if true, false otherwise
       */
         public function &config($config,$require=false)
            {
               $r=false;

            // If the config isn't loaded, try to load it
               if(empty($this->_['config'][$config]))
                  {
                     if(is_readable($config_location=CONFIG_LOCATION.'/'.$config.'.config.inc.php'))
                        {
                           require($config_location);

                           if(!empty($$config))
                              {
                                 $this->_['config'][$config]=&$$config;

                                 $r=&$this->_['config'][$config];
                              }
                        }
                  }
            // Config is loaded, just return a reference
               else
                  {
                     $r=&$this->_['config'][$config];
                  }

            // Check if the config meets the requirements
               if(is_array($r)&&is_array($require))
                  {
                     $missing_keys=array();

                     foreach($require as $key)
                        {
                           if(empty($r[$key]))
                              {
                                 $missing_keys[]=$key;
                              }
                        }

                     if(!empty($missing_keys))
                        {
                           $plural='';
                           if(count($missing_keys)>1)
                              {
                                 $plural='s';
                              }

                           throw new ArchetypeSystemException("Configuration for '${config}' is missing required key${plural} '".implode("', '",$missing_keys)."'.");
                        }
                  }

               return $r;
            }
      }
?>
