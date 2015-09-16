<?php
class Coupon extends AppModel {
	
	var $name='Coupon';
	public $errMsg=array();
	
	function validate_data($postArray = null){
		
		$id = "";
		if(isset($postArray['id']) &&($postArray['id'] != "")){
			$id = $postArray['id'];
		}
		if(trim($postArray['vendor_id']) == ""){
			$this->errMsg['category_id'][] = REQUIRED_VENDOR_TYPE_ERROR."\n";
		}
		if(trim($postArray['category_id']) == ""){
			$this->errMsg['category_id'][] = REQUIRED_CATEGORY_TYPE_ERROR."\n";
		}
		if(trim($postArray['product_code']) == ""){
			$this->errMsg['product_code'][] = REQUIRED_PRODUCT_TYPE_ERROR."\n";
		}
		if(trim($postArray['expire_date']) == ""){
			$this->errMsg['expire_date'][] = REQUIRED_EXPIRE_DATE_ERROR."\n";
		}
		
		if(trim($postArray['coupon_id']) == ""){
			$this->errMsg['coupon_id'][] = ERR_COUPON_ID_REQUIRE."\n";
		}elseif(!$this->__is_coupon_id($postArray['coupon_id'],$id)){
			$this->errMsg['coupon_id'][] = ERROR_COUPON_ID_INVALID."\n";
		}
		
		foreach($postArray['activation_code'] as $k => $v){
			if($v == ""){
				$this->errMsg['activation_code'][] = REQUIRED_ACTIVATION_CODE_ERROR."\n";
				break;
			}
		}
		
		return $this->errMsg;
	}
	
	
	/*
	* Function for check validity of title
	*/
	
	function __is_coupon_id($couponi_code = null,$coupon_id = null){
        
		if($coupon_id == null){
			$data = $this->find("count",array("conditions"=>array("Coupon.coupon_id"=>$couponi_code,"Coupon.is_deleted"=>"0")));
			if($data == 0){
			    return true;
			}
		}else{
			$data = $this->find("count",array("conditions"=>array("Coupon.coupon_id"=>$couponi_code,'Coupon.id !='=>$coupon_id,"Coupon.is_deleted"=>"0")));
			if($data != 0){
				return false;
			}else{
				$data = $this->find("count",array("conditions"=>array("Coupon.coupon_id"=>$couponi_code,'Coupon.id'=>$coupon_id,"Coupon.is_deleted"=>"0")));
				if($data == 1){
					return true;
				}
			}
				
		}
		return false;
	}
	
	/*
	* Function for save coupon data
	*/
	
	function save_data($data){
		
		$activation_data = "";
		$activation_data = implode('|',$data['Coupon']['activation_code']);
		$data['Coupon']['activation_code'] = $activation_data;
		if(!isset($data['Coupon']['batch_id'])){
			$batch_data = array();
			App::import("Model","Batch");
			$this->Batch = new Batch();
			$batch_data['vendor_id'] = $data['Coupon']['vendor_id'];
			$batch_data['product_code'] = $data['Coupon']['product_code'];
			$batch_data['name'] = $data['Coupon']['product_code']."_".time();
			$this->Batch->save($batch_data);
			$data['Coupon']['batch_id'] = $this->Batch->getLastInsertId();;
		}
		$data['Coupon']['expire_date'] = date("Y-m-d",strtotime($data['Coupon']['expire_date']));
		if($this->save($data)){
			return true;
		}else{
			return false;
		}
	}
	
	/*
	* Function for save csv data
	*/
	
	function save_csv($filename = null,$data_all = null) {
		// to avoid having to tweak the contents of
		// $data you should use your db field name as the heading name
		// eg: Post.id, Post.title, Post.description
	 
		// set the filename to read CSV from
		//$filename = TMP . 'uploads' . DS . 'Post' . DS . $filename;
		 
		// open the file
		$cdata=FALSE;
		$data_count = 0;
		$handle = fopen($filename, "r");
		 
		// read the 1st row as headings
		$header = fgetcsv($handle);
		$header_new = explode(";",$header[0]);
		$data_save = array();
		$data_save['Coupon']["product_code"] = $data_all['Coupon']['product_code'];
		$data_save['Coupon']["expire_date"] = $data_all['Coupon']['expire_date'];
		$data_save['Coupon']["category_id"] = $data_all['Coupon']['category_id'];
		$data_save['Coupon']["vendor_id"] = $data_all['Coupon']['vendor_id'];
		 
		//$data_save['Coupon']["created"] = $header_new[0];
		
		if(($header_new[1] == $data_all['Coupon']['category_title']) &&($header_new[2] == $data_all['Coupon']['product_code'])){
			// read each data row in the file
			$row = 1;
			while (($data_csv = fgetcsv($handle)) !== FALSE) {
				
				if($row == 1 ){
						App::import("Model","Batch");
						$this->Batch = new Batch();
						$batch_name = $data_all['Coupon']['product_code'].'_'.time();
						$newbatch=array("Batch"=> array("name" => $batch_name,'vendor_id' => $data_all['Coupon']['vendor_id'], 'product_code' => $data_all['Coupon']['product_code']));
						$this->Batch->save($newbatch);
						$data_save['Coupon']["batch_id"] = $this->Batch->getLastInsertId();
				}
				
				$num = count($data_csv);
				$row++;
				for ($c=0; $c < $num; $c++) {
					$data_temp = explode(";",$data_csv[$c]);
					$data_save['Coupon']["coupon_id"] = $data_temp[0];
					unset($data_temp[0]);
					$activation_code = implode('|',$data_temp);
					$data_save['Coupon']["activation_code"] = $activation_code;
					if(!empty($data_temp) && (!empty($activation_code))){  
			 				if($this->save($data_save)){
								$data_count++;
								$this->create();
							} 
					}
				}
			}
			fclose($handle);
			return $data_count;
		}
		fclose($handle);
		return $data_count;
	}
 
	
}
?>