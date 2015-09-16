<header id="header">
    <section class="logo">
        <?php
        echo $this->Html->link($this->Html->image("hallo.png",array("alt"=>"Hallo.dk","width"=>"110","height"=>"70")),"javascript:void(0);",array("escape"=>false,"title"=>"Hallo.dk")); ?>
    </section>
    <?php /*
    <nav class="nav">
        
            echo $this->Html->link("HOME","#",array("escape"=>false,"title"=>"HOME","class"=>"active"));
            echo $this->Html->link("ABOUT ","#",array("escape"=>false,"title"=>"ABOUT"));
            echo $this->Html->link("FAQ ","#",array("escape"=>false,"title"=>"FAQ"));
            echo $this->Html->link("PRICELISTS","#",array("escape"=>false,"title"=>"PRICELISTS"));
            echo $this->Html->link("INSTALATION ","#",array("escape"=>false,"title"=>"INSTALATION"));
            echo $this->Html->link("CONTACT ","#",array("escape"=>false,"title"=>"CONTACT"));
            echo $this->Html->link("LOGIN ".$this->Html->image("login.png",array("alt"=>"login")),"#",array("escape"=>false,"title"=>"LOGIN"));
        
    </nav>
    */
    ?>
    <nav class="nav">
        <a href="javascript:void(0);" title="HOME" class="active">HOME</a>
        <a href="javascript:void(0);" title="ABOUT">ABOUT </a>
        <a href="javascript:void(0);" title="FAQ">FAQ </a>
        <a href="javascript:void(0);" title="PRICELISTS">PRICELISTS</a>
        <a href="javascript:void(0);" title="INSTALATION">INSTALATION </a>
        <a href="javascript:void(0);" title="CONTACT">CONTACT </a>
        <a href="javascript:void(0);" title="LOGIN">LOGIN <img src="img/login.png" alt="login"></a>
    </nav>
   
    
</header>