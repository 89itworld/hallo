<?php
class CommonComponent extends Component {
    	
		
		
/*******This function is used  display common Flash Message  for Actions Active/Inactive/Delete ******** 
 * 
 ******* Return @Message 
 */ 
		    public function actionFlashMessage($action=null,$message=null,$count=null) { 
			   
					if((!empty($action) && !empty($message)) || ( $action==0  && !empty($message) )){ 
						switch($action){								
							case '0':
								$action='inactive';
							break;
							case '1':
								$action='active';
							break;
							case '2':
								$action='deleted';
							break;	
						}
						$Message=$message;
						$patterns = array();
						$patterns[0]="{Record}";
						$patterns[1]="{Action}";
						 
						$replacements = array();
						$record=($count > 1)?'Records':'Record';
						$replacements[0] = $record;
						$replacements[1] = $action;
						$returnMsg=preg_replace($patterns, $replacements, $Message);	
						
						//remove placeHolder '{}' from $returnMsg
						$braces = array("{", "}");
						$message = str_replace($braces, "", $returnMsg); 
						   
					}
					return $message;
		    }
}


?>