<?php
class CategoriesController extends AppController{
	
	var $name = 'Categories';
	var $components = array('Auth','Session','RequestHandler');
	var $helpers = array('Form', 'Html', 'Js','Common','Paginator');
	
	/*
 	* This function used for Category listing 	
 	*/

 	function admin_listing(){
 		
		$this->layout='backend/backend';
		$this->set("title_for_layout",CATEGORY_LISTING);
		$conditions = array("Category.is_deleted"=>0);
		
		$title = isset($this->params['url']['title'])?$this->params['url']['title']:(isset($this->params['named']['title'])?$this->params['named']['title']:"");
		if(trim($title) != ""){
			$conditions = array_merge($conditions ,array("Category.title like"=>"%".trim($title)."%"));
		}		
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		if(trim($is_active) != ""){
			$conditions = array_merge($conditions ,array("Category.is_active"=>$is_active));
		}
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
            
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Category.created,'%Y-%m-%d') >="=>trim($from)));
		}
		
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Category.created,'%Y-%m-%d') <="=>trim($to)));
		}
		
		$this->paginate = array('limit'=>CATEGORY_LIMIT,'conditions'=>$conditions,'order'=>array("Category.created"=>"desc","recursive"=>-1));
		$this->set('category_data',$this->paginate('Category'));
	}
	
	/*
	* Function for activate/deactivate/delete in bulk
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
			}
			else if($action == 2){//Delete selected records
			    $this->$model_name->updateAll(array("$model_name.is_deleted"=>"'1'"),array("$model_name.id"=>$infected_records));
			    $this->Session->setFlash(RECORD_DELETED, 'message/green');
			}
			
			$this->redirect($this->referer());exit();	
		}
			
	}
	
	/*
	* Function for activate/deactivate not in bulk
	*/
	
	function admin_activate_category($is_active = null,$cat_id = null,$model_name = null){
		
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
 	* This function used to Add Category
 	*/

 	function admin_add_category(){
 		
		$this->layout='backend/backend';
		$this->set("title_for_layout",ADD_CATEGORY);		
		if(!empty($this->data)){				
				
			/* Then, to check if the data validates, use the validates method of the model, 
			* which will return true if it validates and false if it doesn’t:
	 		*/ 
			$errors = $this->Category->validate_add_category($this->data['Category']);
			if(count($errors) == 0){
				
				if($this->Category->save($this->data)){
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array("controller"=>"categories","action"=>"listing","admin"=>true)); 
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array("controller"=>"categories","action"=>"add_category","admin"=>true));
				}		 
			}else{
				$this->set("errors",$errors);
			}
		}
	}
	
	/*
 	* Ajax  function for validating category while adding and editing category
 	*/
	
	function admin_validate_add_category_ajax(){
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",EDIT_CATEGORY);
		if($this->RequestHandler->isAjax()){
			$errors_msg = null;
			$data = $this->data;
			if(isset($this->data['Category']['id'])){
				$data['Category']['id'] = DECRYPT_DATA($data['Category']['id']);
			}
			$errors = $this->Category->validate_add_category($data['Category']);
			if ( is_array ($this->data) ){
				foreach ($this->data['Category'] as $key => $value ){
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
	* Function to add and edit category
	*/
	    
	function admin_edit_category($category_id = null){
		
		$category_id = DECRYPT_DATA($category_id); 
		$this->layout='backend/backend';
		$this->set("title_for_layout",EDIT_CATEGORY);
		if(!empty($this->data)){
			$data = $this->data;
			$data['Category']['id'] = DECRYPT_DATA($data['Category']['id']);
			$errors = $this->Category->validate_add_category($data['Category']);
			if(count($errors) == 0){
				if($this->Category->save($data)){
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array("controller"=>"categories","action"=>"listing","admin"=>true)); 
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array("controller"=>"categories","action"=>"edit_category",$this->data['Category']['id'],"admin"=>true));
				}
			}else{
				$this->set("errors",$errors);
			}
		}else if(isset($category_id)){
			if($this->is_id_exist($category_id,"Category")){
				$this->Category->id = $category_id;
				$data = $this->Category->read();
				$data['Category']['id'] = ENCRYPT_DATA($data['Category']['id']);
				$this->data = $data;
			}else{
				$this->Session->setFlash(NOT_FOUND_ERROR, 'message/red');
				$this->redirect(array("controller"=>"categories",'action'=>'listing','admin'=>true));exit();
			}
		}
	}
	
	/*
 	* This function used for Category listing 	
 	*/

 	function admin_voucher_report(){
 		
		$this->layout='backend/backend';
		$this->set("title_for_layout",VOUCHER_REPORT);
		$conditions = array("Category.is_deleted"=>0);
		
		$title = isset($this->params['url']['title'])?$this->params['url']['title']:(isset($this->params['named']['title'])?$this->params['named']['title']:"");
		if(trim($title) != ""){
			$conditions = array_merge($conditions ,array("Category.title like"=>"%".trim($title)."%"));
		}		
		
		$is_active = isset($this->params['url']['is_active'])?trim($this->params['url']['is_active']):(isset($this->params['named']['is_active'])?$this->params['named']['is_active']:"");
		if(trim($is_active) != ""){
			$conditions = array_merge($conditions ,array("Category.is_active"=>$is_active));
		}
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
            
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Category.modified,'%Y-%m-%d') >="=>trim($from)));
		}
		
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Category.modified,'%Y-%m-%d') <="=>trim($to)));
		}
		
		$sort = isset($this->params['url']['sort'])?$this->params['url']['sort']:(isset($this->params['named']['sort'])?$this->params['named']['sort']:"total_price");
		$direction = isset($this->params['url']['direction'])?$this->params['url']['direction']:(isset($this->params['named']['direction'])?$this->params['named']['direction']:"desc");
		
		$join_coupon_sell = array('table'=>'coupon_sales','alias'=>'CouponSale','forignKey'=>false,'type'=>'left','conditions'=>array('CouponSale.category_id = Category.id'),'order'=>array("CouponSell.price"=>"asc"));
		$field = array('Category.*','sum(CouponSale.price) AS total_price');
		
		$this->paginate = array('recursive'=>-1,"fields"=>$field,"joins"=>array($join_coupon_sell),"conditions"=>$conditions,"limit"=>CATEGORY_LIMIT,'group' => 'Category.id','passit' => array("sort"=>$sort,"direction"=>$direction));
		
		$category_data = $this->paginate('Category');
		
		$this->set('category_data',$category_data);
		
		$csv_export = isset($this->params['url']['export_to_csv'])?$this->params['url']['export_to_csv']:(isset($this->params['named']['export_to_csv'])?$this->params['named']['export_to_csv']:"paginate");
		if($csv_export == "csv"){
		    $this->layout = "";
		    $this->generate_voucher_report_csv($category_data);
		}
		
		$pdf_export = isset($this->params['url']['export_to_pdf'])?$this->params['url']['export_to_pdf']:(isset($this->params['named']['export_to_pdf'])?$this->params['named']['export_to_pdf']:"paginate");
		if($pdf_export == "pdf"){
			$this->layout = "";
			$this->generate_voucher_report_pdf($category_data);
		}
	}
	
	/*
	* Function for generating voucher report csv
	*/
	
	function generate_voucher_report_csv($vendor_data=array()){
		
		App::import('Helper','csv');
		$csv = new csvHelper();
		$line = array('Voucher','Status','Total Sale(DKK)','Modified');
		$csv->addRow($line);
		if(!empty($vendor_data)){
			$status = array('Deactive','Active');
			
			foreach($vendor_data as $data){
			$sold_price = $data[0]['total_price'];
			$line = array(ucfirst($data['Category']['title']),$status[$data['Category']['is_active']],sprintf('%0.2f',$sold_price),$data['Category']['modified']);
			$csv->addRow($line);
			}
			
		}
		echo $csv->render("voucher_report".date("d/M/Y"));
		exit();
	}
	
	/*
	* Function for generating voucher report pdf
	*/
	
	function generate_voucher_report_pdf($data = null){
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
                $htmlcontent.="<strong><h2>Voucher Report</h2></strong>";
                
                $htmlcontent.="<br/>";
                // table start
                $htmlcontent.="<table border=\".5\" >
                <tr>
                        <td align='left' valign='top'><strong>Voucher</strong></td>
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
                                $htmlcontent.= ucfirst($result['Category']['title']); 
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$status[$result['Category']['is_active']];
                                $htmlcontent.="</td><td align='right' valign='top'>";
                                $htmlcontent.=sprintf("%0.2f",$total_sale);
                                $htmlcontent.="</td><td align='left' valign='top'>";
                                $htmlcontent.=$result['Category']['modified'];
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
}
?>