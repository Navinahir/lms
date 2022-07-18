<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Locations extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/location_model', 'admin/inventory_model'));
    }

    /**
     * Display Users
     * @param  : ---
     * @return : ---
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function display_locations() {
        $data['title'] = 'Display Locations';
        $this->template->load('default_front', 'front/locations/location_display', $data);
    }

    /**
     * Get data of Locations request for listing
     * @param  : ---
     * @return : json
     * @author HPA [Last Edited : 29/06/2018]
     */
    public function get_ajax_data() {
        $final['recordsTotal'] = $this->location_model->get_ajax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $roles = $this->location_model->get_ajax_data('result');
        $start = $this->input->get('start') + 1;
        foreach ($roles as $key => $val) {
            $roles[$key] = $val;
            $roles[$key]['sr_no'] = $start++;
            $roles[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $roles[$key]['responsive'] = '';
        }
        $final['data'] = $roles;
        echo json_encode($final);
    }

    /**
     * Add/Edit Locations
     * @param  : $id String
     * @return : ---
     * @author HPA [Last Edited : 08/06/2018]
     */
    public function edit_locations($id = null) {
        if (is_null($id)) {
            $data['user'] = $this->users_model->get_users_details();
            if (!empty($data['user'])):
                $data['total_locations'] = $this->location_model->total_locations();
                if ($data['total_locations'] >= $data['user']['no_of_locations']) :
                    $locations = ($data['user']['no_of_locations'] == 1) ? 'Location' : 'Locations';
                    $this->session->set_flashdata('error', 'You are currently subscribed to the <b>' . $data['user']['name'] . '</b>. You can add a maximum of <b>' . ($data['user']['no_of_locations']) . ' ' . $locations . '</b> to your account.');
                    redirect('locations');
                endif;
            else:
                $this->session->set_flashdata('error', 'You are currently subscribed to the Free plan. You can add a maximum of 1 locations to your account.');
                redirect('locations');
            endif;
        }
        if ($this->input->post()) {
            $this->form_validation->set_rules('txt_location_name', 'Location Name', 'trim|required|max_length[100]');
//            $this->form_validation->set_rules('txt_quantity', 'Quantity', 'trim|required');
            if ($this->form_validation->run() == true) {
                $insertArr = array(
                    'name' => htmlentities($this->input->post('txt_location_name')),
                    'description' => htmlentities($this->input->post('txt_description')),
                    'business_user_id' => checkUserLogin('C'),
                    'modified_date' => date('Y-m-d H:i:s')
                );
                extract($insertArr);
                if (!is_null($id)) {                                                
                    $record_id = base64_decode($id);
                    $insertArr['last_modified_by'] = checkUserLogin('I');
                    $update_id = $this->location_model->insert_update('update', TBL_LOCATIONS, $insertArr, ['id' => $record_id]);
                    $this->session->set_flashdata('success', '"<b>' . $name . '</b>" has been updated successfully.');
                } else {
                    $insertArr['created_date'] = date('Y-m-d H:i:s');
                    $insert_id = $this->location_model->insert_update('insert', TBL_LOCATIONS, $insertArr);
                    if ($insert_id > 0) {
                        $this->session->set_flashdata('success', '"<b>' . $name . '</b>" has been added successfully.');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                    }
                }
                redirect('locations');
            } else {
                echo validation_errors();
                die;
            }
        }
    }

    /**
     * It will check name is unique or not for Role
     * @param  : $id String
     * @return : Boolean (true/false)
     * @author HPA [Last Edited : 08/06/2018]
     */
    public function checkUniqueName($id = null) {
        $location_name = trim($this->input->get_post('txt_location_name'));
        $data = array(
            'name' => $location_name,
            'business_user_id' => checkUserLogin('C')
        );
        if (!is_null($id)) {
            $data = array_merge($data, array('id!=' => $id));
        }
        $user = $this->location_model->check_unique_location($data);
        if ($user > 0) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    public function action($action, $id) {
        $record_id = base64_decode($id);
        if ($action == 'delete'):
            $res = [
                'is_deleted' => 1
            ];
            $this->session->set_flashdata('success', 'Locations request has been deleted successfully.');
        endif;
        $this->location_model->insert_update('update', TBL_LOCATIONS, $res, array('id' => $record_id));
        redirect('locations');
    }

    /**
     * This function is used to GET PAYOUT REASONS via ajax
     * @param  : ---
     * @return : json data
     * @author HPA [Last Edited : 22/06/2018]
     */
    public function get_location_by_id() {
        $record_id = base64_decode($this->input->post('id'));
        $condition = array(
            'id' => $record_id,
            'is_deleted' => 0,
            'is_active' => 1,
            'business_user_id' => checkUserLogin('C')
        );
        $dataArr = $this->location_model->get_all_details(TBL_LOCATIONS, $condition)->row_array();
        echo json_encode($dataArr);
    }

    public function location_view($id = null) {
        if (!is_null($id)) {
            $data['title'] = 'View Location Details';
            $location_id = base64_decode($id);
            $data['locations'] = $this->location_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'id' => $location_id, 'is_active' => 1])->row_array();
            $this->template->load('default_front', 'front/locations/location_view', $data);
        }
    }

    /**
     * Get data of Locations request for listing
     * @param  : ---
     * @return : json
     * @author HPA [Last Edited : 29/06/2018]
     */
    public function get_loc_item_ajax_data($location_id = null) {
        $final['recordsTotal'] = $this->inventory_model->get_location_items_data('count', $location_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $locations = $this->inventory_model->get_location_items_data('result', $location_id)->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($locations as $key => $val) {
            $locations[$key] = $val;
            $locations[$key]['sr_no'] = $start++;
            $locations[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $locations[$key]['responsive'] = '';
        }
        $final['data'] = $locations;
        echo json_encode($final);
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */