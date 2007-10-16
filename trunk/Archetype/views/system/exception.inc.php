<?=$this->system->view('global/header')?>
<div id="exception">
   <h1 class="error">Caught Fatal Exception</h1>
   <p><?=str_replace("\n","<br />",$message)?></p>
</div>
<?=$this->system->view('global/footer')?>
