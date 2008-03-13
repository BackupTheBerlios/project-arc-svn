<?=$this->system->view('common/header')?>
<div id="content">
   <h1>Project Archetype</h1>
   <h2><?=A_VERSION?></h2>
   <p>
      Congratulations!  It looks like you were able to install the system.  Easy, wasn't it?
   </p>
   <p>
      Everything on this page is being dynamically generated, which is demonstrated by the following few examples:
      <span>
         Server IP: <?=$_SERVER['SERVER_ADDR']?><br />
         Client IP: <?=$_SERVER['REMOTE_ADDR']?><br />
         Webroot: <a href="<?=$root?>"><?=$root?></a><br />
         Server Time: <?=date('H:i:s F j, Y')?>
      </span>
   </p>
   <p>
      While this page itself has no purpose for a model, the system model is the foundation of the system and is helping power everything.  You can find the system model on the filesystem here:
      <span>
         <?=A_MODELS_LOCATION?>system.inc.php
      </span>
   </p>
   <p>
      You can find the view being used to generate output on the filesystem here:
      <span>
         <?=A_VIEWS_LOCATION?>welcome.inc.php
      </span>
   </p>
   <p>
      You can find the controller coordinating this page's output on the filesystem here:
      <span>
         <?=A_CONTROLLERS_LOCATION?>system.inc.php
      </span>
   </p>
</div>
<?=$this->system->view('common/footer')?>
