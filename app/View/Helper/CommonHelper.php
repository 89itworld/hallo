<?php
class CommonHelper extends AppHelper {
	
	var $helpers = array('Form', 'Html', 'Link');
	
	
					
/******This function used for Shown Image
 * 	@return =>  Active/Inactive image with link 
 */
 
 	function is_active($status=null,$link = null){
		
 		if($status){
 			 echo $this->Html->link(
					    $this->Html->image('/img/active.gif',array('title' =>'Active','alt' => 'Active')),$link,array('escape' => false));
 		}else{
 			echo $this->Html->link(
					    $this->Html->image('/img/inactive.gif',array('title' =>'Inactive','alt' => 'Inactive')),$link, array('escape' => false));}
 	}
	


/******This function used for Format an date
 * 	@return =>  Format Date text only 
 */
 
 	function format_date($date=null,$format=null){
 		 
		if(!empty($date) && !empty($format)){
			
			switch($format){
				
				case 'F d,Y':
					return date('F d,Y',strtotime($date));
				break;
					
				default:
					return date('F d,Y',strtotime($date));
				break;
			} 
			
		}
 		
 	}
	
	/*
	* Function for make search url 2_1_2013
	*/
	function make_search_url($fields = array()){
		$urlArray=array();
		$condition = isset($this->params['named']['sort'])?$this->params['named']:$this->params['url'];
		//$condition = isset($this->params['named']['page'])?$this->params['named']:$this->params['url'];
		$query_string = "";
		if(!empty($condition)){
			foreach($condition as $field=>$val){
				if(in_array($field,$fields)){
					if(is_array($val)){
						$urlArray = array_merge($urlArray,array($field=>implode("))|((",$val)));
						foreach($val as $v){
						   $query_string .= $field."[]=".$v."&";
						}
								    
					}else{
					    $urlArray = array_merge($urlArray,array($field=>$val));
					    $query_string .= $field."=".$val."&";
					}
					
				}
			}
		}
		return array("urlArray"=>$urlArray,"query_string"=>$query_string);
	}
}
?>
