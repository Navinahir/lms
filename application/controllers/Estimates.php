<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . '/Library/QuickBook/autoload.php';
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Estimate;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\TaxRate;
use QuickBooksOnline\API\Facades\TaxService;
use QuickBooksOnline\API\Data\IPPReferenceType;
use QuickBooksOnline\API\Data\IPPAttachableRef;
use QuickBooksOnline\API\Data\IPPAttachable;
use Mpdf\Mpdf;

class Estimates extends MY_Controller {

    public function __construct() {
        global $data_service;
        parent::__construct();
        $this->load->model(array('admin/estimate_model', 'admin/users_model', 'admin/dashboard_model', 'admin/inventory_model','admin/invoice_model'));
        $this->load->library('m_pdf');
        $data_service = $this->data_service();
    }
    
    /**
     * Display All items 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function display_estimates() {
        $data['title'] = 'List of Estimates';
        $this->template->load('default_front', 'front/estimates/estimates_display', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_items_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->estimate_model->get_estimates_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->estimate_model->get_estimates_data('result');
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
            $items[$key]['responsive'] = '';
            $items[$key]['quickbooks'] = $this->estimate_quickbook_status($val['id']);
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
    public function estimate_trash() {
        $data['title'] = 'Estimates Trash';
        $this->template->load('default_front', 'front/estimates/estimates_trash', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 09/10/2020]
     */
    public function get_items_data_trash() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->estimate_model->get_estimates_data_trash('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->estimate_model->get_estimates_data_trash('result');
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
            $this->session->set_flashdata('success', 'Estimate has been deleted successfully.');
            $data = $this->estimate_model->insert_update('update',TBL_ESTIMATES,$dataArr,$where);
            echo json_encode($data);
        }
    }

    /**
     * Recover selected estimate
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 09/10/2020]
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
            $this->session->set_flashdata('success', 'Estimate has been recoverd successfully.');
            $data = $this->estimate_model->insert_update('update',TBL_ESTIMATES,$dataArr,$where);
            echo json_encode($data);
        }
    }

    /**
     * Edit Items
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 20/07/2019]
     */
    public function edit_estimates($id = null) {    
        if(!empty($_REQUEST['multipul_est_id']) && !empty($_REQUEST['multipul_est_loc_id']) && !empty($_REQUEST['multipul_est_tra_id']))
        {
            $multipul_est_id = implode(',',$_REQUEST['multipul_est_id']);
            $multipul_est_loc_id = $_REQUEST['multipul_est_loc_id'];
            $multipul_est_tra_id = $_REQUEST['multipul_est_tra_id'];
        } 

        $e_id = ($this->input->get('id')) ? $this->input->get('id', TRUE) : '';
        $total_estimation = $this->estimate_model->get_all_details(TBL_ESTIMATES, array('business_user_id' => checkUserLogin('C'), 'is_invoiced' => 0))->num_rows();
        $est_id = 0;
        $data = array(
            'title' => 'Add Estimate',
            'format' => MY_Controller::$date_format,
            'currency' => MY_Controller::$currency,
            'estimate_id' => 'EST-' . str_pad(($total_estimation + 1), 6, '0', STR_PAD_LEFT),
            'users' => $this->users_model->total_users(1),
            'companyArr' => $this->estimate_model->get_all_details(TBL_COMPANY, array('is_delete' => 0, 'status' => 'active'))->result_array(),
            'yearArr' => $this->estimate_model->get_all_details(TBL_YEAR, array('is_delete' => 0),array(array('field' => 'name','type' => 'asc')))->result_array(),
            'colors' => $this->estimate_model->get_all_details(TBL_VEHICLE_COLORS, array('is_deleted' => 0))->result_array(),
            'taxes' => $this->estimate_model->get_all_details(TBL_TAXES, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->result_array(),
            'locations' => $this->estimate_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array(),
            'services' => $this->estimate_model->get_all_details(TBL_SERVICES, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C')])->result_array(),
            'customers' => $this->estimate_model->get_all_details(TBL_CUSTOMERS, ['is_deleted' => 0, 'added_by' => checkUserLogin('C')])->result_array(),
            'vehicleArr' => $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array(),
            'fieldArr' => $this->inventory_model->get_all_details(TBL_USER_SETTINGS_FIELD, array('is_deleted' => 0))->result_array(),
        );

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

        // Multipul estimation
        if ($multipul_est_id != '') {
            $r_id = $multipul_est_id;
            $location_id = $multipul_est_loc_id;
            $transponder_id = $multipul_est_tra_id;
            $type = $this->input->get('type') ? base64_decode($this->input->get('type')) : null;
            $data['itemArr_multi'] = $this->get_item_data_ajax_by_id_multiple($r_id, $location_id, $transponder_id, $type)['itemArr_multi'];
            if (!empty($data['itemArr_multi']))
            $data['location_id'] = $location_id;
        }

        if (!is_null($id)) {
            $record_id = base64_decode($id);
            $est_id = $record_id;
            $data['title'] = 'Edit Estimate';
            $data['dataArr'] = $this->estimate_model->get_estimate($record_id);
            $data['estimation_attachments'] = $this->estimate_model->get_all_details(TBL_ESTIMATION_ATTACHMENTS, array('estimation_id' => $record_id))->result_array();

            if (!empty($data['dataArr'])) {
                $data['parts'] = array_column($data['dataArr']['parts'], 'part_id');
                $data['Allservices'] = array_column($data['dataArr']['services'], 'service_id');
            }
        }
        if ($this->input->post()) {
            // $this->form_validation->set_rules('txt_cust_name', 'Customer Name', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('sales_person', 'Sales Person', 'trim|required|max_length[100]');
            if ($this->form_validation->run() == true) {
                $updateArr = array(
                    'business_user_id' => checkUserLogin('C'),
                    'estimate_id' => htmlentities($this->input->post('hidden_estimate_id')),
                    'cust_name' => htmlentities($this->input->post('txt_cust_name')),
                    'phone_number' => ($this->input->post('txt_phone_number') != '') ? htmlentities($this->input->post('txt_phone_number')) : null,
                    'email' => ($this->input->post('txt_email') != '') ? htmlentities($this->input->post('txt_email')) : null,
                    'address' => ($this->input->post('txt_address') != '') ? htmlentities($this->input->post('txt_address')) : null,
                    'notes' => ($this->input->post('txt_notes') != '') ? htmlentities($this->input->post('txt_notes')) : null,
                    'estimate_date' => $this->input->post('estimate_date_submit'),
                    // 'estimate_date' => date('Y/m/d',strtotime($this->input->post('hidden_estimate_date'))),
                    'expiry_date' => ($this->input->post('expiry_date_submit') != '') ? ($this->input->post('expiry_date_submit')) : null,
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
                    'tax_rate' => $this->input->post('final_tax_rate'),
                    'sub_total' => $this->input->post('sub_total'),
                    'total' => $this->input->post('total'),
                    'shipping_charge' => $this->input->post('shipping_charge'),
                    'shipping_display_status' => $this->input->post('shipping_status'),
                    'individual_part_tax' => implode(",",$this->input->post('individual_part_tax')),
                    'individual_service_tax' => implode(",",$this->input->post('individual_service_tax')),
                );
                // Status toggle
                if(!empty($this->input->post('invoice_type')) && $this->input->post('invoice_type') == 1)
                {
                    $updateArr['is_sent'] = 1;
                    $updateArr['is_save_draft'] = 0;
                } else {
                    $updateArr['is_sent'] = 0;
                    $updateArr['is_save_draft'] = 1;
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
                    if ($this->input->post('hidden_part_id')) {
                        foreach ($this->input->post('hidden_part_id') as $k => $p):
                            if (array_key_exists($k, $data['dataArr']['parts'])) {
                                $parts = $data['dataArr']['parts'][$k];

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

                                if (($key = array_search($p, $existing_part)) !== false) {
                                    unset($existing_part[$key]);
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
                                        'modified_date' => date('Y-m-d h:i:s')
                                    ];
                                    $this->estimate_model->insert_update('update', TBL_ESTIMATE_PARTS, $edit_parts, array('estimate_id' => $record_id, 'id' => $parts['id']));
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
                                        'created_date' => date('Y-m-d h:i:s')
                                    ];
                                    $this->estimate_model->insert_update('insert', TBL_ESTIMATE_PARTS, $add_parts);
                                }
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
                                    'created_date' => date('Y-m-d h:i:s')
                                ];
                                $this->estimate_model->insert_update('insert', TBL_ESTIMATE_PARTS, $add_parts);
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
                            $check_services = $data['dataArr']['services'];
                            if($p != null)
                            {
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
                    
                    $this->session->set_flashdata('success', 'Estimate has been updated successfully.');
                } else {
                    // $updateArr['is_save_draft'] = ($this->input->post('save_draft')) ? 1 : 0;
                    // $updateArr['is_sent'] = ($this->input->post('save_send')) ? 1 : 0;
                    if(!empty($this->input->post('invoice_type')) && $this->input->post('invoice_type') == 1){
                        $updateArr['is_sent'] = 1;
                        $updateArr['is_save_draft'] = 0;
                    }else{
                        $updateArr['is_sent'] = 0;
                        $updateArr['is_save_draft'] = 1;
                    }
                    $updateArr['created_date'] = date('Y-m-d h:i:s');
                    $updateArr['added_by'] = checkUserLogin('I');

                    if($this->input->post('customer_id') == "")
                    {
                        $updateArr['customer_id'] = "";
                    } else { 
                        $updateArr['customer_id'] = $this->input->post('customer_id');
                    }
                    $estimate_id = $this->estimate_model->insert_update('insert', TBL_ESTIMATES, $updateArr);
                    if ($estimate_id){
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
                                    'estimate_id' => $estimate_id,
                                    'part_id' => $p,
                                    'description' => $this->input->post('description')[$k],
                                    'item_note' => $this->input->post('item_note')[$k],
                                    'location_id' => $this->input->post('location_id')[$k],
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
                            endforeach;
                            $this->estimate_model->batch_insert_update('insert', TBL_ESTIMATE_PARTS, $parts);
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
                            // pr($create_hidden_tax_amount);
                            foreach($create_hidden_tax_amount as $key => $value){
                                $new_service_tax_amount[$s.'_'.$create_service_id[$s]] = implode(',',$value);
                                $s++;    
                            }
                            foreach ($this->input->post('service_id') as $k => $p):
                                if ($p != '') {

                                    $services[$k] = [
                                        'estimate_id' => $estimate_id,
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
                        $last_estimate = $this->estimate_model->get_estimates_from_id(['e.id' => $estimate_id]);
                        if($last_estimate['business_user_id'] != $last_estimate['added_by'])
                        {
                            $notification['business_user_id'] = $last_estimate['business_user_id'];
                            $notification['added_by'] = $last_estimate['added_by'];
                            $notification['enum_id'] = $estimate_id;
                            $notification['estimate_invoice'] = '0';
                            $notification_is_saved = $this->estimate_model->insert_update('insert', TBL_NOTIFICATION, $notification);
                            $this->session->set_userdata(['estimate_notification' => 1]);
                        }
                    }
                    $this->session->set_flashdata('success', 'Estimate has been created successfully.');
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
                }
                
                if (isset($_SESSION['sessionAccessToken'])) 
                {
                    if (!is_null($id)) 
                    {
                        $session_accesToken = $_SESSION['sessionAccessToken'];
                        $estimate_quickbook_details = $this->estimate_model->get_all_details(TBL_QUICKBOOK_ESTIMATE, ['estimate_id' => $record_id,'realmId' => $session_accesToken-> getRealmID()])->row_array();
                        if(!empty($estimate_quickbook_details))
                        {
                            $this->add_edit_estimate_quickboook($id, $flag = "update");
                        }
                    }
                    else
                    {
                        $estimate_id = base64_encode($estimate_id);
                        $this->add_edit_estimate_quickboook($estimate_id, $flag=1);
                    }
                }
                
                if ($this->input->post('save_send') == 1) {
                    $this->session->set_flashdata('success','Estimate has been created and sent successfully.');
                    
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
                        $last_estimate_id = $this->estimate_model->get_all_details(TBL_ESTIMATES,array('is_deleted' => 0,'added_by' => $this->session->userdata('u_user_id'),'is_invoiced' => 0), array(array('field' => 'id', 'type' => 'desc')),array('l1' => 1, 'l2' => 0))->row_array();
                        $final_estimate_id = base64_encode($last_estimate_id['id']);
                        $print_back_url = base_url('estimates/edit/'.$final_estimate_id);
                        redirect($print_back_url, 'refresh');    
                    }
                }

                if($referred_from = $this->session->userdata('referred_from'))
                {
                    redirect($referred_from, 'refresh');    
                } else { 
                    redirect('estimates');
                }
            }
        }

        $this->template->load('default_front', 'front/estimates/estimate_add', $data);
    }

    /**
     * Delete Items
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function delete_estimates($id = '') {
        $record_id = base64_decode($id);
        $this->estimate_model->insert_update('update', TBL_ESTIMATES, array('is_deleted' => 1), array('id' => $record_id));
        global $data_service;
        if(isset($data_service['accessTokenJson']))
        {
            $where = array(
                'estimate_id' => $record_id,
                'realmId' => $data_service['accessToken']-> getRealmID(),
            );

            $estimate_quickbook_details = $this->estimate_model->get_all_details(TBL_QUICKBOOK_ESTIMATE, $where)->row_array();
            if(!empty($estimate_quickbook_details))
            {
                $invoice = $data_service['dataService']->FindbyId('estimate', $estimate_quickbook_details['quickbook_id']);
                $resultingObj = $data_service['dataService']->Delete($invoice);
                $this->inventory_model->insert_update('update', TBL_QUICKBOOK_ESTIMATE, array('is_deleted' => 1), $where);
            }
        }
        $this->session->set_flashdata('success', 'Estimate has been deleted successfully.');
        redirect('estimates');
    }

    /**
     * Recoverd Estimate 
     * @param $id - String
     * @return --
     * @author JJP [Last Edited : 13/10/2020]
     */
    public function trash_recover_estimates($id = '') {
        $record_id = base64_decode($id);
        $this->estimate_model->insert_update('update', TBL_ESTIMATES, array('is_deleted' => 0), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Estimate has been recoverd successfully.');
        redirect('estimates');
    }

    /**
     * Delete Estimate trash record
     * @param $id - String
     * @return --
     * @author JJP [Last Edited : 03/02/2018]
     */
    public function trash_delete_estimates($id = '') {
        $record_id = base64_decode($id);
        $this->estimate_model->insert_update('update', TBL_ESTIMATES, array('is_deleted' => 2), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Estimate has been deleted successfully.');
        redirect('estimates/estimate_trash');
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
            $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
            $this->db->join(TBL_ITEMS . ' AS gi', 'i.referred_item_id=gi.id', 'left');
            $this->db->where(['i.business_user_id' => checkUserLogin('C'), 'i.is_delete' => 0]);
            // $this->db->having('location_quantity > 0 AND location_quantity IS NOT NULL');
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
        $data['title'] = 'View Estimate';
        $record_id = base64_decode($id);
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['UserInfo'] = $this->users_model->get_profile(checkUserLogin('C'));
        $data['estimate'] = $this->estimate_model->get_estimate($record_id);
        $data['terms_condition'] = $this->estimate_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array()['estimate_terms_condition'];
        $data['taxes'] = $this->estimate_model->get_all_details(TBL_TAXES, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->result_array();
        $data['estimation_attachments'] = $this->estimate_model->get_all_details(TBL_ESTIMATION_ATTACHMENTS, array('estimation_id' => $record_id))->result_array();
        $data['fieldArr'] = $this->inventory_model->get_all_details(TBL_USER_SETTINGS_FIELD, array('is_deleted' => 0))->result_array();
        $data['vehicleArr'] = $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();
        $this->template->load('default_front', 'front/estimates/estimate_view', $data);
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
     * Generate Estimate PDF file
     * @param $id - String
     * @return --
     * @author JJP [Last Edited : 03/12/2020]
     */
    public function pdf_preview($id, $print = null) {
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['UserInfo'] = $this->users_model->get_profile(checkUserLogin('C'));
        $data['estimate'] = $this->estimate_model->get_estimate($id);
        $data['terms_condition'] = $this->estimate_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array()['estimate_terms_condition'];
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
        
        $html = $this->load->view('front/estimates/estimate_pdf_view', $data, true);
        
        if ($_SERVER['HTTP_HOST'] == 'alwaysreliablekeys.com' || $_SERVER['HTTP_HOST'] == 'clientapp.narola.online') {
            require_once FCPATH . 'vendor/autoload.php';
            $mpdf = new Mpdf();
        } else {
            require_once FCPATH . 'vendor 1/autoload.php';
            $mpdf = new \mPDF();
        }

        $mpdf->SetTitle("Estimation : " . $data['estimate']['estimate_id']);
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
       
        $url = base_url() . 'estimates/view/' . base64_encode($record_id);
        $pdf_url = base_url() . 'pdf/index/' . base64_encode($record_id) . '/' . base64_encode(checkUserLogin('C')) . '/' . base64_encode('estimate');
        if ($data['estimate']['email'] != '' || $data['estimate']['email'] != null) {
            $pdf = $this->pdf($record_id);
            $email_var = array(
                'title' => "Here's your estimate from ".checkUserLogin('B'),
                'estimate_id' => $data['estimate']['estimate_id'],
                'first_name' => $data['estimate']['cust_name'],
                'estimate_date' => date($data['format']['format'], strtotime($data['estimate']['estimate_date'])),
                'estimate_total' => $data['currency']['symbol'] . $data['estimate']['total'],
                'url' => $pdf_url,
                'user_info' => $data['UserInfo']
            );

            if (!empty($data['estimate']) && !empty($data['estimate']['signature_attachment']) && file_exists(FCPATH . 'uploads/signatures/' . $data['estimate']['signature_attachment'])) {
                $email_var['signature_image_url'] = base_url('uploads/signatures/' . $data['estimate']['signature_attachment']);
            }

            $message = $this->load->view('email_template/default_header.php', $email_var, true);
            $message .= $this->load->view('email_template/send_estimate.php', $email_var, true);
            $message .= $this->load->view('email_template/user_details_footer.php', $email_var, true);

            $email_array = array(
                'mail_type' => 'html',
                // 'from_mail_id' => checkUserLogin('E'),
                'from_mail_id' => $data['UserInfo']['email_id'],
                'from_mail_name' => 'ARK Team',
                'to_mail_id' => $data['estimate']['email'],
                // 'to_mail_id' => $final_mail_array,
                'cc_mail_id' => '',
                'subject_message' => 'Estimate - ' . $data['estimate']['estimate_id'] . ' is awaiting your approval',
                'body_messages' => $message,
                'attachment' => $pdf
            );

            $email_send = common_email_send($email_array);
            if (is_null($edit)) {
                $this->session->set_flashdata('success', 'Estimate has been sent successfully.');
                redirect($url);
            }
        } else {
            $this->session->set_flashdata('error', 'Estimate has not customer email address. Please add it!!');
            if (is_null($edit)) {
                redirect($url);
            } else {
                redirect(base_url() . 'estimates/edit/' . base64_encode($record_id));
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
        // echo $print; die();
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;

        $data['UserInfo'] = $this->users_model->get_profile(checkUserLogin('C'));
        $data['estimate'] = $this->estimate_model->get_estimate($id);
        $data['terms_condition'] = $this->estimate_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array()['estimate_terms_condition'];
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
        
        $html = $this->load->view('front/estimates/estimate_pdf_view', $data, true);
        
        if ($_SERVER['HTTP_HOST'] == 'www.alwaysreliablekeys.com') {
            require_once FCPATH . 'vendor/autoload.php';
        } else if ($_SERVER['HTTP_HOST'] == 'clientapp.narola.online') {
            require_once FCPATH . 'vendor/autoload.php';
        } else {
            require_once FCPATH . 'vendor 1/autoload.php';
        }

        // require_once FCPATH . 'vendor/autoload.php';
        
        // For Online Server(ClientApp & Live)
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
           // $mpdf = new \mPDF('utf-8', array(190,236));
        }

        $mpdf->SetTitle("Estimation : " . $data['estimate']['estimate_id']);
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
            
            // Update Send Status On Estimate List Page
            $setdstatus=array(
            'is_save_draft'=> 0,
            'is_sent'=> 2
            );
            $this->estimate_model->updatestatus($id,$setdstatus);
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
        $data['terms_condition'] = $this->estimate_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array()['estimate_terms_condition'];
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

    /**
     * Get Item's data by its ID
     * @param --
     * @return HTML data
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_part_data() {
        if ($this->input->post()) {
            $data = $this->estimate_model->get_all_parts_by_make($this->input->post());
            $data['code'] = 200;
            echo json_encode($data);
            die;
        }
    }

    public function get_item_data_id() {
        $item_id = '';
        $location_items = [];

        if (!empty($this->input->post('part_no'))) {
            $part_no = trim($this->input->post('part_no'));
            $this->db->select('*');
            $this->db->from(TBL_USER_ITEMS . ' AS i');
            $this->db->where(['i.business_user_id' => checkUserLogin('C'), 'i.is_delete' => 0]);
            $this->db->like('i.part_no', $part_no);
            $result = $this->db->get();
            $get_items = $result->row_array();

            if (!empty($get_items)) {
                $item_id = $get_items['id'];

                $location_items = $this->estimate_model->get_all_details(TBL_ITEM_LOCATION_DETAILS, ['is_deleted' => 0, 'item_id' => $item_id, 'is_active' => 1, 'quantity >' => 0])->result_array();
            }
        }

        $data['item_id'] = $item_id;
        $data['location_item'] = $location_items;
        echo json_encode($data);
        exit;
    }

    public function remove_attchment($id) {
        $is_delete = 0;
        $record_id = base64_decode($id);

        $attachment_details = $this->estimate_model->get_all_details(TBL_ESTIMATION_ATTACHMENTS, ['id' => $record_id])->row_array();

        if (!empty($attachment_details) && sizeof($attachment_details) > 0) {
            unlink('uploads/attachments/' . $attachment_details['file_name']);

            $this->db->where('id', $record_id);
            $is_deleted = $this->db->delete(TBL_ESTIMATION_ATTACHMENTS);

            if ($is_deleted) {
                $is_delete = 1;
            }
        }

        echo $is_delete;
        exit;
    }


    /**
     * [add_edit_estimate_quickboook add data to quickbook]
     * @param [type] $id [description]
     * @author  KBH [created : 12-08-2019]
     * Last edited [16-08-2019]
     */
    public function add_edit_estimate_quickboook($id = NULL, $flag = NULL)
    {
        if($id != '' || $id != NULL)
        {
            $estimate_id            = base64_decode($id);
            $this->quickbook_estimate($estimate_id, $flag);
        }
        else if($this->input->post('unsync_id') != '' || $this->input->post('unsync_id') != null)
        {
            $unsync_id              =  $this->input->post('unsync_id');
            $estimate_ids           = explode(',', $unsync_id);
            foreach ($estimate_ids as $estimate_id) 
            {
                $this->quickbook_estimate($estimate_id,'', "ajax");
            }
            $this->session->set_flashdata('success', "Sync all the Estimates to Quickbooks successfully.");
            $data['success']        = "success";
            echo json_encode($data);
            exit();
        }
    }


    /**
     * [quickbook_estimate add stimate to quickbook ]
     * @param  [string] $estimate_id [estimate id to upload on Quickbooks]
     * @param  [type] $flag        [update/insert]
     * @param  [type] $type        [ajax/post]
     * @author KBH 08-11-2019
     */
    public function quickbook_estimate($estimate_id = NULL, $flag = NULL, $type = NULL)
    {
        global $data_service;
        if(isset($data_service['accessTokenJson']))
        {
            $estimate_data                  = $this->estimate_model->get_estimate($estimate_id);
            $customer_detail                = $this->estimate_model->get_all_details(TBL_CUSTOMERS, ['id' => $estimate_data['customer_id'],'is_deleted' => 0])->row_array();

            $custome_quickbook_details      = $this->estimate_model->get_all_details(TBL_QUICKBOOK_CUSTOMER, ['customer_id' => $customer_detail['id'],'realmId' => $data_service['accessToken']->getRealmID()])->row_array();

            if($estimate_data['customer_id'] == 0)
            {
                $customer_config                = $this->inventory_model->get_all_details(TBL_QUICKBOOK_CONFIG, ['user_id' => $this->session->userdata('u_user_id'),'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();
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
                    $this->session->set_flashdata('error', "Estimate number '" .$estimate_data['estimate_id'] ."' in Customer '". $customer_detail['first_name'] ." ". $customer_detail['last_name'] ."' are not available in your quickbook.");
                    if($type == "ajax")
                    {
                        $data['customer']       = "not available";
                        echo json_encode($data);
                        exit();
                    }
                    else
                    {
                        redirect('estimates');
                    }
                }
            }
            $parts_array                = array();
            $qbEntity                   = new QuickBooksOnline\API\Data\IPPEstimate;
            $qbEntity->CustomerRef      = $customer_data[0]->Id;
            $qbEntity->total            = $estimate_data['total'];
            $qbEntity->BillAddr         = $estimate_data['address'];
            $qbEntity->ShipAddr         = $estimate_data['address'];
            $qbEntity->PONumber         = $estimate_data['phone_number'];
            $qbEntity->TotalAmt         = $estimate_data['total'];
            $qbEntity->DocNumber        = $estimate_data['estimate_id'];
            $qbEntity->TxnDate          = $estimate_data['estimate_date'];
            $qbEntity->ExpirationDate   = $estimate_data['expiry_date'];
            $qbEntity->PrivateNote      = $estimate_data['cust_name'];
            $qbEntity->BillEmail        = [];
            $qbEntity->BillEmail        = new \QuickBooksOnline\API\Data\IPPEmailAddress ([
                'Address' => $estimate_data['email'],
            ]);
            $qbEntity->Line             = [];
            if(!empty($estimate_data))
            {
                if(count($estimate_data['parts']) != 0)
                {
                    foreach ($estimate_data['parts'] as $part) 
                    {
                        $item_quickbook_data        = $this->estimate_model->get_all_details(TBL_QUICKBOOK_ITEMS, ['item_id' => $part['part_id'], 'realmId' => $data_service['accessToken']->getRealmID(), 'is_deleted' => 0])->row_array();
                        $individual_part_tax = explode(',',$estimate_data['individual_part_tax']);
                        $individual_service_tax = explode(',',$estimate_data['individual_service_tax']);

                        $total_part_tax = array_sum($individual_part_tax);
                        $total_service_tax = array_sum($individual_service_tax);
                        $total_payable_tax = $total_part_tax + $total_service_tax;

                        if(!empty($item_quickbook_data))
                        {
                           $parts_data = $data_service['dataService']->Query("select * from Item where Id = '" . $item_quickbook_data['quickbook_id'] ."' MAXRESULTS 1");
                            if($parts_data[0]->UnitPrice != $part['rate'])
                            {
                                $UnitPrice = $part['rate'];
                            }
                            else
                            {
                                $UnitPrice = $parts_data[0]->UnitPrice;
                            }
                            if(!empty($parts_data))
                            {
                                $qbEntity->Line[]           = new \QuickBooksOnline\API\Data\IPPLine([
                                    'LineNum'               => ++$i,
                                    'Description'           =>$parts_data[0]->Description,
                                    'Amount'                => $UnitPrice * $part['quantity'],
                                    'DetailType'            => 'SalesItemLineDetail',
                                    'SalesItemLineDetail'   => new \QuickBooksOnline\API\Data\IPPSalesItemLineDetail([
                                        'ItemRef'           => new \QuickBooksOnline\API\Data\IPPReferenceType([
                                            'value'         => $parts_data[0]->Id,
                                            'name'          => $parts_data[0]->Name,
                                        ]),
                                        'UnitPrice'         => $UnitPrice,
                                        'Qty'               => $part['quantity'],
                                        'TaxCodeRef'        => new \QuickBooksOnline\API\Data\IPPReferenceType([
                                                "value"     => 'TAX',
                                        ]),
                                    ]),
                                ]);

                                $qbEntity->TxnTaxDetail     = [];
                                $qbEntity->TxnTaxDetail     = new \QuickBooksOnline\API\Data\IPPTxnTaxDetail ([
                                    "TotalTax" => $total_payable_tax,
                                   ]); 
                            }
                            else 
                            {
                                $this->session->set_flashdata('error', "Estimate number '" .$estimate_data['estimate_id'] ."' for part no '". $part['part_no'] ."' are not available in your quickbook.");

                                if($type == "ajax")
                                {
                                    $data['parts']      = "not available";
                                    echo json_encode($data);
                                    exit();
                                }
                                else
                                {
                                    redirect('estimates');
                                }
                            } 
                        }
                        else 
                        {
                            $this->session->set_flashdata('error', "Estimate number '" .$estimate_data['estimate_id'] ."' for part no '". $part['part_no'] ."' are not available in your quickbook.");

                            if($type == "ajax")
                            {
                                $data['parts']      = "not available";
                                echo json_encode($data);
                                exit();
                            }
                            else
                            {
                                redirect('estimates');
                            }
                        }
                    }
                }
                if(count($estimate_data['services']) != 0)
                {
                    foreach ($estimate_data['services'] as $estimate_service) 
                    {
                        $service_quickbook_data         = $this->estimate_model->get_all_details(TBL_QUICKBOOK_SERVICE, ['service_id' => $estimate_service['service_id'], 'realmId' => $data_service['accessToken']->getRealmID(), 'is_deleted' => 0])->row_array();

                        if(!empty($service_quickbook_data))
                        {
                            $service_data               = $data_service['dataService']->Query("select * from Item where Id = '" . $service_quickbook_data['quickbook_id'] ."' MAXRESULTS 1");
                            if($service_data[0]->UnitPrice != $estimate_service['rate'])
                            {
                                $UnitPrice = $estimate_service['rate'];
                            }
                            else
                            {
                                $UnitPrice = $service_data[0]->UnitPrice;
                            }
                            if(!empty($service_data))
                            {
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
                                $this->session->set_flashdata('error', "Estimate number '" .$estimate_data['estimate_id'] ."' for service_name '". $estimate_service['service_name'] ."' are not available in your quickbook.");
                                if($type == "ajax")
                                {
                                    $data['services']       = "not available";
                                    echo json_encode($data);
                                    exit();
                                }
                                else
                                {
                                    redirect('estimates');
                                }
                            }
                        }
                        else
                        {
                            $this->session->set_flashdata('error', "Estimate number '" .$estimate_data['estimate_id'] ."' for service_name '". $estimate_service['service_name'] ."' are not available in your quickbook.");
                            if($type == "ajax")
                            {
                                $data['services']       = "not available";
                                echo json_encode($data);
                                exit();
                            }
                            else
                            {
                                redirect('estimates');
                            }
                        }
                    }
                }

                if($estimate_data['shipping_charge'] != '')
                {
                    $qbEntity->Line[]           = new \QuickBooksOnline\API\Data\IPPLine([
                        'Amount'                => $estimate_data['shipping_charge'],
                        'DetailType'            => 'SalesItemLineDetail',
                        'SalesItemLineDetail'   => new \QuickBooksOnline\API\Data\IPPSalesItemLineDetail([
                            'ItemRef'           => 'SHIPPING_ITEM_ID',
                        ]),
                    ]);
                }
                $data_service['dataService']->throwExceptionOnError(true);

                try 
                {
                    if($flag == "update") 
                    {
                        $estimate_quickbook_details                 = $this->estimate_model->get_all_details(TBL_QUICKBOOK_ESTIMATE, ['estimate_id' => $estimate_id,'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

                        $estimate_quickbook_attachment_details      = $this->estimate_model->get_all_details(TBL_ESTIMATE_QUICKBOOK_ATTACHMENT, ['estimate_quickbook_id' => $estimate_quickbook_details['id'], 'is_deleted' => '0' ])->result_array();

                        if(!empty($estimate_quickbook_attachment_details))
                        {
                            foreach ($estimate_quickbook_attachment_details as $attachment) 
                            {
                                $attachables                =  $data_service['dataService']->FindbyId('attachable', $attachment['attachment_id']);

                                $objAttachable              = new IPPAttachable();
                                $objAttachable->Id          = $attachables->Id;
                                $objAttachable->FileName    = $attachables->FileName;
                                $objAttachable->Note        = 'Delete attachable';

                                $delete_resultingObj                = $data_service['dataService']->Delete($objAttachable); 

                                $this->estimate_model->insert_update('update', TBL_ESTIMATE_QUICKBOOK_ATTACHMENT, ['is_deleted' => 1], array('id' => $attachment['id']));

                                $estimate_quickbook_details         = $this->estimate_model->get_all_details(TBL_QUICKBOOK_ESTIMATE, ['estimate_id' => $estimate_id,'realmId' => $data_service['accessToken']->getRealmID()])->row_array();

                                $this->estimate_model->insert_update('update', TBL_QUICKBOOK_ESTIMATE, ['update_count' => $estimate_quickbook_details['update_count'] + 1],array('id' => $estimate_quickbook_details['id']));
                            }
                        }

                        $estimate                   = $data_service['dataService']->FindbyId('Estimate', $estimate_quickbook_details['quickbook_id']);

                        $estimate_quickbook_details = $this->estimate_model->get_all_details(TBL_QUICKBOOK_ESTIMATE, ['estimate_id' => $estimate_id,'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

                        $qbEntity->Id           = $estimate->Id;
                        $qbEntity->SyncToken    = $estimate_quickbook_details['update_count'];

                        $resultingObj           = $data_service['dataService']->Update($qbEntity);

                        $this->estimate_model->insert_update('update', TBL_QUICKBOOK_ESTIMATE, ['update_count' => $estimate_quickbook_details['update_count'] + 1],array('id' => $estimate_quickbook_details['id']));
                    }
                    else
                    {
                        $resultingObj                           = $data_service['dataService']->Add($qbEntity);
                        $quickbook_invoice['realmId']           = $data_service['accessToken']->getRealmID();
                        $quickbook_invoice['estimate_id']       = $estimate_id;
                        $quickbook_invoice['quickbook_id']      = $resultingObj->Id;

                        $is_quickbook_estimate_saved            = $this->estimate_model->insert_update('insert', TBL_QUICKBOOK_ESTIMATE, $quickbook_invoice);  
                    }

                    if($estimate_data['signature_attachment'] != '')
                    {
                        $estimate_signture              = site_url(SIGNATURE_IMAGE_PATH . $estimate_data['signature_attachment']);
                        $ext                            = strtolower( pathinfo($estimate_data['signature_attachment'], PATHINFO_EXTENSION));    
                        $imageBase64                    = array();
                        $file                           =  $this->echoBase64($estimate_signture);
                        $imageBase64['image/jpeg']      = $file;
                        $sendMimeType                   = "image/jpeg";

                        $randId                         = rand();

                        $entityRef                      = new IPPReferenceType(array('value'=>$resultingObj->Id, 'type'=>'Estimate'));
                        $attachableRef                  = new IPPAttachableRef(array('EntityRef'=>$entityRef));

                        $objAttachable                  = new IPPAttachable();
                        $objAttachable->FileName        = $randId . "." . $ext;
                        $objAttachable->AttachableRef   = $attachableRef;
                        $objAttachable->Category        = 'Image';
                        $objAttachable->Tag             = 'Tag_' . $randId;

                        $resultObj                  = $data_service['dataService']->Upload(base64_decode($imageBase64[$sendMimeType]), $objAttachable->FileName, $sendMimeType, $objAttachable);

                        $quickbook_estimate_attachment['attachment_id '] = $resultObj->Attachable->Id;

                        $estimate_quickbook_details                     = $this->estimate_model->get_all_details(TBL_QUICKBOOK_ESTIMATE, ['estimate_id' => $estimate_id,'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

                        $this->estimate_model->insert_update('update', TBL_QUICKBOOK_ESTIMATE, ['update_count' => $estimate_quickbook_details['update_count'] + 1],array('id' => $estimate_quickbook_details['id']));    
                        
                        if($flag == "update") 
                        {
                            $quickbook_estimate_attachment['estimate_quickbook_id'] = $estimate_quickbook_details['id'];
                        }
                        else 
                        {
                            $quickbook_estimate_attachment['estimate_quickbook_id'] = $is_quickbook_estimate_saved;
                        }
                        $is_quickbook_estimate_attachment_saved = $this->estimate_model->insert_update('insert', TBL_ESTIMATE_QUICKBOOK_ATTACHMENT, $quickbook_estimate_attachment);
                    }
                    $estimate_data                              = $this->db->query("SELECT estimation_id,file_name FROM ".TBL_ESTIMATION_ATTACHMENTS." WHERE estimation_id = '".$estimate_id."' ")->result_array();
                    if(!empty($estimate_data))
                    {
                        foreach ($estimate_data as $image) 
                        {
                            $estimate_image                 = base_url() . ESTIMATE_IMAGE_PATH . "/" . $image['file_name'];
                            $ext                            = strtolower( pathinfo($image['file_name'], PATHINFO_EXTENSION));
                            $imageBase64                    = array();
                            $file                           =  $this->echoBase64($estimate_image);
                            $imageBase64['image/jpeg']      = $file;
                            $sendMimeType                   = "image/jpeg";
                            $randId                         = rand();

                            $entityRef                      = new IPPReferenceType(array('value'=>$resultingObj->Id, 'type'=>'Estimate'));

                            $attachableRef                  = new IPPAttachableRef(array('EntityRef'=>$entityRef));

                            $objAttachable                  = new IPPAttachable();
                            $objAttachable->FileName        = $randId . "." . $ext;
                            $objAttachable->AttachableRef   = $attachableRef;
                            $objAttachable->Category        = 'Image';
                            $objAttachable->Tag             = 'Tag_' . $randId;

                            $resultObj                      = $data_service['dataService']->Upload(base64_decode($imageBase64[$sendMimeType]), $objAttachable->FileName, $sendMimeType, $objAttachable);

                            $estimate_quickbook_details     = $this->estimate_model->get_all_details(TBL_QUICKBOOK_ESTIMATE, ['estimate_id' => $estimate_id,'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

                            $this->estimate_model->insert_update('update', TBL_QUICKBOOK_ESTIMATE, ['update_count' => $estimate_quickbook_details['update_count'] + 1],array('id' => $estimate_quickbook_details['id']));
                            $quickbook_estimate_attachment['attachment_id '] = $resultObj->Attachable->Id;

                            $is_quickbook_estimate_attachment_saved = $this->estimate_model->insert_update('insert', TBL_ESTIMATE_QUICKBOOK_ATTACHMENT, $quickbook_estimate_attachment);
                        }
                    }
                    $error = $data_service['dataService']->getLastError();
                    if ($error) {
                        $this->session->set_flashdata('error', $error->getHttpStatusCode());
                        $this->session->set_flashdata('error', $error->getOAuthHelperError());
                        $this->session->set_flashdata('error', $error->getResponseBody());
                      
                        redirect('estimates');
                    }
                    else
                    {
                        if ($flag == "update")
                        {
                            $this->session->set_flashdata('success', 'Estimate has been updated successfully also in Quickbook.');  
                        }
                        else 
                        {
                            if($flag == 1)
                            {
                                $this->session->set_flashdata('success', 'Estimate has been created successfully also in Quickbook.');    
                            }
                            else
                            {
                                if($type != "ajax")
                                {
                                    $this->session->set_flashdata('success', 'Estimate 
                                    has been created in Quickbook successfully.');
                                    redirect('estimates');
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
                        redirect('estimates');
                    }
                }
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'Please login to Quickbook! Your session has been exprire.');
            if($type == "ajax")
            {
                $data['session']        = "exprired";
                echo json_encode($data);
                exit();
            }
            else
            {
                redirect('estimate');
            }
        }
    }
}

/* End of file Inventory.php */
/* Location: ./application/controllers/Inventory.php */