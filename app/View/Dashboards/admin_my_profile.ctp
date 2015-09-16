<!-- Content -->
<article class="container_12">
		<?php //echo $this->element('backend/admin_leftMenu');?>
		<section class="grid_12">
				<div class="block-border">
						<?php echo $this->element('backend/server_error');
						
						echo $this->Form->create('User',array('url'=>array('controller'=>'dashboards','action' => 'my_profile', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),'id'=>'edit_user','name' =>'edit_user','class' => 'block-content form'));
						echo $this->Form->hidden('id');
						echo $this->Form->hidden('UserProfile.id');
						?>
						<h1><?php echo MY_PROFILE; ?></h1>
						<fieldset>
								<?php echo $this->Session->flash(); ?>
								<p>
										<label for="simple-required">Email</label> 
										
										<span class="input-type-text">
										<?php echo $this->data['User']['email'];?>
					
										</span>
								</p>
								
								
								<p>
										<label for="simple-required">Dealer Name<?php echo REQUIRED; ?></label>
										<?php
										if(($this->Session->read("Auth.User.role_id") == "2") || ($this->Session->read("Auth.User.role_id") == "3")){ ?>
										<span class="input-type-text">
										<?php echo $this->data['UserProfile']['first_name'];?>
					
										</span>		
								<?php }else{ ?>
										<?php echo $this->Form->input('UserProfile.first_name',array('class'=>'full-width'));
										} ?>
								</p>
								<?php 
										if(($this->Session->read("Auth.User.role_id") == "2") || ($this->Session->read("Auth.User.role_id") == "3")){
								}else{ ?>				
								<p>
										<label for="simple-required">Password</label> 
										<?php echo $this->Form->input('powd',array('type'=>'password','class'=>'full-width','value'=>''));?>
								</p>
								<p>
										<label for="simple-required">Confirm Password</label> 
										<?php echo $this->Form->input('confirm_password',array('type'=>'password','class'=>'full-width'));?>
								</p>
								<?php } ?>
								<p>
										<label for="simple-required">Phone No<?php echo REQUIRED; ?></label>
										<?php
										if(($this->Session->read("Auth.User.role_id") == "2") || ($this->Session->read("Auth.User.role_id") == "3")){ ?>
										<span class="input-type-text">
										<?php echo $this->data['UserProfile']['phone_no'];?>
					
										</span>		
								<?php }else{ ?>
										<?php echo $this->Form->input('UserProfile.phone_no',array('class'=>'full-width'));
								}?>
								</p>
								<p>
										<span class="submit_button_p">
										<?php
										if(($this->Session->read("Auth.User.role_id") == "2") || ($this->Session->read("Auth.User.role_id") == "3")){
												
										}else{
										//echo $this->Html->link( 'Cancel' , array( 'controller' => 'dashboards' , 'action' => 'index' , 'admin' => true ),array('class' => 'cancle_button'));
										echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
										echo $this->Form->submit('Submit',array('div'=>FALSE,'onclick' => 'return ajax_form("edit_user","admin/users/validate_add_user_ajax","receiver")','class'=>'submit_button'));
										}
										?>
										</span>
								</p>
						</fieldset>
						<?php echo $this->Form->end();?>
				</div>
		</section>
		<div class="clear"></div>
</article>