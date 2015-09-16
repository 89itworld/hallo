<!-- Content --><?php  //pr($_SERVER); die; ?>
	<article class="container_12">
		
		<?php //echo $this->element('backend/admin_leftMenu');?>
		
		<section class="grid_12">
			 
			<div class="block-border">
					<?php echo $this->element('backend/server_error');?>
					 
					<?php echo $this->Form->create('Vendor',array('url'=>array('controller'=>'vendors','action' => 'edit', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),'enctype' => 'multipart/form-data','id'=>'edit_record', 'name' => 'edit_record','class' => 'block-content form')); ?>
	 				<?php echo $this->form->input('Vendor.id',array('type'=>'hidden')); ?> 
						<h1><?php echo EDIT_VENDOR; ?></h1>
						 
						<?php //echo $d=enCrypt($this->data['Vendor']['id']);  ?>
						<fieldset>
							<!--<legend>Fieldset</legend> -->
							<?php echo $this->Session->flash(); ?>
							<!--<ul class="message error no-margin">
								<li>This is an <strong>error message</strong>, inside a form</li>
							</ul> -->
							
							<p>
								<label for="simple-required">Name<?php echo REQUIRED; ?></label> 
								<?php echo $this->Form->input('Vendor.name',array('class'=>'full-width'));?>
							</p>
							<p>
								<label for="simple-required">Vendor Image<?php echo REQUIRED; ?></label> 
								<?php echo $this->Form->input('Vendor.image',array('type'=>'file','class'=>'full-width','value'=>''));
								echo $this->Form->input('Vendor.previous_image',array('type'=>'hidden','class'=>'full-width','value'=>$this->data['Vendor']['image']));
								?>
							</p>
							<p>
								<label for="simple-action">Select action</label>
						 
								<?php				
								$options=array('1'=>'Save and publish','0'=> 'Save only');
								$selected = "";
								if(isset($this->data['Vendor']['is_active'])){
									$selected = $this->data['Vendor']['is_active'];	
								}
								echo $this->Form->input('is_active',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$options,'selected'=>$selected,'class'=>'full-width'));
								?>
							</p>
							<p>
								<span class="submit_button_p">
								<?php
								echo $this->Html->link( 'Cancel' , array( 'controller' => 'vendors' , 'action' => 'list' , 'admin' => true ),array('class' => 'cancle_button'));
								echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
								echo $this->Form->submit('Submit',array('div'=>FALSE,'onclick' => 'return ajax_form("edit_record","admin/vendors/validate_data_ajax","receiver")','class'=>'submit_button'));
								
								?>
								</span>
								
							</p>
							
						</fieldset>
						
						 
							
					</form>
			</div>
		
		</section>
		 
		<div class="clear"></div>
</article>