<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleSearch extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/dashboard_model', 'admin/product_model', 'admin/inventory_model', 'admin/estimate_model', 'admin/invoice_model', 'admin/content_model', 'admin/blogs_model'));
        header('Access-Control-Allow-Origin: *');
    }

    public function demo(){
        $this->load->view('api/partial_view/List_of_part');
    }

    public function index($vendor_id) {
        if (!empty($vendor_id)) {
            $data['app_url'] = base_url();
            $data['companyArr'] = $this->dashboard_model->get_all_details(TBL_COMPANY, array('is_delete' => 0, 'status' => 'active'),array(array('field' => 'name', 'type' => 'asc')))->result_array();
            $data['yearArr'] = $this->dashboard_model->get_all_details(TBL_YEAR, array('is_delete' => 0))->result_array();
            $data['estimateArr'] = $this->estimate_model->get_recent_estimates_data();
            $data['invoiceArr'] = $this->invoice_model->get_recent_invoices_data();
            $data['vendor_id'] = $vendor_id;
            $data['custom_class'] = md5(rand(985478475, 100000000000000));

            if (isset($_COOKIE['currentOffset'])) {
                foreach ($data['estimateArr'] as $key => $val) {
                    $data['estimateArr'][$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
                }
                foreach ($data['invoiceArr'] as $key => $val) {
                    $data['invoiceArr'][$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
                }
            } else {
                foreach ($data['estimateArr'] as $key => $val) {
                    $data['estimateArr'][$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
                }
                foreach ($data['invoiceArr'] as $key => $val) {
                    $data['invoiceArr'][$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
                }
            }

            if ($this->input->get('make') && $this->input->get('model') && $this->input->get('year')) {
                $make_id = base64_decode($this->input->get('make'));
                $model_id = base64_decode($this->input->get('model'));
                $year_id = base64_decode($this->input->get('year'));
                $data['searchData'] = [
                    'make_id' => $make_id,
                    'model_id' => $model_id,
                    'year_id' => $year_id,
                ];
                $data['modelArr'] = $this->product_model->get_all_details(TBL_MODEL, array('make_id' => $make_id, 'status' => 'active', 'is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
            }
            $data['searchArr'] = $this->dashboard_model->get_user_recent_search_details(TBL_RECENT_SEARCH_DETAILS, array('t.is_delete' => 0, 'business_user_id' => checkUserLogin('C')))->result_array();

            $search_html = $this->load->view('api/search', $data, TRUE);
        } else {
            $search_html = '<h2>Something went wrong, Please contact to admin.</h2>';
        }

        echo json_encode($search_html);
        exit;
    }

    /**
     * When change in make dropdown of Model
     * @param --
     * @return Object (Json Format)
     * @author HGA
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
     * When change in make dropdown of Year
     * @param --
     * @return Object (Json Format)
     * @author HGA
     */
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

    public function get_transponder_details() {
        $make_id = $this->input->post('_make_id');
        $model_id = $this->input->post('_model_id');
        $year_id = $this->input->post('_year_id');
        $vendor_id = $this->input->post('vendor_id');
        $condition = array(
            't.make_id' => $make_id,
            't.model_id' => $model_id,
            't.year_id' => $year_id,
        );
        
        $transponder_result = $this->dashboard_model->get_api_vendor_transponder_details($condition, $vendor_id)->row_array();
        // echo "<pre>";
        // print_r($transponder_result); die();
        $table_body = $div_part_list = $div_tool_list = '';
        if (!empty($transponder_result)) {
            $table_body = '<tbody>';
            $table_body .= '<tr><td><b>Make</b></td><td>' . $transponder_result['make_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Model</b></td><td>' . $transponder_result['model_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Year</b></td><td>' . $transponder_result['year_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Transponder Equipped</b></td><td>' . ucfirst($transponder_result['transponder_equipped']) . '</td></tr>';
            $table_body .= '<tr><td><b>Key Type</b></td><td>' . str_replace('_', ' ', join(', ', array_map('ucfirst', explode(',', $transponder_result['key_value'])))) . '</td></tr>';
            $table_body .= '<tr><td><b>System</b></td><td>' . $transponder_result['mvp_system'] . '</td></tr>';
            $table_body .= '<tr><td><b>Pincode Required</b></td><td>' . ucfirst($transponder_result['pincode_required']) . '</td></tr>';
            $table_body .= '<tr><td><b>Pincode Reading Available</b></td><td>' . $transponder_result['pincode_reading_available'] . '</td></tr>';
            $table_body .= '<tr><td><b>Key Onboard Progaming</b></td><td>' . $transponder_result['key_onboard_progaming'] . '</td></tr>';
            $table_body .= '<tr><td><b>Remote Onboard Progaming</b></td><td>' . $transponder_result['remote_onboard_progaming'] . '</td></tr>';
            $table_body .= '<tr><td><b>IIco</b></td><td>' . $transponder_result['iico'] . '</td></tr>';
            $table_body .= '<tr><td><b>JMA</b></td><td>' . $transponder_result['jma'] . '</td></tr>';
            $table_body .= '<tr><td><b>Keyline</b></td><td>' . $transponder_result['keyline'] . '</td></tr>';
            $table_body .= '<tr><td><b>Strattec Non-Remote Key</b></td><td>' . $transponder_result['strattec_non_remote_key'] . '</td></tr>';
            $table_body .= '</tbody>';
            $table_body2 = '<tbody>';
            $table_body2 .= '<tr><td><b>Strattec Remote Key</b></td><td>' . $transponder_result['strattec_remote_key'] . '</td></tr>';
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

            if ($transponder_result['field_name'] != '') {
                $field_name_Arr = explode(':-:', $transponder_result['field_name']);
                $field_value_Arr = explode(':-:', $transponder_result['field_value']);
                foreach ($field_name_Arr as $k => $v) {
                    $table_body2 .= '<tr><td><b>' . ucwords($v) . '</b></td><td>' . $field_value_Arr[$k] . '</td></tr>';
                }
            }
            $table_body2 .= '</tbody>';
            $parts_no_Arr = explode(':-:', $transponder_result['parts_no']);
            $parts_id_Arr = explode(':-:', $transponder_result['parts_id']);
            $parts_stock_Arr = explode(':-:', $transponder_result['qty_on_hand']);
            $vendor_name_Arr = explode(':-:', $transponder_result['vendor_name']);
            
            $part_list_Arr = [];
            foreach ($vendor_name_Arr as $k => $v) {
                if ($v != '') {

                    $part_id = $parts_id_Arr[$k];

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
                    
                    $this->db->select('c.name as company,m.name as model,y.name as year');
                    $this->db->from(TBL_ITEMS . ' AS i');
                    $this->db->join(TBL_VENDORS . ' AS v1', 'i.preferred_vendor=v1.id', 'left');
                    $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
                    $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
                    $this->db->join(TBL_COMPANY . ' as c', 'c.id=t.make_id and c.status="active" and c.is_delete=0', 'left');
                    $this->db->join(TBL_MODEL . ' as m', 'm.id=t.model_id and m.status="active" and m.is_delete=0', 'left');
                    $this->db->join(TBL_YEAR . ' as y', 'y.id=t.year_id and y.is_delete=0', 'left');
                    $this->db->where('i.id', $part_id);
                    $parts_result = $this->db->get();
                    $data['partArr'] = $parts_result->result_array();

                    if($data['viewArr']['image'] != "" && $data['viewArr']['image'] != NULL)
                    {
                        $partimg = '<img src="' . base_url('uploads/items/'.$data['viewArr']['image']) . '" style="height: 100px; width: 100px;"/>';
                    } else {
                        $partimg = '<img src="' . base_url('uploads/items/no_image.jpg') . '" style="height: 100px; width: 100px;" />';  
                    }

                    $part_list_Arr[$v][] = 
                            '<section>
                                <div class="list-data">
                                    <ul>
                                        <li>
                                            <div class="list-detail">
                                                <div class="">
                                                    <span class="label label-info view-part-image" data-id="' . base64_encode($parts_id_Arr[$k]) . '" style="margin:3px;font-size:12px; font-family:monospace;">
                                                        '.$partimg.'
                                                    </span>
                                                </div>
                                                <div class="list-info">
                                                    <h6 class="text-primary">'.$data['viewArr']['description'].'</h6>
                                                    <p>Item Part No: <span> '. $data['viewArr']['part_no'] .'</span></p>
                                                    <p>Alternate Part No or SKU: <span>'.$data['viewArr']['internal_part_no'].'</span></p>
                                                    <p>Department: <span>'.$data['viewArr']['dept_name'].'</span></p>
                                                    <p>Vendor: <span>'.$data['viewArr']['v1_name'].'</span></p>
                                                    <p>Manufacturer: <span>'.$data['viewArr']['manufacturer'].'</span></p>
                                                </div>
                                            </div>
                                            <div class="list-button">
                                                <a target="_parent" title="View" class="btn-1" href="' . $data['viewArr']['item_link'] . '" >View</a>
                                                <a href="javascript:void(0);" class="btn_home_item_view btn-2" title="Compatibility" id="' . base64_encode($parts_id_Arr[$k]) . '" >Compatibility</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </section>';

                    // .................
                    // $part_list_Arr[$v][] = 
                    //         '<a href="javascript:void(0);" class="btn_home_item_view" title="View" id="' . base64_encode($parts_id_Arr[$k]) . '">Image<span class="label label-success bg-blue" style="margin:3px;font-size:12px;font-family:monospace;">' . $parts_stock_Arr[$k] . '</span></a>'

                    //         . '<span class="label label-info view-part-image" data-id="' . base64_encode($parts_id_Arr[$k]) . '" style="margin:3px;font-size:12px;font-family:monospace;"><img src="' . base_url('api/assets/images/camera.jpg') . '" width="20px" height="16px" /></span>&nbsp; </br>'

                    //         .'<a href="javascript:void(0);" class="btn_home_item_view" title="View" id="' . base64_encode($parts_id_Arr[$k]) . '">Part<span class="label label-success bg-blue" style="margin:3px;font-size:12px;font-family:monospace;">' . $parts_no_Arr[$k] . '</span></a> <br/>'                         

                    //         .'Alternate Part No or SKU<span class="label label-success bg-blue" style="margin:3px;font-size:12px;font-family:monospace;">' .  $data['viewArr']['internal_part_no'] . '</span></a> </br>'

                    //         .'Item Description<span class="label label-success bg-blue" style="margin:3px;font-size:12px;font-family:monospace;">' .  $data['viewArr']['description'] . '</span></a> </br>'

                    //         .'Manufacturer<span class="label label-success bg-blue" style="margin:3px;font-size:12px;font-family:monospace;">' .  $data['viewArr']['manufacturer'] . '</span></a> <br/>'

                    //         .'<a href="javascript:void(0);" class="btn_home_item_view" title="View" id="' . base64_encode($parts_id_Arr[$k]) . '">View</a> <br/>';

                    // $this->load->view('api/partial_view/List_of_part',$data);



                }
            }

            $vendor_name = '';
            $cnt = 0;
            foreach ($part_list_Arr as $k => $v) {
                if ($vendor_name != $k) {
                    if ($cnt > 0) {
                        $div_part_list .= '<hr style="border-top: 2px solid #ddd;margin: 10px 0;">';
                    }
                    $vendor_name = $k;
                    $div_part_list .= '<h5 class="tempcls" style="margin-bottom:0px">' . $k . '</h5>';
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
        } else {
            $table_body = '<tbody>';
            $table_body .= '<tr><td class="text-center"><h1 style="font-weight: 500 !important;color: #b5b3b3 !important;">No Such Data Found.</h1></td></tr>';
            $div_part_list = 'No Data Exists';
        }
        
        if(empty($div_part_list)){
            $div_part_list = '<div class="row"><div class="col-md-12 col-sm-12 text-center"><h6>Result not found.</h6></div></div>';
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


        $this->db->select('c.name as company,m.name as model,y.name as year');
        $this->db->from(TBL_ITEMS . ' AS i');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.preferred_vendor=v1.id', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
        $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_COMPANY . ' as c', 'c.id=t.make_id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 'm.id=t.model_id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 'y.id=t.year_id and y.is_delete=0', 'left');
        $this->db->where('i.id', $part_id);
        $parts_result = $this->db->get();
        $data['partArr'] = $parts_result->result_array();

        return $this->load->view('api/partial_view/view_search_data', $data);
        die;
    }

    public function get_item_image() {
        $image_path = site_url('uploads/items/no_image.jpg');
        // $image_path = site_url('assets/images/no_image.jpg');
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

    public function getPartDetails() {
        $where = [];

        if (!empty($this->input->post())) {
            $part_no = $this->input->post('part_no');
            $part_description = $this->input->post('part_description');
            $vendor_id = $this->input->post('vendor_id');

            if (!empty($part_no)) {
                $where['part_no'] = $part_no;
            }

            if (!empty($part_description)) {
                $where['description'] = $part_description;
            }

            //Get Item data based on search text
            $this->db->select('i.*,d.name as dept_name,v1.name as v1_name');
            $this->db->join(TBL_DEPARTMENTS . ' AS d', 'd.id=i.department_id', 'left');
                    $this->db->join(TBL_VENDORS . ' AS v1', 'v1.id=i.preferred_vendor', 'left');
            $this->db->where(array(
                'i.preferred_vendor' => $vendor_id,
                'i.is_delete' => 0
            ));

            if (!empty($where)) {
                if (!empty($where['part_no'])) {
                    $this->db->like('part_no', $where['part_no']);
                }

                if (!empty($where['description'])) {
                    $this->db->like('i.description', $where['description']);
                }
            }
            $query = $this->db->get('items i');
            $data['viewArr'] = $query->result_array();
           //  echo $this->db->last_query();
           //  pr($data['viewArr']);
           // exit;
            return $this->load->view('api/partial_view/view_part_searched_data', $data);
        }
    }

    public function get_vin_vehical_details() {
        $data['results'] = [];

        $vin_no = $this->input->post('vin_no');
        $response_data = $this->dashboard_model->get_vehical_details($vin_no);

        if (!empty($response_data)) {
            if ($response_data->Count > 0) {
                $data['results'] = $response_data->Results;
            }
        }

        return $this->load->view('front/partial_view/view_vehical_details', $data);
    }

    public function token_authentication($token) {
        $data['status'] = 'error';
        $data['message'] = 'Something Went Wrong';
        $data['data'] = array();

        try {
            if (!empty($token)) {
                $this->db->select('v.*');
                $this->db->from(TBL_VENDORS . ' as v');
                $this->db->join(TBL_API_TOKENS . ' AS ap', 'v.id = ap.vendor_id');
                $this->db->where('ap.token', $token);
                $this->db->where('v.is_delete', 0);
                $res = $this->db->get();
                $getVendorDetails = $res->row_array();

                if (!empty($getVendorDetails)) {
                    $data['status'] = 'success';
                    $data['message'] = 'Token has been matched.';
                    $data['data'] = $getVendorDetails;
                } else {
                    $data['status'] = 'error';
                    $data['message'] = 'Your API Token does not match with our records. Please contact to admin.';
                }
            }
        } catch (Exception $ex) {
            $data['status'] = 'error';
            $data['message'] = $ex->getMessage();
        }

        echo json_encode($data);
        exit;
    }

}
