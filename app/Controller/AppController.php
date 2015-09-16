<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 * 
 * 
 * 
 *@ Cakephp Version => 2.2.0
 *@ Started => December 2012
 *@ 
 */

App::uses('Controller', 'Controller'); 
App::import('Sanitize');

class AppController extends Controller {
    var $components = array('Session','Auth','Email'); // Not necessary if declared in your app controller
    var $allow=0;
    function beforeRender () {
	/*Check cakephp error page*/
	//if ($this->name == "CakeError") {  
	//    $this->layout = "error";  
	//}
	
	/* It is used to redirect page*/
	if(($this->here == "/homes") || ($this->here == "/homes/") || ($this->here == "/homes/index/1") || ($this->here == "/home")){
	    $this->redirect(array('controller' => 'homes', 'action' => 'index'));
	}
	
	// It is only used to see my profile for sub dealer if it is set by admin
	if($this->Session->read("Auth.User.role_id") == "3"){
		App::import("Model","UserPermission");
		$this->UserPermission = new UserPermission();
		$my_profile_permission = $this->UserPermission->find("all",array("fields"=>array("UserPermission.permission_id"),"conditions"=>array("UserPermission.user_id"=>$this->Session->read("Auth.User.id"),"UserPermission.is_active"=>"1","UserPermission.is_deleted"=>"0")));
		$my_profile_permissions = false;
		
		foreach($my_profile_permission as $k=>$v){
			if($v['UserPermission']['permission_id'] == '2'){
				$my_profile_permission = false;
				break;
			}elseif($v['UserPermission']['permission_id'] == '9'){
				$my_profile_permissions = true;
				break;
			}
		}
		$this->set("my_profile_permissions",$my_profile_permissions);
	}
    }
    
