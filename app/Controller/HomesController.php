<?php
/**
 * Coupons Controller
 */
class HomesController extends AppController {
	 
	var $name = 'Homes';
	//var $components = array('Auth','Session','RequestHandler','Common');
	var $helpers = array('Form', 'Html', 'Js');
	var $uses = array();
 	
	/*
	* Function for setting index
	*/
	
	function index($view_value = null){
		
		$this->layout = 'front_end/default_new';
		
		App::import("Model","CmsPage");
		$this->CmsPage = new CmsPage();
		$page_list_data = $this->CmsPage->find("list",array("fields"=>array("CmsPage.id","CmsPage.title"),"conditions"=>array("CmsPage.is_deleted"=>"0","CmsPage.is_active"=>"1"),"order"=>array("CmsPage.page_order"=> "asc")));
		$this->set("page_list_data",$page_list_data);
		
		if($view_value == null || ($view_value == "1")){
			$view_value = "1";
			$cms_data = $this->CmsPage->find("first",array("fields"=>array("CmsPage.id","CmsPage.title","CmsPage.content","CmsPage.meta_title","CmsPage.meta_description"),"conditions"=>array("CmsPage.is_deleted"=>"0","CmsPage.is_active"=>"1",'CmsPage.id'=>$view_value)));
			$this->set("title_for_layout",$cms_data['CmsPage']['meta_title']);
			$this->set("meta_description",$cms_data['CmsPage']['meta_description']);
		}else{
			$cms_data = $this->CmsPage->find("first",array("fields"=>array("CmsPage.id","CmsPage.title","CmsPage.content","CmsPage.meta_title","CmsPage.meta_description"),"conditions"=>array("CmsPage.is_deleted"=>"0","CmsPage.is_active"=>"1",'CmsPage.id'=>$view_value)));
			$this->set("title_for_layout",$cms_data['CmsPage']['meta_title']);
			$this->set("meta_description",$cms_data['CmsPage']['meta_description']);
		}
		$this->set("cms_data",$cms_data);
	}
	
}
?>