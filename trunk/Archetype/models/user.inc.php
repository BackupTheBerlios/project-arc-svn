<?php if(!defined('ARCHETYPE_VERSION')){die();}

   ////////////////////////////////////////////////////////////////////
   //                P R O J E C T A R C H E T Y P E                 //
   //                 www.fuzzywoodlandcreature.net                  //
   ////////////////////////////////////////////////////////////////////

/**
 * User model
 *
 * @package Archetype
 * @subpackage user
 * @author Justin Krueger <fuzzywoodlandcreature@gmail.com>
 * @copyright © 2007 Justin Krueger.  All rights reserved.
 * @license http://www.opensource.org/licenses/mit-license.html MIT
 * @link http://fuzzywoodlandcreature.net/archetype
 * @version 2007.9.10
 */

   define('E_USER_EXISTS',10);

/**
 * Simple interaction with the user layer
 */
   class user_model extends A_model
      {
      /**
       * Store opened user accounts
       * @access public
       * @var array
       */
         public $users=array(0=>false);

      /**
       * Construct - open settings and models
       * @access public
       * @return void
       */
         public function construct()
            {
               $this->system->settings('user',$this,array('hash_salt'));

               $this->system->model('SeboDB',$this);
               $this->system->model('http',$this);
            }

      /**
       * Creates user accounts
       * @access public
       * @param string $email User email to be associated with the created account
       * @param string $password Password to be associated with created account
       * @param array $profile Optionally describe profile contents while creating account
       * @return mixed True on success, on failure it can return E_USER_EXISTS if the specified email is in use or false if failure
       * @todo make it use $this->open() instead of its own SQL to figure out if the user exists or not
       */
         public function create($email,$password,$profile=array())
            {
               $r=false;

               $d=&$this->SeboDB->default;

               $d->query('SELECT null FROM ^users WHERE email="'.$d->escape($email).'"');
               if($d->results()===0)
                  {
                     $profile['email']=&$email;
                     $profile['password_hash']=$this->hash($password);

                     $fields=$values=array();

                     foreach($profile as $key=>$value)
                        {
                           $fields[]=$d->escape($key);
                           $values[]=$d->escape($value);
                        }

                     if($d->query('INSERT INTO ^users ('.implode(',',$fields).') VALUES ("'.implode('","',$values).'")'))
                        {
                           $r=true;
                        }
                  }
               else
                  {
                     $r=E_USER_EXISTS;
                  }

               return $r;
            }

      /**
       * Modifies user accounts
       * @access public
       * @param mixed $account String email or int id
       * @param string $fields Associative array (column=>value) of fields to modify in the user table
       * @return bool True on success false on failure
       */
         public function modify($account,$fields=array())
            {
               $r=false;

               if($user=&$this->open($account)&&!empty($fields))
                  {
                     $d=&$this->SeboDB->default;

                     $set=array();
                     foreach($fields as $key=>$value)
                        {
                           $set[]=$d->escape($key).'="'.$d->escape($value).'"';
                        }

                     if($d->query('UPDATE ^users SET '.implode(',',$set).' WHERE id="'.(int)$user['id'].'"'))
                        {
                           foreach($fields as $key=>$value)
                              {
                                 $user[$key]=$value;
                              }

                        // Re-stamp if they modified critical account information
                           if(!empty($fields['email'])||!empty($fields['password_hash']))
                              {
                                 $this->restamp();
                              }

                           $r=true;
                        }
                  }

               return $r;
            }

      /**
       * Deletes user accounts
       * @access public
       * @param string $email Email associated with the account to delete
       * @return bool True on success false on failure
       */
         public function delete($account)
            {
               $r=false;

               $d=&$this->SeboDB->default;

               if($user=&$this->open($account))
                  {
                     $d->query('DELETE FROM ^users WHERE id="'.(int)$id.'"');

                     $r=true;
                  }

               return $r;
            }

      /**
       * Opens user accounts and stores them in $this->users
       * @access public
       * @param mixed $account String email or int user id
       * @param string $password_hash Optional password hash stored in the account
       * @param bool $current Whether or not to link the user account to $this->users[0], which sets it as the current user
       * @return mixed An array containing the user account on success false on failure
       */
         public function &open($account,$password_hash=false,$current=false)
            {
               $r=false;

               if(!empty($this->users[$account]))
                  {
                     $r=&$this->users[$account];
                  }
               else
                  {
                     $d=&$this->SeboDB->default;

                     $password_clause='';
                     if(!empty($password_hash))
                        {
                           $password_clause='AND password_hash="'.$d->escape($password_hash).'"';
                        }

                     if(is_string($account))
                        {
                           $field='email';
                           $value=$account;
                        }
                     else
                        {
                           $field='id';
                           $value=(int)$account;
                        }

                     $d->query('SELECT * FROM ^users WHERE '.$field.'="'.$d->escape($account).'" '.$password_clause.' LIMIT 1');
                     if($d->results())
                        {
                           $r=$d->fetch();

                           $this->users[$r['id']]=&$r;
                           $this->users[$r['email']]=&$r;

                           if($current)
                              {
                                 $this->users[0]=&$r;
                              }
                        }
                  }

               return $r;
            }

      /**
       * Stamps cookies with user information
       * @access public
       * @return bool True on success false on failure
       */
         public function stamp()
            {
               $r=false;

               if(!empty($this->users[0]))
                  {
                     $this->http->cookie('email',$this->users[0]['email']);
                     $this->http->cookie('password_hash',$this->users[0]['password_hash']);

                     $r=true;
                  }

               return $r;
            }

      /**
       * Stamps session with user information
       * @access public
       * @return bool True on success false on failure
       */
         public function halfstamp()
            {
               $r=false;

               if(!empty($this->users[0]))
                  {
                     $_SESSION['email']=$this->users[0]['email'];
                     $_SESSION['password_hash']=$this->users[0]['password_hash'];

                     $r=true;
                  }

               return $r;
            }

      /**
       * Re-Stamps the session or cookie information storage, depending on which is being used
       * @access public
       * @return bool True on success false on failure
       */
         public function restamp()
            {
               $r=false;

               if(!empty($_SESSION['email'])&&!empty($_SESSION['password_hash']))
                  {
                     $r=$this->halfstamp();
                  }
               else
                  {
                     $r=$this->stamp();
                  }

               return $r;
            }

      /**
       * Unstamps session and cookies that are storing user information
       * @access public
       * @return bool Always returns true
       */
         public function unstamp()
            {
               $r=true;

               unset($_SESSION['email']);
               unset($_SESSION['password_hash']);

               $this->http->cookie('email','');
               $this->http->cookie('password_hash','');

               $this->users[0]=false;

               return $r;
            }

      /**
       * Used to hash passwords for storage security
       * @access public
       * @return string Always returns hashed string
       */
         public function hash($string)
            {
               $s=&$this->settings['user']['hash_salt'];
               return sha1($s.md5($string.sha1($s)));
            }

      /**
       * Checks if a user is set as active
       * @access public
       * @return mixed Returns a reference to the user if they're active, false otherwise
       */
         public function &active()
            {
               $r=false;

               if(!empty($this->users[0]))
                  {
                     $r=&$this->users[0];
                  }

               return $r;
            }
      }
?>
