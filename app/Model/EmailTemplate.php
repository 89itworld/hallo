<?php
class EmailTemplate extends AppModel{
    var $name = 'EmailTemplate';
    var $errMsg=array();
    
    /*function valid_edit_email_template($post_array){
        $id = $post_array['EmailTemplate']['id'];
        if(trim($post_array['EmailTemplate']['title']) == ""){
	    $this->errMsg['title'][] = ERR_EMAIL_TEMPLATE_EMPTY."\n";
	}elseif(!$this->__is_email_template_valid($post_array['EmailTemplate']['title'],$id)){
	    $this->errMsg['title'][] = ERR_EMAIL_TEMPLATE_INVALID."\n";
	}
        if(trim($post_array['EmailTemplate']['subject']) == ""){
	    $this->errMsg['subject'][] = ERR_EMAIL_TEMPLATE_SUBJECT_EMPTY."\n";
	}elseif(!$this->__is_email_template_valid($post_array['EmailTemplate']['subject'],$id)){
	    $this->errMsg['subject'][] = ERR_EMAIL_TEMPLATE_SUBJECT_INVALID."\n";
	}
        
        if(trim($post_array['EmailTemplate']['description']) == ""){
            $this->errMsg['description'][] = ERR_EMAIL_TEMPLATE_DESCRIPTION_EMPTY."\n";
        }
        return $this->errMsg;
        
    }
    function __is_email_template_valid($template_name,$template_id){
        
        if($template_id != null){
            $data = $this->find("count",array("conditions"=>array("EmailTemplate.title"=>$template_name)));
            if($data == 0){
                return true;
            }
            $data = $this->find("count",array("conditions"=>array("EmailTemplate.title"=>$template_name,'EmailTemplate.id'=>$template_id)));
            if($data == 1){
                return true;
            }
            $new_id = $this->find("first",array("conditions"=>array("EmailTemplate.title"=>$template_name),'fields'=>array('EmailTemplate.id')));
            if(!isset($new_id['EmailTemplate']['id'])){
                return true;
            }
        }
	return false;
    }*/
}

?>