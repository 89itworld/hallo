<?php echo $this->Html->script(array('admin/jquery.bpopup-0.8.0.min') ); ?>   
<?php
echo $this->element("backend/datepicker");
$after_click_sort = array('asc'=>'<span class="sort-up"></span><span class="sort-down active"></span>','desc'=>'<span class="sort-up active"></span><span class="sort-down"></span>');
$before_click_sort = '<span class="sort-up"></span><span class="sort-down"></span>';
$default_sort = '<span class="sort-up active"></span><span class="sort-down"></span>';
//$condition = isset($this->params['named']['page'])?$this->params['named']:$this->params['url']
$fields = array("from","to","sort","direction");
$condition = isset($this->params['named']['sort'])?$this->params['named']:$this->params['url'];
$search_url = $this->Common->make_search_url($fields);
$this->Paginator->options(array('url'=>$search_url['urlArray']));

?>
<!--<article class="container_12">
    <section class="grid_8" style="width:100%;">-->
        <div class="block-border">
            <?php echo $this->element('backend/server_error');
                echo $this->Form->create('Batch',array('url'=>array('controller'=>'batches','action' => 'listing', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),"type"=>"get",'id'=>'batch_list', 'name' => 'batch_list','class' => 'block-content form')); ?>
		<h1>
		    <?php echo $this->Html->image("../images/icons/fugue/plus-circle-blue.png",array("width"=>"16","height"=>"16","style"=>"margin-right:5px;cursor: pointer;","class"=>"search_image"));
		    echo SEARCH; ?>
		</h1>
                <fieldset class="search_toggle">
		    <div class="columns">
			<p class="colx2-left">
			    
			    <div class="float-left gutter-right">
				<label for="stats-period">From</label>
				<span class="input-type-text">
				    <?php echo $this->Form->input("from",array("type"=>"text","class"=>"datepicker_from",'value'=>isset($condition['from'])?$condition['from']:"",'readonly' => true));?>
				</span>
			    </div>
			    <div class="float-left gutter-right">
				<label for="stats-period">TO</label>
				<span class="input-type-text">
				    <?php echo $this->Form->input("to",array("class"=>" datepicker_to",'value'=>isset($condition['to'])?$condition['to']:"",'readonly' => true));?>
				</span>
			    </div>
			    
			    
			    <div class="float-left">
				<label for="stats-period">&nbsp;</label>
				<div class="float-right"> 
				<?php
				    echo $this->Html->link( 'Cancel' , array( 'controller' => 'batches' , 'action' => 'listing' , 'admin' => true ),array('class' => 'cancle_button'));
				    echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;width:auto;'));
				    echo $this->Form->submit('Search',array('div'=>FALSE,'class'=>'submit_button'));
				    ?>
				</div>
			    </div>
			</p>
		    </div>
		    
                </fieldset>
            <?php echo $this->Form->end();?>
        </div>
    
   <!-- </section>-->
     
    <div class="clear"></div>
<!--</article>-->
<!--Search section end-->
<section class="grid_13">
    <div class="block-border">
	<?php
	echo $this->element("backend/common_paging");
	echo $this->Form->create("Model",array("url"=>array("controller"=>"batches","action"=>"bulk_action","admin"=>true),"id"=>"bulk_action",'class' => 'block-content form'));
	echo $this->Form->input("model_name",array('type'=>'hidden','value'=>"Batch"));
