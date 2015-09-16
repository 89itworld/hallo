<?php echo $this->Html->script("admin/admin.js");?>
<section id="message">
<div class="block-border"><div class="block-content no-title dark-bg">
	<!--<p class="mini-infos">For demo website, use <b>admin</b> / <b>admin</b></p>-->
</div></div>
</section>

<section id="login-block">
<div class="block-border"><div class="block-content">
		
	<h1><?php echo RESET_PASSWORD; ?></h1>
	
	<?php
		echo $this->element('backend/server_error');
		echo $this->Session->flash();
		echo $this->Form->create('User',array('class' => 'form with-margin','name'=> 'login-form',"id"=>"reset-password-form",'url' => array('controller'=>'users','action'=>'reset_password',$user_id,$passcode,'admin'=>true))); 
		
		?>
		<p class="inline-small-label">
			<label for="login"><span class="big">Password<?php echo REQUIRED;?></span></label>
			<?php echo $this->Form->input('powd',array('type'=>'password','class'=>'full-width','label' =>FALSE,'div'=>FALSE));?>
		</p>
		<p class="inline-small-label">
			<label for="pass"><span class="big">Confirm password<?php echo REQUIRED;?></span></label>
			<?php echo $this->Form->input('confirm_password',array('type'=>'password','class'=>'full-width required','label' =>FALSE,'div'=>FALSE));?>
		</p>
		<?php echo $this->Form->button('Save',array('type'=>'submit','class'=>'float-right','div'=>FALSE,'onclick' => 'return ajax_form("reset-password-form","admin/users/validate_reset_password_ajax","receiver")')); ?>
		<p class="input-height"></p>
		
		<?php echo $this->Form->end(); ?> 
		
		
</div></div>
</section>
