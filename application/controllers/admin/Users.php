<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/users_model', 'admin/roles_model'));
        controller_validation();
    }

	/**
	 * Add Lead
	 * @param --
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function add_users() {
		$data['title'] = 'Admin | Add Users';
		$data['rolesArr'] = $rolesArr = $this->roles_model->get_all_details(TBL_ROLES, array('is_delete' => 0))->result_array();

		$this->form_validation->set_rules('first_name', 'Firstname', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Lastname', 'trim|required');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('email_id', 'Email', 'trim|required');
		if ($this->form_validation->run() == true) {
			$insertArr = array(
				'user_role' => htmlentities($this->input->post('user_role')),
				'first_name' => htmlentities($this->input->post('first_name')),
				'last_name' => htmlentities($this->input->post('last_name')),
				'username' => htmlentities($this->input->post('username')),
				'email_id' => htmlentities($this->input->post('email_id')),
				'contact_number' => htmlentities($this->input->post('contact_number')),
				'address' => htmlentities($this->input->post('address')),
				'state_id' => htmlentities($this->input->post('state_id')),
				'zip_code' => htmlentities($this->input->post('zip_code'))
			);
			if ($this->input->post('password') != '' && ($this->input->post('password') == $this->input->post('cpassword'))) {
				$password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
				$algo = $password_encrypt_key . $this->input->post('password') . $password_encrypt_key;
				$encrypted_pass = hash('sha256', $algo);
				$insertArr['password'] = $encrypted_pass;
				$insertArr['password_encrypt_key'] = $password_encrypt_key;
			}
			$insert_id = $this->users_model->insert_update('insert', TBL_USERS, $insertArr);

			if ($insert_id > 0) {
				$this->session->set_flashdata('success', 'Data has been added successfully.');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong! Please try again.');
			}
			redirect('admin/users/');
		}

		$data = array(
			'title' => 'Edit Users',
			'rolesArr' => $rolesArr);

		$this->template->load('default', 'admin/users/users_add', $data);
	}

	/**
	 * Edit Transponder
	 * @param $id - String
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function edit_users($id = '') {
//		controller_validation();
		$record_id = base64_decode($id);
		$dataArr = $this->users_model->get_all_details(TBL_USERS, array('id' => $record_id))->row_array();
		$data['rolesArr'] = $rolesArr = $this->roles_model->get_all_details(TBL_ROLES, array('is_delete' => 0))->result_array();
		$this->form_validation->set_rules('first_name', 'Firstname', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Lastname', 'trim|required');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('email_id', 'Email', 'trim|required');
		if ($this->form_validation->run() == true) {
			$updateArr = array(
				'user_role' => htmlentities($this->input->post('user_role')),
				'first_name' => htmlentities($this->input->post('first_name')),
				'last_name' => htmlentities($this->input->post('last_name')),
				'username' => htmlentities($this->input->post('username')),
				'email_id' => htmlentities($this->input->post('email_id')),
				'contact_number' => htmlentities($this->input->post('contact_number')),
				'address' => htmlentities($this->input->post('address')),
				'state_id' => htmlentities($this->input->post('state_id')),
				'zip_code' => htmlentities($this->input->post('zip_code'))
			);
			if ($this->input->post('password') != '' && ($this->input->post('password') == $this->input->post('cpassword'))) {
				$password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
				$algo = $password_encrypt_key . $this->input->post('password') . $password_encrypt_key;
				$encrypted_pass = hash('sha256', $algo);
				$updateArr['password'] = $encrypted_pass;
				$updateArr['password_encrypt_key'] = $password_encrypt_key;
			}
			$this->users_model->insert_update('update', TBL_USERS, $updateArr, array('id' => $record_id));

			$this->session->set_flashdata('success', 'Data has been updated successfully.');
			redirect('admin/users/');
		}
		$data = array(
			'title' => 'Edit Transponder',
			'dataArr' => $dataArr,
			'rolesArr' => $rolesArr,
			'record_id' => $record_id
		);
		$this->template->load('default', 'admin/users/users_add', $data);
	}
	/**
	 * Delete Transponder
	 * @param $id - String
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function delete_users($id = '') {
//		controller_validation();
		$record_id = base64_decode($id);
		$this->users_model->insert_update('delete', TBL_USERS, '', array('id' => $record_id));
		$this->session->set_flashdata('error', 'Data data has been deleted successfully.');
		redirect('admin/users/');
	}
    /**
     * Display Users
     * @param  : ---
     * @return : ---
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function display_users() {
        $data['title'] = 'Display Users';
        $this->template->load('default', 'admin/users/display', $data);
    }

    /**
     * Display Users Request
     * @param  : ---
     * @return : ---
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function display_users_request() {
        $data['title'] = 'Display Users Request';
        $this->template->load('default', 'admin/users/display_request', $data);
    }

    /**
     * Display Users Request
     * @param  : ---
     * @return : ---
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function display_cancel_subscription() {
        $data['title'] = 'Display cancel subscription';
        $this->template->load('default', 'admin/users/display_cancel_subscription', $data);
    }

    /**
     * Get data of Users request for listing
     * @param  : ---
     * @return : json
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function get_ajax_data() {
        $final['recordsTotal'] = $this->users_model->get_ajax_data('count', ['u.status' => 'pending']);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $staff = $this->users_model->get_ajax_data('result', ['u.status' => 'pending']);
        $start = $this->input->get('start') + 1;
        foreach ($staff as $key => $val) {
            $staff[$key] = $val;
            $staff[$key]['sr_no'] = $start++;
            $staff[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $staff[$key]['responsive'] = '';
        }
        $final['data'] = $staff;
        echo json_encode($final);
    }

    /**
     * Get data of cancle subscription Users listing
     * @param  : ---
     * @return : json
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function get_cancel_subscription_users_ajax_data() {
        $final['recordsTotal'] = $this->users_model->get_cancel_subscription_users_ajax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $staff = $this->users_model->get_cancel_subscription_users_ajax_data('result');
        $start = $this->input->get('start') + 1;
        foreach ($staff as $key => $val) {
            $staff[$key] = $val;
            $staff[$key]['sr_no'] = $start++;
            $staff[$key]['modified_date'] = date('m-d-Y', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $staff[$key]['responsive'] = '';
        }
        $final['data'] = $staff;
        echo json_encode($final);
    }


    /**
     * Get data of Users for listing
     * @param  : ---
     * @return : json
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function get_users_ajax_data() {
        $final['recordsTotal'] = $this->users_model->get_ajax_data('count', ['u.status !=' => 'pending']);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $staff = $this->users_model->get_ajax_data('result', ['u.status !=' => 'pending']);
        $start = $this->input->get('start') + 1;
        foreach ($staff as $key => $val) {
            $staff[$key] = $val;
            $staff[$key]['sr_no'] = $start++;
            $staff[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $staff[$key]['responsive'] = '';
        }
        $final['data'] = $staff;
        echo json_encode($final);
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
        $user = $this->users_model->check_unique_email_for_staff($data);
        if ($user > 0) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    public function action($action, $id) {
        $data['stripe_data'] = get_stripe_data();
        require_once APPPATH . "third_party/stripe/init.php";
        \Stripe\Stripe::setApiKey($data['stripe_data']['STRIPE_SECRET_KEY']);

        $record_id = base64_decode($id);
        $redirect = 'admin/users';

        $subscription_data = $this->subscription_model->get_user_subscription_details($record_id);

        $condition = array(
            'duration' => 'FOREVER',
            'is_deleted' => 0
        );

        $free_coupon = $this->users_model->get_all_details(TBL_SUBSCRIPTIONS, $condition)->row_array();

        if ($action == 'approve'):
            $res = [
                'status' => 'active',
            ];
            $condition = array(
                'id' => $record_id,
                'is_delete' => 0
            );
            $dataArr = $this->users_model->get_all_details(TBL_USERS, $condition)->row_array();
            if (!empty($dataArr)):
                extract($dataArr);
                $password = $org_pass = randomPassword();
                $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
                $algo = $password_encrypt_key . $password . $password_encrypt_key;
                $encrypted_pass = hash('sha256', $algo);
                $user_array = [
                    'password' => $encrypted_pass,
                    'password_encrypt_key' => $password_encrypt_key,
                    'package_activated_date' => date('Y-m-d'),
                    'renewal_date' => date('Y-m-d h:i:s', strtotime('+1 months')),
                ];

                $id = $this->users_model->insert_update('update', TBL_USERS, $user_array, ['id' => $record_id]);
                if ($id) :
                    $email_var = array(
                        'user_id' => $record_id,
                        'first_name' => $first_name,
                        'username' => $username,
                        'email_id' => $email_id,
                        'password' => $org_pass
                    );
                    $message = $this->load->view('email_template/default_header.php', $email_var, true);
                    $message .= $this->load->view('email_template/user_registration.php', $email_var, true);
                    $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
                    $email_array = array(
                        'mail_type' => 'html',
                        'from_mail_id' => $this->config->item('smtp_user'),
                        'from_mail_name' => 'ARK Team',
                        'to_mail_id' => $email_id,
                        'cc_mail_id' => '',
                        'subject_message' => 'Account Approved',
                        'body_messages' => $message
                    );
                    $email_send = common_email_send($email_array);
                endif;
            endif;
            $redirect = 'admin/users/request';
            $this->session->set_flashdata('success', 'User request has been approved successfully.');
        elseif ($action == 'reject'):
            $redirect = 'admin/users/request';

            $res = [
                'status' => 'rejected'
            ];

            try {
                if (!empty($subscription_data) && $subscription_data['customer_id']) {
                    $subscription_id = $subscription_data['subscription_id'];

                    $subscription = \Stripe\Subscription::retrieve($subscription_id);
                    $is_canceled = $subscription->cancel();

                    if (isset($is_canceled)) {
                        // Email notification for Admin after pause subscription.
                        $email_var = array(
                            'full_name' => $subscription_data['full_name']
                        );

                        $message = $this->load->view('email_template/default_header.php', $email_var, true);
                        $message .= $this->load->view('email_template/notification_of_canceled_subscription_on_reject_account.php', $email_var, true);
                        $message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                        $email_array = array(
                            'mail_type' => 'html',
                            'from_mail_id' => $this->config->item('smtp_user'),
                            'from_mail_name' => 'ARK Team',
                            'to_mail_id' => $subscription_data['email_id'],
                            'cc_mail_id' => 'support@reliablekeys.com',
                            'bcc_mail_id' => '',
                            'subject_message' => 'Your Account Has Been Rejected - ARK Admin',
                            'body_messages' => $message
                        );

                        common_email_send($email_array);
                    }
                }
            } catch (Exception $ex) {
                $this->session->set_flashdata('error', $ex->getMessage());
                redirect($redirect);
            }

            $this->session->set_flashdata('success', 'User request has been rejected successfully.');
        elseif ($action == 'active'):
            $res = [
                'status' => 'active'
            ];

            if (!empty($subscription_data) && $subscription_data['customer_id']) {
                try {
                    $subscription_id = $subscription_data['subscription_id'];
                    //Set free coupnon code on update subscription
                    $subscription = \Stripe\Subscription::retrieve($subscription_id);
                    $subscription->coupon = null;
                    $is_updated_subscription = $subscription->save();

                    if ($is_updated_subscription) {
                        // Email notification for Admin after pause subscription.
                        $email_var = array(
                            'full_name' => $subscription_data['full_name']
                        );

                        $message = $this->load->view('email_template/default_header.php', $email_var, true);
                        $message .= $this->load->view('email_template/notification_of_resume_subscription.php', $email_var, true);
                        $message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                        $email_array = array(
                            'mail_type' => 'html',
                            'from_mail_id' => $this->config->item('smtp_user'),
                            'from_mail_name' => 'ARK Team',
                            'to_mail_id' => $subscription_data['email_id'],
                            'cc_mail_id' => 'support@reliablekeys.com',
                            'bcc_mail_id' => '',
                            'subject_message' => 'Your Account Has Been Re-Activated - ARK Admin',
                            'body_messages' => $message
                        );

                        common_email_send($email_array);
                    }
                } catch (Exception $ex) {
                    $error = $ex->getMessage();
                    $this->session->set_flashdata('error', $error);
                    redirect($redirect);
                }
            }

            $this->session->set_flashdata('success', 'User has been Reactivated successfully.');
        elseif ($action == 'block'):
            $res = [
                'status' => 'block'
            ];

            if (!empty($subscription_data) && $subscription_data['customer_id']) {
                try {
                    $subscription_id = $subscription_data['subscription_id'];
                    if (count($free_coupon) > 0) {
                        //Set free coupnon code on update subscription
                        $subscription = \Stripe\Subscription::retrieve($subscription_id);
                        $subscription->coupon = $free_coupon['name'];
                        $is_updated_subscription = $subscription->save();

                        if ($is_updated_subscription) {
                            // Email notification for Admin after pause subscription.
                            $email_var = array(
                                'full_name' => $subscription_data['full_name']
                            );

                            $message = $this->load->view('email_template/default_header.php', $email_var, true);
                            $message .= $this->load->view('email_template/notification_of_pause_subscription.php', $email_var, true);
                            $message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                            $email_array = array(
                                'mail_type' => 'html',
                                'from_mail_id' => $this->config->item('smtp_user'),
                                'from_mail_name' => 'ARK Team',
                                'to_mail_id' => $subscription_data['email_id'],
                                'cc_mail_id' => 'support@reliablekeys.com',
                                'bcc_mail_id' => '',
                                'subject_message' => 'Your Account Has Been Paused - ARK Admin',
                                'body_messages' => $message
                            );

                            common_email_send($email_array);
                        }
                    } else {
                        $this->session->set_flashdata('error', 'coupon not found. Please add 100% Free coupon.');
                        redirect($redirect);
                    }
                } catch (Exception $ex) {
                    $error = $ex->getMessage();
                    $this->session->set_flashdata('error', $error);
                    redirect($redirect);
                }
            }


            $this->session->set_flashdata('success', 'User has been paused(blocked) successfully.');
        endif;

        $this->users_model->insert_update('update', TBL_USERS, $res, array('id' => $record_id));
        redirect($redirect);
    }

    public function view($id = null) {
        if (!is_null($id)) {
            $data['title'] = 'View User Details';
            $record_id = base64_decode($id);
            $condition = array(
                'id' => $record_id,
                'is_delete' => 0
            );
            $data['dataArr'] = $this->users_model->get_all_details(TBL_USERS, $condition)->row_array();

            if (!empty($data['dataArr'])) {
                $data['dataArr']['package'] = $this->users_model->get_all_details(TBL_PACKAGES, ['id' => $data['dataArr']['package_id']])->row_array();
                $data['dataArr']['sub_users'] = $this->users_model->get_all_details(TBL_USERS, ['business_user_id' => $data['dataArr']['id'], 'is_delete' => 0])->result_array();
            }

            $data['dataArr']['state'] = $this->users_model->get_all_details(TBL_STATES, ['id' => $data['dataArr']['state_id']])->row_array()['name'];
            $this->template->load('default', 'admin/users/view', $data);
        }
    }

    public function get_total_users_under_account() {
        $data = array();
        $data['active_users_count'] = 0;
        $data['total_users_count'] = 0;

        $user_id = $this->input->post('user_id');
        $package_id = $this->input->post('package_id');

        if ($user_id) {
            //Total no. of users in selected package
            $condition = array(
                'id' => $package_id,
                'is_delete' => 0,
            );

            $total_user_details = $this->users_model->get_all_details(TBL_PACKAGES, $condition)->row_array();

            if (!empty($total_user_details)) {
                $data['total_users_count'] = $total_user_details['no_of_users'];
            }

            //Active user count
            $condition = array(
                'business_user_id' => $user_id,
                'is_delete' => 0,
                'status' => 'active'
            );

            $active_user_details = $this->users_model->get_all_details(TBL_USERS, $condition)->result_array();

            if (!empty($active_user_details)) {
                $data['active_users_count'] = count($active_user_details);
            }
        }

        echo json_encode($data);
    }

    public function get_payment_transaction_data($record_id) {
        $record_id = base64_decode($record_id);
        $where = ['pm.user_id' => $record_id];
        $final['recordsTotal'] = $this->users_model->get_user_payment_history('count', $where);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $staff = $this->users_model->get_user_payment_history('result', $where);
        $start = $this->input->get('start') + 1;
        foreach ($staff as $key => $val) {
            $staff[$key] = $val;
            $staff[$key]['sr_no'] = $start++;
            $staff[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['created']) + $_COOKIE['currentOffset']);
            $staff[$key]['responsive'] = '';
        }
        $final['data'] = $staff;
        echo json_encode($final);
    }

    public function get_account_under_users($record_id) {
        $record_id = base64_decode($record_id);
        $where = ['u.business_user_id' => $record_id];

        $final['recordsTotal'] = $this->users_model->get_account_under_users_data('count', $where);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $staff = $this->users_model->get_account_under_users_data('result', $where);
        $start = $this->input->get('start') + 1;
        $no = 2;
        foreach ($staff as $key => $val) {
            $staff[$key] = $val;
            $staff[$key]['sr_no'] = $no++;
            $staff[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $staff[$key]['responsive'] = '';
        }
        $final['data'] = $staff;
        echo json_encode($final);
    }

    public function delete($id) {
        $record_id = base64_decode($id);
        $this->users_model->insert_update('update', TBL_USERS, array('is_delete' => 1), array('id' => $record_id));
        $this->session->set_flashdata('success', 'User has been deleted successfully.');
        redirect('admin/users');
    }

    /**
     * Admin can check all the active users account using this function
     * @param  : $id String
     * @return : Boolean (true/false)
     * @author HGA [Added At : 04/01/2019]
     */
    public function review_account($id) {
        try {
            $user_id = base64_decode($id);
            $this->db->select('u.*');
            $this->db->from(TBL_USERS . ' as u');
            $this->db->group_start();
            $this->db->where('u.id', $user_id);
            $this->db->group_end();
            $this->db->where(array(
                'u.is_delete' => 0,
            ));
            $res = $this->db->get();
            $data = $res->row_array();

            if (!empty($data)) {
                if ($data['status'] != 'active' || $data['is_delete'] == 1) {
                    $this->session->set_flashdata('error', 'User no longer active. Please check again.');
                    redirect('/admin/users');
                }

                if (!empty($_SESSION) && !empty($_SESSION['is_item_intro_show'])) {
                    $this->session->unset_userdata('is_item_intro_show');
                }

                $ssn_data = array();
                $ssn_data['u_user_id'] = $data['id'];
                $ssn_data['u_first_name'] = $data['first_name'];
                $ssn_data['u_last_name'] = $data['last_name'];
                $ssn_data['u_username'] = $data['username'];
                $ssn_data['u_user_role'] = $data['user_role'];
                $ssn_data['u_email_id'] = $data['email_id'];
                $ssn_data['u_phone'] = $data['contact_number'];
                $ssn_data['u_quickbook_status'] = $data['quickbook_status'];
                $ssn_data['user_logged_in'] = 1;

                $this->session->set_userdata($ssn_data);

                redirect(site_url('/dashboard'));
            } else {
                $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
                redirect(site_url('/admin/users'));
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
            redirect(site_url('/admin/users'));
        }
    }

    public function get_requested_user_details() {
        $user_id = $this->input->post('user_id');
        $data['user_details'] = $this->users_model->get_all_details(TBL_USERS, ['id' => $user_id])->row_array();

        return $this->load->view('admin/partial_view/requested_users_data', $data);
    }

    public function send_otp_notification() {
        $admin_id = checkLogin('I');
        $admin_email = checkLogin('E');
        $otp = rand(100000, 1000000);

        $is_sent = 0;

        $is_updated = $this->users_model->insert_update('update', TBL_USERS, array('otp_verification' => md5($otp)), array('id' => $admin_id));

        if ($is_updated) {
            $email_var = array(
                'otp' => $otp
            );

            $message = $this->load->view('email_template/default_header.php', $email_var, true);
            $message .= $this->load->view('email_template/send_otp_notification_to_admin.php', $email_var, true);
            $message .= $this->load->view('email_template/default_footer.php', $email_var, true);

            $email_array = array(
                'mail_type' => 'html',
                'from_mail_id' => $this->config->item('smtp_user'),
                'from_mail_name' => 'ARK Team',
                'to_mail_id' => $admin_email,
                'cc_mail_id' => 'support@reliablekeys.com',
                'bcc_mail_id' => '',
                'subject_message' => 'Your Account Has Been Paused - ARK Admin',
                'body_messages' => $message
            );

            common_email_send($email_array);
            $is_sent = 1;
        }

        echo $is_sent;
        exit;
    }

    public function otp_verifitcatio_and_delete_user() {
        $data['status'] = 'error';
        $data['message'] = 'Something went wrong!';

        if (!empty($this->input->post())) {
            $otp_verification = $this->input->post('otp_verification');
            $user_id = $this->input->post('user_id');
            $admin_id = checkLogin('I');

            $admin_array = [
                'id' => $admin_id,
                'otp_verification' => md5($otp_verification)
            ];

            $is_valid_qr_code = $this->users_model->get_all_details(TBL_USERS, $admin_array)->row_array();

            if (!empty($is_valid_qr_code)) {
                $record_id = base64_decode($user_id);
                $is_deleted = $this->users_model->insert_update('update', TBL_USERS, array('is_delete' => 1), array('id' => $record_id));

                if ($is_deleted) {
                    $this->users_model->insert_update('update', TBL_USERS, array('otp_verification' => NULL), array('id' => $admin_id));
                    $data['status'] = 'success';
                    $data['message'] = 'User has been deleted successfully!';
                }
            } else {
                $data['status'] = 'error';
                $data['message'] = 'OTP has not been matched with our record.';
            }
        }

        echo json_encode($data);
        exit;
    }
}

/* End of file Users.php */
/* Location: ./application/controllers/Users.php */
