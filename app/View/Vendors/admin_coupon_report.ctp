<?php
echo $this->element("backend/datepicker");
$after_click_sort = array('asc'=>'<span class="sort-up"></span><span class="sort-down active"></span>','desc'=>'<span class="sort-up active"></span><span class="sort-down"></span>');
$before_click_sort = '<span class="sort-up"></span><span class="sort-down"></span>';
$default_sort = '<span class="sort-up active"></span><span class="sort-down"></span>';

$fields = array("product_code","batch_id","de_i","is_sold","from","to","sort","c_code","direction");
//$condition = isset($this->params['named']['page'])?$this->params['named']:$this->params['url'];
$condition = isset($this->params['named']['sort'])?$this->params['named']:$this->params['url'];;
$search_url = $this->Common->make_search_url($fields);
$this->Paginator->options(array('url'=>$search_url['urlArray']));
?>
<!--<article class="container_12">
    <section class="grid_8" style="width:100%;">-->
        <div class="block-border">
            <?php echo $this->element('backend/server_error');
                echo $this->Form->create('Coupon',array('url'=>array('controller'=>'vendors','action' => 'coupon_report', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),"type"=>"get",'id'=>'coupon_report', 'name' => 'coupon_report','class' => 'block-content form'));
		?>
                <fieldset class="">
		    <div class="columns">
			<p class="colx2-left">
			    <?php
				echo $this->Form->input("sort",array("type"=>"hidden",'value'=>isset($condition['sort'])?$condition['sort']:"created"));
				echo $this->Form->input("direction",array("type"=>"hidden",'value'=>isset($condition['direction'])?$condition['direction']:"desc"));
			    ?>
			    <div class="float-left gutter-right">
				<label for="stats-period">Coupon Id</label>
				<span class="input-type-text">
				    <?php echo $this->Form->input("c_code",array("type"=>"text",'value'=>isset($condition['c_code'])?$condition['c_code']:""));?>
				</span>
			    </div>
			    <div class="float-left gutter-right">
				<label for="stats-period">Dealer</label>
				<?php
				    
				    $selected_dealer = isset($condition['de_i'])?$condition['de_i']:""; 
				    echo $this->Form->input('de_i',array('type'=>'select','options'=>$dealer_data,'selected'=>$selected_dealer,"empty"=>"All","label"=>false,"div"=>false));
				    ?>
			    </div>
			    <div class="float-left gutter-right">
				<label for="stats-period">Product</label>
				<?php
				    
				    $selected_product = isset($condition['product_code'])?$condition['product_code']:""; 
				    echo $this->Form->input('product_code',array('type'=>'select','options'=>$product_data,'selected'=>$selected_product,"empty"=>"All products","label"=>false,"div"=>false,"escape"=>false,"class"=>"select_product"));
				    ?>
			    </div>
			    <div class="float-left gutter-right">
				<label for="stats-period">Batch</label>
				<?php
				    
				    $selected_batch = isset($condition['batch_id'])?$condition['batch_id']:""; 
				    echo $this->Form->input('batch_id',array('type'=>'select','options'=>$batch_data,'selected'=>$selected_batch,"empty"=>"All","label"=>false,"div"=>false));
				    ?>
			    </div>
			    
			    <div class="float-left gutter-right">
				<label for="stats-period">Sold</label>
				<?php
				    $status_sold = array(1=>"Yes",0=>"No");
				    $selected_sold = isset($condition['is_sold'])?$condition['is_sold']:""; 
				    echo $this->Form->input('is_sold',array('type'=>'select','options'=>$status_sold,'selected'=>$selected_sold,"empty"=>"All","label"=>false,"div"=>false));
				    ?>
			    </div>
			    
			    
			    <div class="float-left gutter-right">
				<label for="stats-period">From</label>
				<span class="input-type-text">
				    <?php echo $this->Form->input("from",array("type"=>"text","class"=>"datepicker_from",'value'=>isset($condition['from'])?$condition['from']:"",'readonly' => true));?>
				</span>
			    </div>
			    <div class="float-left gutter-right">
				<label for="stats-period">TO</label>
				<span class="input-type-text">
				    <?php echo $this->Form->input("to",array("class"=>"datepicker_to",'value'=>isset($condition['to'])?$condition['to']:"",'readonly' => true));?>
				</span>
			    </div>
			    
			    <div class="float-left gutter-right">
				<label for="stats-period">&nbsp;</label>
				<span class="">
				<?php
				echo $this->Form->button('Search',array("type"=>"submit",'div'=>FALSE,"tabindex"=>"5"));
			        echo $this->Form->button("Export To CSV",array("type"=>"submit","class"=>"red","style"=>'margin:0 4px;width:auto;','name'=>'export_to_csv','value'=>'csv'));
					    
				echo $this->Form->button("Export To PDF",array("type"=>"submit","class"=>"grey","style"=>'margin:0 4px;width:auto;','name'=>'export_to_pdf','value'=>'pdf'));
					    
				
				?>
				</span>
			    </div>
			</p>
		    </div>
		</fieldset>
            <?php echo $this->Form->end();?>
        </div>
    
    <!--</section>-->
     
    <div class="clear"></div>
