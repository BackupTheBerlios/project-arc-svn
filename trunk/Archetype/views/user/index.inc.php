<?=$this->system->view('common/header')?>
<div id="content">
   <h1>User Home</h1>
   <p>
      <a href="<?=$root?>user/logout/<?=session_id()?>/">Logout</a><br />
      <a href="<?=$root?>user/profile/<?=$user['id']?>/">View Profile</a><br />
      <a href="<?=$root?>user/profile/">Edit Profile</a><br />
   </p>
</div>
<?=$this->system->view('common/footer')?>
