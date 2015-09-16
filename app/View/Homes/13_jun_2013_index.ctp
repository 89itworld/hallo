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
        <?php
            if(!empty($cms_data)){
                foreach($cms_data as $k=>$v){ ?>
                   <li> <h4><?php echo $v['CmsPage']['title']; ?></h4>
                   <?php  /*
                    <p>
                        <?php
                        if(file_exists("img/cms_photos/".$v['CmsPage']['image'])){
                            echo $this->Html->image("cms_photos/".$v['CmsPage']['image'],array("alt"=>$v['CmsPage']['title']));
                        }
                        ?>
                    </p>
                    <div class="article-text">
                    */ ?>
                        <?php echo $v['CmsPage']['content']; ?>
                    <!--</div>-->
                    <?php //echo $this->Html->link("Læs mere","#",array("escape"=>false,"class"=>"link")); ?>
                    <li>
        <?php   }
            }
        ?>
        
    </ul>
    
</div>
<!-- content End -->