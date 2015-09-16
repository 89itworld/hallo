<header id="header">
    <section class="logo">
        <?php
        echo $this->Html->link($this->Html->image("hallo.png",array("alt"=>"Hallo.dk","width"=>"110","height"=>"70")),array('controller'=>'homes', 'action' => 'index'), array("escape"=>false,"title"=>"Hallo.dk")); ?>
    </section>
    
    <nav class="nav">
	<?php
	if(!empty($page_list_data)){
	    foreach($page_list_data as $k1=>$v1 ){
		echo $this->Html->link($v1.$this->Html->image("nav.png",array("class"=>"nav-arrow")),array('controller'=>'homes', 'action' => 'index',str_replace(" ","",$k1)), array("title"=>$v1,"class"=>(!empty($this->params['pass']) && ($this->params['pass']['0'] == $k1))?"active":(empty($this->params['pass']) && ($k1 == '1'))?"active":"","escape"=>false));  
	    }
	}
	?>
        <?php
   	echo $this->Html->link('LOGIN'.$this->Html->image("nav.png",array("class"=>"nav-arrow")).' '.$this->Html->image("login.png",array("alt"=>"login")),array('controller'=>'users', 'action' => 'login','admin'=>true), array("title"=>"LOGIN","escape"=>false,"class"=>($this->params['action'] == 'login')?"active":""));
	?>
    </nav>
</header>