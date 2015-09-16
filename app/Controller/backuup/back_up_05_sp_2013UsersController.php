<?php
/**
* Users Controller
*/
App::uses('AppController', 'Controller');
class UsersController extends AppController {
	 
	var $name = 'Users';
	var $components = array('Auth','Session','RequestHandler');
	var $helpers = array('Form', 'Html', 'Js','Common');
 	
	/******This function will executed bydefualt when run any funtion of this controller
	* 	Case : User alreadyLogin / CheckAuthantication
	*/
	
	function beforeFilter(){
		parent::beforeFilter();
		//$this->Auth->allow('admin_login');
	}
	
					
	/******This function used for admin login 
	* 	@layout => 'backend/adminlogin' 
	*/
	
	function admin_login(){
		
		$this->layout='backend/adminlogin';
		$this->set("title_for_layput",USER_LOGIN);
		if($this->request->is('post')) {	 
			if($this->data['User']['email'] == ''){
	    		 	$this->Session->setFlash(ERR_EMAIL_EMPTY,'message/yellow');
			}elseif($this->data['User']['powd'] == ''){
			 	$this->Session->setFlash(ERR_EMAIL_PASSWORD,'message/yellow');
			}else{
				$this->request->data['User']['powd'] = AuthComponent::password($this->request->data['User']['powd']);
				if($this->Auth->login()) {
					$last_login_ip = $this->RequestHandler->getClientIp();
					$id = $this->Session->read('Auth.User.id');
					$this->User->updateAll(array('User.last_login_ip'=>"'$last_login_ip'"),array('User.id'=>$id));
					if($this->Session->read('Auth.User')) {
						
						$active = ($this->Auth->user('is_active') == '1')?'1':'0';;
					 	$del = ($this->Auth->user('is_deleted') == '1')?'1':'0';
						
						if (($active == "1") && ($del == '0')) {
							//$this->redirect($this->Auth->redirect());
							if(($this->Session->read('Auth.User.role_id') == '2') || ($this->Session->read('Auth.User.role_id') == '3')){
								$this->redirect(array('controller' => 'coupons', 'action' => 'sale','admin'=>'true'));
							}else{
								$this->redirect(array('controller' => 'users', 'action' => 'overall_trends','admin'=>'true'));
							}				
				
						}else{
							$this->Session->destroy();
							$this->Session->setFlash(__(INVALID_USERNAME_PASSWORD));
						}
					}
			        } else {
					$this->Session->setFlash(__(INVALID_USERNAME_PASSWORD));
			        }			    	 
			}
		}else{
			if($this->Auth->login()) {
				$this->redirect($this->Auth->redirect());
			}      	
		}
	}
	
	/******This function used for admin logout 
	* 	After logout clear/destroy session and redirect to login page
	* 	@autoRender => flase 
	*/
	
	function admin_logout() {
		$this->autoRender=false; 
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}
	
	
	/*
	* Function for reset password
	*/
	
	function admin_forgot_password(){
		
		$this->layout = "";
		$this->autoRender = false;
		$this->set("title_for_layput",FORGOT_PASSWORD);
		
		if(!empty($this->data)){
			if(trim($this->data['User']['email'] == '')){
				$this->Session->setFlash(ERR_EMAIL_EMPTY,'message/yellow');
			}else{
				$email = array($this->data['User']['email']);
				$user_data = $this->User->find('first',array('conditions'=>array('User.email'=>$email),'fields'=>array('User.id')));
				if(!empty($user_data)){
					$id = $user_data['User']['id'];
					$activation_key = md5(microtime());
					$link = HTTP_HOST."admin/users/reset_password/$id/$activation_key";
					$this->User->updateAll(array('User.activation_key'=>"'$activation_key'"),array('User.id'=>$id));
					
					$this->Session->setFlash(CHECK_MAIL,'message/green');
					
					$this->send_email(array("{PASSLINK}"),array($link),"forgot-password",EMAIL_NOTIFICATION,$email,NOREPLY_EMAIL);
					
					$this->redirect(array('controller'=>'users','action'=>'login','admin'=>true));
					
				}else{
					$this->Session->setFlash(ERR_CORRECT_EMAIL,'message/yellow');	
					$this->redirect(array('controller'=>'users','action'=>'login','admin'=>true));
				}
			}
		}
		$this->redirect(array('controller'=>'users','action'=>'login','admin'=>true));
	}
	
	
	/*
	*Function for reset passwod 
	*/
	
