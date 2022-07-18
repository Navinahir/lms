<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Print array/string.
 * @param array $data - data which is going to be printed
 * @param boolean $is_die - if set to true then excecution will stop after print. 
 */
function pr($data, $is_die = false) {
    if (is_array($data)) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
    else if(is_object($data))
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
    else {
        echo $data;
    }

    if ($is_die)
        die;
}

/**
 * Print last executed query
 * @param boolean $bool - if set to true then excecution will stop after print
 */
function qry($bool = false) {
    $CI = & get_instance();
    echo $CI->db->last_query();
    if ($bool)
        die;
}

/**
 * Uploads image
 * @param string $image_name
 * @param string $image_path
 * @return array - Either name of the image if uploaded successfully or Array of errors if image is not uploaded successfully
 */
function upload_image($image_name, $image_path) {
    $CI = & get_instance();
    $extension = explode('/', $_FILES[$image_name]['type']);
    $randname = time() . '.' . end($extension);
    $config = array(
        'upload_path' => $image_path,
        'allowed_types' => "png|jpg|jpeg|gif",
        'max_size' => "10240",
        'file_name' => $randname
    );
    //--Load the upload library
    $CI->load->library('upload');
    $CI->upload->initialize($config);
    if ($CI->upload->do_upload($image_name)) {
        $img_data = $CI->upload->data();
        $imgname = $img_data['file_name'];
    } else {
        $imgname = array('errors' => $CI->upload->display_errors());
    }
    return $imgname;
}

/**
 * Set up configuration array for pagination
 * @return array - Configuration array for pagination
 */
function front_pagination() {
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['first_link'] = 'First';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li style="display:none"></li><li class="active"><a data-type="checked" style="background-color:#62a0b4;color:#ffffff; pointer-events: none;">';
    $config['cur_tag_close'] = '</a></li><li style="display:none"></li>';
    $config['prev_link'] = '&laquo;';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '&raquo;';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['last_link'] = 'Last';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    return $config;
}

/**
 * Returns all the categories
 */
function get_all_cats() {
    echo "here";
    exit;
    $CI = & get_instance();
    p(1, 1);
    $CI->load->model('categories_model');
    $data = $this->categories_model->get_all_active_cats();
    return $data;
}

/**
 * Return verfication code with check already exit or not for business user signup
 */
function verification_code() {
    $CI = & get_instance();
    $CI->load->model('users_model');
    for ($i = 0; $i < 1; $i++) {
        $verification_string = 'abcdefghijk123' . time();
        $verification_code = str_shuffle($verification_string);
        $check_code = $CI->users_model->get_all_details(TBL_USERS, array('password_verify' => $verification_code))->num_rows();
        if ($check_code > 0) {
            $i--;
        } else {
            return $verification_code;
        }
    }
}

/**
 * Returns file size in GB/MB or KB
 * @author KU
 * @param int $bytes
 * @return string
 */
function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}

/**
 * Send Email
 * @param array $email_values
 * @return string success
 */
function common_email_send($email_values = array()) {
    $CI = & get_instance();
    $CI->load->library('email');
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.gmail.com';
    $config['smtp_port'] = '465';
    $config['smtp_user'] = 'admin@reliablekeys.com';
    $config['smtp_pass'] = 'ARKse668520!';
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html';
    $config['validation'] = TRUE;
    $CI->email->initialize($config);
    $type = $email_values ['mail_type'];
    $subject = $email_values ['subject_message'];
    $to = $email_values ['to_mail_id'];
    $from = $email_values ['from_mail_id'];
    $from_name = $email_values ['from_mail_name'];
    $CI->email->subject($subject);
    $CI->email->from($from, $from_name);
    $CI->email->to($to);
    if ($email_values['cc_mail_id'] != '') {
        $CI->email->cc($email_values['cc_mail_id']);
    }
    if (isset($email_values['bcc_mail_id']) && $email_values['bcc_mail_id'] != '') {
        $CI->email->bcc($email_values['bcc_mail_id']);
    }
    $CI->email->message(stripslashes($email_values ['body_messages']));

    if (isset($email_values['attachment'])) {
        $CI->email->attach($email_values['attachment']);
    }
    if (!$CI->email->send()) {
        echo $CI->email->print_debugger();
    }
    return 'success';
}

