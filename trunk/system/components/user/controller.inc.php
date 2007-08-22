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
 * @version 2007.8.20
 */

/**
 * User output class
 */
   class user_controller extends A_controller
      {
      /**
       * Load dependencies
       * @access public
       * @return void
       */
         public function construct()
            {
               $this->system->model('SeboDB',$this);
               $this->system->model('user',$this);

               $this->system->config('user',$this);
            }

      /**
       * User home page
       * @access public
       * @param integer $id Optioanlly if an id is specified, go into view mode and view that id
       * @return void
       */
         public function index()
            {
               $view='user/login';$input=array();

               if($input['user']=&$this->user->active())
                  {
                     $view='user/index';
                  }

               echo($this->system->view($view,$input));
            }

      /**
       * Views and edits profiles
       * @access public
       * @param integer $id Optioanlly if an id is specified, go into view mode and view that id
       * @return void
       */
         public function profile($id=false)
            {
               $view='user/login';$input=array();
               echo($this->system->view($view,$input));
            }
         
      /**
       * Registers user accounts
       * @access public
       * @param string $hash First 8 characters of the user's password_hash
       * @return void
       */
         public function validate($hash=false)
            {
            }

      /**
       * Registers user accounts
       * @access public
       * @return void
       */
         public function register()
            {
               $view='user/register';$input=array();

               if(!$this->config['user']['registration_enabled'])
                  {
                     $view='system/message';
                     $input['title']="Alert!";
                     $input['message']="Registration has been disabled";
                  }
               else
                  {
                  // Allows a degree of simple configurability
                     $require=array('email','password');
                     $accept=array('email','password','first_name','last_name');
      
                  // For memory forms, default the values to nothing
                     foreach($accept as $field)
                        {
                           $input['values'][$field]='';
                        }
      
                     if($this->user->active())
                        {
                           $view='system/message';
                           $input['title']="Can't Register";
                           $input['message']="You're already registered and logged in.";
                        }
                     elseif(!empty($_POST))
                        {
                           foreach($_POST as $key=>$value)
                              {
                              // Trim $_POST to only those things allowed in $accept (get important data out of $_POST before here)
                                 if(!in_array($key,$accept))
                                    {
                                       unset($_POST[$key]);
                                    }
                              // Put everything else in $input['values'] so the forms can remember their value
                                 else
                                    {
                                       $input['values'][$key]=$value;
                                    }
                              }
      
                        // Make sure we have our required fields
                           $missing=array();
                           foreach($require as $field)
                              {
                                 if(empty($_POST[$field]))
                                    {
                                       $missing[]=$field;
                                       $halt=true;
                                    }
                              }
      
                        // Spit out missing fields if there are any
                           if(!empty($halt))
                              {
                                 $s='';
                                 if(count($missing)>1)
                                    {
                                       $s='s';
                                    }
      
                                 $input['message']="Please fill required field${s}: '".implode("', '",$missing)."'";
                              }
                        // Or finish up by creating the user
                           else
                              {
                                 $email=$_POST['email'];
                                 unset($_POST['email']);

                                 $password=$_POST['password'];
                                 unset($_POST['password']);

                                 $status=$this->user->create($email,$password,$_POST);

                                 if($status&&!is_int($status))
                                    {
                                       $view='system/message';
                                       $input['title']='Congratulations';
                                       $input['message']='Successfully created new account';
                                    }
                                 elseif($status===E_USER_EXISTS)
                                    {
                                       $input['message']='Please pick another E-mail, this one is in use';
                                    }
                                 else
                                    {
                                       $input['message']='An unknown error has occurred';
                                    }
                              }
                        }
                  }


               echo($this->system->view($view,$input));
            }

      /**
       * Logs users into the system
       * @access public
       * @return void
       */
         public function login()
            {
               $view='user/login';$input=array();

               if(!$this->config['user']['login_enabled'])
                  {
                     $view='system/message';
                     $input['title']="Alert!";
                     $input['message']="Login has been disabled";
                  }
               elseif($this->user->active())
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
                                 $input['title']='Congratulations';
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

      /**
       * Logs a user out of the user system
       * @access public
       * @param string $session_id Requires the link to have the session ID so only the system can log users out of the system
       * @return void
       */
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
