
            <div>
               <span id="copyright" class="center">
<?
                  if($this->system->exists('model','benchmark'))
                     {
                     // This doesn't belong here but it's the best way to ensure an accurate build time report so we're bending the rules
                        $this->system->model('benchmark',$this);
                        $this->benchmark->mark('archetype_end');
                        echo('Compiled in '.sprintf('%.3f',$this->benchmark->measure('archetype_start','archetype_end')).' seconds<br />');
                     }
?>
                  Copyright &copy; 2007 Justin Krueger.  All rights reserved.
               </span>
            </div>
         </div>
      </div>
   </body>
</html>
