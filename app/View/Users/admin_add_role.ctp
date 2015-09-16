<!-- Content -->
<article class="container_12">
		<?php //echo $this->element('backend/admin_leftMenu');?>
		<section class="grid_12">
				<div class="block-border">
						<?php echo $this->element('backend/server_error');
						echo $this->Form->create('Role',array('url'=>array('controller'=>'users','action' => 'add_role', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),'id'=>'add_role','name' =>'add_role','class' => 'block-content form')); ?>
						<h1><?php echo ADD_ROLE; ?></h1>
						<fieldset>
								<?php echo $this->Session->flash(); ?>
								<p>
										<label for="simple-required"> Role name<?php echo REQUIRED; ?></label> 
										<?php echo $this->Form->input('title',array('class'=>'full-width'));?>
								</p>
								<p>
										<label for="simple-required">Description</label> 
										<?php echo $this->Form->input('description',array('type'=>'textarea',"rows"=>3,'class'=>'full-width'));?>
								</p>
								
								<p>
										<label for="simple-action">Select action</label>
										<?php											$options=array('1'=>'Save and publish','0'=> 'Save only');					
										echo $this->Form->input('is_active',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$options,'class'=>'full-width'));
										?>
								</p>
								
								<p>
										<label for="simple-required">User Permissions<?php echo REQUIRED; ?></label> 
										<?php
										$permissions_list = array();
										if(isset($permissions)){
												foreach($permissions as $v){?>
												<div style="margin-top:15px;">
												<p>
														<?php echo $this->Form->input("RolePermission.permission_id][",array("type"=>"checkbox","value"=>$v['Permission']['id'],"hiddenField"=>false,'checked'=>"checked","class"=>"parent_permission"));
												?>
												&nbsp;		
												<label for="simple-checkbox-1"><?php echo $v['Permission']['title']?></label> 
												</p>
												<?php foreach($v['SubPermission'] as $v1){?>
												<p>
														<?php echo $this->Form->input("RolePermission.permission_id][",array("type"=>"checkbox","value"=>$v1['id'],"hiddenField"=>false,'checked'=>"checked","style"=>"margin-left:20px;","class"=>"child_permission"));
												?>
												&nbsp;		
												<label for="simple-checkbox-1"><?php echo $v1['title']?></label> 
												</p>
												
										<?php } ?></div><?php }} ?>
								</p>
								
								
								<p>
										<span class="submit_button_p">
										<?php
										echo $this->Html->link( 'Cancel' , array( 'controller' => 'users' , 'action' => 'role_listing' , 'admin' => true ),array('class' => 'cancle_button'));
										echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
										echo $this->Form->submit('Submit',array('div'=>FALSE,'onclick' => 'return ajax_form("add_role","admin/users/validate_add_role_ajax","receiver")','class'=>'submit_button'));
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