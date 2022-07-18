<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class History extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/users_model', 'admin/inventory_model'));
    }

    /**
     * Home Page
     * @param --
     * @return --
     * @author HPA [Last Edited : 02/06/2018]
     */
    public function index() {
        if ((!$this->session->userdata('vendor_logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('/vendor/login', 'refresh');
        }
        $data['title'] = 'History';
        $this->template->load('default_vendor', 'vendor/history/history_display', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_history_data() {
        $final['recordsTotal'] = $this->inventory_model->get_vendor_history_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->inventory_model->get_vendor_history_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['created_date'] = date('l, F, d, Y h:i A', strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['date'] = date('h:i A l M, d, Y', strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get Item's data by its ID
     * @param --
     * @return HTML data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_item_data_ajax_by_id() {
        $part_id = base64_decode($this->input->post('id'));
        $this->db->select('i.*,d.name as dept_name,v1.name as v1_name,it.total_quantity,i.manufacturer');
        $this->db->from(TBL_USER_ITEMS . ' AS i');
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.vendor_id=v1.id', 'left');
        $this->db->where('i.id', $part_id);
        $result = $this->db->get();
        $data['viewArr'] = $result->row_array();
//        if (!empty($data['viewArr'])) {
//            $locations = $this->inventory_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array();
//            pr($locations);
//            $this->db->select('il.*');
//            $this->db->where(['il.item_id' => $part_id, 'il.is_deleted' => 0]);
//            $location_qty = $this->db->get(TBL_ITEM_LOCATION_DETAILS . ' il')->result_array();
//            foreach ($locations as $k => $l) {
//                if (($key = (array_search($l['id'], array_column($location_qty, 'location_id')))) !== false) {
//                    $l['location_quantity'] = $location_qty[$key]['quantity'];
//                } else {
//                    $l['location_quantity'] = 0;
//                }
//                $locations[$k] = $l;
//            }
//            $data['viewArr']['locations'] = $locations;
//        }
//        pr($data['viewArr'], 1);
        //$data['viewArr'] = $this->inventory_model->get_all_details(TBL_ITEMS,array('id'=>$part_id))->row_array();
        return $this->load->view('vendor/partial_view/view_report_search_data', $data);
        die;
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */