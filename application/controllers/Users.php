<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/users_model', 'admin/inventory_model', 'admin/package_model', 'admin/payment_model'));
    }

    /**
     * Display Users Request
     * @param  : ---
     * @return : ---
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function display_users() {
        $data['title'] = 'Display Users';
        $this->template->load('default_front', 'front/users/user_display', $data);
    }

    /**
     * Get data of Users for listing
     * @param  : ---
     * @return : json
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function get_users_ajax_data() {
        $final['recordsTotal'] = $this->users_model->get_business_users_ajax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $staff = $this->users_model->get_business_users_ajax_data('result');
        $start = $this->input->get('start') + 1;
        foreach ($staff as $key => $val) {
            $staff[$key] = $val;
            $staff[$key]['sr_no'] = $start++;
            $staff[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']));
            // + $_COOKIE['currentOffset']
        }
        $final['data'] = $staff;
        echo json_encode($final);
    }

    public function action($action, $id) {
        $record_id = base64_decode($id);
        $redirect = 'users';
        if ($action == 'delete'):
            $res = [
                'is_delete' => 1
            ];
            $this->session->set_flashdata('success', 'User request has been deleted successfully.');
        elseif ($action == 'reject'):
            $res = [
                'status' => 'rejected'
            ];
            $this->session->set_flashdata('success', 'User request has been rejected successfully.');
        elseif ($action == 'active'):
            $data['total_users'] = $this->users_model->total_users();
            $data['user'] = $this->users_model->get_users_details();
            if (!empty($data['user'])) :
                if ($data['total_users'] >= $data['user']['no_of_users']) {
                    $users = ($data['user']['no_of_users'] == 1) ? 'User' : 'Users';
                    $this->session->set_flashdata('error', 'You are currently subscribed to the <b>' . $data['user']['name'] . '</b>. You can add a maximum of <b>' . $data['user']['no_of_users'] . ' ' . $users . '</b> to your account.');
                    redirect('users');
                } else {
                    $res = [
                        'status' => 'active'
                    ];
                    $this->session->set_flashdata('success', 'User has been Reactivated successfully.');
                }
            else :
                $this->session->set_flashdata('error', 'You are currently subscribed to the Free plan. You can add a maximum of 1 users to your account.');
                redirect('users');
            endif;
        elseif ($action == 'block'):
            $res = [
                'status' => 'block'
            ];
            $this->session->set_flashdata('success', 'User has been paused(blocked) successfully.');
        endif;
        $this->users_model->insert_update('update', TBL_USERS, $res, array('id' => $record_id));
        redirect($redirect);
    }

    /**
     * Add/Edit User
     * @param  : $id String
     * @return : ---
     * @author HPA [Last Edited : 11/06/2018]
     */
    public function edit_users($id = null) {
        if (is_null($id)) {
            $data['location_name'] = $this->users_model->get_all_details(TBL_LOCATIONS, array('is_deleted' => 0, 'is_active' => 1, 'business_user_id' => checkUserLogin('C')))->result_array();
            $data['total_users'] = $this->users_model->total_users();
            $data['user'] = $this->users_model->get_users_details();
            if (!empty($data['user'])) {
                if ($data['total_users'] >= $data['user']['no_of_users']) {
                    $users = ($data['user']['no_of_users'] == 1) ? 'User' : 'Users';
                    $this->session->set_flashdata('error', 'You are currently subscribed to the <b>' . $data['user']['name'] . '</b>. You can add a maximum of <b>' . $data['user']['no_of_users'] . ' ' . $users . '</b> to your account.');
                    redirect('users');
                }
            } else {
                $this->session->set_flashdata('error', 'You are currently subscribed to the Free plan. You can add a maximum of 1 users to your account.');
                redirect('users');
            }
        }
        $data['title'] = 'Invite User';
        $data['roles'] = $this->users_model->get_all_details(TBL_ROLES, array('is_delete' => 0, 'is_active' => 0, 'business_user_id' => checkUserLogin('C')))->result_array();
        $data['location_name'] = $this->users_model->get_all_details(TBL_LOCATIONS, array('is_deleted' => 0, 'is_active' => 1, 'business_user_id' => checkUserLogin('C')))->result_array();
        if (!is_null($id)) {
            $record_id = base64_decode($id);
            $data['title'] = 'Edit User';
            $data['dataArr'] = $this->users_model->get_all_details(TBL_USERS, array('id' => $record_id))->row_array();
        }
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('email_id', 'Email ID', 'trim|required|valid_email');
            if ($this->form_validation->run() == true) {
                $user_array = [
                    'first_name' => htmlentities($this->input->post('first_name')),
                    'last_name' => htmlentities($this->input->post('last_name')),
                    'full_name' => htmlentities($this->input->post('first_name')) . ' ' . htmlentities($this->input->post('last_name')),
                    'email_id' => htmlentities($this->input->post('email_id')),
                    'contact_number' => htmlentities($this->input->post('contact_number')),
                    'user_role' => htmlentities($this->input->post('user_role')),
                    'status' => 'active',
                    'business_name' => htmlentities($this->input->post('business_name')),
                    'address' => htmlentities($this->input->post('address')),
                    'location_id' => htmlentities($this->input->post('location_name')),
                    'business_user_id' => checkUserLogin('C'),
                ];
                extract($user_array);
                if (!is_null($id)) {
                    $user_array['modified_date'] = date('Y-m-d H:i:s');
                    $update_id = $this->users_model->insert_update('update', TBL_USERS, $user_array, ['id' => $record_id]);
                    $this->session->set_flashdata('success', '"<b>' . $first_name . ' ' . $last_name . '</b>" has been updated successfully.');
                } else {
                    $password = $org_pass = randomPassword();
                    $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
                    $algo = $password_encrypt_key . $password . $password_encrypt_key;
                    $encrypted_pass = hash('sha256', $algo);
                    $username = $this->generate_unique_username(htmlentities($this->input->post('first_name')) . ' ' . htmlentities($this->input->post('last_name')));
                    $user_array['created_date'] = date('Y-m-d H:i:s');
                    $user_array['modified_date'] = date('Y-m-d H:i:s');
                    $user_array['username'] = $username;
                    $user_array['password'] = $encrypted_pass;
                    $user_array['password_encrypt_key'] = $password_encrypt_key;
                    $insert_id = $this->users_model->insert_update('insert', TBL_USERS, $user_array);
                    if ($insert_id > 0) {
                        $email_var = array(
                            'user_id' => $insert_id,
                            'first_name' => $first_name,
                            'username' => $username,
                            'email_id' => $email_id,
                            'password' => $org_pass,
                            'type' => 'invite',
                            'business_username' => checkUserLogin('B')
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
                            'subject_message' => 'Account Invitation',
                            'body_messages' => $message
                        );
                        $email_send = common_email_send($email_array);
                        $this->session->set_flashdata('success', '"<b>' . $first_name . ' ' . $last_name . '</b>" has been created successfully.');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                    }
                }
                redirect('users');
            }
        }
        $this->template->load('default_front', 'front/users/add_user', $data);
    }

}

/* End of file Home.php */
    /* Location: ./application/controllers/Home.php */    