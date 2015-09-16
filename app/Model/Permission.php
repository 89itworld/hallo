<?php
class Permission extends AppModel{
    
    var $name = 'Permission';
    
    var $hasMany = array("SubPermission"=>array("className"=>"Permission","foreignKey"=>"parent_id",'conditions'=>array("is_active"=>1,"is_deleted"=>0),"fields"=>array("id","title","controller","action","show_as_link")));
    
    /*
    * Function for find all permessions
    */
    function get_all_permissions(){
        $permissions = $this->find('all',array('conditions'=>array('Permission.is_deleted'=>0,"Permission.is_active"=>1,'is_main_action'=>'1'),'fields'=>array('Permission.id','Permission.title')));
        return $permissions;
    }
    
    /*
    * Function for find perticular role permessions
    */
    
    function get_user_permissions($role_id = null){
        
        $role_permission_join = array("table"=>"role_permissions","alias"=>"RolePermission","type"=>"inner","foreignKey"=>false,"conditions"=>array("RolePermission.permission_id = Permission.id","RolePermission.role_id"=>$role_id,"RolePermission.is_active"=>"1","RolePermission.is_deleted"=>"0"));
		
        $permissions = $this->find('all',array('conditions'=>array('Permission.is_deleted'=>0,"Permission.is_active"=>1),'fields'=>array('Permission.id','Permission.title','Permission.parent_id','Permission.is_main_action'),'joins'=>array($role_permission_join),"recursive"=>-1));
        
        $new_permissions = $permissions;
        $new_arr = array();
        $j = 0;
        foreach($permissions as $k=>$v){
            if($v['Permission']['is_main_action'] == '1'){
                $new_arr[$j]['Permission']['id'] = $v['Permission']['id'];
                $new_arr[$j]['Permission']['title'] = $v['Permission']['title'];
            }
            $i = 0;
            foreach($new_permissions as $k1=>$v1){
                if($v1['Permission']['parent_id'] == $v['Permission']['id'] && ($v1['Permission']['is_main_action'] != "1")){
                        $new_arr[$j]['SubPermission'][$i]['id'] = $v1['Permission']['id'];
                        $new_arr[$j]['SubPermission'][$i]['title'] = $v1['Permission']['title'];
                    $i++;
                }
            }
            $j++;
        }
	if($role_id == '3'){
	    foreach($permissions as $K2=>$v2){
		if($v2['Permission']['id'] == '2'){
		    break;
		}else{
		    if($v2['Permission']['id'] == '9'){
			$new_arr[$j]['Permission']['id'] = $v2['Permission']['id'];
			$new_arr[$j]['Permission']['title'] = $v2['Permission']['title'];
			break;
		    }
		}
	    }
	}
	
	return $new_arr;
    }
    
}
?>