	function admin_reset_password($user_id = null, $token = null){
		
		$this->layout='backend/adminlogin';
		$this->set("title_for_layput",RESET_PASSWORD);
		$checkpass = $this->User->valid_password_code($user_id,$token);
		if(!empty($checkpass)){
			$this->set("passcode",$token);
			$this->set("user_id",$user_id);
			if(isset($this->data) && (!empty($this->data))){
				$errors = $this->User->valid_match_password($this->data['User']);
				if(count($errors) == 0){
					$id  = $checkpass['User']['id'];
					$pword = AuthComponent::password(AuthComponent::password($this->data['User']['powd']));
					if($this->User->updateAll(array('User.activation_key'=>"''",'User.powd'=>"'$pword'"),array('User.id'=>$id))){
						$this->Session->setFlash(PASSWORD_CHANGE_SUCCESS,'message/green');
						$this->redirect(array('controller'=>'dashboards','action'=>'index','admin'=>true));exit();
					}
				}else{
					$this->set("errors",$errors);
				}
			}
		}else {
			$this->set("passcode","");
			$this->set("user_id","");
			$this->Session->setFlash(ERR_RESET_PASSWORD,'message/red');
		}
	}
	
	/*
	*Ajax Function for validate reset passwod 
	*/
	function admin_validate_reset_password_ajax(){
		
		$this->layout = "";
		$this->autoRender = false;
		if($this->RequestHandler->isAjax()){
			$errors_msg = null;
			$errors = $this->User->valid_match_password($this->data['User']);
			if ( is_array ( $this->data ) ){
				foreach ($this->data['User'] as $key => $value ){
					
					if( array_key_exists ( $key, $errors) ){
						foreach ( $errors [ $key ] as $k => $v ){
							$errors_msg .= "error|$key|$v";
						}	
					}else {
						$errors_msg .= "ok|$key\n";
					}
				}
			}
			echo $errors_msg;
			die;
		}	

	}
	
	/*
	* Function for add user
	*/
	
