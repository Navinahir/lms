<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {

    public function __construct() {
        parent::__construct();
        ini_set('memory_limit', '-1');
        $this->load->model(array('admin/product_model', 'admin/reports_model','admin/dashboard_model', 'admin/inventory_model', 'admin/estimate_model', 'admin/invoice_model'));
    }

    public function display() {
        $data['title'] = 'Admin | List Item Media Reports';
        $this->template->load('default', 'admin/items_report/display_items_report', $data);
    }

    /**
     * Displaying data of Item Media Reports
     * @param --
     * @return --
     * @author JJP [Last Edited : 13/07/2019]
     */
    public function get_item_report() {
        $where = [];
        $final['recordsTotal'] = $this->reports_model->get_item_report('count', $where);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];


        $items = $this->reports_model->get_item_report('result', $where);
       
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['created_date'] = date('m-d-Y h:i A', strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';

            $imgpath = 'uploads/items/';
            if(!file_exists($imgpath.$items[$key]['image']) or $items[$key]['image'] == "" or $items[$key]['image'] == NULL)
            {
                $items[$key]['image'] = 'fail';
            } 
            else 
            {
                $items[$key]['image'] = $items[$key]['image'] ;
            }

            $path = 'assets/qr_codes/';
            if(!file_exists($path.$items[$key]['item_qr_code']))
            {
                $items[$key]['item_qr_code'] = 'fail';
            } 
            else if($items[$key]['item_qr_code'] == "" || $items[$key]['item_qr_code'] == NULL)
            {
                $items[$key]['item_qr_code'] = 'fail';
            }
            else 
            {
                $items[$key]['item_qr_code'] = $items[$key]['item_qr_code'] ;
            }    

        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Displaying data of transponder
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_transponder_report() {
        $data['title'] = 'Reports - Transponder';
        // $condition = array();
        // $data['dataArr'] = $this->reports_model->get_transponder_report($condition)->result_array();
        $this->template->load('default', 'admin/reports/transponder', $data);
    }

    /**
     * Used to do get transponder's reports data by ajax as per filtration
     * @param --
     * @return Json data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_transponder_report_ajax() {
        $txt_search = htmlentities($this->input->post('txt_search'));
        $txt_strattec_part = htmlentities($this->input->post('txt_strattec_part'));
        $txt_status = htmlentities($this->input->post('txt_status'));
        $condition = array();
        $condition['keyword'] = $txt_search;
        $condition['strattec_non_remote_key'] = $txt_strattec_part;
        $condition['status'] = $txt_status;

        $dataArr = $this->reports_model->get_transponder_report($condition)->result_array();
        $tbl_body = '';
        foreach ($dataArr as $k => $v) {
            if ($v['qty_on_hand'] > 0) {
                $ava = '<span class="label bg-success-400">IN STOCK</span>';
            } else {
                $ava = '<span class="label bg-danger-400">OUT OF STOCK</span>';
            }
            $cnt = $k + 1;
            $tbl_body.= '<tr>';
            $tbl_body.= '<td>' . $cnt . '</td>';
            $tbl_body.= '<td>' . $v['year_name'] . '</td>';
            $tbl_body.= '<td>' . $v['make_name'] . ' ' . $v['model_name'] . '</td>';
            $tbl_body.= '<td>' . $ava . '</td>';
            $tbl_body.= '</tr>';
        }
        echo json_encode($tbl_body);
        // echo $tbl_body; die();
        die;
    }

    /**
     * Disaply Transponder listing
     * @param --
     * @return --
     * @author JJP [Last Edited : 18/02/2020]
     */
    public function application_reports_display() {
        $data['title'] = 'Admin | List Of Applicaton Data Reports';
        $columns = array_column($this->db->query("SHOW COLUMNS FROM " . TBL_TRANSPONDER)->result_array(), 'Field');
        $columns_Arr = array_values(array_diff($columns, array('id', 'make_id', 'model_id', 'year_id', 'modified_date', 'created_date', 'status', 'is_delete')));
        $data['columns_Arr'] = $columns_Arr;
        $data['companyArr'] = $this->dashboard_model->get_all_details(TBL_COMPANY, array('is_delete' => 0, 'status' => 'active'),array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $this->template->load('default', 'admin/application_report/application_data_report', $data);
    }   

    /**
     * Get Transponder data by ajax and displaying in datatable while displaying
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 18/02/2018]
     */
    public function get_transponder() {
        $final['recordsTotal'] = $this->reports_model->get_transponder('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $transponder = $this->reports_model->get_transponder('result')->result_array();
        $start = $this->input->get('start') + 1;
        $itemArr = $this->reports_model->get_item_details()->result_array();
        foreach ($transponder as $key => $val) {
            $percentage = 0;
            $transponder[$key] = $val;
            $transponder[$key]['sr_no'] = $start++;
            $transponder[$key]['responsive'] = '';
            $transponder[$key]['part_attached'] = $this->product_model->get_all_details(TBL_TRANSPONDER_ITEMS, array('transponder_id' => $val['id']))->result_array();
            $transponder[$key]['part_attached_id'] = array_column($transponder[$key]['part_attached'], 'items_id');
            $transponder[$key]['total_part_attached_id'] = 0;
            foreach ($itemArr as $k => $item) {
                if(in_array($item['id'], $transponder[$key]['part_attached_id'])) {
                    $transponder[$key]['total_part_attached_id'] ++;
                }
            } 
            if($val['transponder_equipped'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['key_value'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['mvp_system'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['pincode_required'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['pincode_reading_available'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['key_onboard_progaming'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['remote_onboard_progaming'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['test_key'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['iico'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['jet'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['jma'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['keyline'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['strattec_non_remote_key'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['strattec_remote_key'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['oem_non_remote_key'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['oem_remote_key'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['other'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['code_series'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['fcc_id'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['ic'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['frequency'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['chip_ID'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['transponder_re_use'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['max_no_of_keys'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['key_shell'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['notes'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['tools'] != "")
            {
                $tools = $val['tools'];
                $tools_array = explode(",", $tools);
                $total_tools = count($tools_array);
            } else {
                $total_tools = 0;
            }
            $transponder[$key]['total_tools'] = $total_tools;
            $transponder[$key]['percentage'] = round($percentage).'%';
        }
        $final['data'] = $transponder;
        echo json_encode($final);
        die;
    }

    /**
     * Get Transponder data by ajax and displaying in datatable while displaying
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 18/02/2018]
     */
    public function get_transponder_year_filter() {
        $final['recordsTotal'] = $this->reports_model->get_transponder_year_filter('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $transponder = $this->reports_model->get_transponder_year_filter('result')->result_array();
        $start = $this->input->get('start') + 1;
        $itemArr = $this->reports_model->get_item_details()->result_array();
        foreach ($transponder as $key => $val) {
            $percentage = 0;
            $transponder[$key] = $val;
            $transponder[$key]['sr_no'] = $start++;
            $transponder[$key]['responsive'] = '';
            $transponder[$key]['part_attached'] = $this->product_model->get_all_details(TBL_TRANSPONDER_ITEMS, array('transponder_id' => $val['id']))->result_array();
            $transponder[$key]['part_attached_id'] = array_column($transponder[$key]['part_attached'], 'items_id');
            $transponder[$key]['total_part_attached_id'] = 0;
            foreach ($itemArr as $k => $item) {
                if(in_array($item['id'], $transponder[$key]['part_attached_id'])) {
                    $transponder[$key]['total_part_attached_id'] ++;
                }
            } 
            if($val['transponder_equipped'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['key_value'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['mvp_system'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['pincode_required'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['pincode_reading_available'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['key_onboard_progaming'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['remote_onboard_progaming'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['test_key'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['iico'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['jet'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['jma'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['keyline'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['strattec_non_remote_key'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['strattec_remote_key'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['oem_non_remote_key'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['oem_remote_key'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['other'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['code_series'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['fcc_id'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['ic'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['frequency'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['chip_ID'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['transponder_re_use'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['max_no_of_keys'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['key_shell'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['notes'] != "") { $percentage += 3.846; } else { $percentage += 0; }
            if($val['tools'] != "")
            {
                $tools = $val['tools'];
                $tools_array = explode(",", $tools);
                $total_tools = count($tools_array);
            } else {
                $total_tools = 0;
            }
            $transponder[$key]['total_tools'] = $total_tools;
            $transponder[$key]['percentage'] = round($percentage).'%';
        }
        $final['data'] = $transponder;
        echo json_encode($final);
        die;
    }

}

/* End of file Reports.php */
/* Location: ./application/controllers/Reports.php */