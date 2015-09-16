<?php
class UserProfile extends AppModel {
	
	var $name='UserProfile';
     
	var $belongsTo = array('User' => array('className' => 'User','foreignKey' => 'user_id')); 
	
	function find_dealer_name(){
		$dealer = $this->find("all",array("conditions"=>array("User.is_deleted"=>"0","User.is_active"=>"1","User.is_dealer"=>"1"),'fields'=>array("user_id","first_name","last_name"),'order'=>"UserProfile.first_name asc"));
		$dealer_array = array();
		if(!empty($dealer)){
			foreach($dealer as $k=>$v){
				$dealer_array[$v['UserProfile']['user_id']] = ucfirst($v['UserProfile']['first_name'])." ".ucfirst($v['UserProfile']['last_name']);
			}
		}
		return $dealer_array;
	}
}
?>
