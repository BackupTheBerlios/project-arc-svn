<?=$this->system->view('system/global/header')?>
<div id="content">
   <h1>Project Archetype</h1>
   <h2><?=$archetype_version?></h2>
   <p>
      Archetype is an advanced, strictly object-oriented MVC framework designed to greatly ease the process of building complex PHP5 applications.
      &nbsp; While this system is in a usable state right now, this is pre-beta software and as such is likely to contain bugs.&nbsp; As such, you are using this software at your own risk.
      &nbsp; Documentation is in the code itself, it's extremely well commented for the most part.
   </p>
   <p>
      Everything on this page is being dynamically generated, which is demonstrated by the following example:
      <span class="box">
         Host IP: <?=$server_ip?><br />
         Remote IP: <?=$_SERVER['REMOTE_ADDR']?><br />
         Webroot: <a href="<?=$webroot?>"><?=$webroot?></a><br />
         Time: <?=date('H:i:s F j, Y')?>
      </span>
   </p>
   <p>
      Models primarily serve as libraries to be used by Controllers and Views.  You can find models on the filesystem in the following pattern:
      <span>
         ./system/components/name/model.inc.php
      </span>
   </p>
   <p>
      Markup is stored in Views. &nbsp;The View for this page can be found on the filesystem here:
      <span>
         ./system/components/system/views/welcome.inc.php
      </span>
   </p>
   <p>
      Functionality is directed by Controllers.  &nbsp;The Controller for this page can be found on the filesystem here:
      <span>
         ./system/components/system/controller.inc.php
      </span>
   </p>
</div>
<?=$this->system->view('system/global/footer')?>