<!--</article>-->

<section class="grid_13">
    <div class="block-border">
	<?php
	echo $this->element("backend/common_paging");
	echo $this->Form->create("Model",array("url"=>array("controller"=>"vendors","action"=>"bulk_action","admin"=>true),"id"=>"bulk_action",'class' => 'block-content form'));
	
?>
	<h1><?php echo COUPON_LISTING; ?></h1>
	
	<div class="no-margin"><table class="table" cellspacing="0" width="100%">
	
	    <thead>
	    <tr>
		<th scope="col">
		<?php echo '<span class="sortkey">Product</span>'; ?> 
		</th>
		<th scope="col">
		<?php echo '<span class="sortkey">Product Code</span>'; ?> 
		</th>
		<th scope="col">
		<?php echo '<span class="sortkey">Batch</span>'; ?> 
		</th>
		<th scope="col">
		<?php echo '<span class="sortkey">Coupon Id</span>'; ?> 
		</th>
		
		<th scope="col">
		<?php echo '<span class="sortkey">Sold</span>'; ?>
		</th>
		
		<th scope="col">
		<?php echo '<span class="sortkey">Sale Time</span>'; ?> 
		</th>
			    
	    </tr>
	    </thead>
		
	    <tbody>
	    <?php 
	    if(!empty($coupon_data)){foreach($coupon_data as $Record){ ?>
		<tr>
		    <td> 
			<?php echo preg_replace("/&#?[a-z0-9]+;/i","",$product_data[$Record['Coupon']['product_code']]); ?>
		    </td>
		    <td> 
			<?php echo $Record['Coupon']['product_code']; ?>
		    </td>
		    <td> 
			<?php echo $batch_data[$Record['Coupon']['batch_id']]; ?>
		    </td>
		    <td> 
			<?php echo $Record['Coupon']['coupon_id']; ?>
		    </td>
		    
		    <td> 
			<?php echo $status_sold[$Record['Coupon']['is_sold']];?>
		    </td>
		    
		    <td>  
			<?php
			
			echo  ($Record['Coupon']['is_sold'] == "1")?date( 'F d,Y h:i:s a',strtotime($Record['Coupon']['modified'])):"Not yet";
			?>
		    </td>
		   
		</tr>
	<?php  } 
    
	    }else{
		echo '<tr><td colspan=6>'.NO_RECORD_FOUND.'</td><tr>';
	    }
	    ?>
	     
    </tbody>
	
	</table>
	</div>
	<ul class="message no-margin">
	    <li>  
		<?php
		echo $this->Paginator->counter(array(
			    'format' => 'Results %page% - %pages% out of %count%'
			));
		?>
	    </li>
	</ul>
	<div class="block-footer"></div>
	<?php echo $this->Form->end();?>			
    </div>		 
		
</section>

<script type="application/x-javascript">
$(document).ready(function(){
    $(".select_product option").each(function(){
	if ($(this).val().indexOf("_") !=-1) {
	    $(this).attr("style","color:#00F;");
	}
    });
});
</script>