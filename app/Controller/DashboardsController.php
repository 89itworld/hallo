<?php
class DashboardsController extends AppController {
	
	var $uses = null;
	var $name = 'Dashboards';
	var $components = array('Auth','Session','RequestHandler');
	var $helpers = array('Form', 'Html', 'Js');
 	 
	function admin_index(){
		
		$this->layout='backend/backend';
		$this->set("title_for_layout",DASHBOARD);
		
		App::import("Model","Vendor");
		$this->Vendor= new Vendor();
		$field = array("Vendor.name");
		$vendor_data = $this->Vendor->find("all",array("fields"=>$field,"conditions"=>array("Vendor.is_deleted"=>"0","Vendor.is_active"=>"1"),"order"=>"Vendor.name asc","contain"=>array("Product"=>array("fields"=>array("DISTINCT Product.vendor_id","Product.category_id")))));
		$category_list = $this->find_categories_list();
		$this->set("categories_list",$category_list);
		$this->set("vendors_data",$vendor_data);
		
		
	}
	
	/*
	* Function for unauthorize user
	*/
	
	function admin_unauthorize(){
		$this->layout='backend/backend';
		$this->set("title_for_layout",ANAUTHORIZE);
		
	}
	
	/*
	* Function for edit user
	*/
	
	function admin_my_profile($user_id = null){
		
		$this->layout = "backend/backend";
		$this->set("title_for_layout",MY_PROFILE);
		
		$user_id = !empty($user_id)?DECRYPT_DATA($user_id):$this->Session->read("Auth.User.id");
		App::import("Model","User");
		$this->User = new User();
		
		if(!empty($this->data)){
			$data = $this->data;
			$data['User']['id'] = DECRYPT_DATA($data['User']['id']);
			$data['UserProfile']['id'] = DECRYPT_DATA($data['UserProfile']['id']);
			$errors = $this->User->validate_add_user($data);
			if(count($errors) == 0){
				
				$user_id = $this->User->save_user($data);
				if($user_id != ""){
					$this->Session->setFlash(RECORD_SAVE, 'message/green');
					$this->redirect(array('controller'=>'users','action'=>'user_listing',"admin"=>true));
				}else{
					$this->Session->setFlash(RECORD_ERROR, 'message/red');
					$this->redirect(array('controller'=>'dashboards','action'=>'my_profile',ENCRYPT_DATA($this->data['User']['id']),"admin"=>true));
				}		 
			}else{
				$this->set("errors",$errors);
			}
		}else if(isset($user_id)){
			if($this->is_id_exist($user_id,"User")){
				$this->User->id = $user_id;
				$data = $this->User->read();
				$data['User']['id'] = ENCRYPT_DATA($data['User']['id']);
				$data['UserProfile']['id'] = ENCRYPT_DATA($data['UserProfile']['id']);
				$this->data = $data;
				
			}
			else{
				$this->Session->setFlash(NOT_FOUND_ERROR, 'message/red');
				$this->redirect(array('controller'=>'users','action'=>'user_listing','admin'=>true));exit();
			}
		}
	}
	
	/*
	* Ajax function for find coupon count
	*/
	
	function admin_coupon_statistics($vandoe_id = null){
	    
		$this->layout = "ajax";
		$this->autoRender = false;
	       
		App::import("Model","Coupon");
		$this->Coupon = new Coupon();
	        $categories_list = $this->find_categories_list();
		
		$handled_cat = $this->Coupon->find('list',array('conditions'=>array('Coupon.vendor_id'=>$vandoe_id,'Coupon.is_active'=>'1','Coupon.is_deleted'=>'0'),'fields'=>array('Coupon.id','Coupon.category_id')));
		
		$handled_cat = array_unique($handled_cat); 
		$large_array=array();
		foreach($handled_cat as $key=>$value){
			
			$total = $this->Coupon->find('count',array('conditions'=>array("Coupon.vendor_id"=>$vandoe_id,"is_active"=>"1","is_deleted"=>"0","Coupon.category_id"=>$value)));
			if(empty($total)){
				$large_array[$value]['total']=0;
			}else{
				$large_array[$value]['total']=$total;
			}
			$remain = $this->Coupon->find('count',array('conditions'=>array("Coupon.vendor_id"=>$vandoe_id,"Coupon.is_sold"=>"0","is_active"=>"1","is_deleted"=>"0","Coupon.expire_date >"=>date("Y-m-d"),"Coupon.category_id"=>$value)));
			if(empty($remain)){
				$large_array[$value]['remain']=0;
			}else{
				$large_array[$value]['remain']=$remain;
			}
			$exp = $this->Coupon->find('count',array('conditions'=>array("Coupon.vendor_id"=>$vandoe_id,"Coupon.is_sold"=>"0","is_active"=>"1","is_deleted"=>"0","Coupon.expire_date <"=>date("Y-m-d"),"Coupon.category_id"=>$value)));
			if(empty($exp)){
				$large_array[$value]['expired']=0;
			}else{
				$large_array[$value]['expired']=$exp;
			}
			$sold = $this->Coupon->find('count',array('conditions'=>array("Coupon.category_id"=>$value,"Coupon.vendor_id"=>$vandoe_id,"Coupon.is_sold"=>"1","is_active"=>"1","is_deleted"=>"0")));
			if(empty($sold)){
				$large_array[$value]['sold']=0;
			}else{
				$large_array[$value]['sold']=$sold;
				
			}
			$large_array[$value]['cat_name']=$categories_list[$value];
		}
		
		
		if(!empty($large_array)){
			$table_format = "<table class='table' cellspacing='0' width='100%'><thead><tr><th>Voucher</th><th>Total Coupon</th><th>Remaining Coupon</th><th>Sold Coupon</th><th>Expire Coupon</th></tr></thead></tbody>";
			foreach($large_array as $k=>$v){
				$table_format .= "<tr><td>".$v['cat_name']."</td><td style = 'color:#000;'>".$v['total']."</td><td style = 'color:#090;'>".$v['remain']."</td><td style = 'color:#00F;'>".$v['sold']."</td><td style = 'color:#F00;'>".$v['expired']."</td></tr>";
			}
			$table_format .="</tbody></table>";
		}else{
			$table_format = "<span style='color:#F00;'><b>No Coupon found.<b><span>";
		}
		echo $table_format;
		
	}
}

 ?>