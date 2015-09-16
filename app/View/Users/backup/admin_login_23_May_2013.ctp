<section id="message">
		<!--<div class="block-border">
				<div class="block-content no-title dark-bg">
					<p class="mini-infos">For demo website, use <b>admin</b> / <b>admin</b></p>
				</div>
		</div>-->
	</section>
	
	<section id="login-block">
		<div class="block-border"><div class="block-content">
				
			<h1>Admin</h1>
			<div class="block-header">Please login</div>
			<?php  echo $this->Session->flash();
				
				echo $this->Form->create('User',array('class' => 'form with-margin','name'=> 'login-form',"id"=>"login-form",'url' => array("controller"=>"users","action"=>"login","admin"=>true)));
				
				echo $this->Form->input('User.errorstatus',array('type'=>'hidden'));?>
				
				<p class="inline-small-label">
					<label for="login"><span class="big">Email<?php echo REQUIRED;?></span></label>
					<?php echo $this->Form->input('email',array('class'=>'full-width','label' =>FALSE,'div'=>FALSE));?>
				</p>
				<p class="inline-small-label">
					<label for="pass"><span class="big">Password<?php echo REQUIRED;?></span></label>
					<?php echo $this->Form->input('powd',array('type'=>'password','class'=>'full-width required','label' =>FALSE,'div'=>FALSE));?>
				</p>
				<?php echo $this->Form->button('Login',array('type'=>'submit','class'=>'float-right','div'=>FALSE));?> 
				<p class="input-height">
					<!--<input type="checkbox" name="keep-logged" id="keep-logged" value="1" class="mini-switch" checked="checked">
					<label for="keep-logged" class="inline">Keep me logged in</label>-->
				</p>
				<?php
				echo $this->Form->end();
				
				echo $this->Form->create('User',array('class' => 'form','name'=> 'forgot_password_form',"id"=>"password-recovery",'url' => array("controller"=>"users","action"=>"forgot_password","admin"=>true)));
				?>
				<fieldset class="grey-bg no-margin collapse">
					<legend><a href="#">Lost password?</a></legend>
					<p class="input-with-button">
						<label for="recovery-mail">Enter your e-mail address<?php echo REQUIRED;?></label>
						<?php echo $this->Form->input('email',array('type'=>'text',"id"=>"recovery-mail",'label' =>FALSE,'div'=>FALSE));
						echo $this->Form->button('Send',array('type'=>'submit','div'=>FALSE));?> 
					</p>
				</fieldset>
				</p>
				<?php echo $this->Form->end(); ?>
		</div></div>
	</section>
