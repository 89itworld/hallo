<?php
class Category extends AppModel {
	
	var $name = 'Category';
	var $errMsg = array();
	
	var $hasMany = array("Product"=>array("className"=>"Product"));
	
	var $actsAs = array("Containable");
	
	function validate_add_category($postArray=null){
		
		$id = null;
		if(isset($postArray['id']) && ($postArray['id'] != "")){
			$id = $postArray['id']; 
		}
		if(trim($postArray['title']) == ""){
			$this->errMsg['title'][] = REQUIRED_CATEGORY_ERROR."\n";
		}elseif(!is_numeric($postArray['title']) && ($postArray['title'] <= 0)){
			$this->errMsg['title'][] = ERROR_VOUCHER_INVALID."\n";
		}elseif(!$this->__is_category_valid($postArray['title'],$id)){
			$this->errMsg['title'][] = ERROR_CATEGORY_INVALID."\n";
		} 
		return $this->errMsg;
		/*
		elseif(!preg_match(ALPHABET_WHITESPACE,trim($postArray['title']))){
			$this->errMsg['title'][] = CHARACTER_CATEGORY_ERROR."\n";
		}elseif(strlen(trim($postArray['title'])) < 3){	       		 
			$this->errMsg['title'][] =  MINIMUM_CHARACTER_CATEGORY_ERROR."\n";	       		 
		} 
		*/

	}
	
	/*
	* Function for check validity of title
	*/
	
	function __is_category_valid($category_title = null,$category_id = null){
        
		if($category_id == null){
		    $data = $this->find("count",array("conditions"=>array("Category.title"=>$category_title,"Category.is_deleted"=>"0")));
		    if($data == 0){
			return true;
		    }
		}else{
			$data = $this->find("count",array("conditions"=>array("Category.title"=>$category_title,'Category.id'=>$category_id,"Category.is_deleted"=>"0")));
			if($data == 1){
				return true;
			}elseif($data == 0){
				$data = $this->find("count",array("conditions"=>array("Category.title"=>$category_title,'Category.id !='=>$category_id,"Category.is_deleted"=>"0")));
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
	* Overwrite paginate function for voucher reporting
	*/
	
	function paginate($condition, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		$join = isset($extra['joins'])?$extra['joins']:array();
		if(empty($order)){
		    $order = array($extra['passit']['sort'] => $extra['passit']['direction']);
		}
		$group = isset($extra['group'])?$extra['group']:"";
		return $this->find('all', array("recursive"=>$recursive,"fields"=>$fields,"joins"=>$join,'conditions'=>$condition,"limit"=>$limit,'group' => $group,'order'=>$order,'page'=>$page));
	
	}
	
	
	
}
?>