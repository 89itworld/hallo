<style>
    .product_description > p{margin-bottom: 0px;}
</style>
<?php
echo $this->element("backend/datepicker");
$after_click_sort = array('asc'=>'<span class="sort-up"></span><span class="sort-down active"></span>','desc'=>'<span class="sort-up active"></span><span class="sort-down"></span>');
$before_click_sort = '<span class="sort-up"></span><span class="sort-down"></span>';
$default_sort = '<span class="sort-up active"></span><span class="sort-down"></span>';
//$condition = isset($this->params['named']['page'])?$this->params['named']:$this->params['url'];

$fields = array("title","lt","is_active","from","to","sort","direction");
$condition = isset($this->params['named']['sort'])?$this->params['named']:$this->params['url'];
$search_url = $this->Common->make_search_url($fields);
$this->Paginator->options(array('url'=>$search_url['urlArray']));
$query_string = $search_url['query_string'];

?>
<!--<article class="container_12">
    <section class="grid_8" style="width:100%;">-->
        <div class="block-border">
            <?php echo $this->element('backend/server_error');
                echo $this->Form->create('Product',array('url'=>array('controller'=>'products','action' => 'listing', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),"type"=>"get",'id'=>'category_list', 'name' => 'category_list','class' => 'block-content form')); ?>
		<?php /*<h1>
		    echo $this->Html->image("../images/icons/fugue/plus-circle-blue.png",array("width"=>"16","height"=>"16","style"=>"margin-right:5px;cursor: pointer;","class"=>"search_image"));
		    echo SEARCH; 
		</h1> search_toggle*/ ?>
                <fieldset class="">
		    <div class="columns">
			<p class="colx2-left">
			    <div class="float-left gutter-right">
				<label for="stats-period">Product Title</label>
				<span class="input-type-text">
				    <?php
				    echo $this->Form->input("sort",array("type"=>"hidden",'value'=>isset($condition['sort'])?$condition['sort']:"created"));
				echo $this->Form->input("direction",array("type"=>"hidden",'value'=>isset($condition['direction'])?$condition['direction']:"desc"));
				
				    echo $this->Form->input("title",array("type"=>"text",'value'=>isset($condition['title'])?$condition['title']:""));?>
				</span>
			    </div>
			    <div class="float-left gutter-right">
				<label for="stats-period">Maximum Lines</label>
				<span class="input-type-text">
				    <?php echo $this->Form->input("lt",array("type"=>"text",'value'=>isset($condition['lt'])?$condition['lt']:""));?>
				</span>
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
				    <?php echo $this->Form->input("to",array("class"=>" datepicker_to",'value'=>isset($condition['to'])?$condition['to']:"",'readonly' => true));?>
				</span>
			    </div>
			    
			    <div class="float-left gutter-right">
				<label for="stats-period">Status</label>
				<?php
				    $status = array(1=>"Active",0=>"Inactive");
				    $selected = isset($condition['is_active'])?$condition['is_active']:""; 
				    echo $this->Form->input('is_active',array('type'=>'select','options'=>$status,'selected'=>$selected,"empty"=>"All","label"=>false,"div"=>false));
				    ?>
			    </div>
			    
			    <div class="float-left">
				<label for="stats-period">&nbsp;</label>
				<div class="float-right"> 
				<?php
				    echo $this->Html->link( 'Cancel' , array( 'controller' => 'products' , 'action' => 'listing' , 'admin' => true ),array('class' => 'cancle_button'));
				    echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;width:auto;'));
				    echo $this->Form->button("Export To CSV",array("type"=>"submit","class"=>"red","style"=>'margin:0 4px;width:auto;','name'=>'export_to_csv','value'=>'csv'));
					    
				echo $this->Form->button("Export To PDF",array("type"=>"submit","class"=>"grey","style"=>'margin:0 4px;width:auto;','name'=>'export_to_pdf','value'=>'pdf'));
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
<div style="opacity: 1;" id="control-bar" class="grey-bg clearfix">
		<div class="container_12">
				<div class="float-right">
						<span class="submit_button_p">
						<?php
						echo $this->Html->link(ADD_PRODUCT,array("controller"=>"products","action"=>"add_product","admin"=>true),array("escape"=>false,"class"=>"submit_button"));
						?>
						
						</span>
				</div>
		</div>
