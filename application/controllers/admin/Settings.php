<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/dashboard_model', 'admin/product_model', 'admin/inventory_model'));
    }

    /**
     * This is default function
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function index() {
        if ((!$this->session->userdata('logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect(base_url(), 'refresh');
        }
        $data['title'] = 'Settings';
        $data['mode'] = get_stripe_mode();
        $data['Arr'] = get_stripe_data();
        
        $this->template->load('default', 'admin/settings/stripe', $data);
        
        if ($this->input->post()) {
            $arr = [];
            $newarr = $this->input->post(null);
            if ($newarr['STRIPE_MODE'] == 'test') {
                $newarr['STRIPE_TEST_PUBLISH_KEY'] = $newarr['STRIPE_PUBLISH_KEY'];
                $newarr['STRIPE_TEST_SECRET_KEY'] = $newarr['STRIPE_SECRET_KEY'];
                $newarr['STRIPE_TEST_PRODUCT_KEY'] = $newarr['STRIPE_PRODUCT_KEY'];

                unset($newarr['STRIPE_PUBLISH_KEY']);
                unset($newarr['STRIPE_SECRET_KEY']);
                unset($newarr['STRIPE_PRODUCT_KEY']);
            }
            foreach ($newarr as $k => $v) {
                $arr[] = [
                    'key_value' => $k,
                    'key_description' => $v,
                ];
            }

            if (!empty($arr)):
                $this->dashboard_model->batch_insert_update('update', TBL_ADMIN_SETTINGS, $arr, 'key_value');
                $this->session->set_flashdata('success', 'Settings details has been updated successfully!');
                redirect(site_url('/admin/settings'));
            endif;
        }
    }

    public function get_stripe_data() {
        if ($this->input->post()) {
            $stripe_mode = $this->input->post('mode');
            if ($stripe_mode == 'test') {
                $where = 'key_value = "STRIPE_TEST_SECRET_KEY" OR key_value= "STRIPE_TEST_PUBLISH_KEY" OR key_value="STRIPE_TEST_PRODUCT_KEY"';
            } else {
                $where = 'key_value = "STRIPE_SECRET_KEY" OR key_value= "STRIPE_PUBLISH_KEY" OR key_value="STRIPE_PRODUCT_KEY"';
            }
            $stripe = $this->dashboard_model->get_stripe_details(TBL_ADMIN_SETTINGS, $where)->result_array();
            $stripe_details = [];
            if (!empty($stripe)):
                foreach ($stripe as $v):
                    $stripe_details[$v['key_value']] = $v['key_description'];
                endforeach;

                if ($stripe_mode == 'test') {
                    $stripe_details['STRIPE_PUBLISH_KEY'] = $stripe_details['STRIPE_TEST_PUBLISH_KEY'];
                    $stripe_details['STRIPE_SECRET_KEY'] = $stripe_details['STRIPE_TEST_SECRET_KEY'];
                    $stripe_details['STRIPE_PRODUCT_KEY'] = $stripe_details['STRIPE_TEST_PRODUCT_KEY'];
                    unset($stripe_details['STRIPE_TEST_PUBLISH_KEY']);
                    unset($stripe_details['STRIPE_TEST_SECRET_KEY']);
                    unset($stripe_details['STRIPE_TEST_PRODUCT_KEY']);
                }
            endif;
            echo json_encode($stripe_details);
            die;
        }
    }

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */