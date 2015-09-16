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
    
    var $components = array('Auth','Email'); // Not necessary if declared in your app controller
    
    function beforeFilter(){	
    	parent::beforeFilter();
	$this->Auth->allow("admin_forgot_password","admin_reset_password");
	$this->Auth->authenticate = array('Form' => array('userModel' => 'User','fields' => array('username' => 'email', 'password' => 'powd')));
		if($this->Auth->user()){
		    $user = $this->Auth->user();
		    $this->set('User',$user);
		} 
		if($this->params['prefix'] == 'admin'){
		    /*$referer = $this->referer(null, true);
		    if (empty($referer)) {
			$referer = array('controller' => 'dashboards', 'action' => 'index','admin'=>'true');
		    }*/
		    
		    $this->Auth->userScope = array('User.is_active' => '1','User.is_deleted' => '0','User.user_type !=' => '0');
		    
		    $this->Auth->loginAction = array('controller'=>'users','action'=>'login', 'admin'=>true);
		    
		    $this->Auth->loginRedirect = array('controller' => 'dashboards', 'action' => 'index','admin'=>'true');
		    
		    $this->Auth->logoutRedirect = array('controller'=>'users','action'=>'login','admin'=> true);		
		}else{
		} 
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


	
	
/**********This function is used to Bulk Active/Inactive/Delete Record
 *  @ $Record_Id => ID of all bulk record 
 * 	@ $Options => array(
 * 						  'Action' =>  < Possible values >  
 * 										'1' => Active,
 * 										'0' => Inactive,
 * 										'2' => Delete
 * 
 * 						  'Model' =>   < Model name >
 * 						  		
 * 							
 * 						  'RelationModel' => < Possible values >  
 * 											   'Null'
 * 											   'Modelname'			
 * 				 
 * 					)								
 * 
 * 
 *	@Return => 	Bollean value TRUE / FALSE 
 * 
 ***********/
	function is_action($Record_Id=null,$Options=null){
		
		if(!empty($Record_Id) && is_array($Record_Id) && !empty($Options) && is_array($Options)){
				  
				$Model = $Options['Model'];
				$Action = $Options['Action'];
					
				/*****If RelationModel is empty ****/
				if(empty($Options['RelationModel'])){  //pr($Record_Id);	 pr($Options);			die('s');
					
					$this->loadModel($Model);		
					switch($Action){
						
						//Case is_active => 0
						case '0':
							if($this->$Model->updateAll(array("$Model.is_active"=> "'0'"),array("$Model.id"=>$Record_Id))){
							 	return TRUE;
							}else{
								return FALSE;
							}							
						break;
						
						
						//Case is_active => 1
						case '1':
							if($this->$Model->updateAll(array("$Model.is_active"=> "'1'"),array("$Model.id"=>$Record_Id))){
						 		return TRUE;
							}else{
								return FALSE;
							}
						break;
						
						
						//Case is_deleted => 1 	
						case '2':
							if($this->$Model->updateAll(array("$Model.is_deleted"=> "'1'"),array("$Model.id"=>$Record_Id))){
								return TRUE;
							}else{
								return FALSE;
							}
						break;
							
						
						// Defualt return false
						default:
								return FALSE;
						break;
						
					}
					
				}else{
					return FALSE;
				}
				
			
		}else{ 
			
			
			return FALSE;
		}
		
		
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
	$data = $this->Category->find('list',array("fields"=>array('id','title'),"conditions"=>array("Category.is_deleted"=>0))); 
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
	$data = $this->Product->find('list',array("fields"=>array('id','title'),"conditions"=>array("Product.is_deleted"=>0)));
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
        //$this->EmailTemplate =& new EmailTemplate();
	
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
 
}
