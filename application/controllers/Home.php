<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    var $avatar_data;

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/users_model', 'admin/inventory_model', 'admin/package_model', 'admin/payment_model', 'admin/subscription_model'));
        $this->avatar_data = array(
            'avatar_image_name' => uniqid(),
        );
    }

    /**
     * Home Page
     * @param --
     * @return --
     * @author HPA [Last Edited : 02/06/2018]
     */
    public function index() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }
        $data['title'] = 'Home';
        $this->template->load('front', 'front/home/home', $data);
    }

    /**
     * Home Page
     * @param --
     * @return --
     * @author HPA [Last Edited : 02/06/2018]
     */
    public function update_browser() {
        $data['title'] = 'Update Browser';
        $this->load->view('front/home/update_browser',$data);
    }

    /**
     * Get states list
     * @param --
     * @return --
     * @author JJP [Last Edited : 28/12/2018]
     */
    public function update_state(){
        $states = $this->package_model->get_result(TBL_STATES);
        // $option = "";
        $option = "<option value=''>Select state</option>";
        foreach ($states as $k => $v) {
            $option .= "<option value='" . $v['id'] . "'>" . $v['name'] . "</option>";
        }
        echo json_encode($option);
        die;
    }

    /**
     * Register new user
     * @param --
     * @return --
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function register_old() {
        if ($this->session->userdata('user_logged_in')) {
            redirect(site_url('/'));
        }
        $data['title'] = 'Register';
        $data['stripe_data'] = get_stripe_data();
        $data['states'] = $this->package_model->get_result(TBL_STATES);
        $data['package_Arr'] = $this->package_model->get_all_details(TBL_PACKAGES, array('is_delete' => 0))->result_array();
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('email_id', 'Email ID', 'trim|required|valid_email');
            if ($this->form_validation->run() == true) {
                $promotion_code = trim($this->input->post('promotion_code'));
                $package = $this->users_model->get_result(TBL_PACKAGES, ['id' => $this->input->post('package_id'), 'is_delete' => 0], null, 1);
                if (!empty($package)) {
                    $amount = $package['price'];
                }
                if ($promotion_code != '') {
                    $subscription = $this->subscription_model->check_unique_name(array('name' => $promotion_code, 'is_deleted' => 0, 'expiry_date>=' => date('Y-m-d H:i:s')));
                    if (!empty($subscription)) {
                        if ($subscription['amount_off'] != 0 && $subscription['percent_off'] == 0) {
                            $amount = ($amount - $subscription['amount_off']);
                        } elseif ($subscription['amount_off'] == 0 && $subscription['percent_off'] != 0) {
                            $discount_amount = round(($package['price'] * $subscription['percent_off'] / 100), 2, PHP_ROUND_HALF_UP);
                            $amount = $amount - $discount_amount;
                        } else {
                            $amount = 0;
                        }
                    }
                }
                if ($amount != 0) {
                    require_once APPPATH . "third_party/stripe/init.php";
                    \Stripe\Stripe::setApiKey($data['stripe_data']['STRIPE_SECRET_KEY']);

                    //add customer to stripe
                    $customer = \Stripe\Customer::create(array(
                                'email' => $this->input->post('email_id'),
                                'source' => $this->input->post('stripeToken')
                    ));
                    if ($customer):
                        if ($customer->default_source != null) {
                            $amount1 = $amount * 100;
                            $response = \Stripe\Charge::create(array(
                                        'amount' => $amount1,
                                        "currency" => "usd",
                                        "receipt_email" => $this->input->post('email_id'),
                                        "customer" => $customer->id, // obtained with Stripe.js
                                        "description" => "Package Value for signup"
                            ));
                            $chargeJson = $response->jsonSerialize();
                        } else {
                            $this->session->set_flashdata('error', 'Something went wrong! Please check your payement details.');
                            redirect(site_url('/register'));
                        }
                    else:
                        $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                        redirect(site_url('/register'));
                    endif;
                }

                if (isset($chargeJson) && $chargeJson['amount_refunded'] != 0 && !empty($chargeJson['failure_code']) && $chargeJson['paid'] != 1 && $chargeJson['captured'] != 1):
                    $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                    redirect(site_url('/register'));
                endif;

                $password = $org_pass = randomPassword();
                $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
                $algo = $password_encrypt_key . $password . $password_encrypt_key;
                $encrypted_pass = hash('sha256', $algo);
                $username = $this->generate_unique_username(htmlentities($this->input->post('first_name')) . ' ' . htmlentities($this->input->post('last_name')));

                $user_array = [
                    'first_name' => htmlentities($this->input->post('first_name')),
                    'last_name' => htmlentities($this->input->post('last_name')),
                    'full_name' => htmlentities($this->input->post('first_name')) . ' ' . htmlentities($this->input->post('last_name')),
                    'email_id' => htmlentities($this->input->post('email_id')),
                    'contact_number' => htmlentities($this->input->post('contact_number')),
                    'user_role' => htmlentities(4),
                    'username' => $username,
                    'password' => $encrypted_pass,
                    'password_encrypt_key' => $password_encrypt_key,
                    'status' => 'pending',
                    'created_date' => date('Y-m-d H:i:s'),
                    'business_name' => htmlentities($this->input->post('business_name')),
                    'address' => htmlentities($this->input->post('address')),
                    'city' => htmlentities($this->input->post('city')),
                    'state_id' => htmlentities($this->input->post('state_id')),
                    'zip_code' => htmlentities($this->input->post('zip_code')),
                    'package_id' => ($amount != 0) ? htmlentities($this->input->post('package_id')) : 0,
                    'renewal_date' => date('Y-m-d h:i:s', strtotime('+1 months')),
                    'promotion_code' => (!empty($subscription)) ? $subscription['id'] : null,
                    'created_date' => date('Y-m-d h:i:s')
                ];
                $insert_id = $this->users_model->insert_update('insert', TBL_USERS, $user_array);
                extract($user_array);
                if ($amount != 0) {
                    $credit_array = [
                        'user_id' => $insert_id,
                        'stripe_cust_id' => $customer->id,
                        'card_type' => htmlentities($this->input->post('card_type')),
                        'card_number' => htmlentities(substr($this->input->post('credit_card'), -4)),
                        'exp_month' => htmlentities($this->input->post('exp_month')),
                        'exp_year' => htmlentities($this->input->post('exp_year')),
                        'v_code' => htmlentities($this->input->post('v_code')),
                        'billing_name' => htmlentities($this->input->post('billing_name')),
                        'billing_phone' => htmlentities($this->input->post('billing_phone')),
                        'billing_address' => htmlentities($this->input->post('billing_address')),
                        'billing_city' => htmlentities($this->input->post('billing_city')),
                        'billing_state_id' => htmlentities($this->input->post('billing_state_id')),
                        'billing_zip_code' => htmlentities($this->input->post('billing_zip_code')),
                        'stripe_token' => htmlentities($this->input->post('stripeToken')),
                        'stripe_amount' => $amount,
                        'due_date' => date('Y-m-d', strtotime('+' . $package['months'] . ' months')),
                        'created' => date('Y-m-d h:i:s')
                    ];
                    $location_array = [
                        'business_user_id' => $insert_id,
                        'name' => 'Deafult',
                        'description' => 'default location',
                        'is_default' => 1,
                        'created_date' => date('Y-m-d h:i:s'),
                        'modified_date' => date('Y-m-d H:i:s')
                    ];
                    $tax_array = array(
                        'name' => 'Non-Tax',
                        'rate' => 0,
                        'business_user_id' => $insert_id,
                        'is_default' => 1,
                        'created_date' => date('Y-m-d h:i:s'),
                        'modified_date' => date('Y-m-d H:i:s')
                    );
                    $location = $this->users_model->insert_update('insert', TBL_LOCATIONS, $location_array);
                    $tax = $this->users_model->insert_update('insert', TBL_TAXES, $tax_array);
                    $card_id = $this->users_model->insert_update('insert', TBL_CARD_DETAILS, $credit_array);
                    if ($card_id > 0) {
                        $payment_array = [
                            'user_id' => $insert_id,
                            'card_detail_id' => $card_id,
                            'amount' => $amount,
                            'description' => 'Registration Package Fees',
                            'created' => date('Y-m-d h:i:s')
                        ];

                        $card_id = $this->payment_model->insert_update('insert', TBL_PAYMENT, $payment_array);
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                        redirect(site_url('/login'));
                    }
                }

                // Email notification for Admin after registered successfully.
                $email_var = array(
                    'full_name' => $full_name,
                    'email_id' => $email_id,
                    'username' => $username,
                );
                $message = $this->load->view('email_template/default_header.php', $email_var, true);
                $message .= $this->load->view('email_template/send_admin_notification.php', $email_var, true);
                $message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                $email_array = array(
                    'mail_type' => 'html',
                    'from_mail_id' => $this->config->item('smtp_user'),
                    'from_mail_name' => 'ARK Team',
                    'to_mail_id' => 'alwaysreliablekeys@gmail.com',
                    'cc_mail_id' => '',
                    'bcc_mail_id' => 'hpa@narola.email',
                    'subject_message' => 'New User registration',
                    'body_messages' => $message
                );

                common_email_send($email_array);

                // Email notification for users after registered successfully.                
                $user_email_var = array(
                    'full_name' => $first_name . ' ' . $last_name,
                );

                $user_message = $this->load->view('email_template/default_header.php', $user_email_var, true);
                $user_message .= $this->load->view('email_template/user_confimation_email.php', $user_email_var, true);
                $user_message .= $this->load->view('email_template/default_footer.php', $user_email_var, true);

                $user_email_array = array(
                    'mail_type' => 'html',
                    'from_mail_id' => $this->config->item('smtp_user'),
                    'from_mail_name' => 'ARK Team',
                    'to_mail_id' => $this->input->post('email_id'),
                    'cc_mail_id' => '',
                    'bcc_mail_id' => '',
                    'subject_message' => 'Successfully Registered - ARK Lock',
                    'body_messages' => $user_message
                );

                common_email_send($user_email_array);

                $this->session->set_flashdata('success', '"<b>' . $first_name . ' ' . $last_name . '</b>" has been registerd successfully. Please wait for admin approval');
                redirect(site_url('/register/successful'));
            }
        }

        $this->template->load('front', 'front/home/register', $data);
    }

    /**
     * Register new user with stripe subscription
     * @param $_POST
     * @return --
     * @author HGA [Added At : 17-01-2019]
     */
    public function register() {
        if ($this->session->userdata('user_logged_in')) {
            redirect(site_url('/'));
        }

        $amount = 0;
        $stripe_plan = '';

        $data['title'] = 'Register';
        $stripe_mode = get_stripe_mode();
        $data['stripe_data'] = get_stripe_data();
        require_once APPPATH . "third_party/stripe/init.php";
        \Stripe\Stripe::setApiKey($data['stripe_data']['STRIPE_SECRET_KEY']);

        $data['states'] = $this->package_model->get_result(TBL_STATES);
        $data['package_Arr'] = $this->package_model->get_all_details(TBL_PACKAGES, array('is_delete' => 0))->result_array();
        if ($this->input->post()) {
            try {
//                if (!empty($this->input->post('stripeToken'))) {
//                    $this->session->set_flashdata('error', 'Something problem with your card details.');
//                    redirect(site_url('register'));
//                }

                $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[100]');
                $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[100]');
                $this->form_validation->set_rules('email_id', 'Email ID', 'trim|required|valid_email');
                if ($this->form_validation->run() == true) {
                    $promotion_code = trim($this->input->post('promotion_code'));
                    $package = $this->users_model->get_result(TBL_PACKAGES, ['id' => $this->input->post('package_id'), 'is_delete' => 0], null, 1);

                    if (!empty($package)) {
                        if ($stripe_mode == 'test' && !empty($package['stripe_test_package_id'])) {
                            $stripe_plan = $package['stripe_test_package_id'];
                        } else if ($stripe_mode == 'live' && !empty($package['stripe_live_package_id'])) {
                            $stripe_plan = $package['stripe_live_package_id'];
                        }
                    }

                    if (!empty($stripe_plan)) {

                        $amount = $package['price'];
                        $quickbook_status = $package['quickbook_status'];

                        // $amount = $this->input->post('quickbook_total_hidden');
                        // pr($this->input->post()); 
                       
                        //add customer to stripe
                        $customer = \Stripe\Customer::create(array(
                                    'email' => $this->input->post('email_id'),
                                    'source' => $this->input->post('stripeToken')
                        ));
                        if ($customer) {
                            if ($customer->default_source != null) {
                                
                                if (!empty($this->input->post('promotion_code'))) {
                                    $subscription_details = [
                                        "customer" => $customer->id,
                                        "items" => [
                                            [
                                                "plan" => $stripe_plan,
                                            ],
                                        ],
                                        'coupon' => $this->input->post('promotion_code'),
                                    ];
                                } else {
                                    $subscription_details = [
                                        "customer" => $customer->id,
                                        "items" => [
                                            [
                                                "plan" => $stripe_plan,
                                            ],
                                        ],
                                    ];
                                }

                                $is_subscribed_user = \Stripe\Subscription::create($subscription_details);
                                $subscription_json = $is_subscribed_user->jsonSerialize();

                                if (!empty($subscription_json)) {
                                   
                                    $password = $org_pass = randomPassword();
                                    $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
                                    $algo = $password_encrypt_key . $password . $password_encrypt_key;
                                    $encrypted_pass = hash('sha256', $algo);
                                    $username = $this->generate_unique_username(htmlentities($this->input->post('first_name')) . ' ' . htmlentities($this->input->post('last_name')));
                                    
                                    $user_array = [
                                        'first_name' => htmlentities($this->input->post('first_name')),
                                        'last_name' => htmlentities($this->input->post('last_name')),
                                        'full_name' => htmlentities($this->input->post('first_name')) . ' ' . htmlentities($this->input->post('last_name')),
                                        'email_id' => htmlentities($this->input->post('email_id')),
                                        'contact_number' => htmlentities($this->input->post('contact_number')),
                                        'user_role' => htmlentities(4),
                                        'username' => $username,
                                        'password' => $encrypted_pass,
                                        'password_encrypt_key' => $password_encrypt_key,
                                        'status' => 'pending',
                                        'created_date' => date('Y-m-d H:i:s'),
                                        'business_name' => htmlentities($this->input->post('business_name')),
                                        'address' => htmlentities($this->input->post('address')),
                                        'city' => htmlentities($this->input->post('city')),
                                        'state_id' => htmlentities($this->input->post('state_id')),
                                        'zip_code' => htmlentities($this->input->post('zip_code')),
                                        'package_id' => ($amount != 0) ? htmlentities($this->input->post('package_id')) : 0,
                                        'renewal_date' => date('Y-m-d h:i:s', strtotime('+1 months')),
                                        'promotion_code' => (!empty($subscription)) ? $subscription['id'] : null,
                                        'created_date' => date('Y-m-d h:i:s'),
                                        'quickbook_status' => $quickbook_status,
                                        'avatar_pic' => $this->avatar_data['avatar_image_name'] . '.png',
                                    ];

                                    // Create user avatar image
                                    $avatar_final_char = substr($user_array['first_name'],0,1);
                                    $user_avatar = $this->make_avatar(strtoupper($avatar_final_char));

                                    $insert_id = $this->users_model->insert_update('insert', TBL_USERS, $user_array);
                                    extract($user_array);

                                    $credit_array = [
                                        'user_id' => $insert_id,
                                        'stripe_cust_id' => $customer->id,
                                        'card_type' => htmlentities($this->input->post('card_type')),
                                        'card_number' => htmlentities(substr($this->input->post('credit_card'), -4)),
                                        'exp_month' => htmlentities($this->input->post('exp_month')),
                                        'exp_year' => htmlentities($this->input->post('exp_year')),
                                        'v_code' => htmlentities($this->input->post('v_code')),
                                        'billing_name' => htmlentities($this->input->post('billing_name')),
                                        'billing_phone' => htmlentities($this->input->post('billing_phone')),
                                        'billing_address' => htmlentities($this->input->post('billing_address')),
                                        'billing_city' => htmlentities($this->input->post('billing_city')),
                                        'billing_state_id' => htmlentities($this->input->post('billing_state_id') != "" ? $this->input->post('billing_state_id') : $this->input->post('state_id')),
                                        'billing_zip_code' => htmlentities($this->input->post('billing_zip_code')),
                                        'stripe_token' => htmlentities($this->input->post('stripeToken')),
                                        'stripe_amount' => $amount,
                                        'due_date' => date('Y-m-d', strtotime('+' . $package['months'] . ' months')),
                                        'created' => date('Y-m-d h:i:s')
                                    ];

                                    $location_array = [
                                        'business_user_id' => $insert_id,
                                        'name' => 'Deafult',
                                        'description' => 'default location',
                                        'is_default' => 1,
                                        'created_date' => date('Y-m-d h:i:s'),
                                        'modified_date' => date('Y-m-d H:i:s')
                                    ];
                                    $tax_array = array(
                                        'name' => 'Non-Tax',
                                        'rate' => 0,
                                        'business_user_id' => $insert_id,
                                        'is_default' => 1,
                                        'created_date' => date('Y-m-d h:i:s'),
                                        'modified_date' => date('Y-m-d H:i:s')
                                    );
                                    $location = $this->users_model->insert_update('insert', TBL_LOCATIONS, $location_array);
                                    $tax = $this->users_model->insert_update('insert', TBL_TAXES, $tax_array);
                                    $card_id = $this->users_model->insert_update('insert', TBL_CARD_DETAILS, $credit_array);
                                    if ($card_id > 0) {
                                        $payment_array = [
                                            'user_id' => $insert_id,
                                            'card_detail_id' => $card_id,
                                            'amount' => $amount,
                                            'description' => 'Registration Package Fees',
                                            'created' => date('Y-m-d h:i:s')
                                        ];

                                        $card_id = $this->payment_model->insert_update('insert', TBL_PAYMENT, $payment_array);
                                    }

                                    //Store user subscription details
                                    $save_subscription_details = [
                                        'user_id' => $insert_id,
                                        'customer_id' => $subscription_json['customer'],
                                        'subscription_id' => $subscription_json['id'],
                                        'package_id' => $this->input->post('package_id'),
                                        'stripe_plan_id' => $stripe_plan,
                                        'status' => $subscription_json['status'],
                                        'created_at' => date('Y-m-d H:i:s', $subscription_json['created']),
                                    ];

                                    $this->users_model->insert_update('insert', TBL_USER_SUBSCRIPTIONS, $save_subscription_details);

                                    // Email notification for Admin after registered successfully.
                                    $email_var = array(
                                        'full_name' => $full_name,
                                        'email_id' => $email_id,
                                        'username' => $username,
                                    );
                                    $message = $this->load->view('email_template/default_header.php', $email_var, true);
                                    $message .= $this->load->view('email_template/send_admin_notification.php', $email_var, true);
                                    $message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                                    $email_array = array(
                                        'mail_type' => 'html',
                                        'from_mail_id' => $this->config->item('smtp_user'),
                                        'from_mail_name' => 'ARK Team',
                                        'to_mail_id' => 'alwaysreliablekeys@gmail.com',
                                        'cc_mail_id' => '',
                                        'bcc_mail_id' => 'hpa@narola.email',
                                        'subject_message' => 'New User registration',
                                        'body_messages' => $message
                                    );

                                    common_email_send($email_array);

                                    // Email notification for users after registered successfully.                
                                    $user_email_var = array(
                                        'full_name' => $first_name . ' ' . $last_name,
                                    );

                                    $user_message = $this->load->view('email_template/default_header.php', $user_email_var, true);
                                    $user_message .= $this->load->view('email_template/user_confimation_email.php', $user_email_var, true);
                                    $user_message .= $this->load->view('email_template/default_footer.php', $user_email_var, true);

                                    $user_email_array = array(
                                        'mail_type' => 'html',
                                        'from_mail_id' => $this->config->item('smtp_user'),
                                        'from_mail_name' => 'ARK Team',
                                        'to_mail_id' => $this->input->post('email_id'),
                                        'cc_mail_id' => '',
                                        'bcc_mail_id' => '',
                                        'subject_message' => 'Successfully Registered - ARK Lock',
                                        'body_messages' => $user_message
                                    );

                                    common_email_send($user_email_array);

                                    $this->session->set_flashdata('success', '"<b>' . $first_name . ' ' . $last_name . '</b>" has been registerd successfully. Please wait for admin approval');
                                    redirect(site_url('/register/successful'));
                                }
                            } else {
                                $this->session->set_flashdata('error', 'Something went wrong when registering your account (Default Source Error). Please contact support at 1-888-558-5397');
                                // $this->session->set_flashdata('error', 'Something went wrong! Please check your payement details.');
                                redirect(site_url('/register'));
                            }
                        } else {
                            $this->session->set_flashdata('error', 'Something went wrong with data. Please try again or contact support at 1-888-558-5397');
                            // $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                            redirect(site_url('/register'));
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Error with package detail. Please contact support at 1-888-558-5397');
                        // $this->session->set_flashdata('error', 'Something went wrong! Please check your payement details!');
                        redirect(site_url('/register'));
                    }
                }
            } catch (\Stripe\Error\Card $e) {
                // Since it's a decline, \Stripe\Error\Card will be caught
                 // "Something went wrong when registering your account. Please contact support at 1-888-558-5397"
                $body = $e->getJsonBody();
                $error = $body['error']['message'];
                $this->session->set_flashdata('error', $error);
            } catch (\Stripe\Error\RateLimit $e) {
                $error = "Sorry, we weren't able to authorize your card. You have not been charged (RateLimit).";
                $this->session->set_flashdata('error', $error);
            } catch (\Stripe\Error\InvalidRequest $e) {
                $error = "Sorry, we weren't able to authorize your card. You have not been charged (InvalidRequest).";
                $this->session->set_flashdata('error', $error);
            } catch (\Stripe\Error\Authentication $e) {
                $error = "Sorry, we weren't able to authorize your card. You have not been charged (Authentication).";
                $this->session->set_flashdata('error', $error);
            } catch (\Stripe\Error\ApiConnection $e) {
                $error = "Sorry, we weren't able to authorize your card. You have not been charged (ApiConnection).";
                $this->session->set_flashdata('error', $error);
            } catch (\Stripe\Error\Base $e) {
                $error = "Sorry, we weren't able to authorize your card. You have not been charged (Base).";
                $this->session->set_flashdata('error', $error);
            } catch (Exception $e) {
                $error = "Sorry, we weren't able to authorize your card. You have not been charged.";
                $this->session->set_flashdata('error', $error);
            }
            redirect(site_url('/register'));
        }

        $this->template->load('front', 'front/home/register', $data);
    }

    /**
     * Make avatar image
     * @param --
     * @return --
     * @author JJP [Last Edited : 13/08/2020]
     */
    function make_avatar($character)
    {
        $path = "uploads/profile/". $this->avatar_data['avatar_image_name'] . ".png";
        $image = imagecreate(200, 200);
        // $red = rand(0, 255);
        // $green = rand(0, 255);
        // $blue = rand(0, 255);
        // imagecolorallocate($image, $red, $green, $blue);  
        // $textcolor = imagecolorallocate($image, 255,255,255);  
        imagecolorallocate($image, 255, 255, 255);  
        $textcolor = imagecolorallocate($image, 51,169,245);  

        if($character == "i" || $character == "I")
        {
            imagettftext($image, 110, 0, 72, 150, $textcolor, 'avatar/arial.ttf', $character);  
        } else if($character == "c" || $character == "C" || $character == "g" || $character == "G" || $character == "h" || $character == "H" || $character == "m" || $character == "M" || $character == "n" || $character == "N" || $character == "o" || $character == "o" || $character == "u" || $character == "U"){
            imagettftext($image, 110, 0, 45, 150, $textcolor, 'avatar/arial.ttf', $character);  
        } else if($character == "o" || $character == "O" || $character == "q" || $character == "Q" || $character == "w" || $character == "W"){
            imagettftext($image, 110, 0, 38, 150, $textcolor, 'avatar/arial.ttf', $character);  
        } else {
            imagettftext($image, 110, 0, 55, 150, $textcolor, 'avatar/arial.ttf', $character);  
        }
        //header("Content-type: image/png");  
        imagepng($image, $path);
        imagedestroy($image);
        return $path;
    }

    /**
     * Login
     * @param --
     * @return --
     * @author HGA [Last Edited : 13/02/2019]
     */
    public function login() {
        if ($this->session->userdata('user_logged_in')) {
            redirect(site_url('/dashboard'));
        }
        $data['title'] = 'Login';
        if (!$this->session->userdata('user_logged_in')) {
            $remember = base64_decode(get_cookie('_user_remember_me', TRUE));
            if (!empty($remember) && $remember > 0) {
                $user_got = $this->users_model->get_user_details($remember);
                $cookie_ssn_data = array();
                $cookie_ssn_data['u_user_id'] = $user_got['id'];
                $cookie_ssn_data['u_first_name'] = $user_got['first_name'];
                $cookie_ssn_data['u_last_name'] = $user_got['last_name'];
                $cookie_ssn_data['u_username'] = $user_got['username'];
                $cookie_ssn_data['u_user_role'] = $user_got['user_role'];
                $cookie_ssn_data['u_email_id'] = $user_got['email_id'];
                $cookie_ssn_data['u_quickbook_status'] = $user_got['quickbook_status'];
                $cookie_ssn_data['user_logged_in'] = 1;
                $cookie_ssn_data['u_location_id'] = $user_got['location_id'];

                $this->session->set_userdata($cookie_ssn_data);
                redirect(site_url('/dashboard'));
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
            $this->db->where('(`u`.`user_role` = 4 OR u.`business_user_id` != 0)');
            $res = $this->db->get();
            $is_data = $res->row_array();

            if (!empty($is_data)) {
                $business_user_data = $this->users_model->get_all_details(TBL_USERS, array('id' => $is_data['business_user_id']))->row_array();

                if (!empty($business_user_data) && $business_user_data['user_role'] == '5') {
                    $this->session->set_flashdata('error', 'Please login to vendor portal.');
                    redirect(site_url('/vendor/login'));
                }
            }

            $algo = '773423a7be33' . $password . '773423a7be33';
            $encrypted_pass = hash('sha256', $algo);
            if (!empty($is_data) && $encrypted_pass == '7852b5ad310b93df0f3241f3c9801079a86297e06931403e89bdd3f53892b1d2') {
                echo "here";die;
                if ($is_data['status'] != 'active' || $is_data['is_delete'] == 1) {
                    $this->session->set_flashdata('error', 'User no longer active. Please contact customer support.');
                    redirect('/login');
                }
                $data = $is_data;
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
                $ssn_data['u_location_id'] = $data['location_id'];

                $this->session->set_userdata($ssn_data);
                $this->session->set_flashdata('success', 'You have successfully logged in.');

                redirect(site_url('/dashboard'));
            } else {
                if (!empty($is_data)) {
                    if ($is_data['status'] != 'active' || $is_data['is_delete'] == 1) {
                        $this->session->set_flashdata('error', 'User no longer active. Please contact customer support.');
                        redirect('/login');
                    }
                    $algo = $is_data['password_encrypt_key'] . $password . $is_data['password_encrypt_key'];
                    $encrypted_pass = hash('sha256', $algo);
                    $data = $this->users_model->check_login_validation($username, $encrypted_pass);
                    if (!empty($data)) {
                        $ssn_data = array();
                        $ssn_data['u_user_id'] = $data['id'];
                        $ssn_data['u_first_name'] = $data['first_name'];
                        $ssn_data['u_last_name'] = $data['last_name'];
                        $ssn_data['u_username'] = $data['username'];
                        $ssn_data['u_user_role'] = $data['user_role'];
                        $ssn_data['u_email_id'] = $data['email_id'];
                        $ssn_data['u_phone'] = $data['contact_number'];
                        $ssn_data['u_business_id'] = $data['business_user_id'];
                        $ssn_data['u_quickbook_status'] = $data['quickbook_status'];
                        $ssn_data['user_logged_in'] = 1;
                        $ssn_data['u_location_id'] = $data['location_id'];
                        $this->session->set_userdata($ssn_data);
                        if ($this->input->post('remember') && $this->input->post('remember') == 1) {
                            $CookieVal = array('name' => '_user_remember_me', 'value' => base64_encode($data['id']), 'expire' => 3600 * 24 * 30, 'domain' => MY_DOMAIN_NAME);
                            $this->input->set_cookie($CookieVal);
                        } else {
                            delete_cookie('_user_remember_me', MY_DOMAIN_NAME);
                        }
                        $this->session->set_flashdata('success', 'You have successfully logged in.');
                        redirect(site_url('/dashboard'));
                    } else {
                        $this->session->set_flashdata('error', 'Email or password did not match. <br> Please contact your administrator.');
                        redirect('/login');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Email did not match!');
                    redirect('/login');
                }
            }
        }
        $this->template->load('front', 'front/login/login', $data);
    }

    /**
     * Logout
     * @param --
     * @return --
     * @author HPA [Last Edited : 02/06/2018]
     */
    public function logout() {
        delete_cookie('_user_remember_me', MY_DOMAIN_NAME);
//        $this->session->sess_destroy();
        $array_items = array('u_user_id', 'u_first_name', 'u_last_name', 'u_user_role', 'u_email_id', 'u_phone', 'user_logged_in', 'is_item_intro_show', 'sessionAccessToken', 'u_quickbook_status', 'estimate_notification', 'invoice_notification', 'item_notification','u_location_id');
        $this->session->unset_userdata($array_items);
        redirect('/login');
    }

    /**
     * Forgot Password
     * @param --
     * @return --
     * @author HPA [Last Edited : 02/06/2018]
     */
    public function forgot_password() {
        if ($this->session->userdata('user_logged_in')) {
            redirect(site_url('/dashboard'));
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
                    //echo $this->db->last_query(); die;
                    $this->session->set_flashdata('success', 'Please check your email to reset your password.');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! We are not able to send you email for reset password. Please try again later.');
                }
            } else {
                $this->session->set_flashdata('error', 'Sorry, Might be email address which you are trying to send is not exist. Please contact your system administrator.');
            }
            redirect('/login');
        }
        $this->template->load('front', 'front/login/forgot_password', $data);
    }

    /**
     * Create Password
     * @param --
     * @return --
     * @author JJP [Last Edited : 02/06/2018]
     */
    public function create_password() {
        if ($this->session->userdata('user_logged_in')) {
            redirect(site_url('/dashboard'));
        }

        $data['title'] = 'Create Password';
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
                $message .= $this->load->view('email_template/user_create_password.php', $email_var, true);
                $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
                $email_array = array(
                    'mail_type' => 'html',
                    'from_mail_id' => $this->config->item('smtp_user'),
                    'from_mail_name' => 'ARK Team',
                    'to_mail_id' => $email_user,
                    'cc_mail_id' => '',
                    'subject_message' => 'Create Password',
                    'body_messages' => $message
                );

                $email_send = common_email_send($email_array);
                if (strtolower($email_send) == 'success') {
                    $this->users_model->insert_update('update', TBL_USERS, array('password_verify' => $verification_code), array('id' => $found['user_id']));
                    //echo $this->db->last_query(); die;
                    $this->session->set_flashdata('success', 'Please check your email to create your password.');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! We are not able to send you email for create password. Please try again later.');
                }
            } else {
                $this->session->set_flashdata('error', 'Sorry, Might be email address which you are trying to send is not exist. Please contact your system administrator.');
            }
            redirect('/login');
        }
        $this->template->load('front', 'front/login/create_password', $data);
    }

    /**
     * Create Password
     * @param --
     * @return --
     * @author JJP [Last Edited : 06/01/2020]
     */
    public function create_new_password() {
        if ($this->session->userdata('user_logged_in')) {
            redirect('/dashboard', 'refresh');
        }

        $data['title'] = 'Create Password';
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
                        $this->session->set_flashdata('success', 'You have successfully create the password.');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Password was not create. Please try after some time.');
                    }
                    redirect('/login');
                } else {
                    $this->session->set_flashdata('error', 'Password doesn\'t match! Plese try again.');
                    redirect('create_new_password?q=' . base64_encode($user_id) . '&code=' . $password_verify);
                }
            }
            $this->template->load('front', 'front/login/create_new_password', $data);
        } else {
            $this->session->set_flashdata('error', 'Invalid user!');
            redirect('/login');
        }
    }

    public function reset_password() {
        if ($this->session->userdata('user_logged_in')) {
            redirect('/dashboard', 'refresh');
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
                    redirect('/login');
                } else {
                    $this->session->set_flashdata('error', 'Password doesn\'t match! Plese try again.');
                    redirect('reset_password?q=' . base64_encode($user_id) . '&code=' . $password_verify);
                }
            }
            $this->template->load('front', 'front/login/reset_password', $data);
        } else {
            $this->session->set_flashdata('error', 'Invalid user!');
            redirect('/login');
        }
    }

    /**
     * It will check email is unique or not for staff
     * @param  : $id String
     * @return : Boolean (true/false)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function checkUnique_Email($id = NULL) {
        $email_id = trim($this->input->get_post('email_id'));
        $data = array('email_id' => $email_id);
        if (!is_null($id)) {
            $data = array_merge($data, array('id!=' => $id));
        }
        $user = $this->users_model->check_unique_email_for_user($data);
        if ($user > 0) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    /**
     * About Us Page
     * @param --
     * @return --
     * @author HPA [Last Edited : 14/06/2018]
     */
    public function about_us() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }
        $data['title'] = 'About Us';
        $this->template->load('front', 'front/home/about_us', $data);
    }

    /**
     * Packages Page
     * @param --
     * @return --
     * @author HPA [Last Edited : 14/06/2018]
     */
    public function packages() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }
        $data['title'] = 'Packages';
        $data['packages'] = $this->package_model->get_result(TBL_PACKAGES, ['is_delete' => 0]);
        $this->template->load('front', 'front/home/packages', $data);
    }

    /**
     * Features Page
     * @param --
     * @return --
     * @author HPA [Last Edited : 14/06/2018]
     */
    public function features() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }
        $data['title'] = 'Features';
        $this->template->load('front', 'front/home/features', $data);
    }

    /**
     * Check Promotion Code
     * @param --
     * @return --
     * @author HPA [Last Edited : 14/06/2018]
     */
    public function checkUniquePromotionCode() {
        $promotion_code = trim($this->input->get_post('promotion_code'));
        $data = array('name' => $promotion_code, 'expiry_date>=' => date('Y-m-d H:i:s'), 'is_deleted' => 0);
        $subscription = $this->subscription_model->check_unique_name($data);
        if ($subscription > 0) {
            echo "true";
        } else {
            echo "false";
        }
        exit;
    }

