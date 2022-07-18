<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lead extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/lead_model'));
    }

    /**********************************************
      Manage Lead
    ***********************************************/

	/**
	 * This is default function
	 * @param --
	 * @return --
	 */
	public function index() {
		if ((!$this->session->userdata('logged_in'))) {
			$this->session->set_flashdata('error', 'Please login to continue!');
			redirect(base_url(), 'refresh');
		}
		$data['title'] = 'Lead Management';
//		$data['companyArr'] = $this->dashboard_model->get_all_details(TBL_COMPANY, array('is_delete' => 0, 'status' => 'active'),array(array('field' => 'name', 'type' => 'asc')))->result_array();
//		$data['yearArr'] = $this->dashboard_model->get_all_details(TBL_YEAR, array('is_delete' => 0))->result_array();
//		$this->template->load('default', 'login/dashboard', $data);
		$this->template->load('default', 'admin/lead/lead_display',$data);
	}

	/**
	 * Add Lead
	 * @param --
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function add_lead() {
//		controller_validation();
		$data['title'] = 'Admin | Add Lead';
//		$data['companyArr'] = $companyArr = $this->product_model->get_all_details(TBL_COMPANY, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
//		$data['yearArr'] = $yearArr = $this->product_model->get_all_details(TBL_YEAR, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
//		$data['itemArr'] = $itemArr = $this->inventory_model->get_item_details()->result_array();
//		$data['toolArr'] = $toolArr = $this->inventory_model->get_tool_details()->result_array();
		$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
		$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		if ($this->form_validation->run() == true) {
			$insertArr = array(
				'source' => htmlentities($this->input->post('source')),
				'firstname' => htmlentities($this->input->post('firstname')),
				'lastname' => htmlentities($this->input->post('lastname')),
				'email' => htmlentities($this->input->post('email')),
				'phone_number' => htmlentities($this->input->post('phone_number')),
				'address' => htmlentities($this->input->post('address')),
				'state' => htmlentities($this->input->post('state')),
				'post_code' => htmlentities($this->input->post('post_code')),
				'note' => htmlentities($this->input->post('note')),
			);
			$insert_id = $this->lead_model->insert_update('insert', TBL_LEAD, $insertArr);

			if ($insert_id > 0) {
				$this->session->set_flashdata('success', 'Data has been added successfully.');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong! Please try again.');
			}
			redirect('admin/lead/');
		}
		$this->template->load('default', 'admin/lead/lead_add', $data);
	}

	/**
	 * Get Lead data by ajax and displaying in datatable while displaying
	 * @param --
	 * @return Object (Json Format)
	 */
	public function get_lead() {
		$final['recordsTotal'] = $this->lead_model->get_lead('count');
		$final['redraw'] = 1;
		$final['recordsFiltered'] = $final['recordsTotal'];
		$lead = $this->lead_model->get_lead('result')->result_array();
		$start = $this->input->get('start') + 1;
//		foreach ($transponder as $key => $val) {
//			$transponder[$key] = $val;
//			$transponder[$key]['sr_no'] = $start++;
//			$transponder[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
//			$transponder[$key]['responsive'] = '';
//		}
		$final['data'] = $lead;
		echo json_encode($final);
	}

	/**
	 * Edit Transponder
	 * @param $id - String
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function edit_lead($id = '') {
		controller_validation();
		$record_id = base64_decode($id);
		$dataArr = $this->lead_model->get_all_details(TBL_LEAD, array('id' => $record_id))->row_array();
//		$additional_Arr = $this->product_model->get_all_details(TBL_TRANSPONDER_ADDITIONAL, array('transponder_id' => $record_id, 'is_delete' => 0), array(array('field' => 'id', 'type' => 'asc')))->result_array();
//		$companyArr = $this->product_model->get_all_details(TBL_COMPANY, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
//		$modelArr = $this->product_model->get_all_details(TBL_MODEL, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
//		$yearArr = $this->product_model->get_all_details(TBL_YEAR, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
//		$itemArr = $this->inventory_model->get_item_details()->result_array();
//		$trans_items = $this->product_model->get_all_details(TBL_TRANSPONDER_ITEMS, array('transponder_id' => $record_id))->result_array();
//		$toolArr = $this->inventory_model->get_tool_details()->result_array();
//		if (!empty($trans_items)) {
//			$trans_itemsArr = array_column($trans_items, 'items_id');
//		} else {
//			$trans_itemsArr = array();
//		}

		$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
		$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');

		if ($this->form_validation->run() == true) {
			$updateArr = array(
				'source' => htmlentities($this->input->post('source')),
				'firstname' => htmlentities($this->input->post('firstname')),
				'lastname' => htmlentities($this->input->post('lastname')),
				'email' => htmlentities($this->input->post('email')),
				'phone_number' => htmlentities($this->input->post('phone_number')),
				'address' => htmlentities($this->input->post('address')),
				'state' => htmlentities($this->input->post('state')),
				'post_code' => htmlentities($this->input->post('post_code')),
				'note' => htmlentities($this->input->post('note')),
			);

			$insert_id = $this->lead_model->insert_update('update', TBL_LEAD, $updateArr, array('id' => $record_id));

			$this->session->set_flashdata('success', 'Data has been updated successfully.');
			redirect('admin/lead/');
		}
		$data = array(
			'title' => 'Edit Transponder',
			'dataArr' => $dataArr,
//			'companyArr' => $companyArr,
//			'modelArr' => $modelArr,
//			'yearArr' => $yearArr,
//			'itemArr' => $itemArr,
//			'trans_items' => array_column($trans_items, 'items_id'),
//			'additional_Arr' => $additional_Arr,
//			'toolArr' => $toolArr,
			'record_id' => $record_id
		);
		$this->template->load('default', 'admin/lead/lead_add', $data);
	}

	/**
	 * Delete Transponder
	 * @param $id - String
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function delete_lead($id = '') {
//		controller_validation();
		$record_id = base64_decode($id);
		$this->lead_model->insert_update('delete', TBL_LEAD, '', array('id' => $record_id));
		$this->session->set_flashdata('error', 'Data data has been deleted successfully.');
		redirect('admin/lead/');
	}



}
