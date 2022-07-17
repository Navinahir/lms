<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/staff_model'));
        controller_validation();
    }

    /**
     * Display Staff
     * @param  : ---
     * @return : ---
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function display_staff() {
        $data['title'] = 'Display Staff';
        $this->template->load('default', 'admin/staff/display', $data);
    }

    /**
     * Get data of staff for listing
     * @param  : ---
     * @return : json
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_ajax_data() {
        $final['recordsTotal'] = $this->staff_model->get_ajax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $staff = $this->staff_model->get_ajax_data('result');
        $start = $this->input->get('start') + 1;
        foreach ($staff as $key => $val) {
            $staff[$key] = $val;
            $staff[$key]['sr_no'] = $start++;
            $staff[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
        }
        $final['data'] = $staff;
        echo json_encode($final);
    }

    /**
     * Add Staff
     * @param  : --
     * @return : ---
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function add_staff() {
        $data['title'] = 'Add Staff';
        $data['roleArr'] = $this->staff_model->get_all_details(TBL_ROLES, array('is_delete' => 0, 'is_active' => 1), array(array('field' => 'role_name', 'type' => 'asc')))->result_array();
        $this->form_validation->set_rules('txt_first_name', 'First Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('txt_last_name', 'Last Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('txt_email_id', 'Email ID', 'trim|required|valid_email');
        if ($this->form_validation->run() == true) {
            $password = $org_pass = randomPassword();
            $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
            $algo = $password_encrypt_key . $password . $password_encrypt_key;
            $encrypted_pass = hash('sha256', $algo);
            $username = $this->generate_unique_username(htmlentities($this->input->post('txt_first_name')) . ' ' . htmlentities($this->input->post('txt_last_name')));
            $insertArr = array(
                'first_name' => htmlentities($this->input->post('txt_first_name')),
                'last_name' => htmlentities($this->input->post('txt_last_name')),
                'full_name' => htmlentities($this->input->post('txt_first_name')) . ' ' . htmlentities($this->input->post('txt_last_name')),
                'email_id' => htmlentities($this->input->post('txt_email_id')),
                'contact_number' => htmlentities($this->input->post('txt_contact_no')),
                'user_role' => htmlentities($this->input->post('txt_role')),
                'username' => $username,
                'password' => $encrypted_pass,
                'password_encrypt_key' => $password_encrypt_key,
                'status' => $this->input->post('txt_status') == 'on' ? 'active' : 'block',
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert_id = $this->staff_model->insert_update('insert', TBL_USERS, $insertArr);
            extract($insertArr);
            $email_var = array(
                'user_id' => $insert_id,
                'first_name' => $first_name,
                'username' => $username,
                'email_id' => $email_id,
                'password' => $org_pass
            );
            $message = $this->load->view('email_template/default_header.php', $email_var, true);
            $message .= $this->load->view('email_template/staff_registration.php', $email_var, true);
            $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
            $email_array = array(
                'mail_type' => 'html',
                'from_mail_id' => $this->config->item('smtp_user'),
                'from_mail_name' => 'ARK Team',
                'to_mail_id' => $email_id,
                'cc_mail_id' => '',
                'subject_message' => 'Account Registration',
                'body_messages' => $message
            );
            $email_send = common_email_send($email_array);
            if ($insert_id > 0) {
                $this->session->set_flashdata('success', '"<b>' . $first_name . ' ' . $last_name . '</b>" has been added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
            }
            redirect('admin/staff');
        }
        $this->template->load('default', 'admin/staff/add', $data);
    }

    /**
     * Add Staff
     * @param  : --
     * @return : ---
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function edit_staff($id = '') {
        $data['title'] = 'Edit Staff';
        $staff_id = base64_decode($id);
        $data['dataArr'] = $this->staff_model->get_all_details(TBL_USERS, array('id' => $staff_id))->row_array();
        $data['roleArr'] = $this->staff_model->get_all_details(TBL_ROLES, array('is_delete' => 0, 'is_active' => 1), array(array('field' => 'role_name', 'type' => 'asc')))->result_array();
        $this->form_validation->set_rules('txt_first_name', 'First Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('txt_last_name', 'Last Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('txt_user_name', 'User Name', 'trim|required');
        $this->form_validation->set_rules('txt_email_id', 'Email ID', 'trim|required|valid_email');

        if ($this->form_validation->run() == true) {
            $updateArr = array(
                'first_name' => htmlentities($this->input->post('txt_first_name')),
                'last_name' => htmlentities($this->input->post('txt_last_name')),
                'full_name' => htmlentities($this->input->post('txt_first_name')) . ' ' . htmlentities($this->input->post('txt_last_name')),
                'email_id' => htmlentities($this->input->post('txt_email_id')),
                'contact_number' => htmlentities($this->input->post('txt_contact_no')),
                'user_role' => htmlentities($this->input->post('txt_role')),
                'username' => htmlentities($this->input->post('txt_user_name')),
                'status' => $this->input->post('txt_status') == 'on' ? 'active' : 'block'
            );
            extract($updateArr);
            $this->staff_model->insert_update('update', TBL_USERS, $updateArr, array('id' => $staff_id));
            $this->session->set_flashdata('success', 'Details of "<b>' . $first_name . ' ' . $last_name . '</b>" has been edited successfully.');
            redirect('admin/staff');
        }
        $this->template->load('default', 'admin/staff/add', $data);
    }

    /**
     * Delete Staff
     * @param  : $id String
     * @return : ---
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function delete_staff($id = NULL) {
        $record_id = base64_decode($id);
        $this->users_model->insert_update('update', TBL_USERS, array('is_delete' => 1), array('id' => $record_id));
        $this->session->set_flashdata('error', 'User has been deleted successfully.');
        redirect('admin/staff');
    }

    /**
     * It will check username is unique or not for staff
     * @param  : $id String
     * @return : Boolean (true/false)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function checkUnique_Username($id = NULL) {
        $username = trim($this->input->get_post('txt_user_name'));
        $data = array('username' => $username);
        if (!is_null($id)) {
            $data = array_merge($data, array('id!=' => $id));
        }
        $user = $this->staff_model->check_unique_email_for_staff($data);
        if ($user > 0) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    /**
     * It will check email is unique or not for staff
     * @param  : $id String
     * @return : Boolean (true/false)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function checkUnique_Email($id = NULL) {
        $email_id = trim($this->input->get_post('txt_email_id'));
        $data = array('email_id' => $email_id);
        if (!is_null($id)) {
            $data = array_merge($data, array('id!=' => $id));
        }
        $user = $this->staff_model->check_unique_email_for_staff($data);
        if ($user > 0) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    /**
     * It will change staff member status
     * @param  : $id String
     * @return : ---
     * @author JJP [Last Edited : 21/01/2020]
     */
    public function change_status() {
        $update_status = $this->staff_model->insert_update('update',TBL_USERS,array('status' => $this->input->post('staff_status')),array('id' => $this->input->post('staff_id')));
        $final['affected_row'] = $update_status;
        $final['staff_id'] = $this->input->post('staff_id');
        echo json_encode($final);
        exit;
    }

}

/* End of file Staff.php */
/* Location: ./application/controllers/Staff.php */