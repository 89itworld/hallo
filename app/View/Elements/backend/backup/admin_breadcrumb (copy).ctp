
<?php
$parmissions = $this->Session->read('UserPermission');
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
					echo "<ul id='breadcrumb'><li>".$this->Html->link(ucfirst($v['Permission']['title']),array("controller"=>$v['Permission']['controller'],"action"=>$v['Permission']['action']),array("escape"=>false))."</li>";
					echo "<li>".ucfirst($s['title'])."</li></ul>";
					break;
				}
			}
		}
	}
}}
?>
