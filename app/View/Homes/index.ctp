<?php
if($cms_data['CmsPage']['id'] == "1"){
?>
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
    <?php
    if(!empty($cms_data)){
        echo $cms_data['CmsPage']['content'];
    }
    ?>
 </div>
<!-- content End -->
<?php
}else{
?>

<!--page-row start-->
<section class="page-row content-wrapper">
        <section class="lt-cr"></section>
        <section class="rt-cr"></section>
        <section class="row-mid">
                <div class="back">
                       <!-- <a href="javascript" class="back-arrow">tilbage</a>-->
                    <?php echo $this->Html->link('tilbage',"javascript:history.back();", array("title"=>"tilbage","escape"=>false,"class"=>"back-arrow"));
                    ?>
                </div>
                <div class="head-block">
                        <h1>
                        <?php    if(!empty($cms_data)){
                            echo $cms_data['CmsPage']['title'];
                        }
                        ?>
                    </h1>
                </div>
        </section>
</section>
<!--page-row end-->

<!--content-widget start-->
<section class="content-widget">
        <!--left-widget start-->
        <section class="left-widget">
                <ul class="left-nav">

                        <li>
                        <?php echo $this->Html->link('PRISER / PRODUKTER',"javascript:void(0);", array("title"=>"PRISER / PRODUKTER","escape"=>false,"class"=>"active"));
                        ?>
                        </li>
                        
                </ul>

        </section>
        <!--left-widget end-->

        <!--right-widget start-->
        <section class="right-widget">
        <?php 
                if(!empty($cms_data)){
                    echo $cms_data['CmsPage']['content'];
                }
        ?>
        </section>
        <!--right-widget end-->
        <div class="clear"></div>
</section>
<!--content-widget end-->

<?php
}
?>