	function admin_add_user() {
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",ADD_USER);
		
		App::import("Model","Role");
		$this->Role = new Role();
		$role_data  = $this->Role->find('list' , array( 'conditions' => array('Role.is_super_admin <>' => '1','Role.is_active'=>'1','is_deleted'=>'0') , 'recursive' => -1 ) );
		$this->set('roles',$role_data);
		
		$dealer = $this->User->UserProfile->find_dealer_name();
		$this->set('dealer',$dealer);
		
		if(!empty($this->data)){
			/*** Then, to check if the data validates, use the validates method of the model, 
			* which will return true if it validates and false if it doesn�t:
	 		*/
			$errors = $this->User->validate_add_user($this->data,$role_data);
			if(count($errors) == 0){
				$user_id = $this->User->save_user($this->data);
				if($user_id != ""){
					App::import('Model','UserPermission');
					$this->UserPermission = new UserPermission();
					$permission_data['UserPermission']['user_id'] = $user_id;
					$this->UserPermission->deleteAll(array("user_id"=>$user_id));
					
					foreach($this->data['UserPermission']['permission_id'] as $permisson_id){
						$permission_data['UserPermission']['permission_id'] = $permisson_id;
						$this->UserPermission->create();
						$this->UserPermission->save($permission_data);
					}
					
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array('controller'=>'users','action'=>'user_listing',"admin"=>true));
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array('controller'=>'users','action'=>'add_user',"admin"=>true));
				}		 
			}else{
				$this->set("errors",$errors);
			}
				    
		}
		
	}
	
	/*
	* Function for edit user
	*/
	
	function admin_edit_user($user_id = null){
		
		$this->layout = "backend/backend";
		$this->set("title_for_layout",EDIT_USER);
		$user_id = DECRYPT_DATA($user_id);
		
		App::import("Model","Role");
		$this->Role = new Role();
		$role_data  = $this->Role->find('list' , array( 'conditions' => array('Role.is_super_admin <>' => '1','Role.is_active'=>'1','is_deleted'=>'0') , 'recursive' => -1 ) );
		$this->set('roles',$role_data);
		
		$dealer = $this->User->UserProfile->find_dealer_name();
		$this->set('dealer',$dealer);
		
		if(!empty($this->data)){
			$data = $this->data;
			$data['User']['id'] = DECRYPT_DATA($data['User']['id']);
			$data['UserProfile']['id'] = DECRYPT_DATA($data['UserProfile']['id']);
			$errors = $this->User->validate_add_user($data,$role_data);
			if(count($errors) == 0){
				
				$user_id = $this->User->save_user($data);
				if($user_id != ""){
					App::import('Model','UserPermission');
					$this->UserPermission = new UserPermission();
					$permission_data['UserPermission']['user_id'] = $user_id;
					$this->UserPermission->deleteAll(array("user_id"=>$user_id));
					
					foreach($this->data['UserPermission']['permission_id'] as $permisson_id){
						$permission_data['UserPermission']['permission_id'] = $permisson_id;
						$this->UserPermission->create();
						$this->UserPermission->save($permission_data);
					}
				
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array('controller'=>'users','action'=>'user_listing',"admin"=>true));
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array('controller'=>'users','action'=>'edit_user',$this->data['User']['id'],"admin"=>true));
				}		 
			}else{
				$this->set("errors",$errors);
			}
		}else if(isset($user_id)){
			if($this->is_id_exist($user_id,"User")){
				$this->User->id = $user_id;
				$data = $this->User->read();
				$data['User']['id'] = ENCRYPT_DATA($data['User']['id']);
				$data['UserProfile']['id'] = ENCRYPT_DATA($data['UserProfile']['id']);
				$this->data = $data;
				$this->admin_user_permission($this->data['User']['role_id']);
			}
			else{
				$this->Session->setFlash(NOT_FOUND_ERROR, 'message/red');
				$this->redirect(array('controller'=>'users','action'=>'role_listing','admin'=>true));exit();
			}
		}
	}
	
	/*
	* Ajax Function for validate add_edit_user
	*/
	
	function admin_validate_add_user_ajax(){
		
		$this->layout = "";
		$this->autoRender = false;
		if($this->RequestHandler->isAjax()){
			$errors_msg = null;
			if(!isset($this->data['UserPermission'])){
				$this->data['UserPermission'] = array();
			}
			$data = $this->data;
			
			if(isset($this->data['User']['id'])){
				$data['User']['id'] = DECRYPT_DATA($data['User']['id']);
			}
			App::import("Model","UserProfile");
			$this->UserProfile = new UserProfile();
			$errors = $this->User->validate_add_user($data);
			if(is_array($this->data)){
				foreach ($this->data['User'] as $key => $value ){
					if( array_key_exists ( $key, $errors) ){
						foreach ( $errors [ $key ] as $k => $v ){
							$errors_msg .= "error|$key|$v";
						}	
					}
					else {
						$errors_msg .= "ok|$key\n";
					}
				}
				foreach ($this->data['UserProfile'] as $key => $value ){
					if( array_key_exists ( $key, $errors) ){
						foreach ( $errors [ $key ] as $k => $v ){
							$errors_msg .= "error|$key|$v";
						}	
					}
					else {
						$errors_msg .= "ok|$key\n";
					}
				}
				if(array_key_exists("UserPermission",$errors)){
					$errors_msg .= "error|UserPermission|".$errors['UserPermission'][0];
				}
				
			}
			echo $errors_msg;
			exit();
		}	
	}

	
	
	/*
	* Function for user permission
	*/
	function admin_user_permission($r_id = null){
		$this->layoout = "";
		App::import("Model","Permission");
		$this->Permission = new Permission();
		$permissions = $this->Permission->get_user_permissions($r_id);
		$this->set('permissions',$permissions);
	}
	
	/*
	* Function for role listing
	*/
	
	function admin_user_listing(){
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",USER_LISTING);
		$conditions = array("User.is_deleted"=>0,"User.role_id !="=>"1");
		if(($this->Session->read("Auth.User.role_id") == "3")){
			$this->redirect(array("controller"=>"coupons","action"=>"sale","admin"=>"true"));exit();
		}
			
		if($this->Session->read("Auth.User.role_id") == "2"){
			$conditions = array_merge($conditions ,array("User.parent_id"=>$this->Session->read("Auth.User.id")));
		}
		App::import("Model","Role");
		$this->Role = new Role();

		$this->set('roles',$this->Role->find('list' , array( 'conditions' => array('Role.is_super_admin <>' => '1','Role.is_active'=>'1','is_deleted'=>'0') , 'recursive' => -1 ) ));
		
		$name = isset($this->params['url']['name'])?$this->params['url']['name']:(isset($this->params['named']['name'])?$this->params['named']['name']:"");
		if(trim($name) != ""){
			$conditions = array_merge($conditions ,array("UserProfile.first_name like"=>"%".trim($name)."%"));
		}		
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		if(trim($is_active) != ""){
			$conditions = array_merge($conditions ,array("User.is_active"=>$is_active));
		}
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
            
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(User.created,'%Y-%m-%d') >="=>trim($from)));
		}
		
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(User.created,'%Y-%m-%d') <="=>trim($to)));
		}
		$dealer_joins = array("table"=>"user_profiles","alias"=>"Dealer","type"=>"left","foreignKey"=>"false","conditions"=>array("Dealer.user_id = User.parent_id"));
		$sub_dealer_joins = array("table"=>"users","alias"=>"SubDealer","type"=>"left","foreignKey"=>"false","conditions"=>array("User.id = SubDealer.parent_id","SubDealer.is_deleted"=>"0"));
		$fields = array("User.id","User.u_id","User.is_active","User.email","User.parent_id","User.role_id","User.created","UserProfile.first_name","UserProfile.last_name","Dealer.first_name","Dealer.last_name","COUNT(SubDealer.id) AS total_subdealer");
		$this->paginate = array('recursive'=>'0',"fields"=>$fields,'limit'=>USER_LIMIT,'conditions'=>$conditions,'order'=>array("User.created"=>"desc"),"joins"=>array($dealer_joins,$sub_dealer_joins),"group"=>"User.id");
		$user_data = $this->paginate('User');
		$this->set('user_data',$user_data);
		
	}
	
	
	/*
	* Function for role listing
	*/
	
	function admin_role_listing(){
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",ROLE_LISTING);
		$conditions = array("Role.is_deleted"=>0,"Role.id !="=>'1');
		
		App::import("Model","Role");
		$this->Role = new Role();
		
		$title = isset($this->params['url']['title'])?$this->params['url']['title']:(isset($this->params['named']['title'])?$this->params['named']['title']:"");
		if(trim($title) != ""){
			$conditions = array_merge($conditions ,array("Role.title like"=>"%".trim($title)."%"));
		}		
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		if(trim($is_active) != ""){
			$conditions = array_merge($conditions ,array("Role.is_active"=>$is_active));
		}
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
            
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Role.created,'%Y-%m-%d') >="=>trim($from)));
		}
		
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Role.created,'%Y-%m-%d') <="=>trim($to)));
		}
		
		$this->paginate = array('limit'=>ROLE_LIMIT,'conditions'=>$conditions,'order'=>array("Role.created"=>"desc","recursive"=>-1));
		
		$this->set('role_data',$this->paginate('Role'));
		
	}
	
	/*
	* Function for Active/Deactive/Delete in bulk for user and role
	*/
	
	function admin_bulk_action(){
		
		$this->layout = "";
		$this->autoRender = false;
		if(isset($this->data['Model']['id']) && count($this->data['Model']['id'])>0 && isset($this->data['Model']['action'])){
			
			$infected_records = array();
			foreach($this->data['Model']['id'] as $ids){
				$infected_records[] = DECRYPT_DATA($ids);
			}
			$model_name = $this->data['Model']['model_name'];
			$action = $this->data['Model']['action'];
			App::import("Model",$model_name);
			$this->$model_name = new $model_name();
			if($action == 0){//Activate Status
			    $this->$model_name->updateAll(array("$model_name.is_active"=>"'1'"),array("$model_name.id"=>$infected_records));
			    $this->Session->setFlash(RECORD_ACTIVATED, 'message/green');
			}
			else if($action == 1){//Inactivate Status
			    $this->$model_name->updateAll(array("$model_name.is_active"=>"'0'"),array("$model_name.id"=>$infected_records));
			    $this->Session->setFlash(RECORD_DEACTIVATED, 'message/green');
			}else if($action == 2){//Delete selected records
			    $this->$model_name->updateAll(array("$model_name.is_deleted"=>"'1'"),array("$model_name.id"=>$infected_records));
			    $this->Session->setFlash(RECORD_DELETED, 'message/green');
			}
			$this->redirect($this->referer());exit();	
		}
			
	}
	
	/*
	* Function for Active/Deactive not in bulk for user and role
	*/
	
	function admin_activate_bulk($is_active = null,$cat_id = null,$model_name = null){
		
		$this->layout = "";
		$this->autoRender = false;
		$cat_id = DECRYPT_DATA($cat_id);
		if(isset($is_active) && isset($cat_id) && isset($model_name)){
			App::import("Model",$model_name);
			$this->$model_name = new $model_name();
			
			if($is_active == 0){//Activate Status
			    $this->$model_name->updateAll(array("$model_name.is_active"=>"'1'"),array("$model_name.id"=>$cat_id));
			    $this->Session->setFlash(RECORD_ACTIVATED, 'message/green');
			}else if($is_active == 1){//Inactivate Status
			    $this->$model_name->updateAll(array("$model_name.is_active"=>"'0'"),array("$model_name.id"=>$cat_id));
			    $this->Session->setFlash(RECORD_DEACTIVATED, 'message/green');
			}
		}
		$this->redirect($this->referer());exit();	
	}
	
	/*
	* Function for delete user
	*/
	
	function admin_delete_bulk($coupon_id = null,$model_name = null){
		
		$this->layout = "";
		$this->autoRender = false;  
		$id = DECRYPT_DATA($coupon_id);
		if(isset($coupon_id)){
			App::import("Model",$model_name);
			$this->$model_name = new $model_name();
			if($model_name == "User"){
				//$find_all_ids = $this->User->field("id",array("User.parent_id"=>$id));
				$find_all_ids = $this->User->find("all",array("recursive"=>-1,"conditions"=>array("User.parent_id"=>$id,"User.is_deleted"=>"0"),"fields"=>array("User.id")));
				if(!empty($find_all_ids)){
					$all_ids = array();
					foreach($find_all_ids as $k=>$v){
						$all_ids[] =  $v['User']['id'];
					}
					$this->$model_name->updateAll(array("$model_name.is_deleted"=>"'1'"),array("$model_name.id"=>$all_ids));
					
				}
				$this->$model_name->updateAll(array("$model_name.is_deleted"=>"'1'"),array("$model_name.id"=>$id));
			}else{
				$this->$model_name->updateAll(array("$model_name.is_deleted"=>"'1'"),array("$model_name.id"=>$id));
			}
			
			$this->Session->setFlash(RECORD_DELETED, 'message/green');
		}
		$this->redirect($this->referer());exit();	
	}
	
	/*
	* Function for add role
	*/
	
	function admin_add_role(){
		
		$this->layout = "backend/backend";
		$this->set("title_for_layout",ADD_ROLE);
		
		App::import("Model","Permission");
		$this->Permission = new Permission();
		$permissions = $this->Permission->get_all_permissions();
		$this->set('permissions',$permissions);
		
		App::import("Model","Role");
		$this->Role = new Role();
		
		if(!empty($this->data)){
			
			$errors = $this->Role->validate_add_role($this->data);
			if(count($errors) == 0){
				if($this->Role->save($this->data)){
					$role_id = $this->Role->getLastInsertId();
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
				}
				App::import('Model','RolePermission');
				$this->RolePermission = new RolePermission();
				$permission_data['RolePermission']['role_id'] = $role_id;
				$this->RolePermission->deleteAll(array("role_id"=>$role_id));
				
				foreach($this->data['RolePermission']['permission_id'] as $permisson_id){
					$permission_data['RolePermission']['permission_id'] = $permisson_id;
					$this->RolePermission->create();
					$this->RolePermission->save($permission_data);
				}
				$this->redirect(array("controller"=>'users',"action"=>"role_listing",'admin'=>true));exit();
			}else{
				$this->set("errors",$errors);
			}
		}
	}
	
	/*
	* Function for edit role
	*/
	
	function admin_edit_role($role_id = null){
		
		$this->layout = "backend/backend";
		$this->set("title_for_layout",EDIT_ROLE);
		$role_id = DECRYPT_DATA($role_id);
		App::import("Model","Permission");
		$this->Permission = new Permission();
		$permissions = $this->Permission->get_all_permissions();
		$this->set('permissions',$permissions);
		
		App::import("Model","Role");
		$this->Role = new Role();
		
		if(!empty($this->data)){
			
			$data = $this->data;
			$data['Role']['id'] = DECRYPT_DATA($data['Role']['id']);
			$errors = $this->Role->validate_add_role($data);
			
			if(count($errors) == 0){
				if($this->Role->save($data)){
					$role_id = $data['Role']['id'];
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
				}
				
				App::import('Model','RolePermission');
				$this->RolePermission = new RolePermission();
				$permission_data['RolePermission']['role_id'] = $role_id;
				$this->RolePermission->deleteAll(array("role_id"=>$role_id));
				foreach($this->data['RolePermission']['permission_id'] as $permisson_id){
					$permission_data['RolePermission']['permission_id'] = $permisson_id;
					$this->RolePermission->create();
					$this->RolePermission->save($permission_data);
				}
				$this->redirect(array("controller"=>'users',"action"=>"role_listing",'admin'=>true));exit();
			}else{
				$this->set("errors",$errors);
			}
		}else if(isset($role_id)){
			if($this->is_id_exist($role_id,"Role")){
				$this->Role->id = $role_id;
				$data = $this->Role->read();
				$data['Role']['id'] = ENCRYPT_DATA($data['Role']['id']);
				$this->data = $data;
			}
			else{
				$this->Session->setFlash(NOT_FOUND_ERROR, 'message/red');
				$this->redirect(array('controller'=>'users','action'=>'role_listing','admin'=>true));exit();
			}
		}
	}
	
	/*
	* Ajax Function for validate add_edit_role
	*/
	
	function admin_validate_add_role_ajax(){
		
		$this->layout = "";
		$this->autoRender = false;
		if($this->RequestHandler->isAjax()){
			$errors_msg = null;
			$data = $this->data;
			if(isset($this->data['Role']['id'])){
				$data['Role']['id'] = DECRYPT_DATA($data['Role']['id']);
			}
			
			App::import("model","Role");
			$this->Role = new Role();
			$errors = $this->Role->validate_add_role($data);
			
			if ( is_array ( $this->data ) ){
				foreach ($this->data['Role'] as $key => $value ){
					if( array_key_exists ( $key, $errors) ){
						foreach ( $errors [ $key ] as $k => $v ){
							$errors_msg .= "error|$key|$v";
						}	
					}
					else {
						$errors_msg .= "ok|$key\n";
					}
				}
				if(array_key_exists("RolePermission",$errors)){
					$errors_msg .= "error|RolePermission|".$errors['RolePermission'][0];
				}
				
			}
			echo $errors_msg;
			
			exit();
		}	
	}
	
	/*
	* Function for creating dealer reports
	*/
	
	function admin_dealer_report(){
		
		$this->layout = "backend/backend";
		$this->set("title_for_layout",DEALER_REPORT);
		
		App::import('Model','Reporter');
		$this->Reporter= new Reporter();
		
		$conditions = array('Reporter.is_deleted'=>'0');
		
		$name = isset($this->params['url']['name'])?$this->params['url']['name']:(isset($this->params['named']['name'])?$this->params['named']['name']:"");
		if(trim($name) != ""){
			$conditions = array_merge($conditions ,array("Reporter.first_name like"=>"%".trim($name)."%"));
		}		
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		if(trim($is_active) != ""){
			$conditions = array_merge($conditions ,array("Reporter.is_active"=>$is_active));
		}
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
            
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Reporter.modified,'%Y-%m-%d') >="=>trim($from)));
		}
		
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Reporter.modified,'%Y-%m-%d') <="=>trim($to)));
		}
		
		$sort = isset($this->params['url']['sort'])?$this->params['url']['sort']:(isset($this->params['named']['sort'])?$this->params['named']['sort']:"total");
		$direction = isset($this->params['url']['direction'])?$this->params['url']['direction']:(isset($this->params['named']['direction'])?$this->params['named']['direction']:"desc");
		$order = $sort." ".$direction;
		$field = array("Reporter.*");
		$this->paginate = array("fields"=>$field,"conditions"=>$conditions,"limit"=>USER_LIMIT,'order'=>$order);
		
		$user_data = $this->paginate('Reporter');
		$this->set('user_data',$user_data);
		
		$csv_export = isset($this->params['url']['export_to_csv'])?$this->params['url']['export_to_csv']:(isset($this->params['named']['export_to_csv'])?$this->params['named']['export_to_csv']:"paginate");
		if($csv_export == "csv"){
		    $this->layout = "";
		    $this->generate_dealer_report_csv($user_data);
		}
		
		$pdf_export = isset($this->params['url']['export_to_pdf'])?$this->params['url']['export_to_pdf']:(isset($this->params['named']['export_to_pdf'])?$this->params['named']['export_to_pdf']:"paginate");
		if($pdf_export == "pdf"){
			$this->layout = "";
			$this->generate_dealer_report_pdf($user_data);
		}
	}
	
	
	/*
	* Function for generating voucher report csv
	*/
	
	function generate_dealer_report_csv($vendor_data=array()){
		
		App::import('Helper','csv');
		$csv = new csvHelper();
		$line = array('Dealer','Email','Status','Total Sale(DKK)','Modified');
		$csv->addRow($line);
		if(!empty($vendor_data)){
			$status = array('Deactive','Active');
			foreach($vendor_data as $data){
				
				$line = array(ucfirst($data['Reporter']['first_name'])." ".ucfirst($data['Reporter']['last_name']),$data['Reporter']['email'],$status[$data['Reporter']['is_active']],sprintf('%0.2f',$data['Reporter']['total']),$data['Reporter']['modified']);
				$csv->addRow($line);
			
			}
			
		}
		echo $csv->render("dealer_report".date("d/M/Y"));
		exit();
	}
	
	
	/*
	* Function for generating voucher report pdf
	*/
	
	function generate_dealer_report_pdf($data = null){
		App::import('Vendor','tcpdf/tcpdf');
                $tcpdf = new TCPDF();
                $textfont = 'helvetica';
                $tcpdf->SetAutoPageBreak(true);
                 
                $tcpdf->setPrintHeader(false);
                $tcpdf->setPrintFooter(false);
                 
                $tcpdf->SetTextColor(0, 0, 0);
                $tcpdf->SetFont($textfont,'',10);
                 
                $tcpdf->AddPage();
                
               // session details
                $htmlcontent="<html><body>";
                $htmlcontent.="<strong><h2>Dealer Report</h2></strong>";
                
                $htmlcontent.="<br/>";
                // table start
                $htmlcontent.="<table border=\".5\" >
                <tr>
                        <td align='left' valign='top'><strong>Dealer</strong></td>
			<td align='left' valign='top'><strong>Email</strong></td>
			<td align='left' valign='top'><strong>Status</strong></td>
			<td align='left' valign='top'><strong>Total Sale(DKK)</strong></td>
                        <td align='left' valign='top'><strong>Modified</strong></td>
                        
                </tr>";
                $charges="No";
		
		if(count($data)>0){ 
                        $status = array('Deactive','Active');
                        
                        foreach($data as $result){
				
				$htmlcontent.="<tr><td align='left' valign='top'>";
                                $htmlcontent.= ucfirst($result['Reporter']['first_name'])." ".ucfirst($result['Reporter']['last_name']);
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$result['Reporter']['email'];
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$status[$result['Reporter']['is_active']];
                                $htmlcontent.="</td><td align='right' valign='top'>";
                                $htmlcontent.=sprintf("%0.2f",$result['Reporter']['total']);
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$result['Reporter']['modified'];
                                $htmlcontent.="</td></tr>";
                        }
                }
                else {
                    $htmlcontent.="<tr><td colspan='5' style='border-bottom:0px' class='tc'>No records found</td></tr>";
                }
                
                
                $htmlcontent.="</table></body></html>";	    
                
                // output the HTML content	    
                $tcpdf->writeHTML($htmlcontent, true, 0, true, 0);
                
                $tcpdf->Output('dealer_report.pdf', 'D');
                exit();
	}
	
	
	/*
	* Function for creating sub dealer reports
	*/
	
	function admin_sub_dealer_report(){
		
		$this->layout = "backend/backend";
		
		$this->set("title_for_layout",SUB_DEALER_REPORT);
		
		$conditions = array("User.is_deleted"=>0,"User.role_id"=>"3");
		
		$parent_id = isset($this->params['url']['parent_id'])?$this->params['url']['parent_id']:(isset($this->params['named']['parent_id'])?$this->params['named']['parent_id']:"");
		if(trim($parent_id) != ""){
			$conditions = array_merge($conditions ,array("User.parent_id like"=>"%".trim($parent_id)."%"));
		}
		
		$name = isset($this->params['url']['name'])?$this->params['url']['name']:(isset($this->params['named']['name'])?$this->params['named']['name']:"");
		if(trim($name) != ""){
			$conditions = array_merge($conditions ,array("UserProfile.first_name like"=>"%".trim($name)."%"));
		}		
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		if(trim($is_active) != ""){
			$conditions = array_merge($conditions ,array("User.is_active"=>$is_active));
		}
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
            
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(User.modified,'%Y-%m-%d') >="=>trim($from)));
		}
		
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(User.modified,'%Y-%m-%d') <="=>trim($to)));
		}
		
		$sort = isset($this->params['url']['sort'])?$this->params['url']['sort']:(isset($this->params['named']['sort'])?$this->params['named']['sort']:"total_price");
		
		$direction = isset($this->params['url']['direction'])?$this->params['url']['direction']:(isset($this->params['named']['direction'])?$this->params['named']['direction']:"desc");
		
		$join_coupon_sell = array('table'=>'coupon_sales','alias'=>'CouponSale','forignKey'=>false,'type'=>'left','conditions'=>array('CouponSale.dealer_id = User.id'));
		
		$field = array("User.is_active","User.email","User.role_id","User.parent_id","User.modified","UserProfile.first_name","UserProfile.last_name","sum(CouponSale.price) AS total_price");
		
		$this->paginate = array("recursive"=>0,"fields"=>$field,"joins"=>array($join_coupon_sell),"conditions"=>$conditions,"limit"=>USER_LIMIT,'group' => array('User.parent_id'),'passit' => array("sort"=>$sort,"direction"=>$direction));
		
		$user_data = $this->paginate('User');
		$this->set('user_data',$user_data);
		
		$csv_export = isset($this->params['url']['export_to_csv'])?$this->params['url']['export_to_csv']:(isset($this->params['named']['export_to_csv'])?$this->params['named']['export_to_csv']:"paginate");
		if($csv_export == "csv"){
		    $this->layout = "";
		    $this->generate_sub_dealer_report_csv($user_data);
		}
		
		$pdf_export = isset($this->params['url']['export_to_pdf'])?$this->params['url']['export_to_pdf']:(isset($this->params['named']['export_to_pdf'])?$this->params['named']['export_to_pdf']:"paginate");
		if($pdf_export == "pdf"){
			$this->layout = "";
			$this->generate_sub_dealer_report_pdf($user_data);
		}
	}
	
	
	
	/*
	* Function for generating voucher report csv
	*/
	
	function generate_sub_dealer_report_csv($vendor_data=array()){
		
		App::import('Helper','csv');
		$csv = new csvHelper();
		$line = array('Dealer','Email','Status','Total Sale(DKK)','Modified');
		$csv->addRow($line);
		if(!empty($vendor_data)){
			$status = array('Deactive','Active');
			
			foreach($vendor_data as $data){
				$sold_price = $data[0]['total_price'];
				$line = array(ucfirst($data['UserProfile']['first_name'])." ".ucfirst($data['UserProfile']['last_name']),$data['User']['email'],$status[$data['User']['is_active']],sprintf('%0.2f',$sold_price),$data['User']['modified']);
				$csv->addRow($line);
			}
			
		}
		echo $csv->render("sub_dealer_report".date("d/M/Y"));
		exit();
	}
	
	
	/*
	* Function for generating voucher report pdf
	*/
	
	function generate_sub_dealer_report_pdf($data = null){
		App::import('Vendor','tcpdf/tcpdf');
                $tcpdf = new TCPDF();
                $textfont = 'helvetica';
                $tcpdf->SetAutoPageBreak(true);
                 
                $tcpdf->setPrintHeader(false);
                $tcpdf->setPrintFooter(false);
                 
                $tcpdf->SetTextColor(0, 0, 0);
                $tcpdf->SetFont($textfont,'',10);
                 
                $tcpdf->AddPage();
                
               // session details
                $htmlcontent="<html><body>";
                $htmlcontent.="<strong><h2>Sub-Dealer Report</h2></strong>";
                
                $htmlcontent.="<br/>";
                // table start
                $htmlcontent.="<table border=\".5\" >
                <tr>
                        <td align='left' valign='top'><strong>Dealer</strong></td>
			<td align='left' valign='top'><strong>Email</strong></td>
                        <td align='left' valign='top'><strong>Status</strong></td>
			<td align='left' valign='top'><strong>Total Sale(DKK)</strong></td>
                        <td align='left' valign='top'><strong>Modified</strong></td>
                        
                </tr>";
                $charges="No";
		
		if(count($data)>0){ 
                        $status = array('Deactive','Active');
                        
                        foreach($data as $result){
				$total_sale = $result[0]['total_price'];
				
                                $htmlcontent.="<tr><td align='left' valign='top'>";
                                $htmlcontent.= ucfirst($result['UserProfile']['first_name'])." ".ucfirst($result['UserProfile']['last_name']);
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$result['User']['email'];
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$status[$result['User']['is_active']];
                                $htmlcontent.="</td><td align='right' valign='top'>";
                                $htmlcontent.=sprintf("%0.2f",$total_sale);
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$result['User']['modified'];
                                $htmlcontent.="</td></tr>";
                        }
                }
                else {
                    $htmlcontent.="<tr><td colspan='5' style='border-bottom:0px' class='tc'>No records found</td></tr>";
                }
                
                
                $htmlcontent.="</table></body></html>";	    
                
                // output the HTML content	    
                $tcpdf->writeHTML($htmlcontent, true, 0, true, 0);
                
                $tcpdf->Output('sub_dealer_report.pdf', 'D');
                exit();
	}
	
	
	/*
	* Function for overall  trends
	*/
	
	function admin_overall_trends(){
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",REPORT_MANAGEMENT);
		
		App::import("Model","Vendor");
		$this->Vendor= new Vendor();
		$vendor_list = $this->Vendor->find('list',array('conditions'=>array("is_active"=>"1","is_deleted"=>"0"),'order'=>'Vendor.name'));
		$this->set("vlist",$vendor_list);
		
		App::import("Model","User");
		$this->User= new User();
		
		//$ulist=$this->User->find('all',array('conditions'=>array('User.role_id'=>2),'fields'=>array('User.id','concat(CONCAT(UCASE(SUBSTRING(UserProfile.first_name, 1, 1)),LCASE(SUBSTRING(UserProfile.first_name, 2)))," ",CONCAT(UCASE(SUBSTRING(UserProfile.last_name, 1, 1)),LCASE(SUBSTRING(UserProfile.last_name, 2)))) as name'),'recursive'=>0));
		$ulist = $this->User->find('all',array('conditions'=>array('User.role_id'=>2,'User.is_deleted'=>'0'),'fields'=>array('User.id','CONCAT(UCASE(SUBSTRING(UserProfile.first_name, 1, 1)),LCASE(SUBSTRING(UserProfile.first_name, 2))) as name'),'recursive'=>0));
		
		App::import("Model","Category");
		$this->Category = new Category();
		$voucher_list = $this->Category->find("list",array("conditions"=>array("is_deleted"=>"0"),"fields"=>array("id","title")));
		$this->set("voucher_list",$voucher_list);
		
		$dlist=array();
		foreach ($ulist as $key => $value) {
			$dlist[$value['User']['id']]=$value['0']['name'];
		}
		$this->set('dlist',$dlist);
		
		$field = array("Vendor.name","Vendor.id");
		
		
		App::import("Model","Coupon");
		$this->Coupon = new Coupon();
		
		App::import('Model','Product');
		$this->Product =  new Product();
		$product_list =  $this->Product->find('all',array('recursive'=>-1,'fields'=>array('Product.product_code','Product.title','Product.vendor_id'),'conditions'=>array('Product.is_deleted'=>'0','Product.is_active'=>'1'),'order'=>'Product.title'));
		
		$large_array=array();
		$i = 0;
		foreach($product_list as $k1=>$v1){
			
			$remain = $this->Coupon->find('count',array('conditions'=>array("Coupon.is_sold"=>"0","is_active"=>"1","is_deleted"=>"0","Coupon.expire_date >"=>date("Y-m-d"),"Coupon.product_code"=>$v1['Product']['product_code'])));
			$large_array[$i]['name'] = $vendor_list[$v1['Product']['vendor_id']].'- '.$v1['Product']['title'];
			if(empty($remain)){
				$large_array[$i]['remain'] = 0;
			}else{
				$large_array[$i]['remain'] = $remain;
			}
			$i++;
		}
		$this->set("coupon_stock",$large_array);
	}
	
	
	/*
	* This function is just to user for testing new things
	*/
	function admin_test(){
		
		$this->layout = "backend/test_backend";
	}
	
}

?>
