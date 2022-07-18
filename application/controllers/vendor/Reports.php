<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {

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
        $data['title'] = 'Reports';
        $this->template->load('default_vendor', 'vendor/items/report_display', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_items_data() {
        $final['recordsTotal'] = $this->inventory_model->get_vendor_reports_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->inventory_model->get_vendor_reports_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
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
        $this->db->select('i.*,d.name as dept_name,v1.name as v1_name,GROUP_CONCAT(CONCAT(c.name, " ", `m`.`name`, " ", y.name)) as compatibility');
        $this->db->from(TBL_ITEMS . ' AS i');
        $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.preferred_vendor=v1.id', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
        $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_COMPANY . ' as c', 'c.id=t.make_id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 'm.id=t.model_id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 'y.id=t.year_id and y.is_delete=0', 'left');
        $this->db->where('i.id', $part_id);
        $result = $this->db->get();
        $data['viewArr'] = $result->row_array();
        return $this->load->view('vendor/partial_view/view_search_data', $data);
        die;
    }

    public function parts_compatability() {
        if ((!$this->session->userdata('vendor_logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('/vendor/login', 'refresh');
        }
        $data['title'] = 'Part Compatibility Count';

        $data['companies'] = $this->inventory_model->get_all_details(TBL_COMPANY, array('is_delete' => 0))->result_array();
        $data['models'] = $this->inventory_model->get_all_details(TBL_MODEL, array('is_delete' => 0))->result_array();
        $this->template->load('default_vendor', 'vendor/reports/display_part_compatibility_counts', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_parts_compatability_data_count() {
        $format = MY_Controller::$date_format;
        $where = [];

        if (!empty($this->input->get('make_id'))) {
            $where['t.make_id'] = $this->input->get('make_id');
        }

        if (!empty($this->input->get('model_id'))) {
            $where['t.model_id'] = $this->input->get('model_id');
        }
        
        $final['recordsTotal'] = $this->inventory_model->get_parts_compatability_data('count', $where);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];

        $items = $this->inventory_model->get_parts_compatability_data('result', $where);

        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */