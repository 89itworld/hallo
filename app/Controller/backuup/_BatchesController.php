<?php
class BatchesController extends AppController{
	
	var $name = 'Batches';
	var $components = array('Auth','Session','RequestHandler');
	var $helpers = array('Form', 'Html', 'Js','Common','Paginator');
	
	/*
 	* This function used for batch listing 	
 	*/
	
	function admin_listing(){
 		
		$this->layout='backend/backend';
		$this->set("title_for_layout",BATCH_LISTING);

		$vendor_list = $this->find_vendor_listing();
		$this->set("vendor_list",$vendor_list);
		App::import("Model","Product");
		$this->Product = new Product();
		$product_data = $this->Product->find("list",array("fields"=>array("product_code","title"),"conditions"=>array("Product.is_deleted"=>"0","Product.is_active"=>"1")));
		$this->set("product_data",$product_data);
		
		$conditions = array("Batch.is_deleted"=>0);
		
		$from = isset($this->params['url']['from'])?$this->params['url']['from']:(isset($this->params['named']['from'])?$this->params['named']['from']:"");
		$to = isset($this->params['url']['to'])?$this->params['url']['to']:(isset($this->params['named']['to'])?$this->params['named']['to']:"");
		if(trim($from) != ""){
			$from = $this->covertToSystemDate($from);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Batch.created,'%Y-%m-%d') >="=>trim($from)));
		}
		if(trim($to) != ""){
			$to = $this->covertToSystemDate($to);
			$conditions = array_merge($conditions ,array("DATE_FORMAT(Batch.created,'%Y-%m-%d') <="=>trim($to)));
		}
		$this->paginate = array("recursive"=>-1,'limit'=>BATCH_LIMIT,'conditions'=>$conditions,'order'=>array("Batch.created"=>"desc"));
		$this->set('batch_data',$this->paginate('Batch'));
	}
	
	/*
	* Function for delete in bulk
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
			if($action == 2){//Delete selected records
				
				$this->$model_name->updateAll(array("$model_name.is_deleted"=>"'1'"),array("$model_name.id"=>$infected_records));
				if($model_name == "Batch"){
				    App::import("Model","Coupon");
				    $this->Coupon = new Coupon();
				    $this->Coupon->updateAll(array("Coupon.is_deleted"=>"'1'"),array("Coupon.batch_id"=>$infected_records));
				}
				$this->Session->setFlash(RECORD_DELETED, 'message/green');
			}
			$this->redirect($this->referer());exit();	
		}
	}
	
	/*
	* Function for delete batch
	*/
	
	function admin_delete_batch($batch_id = null){
		
		$this->layout = "";
		$this->autoRender = false;
		$batch_id = DECRYPT_DATA($batch_id);
		if(isset($batch_id)){
			$this->Batch->updateAll(array("Batch.is_deleted"=>"'1'"),array("Batch.id"=>$batch_id));
			App::import("Model","Coupon");
			$this->Coupon = new Coupon();
			$this->Coupon->updateAll(array("Coupon.is_deleted"=>"'1'"),array("Coupon.batch_id"=>$batch_id));
			$this->Session->setFlash(RECORD_DELETED, 'message/green');
			
		}
		$this->redirect($this->referer());exit();	
	}
 
		
	function admin_batch_popup($batch_id = null){ 
		$this->layout = "";
		
		$coupon_data=array();
		if(!empty($batch_id)  && $this->RequestHandler->isAjax()){
			$batch_id = DECRYPT_DATA($batch_id); 
			$conditions=array('Coupon.batch_id'=>$batch_id);			
			
			App::import("Model","Coupon");
			$this->Coupon = new Coupon();
			$this->paginate = array('recursive'=>-1,'limit' =>10,'conditions'=>$conditions,'order'=>array("Coupon.expire_date"=>"desc"));
			$this->set('coupon_data',$this->paginate('Coupon'));
		 
		}else{
				$this->redirect(array("controller"=>"dashboards","action"=>"unauthorize","admin"=>true));
		}
		 
		//$this->set('coupon_data',$coupon_data);
	}
}
?>