<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends MY_Controller {

    public function __construct() {
    	parent::__construct();
        $this->load->model(array('admin/subscription_model', 'admin/estimate_model'));
    }

    public function index() {
        $endpoint_secret = '';
        $event = null;

        require_once APPPATH . "third_party/stripe/init.php";
        $data['stripe_data'] = get_stripe_data();

        \Stripe\Stripe::setApiKey($data['stripe_data']['STRIPE_SECRET_KEY']);
        // You can find your endpoint's secret in your webhook settings
        $stripe_mode = get_stripe_mode();

        if ($stripe_mode == 'live') {
            $endpoint_secret = "whsec_0E9JfXX73BmsbdXjbRNfNUazpWhmdaq3";
        } else if ($stripe_mode == 'test') {
            $endpoint_secret = "whsec_lXRQAY3dfVUvtQ7cg6xCPdGUacuSt1pn";
        }

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try {
            $event = \Stripe\Webhook::constructEvent(
                            $payload, $sig_header, $endpoint_secret
            );

            //Invoice Payment succeeded and invoice created
            if (isset($event) && $event->type == "invoice.created") {
//            if (isset($event) && $event->type == "invoice.created" || $event->type == "invoice.payment_succeeded") {
                $customer = \Stripe\Customer::retrieve($event->data->object->customer);
                $email = $customer->email;

                $invoice_data['invoice'] = $event->jsonSerialize();
                $invoice_data['UserInfo'] = $this->subscription_model->get_all_details(TBL_USERS, array('email_id' => $email, 'is_delete' => 0))->row_array();

                if (!empty($invoice_data['UserInfo'])) {
                    $message = $this->load->view('email_template/default_header.php', $invoice_data, true);
                    $message .= $this->load->view('email_template/send_billing_invoice.php', $invoice_data, true);

                    $email_array = array(
                        'mail_type' => 'html',
                        'from_mail_id' => $this->config->item('smtp_user'),
                        'from_mail_name' => 'ARK Team',
                        'to_mail_id' => $invoice_data['UserInfo']['email_id'],
                        'cc_mail_id' => '',
                        'subject_message' => 'Billing Invoice - Always Reliable Keys',
                        'body_messages' => $message
                    );

                    common_email_send($email_array);
                }
            }

            //Customer subscription canceled
            if (isset($event) && $event->type == "customer.subscription.deleted") {
                try {
                    $customer = \Stripe\Customer::retrieve($event->data->object->customer);
                    $email = $customer->email;

                    // $user_data = $this->subscription_model->get_all_details(TBL_USERS, array('email_id' => $email, 'is_delete' => 0))->row_array();

                    $user_data = $this->subscription_model->get_user_subscription_details(checkUserLogin('C'));

                    if (!empty($user_data)) {
                        //After canceled subscription remove users from system.
                        $record_array = [
                            'is_delete' => 1
                        ];

                        $this->subscription_model->insert_update('update', TBL_USERS, $record_array, array('id' => $user_data['id']));

                        $email_var = array(
                            'full_name' => $user_data['full_name'],
                            'email_id' => $user_data['email_id'],
                            'contact_number' => $user_data['contact_number'],
                            'package_name' => $user_data['package_name'],
                            'package_price' => $user_data['package_price'],
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
                            'subject_message' => $user_data['full_name'] . ' - Subscription has been canceled.',
                            'body_messages' => $message
                        );

                        common_email_send($email_array);

                        //Email notification for users after canceled subscritpion
                        $user_message = $this->load->view('email_template/default_header.php', $email_var, true);
                        $user_message .= $this->load->view('email_template/webhook_canceled_subscription_notification.php', $email_var, true);
                        $user_message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                        $user_email_array = array(
                            'mail_type' => 'html',
                            'from_mail_id' => $this->config->item('smtp_user'),
                            'from_mail_name' => 'ARK Team',
                            'to_mail_id' => $user_data['email_id'],
                            'cc_mail_id' => 'support@reliablekeys.com',
                            'bcc_mail_id' => '',
                            'subject_message' => 'Your subscription has been canceled - Always Reliable Keys',
                            'body_messages' => $user_message
                        );

                        common_email_send($user_email_array);
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }

            //Invoice Payment Failed
            if (isset($event) && $event->type == "invoice.payment_failed") {
                try {
                    $customer = \Stripe\Customer::retrieve($event->data->object->customer);
                    $email = $customer->email;

                    $user_data = $this->subscription_model->get_all_details(TBL_USERS, array('email_id' => $email, 'is_delete' => 0))->row_array();

                    if (!empty($user_data)) {
                        // Sending your customers the amount in pennies is weird, so convert to dollars
                        $amount = sprintf('$%0.2f', $event->data->object->amount_due / 100.0);

                        $email_var = [
                            'full_name' => $user_data['full_name'],
                            'invoice_amnount' => $amount,
                            'invoice_number' => $event->data->object->number
                        ];

                        //Email notification for users after invoice payment failed.
                        $user_message = $this->load->view('email_template/default_header.php', $email_var, true);
                        $user_message .= $this->load->view('email_template/send_invoice_payment_failed_notification.php', $email_var, true);
                        $user_message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                        $user_email_array = array(
                            'mail_type' => 'html',
                            'from_mail_id' => $this->config->item('smtp_user'),
                            'from_mail_name' => 'ARK Team',
                            'to_mail_id' => $user_data['email_id'],
                            'cc_mail_id' => 'support@reliablekeys.com',
                            'bcc_mail_id' => '',
                            'subject_message' => 'There was a problem with your payment – Always Reliable Keys',
                            'body_messages' => $user_message
                        );

                        common_email_send($user_email_array);

                        // Email notification for admin after user's invoice payemnt failed.
                        $message = $this->load->view('email_template/default_header.php', $email_var, true);
                        $message .= $this->load->view('email_template/send_invoice_payment_failed_notific_admin.php', $email_var, true);
                        $message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                        $email_array = array(
                            'mail_type' => 'html',
                            'from_mail_id' => $this->config->item('smtp_user'),
                            'from_mail_name' => 'ARK Team',
                            'to_mail_id' => 'alwaysreliablekeys@gmail.com',
                            'cc_mail_id' => 'support@reliablekeys.com',
                            'bcc_mail_id' => 'hpa@narola.email',
                            'subject_message' => $user_data['full_name'] . ' - Subscription has been canceled.',
                            'body_messages' => $message
                        );

                        common_email_send($email_array);
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400); // PHP 5.4 or greater
            exit();
        } catch (\Stripe\Error\SignatureVerification $e) {
            // Invalid signature
            http_response_code(400); // PHP 5.4 or greater
            exit();
        }

        // Do something with $event
        http_response_code(200);
    }

    public function generate_qr_code() {
        $this->load->library('ciqrcode');

//        $params['data'] = 'XHNTYGWQ';
//        $params['level'] = 'H';
//        $params['size'] = 6;
//        $params['savename'] = FCPATH . 'assets\qr_codes\tes.png';
//
//        $image = $this->ciqrcode->generate($params);

        $part_no = "NAROLA-2K19";
        $file_name = $part_no . '.png';

        $params['data'] = $part_no;
        $params['level'] = 'H';
        $params['size'] = 6;
        $params['savename'] = FCPATH . 'assets\qr_codes\assets\"' . $file_name;

        echo '<img src="' . base_url('/assets/qr_codes/tes.png') . '" />';
    }

    public function scan() {
        $this->load->view('qr_scan');
    }

    public function generate_vendor_items_qr_code() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        $item_data = $this->subscription_model->get_all_details(TBL_ITEMS, array('item_qr_code' => NULL, 'is_delete' => 0))->result_array();

        if (!empty($item_data)) {
            foreach ($item_data as $item) {
                $part_no = $item['part_no'];
                $item_id = $item['id'];

                $qr_code_image = $this->generat_item_qr_code($part_no);

                $updateArr = [
                    'item_qr_code' => $qr_code_image
                ];

                $is_updated = $this->estimate_model->insert_update('update', TBL_ITEMS, $updateArr, array('id' => $item_id));

                echo '<br><br>';
                echo $part_no . ' Creating....';
                echo '<br>';
                if ($is_updated) {
                    echo $qr_code_image . ' has been created for ' . $item_id;
                    echo '<br><br>';
                } else {
                    echo $qr_code_image . ' has not been created for ' . $item_id; 
                    echo '<br><br>';
                }
            }
        }
    }
    
    public function generate_user_items_qr_code() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        $item_data = $this->subscription_model->get_all_details(TBL_USER_ITEMS, array())->row_array();

        if (!empty($item_data)) {
            foreach ($item_data as $item) {
                $part_no = $item['part_no'];
                $item_id = $item['id'];

                $qr_code_image = $this->generat_user_item_qr_code($part_no);

                $updateArr = [
                    'user_item_qr_code' => $qr_code_image
                ];

                $is_updated = $this->estimate_model->insert_update('update', TBL_USER_ITEMS, $updateArr, array('id' => $item_id));

                echo '<br><br>';
                echo $part_no . ' Creating....';
                echo '<br>';
                if ($is_updated) {
                    echo $qr_code_image . ' has been created for ' . $item_id;
                    echo '<br><br>';
                } else {
                    echo $qr_code_image . ' has not been created for ' . $item_id;
                    echo '<br><br>';
                }
            }
        }
    }

    public function generat_item_qr_code($part_no) {
        $this->load->library('ciqrcode');
        $file_name = $part_no . '.png';

        $params['data'] = $part_no;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH . 'assets/qr_codes/' . $file_name;

        $is_generated = $this->ciqrcode->generate($params);

        if($is_generated){
            return $file_name;
        }else{
            return '';
        }

    }

    public function generat_user_item_qr_code($part_no) {
        $this->load->library('ciqrcode');
        $file_name = $part_no . '.png';

        $params['data'] = $part_no;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH . 'assets/users_qr_codes/' . $file_name;

        $is_generated = $this->ciqrcode->generate($params);

        if($is_generated){
            return $file_name;
        }else{
            return '';
        }

    }

}
