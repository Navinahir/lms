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
		$data['states'] =$states = $this->lead_model->get_all_details(TBL_STATES, array(''))->result_array();
		$data['assign_sale_person'] = $assign_sale_person = $this->lead_model->get_all_details(TBL_USERS, array('user_role' => 2))->result_array();
		$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
		$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		if ($this->form_validation->run() == true) {
			$insertArr = array(
				'source' => htmlentities($this->input->post('source')),
				'assign_user_id' => (htmlentities($this->input->post('assign_user_id')) > 0) ? htmlentities($this->input->post('assign_user_id')) : strtoupper(checkLogin('I')),
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
		$data = array(
			'title' => 'Add Lead',
			'states' => $states,
			'assign_sale_person' => $assign_sale_person
		);

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
//		controller_validation();
		$record_id = base64_decode($id);
		$dataArr = $this->lead_model->get_all_details(TBL_LEAD, array('id' => $record_id))->row_array();
		$data['states'] =$states = $this->lead_model->get_all_details(TBL_STATES, array(''))->result_array();
		$data['assign_sale_person'] = $assign_sale_person = $this->lead_model->get_all_details(TBL_USERS, array('user_role' => 2))->result_array();
		$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
		$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');

		if ($this->form_validation->run() == true) {
			$updateArr = array(
				'source' => htmlentities($this->input->post('source')),
				'assign_user_id' => (htmlentities($this->input->post('assign_user_id')) > 0) ? htmlentities($this->input->post('assign_user_id')) : strtoupper(checkLogin('I')),
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
			'title' => 'Edit Lead',
			'dataArr' => $dataArr,
			'record_id' => $record_id,
			'states' => $states,
			'assign_sale_person' => $assign_sale_person
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
