<?php
class Product extends AppModel {
	
	var $name = 'Product';
	var $errMsg = array();
	var $belongsTo = array("Category"=>array("className"=>"Category"),"Vendor"=>array("className"=>"Vendor"));
	var $actsAs = array("Containable");
	
	function validate_add_product($postArray=null){
		
		$id = null;
		$cat_id = null;
		if(isset($postArray['id']) && ($postArray['id'] != "")){
			$id = $postArray['id']; 
		}
		
		if(trim($postArray['vendor_id']) == ""){
			$this->errMsg['vendor_id'][] = REQUIRED_VENDOR_TYPE_ERROR."\n";
		}
		
		if(trim($postArray['voucher_value']) == ""){
			$this->errMsg['voucher_value'][] = REQUIRED_VOUCHER_ERROR."\n";
		}/*elseif(!is_numeric(trim($postArray['voucher_value'])) || (trim($postArray['voucher_value']) <= 0) || (trim($postArray['voucher_value']) > 9999)){
			$this->errMsg['voucher_value'][] = VALID_VOUCHER_ERROR."\n";
		}*/
			
		if(trim($postArray['p_id']) == ""){
			$this->errMsg['p_id'][] = REQUIRED_PRODUCTID_ERROR."\n";
		}elseif(!$this->__is_productid_valid($postArray['p_id'],$id)){
			$this->errMsg['p_id'][] = ERROR_PRODUCTID_INVALID."\n";
		}
		
		if(trim($postArray['title']) == ""){
			$this->errMsg['title'][] = REQUIRED_PRODUCT_ERROR."\n";
		}elseif(strlen(trim($postArray['title'])) < 3){	       		 
			$this->errMsg['title'][] =  MINIMUM_CHARACTER_PRODUCT_ERROR."\n";	       	 }
		/*
		elseif(!$this->__is_category_valid($postArray['title'],$id,$cat_id)){
			$this->errMsg['title'][] = ERROR_PRODUCT_INVALID."\n";
		}
		*/
		
		if(trim($postArray['product_code']) == ""){
			$this->errMsg['product_code'][] = REQUIRED_PRODUCT_ID_ERROR."\n";
		}elseif(!$this->__is_product_id_valid($postArray['product_code'],$id)){
			$this->errMsg['product_code'][] = ERROR_PRODUCT_ID_INVALID."\n";
		}
		
		if(isset($postArray['delimiter'])){
			if(trim($postArray['delimiter']) == ""){
				$this->errMsg['delimiter'][] = ERR_DELIMITER_EMPTY."\n";
			}elseif((strlen($postArray['delimiter']) != 1) || (preg_match("/^\W+$/",trim($postArray['delimiter'])) != 1)){
				$this->errMsg['delimiter'][] = ERR_VALID_DELIMITER."\n";
			}	
		}
		
		//{USERNAME}, {ADDRESS}, {CITY}, {POSTNUMBER}, {DATE}, {TIME}
		if(trim($postArray['description']) == ""){
			$this->errMsg['description'][] = REQUIRED_DESCRIPTION_ERROR."\n";
		}elseif(!preg_match("/{VENDOR_NAME}/",$postArray['description'])){
			$this->errMsg['description'][] = str_replace("{REPLACE}","{VENDOR_NAME}",ERR_VALID_PRODUCT_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['description'],"{VOUCHER}")){
			$this->errMsg['description'][] = str_replace("{REPLACE}","{VOUCHER}",ERR_VALID_PRODUCT_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['description'],"{COUPON_CODE}")){
			$this->errMsg['description'][] = str_replace("{REPLACE}","{COUPON_CODE}",ERR_VALID_PRODUCT_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['description'],"{ACTIVATION_CODE}")){
			$this->errMsg['description'][] = str_replace("{REPLACE}","{ACTIVATION_CODE}",ERR_VALID_PRODUCT_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['description'],"{USERNAME}")){
			$this->errMsg['description'][] = str_replace("{REPLACE}","{USERNAME}",ERR_VALID_PRODUCT_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['description'],"{ADDRESS}")){
			$this->errMsg['description'][] = str_replace("{REPLACE}","{ADDRESS}",ERR_VALID_PRODUCT_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['description'],"{CITY}")){
			$this->errMsg['description'][] = str_replace("{REPLACE}","{CITY}",ERR_VALID_PRODUCT_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['description'],"{POSTNUMBER}")){
			$this->errMsg['description'][] = str_replace("{REPLACE}","{POSTNUMBER}",ERR_VALID_PRODUCT_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['description'],"{DATE}")){
			$this->errMsg['description'][] = str_replace("{REPLACE}","{DATE}",ERR_VALID_PRODUCT_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['description'],"{TIME}")){
			$this->errMsg['description'][] = str_replace("{REPLACE}","{TIME}",ERR_VALID_PRODUCT_DESCRIPTION)."\n";
		}
		
		
		if(trim($postArray['sms_description']) == ""){
			$this->errMsg['sms_description'][] = REQUIRED_SMS_DESCRIPTION_ERROR."\n";
		}elseif(!preg_match("/{VENDOR_NAME}/",$postArray['description'])){
			$this->errMsg['sms_description'][] = str_replace("{REPLACE}","{VENDOR_NAME}",ERR_VALID_PRODUCT_SMS_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['sms_description'],"{VOUCHER}")){
			
			$this->errMsg['sms_description'][] = str_replace("{REPLACE}","{VOUCHER}",ERR_VALID_PRODUCT_SMS_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['sms_description'],"{COUPON_CODE}")){
			$this->errMsg['sms_description'][] = str_replace("{REPLACE}","{COUPON_CODE}",ERR_VALID_PRODUCT_SMS_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['sms_description'],"{ACTIVATION_CODE}")){
			$this->errMsg['sms_description'][] = str_replace("{REPLACE}","{ACTIVATION_CODE}",ERR_VALID_PRODUCT_SMS_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['sms_description'],"{USERNAME}")){
			$this->errMsg['sms_description'][] = str_replace("{REPLACE}","{USERNAME}",ERR_VALID_PRODUCT_SMS_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['sms_description'],"{ADDRESS}")){
			$this->errMsg['sms_description'][] = str_replace("{REPLACE}","{ADDRESS}",ERR_VALID_PRODUCT_SMS_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['sms_description'],"{CITY}")){
			$this->errMsg['sms_description'][] = str_replace("{REPLACE}","{CITY}",ERR_VALID_PRODUCT_SMS_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['sms_description'],"{POSTNUMBER}")){
			$this->errMsg['sms_description'][] = str_replace("{REPLACE}","{POSTNUMBER}",ERR_VALID_PRODUCT_SMS_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['sms_description'],"{DATE}")){
			$this->errMsg['sms_description'][] = str_replace("{REPLACE}","{DATE}",ERR_VALID_PRODUCT_SMS_DESCRIPTION)."\n";
		}elseif(!strpos($postArray['sms_description'],"{TIME}")){
			$this->errMsg['sms_description'][] = str_replace("{REPLACE}","{TIME}",ERR_VALID_PRODUCT_SMS_DESCRIPTION)."\n";
		}
		
		/*elseif(strlen($postArray['sms_description'])>WORD_COUNT){
			$this->errMsg['sms_description'][] = str_replace("{CHARACTER_LIMIT}",WORD_COUNT,ERR_VALID_SMS_CHARACTER_LIMIT)."\n";
		}*/
		
		/*
		elseif(!preg_match(ALPHABET_WHITESPACE,trim($postArray['title']))){
			$this->errMsg['title'][] = CHARACTER_PRODUCT_ERROR."\n";
		}
		*/
		return $this->errMsg;
	
	}
	
