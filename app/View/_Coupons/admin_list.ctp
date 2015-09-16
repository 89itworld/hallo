<?php
echo $this->element("backend/datepicker");
$condition_sort = isset($this->params['named']['sort'])?$this->params['named']:$this->params['url'];
$after_click_sort = array('asc'=>'<span class="sort-up"></span><span class="sort-down active"></span>','desc'=>'<span class="sort-up active"></span><span class="sort-down"></span>');
$before_click_sort = '<span class="sort-up"></span><span class="sort-down"></span>';
$default_sort = '<span class="sort-up active"></span><span class="sort-down"></span>';

$fields = array("coupon_id","is_active","from","to","sort","direction");
$condition = isset($this->params['named']['page'])?$this->params['named']:$this->params['url'];
$search_url = $this->Common->make_search_url($fields);
$this->Paginator->options(array('url'=>$search_url['urlArray']));
$query_string = $search_url['query_string'];
if(strlen($query_string)>1){
    $query_string = substr($query_string,0,-1);
}

?>

<article class="container_12">
    <section class="grid_8" style="width:100%;">
        <div class="block-border">
            <?php echo $this->element('backend/server_error');
                echo $this->Form->create('Coupon',array('url'=>array('controller'=>'coupons','action' => 'list', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),"type"=>"get",'id'=>'category_list', 'name' => 'category_list','class' => 'block-content form')); ?>
		<h1><?php echo SEARCH; ?></h1>
                <fieldset>
				<div class="columns">
						<p class="colx2-left">
						<label for="complex-fr-url">Coupon Id</label>
						<span class="relative">
						    <?php echo $this->Form->input("coupon_id",array("type"=>"text","class"=>"full-width",'value'=>isset($condition['coupon_id'])?$condition['coupon_id']:""));?>
						</span>
						</p>
						<p class="colx2-right">
								<label for="complex-fr-style">Status</label>
								<?php
								    $status = array(1=>"Active",0=>"Inactive");
								    echo $this->Form->input("is_active",array('type'=>'select','options'=>$status,'selected'=>isset($condition['is_active'])?$condition['is_active']:"","empty"=>"All","class"=>"full-width"));
								?>
								
						</p>
					
				</div>
				<div class="columns">
						<p class="colx2-left">
								<label for="complex-fr-url">From</label>
								<span class="relative">
								    <?php echo $this->Form->input("from",array("class"=>"full-width datepicker_from",'value'=>isset($condition['from'])?$condition['from']:"",'readonly' => true));?>
								</span>
						</p>
						<p class="colx2-right">
								<label for="complex-fr-subtitle">To</label>
								<?php echo $this->Form->input("to",array("class"=>"full-width datepicker_to",'value'=>isset($condition['to'])?$condition['to']:"",'readonly' => true));?>
						</p>
					
				</div>
				<div class="columns">
						<p class="input-with-button">
								<span class="submit_button_p">
								<?php
								echo $this->Html->link( 'Cancel' , array( 'controller' => 'coupons' , 'action' => 'list' , 'admin' => true ),array('class' => 'cancle_button'));
								echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;width:auto;'));
								echo $this->Form->submit('Search',array('div'=>FALSE,'class'=>'submit_button'));
								?>
								</span>
						    
						</p>
				</div>
                </fieldset>
                
                    
            <?php echo $this->Form->end();?>
        </div>
    
    </section>
     
    <div class="clear"></div>
</article>
<!--Search section end-->
<div style="opacity: 1;" id="control-bar" class="grey-bg clearfix">
		<div class="container_12">
				<div class="float-right">
						<span class="submit_button_p">
						<?php
						echo $this->Html->link(ADD_COUPON,array("controller"=>"coupons","action"=>"add","admin"=>true),array("escape"=>false,"class"=>"submit_button"));
						?>
						
						</span>
				</div>
		</div>
</div>

<section class="grid_13">
		<div class="block-border">
				<?php
				echo $this->element("backend/common_paging");
				echo $this->Form->create("Model",array("url"=>array("controller"=>"vendors","action"=>"bulk_action","admin"=>true),"id"=>"bulk_action",'class' => 'block-content form'));
				echo $this->Form->input("model_name",array('type'=>'hidden','value'=>"Coupon"));
            ?>
				<h1><?php echo LBL_COUPON_LISTING; ?></h1>
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
								
								if(isset($condition_sort['sort']) && ($condition_sort['sort'] == "category_title")){
										echo $after_click_sort[$condition_sort['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>

								</span>
								<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'category_id','Category').'</span>'; ?> 
								</th>
								
								
								<th scope="col">
								<span class="column-sort">
										
								<?php
								
								if(isset($condition_sort['sort']) && ($condition_sort['sort'] == "Product_title")){
										echo $after_click_sort[$condition_sort['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>

								</span>
								<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'product_id','Product').'</span>'; ?> 
								</th>
								
								
								<th scope="col">
								<span class="column-sort">
										
								<?php
								
								if(isset($condition_sort['sort']) && ($condition_sort['sort'] == "coupon_id")){
										echo $after_click_sort[$condition_sort['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>

								</span>
								<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'coupon_id','Coupon').'</span>'; ?> 
								</th>
								<th scope="col">
								<span class="column-sort">
								<?php
								
								if(isset($condition_sort['sort']) && ($condition_sort['sort'] == "activation_code")){
										echo $after_click_sort[$condition_sort['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>
								</span>
								<?php echo '<span class="sortkey">'. $this->Paginator->sort('activation_code','Activation Code').'</span>'; ?> 
								</th>
								<th scope="col">
								<span class="column-sort">
								<?php
								
								if(isset($condition_sort['sort']) && ($condition_sort['sort'] == "is_active")){
										echo $after_click_sort[$condition_sort['direction']];		
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
								if(empty($condition_sort)){
									echo $default_sort;	
								}elseif(isset($condition_sort['sort']) && ($condition_sort['sort'] == "created")){
										echo $after_click_sort[$condition_sort['direction']];		
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
						if(!empty($coupon_data)){
								foreach($coupon_data as $Record){
						?>
								<tr>
										<th scope="row" class="table-check-cell">
										 <!--<input type="checkbox" name="selected[]" id="table-selected-1" value="1"> -->
										<?php echo $this->Form->checkbox('id][', array('hiddenField' => false , 'class' => 'check_box' , 'value' =>ENCRYPT_DATA($Record['Coupon']['id']))); ?>
										</th>
										<td> 
											<?php echo $category_list[$Record['Coupon']['category_id']]; ?>
										</td>
										<td> 
											<?php echo $product_listing[$Record['Coupon']['product_id']]; ?>
										</td>
										<td> 
											<?php echo $Record['Coupon']['coupon_id']; ?>
										</td>
										<td>  
											<?php  
											echo  $Record['Coupon']['activation_code'];
											?>
										</td>
										<td> 
											<?php 
												$status =($Record['Coupon']['is_active'] == '1')?1:0;
											
											echo $this->Common->is_active($Record['Coupon']['is_active'],'/admin/coupons/activate_coupon/'.ENCRYPT_DATA($status).'/'.ENCRYPT_DATA($Record['Coupon']['id']).'/Coupon'); 
											?>
										</td>
										
										<td>  
											<?php  
											echo  $this->Common->format_date($Record['Coupon']['created'], 'F d,Y');
											?>
										</td>
										<td class="table-actions">
												<?php
												
												echo $this->Html->link(
													    $this->Html->image('/images/icons/fugue/pencil.png',array('title' =>'Edit','class' => 'with-tip')),array("controller"=>"coupons","action"=>"edit",ENCRYPT_DATA($Record['Coupon']['id'])),array('escape' => false));
												echo $this->Html->link($this->Html->image('/images/icons/fugue/cross-circle.png',array('title' =>'Delete','class' => 'with-tip')),array("controller"=>"coupons","action"=>"delete_coupon",ENCRYPT_DATA($Record['Coupon']['id'])),array('escape' => false,'onclick'=>"return  confirm('Are you sure you want to delete?')"));
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
							$options=array('Active','Deactive','Delete');					
							echo $this->Form->input('action',array('type'=>'select','legend'=>false,'div'=>false,'label'=>FALSE,'class'=>'small action_items','options'=>$options,'empty'=>'Action for selected...'));
					?>
					 
					<button type="submit" class="small">Ok</button>
				</div>
				<?php echo $this->Form->end();?>			
		
					
		</div>		 
		
</section>