    function beforeFilter(){	
    	parent::beforeFilter();
	if($this->params['prefix'] == 'admin'){  
				$this->Auth->allow("admin_login","admin_forgot_password","admin_reset_password");
				$this->Auth->authenticate = array('Form' => array('userModel' => 'User','fields' => array('username' => 'email', 'password' => 'powd')));
	
				$this->Auth->userScope = array('User.is_active' => '1','User.is_deleted' => '0','User.user_type !=' => '0');
			 	$this->Auth->loginAction = array('controller'=>'users','action'=>'login', 'admin'=>true);
				$check_auth_session_set = $this->Session->read("Auth.User");
				$check_auth_user_role_id = $this->Session->read("Auth.User.role_id");
				if(isset($check_auth_session_set) && (($check_auth_user_role_id == "2") || ($check_auth_user_role_id == "3"))){
				    
				    $this->Auth->loginRedirect = array('controller' => 'coupons', 'action' => 'sale','admin'=>'true');
				}else{
				    $this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'overall_trends','admin'=>'true');
				}
				$this->Auth->logoutRedirect = array('controller'=>'users','action'=>'login','admin'=> true);		
				
				if($this->Auth->user()){
					$user=$this->Auth->user();
					if(!$this->Session->check('UserPermissions')){
					    App::import("Model","UserPermission");
					    $this->UserPermission = new UserPermission();
					    App::import("Model","Permission");
					    $this->Permission = new Permission();
					    
					    if($user['role_id'] == 1){
						$UserPermissions = $this->Permission->find('all',array('fields' => array('Permission.id','Permission.title,Permission.controller,Permission.action','Permission.is_main_action',"Permission.parent_id","Permission.show_as_link"),'conditions' => array('Permission.is_active' => '1','Permission.is_deleted' => '0',"Permission.is_main_action"=>"1")));
						unset($UserPermissions[2]);
						$this->Session->write('UserPermission',$UserPermissions);
					    }else{
						
						$permission_join = array('table' => 'user_permissions','alias' => 'UserPermission','type' => 'INNER','conditions' => array('UserPermission.permission_id=Permission.id','UserPermission.user_id'=> $user['id'],'UserPermission.is_active' => '1','UserPermission.is_deleted' => '0' ));
						$UserPermissions = $this->Permission->find('all',array('recursive'=>-1,'fields' => array('Permission.id','Permission.title,Permission.controller,Permission.action','Permission.is_main_action',"Permission.parent_id","Permission.show_as_link"),'conditions' => array('Permission.is_active' => '1','Permission.is_deleted' => '0'),'joins' => array($permission_join),'order'=>'id asc'));
					  	$this->Session->write('UserPermission',$this->user_permission($UserPermissions)); 	
					    }
					}			
					$Permission=$this->Session->read('UserPermission');
					foreach($Permission as $key => $value){ 
					    $User_Allow_Permissions[]= $value['Permission'];
					    if(!empty($value['SubPermission'])){
						foreach($value['SubPermission'] as $key => $value){ 
							$User_Allow_Permissions[]= $value;
						}
					    }
					}
					
					//$User_Allow_Permissions = Set::extract('/Permission/.', $Permission);
					$role_list = $this->find_role_list();
					$this->set("role_list",$role_list);
			
			    }
				
				/******Start disable cache for admin *****/ 
				//$this->disableCache(); 
				/*****End disable cache for admin *****/
				
				/****************Start Check Permissions *******************/
				$controller=$this->params['controller'];
				$action=$this->params['action'];
					     
				//Check UserIs Looged In .....And $User_Allow_Permissions variable is set Before / ....
				if(!empty($User_Allow_Permissions) && is_array($User_Allow_Permissions)) {  
				    foreach ($User_Allow_Permissions as $key => $value) {
					if (in_array($controller, $value) && in_array($action, $value) ) {
					    $this->allow=1;
					}
				    }
				    if($this->allow != 1){ 
					$allowed_actions = array('admin_login','admin_logout','admin_unauthorize','admin_validate_add_user_ajax','admin_my_profile');
					switch($action){
					    case 'admin_login':
					    case 'admin_logout':
					    case 'admin_validate_add_user_ajax':
						    $this->allow=($controller == 'users')?1:0;
					    break;
						case 'admin_index':
						    $this->allow=($controller == 'graphs')?1:0;
					    break;
					    case 'admin_user_listing':
						$this->allow=($controller == 'users')?1:0;  
					    break;
					    case 'admin_my_profile':
						$this->allow=($controller == 'dashboards')?1:0;  
					    break;	
					    default:
						    $this->allow=0;
					    break; 
					}
				    } 
				    if($this->allow == 0 ){
					    $this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));
				    }		
				} 
		    /****************End Check Permissions ********************/
	    }else{
	    	$this->Auth->allow("index","indexnew","content"); 
		 	$this->Auth->loginAction = array('controller'=>'homes','action'=>'index');
			$this->Auth->loginRedirect = array('controller'=>'homes','action'=>'index');
			$this->Auth->logoutRedirect = array('controller'=>'homes','action'=>'index');	
			 	
				
	    	/*if($this->Auth->user()) {
			  	 $user =$this->Auth->user();
				 $role_id=$user['role_id'];
				 switch($role_id){
				 	case '0':
					     $this->redirect($this->Auth->redirect());
				   	break;
					case '1':
					case '2':
					case '3':
					    $this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));
			   	 	break;
					default:
					     $this->redirect($this->Auth->redirect());
			   		break;
				 } 
			}*/ 
			
	    } 
	}
	
	/*
	*Function for making make array for user permission
	*/
	function user_permission($permissions = null){
	    
	    $new_permissions = $permissions;
	    $new_arr = array();
	    $j = 0;
	    
	    foreach($permissions as $k=>$v){
		    
		if($v['Permission']['is_main_action'] == '1'){
		    $new_arr[$j]['Permission']['id'] = $v['Permission']['id'];
		    $new_arr[$j]['Permission']['title'] = $v['Permission']['title'];
		    $new_arr[$j]['Permission']['controller'] = $v['Permission']['controller'];
		    $new_arr[$j]['Permission']['action'] = $v['Permission']['action'];
		    $new_arr[$j]['Permission']['show_as_link'] = $v['Permission']['show_as_link'];
		    $new_arr[$j]['Permission']['parent_id'] = $v['Permission']['parent_id'];
		} 
		$i = 0;
		foreach($new_permissions as $k1=>$v1){
		    if($v1['Permission']['parent_id'] == $v['Permission']['id'] && ($v1['Permission']['is_main_action'] != "1")){
			    
			$new_arr[$j]['SubPermission'][$i]['id'] = $v1['Permission']['id'];
			$new_arr[$j]['SubPermission'][$i]['title'] = $v1['Permission']['title'];
			$new_arr[$j]['SubPermission'][$i]['controller'] = $v1['Permission']['controller'];
			$new_arr[$j]['SubPermission'][$i]['action'] = $v1['Permission']['action'];
			$new_arr[$j]['SubPermission'][$i]['parent_id'] = $v1['Permission']['parent_id'];
			$new_arr[$j]['SubPermission'][$i]['show_as_link'] = $v1['Permission']['show_as_link']; 
			$i++;
		    }
			
		}
		$j++;
	    }
	    return $new_arr;
	}
	
    /***** This function is used to check whether User is alredy login
    *  @return => true/false
    */
    function isAuthorized($user = null) {
        // Any registered user can access public functions
        if (empty($this->request->params['admin'])) {
            return true;
        }

        // Only admins can access admin functions
        if (isset($this->request->params['admin'])) {
            return (bool)($user['role'] === 'admin');
        }
	// Default deny
        return false;
    } 

    /*
    * Function for validdate id is exist or not
    */
	
    function is_id_exist($id = null, $model_name = null){
	
	App::import("model",$model_name);
	$this->$model_name = new $model_name();
	if(is_null($id) || !is_numeric($id)){
	    return false;
	}
	if($this->$model_name->find("count",array("conditions"=>array("$model_name.id"=>$id)))>0){
	    return true;
	}else{
	    return false;
	}
    }
    
    /*
    * Function for finding the list of category
    */
    
    function find_categories_list(){
	App::import("Model","Category");
	$this->Category = new Category();
	$data = $this->Category->find('list',array("fields"=>array('id','title'),"conditions"=>array("Category.is_deleted"=>0,"Category.is_active"=>"1"),"order"=>"Category.title asc")); 
	return $data;
    }
    
    /*
    * Function for change the dadte format according to the database
    */
    function covertToSystemDate($date){
	$dateArr=explode("-",$date);
	$newDate="";
	if(in_array("",$dateArr)){
	  return $newDate;
	}
	if(count($dateArr)==3){
	  return $newDate=$dateArr[2]."-".$dateArr[0]."-".$dateArr[1];
	}
	return $newDate;
    }
    
    /*
    * Function for createing dictionary 3_1_2013
    */
    
    function create_directory($directory_name = null){
	$directory_path = WWW_ROOT."img/".$directory_name."/";
	if(!is_dir($directory_path)){
		mkdir($directory_path, 0755, TRUE);
		return $directory_path;
	}else{
		return $directory_path;
	}
    }

    /*
    *Function for finding product list 4_1_2013
    */
    
    function find_product_listing(){
	App::import("Model","Product");
	$this->Product = new Product();
	$data = $this->Product->find('list',array("fields"=>array('id','title'),"conditions"=>array("Product.is_deleted"=>0,"Product.is_active"=> '1'),"order"=>"Product.title asc"));
	return $data;
    }
    

    /*
    *Function for finding product list 7_1_2013 / modified 12/01/2012
    */
    
    function show_product_list(){
	App::import("Model","Product");
	$this->Product = new Product();
	$data = $this->Product->find('list',array("fields"=>array('Product.product_code','Product.title'),"conditions"=>array("Product.is_deleted"=> '0',"Product.is_active"=> '1'),"order"=>"Product.title asc"));
	return $data;
    }
	
	
	/*
    *Function for finding product list 7_1_2013 / modified 12/01/2012
    */
    
    function show_product_vendor_list(){
	App::import("Model","Product");
	$this->Product = new Product();
	$data = $this->Product->find('list',array("fields"=>array('Product.product_code','Product.vendor_id'),"conditions"=>array("Product.is_deleted"=> '0',"Product.is_active"=> '1')));
	return $data;
    }
	
	
    /*
    *Function for finding vendor list 7_1_2013
    */
    
    function find_vendor_listing(){
		App::import("Model","Vendor");
		$this->Vendor = new Vendor();
		$data = $this->Vendor->find('list',array("fields"=>array('id','name'),"conditions"=>array("Vendor.is_deleted"=>"0","Vendor.is_active"=>"1"),"order"=>"Vendor.name asc"));
		return $data;
    }	
	
    /*
    *Function for sending email 9_1_2012
    */
    
    function send_email($replace_fields=array(),$replace_with=array(),$email_template=null,$from=null,$to=null,$reply_to=null){
	
        $this->Email->delivery = MAIL_DELIVERY;//possible values smtp or mail
        $this->Email->smtpOptions = array('host' => SMTP_HOST,'username' => SMTP_USERNAME,'password' =>SMTP_PWD,'port'=>SMTP_PORT);
        App::import('Model','EmailTemplate');
		$this->EmailTemplate = new EmailTemplate();
        $template = $this->EmailTemplate->find("first",array("conditions"=>array('EmailTemplate.slug'=>$email_template)));
        $template_data = $template['EmailTemplate']['description'];
        $template_info = str_replace($replace_fields,$replace_with,$template_data);
        $this->set('data',$template_info);
		$this->Email->to = $to;
        $this->Email->subject =$template['EmailTemplate']['subject'];
        if(!is_null($from) && trim($from)!=""){		
            $this->Email->from = $from;
        }
        else{
            $this->Email->from = false;
            $this->Email->fromName = false;
        }
        $this->Email->template = 'email_template';
        $this->Email->replyTo  = $reply_to;
        $this->Email->sendAs = 'both';
	
        if($this->Email->send()){
            return true;
        }else{
            return false;
        }
    }
    
    
    
    /*
    *Function for finding role listing
    */
    
    function find_role_list(){
	App::import("Model","Role");
	$this->Role = new Role();
	$role_list = $this->Role->find("list",array("conditions"=>array("Role.is_deleted"=>"0","Role.is_active"=>"1"),"fields"=>array("id","title"),"order"=>"Role.title asc"));
	
	return $role_list;
    }
}
