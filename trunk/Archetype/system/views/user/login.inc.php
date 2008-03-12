<?=$this->system->view('common/header')?>
<div id="content">
   <h1>Login</h1>
<?php
   if(!empty($message))
      {
         echo("<h2>".$message."</h2>");
      }
?>
   <p class="center">If you have an account on this system, please login below.  If you need one, <a href="<?=$root?>user/register/">try registering</a>.</p>
   <form action="<?=$root?>user/login/" method="post">
      <ul class="form">
         <li>
            <span>E-mail</span>
            <input type="text" name="email" />
         </li>
         <li>
            <span>Password</span>
            <input type="password" name="password" />
         </li>
         <li>
            <input type="submit" value="Login" />
            <input type="reset" value="Clear" />
         </li>
      </ul>
   </form>
</div>
<?=$this->system->view('common/footer')?>
