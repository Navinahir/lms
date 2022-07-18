<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ((!$this->session->userdata('vendor_logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('/vendor/login', 'refresh');
        }
        $this->load->model(array('admin/users_model', 'admin/inventory_model', 'admin/product_model'));
    }

    /**
     * Display Roles
     * @param  : ---
     * @return : ---
     * @author HGA [Last Edited : 12-02-2019]
     */
    public function display_roles() {
        $data['title'] = 'Display Roles';
        $this->template->load('default_vendor', 'vendor/vendors/role_display', $data);
    }

    /**
     * Get data of Roles request for listing
     * @param  : ---
     * @return : json
     * @author HGA [Last Edited : 12-02-2019]
     */
    public function filter_roles_data() {
        $final['recordsTotal'] = $this->users_model->get_vendor_roles_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $roles = $this->users_model->get_vendor_roles_data('result');
        $start = $this->input->get('start') + 1;
        foreach ($roles as $key => $val) {
            $roles[$key] = $val;
            $roles[$key]['sr_no'] = $start++;
            $roles[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
        }
        $final['data'] = $roles;
        echo json_encode($final);
    }

    /**
     * Add/Edit Role
     * @param  : $id String
     * @return : ---
     * @author HGA [Last Edited : 08/06/2018]
     */
    public function add_edit_roles($id = null) {
        $data['title'] = 'Add Role';

        $condition = ['Products', 'Reports', 'History'];
        $data['permissions'] = $this->users_model->get_vendor_controllers($condition);
        $data['access'] = '';

        if (!is_null($id)) {
            $record_id = base64_decode($id);
            $data['title'] = 'Edit Role';
            $data['dataArr'] = $this->users_model->get_all_details(TBL_ROLES, array('id' => $record_id))->row_array();
            $data['access'] = $this->users_model->get_vendor_permission($record_id);
        }

        $exist_permission = [196, 185, 184, 175, 174, 173];
        $data['exist_permission'] = $exist_permission;

        if ($this->input->post()) {
            $this->form_validation->set_rules('txt_role_name', 'Role Name', 'trim|required|max_length[50]');
            if ($this->form_validation->run() == true) {
                $insertArr = array(
                    'role_name' => htmlentities($this->input->post('txt_role_name')),
                    'description' => htmlentities($this->input->post('txt_description')),
                    'business_user_id' => checkVendorLogin('I'),
                    'modified_date' => date('Y-m-d H:i:s')
                );

                $post_permission = ($this->input->post('permission')) ? $this->input->post('permission') : [];
                $permission = array_merge($exist_permission, $post_permission);
                $record_array = [
                    'company_id' => checkVendorLogin('I'),
                    'method_id' => (!empty($permission)) ? implode(',', array_values($permission)) : '',
                ];

                extract($insertArr);
                if (!is_null($id)) {
                    $record_array['permission_id'] = $record_id;
                    $record_exist_condition = array(
                        'company_id' => checkVendorLogin('I'),
                        'permission_id' => $record_id
                    );
                    $permission_exists = $this->users_model->get_all_details(TBL_USER_PERMISSION, $record_exist_condition)->row_array();
                    $update_id = $this->users_model->insert_update('update', TBL_ROLES, $insertArr, ['id' => $record_id]);
                    if (isset($permission_exists) && !empty($permission_exists)) {
                        $this->users_model->insert_update('update', TBL_USER_PERMISSION, $record_array, ['id' => $permission_exists['id']]);
                    } else {
                        $record_array['created_date'] = date('Y-m-d H:i:s');
                        $this->users_model->insert_update('insert', TBL_USER_PERMISSION, $record_array);
                    }
                    $this->session->set_flashdata('success', '"<b>' . $role_name . '</b>" has been updated successfully.');
                } else {
                    $insertArr['created_date'] = date('Y-m-d H:i:s');
                    $insert_id = $this->users_model->insert_update('insert', TBL_ROLES, $insertArr);
                    if ($insert_id > 0) {
                        $record_array['permission_id'] = $insert_id;
                        $update_id = $this->users_model->insert_update('insert', TBL_USER_PERMISSION, $record_array);
                        $this->session->set_flashdata('success', '"<b>' . $role_name . '</b>" has been added successfully.');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                    }
                }
            }
            redirect('vendor/roles');
        }
        $this->template->load('default_vendor', 'vendor/vendors/add_role', $data);
    }

    /**
     * It will check name is unique or not for Role
     * @param  : $id String
     * @return : Boolean (true/false)
     * @author HGA [Last Edited : 08/06/2018]
     */
    public function checkUnique_Rolename($id = null) {
        $role_name = trim($this->input->get_post('txt_role_name'));
        $data = array(
            'role_name' => $role_name,
            'is_delete' => 0,
            'business_user_id' => checkVendorLogin('I')
        );
        if (!is_null($id)) {
            $data = array_merge($data, array('id!=' => $id));
        }
        $user = $this->users_model->check_unique_role_for_user($data);
        if ($user > 0) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    public function delete_roles($id) {
        $record_id = base64_decode($id);

        $res = [
            'is_delete' => 1
        ];

        $this->users_model->insert_update('update', TBL_ROLES, $res, array('id' => $record_id));
        $this->session->set_flashdata('success', 'Role request has been deleted successfully.');

        redirect('vendor/roles');
    }

    public function count_total_user() {
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $user = $this->users_model->check_role_wise_user($id);
            if ($user > 0) {
                echo json_encode(['code' => 400, 'data' => $user]);
            } else {
                echo json_encode(['code' => 200]);
            }
            exit;
        }
    }

}
