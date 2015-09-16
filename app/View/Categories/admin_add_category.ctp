
	<article class="container_12">
		<?php //echo $this->element('backend/admin_leftMenu');?>
		<section class="grid_12">
			 
			<div class="block-border">
					<?php echo $this->element('backend/server_error');
						echo $this->Form->create('Category',array('url'=>array('controller'=>'categories','action' => 'add_category', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),'id'=>'add_category', 'name' => 'add_category','class' => 'block-content form')); ?>
	 
						<h1><?php echo ADD_CATEGORY; ?></h1>
						 
						
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
							
						<?php 	 
						/*
							<p>
								<span class="label">Inline checkable</span>
								
								
									$options=array('1'=>' Active','0'=> ' Inactive');
									$attributes=array('legend'=>false,'label'=>TRUE,"style" => "margin-left:10px;",'default'=>'1');
									echo $this->Form->radio('Vendor.is_active',$options,$attributes);
								
						 </p>
							*/
						?>	 
							<p>
								<label for="simple-action">Select action</label>
						 
								<?php				
									$options=array('1'=>'Save and publish','0'=> 'Save only');					
									echo $this->Form->input('is_active',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$options,'class'=>'full-width'));
								?>
							</p>
							<p>
								<span class="submit_button_p">
								<?php
								echo $this->Html->link( 'Cancel' , array( 'controller' => 'categories' , 'action' => 'listing' , 'admin' => true ),array('class' => 'cancle_button'));
								echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
								echo $this->Form->submit('Submit',array('div'=>FALSE,'onclick' => 'return ajax_form("add_category","admin/categories/validate_add_category_ajax","receiver")','class'=>'submit_button'));
								?>
								</span>
								
							</p>
						</fieldset>
					<?php echo $this->Form->end();?>
			</div>
		
		</section>
		 
		<div class="clear"></div>
</article>