<?php

defined('BASEPATH') OR exit('No direct script access allowed');


require_once FCPATH . '/Library/QuickBook/autoload.php';
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\TaxRate;
use QuickBooksOnline\API\Facades\TaxService;
use QuickBooksOnline\API\Data\IPPReferenceType;
use QuickBooksOnline\API\Data\IPPAttachableRef;
use QuickBooksOnline\API\Data\IPPAttachable;
use Mpdf\Mpdf;

class Invoices extends MY_Controller {

    public function __construct() {
        global $data_service;
        parent::__construct();
        $this->load->model(array('admin/estimate_model', 'admin/users_model', 'admin/dashboard_model', 'admin/invoice_model', 'admin/inventory_model'));
        // date_default_timezone_set("America/Boise");
        $this->load->library('m_pdf');  
        $data_service = $this->data_service();      
    }

    /**
     * Display All items 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function display_invoices() {
        $data['title'] = 'List of Invoices';
        $this->template->load('default_front', 'front/invoices/invoice_display', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_invoice_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->invoice_model->get_invoices_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->invoice_model->get_invoices_data('result');
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
            $items[$key]['responsive'] = '';
            $items[$key]['quickbooks'] = $this->invoice_quickbook_status($val['id']);
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Display All items 
     * @param --
     * @return --
     * @author JJP [Last Edited : 09/10/2020]
     */
    public function invoice_trash() {
        $data['title'] = 'Invoice Trash';
        $this->template->load('default_front', 'front/invoices/invoice_trash', $data);
    }

