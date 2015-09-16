<!-- Content --> 
	<article class="container_12">
		
		<?php echo $this->element('backend/admin_leftMenu');?>
		
		<section class="grid_8">
			 
			<div class="block-border">
				<?php echo $this->element('backend/server_error');?>
	
				<?php echo $this->Form->create('Coupon',array('url'=>array('controller'=>'coupons','action' => 'add', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),'id'=>'add_record', 'name' => 'add_record','class' => 'block-content form')); ?>
 
					<h1><?php echo ADD_COUPON; ?></h1>
					<fieldset>							 
						<?php echo $this->Session->flash(); ?>
						<p>
							<label for="simple-required">Category Type<?php echo REQUIRED; ?></label> 
							<?php echo $this->Form->input('category_id',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$category_list,'empty'=>"Select category",'class'=>'full-width','id'=>'select_category')); ?>	
						</p>
						<p>
							<label for="simple-required">Product<?php echo REQUIRED; ?></label> 
							<?php echo $this->Form->input('product_id',array('type'=>'select','legend'=>false,'label'=>FALSE,'empty'=>"Select product",'class'=>'full-width','id'=>'select_product_list')); ?>	
						</p> 
						 
						<p>
							<label for="simple-required">Coupon Id<?php echo REQUIRED; ?></label> 
							<?php echo $this->Form->input('Coupon.coupon_id',array('type'=>'text','class'=>'full-width'));?>
						</p>
						<p style="margin-bottom:0px;">
							<label for="simple-required">Activation Code<?php echo REQUIRED; ?></label> 
						</p>
						<p>
							<?php
							echo $this->Form->input('activation_code][',array('type'=>'text','class'=>'full-width','style'=>'width:97%;'))."<span>".$this->Html->image("../images/add_icon.png",array('title'=>"Add More","alt"=>"Add More","class"=>"add_more_txt")).'</span>';
							
							?>
						</p>
						
					</fieldset>
					<fieldset>
						<p>
							<label for="simple-action">Select action</label>
					 
							<?php				
								$options=array('1'=>'Save and publish','0'=> 'Save only');					
								echo $this->Form->input('Coupon.is_active',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$options,'class'=>'full-width'));
							?>
						</p>
						<p>
							<span class="submit_button_p">
							<?php
							
							echo $this->Html->link( 'Cancel' , array( 'controller' => 'coupons' , 'action' => 'list' , 'admin' => true ),array('class' => 'cancle_button'));
							echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
							echo $this->Form->submit('Submit',array('div'=>FALSE,'onclick' => 'return ajax_form("add_record","admin/coupons/validate_data_ajax","receiver")','class'=>'submit_button'));
							?>
							</span>
						
						</p>
					</fieldset>
				<?php echo $this->Form->end();?>
			</div>
		
		</section>
		 
		<div class="clear"></div>
</article>