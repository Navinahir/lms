<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ((!$this->session->userdata('vendor_logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('/vendor/login', 'refresh');
        }
        $this->load->model(array('admin/users_model', 'admin/inventory_model', 'admin/product_model'));
    }

    /**
     * Vendors Page
     * @param --
     * @return --
     * @author HGA [Last Edited : 07/02/2019]
     */
    public function index() {
        $data['title'] = 'List of Vendors';
        $this->template->load('default_vendor', 'vendor/vendors/vendors_display', $data);
    }

    /**
     * Get data of Vendors for listing
     * @param  : ---
     * @return : json
     * @author HGA [Last Edited : 12/02/2019]
     */
    public function filter_vendors_data() {
        $final['recordsTotal'] = $this->users_model->get_business_vendors_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $staff = $this->users_model->get_business_vendors_data('result');
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
     * Add/Edit Vendors
     * @param  : $id String
     * @return : ---
     * @author HGA [Last Edited : 13/02/2019]
     */
    public function add_edit_users($id = null) {
        if (is_null($id)) {
            //Check if vendor has permisson to add multiple sub-users.
            $vendor_data = $this->users_model->get_all_details(TBL_VENDORS, array('user_id' => checkVendorLogin('I')))->row_array();
            
            if (!empty($vendor_data) && $vendor_data['can_add_multi_staff'] == 0) {
                $this->session->set_flashdata('error', 'You do not have permission to add more users, Please contact to admin.');
                redirect('vendor/users');
            }

            //Check that vendor has permission of add max 10 users
            $data['total_vendor_users'] = $this->users_model->total_vendor_users();

            if ($data['total_vendor_users'] === MAX_SUB_VENDOR_USERS) {
                $this->session->set_flashdata('error', 'You can add a maximum of <b>' . MAX_SUB_VENDOR_USERS . '</b> users to your account.');
                redirect('vendor/users');
            }
        }

        $data['title'] = 'Invite Vendor User';
        $data['roles'] = $this->users_model->get_all_details(TBL_ROLES, array('is_delete' => 0, 'is_active' => 0, 'business_user_id' => checkVendorLogin('I')))->result_array();

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
                    'business_user_id' => checkVendorLogin('I'),
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
                            'vendor_name' => $vendor_data['name'],
                            'user_id' => $insert_id,
                            'first_name' => $first_name,
                            'username' => $username,
                            'email_id' => $email_id,
                            'password' => $org_pass,
                            'type' => 'invite'
                        );
                        $message = $this->load->view('email_template/default_header.php', $email_var, true);
                        $message .= $this->load->view('email_template/vendor_staff_registration.php', $email_var, true);
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
                redirect('vendor/users');
            }
        }

        $this->template->load('default_vendor', 'vendor/vendors/add_vendor', $data);
    }

    public function delete_users($id) {
        $record_id = base64_decode($id);
        $this->users_model->insert_update('update', TBL_USERS, array('is_delete' => 1), array('id' => $record_id));
        $this->session->set_flashdata('success', 'User has been deleted successfully.');
        redirect('vendor/users');
    }

}
