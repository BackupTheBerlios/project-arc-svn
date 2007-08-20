<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * User controller
 *
 * @package Archetype
 * @subpackage user
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.4.1
 */

/**
 * User output class
 */
   class user_controller extends A_controller
      {
         public function construct()
            {
               $this->system->model('SeboDB',$this);
               $this->system->model('user',$this);
            }

         public function index()
            {
               $view='user/login';$input=array();

               if($input['user']=&$this->user->active())
                  {
                     $view='user/index';
                  }

               echo($this->system->view($view,$input));
            }

         public function profile($id=false)
            {
            }
         
         public function settings()
            {
            }

         public function validate()
            {
            }

         public function register()
            {
               $view='user/register';$input=array();

               if($this->user->active())
                  {
                     $view='system/message';
                     $input['title']="Can't Register";
                     $input['message']="You're already registered and logged in.";
                  }
               elseif(!empty($_POST))
                  {
                  }

               echo($this->system->view($view,$input));
            }

         public function login()
            {
               $view='user/login';$input=array();

               if($this->user->active())
                  {
                     $view='system/message';
                     $input['title']="Can't Login";
                     $input['message']="You're already logged in.";
                  }
               elseif(!empty($_POST))
                  {
                     if(!empty($_POST['email'])&&!empty($_POST['password']))
                        {
                           if($this->user->open($_POST['email'],$this->user->hash($_POST['password']),true))
                              {
                                 $this->user->stamp();

                                 $view='system/message';
                                 $input['title']='Success';
                                 $input['message']='You have successfully logged into the system.';
                              }
                           else
                              {
                                 $input['message']='Invalid login credentials';
                              }
                        }
                     else
                        {
                           $input['message']="Please fill out the form properly";
                        }
                  }

               echo($this->system->view($view,$input));
            }

         public function logout($session_id=false)
            {
               $view='system/message';$input=array();

               if($this->user->active())
                  {
                     if($session_id===session_id())
                        {
                           $this->user->unstamp();
                           $input['title']='Success';
                           $input['message']='You have been successfully logged out of the system.';
                        }
                     else
                        {
                           $view='system/error';
                           $input['message']='Invalid session id given, could not logout.';
                        }
                  }
               else
                  {
                     $view='user/login';
                     $input['message']='You must login';
                  }

               echo($this->system->view($view,$input));
            }
      }
?>
