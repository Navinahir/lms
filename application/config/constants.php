<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

if ($_SERVER['HTTP_HOST'] == 'dev.arklock.com') {
    $domain = '.dev.arklock.com';
} else {
    $domain = '.clientapp.narola.online';
}
define('MY_DOMAIN_NAME', $domain);
define('SYSTEM_CONTACT_NO', '1-888-558-5397');


define('SITE_NAME', 'Always Reliable Keys');

/* * ************************************
  DB TABLE NAMES
 * ************************************** */
define('TBL_ADMIN_SETTINGS', 'admin_settings');
define('TBL_API_TOKENS', 'api_tokens');
define('TBL_BLOGS', 'blogs');
define('TBL_BLOG_MEDIA_CONTENTS', 'blog_media_contents');
define('TBL_CUSTOMER_NOTES', 'customer_notes');
define('TBL_CARD_DETAILS', 'card_details');
define('TBL_COMPANY', 'company');
define('TBL_CONTROLLERS', 'controllers');
define('TBL_CURRENCY', 'currency');
define('TBL_CUSTOMERS', 'customers');
define('TBL_CUSTOMER_EMAIL', 'customer_email');
define('TBL_DEPARTMENTS', 'departments');
define('TBL_DATE_FORMAT', 'date_format');
define('TBL_ESTIMATES', 'estimates');
define('TBL_ESTIMATE_PARTS', 'estimate_parts');
define('TBL_ESTIMATE_SERVICES', 'estimate_services');
define('TBL_ESTIMATION_ATTACHMENTS', 'estimation_attachments');
define('TBL_EQUIPMENT_TYPES', 'equipment_types');
define('TBL_EQUIPMENT_NAMES', 'equipment_names');
define('TBL_INVENTORY_HISTORY', 'inventory_history');
define('TBL_ITEMS', 'items');
define('TBL_ITEM_INVENTORY_DETAILS', 'item_inevntory_details');
define('TBL_ITEM_LOCATION_DETAILS', 'item_location_details');
define('TBL_ITEM_LOCATION_TRANSFER_DETAILS', 'item_location_transfer_details');
define('TBL_LOCATIONS', 'locations');
define('TBL_MANUFACTURERES', 'manufacturers');
define('TBL_METHODS', 'methods');
define('TBL_MODEL', 'model');
define('TBL_MODULES_CONTENT', 'modules_content');
define('TBL_NOTIFICATION', 'notification');
define('TBL_ORDER', 'orders');
define('TBL_PACKAGES', 'packages');
define('TBL_PAYMENT', 'payment');
define('TBL_PAYMENT_METHODS', 'payment_methods');
define('TBL_QUICKBOOK_CUSTOMER', 'quickbook_customer');
define('TBL_QUICKBOOK_CUSTOMER_MAINTAIN', 'quickbook_customer_maintain');
define('TBL_QUICKBOOK_CONFIG', 'quickbook_config');
define('TBL_QUICKBOOK_ITEMS', 'quickbook_items');
define('TBL_QUICKBOOK_SERVICE', 'quickbook_service');
define('TBL_QUICKBOOK_INVOICE', 'quickbook_invoice');
define('TBL_QUICKBOOK_ESTIMATE', 'quickbook_estimate');
define('TBL_INVOICE_QUICKBOOK_ATTACHMENT', 'invoice_quickbook_attachment');
define('TBL_ESTIMATE_QUICKBOOK_ATTACHMENT', 'estimate_quickbook_attachment');
define('TBL_ROLES', 'roles');
define('TBL_RECENT_SEARCH_DETAILS', 'recent_search_details');
define('TBL_SERVICES', 'services');
define('TBL_STATES', 'states');
define('TBL_SUBSCRIPTIONS', 'subscriptions');
define('TBL_STATUS', 'status');
define('TBL_TAXES', 'taxes');
define('TBL_TERMS_AND_PRIVACY_POLICIES', 'terms_and_privacy_policies');
define('TBL_TRANSPONDER', 'transponder');
define('TBL_TRANSPONDER_ADDITIONAL', 'transponder_additional');
define('TBL_TRANSPONDER_ITEMS', 'transponder_items');
define('TBL_TRANSPONDER_USER_ITEMS', 'transponder_user_items');
define('TBL_USERS', 'users');
define('TBL_USER_PERMISSION', 'user_permission');
define('TBL_USER_ITEMS', 'user_items');
define('TBL_USER_SETTINGS', 'user_settings');
define('TBL_USER_SETTINGS_FIELD', 'user_settings_field');
define('TBL_USER_SUBSCRIPTIONS', 'user_subscriptions');
define('TBL_VEHICLE_COLORS', 'vehicle_colors');
define('TBL_VENDORS', 'vendors');
define('TBL_VENDOR_HISTROY', 'vendor_history');
define('TBL_YEAR', 'year');
define('TBL_INVOICE_INVENTORY', 'invoice_inventory');
define('TBL_SUBSCRIBER', 'subscriber');

/* * ************************************
  CSV File Dummy Path
 * ************************************** */
define('MAKE_DUMMY_CSV', 'uploads/csv/make/dummy_data.csv');
define('MAKE_CSV', 'uploads/csv/make');

define('MODEL_DUMMY_CSV', 'uploads/csv/model/dummy_data.csv');
define('MODEL_CSV', 'uploads/csv/model');

define('ITEMS_IMAGE_PATH', 'uploads/items');
define('ESTIMATE_IMAGE_PATH', 'uploads/attachments');
define('SIGNATURE_IMAGE_PATH', 'uploads/signatures/');
define('QRCODE_IMAGE_PATH', 'assets/qr_codes');
define('USER_QRCODE_IMAGE_PATH', 'assets/users_qr_codes');

define('MANUFACTURER_DUMMY_CSV', 'uploads/csv/manufacturer/dummy_data.csv');
define('MANUFACTURER_CSV', 'uploads/csv/manufacturer');

define('TYPE_DUMMY_CSV', 'uploads/csv/type/dummy_data.csv');
define('TYPE_CSV', 'uploads/csv/type');

define('ITEM_DUMMY_CSV', 'uploads/csv/item/dummy_data.csv');
define('ITEM_CSV', 'uploads/csv/item');
/* * ************************************
  Stripe credetials :
 * ************************************** */
define('STRIPE_PUBLISH_KEY', 'pk_test_dFnzC47LbMxt9Xt4hB5hxqid');
define('STRIPE_SECRET_KEY', 'sk_test_OmrHVq5wpH7EFE4bLDuSXPQA');
/* * ************************************
  Contact Us Email :
 * ************************************** */
define('CONTACT_EMAIL', 'alwaysreliablekeys@gmail.com');
/* * ************************************
  Stripe :
 * ************************************** */
define('STRIPE_PACKAGE_PRODUCT', 'Always Reliable Keys');
define('STRIPE_PACKAGE_PRODUCT_ID', 'prod_ELsR5enqw81CFF');
define('SYSTEM_CURRENCY', 'usd');
define('PLAN_DURATION', 'month');
/* * ************************************
  Vendor Portal :
 * ************************************** */
define('MAX_SUB_VENDOR_USERS', 10);

/* * ************************************
  Admin Portal :
 * ************************************** */
define('MAX_MEDIA_CONTENTS_FIELDS_LIMIT', 10);
