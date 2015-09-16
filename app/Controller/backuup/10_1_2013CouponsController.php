<?php
/**
 * Coupons Controller
 */
class CouponsController extends AppController {
	 
	var $name = 'Coupons';
	var $components = array('Auth','Session','RequestHandler','Common');
	var $helpers = array('Form', 'Html', 'Js','Common','Paginator');
 	
	/*
	* Function for coupon listing
	*/
 	function admin_list(){
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",COUPON_LISTING);
		$conditions = array("Coupon.is_deleted"=>"0");
		$category_list = $this->find_categories_list();
		$this->set("category_list",$category_list);
		
		$product_listing = $this->find_product_listing();
		$this->set("product_listing",$product_listing);
		
		
		$coupon_id = isset($this->params['url']['coupon_id'])?$this->params['url']['coupon_id']:(isset($this->params['named']['coupon_id'])?$this->params['named']['coupon_id']:"");
		if(trim($coupon_id) != ""){
			$conditions = array_merge($conditions ,array("Coupon.coupon_id like"=>"%".trim($coupon_id)."%"));
		}		
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		if(trim($is_active) != ""){
			$conditions = array_merge($conditions ,array("Coupon.is_active"=>$is_active));
		}
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
            
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Coupon.created,'%Y-%m-%d') >="=>trim($from)));
		}
		
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Coupon.created,'%Y-%m-%d') <="=>trim($to)));
		}
		
		$this->paginate = array('limit' =>COUPON_LIMIT,'conditions'=>$conditions,'order'=>array("Coupon.created"=>"desc"));
		$coupon_data = $this->paginate('Coupon');  
		$this->set('coupon_data',$coupon_data);
	}
	
	
	/*
	* Fuction for activate,deactivate and delete in bulk
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
	* Function for activate/deactivate coupon
	*/
	
	function admin_activate_coupon($is_active = null,$cat_id = null,$model_name = null){
		
		$this->layout = "";
		$this->autoRender = false;
		$is_active = DECRYPT_DATA($is_active);
		$cat_id = DECRYPT_DATA($cat_id);
		if(isset($is_active) && isset($cat_id) && isset($model_name)){
			
			if($is_active == 0){//Activate Status
			    $this->$model_name->updateAll(array("$model_name.is_active"=>"'1'"),array("$model_name.id"=>$cat_id));
			    $this->Session->setFlash(RECORD_ACTIVATED, 'message/green');
			}
			else if($is_active == 1){//Inactivate Status
			    $this->$model_name->updateAll(array("$model_name.is_active"=>"'0'"),array("$model_name.id"=>$cat_id));
			    $this->Session->setFlash(RECORD_DEACTIVATED, 'message/green');
			}
		}
		$this->redirect($this->referer());exit();	
	}
	
	/*
	* Function for delete coupon
	*/
	
	function admin_delete_coupon($coupon_id = null){
		
		$this->layout = "";
		$this->autoRender = false;
		$vendor_id = DECRYPT_DATA($coupon_id);
		if(isset($coupon_id)){
			$this->Coupon->updateAll(array("Coupon.is_deleted"=>"'1'"),array("Coupon.id"=>$vendor_id));
			$this->Session->setFlash(RECORD_DELETED, 'message/green');
		}
		$this->redirect($this->referer());exit();	
	}
	
 
/******This function used for Add New Coupon
 * 	@layout => 'backend/adminlogin' 
 */

 	function admin_add(){
		
 		$this->layout='backend/backend';
		$this->set("title_for_layout",ADD_COUPON);
		$category_list = $this->find_categories_list();
		$this->set("category_list",$category_list);
		if(!empty($this->data)){				
				
			/*** Then, to check if the data validates, use the validates method of the model, 
			* which will return true if it validates and false if it doesn’t:
	 		*/
			
			$errors = $this->Coupon->validate_data($this->data['Coupon']);
			if(count($errors) == 0){
				$result = $this->Coupon->save_data($this->data);
				if($result){
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array('controller'=>'coupons','action'=>'list'));
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array('controller'=>'coupons','action'=>'add'));
				}		 
			}else{
				$this->set("errors",$errors);
			}
				    
		}
		
 	}
	
	
