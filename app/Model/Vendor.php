<?php
class Vendor extends AppModel {
	var $name = 'Vendor';
	public $errMsg = array(); 
	var $actsAs = array("Containable");
	
	var $hasMany = array("Product"=>array("className"=>"Product","foreignKey"=>"vendor_id",'conditions'=>array("is_active"=>1,"is_deleted"=>0),"order"=>"Product.category_id desc"));
	
	function validate_data($postArray = null){
	 	
		$name=trim($postArray['Vendor']['name']);
		$id = "";
		if(isset($postArray['Vendor']['id']) && ($postArray['Vendor']['id'] != "")){
			$id = $postArray['Vendor']['id']; 
		}
		if($name == ""){
			$this->errMsg['name'][] = ERR_VENDOR_REQUIRE."\n";
		}elseif(!$this->__is_vendor_valid($name,$id)){
			$this->errMsg['name'][] = ERROR_VENDOR_INVALID."\n";
		}
		if($id == ""){
			if($postArray['Vendor']['image']['name'] == ""){
				$this->errMsg['image'][] = ERR_IMAGE_REQUIRED."\n";
			}
		}
		return $this->errMsg;
	
		//elseif(!preg_match(VALIDATE_NAME_WITH_SPACE,$name)){
		//	$this->errMsg['name'][] = CHARACTER_ERROR."\n";
		//}elseif(strlen($name) < 3){	       		 
		//	$this->errMsg['name'][] =  MINIMUM_CHARACHTER_ERROR." 3 charachters.\n";	       		 
		//}
	}
	
	/*
	* Function for check validity of title
	*/
	
	function __is_vendor_valid($vendor_name = null,$vendor_id = null){
        
		if($vendor_id == null){
		    $data = $this->find("count",array("conditions"=>array("Vendor.name"=>$vendor_name,"Vendor.is_deleted"=>"0")));
		    if($data == 0){
			return true;
		    }
		}else{
			$data = $this->find("count",array("conditions"=>array("Vendor.name"=>$vendor_name,'Vendor.id'=>$vendor_id,"Vendor.is_deleted"=>"0")));
			if($data == 1){
				return true;
			}elseif($data == 0){
				$data = $this->find("count",array("conditions"=>array("Vendor.name"=>$vendor_name,'Vendor.id !='=>$vendor_id,"Vendor.is_deleted"=>"0")));
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
	* Overwrite paginate function for vendor reporting
	*/
	
	function paginate($condition, $fields, $order, $limit, $page, $recursive = null, $extra = array()){
		
		$join = isset($extra['joins'])?$extra['joins']:array();
		if(empty($order)){
		    $order = array($extra['passit']['sort'] => $extra['passit']['direction']);
		}
		$group = isset($extra['group'])?$extra['group']:"";
		return $this->find('all', array("recursive"=>$recursive,"fields"=>$fields,"joins"=>$join,'conditions'=>$condition,"limit"=>$limit,'group' => $group,'order'=>$order,'page'=>$page));
	
	}
	
	

	
}
?>