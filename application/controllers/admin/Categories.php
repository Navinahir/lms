<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(array('admin/lead_model','admin/categories_model'));
	}

	/**********************************************
	Manage Categories
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
		$data['title'] = 'Categories Management';
//		$data['companyArr'] = $this->dashboard_model->get_all_details(TBL_COMPANY, array('is_delete' => 0, 'status' => 'active'),array(array('field' => 'name', 'type' => 'asc')))->result_array();
//		$data['yearArr'] = $this->dashboard_model->get_all_details(TBL_YEAR, array('is_delete' => 0))->result_array();
//		$this->template->load('default', 'login/dashboard', $data);
		$this->template->load('default', 'admin/categories/categories_display',$data);
	}

	/**
	 * Add Categories
	 * @param --
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function add_categories() {
//		controller_validation();
		$data['title'] = 'Admin | Add Categories';
		$this->form_validation->set_rules('name', 'Categoriesname', 'trim|required');
		if ($this->form_validation->run() == true) {
			$insertArr = array(
				'name' => htmlentities($this->input->post('name')),
			);
			$insert_id = $this->categories_model->insert_update('insert', TBL_CATEGORIES, $insertArr);

			if ($insert_id > 0) {
				$this->session->set_flashdata('success', 'Data has been added successfully.');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong! Please try again.');
			}
			redirect('admin/categories/');
		}
		$this->template->load('default', 'admin/categories/categories_add', $data);
	}

	/**
	 * Get Categories data by ajax and displaying in datatable while displaying
	 * @param --
	 * @return Object (Json Format)
	 */
	public function get_categories() {
		$final['recordsTotal'] = $this->categories_model->get_categories('count');
		$final['redraw'] = 1;
		$final['recordsFiltered'] = $final['recordsTotal'];
		$categories = $this->categories_model->get_categories('result')->result_array();
		$start = $this->input->get('start') + 1;
		foreach ($categories as $key => $val) {
			$categories[$key] = $val;
			$categories[$key]['sr_no'] = $start++;
		}
		$final['data'] = $categories;
		echo json_encode($final);
	}

	/**
	 * Edit Transponder
	 * @param $id - String
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function edit_categories($id = '') {
		controller_validation();
		$record_id = base64_decode($id);
		$dataArr = $this->categories_model->get_all_details(TBL_CATEGORIES, array('id' => $record_id))->row_array();

		$this->form_validation->set_rules('name', 'Categoriesname', 'trim|required');

		if ($this->form_validation->run() == true) {
			$updateArr = array(
				'name' => htmlentities($this->input->post('name')),
			);

			$insert_id = $this->categories_model->insert_update('update', TBL_CATEGORIES, $updateArr, array('id' => $record_id));

			$this->session->set_flashdata('success', 'Data has been updated successfully.');
			redirect('admin/categories/');
		}
		$data = array(
			'title' => 'Edit Categories',
			'dataArr' => $dataArr,
			'record_id' => $record_id
		);
		$this->template->load('default', 'admin/categories/categories_add', $data);
	}

	/**
	 * Delete Transponder
	 * @param $id - String
	 * @return --
	 * @author PAV [Last Edited : 03/02/2018]
	 */
	public function delete_categories($id = '') {
//		controller_validation();
		$record_id = base64_decode($id);
		$this->categories_model->insert_update('delete', TBL_CATEGORIES, '', array('id' => $record_id));
		$this->session->set_flashdata('error', 'Data data has been deleted successfully.');
		redirect('admin/categories/');
	}



}
