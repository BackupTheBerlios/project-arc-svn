<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Gallery model
 *
 * @package Archetype
 * @subpackage gallery
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.5.20
 */

/**
 * Tools for generating pixmap galleries from a directory hierarchy
 */
   class gallery_model extends Archetype_model
      {
      /**
       * Constructor loads required models and configurations
       * @access public
       * @return void
       */
         public function construct()
            {
            // We need pixlib
               $this->system->depend('pixlib','model');

            // Load pixlib
               $this->system->model('pixlib',$this);

            // Load our gallery config once in the constructor
               $this->system->config('gallery',$this,array('default'));
            }

      /**
       * Creates a thumbnail for a pixmap that doesn't have one and returns the path to it or just returns the path to an existing thumbnail
       * @access private
       * @param string $pixmap Location to a pixmap to thumbnail/reurn
       * @return string Path to thumbnail for pixmap provided as parameter
       */
         private function _thumbnail($pixmap,$config='default')
            {
               $r=$this->config['gallery'][$config]['thumbnail_location'].'/'.$pixmap;

               if(!is_readable($r))
                  {
                  // No need to check if it's a real image, pixlib will do all that
                     $this->pixlib->open($pixmap);
                     $this->pixlib->scale($this->config['gallery'][$config]['thumbnail_dimensions']);
                     $this->pixlib->save($r);
                     $this->pixlib->close();
                  }

               return $r;
            }

      /**
       * Generate an array of image locations based on the filesystem
       * @access public
       * @param string $directory Location to generate gallery from
       * @return array Array containing information on a gallery's contents
       */
         public function generate($directory,$config='default')
            {
               if(is_readable($directory))
                  {
                  // Scan the parameter
                     $contents=array_slice(scandir($directory),2);

                  // Setup a container to return
                     $r=array('directories'=>array(),'pixmaps'=>array());

                  // Fill the container
                     foreach($contents as &$value)
                        {
                           if(is_dir($value)&&is_readable($value))
                              {
                                 $r['directories'][]=&$value;
                              }
                           elseif(is_file($directory.'/'.$value)&&is_readable($directory.'/'.$value))
                              {
                                 preg_match('/^.*\.(.+)$/',$value,$match);

                                 if(in_array($match[1],$this->config['gallery'][$config]['filetypes']))
                                    {
                                       $r['pixmaps'][]=array('thumbnail'=>$this->_thumbnail($directory.'/'.$value,$config),'full'=>$directory.'/'.$value);
                                    }
                              }
                        }
                  }
               else
                  {
                     throw new ArchetypeException("Directory '${directory}' could not be read");
                  }

               return $r;
            }
      }
?>
