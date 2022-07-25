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
		$data['categories'] = $categories = $this->categories_model->get_all_categories();
		$this->form_validation->set_rules('category', 'Categoriesname', 'trim|required');
		if ($this->form_validation->run() == true) {
			$insertArr = array(
				'category' => htmlentities($this->input->post('category')),
				'system_size' => htmlentities($this->input->post('system_size')),
				'brand' => htmlentities($this->input->post('brand')),
				'warranties' => htmlentities($this->input->post('warranties')),
				'mounting' => htmlentities($this->input->post('mounting')),
				'electric_kit' => htmlentities($this->input->post('electric_kit')),
				'export_limit' => htmlentities($this->input->post('export_limit')),
				'house_type' => htmlentities($this->input->post('house_type')),
				'roof_type' => htmlentities($this->input->post('roof_type')),
				'roof_angle' => htmlentities($this->input->post('roof_angle')),
				'basic_system_cost' => htmlentities($this->input->post('basic_system_cost')),
				'special_discount' => htmlentities($this->input->post('special_discount')),
				'other_price' => htmlentities($this->input->post('other_price')),
				'balance_due' => htmlentities($this->input->post('balance_due')),
				'special_note' => htmlentities($this->input->post('special_note')),
				'project_note' => htmlentities($this->input->post('project_note'))
			);
			$insert_id = $this->project_model->insert_update('insert', TBL_PROJECT, $insertArr);

			if ($insert_id > 0) {
				$this->session->set_flashdata('success', 'Data has been added successfully.');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong! Please try again.');
			}
			redirect('admin/project/');
		}
		$data = array(
			'title' => 'Add Project',
			'categories' => $categories);
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
