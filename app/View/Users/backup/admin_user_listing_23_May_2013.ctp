<?php
echo $this->element("backend/datepicker");
$after_click_sort = array('asc'=>'<span class="sort-up"></span><span class="sort-down active"></span>','desc'=>'<span class="sort-up active"></span><span class="sort-down"></span>');
$before_click_sort = '<span class="sort-up"></span><span class="sort-down"></span>';
$default_sort = '<span class="sort-up active"></span><span class="sort-down"></span>';
$fields = array("name","is_active","from","to","sort","direction");
//$condition = isset($this->params['named']['page'])?$this->params['named']:$this->params['url'];
$condition = isset($this->params['named']['sort'])?$this->params['named']:$this->params['url'];
$search_url = $this->Common->make_search_url($fields);
$this->Paginator->options(array('url'=>$search_url['urlArray']));
?>

<div class="block-border">
    <?php echo $this->element('backend/server_error');
    echo $this->Form->create('User',array('url'=>array('controller'=>'users','action' => 'user_listing', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),"type"=>"get",'id'=>'role_lis', 'name' => 'role_list','class' => 'block-content form')); ?>
    
    <h1>
	<?php echo $this->Html->image("../images/icons/fugue/plus-circle-blue.png",array("width"=>"16","height"=>"16","style"=>"margin-right:5px;cursor: pointer;","class"=>"search_image"));
	echo SEARCH; ?>
    </h1>
    <fieldset class="search_toggle">
	<div class="columns">
	    <p class="colx2-left">
		<div class="float-left gutter-right">
		    <label for="stats-period">Dealer Name</label>
		    <span class="input-type-text">
			<?php
			echo $this->Form->input("name",array("type"=>"text",'value'=>isset($condition['name'])?$condition['name']:""));?>
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
			echo $this->Form->input('is_active',array('type'=>'select','options'=>$status,'selected'=>$selected,"empty"=>"All","label"=>false,"div"=>false));;
			?>
		</div>
		
		<div class="float-left">
		    <label for="stats-period">&nbsp;</label>
		    <div class="float-right"> 
		    <?php
			echo $this->Html->link( 'Cancel' , array( 'controller' => 'users' , 'action' => 'user_listing' , 'admin' => true ),array('class' => 'cancle_button'));
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
<div class="clear"></div>

<!--Search section end-->
<div style="opacity: 1;" id="control-bar" class="grey-bg clearfix">
		<div class="container_12">
				<div class="float-right">
						<span class="submit_button_p">
						<?php
						if($this->Session->read("Auth.User.role_id") == "1"){
						    echo $this->Html->link(ADD_USER,array("controller"=>"users","action"=>"add_user","admin"=>true),array("escape"=>false,"class"=>"submit_button"));
						}
						
						?>
						</span>
				</div>
		</div>
</div>

<section class="grid_13">
		
		<div class="block-border">
				<?php
				echo $this->element("backend/common_paging");
				echo $this->Form->create("Model",array("url"=>array("controller"=>"users","action"=>"bulk_action","admin"=>true),"id"=>"bulk_action",'class' => 'block-content form'));
				echo $this->Form->input("model_name",array('type'=>'hidden','value'=>"User"));
            ?>
				<h1><?php echo USER_LISTING; ?></h1>
				
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
								
								if(isset($condition['sort']) && ($condition['sort'] == "User.u_id")){
										echo $after_click_sort[$condition['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>

								</span>
								<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'User.u_id','ID').'</span>'; ?> 
								</th>
								<th scope="col">
								<span class="column-sort">
										
								<?php
								
								if(isset($condition['sort']) && ($condition['sort'] == "UserProfile.first_name")){
										echo $after_click_sort[$condition['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>

								</span>
								<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'UserProfile.first_name','Name').'</span>'; ?> 
								</th>
								<th scope="col">
								<span class="column-sort">
								<?php
								if(isset($condition['sort']) && ($condition['sort'] == "role_id")){
										echo $after_click_sort[$condition['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>
								</span>
								<?php echo '<span class="sortkey">'.$this->Paginator->sort('role_id','Role').'</span>'; ?>
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
								
								if(isset($condition['sort']) && ($condition['sort'] == "email")){
										echo $after_click_sort[$condition['direction']];		
								}else{
									echo $before_click_sort;	
								}
								?>
								</span>
								<?php echo '<span class="sortkey">'. $this->Paginator->sort('email','Email').'</span>'; ?> 
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
						if(!empty($user_data)){
								foreach($user_data as $Record){
						?>
								<tr>
										<th scope="row" class="table-check-cell">
										
										<?php echo $this->Form->checkbox('id][', array('hiddenField' => false , 'class' => 'check_box' , 'value' =>ENCRYPT_DATA($Record['User']['id']))); ?>
										</th>
										<td>
											 <?php  
												echo  !empty($Record['User']['u_id'])?$Record['User']['u_id']:"N/A";
											?>
										</td>
										<td> 
											<?php echo ucwords($Record['UserProfile']['first_name'])." ".ucfirst($Record['UserProfile']['last_name']); ?>
										</td>
										<td> 
											<?php echo ucfirst($roles[$Record['User']['role_id']]);
											if(!empty($Record['Dealer']['first_name'])){
											echo "(".ucfirst($Record['Dealer']['first_name'])." ".ucfirst($Record['Dealer']['last_name']).")";    
											}
											?>
										</td>
										<td> 
											<?php 
												$status =($Record['User']['is_active'] == '1')?1:0;
											echo $this->Common->is_active($Record['User']['is_active'],'/admin/users/activate_bulk/'.$status.'/'.ENCRYPT_DATA($Record['User']['id']).'/User'); 
											?>
										</td>
										<td>
											 <?php  
												echo  $Record['User']['email'];
											?>
										</td>
										<td>  
											<?php  
											echo  $this->Common->format_date($Record['User']['created'], 'F d,Y');
											?>
										</td>
								 
										
										<td class="table-actions">
											<?php
											    if($this->Session->read("Auth.User.role_id") == "1"){
												echo $this->Html->link($this->Html->image('/images/icons/fugue/pencil.png',array('title' =>'Edit','class' => 'with-tip')),array("controller"=>"users","action"=>"edit_user",ENCRYPT_DATA($Record['User']['id'])),array('escape' => false));
												echo $this->Html->link($this->Html->image('/images/icons/fugue/cross-circle.png',array('title' =>'Delete','class' => 'with-tip')),array("controller"=>"users","action"=>"delete_bulk",ENCRYPT_DATA($Record['User']['id']),"User"),array('escape' => false,'onclick'=>!empty($Record[0]['total_subdealer'])?"return  confirm('There are ".$Record[0]['total_subdealer']." subdealer(s) undedr this dealer. Are you sure you want to delete?')":"return  confirm('There are Are you sure you want to delete?')"));
											    }else{
												echo NO_ACTION;
											    }
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
					 
						<?php
						
						if($this->Session->read("Auth.User.role_id") == "1"){
						echo  $this->Html->image('/images/icons/fugue/arrow-curve-000-left.png',array('class' =>'picto')); ?> 
						 
						
						<span class="sep"></span>
						<?php				
								$options=array('Active','Deactive');					
								echo $this->Form->input('action',array('type'=>'select','legend'=>false,'div'=>false,'label'=>FALSE,'class'=>'small action_items','options'=>$options,'empty'=>'Action for selected...'));
						?>
						 
						<button type="submit" class="small">Ok</button>
						<?php } ?>
				</div>
				<?php echo $this->Form->end();?>			
		</div>		 
		
</section>