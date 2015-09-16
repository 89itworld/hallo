<?php 
class User extends AppModel {
	
	var $name = 'User';
	var $hasOne = array('UserProfile' =>array('className' =>'UserProfile','dependent'=>true));
	var $hasMany = array("UserPermission"=>array("className"=>"UserPermission"));
	var $errMsg = array();
	var $actsAs = array("Containable");
	
	/*
	* Function for save data in user
	*/
	
	function save_user($data = null){
		$user_id ="";
		if(isset($data['User']['parent_id']) && ($data['User']['parent_id'] == "")){
			$data['User']['parent_id'] = 0;
		}
		if(isset($data['User']['is_dealer']) && ($data['User']['is_dealer'] == "")){
			$data['User']['is_dealer'] = 0;
		}
		if(isset($data['User']['role_id']) && ($data['User']['role_id'] == 2)){//|| ($data['User']['role_id'] == 3)
		}else{unset($data['User']['selling_price_limit']);
		}
		
		if(!empty($data['User']['powd'])){
			$data['User']['powd'] = AuthComponent::password(AuthComponent::password($data['User']['powd']));
		}
		if(isset($data['User']['id']) && trim($data['User']['powd'] == "")){
			unset($data['User']['powd']);
		}
		
		if($this->saveAll($data)){
			if(isset($data['User']['id']) &&  (!empty($data['User']['id']))){
				$user_id = $data['User']['id'];
			}else{
				$user_id = $this->getLastInsertId();
			}
		}
		return $user_id;
	}
	
	/*
	* Function for validate add user
	*/
	
	function validate_add_user($postArray = null){
		
		$this->_check($postArray['User']);
		if(trim($postArray['UserProfile']['first_name']) == ""){
			$this->errMsg['first_name'][] = ERR_FIRST_NAME_EMPTY."\n";
		}/*elseif(preg_match(ONLY_LETTER_DENISH,trim($postArray['UserProfile']['first_name']))){
			$this->errMsg['first_name'][] = ERR_VALID_FIRST_NAME."\n";
		}*/
		
		if(trim($postArray['UserProfile']['phone_no']) == ""){
			$this->errMsg['phone_no'][] = ERR_PHONE_NUMBER_EMPTY."\n";
		}
		if(isset($postArray['UserPermission'])){
			if(count($postArray['UserPermission'])== 0){
				$this->errMsg['UserPermission'][] = ERR_USER_PERMISSION."\n";
			}
		}elseif(!isset($postArray['UserPermission'])){
			$this->errMsg['UserPermission'][] = ERR_USER_PERMISSION."\n";
		}
		
		return $this->errMsg;
	}
	
	function _check($post_array = null){
		if(isset($post_array['role_id'])){
			
			App::import("Model","Role");
			$this->Role = new Role();
			$role_data  = $this->Role->find('list' , array( 'conditions' => array('Role.is_super_admin <>' => '1','Role.is_active'=>'1','is_deleted'=>'0') , 'recursive' => -1 ) );
			
			if($post_array['role_id'] == ""){
				$this->errMsg['role_id'][] = ERR_SELECT_ROLE_EMPTY."\n";
			}elseif(!array_key_exists($post_array['role_id'],$role_data)){
				$this->errMsg['role_id'][] = ERR_VALID_ROLE_ID."\n";
			}elseif(trim($post_array['role_id']) == 3){
				$user_data = $this->find("list",array("conditions"=>array("User.is_deleted"=>"0","User.is_active"=>"1","User.role_id"=>"2"),"fields"=>array("User.id","User.id")));
				if(trim($post_array['parent_id']) == ""){
					$this->errMsg['parent_id'][] = ERR_SELECT_DEALER_EMPTY."\n";
				}elseif(!array_key_exists($post_array['parent_id'],$user_data)){
					$this->errMsg['parent_id'][] = ERR_VALID_DEALER_ID."\n";
				}
			}
			if(($post_array['role_id'] == "2")){ //|| ($post_array['role_id'] == "3")
				if(trim($post_array['selling_limit']) == ""){
					$this->errMsg['selling_limit'][] = ERR_SELECT_SELECT_LIMIT_EMPTY."\n";
				}
				if(trim($post_array['selling_price_limit']) == ""){
					$this->errMsg['selling_price_limit'][] = ERR_SELLING_PRICE_LIMIT_EMPTY."\n";
				}elseif(!is_numeric($post_array['selling_price_limit']) || ($post_array['selling_price_limit'] <= 0)){
				    $this->errMsg['selling_price_limit'][] = ERR_INVALID_SELLING_PRICE_LIMIT."\n";
				}
				
			}
		}
		
		$u_id = "";
		if(isset($post_array['id']) && (!empty($post_array['id']))){
			$u_id = $post_array['id'];
		}
		
		if(trim($post_array['u_id']) == ""){
			$this->errMsg['u_id'][] = ERR_DEALERID_EMPTY."\n";
		}elseif(!($this->__is_userid_valid($post_array['u_id'],$u_id))){
			$this->errMsg['u_id'][] = LBL_DEALERID_ALREADY_EXIST."\n";
		}
				
		
		if(isset($post_array['email'])){
			if(trim($post_array['email']) == ""){
				$this->errMsg['email'][] = ERR_EMAIL_EMPTY."\n";
			}elseif(preg_match(REGULAR_EXPRESSION_EMAIL,trim($post_array['email'])) != 1){
				$this->errMsg['email'][] = ERR_EMAIL_VALID."\n";
			}elseif(!($this->__is_email_valid($post_array['email'],$u_id))){
				$this->errMsg['email'][] = LBL_EMAIL_ALREADY_EXIST."\n";
			}
		}
        
		if(!empty($post_array['id']) && trim($post_array['powd']) != ""){
			if(trim($post_array['confirm_password']) == ""){
			    $this->errMsg['confirm_password'][] = ERR_CONFIRM_PASSWORD_EMPTY."\n";
			}
			if(trim($post_array['powd']) != trim($post_array['confirm_password'])){
			    $this->errMsg['powd'][] = ERR_PASSWORD_NOT_MATCH."\n";
			}
		}elseif(!isset($post_array['id'])){
			if(trim($post_array['powd']) == ""){
			    $this->errMsg['powd'][] = ERR_PASSWORD_EMPTY."\n";
			}
			if(trim($post_array['confirm_password']) == ""){
			    $this->errMsg['confirm_password'][] = ERR_CONFIRM_PASSWORD_EMPTY."\n";
			}
			if(trim($post_array['powd']) != trim($post_array['confirm_password'])){
			    $this->errMsg['powd'][] = ERR_PASSWORD_NOT_MATCH."\n";
			}
		}
	}
	
