<!-- banner Starts -->
<section id="banner">
    <p>
        <?php echo $this->Html->image("banner-image.png",array("alt"=>"Hallo.dk"));?>
    </p>
    <div class="banner-btn">
    <?php echo $this->Html->link($this->Html->image("start-btn.png",array("alt"=>"Start")),"#1",array("escape"=>false)); ?>
    </div>
</section>
<!-- banner end -->
<div class="clients">
    <?php echo $this->Html->link($this->Html->image("clients.png",array("alt"=>"Hallo.dk")),"#",array("escape"=>false)); ?>
</div>
<!-- content Starts -->
<div class="content">
    <ul class="article">
        <li>
                <h4>ABOUT HALLO.DK</h4>
                <p>
                    <?php echo $this->Html->image("about.png",array("alt"=>"ABOUT HALLO.DK"));?>
                </p>
                <div class="article-text"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris consectetur mattis sem. Praesent scelerisque nibh et nisl molestie sit amet ultrices ante egestas lorem ipsum dolor. </div>
                <?php echo $this->Html->link("Læs mere","#",array("escape"=>false,"class"=>"link")); ?>
        </li>
        <li>
                <h4>COMPANIES &amp; PRICELISTS</h4>
                <p>
                    <?php echo $this->Html->image("company.png",array("alt"=>"COMPANIES &amp; PRICELISTS"));?>
                </p>
                <div class="article-text"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris consectetur mattis sem. Praesent scelerisque nibh et nisl molestie sit amet ultrices ante egestas lorem ipsum dolor. </div>
                <?php echo $this->Html->link("Læs mere","#",array("escape"=>false,"class"=>"link")); ?>
        </li>
        <li>
                <h4>FAQ</h4>
                <p>
                <?php echo $this->Html->image("faq.png",array("alt"=>"FAQ"));?>
                </p>
                <div class="article-text"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris consectetur mattis sem. Praesent scelerisque nibh et nisl molestie sit amet ultrices ante egestas lorem ipsum dolor. </div>
                <?php echo $this->Html->link("Læs mere","#",array("escape"=>false,"class"=>"link")); ?>        
        </li>
        <li style=" margin-right:0px;">
                <h4>INSTALL YOUR TOP-UP</h4>
                <p>
                    <?php echo $this->Html->image("install.png",array("alt"=>"INSTALL"));?>
                </p>
                <div class="article-text"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris consectetur mattis sem. Praesent scelerisque nibh et nisl molestie sit amet ultrices ante egestas lorem ipsum dolor. </div>
                <?php echo $this->Html->link("Læs mere","#",array("escape"=>false,"class"=>"link")); ?>
        </li>
    </ul>
</div>
<!-- content End -->