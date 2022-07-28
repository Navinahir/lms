<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/dashboard_model', 'admin/product_model', 'admin/inventory_model'));
    }

    /**
     * This is default function
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function index() {
        if ((!$this->session->userdata('logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect(base_url(), 'refresh');
        }
        $data['title'] = 'Dashboard';
        $data['companyArr'] = $this->dashboard_model->get_all_details(TBL_COMPANY, array('is_delete' => 0, 'status' => 'active'),array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $data['yearArr'] = $this->dashboard_model->get_all_details(TBL_YEAR, array('is_delete' => 0))->result_array();
        $this->template->load('default', 'login/dashboard', $data);
    }

    /**
     * When change in make dropdown of dashboard
     * @param --
     * @return Object (Json Format)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function change_make_get_ajax() {
        $make_id = $this->input->post('id');
        $modelArr = $this->product_model->get_all_details(TBL_MODEL, array('make_id' => $make_id, 'status' => 'active', 'is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $option = "<option></option>";
        foreach ($modelArr as $k => $v) {
            $option .= "<option value='" . $v['id'] . "'>" . $v['name'] . "</option>";
        }
        echo json_encode($option);
        die;
    }

    /**
     * Get all the transponder details on the basis of make, model, year
     * @param --
     * @return Object (Json Format)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_transponder_details() {
        $make_id = $this->input->post('_make_id');
        $model_id = $this->input->post('_model_id');
        $year_id = $this->input->post('_year_id');
        $condition = array(
            't.make_id' => $make_id,
            't.model_id' => $model_id,
            't.year_id' => $year_id
        );
        $transponder_result = $this->dashboard_model->get_transponder_details($condition)->row_array();
        $table_body = $div_part_list = $div_tool_list = $table_body2 = '';
        if (!empty($transponder_result)) {
            $table_body = '<tbody>';
            $table_body .= '<tr><td width="30%"><b>Make</b></td><td>' . $transponder_result['make_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Model</b></td><td>' . $transponder_result['model_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Year</b></td><td>' . $transponder_result['year_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Transponder Equipped</b></td><td>' . ucfirst($transponder_result['transponder_equipped']) . '</td></tr>';
            $table_body .= '<tr><td><b>Key Type</b></td><td>' . str_replace('_', ' ', join(', ', array_map('ucfirst', explode(',', $transponder_result['key_value'])))) . '</td></tr>';
            $table_body .= '<tr><td><b>System</b></td><td>' . $transponder_result['mvp_system'] . '</td></tr>';
            $table_body .= '<tr><td><b>Pincode Required</b></td><td>' . ucfirst($transponder_result['pincode_required']) . '</td></tr>';
            $table_body .= '<tr><td><b>Pincode Reading Available</b></td><td>' . $transponder_result['pincode_reading_available'] . '</td></tr>';
            $table_body .= '<tr><td><b>Key Onboard Progaming</b></td><td>' . $transponder_result['key_onboard_progaming'] . '</td></tr>';
            $table_body .= '<tr><td><b>Remote Onboard Progaming</b></td><td>' . $transponder_result['remote_onboard_progaming'] . '</td></tr>';
            $table_body .= '<tr><td><b>Test Key</b></td><td>' . ((!empty($transponder_result['test_key'])) ? $transponder_result['test_key'] : '') . '</td></tr>';
            $table_body .= '<tr><td><b>IIco</b></td><td>' . $transponder_result['iico'] . '</td></tr>';
            $table_body .= '<tr><td><b>JET</b></td><td>' . ((!empty($transponder_result['jet'])) ? $transponder_result['jet'] : '') . '</td></tr>';
            $table_body .= '<tr><td><b>JMA</b></td><td>' . $transponder_result['jma'] . '</td></tr>';
            $table_body .= '<tr><td><b>Keyline</b></td><td>' . $transponder_result['keyline'] . '</td></tr>';
            $table_body .= '<tr><td><b>Strattec Non-Remote Key</b></td><td>' . $transponder_result['strattec_non_remote_key'] . '</td></tr>';
            $table_body .= '</tbody>';
            $table_body2 = '<tbody>';
            $table_body2 .= '<tr><td width="30%"><b>Strattec Remote Key</b></td><td>' . $transponder_result['strattec_remote_key'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>OEM Non-Remote Key</b></td><td>' . $transponder_result['oem_non_remote_key'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>OEM Remote Key</b></td><td>' . $transponder_result['oem_remote_key'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Other</b></td><td>' . $transponder_result['other_non_remote_key'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>FCC ID#</b></td><td>' . $transponder_result['fcc_id'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>IC#</b></td><td>' . $transponder_result['ic'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Frequency</b></td><td>' . $transponder_result['frequency'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Code Series</b></td><td>' . $transponder_result['code_series'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Chip ID</b></td><td>' . $transponder_result['chip_ID'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Transponder Re-Use</b></td><td>' . $transponder_result['transponder_re_use'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Max No of Keys</b></td><td>' . $transponder_result['max_no_of_keys'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Key Shell</b></td><td>' . $transponder_result['key_shell'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Notes</b></td><td>' . $transponder_result['notes'] . '</td></tr>';
            $transponder_result['field_name'] = rtrim($transponder_result['field_name'], ':-:');

            /* if ($transponder_result['field_name'] != '') {
              $field_name_Arr = explode(':-:', $transponder_result['field_name']);
              $field_value_Arr = explode(':-:', $transponder_result['field_value']);
              foreach ($field_name_Arr as $k => $v) {
              $table_body2.='<tr><td><b>' . ucwords($v) . '</b></td><td>' . $field_value_Arr[$k] . '</td></tr>';
              }
              } */

            $transponder_id = $transponder_result['id'];
            $transponder_additional_data = $this->product_model->get_all_details(TBL_TRANSPONDER_ADDITIONAL, array('transponder_id' => $transponder_id, 'is_delete' => 0))->result_array();

            if (!empty($transponder_additional_data)) {
                foreach ($transponder_additional_data as $v) {
                    $table_body2 .= '<tr><td><b>' . $v['field_name'] . '</b></td><td>' . $v['field_value'] . '</td></tr>';
                }
            }

            $table_body2 .= '</tbody>';
            $parts_no_Arr = explode(':-:', $transponder_result['parts_no']);
            $parts_id_Arr = explode(':-:', $transponder_result['parts_id']);
            $parts_stock_Arr = explode(':-:', $transponder_result['qty_on_hand']);
            $vendor_name_Arr = explode(':-:', $transponder_result['vendor_name']);

            $part_list_Arr = [];
            foreach ($vendor_name_Arr as $k => $v) {
//                if ($parts_stock_Arr[$k] > 0) {
                $part_list_Arr[$v][] = '<a href="javascript:void(0);" class="btn_home_item_view" title="View" id="' . base64_encode($parts_id_Arr[$k]) . '"><span class="label label-primary bg-blue" style="margin:3px;font-size:12px;font-family:monospace;">' . $parts_no_Arr[$k] . '</span></a>'
                        . '&nbsp;-&nbsp;<span class="label label-info view-part-image" data-id="' . base64_encode($parts_id_Arr[$k]) . '" style="margin:3px;font-size:12px;font-family:monospace;"><i class="icon icon-image3"></i></span>&nbsp;';
//                } else {
//                $part_list_Arr[$v][] = '<a href="javascript:void(0);" class="btn_home_item_view" title="View" id="' . base64_encode($parts_id_Arr[$k]) . '"><span class="label label-primary" style="margin:3px;font-size:12px;font-family:monospace;">' . $parts_no_Arr[$k] . '</span></a>';
//                }
            }

            $vendor_name = '';
            $cnt = 0;
            foreach ($part_list_Arr as $k => $v) {
                if ($vendor_name != $k) {
                    if ($cnt > 0) {
                        $div_part_list .= '<hr style="border-top: 2px solid #ddd;margin: 10px 0;">';
                    }
                    $vendor_name = $k;
                    $div_part_list .= '<h5 style="margin-bottom:0px">' . $k . '</h5>';
                    $cnt++;
                }
                foreach ($v as $key => $value) {
                    $div_part_list .= $value;
                }
            }
            $toolArr = $this->inventory_model->get_tool_details()->result_array();
            if ($transponder_result['tools'] != null) {
                $tools = explode(',', $transponder_result['tools']);
                $tool_details = explode(':-:', $transponder_result['tool_details']);
                if (!empty($tools)) {
                    foreach ($tools as $k => $t) {
                        $key = array_search($t, array_column($toolArr, 'id'));
                        $content = (isset($tool_details[$k])) ? $tool_details[$k] : '';
                        $div_tool_list .= '<div class="col-md-4 tool_div custom_scrollbar" id="div_' . $t . '">' .
                                '<div class="panel panel-primary panel-bordered">' .
                                '<div class="panel-heading">' .
                                '<h6 class="panel-title">' . $toolArr[$key]["equip_name"] . ' (' . $toolArr[$key]['manu_name'] . ')</h6>' .
                                '</div>' .
                                '<div class="panel-body">' .
                                $content .
                                '</div>' .
                                '</div>' .
                                '</div>';
                    }
                }
            }
            // foreach($parts_no_Arr as $k => $v){
            //     if($parts_stock_Arr[$k]>0){
            //         $div_part_list.='<a href="javascript:void(0);" class="btn_home_item_view" title="View" id="'.base64_encode($parts_id_Arr[$k]).'"><span class="label label-success" style="margin:3px;font-size:12px;letter-spacing:1px;font-family:monospace;">'.$v.'</span></a>';
            //     }else{
            //         $div_part_list.='<a href="javascript:void(0);" class="btn_home_item_view" title="View" id="'.base64_encode($parts_id_Arr[$k]).'"><span class="label label-danger" style="margin:3px;font-size:12px;letter-spacing:1px;font-family:monospace;">'.$v.'</span></a>';
            //     }
            // }
        } else {
            $table_body = '<tbody>';
            $table_body .= '<tr><td class="text-center"><h1 style="font-weight: 500 !important;color: #b5b3b3 !important;">No Such Data Found.</h1></td></tr>';
            $div_part_list = 'No Data Exists';
        }
        $result = array(
            'table_body' => $table_body,
            'table_body2' => $table_body2,
            'div_part_list' => $div_part_list,
            'div_tool_list' => $div_tool_list
        );
        echo json_encode($result);
        exit;
    }

    public function get_transponder_item_years() {
        $make_id = $this->input->post('make_id');
        $model_id = $this->input->post('model_id');

        $part_array = array(
            't.is_delete' => 0,
            't.make_id' => $make_id,
            't.model_id' => $model_id,
        );

        $this->db->select('y.id,y.name');
        $this->db->from(TBL_TRANSPONDER . ' as t');
        $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id', 'left');
        $this->db->where($part_array);
        $this->db->order_by('y.name', 'asc');
        $year_data = $this->db->get()->result_array();

        $option = "<option></option>";

        if (!empty($year_data)) {
            foreach ($year_data as $k => $v) {
                $option .= "<option value='" . $v['id'] . "'>" . $v['name'] . "</option>";
            }
        }
        echo json_encode($option);
        die;
    }

    public function get_item_image() {
        $image_path = site_url('assets/images/no_image.jpg');
        $title = '';

        $item_id = base64_decode($this->input->post('item_id'));

        $item_array = array(
            'id' => $item_id,
        );

        $this->db->select('*');
        $this->db->from(TBL_ITEMS);
        $this->db->where($item_array);
        $item_data = $this->db->get()->row_array();

        if (!empty($item_data) && !empty($item_data['image'])) {
            $path = './uploads/items/' . $item_data['image'];
            if (file_exists($path)) {
                $image_path = site_url('uploads/items/' . $item_data['image']);
            }
        }

        $data['image_path'] = $image_path;
        echo json_encode($data);
        exit;
    }

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */