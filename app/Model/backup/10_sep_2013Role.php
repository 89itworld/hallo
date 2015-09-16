<?php
class Role extends AppModel{
    
    var $name = 'Role';
    var $hasMany = array("RolePermission"=>array("className"=>"RolePermission"));
    var $errMsg=array();
    
    function validate_add_role($post_array=null){
        
	$role_id = "";
	if((isset($post_array['Role']['id'])) && ($post_array['Role']['id'] != "")){
	    $role_id = $post_array['Role']['id'];
	}
	if(trim($post_array['Role']['title']) == ""){
	    $this->errMsg['title'][] = ERR_ROLE_EMPTY."\n";
	}elseif(!$this->__is_role_valid($post_array['Role']['title'],$role_id)){
	    $this->errMsg['title'][] = ERR_ROLE_INVALID."\n";
	}
        if(count($post_array['RolePermission'])== 0){
            $this->errMsg['RolePermission'][] = ERR_ROLE_PERMISSION."\n";
        } 
        return $this->errMsg;
       
    }
    
    function __is_role_valid($role_name= null,$role_id = null){
        
        if($role_id == null){
            $data = $this->find("count",array("conditions"=>array("Role.title"=>$role_name,'Role.is_deleted'=>0)));
            if($data == 0){
                return true;
            }
        }else{
            $data = $this->find("count",array("conditions"=>array("Role.title"=>$role_name,'Role.id'=>$role_id)));
            if($data == 1){
                return true;
            }else{
		$data = $this->find("count",array("conditions"=>array("Role.title"=>$role_name,'Role.id !='=>$role_id,'Role.is_deleted'=>0)));
		if($data == 0){
		    return true;
		}
	    }
        }
	return false;
    }
}
?>
