<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Mpdf\Mpdf;

class Company_Profile extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/users_model', 'admin/inventory_model', 'admin/package_model', 'admin/payment_model', 'admin/subscription_model'));
        $this->load->library('m_pdf');
    }

    public function index() {
        $stripe_package_id = '';

        if ((!$this->session->userdata('user_logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('/login', 'refresh');
        }
        $data['subscription_invoices'] = $data['upcoming_invoice'] = [];
        
        $data['stripe_data'] = get_stripe_data();
        require_once APPPATH . "third_party/stripe/init.php";
        \Stripe\Stripe::setApiKey($data['stripe_data']['STRIPE_SECRET_KEY']);
        $stripe_mode = get_stripe_mode();
        $data['stripe_mode'] = $stripe_mode;

        $data['dataArr'] = $this->users_model->get_profile(checkUserLogin('C'));
        $data['subscription_data'] = $this->subscription_model->get_user_subscription_details(checkUserLogin('C'));

        try {
            if (!empty($data['subscription_data']) && !empty($data['subscription_data']['customer_id'])) {
                $data['subscription_invoices'] = \Stripe\Invoice::all(['customer' => $data['subscription_data']['customer_id']])->jsonSerialize();
                $data['upcoming_invoice'] = \Stripe\Invoice::upcoming(["customer" => $data['subscription_data']['customer_id']])->jsonSerialize();
            }
        } catch (Exception $ex) {
            $data['subscription_invoices'] = $data['upcoming_invoice'] = [];
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('exp_month', 'Card Expiry Month', 'trim|required|less_than[13]|greater_than[0]');
            $this->form_validation->set_rules('exp_year', 'Card Expiry Year', 'trim|required');
            if ($this->form_validation->run() == true) {
                $amount = 0;
                $user_array = [
                    'first_name' => htmlentities($this->input->post('first_name')),
                    'last_name' => htmlentities($this->input->post('last_name')),
                    'full_name' => htmlentities($this->input->post('first_name')) . ' ' . htmlentities($this->input->post('last_name')),
                    'email_id' => htmlentities($this->input->post('email_id')),
                    'contact_number' => htmlentities($this->input->post('contact_number')),
                    'business_name' => htmlentities($this->input->post('business_name')),
                    'address' => htmlentities($this->input->post('address')),
                    'city' => htmlentities($this->input->post('city')),
                    'state_id' => htmlentities($this->input->post('state_id')),
                    'zip_code' => htmlentities($this->input->post('zip_code')),
                    'currency_id' => htmlentities($this->input->post('currency_id')),
                    'date_format_id' => htmlentities($this->input->post('date_format_id')),
                    'last_modified_by' => checkUserLogin('I'),
                ];

                extract($user_array);
                $credit_array = [
                    'card_type' => htmlentities($this->input->post('card_type')),
                    'v_code' => ($this->input->post('v_code') != '') ? htmlentities($this->input->post('v_code')) : $data['dataArr']['v_code'],
                    'billing_name' => htmlentities($this->input->post('billing_name')),
                    'billing_phone' => htmlentities($this->input->post('billing_phone')),
                    'billing_address' => htmlentities($this->input->post('billing_address')),
                    'billing_city' => htmlentities($this->input->post('billing_city')),
                    'billing_state_id' => htmlentities($this->input->post('billing_state_id')),
                    'billing_zip_code' => htmlentities($this->input->post('billing_zip_code')),
                    'stripe_token' => htmlentities($this->input->post('stripeToken')),
                ];
                if ($_FILES['profile_pic']['error'] == 0) {
                    $img_name_arr = explode('.', $_FILES['profile_pic']['name']);
                    $config['upload_path'] = './uploads/profile/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = '20000';
                    $config['overwrite'] = TRUE;
                    $image_name = date('Ymdhis') . '.' . $img_name_arr[(count($img_name_arr) - 1)];
                    $_FILES['profile_pic']['name'] = $image_name;
                    $this->upload->initialize($config);
                    if (($_FILES['profile_pic']['size'] > 2097152)) {
                        $message = 'File too large. File must be less than 2 megabytes.';
                        $this->session->set_flashdata('error', $message);
                        redirect('company_profile');
                    } else {
                        if ($this->upload->do_upload('profile_pic')) {
                            $user_array['profile_pic'] = $image_name;
                        } else {
                            $error = $this->upload->display_errors();
                            $this->session->set_flashdata('error', $error);
                            redirect('company_profile');
                        }
                    }
                }

                if (!empty($data['subscription_data']) && !empty($data['subscription_data']['subscription_id'])) {
                    $customer = \Stripe\Customer::retrieve($data['subscription_data']['customer_id']);
                    $check_customer_details = $customer->jsonSerialize();

                    if (!empty($check_customer_details)):
                        $stripe_card_id = $data['dataArr']['stripe_card_id'];
                        if ($this->input->post('card_change') && $this->input->post('card_change') == 1) {
                            if ($customer->default_source != null) {
//                                $customer->sources->create(array("source" => $this->input->post('stripeToken')));

                                $customer_card = \Stripe\Customer::update($data['subscription_data']['customer_id'], [
                                            'source' => $this->input->post('stripeToken')
                                        ])->jsonSerialize();

                                $credit_array['card_number'] = htmlentities(substr($this->input->post('credit_card'), -4));
                                $credit_array['exp_month'] = htmlentities($this->input->post('exp_month'));
                                $credit_array['exp_year'] = htmlentities($this->input->post('exp_year'));


                                if (!empty($customer_card)) {
                                    $stripe_card_id = $customer_card['sources']['data'][0]['id'];
                                	$credit_array['stripe_card_id'] = $stripe_card_id;
                                }
                            } else {
                                $this->session->set_flashdata('error', 'Something went wrong! Please check your payement details.');
                                redirect(site_url('/company_profile'));
                            }
                        } else if ($this->input->post('exp_month') != $data['dataArr']['exp_month'] || $this->input->post('exp_year') != $data['dataArr']['exp_year']) {
                            $response = $customer->sources->retrieve($data['dataArr']['stripe_card_id']);
                            $response->exp_month = $this->input->post('exp_month');
                            $response->exp_year = $this->input->post('exp_year');
                            $response->save();
                            if ($response):
                                $credit_array['exp_month'] = htmlentities($this->input->post('exp_month'));
                                $credit_array['exp_year'] = htmlentities($this->input->post('exp_year'));
                            else:
                                $this->session->set_flashdata('error', 'Something went wrong!');
                            endif;
                        }
                        $this->users_model->insert_update('update', TBL_CARD_DETAILS, $credit_array, ['id' => $data['dataArr']['id']]);

                        if ($this->input->post('package_change') && $this->input->post('package_change') == 1) {
                            $existing_package = $this->users_model->get_result(TBL_PACKAGES, ['id' => $data['dataArr']['package_id'], 'is_delete' => 0], null, 1);
                            $package = $this->users_model->get_result(TBL_PACKAGES, ['id' => $this->input->post('package_id'), 'is_delete' => 0], null, 1);
                            if (!empty($package) && !empty($existing_package)) {
                                if ($existing_package['price'] >= $package['price']) {
                                    $this->users_model->block_users_locations($package, $existing_package);
                                }

                                if ($stripe_mode == 'test' && !empty($package['stripe_test_package_id'])) {
                                    $stripe_package_id = $package['stripe_test_package_id'];
                                } else if ($stripe_mode == 'live' && !empty($package['stripe_live_package_id'])) {
                                    $stripe_package_id = $package['stripe_live_package_id'];
                                }

                                if (!empty($stripe_package_id)) {
                                    //Upgrade/Downgrade Subscription
                                    $stripe_subscription_id = $data['subscription_data']['subscription_id'];
                                    $subscription = \Stripe\Subscription::retrieve($stripe_subscription_id);
                                    $response = \Stripe\Subscription::update($stripe_subscription_id, [
                                                'items' => [
                                                    [
                                                        'id' => $subscription->items->data[0]->id,
                                                        'plan' => $stripe_package_id,
                                                    ],
                                                ],
                                    ]);
                                    $updated_subscription_json = $response->jsonSerialize();

                                    if (!empty($updated_subscription_json)):
                                        $amount = $package['price'];
                                        if ($amount != 0) :
                                            $payment_array = [
                                                'user_id' => checkUserLogin('C'),
                                                'card_detail_id' => $data['dataArr']['id'],
                                                'amount' => $amount,
                                                'description' => 'Upgrade/Downgrade Package'
                                            ];

                                            $user_array = [
                                                'package_id' => $this->input->post('package_id'),
                                                'package_activated_date' => date('Y-m-d'),
                                                'renewal_date' => date('Y-m-d h:i:s', strtotime('+1 months')),
                                            ];

                                            $card_id = $this->payment_model->insert_update('insert', TBL_PAYMENT, $payment_array);
                                            $this->users_model->insert_update('update', TBL_USERS, $user_array, ['id' => $data['dataArr']['uid']]);
                                        endif;

                                        //Update user subscription details
                                        $update_subscription_details = [
                                            'subscription_id' => $updated_subscription_json['id'],
                                            'package_id' => $package['id'],
                                            'stripe_plan_id' => $stripe_package_id,
                                            'status' => $updated_subscription_json['status'],
                                                // 'created_at' => date('Y-m-d H:i:s', $updated_subscription_json['modified_at']),
                                        ];

                                        $this->users_model->insert_update('update', TBL_USER_SUBSCRIPTIONS, $update_subscription_details, ['user_id' => checkUserLogin('C')]);
                                    endif;
                                }else {
                                    $this->session->set_flashdata('error', 'Can not find plan details on server. Please contact to admin.');
                                    redirect(site_url('/company_profile'));
                                }
                            }
                        }
                        $this->users_model->insert_update('update', TBL_USERS, $user_array, ['id' => $data['dataArr']['uid']]);
                        $this->session->set_flashdata('success', 'You have successfully updated company Profile.');
                    else:
                        $this->session->set_flashdata('error', 'Something went wrong!');
                    endif;
                }
            }
            redirect(site_url('/company_profile'));
        }
        $data['title'] = 'Company Profile';
        $data['states'] = $this->users_model->get_result(TBL_STATES);
        $data['taxes'] = $this->users_model->get_result(TBL_TAXES, ['is_deleted' => 0]);
        $data['currencies'] = $this->users_model->get_result(TBL_CURRENCY);
        $data['dateFormats'] = $this->users_model->get_result(TBL_DATE_FORMAT, ['is_deleted' => 0]);
        $data['package_Arr'] = $this->package_model->get_all_details(TBL_PACKAGES, array('is_delete' => 0))->result_array();
        $this->template->load('default_front', 'front/home/company_profile', $data);
    }

    public function change_password(){
        if ($this->input->post()) {
            if ($this->input->post('password') != '' && ($this->input->post('password') == $this->input->post('cpassword'))) {
                $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
                $algo = $password_encrypt_key . $this->input->post('password') . $password_encrypt_key;
                $encrypted_pass = hash('sha256', $algo);

                $update_array = [
                    'password' => $encrypted_pass,
                    'password_encrypt_key' => $password_encrypt_key
                ];

                $is_updated = $this->users_model->insert_update('update', TBL_USERS, $update_array, ['id' => checkUserLogin('I')]);

                if($is_updated){
                    $this->session->set_flashdata('success', 'Password has been changed successfully.');
                }else{
                    $this->session->set_flashdata('error', 'Password has not been changed. Please try again.');
                }
            }else{
                $this->session->set_flashdata('error', 'Your new password do not match with confirm password.');
            }
        }else{
            $this->session->set_flashdata('error', 'Something went wrong!');
        }

        redirect(site_url('/company_profile'));
    }

    public function download_view_billing_invoice($invoice_id) {
        require_once APPPATH . "third_party/stripe/init.php";
        $data['stripe_data'] = get_stripe_data();
        \Stripe\Stripe::setApiKey($data['stripe_data']['STRIPE_SECRET_KEY']);

        try {
            $invoice_id = base64_decode($invoice_id);

            if (!empty($invoice_id)) {
                $invoice_data['subscription_data'] = $this->subscription_model->get_user_subscription_details(checkUserLogin('C'));
                $invoice_data['invoice'] = \Stripe\Invoice::retrieve($invoice_id)->jsonSerialize();

                if ($invoice_data['invoice']) {
                    $html = $this->load->view('front/home/billing_invoice_html.php', $invoice_data, true);

                    // require_once FCPATH . 'vendor/autoload.php';
                    // $mpdf = new Mpdf();

                    if ($_SERVER['HTTP_HOST'] == 'www.alwaysreliablekeys.com') {
                        require_once FCPATH . 'vendor/autoload.php';
                    } else if ($_SERVER['HTTP_HOST'] == 'clientapp.narola.online') {
                        require_once FCPATH . 'vendor/autoload.php';
                    } else {
                        require_once FCPATH . 'vendor 1/autoload.php';
                    }
        
                    // require_once FCPATH . 'vendor/autoload.php';
                    
                    // For Online Server(ClientApp)
                    // $mpdf = new Mpdf();
                    // Load Library 
                    // For Off Line (Localhost)
                    // $mpdf = new \mPDF();

                    if ($_SERVER['HTTP_HOST'] == 'alwaysreliablekeys.com') {
                       $mpdf = new Mpdf();
                    } else if ($_SERVER['HTTP_HOST'] == 'clientapp.narola.online') {
                        $mpdf = new Mpdf();
                    } else {
                       $mpdf = new \mPDF();
                    }


                    $mpdf->SetTitle($invoice_data['subscription_data']['full_name'] . "'s Billing Invoice");
                    $mpdf->defaultfooterline = 0;

                    // Invoice Header                    
                    $mpdf->SetHeader('<div style="display: inline; width:100%; border-bottom: 5px solid white;">
                        <div align="right" style="text-align: right; ">                        
                                <img width="150Px" style="display:block;margin-left:auto;" src="' . site_url('assets/images/logo.png') . '" alt="">
                        </div>
                    </div>');

                    //Invoice Footer
                    $mpdf->setFooter('<div align="left" style="border-top: 3px solid #888;"><br>
                            <div class="footer-bg" style="text-align:center;">Always Reliable Keys at support@reliablekeys.com or call at +1
                                208-313-5332.</div>
                        </div>
                        <br>
                        <div class="footer-bg" style="text-align:center;"><span>Page:</span> {PAGENO}</div>');

                    $stylesheet = file_get_contents(site_url('assets/css/invoice.css'));

                    $mpdf->AddPage('P', // L - landscape, P - portrait 
                            '', '', '', '', 7, // margin_left
                            7, // margin right
                            25, // margin top
                            5, // margin bottom
                            5, // margin header
                            7  // margin footer
                    );

                    $mpdf->WriteHTML($stylesheet, 1);
                    $mpdf->WriteHTML($html, 2);
                    $filename = $invoice_data['invoice']['number'] . '-' . date('Ymdhis') . ".pdf";
                    $mpdf->Output($filename, "I");
                } else {
                    $this->session->set_flashdata('error', 'Your billing details not found.');
                    redirect(site_url('/company_profile/'));
                }
            } else {
                $this->session->set_flashdata('error', 'Something went wrong!');
                redirect(site_url('/company_profile'));
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', 'Something went wrong!');
            redirect(site_url('/company_profile'));
        }
    }

    public function download_view_upcoming_invoice() {
        require_once APPPATH . "third_party/stripe/init.php";
        $data['stripe_data'] = get_stripe_data();
        \Stripe\Stripe::setApiKey($data['stripe_data']['STRIPE_SECRET_KEY']);

        if ($this->input->post()) {
            $customer_id = base64_decode($this->input->post('stripe_customer_id'));
            $invoice_data['upcoming_invoice'] = \Stripe\Invoice::upcoming(["customer" => $customer_id])->jsonSerialize();

            $html = $this->load->view('front/home/upcoming_billing_invoice', $invoice_data, true);
            echo $html;
            exit;
        }
    }

    public function cancel_subscription() {
        $data['stripe_data'] = get_stripe_data();
        require_once APPPATH . "third_party/stripe/init.php";
        \Stripe\Stripe::setApiKey($data['stripe_data']['STRIPE_SECRET_KEY']);

        try {
            $user_subscription_details = $this->subscription_model->get_user_subscription_details(checkUserLogin('C'));

            if (!empty($user_subscription_details) && !empty($user_subscription_details['subscription_id'])) {
                $subscription = \Stripe\Subscription::retrieve($user_subscription_details['subscription_id']);
                $is_canceled_subscription = $subscription->cancel();

                if ($is_canceled_subscription) {
                    $record_array = [
                        'is_delete' => 1
                    ];
                    $is_deleted = $this->subscription_model->insert_update('update', TBL_USERS, $record_array, array('id' => checkUserLogin('C')));

                    if ($is_deleted) {
                        // Email notification for Admin after canceled subscription.
                        $email_var = array(
                            'full_name' => $user_subscription_details['full_name'],
                            'email_id' => $user_subscription_details['email_id'],
                            'contact_number' => $user_subscription_details['contact_number'],
                            'package_name' => $user_subscription_details['package_name'],
                            'package_price' => $user_subscription_details['package_price'],
                        );

                        $message = $this->load->view('email_template/default_header.php', $email_var, true);
                        $message .= $this->load->view('email_template/send_admin_notification_of_cancel_subscription.php', $email_var, true);
                        $message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                        $email_array = array(
                            'mail_type' => 'html',
                            'from_mail_id' => $this->config->item('smtp_user'),
                            'from_mail_name' => 'ARK Team',
                            'to_mail_id' => 'alwaysreliablekeys@gmail.com',
                            'cc_mail_id' => 'support@reliablekeys.com',
                            'bcc_mail_id' => 'hpa@narola.email',
                            'subject_message' => 'User has cancelled subscription!!',
                            'body_messages' => $message
                        );

                        common_email_send($email_array);

                        $user_message = $this->load->view('email_template/default_header.php', $email_var, true);
                        $user_message .= $this->load->view('email_template/user_cancelled_subscription.php', $email_var, true);
                        $user_message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                        $user_email_array = array(
                            'mail_type' => 'html',
                            'from_mail_id' => $this->config->item('smtp_user'),
                            'from_mail_name' => 'ARK Team',
                            'to_mail_id' => $user_subscription_details['email_id'],
                            'cc_mail_id' => 'support@reliablekeys.com',
                            'bcc_mail_id' => '',
                            'subject_message' => 'Subscription with ' . $user_subscription_details['package_name'] . ' has been canceled.',
                            'body_messages' => $user_message
                        );

                        common_email_send($user_email_array);

                        //Logout from system after cancelled subscription.
                        delete_cookie('_user_remember_me', MY_DOMAIN_NAME);
                        $array_items = array('u_user_id', 'u_first_name', 'u_last_name', 'u_user_role', 'u_email_id', 'u_phone', 'user_logged_in');
                        $this->session->unset_userdata($array_items);

                        $this->session->set_flashdata('success', 'Your subscription has been cancelled successfully.');
                        redirect(site_url('/login'));
                    }
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong with you cancel subscription');
                    redirect(site_url('/company_profile'));
                }
            } else {
                $this->session->set_flashdata('error', 'You subscription details not found.');
                redirect(site_url('/company_profile'));
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', 'Something went wrong!');
            redirect(site_url('/company_profile'));
        }
    }

    public function send_billing_invoice($invoice_id) {
        require_once APPPATH . "third_party/stripe/init.php";
        $data['stripe_data'] = get_stripe_data();
        \Stripe\Stripe::setApiKey($data['stripe_data']['STRIPE_SECRET_KEY']);

        try {
            $invoice_id = base64_decode($invoice_id);

            if (!empty($invoice_id)) {
                $invoice_data['UserInfo'] = $this->subscription_model->get_all_details(TBL_USERS, array('email_id' => checkUserLogin('E'), 'is_delete' => 0))->row_array();
                $invoice_data['invoice'] = \Stripe\Invoice::retrieve($invoice_id)->jsonSerialize();

                if ($invoice_data['invoice']) {
                    $message = $this->load->view('email_template/default_header.php', $invoice_data, true);
                    $message .= $this->load->view('email_template/send_email_billing_invoice.php', $invoice_data, true);

                    $email_array = array(
                        'mail_type' => 'html',
                        'from_mail_id' => $this->config->item('smtp_user'),
                        'from_mail_name' => 'ARK Team',
                        'to_mail_id' => $invoice_data['UserInfo']['email_id'],
                        'cc_mail_id' => '',
                        'subject_message' => 'Billing Invoice - ARK Lock',
                        'body_messages' => $message
                    );
                    $email_send = common_email_send($email_array);

                    $this->session->set_flashdata('success', 'Billing invoice sent to your email.');
                    redirect(site_url('company_profile'));
                } else {
                    $this->session->set_flashdata('error', 'Your billing details not found.');
                    redirect(site_url('/company_profile/'));
                }
            } else {
                $this->session->set_flashdata('error', 'Something went wrong!');
                redirect(site_url('/company_profile'));
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', 'Something went wrong!');
            redirect(site_url('/company_profile'));
        }
    }
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */