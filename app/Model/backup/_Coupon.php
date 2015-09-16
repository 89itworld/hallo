<?php
class Coupon extends AppModel {
	var $name='Coupon';
	public $errMsg=array();
	var $belongsTo = array("Product"=>array("className"=>"Product"));
	
	function validate_data($postArray = null){
		
		if(trim($postArray['category_id']) == ""){
			$this->errMsg['category_id'][] = REQUIRED_CATEGORY_TYPE_ERROR."\n";
		}
		if(trim($postArray['product_id']) == ""){
			$this->errMsg['product_id'][] = REQUIRED_PRODUCT_TYPE_ERROR."\n";
		}
		
		if(trim($postArray['coupon_id']) == ""){
			$this->errMsg['coupon_id'][] = 'Coupon id'.EMPTY_FIELD_ERROR."\n";
		}elseif(!preg_match(VALIDATE_ALFHA_NUM_WITHOUT_SPACE,trim($postArray['coupon_id']))){
				$this->errMsg['coupon_id'][] = 'Coupon id'.VALIDATE_ALFHA_NUM_WITHOUT_SPACE_ERROR."\n";
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
	* Function for save coupon data
	*/
	
	function save_data($data){
		
		$activation_data = "";
		$activation_data = implode('|',$data['Coupon']['activation_code']);
		$data['Coupon']['activation_code'] = $activation_data;
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
		$handle = fopen($filename, "r");
		 
		// read the 1st row as headings
		$header = fgetcsv($handle);
		$header_new = explode(";",$header[0]);
		$data_save = array();
		$data_save['Coupon']["product_id"] = $data_all['Coupon']['product_id'];
		$data_save['Coupon']["category_id"] = $data_all['Coupon']['category_id'];
		$data_save['Coupon']["created"] = $header_new[0];
		
		if(($header_new[1] == $data_all['Coupon']['category_title']) &&($header_new[2] == $data_all['Coupon']['product_id'])){
			// read each data row in the file
			$row = 1;
			while (($data_csv = fgetcsv($handle)) !== FALSE) {
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
							$this->create();
						}
					}
				}
				
			}
			fclose($handle);
			return true;
		}
		return false;
	}
 
	
}
?>