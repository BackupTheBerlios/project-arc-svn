<?=$this->system->view('system/global/header')?>
<div id="content">
   <h1>User Home</h1>
   <p>
      <a href="<?=$webroot?>user/logout/<?=session_id()?>/">Logout</a><br />
      <a href="<?=$webroot?>user/profile/<?=$user['id']?>/">View Profile</a><br />
      <a href="<?=$webroot?>user/profile/">Edit Profile</a><br />
   </p>
</div>
<?=$this->system->view('system/global/footer')?>