/******This function used for Edit Coupon
 * 	@layout => 'backend/adminlogin' 
 */

 	function admin_edit($coupon_id = null){
		
		$coupon_id = DECRYPT_DATA($coupon_id);
		$this->layout='backend/backend';
		$this->set("title_for_layout",EDIT_COUPON);
		
		$category_list = $this->find_categories_list();
		$this->set("category_list",$category_list);
		if(!empty($this->data)){
			$data = $this->data;
			$data['Coupon']['id'] = DECRYPT_DATA($data['Coupon']['id']);
			$errors = $this->Coupon->validate_data($data['Coupon']);
			
			if(count($errors) == 0){
				$result = $this->Coupon->save_data($data);
				if($result){
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array("controller"=>"coupons","action"=>"list","admin"=>true)); 
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array("controller"=>"coupons","action"=>"edit",$this->data['Coupon']['id'],"admin"=>true));
				}
			}else{
				$this->set("errors",$errors);
			}
		}else if(isset($coupon_id)){
			if($this->is_id_exist($coupon_id,"Coupon")){
				$this->Coupon->id = $coupon_id;
				$data = $this->Coupon->read();
				$data['Coupon']['id'] = ENCRYPT_DATA($data['Coupon']['id']);
				$this->data = $data;
				$this->set("product_options",$this->find_product_list($this->data['Coupon']['product_id']));
			}else{
				$this->Session->setFlash(NOT_FOUND_ERROR, 'message/red');
				$this->redirect(array("controller"=>"products",'action'=>'list','admin'=>true));exit();
			}
		}
	}
	
	/*This function is used for Validation check list by using Ajax*/
	function admin_validate_data_ajax(){
          
		$this->layout = "";
		$this->autoRender = false;
		if($this->RequestHandler->isAjax()){
			$errors_msg = null;
			$data = $this->data;
			$data['Coupon']['id'] = DECRYPT_DATA($data['Coupon']['id']);
			$errors = $this->Coupon->validate_data($data['Coupon']);
			if ( is_array ($this->data) ){
			    foreach ($this->data['Coupon'] as $key => $value ){
				if( array_key_exists ( $key, $errors) ){
				    foreach ( $errors [ $key ] as $k => $v ){
					$errors_msg .= "error|$key|$v";
				    }	
				}else {
					$errors_msg .= "ok|$key\n";
				}
			    }
			    
			}
			echo $errors_msg;die();
		}	
	}
	
	/*
	* Function for select state
	*/
	
	function admin_select_product($id = null){
		
		$this->layout = "";
		$this->autoRender = false;
		App::import("Model","Product");
		$this->Product = new Product();
		if($this->RequestHandler->isAjax()){
			$product_list = $this->Product->find('list',array('conditions'=>array('category_id'=>$id),'fields'=>array('id','title')));
			$option ="<option>Select product</option>";
			foreach($product_list as $k=>$v){
				$option.= "<option value=".$k.">".$v."</option>";
			}
			echo $option;
		}
	}
 
	function find_product_list($product_id = null){
		
		App::import("Model","Product");
		$this->Product = new Product();
		$product_list = $this->Product->find("list",array("conditions"=>array("Product.id"=>$product_id),"fields"=>array("id","title")));
		return $product_list;
		
	}
	
	/*
	*Function for upload csv file of coupon
	*/
	
	function admin_upload_coupon_csv(){
		
		$this->layout = "backend/product";
		$this->set("title_for_layout",UPLOAD_COUPON);
		
		App::import("Model","Category");
		$this->Category = new Category();
		$fields = array("Category.id","Category.title");
		$category_data = $this->Category->find("all",array("conditions"=>array("Category.is_deleted"=>0),"order"=>"Category.title asc","fields"=>$fields,"contain"=>array("Product"=>array("conditions"=>array("Product.is_deleted"=>0),'fields'=>array("Product.id","Product.title")))));
		$this->set("category_data",$category_data);
		
		if(!empty($this->data)){
			
			$errors = array();
			if(($this->data['Coupon']['product_id'] != "") && ($this->data['Coupon']['category_id'] != "") && ($this->data['Coupon']['category_title'] != "") && ($this->data['Coupon']['csv_file']['name'] != "") ){
				App::import("Component","Upload");
				$upload = new UploadComponent();
				$file = $this->data['Coupon']['csv_file'];
				$name = $this->data['Coupon']['csv_file']['name'];
				$path_info = pathinfo($this->data['Coupon']['csv_file']['name']);
				$file_extension = strtolower($path_info['extension']);
				if($file_extension == 'csv'){
					$directory_path = $this->create_directory("coupon_csv_upload");
					$file_name = $upload->upload($file,$directory_path,$name,null,array($file_extension));
					if($file_name){
						$result = $this->Coupon->save_csv($directory_path.$name,$this->data);
						if($result){
							unlink($directory_path.$name);
							$this->Session->setFlash(RECORD_SAVE, 'message/green');
							$this->redirect(array("controller"=>"coupons","action"=>"upload_coupon_csv","admin"=>true));
						}else{
							unlink($directory_path.$name);
							$errors['Product'][] = WRONG_PRODUCT_CATEGORY;
							$this->set("errors",$errors);
						}
						
					}else{
						
						$errors['Product'][] = NOT_UPLODED_CSV;
						$this->set("errors",$errors);
					}
				}else{
					$errors['Product'][] = ERR_COUPON_CSV;
					$this->set("errors",$errors);
				}
			}else{
				if(($this->data['Coupon']['product_id'] == "") && ($this->data['Coupon']['category_id'] == "")){
					$errors['Product'][] = ERR_PRODUCT_CATEGORY_EMPTY;	
				}
				
				if($this->data['Coupon']['csv_file']['name'] == ""){
					$errors['Product'][] = ERR_COUPON_CSV;
				}
			}
			$this->set("errors",$errors);
		}
	}
	
	/*
	*function for find the all product detail
	*/
	function product_detail_json($product_id = null){
		
		$this->layout = false;
		$this->autoRender = false;
		//$product_id = DECRYPT_DATA($product_id);
		App::import("Model","Product");
		$this->Product = new Product();
		if(isset($product_id)){
		    if($this->is_id_exist($product_id,"Product")){
			//$data = $this->Product->find('first',array("recursive"=>-1,"conditions"=>array("Product.id"=>$product_id),"fields"=>array("id","category_id","title")));
			$fields = array("Product.id","Product.category_id","Product.title","Category.title");
			$data = $this->Product->find('first',array("fields"=>$fields,"conditions"=>array("Product.id"=>$product_id)));
			echo json_encode($data['Product']);
		    }
		}
        }
 	
}