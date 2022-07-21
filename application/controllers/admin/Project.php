<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(array('admin/lead_model','admin/categories_model','admin/project_model'));
	}

	/**********************************************
	Manage Project
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
		$data['title'] = 'Project Management';
//		$data['companyArr'] = $this->dashboard_model->get_all_details(TBL_COMPANY, array('is_delete' => 0, 'status' => 'active'),array(array('field' => 'name', 'type' => 'asc')))->result_array();
//		$data['yearArr'] = $this->dashboard_model->get_all_details(TBL_YEAR, array('is_delete' => 0))->result_array();
//		$this->template->load('default', 'login/dashboard', $data);
		$this->template->load('default', 'admin/project/project_display',$data);
	}

	/**
	 * Add Project
	 * @param --
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function add_project() {
//		controller_validation();
		$data['title'] = 'Admin | Add Project';
		$this->form_validation->set_rules('name', 'Categoriesname', 'trim|required');
		if ($this->form_validation->run() == true) {
			$insertArr = array(
				'name' => htmlentities($this->input->post('name')),
			);
			$insert_id = $this->project_model->insert_update('insert', TBL_PROJECT, $insertArr);

			if ($insert_id > 0) {
				$this->session->set_flashdata('success', 'Data has been added successfully.');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong! Please try again.');
			}
			redirect('admin/project/');
		}
		$this->template->load('default', 'admin/project/project_add', $data);
	}

	/**
	 * Get Project data by ajax and displaying in datatable while displaying
	 * @param --
	 * @return Object (Json Format)
	 */
	public function get_project() {
		$final['recordsTotal'] = $this->project_model->get_project('count');
		$final['redraw'] = 1;
		$final['recordsFiltered'] = $final['recordsTotal'];
		$project = $this->project_model->get_project('result')->result_array();
		$start = $this->input->get('start') + 1;
		foreach ($project as $key => $val) {
			$project[$key] = $val;
			$project[$key]['sr_no'] = $start++;
		}
		$final['data'] = $project;
		echo json_encode($final);
	}

	/**
	 * Edit Transponder
	 * @param $id - String
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function edit_project($id = '') {
		controller_validation();
		$record_id = base64_decode($id);
		$dataArr = $this->project_model->get_all_details(TBL_PROJECT, array('id' => $record_id))->row_array();

		$this->form_validation->set_rules('name', 'projectname', 'trim|required');

		if ($this->form_validation->run() == true) {
			$updateArr = array(
				'name' => htmlentities($this->input->post('name')),
			);

			$insert_id = $this->project_model->insert_update('update', TBL_PROJECT, $updateArr, array('id' => $record_id));

			$this->session->set_flashdata('success', 'Data has been updated successfully.');
			redirect('admin/project/');
		}
		$data = array(
			'title' => 'Edit project',
			'dataArr' => $dataArr,
			'record_id' => $record_id
		);
		$this->template->load('default', 'admin/project/project_add', $data);
	}

	/**
	 * Delete Transponder
	 * @param $id - String
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function delete_project($id = '') {
//		controller_validation();
		$record_id = base64_decode($id);
		$this->project_model->insert_update('delete', TBL_PROJECT, '', array('id' => $record_id));
		$this->session->set_flashdata('error', 'Data data has been deleted successfully.');
		redirect('admin/project/');
	}



}
