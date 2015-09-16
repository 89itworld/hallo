<!--Left section start-->
<section class="grid_4" style="min-width:30%;">
	<div class="block-border"><div class="block-content">
		<h1><?php echo LBL_CATEGOREY_LISTING;?></h1>
		<ul class="collapsible-list with-bg">
                    <?php if(!empty($category_data)){
                    foreach($category_data as $k=>$v){	
                    ?>
                    <li class="closed">
                            <b class="toggle"></b>
                            <span><?php echo ucfirst($v['Category']['title']);?></span>
                            <ul class="with-icon icon-user">
                            <?php if(!empty($v['Product'])){foreach($v['Product'] as $k1=>$v1){?>
                                    <li><?php echo $this->Html->link(ucfirst($v1['title']),"javascript:void(0);",array("alt"=>$v1['id'],"escape"=>false,"class"=>"product_detail_csv"));?></a></li>
                            <?php }
                            }else{
                                    echo "<li>".NO_PRODUCT_FOUND."</li>";
                            }
                            ?>
                            </ul>
                    </li>
                    <?php }} ?>
		</ul>
		
	</div></div>
</section>
<!--Left section end-->
