<?php
/***Custom Messages start **/
define("LOGIN_ERROR","");	
/*****Common Mesage  Start*****/

/***Success Message****/
define('RECORD_SAVE',"Record has been saved successfully.");
define('RECORD_DELETED',"Record has been deleted successfully.");
define('ACTION_SUCCESS',"{Record} has  been {Action} successfully."); 
define('NO_RECORD_FOUND',"No Record Found !"); 

/***Error Message *****/
define('RECORD_ERROR',"Record has not been saved...Please try again!");
define('RECORD_DELETED_ERROR',"Record has not been deleted...Please try again!");

define('ACTION_ERROR',"{Record} has not been {Action}...Please try again!");
define('EMPTY_ACTION_ERROR',"Please select an action to perform action!");
define('EMPTY_RECORD_ERROR',"Please select an record to perform action!");

define('INVALID_RECORD_ERROR',"Invalid request....Please try again!");

define('EMPTY_FIELD_ERROR',' can not be empty.');
define('CHARACTER_ERROR','Please enter only alphabets characters.');		
define('MINIMUM_CHARACHTER_ERROR','Please enter minimum ');	
define('VALIDATE_ALFHA_NUM_WITHOUT_SPACE_ERROR',' can only be alphanumeric characters.');	
	
/*****Common Mesage  End*****/
/*Common messages for categories 27_12_2012*/
define("CATEGORY_LISTING","Category Listing");
define("ADD_CATEGORY","Add Category");
define("REQUIRED_CATEGORY_ERROR","Please enter category title.");
define("EDIT_CATEGORY","Edit Category");
define("NOT_FOUND_ERROR","This record is not found.");
define("CHARACTER_CATEGORY_ERROR","Please enter only alphabets characters in category title.");
define('MINIMUM_CHARACTER_CATEGORY_ERROR','Please enter minimum 3 characters in category title.');
define("ERROR_CATEGORY_INVALID","This category title is already exist.");

/*Common messages for products 28_12_2012*/
define("PRODUCT_LISTING","Product Listing");
define("ADD_PRODUCT","Add Product");
define("EDIT_PRODUCT","Edit Product.");
define("REQUIRED_CATEGORY_TYPE_ERROR","Please select category.");
define("REQUIRED_PRODUCT_ERROR","Please enter product title.");
define("CHARACTER_PRODUCT_ERROR","Please enter only alphabets characters in product title.");
define('MINIMUM_CHARACTER_PRODUCT_ERROR','Please enter minimum 3 characters in category title.');
define("ERROR_PRODUCT_INVALID","This product title is already exist.");

/*Common message for active, dective and delete 29_12_2012*/
define("RECORD_ACTIVATED","Record(s) activated successfully.");
define("RECORD_DEACTIVATED","Record(s) deactivated successfully.");


/*31_12_2012*/
define("LBL_PRODUCT_LISTING","Product Listing");
define("LBL_CATEGOREY_LISTING","Category Listing");

/*vendor 31_12_2012 */
define("VENDOR_LISTING","Vendor Listing");
define("LBL_VENDOR_LISTING","Vendor Listing");


/*logout 1_1_2013*/
define("LOGOUT_SUCCESS","You loged out successfully.");
define("ADMIN_LOGIN","Login");
define("UNAME_RERUIRED","Please enter user name.");
define("PASSWORD_REQUIRED","Please enter password.");
define("STH_CUSTOM","Something custom.");

/*coupon 1_1_2013*/
define("COUPON_LISTING","Coupon Listing");
define("LBL_COUPON_LISTING","Coupon Listing");
define("EDIT_COUPON","Edit Coupon");
define("REQUIRED_PRODUCT_TYPE_ERROR","Please select product.");
define("REQUIRED_ACTIVATION_CODE_ERROR","Please enter activation code.");
/*** Vendor Controller start**/
define('ADD_VENDOR','Add Vendor');	
define('EDIT_VENDOR','Edit Vendor');	
define('ADD_COUPON','Add Coupon');
define('SEARCH',"Search");
define('UPLOAD_COUPON',"Upload Coupon");
define("NO_PRODUCT_FOUND","No product found");

/*3_1_2013*/
define("UPLOAD_COUPON_CSV","Upload Coupon CSV");
define("ERR_PRODUCT_CATEGORY_EMPTY","First select product from left menu.");
define("ERR_COUPON_CSV","Please select csv file only.");
define("NOT_UPLODED_CSV","Selected file is not uploaded successfully.");
define("WRONG_PRODUCT_CATEGORY","Something is wrong with your product/category.");

/* 5_1_2012*/
define("USER_LOGIN","User Login");
define("ERR_EMAIL_EMPTY","Please enter email.");
define("ERR_PASSWORD_EMPTY","Please enter password.");
define("INVALID_USERNAME_PASSWORD","Invalid username or password, try again.");
define("ADD_USER","Add User");
define("ADD_ROLE","Add Role");
define("ERR_ROLE_EMPTY","Please enter role.");
define("ERR_ROLE_INVALID","This role is already exist.");
define("ERR_ROLE_PERMISSION","Please select permission.");
define("EDIT_ROLE","Edit Role");
define("ROLE_LISTING","Role Listing");
/*07_01_2013*/
define("ERR_SELECT_ROLE_EMPTY","Please select role type.");
define("ERR_FIRST_NAME_EMPTY","Please enter first name.");
define("ERR_VALID_FIRST_NAME","Please enter valid first name.");
define("ERR_LAST_NAME_EMPTY","Please enter last name.");
define("ERR_VALID_LAST_NAME","Please enter valid last name.");
define("ERR_PHONE_NUMBER_EMPTY","Please enter phone number.");
define("ERR_USER_PERMISSION","Please select user permission.");
define("ERR_EMAIL_VALID","Please enter valid email.");
define("LBL_EMAIL_ALREADY_EXIST","This email address is already exist, please enter another email.");
define("ERR_CONFIRM_PASSWORD_EMPTY","Please enter confirm password.");
define("ERR_PASSWORD_NOT_MATCH","Password do not match.");
define("ERR_SELECT_SELECT_LIMIT_EMPTY","Please select selling limit.");
define("ERR_SELLING_PRICE_LIMIT_EMPTY","Please enter selling limit price.");
define("ERR_INVALID_SELLING_PRICE_LIMIT","Please enter valid selling pice limit.");
define("EDIT_USER","Edit User");
define("USER_LISTING","User Listing");
define("NO_PERMISSION","No permissions");
define("ENTER_SUB_DEALER","Please first delete sub dealer.");
define("FORGOT_PASSWORD","Forgot Password");
define("CHECK_MAIL","Please check your email and click on link.");
define("ERR_CORRECT_EMAIL","Please enter correct email.");
define("RESET_PASSWORD","Reset Password");
define("PASSWORD_CHANGE_SUCCESS","Password has been chanbged successfully.");
define("ERR_RESET_PASSWORD","There is a problem to reset password");
?>



