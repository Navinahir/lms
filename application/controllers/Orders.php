<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Mpdf\Mpdf;

class Orders extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/estimate_model', 'admin/users_model', 'admin/dashboard_model', 'admin/invoice_model', 'admin/inventory_model', 'admin/orders_model'));
    }

    /**
     * Display All items 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function display_orders() {
        $data['title'] = 'List of Orders';
        $data['statuses'] = $this->users_model->get_all_details(TBL_STATUS, ['is_deleted' => 0, 'module_type' => 'orders'])->result_array();
        $this->template->load('default_front', 'front/orders/order_display', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_order_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->orders_model->get_orders_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $where = [];

        if (!empty($this->input->get('status_id'))) {
            $where = array('o.status_id' => $this->input->get('status_id'));
        }

        $items = $this->orders_model->get_orders_data('result', $where);

        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            // $items[$key]['ordered_date'] = date($format['format'], strtotime($val['ordered_date']) + $_COOKIE['currentOffset']);
            $items[$key]['ordered_date'] = date($format['format'], strtotime($val['ordered_date']));
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_customer_order_data($cname) {
        $cname = urldecode($cname);
        $format = MY_Controller::$date_format;
        $where_cnt = array('o.customer_name' => $cname);
        $final['recordsTotal'] = $this->orders_model->get_customer_order_data('count', $where_cnt);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $where = [];

        if (!empty($this->input->get('status_id'))) {
            $where = array('o.status_id' => $this->input->get('status_id'), 'o.customer_name' => $cname);
        }else{
            $where = array('o.customer_name' => $cname);
        }

        $items = $this->orders_model->get_customer_order_data('result', $where);
        
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['ordered_date'] = date($format['format'], strtotime($val['ordered_date']) + $_COOKIE['currentOffset']);
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
    public function edit_orders($id = null) {
        $data['part_details'] = [];

        $total_orders = $this->orders_model->get_all_details(TBL_ORDER, [])->num_rows();
        $data = array(
            'title' => 'Add Order',
            'format' => MY_Controller::$date_format,
            'currency' => MY_Controller::$currency,
            'order_no' => 'ODR-' . str_pad(($total_orders + 1), 6, '0', STR_PAD_LEFT),
            'users' => $this->users_model->total_users(1),
            'payment_methods' => $this->dashboard_model->get_all_details(TBL_PAYMENT_METHODS, array('is_deleted' => 0))->result_array(),
            'statuses' => $this->users_model->get_all_details(TBL_STATUS, ['is_deleted' => 0, 'module_type' => 'orders'])->result_array(),
            'business_users' => $this->users_model->get_active_business_users('result'),
            'items_records' => $this->get_item_records(),
            'vendors' => $this->users_model->get_all_details(TBL_VENDORS, ['is_delete' => 0])->result_array(),
            'customers' => $this->estimate_model->get_all_details(TBL_CUSTOMERS, ['is_deleted' => 0, 'added_by' => checkUserLogin('C')])->result_array(),
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
            $data['title'] = 'Edit Orders';
            $data['dataArr'] = $this->orders_model->get_all_details(TBL_ORDER, ['id' => $record_id])->row_array();
        }

        if ($this->input->post()) {
            $orderArr = array(
                'order_taken_user_id' => $this->input->post('ordered_taken_user_id'),
                'order_given_user_id' => $this->input->post('ordered_given_user_id'),
                'customer_name' => $this->input->post('customer_name'),
                'customer_phone' => $this->input->post('customer_phone'),
                'description' => $this->input->post('description'),
                'vendor_id' => $this->input->post('ordered_from_id'),
                'vendor_part_no' => trim($this->input->post('vendor_part')),
                'quoted_price' => $this->input->post('quoted_price'),
                'total_receipt_amount' => $this->input->post('total_receipt_amount'),
                'paid_for' => $this->input->post('paid_for'),
                'payment_method_id' => $this->input->post('payment_method_id'),
                'status_id' => $this->input->post('status'),
                'payment_notes' => trim($this->input->post('payment_notes')),
                'order_id' => trim($this->input->post('order_id')),
            );

            if (!is_null($id)) {
                $estimate_id = $this->orders_model->insert_update('update', TBL_ORDER, $orderArr, array('id' => $record_id));

                if ($estimate_id) {
                    $this->session->set_flashdata('success', 'Order has been updated successfully.');
                }
            } else {
                $orderArr['order_no'] = $this->input->post('order_no');
                $orderArr['ordered_date'] = $this->input->post('ordered_date_submit');
                $orderArr['current_date'] = $this->input->post('current_date_submit');
                $orderArr['receipt_no'] = $this->input->post('receipt_number');
                $orderArr['created_date'] = date('Y-m-d h:i:s');
                $orderArr['added_by'] = checkUserLogin('I');

                $estimate_id = $this->orders_model->insert_update('insert', TBL_ORDER, $orderArr);

                if ($estimate_id) {
                    $this->session->set_flashdata('success', 'Order has been created successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Order has not been created.');
                }
            }

            if($referred_from = $this->session->userdata('referred_from'))
            {
                redirect($referred_from, 'refresh');    
            }
            else 
            {
               redirect('orders');
            }        
        }
        $this->template->load('default_front', 'front/orders/order_add', $data);
    }

    /**
     * Delete Items
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function delete_orders($id = '') {
        $record_id = base64_decode($id);
        $this->orders_model->insert_update('update', TBL_ORDER, array('is_deleted' => 1), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Order has been deleted successfully.');
        redirect('orders');
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
            $this->db->select('i.*,d.name as dept_name,v1.name as v1_name,it.total_quantity,il.quantity as location_quantity,il.location_id as selected_loation_id');
            $this->db->from(TBL_USER_ITEMS . ' AS i');
            $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
            $this->db->join(TBL_VENDORS . ' AS v1', 'i.vendor_id=v1.id', 'left');
            $this->db->join(TBL_ITEM_LOCATION_DETAILS . ' AS il', 'il.item_id=i.id AND il.location_id =' . $location_id, 'left');
            $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
            $this->db->where(['i.business_user_id' => checkUserLogin('C'), 'i.is_delete' => 0]);
            $this->db->having('location_quantity > 0 AND location_quantity IS NOT NULL');
            if ($itsfor != '') {
                $this->db->where('i.id', $part_id);
                $result = $this->db->get();
                $data['viewArr'] = $result->row_array();
            } else {
                $this->db->like('i.part_no', $part_id);
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
     * View Order
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
        $this->template->load('default_front', 'front/invoices/invoice_view', $data);
    }

    /**
     * Get Orders data by its ID
     * @param --
     * @return HTML data
     * @author HGA [Created AT : 27/11/2019]
     */
    public function get_order_data_ajax_by_id() {
        $data['result'] = array();
        $data['format'] = MY_Controller::$date_format;
        $order_id = base64_decode($this->input->post('id'));
        $this->db->select('o.*, s.status_name, v.name as vendor_name, order_taken.full_name as order_taken_name, order_given.full_name as order_given_name, pm.name as payment_method_name');
        $this->db->from(TBL_ORDER . ' AS o');
        $this->db->where(['o.is_deleted' => 0, 'o.id' => $order_id]);
        $this->db->join(TBL_STATUS . ' AS s', 'o.status_id=s.id', 'left');
//        $this->db->join(TBL_ITEMS . ' AS i', 'o.item_id=i.id', 'left');
        $this->db->join(TBL_VENDORS . ' AS v', 'o.vendor_id=v.id', 'left');
        $this->db->join(TBL_USERS . ' AS order_taken', 'o.order_taken_user_id=order_taken.id', 'left');
        $this->db->join(TBL_USERS . ' AS order_given', 'o.order_given_user_id=order_given.id', 'left');
        $this->db->join(TBL_PAYMENT_METHODS . ' AS pm', 'o.payment_method_id=pm.id', 'left');
        $result = $this->db->get()->result_array();

        if ($result) {
            $data['result'] = $result[0];
        }

        return $this->load->view('front/partial_view/view_order_data', $data);
    }

    /**
     * Get Item's data by search value
     * @param --
     * @return JSOn data
     * @author HPA [Last Edited : 17/09/2018]
     */
    public function get_item_records() {
        //User Parts
        $this->db->select('v1.id as vendor_id, i.part_no,v1.name, i.id as item_id');
        $this->db->from(TBL_USER_ITEMS . ' AS ui');
        $this->db->where(['ui.business_user_id' => checkUserLogin('C'), 'ui.is_delete' => 0, 'i.is_delete' => 0]);
        $this->db->join(TBL_ITEMS . ' AS i', 'ui.referred_item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.preferred_vendor=v1.id', 'left');
        $result = $this->db->get()->result_array();

        if (empty($result)) {
            //Global Parts
            $this->db->select('v1.id as vendor_id, i.part_no,v1.name, i.id as item_id');
            $this->db->from(TBL_ITEMS . ' AS i');
            $this->db->where(['i.is_delete' => 0]);
            $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
            $this->db->join(TBL_VENDORS . ' AS v1', 'i.preferred_vendor=v1.id', 'left');
            $result = $this->db->get()->result_array();
        }

        return $result;
    }

    public function change_order_status() {
        $status_id = $this->input->post('status_id');
        $order_id = $this->input->post('order_id');

        $is_updated = $this->orders_model->insert_update('update', TBL_ORDER, array('status_id' => $status_id), array('id' => $order_id));

        if ($is_updated) {
            $is_updated = 1;
        } else {
            $is_updated = 0;
        }

        echo $is_updated;
        exit;
    }

    public function check_part() {
        $part_details = [];

        $vendor_part = $this->input->post('vendor_part');
        $part_details = $this->users_model->get_all_details(TBL_ITEMS, ['is_delete' => 0, 'part_no' => $vendor_part])->result_array();

        if (!empty($part_details)) {
            $part_details = $part_details[0];
        }

        echo json_encode($part_details);
        exit;
    }

}

/* End of file Inventory.php */
/* Location: ./application/controllers/Inventory.php */