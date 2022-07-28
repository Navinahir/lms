<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . '/Library/QuickBook/autoload.php';
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;

use Mpdf\Mpdf;


class Customers extends MY_Controller {

    public function __construct() {
        global $data_service;
        parent::__construct();
        $this->load->model(array('admin/customers_model','admin/invoice_model'));
        $data_service = $this->data_service();
    }

    /**
     * Display All items 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function display_customers() {
        $data['title'] = 'List of Customers';
        $user_id = $this->customers_model->auth_customer_id();
        // $user_id = checkUserLogin('I');

        $current_year = date('Y');

        //Fetch Total Estimates and Total Amount
        $this->db->select(' IFNULL(SUM(e.total), 0) AS total_invoices_amount');
        $this->db->from(TBL_CUSTOMERS . ' AS c');
        $this->db->where(['c.is_deleted' => 0, 'c.added_by' => $user_id, 'YEAR(e.estimate_date)' => $current_year]);
        $this->db->join(TBL_ESTIMATES . ' as e', 'c.id=e.customer_id AND e.is_invoiced = 0 AND e.is_deleted = 0', 'inner');
        $data['total_invoices_amount'] = $this->db->get()->row_array();


        // Fetch Total Due Amount
        $this->db->select('IFNULL(SUM(e.total),0) as due_payment, IFNULL(COUNT(e.id),0) as total_due_invoices');
        $this->db->from(TBL_CUSTOMERS . ' AS c');
        $this->db->join(TBL_ESTIMATES . ' as e', 'c.id=e.customer_id AND e.is_deleted = 0 AND e.is_deleted = 0 AND e.is_invoiced = 1', 'inner');
        $this->db->join(TBL_PAYMENT_METHODS . ' as pm', 'e.payment_method_id=pm.id', 'inner');
        $this->db->where(['c.is_deleted' => 0, 'c.added_by' => $user_id]);
        $this->db->where(['pm.name' => 'Bill to Account']);
        $data['due_amount'] = $this->db->get()->row_array();

        // OPEN INVOICES
        $this->db->select('IFNULL(COUNT(e.id),0) as total_invoices,IFNULL(SUM(e.total),0) AS total_invoices_amount');
        $this->db->from(TBL_CUSTOMERS . ' AS c');
        $this->db->join(TBL_ESTIMATES . ' as e', 'c.id=e.customer_id AND e.is_deleted = 0 AND e.is_deleted = 0 AND e.is_invoiced = 1', 'inner');
        $this->db->join(TBL_PAYMENT_METHODS . ' as pm', 'e.payment_method_id=pm.id', 'inner');
        $this->db->where(['c.is_deleted' => 0, 'c.added_by' => $user_id]);
        $this->db->where(['pm.name' => 'Bill to Account']);
        $data['total_invoices'] = $this->db->get()->row_array();

        //Count Revenue
        $year = $this->input->post('year');
        $data['totalrevenues']=$this->customers_model->totalrevenue($year,$user_id);

        $this->template->load('default_front', 'front/customers/customer_display', $data);

    }

    /**
     * Display all deleted customer 
     * @param --
     * @return --
     * @author JJP [Last Edited : 04/11/2020]
     */
    public function customer_trash() {
        $data['title'] = 'Customer trash';
        $this->template->load('default_front', 'front/customers/customer_trash', $data);
    }

