<?php
/***Custom Constants start **/
	

/**** Variables for common  start***/	
	define('REQUIRED','<font color=red>*</font>');	
	
	define('VALIDATE_NAME','/^[a-z]+$/i');						
	
	define("VALIDATE_NAME_WITH_SPACE","/^[a-zA-Z]+[a-zA-Z\ ]*$/i");
	
	define("VALIDATE_ALFHA_NUM_WITH_SPACE","/^[a-zA-Z0-9]+[a-zA-Z0-9\ ]*$/i");

	define("VALIDATE_ALFHA_NUM_WITHOUT_SPACE","/^[a-zA-Z0-9]+[a-zA-Z0-9]*$/i");
	
	 
/*++++++++++ Variables for common  end +++++++++++++++++++++++*/		


/**** Script for common  start***/	
	 
	
	if(env("SERVER_PORT") == "443"){
		define('HTTP_HOST', 'https://'.env('HTTP_HOST')."/");
	}else{
		define('HTTP_HOST', 'http://'.env('HTTP_HOST')."/"."cakephp-2.2.0/");
	}
	 
	
	/********* enc ***/	
	//var $key = 'PuTyOuRK3yHeRe';
	define('ENCRYPT_KEY','PuTyOuRK3xdsfzxyHeRe234');	
	
	function ENCRYPT_DATA($data = null) {
		if (($data != null) || ($data == 0)) {
			// Make an encryption resource using a cipher
			$td = mcrypt_module_open('cast-256', '', 'ecb', '');
			// Create and encryption vector based on the $td size and random
			$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
			// Initialize the module using the resource, my key and the string vector
			mcrypt_generic_init($td, ENCRYPT_KEY , $iv);
			// Encrypt the data using the $td resource
			$encrypted_data = mcrypt_generic($td, $data);
			// Encode in base64 for DB storage
			$encoded = base64_encode($encrypted_data);
			// Make sure the encryption modules get un-loaded
			if (!mcrypt_generic_deinit($td) || !mcrypt_module_close($td)) {
			    $encoded = false;
			}
		}else{
			$encoded = false;
		}
		return $encoded;
	}
	
	function DECRYPT_DATA($data = null) {
		if ($data != null) {
			// The reverse of encrypt.  See that function for details
			$data = (string) base64_decode(trim($data));
			$td = mcrypt_module_open('cast-256', '', 'ecb', '');
			$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
			mcrypt_generic_init($td, ENCRYPT_KEY, $iv);
			$data = (string) trim(mdecrypt_generic($td, $data));
			// Make sure the encryption modules get un-loaded
			if (!mcrypt_generic_deinit($td) || !mcrypt_module_close($td)) {
			    $data = false;
			}
		} else {
		    $data = false;
		}
		return $data;
	    } 
	

/*27_12_2012*/
define('ALPHABET_WHITESPACE','/^[a-zA-Z]+[a-zA-Z\ ]*$/i');
/*28_12_2012*/
define("CATEGORY_LIMIT",3);
define("PRODUCT_LIMIT",2);
define("VENDOR_LIMIT",2);
define("COUPON_LIMIT",50);


/*5_01_2013*/
define("ROLE_LIMIT",2);
define("USER_LIMIT",2);
/*5_01_2013*/
define("ONLY_LETTER","/^[a-zA-Z]+$/i");
define("REGULAR_EXPRESSION_EMAIL","/^([a-z0-9\\+_\\-]+)(\\.[a-z0-9\\+_\\-]+)*@([a-z0-9\\-]+\\.)+[a-z]{2,6}$/ix");

define('MAIL_DELIVERY','smtp');// other possible value 'smtp'  if specified 'smtp'  no need to modify other values.
/*define('SMTP_HOST','smtp.89itworld.com');
define('SMTP_USERNAME','test@89itworld.com');
define('SMTP_PWD','m%FvgM!3');
define('SMTP_PORT',"465");*///587

define('SMTP_HOST','ssl://smtp.gmail.com');
define('SMTP_USERNAME','89itworld@gmail.com');
define('SMTP_PWD','89itworld123');
define('SMTP_PORT',"465");//587

define('EMAIL_NOTIFICATION','support@hallo.dk');
define('NOREPLY_EMAIL','donotreply@hallo.dk');
define("EMAIL_FROM",'info@hallo.dk');
define("EMAIL_RESPONSE_GATEWAY","intekhab@89itword.com");// This is the admin email address where payment gateway send the response

?>