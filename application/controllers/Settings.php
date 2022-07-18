<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/users_model', 'admin/inventory_model', 'admin/package_model', 'admin/payment_model', 'admin/subscription_model'));
    }

    public function index() {
        if ((!$this->session->userdata('user_logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('/login', 'refresh');
        }
        $data['vendors'] = $this->inventory_model->get_all_details(TBL_VENDORS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $data['equipments'] = $this->inventory_model->get_tool_details()->result_array();
        $data['dataArr'] = $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();
        $data['fieldArr'] = $this->inventory_model->get_all_details(TBL_USER_SETTINGS_FIELD, array('is_deleted' => 0))->result_array();
        if ($this->input->post()) {
            $updateArr = [
                'business_user_id' => checkUserLogin('C'),
                'vendor_id' => ($this->input->post('vendor_id')) ? implode(',', $this->input->post('vendor_id')) : null,
                'equipment_id' => ($this->input->post('equipment_id')) ? implode(',', $this->input->post('equipment_id')) : null,
                'estimate_field' => ($this->input->post('estimate_id')) ? implode(',', $this->input->post('estimate_id')) : null,
                'invoice_field' => ($this->input->post('invoice_id')) ? implode(',', $this->input->post('invoice_id')) : null,
                'label_size' => ($this->input->post('label_size')) ? $this->input->post('label_size') : null,
                'estimate_terms_condition' => ($this->input->post('estimate_terms_condition')) ? $this->input->post('estimate_terms_condition') : null,
                'invoice_terms_condition' => ($this->input->post('invoice_terms_condition')) ? $this->input->post('invoice_terms_condition') : null,
                'is_edited' => 1,
            ];
            if(!empty($data['dataArr'])) {
                $updateArr['modified_date'] = date('Y-m-d H:i:s');
                $this->inventory_model->insert_update('update', TBL_USER_SETTINGS, $updateArr, ['id' => $data['dataArr']['id']]);
                $this->session->set_flashdata('success', 'Customization has been updated successfully.');
            } else {
                $updateArr['created_date'] = date('Y-m-d H:i:s');
                $setting_id = $this->inventory_model->insert_update('insert', TBL_USER_SETTINGS, $updateArr);
                if ($setting_id)
                    $this->session->set_flashdata('success', 'Customization has been inserted successfully.');
            }
            redirect(site_url('/settings'));
        }
        $data['title'] = 'Customization';
        $this->template->load('default_front', 'front/settings/index', $data);
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */