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
		$conditions = array("Coupon.is_deleted"=>"0","Coupon.is_pulled"=>"0","Coupon.is_sold"=>"0");
		$category_list = $this->find_categories_list();
		$this->set("category_list",$category_list);
		$vendor_list = $this->find_vendor_listing();
		$this->set("vendor_list",$vendor_list);
		
		App::import("Model","Batch");
		$this->Batch = new Batch();
		$batch_data = $this->Batch->find("list",array("fields"=>array("Batch.id","Batch.name"),"conditions"=>array("Batch.is_deleted"=>"0","Batch.is_active"=>"1"),"order"=>"Batch.name asc"));
		$this->set("batch_data",$batch_data);		
		
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
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Coupon.expire_date,'%Y-%m-%d') >="=>trim($from)));
		}
		
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Coupon.expire_date,'%Y-%m-%d') <="=>trim($to)));
		}
		
		$this->paginate = array('recursive'=>-1,'limit' =>COUPON_LIMIT,'conditions'=>$conditions,'order'=>array("Coupon.expire_date"=>"desc"));
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
		$vendor_list = $this->find_vendor_listing();
		$this->set("vendor_list",$vendor_list);
		
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
		$vendor_list = $this->find_vendor_listing();
		$this->set("vendor_list",$vendor_list);
		
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
				$this->set("product_options",$this->find_product_list($this->data['Coupon']['product_code']));
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
			if(isset($this->data['Coupon']['id'])){
				$data['Coupon']['id'] = DECRYPT_DATA($data['Coupon']['id']);
			}
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
			$product_list = $this->Product->find('list',array('conditions'=>array('category_id'=>$id),'fields'=>array('product_code','product_code')));
			$option ="<option>Select product code</option>";
			foreach($product_list as $k=>$v){
				$option.= "<option value=".$k.">".$v."</option>";
			}
			echo $option;
		}
	}
 
	function find_product_list($product_id = null){
		
		App::import("Model","Product");
		$this->Product = new Product();
		$product_list = $this->Product->find("list",array("conditions"=>array("Product.product_code"=>$product_id),"fields"=>array("product_code","product_code")));
		return $product_list;
		
	}
	
	/*
	*Function for upload csv file of coupon
	*/
	
	function admin_upload_coupon_csv(){
		@set_time_limit(0); 
		$this->layout = "backend/backend";
		$this->set("title_for_layout",UPLOAD_COUPON);
		
		App::import("Model","Category");
		$this->Category = new Category();
		$fields = array("Category.id","Category.title");
		$category_data = $this->Category->find("all",array("conditions"=>array("Category.is_deleted"=>0),"order"=>"Category.title asc","fields"=>$fields,"contain"=>array("Product"=>array("conditions"=>array("Product.is_deleted"=>0),'fields'=>array("Product.id","Product.title","product_code")))));
		$this->set("category_data",$category_data);
		
		if(!empty($this->data)){
			$name = $_POST['fileToUpload_one'];   
			$directory_path = CSV_UPLOAD_PATH;  
			$uploaded_file = $directory_path.$name;
			$handle = fopen($uploaded_file, "r");
			  
			//read the 1st row as headings
			$header = fgetcsv($handle);
			$header_new = explode(";",$header[0]);   
			
			if(!empty($header_new) && count($header_new) == 3){				
				$this->request->data['Coupon']['expire_date'] =	$header_new['0']; 
				$this->request->data['Coupon']['category_title'] = 	$header_new['1']; 
				
				App::import("Model","Product");
				$this->Product = new Product();
				$product_data = $this->Product->find("first",array("fields"=>array('Product.product_code','Product.category_id','Product.vendor_id'),"conditions"=>array("Product.product_code" => $header_new['2'],"Product.is_active"=> 1,"Product.is_deleted"=> 0),'recursive' => -1));
				 
				if(!empty($product_data) && is_array($product_data)){  
					foreach ($product_data as $key => $value) {  
						$this->request->data['Coupon']['category_id'] = $value['category_id'];	
						$this->request->data['Coupon']['product_code'] = $value['product_code'];	
						$this->request->data['Coupon']['vendor_id'] = $value['vendor_id'];				
					}
				}else{
					$this->request->data['Coupon']['category_id'] = '';	
					$this->request->data['Coupon']['product_code'] = $header_new['2'];	
					$this->request->data['Coupon']['vendor_id'] = '';		
				} 
				
				
			}else{
				$this->request->data['Coupon']['category_id'] = '';	
				$this->request->data['Coupon']['product_code'] = '';		
				$this->request->data['Coupon']['vendor_id'] = '';		
			} 
			
			$errors = array();
			if($name != "" ){  
				App::import("Component","Upload");
				$upload = new UploadComponent();
				$path_info = pathinfo($name);
				$file_extension = strtolower($path_info['extension']);
				$allowed_ext=array('csv','txt');
				if(in_array($file_extension,$allowed_ext)){
					$file_name=$name; 
					if($file_name){
						 $result = $this->Coupon->save_csv($directory_path.$name,$this->data);
						if($result != 0){
							 
							unlink($directory_path.$name);
							$this->Session->setFlash($result.CSV_RECORD_SAVE, 'message/green');
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
				$errors['Product'][] = NOT_UPLODED_CSV;
				$this->set("errors",$errors);	
				
			}
			$this->set("errors",$errors);
		}
	}
	 
	function admin_coupon_csv_conformation(){
		$this->layout = "";
		$this->autoRender = false; 
		
		$fileElementName = 'fileToUpload';
		if(isset($_FILES[$fileElementName]) && is_array($_FILES[$fileElementName])){  
			$error = "";
			$msg = "";
			
			if(!empty($_FILES[$fileElementName]['error']))
			{
				switch($_FILES[$fileElementName]['error'])
				{
		
					case '1':
						$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
						break;
					case '2':
						$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
						break;
					case '3':
						$error = 'The uploaded file was only partially uploaded';
						break;
					case '4':
						$error = 'No file was uploaded.';
						break;
		
					case '6':
						$error = 'Missing a temporary folder';
						break;
					case '7':
						$error = 'Failed to write file to disk';
						break;
					case '8':
						$error = 'File upload stopped by extension';
						break;
					case '999':
					default:
						$error = 'No error code avaiable';
				}
				echo "{";
						echo	"error: '" . $error . "',\n";
				echo "}";
			}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none'){
				$error = 'No file was uploaded..';
				echo "{";
					echo	"error: '" . $error . "',\n";
				echo "}";
			}else{
				$msg .= " File Name: " . $_FILES['fileToUpload']['name'] . ", ";
				$msg .= " File Size: " . @filesize($_FILES['fileToUpload']['tmp_name']);
				
				$uploads_dir = CSV_UPLOAD_PATH;
				$timestamp = time(); 
			 
				$tmp_name = $_FILES["fileToUpload"]["tmp_name"];
				$name = $_FILES["fileToUpload"]["name"];
				$upload_dir=$uploads_dir.$name; 
				$csvfilename=$name;

				$path_parts = pathinfo($upload_dir);
				$extension=$path_parts['extension']; 
				
				$allowed_ext=array('csv','txt');
					if(in_array($extension,$allowed_ext)){ 
						if(move_uploaded_file($tmp_name,$upload_dir) ){
								 
						  		App::import("Model","Coupon");
								$this->Coupon = new Coupon();
							 	$all_coupon=$this->Coupon->find('list',array('fields' => array('Coupon.coupon_id','Coupon.id'),'conditions' => array('Coupon.is_sold'=>0)));
								 
								$duplicate_count=0;	
								
								$filename= $uploads_dir.$csvfilename;
							 	$data_count = 0;
								$handle = fopen($filename, "r"); 
								  					 	 	
								if($handle){
								  	$delimiter='';
								  	$header = fgetcsv($handle);
							 		//$header_new = explode(";",$header[0]); 
									$header_new = array();   
									if(preg_match("/[\t]/",$header[0])){
										$header_new = explode("\t",$header[0]); 
										$delimiter ='\t';
									}  
									if(preg_match("/[;]/",$header[0])){
										$header_new = explode(";",$header[0]); 
										$delimiter =';';
									}
									
									if(!empty($header_new) && count($header_new) == 3){	
										$expiry_date = $header_new['0'];
										$coupon_price = $header_new['1'];
										$coupon_product_code = $header_new['2'];
										App::import("Model","Product");
										$this->Product = new Product();
									 	$isProductCode=$this->Product->find('count',array("conditions"=>array("Product.product_code" =>$coupon_product_code,"Product.is_active"=> 1,"Product.is_deleted"=> 0) ));
							  			 
										 
										$expiry_date_tm = date("Y-m-d H:i:s",strtotime($expiry_date));
									    $valid_date_tm = date('Y-m-d H:i:s',time());
									    $valid_expDate=strtotime($expiry_date_tm) >= strtotime($valid_date_tm) ? true : false;
										
									 	if($valid_expDate){	 
											
											if($isProductCode !=0){											 	
													$myFile = $uploads_dir.'tmp_'.$csvfilename;
													$tmp_upfile = fopen($myFile, 'w');
													if($delimiter =='\t'){
														$firstline=array();
														$firstline[]=implode(';', $header_new);
														fputcsv($tmp_upfile, $firstline);
													}else{
														fputcsv($tmp_upfile, $header);
													} 
													//fputcsv($tmp_upfile, $header);
													// read each data row in the file
												 	$row = 1;
													$data_count = 0;
													$empty_data = 0;
													$total = 0;
													$uploaded_coupon_id=array();$tmp_csv_coupon_id_array = array(); 
													while (($data_csv = fgetcsv($handle)) !== FALSE) {
														$num = count($data_csv);
														$row++;
														$total++;
														for ($c=0; $c < $num; $c++) {
															$data_temp = explode($delimiter,$data_csv[$c]);
															if(preg_match("/[\t|-]/",$data_temp[0])){ // echo 'f';
																$data_temp[0]=trim($data_temp[0]);
																$data_temp[0] = preg_replace('/[\t|-]+/', ';',$data_temp[0]);
																//$data_temp[0] = preg_replace('/[-]+/', ';',$data_temp[0]);  
																$data_csv[0]=$data_temp[0];
															}
															//$activation_code = implode('|',$data_temp);
															//$data_save['Coupon']["activation_code"] = $activation_code;
															$csv_coupon_id='';
															$csv_coupon_ac_code='';
															//if(!empty($data_temp) && (!empty($activation_code))){
															if(!empty($data_temp)){
																$csvrow = implode(";", $data_temp);
																   
																$csv_coupon_id=substr($csvrow,0,strpos($csvrow, ';')); 
																$csv_coupon_id="{$csv_coupon_id}"; 
																	
																if((isset($all_coupon[$csv_coupon_id]))){
																	$duplicate_count +=1; 
																}else{
																	$csv_coupon_ac_code=substr($csvrow,strpos($csvrow, ';') + 1);
																	$csv_coupon_ac_code="{$csv_coupon_ac_code}";
																	 
																	if(preg_match ("/^[0-9]+$/",$csv_coupon_id) && preg_match ("/^[0-9]+[;]{0,}[0-9]+([;]{1}[0-9]+)*$/", $csv_coupon_ac_code)){
																		if((isset($tmp_csv_coupon_id_array[$csv_coupon_id]))){
																			$duplicate_count +=1;											
																		}else{
																			fputcsv($tmp_upfile, $data_csv); 
																			$data_count++;
																			$tmp_csv_coupon_id_array[$csv_coupon_id] = $csv_coupon_id;
																		}
																	}else{
																			$empty_data++;
																	}
																} 
															}else{
																$empty_data++;
															}
														}
													}
													fclose($handle);
													fclose($tmp_upfile); 
													
													//if($data_count != $duplicate_count && $data_count >0){
													if($data_count >0){  
														unlink($filename);
														rename($myFile, $filename);  
														 
														echo "{";
															echo	"error: '" . $error . "',\n"; 
														 	echo	"num: '" . $total . "',\n";
															echo	"duplicate_count: '" . $duplicate_count . "',\n";
														  	echo	"data_count: '" . $data_count . "',\n";
														   	echo	"empty_data: '" . $empty_data . "',\n";
															echo	"expiry_date: '" . $expiry_date . "',\n";
															echo	"coupon_price: '" . $coupon_price . "',\n";
															echo	"product_code: '" . $coupon_product_code . "',\n";
														echo "}";
													}else{
														unlink($filename);
														unlink($myFile);
														$r=($empty_data > 1)?'records':'record';
														$error="The duplicate $duplicate_count out of $total with $empty_data invalid $r.\\nPlease upload another file....";
														echo "{";
															echo	"error: '" . $error . "',\n";   
														echo "}";
													}
											}else{
												fclose($handle); 
												unlink($upload_dir);
												$error="Please upload file with valid product code...";
											 	echo "{";
													echo	"error: '" . $error . "',\n"; 
												echo "}";
											}
											
										}else{
											fclose($handle);
										 	unlink($upload_dir);
											$error="Please upload file with valid future date...Example :: yyyy-mm-dd";
										 	echo "{";
												echo	"error: '" . $error . "',\n"; 
											echo "}";
										}
									 
									}else{
											fclose($handle);
										   	unlink($upload_dir);
											$error="Please upload valid format of .$extension file..Example :: Exp_Date ;Coupon_Value;Product_Code";
										 	echo "{";
												echo	"error: '" . $error . "',\n"; 
											echo "}";
									}
							}else{
								$error="Unable to upload .$extension file..";
							 	echo "{";
									echo	"error: '" . $error . "',\n"; 
								echo "}";
							}
						  
						}else{	 
							$total='';
							$data_count='';
							$empty_data='';
							$error="Unable to upload .$extension file..";
						 	echo "{";
								echo	"error: '" . $error . "',\n"; 
							echo "}";
						}  
							
					 }else{
					 	$error="Please select file, which type should be .txt or .csv.";
					 	echo "{";
							echo	"error: '" . $error . "',\n"; 
						echo "}";
					 }  	
			} 
		
		}else{
			$this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));
		}
		
	}
	 
	/*
	* Function for Bulk coupon Sale  
	*/
	
 	function admin_coupon_pull(){
		$this->layout='backend/backend';
		$user=$this->Auth->user();
		if($user['role_id'] == 1) {
			$this->set("title_for_layout",LBL_COUPON_SALE);
			App::import("Model","Product");
			$this->Product = new Product();
			$Productdata = $this->Product->find('all',array('fields' => array('Product.id','Product.title','Product.product_code','Product.vendor_id'),'conditions' => array('Product.is_active' => '1','Product.is_deleted' => '0'),'recursive' => 0   ));
			$tmp_Productdata = $Productdata ;
			$Productdata = Set::extract('{n}.Product',$Productdata);  
			$tmp_Productdata = Set::extract('{n}.Product.product_code',$tmp_Productdata);   
			App::import("Model","Coupon"); 
			$this->Coupon = new Coupon(); 
			$Coupontdata = $this->Coupon->find('all',array('fields' => array('Coupon.product_code','COUNT(product_code) AS products_count'),'conditions' => array('Coupon.product_code' => $tmp_Productdata,'Coupon.is_sold' => '0','Coupon.is_deleted'=>'0' ) , 'group' => array('Coupon.product_code' ) ));
			
			$product_Coupon_key = Set::extract('{n}.Coupon.product_code',$Coupontdata);
			$product_Coupon_value = Set::extract('{n}/0/products_count',$Coupontdata);
			
			if(!empty($product_Coupon_key) && !empty($product_Coupon_value)) { 
				$product_Coupons=array();					
				$product_Coupons = array_combine($product_Coupon_key, $product_Coupon_value); 		
			} 	 
			
			App::import("Model","Vendor");
			$this->Vendor = new Vendor();
			$vendor_data = $this->Vendor->find("list",array("fields"=>array("Vendor.id","Vendor.name"),"conditions"=>array("Vendor.is_active"=>"1","Vendor.is_deleted"=>"0")));
			$product_data=array();
			
			foreach($Productdata as $key=>$value){ 
				$ky = ENCRYPT_DATA((string)$value['product_code']);
				
				if(!empty($product_Coupons) && isset($product_Coupons[$value['product_code']]) ){
					
					$value = $vendor_data[$value['vendor_id']].' '.$value['title']." {".$product_Coupons[$value['product_code']]."}";
				}else{ 
					$value = $value['title']." {0}";
				}
				$product_data[$ky] =  $value ;	
													 
			}
			
			$this->set("product_data",$product_data);  
				   
		}else{
			$this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));
		}
	}	
	
	/*
	* Function for Bulk sale coupon under specific product code/quantity
	*/
	function admin_bulksalecoupon($product_id=null,$counter=null){    
		
		$this->layout = "";
		$this->set("product_id",$product_id);
		$this->set("count_coupon",$counter);
		$product_id=DECRYPT_DATA($product_id);  
		 
			 
		if($this->RequestHandler->isAjax() || (!empty($product_id) && !empty($counter) && is_numeric($product_id) && is_numeric($counter))){
			App::import("Model","Product");
			$this->Product = new Product();
			if(!empty($this->data)){
				App::import("Model","Batch");
				$this->Batch = new Batch();
				$batch_data = $this->Batch->find("list",array("fields"=>array("Batch.id","Batch.name"),"conditions"=>array("Batch.is_deleted"=>"0","Batch.is_active"=>"1"),"order"=>"Batch.name asc"));
				App::import("Model","Vendor");
				$this->Vendor = new Vendor();
				$vendor_data = $this->Vendor->find("list",array("fields"=>array("Vendor.id","Vendor.name"),"conditions"=>array("Vendor.is_active"=>"1","Vendor.is_deleted"=>"0")));
				$product_data = $this->Product->find('first',array("fields"=>array("Product.vendor_id","Product.title"),'conditions'=>array("Product.product_code"=>$product_id)));
				$product_ttl = $product_data['Product']['title'];
				$vendor_name = $vendor_data[$product_data['Product']['vendor_id']];
				
				$coupon_data = $this->Coupon->find("all",array("recursive"=>-1,"fields"=>array("*"),"conditions"=>array("Coupon.id"=>$this->data['Coupon']['id'])));
				
				if(isset($this->data['export_to_csv']) && ($this->data['export_to_csv'] == "csv")){
					$this->generate_coupon_report_csv($coupon_data,$batch_data,$product_ttl,$vendor_name);
					
				}
				if(isset($this->data['export_to_pdf']) &&  ($this->data['export_to_pdf']== "pdf")){
					$this->generate_coupon_report_pdf($coupon_data,$batch_data,$product_ttl,$vendor_name);
				}
				
			}
		//if(!empty($product_id) && !empty($counter) && is_numeric($product_id) && is_numeric($counter)){
			 
			$ProductValid=$this->Product->find('list',array('fields'=>array('id','product_code'),'conditions' => array('Product.product_code' => $product_id,'Product.is_active' => '1','Product.is_deleted' => '0')) );
			 
			$result_message = "";
			$error_message = "";
			if(!empty($ProductValid) ){
				
				App::import("Model","Coupon");
				$this->Coupon = new Coupon();
		
				$ISCouponAvaliable=$this->Coupon->find('list',array('fields'=>array('id','coupon_id'),'conditions' => array('Coupon.product_code' => $product_id,'Coupon.is_sold' => '0',"Coupon.is_deleted" => '0'),'limit' => $counter) );
				$available=count($ISCouponAvaliable);
				 
				if($available == $counter){
					
					if($this->Coupon->updateAll(array("Coupon.is_pulled"=>"'1'","Coupon.is_sold"=>"'1'"),array("Coupon.coupon_id"=>$ISCouponAvaliable,"Coupon.is_deleted" => '0'))){
					$result_message="$available coupons are sold successfully !";
					$this->set('sold_CouponID',$ISCouponAvaliable);  
						
									
					}else{
						$error_message="Sorry,unable to complete invalid request !";	
					}
					
				}else{
					$coupons=($available > 1)?'coupons are': 'coupon is' ;
					$error_message="Sorry,There is only $available $coupons available !"; 
				} 
			}else{
					$error_message="Sorry,unable to complete invalid request !";  
			}
			$this->set('error_message',$error_message);  
			$this->set('result_message',$result_message);
		}else{
			$this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));
		}
	}
	
	/*
	* Function for generating vendor report csv
	*/
	
	function generate_coupon_report_csv($c_data=array(),$b_data=array(),$p_title=null,$v_name=null){
		
		App::import('Helper','csv');
		$csv = new csvHelper();
		$list[] = $line = array('Vendor','Product','Product Code','Batch','Coupon Id','Activation Code','Status','Sold','Expire Date','Sale Time');
		
		$csv->addRow($line);
		
		if(!empty($c_data)){
			$status = array('Deactive','Active');
			$status_sold = array(1=>"Yes",0=>"No");
			App::import("Model","Product");
			$this->Product = new Product();
			$product_data = $this->Product->find("list",array("recursive"=>-1,"fields"=>array("Product.product_code","Product.title"),"conditions"=>array("Product.is_deleted"=>"0","Product.is_active"=>"1")));
			foreach($c_data as $data){
				$sale_time = ($data['Coupon']['is_sold'] == "1")?$data['Coupon']['modified']:"Not yet";
				$line = array($v_name,$product_data[$data['Coupon']['product_code']],$data['Coupon']['product_code'],$b_data[$data['Coupon']['batch_id']],$data['Coupon']['coupon_id'],$data['Coupon']['activation_code'],$status[$data['Coupon']['is_active']],$status_sold[$data['Coupon']['is_sold']],$data['Coupon']['expire_date'],$sale_time);
				$csv->addRow($line);
				$list[]=$line;
			}
			
		}
		
		echo $csv->render($v_name."_".str_replace(" ","_","$p_title")."_".date("d_M_Y_h_i_s_a"));
		
		//for save into folder
		$directory_path = $this->create_directory("pull_coupon_sale_csv");
		$fp = fopen($directory_path.$v_name."_".str_replace(" ","_","$p_title")."_".date("d_M_Y_h_i_s_a").'.csv', 'w');
		foreach ($list as $fields) {
		    fputcsv($fp, $fields);
		}
		fclose($fp);
		exit();
	}
	
	/*
	* Function for generating vendor report pdf
	*/
	
	function generate_coupon_report_pdf($data = null,$b_data=array(),$p_title=null,$v_name=null){
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
                $htmlcontent.="<strong><h2>Pull Coupon Report</h2></strong>";
                
                $htmlcontent.="<br/>";
                // table start
                $htmlcontent.="<table border=\".5\" >
                <tr>
			<td align='left' valign='top'><strong>Vendor</strong></td>
			<td align='left' valign='top'><strong>Product</strong></td>
                        <td align='left' valign='top'><strong>Product Code</strong></td>
			<td align='left' valign='top'><strong>Batch</strong></td>
			<td align='left' valign='top'><strong>Coupon Id</strong></td>
			<td align='left' valign='top'><strong>Activation Code</strong></td>
                        <td align='left' valign='top'><strong>Status</strong></td>
			<td align='left' valign='top'><strong>Sold</strong></td>
                        <td align='left' valign='top'><strong>Expire Date</strong></td>
			<td align='left' valign='top'><strong>Sale Time</strong></td>
                        
                </tr>";
                $charges="No";
                if(count($data)>0){ 
                        $status = array('Deactive','Active');
                        $status_sold = array(1=>"Yes",0=>"No");
			
			App::import("Model","Product");
			$this->Product = new Product();
			$product_data = $this->Product->find("list",array("recursive"=>-1,"fields"=>array("Product.product_code","Product.title"),"conditions"=>array("Product.is_deleted"=>"0","Product.is_active"=>"1")));
			
			foreach($data as $result){
				$sale_time = ($result['Coupon']['is_sold'] == "1")?$result['Coupon']['modified']:"Not yet";
				$htmlcontent.="<tr><td align='left' valign='top'>";
				$htmlcontent.= $v_name;
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.= $product_data[$result['Coupon']['product_code']];
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.= $result['Coupon']['product_code'];
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.= $b_data[$result['Coupon']['batch_id']]; 
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.= $result['Coupon']['coupon_id']; 
                                $htmlcontent.="</td><td align='left' valign='top'>";
				$htmlcontent.= $result['Coupon']['activation_code']; 
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$status[$result['Coupon']['is_active']];
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$status_sold[$result['Coupon']['is_sold']];
                                $htmlcontent.="</td><td align='right' valign='top'>";
                                $htmlcontent.=$result['Coupon']['expire_date'];
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$sale_time;
                                $htmlcontent.="</td></tr>";
                        }
                }
                else {
                    $htmlcontent.="<tr><td colspan='7' style='border-bottom:0px' class='tc'>No records found</td></tr>";
                }
                
                $htmlcontent.="</table></body></html>";	    
                // output the HTML content	    
                $tcpdf->writeHTML($htmlcontent, true, 0, true, 0);
                $tcpdf->Output($v_name."_".str_replace(" ","_","$p_title")."_".date("d_M_Y_h_i_s_a").'.pdf', 'D');
		
		//for save into folder
		$directory_path = $this->create_directory("pull_coupon_sale_pdf");
		$tcpdf->Output($directory_path.$v_name."_".str_replace(" ","_","$p_title")."_".date("d_M_Y_h_i_s_a").'.pdf', 'F');
		exit();
	}
	
	/*
	 * Function to view list of csv/pdf
	*/
	
	function admin_coupon_pull_history(){
		$this->layout='backend/backend';
		$cs_files = scandir(WWW_ROOT."img/pull_coupon_sale_csv/",1);
		$pd_files = scandir(WWW_ROOT."img/pull_coupon_sale_pdf/",1);
		//$csv_files = array();
		$months = array('Jan'=>'1','Feb'=>'2', 'Mar' => '3','Apr'=>'4','May'=>'5','Jun'=>'6','Jul'=>'7','Aug'=>'8','Sep'=>'9','Oct'=>'10','Nov'=>'11','Dec'=>'12');
		
		foreach($cs_files as $k=>$v){
			$pos = strpos($v,"deleted_"); 
			if($pos === false && ($v != '.') && ($v != '..')){
				//$csv_files[] = $v;
				$date_time = substr($v,strrpos($v, '_', -26)+1);
				$d = explode("_",substr($date_time,0,11));
				$t = explode("_",str_replace(".csv","",substr($date_time,12)));
				
				$c_f[$d[2].'_'.$months[$d[1]].'_'.$d[0].'_'.$t[0].'_'.$t[1].'_'.$t[2].'_'.$t[3]] = $v;
			}
		}
		krsort($c_f);
		//$pdf_files = array();
		foreach($pd_files as $k1=>$v1){
			$pos_pdf = strpos($v1,"deleted_"); 
			if($pos_pdf === false && ($v1 != '.') && ($v1 != '..')){
				//$pdf_files[] = $v1;
				$date_time = substr($v1,strrpos($v1, '_', -26)+1);
				$d = explode("_",substr($date_time,0,11));
				$t = explode("_",str_replace(".pdf","",substr($date_time,12)));
				
				$p_f[$d[2].'_'.$months[$d[1]].'_'.$d[0].'_'.$t[0].'_'.$t[1].'_'.$t[2].'_'.$t[3]] = $v1;
			}
		}
		krsort($p_f);
		$this->set("csv_files",$c_f);
		$this->set("pdf_files",$p_f);
	}
	
	/*
	 * function to delete csv/pdf
	*/
	
	function admin_delete_csv_pdf($type = null,$f_name = null){
		if($type == "c"){
			if(file_exists(WWW_ROOT."img/pull_coupon_sale_csv/".$f_name)){
				rename(WWW_ROOT."img/pull_coupon_sale_csv/".$f_name,WWW_ROOT."img/pull_coupon_sale_csv/deleted_".$f_name);
				$this->Session->setFlash(CSV_DELETED_SUCCESS, 'message/green');
				$this->redirect(array("controller"=>"coupons","action"=>"coupon_pull_history","admin"=>true));
			}else{
				$this->Session->setFlash(CSV_PDF_DELETED_ERR, 'message/red');
				$this->redirect(array("controller"=>"coupons","action"=>"coupon_pull_history","admin"=>true));
			}
		}elseif($type == "p"){
			if(file_exists(WWW_ROOT."img/pull_coupon_sale_pdf/".$f_name)){
				rename(WWW_ROOT."img/pull_coupon_sale_pdf/".$f_name,WWW_ROOT."img/pull_coupon_sale_pdf/deleted_".$f_name);
				$this->Session->setFlash(PDF_DELETED_SUCCESS, 'message/green');
				$this->redirect(array("controller"=>"coupons","action"=>"coupon_pull_history","admin"=>true));
			}else{
				$this->Session->setFlash(CSV_PDF_DELETED_ERR, 'message/red');
				$this->redirect(array("controller"=>"coupons","action"=>"coupon_pull_history","admin"=>true));
			}
		}else{
			$this->Session->setFlash(CSV_PDF_DELETED_ERR, 'message/red');
			$this->redirect(array("controller"=>"coupons","action"=>"coupon_pull_history","admin"=>true));
		}
	}
	
	/*
	* Function for coupon Sale
	*/
	
 	function admin_sale(){  
		
		$this->layout='backend/backend';
		$user=$this->Auth->user();
		if($user['role_id'] == 2 || $user['role_id'] == 3) {
			$dealer_id = $user['id']; 
			$selling_limit = $user['selling_limit'];
			$Dealerlimit = $this->admin_couponlimit($dealer_id);     
				if(is_array($Dealerlimit) && !empty($Dealerlimit) && $Dealerlimit['action'] && $Dealerlimit['price_coupon_limit']!=0 ){  
					$this->set("title_for_layout",LBL_COUPON_SALE);				 
					$vendor_result = $this->find_vendor_listing();   
					$vendor_listing = array(); 
					foreach($vendor_result as $key=>$value){ 
						settype($key,"string");
						$tmp = ENCRYPT_DATA($key) ;
						$vendor_listing[$tmp] =  $value;
					}
					
					$this->set("vendor_listing",$vendor_listing);  
					$coupon_limit=$Dealerlimit['price_coupon_limit'];
					$this->set('coupon_limit',$coupon_limit); 
					
				 	if($coupon_limit <= 500){
						$this->set('coupon_balance_limit','<b> Your balance is low now !</b>');
					}
					if($coupon_limit > 500 && $coupon_limit <= 800 ){  
						$this->set('alert_balance_limit',1);
					}
					 
				}else{  
				 	$this->set('coupon_limit',0);  
					$limit_type=($selling_limit == 0)?'day':(($selling_limit == 1)?'weekly':(($selling_limit == 2)?'monthly':'yearly'));
					$this->set('coupon_balance_limit',"<b> Your $limit_type limit is empty now !</b>");
				  	$this->set('balance_message',"<b> Your coupon sale $limit_type limit has been exceeded now ! Please try again later ...</b>");	
		 	 	}  
		}else{
			$this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));
		}
	}
	/*
	* Function for select coupon under specific vendor/category/product code
	*/
	function admin_showcoupon($vendor_id=null,$category_id=null){
		$this->layout = "";
		$this->autoRender = false;
		
		if($this->RequestHandler->isAjax()){
		//if(1){ 
			$user=$this->Auth->user();
			if($user['role_id'] == 2 || $user['role_id'] == 3) {
				$dealer_id = $user['id']; 
				$Dealerlimit = $this->admin_couponlimit($dealer_id); 
				if(is_array($Dealerlimit) && !empty($Dealerlimit) && $Dealerlimit['action'] && $Dealerlimit['price_coupon_limit'] !=0 ){ 
					$price_coupon_limit = $Dealerlimit['price_coupon_limit']; 
					 
					$set_coupondata=array(); 
					if((!empty($vendor_id)) && (!empty($category_id)) ){ 
						App::import("Model","Vendor");
						$this->Vendor = new Vendor(); 
						App::import("Model","Category");
						$this->Category = new Category(); 
						
						$vendor_id = DECRYPT_DATA($vendor_id);
						$category_id = DECRYPT_DATA($category_id);
						$validvendorID = $this->Vendor->find('count',array("fields"=>array('id'),"conditions"=>array("Vendor.id"=> $vendor_id,"is_active"=> '1',"is_deleted"=> '0')));
						$validcategoryID = $this->Category->find('count',array("fields"=>array('id'),"conditions"=>array("Category.title <= $price_coupon_limit" , "Category.id"=> $category_id,"is_active"=> '1',"is_deleted"=> '0')));
						 
						if(($validvendorID > 0 ) && ($validcategoryID > 0)){    
								App::import("Model","Coupon");
								$this->Coupon = new Coupon(); 
							 	$Coupon = $this->Coupon->find('first',array("conditions"=>array('Coupon.vendor_id'=> $vendor_id,'Coupon.category_id' =>$category_id ,'Coupon.is_sold'=> '0','Coupon.is_deleted'=>"0")));
							 	if(!empty($Coupon) && is_array($Coupon)){ 
							 			App::import("Model","Product");
										$this->Product = new Product();
										$Productdata = $this->Product->find('list',array("fields"=>array('Product.product_code','Product.description'),"conditions"=>array("Product.product_code" => $Coupon['Coupon']['product_code'],"Product.is_active" => '1',"Product.is_deleted"=> '0')));
										
										$product_delimiter = $this->Product->Field("Product.delimiter",array("Product.product_code" => $Coupon['Coupon']['product_code'],"Product.is_active" => '1',"Product.is_deleted"=> '0'));
										
										$Vendordata = $this->Vendor->find('list',array("fields"=>array('Vendor.id','Vendor.name'),"conditions"=>array("Vendor.id" => $validvendorID,"Vendor.is_active" => '1',"Vendor.is_deleted"=> '0')));
										
										$Categorydata = $this->Category->find('list',array("fields"=>array('Category.id','Category.title'),"conditions"=>array("Category.id" => $validcategoryID,"Category.is_active" => '1',"Category.is_deleted"=> '0')));
										 
										if(!empty($Productdata) && is_array($Productdata)){
													
											$sold_CouponID =  $Coupon['Coupon']['id']; 
											//$d_id  is used to save dealer-id in to the coupons table if any sub dealer will sale the his dealer id will save insted of sub dealer id 
											$d_id = $dealer_id;
											if($user['parent_id'] != '0'){
												$d_id = $d_id;
											}
											
											$soldcoupon = array('Coupon'=> array('id' => $sold_CouponID ,'is_sold'=> '1','id_dealer'=>$d_id));
											$category_price = $this->Category->find('list',array('fields' => array('id','title'),'conditions' => array('id'=> $Coupon['Coupon']['category_id']) ));
										 	foreach($category_price as $k => $value){
												$coupon_price = $value;
											}
											
											$coupon_sell_record = array('CouponSale'=> array( 'coupon_id'=> $sold_CouponID,'vendor_id'=> $Coupon['Coupon']['vendor_id'],'category_id' => $Coupon['Coupon']['category_id'],'parent_id'=> $user['parent_id'],'product_code'=> $Coupon['Coupon']['product_code'],'dealer_id'=> $dealer_id,'price' => $coupon_price ) );
										 	
										 	if($this->Coupon->save($soldcoupon) && $this->CouponSale->save($coupon_sell_record)){
										 		foreach($Coupon as $key=> $value){
													//settype($value['Product'][''], "string");  
													$set_coupondata['coupon_id'] = $value['coupon_id']; 
													
	$user_all_data = $this->Session->read("Auth.User");												//$coupon_actv_code = str_replace('|',' - ', $value['activation_code']);
	$coupon_actv_code = str_replace("|",$product_delimiter,$value['activation_code']);
	$set_coupondata['activation_code']=  $coupon_actv_code; 
													
	$current_date = date("Y-m-d");
	$current_time = date("h:i:s A");
	
												
													foreach($Productdata as $k => $val){
														$newphrase='';
														if(!empty($val)){
															$searchText = array('{VENDOR_NAME}','{VOUCHER}','{COUPON_CODE}','{ACTIVATION_CODE}','{USERNAME}','{ADDRESS}','{CITY}','{POSTNUMBER}','{DATE}', '{TIME}','{n}');
															$replaceText = array($Vendordata[$validvendorID],$Categorydata[$validcategoryID],$value['coupon_id'],$coupon_actv_code,$user_all_data['u_name'],$user_all_data['UserProfile']['address'],$user_all_data['UserProfile']['city'],$user_all_data['UserProfile']['post_number'],$current_date,$current_time,'');
															$newphrase = str_replace($searchText, $replaceText, $val);
														}
		$set_coupondata['description']=  $newphrase;
	} 
												}  
												   
												
										 	} 	
											  
											
										}  
							 	} 
						}
					}	
					
				}
				if(!empty($set_coupondata) && is_array($set_coupondata)){
					return json_encode($set_coupondata);
				}
				return FALSE; 
			}
		}else{
			$this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));
		}
		
	} 
	/*
	* Function for shown coupon activation code using popup box
	*/
	
	function admin_salecoupon($coupon_id=null){  
		$this->layout=''; 
		if(!$this->RequestHandler->isAjax()){
			$this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));	
		}	
	}  
	/*
	* Function to see coupon limit
	 * @ return $Dealerlimit = array ($Dealerlimit['price_coupon_limit'] = $price_coupon_limit,
									  $Dealerlimit['action'] = TRUE);
	*/
	
	function admin_couponlimit($dealer_id=null){ 
		$Dealerlimit = array();
		if(!empty($dealer_id) ){
						
			App::import("Model", "User");
			$this->User = new User(); 	
			 
			
			$Dealer = $this->User->find('first',array('conditions'=>array('User.id' => $dealer_id,'is_active' => '1','is_deleted' => '0'),'recursive' => -1));
			
			if( ( !empty($Dealer) && is_array($Dealer) )  && ( ($Dealer['User']['role_id'] == 2) || ($Dealer['User']['role_id'] == 3) )  ){
				
				if($Dealer['User']['role_id'] == '3'){
					$Dealer=$this->User->find('first',array('conditions'=>array('User.id' => $Dealer['User']['parent_id'],'is_active' => '1','is_deleted' => '0'),'recursive' => -1));
				}   	
				
				$dealer_role_id = $Dealer['User']['role_id'];
				$dealer_id = $Dealer['User']['id'];
				
				$selling_limit = $Dealer['User']['selling_limit'];
				$selling_price_limit = $Dealer['User']['selling_price_limit'];
 
				
			 	App::import("Model", "CouponSale");
				$this->CouponSale = new CouponSale(); 	
				
				//if($dealer_role_id == 2) {
					$Dealer_has_subdealers= $this->User->find('all',array('fields' => array('id') ,'conditions'=>array('User.parent_id' => $dealer_id,'is_active' => '1','is_deleted' => '0'),'recursive' => -1));
					$Dealer_has_subdealers= Set::extract('/User/id/.',$Dealer_has_subdealers);
					$Dealer_has_subdealers[] = $dealer_id;
				//}
				
				
				
			 	if($selling_limit == 0){                    // "0"=>"day" 
					$limit_condition = date('Y-m-d'); 
				
				}elseif($selling_limit == 1){             // "1"=>"week"					
					$day_week = date('D');  
					$dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
					
					switch($day_week){
						
						case $dowMap['0']:
							$startdate_w=date("Y-m-d");
						break;
						
						case $dowMap['1']:
							$startdate_w=date("Y-m-d", strtotime("-1 day"));
						break;
						
						case $dowMap['2']: 
							$startdate_w=date("Y-m-d", strtotime("-2 day"));
						break;
						
						case $dowMap['3']:
							$startdate_w=date("Y-m-d", strtotime("-3 day"));
						break;
						
						case $dowMap['4']:
							$startdate_w=date("Y-m-d", strtotime("-4 day"));
						break;
						
						case $dowMap['5']:
							$startdate_w=date("Y-m-d", strtotime("-5 day"));
						break;
						
						case $dowMap['6']:
							$startdate_w=date("Y-m-d", strtotime("-6 day"));
						break; 
					}  
					$limit_condition = $startdate_w;
					 
				}elseif($selling_limit == 2){           //"2"=>"month"
					$limit_condition = date('Y-m'); 
					
				}else{								  //"3"=>"year"
					$limit_condition = date('Y'); 
				} 

				
				/*
				if($selling_limit == 1){
						$Dealer_CouponSale = $this->CouponSale->find('all',array('fields' => array('SUM(CouponSale.price) AS total'),'conditions'=>array('CouponSale.dealer_id' => $dealer_id,'CouponSale.created >=' => $startdate_w )));
			 	}else{
						$Dealer_CouponSale = $this->CouponSale->find('all',array('fields' => array('SUM(CouponSale.price) AS total'),'conditions'=>array('CouponSale.dealer_id' => $dealer_id,'CouponSale.created LIKE'=> "%$limit_condition%")));
			 	}
				$Dealer_CouponSale = Set::extract('{n}.{n}.total',$Dealer_CouponSale); 
				$Dealer_CouponSale= Set::extract('/0/0/.',$Dealer_CouponSale);
				*/ 
			//	if($dealer_role_id == 2 && !empty($Dealer_has_subdealers) && is_array($Dealer_has_subdealers)) {
					$Dealer_CouponSale= $this->CouponSale->find('all',array('fields' => array('SUM(CouponSale.price) AS total'),'conditions'=>array('CouponSale.dealer_id' => $Dealer_has_subdealers,'CouponSale.created LIKE'=> "%$limit_condition%")));
					$Dealer_CouponSale= Set::extract('{n}.{n}.total',$Dealer_CouponSale);
					$Dealer_CouponSale= Set::extract('/0/0/.',$Dealer_CouponSale);
					
			/*	}else{
					$Dealer_CouponSale= $this->CouponSale->find('all',array('fields' => array('SUM(CouponSale.price) AS total'),'conditions'=>array('CouponSale.dealer_id' => $dealer_id,'CouponSale.created LIKE'=> "%$limit_condition%")));
					$Dealer_CouponSale= Set::extract('{n}.{n}.total',$Dealer_CouponSale);
					$Dealer_CouponSale= Set::extract('/0/0/.',$Dealer_CouponSale);
				}*/
 
				 
				 
				/* 
				/// IF Get total from $Dealer_CouponSale within specific conditions (// $selling_limit => array( "0"=>"day","1"=>"week","2"=>"month","3"=>"year") )  
				if(is_array($Dealer_CouponSale) && !empty($Dealer_CouponSale['0']) ){ 
					$price_coupon_limit = $selling_price_limit - $Dealer_CouponSale['0']; 
					//return balance price_coupon_limit AND PERMISSION 
					$Dealerlimit['price_coupon_limit'] = $price_coupon_limit;
					$Dealerlimit['action'] = TRUE;
					return $Dealerlimit;
				}else{
					$Dealerlimit['price_coupon_limit'] = $selling_price_limit;
					$Dealerlimit['action'] = TRUE;
					return $Dealerlimit;
				}
				*/
				 /// IF Get total from $Dealer_CouponSale within specific conditions (// $selling_limit => array( "0"=>"day","1"=>"week","2"=>"month","3"=>"year") )
				if(is_array($Dealer_CouponSale) && !empty($Dealer_CouponSale['0']) ){
						$price_coupon_limit = $selling_price_limit - $Dealer_CouponSale['0'];
						//return balance price_coupon_limit AND PERMISSION
						$Delarlimit['price_coupon_limit'] = $price_coupon_limit;
						$Delarlimit['action'] = TRUE;
						 
						return $Delarlimit;
				}else{
						$Delarlimit['price_coupon_limit'] = $selling_price_limit;
						$Delarlimit['action'] = TRUE;
						 
						return $Delarlimit;
				}
				
				
			}  
		} 
		return $Dealerlimit;
	}
	/*
	*function for find the all product Search
	*/
	
	function admin_search($action=null,$id=null){
		
 		$this->layout = "";
		$this->autoRender = false;
		if($this->RequestHandler->isAjax()){
		//if(1){
			$user = $this->Auth->user();
				if(!empty($action)){
						if($user['role_id'] == 2 || $user['role_id'] == 3) { 
							$dealer_id = $user['id'];
							$Dealerlimit = $this->admin_couponlimit($dealer_id);  
							if(is_array($Dealerlimit) && !empty($Dealerlimit) && $Dealerlimit['action'] && $Dealerlimit['price_coupon_limit']!=0 ){  
									$price_coupon_limit = $Dealerlimit['price_coupon_limit']; 
									switch($action){ 
										case 'category':
											$set_categorydata = array();
										
											if(!empty($id)){
												$vendor_id=DECRYPT_DATA($id);
												App::import("Model","Vendor");
												$this->Vendor = new Vendor();
												$validID = $this->Vendor->find('count',array("fields"=>array('id'),"conditions"=>array("Vendor.id"=> $vendor_id)));
												 
												if($validID > 0){
													App::import("Model","Category");
													$this->Category = new Category();
													App::import("Model","Product");
													$this->Product = new Product();
													
													$product_join = array('table' => 'products','alias' => 'Product','type' => 'INNER','conditions' => array('Product.category_id = Category.id','Product.vendor_id'=> $vendor_id ,'Product.is_active' => '1','Product.is_deleted' => '0'));
													$Categorydata = $this->Category->find('all',array('fields' => array('Category.id,Category.title'),'conditions' => array("Category.title <= $price_coupon_limit" ,'Category.is_active' => '1','Category.is_deleted' => '0'),'recursive' => 0,'joins' => array($product_join)) );
													
													$Categorydata = Set::extract('/Category/.',$Categorydata); 
													$result = array_map("unserialize", array_unique(array_map("serialize", $Categorydata)));
													$counter=0;
													foreach($result as $key=>$value){  
														$set_categorydata[$counter]['key'] = ENCRYPT_DATA($value['id']) ;
														$set_categorydata[$counter]['value']= $value['title'];
														$counter++;
													}  
												}  
											}
										return json_encode($set_categorydata ) ;
										break; 
									}		
							}
						}  
				}
		}else{
			$this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));
		}
				
	}

	function admin_couponemail($coupon_id=null,$a_code=null,$email=null){
		$this->layout = "";
		$this->autoRender = false;
		if($this->RequestHandler->isAjax()){
		 //if(1){
		 	 
			 
			if(empty($email)){ 
				$email=$this->Session->read("Auth.User.email");
			}
			
			if(!empty($coupon_id) && !empty($a_code)){
				App::import("Model","Product");
				$this->Product = new Product(); 
				App::import("Model","Coupon");
				$this->Coupon = new Coupon(); 
			 	App::import("Model","Vendor");
				$this->Vendor = new Vendor(); 
				App::import("Model","Category");
				$this->Category = new Category(); 
			 	
			 	$Coupon = $this->Coupon->find('first',array("conditions"=>array('Coupon.coupon_id'=> $coupon_id,'Coupon.is_sold'=> '1')));
			 		
			 	
				$validvendorID = $Coupon['Coupon']['vendor_id'];
				$validcategoryID = $Coupon['Coupon']['category_id'];
			 	
				$Productdata = $this->Product->find('list',array("fields"=>array('Product.product_code','Product.description'),"conditions"=>array("Product.product_code" => $Coupon['Coupon']['product_code'],"Product.is_active" => '1',"Product.is_deleted"=> '0')));
			 	$Vendordata = $this->Vendor->find('list',array("fields"=>array('Vendor.id','Vendor.name'),"conditions"=>array("Vendor.id" => $validvendorID,"Vendor.is_active" => '1',"Vendor.is_deleted"=> '0')));
				$Categorydata = $this->Category->find('list',array("fields"=>array('Category.id','Category.title'),"conditions"=>array("Category.id" => $validcategoryID,"Category.is_active" => '1',"Category.is_deleted"=> '0')));
				 
				$user_all_data = $this->Session->read("Auth.User");
				$current_date = date("Y-m-d");
				$current_time = date("h:i:s A");
				
				$product_delimiter = $this->Product->Field("Product.delimiter",array("Product.product_code" => $Coupon['Coupon']['product_code'],"Product.is_active" => '1',"Product.is_deleted"=> '0'));
				
				foreach($Productdata as $k => $val){
					$newphrase='';
					if(!empty($val)){
						$searchText = array('{VENDOR_NAME}','{VOUCHER}','{COUPON_CODE}','{ACTIVATION_CODE}','{USERNAME}', '{ADDRESS}', '{CITY}', '{POSTNUMBER}', '{DATE}', '{TIME}','{n}');
						$replaceText = array($Vendordata[$validvendorID],$Categorydata[$validcategoryID],$coupon_id,str_replace("|",$product_delimiter,$a_code),$user_all_data['u_name'],$user_all_data['UserProfile']['address'],$user_all_data['UserProfile']['city'],$user_all_data['UserProfile']['post_number'],$current_date,$current_time,'');
						
						$newphrase = str_replace($searchText, $replaceText, $val);
						//echo $newphrase;
					}
				} 
				if($this->send_email(array("{COUPON_ID}","{ACTIVATION_CODE}","{DESCRIPTION_CODE}"),array($coupon_id,$a_code,$newphrase),"coupon-sold",EMAIL_NOTIFICATION,$email,NOREPLY_EMAIL)){
					return TRUE;
				}else{
					return FALSE;
				}				
			}else{
				return FALSE;
			}
		}else{
			$this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));
		}			
	}
	
	function admin_couponsms($mobile_number=null,$coupon_id=null,$a_code=null){
		$this->layout = "";
		$this->autoRender = false;
		if($this->RequestHandler->isAjax()){
		//if(1){
			 
			if(!empty($mobile_number) && !empty($coupon_id) && !empty($a_code)){
				App::import("Model","Product");
				$this->Product = new Product(); 
				App::import("Model","Coupon");
				$this->Coupon = new Coupon(); 
			 	App::import("Model","Vendor");
				$this->Vendor = new Vendor(); 
				App::import("Model","Category");
				$this->Category = new Category(); 
			 	
			 	$Coupon = $this->Coupon->find('first',array("conditions"=>array('Coupon.coupon_id'=> $coupon_id,'Coupon.is_sold'=> '1','Coupon.is_deleted'=> '0')));
			 	
			 	$validvendorID = $Coupon['Coupon']['vendor_id'];
				$validcategoryID = $Coupon['Coupon']['category_id'];
			 	
				$Productdata = $this->Product->find('list',array("fields"=>array('Product.product_code','Product.sms_description'),"conditions"=>array("Product.product_code" => $Coupon['Coupon']['product_code'],"Product.is_active" => '1',"Product.is_deleted"=> '0')));
			 	$Vendordata = $this->Vendor->find('list',array("fields"=>array('Vendor.id','Vendor.name'),"conditions"=>array("Vendor.id" => $validvendorID,"Vendor.is_active" => '1',"Vendor.is_deleted"=> '0')));
				$Categorydata = $this->Category->find('list',array("fields"=>array('Category.id','Category.title'),"conditions"=>array("Category.id" => $validcategoryID,"Category.is_active" => '1',"Category.is_deleted"=> '0')));
				
				$product_delimiter = $this->Product->Field("Product.delimiter",array("Product.product_code" => $Coupon['Coupon']['product_code'],"Product.is_active" => '1',"Product.is_deleted"=> '0'));
				
				$user_all_data = $this->Session->read("Auth.User");
				$current_date = date("Y-m-d");
				$current_time = date("h:i:s A");				
				foreach($Productdata as $k => $val){
					$newphrase='';
					if(!empty($val)){
						$searchText = array('{VENDOR_NAME}','{VOUCHER}','{COUPON_CODE}','{ACTIVATION_CODE}','{USERNAME}', '{ADDRESS}','{CITY}','{POSTNUMBER}','{DATE}', '{TIME}','{n}');
						$replaceText = array($Vendordata[$validvendorID],$Categorydata[$validcategoryID],$coupon_id,str_replace("|",$product_delimiter,$a_code),$user_all_data['u_name'],$user_all_data['UserProfile']['address'],$user_all_data['UserProfile']['city'],$user_all_data['UserProfile']['post_number'],$current_date,$current_time,'%0a');
						$newphrase = str_replace($searchText, $replaceText, $val);
					} 									
				}
				echo $newphrase = str_replace($searchText, $replaceText, $val);

				//$message="coupon id $coupon_id having activation code is $a_code."; 
				$message=strip_tags($newphrase); 
				
				$url = "http://www.cpsms.dk/sms/";
				$url .= "?username=Hallodk"; // Username
				$url .= "&password=010203"; // Password
				$url .= "&utf8=1"; 
				$url .= "&recipient=$mobile_number"; // Recipient 4552520552
				$url .= "&message=" .urlencode("$message");
				$url .= "&from=" . urlencode("Hallodk"); // Sendername				   
				$finalurl = str_replace('%250a', '%0a', $url);
				
				// The url is opened 
			 	$reply = file_get_contents($finalurl);
				if(strstr($reply, "<succes>")) {
					//return TRUE;
					// If the reply contains the tag <succes> the SMS has been sent.
					//echo "The message has been sent. Server response: ".$reply;
					echo "The message has been sent successfully.";
				}else{
					//return FALSE;
					// If not, there has been an error.
					//echo "The message has NOT been sent. Server response: ".$reply;
					echo "Sorry, message has not been sent.";
				}  
			}else{
				//return FALSE;
				echo "Sorry, message has not been sent.";
			}
			 
			
	 	}else{
			$this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));
		}			
	}
	
	function admin_history(){
		$this->layout='backend/backend';
		$this->set("title_for_layout",COUPON_LISTING);
		
		App::import("Model","User");
		$this->User = new User();
		$user_profile_join = array('table'=>'user_profiles','alias'=>'UserProfile','forignKey'=>false,'type'=>'left','conditions'=>array('UserProfile.user_id = User.id'),'order'=>array("UserProfile.first_name"=>"DESC"));
		
		$sub_dealer_data = $this->User->find("list",array("fields"=>array("id","UserProfile.first_name"),"joins"=>array($user_profile_join),"conditions"=>array("User.is_deleted"=>"0","User.is_active"=>"1","User.parent_id"=>$this->Session->read("Auth.User.id"))));
		$this->set("sub_dealer_data",$sub_dealer_data);
		
		App::import("Model","Product");
		$this->Product = new Product();
		$product_data = $this->Product->find("list",array("fields"=>array("product_code","title"),"conditions"=>array("Product.is_deleted"=>"0","Product.is_active"=>"1")));
		$this->set("product_data",$product_data);
		
		App::import("Model", "CouponSale");
		$this->CouponSale = new CouponSale();
			
		$this->CouponSale->bindModel(array('belongsTo' => array('UserProfile' => array('className' => 'UserProfile','foreignKey' => 'dealer_id'),'Coupon' => array('className' => 'Coupon','foreignKey' => 'coupon_id'))));
			 
		$conditions = array();
		$user=$this->Auth->user();
		
		if($user['role_id'] == 2){
				App::import("Model", "User");
				$this->User = new User();
				$sub_dealer =  $this->User->find('list',array('conditions' => array('parent_id' => $user['id']),'fields' => array('id','id')));
				$sub_dealer[] = $user['id'];
				$conditions = array_merge($conditions ,array("dealer_id"=> $sub_dealer ));
				
		}
		
		if($user['role_id'] == 3){
		 	$conditions = array_merge($conditions ,array("dealer_id"=> $user['id'] )); 
		}		
		if(!empty($this->data)){ 
			$from = $this->data['Coupon']['from'];
			$to = $this->data['Coupon']['to'];
			$c_id = $this->data['Coupon']['c_id'];
			$s_d = $this->data['Coupon']['s_d'];
			$p_c = $this->data['Coupon']['p_c'];
			$limit = $this->data['Coupon']['limit'];
			if(trim($from) != ""){ 
				$from = $this->covertToSystemDate($from);
				$conditions = array_merge($conditions ,array("DATE_FORMAT(CouponSale.created,'%Y-%m-%d') >="=>trim($from)));
			}
			if(trim($to) != ""){ 
				$to = $this->covertToSystemDate($to);
				$conditions = array_merge($conditions ,array("DATE_FORMAT(CouponSale.created,'%Y-%m-%d') <="=>trim($to)));
			}
			if(trim($c_id) != ""){
				app::import("Model","Coupon");
				$this->Coupon = new Coupon();
				$c_code = $this->Coupon->field("id",array("coupon_id"=>$c_id));
				$conditions = array_merge($conditions ,array("CouponSale.coupon_id"=>trim($c_code)));
				
			}
			if(trim($s_d) != ""){
				$conditions = array_merge($conditions ,array("CouponSale.dealer_id"=>trim($s_d)));
				$this->set("sub_dealer",$s_d);
			}
			if(trim($p_c) != ""){
				$conditions = array_merge($conditions ,array("CouponSale.product_code"=>trim($p_c)));
				$this->set("product_c",$p_c);
			} 
			if(trim($limit) == "" || trim($limit) <=0){ 
				$limit = 100;
			} 
		}else{
			$limit = 100;
		}   
		$coupon_data = $this->CouponSale->find('all',array('conditions' => $conditions,'order' =>array("CouponSale.created"=>"desc"),'limit'=>$limit));  
		$this->set('coupon_data',$coupon_data);
		
	}
}
?>