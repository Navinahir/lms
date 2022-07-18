<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/users_model', 'admin/inventory_model', 'admin/product_model'));
    }

    /**
     * Home Page
     * @param --
     * @return --
     * @author HPA [Last Edited : 02/06/2018]
     */
    public function index() {
        if ((!$this->session->userdata('vendor_logged_in'))) {
            $this->session->set_flashdata('error', 'Please login to continue!');
            redirect('/vendor/login', 'refresh');
        }
        $data['title'] = 'List of Products';
        $this->template->load('default_vendor', 'vendor/items/items_display', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_items_data() {
        $final['recordsTotal'] = $this->inventory_model->get_vendor_items_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->inventory_model->get_vendor_items_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get Item's data by its ID
     * @param --
     * @return HTML data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_item_data_ajax_by_id() {
        $part_id = base64_decode($this->input->post('id'));

        $this->db->select('i.*,d.name as dept_name,v1.name as v1_name');
        $this->db->from(TBL_ITEMS . ' AS i');
        $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.preferred_vendor=v1.id', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
        $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_COMPANY . ' as c', 'c.id=t.make_id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 'm.id=t.model_id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 'y.id=t.year_id and y.is_delete=0', 'left');
        $this->db->where('i.id', $part_id);
        $result = $this->db->get();
        $data['viewArr'] = $result->row_array();

        $this->db->select('c.name as company,m.name as model,y.name as year');
        $this->db->from(TBL_ITEMS . ' AS i');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.preferred_vendor=v1.id', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
        $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_COMPANY . ' as c', 'c.id=t.make_id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 'm.id=t.model_id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 'y.id=t.year_id and y.is_delete=0', 'left');
        $this->db->where('i.id', $part_id);
        $parts_result = $this->db->get();
        $data['partArr'] = $parts_result->result_array();

        return $this->load->view('vendor/partial_view/view_search_data', $data);
        die;
    }

    /**
     * Edit Items
     * @param $id - String
     * @return --
     * @author HGA [Last Edited : 04/02/2019]
     */
    public function edit_items($id = null) {
        $year_where = $year_data = $selected_years = $insertArr = [];

        $user_data = $this->users_model->get_profile(checkVendorLogin('I'));
        $dept_Arr = $this->inventory_model->get_all_details(TBL_DEPARTMENTS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $vendor_Arr = $this->inventory_model->get_all_details(TBL_VENDORS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $data = array(
            'title' => 'Add Items',
            'dept_Arr' => $dept_Arr,
            'vendor_Arr' => $vendor_Arr,
            'user_data' => $user_data,
        );

        if (!is_null($id)):
            $record_id = base64_decode($id);
            $dataArr = $this->inventory_model->get_all_details(TBL_ITEMS, array('id' => $record_id, 'is_delete' => 0))->row_array();
            $transdataArr = $this->inventory_model->get_trans_items($record_id);
            $trans_items_data = $this->inventory_model->edit_trans_items($record_id);

            foreach ($trans_items_data as $make_model) {
                $year_where = [
                    't.make_id' => $make_model['make_id'],
                    't.model_id' => $make_model['model_id'],
                    't.is_delete' => 0,
                ];

                $this->db->select('y.id,y.name');
                $this->db->from(TBL_TRANSPONDER . ' as t');
                $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id', 'left');
                $this->db->where($year_where);
                $this->db->order_by('y.name', 'asc');
                $year_data[$make_model['id']] = $this->db->get()->result_array();
            }

            foreach ($transdataArr as $yearVal) {
                $selected_years[$yearVal['make_id']][$yearVal['model_id']][] = $yearVal['year_id'];
            }

            $data = array(
                'title' => 'Edit Items',
                'dataArr' => $dataArr,
                'dept_Arr' => $dept_Arr,
                'vendor_Arr' => $vendor_Arr,
                'trans_Arr' => $transdataArr,
                'trans_items_data' => $trans_items_data,
                'year_data' => $year_data,
                'selected_years' => $selected_years
            );
        endif;

        $data['companyArr'] = $companyArr = $this->product_model->get_all_details(TBL_COMPANY, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
        $data['yearArr'] = $yearArr = $this->product_model->get_all_details(TBL_YEAR, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
        $data['modelArr'] = $this->product_model->get_all_details(TBL_MODEL, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();

        $this->form_validation->set_rules('txt_item_part', 'Item Part', 'trim|required');
        $this->form_validation->set_rules('txt_internal_part', 'Internal Part', 'trim|required');
        $this->form_validation->set_rules('txt_department', 'Department', 'trim|required');
        $this->form_validation->set_rules('txt_pref_vendor', 'Preffered Vendor', 'trim|required');
        $this->form_validation->set_rules('txt_manufacturer', 'Preffered Vendor Part', 'trim|required');
        $this->form_validation->set_rules('txt_item_description', 'Item Description', 'trim|required');
        if ($this->form_validation->run() == true) {
            $flag = 0;
            $item_image = '';

            $insertArr['part_no'] = htmlentities($this->input->post('txt_item_part'));
            $insertArr['description'] = htmlentities($this->input->post('txt_item_description'));
            $insertArr['internal_part_no'] = htmlentities($this->input->post('txt_internal_part'));
            $insertArr['department_id'] = htmlentities($this->input->post('txt_department'));
            $insertArr['preferred_vendor'] = htmlentities($this->input->post('txt_pref_vendor'));
            $insertArr['manufacturer'] = htmlentities($this->input->post('txt_manufacturer'));
            $insertArr['item_link'] = htmlentities($this->input->post('item_link'));

            if ($_FILES['image_link']['name'] != '') {
                $img_array = array('png', 'jpeg', 'jpg', 'PNG', 'JPEG', 'JPG');
                $exts = explode(".", $_FILES['image_link']['name']);
                $name = time() . "." . end($exts);

                $config['upload_path'] = ITEMS_IMAGE_PATH;
                $config['allowed_types'] = implode("|", $img_array);
                $config['max_size'] = '2048';
                $config['file_name'] = $name;

                $this->upload->initialize($config);
                if (($_FILES['image_link']['size'] > 2097152)) {
                    $message = 'File too large. File must be less than 2 megabytes.';
                    $this->session->set_flashdata('error', $message);
                    redirect('vendor/products');
                } else {
                    if (!$this->upload->do_upload('image_link')) {
                        $flag = 1;
                        $data['item_image_validation'] = $this->upload->display_errors();
                    } else {
                        $file_info = $this->upload->data();
                        $item_image = $file_info['file_name'];
                        $insertArr['image'] = $item_image;
                    }
                }
            }

            if (!is_null($id)):
                $allItems = array_column($data['trans_Arr'], 'id');
                $insertArr['modified_date'] = date('Y-m-d h:i:s');
                $make_array = $this->input->post('txt_make_name');

                if (!empty($dataArr) && empty($dataArr['item_qr_code'])) {
                    $insertArr['item_qr_code'] = $this->generat_item_qr_code($this->input->post('txt_item_part'));
                }

                $this->inventory_model->insert_update('update', TBL_ITEMS, $insertArr, array('id' => $record_id));
                $vendor_array = [
                    'created_date' => date('Y-m-d h:i:s'),
                    'item_id' => $record_id,
                    'vendor_id' => checkVendorLogin('I'),
                    'action' => 'edited',
                ];
                $this->inventory_model->insert_update('insert', TBL_VENDOR_HISTROY, $vendor_array);

                if (isset($allItems) && !empty($allItems)):
                    $this->db->where_in('items_id', $record_id);
                    $this->db->delete(TBL_TRANSPONDER_ITEMS);
                endif;

                $transItemArr = $this->add_transponder_items($record_id);

                if (isset($transItemArr) && !empty($transItemArr)) {
                    $this->inventory_model->batch_insert_update('insert', TBL_TRANSPONDER_ITEMS, $transItemArr);
                } else {
                    $this->session->set_flashdata('error', 'Make, Model, Year could not been found.');
                    redirect('vendor/products/edit/' . $id);
                }

                $this->session->set_flashdata('success', 'Data has been updated successfully.');
            else:
                $insertArr['item_qr_code'] = $this->generat_item_qr_code($this->input->post('txt_item_part'));
                $insertArr['created_date'] = date('Y-m-d h:i:s');
                $insertArr['modified_date'] = date('Y-m-d h:i:s');

                $insert_id = $this->inventory_model->insert_update('insert', TBL_ITEMS, $insertArr);
                $vendor_array = [
                    'created_date' => date('Y-m-d h:i:s'),
                    'item_id' => $insert_id,
                    'vendor_id' => checkVendorLogin('I'),
                    'action' => 'added',
                ];
                $vendor_history_id = $this->inventory_model->insert_update('insert', TBL_VENDOR_HISTROY, $vendor_array);
                if ($insert_id > 0) {
                    $transItemArr = $this->add_transponder_items($insert_id);

                    if (isset($transItemArr) && !empty($transItemArr)) {
                        $this->inventory_model->batch_insert_update('insert', TBL_TRANSPONDER_ITEMS, $transItemArr);
                        $this->session->set_flashdata('success', 'Data has been added successfully.');
                    } else {
                        $this->session->set_flashdata('error', 'Make, Model, Year could not been found.');
                        redirect('vendor/products/add');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                }
            endif;

            redirect('vendor/products');
        }
        $this->template->load('default_vendor', 'vendor/items/items_add', $data);
    }

    /**
     * Delete Items
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function delete_items($id = '') {
        $record_id = base64_decode($id);
        $this->inventory_model->insert_update('update', TBL_ITEMS, array('is_delete' => 1), array('id' => $record_id));
        $vendor_array = [
            'created_date' => date('Y-m-d h:i:s'),
            'item_id' => $record_id,
            'vendor_id' => checkVendorLogin('I'),
            'action' => 'deleted',
        ];
        $this->inventory_model->insert_update('insert', TBL_VENDOR_HISTROY, $vendor_array);
        $this->session->set_flashdata('error', 'Product\'s data has been deleted successfully.');
        redirect('vendor/products');
    }

    public function check_unique_item_data() {
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $data = array('part_no' => $this->input->post('part_no'), 'preferred_vendor' => $this->input->post('preferred_vendor'), 'is_delete' => 0);
            if (!is_null($id)) {
                $data = array_merge($data, array('id!=' => $id));
            }
            $dataArr = $this->inventory_model->get_all_details(TBL_ITEMS, $data)->result_array();
            if (count($dataArr) > 0) {
                echo json_encode(['code' => 200]);
            } else {
                echo json_encode(['code' => 404]);
            }
        }
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

    public function item_bulk_add() {
        $file = $this->input->post('upload_csv');
        $fileDirectory = ITEM_CSV;
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
                $csv_format = array('item_part_no', 'alternate_no_SKU', 'department', 'item_Description', 'vendor', 'manufacturer');
                if ($data == $csv_format) {
                    fclose($handle);
                    $handle = fopen($fileDirectory . "/" . $fileDetails['file_name'], "r");
                    $insertArr = array();
                    $dublicate = 0;
                    while (($csv_data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                        if ($row == 1) {
                            $row++;
                            continue;
                        }
                        $item_name = ucwords($csv_data[0]);
                        $dept_name = ucwords($csv_data[2]);
                        $vendor_name = ucwords($csv_data[4]);
                        if ($item_name == '' || $dept_name == '' || $vendor_name == '') {
                            fclose($handle);
                            $this->session->set_flashdata('error', 'Some required fields are missing.');
                            redirect('vendor/products/add');
                        } else {
                            $nameArr = array_column($insertArr, 'item_part_no');
                            if (!in_array($item_name, $nameArr)) {
                                $dept_id = $this->product_model->get_all_details(TBL_DEPARTMENTS, array('name' => trim($dept_name), 'is_delete' => 0))->row_array()['id'];
                                $vendor_id = $this->product_model->get_all_details(TBL_VENDORS, array('name' => trim($vendor_name), 'is_delete' => 0))->row_array()['id'];
                                if ($vendor_id != null) {
                                    $data = array('part_no' => $item_name, 'internal_part_no' => $csv_data[1], 'preferred_vendor' => $vendor_id, 'is_delete' => 0);
                                    $dataArr = $this->product_model->get_all_details(TBL_ITEMS, $data)->result_array();
                                    if (count($dataArr) > 0) {
                                        $dublicate = 1;
                                    } else {
                                        $insertArr[] = array(
                                            'part_no' => $item_name,
                                            'description' => $csv_data[3],
                                            'internal_part_no' => $csv_data[1],
                                            'department_id' => $dept_id,
                                            'preferred_vendor' => $vendor_id,
                                            'manufacturer' => $csv_data[5],
                                            'created_date' => date('Y-m-d h:i:s')
                                        );
                                    }
                                }
                            }
                        }
                    }
                    if (!empty($insertArr)) {
                        $this->db->insert_batch(TBL_ITEMS, $insertArr);
                        if ($dublicate == 1) {
                            $this->session->set_flashdata('error', 'Items added successfully and Some Items haven\'t added, because same items already Exits!!');
                        } else {
                            $this->session->set_flashdata('success', 'Items added successfully!!');
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Items already Exits!!');
                    }
                } else {
                    fclose($handle);
                    $this->session->set_flashdata('error', 'The columns in this csv file does not match to the database');
                }
                redirect('vendor/products/add');
            }
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('vendor/products/add');
        }
    }

    public function add_transponder_items($item_id) {
        $transItemArr = [];
        $i = 0;
        foreach ($this->input->post('txt_year_name') as $year_key => $year_value) {
            if (count($year_value) > 0) {
                $make_id = $this->input->post('txt_make_name')[$i];
                if (!empty($make_id)) {
                    $t_items_arr = array();
                    $model_id = $this->input->post('txt_model_name')[$i];
                    foreach ($year_value as $year) {
                        $part_array = array(
                            'is_delete' => 0,
                            'make_id' => $make_id,
                            'model_id' => $model_id,
                            'year_id' => $year
                        );

                        $transponder = $this->product_model->get_all_details(TBL_TRANSPONDER, $part_array)->row_array();

                        if (!empty($transponder)) {
                            $t_items_arr = [
                                'transponder_id' => $transponder['id'],
                                'items_id' => $item_id
                            ];
                            array_push($transItemArr, $t_items_arr);
                        }
                    }
                }
            }
            $i++;
        }

        return $transItemArr;
    }

    public function get_transponder_item_years() {
        $make_id = $this->input->post('make_id');
        $model_id = $this->input->post('model_id');

        $part_array = array(
            't.is_delete' => 0,
            't.make_id' => $make_id,
            't.model_id' => $model_id,
        );

        $this->db->select('y.id,y.name');
        $this->db->from(TBL_TRANSPONDER . ' as t');
        $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id', 'left');
        $this->db->where($part_array);
        $this->db->order_by('y.name', 'asc');
        $year_data = $this->db->get()->result_array();

        $option = "<option></option>";

        if (!empty($year_data)) {
            foreach ($year_data as $k => $v) {
                $option .= "<option value='" . $v['id'] . "'>" . $v['name'] . "</option>";
            }
        }
        echo json_encode($option);
        die;
    }

    public function export_products() {
        try {
            $redirect_url = site_url('vendor/products');
            $vendor_role = checkVendorLogin('R');
            if ($vendor_role == 5) {
                $user_id = checkVendorLogin('I');

                $products_data = $this->inventory_model->export_vendor_all_items($user_id);

                // file name 
                $filename = 'products_' . date('Ymd') . '.csv';
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$filename");
                header("Content-Type: application/csv; ");
                header("refresh:5;url=$redirect_url");

                // file creation 
                $file = fopen('php://output', 'w');

                $header = array("item_part_no", "alternate_no_SKU", "department", "item_Description", "vendor", "manufacturer");
                fputcsv($file, $header);

                foreach ($products_data as $key => $line) {
                    fputcsv($file, $line);
                }

                fclose($file);
                exit;
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
        }
    }

    public function generat_item_qr_code($part_no) {
        $this->load->library('ciqrcode');

        $file_name = $part_no . '.png';

        $params['data'] = $part_no;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = str_replace('"', "", FCPATH . 'assets\qr_codes\"' . $file_name);

        $this->ciqrcode->generate($params);

        return $file_name;
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */