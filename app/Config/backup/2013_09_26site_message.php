<?php
/***Custom Messages start **/
define("LOGIN_ERROR","");	
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

/*Common messages for categories 27_12_2012*/
define("CATEGORY_LISTING","Voucher Listing");
define("ADD_CATEGORY","Add Voucher");
define("REQUIRED_CATEGORY_ERROR","Please enter voucher value.");
define("EDIT_CATEGORY","Edit Voucher");
define("NOT_FOUND_ERROR","This record is not found.");
define("CHARACTER_CATEGORY_ERROR","Please enter only alphabets characters in vouchar value.");
define('MINIMUM_CHARACTER_CATEGORY_ERROR','Please enter minimum 3 characters in voucher value.');
define("ERROR_CATEGORY_INVALID","This voucher value is already exist.");

/*Common messages for products 28_12_2012*/
define("PRODUCT_LISTING","Product Listing");
define("ADD_PRODUCT","Add Product");
define("EDIT_PRODUCT","Edit Product.");
define("REQUIRED_CATEGORY_TYPE_ERROR","Please select voucher.");
define("REQUIRED_PRODUCT_ERROR","Please enter product title.");
define("CHARACTER_PRODUCT_ERROR","Please enter only alphabets characters in product title.");
define('MINIMUM_CHARACTER_PRODUCT_ERROR','Please enter minimum 3 characters in category title.');
define("ERROR_PRODUCT_INVALID","This product title is already exist.");

/*Common message for active, dective and delete 29_12_2012*/
define("RECORD_ACTIVATED","Record(s) activated successfully.");
define("RECORD_DEACTIVATED","Record(s) deactivated successfully.");


/*31_12_2012*/
define("LBL_PRODUCT_LISTING","Product Listing");
define("LBL_CATEGOREY_LISTING","Voucher Listing");

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

/***coupon sell 07-01-2012 */
define("COUPON_SELL_LISTING","Coupon Sell Listing");
define("LBL_COUPON_SALE","Coupon Sale");
/*** Vendor Controller start**/
define('ADD_VENDOR','Add Vendor');	
define('EDIT_VENDOR','Edit Vendor');	
define('ADD_COUPON','Add Coupon');
define('SEARCH',"Search");
define('UPLOAD_COUPON',"Upload Coupon");
define("NO_PRODUCT_FOUND","No product code found!");
define('SELL_COUPON',"Sell Coupon");

/*3_1_2013*/
define("UPLOAD_COUPON_CSV","Upload Coupon CSV");
define("ERR_PRODUCT_CATEGORY_EMPTY","First select product code from left menu.");
define("ERR_COUPON_CSV","Please select csv file only.");
define("NOT_UPLODED_CSV","Selected file is not uploaded successfully.");
define("WRONG_PRODUCT_CATEGORY","Something is wrong with your product code/Voucher.");

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
define("ERR_FIRST_NAME_EMPTY","Please enter dealer name.");
define("ERR_VALID_FIRST_NAME","Please enter valid dealer name.");
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

/*10_01_2013*/
define("DASHBOARD","Dashboard");
define("ANAUTHORIZE","Anauthorize");

/*11_01_2013*/
define("REQUIRED_VENDOR_TYPE_ERROR","Please select vendor.");
define("REQUIRED_PRODUCT_ID_ERROR","Please select product code first");
define("ERROR_PRODUCT_ID_INVALID","This product code is already exist.");
define("NO_ACTION","No Action");
define("MY_PROFILE","My Profile");
define("CSV_RECORD_SAVE"," coupons(s) added successfully.");
define("LBL_VOUCHER_LISTING","Voucher Listing");
define("ERROR_VENDOR_INVALID","This vendor is already exist.");
define("ERROR_VOUCHER_INVALID","Please enter a valid voucher title.");
define("DEALER_REPORT","Dealer Report");
define("VENDOR_REPORT","Vendor Report");
define("VOUCHER_REPORT","Voucher Report");
define("DEALER_LISTING","Dealer Listing");
define("SUB_DEALER_REPORT","Sub Dealer Report");
define("DEALER_REPORTER","Dealer Report");
define("SUB_DEALER_LISTING","Sub-Dealer Listing.");
define("ERR_VENDOR_REQUIRE","Please enter vendor name.");
define("ERR_COUPON_ID_REQUIRE","Please enter coupon id.");
define("ERROR_COUPON_ID_INVALID","This coupon id is already exist, please enter another coupon id.");
define("ERR_SELECT_DEALER_EMPTY","Please select dealer first.");
define("AUTHORIZE_RESTRICTED","Authorize restricted");
define("REQUIRED_EXPIRE_DATE_ERROR","Please select coupon expire date.");
define("NO_VOUCHER_FOUND","No Voucher Found !");
define("REPORT_MANAGEMENT","Report Management");
define("REQUIRED_VOUCHER_ERROR","Plese enter voucher value.");
define("VALID_VOUCHER_ERROR","Please enter a valid voucher value.");
define("SETTING","Setting");
define("CMS_PAGE_LISTING","Cms Page Listing");
define("EDIT_CMS_PAGE","Edit Cms Page");
define("ERR_CMS_EMPTY","Please enter cms page title");
define("ERR_CMS_INVALID","Please enter a valid cms page title.");
define("ERR_CMS_CONTENT_EMPTY","Please enter contet.");
define("COUPON_REPORT","Coupon Report");
define("COUPON_SALE_REPORT","Coupon Sale Report");
define("HOME_PAGE","Home Page");
define("ERR_EMAIL_PASSWORD","Please enter username or password.");
define("ERR_VALID_ROLE_ID","The selected role is not exist here, plese select a valid role.");
define("ERR_VALID_DEALER_ID","This dealer is not exist, please select a valid dealer.");
define("BATCH_LISTING","Batch Listing");
define("REQUIRED_DESCRIPTION_ERROR","Please enter product description with required fields.");
define("ERR_VALID_PRODUCT_DESCRIPTION","Please enter required field {REPLACE} in product description.");
define("PRODUCT_DESCRIPTION_DEFAULT_VALUE","<p>Tak for dit køb af {VENDOR_NAME} {VOUCHER}.</p><p>Din Tank-op kode er:</p>  <p>{ACTIVATION_CODE}</p><p>1. Ring til 80 80 80 80</p> <p>2. Følg vejledningnen</p><p>Har du spørgsmål? Besøg</p>  <p>hallo.dk eller ring til vores</p> <p>support på  70 30 00 30</p><p>Refference #{COUPON_CODE}.</p> <p>-----------------------------</p>");

define("REQUIRED_SMS_DESCRIPTION_ERROR","Please enter product description with required fields.");
define("ERR_VALID_PRODUCT_SMS_DESCRIPTION","Please enter required field {REPLACE} in sms description.");


define("PRODUCT_SMS_DESCRIPTION_DEFAULT_VALUE","Tak for dit køb af {VENDOR_NAME} {VOUCHER}. {n} {n}  Din Tank-op kode er: {n} {ACTIVATION_CODE} {n} 1. Ring til 80 80 80 80{n} 2. Følg vejledningnen{n} {n} Har du spørgsmål? Besøg  hallo.dk eller ring til vores support på  70 30 00 30{n}  Refference #{COUPON_CODE}. {n}");


define("PDF_DELETED_SUCCESS","PDF deleted successfully.");
define("CSV_DELETED_SUCCESS","csv deleted successfully.");
define("CSV_PDF_DELETED_ERR","There is an error to delete the csv/pdf.");

define("REQUIRED_PRODUCTID_ERROR","Please enter product id.");
define("ERROR_PRODUCTID_INVALID","This product id is already exist, please enter another product id.");
define("WORD_COUNT","400");
define("MESSAGE_COUNT","160");
define("ERR_DEALERID_EMPTY","Please enter id.");
define("LBL_DEALERID_ALREADY_EXIST","This id is already exist, please enter another id.");

define("ERR_VALID_SMS_CHARACTER_LIMIT","Please enter sms description of limit {CHARACTER_LIMIT} characters only.") ;

define("ERR_SELECT_SELECT_LIMIT_DEALER","The Dealer's selling limit is <b>{SELLING_LIMIT}</b>. Then please select sub-dealer's selling limit same as dealer's selling limit.");
define("sub_dealer_selling_price_limit","Please enter sub dealer's selling price amount is <b>{SUB_DEALER_SELLING_PRICE}DKK</b> or less than <b>{SUB_DEALER_SELLING_PRICE}DKK</b>.");
define("ERR_IMAGE_REQUIRED","Please select vendor image.");
define("ERR_IMAGE_TYPE","Selected file type is not right. Please select right file type.");
define("ERR_CMS_IMAGE_REQUIRED","Please select image first.");
define("ERR_CMS_IMAGE_TYPE","Please select valid image type.");
define("ABOUT_US_PAGE","About us");
define("ADD_CMS_PAGE","Add Cms Page");
define("ERR_CMS_PAGE_ORDER_EMPTY","Please enter page order.");
define("ERR_CMS_PAGE_ORDER_INVALID","Please enter a valid page order.");
define("ERR_CMS_META_TITLE_EMPTY","Please enter meta title");
define("ERR_CMS_META_TITLE_INVALID","This meta title is already exist, please enter another meta title.");
define("ERR_CMS_META_DESCRIPTION_EMPTY","Please enter meta description.");
?>