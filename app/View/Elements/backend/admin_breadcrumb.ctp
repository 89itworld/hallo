<style>
	.sub_mainu_b{margin-right:10px;}
</style>
<?php 
$parmission_add_user=$parmissions = $this->Session->read('UserPermission');
if(!empty($parmissions)){
$current_controller = $this->params['controller'];
$current_action = $this->params['action'];
foreach($parmissions as $v){
	if(!empty($v['SubPermission'])){
		if($current_controller == trim($v['Permission']['controller']) && $current_action == trim($v['Permission']['action'])){
			$parent_id = ($v['Permission']['parent_id'] == 0)?$v['Permission']['id']:$v['Permission']['parent_id'];
		}
		if(!empty($v['SubPermission'])){
			foreach($v['SubPermission'] as $s){
				if($s['controller'] == $current_controller && $s['action'] == $current_action && $v['Permission']['id'] == $s['parent_id']){
					if(($s['controller'] == "coupons") && ($s['action'] == "admin_list")){
						echo "<ul class='keywords' style='float:left;margin:10px;'>";
						
						foreach($parmission_add_user as $k4=>$v4){
							foreach($v4['SubPermission'] as $k5=>$v5){
								
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_upload_coupon_csv")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "batches") && ($v5['action'] == "admin_listing")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_coupon_pull")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_coupon_pull_history")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
							    
							}
						}
						echo "</ul>";
					}elseif(($s['controller'] == "coupons") && ($s['action'] == "admin_upload_coupon_csv")){
						echo "<ul class='keywords' style='float:left;margin:10px;'>";
						foreach($parmission_add_user as $k4=>$v4){
							foreach($v4['SubPermission'] as $k5=>$v5){
								
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_upload_coupon_csv")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "batches") && ($v5['action'] == "admin_listing")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_coupon_pull")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_coupon_pull_history")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
							    
							}
						}
						echo "</ul>";
					}elseif(($s['controller'] == "batches") && ($s['action'] == "admin_listing")){
						echo "<ul class='keywords' style='float:left;margin:10px;'>";
						foreach($parmission_add_user as $k4=>$v4){
							foreach($v4['SubPermission'] as $k5=>$v5){
								
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_upload_coupon_csv")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "batches") && ($v5['action'] == "admin_listing")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_coupon_pull")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_coupon_pull_history")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
							    
							}
						}
						echo "</ul>";
					}elseif(($s['controller'] == "coupons") && ($s['action'] == "admin_coupon_pull")){
						echo "<ul class='keywords' style='float:left;margin:10px;'>";
						foreach($parmission_add_user as $k4=>$v4){
							foreach($v4['SubPermission'] as $k5=>$v5){
								
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_upload_coupon_csv")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "batches") && ($v5['action'] == "admin_listing")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_coupon_pull")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_coupon_pull_history")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
							    
							}
						}
						echo "</ul>";
					}elseif(($s['controller'] == "coupons") && ($s['action'] == "admin_coupon_pull_history")){
						echo "<ul class='keywords' style='float:left;margin:10px;'>";
						foreach($parmission_add_user as $k4=>$v4){
							foreach($v4['SubPermission'] as $k5=>$v5){
								
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_upload_coupon_csv")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "batches") && ($v5['action'] == "admin_listing")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_coupon_pull")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
								if(($v5['controller'] == "coupons") && ($v5['action'] == "admin_coupon_pull_history")){
								echo "<li class='sub_mainu_b'>".$this->Html->link(ucfirst($v5['title']),array("controller"=>$v5['controller'],"action"=>$v5['action']),array("escape"=>false))."</li>"  ; 
								
								}
							    
							}
						}
						echo "</ul>";
					}else{
						echo "<ul id='breadcrumb'><li>".$this->Html->link(ucfirst($v['Permission']['title']),array("controller"=>$v['Permission']['controller'],"action"=>$v['Permission']['action']),array("escape"=>false))."</li>";
						echo "<li>".ucfirst($s['title'])."</li></ul>";
						break;	
					}
					
				}
			}
		}
	}
}}
?>


