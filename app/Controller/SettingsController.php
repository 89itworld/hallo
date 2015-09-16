<?php
/**
 * Coupons Controller
 */
class SettingsController extends AppController {
	 
	var $name = 'Settings';
	var $components = array('Auth','Session','RequestHandler','Common');
	var $helpers = array('Form', 'Html', 'Js','Common','Paginator');
	var $uses = array();
 	
	/*
	* Function for setting index
	*/
	
	function admin_index(){
		$this->layout='backend/backend';
		$this->set("title_for_layout",SETTING);
	}
	
	/*
	* Function for coupon listing
	*/
	
 	function admin_cms_list(){
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",CMS_PAGE_LISTING);
		App::import("Model","CmsPage");
		$this->CmsPage = new CmsPage();
		$conditions = array("CmsPage.is_deleted"=>"0");
		
		$this->paginate = array('recursive'=>-1,'conditions'=>$conditions,'limit' =>CMS_PAGE_LIMIT,'order'=>array("CmsPage.page_order"=>"asc"));
		$cms_page_data = $this->paginate('CmsPage');  
		$this->set('cms_page_data',$cms_page_data);
	}
	
	/*
	* Function for activate/deactivate cmspage
	*/
	
	function admin_activate_cmspage($is_active = null,$cms_id = null,$model_name = null){
		
		$this->layout = "";
		$this->autoRender = false;
		$cms_id = DECRYPT_DATA($cms_id);
		if(isset($is_active) && isset($cms_id) && isset($model_name)){
			App::import("Model",$model_name);
			$this->$model_name = new $model_name();
			if($is_active == 0){//Activate Status
			    $this->$model_name->updateAll(array("$model_name.is_active"=>"'1'"),array("$model_name.id"=>$cms_id));
			    $this->Session->setFlash(RECORD_ACTIVATED, 'message/green');
			}
			else if($is_active == 1){//Inactivate Status
			    $this->$model_name->updateAll(array("$model_name.is_active"=>"'0'"),array("$model_name.id"=>$cms_id));
			    $this->Session->setFlash(RECORD_DEACTIVATED, 'message/green');
			}
		}
		$this->redirect($this->referer());exit();	
	}
	
	/*
	* Function for delete cms page
	*/
	
	function admin_delete_cmspage($cms_id = null){
		
		$this->layout = "";
		$this->autoRender = false;
		$cms_id = DECRYPT_DATA($cms_id);
		if(isset($cms_id)){
			App::import("Model","CmsPage");
			$this->CmsPage = new CmsPage();
			$this->CmsPage->updateAll(array("CmsPage.is_deleted"=>"'1'"),array("CmsPage.id"=>$cms_id));
			$this->Session->setFlash(RECORD_DELETED, 'message/green');
		}
		$this->redirect($this->referer());exit();	
	}
	
	/*
	* Function to add cms page
	*/

 	function admin_add_cms_page(){
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",ADD_CMS_PAGE);
		if(!empty($this->data)){
			
			App::import("Model","CmsPage");
			$this->CmsPage = new CmsPage();
			$data = $this->data;
			$errors = $this->CmsPage->valid_edit_cms($data);
			if(count($errors) == 0){
				if($this->CmsPage->save($data)){
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array("controller"=>"settings","action"=>"admin_cms_list","admin"=>true)); 
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array("controller"=>"coupons","action"=>"edit",$this->data['CmsPage']['id'],"admin"=>true));
				}
			}
			$this->set("errors",$errors);
		}
	}
	
	
	/*
	* Function to edit cms page
	*/

 	function admin_edit_cms_page($cms_page_id = null){
		
		$cms_page_id = DECRYPT_DATA($cms_page_id);
		$this->layout='backend/backend';
		$this->set("title_for_layout",EDIT_CMS_PAGE);
		if(!empty($this->data)){
			App::import("Model","CmsPage");
			$this->CmsPage = new CmsPage();
			$data = $this->data;
			$data['CmsPage']['id'] = DECRYPT_DATA($data['CmsPage']['id']);
			$errors = $this->CmsPage->valid_edit_cms($data);
			if(count($errors) == 0){
				
				if($this->CmsPage->save($data)){
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array("controller"=>"settings","action"=>"admin_cms_list","admin"=>true)); 
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array("controller"=>"coupons","action"=>"edit",$this->data['CmsPage']['id'],"admin"=>true));
				}
				
			}
			$this->set("errors",$errors);
		}else if(isset($cms_page_id)){
			if($this->is_id_exist($cms_page_id,"CmsPage")){
				$this->CmsPage->id = $cms_page_id;
				$data = $this->CmsPage->read();
				$data['CmsPage']['id'] = ENCRYPT_DATA($data['CmsPage']['id']);
				$this->data = $data;
			}else{
				$this->Session->setFlash(NOT_FOUND_ERROR, 'message/red');
				$this->redirect(array("controller"=>"coupons",'action'=>'admin_cms_list','admin'=>true));exit();
			}
		}
	}
	
	/*
	*Function for validate cms edit
	*/
	function admin_validate_edit_cms_ajax(){
		
		$this->layout = "";
		$this->autoRender = false;
		if($this->RequestHandler->isAjax()){
			$errors_msg = null;
			App::import('Model','CmsPage');
			$this->CmsPage = new CmsPage();
			
			$data = $this->data;
			if(isset($data['CmsPage']['id'])){
				$data['CmsPage']['id'] = DECRYPT_DATA($data['CmsPage']['id']);
			}
			$errors = $this->CmsPage->valid_edit_cms($data);
			if ( is_array ( $this->data ) ){
				foreach ($this->data['CmsPage'] as $key => $value ){
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
			die();
		}	
	}
}
?>