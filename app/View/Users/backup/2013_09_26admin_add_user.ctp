<!-- Content -->
<style>
.hide_limit{display:none;}
</style>
<article class="container_12">
		<?php //echo $this->element('backend/admin_leftMenu');?>
		<section class="grid_12">
				<div class="block-border">
						<?php echo $this->element('backend/server_error');
						echo $this->Form->create('User',array('url'=>array('controller'=>'users','action' => 'add_user', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),'id'=>'add_user','name' =>'add_user','class' => 'block-content form'));
						echo $this->Form->input('is_dealer',array('type'=>'hidden','id'=>'is_dealer_selected'));
						?>
						<h1><?php echo ADD_USER; ?></h1>
						<fieldset>
								<?php echo $this->Session->flash(); ?>
								
								<p>
										<label for="simple-action">Role<?php echo REQUIRED; ?></label>
										<?php
										
										if($this->Session->read("Auth.User.role_id") == "2"){ 
												foreach ($roles as $key => $value){
														if ($key != '3'){
																unset($roles[$key]);
														}
												}
												echo $this->Form->input('role_id',array('type'=>'select','legend'=>false,'options'=>$roles,'class'=>'full-width select_user_type',"selected"=>$roles['3']));
										?>
										<script type="text/javascript">
										$(document).ready(function(){
												$("#select_dealer_name").parent("p").removeClass("hide");
        
												$("#is_dealer_selected").attr('value','0');
        
        
												$('#set_user_permission').append('<img src="'+ajax_url+'images/arbo-loader-grey.gif" class="temp_loading" alt="Please wait..." >');
												$.ajax({
												    url:ajax_url+'admin/users/user_permission/3',
												    success:function(response){
													$("#set_user_permission").find('img.temp_loading').remove();
													$("#set_user_permission").html(response);
												    }
												});
												return false;  		
										}); 
										</script>
										
										
										<?php }else{
												echo $this->Form->input('role_id',array('type'=>'select','legend'=>false,'options'=>$roles,'class'=>'full-width select_user_type',"empty"=>"Select user type"));
										}
										?>
								</p>
								<p class="hide">
										<label for="simple-action">Dealer<?php echo REQUIRED; ?></label>
										<?php 
										if($this->Session->read("Auth.User.role_id") == "2"){
												echo "<span  id='select_dealer_name'><b>".$this->Session->read("Auth.User.UserProfile.first_name").'</b></span>';
												echo $this->Form->input('parent_id',array('type'=>'hidden',"value"=>$this->Session->read("Auth.User.id")));
										
										}else{
												echo $this->Form->input('parent_id',array('type'=>'select','legend'=>false,'options'=>$dealer,'class'=>'full-width','id'=>'select_dealer_name',"empty"=>"Select deler"));
										}
										?>
								</p>
								
								<p class="hide_limit">
										<label for="simple-action">Selling Limit<?php echo REQUIRED; ?></label>
										<?php
										
										
										$salling_options =array("Daily","Weekly","Monthly");
										/*if($this->Session->read("Auth.User.role_id") == "2"){
												foreach ($salling_options as $key1 => $value1){
														if ($key1 != $this->Session->read("Auth.User.selling_limit")){
																
																unset($salling_options[$key1]);
														}
												}	
										}*/
										
										echo $this->Form->input('selling_limit',array('type'=>'select','legend'=>false,'options'=>$salling_options,'class'=>'full-width selling_limit_hide_show',"empty"=>"Select selling limit"));
										?>
								</p>
								
								<p class="hide_limit">
										<label for="simple-required">Selling Price Limit(DKK)<?php echo REQUIRED; ?></label> 
										<?php echo $this->Form->input('selling_price_limit',array('type'=>'text','class'=>'full-width selling_limit_hide_show'));?>
								</p>
								
								<p>
										<label for="simple-required">ID<?php echo REQUIRED; ?></label> 
										
										<?php
										
										if($this->Session->read("Auth.User.role_id") == "2"){
												echo "<span><b>".$this->Session->read("Auth.User.u_id").'</b></span>';
												echo $this->Form->input('u_id',array('type'=>'hidden',"value"=>$this->Session->read("Auth.User.u_id")));
										}else{
												echo $this->Form->input('u_id',array('type'=>'text','class'=>'full-width add_user_id'));
										}
										?>
								</p>
								
								<p>
										<label for="simple-required">Email<?php echo REQUIRED; ?></label> 
										<?php echo $this->Form->input('email',array('class'=>'full-width'));?>
								</p>
								
								<p>
										<label for="simple-required">Dealer Name<?php echo REQUIRED; ?></label> 
										<?php echo $this->Form->input('UserProfile.first_name',array('class'=>'full-width'));?>
								</p>
								
								<p>
										<label for="simple-required">Password<?php echo REQUIRED; ?></label> 
										<?php echo $this->Form->input('powd',array('type'=>'password','class'=>'full-width','value'=>''));?>
								</p>
								<p>
										<label for="simple-required">Confirm Password<?php echo REQUIRED; ?></label> 
										<?php echo $this->Form->input('confirm_password',array('type'=>'password','class'=>'full-width','value'=>''));?>
								</p>
								<p>
										<label for="simple-required">Phone No<?php echo REQUIRED; ?></label> 
										<?php echo $this->Form->input('UserProfile.phone_no',array('class'=>'full-width'));?>
								</p>
								<p>
										<label for="simple-action">Select action</label>
										<?php											$options=array('1'=>'Save and publish','0'=> 'Save only');					
										echo $this->Form->input('is_active',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$options,'class'=>'full-width'));
										?>
								</p>
								<p id="set_user_permission">
										<label for="simple-required">User Permissions(Select role first)<?php echo REQUIRED; ?></label> 
										
								</p>
								
								<p>
										<span class="submit_button_p">
										<?php
										echo $this->Html->link( 'Cancel' , array( 'controller' => 'users' , 'action' => 'user_listing' , 'admin' => true ),array('class' => 'cancle_button'));
										echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
										echo $this->Form->submit('Submit',array('div'=>FALSE,'onclick' => 'return ajax_form("add_user","admin/users/validate_add_user_ajax","receiver")','class'=>'submit_button'));
										?>
										</span>
								</p>
						</fieldset>
						<?php echo $this->Form->end();?>
				</div>
		</section>
		<div class="clear"></div>
</article>
<script type="text/javascript">
$(document).ready(function(){
		$('.parent_permission').live("click",function(){  
				if($(this).is(':checked')){
				    $(this).parent('p').parent('div').find(".child_permission").attr('checked','checked'); 
				}else{
				    $(this).parent('p').parent('div').find(".child_permission").removeAttr('checked');
				}
		});
});
</script>