	/*
	* Function for check validity of product id
	*/
	
	function __is_productid_valid($pr_id = null,$product_id = null){
		
		if($product_id == null){
			$data = $this->find("count",array("conditions"=>array("Product.p_id"=>$pr_id,"Product.is_deleted"=>"0")));
			if($data == 0){
				return true;
			}
		}else{
			$data = $this->find("count",array("conditions"=>array("Product.p_id"=>$pr_id,'Product.id'=>$product_id,"Product.is_deleted"=>"0")));
			
			if($data == 1){
				return true;
			}elseif($data == 0){
				$data = $this->find("count",array("conditions"=>array("Product.p_id"=>$pr_id,'Product.id !='=>$product_id,"Product.is_deleted"=>"0")));
				if($data != 0){
					return false;
				}else{
					return true;
				}
			}	
		}
		return false;
	}
	
	/*
	* Function for check validity of title
	*/
	
	function __is_category_valid($product_title = null,$product_id = null,$category_id = null){
        
		if($product_id == null){
		    $data = $this->find("count",array("conditions"=>array("Product.title"=>$product_title,"Product.category_id"=>$category_id,"Product.is_deleted"=>"0")));
		    if($data == 0){
			return true;
		    }
		}else{
			$data = $this->find("count",array("conditions"=>array("Product.title"=>$product_title,'Product.id'=>$product_id,"Product.is_deleted"=>"0")));
			
			if($data == 1){
				return true;
			}elseif($data == 0){
				$data = $this->find("count",array("conditions"=>array("Product.title"=>$product_title,'Product.id !='=>$product_id,"Product.is_deleted"=>"0")));
				if($data != 0){
					return false;
				}else{
					return true;
				}
			}	
		}
		return false;
	}
	
	/*
	* Function for check validity of title
	*/
	
	function __is_product_id_valid($product_code = null,$product_id = null){
        
		if($product_id == null){
		    $data = $this->find("count",array("conditions"=>array("Product.product_code"=>$product_code,"Product.is_deleted"=>"0")));
		    if($data == 0){
			return true;
		    }
		}else{
			$data = $this->find("count",array("conditions"=>array("Product.product_code"=>$product_code,'Product.id'=>$product_id,"Product.is_deleted"=>"0")));
			
			if($data == 1){
				return true;
			}elseif($data == 0){
				$data = $this->find("count",array("conditions"=>array("Product.product_code"=>$product_code,'Product.id !='=>$product_id,"Product.is_deleted"=>"0")));
				if($data != 0){
					return false;
				}else{
					return true;
				}
			}	
		}
		return false;
	}
	
}
?>