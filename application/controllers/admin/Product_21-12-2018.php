<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/inventory_model', 'admin/product_model', 'admin/package_model'));
    }

    /*     * ********************************************
      Manage Transponder
     * ********************************************* */

    /**
     * Disaply Transponder listing
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function display_transponder() {
        $data['title'] = 'Admin | List Of Applicaton Data';
        $columns = array_column($this->db->query("SHOW COLUMNS FROM " . TBL_TRANSPONDER)->result_array(), 'Field');
        $columns_Arr = array_values(array_diff($columns, array('id', 'make_id', 'model_id', 'year_id', 'modified_date', 'created_date', 'status', 'is_delete')));
        $data['columns_Arr'] = $columns_Arr;
        $this->template->load('default', 'admin/product/transponder_display', $data);
    }

    /**
     * Get Transponder data by ajax and displaying in datatable while displaying
     * @param --
     * @return Object (Json Format)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_transponder() {
        $final['recordsTotal'] = $this->product_model->get_transponder('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $transponder = $this->product_model->get_transponder('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($transponder as $key => $val) {
            $transponder[$key] = $val;
            $transponder[$key]['sr_no'] = $start++;
            $transponder[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $transponder[$key]['responsive'] = '';
        }
        $final['data'] = $transponder;
        echo json_encode($final);
    }

    /**
     * Add Transponder
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function add_transponder() {
        controller_validation();
        $data['title'] = 'Admin | Add Application Data';
        $data['companyArr'] = $companyArr = $this->product_model->get_all_details(TBL_COMPANY, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
        $data['yearArr'] = $yearArr = $this->product_model->get_all_details(TBL_YEAR, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
        $data['itemArr'] = $itemArr = $this->inventory_model->get_item_details()->result_array();
        $data['toolArr'] = $toolArr = $this->inventory_model->get_tool_details()->result_array();
        $this->form_validation->set_rules('txt_make_name', 'Make', 'trim|required|callback_dup_data');
        $this->form_validation->set_rules('txt_model_name', 'Model', 'trim|required');
        $this->form_validation->set_rules('txt_year_name', 'Year', 'trim|required');
        if ($this->form_validation->run() == true) {
            if ($this->input->post('txt_key_value') == '') {
                $key_value = '';
            } else {
                $key_value = htmlentities(implode(',', $this->input->post('txt_key_value')));
            }
            $insertArr = array(
                'make_id' => htmlentities($this->input->post('txt_make_name')),
                'model_id' => htmlentities($this->input->post('txt_model_name')),
                'year_id' => htmlentities($this->input->post('txt_year_name')),
                'transponder_equipped' => (in_array($this->input->post('txt_transponder_equipped'), array('yes', 'no', 'required'))) ? htmlentities($this->input->post('txt_transponder_equipped')) : 'optional',
                'key_value' => $key_value,
                'mvp_system' => htmlentities($this->input->post('txt_mvp_system')),
//                'dongle' => htmlentities($this->input->post('txt_dongle')),
                'pincode_required' => (in_array($this->input->post('txt_pincode_required'), array('yes', 'no', 'required'))) ? htmlentities($this->input->post('txt_pincode_required')) : 'optional',
                'pincode_reading_available' => htmlentities($this->input->post('txt_pincode_avail')),
                'key_onboard_progaming' => htmlentities($this->input->post('txt_key_onboard_prog')),
                'remote_onboard_progaming' => htmlentities($this->input->post('txt_remote_onboard_prog')),
                'test_key' => htmlentities($this->input->post('txt_test_key')),
                'iico' => htmlentities($this->input->post('txt_iico')),
                'jet' => htmlentities($this->input->post('txt_jet')),
                'jma' => htmlentities($this->input->post('txt_jma')),
                'keyline' => htmlentities($this->input->post('txt_keyline')),
                'strattec_non_remote_key' => htmlentities($this->input->post('txt_str_non_rem_key')),
                'strattec_remote_key' => htmlentities($this->input->post('txt_str_rem_key')),
                'oem_non_remote_key' => htmlentities($this->input->post('txt_oem_non_rem_key')),
                'oem_remote_key' => htmlentities($this->input->post('txt_oem_rem_key')),
                'other_non_remote_key' => htmlentities($this->input->post('txt_other_non_rem_key')),
//                'other' => htmlentities($this->input->post('txt_other')),
                'fcc_id' => htmlentities($this->input->post('txt_fcc_id')),
                'code_series' => htmlentities($this->input->post('txt_code_series')),
                'frequency' => htmlentities($this->input->post('txt_frequency')),
                'ic' => htmlentities($this->input->post('txt_ic')),
                'chip_ID' => htmlentities($this->input->post('txt_chip_id')),
                'transponder_re_use' => (in_array($this->input->post('txt_transponder_re_use'), array('yes', 'no', 'required'))) ? htmlentities($this->input->post('txt_transponder_re_use')) : 'optional',
                'max_no_of_keys' => htmlentities($this->input->post('txt_max_no_of_keys')),
                'key_shell' => htmlentities($this->input->post('txt_key_shell')),
//                'cloneable_chip' => htmlentities($this->input->post('txt_cloneable_chip')),
//                'decoders_readers' => htmlentities($this->input->post('txt_decoders_readers')),
//                'tumbler_info' => htmlentities($this->input->post('txt_tumbler_info')),
                'notes' => htmlentities($this->input->post('txt_notes')),
                'tools' => implode(',', $this->input->post('txt_tools')),
                'tool_details' => implode(':-:', $this->input->post('tool_details'))
            );
            $insert_id = $this->product_model->insert_update('insert', TBL_TRANSPONDER, $insertArr);

            // Edit Part's Items
            $txt_items = $this->input->post('txt_items');
            if (!empty($txt_items)) {
                sort($txt_items);
                $updateArr2 = array();
                foreach ($txt_items as $k => $v) {
                    $updateArr2[] = array('transponder_id' => $insert_id, 'items_id' => $v);
                }
                if (!empty($updateArr2)) {
                    $this->product_model->batch_insert_update('insert', TBL_TRANSPONDER_ITEMS, $updateArr2);
                }
            }

            // Edit Additional Fields
            $field_name_Arr = $this->input->post('txt_field_name[]');
            $field_value_Arr = $this->input->post('txt_field_value[]');
            $updateArr3 = array();
            foreach ($field_name_Arr as $k => $v) {
                if ($v != '') {
                    $updateArr3[] = array('transponder_id' => $insert_id, 'field_name' => $v, 'field_value' => $field_value_Arr[$k]);
                }
            }
            if (!empty($updateArr3)) {
                $this->product_model->batch_insert_update('insert', TBL_TRANSPONDER_ADDITIONAL, $updateArr3);
            }

            if ($insert_id > 0) {
                $this->session->set_flashdata('success', 'Data has been added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
            }
            redirect('admin/product/transponder');
        }
        $this->template->load('default', 'admin/product/transponder_add', $data);
    }

    /**
     * Check the data is duplicate or not at the time of ADD
     * @param --
     * @return boolean ( True / False )
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function dup_data() {
        $make_name = htmlentities($this->input->post('txt_make_name'));
        $model_name = htmlentities($this->input->post('txt_model_name'));
        $year_name = htmlentities($this->input->post('txt_year_name'));
        $this->db->select('id');
        $this->db->from(TBL_TRANSPONDER);
        $this->db->where(array(
            'make_id' => $make_name,
            'model_id' => $model_name,
            'year_id' => $year_name
        ));
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $this->form_validation->set_message('dup_data', 'This data already exists in our database');
            return false;
        } else {
            return true;
        }
    }

    /**
     * Edit Transponder
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function edit_transponder($id = '') {
        controller_validation();
        $record_id = base64_decode($id);
        $dataArr = $this->product_model->get_all_details(TBL_TRANSPONDER, array('id' => $record_id))->row_array();
        $additional_Arr = $this->product_model->get_all_details(TBL_TRANSPONDER_ADDITIONAL, array('transponder_id' => $record_id, 'is_delete' => 0), array(array('field' => 'id', 'type' => 'asc')))->result_array();
        $companyArr = $this->product_model->get_all_details(TBL_COMPANY, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
        $modelArr = $this->product_model->get_all_details(TBL_MODEL, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
        $yearArr = $this->product_model->get_all_details(TBL_YEAR, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
        $itemArr = $this->inventory_model->get_item_details()->result_array();
        $trans_items = $this->product_model->get_all_details(TBL_TRANSPONDER_ITEMS, array('transponder_id' => $record_id))->result_array();
        $toolArr = $this->inventory_model->get_tool_details()->result_array();
        if (!empty($trans_items)) {
            $trans_itemsArr = array_column($trans_items, 'items_id');
        } else {
            $trans_itemsArr = array();
        }
        $this->form_validation->set_rules('txt_make_name', 'Make', 'trim|required|callback_dup_data2');
        $this->form_validation->set_rules('txt_model_name', 'Model', 'trim|required');
        $this->form_validation->set_rules('txt_year_name', 'Year', 'trim|required');
        if ($this->form_validation->run() == true) {
            if ($this->input->post('txt_key_value') == '') {
                $key_value = '';
            } else {
                $key_value = htmlentities(implode(',', $this->input->post('txt_key_value')));
            }
            $updateArr = array(
                'make_id' => htmlentities($this->input->post('txt_make_name')),
                'model_id' => htmlentities($this->input->post('txt_model_name')),
                'year_id' => htmlentities($this->input->post('txt_year_name')),
                'transponder_equipped' => (in_array($this->input->post('txt_transponder_equipped'), array('yes', 'no', 'required'))) ? htmlentities($this->input->post('txt_transponder_equipped')) : 'optional',
                'key_value' => $key_value,
                'mvp_system' => htmlentities($this->input->post('txt_mvp_system')),
//                'dongle' => htmlentities($this->input->post('txt_dongle')),
                'pincode_required' => (in_array($this->input->post('txt_pincode_required'), array('yes', 'no', 'required'))) ? htmlentities($this->input->post('txt_pincode_required')) : 'optional',
                'pincode_reading_available' => htmlentities($this->input->post('txt_pincode_avail')),
                'key_onboard_progaming' => htmlentities($this->input->post('txt_key_onboard_prog')),
                'remote_onboard_progaming' => htmlentities($this->input->post('txt_remote_onboard_prog')),
                'test_key' => htmlentities($this->input->post('txt_test_key')),
                'iico' => htmlentities($this->input->post('txt_iico')),
                'jet' => htmlentities($this->input->post('txt_jet')),
                'jma' => htmlentities($this->input->post('txt_jma')),
                'keyline' => htmlentities($this->input->post('txt_keyline')),
                'strattec_non_remote_key' => htmlentities($this->input->post('txt_str_non_rem_key')),
                'strattec_remote_key' => htmlentities($this->input->post('txt_str_rem_key')),
                'oem_non_remote_key' => htmlentities($this->input->post('txt_oem_non_rem_key')),
                'oem_remote_key' => htmlentities($this->input->post('txt_oem_rem_key')),
                'other_non_remote_key' => htmlentities($this->input->post('txt_other_non_rem_key')),
//                'other' => htmlentities($this->input->post('txt_other')),
                'fcc_id' => htmlentities($this->input->post('txt_fcc_id')),
                'code_series' => htmlentities($this->input->post('txt_code_series')),
                'frequency' => htmlentities($this->input->post('txt_frequency')),
                'ic' => htmlentities($this->input->post('txt_ic')),
                'chip_ID' => htmlentities($this->input->post('txt_chip_id')),
                'transponder_re_use' => (in_array($this->input->post('txt_transponder_re_use'), array('yes', 'no', 'required'))) ? htmlentities($this->input->post('txt_transponder_re_use')) : 'optional',
                'max_no_of_keys' => htmlentities($this->input->post('txt_max_no_of_keys')),
                'key_shell' => htmlentities($this->input->post('txt_key_shell')),
//                'cloneable_chip' => htmlentities($this->input->post('txt_cloneable_chip')),
//                'decoders_readers' => htmlentities($this->input->post('txt_decoders_readers')),
//                'tumbler_info' => htmlentities($this->input->post('txt_tumbler_info')),
                'notes' => htmlentities($this->input->post('txt_notes')),
                'tools' => implode(',', $this->input->post('txt_tools')),
                'tool_details' => implode(':-:', $this->input->post('tool_details'))
            );
            $insert_id = $this->product_model->insert_update('update', TBL_TRANSPONDER, $updateArr, array('id' => $record_id));

            // Edit Part's Items
            $txt_items = $this->input->post('txt_items');
            if (!empty($txt_items)) {
                sort($txt_items);
                $diff = array_diff($trans_itemsArr, $txt_items);
                if (!($trans_itemsArr == $txt_items)) {
                    $this->product_model->insert_update('delete', TBL_TRANSPONDER_ITEMS, array(), array('transponder_id' => $record_id));
                }
                $updateArr2 = array();
                $this->product_model->insert_update('delete', TBL_TRANSPONDER_ITEMS, array(), array('transponder_id' => $record_id));
                foreach ($txt_items as $k => $v) {
                    $updateArr2[] = array('transponder_id' => $record_id, 'items_id' => $v);
                }
                $this->product_model->batch_insert_update('insert', TBL_TRANSPONDER_ITEMS, $updateArr2);
            } else {
                $this->product_model->insert_update('delete', TBL_TRANSPONDER_ITEMS, array(), array('transponder_id' => $record_id));
            }

            // Edit Additional Fields
            $field_name_Arr = $this->input->post('txt_field_name[]');
            $field_value_Arr = $this->input->post('txt_field_value[]');

            $updateArr3 = array();
            foreach ($field_name_Arr as $k => $v) {
                if ($v != '') {
                    $updateArr3[] = array('transponder_id' => $record_id, 'field_name' => $v, 'field_value' => $field_value_Arr[$k]);
                }
            }
            if (!empty($updateArr3)) {
                $this->product_model->insert_update('delete', TBL_TRANSPONDER_ADDITIONAL, array(), array('transponder_id' => $record_id));
                $this->product_model->batch_insert_update('insert', TBL_TRANSPONDER_ADDITIONAL, $updateArr3);
            }

            $this->session->set_flashdata('success', 'Data has been updated successfully.');
            redirect('admin/product/transponder');
        }
        $data = array(
            'title' => 'Edit Transponder',
            'dataArr' => $dataArr,
            'companyArr' => $companyArr,
            'modelArr' => $modelArr,
            'yearArr' => $yearArr,
            'itemArr' => $itemArr,
            'trans_items' => array_column($trans_items, 'items_id'),
            'additional_Arr' => $additional_Arr,
            'toolArr' => $toolArr
        );
        $this->template->load('default', 'admin/product/transponder_add', $data);
    }

    /**
     * Check the data is duplicate or not at the time of Edit
     * @param --
     * @return boolean ( True / False )
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function dup_data2() {
        $make_name = htmlentities($this->input->post('txt_make_name'));
        $model_name = htmlentities($this->input->post('txt_model_name'));
        $year_name = htmlentities($this->input->post('txt_year_name'));
        $this->db->select('id');
        $this->db->from(TBL_TRANSPONDER);
        $this->db->where(array(
            'make_id' => $make_name,
            'model_id' => $model_name,
            'year_id' => $year_name
        ));
        $q = $this->db->get();
        if ($q->num_rows() > 1) {
            $this->form_validation->set_message('dup_data2', 'This data already exists in our database');
            return false;
        } else {
            return true;
        }
    }

    /**
     * Delete Transponder
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function delete_transponder($id = '') {
        controller_validation();
        $record_id = base64_decode($id);
        $this->product_model->insert_update('update', TBL_TRANSPONDER, array('is_delete' => 1), array('id' => $record_id));
        $this->session->set_flashdata('error', 'Data data has been deleted successfully.');
        redirect('admin/product/transponder');
    }

    /**
     * Get Transponder data by its id
     * @param --
     * @return HTML format
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function view_transponder_ajax() {
        $trans_id = base64_decode($this->input->post('id'));
        $data['viewArr'] = $this->product_model->get_transponder_data_by_id($trans_id);
        return $this->load->view('admin/partial_view/view_transponder', $data);
        die;
    }

    /**
     * Get all the Model by ajax on the basis of make
     * @param --
     * @return Object (Json Format)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function change_make_get_ajax() {
        $make_id = $this->input->post('id');
        $modelArr = $this->product_model->get_all_details(TBL_MODEL, array('make_id' => $make_id, 'status' => 'active', 'is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $option = "<option></option>";
        foreach ($modelArr as $k => $v) {
            $option .= "<option value='" . $v['id'] . "'>" . $v['name'] . "</option>";
        }
        echo json_encode($option);
        die;
    }

    /**
     * Used to do edit transponder's data in bulk
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function bulk_edit_transponder() {
        $column_name = $this->input->post('txt_new_column');
        $column_value = $this->input->post('txt_new_value');
        $app_id = explode(',', $this->input->post('txt_app_id'));
        $updateArr = [];
        foreach ($app_id as $k => $v) {
            $updateArr[$k]['id'] = $v;
            foreach ($column_name as $k1 => $v1) {
                $updateArr[$k][$v1] = $column_value[$k1];
            }
        }
        $this->product_model->batch_insert_update('update', TBL_TRANSPONDER, $updateArr, 'id', array());
        $this->session->set_flashdata('success', 'Data has been updated successfully.');
        redirect('admin/product/transponder');
    }

    /*     * ********************************************
      Manage Make
     * ********************************************* */

    /**
     * This function is used to display, add, edit takeout_types
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function manage_make() {
        $data['title'] = 'Manage Company';
        $this->form_validation->set_rules('txt_make_name', 'Compnay', 'trim|required|max_length[150]');
        if ($this->form_validation->run() == TRUE) {
            $make = $this->input->post('txt_make_name');
            $record_id = $this->input->post('txt_make_id');
            $record_array = array(
                'name' => htmlentities($make),
                'modified_date' => date('Y-m-d H:i:s')
            );
            if ($record_id != '') {
                $record_exist_condition = array(
                    'id' => $record_id,
                    'is_delete' => 0
                );
                $is_record_exist = $this->product_model->get_all_details(TBL_COMPANY, $record_exist_condition)->result_array();
                if (count($is_record_exist)) {
                    if ($this->product_model->insert_update('update', TBL_COMPANY, $record_array, array('id' => $record_id, 'is_delete' => 0))) {
                        $this->session->set_flashdata('success', 'Company has been updated successfully.');
                        redirect('admin/product/make');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                        redirect('admin/product/make');
                    }
                } else {
                    $this->session->set_flashdata('error', 'No such record found. Please try again..!!');
                    redirect('admin/product/make');
                }
            } else {
                $record_array['created_date'] = date('Y-m-d H:i:s');
                if ($this->product_model->insert_update('insert', TBL_COMPANY, $record_array)) {
                    $this->session->set_flashdata('success', 'Company has been added successfully.');
                    redirect('admin/product/make');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                    redirect('admin/product/make');
                }
            }
        }
        $this->template->load('default', 'admin/product/make', $data);
    }

    /**
     * Used to do get data and displaying it in ajax datatable
     * @param --
     * @return Json data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_make() {
        $final['recordsTotal'] = $this->product_model->get_make('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $make = $this->product_model->get_make('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($make as $key => $val) {
            $make[$key] = $val;
            $make[$key]['sr_no'] = $start++;
            $make[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
        }
        $final['data'] = $make;
        echo json_encode($final);
        die;
    }

    /**
     * This function is used to GET PAYOUT REASONS via ajax
     * @param --
     * @return Json Data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_make_by_id() {
        $record_id = base64_decode($this->input->post('id'));
        $condition = array(
            'id' => $record_id,
            'is_delete' => 0
        );
        $dataArr = $this->product_model->get_all_details(TBL_COMPANY, $condition)->row_array();
        echo json_encode($dataArr);
    }

    /**
     * Delete Make data by its id
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function delete_make($id = '') {
        $record_id = base64_decode($id);
        $this->product_model->insert_update('update', TBL_COMPANY, array('is_delete' => 1), array('id' => $record_id));
        $this->session->set_flashdata('error', 'Company has been deleted successfully.');
        redirect('admin/product/make');
    }

    /**
     * This function is used to Check Takeout Types NAME for unique.
     * @param  : $id String
     * @return : Boolean Value - True / False
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function checkUnique_Make_Name($id = NULL) {
        $make = trim($this->input->get('txt_make_name'));
        $condition = 'name="' . $make . '"';
        if ($id != '') {
            $condition .= " AND id!=" . $id;
        }
        $result = $this->product_model->check_unique_name(TBL_COMPANY, $condition);
        if ($result) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    /**
     * Used to do add make's data in bulk
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function make_bulk_add() {
        $file = $this->input->post('upload_csv');
        $fileDirectory = MAKE_CSV;
        if (!is_dir($fileDirectory)) {
            mkdir($fileDirectory, 0777);
        }
        $saved_file_name = time();
        $config['overwrite'] = FALSE;
        $config['remove_spaces'] = TRUE;
        $config['upload_path'] = $fileDirectory;
        $config['allowed_types'] = 'csv';
        $config['file_name'] = $saved_file_name;
        $this->upload->initialize($config);
        if ($this->upload->do_upload('upload_csv')) {
            $fileDetails = $this->upload->data();
            $row = 1;
            $handle = fopen($fileDirectory . "/" . $fileDetails['file_name'], "r");
            if (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                $csv_format = array('company_name');
                if ($data == $csv_format) {
                    fclose($handle);
                    $handle = fopen($fileDirectory . "/" . $fileDetails['file_name'], "r");
                    $insertArr = array();
                    while (($csv_data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                        if ($row == 1) {
                            $row++;
                            continue;
                        }

                        $company_name = ucwords($csv_data[0]);
                        if ($company_name == '') {
                            fclose($handle);
                            $this->session->set_flashdata('error', 'Some required fields are missing.');
                            redirect('admin/product/make');
                        } else {
                            $nameArr = array_column($insertArr, 'name');
                            if (!in_array($company_name, $nameArr)) {
                                $insertArr[] = array(
                                    'name' => $company_name,
                                    'status' => 'active',
                                    'created_date' => date('Y-m-d h:i:s')
                                );
                            }
                        }
                    }
                    $this->db->insert_batch(TBL_COMPANY, $insertArr);
                } else {
                    fclose($handle);
                    $this->session->set_flashdata('error', 'The columns in this csv file does not match to the database');
                }
                redirect('admin/product/make');
            }
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('admin/product/make');
        }
    }

    /**
     * Used to do add make data by ajax
     * @param --
     * @return Json Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function add_make_data_ajax() {
        $make_name = $this->input->post('txt_modal_make_name');
        $insertArr = array(
            'name' => $make_name
        );
        $insert_id = $this->product_model->insert_update('insert', TBL_COMPANY, $insertArr);
        if ($insert_id > 0) {
            $return = array('status' => 'success', 'id' => $insert_id, 'name' => htmlentities($this->input->post('txt_modal_make_name')));
        }
        echo json_encode($return);
        exit;
    }

    /*     * ********************************************
      Manage Model
     * ********************************************* */

    /**
     * This function is used to display, add, edit takeout_types
     * @param  : ---
     * @return : ---
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function manage_model() {
        $data['title'] = 'Manage Company';
        $data['companyArr'] = $companyArr = $this->product_model->get_all_details(TBL_COMPANY, array('status' => 'active', 'is_delete' => 0))->result();
        $this->form_validation->set_rules('txt_make_name', 'Compnay', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('txt_model_name', 'Model', 'trim|required|max_length[150]');
        if ($this->form_validation->run() == TRUE) {
            $make_id = $this->input->post('txt_make_name');
            $model_name = $this->input->post('txt_model_name');
            $record_id = $this->input->post('txt_model_id');
            $record_array = array(
                'make_id' => htmlentities($make_id),
                'name' => htmlentities($model_name),
                'modified_date' => date('Y-m-d H:i:s')
            );
            if ($record_id != '') {
                $record_exist_condition = array(
                    'id' => $record_id,
                    'is_delete' => 0
                );
                $is_record_exist = $this->product_model->get_all_details(TBL_MODEL, $record_exist_condition)->result_array();
                if (count($is_record_exist)) {
                    if ($this->product_model->insert_update('update', TBL_MODEL, $record_array, array('id' => $record_id, 'is_delete' => 0))) {
                        $this->session->set_flashdata('success', 'Model has been updated successfully.');
                        redirect('admin/product/model');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                        redirect('admin/product/model');
                    }
                } else {
                    $this->session->set_flashdata('error', 'No such record found. Please try again..!!');
                    redirect('admin/product/model');
                }
            } else {
                $record_array['created_date'] = date('Y-m-d H:i:s');
                if ($this->product_model->insert_update('insert', TBL_MODEL, $record_array)) {
                    $this->session->set_flashdata('success', 'Model has been added successfully.');
                    redirect('admin/product/model');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                    redirect('admin/product/model');
                }
            }
        }
        $this->template->load('default', 'admin/product/model', $data);
    }

    /**
     * Used to do get model and displaying it in ajax datatable
     * @param --
     * @return Json data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_model() {
        $final['recordsTotal'] = $this->product_model->get_model('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $model = $this->product_model->get_model('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($model as $key => $val) {
            $model[$key] = $val;
            $model[$key]['sr_no'] = $start++;
            $model[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $model[$key]['responsive'] = '';
        }
        $final['data'] = $model;
        echo json_encode($final);
    }

    /**
     * This function is used to GET PAYOUT REASONS via ajax
     * @param  : ---
     * @return : json data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_model_by_id() {
        $record_id = base64_decode($this->input->post('id'));
        $dataArr = $this->product_model->get_model_by_id($record_id)->row_array();
        echo json_encode($dataArr);
    }

    /**
     * Delete Model data by its id
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function delete_model($id = '') {
        $record_id = base64_decode($id);
        $this->product_model->insert_update('update', TBL_MODEL, array('is_delete' => 1), array('id' => $record_id));
        $this->session->set_flashdata('error', 'Model has been deleted successfully.');
        redirect('admin/product/model');
    }

    /**
     * This function is used to Check Takeout Types NAME for unique.
     * @param  : $id String
     * @return : Boolean Value - True / False
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function checkUnique_Model_Name($id = NULL) {
        $make = trim($this->input->get('txt_make_name'));
        $condition = 'name="' . $make . '"';
        if ($id != '') {
            $condition .= " AND id!=" . $id;
        }
        $result = $this->product_model->check_unique_name(TBL_MODEL, $condition);
        if ($result) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    /**
     * Used to do add model's data in bulk
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function model_bulk_add() {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $company_res = $this->product_model->get_all_details(TBL_COMPANY, array('status' => 'active', 'is_delete' => 0))->result();
        foreach ($company_res as $k => $v) {
            $companyArr[$v->id] = $v->name;
        }
        $file = $this->input->post('upload_csv');
        $fileDirectory = MODEL_CSV;
        if (!is_dir($fileDirectory)) {
            mkdir($fileDirectory, 0777);
        }
        $saved_file_name = time();
        $config['overwrite'] = FALSE;
        $config['remove_spaces'] = TRUE;
        $config['upload_path'] = $fileDirectory;
        $config['allowed_types'] = 'csv';
        $config['file_name'] = $saved_file_name;
        $this->upload->initialize($config);
        if ($this->upload->do_upload('upload_csv')) {
            $fileDetails = $this->upload->data();
            $row = 1;
            $handle = fopen($fileDirectory . "/" . $fileDetails['file_name'], "r");
            if (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                $csv_format = array('model_name', 'company_name');
                if ($data == $csv_format) {
                    fclose($handle);
                    $handle = fopen($fileDirectory . "/" . $fileDetails['file_name'], "r");
                    $insertArr = array();
                    while (($csv_data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                        if ($row == 1) {
                            $row++;
                            continue;
                        }

                        $model_name = ucwords($csv_data[0]);
                        $company_name = ucwords($csv_data[1]);
                        if ($model_name == '') {
                            fclose($handle);
                            $this->session->set_flashdata('error', 'Some required fields are missing.');
                            redirect('admin/product/model');
                        } else {
                            $nameArr = array_column($insertArr, 'name');
                            if (in_array($company_name, $companyArr)) {
                                if (false !== $make_id = array_search($company_name, $companyArr)) {
                                    $insertArr[] = array(
                                        'name' => $model_name,
                                        'make_id' => $make_id,
                                        'status' => 'active',
                                        'created_date' => date('Y-m-d h:i:s')
                                    );
                                }
                                // if(false !== $make_id = array_search($company_name, $companyArr)){
                                // 	$insertArr[] = array(
                                // 		'name' 	=> $model_name,
                                // 		'make_id' => $make_id,
                                // 		'status'=> 'active',
                                // 		'created_date' => date('Y-m-d h:i:s')
                                // 	);
                                // }
                            }
                        }
                    }
                    if (!empty($insertArr)) {
                        $this->db->insert_batch(TBL_MODEL, $insertArr);
                    } else {
                        $this->session->set_flashdata('error', 'Company details dosen\'t exists in our data. Please add company first.');
                    }
                    redirect('admin/product/model');
                } else {
                    fclose($handle);
                    $this->session->set_flashdata('error', 'The columns in this csv file does not match to the database');
                }
                redirect('admin/product/model');
            }
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('admin/product/model');
        }
    }

    /**
     * Used to do add model data by ajax
     * @param --
     * @return Json Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function add_model_data_ajax() {
        $model_name = $this->input->post('txt_modal_model_name');
        $make_name = $this->input->post('txt_modal_make_name');
        $insertArr = array(
            'name' => $model_name,
            'make_id' => $make_name
        );
        $insert_id = $this->product_model->insert_update('insert', TBL_MODEL, $insertArr);
        if ($insert_id > 0) {
            $return = array('status' => 'success', 'id' => $insert_id, 'name' => htmlentities($this->input->post('txt_modal_model_name')));
        }
        echo json_encode($return);
        exit;
    }

    /*     * ********************************************
      Manage Year
     * ********************************************* */

    /**
     * This function is used to display, add, edit takeout_types
     * @param  : ---
     * @return : ---
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function manage_year() {
        $data['title'] = 'Manage Year';
        $this->form_validation->set_rules('txt_year_name', 'Year', 'trim|required|numeric|max_length[4]');
        if ($this->form_validation->run() == TRUE) {
            $year = $this->input->post('txt_year_name');
            $record_id = $this->input->post('txt_year_id');
            $record_array = array(
                'name' => htmlentities($year),
                'modified_date' => date('Y-m-d H:i:s')
            );
            if ($record_id != '') {
                $record_exist_condition = array(
                    'id' => $record_id,
                    'is_delete' => 0
                );
                $is_record_exist = $this->product_model->get_all_details(TBL_YEAR, $record_exist_condition)->result_array();
                if (count($is_record_exist)) {
                    if ($this->product_model->insert_update('update', TBL_YEAR, $record_array, array('id' => $record_id, 'is_delete' => 0))) {
                        $this->session->set_flashdata('success', 'Year has been updated successfully.');
                        redirect('admin/product/year');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                        redirect('admin/product/year');
                    }
                } else {
                    $this->session->set_flashdata('error', 'No such record found. Please try again..!!');
                    redirect('admin/product/year');
                }
            } else {
                $record_array['created_date'] = date('Y-m-d H:i:s');
                if ($this->product_model->insert_update('insert', TBL_YEAR, $record_array)) {
                    $this->session->set_flashdata('success', 'Year has been added successfully.');
                    redirect('admin/product/year');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                    redirect('admin/product/year');
                }
            }
        }
        $this->template->load('default', 'admin/product/year', $data);
    }

    /**
     * Used to do get year and displaying it in ajax datatable
     * @param --
     * @return Json data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_year() {
        $final['recordsTotal'] = $this->product_model->get_year('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $year = $this->product_model->get_year('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($year as $key => $val) {
            $year[$key] = $val;
            $year[$key]['sr_no'] = $start++;
            $year[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $year[$key]['responsive'] = '';
        }
        $final['data'] = $year;
        echo json_encode($final);
    }

    /**
     * This function is used to GET PAYOUT REASONS via ajax
     * @param  : ---
     * @return : json data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_year_by_id() {
        $record_id = base64_decode($this->input->post('id'));
        $condition = array(
            'id' => $record_id,
            'is_delete' => 0
        );
        $dataArr = $this->product_model->get_all_details(TBL_YEAR, $condition)->row_array();
        echo json_encode($dataArr);
    }

    /**
     * Delete Year data by its id
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function delete_year($id = '') {
        $record_id = base64_decode($id);
        $this->product_model->insert_update('update', TBL_YEAR, array('is_delete' => 1), array('id' => $record_id));
        $this->session->set_flashdata('error', 'Year has been deleted successfully.');
        redirect('admin/product/year');
    }

    /**
     * This function is used to Check Takeout Types NAME for unique.
     * @param  : $id String
     * @return : Boolean Value - True / False
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function checkUnique_Year_Name($id = NULL) {
        $year = trim($this->input->get('txt_year_name'));
        $condition = 'name=' . $year;
        if ($id != '') {
            $condition .= " AND id!=" . $id;
        }
        $result = $this->product_model->check_unique_name(TBL_YEAR, $condition);
        if ($result) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    /*     * ********************************************
      Manage Package
     * ********************************************* */

    /**
     * This function is used to display, add, edit package
     * @param  : ---
     * @return : ---
     * @author HPA [Last Edited : 02/07/2018]
     */
    public function manage_package() {
        $record_id = '';
        $data['title'] = 'Manage Package';
        $this->form_validation->set_rules('txt_name', 'Package', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $record_array = array(
                'name' => htmlentities($this->input->post('txt_name')),
                'price' => $this->input->post('txt_price'),
                'no_of_users' => $this->input->post('txt_users'),
                'no_of_locations' => $this->input->post('txt_locations'),
                'modified_date' => date('Y-m-d H:i:s')
            );
            $record_id = $this->input->post('txt_package_id');
            if ($record_id != '') {
                $record_exist_condition = array(
                    'id' => $record_id,
                    'is_delete' => 0
                );
                $is_record_exist = $this->product_model->get_all_details(TBL_PACKAGES, $record_exist_condition)->result_array();
                if (count($is_record_exist)) {
                    if ($this->package_model->insert_update('update', TBL_PACKAGES, $record_array, array('id' => $record_id, 'is_delete' => 0))) {
                        $this->session->set_flashdata('success', 'Package has been updated successfully.');
                        redirect('admin/package');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                        redirect('admin/package');
                    }
                } else {
                    $this->session->set_flashdata('error', 'No such record found. Please try again..!!');
                    redirect('admin/package');
                }
            } else {
                $record_array['created_date'] = date('Y-m-d H:i:s');
                if ($this->package_model->insert_update('insert', TBL_PACKAGES, $record_array)) {
                    $this->session->set_flashdata('success', 'Package has been added successfully.');
                    redirect('admin/package');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                    redirect('admin/package');
                }
            }
        }
        $this->template->load('default', 'admin/product/package', $data);
    }

    /**
     * Used to do get year and displaying it in ajax datatable
     * @param --
     * @return Json data
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function get_package() {
        $final['recordsTotal'] = $this->package_model->get_package('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $package = $this->package_model->get_package('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($package as $key => $val) {
            $package[$key] = $val;
            $package[$key]['sr_no'] = $start++;
            $package[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $package[$key]['responsive'] = '';
        }
        $final['data'] = $package;
        echo json_encode($final);
    }

    /**
     * This function is used to GET PAYOUT REASONS via ajax
     * @param  : ---
     * @return : json data
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function get_package_by_id() {
        $record_id = base64_decode($this->input->post('id'));
        $condition = array(
            'id' => $record_id,
            'is_delete' => 0
        );
        $dataArr = $this->package_model->get_all_details(TBL_PACKAGES, $condition)->row_array();
        echo json_encode($dataArr);
    }

    /**
     * Delete Year data by its id
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function delete_package($id = '') {
        $record_id = base64_decode($id);
        $this->package_model->insert_update('update', TBL_PACKAGES, array('is_delete' => 1), array('id' => $record_id));
        $this->session->set_flashdata('error', 'Package has been deleted successfully.');
        redirect('admin/package');
    }

    /**
     * This function is used to Check Takeout Types NAME for unique.
     * @param  : $id String
     * @return : Boolean Value - True / False
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function checkUnique_Package_Name($id = NULL) {
        $package = trim($this->input->get('txt_name'));
        $condition = 'name="' . $package . '"';
        if ($id != '') {
            $condition .= " AND id!=" . $id;
        }
        $result = $this->package_model->check_unique_name(TBL_PACKAGES, $condition);
        if ($result) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    /**
     * This function is used to GET Package Deatils By ID
     * @param  : $id String
     * @return : Array data
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function view_package($id = NULL) {
        $record_id = base64_decode($id);
        $data['title'] = 'View Package Detail';
        $condition = array(
            'id' => $record_id,
            'is_delete' => 0
        );
        $data['dataArr'] = $this->package_model->get_all_details(TBL_PACKAGES, $condition)->row_array();
        $this->template->load('default', 'admin/product/package_view', $data);
    }

}

/* End of file Product.php */
/* Location: ./application/controllers/Product.php */