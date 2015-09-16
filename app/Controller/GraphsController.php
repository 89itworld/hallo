<?php

class GraphsController extends AppController {
    
    var $uses=array();
    
    function admin_index($case=1) {
	
	$this->layout = "";
	$this->autoRender = false;
	$month_list= array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
	switch ($case) {
	    case '1':{
		App::import('Model','Reporter');
		$this->Reporter= new Reporter();
		$query="SELECT CASE WHEN parent_id =0 THEN dealer_id ELSE parent_id END AS deal_id, sum( price ) as total , MONTH(created) FROM coupon_sales GROUP BY MONTH( `created` ) , deal_id";
		$data=$this->Reporter->query($query);
		$temp=array();
		$month=array();
		foreach ($data as $key => $value) {
			if(!in_array($value[0]['MONTH(created)'],$month)){
				$month[]=$value[0]['MONTH(created)'];
			}
		}
		
		App::import('Model','User');
		$this->User=new User();
		$dealer_data=$this->User->query('SELECT c.user_id , concat( c.first_name, "-", c.last_name ) AS name
											FROM users as a
											INNER JOIN user_profiles as c ON c.user_id = a.id
											WHERE a.`role_id` =2 order by c.first_name ' );
		
		
		
		$temp[0][]="Month";
		foreach ($dealer_data as $key => $value) {
			$temp[0][]=$value[0]['name']."||".$value['c']['user_id'];
			
		}
	
	
		$j=1;
		foreach ($month as $key => $value) {
			$temp[$j][]=$month_list[$value-1];
			$j++;
		}
		
		$i=0;
		foreach($temp[0] as $key => $value) {
			if($i!=0){
				$temper=explode("||", $value);
				foreach ($month as $mkey => $mvalue) {
					$m=0;
					foreach ($data as $ekey => $evalue) {
						if($temper[1]==$evalue[0]['deal_id'] && $mvalue==$evalue[0]['MONTH(created)'] ){
							$m=1;
							$temp[$mkey+1][]=(int)$evalue[0]['total'];
							}
						}
						if($m!=1){
							$temp[$mkey+1][]=0;
						  }
				     }
			}
			$i++;
		
		}
		 foreach ($temp[0] as $key => $value) {
			$mtemp=explode('||', $value);
			$temp[0][$key]=ucwords(str_replace("-"," ",$mtemp[0]));
		}
		$temp=str_replace("\"", "'", $temp);
		$temp_new=array();
		$i=0;
		foreach ($temp as $key => $value) {
			foreach ($value as $mkey => $mvalue) {
				$temp_new[$i]['Key'][]=$mvalue;
			}
			$i++;
		}
		
		echo json_encode($temp_new);
		
		break;
	    }
	    case '2':{
		
		App::import('Model','CouponSale');
		$this->CouponSale = new CouponSale();
	       
		$data = $this->CouponSale->query("SELECT vendor_id, sum( price ) as total, MONTH(created) as month FROM coupon_sales GROUP BY MONTH( `created` ) ,vendor_id");
		
		App::import('Model','Vendor');
		$this->Vendor= new Vendor();
		
		$vendor_data = $this->Vendor->query("select `name`,`id` from vendors WHERE `is_deleted`='0' AND `is_active`='1'");
		
	       
		$temp=array();
		$month=array();
		foreach ($data as $key => $value) {
		    if(!in_array($value['0']['month'], $month) && !empty($value['0']['month'])){
			$month[]=$value['0']['month'];
		    }
		}
		
		$temp[0][]="Month";
		foreach ($vendor_data as $key => $value) {
		    $temp[0][]=ucfirst($value['vendors']['name'])."||".$value['vendors']['id'];
		}
		
		$j=1;
		foreach ($month as $key => $value) {
			$temp[$j][]=$month_list[$value-1];
			$j++;
		}
		
		$i=0;
		foreach($temp[0] as $key => $value) {
		    if($i!=0){
			$temper=explode("||", $value);
			foreach ($month as $mkey => $mvalue) {
			    $m=0;
			    foreach ($data as $ekey => $evalue) {
				if($temper[1]==$evalue['coupon_sales']['vendor_id'] && $mvalue==$evalue[0]['month'] ){
					$m=1;
					$temp[$mkey+1][]=(int)$evalue[0]['total'];
				}
			    }
			    if($m!=1){
				    $temp[$mkey+1][]=0;
			    }
			}
		    }
		    $i++;
		
		}
		
		
		foreach ($temp[0] as $key => $value) {
			$mtemp=explode('||', $value);
			$temp[0][$key]=ucwords(str_replace("-"," ",$mtemp[0]));
		}
		$temp=str_replace("\"", "'", $temp);
		$temp_new=array();
		$i=0;
		foreach ($temp as $key => $value) {
			foreach ($value as $mkey => $mvalue) {
				$temp_new[$i]['Key'][]=$mvalue;
			}
			$i++;
		}
		echo json_encode($temp_new);
		break;
	    }
	    case '3':{
		
		App::import("Model","Vendor");
		$this->Vendor= new Vendor();
		$vlist = $this->Vendor->find('list',array('conditions'=>array("Vendor.is_deleted"=>0),'order'=>'Vendor.name'));
		$vendor_ids = array_keys($vlist);
		
		App::import("Model","Category");
		$this->Category = new Category();
		$voucher_list = $this->Category->find("list",array("conditions"=>array("is_deleted"=>"0"),"fields"=>array("id","title")));
		$voucher_ids = array_keys($voucher_list);
	       
		
		App::import("Model","User");
		$this->User= new User();
		
		$ulist = $this->User->find('all',array('conditions'=>array('User.role_id'=>2,'User.is_deleted'=>'0'),'fields'=>array('User.id','concat(CONCAT(UCASE(SUBSTRING(UserProfile.first_name, 1, 1)),LCASE(SUBSTRING(UserProfile.first_name, 2)))," ",CONCAT(UCASE(SUBSTRING(UserProfile.last_name, 1, 1)),LCASE(SUBSTRING(UserProfile.last_name, 2)))) as name'),'recursive'=>0));
		
		if(!empty($ulist)){
		    $dlist = array();
		
		    foreach($ulist as $key => $value) {
			$dlist[$value['User']['id']]=$value['0']['name'];
		    }
		    
		    
		    $dealer_ids = array_keys($dlist);
		    
		    $from = (isset($_GET['from']) && ($_GET['from'] != ""))?$this->covertToSystemDate($_GET['from']):"1990-01-01";
		    $to = (isset($_GET['to']) && ($_GET['to'] != ""))?$this->covertToSystemDate($_GET['to']):date('Y-m-d');
		    $vendor = (isset($_GET['vendor']) && ($_GET['vendor'] != ""))?$_GET['vendor']:implode(',',$vendor_ids);
		    $dealer = (isset($_GET['dealer']) && ($_GET['dealer'] != ""))?$_GET['dealer']:implode(',',$dealer_ids);
		    $voucher = (isset($_GET['voucher']) && ($_GET['voucher'] != ""))?$_GET['voucher']:implode(',',$voucher_ids);
		   
		    App::import('Model','CouponSale');
		    $this->CouponSale = new CouponSale();
		   
		    $query = "SELECT CASE WHEN parent_id =0 THEN dealer_id ELSE parent_id END AS deal_id, sum( price ) as total , MONTH(created),YEAR(created) FROM coupon_sales  WHERE  `vendor_id` IN(".$vendor.") AND `category_id` IN(".$voucher.")AND (`dealer_id` IN(".$dealer.") OR `parent_id` IN(".$dealer."))AND `created` >='".date('Y-m-d h:i:s',strtotime($from))."' AND `created`<= '".date('Y-m-d h:i:s',strtotime($to))."' GROUP BY YEAR(`created`),MONTH( `created` ) , deal_id";
		    $data = $this->CouponSale->query($query);
		    
		    $temp=array();
		    $month=array();
		    foreach ($data as $key => $value) {
			if(!in_array($value[0]['MONTH(created)'],$month)){
			    if(!in_array($value[0]['YEAR(created)']."/".$value[0]['MONTH(created)'],$month)){
				$month[]=$value[0]['YEAR(created)']."/".$value[0]['MONTH(created)'];
			    }
			}
		    }
		    
		    //$month = array_unique($month);
		    
		    App::import('Model','User');
		    $this->User = new User();
		    
		    $dealer_data = $this->User->query('SELECT c.user_id , concat( c.first_name) AS name FROM users as a INNER JOIN user_profiles as c ON c.user_id = a.id WHERE a.`role_id` =2 AND a.`id` IN('.$dealer.') order by c.first_name');
		    
		    $temp[0][]="Year/Month";
		    foreach($dealer_data as $key => $value) {
			$temp[0][]= $value[0]['name']."||".$value['c']['user_id'];
		    }
		    
		    
		    $j = 1;
		    
		    foreach($month as $key => $value) {
			$new_val =  explode("/",$value);
			$temp[$j][] = $new_val[0]."/".$month_list[$new_val[1]-1];
			$j++;
		    }
		    
		    
		    
		    $i = 0;
		    foreach($temp[0] as $key => $value) {
			if($i != 0){
			    $temper=explode("||", $value);
			    
			    foreach ($month as $mkey => $mvalue) {	
				$m=0;
				foreach ($data as $ekey => $evalue) {
				    $mvalue_new = explode("/",$mvalue);
				    
				    if($temper[1] == $evalue[0]['deal_id'] && $mvalue_new[1] == $evalue[0]['MONTH(created)'] ){
					$m=1;
					$temp[$mkey+1][]=(int)$evalue[0]['total'];
				    }
				    
				}
			       
				if($m != 1){
				    $temp[$mkey+1][]=0;
				}
			    } 
			}
			$i++;
		    }
		    
		    
		    foreach ($temp[0] as $key => $value) {
			$mtemp = explode('||', $value);
			$temp[0][$key] = ucwords(str_replace("-"," ",$mtemp[0]));
		    }
		    
		    $temp = str_replace("\"", "'", $temp);
		    $temp_new = array();
		    $i=0;
		    foreach($temp as $key => $value) {
			foreach ($value as $mkey => $mvalue) {
			    $temp_new[$i]['Key'][]=$mvalue;
			}
			$i++;
		    }
		    
		    echo json_encode($temp_new);
		}else{
		    echo "[]";
		}
		break;
	    }
	    case '4':{
		
		App::import("Model","Vendor");
		$this->Vendor= new Vendor();
		$vlist = $this->Vendor->find('list',array('conditions'=>array("Vendor.is_deleted"=>0),'order'=>'Vendor.name'));
		$vendor_ids = array_keys($vlist);
		
		App::import("Model","Product");
		$this->Product = new Product();
		$product_code_list = $this->Product->find("list",array("conditions"=>array("is_deleted"=>"0"),"fields"=>array("product_code","title")));
		$product_codes = array_keys($product_code_list);
	       		
		App::import("Model","User");
		$this->User = new User();
		
		$ulist = $this->User->find('all',array('conditions'=>array('User.role_id'=>2,'User.is_deleted'=>'0'),'fields'=>array('User.id','concat(CONCAT(UCASE(SUBSTRING(UserProfile.first_name, 1, 1)),LCASE(SUBSTRING(UserProfile.first_name, 2)))," ",CONCAT(UCASE(SUBSTRING(UserProfile.last_name, 1, 1)),LCASE(SUBSTRING(UserProfile.last_name, 2)))) as name'),'recursive'=>0));
		
		if(!empty($ulist)){
		    $dlist = array();
		
		    foreach($ulist as $key => $value) {
			$dlist[$value['User']['id']]=$value['0']['name'];
		    }
		    
		    
		    $dealer_ids = array_keys($dlist);
		    
		    $from = ((isset($_GET['from']) && ($_GET['from'] != ""))?$this->covertToSystemDate($_GET['from']):"1990-01-01")." 00:00:01";
		    $to = ((isset($_GET['to']) && ($_GET['to'] != ""))?$this->covertToSystemDate($_GET['to']):date('Y-m-d'))." 23:59:59";
		    $vendor = implode(',',$vendor_ids);
		    $dealer = (isset($_GET['dealer']) && ($_GET['dealer'] != ""))?$_GET['dealer']:implode(',',$dealer_ids);
		    $formated_product_codes = array();
		    foreach($product_codes as $k1=>$v1){
			$formated_product_codes[$k1] = "'".$v1."'";
		    }
		    $product_code = implode(',',$formated_product_codes);
		   
		    $p_code = (isset($_GET['product_code']) && ($_GET['product_code'] != ""))?$_GET['product_code']:"";
		    if(trim($p_code) != ""){
			if(strpos($p_code,"_") === false){
			    $product_code = "'".$p_code."'";
			}else{
			    $vendor = substr($p_code,0,strpos($p_code,'_'));
			}
			
		    }
		   
		    App::import('Model','CouponSale');
		    $this->CouponSale = new CouponSale();
		   
		    $query = "SELECT CASE WHEN parent_id =0 THEN dealer_id ELSE parent_id END AS deal_id, sum( price ) as total , MONTH(created),YEAR(created),count(id) as total_coupon FROM coupon_sales  WHERE  `vendor_id` IN(".$vendor.") AND `product_code` IN(".$product_code.")AND (`dealer_id` IN(".$dealer."))AND `created` >='".date('Y-m-d G:i:s',strtotime($from))."' AND `created`<= '".date('Y-m-d G:i:s',strtotime($to))."' GROUP BY YEAR(`created`),MONTH( `created` ) , deal_id";
		    //OR `parent_id` IN(".$dealer.")
		    
		    $data = $this->CouponSale->query($query);
		   
		    $temp=array();
		    $month=array();
		    foreach ($data as $key => $value) {
			if(!in_array($value[0]['MONTH(created)'],$month)){
			    if(!in_array($value[0]['YEAR(created)']."/".$value[0]['MONTH(created)'],$month)){
				$month[]=$value[0]['YEAR(created)']."/".$value[0]['MONTH(created)'];
			    }
			}
		    }
		    
		    App::import('Model','User');
		    $this->User = new User();
		    
		    $dealer_data = $this->User->query('SELECT c.user_id , concat( c.first_name) AS name FROM users as a INNER JOIN user_profiles as c ON c.user_id = a.id WHERE a.`role_id` =2 AND a.`id` IN('.$dealer.') order by c.first_name');
		    $temp[0][]="Year/Month";
		    foreach($dealer_data as $key => $value) {
			$temp[0][]= $value[0]['name']."||".$value['c']['user_id'];
		    }
		    
		    $j = 1;
		    foreach($month as $key => $value) {
			$new_val =  explode("/",$value);
			$temp[$j][] = $new_val[0]."/".$month_list[$new_val[1]-1];
			$j++;
		    }
		    
		    $i = 0;
		    foreach($temp[0] as $key => $value) {
			if($i != 0){
			    $temper=explode("||", $value);
			    foreach ($month as $mkey => $mvalue) {	
				$m=0;
				foreach ($data as $ekey => $evalue) {
				    $mvalue_new = explode("/",$mvalue);
				    
				    if($temper[1] == $evalue[0]['deal_id'] && $mvalue_new[1] == $evalue[0]['MONTH(created)'] ){
					$m=1;
					$temp[$mkey+1][]=(int)$evalue[0]['total_coupon'];
				    }
				}
				if($m != 1){
				    $temp[$mkey+1][]=0;
				}
			    } 
			}
			$i++;
		    }
		    
		    foreach ($temp[0] as $key => $value) {
			$mtemp = explode('||', $value);
			$temp[0][$key] = ucwords(str_replace("-"," ",$mtemp[0]));
		    }
		    
		    $temp = str_replace("\"", "'", $temp);
		    $temp_new = array();
		    $i=0;
		    foreach($temp as $key => $value) {
			foreach ($value as $mkey => $mvalue) {
			    $temp_new[$i]['Key'][]=$mvalue;
			}
			$i++;
		    }
		    echo json_encode($temp_new);
		}else{
		    echo "[]";
		}
		break;
	    }
	    
	    
	    default:{
		echo "[]";
		break;
	    }
	}
	
    }
	    
    //flatten the array
    function _flatten(array $array) {
	$return = array();
	array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
	return $return;
    
    }
}
?>