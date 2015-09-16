<?php
class CmsPage extends AppModel {
	
	var $name='CmsPage';
	var $errMsg=array();
    
	function valid_edit_cms($post_array){
		
		$id = "";
		if(isset($post_array['CmsPage']['id'])){
			$id = $post_array['CmsPage']['id'];
		}
		if(trim($post_array['CmsPage']['title']) == ""){
			$this->errMsg['title'][] = ERR_CMS_EMPTY."\n";
		}elseif(!$this->__is_cms_valid($post_array['CmsPage']['title'],$id)){
			$this->errMsg['title'][] = ERR_CMS_INVALID."\n";
		}
		if(trim($post_array['CmsPage']['content']) == ""){
			$this->errMsg['content'][] = ERR_CMS_CONTENT_EMPTY."\n";
		}
		
		if(trim($post_array['CmsPage']['meta_title']) == ""){
			$this->errMsg['meta_title'][] = ERR_CMS_META_TITLE_EMPTY."\n";
		}elseif(!$this->__is_cms_meta_title_valid($post_array['CmsPage']['meta_title'],$id)){
			$this->errMsg['meta_title'][] = ERR_CMS_META_TITLE_INVALID."\n";
		}

		if(trim($post_array['CmsPage']['meta_description']) == ""){
			$this->errMsg['meta_description'][] = ERR_CMS_META_DESCRIPTION_EMPTY."\n";
		}

		if(trim($post_array['CmsPage']['page_order']) == ""){
			$this->errMsg['page_order'][] = ERR_CMS_PAGE_ORDER_EMPTY."\n";
		}elseif(!is_numeric($post_array['CmsPage']['page_order']) || (($post_array['CmsPage']['page_order']) <0)){
			
			$this->errMsg['page_order'][] = ERR_CMS_PAGE_ORDER_INVALID."\n";
		}elseif(!$this->__is_cms_page_order_valid($post_array['CmsPage']['page_order'],$id)){
			$this->errMsg['page_order'][] = ERR_CMS_PAGE_ORDER_INVALID."\n";
		}
			
		return $this->errMsg;
	}
	
	/*
	* Function for validate cms title validate
	*/
	
	function __is_cms_valid($cms_name = null,$cms_id = null){
	    
		if($cms_id != null){
			$data = $this->find("count",array("conditions"=>array("CmsPage.title"=>$cms_name,'CmsPage.id'=>$cms_id,"CmsPage.is_deleted"=>"0")));
			if($data == 1){
				return true;
			}else{
				$data = $this->find("count",array("conditions"=>array("CmsPage.title"=>$cms_name,"CmsPage.is_deleted"=>"0")));
				if($data == 1){
					return false;
				}else{
					return true;
				}
			}
		}else{
			$data = $this->find("count",array("conditions"=>array("CmsPage.title"=>$cms_name,"CmsPage.is_deleted"=>"0")));
			if($data == 1){
				return false;
			}else{
				return true;
			}
					
		}
	
	}
	
	/*
	* Function for validate cms meta title validate
	*/
	
	function __is_cms_meta_title_valid($cms_name = null,$cms_id = null){
	    
		if($cms_id != null){
			$data = $this->find("count",array("conditions"=>array("CmsPage.meta_title"=>$cms_name,'CmsPage.id'=>$cms_id,"CmsPage.is_deleted"=>"0")));
			if($data == 1){
				return true;
			}else{
				$data = $this->find("count",array("conditions"=>array("CmsPage.meta_title"=>$cms_name,"CmsPage.is_deleted"=>"0")));
				if($data == 1){
					return false;
				}else{
					return true;
				}
			}
		}else{
			$data = $this->find("count",array("conditions"=>array("CmsPage.meta_title"=>$cms_name,"CmsPage.is_deleted"=>"0")));
			if($data == 1){
				return false;
			}else{
				return true;
			}
					
		}
	
	}
	
	/*
	* Function for validate cms title validate
	*/
	function __is_cms_page_order_valid($order_id = null,$cms_id = null){
		if($cms_id != null){ 
			$data = $this->find("count",array("conditions"=>array("CmsPage.page_order"=>$order_id,'CmsPage.id'=>$cms_id,"CmsPage.is_deleted"=>"0")));
			
			if($data == 1){
				return true;
			}else{
				$data = $this->find("count",array("conditions"=>array("CmsPage.page_order"=>$order_id,"CmsPage.is_deleted"=>"0")));
				if($data == 1){
					return false;
				}else{
					return true;
				}
			}
		}else{
			$data = $this->find("count",array("conditions"=>array("CmsPage.page_order"=>$order_id,"CmsPage.is_deleted"=>"0")));
			if($data == 1){
				return false;
			}else{
				return true;
			}
		}
	}
}
?>