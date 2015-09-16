<?php
class ProductsController extends AppController{
	var $name = 'Products';
	var $components = array('Auth','Session','RequestHandler');
	var $helpers = array('Form', 'Html', 'Js','Common');
	
	/*
 	* This function used for Category listing 	
 	*/

 	function admin_listing(){
 		
		$this->layout='backend/backend';
		$this->set("title_for_layout",PRODUCT_LISTING);
		
		$category_list = $this->find_categories_list();
		$this->set("category_list",$category_list);
		
		$vendor_list = $this->find_vendor_listing();
		$this->set("vendor_list",$vendor_list);
		
		$conditions=array("Product.is_deleted"=>0);
		
		$title = isset($this->params['url']['title'])?$this->params['url']['title']:(isset($this->params['named']['title'])?$this->params['named']['title']:"");
		if(trim($title) != ""){
			$conditions = array_merge($conditions ,array("Product.title like"=>"%".trim($title)."%"));
		}		
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		if(trim($is_active) != ""){
			$conditions = array_merge($conditions ,array("Product.is_active"=>$is_active));
		}
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
            
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Product.created,'%Y-%m-%d') >="=>trim($from)));
		}
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Product.created,'%Y-%m-%d') <="=>trim($to)));
		}
		
		$sort = isset($this->params['url']['sort'])?$this->params['url']['sort']:(isset($this->params['named']['sort'])?$this->params['named']['sort']:"created");
		
		$direction = isset($this->params['url']['direction'])?$this->params['url']['direction']:(isset($this->params['named']['direction'])?$this->params['named']['direction']:"desc");
		$order = $sort." ".$direction;
		
		$lt = (isset($this->params['url']['lt']) && ($this->params['url']['lt'] != ""))?$this->params['url']['lt']:((isset($this->params['named']['lt']) && ($this->params['named']['lt'] != ""))?$this->params['named']['lt']:PRODUCT_LIMIT);
		
		
		$this->paginate = array("recursive"=>-1,'limit' =>$lt,'conditions'=>$conditions,'order' =>$order);
		
		$product_data = $this->paginate('Product');  
		$this->set('product_data',$product_data);
		
		$csv_export = isset($this->params['url']['export_to_csv'])?$this->params['url']['export_to_csv']:(isset($this->params['named']['export_to_csv'])?$this->params['named']['export_to_csv']:"paginate");
		if($csv_export=="csv"){
		    $this->layout = "";
		    $this->generate_product_report_csv($product_data);
		}
		
		$pdf_export = isset($this->params['url']['export_to_pdf'])?$this->params['url']['export_to_pdf']:(isset($this->params['named']['export_to_pdf'])?$this->params['named']['export_to_pdf']:"paginate");
		if($pdf_export == "pdf"){
			$this->layout = "";
			$this->generate_product_report_pdf($product_data);
		}
	}
	
	
	/*
	* Function for generating product report csv
	*/
	
	function generate_product_report_csv($c_data=array()){
		
		App::import('Helper','csv');
		$csv = new csvHelper();
		$line = array('Product','Product Code','Status','created');
		$csv->addRow($line);
		if(!empty($c_data)){
			$status = array('Deactive','Active');
			$status_sold = array(1=>"Yes",0=>"No");
			
			foreach($c_data as $data){
			$line = array($data['Product']['title'],$data['Product']['product_code'],$status[$data['Product']['is_active']],$data['Product']['created']);
			$csv->addRow($line);
			}
			
		}
		echo $csv->render("product_report".date("d/M/Y"));
		exit();
	}
	
	/*
	* Function for generating product report pdf
	*/
	
	function generate_product_report_pdf($data = null){
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
                $htmlcontent.="<strong><h2>Product Report</h2></strong>";
                
                $htmlcontent.="<br/>";
                // table start
                $htmlcontent.="<table border=\".5\" >
                <tr>
                        <td align='left' valign='top'><strong>Product</strong></td>
			<td align='left' valign='top'><strong>Product Code</strong></td>
			<td align='left' valign='top'><strong>Status</strong></td>
			<td align='left' valign='top'><strong>Created</strong></td>
                </tr>";
                $charges="No";
                if(count($data)>0){ 
                        $status = array('Deactive','Active');
                        foreach($data as $result){
				$htmlcontent.="<tr><td align='left' valign='top'>";
                                $htmlcontent.= $result['Product']['title'];
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.= $result['Product']['product_code']; 
				$htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$status[$result['Product']['is_active']];
				$htmlcontent.="</td><td align='left' valign='top'>";
				$htmlcontent.=$result['Product']['created'];
                                $htmlcontent.="</td></tr>";
                        }
                }
                else {
                    $htmlcontent.="<tr><td colspan='4' style='border-bottom:0px' class='tc'>No records found</td></tr>";
                }
                
                
                $htmlcontent.="</table></body></html>";	    
                
                // output the HTML content	    
                $tcpdf->writeHTML($htmlcontent, true, 0, true, 0);
                
                $tcpdf->Output('product_report.pdf', 'D');
                exit();
	}
	
	
	
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
			}
			/*else if($action == 2){//Delete selected records
			    $this->$model_name->updateAll(array("$model_name.is_deleted"=>"'1'"),array("$model_name.id"=>$infected_records));
			    $this->Session->setFlash(RECORD_DELETED, 'message/green');
			}*/
			$this->redirect($this->referer());exit();	
		}
			
	}
	
	/*
	* Function for delete user
	*/
	
	function admin_delete_bulk($product_id = null,$product_code = null,$model_name = null){
		
		$this->layout = "";
		$this->autoRender = false;
		$id = DECRYPT_DATA($product_id);
		$product_code = DECRYPT_DATA($product_code);
		if(isset($product_id) && (isset($product_code))){
			App::import("Model",$model_name);
			$this->$model_name = new $model_name();
			$this->$model_name->updateAll(array("$model_name.is_deleted"=>"'1'"),array("$model_name.id"=>$id));
			App::import("Model","Batch");
			$this->Batch = new Batch();
			$this->Batch->updateAll(array("Batch.is_deleted"=>"'1'"),array("Batch.product_code"=>$product_code));
			App::import("Model","Coupon");
			$this->Coupon = new Coupon();
			$this->Coupon->updateAll(array("Coupon.is_deleted"=>"'1'"),array("Coupon.product_code"=>$product_code));
			$this->Session->setFlash(RECORD_DELETED, 'message/green');
		}
		$this->redirect($this->referer());exit();	
	}
	
	/*
	*
	*/
	function admin_activate_product($is_active = null,$cat_id = null,$model_name = null){
		
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
 	* This function used for Add Category
 	*/

 	function admin_add_product(){
 		
		$this->layout='backend/backend';
		$this->set("title_for_layout",ADD_PRODUCT);
		$category_list = $this->find_categories_list();
		$this->set("category_list",$category_list);
		
		$vendor_list = $this->find_vendor_listing();
		$this->set("vendor_list",$vendor_list);
		
		if(!empty($this->data)){				
			
			/* Then, to check if the data validates, use the validates method of the model, 
			* which will return true if it validates and false if it doesn’t:
	 		*/ 
			$errors = $this->Product->validate_add_product($this->data['Product']);
			if(count($errors) == 0){
				$data = $this->data;
				$voucher_value['Category']['title'] = $data["Product"]['voucher_value'];
				App::import("Model","Category");
				$this->Category = new Category();
				$check_title = $this->Category->find('first',array("recursive"=>-1,"fields"=>array("id"),"conditions"=>array("Category.title"=>$voucher_value['Category']['title'])));
				if(!empty($check_title)){
					$data["Product"]['category_id'] = $check_title['Category']['id'];
				}else{
					$this->Category->save($voucher_value);
					$data["Product"]['category_id'] = $this->Category->getLastInsertId();
				}
				
				
				if($this->Product->save($data)){
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array("controller"=>"products","action"=>"listing","admin"=>true)); 
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array("controller"=>"products","action"=>"add_product","admin"=>true));
				}		 
			}else{
				$this->set("errors",$errors);
			}
		}
	}
	
	
	/*
	* Ajax function for validate product while add or edit product
 	*/
	
	function admin_validate_add_product_ajax(){
		$this->layout = "";
		$this->autoRender = false;
		
		if($this->RequestHandler->isAjax()){
			
			$errors_msg = null;
			$data = $this->data;
			
			if(isset($this->data['Product']['id'])){
				$data['Product']['id'] = DECRYPT_DATA($data['Product']['id']);
			}
			
			$errors = $this->Product->validate_add_product($data['Product']);
			if (is_array ($this->data) ){
				foreach ($this->data['Product'] as $key => $value ){
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
	* Function for add and edit category
	*/
	    
	function admin_edit_product($product_id = null){
		
		$product_id = DECRYPT_DATA($product_id);
		$this->layout='backend/backend';
		$this->set("title_for_layout",EDIT_PRODUCT);
		$category_list = $this->find_categories_list();
		$this->set("category_list",$category_list);
		
		$vendor_list = $this->find_vendor_listing();
		$this->set("vendor_list",$vendor_list);
		
		if(!empty($this->data)){
			$data = $this->data;
			$data['Product']['id'] = DECRYPT_DATA($data['Product']['id']);
			$errors = $this->Product->validate_add_product($data['Product']);
			if(count($errors) == 0){
				$voucher_value['Category']['title'] = $data["Product"]['voucher_value'];
				App::import("Model","Category");
				$this->Category = new Category();
				$check_title = $this->Category->find('first',array("recursive"=>-1,"fields"=>array("id"),"conditions"=>array("Category.title"=>$voucher_value['Category']['title'])));
				if(!empty($check_title)){
					$data["Product"]['category_id'] = $check_title['Category']['id'];
				}else{
					$this->Category->save($voucher_value);
					$data["Product"]['category_id'] = $this->Category->getLastInsertId();
				}
				if($this->Product->save($data)){
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array("controller"=>"products","action"=>"listing","admin"=>true)); 
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array("controller"=>"products","action"=>"edit_product",$this->data['Product']['id'],"admin"=>true));
				}
			}else{
				$this->set("errors",$errors);
			}
		}else if(isset($product_id)){
			if($this->is_id_exist($product_id,"Product")){
				$this->Product->id = $product_id;
				$this->Product->recursive = -1;
				$data = $this->Product->read();
				$data['Product']['id'] = ENCRYPT_DATA($data['Product']['id']);
				$this->data = $data;
				
				
			}else{
				$this->Session->setFlash(NOT_FOUND_ERROR, 'message/red');
				$this->redirect(array("controller"=>"products",'action'=>'listing','admin'=>true));exit();
			}
		}
	}
	
}
?>