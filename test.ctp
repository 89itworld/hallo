<style>

.class_common_arrow > a{text-align: left;}
.current_new {background: -moz-linear-gradient(center top , white, #2BCEF3 5%, #057FDB) repeat scroll 0 0 transparent;border-color: #1EAFDC #1193D5 #035592;border-radius: 0.5em 0.5em 0.5em 0.5em;box-shadow: 0 0 0.25em rgba(0, 0, 0, 0.5);color: #FFFFFF;display: block;line-height: 1.333em; margin: -0.333em -0.25em;min-width: 1.083em;padding: 0.442em 0.6em !important; text-align: center;text-transform: uppercase;}

nav > ul > li > ul > li .menu > ul,nav > ul > li > ul > li .menu ul li:hover > ul {background-image:none !important;background-color: #1c1e20;border-color: #b3b3b3;}

.menu ul li {padding: 0.333em 0px 0.417em 0px !important;}

.menu ul li a {margin: -0.333em 0em -0.417em 4px !important;padding: 0.333em 0px 0.417em  2px !important;}


</style>

<nav id="main-nav">
		
<ul class="container_12">
		<li class="current_new current home"><?php echo $this->Html->link("Home","javascript:void(0);",array("escape"=>false,"title"=>"Home"));?>
		<ul>
				<?php
				$parmissions = $this->Session->read('UserPermission');
if(!empty($parmissions)){	
				$current_controller = $this->params['controller'];
				$current_action = $this->params['action'];
				$current_class = "";
				$class_with_menu = "";
				$margin_menu = "";				
				foreach($parmissions as $v){
						
						$current_class = "";
						if($current_controller == trim($v['Permission']['controller']) && $current_action == trim($v['Permission']['action'])){
								$parent_id = ($v['Permission']['parent_id'] == 0)?$v['Permission']['id']:$v['Permission']['parent_id'];
								$current_class = "current";
								$margin_menu = "margin:0px 0.6em 0px 0px;padding:0 20px 0 0 !important;";	
								
						}else{
							$margin_menu = "";		
						}
						if(!empty($v['SubPermission'])){
								$class_with_menu = "with-menu";
						}else{
								$class_with_menu = "";
						}
						
						if(!empty($v['SubPermission'])){
								foreach($v['SubPermission'] as $s){
										if($s['controller'] == $current_controller && $s['action'] == $current_action && $v['Permission']['id'] == $s['parent_id']){
											    $parent_id = $s['parent_id'];		
											    $current_class = "current";
											    $margin_menu = "margin:0px 0.6em 0px 0px;padding:0 20px 0 0 !important;";	
											    
											    $class_with_menu = "with-menu";
											    break;
										}
								}
						}
						
						if($v['Permission']['show_as_link'] == "Y"){
				?>
						<li style='<?php echo $margin_menu;?>' class='<?php echo $class_with_menu." ".$current_class;?>'><?php echo $this->Html->link(ucfirst($v['Permission']["title"]),array("controller"=>$v['Permission']["controller"],'action'=>$v['Permission']['action'],'admin'=>true))?>
		
				<?php          if(!empty($v['SubPermission'])){?>
						<div class="menu">
								<?php echo $this->Html->image('../images/menu-open-arrow.png',array("width"=>"16","height"=>"16","alt"=>"sub-menu","title"=>"sub-menu")); ?>
								<ul>
								<?php 
								foreach($v['SubPermission'] as $v1){
								if($v1['show_as_link'] == "Y"){
								?>
									<li class="class_common_arrow">
										<?php
										echo $this->Html->link(ucfirst($v1['title']),array("controller"=>$v1["controller"],"action"=>$v1["action"],"admin"=>true),array("escape"=>false));
										?>
									</li>	
								<?php }
								}
								?>
								</ul>
						</div>
				<?php		}?>
				</li>
				<?php
				}}
				} ?>
				
		</ul>
		</li> 
		
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

