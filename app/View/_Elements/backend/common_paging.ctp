<style>
.numbers_tab{font-weight: bold;}
.current{line-height: 1.333em;margin: -0.333em -0.25em;background: -moz-linear-gradient(center top , white, #2BCEF3 5%, #057FDB) repeat scroll 0 0 transparent;border-color: #1EAFDC #1193D5 #035592;border-radius: 0.5em 0.5em 0.5em 0.5em;box-shadow: 0 0 0.25em rgba(0, 0, 0, 0.5);color: #fff;display: block;min-width: 1.083em;padding: 0.442em 0.6em !important;text-align: center;text-transform: uppercase;}

.next_prev_but{line-height: 1.333em;margin: -0.333em -0.25em;background: -moz-linear-gradient(center top , #F8F8F8, #E7E7E7) repeat scroll 0 0 transparent;border: 1px solid white;border-radius: 0.5em 0.5em 0.5em 0.5em;box-shadow: 0 0 0.25em rgba(0, 0, 0, 0.5);color: #333333;display: block;min-width: 1.083em;padding: 0.333em 0.5em;text-align: center;text-transform: uppercase;}

</style>
<div class="block-controls">
						
    <ul class="controls-buttons">
        <li> 
                <?php echo $this->Paginator->prev($this->Html->image("/images/icons/fugue/navigation-180.png").'Prev', array('escape'=>false,'class'=>"next_prev_but"));
                /* echo $this->Html->link($this->Html->image('/images/icons/fugue/navigation-180.png',array('title' =>'Previous')).' Prev','#',array('escape' => false));
              */?>
        </li>
	<?php
	echo $this->Paginator->numbers(array('before' => '','after' => '','separator' => '','tag'=>'li','class' => 'numbers_tab'));?>
	<!--<li><a href="#" title="Page 1"><b>1</b></a></li>
        <li><a href="#" title="Page 2" class="current"><b>2</b></a></li>
	-->
        <li>
                <?php 
                  /*echo $this->Html->link('Next'.$this->Html->image('/images/icons/fugue/navigation.png',array('title' =>'Next')),
                            '#',array('escape' => false));
                */
		  echo $this->Paginator->next('Next'.$this->Html->image("/images/icons/fugue/navigation.png"), array('escape'=>false,'class'=>"next_prev_but")); ?>
        </li> 
        <li class="sep"></li>
        <li>
	    <?php 
		echo $this->Html->link($this->Html->image('/images/icons/fugue/arrow-circle.png'),"javascript:void(0);", array('escape' => false,"class"=>"reload_url"));
	    ?>  
        </li>
    </ul>
            
</div>
<script>
    $(document).ready(function(){
	if($(".next_prev_but").find("a")){
	    $("a").parent("span.next_prev_but").removeClass("next_prev_but");
	}
	$(".reload_url").click(function(){
	    window.location.reload();
	});
    });
</script>