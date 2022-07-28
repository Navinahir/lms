<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Equipments extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/equipment_model'));
        controller_validation();
    }

    /**
     * This function is used to display, add, edit takeout_types
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function manage_manufacturer() {
        $data['title'] = 'Manage Manufacturers';
        $this->form_validation->set_rules('txt_name', 'Name', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('txt_description', 'Description', 'trim|required|max_length[500]');
        if ($this->form_validation->run() == TRUE) {
            $record_id = $this->input->post('txt_manufacturer_id');
            $record_array = array(
                'name' => htmlentities($this->input->post('txt_name')),
                'description' => htmlentities($this->input->post('txt_description')),
                'modified_date' => date('Y-m-d H:i:s')
            );
            if ($record_id != '') {
                $record_exist_condition = array(
                    'id' => $record_id,
                    'is_deleted' => 0
                );
                $is_record_exist = $this->equipment_model->get_all_details(TBL_MANUFACTURERES, $record_exist_condition)->result_array();
                if (count($is_record_exist)) {
                    if ($this->equipment_model->insert_update('update', TBL_MANUFACTURERES, $record_array, array('id' => $record_id, 'is_deleted' => 0))) {
                        $this->session->set_flashdata('success', 'Manufacturer has been updated successfully.');
                        redirect('admin/equipments/manufacturers');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                        redirect('admin/equipments/manufacturers');
                    }
                } else {
                    $this->session->set_flashdata('error', 'No such record found. Please try again..!!');
                    redirect('admin/equipments/manufacturers');
                }
            } else {
                $record_array['created_date'] = date('Y-m-d H:i:s');
                $record_array['modified_date'] = date('Y-m-d H:i:s');
                if ($this->equipment_model->insert_update('insert', TBL_MANUFACTURERES, $record_array)) {
                    $this->session->set_flashdata('success', 'Manufacturer has been added successfully.');
                    redirect('admin/equipments/manufacturers');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                    redirect('admin/equipments/manufacturers');
                }
            }
        }
        $this->template->load('default', 'admin/equipment/manufacturers', $data);
    }

    /**
     * Used to do get data and displaying it in ajax datatable
     * @param --
     * @return Json data
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_manufacturer() {
        $final['recordsTotal'] = $this->equipment_model->get_manufacturer('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $make = $this->equipment_model->get_manufacturer('result')->result_array();
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
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_manufacturer_by_id() {
        $record_id = base64_decode($this->input->post('id'));
        $condition = array(
            'id' => $record_id,
            'is_deleted' => 0
        );
        $dataArr = $this->equipment_model->get_all_details(TBL_MANUFACTURERES, $condition)->row_array();
        echo json_encode($dataArr);
    }

    /**
     * Delete Make data by its id
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function delete_manufacturers($id = '') {
        $record_id = base64_decode($id);
        $this->equipment_model->insert_update('update', TBL_MANUFACTURERES, array('is_deleted' => 1), array('id' => $record_id));
        $this->session->set_flashdata('error', 'Manufacturer has been deleted successfully.');
        redirect('admin/equipments/manufacturers');
    }

    /**
     * This function is used to Check Takeout Types NAME for unique.
     * @param  : $id String
     * @return : Boolean Value - True / False
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function checkUnique_Manufacturer_Name($id = NULL) {
        $name = trim($this->input->get('txt_name'));
        $condition = 'name="' . $name . '"';
        if ($id != '') {
            $condition.=" AND id!=" . $id;
        }
        $result = $this->equipment_model->check_unique_name(TBL_MANUFACTURERES, $condition);
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
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function manufacturer_bulk_add() {
        $file = $this->input->post('upload_csv');
        $fileDirectory = MANUFACTURER_CSV;
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
                $csv_format = array('name', 'description');
                if ($data == $csv_format) {
                    fclose($handle);
                    $handle = fopen($fileDirectory . "/" . $fileDetails['file_name'], "r");
                    $insertArr = array();
                    while (($csv_data = fgetcsv($handle, 10000, ",")) !== FALSE) {

                        if ($row == 1) {
                            $row++;
                            continue;
                        }
                        $name = ucwords($csv_data[0]);
                        $description = ucwords($csv_data[1]);
                        if ($name == '') {
                            fclose($handle);
                            $this->session->set_flashdata('error', 'Some required fields are missing.');
                            redirect('admin/equipments/manufacturers');
                        } else {
                            $nameArr = array_column($insertArr, 'name');
                            if (!in_array($name, $nameArr)) {
                                $insertArr[] = array(
                                    'name' => $name,
                                    'description' => $description,
                                    'created_date' => date('Y-m-d h:i:s'),
                                    'modified_date' => date('Y-m-d h:i:s')
                                );
                            }
                        }
                    }
                    $this->db->insert_batch(TBL_MANUFACTURERES, $insertArr);
                } else {
                    fclose($handle);
                    $this->session->set_flashdata('error', 'The columns in this csv file does not match to the database');
                }
                redirect('admin/equipments/manufacturers');
            }
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('admin/equipments/manufacturers');
        }
    }

    /**
     * Used to do add make data by ajax
     * @param --
     * @return Json Object
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function add_manufacturer_data_ajax() {
        $mname = $this->input->post('txt_name');
        $mDesc = $this->input->post('txt_description');
        $insertArr = array(
            'name' => $mname,
            'description' => $mDesc,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s')
        );
        $insert_id = $this->equipment_model->insert_update('insert', TBL_MANUFACTURERES, $insertArr);
        if ($insert_id > 0) {
            $return = array('status' => 'success', 'id' => $insert_id, 'name' => htmlentities($this->input->post('txt_name')));
        }
        echo json_encode($return);
        exit;
    }

    /**
     * This function is used to display, add, edit takeout_types
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function manage_types() {
        $data['title'] = 'Manage Euipement Types';
        $this->form_validation->set_rules('txt_name', 'Name', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('txt_description', 'Description', 'trim|required|max_length[500]');
        if ($this->form_validation->run() == TRUE) {
            $record_id = $this->input->post('txt_type_id');
            $record_array = array(
                'name' => htmlentities($this->input->post('txt_name')),
                'description' => htmlentities($this->input->post('txt_description')),
                'modified_date' => date('Y-m-d H:i:s')
            );
            if ($record_id != '') {
                $record_exist_condition = array(
                    'id' => $record_id,
                    'is_deleted' => 0
                );
                $is_record_exist = $this->equipment_model->get_all_details(TBL_EQUIPMENT_TYPES, $record_exist_condition)->result_array();
                if (count($is_record_exist)) {
                    if ($this->equipment_model->insert_update('update', TBL_EQUIPMENT_TYPES, $record_array, array('id' => $record_id, 'is_deleted' => 0))) {
                        $this->session->set_flashdata('success', 'Equipement Type has been updated successfully.');
                        redirect('admin/equipments/types');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                        redirect('admin/equipments/types');
                    }
                } else {
                    $this->session->set_flashdata('error', 'No such record found. Please try again..!!');
                    redirect('admin/equipments/types');
                }
            } else {
                $record_array['created_date'] = date('Y-m-d H:i:s');
                $record_array['modified_date'] = date('Y-m-d H:i:s');
                if ($this->equipment_model->insert_update('insert', TBL_EQUIPMENT_TYPES, $record_array)) {
                    $this->session->set_flashdata('success', 'Equipement Type has been added successfully.');
                    redirect('admin/equipments/types');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                    redirect('admin/equipments/types');
                }
            }
        }
        $this->template->load('default', 'admin/equipment/types', $data);
    }

    /**
     * Used to do get data and displaying it in ajax datatable
     * @param --
     * @return Json data
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_equipment_type() {
        $final['recordsTotal'] = $this->equipment_model->get_equipment_type('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $make = $this->equipment_model->get_equipment_type('result')->result_array();
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
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_equipment_type_by_id() {
        $record_id = base64_decode($this->input->post('id'));
        $condition = array(
            'id' => $record_id,
            'is_deleted' => 0
        );
        $dataArr = $this->equipment_model->get_all_details(TBL_EQUIPMENT_TYPES, $condition)->row_array();
        echo json_encode($dataArr);
    }

    /**
     * Delete Make data by its id
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function delete_types($id = '') {
        $record_id = base64_decode($id);
        $this->equipment_model->insert_update('update', TBL_EQUIPMENT_TYPES, array('is_deleted' => 1), array('id' => $record_id));
        $this->session->set_flashdata('error', 'Equipment Type has been deleted successfully.');
        redirect('admin/equipments/types');
    }

    /**
     * This function is used to Check Takeout Types NAME for unique.
     * @param  : $id String
     * @return : Boolean Value - True / False
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function checkUnique_Type_Name($id = NULL) {
        $name = ($this->input->get('txt_type_name')) ? trim($this->input->get('txt_type_name')) : $this->input->get('txt_name');
        $condition = 'name="' . $name . '"';
        if ($id != '') {
            $condition.=" AND id!=" . $id;
        }
        $result = $this->equipment_model->check_unique_name(TBL_EQUIPMENT_TYPES, $condition);
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
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function type_bulk_add() {
        $file = $this->input->post('upload_csv');
        $fileDirectory = TYPE_CSV;
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
                $csv_format = array('name', 'description');
                if ($data == $csv_format) {
                    fclose($handle);
                    $handle = fopen($fileDirectory . "/" . $fileDetails['file_name'], "r");
                    $insertArr = array();
                    while (($csv_data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                        if ($row == 1) {
                            $row++;
                            continue;
                        }
                        if (!empty($csv_data)) {
                            $name = ucwords($csv_data[0]);
                            $description = ucwords($csv_data[1]);
                            if ($name == '') {
                                fclose($handle);
                                $this->session->set_flashdata('error', 'Some required fields are missing.');
                                redirect('admin/equipments/types');
                            } else {
                                $nameArr = array_column($insertArr, 'name');
                                if (!in_array($name, $nameArr)) {
                                    $insertArr[] = array(
                                        'name' => $name,
                                        'description' => $description,
                                        'created_date' => date('Y-m-d h:i:s'),
                                        'modified_date' => date('Y-m-d h:i:s')
                                    );
                                }
                            }
                        }
                    }
                    $this->db->insert_batch(TBL_EQUIPMENT_TYPES, $insertArr);
                } else {
                    fclose($handle);
                    $this->session->set_flashdata('error', 'The columns in this csv file does not match to the database');
                }
                redirect('admin/equipments/types');
            }
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('admin/equipments/types');
        }
    }

    /**
     * Used to do add make data by ajax
     * @param --
     * @return Json Object
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function add_type_data_ajax() {
        $mname = $this->input->post('txt_name');
        $mDesc = $this->input->post('txt_description');
        $insertArr = array(
            'name' => $mname,
            'description' => $mDesc,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s')
        );
        $insert_id = $this->equipment_model->insert_update('insert', TBL_EQUIPMENT_TYPES, $insertArr);
        if ($insert_id > 0) {
            $return = array('status' => 'success', 'id' => $insert_id, 'name' => htmlentities($this->input->post('txt_name')));
        }
        echo json_encode($return);
        exit;
    }

    /**
     * This function is used to display, add, edit takeout_types
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function manage_names() {
        $data['title'] = 'Manage Euipement Name';
        $data['manufacturerArr'] = $this->equipment_model->get_all_details(TBL_MANUFACTURERES, ['is_deleted' => 0])->result_array();
        $data['typeArr'] = $this->equipment_model->get_all_details(TBL_EQUIPMENT_TYPES, ['is_deleted' => 0])->result_array();
        $this->form_validation->set_rules('txt_description', 'Description', 'trim|required|max_length[500]');
        if ($this->form_validation->run() == TRUE) {
            $record_id = $this->input->post('txt_name_id');
            $record_array = array(
                'equipment_type_id' => $this->input->post('equipment_type_id'),
                'manufacturer_id' => $this->input->post('manufacturer_id'),
                'description' => htmlentities($this->input->post('txt_description')),
                'modified_date' => date('Y-m-d H:i:s')
            );
            if ($record_id != '') {
                $record_exist_condition = array(
                    'id' => $record_id,
                    'is_deleted' => 0
                );
                $is_record_exist = $this->equipment_model->get_all_details(TBL_EQUIPMENT_NAMES, $record_exist_condition)->result_array();
                if (count($is_record_exist)) {
                    if ($this->equipment_model->insert_update('update', TBL_EQUIPMENT_NAMES, $record_array, array('id' => $record_id, 'is_deleted' => 0))) {
                        $this->session->set_flashdata('success', 'Equipement Name has been updated successfully.');
                        redirect('admin/equipments/names');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                        redirect('admin/equipments/names');
                    }
                } else {
                    $this->session->set_flashdata('error', 'No such record found. Please try again..!!');
                    redirect('admin/equipments/names');
                }
            } else {
                $record_array['created_date'] = date('Y-m-d H:i:s');
                $record_array['modified_date'] = date('Y-m-d H:i:s');
                if ($this->equipment_model->insert_update('insert', TBL_EQUIPMENT_NAMES, $record_array)) {
                    $this->session->set_flashdata('success', 'Equipement Name has been added successfully.');
                    redirect('admin/equipments/names');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! Please try it again.');
                    redirect('admin/equipments/names');
                }
            }
        }
        $this->template->load('default', 'admin/equipment/names', $data);
    }

    /**
     * Used to do get data and displaying it in ajax datatable
     * @param --
     * @return Json data
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_equipment_name() {
        $final['recordsTotal'] = $this->equipment_model->get_equipment_name('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $make = $this->equipment_model->get_equipment_name('result')->result_array();
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
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_equipment_name_by_id() {
        $record_id = base64_decode($this->input->post('id'));
        $condition = array(
            'id' => $record_id,
            'is_deleted' => 0
        );
        $dataArr = $this->equipment_model->get_all_details(TBL_EQUIPMENT_NAMES, $condition)->row_array();
        echo json_encode($dataArr);
    }

    /**
     * Delete Make data by its id
     * @param $id - String
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function delete_names($id = '') {
        $record_id = base64_decode($id);
        $this->equipment_model->insert_update('update', TBL_EQUIPMENT_NAMES, array('is_deleted' => 1), array('id' => $record_id));
        $this->session->set_flashdata('error', 'Equipment Name has been deleted successfully.');
        redirect('admin/equipments/names');
    }

}
