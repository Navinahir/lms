<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends MY_Controller {

    public function __construct() {
        parent::__construct();
        global $data_service;
        $this->load->model(array('admin/inventory_model', 'admin/location_model'));
        $data_service = $this->data_service();
    }

    public function add_inventory($id = null) {
        global $data_service;
        $data['title'] = 'Receive Inventory';
        $total_receive_amount = 0;
        $data['locations'] = $this->inventory_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array();
        $data['items'] = $this->inventory_model->get_inventory_vendor_data()->result_array();
        if (!is_null($id)) {
            $data['item_id'] = base64_decode($id);
        }
        if ($this->input->post()) {
            $new_inventory = [];
            foreach ($this->input->post('txt_new_inventory') as $k => $v):
                $total_receive_amount += $v;
                $is_exists = $this->inventory_model->get_result(TBL_ITEM_LOCATION_DETAILS, ['business_user_id' => checkUserLogin('C'), 'location_id' => $this->input->post('txt_new_inventory_hidden')[$k], 'item_id' => $this->input->post('txt_item_id'), 'is_deleted' => 0], null, 1);
                // pr($is_exists); 
                if (!empty($is_exists)) {
                    $quantity = ($is_exists['quantity'] + $v);
                    $update_array[$k] = [
                        'quantity' => $quantity,
                        'last_modified_by' => checkUserLogin('I'),
                        'id' => $is_exists['id']
                    ];
                
                } else {
                    $insert_array[$k] = [
                        'business_user_id' => checkUserLogin('C'),
                        'quantity' => $v,
                        'last_modified_by' => checkUserLogin('I'),
                        'item_id' => $this->input->post('txt_item_id'),
                        'location_id' => $this->input->post('txt_new_inventory_hidden')[$k],
                        'created_date' => date('Y-m-d h:i:s')
                    ];
                }
                if ($v > 0) {
                    $insert_invenoty[$k] = [
                        'business_user_id' => checkUserLogin('C'),
                        'quantity' => $v,
                        'added_by' => checkUserLogin('I'),
                        'item_id' => $this->input->post('txt_item_id'),
                        'location_id' => $this->input->post('txt_new_inventory_hidden')[$k],
                        'created_date' => date('Y-m-d h:i:s')
                    ];
                }
            endforeach;

            // Update notification
            $final_qty = $this->inventory_model->get_total_quantity($is_exists['item_id']) + $total_receive_amount;
            $notification_id = $this->inventory_model->get_all_details(TBL_USER_ITEMS,array('id'=>$is_exists['item_id']))->row_array();
            $low_inventory_limit = $notification_id['low_inventory_limit'];

            if($low_inventory_limit < $final_qty)
            {
                $this->inventory_model->insert_update('update',TBL_NOTIFICATION,array('is_delete' => 1),array('enum_id' => $is_exists['item_id']));
            }

            if (isset($insert_invenoty) && !empty($insert_invenoty)):
                $this->inventory_model->batch_insert_update('insert', TBL_ITEM_INVENTORY_DETAILS, $insert_invenoty);
                $first_id = $this->db->insert_id();
                $ids = $first_id;
                for ($i = 1; $i < (count($insert_invenoty)); $i++) {
                    $ids .= ',' . ($first_id + ($i));
                }
                $inventory_history_array = [
                    'business_user_id' => checkUserLogin('C'),
                    'user_id' => checkUserLogin('I'),
                    'its_for' => 'receive',
                    'relate_id' => $ids,
                    'notes' => $this->input->post('notes'),
                    'item_id' => $this->input->post('txt_item_id'),
                    'created_date' => date('Y-m-d h:i:s')
                ];
                $this->inventory_model->insert_update('insert', TBL_INVENTORY_HISTORY, $inventory_history_array);
            endif;
            if (isset($insert_array) && !empty($insert_array)):
                $this->inventory_model->batch_insert_update('insert', TBL_ITEM_LOCATION_DETAILS, $insert_array);
            endif;
            if (isset($update_array) && !empty($update_array)):
                $this->inventory_model->batch_insert_update('update', TBL_ITEM_LOCATION_DETAILS, $update_array, 'id');
            endif;
            if(isset($data_service['accessTokenJson']))
            {
                $quickbook_item_id = empty($is_exists) ? $this->input->post('txt_item_id') : $is_exists['item_id']; 
                
                $this->quickbook_item($quickbook_item_id, $flag = "update", $total_receive_amount);    
            }
            $this->session->set_flashdata('success', 'Item received successfully.');
            redirect('receive_inventory');
        }
        $this->template->load('default_front', 'front/inventory/add_inventory', $data);
    }

    /**
     * This function is used to GET PAYOUT REASONS via ajax
     * @param  : ---
     * @return : json data
     * @author HPA [Last Edited : 22/06/2018]
     */
    public function transfer_inventory($id = "") {
        $data['title'] = 'Move Inventory';
        $data['locations'] = $this->inventory_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array();
        $data['items'] = $this->inventory_model->get_inventory_vendor_data()->result_array();
        $data['defult_item_id'] = base64_decode($id);
        $this->template->load('default_front', 'front/locations/transfer_location', $data);
        if ($this->input->post()) {
            $location_items = $this->inventory_model->get_all_details(TBL_ITEM_LOCATION_DETAILS, ['is_deleted' => 0, 'location_id' => $this->input->post('txt_from_location_id'), 'item_id' => $this->input->post('txt_item_id'), 'is_active' => 1])->row_array();
            $location_to_items = $this->inventory_model->get_all_details(TBL_ITEM_LOCATION_DETAILS, ['is_deleted' => 0, 'location_id' => $this->input->post('txt_to_location_id'), 'item_id' => $this->input->post('txt_item_id'), 'is_active' => 1])->row_array();
            if (!empty($location_items)) {
                $item_location_array = [
                    'item_id' => $this->input->post('txt_item_id'),
                    'from_location_id' => $this->input->post('txt_from_location_id'),
                    'to_location_id' => $this->input->post('txt_to_location_id'),
                    'business_user_id' => checkUserLogin('C'),
                    'quantity' => $this->input->post('txt_quantity'),
                    'transfer_date' => date('Y-m-d H:i:s'),
                    'created_date' => date('Y-m-d h:i:s')
                ];

                $qty = ($location_items['quantity'] - $this->input->post('txt_quantity'));
                $transfer_id = $this->inventory_model->insert_update('insert', TBL_ITEM_LOCATION_TRANSFER_DETAILS, $item_location_array);
                if (!empty($location_to_items)) {
                    $total_qty = ($location_to_items['quantity'] + $this->input->post('txt_quantity'));
                    $location = $this->inventory_model->insert_update('update', TBL_ITEM_LOCATION_DETAILS, ['quantity' => $total_qty, 'last_modified_by' => checkUserLogin('I')], array('id' => $location_to_items['id']));
                } else {
                    $item_loc_array = [
                        'item_id' => $this->input->post('txt_item_id'),
                        'location_id' => $this->input->post('txt_to_location_id'),
                        'business_user_id' => checkUserLogin('C'),
                        'quantity' => $this->input->post('txt_quantity'),
                        'last_modified_by' => checkUserLogin('I'),
                        'created_date' => date('Y-m-d h:i:s')
                    ];
                    $location = $this->inventory_model->insert_update('insert', TBL_ITEM_LOCATION_DETAILS, $item_loc_array);
                }
                $this->inventory_model->insert_update('update', TBL_ITEM_LOCATION_DETAILS, ['quantity' => $qty, 'last_modified_by' => checkUserLogin('I')], array('id' => $location_items['id']));
                $insert_invenoty = [
                    0 => [
                        'business_user_id' => checkUserLogin('C'),
                        'quantity' => (-1 * abs($this->input->post('txt_quantity'))),
                        'added_by' => checkUserLogin('I'),
                        'item_id' => $this->input->post('txt_item_id'),
                        'location_id' => $this->input->post('txt_from_location_id'),
                        'created_date' => date('Y-m-d h:i:s')
                    ],
                    1 => [
                        'business_user_id' => checkUserLogin('C'),
                        'quantity' => $this->input->post('txt_quantity'),
                        'added_by' => checkUserLogin('I'),
                        'item_id' => $this->input->post('txt_item_id'),
                        'location_id' => $this->input->post('txt_to_location_id'),
                        'created_date' => date('Y-m-d h:i:s')
                    ],
                ];
                if (isset($insert_invenoty) && !empty($insert_invenoty)):
                    $this->inventory_model->batch_insert_update('insert', TBL_ITEM_INVENTORY_DETAILS, $insert_invenoty);
                endif;
                $inventory_history_array = [
                    'business_user_id' => checkUserLogin('C'),
                    'user_id' => checkUserLogin('I'),
                    'its_for' => 'transfer',
                    'relate_id' => $transfer_id,
                    'notes' => $this->input->post('notes'),
                    'item_id' => $this->input->post('txt_item_id'),
                    'created_date' => date('Y-m-d h:i:s')
                ];
                $this->inventory_model->insert_update('insert', TBL_INVENTORY_HISTORY, $inventory_history_array);
                $this->session->set_flashdata('success', 'Inventory moved to selected locations successfully.');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong!!');
            }
            redirect('move_inventory');
        }
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
                $location_items = $this->inventory_model->get_all_details(TBL_ITEM_LOCATION_DETAILS, ['is_deleted' => 0, 'location_id' => $id, 'item_id' => $item_id, 'is_active' => 1, 'quantity >' => 0])->row_array();
            } else {
                $location_items = $this->inventory_model->get_all_details(TBL_ITEM_LOCATION_DETAILS, ['is_deleted' => 0, 'item_id' => $item_id, 'is_active' => 1, 'quantity >' => 0])->result_array();
            }
            $location_items['total_quantity'] = $this->inventory_model->get_total_quantity($item_id);
            echo json_encode($location_items);
            die;
        }
    }

    /**
     * Display All Inventory History 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function inventory_history() {
        $data['title'] = 'Inventory History';
        $this->template->load('default_front', 'front/inventory/inventory_history', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_inventory_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->inventory_model->get_inventory_history('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->inventory_model->get_inventory_history('result');
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['created_date'] = date($format['format'], strtotime($val['created_date']));
            // $items[$key]['created_date'] = date('m-d-Y h:i A', strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    public function invoice_inventory(){
        $data['title'] = 'Invoice Inventory History';
        $this->template->load('default_front', 'front/inventory/invoice_inventory', $data);
    }

    /**
     * Get all the data of history for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 08/02/2021]
     */
    public function invoice_inventory_history_ajax_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->inventory_model->invoice_inventory_history_ajax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->inventory_model->invoice_inventory_history_ajax_data('result');
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            // $items[$key]['created_date'] = date($format['format'], strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['created_date'] = date($format['format'], strtotime($val['created_date']));
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
    }

    /**
     * Get all the data of inventory items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 03/02/2018]
     */
    public function get_invoice_inventory_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->inventory_model->get_invoice_inventory_history('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->inventory_model->get_invoice_inventory_history('result');
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['created_date'] = date('m-d-Y h:i A', strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    public function adjust_inventory($id = null) {
        global $data_service;
        $data['title'] = 'Adjust Inventory';
        $data['locations'] = $this->inventory_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array();
        $data['items'] = $this->inventory_model->get_inventory_vendor_data()->result_array();
        if (!is_null($id)) {
            $data['item_id'] = base64_decode($id);
        }
        if ($this->input->post()) {
            $new_inventory = [];
            foreach ($this->input->post('txt_new_inventory') as $k => $v):
                $is_exists = $this->inventory_model->get_result(TBL_ITEM_LOCATION_DETAILS, ['business_user_id' => checkUserLogin('C'), 'location_id' => $this->input->post('txt_new_inventory_hidden')[$k], 'item_id' => $this->input->post('txt_adjust_item_id'), 'is_deleted' => 0], null, 1);
                if (!empty($is_exists)) {
                    $update_array[$k] = [
                        'quantity' => $v,
                        'last_modified_by' => checkUserLogin('I'),
                        'id' => $is_exists['id']
                    ];
                } else {
                    $insert_array[$k] = [
                        'business_user_id' => checkUserLogin('C'),
                        'quantity' => $v,
                        'last_modified_by' => checkUserLogin('I'),
                        'item_id' => $this->input->post('txt_adjust_item_id'),
                        'location_id' => $this->input->post('txt_new_inventory_hidden')[$k],
                        'created_date' => date('Y-m-d h:i:s')
                    ];
                }
                if ($is_exists['quantity'] > $v) {
                    $insert_invenoty[$k] = [
                        'business_user_id' => checkUserLogin('C'),
                        'quantity' => (-1 * abs(($is_exists['quantity'] - $v))),
                        'added_by' => checkUserLogin('I'),
                        'item_id' => $this->input->post('txt_adjust_item_id'),
                        'location_id' => $this->input->post('txt_new_inventory_hidden')[$k],
                        'created_date' => date('Y-m-d h:i:s')
                    ];
                } elseif ($is_exists['quantity'] < $v) {
                    $insert_invenoty[$k] = [
                        'business_user_id' => checkUserLogin('C'),
                        'quantity' => ($v - $is_exists['quantity']),
                        'added_by' => checkUserLogin('I'),
                        'item_id' => $this->input->post('txt_adjust_item_id'),
                        'location_id' => $this->input->post('txt_new_inventory_hidden')[$k],
                        'created_date' => date('Y-m-d h:i:s')
                    ];
                }
            endforeach;
            if (isset($insert_invenoty) && !empty($insert_invenoty)):
                $this->inventory_model->batch_insert_update('insert', TBL_ITEM_INVENTORY_DETAILS, $insert_invenoty);
                $first_id = $this->db->insert_id();
                $ids = $first_id;
                for ($i = 1; $i < (count($insert_invenoty)); $i++) {
                    $ids .= ',' . ($first_id + ($i));
                }
                $inventory_history_array = [
                    'business_user_id' => checkUserLogin('C'),
                    'user_id' => checkUserLogin('I'),
                    'its_for' => 'adjust',
                    'relate_id' => $ids,
                    'notes' => $this->input->post('notes'),
                    'item_id' => $this->input->post('txt_adjust_item_id'),
                    'created_date' => date('Y-m-d h:i:s')
                ];
                $this->inventory_model->insert_update('insert', TBL_INVENTORY_HISTORY, $inventory_history_array);
            endif;
            if (isset($insert_array) && !empty($insert_array)):
                $this->inventory_model->batch_insert_update('insert', TBL_ITEM_LOCATION_DETAILS, $insert_array);
            endif;
            if (isset($update_array) && !empty($update_array)):
                $this->inventory_model->batch_insert_update('update', TBL_ITEM_LOCATION_DETAILS, $update_array, 'id');
            endif;
            if(isset($data_service['accessTokenJson']))
            {
                $quickbook_item_id = empty($is_exists) ? $this->input->post('txt_adjust_item_id') : $is_exists['item_id']; 
                
                $this->quickbook_item($quickbook_item_id, $flag = "update");    
            }
            $this->session->set_flashdata('success', 'Inventory adjusted to selected locations successfully.');
            redirect('adjust_inventory');
        }
        $this->template->load('default_front', 'front/inventory/adjust_inventory', $data);
    }

    public function get_item_data() {
        $data['status'] = 'error';
        $data['message'] = 'Something went wrong!';
        $data['data'] = '';

        if (!empty($this->input->post())) {
            $part_no = $this->input->post('part_no');

            $this->db->select('ui.*, ui.id as i_id, v.name as vendor_name, i.internal_part_no');
            $this->db->from(TBL_USER_ITEMS . ' as ui');
            $this->db->join(TBL_ITEMS . ' as i', 'i.id = ui.referred_item_id AND i.is_delete = 0', 'left');
            $this->db->join(TBL_VENDORS . ' as v', 'ui.vendor_id = v.id and v.is_delete = 0', 'left');
            $this->db->like('ui.part_no', $part_no);
            $this->db->where(array(
                'ui.is_delete' => 0,
                'ui.business_user_id' => checkUserLogin('C')
            ));
            $items = $this->db->get()->row_array();

            if (!empty($items) && sizeof($items) > 0) {
                $data['status'] = 'success';
                $data['message'] = 'Item has been found successfully.';
                $data['data']['item_id'] = $items['id'];
            } else {
                $data['status'] = 'error';
                $data['message'] = 'Item has not been found.';
            }
        }

        echo json_encode($data);
        exit;
    }

    public function get_global_item_data() {
        if ($this->input->post('part_no')) {
            $this->db->select('i.part_no,v1.name');
            $this->db->from(TBL_ITEMS . ' AS i');
            $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
            $this->db->join(TBL_VENDORS . ' AS v1', 'i.preferred_vendor=v1.id', 'left');
            $this->db->where('i.is_delete', '0');
            $this->db->where('v1.is_active', '1');
            $this->db->like('i.part_no', $this->input->post('part_no'));
            $result = $this->db->get()->result_array();
            $new = [];
            if (!empty($result)) {
                foreach ($result as $k => $v) {
                    $new[] = $v['part_no'] . ' (' . $v['name'] . ')';
                }
            }
            echo json_encode($new);
            die;
        }
    }

    public function inventory_locations() {
        $data['title'] = 'Invetory locations';
        $this->template->load('default_front', 'front/inventory/inventory_location_display', $data);
    }

    public function inventory_location_view($id = null) {
        if (!is_null($id)) {
            $data['title'] = 'View Location Details';
            $location_id = base64_decode($id);
            $data['locations'] = $this->location_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'id' => $location_id, 'is_active' => 1])->row_array();
            $this->template->load('default_front', 'front/inventory/inventory_location_view', $data);
        }
    }

}

/* End of file Inventory.php */
/* Location: ./application/controllers/Inventory.php */