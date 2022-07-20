<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  | example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  | https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  | $route['admin/default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  | $route['admin/404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  | $route['admin/translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples: my-controller/index -> my_controller/index
  |   my-controller/my-method -> my_controller/my_method
 */
$route['default_controller'] = 'admin/dashboard';
$route['404_override'] = 'admin/login/show_404';
$route['translate_uri_dashes'] = FALSE;

$route['/'] = 'admin/dashboard';
$route['subscribe'] = 'home/subscribe';
$route['update_browser'] = 'home/update_browser';
$route['login'] = 'home/login';
$route['logout'] = 'home/logout';
$route['register'] = 'home/register';
$route['register/successful'] = 'home/register_success';
$route['forgot_password'] = 'home/forgot_password';
$route['create_password'] = 'home/create_password';
$route['create_new_password'] = 'home/create_new_password';
$route['reset_password'] = 'home/reset_password';
$route['uniq_email'] = 'home/checkUnique_Email';
$route['uniq_email/(:any)'] = 'home/checkUnique_Email/$1';
$route['company_profile'] = 'Company_Profile/index';
$route['company_profile/change_password'] = 'Company_Profile/change_password';
$route['quickbook_login'] = 'Company_Profile/processCode';
$route['change_password'] = 'home/change_password';
$route['dashboard'] = 'dashboard/index';
$route['about_us'] = 'home/about_us';
$route['contact_us/post'] = 'home/contact_us_email';
$route['packages'] = 'home/packages';
$route['features'] = 'home/features';
$route['features/dashboard_search'] = 'home/dashboard_search';
$route['features/users_and_roles'] = 'home/users_and_roles';
$route['features/inventory'] = 'home/inventory';
$route['features/estimates_and_invoices'] = 'home/estimates_and_invoices';
$route['features/reports'] = 'home/reports';
$route['features/programming_and_troubleshooting'] = 'home/programming_and_troubleshooting';
$route['privacy_policies/(:any)'] = 'home/terms_and_privacy/$1';
$route['terms_of_services/(:any)'] = 'home/terms_and_privacy/$1';
$route['webhooks'] = 'Webhook/index';
$route['generate_qr_code'] = 'Webhook/generate_user_items_qr_code';
$route['scan'] = 'Webhook/scan';

$route['js_disabled'] = "home/js_disabled";

$route['subscription/invoice/(:any)'] = 'Company_Profile/download_view_billing_invoice/$1';
$route['subscription/invoice/email/(:any)'] = 'Company_Profile/send_billing_invoice/$1';
$route['subscription/upcoming/invoice'] = 'Company_Profile/download_view_upcoming_invoice';
$route['subscription/cancel'] = 'Company_Profile/cancel_subscription';

$route['blogs'] = 'dashboard/get_blogs';
$route['blogs/(:any)'] = 'dashboard/get_blog_details/$1';

$route['admin/subscriber'] = 'admin/subscriber/display';

$route['users'] = 'users/display_users';
$route['users/add'] = 'users/edit_users';
$route['users/edit/(:any)'] = 'users/edit_users/$1';

$route['users/roles'] = 'roles/display_roles';
$route['users/roles/add'] = 'roles/edit_roles';
$route['users/roles/edit/(:any)'] = 'roles/edit_roles/$1';

$route['taxes'] = 'taxes/display_taxes';
$route['taxes/add'] = 'taxes/edit_taxes';
$route['taxes/edit/(:any)'] = 'taxes/edit_taxes/$1';

$route['locations'] = 'locations/display_locations';
$route['locations/add'] = 'locations/edit_locations';
$route['locations/edit/(:any)'] = 'locations/edit_locations/$1';
$route['locations/view/(:any)'] = 'locations/location_view/$1';

$route['services'] = 'services/display_services';
$route['services/add'] = 'services/edit_services';
$route['services/edit/(:any)'] = 'services/edit_services/$1';
$route['services/add_to_quickbook'] = 'services/add_edit_service_quickbook';
$route['services/add_to_quickbook/(:any)'] = 'services/add_edit_service_quickbook/$1';
$route['services/quickbook_status'] = 'services/service_quickbook_status';

$route['items'] = 'items/display_items';
$route['items/add'] = 'items/edit_items';
$route['items/edit/(:any)'] = 'items/edit_items/$1';
$route['items/delete/(:any)'] = 'items/delete_items/$1';
$route['items/print-label/(:any)'] = 'items/print_item_label/$1';
$route['items/add_to_quickbook'] = 'items/add_edit_item_quickbook';
$route['items/add_to_quickbook/(:any)'] = 'items/add_edit_item_quickbook/$1';
$route['items/quickbook_status'] = 'items/get_item_quickbook_status';

$route['estimates'] = 'estimates/display_estimates';
$route['estimates/add'] = 'estimates/edit_estimates';
$route['estimates/edit/(:any)'] = 'estimates/edit_estimates/$1';
$route['estimates/delete/(:any)'] = 'estimates/delete_estimates/$1';
$route['estimates/trash_recover/(:any)'] = 'estimates/trash_recover_estimates/$1';
$route['estimates/trash_delete/(:any)'] = 'estimates/trash_delete_estimates/$1';
$route['estimates/add_to_quickbook'] = 'estimates/add_edit_estimate_quickboook';
$route['estimates/add_to_quickbook/(:any)'] = 'estimates/add_edit_estimate_quickboook/$1';
$route['estimates/quickbook_status'] = 'estimates/estimate_quickbook_status';

$route['invoices'] = 'invoices/display_invoices';
$route['invoices/add'] = 'invoices/edit_invoices';
$route['invoices/copy_invoice'] = 'invoices/edit_invoices';

$route['invoices/edit/(:any)'] = 'invoices/edit_invoices/$1';
$route['invoices/edit/(:any)/(:any)'] = 'invoices/edit_invoices/$1/$2';
$route['invoices/delete/(:any)'] = 'invoices/delete_invoices/$1';
$route['invoices/trash_recover/(:any)'] = 'invoices/trash_recover_invoices/$1';
$route['invoices/trash_delete/(:any)'] = 'invoices/trash_delete_invoices/$1';
$route['invoices/add_to_quickbook'] = 'invoices/add_edit_invoice_quickbook';
$route['invoices/add_to_quickbook/(:any)'] = 'invoices/add_edit_invoice_quickbook/$1';
$route['invoices/get_customer_details'] = 'invoices/get_customer_details';

$route['invoices/quickbook_status'] = 'invoices/invoice_quickbook_status';

$route['orders'] = 'orders/display_orders';
$route['orders/add'] = 'orders/edit_orders';
$route['orders/edit/(:any)'] = 'orders/edit_orders/$1';
$route['orders/delete/(:any)'] = 'orders/delete_orders/$1';
$route['orders/change_order_status'] = 'orders/change_order_status';

$route['move_inventory'] = 'inventory/transfer_inventory';
$route['move_inventory/(:any)'] = 'inventory/transfer_inventory/$1';
$route['receive_inventory'] = 'inventory/add_inventory';
$route['inventory_history'] = 'inventory/inventory_history';
$route['adjust_inventory'] = 'inventory/adjust_inventory';
$route['adjust_inventory/(:any)'] = 'inventory/adjust_inventory/$1';
$route['receive_inventory/(:any)'] = 'inventory/add_inventory/$1';
$route['get_item_data'] = 'inventory/get_item_data';
$route['get_global_item_data'] = 'inventory/get_global_item_data';
$route['inventory_locations'] = 'inventory/inventory_locations';
$route['inventory_locations/view/(:any)'] = 'inventory/inventory_location_view/$1';

$route['remove_attchment/(:any)'] = 'estimates/remove_attchment/$1';

$route['customers'] = 'customers/display_customers';
$route['customers/add'] = 'customers/add_edit_customers';
$route['customers/edit/(:any)'] = 'customers/add_edit_customers/$1';
$route['customers/delete/(:any)'] = 'customers/delete_customers/$1';
$route['customers/view/(:any)'] = 'customers/view_customers/$1';
$route['customers/note/store'] = 'customers/create_update_notes';
$route['customers/note/update/(:any)'] = 'customers/create_update_notes/$1';
$route['customers/note/get'] = 'customers/get_note';
$route['customers/note/delete/(:any)/(:any)'] = 'customers/delete_note/$1/$2';
$route['customers/openinvoices'] = 'customers/openinvoices';
$route['customers/openinvoiceslist'] = 'customers/openinvoiceslist';
$route['customers/add_to_quickbook'] = 'customers/add_edit_customer_quickbook';
$route['customers/add_to_quickbook/(:any)'] = 'customers/add_edit_customer_quickbook/$1';
$route['customers/quickbook_status'] = 'customers/get_customer_quickbook_status';

$route['vendor'] = 'vendor/login'; // check it before user this routes.
$route['vendor/logout'] = 'vendor/login/logout';
$route['vendor/forgot_password'] = 'vendor/login/forgot_password';
$route['vendor/reset_password'] = 'vendor/login/reset_password';
$route['vendor/change_password'] = 'vendor/login/change_password';

//Vendor Products
$route['vendor/products/add'] = 'vendor/products/edit_items';
$route['vendor/products/edit/(:any)'] = 'vendor/products/edit_items/$1';
$route['vendor/products/delete/(:any)'] = 'vendor/products/delete_items/$1';
$route['vendor/products/export'] = 'vendor/products/export_products';

//Vendor USers
$route['vendor/users'] = 'vendor/vendors/index';
$route['vendor/users/add'] = 'vendor/vendors/add_edit_users';
$route['vendor/users/edit/(:any)'] = 'vendor/vendors/add_edit_users/$1';
$route['vendor/users/delete/(:any)'] = 'vendor/vendors/delete_users/$1';

//Roles
$route['vendor/roles'] = 'vendor/roles/display_roles';
$route['vendor/roles/add'] = 'vendor/roles/add_edit_roles';
$route['vendor/roles/edit/(:any)'] = 'vendor/roles/add_edit_roles/$1';
$route['vendor/roles/delete/(:any)'] = 'vendor/roles/delete_roles/$1';

//Reports
$route['vendor/reports/parts-compatability'] = 'vendor/reports/parts_compatability';

//Admin
$route['admin'] = 'admin/login';
$route['admin/forgot_password'] = 'admin/login/forgot_pwd';
$route['admin/reset_password'] = 'admin/login/reset_pwd';
$route['admin/profile'] = 'admin/login/user_profile';
$route['admin/update_password'] = 'admin/login/admin_password_update';
$route['admin/update_email'] = 'admin/login/admin_email_update';
$route['admin/username_email'] = 'admin/login/admin_username_update';
$route['admin/logout'] = 'admin/login/logout';
$route['admin/dashboard'] = 'admin/dashboard';
$route['dashboard/get_content_data'] = 'dashboard/get_content_data';

// Tranaponder
$route['admin/product/transponder'] = 'admin/product/display_transponder';
$route['admin/product/transponder/add'] = 'admin/product/add_transponder';
$route['admin/product/transponder/edit/(:any)'] = 'admin/product/edit_transponder/$1';
$route['admin/product/transponder/delete/(:any)'] = 'admin/product/delete_transponder/$1';
$route['admin/product/transponder/bulk_edit'] = 'admin/product/bulk_edit_transponder';

// Lead
$route['admin/lead/add'] = 'admin/lead/add_lead';
$route['admin/lead/edit/(:any)'] = 'admin/lead/edit_lead/$1';
$route['admin/lead/delete/(:any)'] = 'admin/lead/delete_lead/$1';

$route['admin/users/add'] = 'admin/users/add_users';
$route['admin/users/edit/(:any)'] = 'admin/users/edit_users/$1';
$route['admin/users/delete/(:any)'] = 'admin/users/delete_users/$1';

$route['admin/products/add'] = 'admin/products/add_products';
$route['admin/products/edit/(:any)'] = 'admin/products/edit_products/$1';
$route['admin/products/delete/(:any)'] = 'admin/products/delete_products/$1';

// Administrator
$route['admin/product/make'] = 'admin/product/manage_make';
$route['admin/product/make/delete/(:any)'] = 'admin/product/delete_make/$1';
$route['admin/product/model'] = 'admin/product/manage_model';
$route['admin/product/model/delete/(:any)'] = 'admin/product/delete_model/$1';
$route['admin/product/year'] = 'admin/product/manage_year';
$route['admin/product/year/delete/(:any)'] = 'admin/product/delete_year/$1';
$route['admin/product/checkUnique_Package_Name/(:any)'] = 'admin/product/checkUnique_Package_Name/$1';
$route['admin/product/checkUnique_Make_Name/(:any)'] = 'admin/product/checkUnique_Make_Name/$1';
$route['admin/product/checkUnique_Model_Name/(:any)'] = 'admin/product/checkUnique_Model_Name/$1';
$route['admin/product/checkUnique_Year_Name/(:any)'] = 'admin/product/checkUnique_Year_Name/$1';

$route['admin/package'] = 'admin/product/manage_package';
$route['admin/package/delete/(:any)'] = 'admin/product/delete_package/$1';
$route['admin/package/view/(:any)'] = 'admin/product/view_package/$1';

// Staff Members
$route['admin/staff'] = 'admin/staff/display_staff';
$route['admin/staff/add'] = 'admin/staff/add_staff';
$route['admin/staff/edit/(:any)'] = 'admin/staff/edit_staff/$1';
$route['admin/staff/delete/(:any)'] = 'admin/staff/delete_staff/$1';
$route['admin/staff/checkUnique_Email/(:any)'] = 'admin/staff/checkUnique_Email/$1';
$route['admin/staff/checkUnique_Username/(:any)'] = 'admin/staff/checkUnique_Username/$1';
$route['admin/staff/reset_password/(:any)'] = 'admin/staff/reset_password/$1';

// Users
$route['admin/users'] = 'admin/users/display_users';
$route['admin/users/request'] = 'admin/users/display_users_request';
$route['admin/users/cancel_subscription'] = 'admin/users/display_cancel_subscription';
$route['admin/users/view/(:any)'] = 'admin/users/view/$1';
$route['admin/users/get_payment_transaction_data/(:any)'] = 'admin/users/get_payment_transaction_data/$1';
$route['admin/users/get_account_under_users/(:any)'] = 'admin/users/get_account_under_users/$1';
$route['admin/users/delete/(:any)'] = 'admin/users/delete/$1';
$route['admin/users/add_to_quickbook/(:any)'] = 'admin/users/add_to_quickbook/$1';
$route['admin/users/account/review/(:any)'] = 'admin/users/review_account/$1';
$route['admin/users/send_otp_notification'] = 'admin/users/send_otp_notification';
$route['admin/users/otp_verifitcatio_and_delete_user'] = 'admin/users/otp_verifitcatio_and_delete_user';

// Inventory
$route['admin/inventory/items'] = 'admin/inventory/display_items';
$route['admin/inventory/items/add'] = 'admin/inventory/add_items';
$route['admin/inventory/items/edit/(:any)'] = 'admin/inventory/edit_items/$1';
$route['admin/inventory/items/delete/(:any)'] = 'admin/inventory/delete_items/$1';

$route['admin/inventory/departments'] = 'admin/inventory/display_departments';
$route['admin/inventory/departments/add'] = 'admin/inventory/add_departments';
$route['admin/inventory/departments/edit/(:any)'] = 'admin/inventory/edit_departments/$1';
$route['admin/inventory/departments/delete/(:any)'] = 'admin/inventory/delete_departments/$1';

$route['admin/inventory/vendors'] = 'admin/inventory/display_vendors';
$route['admin/inventory/vendors/add'] = 'admin/inventory/add_vendors';
$route['admin/inventory/vendors/edit/(:any)'] = 'admin/inventory/edit_vendors/$1';
$route['admin/inventory/vendors/delete/(:any)'] = 'admin/inventory/delete_vendors/$1';
$route['admin/inventory/vendors/reset_password/(:any)'] = 'admin/inventory/reset_password/$1';
$route['admin/inventory/vendors/review/(:any)'] = 'admin/inventory/review_vendor_account/$1';
$route['admin/inventory/vendors/api-token/(:any)'] = 'admin/inventory/generat_api_token/$1';

$route['admin/subscriptions'] = 'admin/subscriptions/display_subscriptions';
$route['admin/subscriptions/add'] = 'admin/subscriptions/manage_subscriptions';
$route['admin/subscriptions/edit/(:any)'] = 'admin/subscriptions/manage_subscriptions/$1';
$route['admin/subscriptions/view/(:any)'] = 'admin/subscriptions/view/$1';
$route['admin/subscriptions/checkUniqueName/(:any)'] = 'admin/subscriptions/checkUniqueName/$1';

$route['admin/equipments/manufacturers'] = 'admin/equipments/manage_manufacturer';
$route['admin/equipments/manufacturer/delete/(:any)'] = 'admin/equipments/delete_manufacturers/$1';
$route['admin/equipments/checkUnique_Manufacturer_Name/(:any)'] = 'admin/equipments/checkUnique_Manufacturer_Name/$1';

$route['admin/equipments/types'] = 'admin/equipments/manage_types';
$route['admin/equipments/type/delete/(:any)'] = 'admin/equipments/delete_types/$1';
$route['admin/equipments/checkUnique_Type_Name/(:any)'] = 'admin/equipments/checkUnique_Type_Name/$1';

$route['admin/equipments/names'] = 'admin/equipments/manage_names';
$route['admin/equipments/name/delete/(:any)'] = 'admin/equipments/delete_names/$1';
$route['admin/equipments/checkUnique_Name/(:any)'] = 'admin/equipments/checkUnique_Name/$1';

$route['admin/reports/transponder'] = 'admin/reports/get_transponder_report';

// Content
$route['admin/content'] = 'admin/content/display_content';
$route['admin/content/add'] = 'admin/content/add_content';
$route['admin/content/edit/(:any)'] = 'admin/content/add_content/$1';
$route['admin/content/delete/(:any)'] = 'admin/content/delete_content/$1';

// Content
$route['admin/terms/privacy'] = 'admin/TermsAndPrivacy/display';
$route['admin/terms/privacy/add'] = 'admin/TermsAndPrivacy/add_edit';
$route['admin/terms/privacy/edit/(:any)'] = 'admin/TermsAndPrivacy/add_edit/$1';
$route['admin/terms/privacy/delete/(:any)'] = 'admin/TermsAndPrivacy/delete/$1';

// Blogs
$route['admin/blogs'] = 'admin/blogs/display';
$route['admin/blogs/add'] = 'admin/blogs/add_edit';
$route['admin/blogs/edit/(:any)'] = 'admin/blogs/add_edit/$1';
$route['admin/blogs/delete/(:any)'] = 'admin/blogs/delete/$1';
$route['admin/blogs/view/(:any)'] = 'admin/blogs/view/$1';

$route['admin/reports'] = 'admin/reports/display';
$route['admin/reports/application_reports'] = 'admin/reports/application_reports_display';
$route['admin/reports/get'] = 'admin/reports/get_item_report';

//Embeded APIs
$route['vehicle/search/form/(:any)'] = 'api/VehicleSearch/index/$1';
$route['vehicle/search/change_make_get_ajax'] = 'api/VehicleSearch/change_make_get_ajax';
$route['vehicle/search/get_transponder_item_years'] = 'api/VehicleSearch/get_transponder_item_years';
$route['vehicle/search/get_transponder_details'] = 'api/VehicleSearch/get_transponder_details';
$route['vehicle/search/get_item_data_ajax_by_id'] = 'api/VehicleSearch/get_item_data_ajax_by_id';
$route['vehicle/search/get_item_image'] = 'api/VehicleSearch/get_item_image';
$route['vehicle/search/getPartDetails'] = 'api/VehicleSearch/getPartDetails';
$route['vehicle/search/get_vin_vehical_details'] = 'api/VehicleSearch/get_vin_vehical_details';
$route['vehicle/search/token_authentication/(:any)'] = 'api/VehicleSearch/token_authentication/$1';

$route['admin/(:any)/(:any)/(:any)'] = 'admin/$1/action/$2/$3';

//Quickbook
$route['quickbook'] = 'Dashboard_quickbook/index';
$route['quickbook/customer/add'] = 'Dashboard_quickbook/add_customer_from_quickbook';
$route['quickbook/config'] = 'Dashboard_quickbook/add_account_for_product_service';
$route['quickbook/estimate'] = 'Dashboard_quickbook/view_estimate';
$route['quickbook/get_estimate'] = 'Dashboard_quickbook/get_unsync_estimate';
// $route['quickbook/estimate/add'] = 'Dashboard_quickbook/all_add_to_quickbook';
// 
$route['quickbook/invoice'] = 'Dashboard_quickbook/view_invoice';
$route['quickbook/get_invoice'] = 'Dashboard_quickbook/get_unsync_invoice';

$route['quickbook/items'] = 'Dashboard_quickbook/view_item';
$route['quickbook/get_items'] = 'Dashboard_quickbook/get_items';
$route['quickbook/out_sync_get_items'] = 'Dashboard_quickbook/out_sync_get_items';
$route['quickbook/update_qty/(:any)/(:any)'] = 'Dashboard_quickbook/update_qty_as_quickbook/$1/$2';

$route['quickbook/item'] = 'Dashboard_quickbook/view_unsync_item';
$route['quickbook/get_item'] = 'Dashboard_quickbook/get_unsync_items';
$route['quickbook/sync_all_items_qty'] = 'Dashboard_quickbook/sync_all_items_qty';

$route['quickbook/service'] = 'Dashboard_quickbook/view_unsync_service';
$route['quickbook/get_service'] = 'Dashboard_quickbook/get_unsync_service';

$route['quickbook/customer'] = 'Dashboard_quickbook/view_unsync_customers';
$route['quickbook/get_customer'] = 'Dashboard_quickbook/get_unsync_customer';

$route['message/send'] = 'Notification/send';
$route['message/view'] = 'Notification/view_notification';
$route['message/count'] = 'Notification/update_count';


$route['quickbook/qb_customer'] = 'Dashboard_quickbook/qb_customer';
$route['quickbook/qb_customer_ajax'] = 'Dashboard_quickbook/qb_customer_ajax';
$route['customers/add_customer_to_ark'] = 'Dashboard_quickbook/add_customer_to_ark';
