<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * Pixlib model
 *
 * @package Archetype
 * @subpackage pixlib
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.5.7
 * @todo Add some kind of logic that throws exceptions when you try to deal with images not supported by your gd
 * @todo Finish...
 */

   define('PIXLIB_QUALITY_LOW',35);
   define('PIXLIB_QUALITY_MEDIUM',55);
   define('PIXLIB_QUALITY_HIGH',100);

   define('PIXLIB_SCALE_PROPORTION',0);
   define('PIXLIB_SCALE_DIMENSION',1);

/**
 * Pixlib is a tool for manipulating images in various ways as supported by GD
 */
   class pixlib_model extends Archetype_model
      {
      /**
       * Assigned GD information in the constructor
       * @access public
       * @var array
       */
         public $gd_info=array();

      /**
       * Images loaded into pixlib
       * @access public
       * @var array
       */
         public $images=array(0=>false); // 0 is a reference to the current image

      /**
       * Load GD's information into $this->gd_info if PHP has the extension or throw an exception if it doesn't
       * @access protected
       * @return void
       */
         protected function construct()
            {
               if(!function_exists('gd_info'))
                  {
                     throw new ArchetypeComponentException("GD needs to be installed to use pixlib");
                  }
               else
                  {
                     $this->gd_info=gd_info();
                  }
            }

      /**
       * Generates a string identifier for images that have none specified
       * @access private
       * @return string Returns an 8-character string
       */
         private function _create_id()
            {
               $r=substr(sha1(rand().microtime(true)),0,8);

            // Make sure it's unique
               if(!empty($this->images[$r]))
                  {
                     $r=$this->_create_id();
                  }

               return $r;
            }

      /**
       * Returns a reference of either the image element specified by id or the last image if no id is specified - or alternatively throws errors
       * @access private
       * @return array Returns a reference to an image's element in the images array
       */
         private function &_get_image($id=false)
            {
               if(empty($id))
                  {
                     if(!empty($this->images[0]))
                        {
                           $r=&$this->images[0];
                        }
                     else
                        {
                           throw new ArchetypeComponentException("No image set as current and no id specified");
                        }
                  }
               else
                  {
                     if(!empty($this->images[$id]))
                        {
                           $r=&$this->images[$id];
                        }
                     else
                        {
                           throw new ArchetypeComponentException("Invalid id: '${id}'");
                        }
                  }

               return $r;
            }

      /**
       * Hides the logic required to open different image types
       * @access private
       * @return string Returns an 8-character string
       */
         private function &_imagecreatefrom($file)
            {
               $r=false;
               return $r;
            }

      /**
       * Opens an image in pixlib for manipulation
       * @access public
       * @param string $file Location of the file on the filesystem
       * @param string $id Optional identifier used for the image inside pixlib (you only need to use it if you're editing multiple images in a non-linear order)
       * @return void
       * @todo Add the ability to open images through http
       */
         public function open($file,$id=false,$make_current=false)
            {
               $r=false;

               if(is_readable($file))
                  {
                  // Assign a unique id if one wasn't specified
                     if(empty($id))
                        {
                           $make_current=true;
                           $id=$this->_create_id();
                        }

                  // Flip out if we're trying to open over another image
                     if(!empty($this->images[$id]))
                        {
                           throw new ArchetypeComponentException("You must close '${id}' before trying to open it again");
                        }

                  // Open the image and store stuff on it
                     if($image=&$this->_imagecreatefrom($file))
                        {
                           $info=getimagesize($file);
                           $this->images[$id]=array('id'=>$id,
                                                   'resource'=>&$image,
                                                   'file'=>$file); // add a bunch of useful information: mime, dimensions, etc. TODO
                        }

                  // Make current if told to or autogenerated $id
                     if($make_current)
                        {
                           $this->images[0]=&$this->images[$id];
                        }
                  }
               else
                  {
                     throw new ArchetypeComponentException("Could not read file: ${file}");
                  }

               return $r;
            }

      /**
       * Creates a new image in pixlib for manipulation
       * @access public
       * @param integer $width Width, in pixels, of the image to be created
       * @param integer $height Height, in pixels, of the image to be created
       * @param string $id Optional identifier used for the image inside pixlib (you only need to use it if you're editing multiple images in a non-linear order)
       * @return void
       */
         public function create($width,$height,$id=false)
            {
            }

      /**
       * Scales an image opened in pixlib
       * @access public
       * @param array $dimensions Associative array specifying two of the following: max-width, max-height, width, height
       * @param integer $quality Either false, PIXLIB_QUALITY_LOW, PIXLIB_QUALITY_MEDIUM or PIXLIB_QUALITY_HIGH
       * @param integer $type Either false, PIXLIB_SCALE_PROPORTION or PIXLIB_SCALE_DIMENSION
       * @param string $id Optional identifier used for the image inside pixlib (you only need to use it if you're editing multiple images in a non-linear order)
       * @return void
       */
         public function scale($dimensions,$quality=false,$type=false,$id=false)
            {
               $r=false;

               $id=&$this->_get_image(&$id);

               if(empty($quality))
                  {
                     $quality=PIXLIB_QUALITY_MEDIUM;
                  }
               if(empty($type))
                  {
                     $type=PIXLIB_SCALE_PROPORTION;
                  }

               $possible_dimensions=array('width','height','max-width','max-height');

               if(is_array($dimensions))
                  {
                     foreach($dimensions as $key=>$value)
                        {
                           if(!in_array($key,$possible_dimensions))
                              {
                                 throw new ArchetypeComponentException("Unknown dimension '${key}'; must be one of the following: '".implode($possible_dimensions,"', '")."'");
                              }
                        }
                  }

               return $r;
            }

      /**
       * 
       * @access public
       * @return void
       */
         public function overlay()
            {
               $r=false;
               return $r;
            }

      /**
       * 
       * @access public
       * @return void
       */
         public function shape()
            {
               $r=false;
               return $r;
            }

      /**
       * 
       * @access public
       * @return void
       */
         public function filter()
            {
               $r=false;
               return $r;
            }

      /**
       * 
       * @access public
       * @return void
       */
         public function save($file,$quality=PIXLIB_QUALITY_HIGH)
            {
               $r=false;
               return $r;
            }

      /**
       * Free the resources being used by an image
       * @access public
       * @param string $id Optional identifier used for the image inside pixlib (you only need to use it if you're editing multiple images in a non-linear order)
       * @return void
       */
         public function close($id=false)
            {
               $r=false;

               if(empty($id))
                  {
                     if(!empty($this->images[0]))
                        {
                           $id=$this->images[0]['id'];
                        }
                     else
                        {
                           throw new ArchetypeComponentException("No image set as current and no id specified");
                        }
                  }

               if(!empty($this->images[$id]))
                  {
                     if($this->images[0]['id']===$id)
                        {
                           unset($this->images[0]);
                        }

                     unset($this->images[$id]);
                  }
               else
                  {
                     throw new ArchetypeComponentException("Invalid id: '${id}'");
                  }

               return $r;
            }
      }
?>