?>
	<h1><?php echo BATCH_LISTING; ?></h1>
	
	<div style="margin-bottom: 25px;">
	    <?php echo $this->Session->Flash();?>
	</div>	
	<div class="no-margin"><table class="table" cellspacing="0" width="100%">
	    <thead>
	    <tr>
		<th class="black-cell">
		<?php echo $this->Form->checkbox('select', array(  'class' => 'check_all' )); ?>
		</th>
		<th scope="col">
		<span class="column-sort">
				
		<?php
		
		if(isset($condition['sort']) && ($condition['sort'] == "vendor_id")){
		    echo $after_click_sort[$condition['direction']];		
		}else{
		    echo $before_click_sort;	
		}
		?>

		</span>
		<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'vendor_id','Vendor').'</span>'; ?> 
		</th>
		
		<th scope="col">
		<span class="column-sort">
				
		<?php
		
		if(isset($condition['sort']) && ($condition['sort'] == "product_code")){
		    echo $after_click_sort[$condition['direction']];		
		}else{
		    echo $before_click_sort;	
		}
		?>

		</span>
		<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'product_code','Product').'</span>'; ?> 
		</th>
		
		<th scope="col">
		<span class="column-sort">
				
		<?php
		
		if(isset($condition['sort']) && ($condition['sort'] == "name")){
		    echo $after_click_sort[$condition['direction']];		
		}else{
		    echo $before_click_sort;	
		}
		?>

		</span>
		<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'name','Batch').'</span>'; ?> 
		</th>
		
		<th scope="col">
		<span class="column-sort">
		<?php
		if(empty($condition)){
			echo $default_sort;	
		}elseif(isset($condition['sort']) && ($condition['sort'] == "created")){
				echo $after_click_sort[$condition['direction']];		
		}else{
				echo $before_click_sort;	
		}
		?>
		</span>
		<?php echo '<span class="sortkey">'. $this->Paginator->sort('created','Created').'</span>'; ?>
		</th> 
		
		<th scope="col" class="table-actions">Actions</th>
	    </tr>
	    </thead>
    
	    <tbody>
	    <?php 
	    if(!empty($batch_data)){
		foreach($batch_data as $Record){
?>
		<tr>
		    <th scope="row" class="table-check-cell">
		     <!--<input type="checkbox" name="selected[]" id="table-selected-1" value="1"> -->
		    <?php echo $this->Form->checkbox('id][', array('hiddenField' => false , 'class' => 'check_box' , 'value' =>ENCRYPT_DATA($Record['Batch']['id']))); ?>
		    </th>
		    <td> 
			<?php echo !empty($Record['Batch']['vendor_id'])?$vendor_list[$Record['Batch']['vendor_id']]:"N/A";?>	
		    </td>
		    <td> 
			<?php echo !empty($Record['Batch']['product_code'])?$product_data[$Record['Batch']['product_code']]:"N/A";?>	
		    </td>
		    
		    <td> 
				<span class="batchname" onclick ="return ShowBatchPopup('<?php echo ENCRYPT_DATA($Record['Batch']['id']); ?>');" ><?php echo $Record['Batch']['name']; ?></span>
		    </td>
		    <td> 
			<?php  
			echo  $this->Common->format_date($Record['Batch']['created'], 'F d,Y');
			?>
		    </td>
     
		    
		    <td class="table-actions">
			<?php 
			echo $this->Html->link($this->Html->image('/images/icons/fugue/cross-circle.png',array('title' =>'Delete','class' => 'with-tip')),'/admin/batches/delete_batch/'.ENCRYPT_DATA($Record['Batch']['id']), array('escape' => false,'onclick'=>"return  confirm('Are you sure you want to delete?')"));
			?> 
		    </td>
		</tr>
<?php  		} 
    
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
	
	<div class="block-footer">
		 
		<?php echo  $this->Html->image('/images/icons/fugue/arrow-curve-000-left.png',array('class' =>'picto')); ?> 
		 
		
		<span class="sep"></span>
		<?php				
		    $options=array('2'=>'Delete');					
		    echo $this->Form->input('action',array('type'=>'select','legend'=>false,'div'=>false,'label'=>FALSE,'class'=>'small action_items','options'=>$options,'empty'=>'Action for selected...'));
		?>
		 
		<button type="submit" class="small">Ok</button>
	</div>
	<div id="popup_to_showbatch"></div>
	
	<?php echo $this->Form->end();?>			
    </div>		 
	<style type="text/css">
			span.batchname{
					cursor: pointer;	
					color: #3399CC;
			}	
	</style>
	<script type="text/javascript">
		$(document).ready(function(){ 	
			$('#popup_to_showbatch').hide();
		});
	</script>	
	
</section>