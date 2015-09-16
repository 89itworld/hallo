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
		
		$this->paginate = array('recursive'=>-1,'limit' =>CMS_PAGE_LIMIT,'order'=>array("CmsPage.title"=>"desc"));
		$cms_page_data = $this->paginate('CmsPage');  
		$this->set('cms_page_data',$cms_page_data);
	}
	
	/******This function used for Edit Coupon
	 * 	@layout => 'backend/adminlogin' 
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
				/* if((($data['CmsPage']['slug'] == 'home-1') || ($data['CmsPage']['slug'] == 'home-2') || ($data['CmsPage']['slug'] == 'home-3') || ($data['CmsPage']['slug'] == 'home-4')) && ($data['CmsPage']['image']['name'] != "")){
					App::import("Component","Upload");
					$upload = new UploadComponent();
					$allowed_ext = array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG');
					$path_info = pathinfo($this->data['CmsPage']['image']['name']);
					$file_extension = strtolower($path_info['extension']);
					
					if(in_array($file_extension,$allowed_ext)){
						$file = $this->data['CmsPage']['image'];
						$filename = str_replace(array(" ","."),"",md5(microtime())).".".$file_extension;
						$image_path = $this->create_directory("cms_photos");
						$file_name = $upload->upload($file,$image_path,$filename,null,$allowed_ext);
						if($data['CmsPage']['previous_image'] != ""){
							unlink($image_path.$data['CmsPage']['previous_image']);
						}
						if($file_name){
							unset($data['CmsPage']['slug']);
							$data['CmsPage']['image'] = $filename;
							$this->CmsPage->save($data);
							$this->Session->setFlash(RECORD_SAVE, 'message/green');
							$this->redirect(array("controller"=>"settings","action"=>"admin_cms_list","admin"=>true));
						}
					}else{
					    $errors['image'][] = ERR_CMS_IMAGE_TYPE;
					}
				}else{
				
					unset($data['CmsPage']['image']);
				*/
					unset($data['CmsPage']['slug']);
					if($this->CmsPage->save($data)){
						$this->Session->setFlash(RECORD_SAVE, 'message/green');
						$this->redirect(array("controller"=>"settings","action"=>"admin_cms_list","admin"=>true)); 
					}else{
						$this->Session->setFlash(RECORD_ERROR, 'message/red');
						$this->redirect(array("controller"=>"coupons","action"=>"edit",$this->data['CmsPage']['id'],"admin"=>true));
					}
				//}
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