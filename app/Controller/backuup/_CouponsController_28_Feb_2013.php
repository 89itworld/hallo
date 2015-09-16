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
		$vendor_list = $this->find_vendor_listing();
		$this->set("vendor_list",$vendor_list);
		
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
			//$this->request->data['Coupon']['csv_file'] = (isset($_FILES['fileToUpload']))?$_FILES['fileToUpload']:$_POST['fileToUpload_one']; 
			//$file = isset($this->data['Coupon']['csv_file'])?$this->data['Coupon']['csv_file']:$_POST['fileToUpload_one'];
			//$name = isset($this->data['Coupon']['csv_file']['name'])?$this->data['Coupon']['csv_file']['name']:$_POST['fileToUpload_one'];   
			$name = $_POST['fileToUpload_one'];   
			$directory_path = CSV_UPLOAD_PATH;  
			$uploaded_file = $directory_path.$name;
			$handle = fopen($uploaded_file, "r");
			  
			//read the 1st row as headings
			$header = fgetcsv($handle);
			$header_new = explode(";",$header[0]);   //pr($header_new);  die; 
			
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
			//if(($this->data['Coupon']['product_code'] != "") && ($this->data['Coupon']['category_id'] != "") && ($this->data['Coupon']['category_title'] != "") && ($this->data['Coupon']['csv_file']['name'] != "") ){
			if($name != "" ){  
				App::import("Component","Upload");
				$upload = new UploadComponent();
				
				//$path_info = pathinfo($this->data['Coupon']['csv_file']['name']);
				$path_info = pathinfo($name);
				$file_extension = strtolower($path_info['extension']);
				$allowed_ext=array('csv','txt');
				if(in_array($file_extension,$allowed_ext)){
					
					//$file_name = $upload->upload($file,$directory_path,$name,null,array($file_extension));
					$file_name=$name; 
					//die($file_name);
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
		//pr(HTTP_HOST);die();
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
			}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
			{
				$error = 'No file was uploaded..';
				echo "{";
						echo	"error: '" . $error . "',\n";
				echo "}";
			}else 
			{
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
							 	$all_coupon=$this->Coupon->find('list',array('fields' => array('Coupon.coupon_id','Coupon.id'),'conditions' => array('Coupon.is_active'=>1,'Coupon.is_deleted'=>0,'Coupon.is_sold'=>0)));
								 
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
													$uploaded_coupon_id=array();
													while (($data_csv = fgetcsv($handle)) !== FALSE) {
														$num = count($data_csv);
														$row++;
														$total++;
														for ($c=0; $c < $num; $c++) {
															$data_temp = explode($delimiter,$data_csv[$c]);
															if(preg_match("/[\t-]/",$data_temp[0])){ // echo 'f';
																$data_temp[0]=trim($data_temp[0]);
																$data_temp[0] = preg_replace('/[\t-]+/', ';',$data_temp[0]);
																//$data_temp[0] = preg_replace('/[-]+/', ';',$data_temp[0]);  
																$data_csv[0]=$data_temp[0];
															}
															$activation_code = implode('|',$data_temp);
															//$data_save['Coupon']["activation_code"] = $activation_code;
															$csv_coupon_id='';
															$csv_coupon_ac_code='';
															if(!empty($data_temp) && (!empty($activation_code))){ 
																if((isset($all_coupon[$data_temp[0]]))){
																	$duplicate_count +=1; 
																}else{
																	$csvrow = implode(";", $data_temp);
																   
																	$csv_coupon_id=substr($csvrow,0,strpos($csvrow, ';')); 
																	$csv_coupon_id="{$csv_coupon_id}";
																	 
																	$csv_coupon_ac_code=substr($csvrow,strpos($csvrow, ';') + 1);
																	$csv_coupon_ac_code="{$csv_coupon_ac_code}";
																	 
																	 if(preg_match ("/^[0-9]+$/",$csv_coupon_id) && preg_match ("/^[0-9]+[;]{0,}[0-9]+([;]{1}[0-9]+)*$/", $csv_coupon_ac_code)){
																			fputcsv($tmp_upfile, $data_csv); 
																		  	$data_count++;
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
													
													if($data_count != $duplicate_count && $data_count >0){ 
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
														//unlink($myFile);
														$r=($empty_data > 1)?'records':'record';
														$error="$duplicate_count/$total with $empty_data invalid $r.Please upload another file....";
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
			$fields = array("Product.vendor_id","Product.id","Product.category_id","Product.title","Product.product_code","Category.title");
			$data = $this->Product->find('first',array("fields"=>$fields,"conditions"=>array("Product.id"=>$product_id)));
			echo json_encode($data['Product']);
		    }
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
			$Dealerlimit=$this->admin_couponlimit($dealer_id);     
				if(is_array($Dealerlimit) && !empty($Dealerlimit) && $Dealerlimit['action'] && $Dealerlimit['price_coupon_limit']!=0 ){  
					$this->set("title_for_layout",LBL_COUPON_SALE);				 
					$vendor_result = $this->find_vendor_listing();   
					$vendor_listing=array(); 
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
				$dealer_id=$user['id']; 
				$Dealerlimit=$this->admin_couponlimit($dealer_id); 
				if(is_array($Dealerlimit) && !empty($Dealerlimit) && $Dealerlimit['action'] && $Dealerlimit['price_coupon_limit']!=0 ){ 
					$price_coupon_limit=$Dealerlimit['price_coupon_limit']; 
					 
					$set_coupondata=array(); 
					if((!empty($vendor_id)) && (!empty($category_id)) ){ 
						App::import("Model","Vendor");
						$this->Vendor = new Vendor(); 
						App::import("Model","Category");
						$this->Category = new Category(); 
						
						$vendor_id=DECRYPT_DATA($vendor_id);
						$category_id = DECRYPT_DATA($category_id);
						$validvendorID = $this->Vendor->find('count',array("fields"=>array('id'),"conditions"=>array("Vendor.id"=> $vendor_id,"is_active"=> '1',"is_deleted"=> '0')));
						$validcategoryID = $this->Category->find('count',array("fields"=>array('id'),"conditions"=>array("Category.title <= $price_coupon_limit" , "Category.id"=> $category_id,"is_active"=> '1',"is_deleted"=> '0')));
						 
						if(($validvendorID > 0 ) && ($validcategoryID > 0)){    
								App::import("Model","Coupon");
								$this->Coupon = new Coupon(); 
							 	$Coupon = $this->Coupon->find('first',array("conditions"=>array('Coupon.vendor_id'=> $vendor_id,'Coupon.category_id' =>$category_id , 'Coupon.is_locked'=> '0','Coupon.is_sold'=> '0','Coupon.is_active'=> '1','Coupon.is_deleted'=> '0')));
							 	if(!empty($Coupon) && is_array($Coupon)){ 
							 			App::import("Model","Product");
										$this->Product = new Product();
										$Productdata = $this->Product->find('list',array("fields"=>array('Product.product_code','Product.description'),"conditions"=>array("Product.product_code" => $Coupon['Coupon']['product_code'],"Product.is_active" => '1',"Product.is_deleted"=> '0')));
										$Vendordata = $this->Vendor->find('list',array("fields"=>array('Vendor.id','Vendor.name'),"conditions"=>array("Vendor.id" => $validvendorID,"Vendor.is_active" => '1',"Vendor.is_deleted"=> '0')));
										$Categorydata = $this->Category->find('list',array("fields"=>array('Category.id','Category.title'),"conditions"=>array("Category.id" => $validcategoryID,"Category.is_active" => '1',"Category.is_deleted"=> '0')));
										 
										if(!empty($Productdata) && is_array($Productdata)){
													
											$sold_CouponID =  $Coupon['Coupon']['id']; 
											$soldcoupon=array('Coupon'=> array('id' => $sold_CouponID ,'is_locked' => '0','is_sold'=> '1','is_active'=> '0','is_deleted'=> '1'));
											$category_price=$this->Category->find('list',array('fields' => array('id','title'),'conditions' => array('id'=> $Coupon['Coupon']['category_id']) ));
										 	foreach($category_price as $k => $value){
												 $coupon_price=$value;
											}
											$coupon_sell_record = array('CouponSale'=> array( 'coupon_id'=> $sold_CouponID,'vendor_id'=> $Coupon['Coupon']['vendor_id'],'category_id' => $Coupon['Coupon']['category_id'],'parent_id'=> $user['parent_id'],'product_code'=> $Coupon['Coupon']['product_code'],'dealer_id'=> $dealer_id,'price' => $coupon_price ) );
										 	
										 	
										 	if($this->Coupon->save($soldcoupon) && $this->CouponSale->save($coupon_sell_record)){
										 		foreach($Coupon as $key=> $value){
													//settype($value['Product'][''], "string");  
													$set_coupondata['coupon_id'] = $value['coupon_id']; 
													
													$coupon_actv_code = str_replace('|',' - ', $value['activation_code']);
													$set_coupondata['activation_code']=  $coupon_actv_code; 
													
													foreach($Productdata as $k => $val){
														$newphrase='';
														if(!empty($val)){
															$searchText = array('{VENDOR_NAME}','{VOUCHER}','{COUPON_CODE}','{ACTIVATION_CODE}');
															$replaceText = array($Vendordata[$validvendorID],$Categorydata[$validcategoryID],$value['coupon_id'],$coupon_actv_code);
															
															$newphrase = str_replace($searchText, $replaceText, $val);
														 	
														}
														$set_coupondata['description']=  $newphrase;
														
														
													} 
													
													
													
													 
												}  
												   
												// pr($set_coupondata); die('yyyyy'); 
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
				
		$Dealerlimit=array();
		if(!empty($dealer_id) ){
			App::import("Model", "User");
			$this->User = new User(); 	
			
			$Dealer= $this->User->find('first',array('conditions'=>array('User.id' => $dealer_id,'is_active' => '1','is_deleted' => '0'),'recursive' => -1));
			
			if( ( !empty($Dealer) && is_array($Dealer) )  && ( ($Dealer['User']['role_id'] == 2) || ($Dealer['User']['role_id'] == 3) )  ){
				$dealer_role_id=$Dealer['User']['role_id'];
				$dealer_id= $Dealer['User']['id'];
				$selling_limit = $Dealer['User']['selling_limit'];
	   			$selling_price_limit = $Dealer['User']['selling_price_limit'];
			 	App::import("Model", "CouponSale");
				$this->CouponSale = new CouponSale(); 	
				
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
					 
				}elseif($selling_limit == 2){           //"2"=>"month"
					$limit_condition = date('Y-m'); 
					
				}else{								  //"3"=>"year"
					$limit_condition = date('Y'); 
				} 

				if($selling_limit == 1){
						$Dealer_CouponSale= $this->CouponSale->find('all',array('fields' => array('SUM(CouponSale.price) AS total'),'conditions'=>array('CouponSale.dealer_id' => $dealer_id,'CouponSale.created >=' => $startdate_w )));
			 	}else{
						$Dealer_CouponSale= $this->CouponSale->find('all',array('fields' => array('SUM(CouponSale.price) AS total'),'conditions'=>array('CouponSale.dealer_id' => $dealer_id,'CouponSale.created LIKE'=> "%$limit_condition%")));
			 	}
			    $Dealer_CouponSale= Set::extract('{n}.{n}.total',$Dealer_CouponSale); 
				$Dealer_CouponSale= Set::extract('/0/0/.',$Dealer_CouponSale);
				 
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
			$user=$this->Auth->user();
				if(!empty($action)){
						if($user['role_id'] == 2 || $user['role_id'] == 3) { 
							$dealer_id = $user['id'];
							$Dealerlimit=$this->admin_couponlimit($dealer_id);  
							if(is_array($Dealerlimit) && !empty($Dealerlimit) && $Dealerlimit['action'] && $Dealerlimit['price_coupon_limit']!=0 ){  
									$price_coupon_limit=$Dealerlimit['price_coupon_limit']; 
										 
									switch($action){ 
										case 'category':
											$set_categorydata=array();
										
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
													
													$product_join = array('table' => 'products','alias' => 'Product','type' => 'INNER','conditions' => array('Product.category_id=Category.id','Product.vendor_id'=> $vendor_id ,'Product.is_active' => '1','Product.is_deleted' => '0'));
													$Categorydata=$this->Category->find('all',array('fields' => array('Category.id,Category.title'),'conditions' => array("Category.title <= $price_coupon_limit" ,'Category.is_active' => '1','Category.is_deleted' => '0'),'recursive' => 0,'joins' => array($product_join)) );
													
													$Categorydata= Set::extract('/Category/.',$Categorydata); 
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
			 	
			 	$Coupon = $this->Coupon->find('first',array("conditions"=>array('Coupon.coupon_id'=> $coupon_id,'Coupon.is_sold'=> '1','Coupon.is_active'=> '0','Coupon.is_deleted'=> '1')));
			 		//pr($Coupon); die;
			 	foreach($Coupon as $value){
			 		$validvendorID = $Coupon['Coupon']['vendor_id'];
					$validcategoryID = $Coupon['Coupon']['category_id'];
			 	}
				$Productdata = $this->Product->find('list',array("fields"=>array('Product.product_code','Product.description'),"conditions"=>array("Product.product_code" => $Coupon['Coupon']['product_code'],"Product.is_active" => '1',"Product.is_deleted"=> '0')));
			 	$Vendordata = $this->Vendor->find('list',array("fields"=>array('Vendor.id','Vendor.name'),"conditions"=>array("Vendor.id" => $validvendorID,"Vendor.is_active" => '1',"Vendor.is_deleted"=> '0')));
				$Categorydata = $this->Category->find('list',array("fields"=>array('Category.id','Category.title'),"conditions"=>array("Category.id" => $validcategoryID,"Category.is_active" => '1',"Category.is_deleted"=> '0')));
				 
				
				foreach($Productdata as $k => $val){
					$newphrase='';
					if(!empty($val)){
						$searchText = array('{VENDOR_NAME}','{VOUCHER}','{COUPON_CODE}','{ACTIVATION_CODE}','{n}');
						$replaceText = array($Vendordata[$validvendorID],$Categorydata[$validcategoryID],$coupon_id,$a_code,'');
						$newphrase = str_replace($searchText, $replaceText, $val);
					 	
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
			 	
			 	$Coupon = $this->Coupon->find('first',array("conditions"=>array('Coupon.coupon_id'=> $coupon_id,'Coupon.is_sold'=> '1','Coupon.is_active'=> '0','Coupon.is_deleted'=> '1')));
			 	//	 pr($Coupon); die;
			 	foreach($Coupon as $value){
			 		$validvendorID = $Coupon['Coupon']['vendor_id'];
					$validcategoryID = $Coupon['Coupon']['category_id'];
			 	}
				$Productdata = $this->Product->find('list',array("fields"=>array('Product.product_code','Product.description'),"conditions"=>array("Product.product_code" => $Coupon['Coupon']['product_code'],"Product.is_active" => '1',"Product.is_deleted"=> '0')));
			 	$Vendordata = $this->Vendor->find('list',array("fields"=>array('Vendor.id','Vendor.name'),"conditions"=>array("Vendor.id" => $validvendorID,"Vendor.is_active" => '1',"Vendor.is_deleted"=> '0')));
				$Categorydata = $this->Category->find('list',array("fields"=>array('Category.id','Category.title'),"conditions"=>array("Category.id" => $validcategoryID,"Category.is_active" => '1',"Category.is_deleted"=> '0')));
				 
				
				foreach($Productdata as $k => $val){
					$newphrase='';
					if(!empty($val)){
						$searchText = array('{VENDOR_NAME}','{VOUCHER}','{COUPON_CODE}','{ACTIVATION_CODE}','{n}');
						$replaceText = array($Vendordata[$validvendorID],$Categorydata[$validcategoryID],$coupon_id,$a_code,'%0a');
						$newphrase = str_replace($searchText, $replaceText, $val);
					 	
					} 									
				} 	 		//echo strip_tags($newphrase); 
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
				//echo $url;// die; 
				// The url is opened 
			 	$reply = file_get_contents($finalurl);
				if(strstr($reply, "<succes>")) {
					//return TRUE;
					// If the reply contains the tag <succes> the SMS has been sent.
					//echo "The message has been sent. Server response: ".$reply;
					echo "The message has been sent successfully.";
				} else {
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
		
		App::import("Model", "CouponSale");
		$this->CouponSale = new CouponSale();
			
		$this->CouponSale->bindModel(
	        array(
	        	'belongsTo' => array(  
	                'UserProfile' => array(
	                    'className' => 'UserProfile',
	                    'foreignKey' => 'dealer_id'
	                ),
	                'Coupon' => array(
	                    'className' => 'Coupon',
	                    'foreignKey' => 'coupon_id'
	                )
	            )
	        )
	    );
		 
		$conditions = array();
		$user=$this->Auth->user();
		//pr($user);
		if($user['role_id'] == 2){
				App::import("Model", "User");
				$this->User = new User();
				$sub_dealer =  $this->User->find('list',array('conditions' => array('parent_id' => $user['id']),'fields' => array('id','id')));
				$sub_dealer[] = $user['id'];
				$conditions = array_merge($conditions ,array("dealer_id"=> $sub_dealer ));
				//pr($sub_dealer);
		}
		
		if($user['role_id'] == 3){
		 	$conditions = array_merge($conditions ,array("dealer_id"=> $user['id'] )); 
		}		
		if(!empty($this->data)){ 
			$from = $this->data['Coupon']['from'];
			$to = $this->data['Coupon']['to'];
			$limit = $this->data['Coupon']['limit'];
	        if(trim($from) != ""){ 
				$from = $this->covertToSystemDate($from);
				$conditions = array_merge($conditions ,array("DATE_FORMAT(CouponSale.created,'%Y-%m-%d') >="=>trim($from)));
			}
			if(trim($to) != ""){ 
				$to = $this->covertToSystemDate($to);
				$conditions = array_merge($conditions ,array("DATE_FORMAT(CouponSale.created,'%Y-%m-%d') <="=>trim($to)));
			}
			 
			if(trim($limit) == "" || trim($limit) <=0){ 
	         	 $limit = 100;
	    	} 
	    }else{
	    	$limit = 100;
	    }   
		$coupon_data = $this->CouponSale->find('all',array('conditions' => $conditions,'order' =>array("CouponSale.created"=>"desc"),'limit'=>$limit));  
		$this->set('coupon_data',$coupon_data);
		//pr($coupon_data);  die; 
	}
	
}
?>