/**
 * This function is used to get all fired quiries
 * @author PAV
 * @param - $is_die - boolean(true/false)
 * @return --
 */
function get_all_queries($is_die = false) {
    $CI = & get_instance();
    echo "<pre>";
    print_r($CI->db->queries);
    echo "</pre>";
    if ($is_die)
        die;
}

/**
 * Generate Random Password
 * @author PAV
 * @param - $pass
 * @return --
 */
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!?~@#-_+<>[]{}";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 12; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function controller_validation() {
    $CI = & get_instance();
    if ($CI->session->userdata('user_role') == '2') {
        $CI->session->set_flashdata('error', 'You can\'t access this page.');
        redirect('admin/dashboard');
    } else {
        return true;
    }
}

function checkLogin($type = '') {
    $CI = & get_instance();
    if ($type == 'I') {
        return $CI->session->userdata('user_id');
    } else if ($type == 'U') {
        return $CI->session->userdata('username');
    } else if ($type == 'F') {
        return $CI->session->userdata('full_name');
    } else if ($type == 'E') {
        return $CI->session->userdata('email_id');
    } else if ($type == 'R') {
        return $CI->session->userdata('user_role');
    } else if ($type == 'L') {
        return $CI->session->userdata('logged_in');
    }
}

function checkUserLogin($type = '') {
    $CI = & get_instance();
    $CI->load->model('users_model');

    if ($type == 'I') {
        return $CI->session->userdata('u_user_id');
    } else if ($type == 'U') {
        // return $CI->session->userdata('u_username');
        return $CI->session->userdata('u_first_name').' '.$CI->session->userdata('u_last_name');    
    } else if ($type == 'F') {
        $data['user_data'] = $CI->users_model->get_profile(checkUserLogin('C'));
        return $data['user_data']['full_name'];
    } else if ($type == 'E') {
        return $CI->session->userdata('u_email_id');
    } else if ($type == 'R') {
        return $CI->session->userdata('u_user_role');
    } else if ($type == 'L') {
        return $CI->session->userdata('user_logged_in');
    } else if ($type == 'C') {
        if ($CI->session->userdata('u_user_role') == 4) {
            return $CI->session->userdata('u_user_id');
        } else {
            return $CI->session->userdata('u_business_id');
        }
    } else if ($type == 'P') {
        $data['user_data'] = $CI->users_model->get_profile(checkUserLogin('C'));
        return $data['user_data']['profile_pic'];
    } else if ($type == 'A') {
        $data['user_data'] = $CI->users_model->get_profile(checkUserLogin('C'));
        return $data['user_data']['avatar_pic'];
    } else if ($type == 'B') {
        $data['user_data'] = $CI->users_model->get_profile(checkUserLogin('C'));
        return $data['user_data']['business_name'];
    } else if ($type == 'D') {
        $data['date_data'] = $CI->users_model->get_date_format(checkUserLogin('C'));
        return $data['date_data'];
    } else if ($type == 'CU') {
        $data['currency_data'] = $CI->users_model->get_currency(checkUserLogin('C'));
        return $data['currency_data'];
    } else if ($type == 'CON') {
        $data['user_data'] = $CI->users_model->get_profile(checkUserLogin('C'));
        return $data['user_data']['contact_number'];
    } else if ($type == 'Date') {
        $data['user_data'] = $CI->users_model->get_profile(checkUserLogin('C'));
        return $data['user_data']['package_activated_date'];
    }
}

function checkVendorLogin($type = '') {
    $CI = & get_instance();
    if ($type == 'I') {
        return $CI->session->userdata('v_user_id');
    } else if ($type == 'U') {
        return $CI->session->userdata('v_username');
    } else if ($type == 'F') {
        return $CI->session->userdata('v_first_name') . ' ' . $CI->session->userdata('v_last_name');
    } else if ($type == 'E') {
        return $CI->session->userdata('v_email_id');
    } else if ($type == 'R') {
        return $CI->session->userdata('v_user_role');
    } else if ($type == 'L') {
        return $CI->session->userdata('vendor_logged_in');
    } else if ($type == 'P') {
        $data['user_data'] = $CI->users_model->get_profile(checkVendorLogin('I'));
        return $data['user_data']['profile_pic'];
    }
}

function time_elapsed_string($datetime) {
    $timestamp = strtotime($datetime);
    $datetime1 = new DateTime("now");
    $datetime2 = date_create($datetime);
    $diff = date_diff($datetime1, $datetime2);
    $timemsg = '';
    if ($diff->y > 0) {
        $timemsg .= $diff->y . ' year';
    }
    if ($diff->m > 0) {
        $timemsg .= $diff->m . ' month' . ($diff->m > 1 ? "s" : '');
    }
    if ($diff->d > 0) {
        $timemsg .= $diff->d . ' day' . ($diff->d > 1 ? "s" : '');
    }
    if ($diff->h > 0) {
        $timemsg .= $diff->h . ' hour' . ($diff->h > 1 ? "s" : '');
    }
    if ($diff->i > 0) {
        $timemsg .= $diff->i . ' min' . ($diff->i > 1 ? "s" : '');
    }
    if ($diff->s > 0) {
        if ($diff->s < 60 && $diff->i <= 0) {
            $timemsg .= 'just now';
        } else {
            $timemsg .= $diff->s . ' sec' . ($diff->s > 1 ? "'s" : '');
        }
    }
    return $timemsg;
}

function get_stripe_data($type = '') {
    $CI = & get_instance();
    $CI->load->model('admin/dashboard_model');
    $stripe_mode = get_stripe_mode();
    if (!empty($stripe_mode)) {
        if ($stripe_mode == 'test') {
            $where = 'key_value = "STRIPE_TEST_SECRET_KEY" OR key_value= "STRIPE_TEST_PUBLISH_KEY" OR key_value="STRIPE_TEST_PRODUCT_KEY"';
        } else {
            $where = 'key_value = "STRIPE_SECRET_KEY" OR key_value= "STRIPE_PUBLISH_KEY" OR key_value="STRIPE_PRODUCT_KEY"';
        }
    }
    $stripe = $CI->dashboard_model->get_stripe_details(TBL_ADMIN_SETTINGS, $where)->result_array();
    $stripe_details = [];
    if (!empty($stripe)):
        foreach ($stripe as $v):
            $stripe_details[$v['key_value']] = $v['key_description'];
        endforeach;
    endif;
    if ($stripe_mode == 'test') {
        $stripe_details['STRIPE_PUBLISH_KEY'] = $stripe_details['STRIPE_TEST_PUBLISH_KEY'];
        $stripe_details['STRIPE_SECRET_KEY'] = $stripe_details['STRIPE_TEST_SECRET_KEY'];
        $stripe_details['STRIPE_PRODUCT_KEY'] = $stripe_details['STRIPE_TEST_PRODUCT_KEY'];
        unset($stripe_details['STRIPE_TEST_PUBLISH_KEY']);
        unset($stripe_details['STRIPE_TEST_SECRET_KEY']);
        unset($stripe_details['STRIPE_TEST_PRODUCT_KEY']);
    }
    return $stripe_details;
}

function get_stripe_mode() {
    $CI = & get_instance();
    $CI->load->model('admin/dashboard_model');
    $stripe_mode = $CI->dashboard_model->get_stripe_details(TBL_ADMIN_SETTINGS, ['key_value' => 'STRIPE_MODE'])->row_array();
    return $stripe_mode['key_description'];
}

function create_update_stripe_plan($stripe_plan_id = NULL, $plan_data) {
    $plan_details = [];

    require_once APPPATH . "third_party/stripe/init.php";
    $data['stripe_data'] = get_stripe_data();
    \Stripe\Stripe::setApiKey($data['stripe_data']['STRIPE_SECRET_KEY']);

    $product_id = $data['stripe_data']['STRIPE_PRODUCT_KEY'];

    if (!empty($plan_data)) {
        if (!empty($stripe_plan_id)) {
            $update_plan = \Stripe\Plan::retrieve($stripe_plan_id);
            $update_plan->nickname = $plan_data['plan_name'];
            $plan_details = $update_plan->save();
        } else {
            $plan_details = \Stripe\Plan::create([
                        "amount" => $plan_data['price'],
                        "interval" => $plan_data['duration'],
                        "nickname" => $plan_data['plan_name'],
                        "product" => $product_id,
                        "currency" => SYSTEM_CURRENCY,
            ]);
        }
    }

    return $plan_details;
}
