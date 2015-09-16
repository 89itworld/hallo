
<label for="simple-required">User Permissions<?php echo REQUIRED; ?></label>

<?php

if(isset($permissions) && (!empty($permissions))){
		foreach($permissions as $v){?>
		<div style="margin-top:15px;">
		<p>
				<?php echo $this->Form->input("UserPermission.permission_id][",array("type"=>"checkbox","value"=>$v['Permission']['id'],"hiddenField"=>false,'checked'=>"checked",'label'=>false,'div'=>false,"class"=>"parent_permission"));
		?>
		&nbsp;		
		<label for="simple-checkbox-1"><?php echo $v['Permission']['title']?></label> 
		</p>
		<?php if(!empty($v['SubPermission'])){foreach($v['SubPermission'] as $v1){?>
		<p>
				<?php echo $this->Form->input("UserPermission.permission_id][",array("type"=>"checkbox","value"=>$v1['id'],"hiddenField"=>false,'label'=>false,'div'=>false,'checked'=>"checked","style"=>"margin-left:20px;","class"=>"child_permission"));
		?>
		&nbsp;		
		<label for="simple-checkbox-1"><?php echo $v1['title']?></label> 
		</p>
		
<?php }}?></div><?php
}}else{ echo "<span style='color:#f00;padding-left:20px;'>".NO_PERMISSION."</span>";} ?>
