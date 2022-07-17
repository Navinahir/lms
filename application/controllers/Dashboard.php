<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . '/Library/QuickBook/autoload.php';
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/dashboard_model', 'admin/product_model', 'admin/inventory_model', 'admin/estimate_model', 'admin/invoice_model', 'admin/content_model', 'admin/blogs_model'));
    }

    /**
     * This is default function
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function index() {
        $format = MY_Controller::$date_format;
        if (!isset($_SESSION['sessionAccessToken'])) {
            $this->add_to_quickbook();
        }
        if ((!$this->session->userdata('user_logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('/login', 'refresh');
        }
        $data['title'] = 'Dashboard';
        $data['companyArr'] = $this->dashboard_model->get_all_details(TBL_COMPANY, array('is_delete' => 0, 'status' => 'active'),array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $data['yearArr'] = $this->dashboard_model->get_all_details(TBL_YEAR, array('is_delete' => 0))->result_array();
        $data['estimateArr'] = $this->estimate_model->get_recent_estimates_data();
        $data['invoiceArr'] = $this->invoice_model->get_recent_invoices_data();
        // if (isset($_COOKIE['currentOffset'])) {
        //     foreach ($data['estimateArr'] as $key => $val) {
        //         $data['estimateArr'][$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
        //     }
        //     foreach ($data['invoiceArr'] as $key => $val) {
        //         $data['invoiceArr'][$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
        //     }
        // } else {
        //     foreach ($data['estimateArr'] as $key => $val) {
        //         $data['estimateArr'][$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
        //     }
        //     foreach ($data['invoiceArr'] as $key => $val) {
        //         $data['invoiceArr'][$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
        //     }
        // }
        foreach ($data['estimateArr'] as $key => $val) {
            $data['estimateArr'][$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
        }
        foreach ($data['invoiceArr'] as $key => $val) {
            $data['invoiceArr'][$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
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
        $this->template->load('default_front', 'front/login/dashboard', $data);
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
       
        $temp = "";

        $vendor_multipul = $this->input->post('vendor_multipul');
        $vendor_multipul = $vendor_multipul[0];

        $manufacturer_multipul=$this->input->post('manufacturer_multipul');
        $manufacturer_multipul = $manufacturer_multipul[0];

        $itemsstatus_multipul=$this->input->post('itemsstatus_multipul');
        $itemsstatus_multipul = $itemsstatus_multipul[0];
        
        $condition = array(
            't.make_id' => $make_id,
            't.model_id' => $model_id,
            't.year_id' => $year_id,
        );

        if($itemsstatus_multipul != "")
        {
            if(in_array("In Stock", $itemsstatus_multipul) && in_array("Out Of Stock", $itemsstatus_multipul))
            {
                $temp = "multipul_In_Stock_Out_Of_Stock";
                $condition = array(
                    't.make_id' => $make_id,
                    't.model_id' => $model_id,
                    't.year_id' => $year_id,
                );
            } else if (in_array("In Stock", $itemsstatus_multipul)) {
                $temp = "multipul_In_Stock";
                $condition = array(
                    't.make_id' => $make_id,
                    't.model_id' => $model_id,
                    't.year_id' => $year_id,
                );
            } else {
                $temp = "multipul_Out_Of_Stock";
                $condition = array(
                    't.make_id' => $make_id,
                    't.model_id' => $model_id,
                    't.year_id' => $year_id,
                );
            }
        }
        
        if($manufacturer_multipul != "")
        {
            $temp = "manufacturer_multipul";
            $condition = array(
                't.make_id' => $make_id,
                't.model_id' => $model_id,
                't.year_id' => $year_id,
                'd.name' => $manufacturer_multipul
            );
        }
        
        if($vendor_multipul != "")
        {
            $temp = "vendor_multipul";
            $condition = array(
                't.make_id' => $make_id,
                't.model_id' => $model_id,
                't.year_id' => $year_id,
                'vg.name' => $vendor_multipul
            );
        }
        

        $search_array = [
            'business_user_id' => checkUserLogin('C'),
            'make_id' => $make_id,
            'model_id' => $model_id,
            'year_id' => $year_id,
            'created_date' => date('Y-m-d H:i:s')
        ];

        $result['vendor_list'] = $this->dashboard_model->get_vendor_list();

        $result['department_list'] = $this->dashboard_model->get_department_list();

        $this->inventory_model->insert_update('insert', TBL_RECENT_SEARCH_DETAILS, $search_array);
        $settings = $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();

        $transponder_result = $this->dashboard_model->get_user_transponder_details($condition, $settings, $temp)->row_array();
        $result['transponder_result'] = $this->dashboard_model->get_user_transponder_details($condition, $settings, $temp)->row_array();

        $result['locations'] = $this->inventory_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array();
        
        $table_body = $div_part_list = $my_part_list_Arr = $div_tool_list = $table_body2 = $div_my_part_list = '';
        if (!empty($transponder_result)) {
            $table_body = '<tbody>';
            $table_body .= '<tr><td width="40%"><b>Make</b></td><td>' . $transponder_result['make_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Model</b></td><td>' . $transponder_result['model_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Year</b></td><td>' . $transponder_result['year_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Transponder Equipped</b></td><td>' . ucfirst($transponder_result['transponder_equipped']) . '</td></tr>';
            $table_body .= '<tr><td><b>Key Type</b></td><td>' . str_replace('_', ' ', join(', ', array_map('ucfirst', explode(',', $transponder_result['key_value'])))) . '</td></tr>';
            $table_body .= '<tr><td><b>System</b></td><td>' . $transponder_result['mvp_system'] . '</td></tr>';
            $table_body .= '<tr><td><b>Pincode Required</b></td><td>' . ucfirst($transponder_result['pincode_required']) . '</td></tr>';
            $table_body .= '<tr><td><b>Pincode Reading Available</b></td><td>' . $transponder_result['pincode_reading_available'] . '</td></tr>';
            $table_body .= '<tr><td><b>Key Onboard Programming</b></td><td>' . $transponder_result['key_onboard_progaming'] . '</td></tr>';
            $table_body .= '<tr><td><b>Remote Onboard Programming</b></td><td>' . $transponder_result['remote_onboard_progaming'] . '</td></tr>';
            $table_body .= '<tr><td><b>Test Key</b></td><td>' . ((!empty($transponder_result['test_key'])) ? $transponder_result['test_key'] : '') . '</td></tr>';
            $table_body .= '<tr><td><b>IIco</b></td><td>' . $transponder_result['iico'] . '</td></tr>';
            $table_body .= '<tr><td><b>JET</b></td><td>' . ((!empty($transponder_result['jet'])) ? $transponder_result['jet'] : '') . '</td></tr>';
            $table_body .= '<tr><td><b>JMA</b></td><td>' . $transponder_result['jma'] . '</td></tr>';
            $table_body .= '<tr><td><b>Keyline</b></td><td>' . $transponder_result['keyline'] . '</td></tr>';
            $table_body .= '<tr><td><b>Strattec Non-Remote Key</b></td><td>' . $transponder_result['strattec_non_remote_key'] . '</td></tr>';
            $table_body .= '</tbody>';
            $table_body2 = '<tbody>';
            $table_body2 .= '<tr><td width="40%"><b>Strattec Remote Key</b></td><td>' . $transponder_result['strattec_remote_key'] . '</td></tr>';
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
            $parts_id_Arr = array_unique($parts_id_Arr);
            // $parts_no_Arr = array_unique($parts_no_Arr);

            $user_global_parts_no_Arr = explode(':-:', $transponder_result['user_global_parts_no']);
            $global_parts_no_Arr = explode(':-:', $transponder_result['global_parts_no']);
            $global_parts_id_Arr = explode(':-:', $transponder_result['global_parts_id']);
            $global_vendor_name_Arr = explode(':-:', $transponder_result['global_vendor_name']);

            $non_global_vendor_name_Arr = explode(':-:', $transponder_result['non_global_vendor_name']);
            $my_parts_stock_Arr = explode(':-:', $transponder_result['my_qty_on_hand']);
            $non_global_parts_id_Arr = explode(':-:', $transponder_result['non_global_parts_id']);
            $non_global_parts_no_Arr = explode(':-:', $transponder_result['non_global_parts_no']);

            $part_list_Arr = [];
            $global_parts_id_Arr = array_unique($global_parts_id_Arr);
            $user_global_parts_no_Arr = array_unique($user_global_parts_no_Arr);
            $global_parts_no_Arr = array_intersect_key($global_parts_no_Arr, $global_parts_id_Arr);
            $global_parts_no_Arr = array_diff($global_parts_no_Arr, $user_global_parts_no_Arr);
            
            $manufacturer = explode(':-:', $transponder_result['manufacturer']);
            $image  = $transponder_result['image'];
            $part_no = $transponder_result['part_no'];
            $internal_part_no = explode(':-:', $transponder_result['globalalternatepart']);
            $department_id = $transponder_result['department_id'];
            $department_name = explode(':-:', $transponder_result['department_name']);
            $globalvendorname = explode(':-:', $transponder_result['global_vendor_name']);
            $globalimage = explode(':-:', $transponder_result['globalimage']);
            $globaldescription = explode(':-:', $transponder_result['globaldescription']);
            $transponder_id = explode(':-:', $transponder_result['transponder_id']);
            $retail_price = explode(':-:', $transponder_result['retail_price']);
            $mypartdescription = explode(':-:', $transponder_result['mypartdescription']);
            $mypartimage = explode(':-:', $transponder_result['mypartimage']);
            $mypartinternalpart = explode(':-:', $transponder_result['mypartinternalpart']);
            $mypartrate = explode(':-:', $transponder_result['mypartrate']);
            $mypartmanufacturer = explode(':-:', $transponder_result['mypartmanufacturer']);
            $mypartdepartment = explode(':-:', $transponder_result['mypartdepartment']);
            $mypartpartsno = explode(':-:', $transponder_result['mypartpartsno']);

            $result['part_result'] = array(
                'parts_no_Arr' => $parts_no_Arr,
                'parts_id_Arr' =>$parts_id_Arr,
                'parts_stock_Arr' =>$parts_stock_Arr,
                'vendor_name_Arr' =>$vendor_name_Arr,
                'user_global_parts_no_Arr' => $user_global_parts_no_Arr,
                'global_parts_no_Arr' => $global_parts_no_Arr,
                'global_parts_id_Arr' => $global_parts_id_Arr,
                'global_vendor_name_Arr' => $global_vendor_name_Arr,
                'non_global_vendor_name_Arr' =>$non_global_vendor_name_Arr,
                'my_parts_stock_Arr' =>$my_parts_stock_Arr,
                'non_global_parts_id_Arr' => $non_global_parts_id_Arr,
                'non_global_parts_no_Arr' =>$non_global_parts_no_Arr,
                'part_list_Arr' => $part_list_Arr,      
                'manufacturer' => $manufacturer,
                'part_no' => $part_no,
                'internal_part_no' => $internal_part_no,
                'department_id' => $department_id,
                'department_name' => $department_name,
                'globalvendorname' =>$globalvendorname,
                'globalimage' => $globalimage,
                'globaldescription' => $globaldescription,
                'transponder_id' => $transponder_id,
                'retail_price' => $retail_price,
                'mypartdescription' => $mypartdescription,
                'mypartimage' => $mypartimage,
                'mypartinternalpart' => $mypartinternalpart,
                'mypartrate' => $mypartrate,
                'mypartmanufacturer' => $mypartmanufacturer,
                'mypartdepartment' => $mypartdepartment,
                'mypartpartsno' => $mypartpartsno,
            );
            // pr($result); die();

            // IML Security Supply Ex
            foreach ($global_parts_no_Arr as $k => $v) {
                if ($global_vendor_name_Arr[$k] != ''):
                    $part_list_Arr[$global_vendor_name_Arr[$k]][] = '<a href="javascript:void(0);" class="btn_global_item_view" title="View" id="' . base64_encode($global_parts_id_Arr[$k]) . '"><span class="label bg-orange" style="margin:3px;font-size:12px;font-family:monospace;">' . $global_parts_no_Arr[$k] . '</span></a>'
                            . '&nbsp;-&nbsp;<span class="label label-primary view-part-image" data-parttype="global" data-id="' . base64_encode($global_parts_id_Arr[$k]) . '" style="margin:3px;font-size:12px;font-family:monospace;"><i class="icon icon-image3"></i></span>&nbsp;';
                endif;
                
            }


            $non_global_parts_id_Arr = array_unique($non_global_parts_id_Arr);
            // $non_global_parts_no_Arr = array_unique($non_global_parts_no_Arr);
            if (!empty($non_global_parts_id_Arr)) {
                foreach ($non_global_parts_id_Arr as $k => $v) {
                    if (!empty($non_global_parts_id_Arr[$k]) && !empty($non_global_parts_no_Arr[$k]) && !empty($my_parts_stock_Arr[$k])) {
                        if ($my_parts_stock_Arr[$k] > 0) {
                            // $my_part_list_Arr[$non_global_vendor_name_Arr[$k]][] = '<a href="javascript:void(0);" class="btn_non_global_item_view" title="View" id="' . base64_encode($non_global_parts_id_Arr[$k]) . '"><span class="label label-success" style="margin:3px;font-size:12px;font-family:monospace;">' . $non_global_parts_no_Arr[$k] . '</span></a>';
//                                    . '&nbsp;-&nbsp;<span class="label label-primary view-part-image" data-id="' . base64_encode($non_global_parts_id_Arr[$k]) . '" style="margin:3px;font-size:12px;font-family:monospace;"><i class="icon icon-image3"></i></span>&nbsp;';
                        } else {
                            // $my_part_list_Arr[$non_global_vendor_name_Arr[$k]][] = '<a href="javascript:void(0);" class="btn_non_global_item_view" title="View" id="'.base64_encode($non_global_parts_id_Arr[$k]).'"><span class="label label-danger" style="margin:3px;font-size:12px;font-family:monospace;">'.$non_global_parts_no_Arr[$k] .'</span></a>';
//                                    . '&nbsp;-&nbsp;<span class="label label-primary view-part-image" data-id="' . base64_encode($non_global_parts_id_Arr[$k]) . '" style="margin:3px;font-size:12px;font-family:monospace;"><i class="icon icon-image3"></i></span>&nbsp;';
                        }
                    }
                }
            }

            foreach ($vendor_name_Arr as $k => $v) {
                if ($v != '' && $v != null):
                    if (isset($parts_id_Arr[$k]) && isset($parts_no_Arr[$k]) && isset($parts_stock_Arr[$k])) {
                        if ($parts_stock_Arr[$k] > 0) {
                            $part_list_Arr[$v][] = '<a href="javascript:void(0);" class="btn_home_item_view" title="View" id="' . base64_encode($parts_id_Arr[$k]) . '"><span class="label label-success" style="margin:3px;font-size:12px;font-family:monospace;">' . $parts_no_Arr[$k] . '</span></a>'
                                    . '&nbsp;-&nbsp;<span class="label label-primary view-part-image" data-parttype="non-global" data-id="' . base64_encode($parts_id_Arr[$k]) . '" style="margin:3px;font-size:12px;font-family:monospace;"><i class="icon icon-image3"></i></span>&nbsp;';
                        } else {
                            $part_list_Arr[$v][] = '<a href="javascript:void(0);" class="btn_home_item_view" title="View" id="' . base64_encode($parts_id_Arr[$k]) . '"><span class="label label-danger" style="margin:3px;font-size:12px;font-family:monospace;">' . $parts_no_Arr[$k] . '</span></a>'
                                    . '&nbsp;-&nbsp;<span class="label label-primary view-part-image" data-parttype="non-global" data-id="' . base64_encode($parts_id_Arr[$k]) . '" style="margin:3px;font-size:12px;font-family:monospace;"><i class="icon icon-image3"></i></span>&nbsp;';
                        }
                    }
                endif;
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

            $vendor_name = '';
            $cnt = 0;

            if(!empty($my_part_list_Arr)){
            foreach ($my_part_list_Arr as $k => $v) {
                if ($vendor_name != $k) {
                        if ($cnt > 0) {
                            $div_my_part_list .= '<hr style="border-top: 2px solid #ddd;margin: 10px 0;">';
                        }
                        $vendor_name = $k;
                        $div_my_part_list .= '<h5 style="margin-bottom:0px">' . $k . '</h5>';
                        $cnt++;
                    }
                    foreach ($v as $key => $value) {
                        $div_my_part_list .= $value;
                    }
                }
            }
            $toolArr = $this->inventory_model->get_tool_details()->result_array();
            if ($transponder_result['tools'] != null) {
                $tools = explode(',', $transponder_result['tools']);
                $tool_details = explode(':-:', $transponder_result['tool_details']);
                if (!empty($tools)) {
                    $equipments = (isset($settings) && $settings['equipment_id'] != null) ? explode(',', $settings['equipment_id']) : '';
                    $div_tool_list .= '<div class="row"><div id="accordion" class="panel-group col-sm-6 panel-flat">';
                    foreach ($tools as $k => $t) {
                        $key = array_search($t, array_column($toolArr, 'id'));
                        $content = (isset($tool_details[$k])) ? $tool_details[$k] : '';
                        if ($equipments != '') {
                            if (in_array($t, $equipments)) {
                                // $div_tool_list .= '<div class="col-md-4 tool_div custom_scrollbar" id="div_' . $t . '">' .
                                //         '<div class="panel panel-primary panel-bordered">' .
                                //         '<div class="panel-heading">' .
                                //         '<h6 class="panel-title">' . $toolArr[$key]["equip_name"] . ' (' . $toolArr[$key]['manu_name'] . ')</h6>' .
                                //         '</div>' .
                                //         '<div class="panel-body">' .
                                //         $content .
                                //         '</div>' .
                                //         '</div>' .
                                //         '</div>';



                                $div_tool_list .= '<div class="panel">' .
                                        '<div class="panel-heading">' .
                                        '<h4 class="panel-title">' .
                                        '<a href="#panelBody' . $t . '" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">&nbsp;&nbsp;&nbsp;<span>' . $toolArr[$key]["equip_name"] . ' (' . $toolArr[$key]['manu_name'] . ')</span></a>' .
                                        '</h4></div>' .
                                        '<div id="panelBody' . $t . '" class="panel-collapse collapse in">' .
                                        '<div class="panel-body">' .
                                        '<p>' . $content . '</p>' .
                                        '</div></div></div>';
                            }
                        } else {
                            // $div_tool_list .= '<div class="col-md-4 tool_div custom_scrollbar" id="div_' . $t . '">' .
                            //         '<div class="panel panel-primary panel-bordered">' .
                            //         '<div class="panel-heading">' .
                            //         '<h6 class="panel-title">' . $toolArr[$key]["equip_name"] . ' (' . $toolArr[$key]['manu_name'] . ')</h6>' .
                            //         '</div>' .
                            //         '<div class="panel-body">' .
                            //         $content .
                            //         '</div>' .
                            //         '</div>' .
                            //         '</div>';

                            $div_tool_list .= '<div class="panel">' .
                                    '<div class="panel-heading">' .
                                    '<h4 class="panel-title">' .
                                    '<a href="#panelBody' . $t . '" class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion">&nbsp;&nbsp;&nbsp;<span>' . $toolArr[$key]["equip_name"] . ' (' . $toolArr[$key]['manu_name'] . ')</span></a>' .
                                    '</h4></div>' .
                                    '<div id="panelBody' . $t . '" class="panel-collapse collapse">' .
                                    '<div class="panel-body">' .
                                    '<p>' . $content . '</p>' .
                                    '</div></div></div>';
                        }
                    }
                    $div_tool_list .= '</div></div>';
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

        $result['tra_result'] = array(
            'table_body' => $table_body,
            'table_body2' => $table_body2,
            'div_part_list' => $div_part_list,
            'div_my_part_list' => $div_my_part_list,
            'div_tool_list' => $div_tool_list,
            'my_part_list_Arr' => $my_part_list_Arr,
            'vendor_multipul' => $vendor_multipul,
            'manufacturer_multipul' => $manufacturer_multipul,
            'itemsstatus_multipul' => $itemsstatus_multipul
        );

        // pr($result); die();

        $view = $this->load->view('front/login/accordion_result',$result,true);
        // pr($view); die();
        echo json_encode($view);
        exit;
        // echo json_encode($result);
        // exit;
    }

    public function search() {
        $this->load->library('user_agent');
        if ($this->input->get()) {
            $data['title'] = 'Dashboard Search';
            $value = ($this->input->get('q')) ? $this->input->get('q') : null;
            $data['q'] = $value;

            if ($this->input->get('search_type') == 'fcc'):
                $data['search_type'] = 'FCC ID';
                $this->template->load('default_front', 'front/search/fcc_search_display', $data);
            elseif ($this->input->get('search_type') == 'chip'):
                $data['search_type'] = 'Chip ID';
                $this->template->load('default_front', 'front/search/chip_search_display', $data);
            elseif ($this->input->get('search_type') == 'part'):
                $data['search_type'] = 'Part NO';
                $this->template->load('default_front', 'front/search/part_search_display', $data);
            elseif ($this->input->get('search_type') == 'cust'):
                $data['search_type'] = 'Customer';
                $this->template->load('default_front', 'front/search/cust_search_display', $data);
            elseif ($this->input->get('search_type') == 'vin'):
                $data['search_type'] = 'VIN';
                $this->template->load('default_front', 'front/search/vin_search_display', $data);
            else:
                $this->session->set_flashdata('error', 'Please enter required values, Result not found.');
                return redirect('dashboard');
            endif;
        } else {
            $this->session->set_flashdata('error', 'Please enter required values, Result not found.');
            return redirect('dashboard');
        }
    }

    public function get_fcc_data() {
        $final['recordsTotal'] = $this->dashboard_model->get_fcc_details('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->dashboard_model->get_fcc_details('result');
        $start = $this->input->get('start') + 1;
        if (!empty($items)):
            foreach ($items as $key => $val) {
                $items[$key] = $val;
                $items[$key]['sr_no'] = $start++;
                $items[$key]['responsive'] = '';
            }
        endif;
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    public function get_Chip_data() {
        $final['recordsTotal'] = $this->dashboard_model->get_chip_details('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->dashboard_model->get_chip_details('result');
        $start = $this->input->get('start') + 1;
        if (!empty($items)):
            foreach ($items as $key => $val) {
                $items[$key] = $val;
                $items[$key]['sr_no'] = $start++;
                $items[$key]['responsive'] = '';
            }
        endif;
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    public function get_cust_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->dashboard_model->get_cust_details('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->dashboard_model->get_cust_details('result');
        $start = $this->input->get('start') + 1;
        if (!empty($items)):
            foreach ($items as $key => $val) {
                $items[$key] = $val;
                $items[$key]['sr_no'] = $start++;
                $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
                $items[$key]['responsive'] = '';
            }
        endif;
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    public function get_part_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->dashboard_model->get_part_details('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->dashboard_model->get_part_details('result');
        $start = $this->input->get('start') + 1;
        if (!empty($items)):
            foreach ($items as $key => $val) {
                $items[$key] = $val;
                $items[$key]['sr_no'] = $start++;
                $items[$key]['responsive'] = '';
            }
        endif;
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    public function get_content_data() {
        $content_data = array();
        $searched_module = $this->input->post('search_val');

        if (!empty($searched_module)) {
            $content_data = $this->content_model->get_contents_result($searched_module);
        } else {
            $content_data = $this->content_model->get_contents_result();
        }

        echo json_encode($content_data);
        exit;
    }

    public function get_content_data_ajax_by_id() {
        $data['result'] = array();
        $module_id = $this->input->post('id');

        $this->db->select('*');
        $this->db->from(TBL_MODULES_CONTENT);
        $this->db->where(['is_deleted' => 0, 'id' => $module_id]);
        $data['result'] = $this->db->get()->row_array();

        return $this->load->view('front/partial_view/view_front_module_content_data', $data);
    }

    public function get_blogs() {
        $length = 10;
        $start = 0;

        if ($this->input->is_ajax_request()) {
            $where = '';
            $start = $this->input->post('start');

            if (!empty($this->input->post('searched_text'))) {
                $searched_text = $this->input->post('searched_text');
                $where = '(blog.blog_title LIKE ' . $this->db->escape('%' . $searched_text . '%') . ' OR blog.tags LIKE ' . $this->db->escape('%' . $searched_text . '%') . ') ';
                $blogs = $this->blogs_model->get_all_blogs($length, $start, $where);
            } else {
                $blogs = $this->blogs_model->get_all_blogs($length, $start);
            }

            $data['records'] = $blogs['records'];

            return $this->load->view('front/partial_view/blogs_list', $data);
        }

        $blogs = $this->blogs_model->get_all_blogs($length, $start);
        $total_blogs = $this->blogs_model->get_num_blogs();

        $data['records'] = $blogs['records'];
        $data['length'] = $length;
        $data['start'] = $start;
        $data['total_blogs'] = $total_blogs['count'];

        $data['title'] = 'Blogs';
        return $this->template->load('default_front', 'front/blogs/list', $data);
    }

    public function get_blog_details($blog_no) {
        try {
            if (!empty($blog_no)) {
                $blog_id = base64_decode($blog_no);

                $data['blogArr'] = $this->blogs_model->get_all_details(TBL_BLOGS, ['id' => $blog_id])->row_array();
                $data['blogDataArr'] = $this->blogs_model->get_all_details(TBL_BLOG_MEDIA_CONTENTS, ['blog_id' => $blog_id])->result_array();
                $data['blogImage'] = $this->blogs_model->get_all_details(TBL_BLOG_MEDIA_CONTENTS, ['blog_id' => $blog_id, 'blog_content_type' => 'Image'])->row_array();

                $data['title'] = 'Blog Details';
                return $this->template->load('default_front', 'front/blogs/details', $data);
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', 'Something went wrong.');
            return redirect('blogs');
        }
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
        $part_type = $this->input->post('part_type');

        $item_array = array(
            'id' => $item_id,
        );

        if ($part_type == 'global') {
            $this->db->where($item_array);
            $this->db->from(TBL_ITEMS);
            $item_data = $this->db->get()->row_array();
        } else if ($part_type == 'non-global') {
            $this->db->select('i.*');
            $this->db->where(['ui.id' => $item_id]);
            $this->db->from(TBL_USER_ITEMS . ' as ui');
            $this->db->join(TBL_ITEMS . ' as i', 'i.id = ui.referred_item_id');
            $item_data = $this->db->get()->row_array();
        }

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

    public function get_vin_data() {
        $final['recordsTotal'] = $this->dashboard_model->get_vin_details('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->dashboard_model->get_vin_details('result');
        $start = $this->input->get('start') + 1;
        if (!empty($items)):
            foreach ($items as $key => $val) {
                $items[$key] = $val;
                $items[$key]['sr_no'] = $start++;
                $items[$key]['responsive'] = '';
            }
        endif;
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }


    public function add_to_quickbook()
    {
        $config = $this->config();
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' =>  $config['client_secret'],
            'RedirectURI' => $config['oauth_redirect_uri'],
            'scope' => $config['oauth_scope'],
            'baseUrl' => "development"
        ));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        $_SESSION['authUrl'] = $authUrl;
        if (isset($_SESSION['sessionAccessToken'])) {
                $accessToken = $_SESSION['sessionAccessToken'];
                $accessTokenJson = array('token_type' => 'bearer',
                'access_token' => $accessToken->getAccessToken(),
                'refresh_token' => $accessToken->getRefreshToken(),
                'x_refresh_token_expires_in' => $accessToken->getRefreshTokenExpiresAt(),
                'expires_in' => $accessToken->getAccessTokenExpiresAt()
            );

            $dataService->updateOAuth2Token($accessToken);
            $oauthLoginHelper = $dataService->getOAuth2LoginHelper();
        }
    }
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */