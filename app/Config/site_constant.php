<?php
/***Custom Constants start **/
	
if(env("SERVER_PORT") == "443"){
	define('HTTP_HOST', 'https://'.env('HTTP_HOST')."/");
}else{
	define('HTTP_HOST', 'http://'.env('HTTP_HOST')."/hallo/");
}

define('REQUIRED',' <font color=red>*</font>');	

define('VALIDATE_NAME','/^[a-z]+$/i');						

define("VALIDATE_NAME_WITH_SPACE","/^[a-zA-Z]+[a-zA-Z\ ]*$/i");

define('CHARACTER_ERROR','Please enter only alphabets characters.');	

define('MINIMUM_CHARACHTER_ERROR','Please enter minimum ');	

define('ENCRYPT_KEY','DYhG93b0qyJfIxfs2guVoUasdubWwvniR2G0FgaC9mi');	

/*
* Function for encrypt data
*/

function ENCRYPT_DATA($str, $key=ENCRYPT_KEY){
	if( is_string($str) && is_string($key) ) {
		$str = mb_convert_encoding($str, 'HTML-ENTITIES', "UTF-8");
		$sl = strlen($str); $kl = strlen($key);
		$fk = str_split(str_repeat($key, ceil($sl/$kl)));
		$es = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$ea = array( 'a' => array(), 'm' => array(), 'c' => array());
		foreach( str_split( $es ) as $key => $char ) { $ea['a'][] = $key; $ea['m'][] = md5($char); $ea['c'][] = $char; }
		$ns = '';
		$sp = str_split($str);
		foreach( $fk as $k => $_char ) {
			if( ! isset( $sp[$k] ) ) break;
				$cm = md5($sp[$k]);
				if( in_array( $cm, $ea['m'] ) ) {
					$kk = array_keys( $ea['m'], $cm );
					if( in_array( md5($_char), $ea['m'] ) ) {
					    $sk = array_keys( $ea['m'], md5($_char) );
					    $n = $ea['a'][$kk[0]] - $ea['a'][$sk[0]];
					    if( $n < 0 )
						$n = $n + (strlen($es));
					    $ns .= $es[$n];
					}else
					    $ns .= $_char;
				}else
				    $ns .= $_char;
		}
		return $ns;
	}
}

/*
* Function for decrypt data
*/

function DECRYPT_DATA($str, $key=ENCRYPT_KEY){
	if( is_string($str) && is_string($key) ) {
		$str = mb_convert_encoding($str, 'HTML-ENTITIES', "UTF-8");
		$sl = strlen($str); $kl = strlen($key);
		$fk = str_split(str_repeat($key, ceil($sl/$kl)));
		$es = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$ea = array( 'a' => array(), 'm' => array(), 'c' => array());
		foreach( str_split( $es ) as $key => $char ) { $ea['a'][] = $key; $ea['m'][] = md5($char); $ea['c'][] = $char; }
		$ns = '';
		$sp = str_split($str);
		foreach( $fk as $k => $_char ) {
			if( ! isset( $sp[$k] ) ) break;
			$cm = md5($sp[$k]);
			if( in_array( $cm, $ea['m'] ) ) {
				$kk = array_keys( $ea['m'], $cm );
				if( in_array( md5($_char), $ea['m'] ) ) {
					$sk = array_keys( $ea['m'], md5($_char) );
					$n = $ea['a'][$kk[0]] + $ea['a'][$sk[0]];
					if( $n > (strlen($es)-1) )
					    $n = $n - strlen($es);
					$ns .= $es[$n];
				}else
					$ns .= $_char;
			}else
				$ns .= $_char;
		}
		return $ns;
	}
}  
	
define("CATEGORY_LIMIT",20);
define("PRODUCT_LIMIT",20);
define("VENDOR_LIMIT",20);
define("COUPON_LIMIT",50);	
define("COUPON_SELL_LIMIT",20);
define("ROLE_LIMIT",20);
define("USER_LIMIT",20);

define('ALPHABET_WHITESPACE','/^[a-zA-Z]+[a-zA-Z\ ]*$/i');
define("ONLY_LETTER","/^[a-zA-Z]+$/i");
define("REGULAR_EXPRESSION_EMAIL","/^([a-z0-9\\+_\\-]+)(\\.[a-z0-9\\+_\\-]+)*@([a-z0-9\\-]+\\.)+[a-z]{2,6}$/ix");

define('MAIL_DELIVERY','smtp');// other possible value 'smtp'  if specified 'smtp'  no need to modify other values.
/*define('SMTP_HOST','smtp.89itworld.com');
define('SMTP_USERNAME','test@89itworld.com');
define('SMTP_PWD','m%FvgM!3');
define('SMTP_PORT',"465");*///587

define('SMTP_HOST','ssl://smtp.gmail.com');
define('SMTP_USERNAME','89itworld@gmail.com');
define('SMTP_PWD','89itworld123##');
define('SMTP_PORT',"465");//587

define('EMAIL_NOTIFICATION','support@hallo.dk');
define('NOREPLY_EMAIL','donotreply@hallo.dk');
define("EMAIL_FROM",'info@hallo.dk');
define("EMAIL_RESPONSE_GATEWAY","intekhab@89itword.com");// This is the admin email address where payment gateway send the response

define("SITE_NAME","Hello.dk");
define("DEALER_LIMIT","10");
define("ONLY_LETTER_DENISH","/[0-9$&+,:;=?@#|_-]+/i");
define("CMS_PAGE_LIMIT",20);

define("CSV_UPLOAD_PATH",'img/coupon_csv_upload/');
define("BATCH_LIMIT",50);
?>