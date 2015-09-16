<?php

/*
* Vendors Controller
*/

class VendorsController extends AppController {
	 
	var $name = 'Vendors';
	var $components = array('Auth','Session','RequestHandler','Common');
	var $helpers = array('Form', 'Html', 'Js','Common','Paginator');
 	
					
	/*
	* This function will executed bydefualt when run any funtion of this controller 
	*/
	
	function beforeFilter(){
		parent::beforeFilter();  
	}
	
	/*
	* Function for vendor listing
	*/

 	function admin_list(){
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",VENDOR_LISTING);
		$conditions = array("Vendor.is_deleted"=>"0");
		
		$name = isset($this->params['url']['name'])?$this->params['url']['name']:(isset($this->params['named']['name'])?$this->params['named']['name']:"");
		if(trim($name) != ""){
			$conditions = array_merge($conditions ,array("Vendor.name like"=>"%".trim($name)."%"));
		}		
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		if(trim($is_active) != ""){
			$conditions = array_merge($conditions ,array("Vendor.is_active"=>$is_active));
		}
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
            
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Vendor.created,'%Y-%m-%d') >="=>trim($from)));
		}
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Vendor.created,'%Y-%m-%d') <="=>trim($to)));
		}
		
		$this->paginate = array('limit' =>VENDOR_LIMIT,'conditions'=>$conditions,'order'=>array("Vendor.created"=>"desc"));
		$vendor_data = $this->paginate('Vendor');  
		$this->set('vendor_data',$vendor_data);
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
			    if($model_name == "Vendor"){
				App::import("Model","Product");
				$this->Product = new Product();
				$this->Product->updateAll(array("Product.is_deleted"=>"'1'"),array("Product.vendor_id"=>$infected_records));
				App::import("Model","Coupon");
				$this->Coupon = new Coupon();
				$this->Coupon->updateAll(array("Coupon.is_deleted"=>"'1'"),array("Coupon.vendor_id"=>$infected_records));
			    }
			    
			    
			    $this->Session->setFlash(RECORD_DELETED, 'message/green');
			}
			$this->redirect($this->referer());exit();	
		}
	}
 
	/*
	* Function for activate/deactivate vendor
	*/
	
	function admin_activate_vendor($is_active = null,$cat_id = null,$model_name = null){
		
		$this->layout = "";
		$this->autoRender = false;
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
	* Function for delete vendor
	*/
	
	function admin_delete_vendor($vendor_id = null){
		
		$this->layout = "";
		$this->autoRender = false;
		$vendor_id = DECRYPT_DATA($vendor_id);
		if(isset($vendor_id)){
			$this->Vendor->updateAll(array("Vendor.is_deleted"=>"'1'"),array("Vendor.id"=>$vendor_id));
			App::import("Model","Product");
			$this->Product = new Product();
			$this->Product->updateAll(array("Product.is_deleted"=>"'1'"),array("Product.vendor_id"=>$vendor_id));
			App::import("Model","Coupon");
			$this->Coupon = new Coupon();
			$this->Coupon->updateAll(array("Coupon.is_deleted"=>"'1'"),array("Coupon.vendor_id"=>$vendor_id));
			$this->Session->setFlash(RECORD_DELETED, 'message/green');
		}
		$this->redirect($this->referer());exit();	
	}
 
	/*
	* 	This function used for Add Vendor
	*/

 	function admin_add(){
		
 		$this->layout='backend/backend';
		$this->set("title_for_layout",ADD_VENDOR);		
		if(!empty($this->data)){				
				
			/*** Then, to check if the data validates, use the validates method of the model, 
			 * which will return true if it validates and false if it doesn’t:
	 		 */ 
			$errors = $this->Vendor->validate_data($this->data);
			if(count($errors) == 0){
				
				App::import("Component","Upload");
				$upload = new UploadComponent();
				$allowed_ext = array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG');
				$path_info = pathinfo($this->data['Vendor']['image']['name']);
				$file_extension = strtolower($path_info['extension']);
				if(in_array($file_extension,$allowed_ext)){
					$file = $this->data['Vendor']['image'];
					$thumb_directory_path = $this->create_directory("vendor_image_thumb");
					$actual_directory_path = $this->create_directory("vendor_image_actual");
					$filename = str_replace(array(" ","."),"",md5(microtime())).".".$path_info['extension'];
					$rules['type'] = 'resizecrop';
					$rules['size'] = array (50,50);    
					$file_name = $upload->upload($file,$thumb_directory_path,$filename,$rules,$allowed_ext);
					$file_name = $upload->upload($file,$actual_directory_path,$filename,null,$allowed_ext);
					if($file_name){
						$data = $this->data;
						$data['Vendor']['image'] = $filename;
						if($this->Vendor->save($data)){
							$this->Session->setFlash(RECORD_SAVE, 'message/green');
							$this->redirect(array('controller'=>"vendors",'action'=>'list','admin'=>true)); 
						}else{
							$this->Session->setFlash(RECORD_ERROR, 'message/red');
							$this->redirect($this->referer()); 
						}
					}
				}else{
					$errors['image'][] = ERR_IMAGE_TYPE;
				}
			}
			$this->set("errors",$errors);
		}
		
 	}
	
	
	/*
	* This function used for Edit Vendor
	*/

 	function admin_edit($vendor_id=null){
		
 		$vendor_id = DECRYPT_DATA($vendor_id);
		$this->layout='backend/backend';
		$this->set("title_for_layout",EDIT_VENDOR);
		if(!empty($this->data)){
			$data = $this->data;
			$data['Vendor']['id'] = DECRYPT_DATA($data['Vendor']['id']);
			$errors = $this->Vendor->validate_data($data);
			if(count($errors) == 0){
				if($this->data['Vendor']['image']['name'] != ""){
					
					App::import("Component","Upload");
					$upload = new UploadComponent();
					$allowed_ext = array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG');
					$path_info = pathinfo($this->data['Vendor']['image']['name']);
					$file_extension = strtolower($path_info['extension']);
					if(in_array($file_extension,$allowed_ext)){
						$file = $this->data['Vendor']['image'];
						$thumb_directory_path = $this->create_directory("vendor_image_thumb");
						$actual_directory_path = $this->create_directory("vendor_image_actual");
						$filename = str_replace(array(" ","."),"",md5(microtime())).".".$path_info['extension'];
						$rules['type'] = 'resizecrop';
						$rules['size'] = array (75,50);    
						
						
						if(file_exists($thumb_directory_path.$data['Vendor']['previous_image'])) {
						   unlink($thumb_directory_path.$data['Vendor']['previous_image']);
						}
						if(file_exists($actual_directory_path.$data['Vendor']['previous_image'])) {
						     unlink($actual_directory_path.$data['Vendor']['previous_image']);
						}
						$file_name = $upload->upload($file,$thumb_directory_path,$filename,$rules,$allowed_ext);
						$file_name = $upload->upload($file,$actual_directory_path,$filename,null,$allowed_ext);
						if($file_name){
							unset($data['Vendor']['previous_image']);
							$data['Vendor']['image'] = $filename;
							if($this->Vendor->save($data)){
								$this->Session->setFlash(RECORD_SAVE, 'message/green');
								$this->redirect(array('controller'=>"vendors",'action'=>'list','admin'=>true)); 
							}else{
								$this->Session->setFlash(RECORD_ERROR, 'message/red');
								$this->redirect($this->referer()); 
							}
						}
					}else{
						$errors['image'][] = ERR_IMAGE_TYPE;
					}
				}else{
					unset($data['Vendor']['image']);
					unset($data['Vendor']['previous_image']);
					if($this->Vendor->save($data)){
						$this->Session->setFlash(RECORD_SAVE, 'message/green');
						$this->redirect(array("controller"=>"vendors","action"=>"list","admin"=>true)); 
					}else{
						$this->Session->setFlash(RECORD_ERROR, 'message/red');
						$this->redirect(array("controller"=>"vendors","action"=>"edit",$this->data['Vendor']['id'],"admin"=>true));
					}
				}
			}
			$this->set("errors",$errors);
			
		}else if(isset($vendor_id)){
			
			if($this->is_id_exist($vendor_id,"Vendor")){
				$this->Vendor->id = $vendor_id;
				$data = $this->Vendor->read();
				$data['Vendor']['id'] = ENCRYPT_DATA($data['Vendor']['id']);
				$this->data = $data;
			}else{
				$this->Session->setFlash(NOT_FOUND_ERROR, 'message/red');
				$this->redirect(array("controller"=>"products",'action'=>'list','admin'=>true));exit();
			}
		}
		
 	}
	
	
	/*
	*This function is used for Validation check list by using Ajax
	**/
	function admin_validate_data_ajax(){
		$this->layout = "";
		$this->autoRender = false;
		if($this->RequestHandler->isAjax()){
			$errors_msg = null;
			$data = $this->data;
			if(isset($this->data['Vendor']['id'])){
				$data['Vendor']['id'] = DECRYPT_DATA($data['Vendor']['id']);
			}
			
			$errors = $this->Vendor->validate_data($data);
			
			if ( is_array ($this->data) ){
				foreach ($this->data['Vendor'] as $key => $value ){
					if( array_key_exists ( $key, $errors) ){
						foreach ( $errors [ $key ] as $k => $v ){
							$errors_msg .= "error|$key|$v";
						}	
					}
					else {
						$errors_msg .= "ok|$key\n";
					}
				}
			    
			}
			echo $errors_msg;die();
		}	
	}
	
	/*
	* Function for vendor listing
	*/

 	function admin_vendor_report(){
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",VENDOR_REPORT);
		$conditions = array("Vendor.is_deleted"=>"0");
		
		$name = isset($this->params['url']['name'])?$this->params['url']['name']:(isset($this->params['named']['name'])?$this->params['named']['name']:"");
		if(trim($name) != ""){
			$conditions = array_merge($conditions ,array("Vendor.name like"=>"%".trim($name)."%"));
		}		
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		
		if(trim($is_active) != ""){
			$conditions = array_merge($conditions ,array("Vendor.is_active"=>$is_active));
		}
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
            
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Vendor.modified,'%Y-%m-%d') >="=>trim($from)));
		}
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Vendor.modified,'%Y-%m-%d') <="=>trim($to)));
		}
		
		$sort = isset($this->params['url']['sort'])?$this->params['url']['sort']:(isset($this->params['named']['sort'])?$this->params['named']['sort']:"total_price");
		$direction = isset($this->params['url']['direction'])?$this->params['url']['direction']:(isset($this->params['named']['direction'])?$this->params['named']['direction']:"desc");
		
		$field = array('Vendor.*','sum(CouponSale.price) AS total_price');
		$join_coupon_sell = array('table'=>'coupon_sales','alias'=>'CouponSale','forignKey'=>false,'type'=>'left','conditions'=>array('CouponSale.vendor_id = Vendor.id'));
		
		$this->paginate = array("fields"=>$field,"joins"=>array($join_coupon_sell),"conditions"=>$conditions,"limit"=>VENDOR_LIMIT,'group' => 'Vendor.id','passit' => array("sort"=>$sort,"direction"=>$direction));
		$vendor_data = $this->paginate('Vendor');
		$this->set('vendor_data',$vendor_data);
		
		$csv_export = isset($this->params['url']['export_to_csv'])?$this->params['url']['export_to_csv']:(isset($this->params['named']['export_to_csv'])?$this->params['named']['export_to_csv']:"paginate");
		if($csv_export=="csv"){
		    $this->layout = "";
		    $this->generate_vendor_report_csv($vendor_data);
		}
		
		$pdf_export = isset($this->params['url']['export_to_pdf'])?$this->params['url']['export_to_pdf']:(isset($this->params['named']['export_to_pdf'])?$this->params['named']['export_to_pdf']:"paginate");
		if($pdf_export == "pdf"){
			$this->layout = "";
			$this->generate_vendor_report_pdf($vendor_data);
		}
		
	}
	
	/*
	* Function for generating vendor report csv
	*/
	
	function generate_vendor_report_csv($vendor_data=array()){
		
		App::import('Helper','csv');
		$csv = new csvHelper();
		$line = array('Vendor Name','Status','Total Sale(DKK)','Modified');
		$csv->addRow($line);
		if(!empty($vendor_data)){
			$status = array('Deactive','Active');
			
			foreach($vendor_data as $data){
			$sold_price = $data[0]['total_price'];
			$line = array(ucfirst($data['Vendor']['name']),$status[$data['Vendor']['is_active']],sprintf('%0.2f',$sold_price),$data['Vendor']['modified']);
			$csv->addRow($line);
			}
			
		}
		echo $csv->render("vendor_report".date("d/M/Y"));
		exit();
	}
	
	/*
	* Function for generating vendor report pdf
	*/
	
	function generate_vendor_report_pdf($data = null){
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
                $htmlcontent.="<strong><h2>Vendor Report</h2></strong>";
                
                $htmlcontent.="<br/>";
                // table start
                $htmlcontent.="<table border=\".5\" >
                <tr>
                        <td align='left' valign='top'><strong>Vendor Name</strong></td>
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
                                $htmlcontent.= ucfirst($result['Vendor']['name']); 
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$status[$result['Vendor']['is_active']];
                                $htmlcontent.="</td><td align='right' valign='top'>";
                                $htmlcontent.=sprintf("%0.2f",$total_sale);
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$result['Vendor']['modified'];
                                $htmlcontent.="</td></tr>";
                        }
                }
                else {
                    $htmlcontent.="<tr><td colspan='4' style='border-bottom:0px' class='tc'>No records found</td></tr>";
                }
                
                
                $htmlcontent.="</table></body></html>";	    
                
                // output the HTML content	    
                $tcpdf->writeHTML($htmlcontent, true, 0, true, 0);
                
                $tcpdf->Output('vendor_report.pdf', 'D');
                exit();
	}
	
	/*
	* Function for vendor listing
	*/

 	function admin_coupon_report(){
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",COUPON_REPORT);
		App::import("Model","Coupon");
		$this->Coupon = new Coupon();
		App::import("Model","Product");
		$this->Product = new Product();
		
		App::import("Model","User");
		$this->User = new User();
		$user_profile_join = array('table'=>'user_profiles','alias'=>'UserProfile','forignKey'=>false,'type'=>'left','conditions'=>array('UserProfile.user_id = User.id'),'order'=>array("UserProfile.first_name"=>"DESC"));
		
		$dealer_data = $this->User->find("list",array("fields"=>array("id","UserProfile.first_name"),"joins"=>array($user_profile_join),"conditions"=>array("User.is_deleted"=>"0","User.is_active"=>"1","User.is_dealer"=>"1")));
		$this->set("dealer_data",$dealer_data);
		
		$product_data = $this->Product->find("all",array("fields"=>array("Product.product_code","Product.title","Product.vendor_id"),"conditions"=>array("Product.is_deleted"=>"0","Product.is_active"=>"1"),"order"=>"Product.product_code asc"));
		
		
		App::import("Model","Vendor");
		$this->Vendor = new Vendor();
		$vendor_data = $this->Vendor->find("list",array("fields"=>array("Vendor.id","Vendor.name"),"conditions"=>array("Vendor.is_deleted"=>"0","Vendor.is_active"=>"1"),"order"=>"Vendor.name asc"));
		$this->set("vendor_data",$vendor_data);
		
		$common_option = array();
		
		foreach($vendor_data as $k=>$v){
			
			$common_option[$k.'_vendor'] = $v;
			foreach($product_data as $k1=>$v1){
				if($v1['Product']['vendor_id'] == $k){
					$common_option[$v1['Product']['product_code']] = '&nbsp;&nbsp;&nbsp;'.$v.'-&nbsp;'.$v1['Product']['title'];
				}
			}
		}
		$this->set("product_data",$common_option);
		
		App::import("Model","Batch");
		$this->Batch = new Batch();
		$batch_data = $this->Batch->find("list",array("fields"=>array("Batch.id","Batch.name"),"conditions"=>array("Batch.is_deleted"=>"0","Batch.is_active"=>"1"),"order"=>"Batch.name asc"));
		$this->set("batch_data",$batch_data);
		
		$conditions = array("Coupon.is_deleted"=>"0","Coupon.is_pulled"=>"0");
		
		$p_code = isset($this->params['url']['product_code'])?trim($this->params['url']['product_code']):(isset($this->params['named']['product_code'])?$this->params['named']['product_code']:"");
		if(trim($p_code) != ""){
			if(strpos($p_code,"_") === false){
				$conditions = array_merge($conditions ,array("Coupon.product_code"=>$p_code));
			}else{
				$conditions = array_merge($conditions ,array("Coupon.vendor_id"=>substr($p_code,0,strpos($p_code,'_'))));
			}
			
		}
		
		$batch_id = isset($this->params['url']['batch_id'])?trim($this->params['url']['batch_id']):(isset($this->params['named']['batch_id'])?$this->params['named']['batch_id']:"");
		if(trim($batch_id) != ""){
			$conditions = array_merge($conditions ,array("Coupon.batch_id"=>$batch_id));
		}
		
		$c_code = isset($this->params['url']['c_code'])?trim($this->params['url']['c_code']):(isset($this->params['named']['c_code'])?$this->params['named']['c_code']:"");
		if(trim($c_code) != ""){
			$conditions = array_merge($conditions ,array("Coupon.coupon_id"=>$c_code));
		}
		
		$is_sold = isset($this->params['url']['is_sold'])?trim($this->params['url']['is_sold']):(isset($this->params['named']['is_sold'])?$this->params['named']['is_sold']:"");
		if(trim($is_sold) != ""){
			$conditions = array_merge($conditions ,array("Coupon.is_sold"=>$is_sold));
		}
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
		$expire = isset($this->params['url']['expire'])?$this->params['url']['expire']:(isset($this->params['named']['expire'])?$this->params['named']['expire']:"");
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Coupon.modified,'%Y-%m-%d') >="=>trim($from)));
		}
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Coupon.modified,'%Y-%m-%d') <="=>trim($to)));
		}
		
		
		$sort = isset($this->params['url']['sort'])?$this->params['url']['sort']:(isset($this->params['named']['sort'])?$this->params['named']['sort']:"created");
		
		$direction = isset($this->params['url']['direction'])?$this->params['url']['direction']:(isset($this->params['named']['direction'])?$this->params['named']['direction']:"desc");
		$order = $sort." ".$direction;
		
		$de_id = isset($this->params['url']['de_i'])?trim($this->params['url']['de_i']):(isset($this->params['named']['de_i'])?$this->params['named']['de_i']:"");
		if(trim($de_id) != ""){
			$conditions = array_merge($conditions ,array("Coupon.id_dealer"=>$de_id));
		}
		
		
		$field = array("Coupon.*");
		
		$this->paginate = array("recursive"=>-1,"fields"=>$field,"conditions"=>$conditions,'order' =>$order);
		$coupon_data = $this->paginate('Coupon');
		
		$this->set('coupon_data',$coupon_data);
		
		$csv_export = isset($this->params['url']['export_to_csv'])?$this->params['url']['export_to_csv']:(isset($this->params['named']['export_to_csv'])?$this->params['named']['export_to_csv']:"");
		if($csv_export=="csv"){
		    $this->layout = "";
		    $this->generate_coupon_report_csv($coupon_data,$batch_data,$common_option);
		}
		
		$pdf_export = isset($this->params['url']['export_to_pdf'])?$this->params['url']['export_to_pdf']:(isset($this->params['named']['export_to_pdf'])?$this->params['named']['export_to_pdf']:"");
		if($pdf_export == "pdf"){
			$this->layout = "";
			$this->generate_coupon_report_pdf($coupon_data,$batch_data,$common_option);
		}
		
	}
	
	/*
	* Function for generating vendor report csv
	*/
	
	function generate_coupon_report_csv($c_data=array(),$b_data=array(),$p_data=array()){
		
		App::import('Helper','csv');
		$csv = new csvHelper();
		$line = array('Product','Product Code','Batch','Coupon Id','Sold','Sale Time');
		$csv->addRow($line);
		if(!empty($c_data)){
			
			$status_sold = array(1=>"Yes",0=>"No");
			
			foreach($c_data as $data){
			$sale_time = ($data['Coupon']['is_sold'] == "1")?$data['Coupon']['modified']:"Not yet";
			$line = array(preg_replace("/&#?[a-z0-9]+;/i","",$p_data[$data['Coupon']['product_code']]),$data['Coupon']['product_code'],$b_data[$data['Coupon']['batch_id']],$data['Coupon']['coupon_id'],$status_sold[$data['Coupon']['is_sold']],$sale_time);
			$csv->addRow($line);
			}
			
		}
		echo $csv->render("coupon_report".date("d/M/Y"));
		exit();
	}
	
	/*
	* Function for generating vendor report pdf
	*/
	
	function generate_coupon_report_pdf($data = null,$b_data=array(),$p_data=array()){
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
                $htmlcontent.="<strong><h2>Coupon Report</h2></strong>";
                
                $htmlcontent.="<br/>";
                // table start
                $htmlcontent.="<table border=\".5\" >
                <tr>
			<td align='left' valign='top'><strong>Products</strong></td>
                        <td align='left' valign='top'><strong>Product Code</strong></td>
			<td align='left' valign='top'><strong>Batch</strong></td>
			<td align='left' valign='top'><strong>Coupon Id</strong></td>
			<td align='left' valign='top'><strong>Sold</strong></td>
			<td align='left' valign='top'><strong>Sale Time</strong></td>
                </tr>";
                $charges="No";
                if(count($data)>0){ 
                        $status = array('Deactive','Active');
                        $status_sold = array(1=>"Yes",0=>"No");
			
			foreach($data as $result){
				$sale_time = ($result['Coupon']['is_sold'] == "1")?$result['Coupon']['modified']:"Not yet";
				$htmlcontent.="<tr><td align='left' valign='top'>";
                                $htmlcontent.= preg_replace("/&#?[a-z0-9]+;/i","",$p_data[$result['Coupon']['product_code']]);
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.= $result['Coupon']['product_code'];
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.= $b_data[$result['Coupon']['batch_id']]; 
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.= $result['Coupon']['coupon_id']; 
                                
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$status_sold[$result['Coupon']['is_sold']];
                                
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$sale_time;
                                $htmlcontent.="</td></tr>";
                        }
                }
                else {
                    $htmlcontent.="<tr><td colspan='5' style='border-bottom:0px' class='tc'>No records found</td></tr>";
                }
                
                
                $htmlcontent.="</table></body></html>";	    
                
                // output the HTML content	    
                $tcpdf->writeHTML($htmlcontent, true, 0, true, 0);
                
                $tcpdf->Output('coupon_report.pdf', 'D');
                exit();
	}
	
	/*
	*	Function to generate weekly csv
	*/
	function admin_weekly_csv(){
		$this->layout='backend/backend';
		$this->set("title_for_layout",COUPON_REPORT);
		
		App::import("Model","CouponSale");
		$this->CouponSale = new CouponSale();
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
		
		if((trim($from) != "") &&  (trim($to) != "")){
			$from = $this->covertToSystemDate($from);
			$to = $this->covertToSystemDate($to);
			$dealer_join = array('table'=>'users','alias'=>'Dealer','forignKey'=>false,'type'=>'inner','conditions'=>array('Dealer.id = CouponSale.dealer_id'));
			$product_join = array('table'=>'products','alias'=>'Product','forignKey'=>false,'type'=>'inner','conditions'=>array('Product.product_code = CouponSale.product_code'));
			
			
			$query = "SELECT p.p_id,u.u_id,(select case when(`coupon_sales`.`parent_id`!=0) then `coupon_sales`.`parent_id` else `dealer_id` end) as dealer_id,count(*) as total FROM `coupon_sales` INNER JOIN users u on u.id=`coupon_sales`.dealer_id INNER JOIN products p on p.product_code =`coupon_sales`.product_code WHERE `coupon_sales`.`created` >='".date('Y-m-d h:i:s',strtotime($from))."' AND `coupon_sales`.`created`<= '".date('Y-m-d h:i:s',strtotime($to))."' group by (select case when(`coupon_sales`.`parent_id`!=0) then `coupon_sales`.`parent_id` else `dealer_id` end)";
			
			$coupon_sale_data = $this->CouponSale->query($query);
			
			//$coupon_sale_data = $this->CouponSale->find("all",array("fields"=>array("Dealer.u_id","Product.title","count(CouponSale.product_code) as total_sale"),"conditions"=>array("DATE_FORMAT(CouponSale.created,'%Y-%m-%d') >="=>trim($from),"DATE_FORMAT(CouponSale.created,'%Y-%m-%d') <="=>trim($to),"OR"=>array(array("CouponSale.dealer_id"),array("CouponSale.parent_id"))),"joins"=>array($dealer_join,$product_join),"group"=>"CouponSale.category_id"));
			$csv_export = isset($this->params['url']['export_to_csv'])?$this->params['url']['export_to_csv']:(isset($this->params['named']['export_to_csv'])?$this->params['named']['export_to_csv']:"");
			if($csv_export=="csv"){
			    $this->layout = "";
			    $this->generate_weekly_report_csv($coupon_sale_data);
			}
			
			$txt_export = isset($this->params['url']['export_to_txt'])?$this->params['url']['export_to_txt']:(isset($this->params['named']['export_to_txt'])?$this->params['named']['export_to_txt']:"");
			if($txt_export=="txt"){
			    $this->layout = "";
			    $this->generate_weekly_report_txt($coupon_sale_data);
			}
		}
	}
	
	
	
	/*
	* Function for generating vendor report txt
	*/
	
	function generate_weekly_report_txt($c_data=array()){
		
		App::import('Helper','txt');
		$txt = new txtHelper();
		$line = array("Store number","Terminal","Vendor","Product","Item","Face Value","Billed downloads","Billed returns","Net Billed Downloads","Net Face Value","Net Cost","Net Commission","Merchant","Site","Street","Postcode","City","Duplicate Downloads","All Downloads","Duplicate Returns","All Returns","Net All Downloads","Download Face Value","Download Cost","Download Commission","Return Face Value","Return Cost","Return Commission","From","To");
		$txt->addRow($line);
		if(!empty($c_data)){
			foreach($c_data as $data){
			
				$line = array($data['u']['u_id'],'X','X','X',$data['p']['p_id'],'X',$data['0']['total'],'X',$data['0']['total'],'X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X');
				$txt->addRow($line);
			}
			
		}
		echo $txt->render(date("d_M_Y_h_i_s")."_weekly");
		exit();
	}
	
	/*
	* Function for generating vendor report csv
	*/
	
	function generate_weekly_report_csv($c_data=array()){
		
		App::import('Helper','csv');
		$csv = new csvHelper();
		$line = array("Store number","Terminal","Vendor","Product","Item","Face Value","Billed downloads","Billed returns","Net Billed Downloads","Net Face Value","Net Cost","Net Commission","Merchant","Site","Street","Postcode","City","Duplicate Downloads","All Downloads","Duplicate Returns","All Returns","Net All Downloads","Download Face Value","Download Cost","Download Commission","Return Face Value","Return Cost","Return Commission","From","To");
		$csv->addRow($line);
		if(!empty($c_data)){
			foreach($c_data as $data){
			
				$line = array($data['u']['u_id'],'X','X','X',$data['p']['title'],'X',$data['0']['total'],'X',$data['0']['total'],'X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X','X');
				$csv->addRow($line);
			}
			
		}
		echo $csv->render("weekly".date("d/M/Y"));
		exit();
	}
}
?>