<style>
.current{box-shadow:none !important;}

</style>
<nav id="main-nav">
		<ul class="container_12">
				<?php
				//$main_tab = array("1"=>"home","2"=>"users","3"=>"role","4"=>"vendor","5"=>"voucher","6"=>"product","7"=>"coupon","8"=>"couponsale","9"=>"stats");
				$main_tab = array("1"=>"home","2"=>"users","3"=>"comments","4"=>"medias","6"=>"settings","7"=>"write","8"=>"write","46"=>"stats","60"=>"backup","72"=>"pull");
				$main_tab_title = array("1"=>"Dashboard","2"=>"User Management","3"=>"Role Management","4"=>"Vendor Management","6"=>"Product Management","7"=>"Coupon Management","8"=>"Sale Coupon Management","46"=>"Report Management","60"=>"Settings","72"=>"Pull Coupons");
				$parmissions = $this->Session->read('UserPermission');
				
				
if(!empty($parmissions)){	
				$current_controller = $this->params['controller'];
				$current_action = $this->params['action'];
				$current_class = "";
				$list_current_class = "";
				$margin_menu = "";
				foreach($parmissions as $v){
						$current_class = "";
						if($current_controller == trim($v['Permission']['controller']) && $current_action == trim($v['Permission']['action'])){
								$parent_id = ($v['Permission']['parent_id'] == 0)?$v['Permission']['id']:$v['Permission']['parent_id'];
								$current_class = "current";
								$list_current_class = "current";
								$margin_menu = "margin:0px 0.6em 0px 0px !important;padding:0px !important;";
						}else{
								$margin_menu = "";
								$list_current_class ="";
						}
						
						if(!empty($v['SubPermission'])){
								foreach($v['SubPermission'] as $s){
										if($s['controller'] == $current_controller && $s['action'] == $current_action && $v['Permission']['id'] == $s['parent_id']){
											    $parent_id = $s['parent_id'];		
											    $current_class = "current";
											    $margin_menu = "margin:0px 0.6em 0px 0px!important;padding:0px !important;";
												break;
										}
								}
						}		
						$href = HTTP_HOST."admin/".$v['Permission']['controller']."/".str_replace("admin_","",$v['Permission']['action']);
				?>
						<li  class='<?php echo $current_class." ".$main_tab[$v['Permission']['id']];?>' title='<?php echo $main_tab_title[$v['Permission']['id']];?>' onclick="window.location='<?php echo $href;?>'"> 
						<?php
						echo $this->Html->link("",array("controller"=>$v['Permission']['controller'],"action"=>$v['Permission']['action'],"admin"=>true),array("escape"=>false));
						//echo $this->Html->link("","javascript:void(0);",array("escape"=>false));
						if($v['Permission']['show_as_link'] == "Y"){
						?>
								<ul>
										<li class='<?php echo $list_current_class;?>' style='<?php echo $margin_menu;?>'>	<?php echo $this->Html->link(ucfirst($v['Permission']["title"]),array("controller"=>$v['Permission']["controller"],'action'=>$v['Permission']['action'],'admin'=>true))?>
										</li>
										<?php
										if(!empty($v['SubPermission'])){
										foreach($v['SubPermission'] as $v1){
										if($v1['show_as_link'] == "Y"){
										$sub_current_class = ($v1['controller'] == $current_controller && ($v1['action'] == $current_action))?"current":"";    
										?>
										<li class='<?php echo $margin_menu." ".$sub_current_class;?>' style='<?php echo $margin_menu;?>'>
										<?php
										echo $this->Html->link(ucfirst($v1['title']),array("controller"=>$v1["controller"],"action"=>$v1["action"],"admin"=>true),array("escape"=>false));
										?>
										</li>	
								<?php }}}?>
								</ul>
						<?php } ?>		
						</li>
				<?php }} ?>
				
		</ul>
</nav>

<div id="sub-nav">
		<div class="container_12">
			<?php /*
			<a href="#" title="Help" class="nav-button"><b>Help</b></a>
		
			<form id="search-form" name="search-form" method="post" action="search.html">
				<input type="text" name="s" id="s" value="" title="Search admin..." autocomplete="off">
			</form>
			*/ ?>
		
		</div>
</div>

