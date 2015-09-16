<div class="block-border">
	<div class="block-content">
		<h1>Control Panel</h1>
		<?php echo $this->Session->flash(); ?>
		<h3>General options</h3>
		<ul class="shortcuts-list">
			<li>						
				<?php echo $this->Html->link($this->Html->image('/images/icons/web-app/48/Profile.png').'My profile',array("controller"=>"dashboards","action"=>"my_profile",ENCRYPT_DATA($this->Session->read("Auth.User.id"))),array('escape' => false,'title'=>'My Profile'));?> 
			
			</li>
		</ul>
		<?php 	$user_role = $this->Session->read('Auth.User.role_id');
			if($user_role == 1){
				echo $this->element('backend/graph'); 
			}
		?>
	</div>
			
</div>