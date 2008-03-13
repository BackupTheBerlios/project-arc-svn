<?=$this->system->view('common/header')?>
<div id="content">
   <h1>Register</h1>
<?php
   if(!empty($message))
      {
         echo("<h2>".$message."</h2>");
      }
?>
   <p class="center">If you already have an account on this system, please <a href="<?=$root?>user/login/">login here</a>.</p>
   <form action="<?=$self?>" method="post">
      <ul class="form">
         <li>
            <span>E-mail *</span>
            <input type="text" name="email" value="<?=$values['email']?>" />
         </li>
         <li>
            <span>Password *</span>
            <input type="password" name="password" />
         </li>
      </ul>
      <ul class="form">
         <li>
            <span>First Name</span>
            <input type="text" name="first_name" value="<?=$values['first_name']?>" />
         </li>
         <li>
            <span>Last Name</span>
            <input type="text" name="last_name" value="<?=$values['last_name']?>" />
         </li>
         <li>
            <input type="submit" value="Register" />
            <input type="reset" value="Reset" />
         </li>
      </ul>
   </form>
</div>
<?=$this->system->view('common/footer')?>