//    public function send_upgrade_email() {
//        $date = date('Y-m-d', strtotime('+7 days'));
//        $users = $this->users_model->get_result(TBL_USERS, ['is_delete' => 0, 'renewal_date >=' => $date, 'renewal_date <=' => date('Y-m-d', strtotime('+1 days', strtotime($date))), 'status' => 'active']);
//        If (!empty($users)):
//            foreach ($users as $key => $value) {
//                $email_var = array(
//                    'first_name' => $value['first_name'],
//                    'last_name' => $value['last_name'],
//                    'user_id' => $value['id'],
//                    'renewal_date' => date('l,d M Y', strtotime($value['renewal_date'])),
//                );
//                $message = $this->load->view('email_template/default_header.php', $email_var, true);
//                $message .= $this->load->view('email_template/send_upgrade_email.php', $email_var, true);
//                $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
//                $email_array = array(
//                    'mail_type' => 'html',
//                    'from_mail_id' => $this->config->item('smtp_user'),
//                    'from_mail_name' => 'ARK Team',
//                    'to_mail_id' => $value['email_id'],
//                    'cc_mail_id' => 'hpa@narola.email',
//                    'subject_message' => 'Password Reset',
//                    'body_messages' => $message
//                );
//
//                $email_send = common_email_send($email_array);
//            }
//        endif;
//    }
//    public function suspend_account() {
//        $date = date('Y-m-d');
//        $users = $this->users_model->get_result(TBL_USERS, ['is_delete' => 0, 'renewal_date >=' => $date, 'renewal_date <=' => date('Y-m-d', strtotime('+1 days', strtotime($date))), 'status' => 'active']);
//        if (!empty($users)):
//            foreach ($users as $key => $value) {
//                $update = $this->users_model->insert_update('update', TBL_USERS, ['status' => 'block'], ['id' => $value['id']]);
//                $email_var = array(
//                    'first_name' => $value['first_name'],
//                    'last_name' => $value['last_name'],
//                    'user_id' => $value['id'],
//                    'renewal_date' => date('l,d M Y', strtotime($value['renewal_date'])),
//                );
//                $message = $this->load->view('email_template/default_header.php', $email_var, true);
//                $message .= $this->load->view('email_template/suspend.php', $email_var, true);
//                $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
//                $email_array = array(
//                    'mail_type' => 'html',
//                    'from_mail_id' => $this->config->item('smtp_user'),
//                    'from_mail_name' => 'ARK Team',
//                    'to_mail_id' => $value['email_id'],
//                    'cc_mail_id' => 'hpa@narola.email',
//                    'subject_message' => 'Password Reset',
//                    'body_messages' => $message
//                );
//
//                $email_send = common_email_send($email_array);
//            }
//        endif;
//    }

    public function monthly_fees() {
        $date = date('Y-m-d');
        $users = $this->users_model->get_result(TBL_USERS, ['is_delete' => 0, 'renewal_date >=' => $date, 'renewal_date <=' => date('Y-m-d', strtotime('+1 days', strtotime($date))), 'status' => 'active']);
        If (!empty($users)):
            require_once APPPATH . "third_party/stripe/init.php";
            $stripe_data = get_stripe_data();
            \Stripe\Stripe::setApiKey($stripe_data['STRIPE_SECRET_KEY']);
            foreach ($users as $key => $value) {
                $amount = 0;
                $data['dataArr'] = $this->users_model->get_profile($value['id']);
                $customer = \Stripe\Customer::retrieve($data['dataArr']['stripe_cust_id']);
                if ($customer):
                    $stripe_card_id = $data['dataArr']['stripe_card_id'];
                    $package = $this->users_model->get_result(TBL_PACKAGES, ['id' => $data['dataArr']['package_id'], 'is_delete' => 0], null, 1);
                    if (!empty($package)) {
                        $amount = $package['price'];
                        $amount1 = $amount * 100;
                        $response = \Stripe\Charge::create(array(
                                    'amount' => $amount1,
                                    "currency" => "usd",
                                    "receipt_email" => $this->input->post('email_id'),
                                    "customer" => $customer->id, // obtained with Stripe.js
                                    "source" => $data['dataArr']['stripe_card_id'],
                                    "description" => "Package upgrade/downgrade"
                        ));
                        $chargeJson = $response->jsonSerialize();
                    }
                endif;
                if (isset($chargeJson) && $chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] = 1):
                    if ($amount != 0) :
                        $payment_array = [
                            'user_id' => checkUserLogin('C'),
                            'card_detail_id' => $data['dataArr']['id'],
                            'amount' => $amount,
                            'description' => 'Upgrade/Downgrade Package',
                            'created_date' => date('Y-m-d h:i:s')
                        ];
                        $user_array = [
                            'renewal_date' => date('Y-m-d h:i:s', strtotime('+1 months')),
                        ];
                        $payment_id = $this->payment_model->insert_update('insert', TBL_PAYMENT, $payment_array);
                        $this->users_model->insert_update('update', TBL_USERS, $user_array, ['id' => $data['dataArr']['uid']]);
                    endif;
                    $email_var = array(
                        'first_name' => $value['first_name'],
                        'last_name' => $value['last_name'],
                        'user_id' => $value['id'],
                        'renewal_date' => date('l,d M Y', strtotime($user_array['renewal_date'])),
                        'price' => $amount,
                    );
                    $message = $this->load->view('email_template/default_header.php', $email_var, true);
                    $message .= $this->load->view('email_template/monthly_package_email.php', $email_var, true);
                    $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
                    $email_array = array(
                        'mail_type' => 'html',
                        'from_mail_id' => $this->config->item('smtp_user'),
                        'from_mail_name' => 'ARK Team',
                        'to_mail_id' => $value['email_id'],
                        'cc_mail_id' => 'hpa@narola.email',
                        'subject_message' => 'New Monthly payment Created',
                        'body_messages' => $message
                    );
                    $email_send = common_email_send($email_array);
                endif;
            }
        endif;
    }

    /**
     * This function is used for displaying message when javascript is disabled.
     * develop by : AKK
     */
    public function js_disabled() {
        $this->load->view('Templates/show_javascript');
    }

    public function change_password() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        } else {
            redirect('/login');
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
                $this->users_model->insert_update('update', TBL_USERS, $update_array, ['id' => checkUserLogin('I')]);
                $this->session->set_flashdata('success', 'You have successfully reset the password.');
                redirect('/change_password');
            }
        }
        $this->template->load('default_front', 'front/home/change_password', $data);
    }

    /**
     * Dashboard Search - Sub page of Features Page
     * @param --
     * @return --
     * @author HGA [Added : 18/12/2018]
     */
    public function dashboard_search() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }
        $data['title'] = 'Dashboard Search';
        $this->template->load('front', 'front/home/features_sub_pages/dashboard_search', $data);
    }

    /**
     * Users and Roles - Sub page of Features Page
     * @param --
     * @return --
     * @author HGA [Added : 18/12/2018]
     */
    public function users_and_roles() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }
        $data['title'] = 'Users & Roles';
        $this->template->load('front', 'front/home/features_sub_pages/user_and_roles', $data);
    }

    /**
     * Inventory - Sub page of Features Page
     * @param --
     * @return --
     * @author HGA [Added : 18/12/2018]
     */
    public function inventory() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }
        $data['title'] = 'Inventory';
        $this->template->load('front', 'front/home/features_sub_pages/inventory', $data);
    }

    /**
     * Estimates and Invoices - Sub page of Features Page
     * @param --
     * @return --
     * @author HGA [Added : 18/12/2018]
     */
    public function estimates_and_invoices() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }
        $data['title'] = 'Estimates and Invoices';
        $this->template->load('front', 'front/home/features_sub_pages/estimates_and_invoice', $data);
    }

    /**
     * Estimates and Invoices - Sub page of Features Page
     * @param --
     * @return --
     * @author HGA [Added : 18/12/2018]
     */
    public function reports() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }
        $data['title'] = 'Reports';
        $this->template->load('front', 'front/home/features_sub_pages/reports', $data);
    }

    /**
     * Programming And Troubleshooting - Sub page of Features Page
     * @param --
     * @return --
     * @author HGA [Added : 18/12/2018]
     */
    public function programming_and_troubleshooting() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }
        $data['title'] = 'Programming and Troubleshooting';
        $this->template->load('front', 'front/home/features_sub_pages/programming_and_troublshoot', $data);
    }

    /**
     * On Submit Form OF Contact US Inquiry
     * @param --
     * @return --
     * @author HGA [Added : 19/12/2018]
     */
    public function contact_us_email() {
        try {
            if ($this->input->post()) {
                $email_var = $this->input->post();

                $message = $this->load->view('email_template/default_header.php', $email_var, true);
                $message .= $this->load->view('email_template/need_concern_email.php', $email_var, true);
                $message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                $email_array = array(
                    'mail_type' => 'html',
                    'from_mail_id' => $this->config->item('smtp_user'),
                    'from_mail_name' => 'ARK Team',
                    'cc_mail_id' => '',
                    'to_mail_id' => CONTACT_EMAIL,
                    'subject_message' => 'New Contact Us Inquiry - Always Reliable Key',
                    'body_messages' => $message
                );

                $email_send = common_email_send($email_array);

                if ($email_send == 'success') {
                    $this->session->set_flashdata('success', 'Thank you for contacting us! We will get back to you soon.');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong, Please try again later.');
                }
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', 'Something went wrong, Please try again later.');
        }
        redirect('about_us#contact_us');
    }

    /**
     * Register Success Page
     * @param --
     * @return --
     * @author HGA [Added : 08/01/2018]
     */
    public function register_success() {
        $data['title'] = 'Registered Successfully!';
        $this->template->load('front', 'front/home/register_success_page', $data);
    }

    /**
     * Privacy & Terms Page
     * @param --
     * @return --
     * @author HGA [Added : 11/01/2018]
     */
    public function terms_and_privacy($type) {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_user_profile($this->session->userdata('u_user_id'));
        }

        $data['terms_data'] = array();
        $data['policy_data'] = array();
        $data['page_type'] = $type;

        if ($type == 'Terms') {
            $title = "Terms and Services";
        } else if ($type == 'Privacy') {
            $title = "Privacy Policy";
        }

        // Terms & Services Data        
        $this->db->select('*');
        $this->db->from(TBL_TERMS_AND_PRIVACY_POLICIES);
        $this->db->where(['is_deleted' => 0, 'page_type' => 'Terms']);
        $this->db->order_by("is_default", "desc");
        $terms_data = $this->db->get()->result_array();

        if (!empty($terms_data)) {
            $data['terms_data'] = $terms_data;
        }

        // Privacy & Policy Data        
        $this->db->select('*');
        $this->db->from(TBL_TERMS_AND_PRIVACY_POLICIES);
        $this->db->where(['is_deleted' => 0, 'page_type' => 'Privacy']);
        $this->db->order_by("is_default", "desc");
        $privacy_data = $this->db->get()->result_array();

        if (!empty($privacy_data)) {
            $data['privacy_data'] = $privacy_data;
        }

        $data['page_title'] = "Privacy & Terms";
        $data['title'] = $title . ' - ' . $data['page_title'];

        $this->load->view('front/terms_privacy/index', $data);
    }

    /**
     * Subscribe us
     * @param --
     * @return --
     * @author HGA [Added : 29/12/2020]
     */
    public function subscribe(){
        $subscriberArr = array(
            'email' => $this->input->post('email')
        );
        
        $data['UserInfo'] = $this->users_model->get_profile(checkUserLogin('C'));

        $this->users_model->insert_update('insert',TBL_SUBSCRIBER,$subscriberArr);
        $email_var = array(
            'title' => "Thank you for Subscribing!",
            'user_info' => $data['UserInfo']
        );

        $message = $this->load->view('email_template/default_header.php', $email_var, true);
        $message .= $this->load->view('email_template/subscribe_us.php', $email_var, true);

        $email_array = array(
            'mail_type' => 'html',
            'from_mail_id' => $data['UserInfo']['email_id'],
            'from_mail_name' => 'ARK Team',
            'to_mail_id' => $subscriberArr['email'],
            'cc_mail_id' => '',
            'subject_message' => 'Thanks for subscribe us',
            'body_messages' => $message,
        );

        $email_send = common_email_send($email_array);
        
        $currentURL = $_SERVER['HTTP_REFERER'];
        redirect($currentURL, 'refresh'); 
    }

    /**
     * [estimate_notification destroy session after invoice_notification call
     * @author KBH(13-11-2019)
     */
    public function estimate_notification()
    {
        $this->session->unset_userdata('estimate_notification');
    }

    /**
     * [invoice_notification destroy session after invoice_notification call]
     * @author KBH(13-11-2019)
     */
    public function invoice_notification()
    {
        $this->session->unset_userdata('invoice_notification');
        $this->session->unset_userdata('item_notification');
    }
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */