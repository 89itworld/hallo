
<!-- Content --><?php  //pr($_SERVER); die; ?>
	<article class="container_12">
		
		<?php //echo $this->element('backend/admin_leftMenu');?>
		
		<section class="grid_12">
			 
			<div class="block-border">
					<?php echo $this->element('backend/server_error');
						echo $this->Form->create('Category',array('url'=>array('controller'=>'categories','action' => 'edit_category', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),'id'=>'edit_category', 'name' => 'edit_category','class' => 'block-content form'));
						echo $this->Form->input('id',array('type'=>'hidden'));
					?>
	 
						<h1><?php echo EDIT_CATEGORY; ?></h1>
						 
						
						<fieldset>
							<?php echo $this->Session->flash(); ?>
							<p>
								<label for="simple-required">Value<?php echo REQUIRED; ?></label> 
								<?php echo $this->Form->input('title',array("type"=>"text",'class'=>'full-width'));?>
							</p>
							<p>
								<label for="simple-required">Description</label> 
								<?php echo $this->Form->input('description',array('type'=>'textarea','row'=>'5','class'=>'full-width'));?>
							</p>
							<p>
								<label for="simple-action">Select action</label>
						 
								<?php				
								$options=array('1'=>'Save and publish','0'=> 'Save only');
								$selected = "";
								if(isset($this->data['Category']['is_active'])){
									$selected = $this->data['Category']['is_active'];	
								}
								echo $this->Form->input('is_active',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$options,'selected'=>$selected,'class'=>'full-width'));
								?>
							</p>
							<p>
								<span class="submit_button_p">
								<?php
								echo $this->Html->link( 'Cancel' , array( 'controller' => 'categories' , 'action' => 'listing' , 'admin' => true ),array('class' => 'cancle_button'));
								echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
								echo $this->Form->submit('Submit',array('div'=>FALSE,'onclick' => 'return ajax_form("edit_category","admin/categories/validate_add_category_ajax","receiver")','class'=>'submit_button'));
								
								?>
								</span>
								
							</p>
						</fieldset>
					<?php echo $this->Form->end();?>
			</div>
		
		</section>
		 
		<div class="clear"></div>
</article>