	/*
	* Function for check validity of user id
	*/
	
	function __is_userid_valid($d_id = null,$user_id = null){
		
		if($user_id == null){
			$data = $this->find("count",array("conditions"=>array("User.u_id"=>$d_id,"User.is_deleted"=>"0")));
			if($data == 0){
				return true;
			}
		}else{
			$data = $this->find("count",array("conditions"=>array("User.u_id"=>$d_id,'User.id'=>$user_id,"User.is_deleted"=>"0")));
			
			if($data == 1){
				return true;
			}elseif($data == 0){
				$data = $this->find("count",array("conditions"=>array("User.u_id"=>$d_id,'User.id !='=>$user_id,"User.is_deleted"=>"0")));
				if($data != 0){
					return false;
				}else{
					return true;
				}
			}	
		}
		return false;
	}
	
	/*To Check uniqueness of email*/
	function __is_email_valid($uname = null,$user_id = null){
		
		if($user_id == null){
			$data = $this->find("count",array("conditions"=>array("User.email"=>$uname,"User.is_deleted"=>"0")));
			if($data == 0){
				return true;
			}
		}else{
			$data = $this->find("count",array("conditions"=>array("User.email"=>$uname,'User.id'=>$user_id)));
			if($data == 1){
			    return true;
			}else{
			    $data = $this->find("count",array("conditions"=>array("User.email"=>$uname,'User.id !='=>$user_id,'User.is_deleted'=>0)));
			    if($data == 0){
				return true;
			    }
			}
		    }
		return false;
	}
	
	/*Function for check link*/
	function valid_password_code($user_id = null, $passcode =null){
		
		$data = $this->find('first', array("conditions" =>array('User.activation_key'=>$passcode,'User.id'=>$user_id)));
		if($data){
			return $data;
		}
		return false;
	}
	
	/*
	*Function for validate password
	*/
	
	function valid_match_password($postArray = null){
		
		if(trim($postArray['powd']) == ""){
		    $this->errMsg['powd'][] = ERR_PASSWORD_EMPTY."\n";
		}
		if(trim($postArray['confirm_password']) == ""){
		    $this->errMsg['confirm_password'][] = ERR_CONFIRM_PASSWORD_EMPTY."\n";
		}
		if(trim($postArray['powd']) != trim($postArray['confirm_password'])){
		   $this->errMsg['powd'][] = ERR_PASSWORD_NOT_MATCH."\n";
		}
		return $this->errMsg;   
	}
	
	/*
	* Overwrite paginate function for dealer reporting
	*/
	
	function paginate($condition, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		
		$join = isset($extra['joins'])?$extra['joins']:array();
		if(empty($order)){
		    $order = array($extra['passit']['sort'] => $extra['passit']['direction']);
		}
		$group = isset($extra['group'])?$extra['group']:"";
		return $this->find('all', array('recursive'=>$recursive,"fields"=>$fields,"joins"=>$join,'conditions'=>$condition,"limit"=>$limit,'group' => $group,'order'=>$order,'page'=>$page));
	
	}
	
}
?>