</div>
<section class="grid_13">
		<div class="block-border">
				<?php
				echo $this->element("backend/common_paging");
				echo $this->Form->create("Model",array("url"=>array("controller"=>"products","action"=>"bulk_action","admin"=>true),"id"=>"bulk_action",'class' => 'block-content form'));
				echo $this->Form->input("model_name",array('type'=>'hidden','value'=>"Product"));
            ?>
				<h1><?php echo LBL_PRODUCT_LISTING; ?></h1>
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
								
								if(isset($condition['sort']) && ($condition['sort'] == "p_id")){
										echo $after_click_sort[$condition['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>

								</span>
								<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'p_id','ID').'</span>'; ?> 
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
								
								if(isset($condition['sort']) && ($condition['sort'] == "category_id")){
										echo $after_click_sort[$condition['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>

								</span>
								<?php echo '<span class="sortkey">'.$this->Paginator->sort('category_id','Voucher').'</span>'; ?> 
								</th>
								<th scope="col">
								<span class="column-sort">
										
								<?php
								
								if(isset($condition['sort']) && ($condition['sort'] == "title")){
										echo $after_click_sort[$condition['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>

								</span>
								<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'title','Product Title').'</span>'; ?> 
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
								<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'product_code','Product Code').'</span>'; ?> 
								</th>
								
								<th scope="col">
								<span class="column-sort">
								<?php
								
								if(isset($condition['sort']) && ($condition['sort'] == "is_active")){
										echo $after_click_sort[$condition['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>
								</span>
								<?php echo '<span class="sortkey">'.$this->Paginator->sort('is_active','Active').'</span>'; ?>
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
						if(!empty($product_data)){
								foreach($product_data as $Record){
						?>
								<tr>
										<th scope="row" class="table-check-cell">
										 <!--<input type="checkbox" name="selected[]" id="table-selected-1" value="1"> -->
										<?php echo $this->Form->checkbox('id][', array('hiddenField' => false , 'class' => 'check_box' , 'value' =>ENCRYPT_DATA($Record['Product']['id']))); ?>
										</th>
										<td> 
											<?php echo $Record['Product']['p_id']; ?>
										</td>
										<td> 
											<?php echo ucfirst($vendor_list[$Record['Product']['vendor_id']]); ?>
										</td>
										
										<td> 
											<?php echo sprintf("%0.2f",$category_list[$Record['Product']['category_id']]); ?>
										</td>
										<td> 
											<?php echo $Record['Product']['title']; ?>
										</td>
										<td> 
											<?php echo $Record['Product']['product_code']; ?>
										</td>
										
										<td> 
											<?php 
												$status =($Record['Product']['is_active'] == '1')?1:0;
											
											echo $this->Common->is_active($Record['Product']['is_active'],'/admin/products/activate_product/'.$status.'/'.ENCRYPT_DATA($Record['Product']['id']).'/Product'); 
											?>
										</td>
										<td>  
											<?php  
											echo  $this->Common->format_date($Record['Product']['created'], 'F d,Y');
											?>
										</td>
								 
										
										<td class="table-actions">
											<?php 
											echo $this->Html->link(
													    $this->Html->image('/images/icons/fugue/pencil.png',array('title' =>'Edit','class' => 'with-tip')),
													    '/admin/products/edit_product/'.ENCRYPT_DATA($Record['Product']['id']),
													    array('escape' => false)
													  );
											echo $this->Html->link($this->Html->image('/images/icons/fugue/cross-circle.png',array('title' =>'Delete','class' => 'with-tip')),array("controller"=>"products","action"=>"delete_bulk",ENCRYPT_DATA($Record['Product']['id']),ENCRYPT_DATA($Record['Product']['product_code']),"Product"),array('escape' => false,'onclick'=>"return  confirm('Are you sure you want to delete?')"));
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
							$options=array('Active','Deactive');					
							echo $this->Form->input('action',array('type'=>'select','legend'=>false,'div'=>false,'label'=>FALSE,'class'=>'small action_items','options'=>$options,'empty'=>'Action for selected...'));
					?>
					 
					<button type="submit" class="small">Ok</button>
				</div>
				<?php echo $this->Form->end();?>			
		
					
		</div>		 
		
</section>