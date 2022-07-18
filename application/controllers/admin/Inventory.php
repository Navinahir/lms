<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/inventory_model'));
        controller_validation();
    }

    /*     * ********************************************************
      Manage Items
     * ******************************************************** */

    /**
     * Display All items 
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function display_items() {
        $data['title'] = 'List of items';
        $this->template->load('default', 'admin/inventory/items_display', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_items_data() {
        $final['recordsTotal'] = $this->inventory_model->get_items_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->inventory_model->get_items_data('result')->result_array();
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
     * Add items
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function add_items() {
        $dept_Arr = $this->inventory_model->get_all_details(TBL_DEPARTMENTS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $vendor_Arr = $this->inventory_model->get_all_details(TBL_VENDORS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $this->form_validation->set_rules('txt_item_part', 'Item Part', 'trim|required');
        $this->form_validation->set_rules('txt_internal_part', 'Internal Part', 'trim|required');
        $this->form_validation->set_rules('txt_department', 'Department', 'trim|required');
        $this->form_validation->set_rules('txt_pref_vendor', 'Preffered Vendor', 'trim|required');
        $this->form_validation->set_rules('txt_manufacturer', 'Manufacturer', 'trim|required');
        $this->form_validation->set_rules('txt_item_description', 'Item Description', 'trim|required');
        if ($this->form_validation->run() == true) {
            $flag = 0;
            $item_image = '';
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
                    redirect('admin/inventory/items');
                } else {
                    if (!$this->upload->do_upload('image_link')) {
                        $flag = 1;
                        $data['item_image_validation'] = $this->upload->display_errors();
                    } else {
                        $file_info = $this->upload->data();
                        $item_image = $file_info['file_name'];
                    }
                }
            }
            if ($flag != 1) {
                $insertArr = array(
                    'part_no' => htmlentities($this->input->post('txt_item_part')),
                    'description' => htmlentities($this->input->post('txt_item_description')),
                    'internal_part_no' => htmlentities($this->input->post('txt_internal_part')),
                    'image' => $item_image,
                    'department_id' => htmlentities($this->input->post('txt_department')),
                    'preferred_vendor' => htmlentities($this->input->post('txt_pref_vendor')),
                    'manufacturer' => htmlentities($this->input->post('txt_manufacturer')),
                    'created_date' => date('Y-m-d h:i:s')
                );
                $insert_id = $this->inventory_model->insert_update('insert', TBL_ITEMS, $insertArr);
                if ($insert_id > 0) {
                    $this->session->set_flashdata('success', 'Data has been added successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                }
                redirect('admin/inventory/items');
            }
        }

        $data = array(
            'title' => 'Add Items',
            'dept_Arr' => $dept_Arr,
            'vendor_Arr' => $vendor_Arr
        );
        $this->template->load('default', 'admin/inventory/items_add', $data);
    }

    /**
     * Edit Items
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function edit_items($id = '') {
        $record_id = base64_decode($id);
        $dept_Arr = $this->inventory_model->get_all_details(TBL_DEPARTMENTS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $vendor_Arr = $this->inventory_model->get_all_details(TBL_VENDORS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $dataArr = $this->inventory_model->get_all_details(TBL_ITEMS, array('id' => $record_id, 'is_delete' => 0))->row_array();
        $this->form_validation->set_rules('txt_item_part', 'Item Part', 'trim|required');
        $this->form_validation->set_rules('txt_internal_part', 'Internal Part', 'trim|required');
        $this->form_validation->set_rules('txt_department', 'Department', 'trim|required');
        $this->form_validation->set_rules('txt_pref_vendor', 'Preffered Vendor', 'trim|required');
        $this->form_validation->set_rules('txt_manufacturer', 'Manufacturer', 'trim|required');
        $this->form_validation->set_rules('txt_item_description', 'Item Description', 'trim|required');
        if ($this->form_validation->run() == true) {
            $flag = 0;
            $item_image = '';
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
                    redirect('admin/inventory/items');
                } else {
                    if (!$this->upload->do_upload('image_link')) {
                        $flag = 1;
                        $data['item_image_validation'] = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $data['item_image_validation']);
                    } else {
                        $file_info = $this->upload->data();
                        $item_image = $file_info['file_name'];
                    }
                }    
            }
            if ($flag != 1) {
                $updateArr = array(
                    'part_no' => htmlentities($this->input->post('txt_item_part')),
                    'description' => htmlentities($this->input->post('txt_item_description')),
                    'internal_part_no' => htmlentities($this->input->post('txt_internal_part')),
                    'department_id' => htmlentities($this->input->post('txt_department')),
                    'preferred_vendor' => htmlentities($this->input->post('txt_pref_vendor')),
                    'manufacturer' => htmlentities($this->input->post('txt_manufacturer')),
                    'created_date' => date('Y-m-d h:i:s')
                );
                if ($item_image != '') {
                    $updateArr['image'] = $item_image;
                }

                //Update New QR Code
                if (!empty($updateArr) && empty($updateArr['item_qr_code'])) {
                    $path = FCPATH . 'assets/qr_codes/';
                    $img_name = $updateArr['part_no'].'.png';
                    if(file_exists($path .$img_name))
                    {
                        unlink($path .$img_name);
                    } 
                    $insertArr['item_qr_code'] = $this->generat_item_qr_code($this->input->post('txt_item_part'));   
                }
                $this->inventory_model->insert_update('update', TBL_ITEMS, $updateArr, array('id' => $record_id));
                $this->session->set_flashdata('success', 'Item has been updated successfully.');
                redirect('admin/inventory/items');
            }
        }

        $data = array(
            'title' => 'Edit Items',
            'dept_Arr' => $dept_Arr,
            'vendor_Arr' => $vendor_Arr,
            'dataArr' => $dataArr
        );
        $this->template->load('default', 'admin/inventory/items_add', $data);
    }

    public function generat_item_qr_code($part_no) {
        $this->load->library('ciqrcode');

        $file_name = $part_no . '.png';

        $params['data'] = $part_no;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = str_replace('"', "", FCPATH . 'assets/qr_codes/' . $file_name);
        $this->ciqrcode->generate($params);

        return $file_name;
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
        $this->session->set_flashdata('error', 'Item\'s data has been deleted successfully.');
        redirect('admin/inventory/items');
    }

    /**
     * Get Item's data by its ID
     * @param --
     * @return HTML data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_item_data_ajax_by_id() {
        $part_id = base64_decode($this->input->post('id'));
        $this->db->select('i.*,d.name as dept_name,v1.name as v1_name,GROUP_CONCAT(CONCAT(c.name, " ", `m`.`name`, " ", y.name)) as compatibility');
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
        //$data['viewArr'] = $this->inventory_model->get_all_details(TBL_ITEMS,array('id'=>$part_id))->row_array();
        return $this->load->view('admin/partial_view/view_search_data', $data);
        die;
    }

    /*     * ********************************************************
      Manage Departments
     * ******************************************************** */

    /**
     * Display Departments
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function display_departments() {
        $data['title'] = 'List of departments';
        $this->template->load('default', 'admin/inventory/department_display', $data);
    }

    /**
     * Get Departments data and displaying in ajax datatable listing
     * @param --
     * @return Object (Json Format)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_departments_data() {
        $final['recordsTotal'] = $this->inventory_model->get_departments_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $departments = $this->inventory_model->get_departments_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($departments as $key => $val) {
            $departments[$key] = $val;
            $departments[$key]['sr_no'] = $start++;
            $departments[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $departments[$key]['responsive'] = '';
        }
        $final['data'] = $departments;
        echo json_encode($final);
        die;
    }

    /**
     * Add Departments
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function add_departments() {
        $data['title'] = 'Add departments';
        $this->form_validation->set_rules('txt_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('txt_desc', 'Description', 'trim|required');
        if ($this->form_validation->run() == true) {
            $insertArr = array(
                'name' => htmlentities($this->input->post('txt_name')),
                'description' => htmlentities($this->input->post('txt_desc'))
            );
            $insert_id = $this->inventory_model->insert_update('insert', TBL_DEPARTMENTS, $insertArr);
            if ($insert_id > 0) {
                $this->session->set_flashdata('success', 'Data has been added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
            }
            redirect('admin/inventory/departments');
        }
        $this->template->load('default', 'admin/inventory/department_add', $data);
    }

    /**
     * Edit Departments
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function edit_departments($id = '') {
        $record_id = base64_decode($id);
        $dataArr = $this->inventory_model->get_all_details(TBL_DEPARTMENTS, array('id' => $record_id))->row_array();
        $this->form_validation->set_rules('txt_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('txt_desc', 'Description', 'trim|required');
        if ($this->form_validation->run() == true) {
            $updateArr = array(
                'name' => htmlentities($this->input->post('txt_name')),
                'description' => htmlentities($this->input->post('txt_desc'))
            );
            $this->inventory_model->insert_update('update', TBL_DEPARTMENTS, $updateArr, array('id' => $record_id));
            $this->session->set_flashdata('success', 'Data has been updated successfully.');
            redirect('admin/inventory/departments');
        }
        $data = array(
            'title' => 'Edit departments',
            'dataArr' => $dataArr
        );
        $this->template->load('default', 'admin/inventory/department_add', $data);
    }

    /**
     * Delete Departments
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function delete_departments($id) {
        $record_id = base64_decode($id);
        $this->inventory_model->insert_update('update', TBL_DEPARTMENTS, array('is_delete' => 1), array('id' => $record_id));
        $this->session->set_flashdata('error', 'Departmanet\'s data has been deleted successfully.');
        redirect('admin/inventory/departments');
    }

    /*     * ********************************************************
      Manage Vendors
     * ******************************************************** */

    /**
     * Display Vendors
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function display_vendors() {
        $data['title'] = 'List of vendors';
        $this->template->load('default', 'admin/inventory/vendor_display', $data);
    }

    /**
     * Get vendor's data and displaying in ajax datatable for listing
     * @param --
     * @return Object (Json format)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_vendors_data() {
        $final['recordsTotal'] = $this->inventory_model->get_vendors_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $vendors = $this->inventory_model->get_vendors_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($vendors as $key => $val) {
            $vendors[$key] = $val;
            $vendors[$key]['sr_no'] = $start++;
            $vendors[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $vendors[$key]['responsive'] = '';
        }
        $final['data'] = $vendors;
        echo json_encode($final);
    }

    /**
     * Add Vendors
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function add_vendors() {
        $data['title'] = 'Add vendors';
        $this->form_validation->set_rules('txt_name', 'Name', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('txt_desc', 'Description', 'trim|required');
        $this->form_validation->set_rules('txt_email_id', 'Email', 'trim|required');
        $this->form_validation->set_rules('txt_contact_person', 'Contact Person', 'trim|required|max_length[150]');

        if ($this->form_validation->run() == true) {
            $password = $org_pass = randomPassword();
            $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
            $algo = $password_encrypt_key . $password . $password_encrypt_key;
            $encrypted_pass = hash('sha256', $algo);
            $username = $this->generate_unique_username(htmlentities($this->input->post('txt_name')));

            $user_array = [
                'first_name' => htmlentities($this->input->post('txt_name')),
                'email_id' => htmlentities($this->input->post('txt_email_id')),
                'user_role' => 5,
                'username' => $username,
                'password' => $encrypted_pass,
                'password_encrypt_key' => $password_encrypt_key,
                'status' => 'active',
                'created_date' => date('Y-m-d H:i:s'),
            ];
            $user_id = $this->users_model->insert_update('insert', TBL_USERS, $user_array);
            extract($user_array);

            $can_add_multi_staff = 0;

            if (!empty($this->input->post('can_add_multi_staff')) && $this->input->post('can_add_multi_staff') == 1) {
                $can_add_multi_staff = 1;
            }

            $insertArr = array(
                'name' => htmlentities($this->input->post('txt_name')),
                'description' => htmlentities($this->input->post('txt_desc')),
                'contact_person' => htmlentities($this->input->post('txt_contact_person')),
                'contact_no' => htmlentities($this->input->post('txt_contact_no')),
                'created_date' => date('Y-m-d H:i:s'),
                'user_id' => $user_id,
                'can_add_multi_staff' => $can_add_multi_staff,
            );
            $insert_id = $this->inventory_model->insert_update('insert', TBL_VENDORS, $insertArr);
            if ($insert_id > 0) {
                $email_var = array(
                    'user_id' => $user_id,
                    'first_name' => $this->input->post('txt_name'),
                    'username' => $username,
                    'email_id' => $this->input->post('txt_email_id'),
                    'password' => $org_pass
                );
                $message = $this->load->view('email_template/default_header.php', $email_var, true);
                $message .= $this->load->view('email_template/staff_registration.php', $email_var, true);
                $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
                $email_array = array(
                    'mail_type' => 'html',
                    'from_mail_id' => $this->config->item('smtp_user'),
                    'from_mail_name' => 'ARK Team',
                    'to_mail_id' => $this->input->post('txt_email_id'),
                    'cc_mail_id' => '',
                    'subject_message' => 'Account Registration',
                    'body_messages' => $message
                );
                $email_send = common_email_send($email_array);
                $this->session->set_flashdata('success', 'Data has been added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
            }
            redirect('admin/inventory/vendors');
        }
        $this->template->load('default', 'admin/inventory/vendor_add', $data);
    }

    /**
     * Edit vendors
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function edit_vendors($id = '') {
        $record_id = base64_decode($id);
        $dataArr = $this->inventory_model->get_vendor_data(TBL_VENDORS, array('v.id' => $record_id))->row_array();
        $dataArr['api_token'] = $this->inventory_model->get_all_details(TBL_API_TOKENS, array('vendor_id' => $record_id))->row_array();

        $this->form_validation->set_rules('txt_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('txt_desc', 'Description', 'trim|required');
        $this->form_validation->set_rules('txt_contact_person', 'Contact Person', 'trim');
        $this->form_validation->set_rules('txt_contact_no', 'Contact No', 'trim');
        if ($this->form_validation->run() == true) {
            $can_add_multi_staff = 0;

            if (!empty($this->input->post('can_add_multi_staff')) && $this->input->post('can_add_multi_staff') == 1) {
                $can_add_multi_staff = 1;
            }

            $updateArr = array(
                'name' => htmlentities($this->input->post('txt_name')),
                'description' => htmlentities($this->input->post('txt_desc')),
                'contact_person' => htmlentities($this->input->post('txt_contact_person')),
                'contact_no' => htmlentities($this->input->post('txt_contact_no')),
                'can_add_multi_staff' => $can_add_multi_staff,
            );
            if ($dataArr['user_id'] != 0) {
                $user_array = [
                    'email_id' => htmlentities($this->input->post('txt_email_id')),
                    'first_name' => htmlentities($this->input->post('txt_name')),
                ];
                $user_id = $this->users_model->insert_update('update', TBL_USERS, $user_array, array('id' => $dataArr['user_id']));
            } else {
                $password = $org_pass = randomPassword();
                $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
                $algo = $password_encrypt_key . $password . $password_encrypt_key;
                $encrypted_pass = hash('sha256', $algo);
                $username = $this->generate_unique_username(htmlentities($this->input->post('txt_name')));

                $user_array = [
                    'first_name' => htmlentities($this->input->post('txt_name')),
                    'email_id' => htmlentities($this->input->post('txt_email_id')),
                    'user_role' => 5,
                    'username' => $username,
                    'password' => $encrypted_pass,
                    'password_encrypt_key' => $password_encrypt_key,
                    'status' => 'active',
                    'created_date' => date('Y-m-d H:i:s'),
                ];
                $user_id = $this->users_model->insert_update('insert', TBL_USERS, $user_array);
                if ($user_id) {
                    $email_var = array(
                        'user_id' => $user_id,
                        'first_name' => $this->input->post('txt_name'),
                        'username' => $username,
                        'email_id' => $this->input->post('txt_email_id'),
                        'password' => $org_pass
                    );
                    $message = $this->load->view('email_template/default_header.php', $email_var, true);
                    $message .= $this->load->view('email_template/staff_registration.php', $email_var, true);
                    $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
                    $email_array = array(
                        'mail_type' => 'html',
                        'from_mail_id' => $this->config->item('smtp_user'),
                        'from_mail_name' => 'ARK Team',
                        'to_mail_id' => $this->input->post('txt_email_id'),
                        'cc_mail_id' => '',
                        'subject_message' => 'Account Registration',
                        'body_messages' => $message
                    );
                    $email_send = common_email_send($email_array);
                }
                extract($user_array);
                $updateArr['user_id'] = $user_id;
            }

            $this->inventory_model->insert_update('update', TBL_VENDORS, $updateArr, array('id' => $record_id));
            $this->session->set_flashdata('success', 'Data has been updated successfully.');
            redirect('admin/inventory/vendors');
        }
        $data = array(
            'title' => 'Edit Vendors',
            'dataArr' => $dataArr
        );
        $this->template->load('default', 'admin/inventory/vendor_add', $data);
    }

    /**
     * Edit Vendors
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function delete_vendors($id) {
        $record_id = base64_decode($id);
        $this->inventory_model->insert_update('update', TBL_VENDORS, array('is_delete' => 1), array('id' => $record_id));
        $dataArr = $this->inventory_model->get_all_details(TBL_VENDORS, array('id' => $record_id))->row_array();
        if (!empty($dataArr)) {
            $this->inventory_model->insert_update('update', TBL_USERS, array('is_delete' => 1), array('id' => $dataArr['user_id']));
        }
        $this->session->set_flashdata('error', 'Vendor\'s data has been deleted successfully.');
        redirect('admin/inventory/vendors');
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
     * Reset Password
     * @param  : $id String
     * @return : ---
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function reset_password($id = NULL) {
        $record_id = base64_decode($id);
        $staff = $this->users_model->get_all_details(TBL_USERS, array('id' => $record_id))->row_array();
        if (!empty($staff)) {
            $password = $org_pass = randomPassword();
            $password_encrypt_key = bin2hex(openssl_random_pseudo_bytes(6, $cstrong));
            $algo = $password_encrypt_key . $password . $password_encrypt_key;
            $encrypted_pass = hash('sha256', $algo);

            $user_array = [
                'password' => $encrypted_pass,
                'password_encrypt_key' => $password_encrypt_key,
                'modified_date' => date('Y-m-d H:i:s')
            ];
            $insert_id = $this->users_model->insert_update('update', TBL_USERS, $user_array, array('id' => $staff['id']));
            extract($staff);

            if ($insert_id > 0) {
                $email_var = array(
                    'first_name' => $full_name,
                    'username' => $username,
                    'email_id' => $email_id,
                    'password' => $org_pass
                );
                $message = $this->load->view('email_template/default_header.php', $email_var, true);
                $message .= $this->load->view('email_template/staff_password_reset.php', $email_var, true);
                $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
                $email_array = array(
                    'mail_type' => 'html',
                    'from_mail_id' => $this->config->item('smtp_user'),
                    'from_mail_name' => 'ARK Team',
                    'to_mail_id' => $email_id,
                    'cc_mail_id' => '',
                    'subject_message' => 'Password Reset by Admin',
                    'body_messages' => $message
                );
                $email_send = common_email_send($email_array);

                $this->session->set_flashdata('success', 'Password has been reset successfully, Please check your Email.');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
            }
        }
        redirect('admin/inventory/vendors');
    }

    public function review_vendor_account($id) {
        try {
            $user_id = base64_decode($id);

            $this->db->select('u.*');
            $this->db->from(TBL_USERS . ' as u');
            $this->db->group_start();
            $this->db->where('u.id', $user_id);
            $this->db->group_end();
            $this->db->where(array(
                'u.is_delete' => 0,
            ));
            $res = $this->db->get();
            $data = $res->row_array();

            if (!empty($data)) {
                if ($data['status'] != 'active' || $data['is_delete'] == 1) {
                    $this->session->set_flashdata('error', 'User no longer active. Please check again.');
                    redirect('admin/inventory/vendors');
                }

                $ssn_data = array();
                $ssn_data['v_user_id'] = $data['id'];
                $ssn_data['v_first_name'] = $data['first_name'];
                $ssn_data['v_last_name'] = $data['last_name'];
                $ssn_data['v_username'] = $data['username'];
                $ssn_data['v_user_role'] = $data['user_role'];
                $ssn_data['v_email_id'] = $data['email_id'];
                $ssn_data['v_phone'] = $data['contact_number'];
                $ssn_data['vendor_logged_in'] = 1;
                $this->session->set_userdata($ssn_data);

                $this->session->set_flashdata('success', 'You have successfully logged in.');
                redirect(site_url('/vendor/home'));
            } else {
                $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
                redirect(site_url('admin/inventory/vendors'));
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
            redirect(site_url('admin/inventory/vendors'));
        }
    }

    public function change_vendor_status() {
        $is_active = $this->input->post('is_active');
        $vendor_id = $this->input->post('vendor_id');

        $is_updated = $this->inventory_model->insert_update('update', TBL_VENDORS, array('is_active' => $is_active), array('id' => $vendor_id));

        if ($is_updated) {
            $is_result = true;
        } else {
            $is_result = false;
        }

        echo $is_result;
        exit;
    }

    public function generat_api_token($vendor_id) {
        try {
            if (!empty($vendor_id)) {
                $vendor_id = base64_decode($vendor_id);

                $this->db->select('ap.*');
                $this->db->from(TBL_VENDORS . ' as v');
                $this->db->join(TBL_API_TOKENS . ' AS ap', 'v.id = ap.vendor_id');
                $this->db->where('v.id', $vendor_id);
                $this->db->where('v.is_delete', 0);
                $res = $this->db->get();
                $data = $res->row_array();

                $token = md5(uniqid($vendor_id, true));
                $token_array = ['token' => $token];

                if (!empty($data)) {
                    $is_updated = $this->inventory_model->insert_update('update', TBL_API_TOKENS, $token_array, array('vendor_id' => $vendor_id));

                    if ($is_updated) {
                        $this->session->set_flashdata('success', 'Vendor API token has been updated successfully.');
                    } else {
                        $this->session->set_flashdata('error', 'Vendor API Token has not been updated.');
                    }
                } else {
                    $token_array['vendor_id'] = $vendor_id;
                    $is_generated = $this->users_model->insert_update('insert', TBL_API_TOKENS, $token_array);

                    if ($is_generated) {
                        $this->session->set_flashdata('success', 'Vendor API Token has been created successfully.');
                    } else {
                        $this->session->set_flashdata('error', 'Vendor API Token has not been created.');
                    }
                }
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
        }
        redirect(site_url('admin/inventory/vendors'));
    }

}

/* End of file Inventory.php */
/* Location: ./application/controllers/Inventory.php */