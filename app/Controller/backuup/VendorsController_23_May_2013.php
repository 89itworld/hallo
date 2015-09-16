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
				if($this->Vendor->save($this->data)){
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array('controller'=>"vendors",'action'=>'list','admin'=>true)); 
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect($this->referer()); 
				}		 
			}else{
				$this->set("errors",$errors);
			}
			
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
				if($this->Vendor->save($data)){
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array("controller"=>"vendors","action"=>"list","admin"=>true)); 
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array("controller"=>"vendors","action"=>"edit",$this->data['Vendor']['id'],"admin"=>true));
				}
			}else{
				$this->set("errors",$errors);
			}
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
		
		$product_data = $this->Product->find("list",array("fields"=>array("Product.product_code","Product.product_code"),"conditions"=>array("Product.is_deleted"=>"0","Product.is_active"=>"1"),"order"=>"Product.product_code asc"));
		$this->set("product_data",$product_data);
		
		App::import("Model","Vendor");
		$this->Vendor = new Vendor();
		$vendor_data = $this->Vendor->find("list",array("fields"=>array("Vendor.id","Vendor.name"),"conditions"=>array("Vendor.is_deleted"=>"0","Vendor.is_active"=>"1"),"order"=>"Vendor.name asc"));
		$this->set("vendor_data",$vendor_data);
		
		App::import("Model","Category");
		$this->Category = new Category();
		$voucher_data = $this->Category->find("list",array("fields"=>array("Category.id","Category.title"),"conditions"=>array("Category.is_deleted"=>"0","Category.is_active"=>"1"),"order"=>"Category.title asc"));
		$this->set("voucher_data",$voucher_data);
		
		App::import("Model","Batch");
		$this->Batch = new Batch();
		$batch_data = $this->Batch->find("list",array("fields"=>array("Batch.id","Batch.name"),"conditions"=>array("Batch.is_deleted"=>"0","Batch.is_active"=>"1"),"order"=>"Batch.name asc"));
		$this->set("batch_data",$batch_data);
		
		$conditions = array("Coupon.is_deleted"=>"0","Coupon.is_pulled"=>"0");
		
		$ve_id = isset($this->params['url']['ve_i'])?trim($this->params['url']['ve_i']):(isset($this->params['named']['ve_i'])?$this->params['named']['ve_i']:"");
		if(trim($ve_id) != ""){
			$conditions = array_merge($conditions ,array("Coupon.vendor_id"=>$ve_id));
		}
		$vo_id = isset($this->params['url']['vo_i'])?trim($this->params['url']['vo_i']):(isset($this->params['named']['vo_i'])?$this->params['named']['vo_i']:"");
		if(trim($vo_id) != ""){
			$conditions = array_merge($conditions ,array("Coupon.category_id"=>$vo_id));
		}
		$p_code = isset($this->params['url']['product_code'])?trim($this->params['url']['product_code']):(isset($this->params['named']['product_code'])?$this->params['named']['product_code']:"");
		if(trim($p_code) != ""){
			$conditions = array_merge($conditions ,array("Coupon.product_code"=>$p_code));
		}
		
		$batch_id = isset($this->params['url']['batch_id'])?trim($this->params['url']['batch_id']):(isset($this->params['named']['batch_id'])?$this->params['named']['batch_id']:"");
		if(trim($batch_id) != ""){
			$conditions = array_merge($conditions ,array("Coupon.batch_id"=>$batch_id));
		}
		
		$is_sold = isset($this->params['url']['is_sold'])?trim($this->params['url']['is_sold']):(isset($this->params['named']['is_sold'])?$this->params['named']['is_sold']:"");
		if(trim($is_sold) != ""){
			$conditions = array_merge($conditions ,array("Coupon.is_sold"=>$is_sold));
		}
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		
		if(trim($is_active) != ""){
			$conditions = array_merge($conditions ,array("Coupon.is_active"=>$is_active));
		}
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
		$expire = isset($this->params['url']['expire'])?$this->params['url']['expire']:(isset($this->params['named']['expire'])?$this->params['named']['expire']:"");
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Coupon.created,'%Y-%m-%d') >="=>trim($from)));
		}
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Coupon.created,'%Y-%m-%d') <="=>trim($to)));
		}
		if(trim($expire) != ""){
			$expire = $this->covertToSystemDate($expire);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Coupon.expire_date,'%Y-%m-%d')"=>trim($expire)));
		}
		
		$sort = isset($this->params['url']['sort'])?$this->params['url']['sort']:(isset($this->params['named']['sort'])?$this->params['named']['sort']:"created");
		
		$direction = isset($this->params['url']['direction'])?$this->params['url']['direction']:(isset($this->params['named']['direction'])?$this->params['named']['direction']:"desc");
		$order = $sort." ".$direction;
		
		$lt = (isset($this->params['url']['lt']) && ($this->params['url']['lt'] != ""))?$this->params['url']['lt']:((isset($this->params['named']['lt']) && ($this->params['named']['lt'] != ""))?$this->params['named']['lt']:COUPON_LIMIT);
		
		$field = array("Coupon.*");
		
		$this->paginate = array("recursive"=>-1,"fields"=>$field,"conditions"=>$conditions,"limit"=>$lt,'order' =>$order);
		$coupon_data = $this->paginate('Coupon');
		
		$this->set('coupon_data',$coupon_data);
		
		$csv_export = isset($this->params['url']['export_to_csv'])?$this->params['url']['export_to_csv']:(isset($this->params['named']['export_to_csv'])?$this->params['named']['export_to_csv']:"");
		if($csv_export=="csv"){
		    $this->layout = "";
		    $this->generate_coupon_report_csv($coupon_data,$batch_data);
		}
		
		$pdf_export = isset($this->params['url']['export_to_pdf'])?$this->params['url']['export_to_pdf']:(isset($this->params['named']['export_to_pdf'])?$this->params['named']['export_to_pdf']:"");
		if($pdf_export == "pdf"){
			$this->layout = "";
			$this->generate_coupon_report_pdf($coupon_data,$batch_data);
		}
		
	}
	
	/*
	* Function for generating vendor report csv
	*/
	
	function generate_coupon_report_csv($c_data=array(),$b_data=array()){
		
		App::import('Helper','csv');
		$csv = new csvHelper();
		$line = array('Product Code','Batch','Coupon Id','Status','Sold','Expire Date','Sale Time');
		$csv->addRow($line);
		if(!empty($c_data)){
			$status = array('Deactive','Active');
			$status_sold = array(1=>"Yes",0=>"No");
			
			foreach($c_data as $data){
			$sale_time = ($data['Coupon']['is_sold'] == "1")?$data['Coupon']['modified']:"Not yet";
			$line = array($data['Coupon']['product_code'],$b_data[$data['Coupon']['batch_id']],$data['Coupon']['coupon_id'],$status[$data['Coupon']['is_active']],$status_sold[$data['Coupon']['is_sold']],$data['Coupon']['expire_date'],$sale_time);
			$csv->addRow($line);
			}
			
		}
		echo $csv->render("coupon_report".date("d/M/Y"));
		exit();
	}
	
	/*
	* Function for generating vendor report pdf
	*/
	
	function generate_coupon_report_pdf($data = null,$b_data=array()){
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
                        <td align='left' valign='top'><strong>Product Code</strong></td>
			<td align='left' valign='top'><strong>Batch</strong></td>
			<td align='left' valign='top'><strong>Coupon Id</strong></td>
                        <td align='left' valign='top'><strong>Status</strong></td>
			<td align='left' valign='top'><strong>Sold</strong></td>
                        <td align='left' valign='top'><strong>Expire Date</strong></td>
			<td align='left' valign='top'><strong>Sale Time</strong></td>
                        
                </tr>";
                $charges="No";
                if(count($data)>0){ 
                        $status = array('Deactive','Active');
                        $status_sold = array(1=>"Yes",0=>"No");
			
			foreach($data as $result){
				$sale_time = ($result['Coupon']['is_sold'] == "1")?$result['Coupon']['modified']:"Not yet";
			       
                                $htmlcontent.="<tr><td align='left' valign='top'>";
                                $htmlcontent.= $result['Coupon']['product_code'];
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.= $b_data[$result['Coupon']['batch_id']]; 
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.= $result['Coupon']['coupon_id']; 
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
                    $htmlcontent.="<tr><td colspan='6' style='border-bottom:0px' class='tc'>No records found</td></tr>";
                }
                
                
                $htmlcontent.="</table></body></html>";	    
                
                // output the HTML content	    
                $tcpdf->writeHTML($htmlcontent, true, 0, true, 0);
                
                $tcpdf->Output('coupon_report.pdf', 'D');
                exit();
	}
	
}
?>