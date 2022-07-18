<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Taxes extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/tax_model'));
    }

    /**
     * Display Users
     * @param  : ---
     * @return : ---
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function display_taxes() {
        $data['title'] = 'Display Tax Rates';
        $this->template->load('default_front', 'front/taxes/tax_display', $data);
    }

    /**
     * Get data of Users request for listing
     * @param  : ---
     * @return : json
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function get_taxes_ajax_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->tax_model->get_taxes_ajax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $roles = $this->tax_model->get_taxes_ajax_data('result');
        $start = $this->input->get('start') + 1;
        foreach ($roles as $key => $val) {
            $roles[$key] = $val;
            $roles[$key]['sr_no'] = $start++;
            $roles[$key]['modified_date'] = date($format['format'], strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
        }
        $final['data'] = $roles;
        echo json_encode($final);
    }

    /**
     * Add/Edit Role
     * @param  : $id String
     * @return : ---
     * @author HPA [Last Edited : 08/06/2018]
     */
    public function edit_taxes($id = null) {
        $this->form_validation->set_rules('txt_tax_name', 'Tax Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('txt_rate', 'Rate', 'trim|required');
        if ($this->form_validation->run() == true) {
            $insertArr = array(
                'name' => htmlentities($this->input->post('txt_tax_name')),
                'rate' => htmlentities($this->input->post('txt_rate')),
                'business_user_id' => checkUserLogin('C'),
                'modified_date' => date('Y-m-d H:i:s')
            );
            extract($insertArr);
            if (!is_null($id)) {
                $record_id = base64_decode($id);
                $update_id = $this->tax_model->insert_update('update', TBL_TAXES, $insertArr, ['id' => $record_id]);
                $this->session->set_flashdata('success', '"<b>' . $name . '</b>" has been updated successfully.');
            } else {
                $insertArr['created_date'] = date('Y-m-d H:i:s');
                $insert_id = $this->tax_model->insert_update('insert', TBL_TAXES, $insertArr);
                if ($insert_id > 0) {
                    $this->session->set_flashdata('success', '"<b>' . $name . '</b>" has been added successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                }
            }
            redirect('taxes');
        } else {
            echo validation_errors();
        }
    }

    /**
     * It will check name is unique or not for Role
     * @param  : $id String
     * @return : Boolean (true/false)
     * @author HPA [Last Edited : 08/06/2018]
     */
    public function checkUniqueName($id = null) {
        $tax_name = trim($this->input->get_post('txt_tax_name'));
        $data = array(
            'name' => $tax_name,
            'business_user_id' => checkUserLogin('C')
        );
        if (!is_null($id)) {
            $data = array_merge($data, array('id!=' => $id));
        }
        $user = $this->tax_model->check_unique_tax($data);
        if ($user > 0) {
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
                'is_deleted' => 1,
            ];
            $this->session->set_flashdata('success', 'Tax Rate request has been deleted successfully.');
        endif;
        $this->tax_model->insert_update('update', TBL_TAXES, $res, array('id' => $record_id));
        redirect('taxes');
    }

    /**
     * This function is used to GET PAYOUT REASONS via ajax
     * @param  : ---
     * @return : json data
     * @author HPA [Last Edited : 22/06/2018]
     */
    public function get_tax_by_id() {
        $record_id = base64_decode($this->input->post('id'));
        $condition = array(
            'id' => $record_id,
            'is_deleted' => 0,
            'business_user_id' => checkUserLogin('C')
        );
        $dataArr = $this->tax_model->get_all_details(TBL_TAXES, $condition)->row_array();
        echo json_encode($dataArr);
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */