<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscriptions extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/subscription_model'));
        controller_validation();
    }

    /**
     * Display Users
     * @param  : ---
     * @return : ---
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function display_subscriptions() {
        $data['title'] = 'Display Subscriptions';
        $this->template->load('default', 'admin/subscriptions/display', $data);
    }

    /**
     * Get data of Users request for listing
     * @param  : ---
     * @return : json
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function get_ajax_data() {
        $final['recordsTotal'] = $this->subscription_model->get_ajax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $staff = $this->subscription_model->get_ajax_data('result');
        $start = $this->input->get('start') + 1;
        foreach ($staff as $key => $val) {
            $staff[$key] = $val;
            $staff[$key]['sr_no'] = $start++;
            $staff[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $staff[$key]['expiry_date'] = date('m-d-Y h:i A', strtotime($val['expiry_date']) + $_COOKIE['currentOffset']);
            $staff[$key]['responsive'] = '';
        }
        $final['data'] = $staff;
        echo json_encode($final);
    }

    /**
     * This function is used to display, add, edit takeout_types
     * @param  : ---
     * @return : ---
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function manage_subscriptions($id = null) {
        $record_id = '';
        $stripe_coupon_array = $is_coupon_saved = [];
        $data['title'] = 'Manage Subscription';

        //Store coupons on stripe.
        $stripe_mode = get_stripe_mode();
        $data['stripe_data'] = get_stripe_data();
        require_once APPPATH . "third_party/stripe/init.php";
        \Stripe\Stripe::setApiKey($data['stripe_data']['STRIPE_SECRET_KEY']);

        if (!is_null($id)):
            $record_id = base64_decode($id);
            $record_exist_condition = array(
                'id' => $record_id,
                'is_deleted' => 0
            );
            $data['dataArr'] = $this->subscription_model->get_all_details(TBL_SUBSCRIPTIONS, $record_exist_condition)->row_array();
            $data['dataArr']['expiry_date'] = date('j F, Y', strtotime($data['dataArr']['expiry_date']));
        endif;

        $this->form_validation->set_rules('txt_name', 'Package', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            if ($this->input->post('txt_coupon_duration') == 'forever') {
                $record_exist_condition = array(
                    'duration' => strtoupper($this->input->post('txt_coupon_duration')),
                    'is_deleted' => 0
                );

                if (is_null($id)):
                    $check_forever_duraction_coupon = $this->subscription_model->get_all_details(TBL_SUBSCRIPTIONS, $record_exist_condition)->result_array();

                    if (count($check_forever_duraction_coupon) > 0) {
                        $this->session->set_flashdata('error', 'There should be only one forever duration coupon.');
                        redirect(site_url('admin/subscriptions'));
                    }
                endif;
            }

            $record_array = array(
                'name' => htmlentities($this->input->post('txt_name')),
                'no_of_months' => $this->input->post('txt_months'),
                'max_redemption' => $this->input->post('txt_redemptions'),
                'expiry_date' => ($this->input->post('expiration_date') != '') ? date('Y-m-d', strtotime($this->input->post('expiration_date'))) : null,
                'description' => $this->input->post('txt_description'),
                'modified_date' => date('Y-m-d H:i:s'),
                'duration' => strtoupper($this->input->post('txt_coupon_duration')),
            );

            try {
                if ($this->input->post('txt_amount') != 0) {
                    if ($this->input->post('is_amount') == 'on') {
                        $record_array['amount_off'] = $this->input->post('txt_amount');
                        $record_array['percent_off'] = 0;
                        $stripe_coupon_array['amount_off'] = $this->input->post('txt_amount') * 100;
                        $stripe_coupon_array['currency'] = SYSTEM_CURRENCY;
                    } else {
                        $record_array['percent_off'] = $this->input->post('txt_amount');
                        $record_array['amount_off'] = 0;
                        $stripe_coupon_array['percent_off'] = $this->input->post('txt_amount');
                    }

                    $stripe_coupon_array['id'] = htmlentities($this->input->post('txt_name'));
                    $stripe_coupon_array['duration'] = $this->input->post('txt_coupon_duration');

                    if ($this->input->post('txt_coupon_duration') == 'repeating') {
                        $stripe_coupon_array['duration_in_months'] = $this->input->post('txt_months');
                    }

                    if ($this->input->post('txt_coupon_duration') != 'forever') {
                        $stripe_coupon_array['max_redemptions'] = $this->input->post('txt_redemptions');
                    }

                    if (!empty($data['dataArr'])) {
                        if ($stripe_mode == 'test') {
                            if ($data['dataArr']['is_on_stripe'] == 0) {
                                $is_coupon_saved = \Stripe\Coupon::create($stripe_coupon_array)->jsonSerialize();
                            }
                        } else if ($stripe_mode == 'live') {
                            if ($data['dataArr']['is_on_stripe_live'] == 0) {
                                $is_coupon_saved = \Stripe\Coupon::create($stripe_coupon_array)->jsonSerialize();
                            }
                        }
                    } else {
                        $is_coupon_saved = \Stripe\Coupon::create($stripe_coupon_array)->jsonSerialize();
                    }

                    if (!empty($is_coupon_saved)) {
                        if ($stripe_mode == 'test') {
                            $record_array['is_on_stripe'] = 1;
                        } else if ($stripe_mode == 'live') {
                            $record_array['is_on_stripe_live'] = 1;
                        }
                    }
                } else {
                    $record_array['amount_off'] = 0;
                    $record_array['percent_off'] = 0;
                }

                $record_id = $this->input->post('txt_subscription_id');
                if ($record_id != '') {
                    if (!empty($data['dataArr'])) {
                        if ($this->subscription_model->insert_update('update', TBL_SUBSCRIPTIONS, $record_array, array('id' => $record_id, 'is_deleted' => 0))) {
                            $this->session->set_flashdata('success', 'Subscriptions has been updated successfully.');
                        } else {
                            $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                        }
                    } else {
                        $this->session->set_flashdata('error', 'No such record found. Please try again..!!');
                    }
                } else {
                    $record_array['created_date'] = date('Y-m-d H:i:s');
                    if ($this->subscription_model->insert_update('insert', TBL_SUBSCRIPTIONS, $record_array)) {
                        $this->session->set_flashdata('success', 'Subscriptions has been added successfully.');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                    }
                }
                redirect('admin/subscriptions');
            } catch (Exception $ex) {
                $this->session->set_flashdata('error', $ex->getMessage());
                redirect('admin/subscriptions');
            }
        }
        $this->template->load('default', 'admin/subscriptions/manage', $data);
    }

    /**
     * It will check username is unique or not for staff
     * @param  : $id String
     * @return : Boolean (true/false)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function checkUniqueName($id = NULL) {
        $name = trim($this->input->get_post('txt_name'));
        $data = array('name' => $name);
        if (!is_null($id)) {
            $data = array_merge($data, array('id!=' => $id));
        }
        $sub = $this->subscription_model->check_unique_name($data);
        if ($sub > 0) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    public function action($action, $id) {
        $record_id = base64_decode($id);
        if ($action == 'delete'):
            $res = [
                'is_deleted' => 1
            ];
            $this->session->set_flashdata('success', 'Subscription has been Deleted successfully.');
        endif;
        $this->users_model->insert_update('update', TBL_SUBSCRIPTIONS, $res, array('id' => $record_id));
        redirect('admin/subscriptions');
    }

    public function view($id = null) {
        if (!is_null($id)) {
            $data['title'] = 'View Subscription';
            $record_id = base64_decode($id);
            $record_exist_condition = array(
                'id' => $record_id,
                'is_deleted' => 0
            );
            $data['dataArr'] = $this->subscription_model->get_all_details(TBL_SUBSCRIPTIONS, $record_exist_condition)->row_array();
            $data['dataArr']['expiry_date'] = date('j F, Y', strtotime($data['dataArr']['expiry_date']));
            $this->template->load('default', 'admin/subscriptions/view', $data);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong!!');
        }
    }

}

/* End of file Users.php */
/* Location: ./application/controllers/Users.php */