    /**
     * Recoverd Invoice 
     * @param $id - String
     * @return --
     * @author JJP [Last Edited : 03/02/2018]
     */
    public function trash_recover_invoices($id = '') {
        $record_id = base64_decode($id);
        $this->invoice_model->insert_update('update', TBL_ESTIMATES, array('is_deleted' => 0), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Invoice has been recoverd successfully.');
        redirect('invoices');
    }

    /**
     * Delete Invoice trash record
     * @param $id - String
     * @return --
     * @author JJP [Last Edited : 03/02/2018]
     */
    public function trash_delete_invoices($id = '') {
        $record_id = base64_decode($id);
        $this->estimate_model->insert_update('update', TBL_ESTIMATES, array('is_deleted' => 2), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Invoice has been deleted successfully.');
        redirect('invoices/invoice_trash');
    }

    /**
     * Delete selected estimate
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 09/10/2020]
     */
    public function delete_multiple(){
        $delete_id_array = $this->input->post('delete_id');
        foreach ($delete_id_array as $key => $value) {
            $dataArr = array(
                'is_deleted' => 2
            );
            $where = array(
                'id' => base64_decode($value) 
            );
            $this->session->set_flashdata('success', 'Invoice has been deleted successfully.');
            $data = $this->estimate_model->insert_update('update',TBL_ESTIMATES,$dataArr,$where);
            echo json_encode($data);
        }
    }

    /**
     * Recover selected estimate
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 09/10/2020]
     */
    public function recover_multiple(){
        $recover_id_array = $this->input->post('recover_id');
        foreach ($recover_id_array as $key => $value) {
            $dataArr = array(
                'is_deleted' => 0
            );
            $where = array(
                'id' => base64_decode($value) 
            );
            $this->session->set_flashdata('success', 'Invoice has been recoverd successfully.');
            $data = $this->estimate_model->insert_update('update',TBL_ESTIMATES,$dataArr,$where);
            echo json_encode($data);
        }
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 09/10/2020]
     */
    public function get_items_data_trash() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->invoice_model->get_invoice_data_trash('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->invoice_model->get_invoice_data_trash('result');
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
            $items[$key]['responsive'] = '';
            $items[$key]['quickbooks'] = $this->estimate_quickbook_status($val['id']);
            $items[$key]['start_date'] = date('Y-m-d', strtotime($val['modified_date']));
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Edit Items
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    // public function edit_invoices($id = null,$copytoinvoice = null) {
    public function edit_invoices($id = null) {
        if(!empty($_REQUEST['multipul_est_id']) && !empty($_REQUEST['multipul_est_loc_id']) && !empty($_REQUEST['multipul_est_tra_id']))
        {
            $multipul_est_id = implode(',',$_REQUEST['multipul_est_id']);
            $multipul_est_loc_id = $_REQUEST['multipul_est_loc_id'];
            $multipul_est_tra_id = $_REQUEST['multipul_est_tra_id'];
        }

        $seg_method = $this->uri->segment(2);
        if($seg_method == 'copy_invoice'){
            $turn_invoice = 1;
        }else {
            $turn_invoice = 0;
        }
        $e_id = ($this->input->get('id')) ? $this->input->get('id', TRUE) : '';
        $es_id = ($this->input->get('estimate')) ? $this->input->get('estimate', TRUE) : '';
        $total_invoice = $this->invoice_model->get_all_details(TBL_ESTIMATES, array('business_user_id' => checkUserLogin('C'), 'is_invoiced' => 1))->num_rows();
        $est_id = 0;

        $data = array(
            'title' => 'Add Invoice',
            'format' => MY_Controller::$date_format,
            'currency' => MY_Controller::$currency,
            'estimate_id' => 'INV-' . str_pad(($total_invoice + 1), 6, '0', STR_PAD_LEFT),
            'users' => $this->users_model->total_users(1),
            'companyArr' => $this->dashboard_model->get_all_details(TBL_COMPANY, array('is_delete' => 0, 'status' => 'active'))->result_array(),
            'yearArr' => $this->dashboard_model->get_all_details(TBL_YEAR, array('is_delete' => 0),array(array('field' => 'name','type' => 'asc')))->result_array(),
            'colors' => $this->dashboard_model->get_all_details(TBL_VEHICLE_COLORS, array('is_deleted' => 0))->result_array(),
            'payment_methods' => $this->dashboard_model->get_all_details(TBL_PAYMENT_METHODS, array('is_deleted' => 0))->result_array(),
            'taxes' => $this->dashboard_model->get_all_details(TBL_TAXES, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->result_array(),
            'locations' => $this->invoice_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array(),
            'services' => $this->estimate_model->get_all_details(TBL_SERVICES, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C')])->result_array(),
            'customers' => $this->estimate_model->get_all_details(TBL_CUSTOMERS, ['is_deleted' => 0, 'added_by' => checkUserLogin('C')])->result_array(),
            'vehicleArr' => $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array(),
            'fieldArr' => $this->inventory_model->get_all_details(TBL_USER_SETTINGS_FIELD, array('is_deleted' => 0))->result_array(),
        );
        $payment_methods = $this->invoice_model->get_invoice_paid_options();
        $payments_ids = array_column($payment_methods, 'id');

        $bill_to_account_payment_methods = $this->invoice_model->get_invoice_bill_to_account_options();
        $bill_to_account_payments_ids = array_column($bill_to_account_payment_methods, 'id');

        if ($e_id != '') {
            $r_id = base64_decode($e_id);
            $location_id = base64_decode($this->input->get('location_id'));
            $transponder_id = $this->input->get('transponder_id') ? base64_decode($this->input->get('transponder_id')) : null;
            $type = $this->input->get('type') ? base64_decode($this->input->get('type')) : null;
            $data['drop_status'] = $this->input->get('drop_status') ? base64_decode($this->input->get('drop_status')) : 0;
            $data['itemArr'] = $this->get_item_data_ajax_by_id($r_id, $location_id, $transponder_id, $type)['itemArr'];
            if (!empty($data['itemArr']))
                $data['itemArr']['location_id'] = $location_id;
        }

        // Multipul Invoice
        if ($multipul_est_id != '') {
            $r_id = $multipul_est_id;
            $location_id = $multipul_est_loc_id;
            $transponder_id = $multipul_est_tra_id;
            $type = $this->input->get('type') ? base64_decode($this->input->get('type')) : null;
            $data['itemArr_multi'] = $this->get_item_data_ajax_by_id_multiple($r_id, $location_id, $transponder_id, $type)['itemArr_multi'];
            if (!empty($data['itemArr_multi']))
                $data['location_id'] = $location_id;
        }

        if ($es_id != '') {
            $estim_id = base64_decode($es_id);
            $data['EstimateArr'] = $this->estimate_model->get_estimate($estim_id);
            $data['copy_invoice_attachment'] = $this->estimate_model->get_all_details(TBL_ESTIMATION_ATTACHMENTS, array('estimation_id' => base64_decode($es_id)))->result_array();
        }
        if (!is_null($id)) {
            $record_id = base64_decode($id);
            $est_id = $record_id;
            $data['title'] = 'Edit Invoice';
            $data['dataArr'] = $this->estimate_model->get_estimate($record_id);
            $data['estimation_attachments'] = $this->estimate_model->get_all_details(TBL_ESTIMATION_ATTACHMENTS, array('estimation_id' => $record_id))->result_array();

            if (!empty($data['dataArr'])) {
                $data['parts']          = array_column($data['dataArr']['parts'], 'part_id');
                $data['Allservices']    = array_column($data['dataArr']['services'], 'service_id');
            }
        }   
        if ($this->input->post()) {
            // $this->form_validation->set_rules('txt_cust_name', 'Customer Name', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('sales_person', 'Sales Person', 'trim|required|max_length[100]');
            if ($this->form_validation->run() == true) {
                $expdate = ($this->input->post('expiry_date') != '') ? date('Y/m/d',strtotime($this->input->post('expiry_date'))) : date('Y/m/d',strtotime($this->input->post('estimate_date_submit')));

                if($seg_method == 'copy_invoice'){
                    // Cope Invoice
                    $updateArr = array(
                    'business_user_id' => checkUserLogin('C'),
                    'estimate_id' => 'INV-' . str_pad(($total_invoice + 1), 6, '0', STR_PAD_LEFT),
                    // 'estimate_id' => $estimate_id,
                    'cust_name' => htmlentities($this->input->post('txt_cust_name')),
                    'phone_number' => ($this->input->post('txt_phone_number') != '') ? htmlentities($this->input->post('txt_phone_number')) : null,
                    'email' => ($this->input->post('txt_email') != '') ? htmlentities($this->input->post('txt_email')) : null,
                    'address' => ($this->input->post('txt_address') != '') ? htmlentities($this->input->post('txt_address')) : null,
                    'notes' => ($this->input->post('txt_notes') != '') ? htmlentities($this->input->post('txt_notes')) : null,
                    'estimate_date' => $this->input->post('estimate_date_submit'),
                    'expiry_date' => ($this->input->post('expiry_date_submit') != '') ? ($this->input->post('expiry_date_submit')) : null,
                    // 'estimate_date' => date('Y/m/d',strtotime($this->input->post('hidden_invoice_date'))),
                    // 'expiry_date' => $expdate,
                    'make_id' => $this->input->post('txt_make_name'),
                    'modal_id' => $this->input->post('txt_model_name'),
                    'year_id' => $this->input->post('txt_year_name'),
                    'color_id' => $this->input->post('txt_color_id'),
                    'vin_id' => ($this->input->post('vin_id') != '') ? htmlentities($this->input->post('vin_id')) : null,
                    'lic_plate_id' => ($this->input->post('lic_plate_id') != '') ? htmlentities($this->input->post('lic_plate_id')) : null,
                    'po_number' => ($this->input->post('po_number') != '') ? htmlentities($this->input->post('po_number')) : null,
                    'stock' => ($this->input->post('stock') != '') ? htmlentities($this->input->post('stock')) : null,
                    'work_order' => ($this->input->post('work_order') != '') ? htmlentities($this->input->post('work_order')) : null,
                    'reference' => ($this->input->post('reference') != '') ? htmlentities($this->input->post('reference')) : null,
                    'tracking' => ($this->input->post('tracking') != '') ? htmlentities($this->input->post('tracking')) : null,
                    'sales_person' => $this->input->post('sales_person'),
                    'sub_total' => $this->input->post('sub_total'),
                    'tax_rate' => $this->input->post('final_tax_rate'),
                    'total' => $this->input->post('total'),
                    'shipping_charge' => $this->input->post('shipping_charge'),
                    'shipping_display_status' => $this->input->post('shipping_status'),
                    'individual_part_tax' => implode(",",$this->input->post('individual_part_tax')),
                    'individual_service_tax' => implode(",",$this->input->post('individual_service_tax')),
                    'is_invoiced' => 1,
                    'payment_method_id' => ($this->input->post('payment_method_id') != '') ? htmlentities($this->input->post('payment_method_id')) : 0,
                    'payment_reference' => ($this->input->post('payment_reference') != '') ? htmlentities($this->input->post('payment_reference')) : null
                    );
                } else {   
                 
                    // Normal Invoice
                    $updateArr = array(
                    'business_user_id' => checkUserLogin('C'),
                    'estimate_id'=> $this->input->post('hidden_estimate_id'),
                    // 'estimate_id' => 'INV-' . str_pad(($total_invoice + 1), 6, '0', STR_PAD_LEFT),
                    // 'estimate_id' => $estimate_id,
                    'cust_name' => htmlentities($this->input->post('txt_cust_name')),
                    // 'customer_uniq_name' => htmlentities($this->input->post('customer_uniq_name')),
                    'phone_number' => ($this->input->post('txt_phone_number') != '') ? htmlentities($this->input->post('txt_phone_number')) : null,
                    'email' => ($this->input->post('txt_email') != '') ? htmlentities($this->input->post('txt_email')) : null,
                    'address' => ($this->input->post('txt_address') != '') ? htmlentities($this->input->post('txt_address')) : null,
                    'notes' => ($this->input->post('txt_notes') != '') ? htmlentities($this->input->post('txt_notes')) : null,
                    'estimate_date' => $this->input->post('estimate_date_submit'),
                    'expiry_date' => ($this->input->post('expiry_date_submit') != '') ? ($this->input->post('expiry_date_submit')) : null,
                    // 'estimate_date' => date('Y/m/d',strtotime($this->input->post('hidden_invoice_date'))),
                    // 'expiry_date' => $expdate,
                    'make_id' => $this->input->post('txt_make_name'),
                    'modal_id' => $this->input->post('txt_model_name'),
                    'year_id' => $this->input->post('txt_year_name'),
                    'color_id' => $this->input->post('txt_color_id'),
                    'vin_id' => ($this->input->post('vin_id') != '') ? htmlentities($this->input->post('vin_id')) : null,
                    'lic_plate_id' => ($this->input->post('lic_plate_id') != '') ? htmlentities($this->input->post('lic_plate_id')) : null,
                    'po_number' => ($this->input->post('po_number') != '') ? htmlentities($this->input->post('po_number')) : null,
                    'stock' => ($this->input->post('stock') != '') ? htmlentities($this->input->post('stock')) : null,
                    'work_order' => ($this->input->post('work_order') != '') ? htmlentities($this->input->post('work_order')) : null,
                    'reference' => ($this->input->post('reference') != '') ? htmlentities($this->input->post('reference')) : null,
                    'tracking' => ($this->input->post('tracking') != '') ? htmlentities($this->input->post('tracking')) : null,
                    'sales_person' => $this->input->post('sales_person'),
                    'sub_total' => $this->input->post('sub_total'),
                    'tax_rate' => $this->input->post('final_tax_rate'),
                    'total' => $this->input->post('total'),
                    'shipping_charge' => $this->input->post('shipping_charge'),
                    'shipping_display_status' => $this->input->post('shipping_status'),
                    'individual_part_tax' => implode(",",$this->input->post('individual_part_tax')),
                    'individual_service_tax' => implode(",",$this->input->post('individual_service_tax')),
                    'is_invoiced' => 1,
                    'payment_method_id' => ($this->input->post('payment_method_id') != '') ? htmlentities($this->input->post('payment_method_id')) : 0,
                    'payment_reference' => ($this->input->post('payment_reference') != '') ? htmlentities($this->input->post('payment_reference')) : null
                    );  
                }

                if(!empty($this->input->post('invoice_type')) && $this->input->post('invoice_type') == 1){
                    if (!empty($this->input->post('payment_method_id')) && !empty($payments_ids) && in_array($this->input->post('payment_method_id'), $payments_ids)) {
                            $updateArr['is_sent'] = 0;
                            $updateArr['is_save_draft'] = 0;
                            $updateArr['is_paid'] = 1;
                    } else if (!empty($this->input->post('payment_method_id')) && in_array($this->input->post('payment_method_id'), $bill_to_account_payments_ids)) {
                        $updateArr['is_save_draft'] = 0;
                        $updateArr['is_paid'] = 0;
                        $updateArr['is_sent'] = 1;
                    } else {
                        $updateArr['is_sent'] = ($this->input->post('save_send')) ? 1 : 0;
                        if (!empty($data['dataArr']) && $data['dataArr']['is_sent'] == 0) {
                            if ($updateArr['is_sent'] == 1) {
                                $updateArr['is_save_draft'] = 0;
                            }
                        }
                    }
                }else{
                    $updateArr['is_sent'] = 0;
                    $updateArr['is_paid'] = 0;
                    $updateArr['is_save_draft'] = 1;
                    $updateArr['payment_method_id'] = 0;
                }
                
                if (!empty($this->input->post('signature_attachment'))) {
                    define('UPLOAD_DIR', 'uploads/signatures/');
                    $img = $this->input->post('signature_attachment');
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $image_data = base64_decode($img);
                    $file = UPLOAD_DIR . $this->input->post('hidden_estimate_id') . '.png';
                    $success = file_put_contents($file, $image_data);
                    if ($success) {
                        $updateArr['signature_attachment'] = $this->input->post('hidden_estimate_id') . '.png';
                    }
                }
                if (!is_null($id)) {
                    $updateArr['last_modified_by'] = checkUserLogin('I');
                    $this->estimate_model->insert_update('update', TBL_ESTIMATES, $updateArr, array('id' => $record_id));
                    $existing_part = array_column($data['dataArr']['parts'], 'part_id');
                    $existing_service = array_column($data['dataArr']['services'], 'service_id');

                    $data['dataArr'] = $this->estimate_model->get_estimate($record_id);

                    if (!empty($data['dataArr'])) {
                        $data['parts'] = array_column($data['dataArr']['parts'], 'part_id');
                        $data['Allservices'] = array_column($data['dataArr']['services'], 'service_id');
                    }

                    if (!empty($this->input->post('hidden_part_id'))) {
                        
                        $create_service_id     = $this->input->post('hidden_part_id');
                        $create_hidden_tax_id  = $this->input->post('hidden_part_tax_id');
                        $create_hidden_tax_list  = $this->input->post('hidden_pname_tax_id');
                        $create_hidden_tax_amount  = $this->input->post('hidden_part_tax_amount');
                        $new_service_tax_id    = array();
                        $new_service_tax_list    = array();
                        $new_service_tax_amount   = array();
                        
                        $i = 0;
                        // pr($create_hidden_tax_id, 1);
                        foreach($create_hidden_tax_id as $key => $value){
                            $new_service_tax_id[$i.'_'.$create_service_id[$i]] = implode(',',$value);
                            $i++;    
                        }

                        $j = 0;
                        // pr($create_hidden_tax_list, 1);
                        foreach($create_hidden_tax_list as $key => $value){
                            $new_service_tax_list[$j.'_'.$create_service_id[$j]] = implode(',',$value);
                            $j++;    
                        }

                        $s = 0;
                        // pr($create_hidden_tax_amount, 1);
                        foreach($create_hidden_tax_amount as $key => $value){
                            $new_service_tax_amount[$s.'_'.$create_service_id[$s]] = implode(',',$value);
                            $s++;    
                        }

                        foreach ($this->input->post('hidden_part_id') as $k => $p):
                            if (($key = array_search($p, $existing_part)) !== false) {
                                unset($existing_part[$key]);
                                $parts = $data['dataArr']['parts'][$k];
                                $edit_parts = [
                                    'part_id' => $p,
                                    'location_id' => $this->input->post('location_id')[$k],
                                    'description' => $this->input->post('description')[$k],
                                    'item_note' => $this->input->post('item_note')[$k],
                                    'quantity' => $this->input->post('quantity')[$k],
                                    'discount' => $this->input->post('discount')[$k],
                                    'discount_type_id' => $this->input->post('discount_type_id')[$k],
                                    'rate' => $this->input->post('rate')[$k],
                                    'tax_id' => $new_service_tax_id[$k.'_'.$p] != '' ? $new_service_tax_id[$k.'_'.$p] : 0,
                                    'tax_list' => $new_service_tax_list[$k.'_'.$p],
                                    'tax_rate' => $this->input->post('tax')[$k],
                                    'individual_part_tax' => $new_service_tax_amount[$k.'_'.$p],
                                    'discount_rate' => $this->input->post('discount_rate')[$k],
                                    'amount' => $this->input->post('amount')[$k],
                                ];
                                $this->estimate_model->insert_update('update', TBL_ESTIMATE_PARTS, $edit_parts,array('estimate_id' => $record_id, 'id' => $parts['id']));
                            } else {
                                $add_parts = [
                                    'estimate_id' => $record_id,
                                    'part_id' => $p,
                                    'location_id' => $this->input->post('location_id')[$k],
                                    'description' => $this->input->post('description')[$k],
                                    'item_note' => $this->input->post('item_note')[$k],
                                    'quantity' => $this->input->post('quantity')[$k],
                                    'discount' => $this->input->post('discount')[$k],
                                    'discount_type_id' => $this->input->post('discount_type_id')[$k],
                                    'rate' => $this->input->post('rate')[$k],
                                    'tax_id' => $new_service_tax_id[$k.'_'.$p] != '' ? $new_service_tax_id[$k.'_'.$p] : 0,
                                    'tax_list' => $new_service_tax_list[$k.'_'.$p],
                                    'tax_rate' => $this->input->post('tax')[$k],
                                    'individual_part_tax' => $new_service_tax_amount[$k.'_'.$p],
                                    'discount_rate' => $this->input->post('discount_rate')[$k],
                                    'amount' => $this->input->post('amount')[$k],
                                    'created_date' => date('Y-m-d h:i:s'),
                                ];
                                $this->estimate_model->insert_update('insert', TBL_ESTIMATE_PARTS, $add_parts);
                                
                                // Deduct quantity
                                $is_exists = $this->inventory_model->get_result(TBL_ITEM_LOCATION_DETAILS, ['business_user_id' => checkUserLogin('C'), 'location_id' => $this->input->post('location_id')[$k], 'item_id' => $p, 'is_deleted' => 0], null, 1);
                                if (!empty($is_exists)) {
                                    $quantity = ($is_exists['quantity'] - $this->input->post('quantity')[$k]);
                                 
                                    $update_array[$k] = [
                                        'quantity' => $quantity,
                                        'last_modified_by' => checkUserLogin('I'),
                                        'id' => $is_exists['id'],
                                        'modified_date' => date('Y-m-d h:i:s')
                                    ];
                                }

                                if(isset($update_array) && !empty($update_array)):
                                    $this->inventory_model->batch_insert_update('update',TBL_ITEM_LOCATION_DETAILS, $update_array, 'id');
                                endif;                                
                            }

                            $is_exists = $this->inventory_model->get_result(TBL_ITEM_LOCATION_DETAILS, ['business_user_id' => checkUserLogin('C'), 'location_id' => $this->input->post('location_id')[$k], 'item_id' => $p, 'is_deleted' => 0], null, 1);
                            $notification_exists = $this->inventory_model->get_result(TBL_NOTIFICATION, ['business_user_id' => checkUserLogin('C'), 'enum_id' => $is_exists['item_id']]);

                            // get_low_inventory Nofification
                            $low_inventroy = $this->get_low_inventory($is_exists['item_id'], 1);
                            if($low_inventroy['total_quantity'] <= $low_inventroy['low_inventory_limit'])
                            {
                                $notification['business_user_id'] = checkUserLogin('C');
                                $notification['enum_id'] = $low_inventroy['item_id'];
                                $notification['estimate_invoice'] = '2';
                                if(!empty($notification_exists))
                                {
                                    $estimate_id = $this->estimate_model->insert_update('update', TBL_NOTIFICATION, array('is_delete' => 0),array('enum_id' =>$is_exists['item_id']));
                                } else {
                                    $estimate_id = $this->estimate_model->insert_update('insert', TBL_NOTIFICATION, $notification);
                                }
                                $this->session->set_userdata(['item_notification' => 1]);
                            }

                            if ($updateArr['is_sent'] == 1) {
                                $insert_invenoty[$k] = [
                                    'business_user_id' => checkUserLogin('C'),
                                    'quantity' => (-1 * abs($this->input->post('quantity')[$k])),
                                    'added_by' => checkUserLogin('I'),
                                    'item_id' => $p,
                                    'location_id' => $this->input->post('location_id')[$k],
                                    'invoice_id' => $record_id,
                                    'created_date' => date('Y-m-d h:i:s')
                                ];

                                // Deduct quantity
                                $is_exists = $this->inventory_model->get_result(TBL_ITEM_LOCATION_DETAILS, ['business_user_id' => checkUserLogin('C'), 'location_id' => $this->input->post('location_id')[$k], 'item_id' => $p, 'is_deleted' => 0], null, 1);
                                if (!empty($is_exists)) {
                                $quantity=($is_exists['quantity'] - $this->input->post('quantity')[$k]);
                                    $update_array[$k] = [
                                        'quantity' => $quantity,
                                        'last_modified_by' => checkUserLogin('I'),
                                        'id' => $is_exists['id'],
                                        'modified_date' => date('Y-m-d h:i:s')
                                    ];
                                }
                            }                            
                        endforeach;
                    }
                    if ($this->input->post('service_id')) {
                        $create_service_id     = $this->input->post('service_id');
                        $create_hidden_tax_id  = $this->input->post('hidden_tax_id');
                        $create_hidden_tax_list  = $this->input->post('hidden_sname_tax_id');
                        $create_hidden_tax_amount  = $this->input->post('hidden_service_tax_amount');
                        $new_service_tax_id    = array();
                        $new_service_tax_list    = array();
                        $new_service_tax_amount    = array();
                        
                        $i = 0;
                        // pr($create_hidden_tax_id, 1);
                        foreach($create_hidden_tax_id as $key => $value){
                            $new_service_tax_id[$i.'_'.$create_service_id[$i]] = implode(',',$value);
                            $i++;    
                        }

                        $j = 0;
                        // pr($create_hidden_tax_list, 1);
                        foreach($create_hidden_tax_list as $key => $value){
                            $new_service_tax_list[$j.'_'.$create_service_id[$j]] = implode(',',$value);
                            $j++;    
                        }

                        $s = 0;
                        // pr($create_hidden_tax_amount, 1);
                        foreach($create_hidden_tax_amount as $key => $value){
                            $new_service_tax_amount[$s.'_'.$create_service_id[$s]] = implode(',',$value);
                            $s++;    
                        }

                        foreach ($this->input->post('service_id') as $k => $p):
                            if($p != null)
                            {
                                $check_services = $data['dataArr']['services'];
                                if (array_key_exists($k, $check_services)) {
                                    $services = $data['dataArr']['services'][$k];
                                    if (($key = array_search($p, $existing_service)) !== false) {
                                        unset($existing_service[$key]);
                                        $edit_services = [
                                            'service_id' => $p,
                                            'service_note' => $this->input->post('service_note')[$k],
                                            'discount' => $this->input->post('service_discount')[$k],
                                            'discount_type_id' => $this->input->post('service_discount_type_id')[$k],
                                            'rate' => $this->input->post('service_rate')[$k],
                                            'qty' => $this->input->post('srvquantity')[$k],
                                            'tax_id' => $new_service_tax_id[$k.'_'.$p] != '' ? $new_service_tax_id[$k.'_'.$p] : 0,
                                            'tax_list' => $new_service_tax_list[$k.'_'.$p],
                                            'tax_rate' => $this->input->post('service_tax')[$k],
                                            'individual_service_tax' => $new_service_tax_amount[$k.'_'.$p],
                                            'discount_rate' => $this->input->post('service_discount_rate')[$k],
                                            'amount' => $this->input->post('service_amount')[$k],
                                            'modified_date' => date('Y-m-d h:i:s')
                                        ];
                                        $this->estimate_model->insert_update('update', TBL_ESTIMATE_SERVICES, $edit_services, array('estimate_id' => $record_id, 'id' => $services['id']));
                                    }
                                } else {
                                    $add_services = [
                                        'estimate_id' => $record_id,
                                        'service_id' => $p,
                                        'service_note' => $this->input->post('service_note')[$k],
                                        'discount' => $this->input->post('service_discount')[$k],
                                        'discount_type_id' => $this->input->post('service_discount_type_id')[$k],
                                        'rate' => $this->input->post('service_rate')[$k],
                                        'qty' => $this->input->post('srvquantity')[$k],
                                        'tax_id' => $new_service_tax_id[$k.'_'.$p] != '' ? $new_service_tax_id[$k.'_'.$p] : 0,
                                        'tax_list' => $new_service_tax_list[$k.'_'.$p],
                                        'tax_rate' => $this->input->post('service_tax')[$k],
                                        'individual_service_tax' => $new_service_tax_amount[$k.'_'.$p],
                                        'discount_rate' => $this->input->post('service_discount_rate')[$k],
                                        'amount' => $this->input->post('service_amount')[$k],
                                        'created_date' => date('Y-m-d h:i:s')
                                    ];
                                    $this->estimate_model->insert_update('insert', TBL_ESTIMATE_SERVICES, $add_services);
                                }
                            }
                        endforeach;
                    }
                    if (!empty($existing_part)) {
                        foreach ($existing_part as $k => $v) {
                            $parts = $data['dataArr']['parts'][$k];
                            $delete_parts = [
                                'is_deleted' => 1
                            ];
                            $this->estimate_model->insert_update('update', TBL_ESTIMATE_PARTS, $delete_parts, array('estimate_id' => $record_id, 'id' => $parts['id'], 'is_deleted' => 0));
                        }
                    }
                    if (!empty($existing_service)) {
                        foreach ($existing_service as $k => $v) {
                            $services = $data['dataArr']['services'][$k];
                            $delete_services = [
                                'is_deleted' => 1
                            ];
                            $this->estimate_model->insert_update('update', TBL_ESTIMATE_SERVICES, $delete_services, array('estimate_id' => $record_id, 'id' => $services['id'], 'is_deleted' => 0));
                        }
                    }
                    if (isset($insert_invenoty) && !empty($insert_invenoty)):
                        $this->inventory_model->batch_insert_update('insert', TBL_ITEM_INVENTORY_DETAILS, $insert_invenoty);
                    endif;
                    if (isset($update_array) && !empty($update_array)):
                        $this->inventory_model->batch_insert_update('update', TBL_ITEM_LOCATION_DETAILS, $update_array, 'id');
                        $this->estimate_model->insert_update('update', TBL_ESTIMATES, ['is_deducted' => 1], ['id' => $record_id]);
                    endif;
                    $this->session->set_flashdata('success', 'Invoice has been updated successfully.');
                } else {

                    // $updateArr['created_date'] = date('Y-m-d h:i:s');
                    $updateArr['added_by'] = checkUserLogin('I');
                    $updateArr['turn_invoiced'] = $turn_invoice;

                    if($this->input->post('customer_id') == "")
                    {
                        $updateArr['customer_id'] = "";
                    } else { 
                        $updateArr['customer_id'] = $this->input->post('customer_id');
                    }
                    $estimate_id = $this->estimate_model->insert_update('insert', TBL_ESTIMATES, $updateArr);
                
                    $last_insert_id = $this->db->insert_id();

                    $est_id = $estimate_id;
                    if ($estimate_id):
                        if ($this->input->post('hidden_part_id')) {
                            $create_service_id     = $this->input->post('hidden_part_id');
                            $create_hidden_tax_id  = $this->input->post('hidden_part_tax_id');
                            $create_hidden_tax_list  = $this->input->post('hidden_pname_tax_id');
                            $create_hidden_tax_amount  = $this->input->post('hidden_part_tax_amount');
                            $new_service_tax_id    = array();
                            $new_service_tax_list    = array();
                            $new_service_tax_amount    = array();

                            $i = 0;
                            // pr($create_hidden_tax_id, 1);
                            foreach($create_hidden_tax_id as $key => $value){
                                $new_service_tax_id[$i.'_'.$create_service_id[$i]] = implode(',',$value);
                                $i++;    
                            }

                            $j = 0;
                            // pr($create_hidden_tax_list, 1);
                            foreach($create_hidden_tax_list as $key => $value){
                                $new_service_tax_list[$j.'_'.$create_service_id[$j]] = implode(',',$value);
                                $j++;    
                            }

                            $s = 0;
                            // pr($create_hidden_tax_amount);
                            foreach($create_hidden_tax_amount as $key => $value){
                                $new_service_tax_amount[$s.'_'.$create_service_id[$s]] = implode(',',$value);
                                $s++;    
                            }

                            foreach ($this->input->post('hidden_part_id') as $k => $p):
                                $parts[$k] = [
                                    'estimate_id' => $est_id,
                                    'part_id' => $p,
                                    'location_id' => $this->input->post('location_id')[$k],
                                    'description' => $this->input->post('description')[$k],
                                    'item_note' => $this->input->post('item_note')[$k],
                                    'quantity' => $this->input->post('quantity')[$k],
                                    'discount' => $this->input->post('discount')[$k],
                                    'discount_type_id' => $this->input->post('discount_type_id')[$k],
                                    'rate' => $this->input->post('rate')[$k],
                                    'tax_id' => $new_service_tax_id[$k.'_'.$p] != '' ? $new_service_tax_id[$k.'_'.$p] : 0,
                                    'tax_list' => $new_service_tax_list[$k.'_'.$p],
                                    'tax_rate' => $this->input->post('tax')[$k],
                                    'individual_part_tax' => $new_service_tax_amount[$k.'_'.$p],
                                    'discount_rate' => $this->input->post('discount_rate')[$k],
                                    'amount' => $this->input->post('amount')[$k],
                                    'created_date' => date('Y-m-d h:i:s')
                                ];        
                                // Insert Into Inventory History
                                $invoice_inventory = array(
                                        'its_for'=>'invoice',
                                        'user_id'=>checkUserLogin('C'),
                                        'invdesc'=>checkUserLogin('U').' has invoiced '.$this->input->post('quantity')[$k],
                                        'invdesc1'=>$this->input->post('hidden_part_id')[$k],
                                        'invdesc2'=>' on invoice'.' INV-' . str_pad(($total_invoice + 1), 6, '0', STR_PAD_LEFT),
                                        'user_name'=>checkUserLogin('U'),
                                        'item_name'=>$this->input->post('hidden_part_id')[$k],
                                        'created_date' => date('Y-m-d h:i:s')
                                    );
                                $this->invoice_model->addinvoiceinventory($invoice_inventory);

                                // Deduct quantity
                                $is_exists = $this->inventory_model->get_result(TBL_ITEM_LOCATION_DETAILS, ['business_user_id' => checkUserLogin('C'), 'location_id' => $this->input->post('location_id')[$k], 'item_id' => $p, 'is_deleted' => 0], null, 1);
                                if (!empty($is_exists)) {
                                    $quantity = ($is_exists['quantity'] - $this->input->post('quantity')[$k]);
                                 
                                    $update_array[$k] = [
                                        'quantity' => $quantity,
                                        'last_modified_by' => checkUserLogin('I'),
                                        'id' => $is_exists['id'],
                                        'modified_date' => date('Y-m-d h:i:s')
                                    ];  
                                }

                                if(isset($update_array) && !empty($update_array)):
                                    $this->inventory_model->batch_insert_update('update',TBL_ITEM_LOCATION_DETAILS, $update_array, 'id');
                                    // End Deduct quantity

                                    $notification_exists = $this->inventory_model->get_result(TBL_NOTIFICATION, ['business_user_id' => checkUserLogin('C'), 'enum_id' => $is_exists['item_id']]);
                                    
                                    $low_inventroy = $this->get_low_inventory($is_exists['item_id'], 1);                                   
                                    if($low_inventroy['total_quantity'] <= $low_inventroy['low_inventory_limit'])
                                    {
                                        $notification['business_user_id'] = checkUserLogin('C');
                                        $notification['enum_id'] = $low_inventroy['item_id'];
                                        $notification['estimate_invoice'] = '2';
                                    
                                        if(!empty($notification_exists))
                                        {
                                            $estimate_id = $this->estimate_model->insert_update('update', TBL_NOTIFICATION, array('is_delete' => 0),array('enum_id' =>$is_exists['item_id']));
                                        } else {
                                            $estimate_id = $this->estimate_model->insert_update('insert', TBL_NOTIFICATION, $notification);
                                        }
                                        $this->session->set_userdata(['item_notification' => 1]);
                                    }
                                                                        
                                    // $inventory_data = $this->get_low_inventory($is_exists['item_id']);
                                    // if(!empty($inventory_data))
                                    // {
                                    //     $notification['business_user_id'] = checkUserLogin('C');
                                    //     $notification['enum_id'] = $inventory_data['item_id'];
                                    //     $notification['estimate_invoice'] = '2';
                                    //     $estimate_id = $this->estimate_model->insert_update('insert', TBL_NOTIFICATION, $notification);
                                    //     $this->session->set_userdata(['item_notification' => 1]);
                                    // }

                                endif;
                                

                                if ($updateArr['is_sent'] == 1) {
                                    $insert_invenoty[$k] = [
                                        'business_user_id' => checkUserLogin('C'),
                                        'quantity' => (-1 * abs($this->input->post('quantity')[$k])),
                                        'added_by' => checkUserLogin('I'),
                                        'item_id' => $p,
                                        'location_id' => $this->input->post('location_id')[$k],
                                        'invoice_id' => $estimate_id,
                                        'created_date' => date('Y-m-d h:i:s')
                                    ];
                                    // Deduct quantity
                                    $is_exists = $this->inventory_model->get_result(TBL_ITEM_LOCATION_DETAILS, ['business_user_id' => checkUserLogin('C'), 'location_id' => $this->input->post('location_id')[$k], 'item_id' => $p, 'is_deleted' => 0], null, 1);
                                    if (!empty($is_exists)) {
                                        $quantity = ($is_exists['quantity'] - $this->input->post('quantity')[$k]);
                                        $update_array[$k] = [
                                            'quantity' => $quantity,
                                            'last_modified_by' => checkUserLogin('I'),
                                            'id' => $is_exists['id'],
                                            'modified_date' => date('Y-m-d h:i:s')
                                        ];
                                    }
                                    if (isset($insert_invenoty) && !empty($insert_invenoty)):
                                        $this->inventory_model->batch_insert_update('insert', TBL_ITEM_INVENTORY_DETAILS, $insert_invenoty);
                                    endif;

                                    $this->estimate_model->insert_update('update', TBL_ESTIMATES, ['is_deducted' => 1], ['id' => $estimate_id]);
                                }
                                
                            endforeach;
                            $this->estimate_model->batch_insert_update('insert', TBL_ESTIMATE_PARTS, $parts);
                            
                        }
                        if ($this->input->post('service_id')) {
                            $create_service_id       = $this->input->post('service_id');
                            $create_hidden_tax_id    = $this->input->post('hidden_tax_id');
                            $create_hidden_tax_list  = $this->input->post('hidden_sname_tax_id');
                            $create_hidden_tax_amount  = $this->input->post('hidden_service_tax_amount');
                            $new_service_tax_id      = array();
                            $new_service_tax_list    = array();
                            $new_service_tax_amount    = array();
                            
                            $i = 0;
                            // pr($create_hidden_tax_id, 1);
                            foreach($create_hidden_tax_id as $key => $value){
                                $new_service_tax_id[$i.'_'.$create_service_id[$i]] = implode(',',$value);
                                $i++;    
                            }

                            $j = 0;
                            // pr($create_hidden_tax_list, 1);
                            foreach($create_hidden_tax_list as $key => $value){
                                $new_service_tax_list[$j.'_'.$create_service_id[$j]] = implode(',',$value);
                                $j++;    
                            }

                            $s = 0;
                            // pr($create_hidden_tax_amount);
                            foreach($create_hidden_tax_amount as $key => $value){
                                $new_service_tax_amount[$s.'_'.$create_service_id[$s]] = implode(',',$value);
                                $s++;    
                            }
                            foreach ($this->input->post('service_id') as $k => $p):
                                if ($p != '') {
                                    $services[$k] = [
                                        'estimate_id' => $est_id,
                                        'service_id' => $p,
                                        'service_note' => $this->input->post('service_note')[$k],
                                        'discount' => $this->input->post('service_discount')[$k],
                                        'discount_type_id' => $this->input->post('service_discount_type_id')[$k],
                                        'rate' => $this->input->post('service_rate')[$k],
                                        'qty' => $this->input->post('srvquantity')[$k],
                                        'tax_id' => $new_service_tax_id[$k.'_'.$p] != '' ? $new_service_tax_id[$k.'_'.$p] : 0,
                                        'tax_list' => $new_service_tax_list[$k.'_'.$p],
                                        'tax_rate' => $this->input->post('service_tax')[$k],
                                        'individual_service_tax' => $new_service_tax_amount[$k.'_'.$p],
                                        'discount_rate' => $this->input->post('service_discount_rate')[$k],
                                        'amount' => $this->input->post('service_amount')[$k],
                                        'created_date' => date('Y-m-d h:i:s')
                                    ];
                                }
                            endforeach;
                            if (isset($services) && !empty($services)) {
                                $this->estimate_model->batch_insert_update('insert', TBL_ESTIMATE_SERVICES, $services);
                            }
                        }
                        $est_id = $estimate_id;

                        $last_inovoice = $this->invoice_model->get_invoice_from_id(['e.id' => $estimate_id]);
                        
                        if($last_inovoice['business_user_id'] != $last_inovoice['added_by'])
                        {
                            $notification['business_user_id'] = $last_inovoice['business_user_id'];
                            $notification['added_by'] = $last_inovoice['added_by'];
                            $notification['enum_id'] = $estimate_id;
                            $notification['estimate_invoice'] = '1';
                            $estimate_id = $this->estimate_model->insert_update('insert', TBL_NOTIFICATION, $notification);
                            $this->session->set_userdata(['invoice_notification' => 1]);
                        }
                    $this->session->set_flashdata('success', 'Estimate has been created successfully.');
                    endif;
                    
                    $this->session->set_flashdata('success', 'Invoice has been created successfully.');
                }
                if (!empty($_FILES) && count($_FILES['attachments']['name']) > 0) {
                    $files = $_FILES['attachments'];
                    $this->load->library('upload');
                    $image_type = array('jpg', 'png', 'jpeg', 'gif');
                    $pdf_type = array('pdf');

                    $order_ids = $this->input->post('order_ids');

                    //$allowed files types should be like, 'jpg|gif|png'
                    $config = array(
                        'upload_path' => 'uploads/attachments',
                        'allowed_types' => 'jpg|png|jpeg|pdf',
                        'overwrite' => TRUE
                    );
                    $order_key = 1;
                    foreach ($files['name'] as $key => $image) {
                        $file_type = 'Image';
                        $image_data = array();
                        $ext = pathinfo($image, PATHINFO_EXTENSION);
                        if (!empty($files['name'][$key])) {
                            $_FILES['attachments']['name'] = $files['name'][$key];
                            $_FILES['attachments']['type'] = $files['type'][$key];
                            $_FILES['attachments']['tmp_name'] = $files['tmp_name'][$key];
                            $_FILES['attachments']['error'] = $files['error'][$key];
                            $_FILES['attachments']['size'] = $files['size'][$key];

                            $fileName = 'attachment_' . $key . '_' . $est_id . '.' . $ext;
                            $config['file_name'] = $fileName;

                            $this->upload->initialize($config);
                            if ($this->upload->do_upload('attachments')) {
                                $this->upload->data();

                                if (in_array($ext, $image_type)) {
                                    $file_type = 'Image';
                                } else if (in_array($ext, $pdf_type)) {
                                    $file_type = 'PDF';
                                }

                                $image_data['file_name'] = $fileName;
                                $image_data['type'] = $file_type;

                                $is_file_attached = $this->estimate_model->get_all_details(TBL_ESTIMATION_ATTACHMENTS, array('estimation_id' => $est_id, 'order_id' => $order_key))->row_array();

                                if (!empty($is_file_attached) && sizeof($is_file_attached) > 0) {
                                    $this->estimate_model->insert_update('update', TBL_ESTIMATION_ATTACHMENTS, $image_data, array('id' => $is_file_attached['id']));
                                } else {
                                    $image_data['order_id'] = $order_key;
                                    $image_data['estimation_id'] = $est_id;
                                    $this->estimate_model->insert_update('insert', TBL_ESTIMATION_ATTACHMENTS, $image_data);
                                }
                            }
                        }
                        $order_key++;
                    }
                } else {
                    // Add copy to invoice attachment 
                    if(!empty($this->input->post('copy_to_inv_attachments')) && $this->input->post('copy_to_inv_attachments') != null)
                    {
                        foreach ($this->input->post('copy_to_inv_attachments') as $cp_id => $cp_value) {
                            if($cp_value != "")
                            {
                                $extension = "";
                                $extension = pathinfo($this->input->post('copy_to_inv_attachments')[$cp_id], PATHINFO_EXTENSION);
                                if($extension != "PDF" && $extension != "pdf")
                                {
                                   $extension = "Image"; 
                                }
                                $cp_attachment[$cp_id] = array(
                                    'estimation_id' => $last_insert_id,
                                    'file_name' => $this->input->post('copy_to_inv_attachments')[$cp_id],
                                    'type' => $extension,
                                    'order_id' => $cp_id + 1,
                                );
                                $this->estimate_model->insert_update('insert',TBL_ESTIMATION_ATTACHMENTS,$cp_attachment[$cp_id]);
                            }
                        }
                    }
                }

                if ($this->input->post('save_send') == 1) {
                    $this->session->set_flashdata('success','Invoice has been created and sent successfully.');

                    if (!is_null($id))
                        $this->send_pdf(base64_encode($est_id));
                    else
                        $this->send_pdf(base64_encode($est_id), 1);
                } else if ($this->input->post('save_send') == 2) {
                    $this->print_pdf(base64_encode($est_id));
                } else if ($this->input->post('save_send') == 3) {
                    $pdf_result = $this->pdf_preview($est_id);
                    $this->session->set_userdata('edit_print_preview',$pdf_result);

                    if($this->uri->segment('2') == "edit")
                    {
                        $currentURL = current_url();
                        redirect($currentURL, 'refresh');    
                    } else {
                        $last_estimate_id = $this->invoice_model->get_all_details(TBL_ESTIMATES,array('is_deleted' => 0,'added_by' => $this->session->userdata('u_user_id'),'is_invoiced' => 1), array(array('field' => 'id', 'type' => 'desc')),array('l1' => 1, 'l2' => 0))->row_array();
                        $final_invoice_id = base64_encode($last_estimate_id['id']);
                        $print_back_url = base_url('invoices/edit/'.$final_invoice_id);
                        redirect($print_back_url, 'refresh');    
                    }
                }

                if (isset($_SESSION['sessionAccessToken'])) 
                {
                    if (!is_null($id)) 
                    {
                        $session_accesToken = $_SESSION['sessionAccessToken'];
                        $invoice_quickbook_details = $this->inventory_model->get_all_details(TBL_QUICKBOOK_INVOICE, ['invoice_id' => $record_id,'realmId' => $session_accesToken-> getRealmID()])->row_array();
                        if(!empty($invoice_quickbook_details))
                        {
                            $this->add_edit_invoice_quickbook($id, $flag = "update");

                        }
                    }
                    else
                    {
                        $estimate_copy_id = $this->input->post('estimate_copy_id');
                        $estimate_id = base64_encode($estimate_id);
                        $this->add_edit_invoice_quickbook($estimate_id, $flag=1, $turn_invoice, $estimate_copy_id);
                    }
                }
                
                if($seg_method == 'copy_invoice')
                {
                    redirect('invoices');
                } else {
                    if($referred_from = $this->session->userdata('referred_from'))
                    {
                        redirect($referred_from, 'refresh');    
                    }
                    else 
                    { 
                        redirect('invoices');
                    }
                }
            }
        }
        
        $this->template->load('default_front', 'front/invoices/invoice_add', $data);
    }

    /**
     * Delete Items
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function delete_invoices($id = '') {
        $record_id = base64_decode($id);
        $this->estimate_model->insert_update('update', TBL_ESTIMATES, array('is_deleted' => 1), array('id' => $record_id, 'is_invoiced' => 1));
        $data_service = $this->data_service();
        global $data_service;
        if(isset($data_service['accessTokenJson']))
        {
            $where = array(
                'invoice_id' => $record_id,
                'realmId' => $data_service['accessToken']-> getRealmID(),
            );
            $invoice_quickbook_details = $this->estimate_model->get_all_details(TBL_QUICKBOOK_INVOICE, $where)->row_array();
            if(!empty($invoice_quickbook_details))
            {
                $invoice = $data_service['dataService']->FindbyId('invoice', $invoice_quickbook_details['quickbook_id']);
                $resultingObj = $data_service['dataService']->Delete($invoice);
                $this->inventory_model->insert_update('update', TBL_QUICKBOOK_INVOICE, array('is_deleted' => 1), $where);
            }
        }
        $this->session->set_flashdata('success', 'Invoice has been deleted successfully.');
        redirect('invoices');
    }

    /**
     * Get Item's data by its ID
     * @param --
     * @return HTML data
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_item_data_ajax_by_part_id() {
        if ($this->input->post()) {
            $part_id = $this->input->post('id');
            $location_id = $this->input->post('location_id');
            $itsfor = ($this->input->post('itsfor')) ? $this->input->post('itsfor') : '';
            $this->db->select('i.*,d.name as dept_name,v1.name as v1_name,it.total_quantity,il.quantity as location_quantity,il.location_id as selected_loation_id, gi.image as global_image');
            $this->db->from(TBL_USER_ITEMS . ' AS i');
            $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
            $this->db->join(TBL_VENDORS . ' AS v1', 'i.vendor_id=v1.id', 'left');
            $this->db->join(TBL_ITEM_LOCATION_DETAILS . ' AS il', 'il.item_id=i.id AND il.location_id =' . $location_id, 'left');
            $this->db->join(TBL_ITEMS . ' AS gi', 'i.referred_item_id=gi.id', 'left');
            $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
            $this->db->where(['i.business_user_id' => checkUserLogin('C'), 'i.is_delete' => 0]);
            $this->db->having('location_quantity > 0 AND location_quantity IS NOT NULL');

            if ($itsfor != '') {
                $this->db->where('i.id', $part_id);
                $result = $this->db->get();
                $data['viewArr'] = $result->row_array();
            } else {
                $this->db->like('i.part_no', $part_id);
                $this->db->or_like('i.upc_barcode', $part_id);
                $result = $this->db->get();
                $data['viewArr'] = $result->result_array();
            }
            if (!empty($data['viewArr'])) {
                $res = ['code' => 200, 'data' => $data['viewArr'], 'count' => count($data['viewArr'])];
            } else {
                $res = ['code' => 404, 'data' => null];
            }
            echo json_encode($res);
            die;
        }
    }

    /**
     * View Estimates
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function view($id = '') {
        $data['title'] = 'View Invoice';
        $record_id = base64_decode($id);
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['estimate'] = $this->estimate_model->get_estimate($record_id);
        $data['UserInfo'] = $this->users_model->get_profile(checkUserLogin('C'));
        $data['terms_condition'] = $this->estimate_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array()['invoice_terms_condition'];
        $data['taxes'] = $this->estimate_model->get_all_details(TBL_TAXES, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->result_array();
        $data['estimation_attachments'] = $this->estimate_model->get_all_details(TBL_ESTIMATION_ATTACHMENTS, array('estimation_id' => $record_id))->result_array();
        $data['fieldArr'] = $this->inventory_model->get_all_details(TBL_USER_SETTINGS_FIELD, array('is_deleted' => 0))->result_array();
        $data['vehicleArr'] = $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();
        $this->template->load('default_front', 'front/invoices/invoice_view', $data);
    }

    /**
     * View Estimates pdf for print
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function print_pdf($id = '') {
        $record_id = base64_decode($id);
        $this->pdf($record_id, 1);
    }

    /**
     * Generate Invoice PDF file
     * @param $id - String
     * @return --
     * @author JJP [Last Edited : 03/12/2020]
     */
    public function pdf_preview($id, $print = null) {
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['UserInfo'] = $this->users_model->get_profile(checkUserLogin('C'));
        $data['estimate'] = $this->estimate_model->get_estimate($id);
        $data['terms_condition'] = $this->estimate_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array()['invoice_terms_condition'];
        $data['taxes'] = $this->estimate_model->get_all_details(TBL_TAXES, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->result_array();
        $data['fieldArr'] = $this->inventory_model->get_all_details(TBL_USER_SETTINGS_FIELD, array('is_deleted' => 0))->result_array();
        $data['vehicleArr'] = $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();
        $data['company_info'] = $this->users_model->get_profile(checkUserLogin('C'));
        
        $this->db->select('s.name');
        $this->db->where(array(
            's.id' => $data['company_info']['state_id'],
            'u.username' => $data['company_info']['username']
        ));
        $this->db->join(TBL_STATES. ' AS s','u.state_id = s.id', 'left');
        $data['company_info']['state_name']  = $this->db->get(TBL_USERS.' AS u')->row_array();

        $html = $this->load->view('front/invoices/invoice_pdf_view', $data, true);
        
        if ($_SERVER['HTTP_HOST'] == 'alwaysreliablekeys.com' || $_SERVER['HTTP_HOST'] == 'clientapp.narola.online') {
            require_once FCPATH . 'vendor/autoload.php';
            $mpdf = new Mpdf();
        } else {
            require_once FCPATH . 'vendor 1/autoload.php';
            $mpdf = new \mPDF();
        }

        $mpdf->SetTitle("Invoice : " . $data['estimate']['estimate_id']);
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '', 
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = $data['estimate']['estimate_id'] . "_" . date('Ymdhis') . ".pdf";
        
        $mpdf->Output("./uploads/pdf/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/' . $filename;
        return $pdf;
    }

    /**
     * View Estimates
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function send_pdf($id = '', $edit = null) {
        $record_id = base64_decode($id);
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['estimate'] = $this->estimate_model->get_estimate($record_id);
        $data['UserInfo'] = $this->users_model->get_profile(checkUserLogin('C'));
        $data['taxes'] = $this->estimate_model->get_all_details(TBL_TAXES, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->result_array();
        $customer_id = $data['estimate']['customer_id']; 
        $data['customer_email'] = $this->estimate_model->get_all_details(TBL_CUSTOMER_EMAIL,array('customer_id' => $customer_id,'status' => 1))->result_array();
        $customer_email_array = [];
        $customer_email = explode(" ",$data['estimate']['email']);
        foreach ($data['customer_email'] as $key => $value) {
            array_push($customer_email_array, $value['customer_email']);
        }
        $marge_email = array_merge($customer_email_array,$customer_email);
        $unique_mail_array = array_unique($marge_email);
        $final_mail_array = (implode(",",$unique_mail_array));

        $url = base_url() . 'invoices/view/' . base64_encode($record_id);
        $pdf_url = base_url() . 'pdf/index/' . base64_encode($record_id) . '/' . base64_encode(checkUserLogin('C')) . '/' . base64_encode('invoice');
        if ($data['estimate']['email'] != '' || $data['estimate']['email'] != null) {
            $pdf = $this->pdf($record_id);

            $email_var = array(
                'title' => "Here's your invoice from ".checkUserLogin('B'),
                'estimate_id' => $data['estimate']['estimate_id'],
                'first_name' => $data['estimate']['cust_name'],
                'estimate_date' => date($data['format']['format'], strtotime($data['estimate']['estimate_date'])),
                'estimate_total' => $data['currency']['symbol'] . $data['estimate']['total'],
                'url' => $pdf_url,
                'UserInfo' => $data['UserInfo']
            );
            
            if (!empty($data['estimate']) && !empty($data['estimate']['signature_attachment']) && file_exists(FCPATH . 'uploads/signatures/' . $data['estimate']['signature_attachment'])) {
                $email_var['signature_image_url'] = base_url('uploads/signatures/' . $data['estimate']['signature_attachment']);
            }

            $message = $this->load->view('email_template/default_header.php', $email_var, true);
            $message .= $this->load->view('email_template/send_invoice.php', $email_var, true);
            $message .= $this->load->view('email_template/user_details_footer.php', $email_var, true);
            $email_array = array(
                'mail_type' => 'html',
                // 'from_mail_id' => checkUserLogin('E'),
                'from_mail_id' => $data['UserInfo']['email_id'],
                'from_mail_name' => $data['UserInfo']['full_name'],
                // 'to_mail_id' => $data['estimate']['email'],
                'to_mail_id' => $final_mail_array,
                'cc_mail_id' => '',
                'subject_message' => 'Invoice - ' . $data['estimate']['estimate_id'] . ' from ' . checkUserLogin('B'),
                'body_messages' => $message,
                'attachment' => $pdf
            );
            $email_send = common_email_send($email_array);
            if (is_null($edit)) {
                $this->session->set_flashdata('success', 'Invoice has been sent successfully.');
                redirect($url);
            }
        } else {
            $this->session->set_flashdata('error', 'Invoice has not customer email address. Please add it!!');
            if (is_null($edit)) {
                redirect($url);
            } else {
                redirect(base_url() . 'invoices/edit/' . base64_encode($record_id));
            }
        }
    }

    /**
     * View Estimates
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function pdf($id, $print = null) {
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['UserInfo'] = $this->users_model->get_profile(checkUserLogin('C'));
        $data['estimate'] = $this->estimate_model->get_estimate($id);
        $data['terms_condition'] = $this->estimate_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array()['invoice_terms_condition'];
        $data['taxes'] = $this->estimate_model->get_all_details(TBL_TAXES, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->result_array();
        $data['fieldArr'] = $this->inventory_model->get_all_details(TBL_USER_SETTINGS_FIELD, array('is_deleted' => 0))->result_array();
        $data['vehicleArr'] = $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();
        $data['company_info'] = $this->users_model->get_profile(checkUserLogin('C'));
        
        $this->db->select('s.name');
        $this->db->where(array(
            's.id' => $data['company_info']['state_id'],
            'u.username' => $data['company_info']['username']
        ));
        $this->db->join(TBL_STATES. ' AS s','u.state_id = s.id', 'left');
        $data['company_info']['state_name']  = $this->db->get(TBL_USERS.' AS u')->row_array();

        $html = $this->load->view('front/invoices/invoice_pdf_view', $data, true);
        
        if ($_SERVER['HTTP_HOST'] == 'www.alwaysreliablekeys.com') {
            require_once FCPATH . 'vendor/autoload.php';
        } else if ($_SERVER['HTTP_HOST'] == 'clientapp.narola.online') {
            require_once FCPATH . 'vendor/autoload.php';
        } else {
            require_once FCPATH . 'vendor 1/autoload.php';
        }

        // require_once FCPATH . 'vendor/autoload.php';
        
        // For Online Server(ClientApp)
        // $mpdf = new Mpdf();
        // Load Library 
        // For Off Line (Localhost)
        // $mpdf = new \mPDF();

        if ($_SERVER['HTTP_HOST'] == 'alwaysreliablekeys.com') {
           $mpdf = new Mpdf();
        } else if ($_SERVER['HTTP_HOST'] == 'clientapp.narola.online') {
            $mpdf = new Mpdf();
        } else {
           $mpdf = new \mPDF();
        }

        $mpdf->SetTitle("Invoice : " . $data['estimate']['estimate_id']);
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '', 
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = $data['estimate']['estimate_id'] . "_" . date('Ymdhis') . ".pdf";
        if (!is_null($print)) {
            $mpdf->SetJS('this.print();');
            $mpdf->Output($filename, "I");
        } else {
            $mpdf->Output("./uploads/pdf/" . $filename, "F");
            $pdf = base_url() . 'uploads/pdf/' . $filename;
            return $pdf;
        }
    }

    /**
     * View Estimates
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function view_pdf($id = '') {
        $record_id = base64_decode($id);
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['estimate'] = $this->estimate_model->get_estimate($record_id);
        $this->template->load('default_front', 'front/estimates/estimate_pdf_view', $data);
    }

    /**
     * This function is used to GET Item Location via ajax
     * @param  : ---
     * @return : json data
     * @author HPA [Last Edited : 10/07/2018]
     */
    public function get_item_location_ajax_data($id = null) {
        if ($this->input->post()) {
            $item_id = $this->input->post('item_id');
            if (!is_null($id)) {
                $location_items = $this->estimate_model->get_all_details(TBL_ITEM_LOCATION_DETAILS, ['is_deleted' => 0, 'location_id' => $id, 'item_id' => $item_id, 'is_active' => 1, 'quantity >' => 0])->row_array();
            } else {
                $location_items = $this->estimate_model->get_all_details(TBL_ITEM_LOCATION_DETAILS, ['is_deleted' => 0, 'item_id' => $item_id, 'is_active' => 1, 'quantity >' => 0])->result_array();
            }
            echo json_encode($location_items);
            die;
        }
    }

    /**
     * Get Item's data by its ID
     * @param --
     * @return HTML data
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_item_data_ajax_by_id($part_id, $location_id, $transponder_id, $type) {
        $data = $this->estimate_model->get_all_parts($part_id, $location_id, $transponder_id, $type);
        return $data;
    }

    public function get_item_data_ajax_by_id_multiple($part_id, $location_id, $transponder_id, $type) {
        $data = $this->estimate_model->get_all_parts_multiple($part_id, $location_id, $transponder_id, $type);
        return $data;
    }


    public function get_customer_details() {
        $customer_id = $this->input->post('customer_id');
        $customer_details = $this->estimate_model->get_all_details(TBL_CUSTOMERS, ['is_deleted' => 0, 'id' => $customer_id])->row_array();
        echo json_encode($customer_details);
        exit;
    }

    public function get_email_list() {
        $customer_id = $this->input->post('customer_id');
        $customer_email = $this->estimate_model->get_all_details(TBL_CUSTOMER_EMAIL, ['customer_id' => $customer_id])->result_array();
        echo json_encode($customer_email);
        exit;
    }

    /**
     * [add_edit_invoice_quickbook add data to quickbook]
     * @param [type] $id [description]
     * @author  KBH [Created : 12-08-2019] [last edited : 07-09-2019]
     */
    public function add_edit_invoice_quickbook($id = null, $flag = null, $copy_invoice = null, $estimate_copy_id =null)
    {
        if($id != '' || $id != NULL)
        {
            $invoice_id                 = base64_decode($id);
            $this->quickbook_invoice($invoice_id, $flag);
        }
        else if($this->input->post('unsync_id') != '' || $this->input->post('unsync_id') != null)
        {
            $unsync_id                  = $this->input->post('unsync_id');
            $invoice_ids                = explode(',', $unsync_id);
            foreach ($invoice_ids as $invoice_id) 
            {
                $this->quickbook_invoice($invoice_id,'', "ajax");
            }
            $this->session->set_flashdata('success', "Sync all the Invoices to Quickbooks successfully.");
            $data['success']            = "success";
            echo json_encode($data);
            exit();
        }
    }

    public function quickbook_invoice($invoice_id = NULL, $flag = NULL, $type = NULL)
    {
        $refer =  $this->agent->referrer();

        global $data_service;
        if(isset($data_service['accessTokenJson']))
        {
            $data_service['dataService']->throwExceptionOnError(true);

            $invoice_data                   = $this->estimate_model->get_estimate($invoice_id);
            $customer_detail                = $this->estimate_model->get_all_details(TBL_CUSTOMERS, ['id' => $invoice_data['customer_id'],'is_deleted' => 0])->row_array();

            $custome_quickbook_details      = $this->estimate_model->get_all_details(TBL_QUICKBOOK_CUSTOMER, ['customer_id' => $customer_detail['id'],'realmId' => $data_service['accessToken']->getRealmID()])->row_array();
            if($invoice_data['customer_id'] == 0)
            {
                $customer_config            = $this->inventory_model->get_all_details(TBL_QUICKBOOK_CONFIG, ['user_id' => $this->session->userdata('u_user_id'),'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

                if(empty($customer_config))
                {
                    $this->session->set_flashdata('error', 'Please select your customer for manual customer entry.');
                    redirect('quickbook');
                }
                else
                {
                    $customer_data              = $data_service['dataService']->Query("SELECT * FROM Customer Where id = '".$customer_config['customer_id']."'");
                }
            }
            else 
            {
                if(!empty($custome_quickbook_details))
                {
                    $customer_data              = $data_service['dataService']->Query("SELECT * FROM Customer Where id = '".$custome_quickbook_details['quickbook_id']."'");
                }
                else
                {
                    $this->session->set_flashdata('error', "Invoice number '" .$invoice_data['estimate_id'] ."' in Customer '". $customer_detail['first_name'] ." ". $customer_detail['last_name'] ."' are not available in your quickbook.");

                    if($type == "ajax")
                    {
                        $data['customer']       = "not available";
                        echo json_encode($data);
                        exit();
                    }
                    else
                    {
                        redirect('invoices');
                    }
                }
            }
            $parts_array                = array();
            $qbEntity                   = new QuickBooksOnline\API\Data\IPPInvoice;

            $qbEntity->CustomerRef      = $customer_data[0]->Id;
            $qbEntity->total            = $invoice_data['total'];
            $qbEntity->BillAddr         = $invoice_data['address'];
            $qbEntity->ShipAddr         = $invoice_data['address'];
            $qbEntity->PONumber         = $invoice_data['phone_number'];
            $qbEntity->TotalAmt         = $invoice_data['total'];
            $qbEntity->DueDate          = $invoice_data['expiry_date'];
            $qbEntity->DocNumber        = $invoice_data['estimate_id'];
            $qbEntity->PrivateNote      = $invoice_data['cust_name'];
            $qbEntity->BillEmail        = [];
            $qbEntity->BillEmail        = new \QuickBooksOnline\API\Data\IPPEmailAddress ([
                'Address' => $invoice_data['email'],
            ]);
 
            if($estimate_copy_id != '')
            {
                $estimate_quickbook_details = $this->estimate_model->get_all_details(TBL_QUICKBOOK_ESTIMATE, ['estimate_id' => $estimate_copy_id,'realmId' => $data_service['accessToken']->getRealmID()])->row_array();

                $qbEntity->LinkedTxn        = [];
                $qbEntity->LinkedTxn        = new \QuickBooksOnline\API\Data\IPPLinkedTxn ([
                    "TxnId" => $estimate_quickbook_details['quickbook_id'], 
                    "TxnType" => "Estimate"
                ]);
            }
            $qbEntity->Line                 = [];
            if(!empty($invoice_data))
            {
                if(count($invoice_data['parts']) != 0)
                {
                    foreach ($invoice_data['parts'] as $invoice) 
                    {
                        $item_quickbook_data            = $this->estimate_model->get_all_details(TBL_QUICKBOOK_ITEMS, ['item_id' => $invoice['part_id'], 'realmId' => $data_service['accessToken']->getRealmID(), 'is_deleted' => 0])->row_array();
                        $individual_part_tax = explode(',',$invoice_data['individual_part_tax']);
                        $individual_service_tax = explode(',',$invoice_data['individual_service_tax']);

                        $total_part_tax = array_sum($individual_part_tax);
                        $total_service_tax = array_sum($individual_service_tax);
                        $total_payable_tax = $total_part_tax + $total_service_tax;
                        if(!empty($item_quickbook_data))
                        {
                            $parts_data                 = $data_service['dataService']->Query("select * from Item where Id = '" . $item_quickbook_data['quickbook_id'] ."' MAXRESULTS 1");
                            if($parts_data[0]->UnitPrice != $invoice['rate'])
                            {
                                $part_unit_price        =  $invoice['rate'];
                            }
                            else
                            {
                                $part_unit_price        =  $parts_data[0]->UnitPrice;
                            }
                            $qbEntity->Line[]           = new \QuickBooksOnline\API\Data\IPPLine([
                                'LineNum'               => ++$i,
                                'Description'           =>$parts_data[0]->Description,
                                'Amount'                => $part_unit_price * $invoice['quantity'],
                                'DetailType'            => 'SalesItemLineDetail',
                                'SalesItemLineDetail'   => new \QuickBooksOnline\API\Data\IPPSalesItemLineDetail([
                                    'ItemRef'           => new \QuickBooksOnline\API\Data\IPPReferenceType([
                                        'value'         => $parts_data[0]->Id,
                                        'name'          => $parts_data[0]->Name,
                                    ]),
                                    'UnitPrice'         => $part_unit_price,
                                     'Qty'              => $invoice['quantity'],
                                    'TaxCodeRef'        => new \QuickBooksOnline\API\Data\IPPReferenceType([
                                        "value"         => 'TAX',
                                    ]),
                                ]),
                            ]);
                            $qbEntity->TxnTaxDetail = [];
                            $qbEntity->TxnTaxDetail = new \QuickBooksOnline\API\Data\IPPTxnTaxDetail ([
                                    "TotalTax"      => $total_payable_tax,
                               ]);
                        }
                        else
                        {
                            $this->session->set_flashdata('error', "Invoice number '" .$invoice_data['estimate_id'] ."' for part no '". $invoice['part_no'] ."' are not available in your quickbook.");
                            if($type == "ajax")
                            {
                                $data['parts']          = "not available";
                                echo json_encode($data);
                                exit();
                            }
                            else
                            {
                                redirect('invoices');
                            }
                        }
                    }
                }
                if(count($invoice_data['services']) != 0)
                {
                    foreach ($invoice_data['services'] as $invoice_service) 
                    {
                        $service_quickbook_data         = $this->estimate_model->get_all_details(TBL_QUICKBOOK_SERVICE, ['service_id' => $invoice_service['service_id'], 'realmId' => $data_service['accessToken']->getRealmID(), 'is_deleted' => 0])->row_array();

                        if(!empty($service_quickbook_data))
                        {
                            $service_data               = $data_service['dataService']->Query("select * from Item where Id = '" . $service_quickbook_data['quickbook_id'] ."' MAXRESULTS 1");
                            if($service_data[0]->UnitPrice != $invoice_service['rate'])
                            {
                                $UnitPrice = $invoice_service['rate'];
                            }
                            else
                            {
                                $UnitPrice = $service_data[0]->UnitPrice;
                            }
                            $qbEntity->Line[]           = new \QuickBooksOnline\API\Data\IPPLine([
                                'LineNum'               => ++$i,
                                'Description'           => $service_data[0]->Description,
                                'Amount'                => $UnitPrice,
                                'DetailType'            => 'SalesItemLineDetail',
                                'SalesItemLineDetail'   => new \QuickBooksOnline\API\Data\IPPSalesItemLineDetail([
                                    'ItemRef'           => new \QuickBooksOnline\API\Data\IPPReferenceType([
                                        'value'         => $service_data[0]->Id,
                                        'name'          => $service_data[0]->Name,
                                    ]),
                                    'UnitPrice'         => $UnitPrice,
                                ]),
                            ]);
                        }
                        else
                        {
                            $this->session->set_flashdata('error', "Invoice number '" .$invoice_data['estimate_id'] ."' for service_name '". $invoice_service['service_name'] ."' are not available in your quickbook.");
                            if($type == "ajax")
                            {
                                $data['services']           = "not available";
                                echo json_encode($data);
                                exit();
                            }
                            else
                            {
                               redirect('invoices');
                            }
                            $this->session->set_flashdata('error', "Service are not available in your quickbook.");
                            redirect('invoices');
                        }
                    }
                }
                if($invoice_data['shipping_charge'] != '')
                {
                    $qbEntity->Line[]           = new \QuickBooksOnline\API\Data\IPPLine([
                        'Amount'                => $invoice_data['shipping_charge'],
                        'DetailType'            => 'SalesItemLineDetail',
                        'SalesItemLineDetail'   => new \QuickBooksOnline\API\Data\IPPSalesItemLineDetail([
                            'ItemRef'           => 'SHIPPING_ITEM_ID',
                        ]),
                    ]);
                }
                try 
                {
                    if($flag == "update") 
                    {
                        $invoice_quickbook_details          = $this->estimate_model->get_all_details(TBL_QUICKBOOK_INVOICE, ['invoice_id' => $invoice_id,'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

                        $invoice_quickbook_attachment_details = $this->estimate_model->get_all_details(TBL_INVOICE_QUICKBOOK_ATTACHMENT, ['invoice_quickbook_id' => $invoice_quickbook_details['id'], 'is_deleted' => '0' ])->result_array();

                        if(!empty($invoice_quickbook_attachment_details))
                        {
                            foreach ($invoice_quickbook_attachment_details as $attachment) 
                            {
                                $attachables                =  $data_service['dataService']->FindbyId('attachable', $attachment['attachment_id']);
                                $objAttachable              = new IPPAttachable();
                                $objAttachable->Id          = $attachables->Id;
                                $objAttachable->FileName    = $attachables->FileName;
                                $objAttachable->Note        = 'Delete attachable';

                                $resultingObj               = $data_service['dataService']->Delete($objAttachable);
                                $this->estimate_model->insert_update('update', TBL_INVOICE_QUICKBOOK_ATTACHMENT, ['is_deleted' => 1], array('id' => $attachment['id']));

                                $invoice_quickbook_details  = $this->estimate_model->get_all_details(TBL_QUICKBOOK_INVOICE, ['invoice_id' => $invoice_id,'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

                                $this->estimate_model->insert_update('update', TBL_QUICKBOOK_INVOICE, ['update_count' => $invoice_quickbook_details['update_count'] + 1],array('id' => $invoice_quickbook_details['id']));
                            }
                        }
                        $invoice                    = $data_service['dataService']->FindbyId('invoice', $invoice_quickbook_details['quickbook_id']);
                        $invoice_quickbook_details  = $this->estimate_model->get_all_details(TBL_QUICKBOOK_INVOICE, ['invoice_id' => $invoice_id,'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

                        $qbEntity->Id               = $invoice->Id;
                        $qbEntity->SyncToken        = $invoice_quickbook_details['update_count'];
                        $resultingObj               = $data_service['dataService']->Update($qbEntity);

                        $this->estimate_model->insert_update('update', TBL_QUICKBOOK_INVOICE, ['update_count' => $invoice_quickbook_details['update_count'] + 1],array('id' => $invoice_quickbook_details['id']));
                    }
                    else
                    {
                        $resultingObj                       = $data_service['dataService']->Add($qbEntity);
                        $quickbook_invoice['realmId']       = $data_service['accessToken']->getRealmID();

                        $quickbook_invoice['invoice_id']    = $invoice_id;
                        $quickbook_invoice['quickbook_id']  = $resultingObj->Id;

                        $is_quickbook_invoice_saved         = $this->estimate_model->insert_update('insert', TBL_QUICKBOOK_INVOICE, $quickbook_invoice);
                    }

                    if($invoice_quickbook_details['payment_status'] == 0 && $invoice_data['payment_method_id'] != 0 && $invoice_data['payment_method_id'] != 4)
                    {
                        if($invoice_data['payment_method_id'] == 1)
                        {
                            $payment_method         = $data_service['dataService']->Query("SELECT * FROM PaymentMethod WHERE name like '%Credit Card%'");
                        }
                        else if($invoice_data['payment_method_id'] == 2)
                        {
                            $payment_method         = $data_service['dataService']->Query("SELECT * FROM PaymentMethod WHERE name like '%cash%'");
                        }
                        else if($invoice_data['payment_method_id'] == 3)
                        {
                            $payment_method         = $data_service['dataService']->Query("SELECT * FROM PaymentMethod WHERE name like '%Check%'");
                        }
                        if(!empty($payment_method))
                        {
                           
                            $payment                        = [
                                "CurrencyRef"               => [
                                        "value"             => "USD",
                                        "name"              => "United States Dollar"
                                    ],

                                    "CustomerRef"           => [
                                      "value"               => $customer_data[0]->Id
                                    ], 
                                    "PaymentMethodRef"      => [
                                        "value"             => $payment_method[0]->Id
                                    ],
                                    "PaymentRefNum"         => $invoice_data['payment_reference'],
                                    "TotalAmt"              => $resultingObj->TotalAmt, 
                                    "Line"                  =>  [
                                        "Amount"            => $resultingObj->TotalAmt,
                                        "LinkedTxn"         =>
                                      [
                                        "TxnId"             => $resultingObj->Id, 
                                        "TxnType"           => "Invoice"
                                      ],
                                    ],
                                    "ProcessPayment"        => false, 
                                ];

                            $payment_theResourceObj     = Payment::create($payment);
                            $payment_resultingObj       = $data_service['dataService']->Add($payment_theResourceObj);

                            $invoice_quickbook_details  = $this->estimate_model->get_all_details(TBL_QUICKBOOK_INVOICE, ['invoice_id' => $invoice_id,'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();
                         
                            $this->estimate_model->insert_update('update', TBL_QUICKBOOK_INVOICE, ['payment_status' => 1, 'update_count' => $invoice_quickbook_details['update_count'] + 1],array('id' => $invoice_quickbook_details['id']));
                        }
                        else
                        {
                           $this->session->set_flashdata('error', "Payment is not done successfully.Please add Payment methods into Quickbooks");
                        }
                    }
                    if($invoice_data['signature_attachment'] != '')
                    {
                        $invoice_signature              = site_url(SIGNATURE_IMAGE_PATH . $invoice_data['signature_attachment']);
                        $ext                            = strtolower( pathinfo($invoice_data['signature_attachment'], PATHINFO_EXTENSION));    
                        $imageBase64 = array();
                        $file                           =  $this->echoBase64($invoice_signature);
                        $imageBase64['image/jpeg']      = $file;
                        $sendMimeType                   = "image/jpeg";

                        $randId                         = rand();
                        $entityRef                      = new IPPReferenceType(array('value'=>$resultingObj->Id, 'type'=>'Invoice'));
                        $attachableRef                  = new IPPAttachableRef(array('EntityRef'=>$entityRef));

                        $objAttachable                  = new IPPAttachable();
                        $objAttachable->FileName        = $randId . "." . $ext;
                        $objAttachable->AttachableRef   = $attachableRef;
                        $objAttachable->Category        = 'Image';
                        $objAttachable->Note            = 'Edit';
                        $objAttachable->Tag             = 'Tag_' . $randId;

                        $resultObj                                      = $data_service['dataService']->Upload(base64_decode($imageBase64[$sendMimeType]), $objAttachable->FileName, $sendMimeType, $objAttachable);  
                        $quickbook_invoice_attachment['attachment_id '] = $resultObj->Attachable->Id;

                        $invoice_quickbook_details      = $this->estimate_model->get_all_details(TBL_QUICKBOOK_INVOICE, ['invoice_id' => $invoice_id,'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

                        $this->estimate_model->insert_update('update', TBL_QUICKBOOK_INVOICE, ['update_count' => $invoice_quickbook_details['update_count'] + 1],array('id' => $invoice_quickbook_details['id']));     

                        if($flag == "update") 
                        {
                            $quickbook_invoice_attachment['invoice_quickbook_id '] = $invoice_quickbook_details['id'];
                        }
                        else 
                        {
                            $quickbook_invoice_attachment['invoice_quickbook_id '] = $is_quickbook_invoice_saved;
                        }

                        $is_quickbook_invoice_attachment_saved  = $this->estimate_model->insert_update('insert', TBL_INVOICE_QUICKBOOK_ATTACHMENT, $quickbook_invoice_attachment);
                    }
                   
                    $estimate_data                              = $this->db->query("SELECT estimation_id,file_name FROM ".TBL_ESTIMATION_ATTACHMENTS." WHERE estimation_id = '".$invoice_id."' ")->result_array();

                    if(!empty($estimate_data))
                    {
                        foreach ($estimate_data as $image) 
                        {
                            $invoice_image                      = base_url() . ESTIMATE_IMAGE_PATH . "/" . $image['file_name'];
                            $ext                                = strtolower( pathinfo($image['file_name'], PATHINFO_EXTENSION));    
                            $imageBase64                        = array();
                            $file                               =  $this->echoBase64($invoice_image);
                            $imageBase64['image/jpeg'] = $file;
                            $sendMimeType                       = "image/jpeg";

                            $randId = rand();
                            $entityRef                          = new IPPReferenceType(array('value'=>$resultingObj->Id, 'type'=>'Invoice'));
                            $attachableRef                      = new IPPAttachableRef(array('EntityRef'=>$entityRef));

                            $objAttachable                      = new IPPAttachable();
                            $objAttachable->FileName            = $randId . "." . $ext;
                            $objAttachable->AttachableRef       = $attachableRef;
                            $objAttachable->Category            = 'Image';
                            $objAttachable->Tag                 = 'Tag_' . $randId;

                            $resultObj                      = $data_service['dataService']->Upload(base64_decode($imageBase64[$sendMimeType]), $objAttachable->FileName, $sendMimeType, $objAttachable);

                            $invoice_quickbook_details      = $this->estimate_model->get_all_details(TBL_QUICKBOOK_INVOICE, ['invoice_id' => $invoice_id,'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();


                            $this->estimate_model->insert_update('update', TBL_QUICKBOOK_INVOICE, ['update_count' => $invoice_quickbook_details['update_count'] + 1],array('id' => $invoice_quickbook_details['id'])); 

                            if($flag == "update") 
                            {
                                $quickbook_invoice_attachment['invoice_quickbook_id '] = $invoice_quickbook_details['id'];
                            }
                            else 
                            {
                                $quickbook_invoice_attachment['invoice_quickbook_id '] = $is_quickbook_invoice_saved;
                            }

                            $quickbook_invoice_attachment['attachment_id '] = $resultObj->Attachable->Id;

                            $is_quickbook_invoice_attachment_saved          = $this->estimate_model->insert_update('insert', TBL_INVOICE_QUICKBOOK_ATTACHMENT, $quickbook_invoice_attachment);
                        }
                    }
                    $error              = $data_service['dataService']->getLastError();
                    if ($error) 
                    {
                        $this->session->set_flashdata('error', $error->getHttpStatusCode());
                        $this->session->set_flashdata('error', $error->getOAuthHelperError());
                        $this->session->set_flashdata('error', $error->getResponseBody());
                        redirect('invoices');
                    }
                    else
                    {
                        if ($flag == "update")
                        {
                            $this->session->set_flashdata('success', 'Invoice has been updated successfully also in Quickbook.');  
                        } 
                        else
                        {
                            if($flag == 1)
                            {
                                $this->session->set_flashdata('success', 'Invoice has been created successfully also in Quickbook.');    
                            }
                            else
                            {
                                if($type != "ajax")
                                {
                                    $this->session->set_flashdata('success', 'Invoice has been created in Quickbook successfully.');
                                    if(!empty($refer)){
                                        redirect($refer);
                                    }else{
                                        redirect('invoices');
                                    }
                                }
                            }
                        }
                    }
                } 
                catch (Exception $e) 
                {
                    echo $e->getMessage();
                    $this->session->set_flashdata('error', $e->getMessage());
                    if($type == "ajax")
                    {
                        $data['error']      = "error";
                        echo json_encode($data);
                        exit();
                    }
                    else
                    {
                        redirect('invoices');
                    }
                }
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'Please login to Quickbook! Your session has been exprire.');
            if($type == "ajax")
            {
                $data['session']            = "exprired";
                echo json_encode($data);
                exit();
            }
            else
            {
                redirect('invoices');
            }
        }
    }
    public function add_customer_id()
    {
        $estimate_data      = $this->dashboard_model->get_all_details(TBL_ESTIMATES, array('is_deleted' => 0))->result_array();

        foreach ($estimate_data as $estimate) 
        {
            $customer_name = explode(" ",$estimate['cust_name']);

            $customer_data = $this->db->query("SELECT * FROM customers WHERE first_name = '". $customer_name[0] ."' AND last_name = '". $customer_name[1 ] ."'")->result_array();

            foreach ($customer_data as $customer) {
                if($customer_name[0] == $customer['first_name'] && $customer_name[1] == $customer['last_name'])
                {
                    $this->db->query("UPDATE `estimates` SET `customer_id` = '".$customer['id']."' WHERE `estimates`.`id` = '".$estimate['id']."'");   
                }
            }
        }
    }


    /**
     * [insert_customer_uniq_name update customer_id into estimate table for Quickbook]
     * @return [type] [description]
     */
    public function insert_customer_uniq_name()
    {
        $customer_data      = $this->dashboard_model->get_all_details(TBL_CUSTOMERS, array('is_deleted' => 0))->result_array();

        $estimate_data      = $this->dashboard_model->get_all_details(TBL_ESTIMATES, array('is_deleted' => 0))->result_array();

        foreach ($estimate_data as $estimate) 
        {
            foreach ($customer_data as $cutsomer) 
            {
                if($cutsomer['email'] === $estimate['email'])
                {
                    $this->db->query("UPDATE estimates SET customer_id = '".$cutsomer['id']."' WHERE email = '".$estimate['email']."'");
                    // echo $i++ ." ==> " . $this->db->last_query() . "<br>";
                    // die;
                }
            }    
        }
    }


    /**
     * [get_low_inventory push_notification for low inventrory]
     * @param  [type] $item_id [description]
     * @author KBH 13-11-2019
     */
    public function get_low_inventory($item_id = NULL, $limit = NULL)
    {
        $this->db->select(TBL_ITEM_LOCATION_DETAILS .'.id,'.TBL_ITEM_LOCATION_DETAILS.'.item_id,SUM(quantity) as total_quantity,' . TBL_USER_ITEMS . '.low_inventory_limit');
        // $this->db->select(TBL_ITEM_LOCATION_DETAILS .'.id,'.TBL_ITEM_LOCATION_DETAILS.'.item_id,SUM(quantity) as total_quantity');
        $this->db->from(TBL_ITEM_LOCATION_DETAILS);
        $this->db->join(TBL_USER_ITEMS, TBL_USER_ITEMS . '.id = ' . TBL_ITEM_LOCATION_DETAILS . '.item_id', 'LEFT');
        $this->db->where('is_deleted', 0);
        $this->db->where('item_id ', $item_id);
        $this->db->group_by('item_id');
        if($limit == '' && $limit == NULL)
        {
            $this->db->having('SUM(quantity) <= 0'); 
        }
        $result         = $this->db->get()->row_array(); 
        return $result;
    }

}

/* End of file Inventory.php */
/* Location: ./application/controllers/Inventory.php */