     /**
     * Display all deleted customer 
     * @param --
     * @return --
     * @author JJP [Last Edited : 04/11/2020]
     */
    public function get_customer_data_trash() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->customers_model->get_customers_data_trash('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $where = [];

        if (!empty($this->input->get('status_id'))) {
            $where = array('o.status_id' => $this->input->get('status_id'));
        }
        $items = $this->customers_model->get_customers_data_trash('result', $where);
        
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['created_date'] = date($format['format'], strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
            $items[$key]['quickbooks'] = $this->get_customer_quickbook_status($val['id']);
            $items[$key]['start_date'] = date('Y-m-d', strtotime($val['updated_date']));
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Recoverd Invoice 
     * @param $id - String
     * @return --
     * @author JJP [Last Edited : 04/11/2020]
     */
    public function trash_recover_customer($id = '') {
        $record_id = base64_decode($id);
        $this->customers_model->insert_update('update', TBL_CUSTOMERS, array('is_deleted' => 0), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Customer has been recoverd successfully.');
        redirect('customers');
    }

    /**
     * Delete customers trash record
     * @param $id - String
     * @return --
     * @author JJP [Last Edited : 04/11/2020]
     */
    public function trash_delete_customer($id = '') {
        $record_id = base64_decode($id);
        $this->customers_model->insert_update('update', TBL_CUSTOMERS, array('is_deleted' => 2), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Customer has been deleted successfully.');
        redirect('customers/customer_trash');
    }

    /**
     * Recover selected customers
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
            $this->session->set_flashdata('success', 'Customer has been recoverd successfully.');
            $data = $this->customers_model->insert_update('update',TBL_CUSTOMERS,$dataArr,$where);
            echo json_encode($data);
        }
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
            $this->session->set_flashdata('success', 'Customer has been deleted successfully.');
            $data = $this->customers_model->insert_update('update',TBL_CUSTOMERS,$dataArr,$where);
            echo json_encode($data);
        }
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_customer_data() {
        if (isset($_SESSION['sessionAccessToken'])) {
            $accessToken = $_SESSION['sessionAccessToken'];
        }
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->customers_model->get_customers_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $where = [];

        if (!empty($this->input->get('status_id'))) {
            $where = array('o.status_id' => $this->input->get('status_id'));
        }

        $items = $this->customers_model->get_customers_data('result', $where);
        //$final['customer_status'] = $this->customers_model->get_customer_added_status($accessToken->getRealmID());

        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['created_date'] = date($format['format'], strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['fullname'] = strip_tags($val['first_name'] .' '. $val['last_name']);
            $items[$key]['company'] = strip_tags($val['company']);
            $items[$key]['responsive'] = '';
            $items[$key]['quickbooks'] = $this->get_customer_quickbook_status($val['id']);
            
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    // Get customer email
    public function email_detail(){
        $custoomer_id = base64_decode($this->input->post('id'));
        $email = $this->customers_model->get_all_details(TBL_CUSTOMER_EMAIL,array('customer_id' => $custoomer_id))->row_array();
        echo json_encode($email);
        die;
    }

    public function openinvoiceslist(){
        $data['title'] = 'List of Open Invoices';
        $this->template->load('default_front', 'front/customers/openinvoice_display',$data);
    }

    // OPEN INVOICES LIST
    public function openinvoices(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->invoice_model->get_open_invoices('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->invoice_model->get_open_invoices('result');

        $start = $this->input->get('start') + 1;
        
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;            
            $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset'] );
            $items[$key]['responsive'] = '';
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
    public function add_edit_customers($id = null) {
        $data['part_details'] = [];
        $total_orders = $this->customers_model->get_all_details(TBL_CUSTOMERS, [])->num_rows();
        $data = array(
            'title' => 'Add Customer',
            'format' => MY_Controller::$date_format,
            'currency' => MY_Controller::$currency,
        );

        if (!empty($this->input->get('id', TRUE))) {
            $r_id = base64_decode($this->input->get('id', TRUE));
            $part_details = $this->users_model->get_all_details(TBL_ITEMS, ['is_delete' => 0, 'id' => $r_id])->row_array();

            if (!empty($part_details)) {
                $data['part_details'] = $part_details;
            }
        }

        if (!is_null($id)) {
            $record_id = base64_decode($id);
            $data['title'] = 'Edit Customer';
            $data['dataArr'] = $this->customers_model->get_all_details(TBL_CUSTOMERS, ['id' => $record_id])->row_array();
        }
        $data['emailArr'] = $this->customers_model->get_all_details(TBL_CUSTOMER_EMAIL,['customer_id' => base64_decode($id)])->result_array();

        if ($this->input->post()) {
            // $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[255]');
            // $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[255]');
            // $this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|max_length[255]');
            // $this->form_validation->set_rules('phone', 'Phone', 'trim|required|max_length[15]');
            // $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|max_length[15]');
            // $this->form_validation->set_rules('fax', 'Fax', 'trim|required|max_length[15]');
            // $this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[255]');

            //if ($this->form_validation->run() == true) {
                $country_data['first_name'] = $this->input->post('first_name');
                $country_data['last_name'] = $this->input->post('last_name');
                // $country_data['customer_uniq_name'] = $this->input->post('first_name') . "_" . $this->input->post('last_name') . "(" . date('d-m-y h-i-s') . ")";
                $country_data['company'] = trim($this->input->post('company_name'));
                $country_data['display_name_as'] = $this->input->post('display_name_as');
                $country_data['phone'] = $this->input->post('phone');
                $country_data['mobile'] = $this->input->post('mobile');
                $country_data['fax'] = $this->input->post('fax');
                // $country_data['email'] = $this->input->post('email');
                $country_data['billing_address'] = trim($this->input->post('billing_address'));
                $country_data['billing_address_city'] = trim($this->input->post('billing_address_city'));
                $country_data['billing_address_state'] = trim($this->input->post('billing_address_state'));
                // $country_data['billing_address_street'] = $this->input->post('billing_address_street');
                $country_data['billing_address_zip'] = trim($this->input->post('billing_address_zip'));
                $country_data['checkbox_status'] = $this->input->post('checkbox_status');
                $country_data['shipping_address'] = trim($this->input->post('shipping_address'));
                $country_data['shipping_address_city'] = trim($this->input->post('shipping_address_city'));
                $country_data['shipping_address_state'] = trim($this->input->post('shipping_address_state'));
                $country_data['shipping_address_street'] = trim($this->input->post('shipping_address_street'));
                $country_data['shipping_address_zip'] = trim($this->input->post('shipping_address_zip'));

                if (!is_null($id)) {

                    // Update customer email
                    $this->customers_model->insert_update('delete',TBL_CUSTOMER_EMAIL,'',['customer_id' => base64_decode($id)]);
                    // if($this->input->post('secondary_email') != "" && !empty($this->input->post('secondary_email')))
                    // {
                    //     foreach ($this->input->post('secondary_email') as $e_key => $email) {
                    //         $emailArr[$e_key] = array(
                    //             'customer_id' => base64_decode($id),
                    //             'customer_email' => $this->input->post('secondary_email')[$e_key],
                    //             'status' => $this->input->post('secondary_email_status')[$e_key],
                    //         );
                    //     }
                    // }

                    $multiple_email = explode(",",$this->input->post('multiple_email'));
                    foreach ($multiple_email as $key => $value) {
                        $email_list[$key] = array(
                            'customer_id' => base64_decode($id),
                            'customer_email' => $value,
                        );
                    }

                    $this->customers_model->batch_insert_update('insert', TBL_CUSTOMER_EMAIL, $email_list);

                    $is_update = $this->customers_model->insert_update('update', TBL_CUSTOMERS, $country_data, array('id' => $record_id));
                        if ($is_update) {
                            if (isset($_SESSION['sessionAccessToken'])) {
                                $session_accesToken = $_SESSION['sessionAccessToken'];
                                
                                $customer_quickbook_details = $this->customers_model->get_all_details(TBL_QUICKBOOK_CUSTOMER, ['customer_id' => $record_id,'realmId' => $session_accesToken-> getRealmID()])->row_array();

                            if($customer_quickbook_details['quickbook_id'] != '' || $customer_quickbook_details['quickbook_id'] != null)
                            {
                                    $this->add_edit_customer_quickbook($id, $flag = "update");
                            }
                        }
                        
                        $this->session->set_flashdata('success', 'Customer has been updated successfully.');
                    }
                } else {
                    $country_data['added_by'] = $this->customers_model->auth_customer_id();
                    $is_saved = $this->customers_model->insert_update('insert', TBL_CUSTOMERS, $country_data);
                    
                    // Add secondary email list
                    $last_id = $this->db->insert_id(); 
                    // if($this->input->post('secondary_email') != "" && !empty($this->input->post('secondary_email')))
                    // {
                    //     foreach ($this->input->post('secondary_email') as $e_key => $email) {
                    //         $emailArr[$e_key] = array(
                    //             'customer_id' => $last_id,
                    //             'customer_email' => $this->input->post('secondary_email')[$e_key],
                    //             'status' => $this->input->post('secondary_email_status')[$e_key],
                    //         );
                    //     }
                    // }

                    $multiple_email = explode(",",$this->input->post('multiple_email'));
                    foreach ($multiple_email as $key => $value) {
                        $email_list[$key] = array(
                            'customer_id' => $last_id,
                            'customer_email' => $value,
                        );
                    }
                    $this->customers_model->batch_insert_update('insert', TBL_CUSTOMER_EMAIL, $email_list);

                    if ($is_saved) {
                        $is_saved = base64_encode($is_saved);
                        $this->session->set_flashdata('success', 'Customer has been created successfully.');
                        if (isset($_SESSION['sessionAccessToken'])) {
                            $this->add_edit_customer_quickbook($is_saved, $flag=1);
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Customer has not been created.');
                    }
                }
                redirect('customers');
            // }
        }
        $this->template->load('default_front', 'front/customers/customer_add', $data);
    }

    /**
     * Delete Items
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function delete_customers($id = '') {
        $record_id = base64_decode($id);
        $this->customers_model->insert_update('update', TBL_CUSTOMERS, array('is_deleted' => 1), array('id' => $record_id));
        global $data_service;
        if (isset($_SESSION['sessionAccessToken'])) 
        { 
            $where = array(
                'customer_id' => $record_id, 
                'realmId' => $data_service['accessToken']->getRealmID(),
            );
            $customer_quickbook_details = $this->customers_model->get_all_details(TBL_QUICKBOOK_CUSTOMER, $where)->row_array();
            if($customer_quickbook_details['quickbook_id'] != '' || $customer_quickbook_details['quickbook_id'] != null)
            {
                $customer = $data_service['dataService']->FindbyId('customer', $customer_quickbook_details['quickbook_id']);
                $theResourceObj = Customer::update($customer, [
                    "Active" => false
                ]);
                $resultingObj = $data_service['dataService']->Update($theResourceObj);
                $this->customers_model->insert_update('update', TBL_QUICKBOOK_CUSTOMER, array('is_deleted' => 1), $where);
            }
        }
        $this->session->set_flashdata('success', 'Customer has been deleted successfully.');
        $last_url      = base_url() . 'quickbook/customer';
        if ($this->agent->referrer() == $last_url)
        {
            $refer     =  $this->agent->referrer();
            redirect($refer);
        }
        else
        {
            redirect('customers');
        }
    }

    /**
     * Get Orders data by its ID
     * @param --
     * @return HTML data
     * @author HGA [Created AT : 27/11/2019]
     */
    public function get_customer_data_ajax_by_id() {
        $data['result'] = array();
        $id = base64_decode($this->input->post('id'));

        $this->db->select('c.*');
        $this->db->from(TBL_CUSTOMERS . ' AS c');
        $this->db->where(['c.is_deleted' => 0, 'c.id' => $id]);
        $result = $this->db->get()->row_array();

        if ($result) {
            $data['result'] = $result;
        }

        return $this->load->view('front/partial_view/view_customer_data', $data);
    }

    public function view_customers($id) {
        $data = array(
            'title' => 'Customer Details',
            'format' => MY_Controller::$date_format,
            'currency' => MY_Controller::$currency,
        );

        $id = base64_decode($id);
        $this->db->select('c.*');
        $this->db->from(TBL_CUSTOMERS . ' AS c');
        $this->db->where(['c.is_deleted' => 0, 'c.id' => $id]);
        $result = $this->db->get()->row_array();

        if ($result) {
            $data['result'] = $result;
        }

        //  Bill to Account
        $this->db->select('IFNULL(SUM(e.total),0) as due_payment');
        $this->db->from(TBL_ESTIMATES . ' AS e');
        $this->db->join(TBL_PAYMENT_METHODS . ' as pm', 'e.payment_method_id=pm.id', 'left');
        $this->db->where(['e.is_deleted' => 0, 'e.is_invoiced' => 1, 'e.customer_id' => $id, 'pm.name' => 'Bill to Account']);
        $get_due_amount = $this->db->get()->row_array();
        $data['due_amount'] = number_format($get_due_amount['due_payment'], 2);
        $data['emailArr'] = $this->customers_model->get_all_details(TBL_CUSTOMER_EMAIL,['customer_id' => $id])->result_array();
        $this->template->load('default_front', 'front/customers/customer_view', $data);
    }

    /**
     * Get today's all invoices and total.
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_customers_invoice_data() {
        $format = MY_Controller::$date_format;

        $final['recordsTotal']      = $this->customers_model->get_customer_invoices_ajax_data('count');
        $final['redraw']            = 1;
        $final['recordsFiltered']   = $final['recordsTotal'];
        $items                      = $this->customers_model->get_customer_invoices_ajax_data('result')->result_array();
        $start                                  = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key]                        = $val;
            $items[$key]['sr_no']               = $start++;
            $items[$key]['estimate_rate']       = number_format((float) $val['total'], 2, '.', '');
            $items[$key]['invoice_date']        = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
            $items[$key]['quickbooks']          = $this->invoice_quickbook_status($val['id']);
            $items[$key]['responsive']          = '';
        }
        $final['data']                          = $items;
        echo json_encode($final);
        die;
    }

    public function create_update_notes($note_id = null) {
        if (!empty($this->input->post())) {
            $note_array = [
                'notes' => $this->input->post('notes')
            ];

            if (!is_null($note_id)) {
                $is_update = $this->customers_model->insert_update('update', TBL_CUSTOMER_NOTES, $note_array, array('id' => $note_id));
                $this->session->set_flashdata('success', 'Note has been updated successfully.');
            } else {
                $note_array['user_id'] = $this->customers_model->auth_customer_id();
                $note_array['customer_id'] = $this->input->post('c_id');

                $is_saved = $this->customers_model->insert_update('insert', TBL_CUSTOMER_NOTES, $note_array);

                if ($is_saved) {
                    $this->session->set_flashdata('success', 'Note has been added successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Note has not been created.');
                }
            }

            redirect('customers/view/' . base64_encode($this->input->post('c_id')));
        }
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_customer_notes() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->customers_model->get_customers_notes('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $where = [];

        if (!empty($this->input->get('status_id'))) {
            $where = array('o.status_id' => $this->input->get('status_id'));
        }

        $items = $this->customers_model->get_customers_notes('result', $where);

        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['notes'] = strip_tags($val['notes']);
            $items[$key]['created_date'] = date($format['format'], strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    public function get_note() {
        $note_comment = '';
        if (!empty($this->input->post())) {
            $note_id = $this->input->post('note_id');

            $note = $this->customers_model->get_all_details(TBL_CUSTOMER_NOTES, ['id' => $note_id])->row_array();

            if (!empty($note)) {
                $note_comment = strip_tags($note['notes']);
            }
        }

        echo $note_comment;
        exit;
    }

    public function delete_note($id, $customer_id) {
        $record_id = base64_decode($id);

        $this->customers_model->insert_update('update', TBL_CUSTOMER_NOTES, array('is_deleted' => 1), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Note has been deleted successfully.');

        redirect('customers/view/' . $customer_id);
    }

    /**
     * Displaying Due Year Wish
     * @param --
     * @return --
     * @author JJP [Last Edited : 02/07/2019]
     */
    public function ajax_yeardue(){
        $year = $this->input->post('year');
        $id = $this->uri->segment(3);
        if($year == "")
        {
        $currentyear = date('Y');
        $this->db->select('IFNULL(SUM(e.total),0) as due_payment');
        $this->db->from(TBL_ESTIMATES . ' AS e');
        $this->db->join(TBL_PAYMENT_METHODS . ' as pm', 'e.payment_method_id=pm.id', 'left');
        $this->db->where(['e.is_deleted' => 0, 'e.is_invoiced' => 1, 'e.customer_id' => $id, 'pm.name != ' => 'Bill to Account', 'year(e.estimate_date)' => $currentyear]);
        $get_due_amount = $this->db->get()->row_array();
        $data['due_amount'] = number_format($get_due_amount['due_payment'], 2);
        echo json_encode($data['due_amount']); 
        } else {
        
        $this->db->select('IFNULL(SUM(e.total),0) as due_payment');
        $this->db->from(TBL_ESTIMATES . ' AS e');
        $this->db->join(TBL_PAYMENT_METHODS . ' as pm', 'e.payment_method_id=pm.id', 'left');
        $this->db->where(['e.is_deleted' => 0, 'e.is_invoiced' => 1, 'e.customer_id' => $id, 'pm.name !=' => 'Bill to Account', 'year(e.estimate_date)' => $year]);
        $this->db->where('year(e.estimate_date)',$year);
        $get_due_amount = $this->db->get()->row_array();
        $data['due_amount'] = number_format($get_due_amount['due_payment'], 2);
        echo json_encode($data['due_amount']);
        }
    }

    /**
     * Displaying Estimate Year Wish
     * @param --
     * @return --
     * @author JJP [Last Edited : 29/07/2019]
     */

    public function ajax_yearestimate(){
        $year = date('Y');
        $user_id = $this->customers_model->auth_customer_id();
        
        if(!empty($this->input->post('year'))){
            $year = $this->input->post('year');
            $this->db->select(' IFNULL(SUM(e.total), 0) AS total_invoices_amount');
            $this->db->from(TBL_CUSTOMERS . ' AS c');
            $this->db->where(['c.is_deleted' => 0, 'c.added_by' => $user_id, 'YEAR(e.estimate_date)' => $year]);
            $this->db->join(TBL_ESTIMATES . ' as e', 'c.id=e.customer_id AND e.is_invoiced = 1 AND e.is_deleted = 0', 'inner');
            $data['total_invoices_amount'] = $this->db->get()->row_array();
            echo json_encode($data['total_invoices_amount']); exit;        
        } 
    }

    /**
     * Displaying Due Month Wish
     * @param --
     * @return --
     * @author JJP [Last Edited : 30/06/2019]
     */

    public function ajax_monthoverdue(){
        $year = date('Y');
        $user_id = $this->customers_model->auth_customer_id();

        if(!empty($this->input->post('month'))){
            $month = $this->input->post('month');
            $this->db->select(' IFNULL(SUM(e.total), 0) AS total_invoices_amount');
            $this->db->from(TBL_CUSTOMERS . ' AS c');
            $this->db->where(['c.is_deleted' => 0, 'c.added_by' => $user_id, 'YEAR(e.estimate_date)' => $year ,'month(e.estimate_date)' => $month]);
            $this->db->join(TBL_ESTIMATES . ' as e', 'c.id=e.customer_id AND e.is_invoiced = 1 AND e.is_deleted = 0', 'inner');
            $data['total_invoices_amount'] = $this->db->get()->row_array();
            echo json_encode($data['total_invoices_amount']); exit;        
        } 
    }

    /**
     * Displaying Due Year Wish
     * @param --
     * @return --
     * @author JJP [Last Edited : 29/06/2019]
     */
    public function openamt(){
        $user_id = $this->customers_model->auth_customer_id();
        $this->db->select(' IFNULL(SUM(e.total), 0) AS total_invoices_amount');
        $this->db->from(TBL_CUSTOMERS . ' AS c');
        $this->db->where(['c.is_deleted' => 0, 'c.added_by' => $user_id ]);
        $this->db->join(TBL_ESTIMATES . ' as e', 'c.id=e.customer_id AND e.is_invoiced = 1 AND e.is_deleted = 0 AND e.is_save_draft = 0 AND e.is_paid = 1', 'inner');
        $data['total_invoices_amount'] = $this->db->get()->row_array();
        echo json_encode($data['total_invoices_amount']); exit;        
    }

    public function callback()
    {
        $this->processCode();
    }

     /**
     * [add_to_quickbook add users to quickbook]
     * @author KBH
     * @date(07-03-2020)
     */
    public function add_edit_customer_quickbook($id = null, $flag = null)
    {
        if($id != '' || $id != NULL)
        {
            $customer_id                    = base64_decode($id);
            $this->quickbook_customer($customer_id, $flag);
        }
        else if($this->input->post('unsync_id') != '' || $this->input->post('unsync_id') != null)
        {

            $unsync_id              =  $this->input->post('unsync_id');
            $customer_ids            = explode(',', $unsync_id);
            foreach ($customer_ids as $customer_id) 
            {
                $this->quickbook_customer($customer_id);
            }
            $this->session->set_flashdata('success', "Sync all the customer to Quickbooks successfully.");
            $data['success']        = "success";
            echo json_encode($data);
            exit();
        }
        else if($this->input->post('unsync_id') == '' || $this->input->post('unsync_id') == null)
        {
            $this->session->set_flashdata('error', 'Not found any records to synchronous to Quickbooks');
            $data['sync_id_not_found']        = "sync id not found";
            echo json_encode($data);
            exit();
        }
    }


    /**
     * [quickbook_customer add customer to Quickbooks]
     * @param  [type] $customer_id [customer id of ARK to add]
     * @param  [type] $flag        [add/update]
     * @return [type]              [description]
     * @author KBH <14-08-2019> <07-03-2020>
     */
    public function  quickbook_customer($customer_id = null, $flag = null)
    {

        global $data_service;
        if(isset($data_service['accessTokenJson']))
        {
            $customer_detail            = $this->customers_model->get_all_details(TBL_CUSTOMERS, ['id' => $customer_id])->row_array();
            $customer_array             = 
            [
                "FullyQualifiedName"            => $customer_detail['display_name_as'], 
                "CompanyName"                   => $customer_detail['company'], 
                "Fax"                           => [
                    "FreeFormNumber"            => $customer_detail['fax']
                ], 
                "PrimaryEmailAddr"              => [
                    "Address"                   => $customer_detail['email']
                ], 
                "DisplayName"                   => $customer_detail['display_name_as'],
                "FamilyName"                    => $customer_detail['last_name'], 

                "PrimaryPhone"                  => [
                    "FreeFormNumber"            => $customer_detail['phone']
                ], 

                "Mobile"                        => [
                    "FreeFormNumber"            => $customer_detail['mobile']
                ], 

                "BillAddr"                      => [
                    "Line1"                     => $customer_detail['billing_address'], 
                    "Line2"                     => $customer_detail['billing_address_street'], 
                    "City"                      => $customer_detail['billing_address_city'], 
                    "CountrySubDivisionCode"    => $customer_detail['billing_address_state'], 
                    "PostalCode"                => $customer_detail['billing_address_zip'], 
                ],  

                "ShipAddr"                      => [
                    "Line1"                     => $customer_detail['shipping_address'], 
                    "Line2"                     => $customer_detail['shipping_address_street'], 
                    "City"                      => $customer_detail['shipping_address_city'], 
                    "CountrySubDivisionCode"    => $customer_detail['billing_address_state'], 
                    "PostalCode"                => $customer_detail['shipping_address_zip'], 
                ], 

                "GivenName"                 => $customer_detail['first_name']
            ];

            $data_service['dataService']->setLogLocation("C:/Users/bkhyati/Desktop/log");
            $data_service['dataService']->throwExceptionOnError(true);
            try 
            {
                if ($flag == "update") 
                {
                    $customer_quickbook_details     = $this->customers_model->get_all_details(TBL_QUICKBOOK_CUSTOMER, ['customer_id' => $customer_id,'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

                    $customer                       = $data_service['dataService']->FindbyId('customer', $customer_quickbook_details['quickbook_id']);

                    $theResourceObj                 = Customer::update($customer, $customer_array);

                    $resultingObj                   = $data_service['dataService']->Update($theResourceObj);
                }
                else
                {
                    $theResourceObj         = Customer::create($customer_array);
                    $resultingObj           = $data_service['dataService']->Add($theResourceObj);

                    $quickbook_customer['realmId']              = $data_service['accessToken']->getRealmID();
                    $quickbook_customer['customer_id']          = $customer_id;
                    $quickbook_customer['quickbook_id']         = $resultingObj->Id;

                    $is_quickbook_customer_saved    = $this->customers_model->insert_update('insert', TBL_QUICKBOOK_CUSTOMER, $quickbook_customer);
                }
                $error                              = $data_service['dataService']->getLastError();
                   
                if ($error) 
                {
                    $this->session->set_flashdata('error', $error->getHttpStatusCode());
                    $this->session->set_flashdata('error', $error->getOAuthHelperError());
                    $this->session->set_flashdata('error', $error->getResponseBody());
                    $this->session->set_flashdata('error', 'Customer has not been created.');
                    redirect('customers');
                }
                else
                {
                    if ($flag == "update")
                    {
                        $this->session->set_flashdata('success', 'Customer has been updated successfully also in Quickbook.');  
                    } 
                    else 
                    {
                        if($flag)
                        {
                            $this->session->set_flashdata('success', 'Customer has been created successfully also in Quickbook.');    
                        }
                        else
                        {
                            if (!$this->input->is_ajax_request()) 
                            {
                                $this->session->set_flashdata('success', 'Customer has been created in Quickbook successfully.');
                                $last_url      = base_url() . 'quickbook/customer';
                                if ($this->agent->referrer() == $last_url)
                                {
                                    $refer     =  $this->agent->referrer();
                                    redirect($refer);
                                }
                                else
                                {
                                    redirect('customers');
                                }
                            }

                        }
                    }
                }
            }
            catch (Exception $e) 
            {
                // echo $e->getMessage();
                $this->session->set_flashdata('error', $customer_id . "  " . 'Customer name "'. $customer_detail['display_name_as'] . '" For ' . $e->getMessage());
                 if ($this->input->is_ajax_request()) 
                {
                    $data['error']      = "error";
                    echo json_encode($data);
                    exit();
                }
                else
                {
                    redirect('customers');
                }
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'Please login to Quickbook! Your session has been exprire.');
            if ($this->input->is_ajax_request()) 
            {
                $data['session']        = "exprired"; 
                echo json_encode($data); 
                exit(); 
            }
            else
            {
                redirect('customers');
            }
        }
    }

 /**
  * [uniq_cust_name Create unique customername to add in quickbook]
  * @author KBH
  */
    public function uniq_cust_name()
    {   
        $query                  = $this->db->query("SELECT id, first_name, last_name, created_date from customers");
        $result                 = $query->result_array();
        foreach ($result as $value) {
            // echo $value['created_date'];
            $date               = date_create($value['created_date']);
            $created_date       = date_format($date, 'Y-m-d');
            $hour               = date_format($date, 'H');
            $min                = date_format($date, 'i');
            $sec                = date_format($date, 's');
            $new                = $value['first_name'] . "_" . $value['last_name'] . "(" . $created_date . " " . $hour . "-" . $min . "-" . $sec . ")";
            $query              = $this->db->query("UPDATE customers SET customer_uniq_name = '" . $new."' WHERE id = " . $value['id']);
        }
    }
}
