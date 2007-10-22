<?=$this->system->view('common/header')?>
<div id="content">
   <h1>Edit Profile</h1>
<?php
   if(!empty($message))
      {
         echo("<h2>".$message."</h2>");
      }
?>
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
            <input type="submit" value="Save" />
            <input type="reset" value="Reset" />
         </li>
      </ul>
   </form>
</div>
<?=$this->system->view('common/footer')?>
