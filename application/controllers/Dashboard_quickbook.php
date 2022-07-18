<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . '/Library/QuickBook/autoload.php';
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;


class Dashboard_quickbook extends MY_Controller {

    public function __construct() {
    global $data_service;
        parent::__construct();
        if (!isset($_SESSION['sessionAccessToken'])) 
        {
            redirect('Dashboard','refresh');
        }
        $data_service           = $this->data_service();
        
        $this->load->model(array('admin/customers_model', 'admin/dashboard_quickbook_model', 'admin/product_model', 'admin/inventory_model', 'admin/service_model'));
    }


    public function index()
    {
        global $data_service;
        $data['title']                      = 'Quickbook';
        //check user has already saved their customers or not
        $data['customer_quickbook_details'] = $this->customers_model->get_all_details(TBL_QUICKBOOK_CONFIG, ['user_id' => $this->session->userdata('u_user_id'),'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

        $data['customer_config']            = $this->customers_model->get_all_details(TBL_QUICKBOOK_CONFIG, ['user_id' => $this->session->userdata('u_user_id'),'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

        $data['realmId']            = $data_service['accessToken']-> getRealmID();
        $data['accounts']           = $data_service['dataService']->Query("select * from Account");
        $data['deposites']          = $data_service['dataService']->Query("select * from Account where AccountType IN ('Other Current Asset', 'Bank') ");
        $data['customers']          = $data_service['dataService']->Query("select * from Customer");
        // $data['accounts'] = $data_service['dataService']->Query("select * from Account MAXRESULTS 2");
        $this->template->load('default_front', 'front/quickbook/dashboard', $data);
    }


    /**
     * [add_customer_from_quickbook get from quickbook and add into ARK]
     * @author KBH 
     * @created 08-10-2019
     */
    public function add_customer_from_quickbook()
    {
        global $data_service;
        if(isset($data_service['accessTokenJson']))
        {
            $customers              = $data_service['dataService']->Query("SELECT * FROM Customer");
            if(!empty($customers))
            {
                foreach($customers as $customer) 
                {
                    $country_data['first_name']                 = (!empty($customer->GivenName)) ? $customer->GivenName : '';
                    $country_data['last_name']                  = (!empty($customer->FamilyName)) ? $customer->FamilyName : '';
                    $country_data['company']                    = (!empty($customer->CompanyName)) ? $customer->CompanyName : '';
                    $country_data['display_name_as']            = (!empty($customer->DisplayName)) ? $customer->DisplayName : '';
                    $country_data['phone']                      =  (!empty($customer->PrimaryPhone->FreeFormNumber)) ? $customer->PrimaryPhone->FreeFormNumber : '';
                    $country_data['mobile']                     =  (!empty($customer->Mobile->FreeFormNumber)) ? $customer->Mobile->FreeFormNumber : '';
                    $country_data['fax']                        =  (!empty($customer->Fax->FreeFormNumber)) ? $customer->Fax->FreeFormNumber : '';
                    $country_data['email']                      =  (!empty($customer->PrimaryEmailAddr->Address)) ? $customer->PrimaryEmailAddr->Address : '';
                    $country_data['billing_address']            =  (!empty($customer->BillAddr->Line1)) ? $customer->BillAddr->Line1 : '';
                    $country_data['billing_address_city']       = (!empty($customer->BillAddr->City)) ? $customer->BillAddr->City : '';
                    $country_data['billing_address_state']      = (!empty($customer->BillAddr->CountrySubDivisionCode)) ? $customer->BillAddr->CountrySubDivisionCode : '';
                    $country_data['billing_address_zip']        = (!empty($customer->BillAddr->PostalCode)) ? $customer->BillAddr->PostalCode : '';
                    $country_data['shipping_address']           =  (!empty($customer->ShipAddr->Line1)) ? $customer->ShipAddr->Line1 : '';
                    $country_data['shipping_address_city']      = (!empty($customer->BillAddr->City)) ? $customer->BillAddr->City : '';
                    $country_data['shipping_address_state']     = (!empty($customer->BillAddr->CountrySubDivisionCode)) ? $customer->BillAddr->CountrySubDivisionCode : '';

                    $country_data['added_by']                   = $this->customers_model->auth_customer_id();

                    $is_saved = $this->customers_model->insert_update('insert', TBL_CUSTOMERS, $country_data);

                    //Insert into TBL_QUICKBOOK_CUSTOMER
                    $quickbook_customer['realmId']              = $data_service['accessToken']->getRealmID();
                    $quickbook_customer['customer_id']          = $is_saved;
                    $quickbook_customer['quickbook_id']         = $customer->Id;

                    $is_quickbook_saved = $this->customers_model->insert_update('insert', TBL_QUICKBOOK_CUSTOMER, $quickbook_customer);
                }
            }

            //Update TBL_QUICKBOOK_CONFIG status when Quickbook data inserted into ARK
            $customer_data['user_id']       = $this->session->userdata('u_user_id');
            $customer_data['realmId']       = $data_service['accessToken']->getRealmID();

            $is_saved = $this->customers_model->insert_update('update', TBL_QUICKBOOK_CONFIG, array('is_sync_customer' => 1), $customer_data);
        }
    }


    /**
     * [quickbook_config add account for product and service ]
     * @return [type] [description]
     * @author KBH
     * @date(10-10-2019)
     */
    public function add_account_for_product_service()
    {
        $quickbook_config['income_account']             = $this->input->post('income_account');
        $quickbook_config['expense_account']            = $this->input->post('expense_account');
        $quickbook_config['inventory_asset_account']    = $this->input->post('inventory_asset_account');
        $quickbook_config['income_account_service']     = $this->input->post('income_account_service');
        $quickbook_config['deposite_to']                = $this->input->post('deposite_to');
        $quickbook_config['customer_id']                = $this->input->post('customer_id');
        $customer_config = $this->customers_model->get_all_details(TBL_QUICKBOOK_CONFIG, ['user_id' => $this->session->userdata('u_user_id'),'realmId' => $this->input->post('realmId') ])->row_array();
        if(empty($customer_config))
        {
            $quickbook_config['realmId']            = $this->input->post('realmId');
            $quickbook_config['user_id']            = $this->session->userdata('u_user_id'); 
            $is_saved = $this->customers_model->insert_update('insert', TBL_QUICKBOOK_CONFIG, $quickbook_config);
            $final['operation'] = "add";
        }
        else
        {
            $is_saved = $this->customers_model->insert_update('update', TBL_QUICKBOOK_CONFIG, $quickbook_config, array('user_id' => $this->session->userdata('u_user_id'), 'realmId' => $this->input->post('realmId')));
            $final['operation'] = "update";
        }
        if($is_saved)
        {
            $final['status'] = true;
            echo json_encode($final);

        }
        else 
        {
            $final['status'] = false;
            echo json_encode($final); 
        }
    }


    /**
     * [get_sync_estimate_id get sync estimate id]
     * @return [string] [multiple estimate id return]
     * @author   KBH (08-11-2019)
     */
    public function get_sync_estimate_id()
    {
        global $data_service;
        $realm_id               = $data_service['accessToken']-> getRealmID();

        $this->db->select('e.*,' . TBL_ESTIMATES . ".id");
        $this->db->from(TBL_QUICKBOOK_ESTIMATE . " as e");
        $this->db->join(TBL_ESTIMATES, "e.estimate_id = ". TBL_ESTIMATES . ".id","INNER");
        $this->db->join(TBL_USERS, TBL_USERS . ".id = ". TBL_ESTIMATES . ".business_user_id","INNER");
        $this->db->where('e.realmId', $realm_id);
        $this->db->where('e.is_deleted', 0);
        $this->db->where(TBL_USERS . '.id', checkUserLogin('C'));
        $this->db->group_by('estimate_id');

        $sync_data              = $this->db->get()->result_array();
        $array_sync_ids         = array_column($sync_data, 'estimate_id');
        $sync_ids               = implode(",", $array_sync_ids);
        return $sync_ids;
    }


    /**
     * [get_unsync_estimate_id get only un-sync id]
     * @return [string] [estimate id]
     * @author KBH 08-11-2019
     */
    public function get_unsync_estimate_id()
    {
        $sync_ids       = $this->get_sync_estimate_id();
        $sync_ids       = explode(",", $sync_ids);

        $this->db->select('e.id');
        $this->db->where(array(
            'e.is_deleted'          => 0,
            'e.business_user_id'    => checkUserLogin('C'),
            'e.is_invoiced'         => 0,
        ));
        $this->db->where_not_in('e.id',$sync_ids);
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');

        $query                  = $this->db->get(TBL_ESTIMATES . ' as e');
        $unsync_data            =  $query->result_array();
        $array_unsync_ids       = array_column($unsync_data, 'id');
        $unsync_ids             = implode(",", $array_unsync_ids);
        return $unsync_ids;
    }


    /**
     * [get_unsync_estimate Get all un-sync record from Quickbook]
     * @return [type] [description]
     * @author KBH <08-11-2019>
     */
    public function get_unsync_estimate()
    {
        $sync_ids                   = $this->get_sync_estimate_id();
        $sync_ids                   = explode(",", $sync_ids);
        $format                     = MY_Controller::$date_format;
        $final['recordsTotal']      = $this->dashboard_quickbook_model->get_unsync_estimates_data('count', $sync_ids);
        $final['redraw']            = 1;
        $final['recordsFiltered']   = $final['recordsTotal'];
        $items                      = $this->dashboard_quickbook_model->get_unsync_estimates_data('result', $sync_ids);
        $start                      = $this->input->get('start') + 1;

        foreach ($items as $key => $val) {
            $items[$key]                    = $val;
            $items[$key]['sr_no']           = $start++;
            $items[$key]['estimate_date']   = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive']      = '';
            $items[$key]['quickbooks']      = $this->estimate_quickbook_status($val['id']);
        }
        $final['data']              = $items;
        echo json_encode($final);
        die;
    }


    /**
     * [view_estimate display all un-sync estimat view]
     * @return [type] [description]
     * @author  KBH 07-11-2019
     */
    public function view_estimate()
    {
        $data['title']      = 'List of Un - Sync Estimates';
        $data['unsync_id']  = $this->get_unsync_estimate_id();
        $this->template->load('default_front', 'front/quickbook/estimates_display', $data);
    }


    /**
     * [get_sync_invoice_id get sync invoice id]
     *@return [string] [multiple invoice id return]
     *@author   KBH (12-11-2019)
     */
    public function get_sync_invoice_id()
    {
        global $data_service;
        $realm_id            = $data_service['accessToken']-> getRealmID();

        $this->db->select('i.*,' . TBL_ESTIMATES . ".id");
        $this->db->from(TBL_QUICKBOOK_INVOICE . " as i");
        $this->db->join(TBL_ESTIMATES, "i.invoice_id = ". TBL_ESTIMATES . ".id", "INNER");
        $this->db->join(TBL_USERS, TBL_USERS . ".id = ". TBL_ESTIMATES . ".business_user_id", "INNER");
        $this->db->where('i.realmId', $realm_id);
        $this->db->where('i.is_deleted', 0);
        $this->db->where(TBL_USERS . '.id', checkUserLogin('C'));
        $this->db->group_by('i.invoice_id');

        $sync_data          = $this->db->get()->result_array();
        $array_sync_ids     = array_column($sync_data, 'invoice_id');
        $sync_ids           = implode(",", $array_sync_ids);
        return $sync_ids;
    }


    /**
     * [get_unsync_invoice_id get un-sync invoice id]
     * @return [string] [invoice id]
     */
    public function get_unsync_invoice_id()
    {
        $sync_ids           = $this->get_sync_invoice_id();
        $sync_ids           = explode(",", $sync_ids);
        $this->db->select('e.id');
        $this->db->where(array(
            'e.is_deleted'          => 0,
            'e.business_user_id'    => checkUserLogin('C'),
            'e.is_invoiced'         => 1,
        ));
        $this->db->where_not_in('e.id',$sync_ids);
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');

        $query               = $this->db->get(TBL_ESTIMATES . ' as e');
        $unsync_data         =  $query->result_array();
        $array_unsync_ids    = array_column($unsync_data, 'id');
        $unsync_ids          = implode(",", $array_unsync_ids);
        return $unsync_ids;
    }


    /**
     * [get_unsync_invoice Get all un-sync record of invoice and listing]
     * @return [object] [description]
     * @author KBH <12-11-2019>
     */
    public function get_unsync_invoice()
    {
        $sync_ids                   = $this->get_sync_invoice_id();
        $sync_ids                   = explode(",", $sync_ids);
        $format                     = MY_Controller::$date_format;
        $final['recordsTotal']      = $this->dashboard_quickbook_model->get_unsync_invoice_data('count', $sync_ids);

        $final['redraw'] = 1;
        $final['recordsFiltered']   = $final['recordsTotal'];
        $items                      = $this->dashboard_quickbook_model->get_unsync_invoice_data('result', $sync_ids);
        $start                      = $this->input->get('start') + 1;

        foreach ($items as $key => $val) {
            $items[$key]                    = $val;
            $items[$key]['sr_no']           = $start++;
            $items[$key]['estimate_date']   = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive']      = '';
            $items[$key]['quickbooks']      = $this->invoice_quickbook_status($val['id']);
        }
        $final['data']                      = $items;
        echo json_encode($final);
        die;
    }


    /**
     * [view_invoice display all un-sync Invoice view]
     * @return [type] [description]
     * @author  KBH 12-11-2019
     */
    public function view_invoice()
    {
        $data['title']              = 'List of Un - Sync Invoice';
        $data['unsync_id']          = $this->get_unsync_invoice_id();
        $this->template->load('default_front', 'front/quickbook/invoice_display', $data);
    }


    /**
     * [view_item display all item view]
     * @return [type] [description]
     * @author  KBH 18-02-2020
     */
    public function view_item()
    {
        $data['title']              = 'List of Items';
        $this->template->load('default_front', 'front/quickbook/item_display', $data);
    }


    /**
     * [get_items Get all the items from Quickbooks and preview ]
     * @return [json] [description]
     * @author KBH 18-02-2020
     */
    public function get_items()
    {
        $search = $this->input->get('search');

        $data['title']                      = 'List of Items';
        $format                             = MY_Controller::$date_format;
        global $data_service;
        $item_data                          = array();
        if(isset($data_service['accessTokenJson']))
        {
            $item_count                     =  $data_service['dataService']->Query("SELECT COUNT(*) FROM Item where type='Inventory'");
            $final['recordsTotal']          = $item_count;
            $final['redraw']                = 1;
            $final['recordsFiltered']       = $final['recordsTotal'];
            // $items = $data_service['dataService']->Query("SELECT * FROM Item maxresults 2");
            if($search['value'] != '' && $search['value'] != null)
            {
                $items                          = $data_service['dataService']->Query("SELECT * FROM Item where type='Inventory' and  
                            Name LIKE '%" . $search['value']. "%' ");
            }
            else
            {

                $items                          = $data_service['dataService']->Query("SELECT * FROM Item where type='Inventory' ");
            }
            $realm_id                       = $data_service['accessToken']-> getRealmID();
            if(!empty($items))
            {
                foreach($items as $key=>$item) 
                {
                    $ark                    = $this->dashboard_quickbook_model->get_quickbook_items($item->Id, $realm_id);
                    $item_data[$key]        = array(

                        'id'                => (!empty($item->Id)) ? $item->Id : '',
                        'part_no'           => (!empty($item->Name)) ? $item->Name : '',
                        'description'       => (!empty($item->Description)) ? $item->Description : '',
                        'retail_price'      => (!empty($item->UnitPrice)) ? $item->UnitPrice : '',
                        'sku'               => (!empty($item->Sku)) ? $item->Sku : '',
                        'qtyOnHand'         => (!empty($item->QtyOnHand)) ? $item->QtyOnHand : '',
                        'last_update_date'  => (!empty($item->MetaData->LastUpdatedTime)) ? $item->MetaData->LastUpdatedTime : '',
                        'ark_id'            => $ark['data']['ark_id'],
                        'qty_on_hand'       => $ark['data']['total_quantity'],

                    );
                }
            }
        }
        $items = $item_data;
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key]            = $val;
            $items[$key]['sr_no']   = $start++;
            // $items[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
        }
        $final['data']              = $items;
        echo json_encode($final);
        die;
    }


    public function update_qty_as_quickbook($qb_qty = null, $ark_item_id)
    {
        global $data_service;
        $qb_qty                     = base64_decode($qb_qty);
        $ark_item_id                = base64_decode($ark_item_id);
        $is_deleted                 = $this->product_model->insert_update('update', TBL_ITEM_LOCATION_DETAILS, array('is_deleted' => 1), array('item_id' => $ark_item_id));

        $locArr = $this->inventory_model->get_all_details(TBL_LOCATIONS, array('business_user_id' => checkUserLogin('C'), 'is_default' => 1, 'is_deleted' => 0, 'is_active' => 1))->row_array();
        if (!is_null($locArr)):
            $locationArr = [
                'business_user_id'  => checkUserLogin('C'),
                'item_id'           => $ark_item_id,
                'location_id'       => $locArr['id'],
                'quantity'          => $qb_qty,
                'last_modified_by'  => checkUserLogin('I'),
                'created_date'      => date('Y-m-d H:i:s')
            ];
            $this->inventory_model->insert_update('insert', TBL_ITEM_LOCATION_DETAILS, $locationArr);
        endif;
        if (!$this->input->is_ajax_request()) 
        {
            $this->session->set_flashdata('success', 'Item\'s data has been updated successfully.');
            redirect('quickbook/items');
        }
    }   


    /**
     * [get_sync_item_id get sync item id]
     *@return [string] [multiple iten id return]
     *@author   KBH (02-03-2020)
     */
    public function get_sync_item_id()
    {
        global $data_service;
        $realm_id                   = $data_service['accessToken']-> getRealmID();

        $this->db->select('i.item_id');
        $this->db->from(TBL_QUICKBOOK_ITEMS . " as i");
        $this->db->join(TBL_USER_ITEMS, "i.item_id = ". TBL_USER_ITEMS . ".id", "LEFT");
        $this->db->join(TBL_USERS, TBL_USERS . ".id = ". TBL_USER_ITEMS . ".business_user_id", "LEFT");
        $this->db->where('i.realmId', $realm_id);
        $this->db->where('i.is_deleted', 0);
        $this->db->where(TBL_USERS . '.id', checkUserLogin('C'));
        $this->db->group_by('i.item_id');

        $sync_data                  = $this->db->get()->result_array();
        $array_sync_ids             = array_column($sync_data, 'item_id');
        $sync_ids                   = implode(",", $array_sync_ids);
        return $sync_ids;
    }


    /**
     * [get_unsync_item_id get un-sync item id]
     * @return [string] [item id]
     * @author KBH <02-03-2020>
     */
    public function get_unsync_item_id()
    {
        $sync_ids               = $this->get_sync_item_id();
        $sync_ids               = explode(",", $sync_ids);
        $this->db->select('i.id');
        $this->db->where(array(
            'i.is_delete'        => 0,
            'i.business_user_id' => checkUserLogin('C'),
        ));
        $this->db->where_not_in('i.id',$sync_ids);
        $query                  = $this->db->get(TBL_USER_ITEMS . ' as i');
        $unsync_data            =  $query->result_array();
        $array_unsync_ids       = array_column($unsync_data, 'id');
        $unsync_ids             = implode(",", $array_unsync_ids);
        return $unsync_ids;
    }



    /**
     * [view_unsync_item display all un-sync items view]
     * @return [type] [description]
     * @author  KBH <02-03-2020>
     */
    public function view_unsync_item()
    {
        $data['title']           = 'List of Un - Sync Item';
        $data['unsync_id']       = $this->get_unsync_item_id();
        $this->template->load('default_front', 'front/quickbook/unsync_item_display', $data);
    }


    /**
     * [get_unsync_items Get all un-sync record of items and listing]
     * @return [object] [description]
     * @author KBH <02-03-2020>
     */
    public function get_unsync_items()
    {
        $sync_ids                       = $this->get_sync_item_id();
        $sync_ids                       = explode(",", $sync_ids);
        $format                         = MY_Controller::$date_format;
        $final['recordsTotal']          = $this->dashboard_quickbook_model->get_user_items_data('count', $sync_ids);
        $final['redraw']                = 1;
        $final['recordsFiltered']       = $final['recordsTotal'];
        $items                          = $this->dashboard_quickbook_model->get_user_items_data('result', $sync_ids);

        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key]                = $val;
            $items[$key]['sr_no']       = $start++;
            $items[$key]['item_date']   = date($format['format'], strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive']  = '';
            $items[$key]['quickbooks']  = $this->item_quickbook_status($val['id']);
        }
        $final['data']                  = $items;
        echo json_encode($final);
        die;
    }


    /**
     * [get_sync_service_id get sync service id]
     * @return [string] [multiple service id return]
     * @author   KBH (03-03-2020)
     */
    public function get_sync_service_id()
    {
        global $data_service;
        $realm_id               = $data_service['accessToken']-> getRealmID();
        $this->db->select('s.service_id');
        $this->db->from(TBL_QUICKBOOK_SERVICE . " as s");
        $this->db->join(TBL_SERVICES, "s.service_id = ". TBL_SERVICES . ".id", "LEFT");
        $this->db->join(TBL_USERS, TBL_USERS . ".id = ". TBL_SERVICES . ".business_user_id", "LEFT");
        $this->db->where('s.realmId', $realm_id);
        $this->db->where('s.is_deleted', 0);
        $this->db->where(TBL_USERS . '.id', checkUserLogin('C'));
        $this->db->group_by('s.service_id');
        $sync_data = $this->db->get()->result_array();
        $array_sync_ids         = array_column($sync_data, 'service_id');
        $sync_ids               = implode(",", $array_sync_ids);
        return $sync_ids;
    }


    /**
     * [get_unsync_service_id get un-sync service id]
     * @return [string] [service id]
     * @author KBH <03-03-2020>
     */
    public function get_unsync_service_id()
    {
        $sync_ids               = $this->get_sync_service_id();
        $sync_ids               = explode(",", $sync_ids);
        $this->db->select('s.id');
        $this->db->where(array(
            's.is_deleted'       => 0,
            's.business_user_id' => checkUserLogin('C'),
        ));
        $this->db->where_not_in('s.id',$sync_ids);
        $query                  = $this->db->get(TBL_SERVICES . ' as s');
        $unsync_data            =  $query->result_array();
        $array_unsync_ids       = array_column($unsync_data, 'id');
        $unsync_ids             = implode(",", $array_unsync_ids);
        return $unsync_ids;
    }


    /**
     * [view_unsync_service display all un-sync service view]
     * @return [type] [description]
     * @author  KBH <03-03-2020>
     */
    public function view_unsync_service()
    {
        $data['title']      = 'List of Un - Sync Service';
        $data['unsync_id']  = $this->get_unsync_service_id();
        $this->template->load('default_front', 'front/quickbook/unsync_service_display', $data);
    }


    /**
     * [get_unsync_service Get all un-sync record of service and listing]
     * @return [object] [description]
     * @author KBH <03-03-2020>
     */
    public function get_unsync_service()
    {
        $sync_ids   = $this->get_sync_service_id();
        $sync_ids   = explode(",", $sync_ids);

        $format     = MY_Controller::$date_format;

        $final['recordsTotal']      = $this->service_model->get_ajax_data('count', NULL, $sync_ids);
        $final['redraw']            = 1;
        $final['recordsFiltered']   = $final['recordsTotal'];
        $roles                      = $this->service_model->get_ajax_data('result', NULL, $sync_ids);
        $start                      = $this->input->get('start') + 1;
        foreach ($roles as $key => $val) {
            $roles[$key]                    = $val;
            $roles[$key]['sr_no']           = $start++;
            $roles[$key]['modified_date']   = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $roles[$key]['quickbooks']      = $this->service_quickbook_status($val['id']);
        }
        $final['data']                      = $roles;
        echo json_encode($final);
    }



    /**
     * [get_sync_customer_id get sync customer id]
     *@return [string] [multiple customer id return]
     *@author   KBH (06-03-2020)
     */
    public function get_sync_customer_id()
    {
        global $data_service;
        $realm_id               = $data_service['accessToken']-> getRealmID();

        $this->db->select('qc.customer_id');
        $this->db->from(TBL_CUSTOMERS . " as c");
        $this->db->join(TBL_QUICKBOOK_CUSTOMER . " as qc", "qc.customer_id = c.id", "LEFT");
        $this->db->join(TBL_USERS, TBL_USERS . ".id = c.added_by", "LEFT");
        $this->db->where('qc.realmId', $realm_id);
        $this->db->where('qc.is_deleted', 0);
        $this->db->where(TBL_USERS . '.id', checkUserLogin('C'));
        $this->db->group_by('qc.customer_id');
        $sync_data = $this->db->get()->result_array();
        $array_sync_ids         = array_column($sync_data, 'customer_id');
        $sync_ids               = implode(",", $array_sync_ids);
        return $sync_ids;
    }


    /**
     * [get_unsync_customer_id get un-sync customer id]
     * @return [string] [service id]
     * @author KBH <06-03-2020>
     */
    public function get_unsync_customer_id()
    {
        $sync_ids               = $this->get_sync_customer_id();
        $sync_ids               = explode(",", $sync_ids);
        $this->db->select('c.id');
        $this->db->where(array(
            'c.is_deleted'          => 0,
            'c.added_by'            => checkUserLogin('C'),
        ));
        $this->db->where_not_in('c.id',$sync_ids);
        $query                  = $this->db->get(TBL_CUSTOMERS . ' as c');
        $unsync_data            =  $query->result_array();
        $array_unsync_ids       = array_column($unsync_data, 'id');
        $unsync_ids             = implode(",", $array_unsync_ids);
        return $unsync_ids;
    }


    /**
     * [view_unsync_customer display all un-sync customer view]
     * @return [loading the unsync_service_display page]
     * @author  KBH <06-03-2020>
     */
    public function view_unsync_customers()
    {
        $data['title']          = 'List of Un - Sync customers';
        $data['unsync_id']      = $this->get_unsync_customer_id();
        $this->template->load('default_front', 'front/quickbook/unsync_customer_display', $data);
    }


    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_unsync_customer() {
        $sync_ids                   = $this->get_sync_customer_id();
        $sync_ids                   = explode(",", $sync_ids);
        $format                     = MY_Controller::$date_format;
        $final['recordsTotal']      = $this->customers_model->get_customers_data('count', NULL, $sync_ids);
        $final['redraw']            = 1;
        $final['recordsFiltered']   = $final['recordsTotal'];
        $where                      = [];

        if (!empty($this->input->get('status_id'))) {
            $where                  = array(
                'o.status_id'       => $this->input->get('status_id')
            );
        }

        $customers                 = $this->customers_model->get_customers_data('result', $where, $sync_ids);

        $start                      = $this->input->get('start') + 1;

        foreach ($customers as $key => $val) {
            $customers[$key]                    = $val;
            $customers[$key]['sr_no']           = $start++;
            $customers[$key]['created_date']    = date($format['format'], strtotime($val['created_date']) + $_COOKIE['currentOffset']);

            $customers[$key]['responsive']      = '';
            $customers[$key]['quickbooks']      = $this->get_customer_quickbook_status($val['id']);
            
        }
        $final['data']                          = $customers;
        echo json_encode($final);
        die;
    }


    /**
     * [out_sync_get_items get only diffrent Quantity records]
     * @return [type] [description]
     * @author KBH <30-03-2020>
     */
    public function out_sync_get_items()
    {
        $search = $this->input->get('search');
        
        $data['title']                      = 'List of Items';
        $format                             = MY_Controller::$date_format;
        global $data_service;
        $item_data                          = array();
        if(isset($data_service['accessTokenJson']))
        {
            $item_count                     =  $data_service['dataService']->Query("SELECT COUNT(*) FROM Item where type='Inventory'");
            $final['recordsTotal']          = $item_count;
            $final['redraw']                = 1;
            $final['recordsFiltered']       = $final['recordsTotal'];
            // $items = $data_service['dataService']->Query("SELECT * FROM Item maxresults 2");
            if($search['value'] != '' && $search['value'] != null)
            {
                $items                          = $data_service['dataService']->Query("SELECT * FROM Item where type='Inventory' and  
                            Name LIKE '%" . $search['value']. "%' ");
            }
            else
            {

                $items                          = $data_service['dataService']->Query("SELECT * FROM Item where type='Inventory' ");
            }
            
            $realm_id                       = $data_service['accessToken']-> getRealmID();
            if(!empty($items))
            {
                $sr_no_key = 0;
                foreach($items as $key=>$item) 
                {
                    $ark                    = $this->dashboard_quickbook_model->get_quickbook_items($item->Id, $realm_id);
                    if($ark['success'] == "true")
                    {
                        if($item->QtyOnHand != $ark['data']['total_quantity'])
                        {
                            $item_data[$sr_no_key]        = array(

                                'id'                => (!empty($item->Id)) ? $item->Id : '',
                                'part_no'           => (!empty($item->Name)) ? $item->Name : '',
                                'description'       => (!empty($item->Description)) ? $item->Description : '',
                                'retail_price'      => (!empty($item->UnitPrice)) ? $item->UnitPrice : '',
                                'sku'               => (!empty($item->Sku)) ? $item->Sku : '',
                                'qtyOnHand'         => (!empty($item->QtyOnHand)) ? $item->QtyOnHand : '',
                                'last_update_date'  => (!empty($item->MetaData->LastUpdatedTime)) ? $item->MetaData->LastUpdatedTime : '',
                                'ark_id'            => $ark['data']['ark_id'],
                                'qty_on_hand'       => $ark['data']['total_quantity'],
                            );
                            $sr_no_key++;
                        }
                    }
                }
            }
        }
        $items = $item_data;
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key]            = $val;
            $items[$key]['sr_no']   = $start++;
            $items[$key]['responsive'] = '';
        }
        $final['data']              = $items;
        echo json_encode($final);
        die;
    }


    /**
     * [sync_all_items_qty sync all items qty at one click (ajax)]
     * @return [type] [description]
     * @author KBH <02-04-2020>
     */
    public function sync_all_items_qty()
    {
        global $data_service;
        if(isset($data_service['accessTokenJson']))
        {
            $items                          = $data_service['dataService']->Query("SELECT * FROM Item where type='Inventory' ");
            $realm_id                       = $data_service['accessToken']-> getRealmID();
            if(!empty($items))
            {
                foreach($items as $key=>$item) 
                {
                    $ark                    = $this->dashboard_quickbook_model->get_quickbook_items($item->Id, $realm_id);
                    if($ark['success'] == "true")
                    {
                        $count = 0;
                        if($item->QtyOnHand != $ark['data']['total_quantity'])
                        {
                            $qb_qty = base64_encode($item->QtyOnHand);
                            $ark_id = base64_encode($ark['data']['ark_id']);
                            $this->update_qty_as_quickbook($qb_qty, $ark_id);
                            $count++;
                        }
                    }
                }
                if($count == 0)
                {
                    $this->session->set_flashdata('success', 'No records found');
                }
                else
                {
                    $this->session->set_flashdata('success', 'Item\'s data has been updated successfully.');
                }
                $data['success'] = "success";
                echo json_encode($data);
                exit();
            }
            else
            {
                if ($this->input->is_ajax_request()) 
                {
                    $data['error'] = "error";
                    echo json_encode($data);
                    exit();
                }
            }
        }
        else
        {
            $this->session->set_flashdata('error', "Quickbook session exprired");

            $data['session'] = "session";
            echo json_encode($data);
            exit();
        }
    }

    public function qb_customer()
    {
        $data['title'] = 'QB Customer';
        $this->template->load('default_front', 'front/quickbook/qb_customer', $data);
    }

    /**
     * [qb_customer get all the customer fom qb and listing]
     * @return [json] [description]
     * @author KBH <03-12-2020>
     */
    public function qb_customer_ajax()
    {
        global $data_service;
        if(isset($data_service['accessTokenJson']))
        {
            $customer_count                  = $data_service['dataService']->Query("SELECT count(*) FROM Customer");
            $customers                      = $data_service['dataService']->Query("SELECT * FROM Customer");
            if(!empty($customers))
            {
                foreach($customers as $key=>$customer) 
                {
                    $customer_data[$key]['qb_id']                 = (!empty($customer->Id)) ? $customer->Id : '';
                    $customer_data[$key]['first_name']                 = (!empty($customer->GivenName)) ? $customer->GivenName : '';
                    $customer_data[$key]['last_name']                  = (!empty($customer->FamilyName)) ? $customer->FamilyName : '';
                    $customer_data[$key]['company']                    = (!empty($customer->CompanyName)) ? $customer->CompanyName : '';
                    $customer_data[$key]['display_name_as']            = (!empty($customer->DisplayName)) ? $customer->DisplayName : '';
                    $customer_data[$key]['phone']                      =  (!empty($customer->PrimaryPhone->FreeFormNumber)) ? $customer->PrimaryPhone->FreeFormNumber : '';
                    $customer_data[$key]['mobile']                     =  (!empty($customer->Mobile->FreeFormNumber)) ? $customer->Mobile->FreeFormNumber : '';
                    $customer_data[$key]['fax']                        =  (!empty($customer->Fax->FreeFormNumber)) ? $customer->Fax->FreeFormNumber : '';
                    $customer_data[$key]['email']                      =  (!empty($customer->PrimaryEmailAddr->Address)) ? $customer->PrimaryEmailAddr->Address : '';
                    $customer_data[$key]['billing_address']            =  (!empty($customer->BillAddr->Line1)) ? $customer->BillAddr->Line1 : '';
                    $customer_data[$key]['billing_address_city']       = (!empty($customer->BillAddr->City)) ? $customer->BillAddr->City : '';
                    $customer_data[$key]['billing_address_state']      = (!empty($customer->BillAddr->CountrySubDivisionCode)) ? $customer->BillAddr->CountrySubDivisionCode : '';
                    $customer_data[$key]['billing_address_zip']        = (!empty($customer->BillAddr->PostalCode)) ? $customer->BillAddr->PostalCode : '';
                    $customer_data[$key]['shipping_address']           =  (!empty($customer->ShipAddr->Line1)) ? $customer->ShipAddr->Line1 : '';
                    $customer_data[$key]['shipping_address_city']      = (!empty($customer->BillAddr->City)) ? $customer->BillAddr->City : '';
                    $customer_data[$key]['shipping_address_state']     = (!empty($customer->BillAddr->CountrySubDivisionCode)) ? $customer->BillAddr->CountrySubDivisionCode : '';
                }
            }
            $format                     = MY_Controller::$date_format;
            $final['recordsTotal']      = $customer_count;
            $final['redraw']            = 1;
            $final['recordsFiltered']   = $final['recordsTotal'];
            $where                      = [];

            $start                      = $this->input->get('start') + 1;

            foreach ($customer_data as $key => $val) {
                $customer_data[$key]                    = $val;
                $customer_data[$key]['sr_no']           = $start++;
                $customer_data[$key]['responsive']      = '';
                $customer_data[$key]['quickbook']      = $this->get_customer_ark_status($val['qb_id']);
            }
            $final['data']                          = $customer_data;
            echo json_encode($final);
            die;
        }
    }


    /**
     * [add_customer_to_ark from QB Id get details and add in ARK]
     * @param [type] $qb_customer_id [QB customer id]
     * @author KBH <04-12-2020>
     */
    public function add_customer_to_ark()
    {
        $qb_customer_id                 = base64_decode($this->input->post('qb_customer_id'));
        global $data_service;
        if(isset($data_service['accessTokenJson']))
        {
            $customers                  = $data_service['dataService']->Query("SELECT * FROM Customer where Id = '".$qb_customer_id."'");
            $customer = $customers[0];
            if(!empty($customer))
            {
                $country_data['first_name']                 = (!empty($customer->GivenName)) ? $customer->GivenName : '';
                $country_data['last_name']                  = (!empty($customer->FamilyName)) ? $customer->FamilyName : '';
                $country_data['company']                    = (!empty($customer->CompanyName)) ? $customer->CompanyName : '';
                $country_data['display_name_as']            = (!empty($customer->DisplayName)) ? $customer->DisplayName : '';
                $country_data['phone']                      =  (!empty($customer->PrimaryPhone->FreeFormNumber)) ? $customer->PrimaryPhone->FreeFormNumber : '';
                $country_data['mobile']                     =  (!empty($customer->Mobile->FreeFormNumber)) ? $customer->Mobile->FreeFormNumber : '';
                $country_data['fax']                        =  (!empty($customer->Fax->FreeFormNumber)) ? $customer->Fax->FreeFormNumber : '';
                $country_data['email']                      =  (!empty($customer->PrimaryEmailAddr->Address)) ? $customer->PrimaryEmailAddr->Address : '';
                $country_data['billing_address']            =  (!empty($customer->BillAddr->Line1)) ? $customer->BillAddr->Line1 : '';
                $country_data['billing_address_city']       = (!empty($customer->BillAddr->City)) ? $customer->BillAddr->City : '';
                $country_data['billing_address_state']      = (!empty($customer->BillAddr->CountrySubDivisionCode)) ? $customer->BillAddr->CountrySubDivisionCode : '';
                $country_data['billing_address_zip']        = (!empty($customer->BillAddr->PostalCode)) ? $customer->BillAddr->PostalCode : '';
                $country_data['shipping_address']           =  (!empty($customer->ShipAddr->Line1)) ? $customer->ShipAddr->Line1 : '';
                $country_data['shipping_address_city']      = (!empty($customer->BillAddr->City)) ? $customer->BillAddr->City : '';
                $country_data['shipping_address_state']     = (!empty($customer->BillAddr->CountrySubDivisionCode)) ? $customer->BillAddr->CountrySubDivisionCode : '';

                $country_data['added_by']                   = $this->customers_model->auth_customer_id();

                $is_saved = $this->customers_model->insert_update('insert', TBL_CUSTOMERS, $country_data);

                if($is_saved)
                {
                    //Insert into TBL_QUICKBOOK_CUSTOMER
                    $quickbook_customer['realmId']              = $data_service['accessToken']->getRealmID();
                    $quickbook_customer['customer_id']          = $is_saved;
                    $quickbook_customer['quickbook_id']         = $customer->Id;

                    $is_quickbook_saved = $this->customers_model->insert_update('insert', TBL_QUICKBOOK_CUSTOMER, $quickbook_customer);

                    $final['success'] = true;
                    $final['message'] = 'Record successfully added in ARK!';
                    echo json_encode($final);
                    exit();
                }
                else
                {
                    $final['success'] = false;
                    $final['message'] = 'Error while adding QuickBook Customer to ARK!';
                    echo json_encode($final);
                    exit();
                }
            }
        }
        else
        {
            $final['success'] = false;
            $final['message'] = 'Your Quickbook session is exprired. Please login again';
            return $final;
        }
    }
}

