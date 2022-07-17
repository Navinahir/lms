<?php

defined('BASEPATH') OR exit('No direct script access allowed');


require_once FCPATH. 'Library/PDF/autoload.php';

require_once FCPATH . '/Library/QuickBook/autoload.php';
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Data\IPPReferenceType;
use QuickBooksOnline\API\Data\IPPAttachableRef;
use QuickBooksOnline\API\Data\IPPAttachable;
use Mpdf\Mpdf;

class Items extends MY_Controller {

    public function __construct() {
        global $data_service;
        parent::__construct();
        $this->load->model(array('admin/inventory_model', 'admin/product_model','admin/dashboard_model'));
        $data_service = $this->data_service();
        //$this->load->library('m_pdf');
    }

    /*********************************************************
      Manage Items
     **********************************************************/

    /**
     * Display All items 
     * @param --
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function display_items() {
        $data['title'] = 'List of Items';
        $this->template->load('default_front', 'front/items/items_display', $data);
    }

    /**
     * Display All items 
     * @param --
     * @return --
     * @author JJP [Last Edited : 14/10/2020]
     */
    public function items_trash() {
        $data['title'] = 'Items trash';
        $this->template->load('default_front', 'front/items/items_trash', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_items_data() {
        $final['recordsTotal'] = $this->inventory_model->get_user_items_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->inventory_model->get_user_items_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $items[$key]['quickbooks'] = $this->get_item_quickbook_status($val['id']);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 14/10/2020]
     */
    public function get_items_data_trash() {
        $final['recordsTotal'] = $this->inventory_model->get_user_items_data_trash('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->inventory_model->get_user_items_data_trash('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
            $items[$key]['quickbooks'] = $this->get_item_quickbook_status($val['id']);
            $items[$key]['start_date'] = date('Y-m-d', strtotime($val['modified_date']));
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Recoverd trash item 
     * @param $id - String
     * @return --
     * @author JJP [Last Edited : 13/10/2020]
     */
    public function trash_recover_item($id = '') {
        $record_id = base64_decode($id);
        $this->inventory_model->insert_update('update', TBL_USER_ITEMS, array('is_delete' => 0), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Item has been recoverd successfully.');
        redirect('items');
    }

    /**
     * Delete selected estimate
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 09/10/2020]
     */
    public function delete_multiple(){
        $delete_id_array = $this->input->post('delete_id');
        foreach ($delete_id_array as $key => $value) {
            $dataArr = array(
                'is_delete' => 2
            );
            $where = array(
                'id' => base64_decode($value) 
            );
            $this->session->set_flashdata('success', 'Item has been deleted successfully.');
            $data = $this->inventory_model->insert_update('update',TBL_USER_ITEMS,$dataArr,$where);
            echo json_encode($data);
        }
    }
    
    /**
     * Recover selected items
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 09/10/2020]
     */
    public function recover_multiple(){
        $recover_id_array = $this->input->post('recover_id');
        foreach ($recover_id_array as $key => $value) {
            $dataArr = array(
                'is_delete' => 0
            );
            $where = array(
                'id' => base64_decode($value) 
            );
            $this->session->set_flashdata('success', 'Item has been recoverd successfully.');
            $data = $this->inventory_model->insert_update('update',TBL_USER_ITEMS,$dataArr,$where);
            echo json_encode($data);
        }
    }

    /**
     * Delete trash item
     * @param $id - String
     * @return --
     * @author JJP [Last Edited : 03/02/2018]
     */
    public function trash_delete_item($id = '') {
        $record_id = base64_decode($id);
        $this->inventory_model->insert_update('update', TBL_USER_ITEMS, array('is_delete' => 2), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Item has been deleted successfully.');
        redirect('items/items_trash');
    }


    /**
     * Edit Items
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function edit_items($id = null) {
        $data['is_allow_on_edit'] = 1;
        $i_id = ($this->input->get('id')) ? $this->input->get('id', TRUE) : '';
        $dept_Arr = $this->inventory_model->get_all_details(TBL_DEPARTMENTS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $vendor_Arr = $this->inventory_model->get_all_details(TBL_VENDORS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $vendors = $this->inventory_model->get_all_details(TBL_VENDORS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $data = array(
            'title' => 'Add Item',
            'dept_Arr' => $dept_Arr,
            'vendor_Arr' => $vendor_Arr,
            'vendors' => $vendors,
        );

        if (is_null($id)) {
            $data['companyArr'] = $this->inventory_model->get_all_details(TBL_COMPANY, array('is_delete' => 0, 'status' => 'active'))->result_array();
            $data['is_allow_on_edit'] = 0;
        }

        if (!is_null($id)) {
            $record_id = base64_decode($id);
            $data['title'] = 'Edit Item';
            $data['dataArr'] = $this->inventory_model->get_all_details(TBL_USER_ITEMS, array('id' => $record_id, 'is_delete' => 0))->row_array();
            $data['dataArr']['global'] = $this->inventory_model->get_all_details(TBL_ITEMS, array('id' => $data['dataArr']['referred_item_id']))->row_array();
            $data['companyArr'] = $companyArr = $this->product_model->get_all_details(TBL_COMPANY, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
            $data['yearArr'] = $yearArr = $this->product_model->get_all_details(TBL_YEAR, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
            $data['modelArr'] = $this->product_model->get_all_details(TBL_MODEL, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
            $data['trans_Arr'] = $this->inventory_model->get_user_trans_items($record_id);
            $data['vendor_list'] = $this->inventory_model->get_vendor_list();
        }
        if ($i_id != '') {
            $item_id = base64_decode($i_id);
            $data['itemArr'] = $this->inventory_model->get_all_details(TBL_ITEMS, array('id' => $item_id, 'is_delete' => 0))->row_array();
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('txt_item_part', 'Item Part', 'trim|required');
            if ($this->form_validation->run() == true) {
                $flag = 0;
                $item_image = '';

                if (isset($_FILES['image_link']['name']) && $_FILES['image_link']['name'] != '') {
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
                        redirect('items');
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
                    $updateArr = array(
                        'business_user_id' => checkUserLogin('C'),
                        'referred_item_id' => ($this->input->post('txt_referred_item_id')) ? htmlentities($this->input->post('txt_referred_item_id')) : null,
                        'part_no' => htmlentities($this->input->post('txt_item_part')),
                        'description' => htmlentities($this->input->post('txt_item_description')),
                        'department_id' => htmlentities($this->input->post('txt_department')),
                        'vendor_id' => htmlentities($this->input->post('txt_pref_vendor')),
                        'unit_cost' => htmlentities($this->input->post('txt_unit_cost')),
                        'retail_price' => htmlentities($this->input->post('txt_retail_price')),
                        'qty_on_hand' => htmlentities($this->input->post('txt_in_stock')),
                        'manufacturer' => htmlentities($this->input->post('txt_manufacturer')),
                        'internal_part_no' => htmlentities($this->input->post('hidden_internal_part')),
                        'part_location' => htmlentities($this->input->post('part_location')),
                        'image' => ($this->input->post('item_image_hidden') != '') ? $this->input->post('item_image_hidden') : null,
                        'upc_barcode' => htmlentities($this->input->post('txt_upc_barcode')),
                        'low_inventory_limit' => htmlentities($this->input->post('txt_low_inventory')),
                    );
                    
                    if ($item_image != '') {
                        $updateArr['image'] = $item_image;
                    }

                    // get_low_inventory Nofification
                    if($this->uri->segment(3) != "")
                    {
                        // echo "1";
                        // die();
                        $is_exists = $this->inventory_model->get_result(TBL_ITEM_LOCATION_DETAILS, ['business_user_id' => checkUserLogin('C'), 'item_id' => $record_id , 'is_deleted' => 0], null, 1);
                        $notification_exists = $this->inventory_model->get_result(TBL_NOTIFICATION, ['business_user_id' => checkUserLogin('C'), 'enum_id' => $record_id]);
                        $low_inventroy = $this->get_low_inventory($is_exists['item_id'], 1);
                        
                        if($updateArr['low_inventory_limit'] != "")
                        {
                            if($low_inventroy['total_quantity'] <= $updateArr['low_inventory_limit'])
                            {
                                if(!empty($notification_exists))
                                {
                                    // echo "If 1";
                                    $estimate_id = $this->inventory_model->insert_update('update', TBL_NOTIFICATION, array('is_delete' => '0'),array('enum_id' =>$is_exists['item_id'], 'estimate_invoice' => '2'));
                                    // qry();
                                    $this->session->set_userdata(['item_notification' => 1]);
                                }
                                else
                                {
                                    // echo "If 2";
                                    $notification['business_user_id'] = checkUserLogin('C');
                                    $notification['enum_id'] = $low_inventroy['item_id'];
                                    $notification['estimate_invoice'] = '2';
                                    $estimate_id = $this->inventory_model->insert_update('insert', TBL_NOTIFICATION, $notification);
                                    $this->session->set_userdata(['item_notification' => 1]);    
                                }
                                // die();   
                            } 
                            else 
                            {
                                if(!empty($notification_exists))
                                {
                                    // echo "Else 1";
                                    $estimate_id = $this->inventory_model->insert_update('update', TBL_NOTIFICATION, array('is_delete' => '1'),array('enum_id' =>$is_exists['item_id'], 'estimate_invoice' => '2'));
                                    // qry();
                                    $this->session->set_userdata(['item_notification' => 1]);
                                }
                                else
                                {
                                    // echo "Else 2";
                                    $notification['business_user_id'] = checkUserLogin('C');
                                    $notification['enum_id'] = $low_inventroy['item_id'];
                                    $notification['estimate_invoice'] = '2';
                                    $estimate_id = $this->inventory_model->insert_update('insert', TBL_NOTIFICATION, $notification);
                                    $this->session->set_userdata(['item_notification' => 1]);    
                                }
                                // die();
                            }
                        } else {
                            // Remove notification if lowet limit is empty
                            if($updateArr['low_inventory_limit'] == "")
                            {
                                if(!empty($notification_exists))
                                {
                                    $estimate_id = $this->inventory_model->insert_update('update', TBL_NOTIFICATION, array('is_delete' => '1'),array('enum_id' =>$is_exists['item_id'], 'estimate_invoice' => '2'));
                                    $this->session->set_userdata(['item_notification' => 1]);
                                }
                            }
                        }
                    } else {
                        if($updateArr['low_inventory_limit'] != "")
                        {
                            if($updateArr['qty_on_hand'] <= $updateArr['low_inventory_limit'])
                            { 
                                $this->db->select("id");
                                $this->db->from(TBL_USER_ITEMS);
                                $this->db->limit(1);
                                $this->db->order_by('id',"DESC");
                                $query = $this->db->get();
                                $result = $query->row_array();
                                $enum_id = $result['id'] + 1;
                                
                                $notification['business_user_id'] = checkUserLogin('C');
                                $notification['enum_id'] = $enum_id;
                                $notification['estimate_invoice'] = '2';
                                $estimate_id = $this->inventory_model->insert_update('insert', TBL_NOTIFICATION, $notification);
                                $this->session->set_userdata(['item_notification' => 1]);
                            }
                        }
                    }

                    if (!is_null($id)) {
                        $updateArr['last_modified_by'] = checkUserLogin('I');
                        $updateArr['modified_date'] = date('Y-m-d h:i:s');
                        $locationArr = [
                            // 'quantity' => $updateArr['qty_on_hand'],
                            'modified_date' => date('Y-m-d H:i:s')
                        ];

                        if (!empty($data['dataArr']) && empty($data['dataArr']['user_item_qr_code'])) {
                            $updateArr['user_item_qr_code'] = $this->generat_item_qr_code($this->input->post('txt_item_part'));
                        }

                        if ($this->input->post('txt_item_part') != $data['dataArr']['part_no']) {
                            unlink("./assets/users_qr_codes/" . $data['dataArr']['user_item_qr_code']);
                            $updateArr['user_item_qr_code'] = $this->generat_item_qr_code($this->input->post('txt_item_part'));
                        }
                        $this->inventory_model->insert_update('update', TBL_USER_ITEMS, $updateArr, array('id' => $record_id));
                        $this->inventory_model->insert_update('update', TBL_ITEM_LOCATION_DETAILS, $locationArr, array('item_id' => $record_id));

                        if (isset($_SESSION['sessionAccessToken'])) {
                            $session_accesToken = $_SESSION['sessionAccessToken'];
                            
                            $item_quickbook_details = $this->inventory_model->get_all_details(TBL_QUICKBOOK_ITEMS, ['item_id' => $record_id,'realmId' => $session_accesToken->getRealmID()])->row_array();

                            if($item_quickbook_details['quickbook_id'] != '' || $item_quickbook_details['quickbook_id'] != null)
                            {
                                $this->add_edit_item_quickbook($id, $flag = "update");
                            }
                        }


                        $allItems = array_column($data['trans_Arr'], 'id');
                        $insertArr['modified_date'] = date('Y-m-d h:i:s');
                        $make_array = $this->input->post('txt_make_name');
                        if (!empty($make_array)) {
                            if ($this->input->post('trans_item_id')) {
                                foreach ($this->input->post('trans_item_id') as $k => $v):
                                    if ($v != '') {
                                        if (($key = array_search($v, $allItems)) !== false) {
                                            unset($allItems[$key]);
                                        }
                                        $transponder_id = $this->product_model->get_all_details(TBL_TRANSPONDER, array('is_delete' => 0, 'make_id' => $make_array[$k], 'model_id' => $this->input->post('txt_model_name')[$k], 'year_id' => $this->input->post('txt_year_name')[$k]))->row_array()['id'];
                                        $edit_t_items[] = [
                                            'id' => $v,
                                            'transponder_id' => $transponder_id,
                                            'items_id' => $record_id
                                        ];
                                        unset($make_array[$k]);
                                    }
                                endforeach;
                            }
                            foreach ($make_array as $k => $v):
                                if ($v != '') {
                                    $transponder_id = $this->product_model->get_all_details(TBL_TRANSPONDER, array('is_delete' => 0, 'make_id' => $v, 'model_id' => $this->input->post('txt_model_name')[$k], 'year_id' => $this->input->post('txt_year_name')[$k]))->row_array()['id'];
                                    if ($transponder_id != ''):
                                        $t_items[] = [
                                            'transponder_id' => $transponder_id,
                                            'items_id' => $record_id
                                        ];
                                    else:
                                        $this->session->set_flashdata('error', 'Something went wrong for MAKE->MODEL->YEAR.');
                                    endif;
                                }
                            endforeach;
                            if (isset($edit_t_items) && !empty($edit_t_items)):
                                $this->inventory_model->batch_insert_update('update', TBL_TRANSPONDER_USER_ITEMS, $edit_t_items, 'id');
                            endif;
                            if (isset($t_items) && !empty($t_items)):
                                $this->inventory_model->batch_insert_update('insert', TBL_TRANSPONDER_USER_ITEMS, $t_items);
                            endif;
                            if (isset($allItems) && !empty($allItems)):
                                $this->db->where_in('id', $allItems);
                                $this->db->delete(TBL_TRANSPONDER_USER_ITEMS);
                            endif;
                        }
                        $this->session->set_flashdata('success', 'Item has been updated successfully.');
                    } else {
                        $updateArr['global_part_no'] = ($this->input->post('txt_global_part_no')) ? htmlentities($this->input->post('txt_global_part_no')) : null;
                        $updateArr['created_date'] = date('Y-m-d h:i:s');
                        $updateArr['added_by'] = checkUserLogin('I');
                        $updateArr['user_item_qr_code'] = $this->generat_item_qr_code($this->input->post('txt_item_part'));
                        $inventory_id = $this->inventory_model->insert_update('insert', TBL_USER_ITEMS, $updateArr);
                        $locArr = $this->inventory_model->get_all_details(TBL_LOCATIONS, array('business_user_id' => checkUserLogin('C'), 'is_default' => 1, 'is_deleted' => 0, 'is_active' => 1))->row_array();
                        if (!is_null($locArr)):
                            $locationArr = [
                                'business_user_id' => checkUserLogin('C'),
                                'item_id' => $inventory_id,
                                'location_id' => $locArr['id'],
                                'quantity' => $updateArr['qty_on_hand'],
                                'last_modified_by' => checkUserLogin('I'),
                                'created_date' => date('Y-m-d H:i:s')
                            ];
                            $this->inventory_model->insert_update('insert', TBL_ITEM_LOCATION_DETAILS, $locationArr);
                        endif;
                        if($inventory_id)
                        {
                            if (isset($_SESSION['sessionAccessToken'])) 
                            {
                                $inventory_id = base64_encode($inventory_id);
                                $this->add_edit_item_quickbook($inventory_id, $existing = 1);
                            }
                        }
                        $this->session->set_flashdata('success', 'Item has been inserted successfully.');
                    }
                    redirect('items');
                }
            }
        }
        if (isset($data['dataArr']) && is_null($data['dataArr']['global_part_no'])) {
            if(!is_null($id)) {
                $record_id = base64_decode($id);
                $data['dataArr']['items_quantity'] = $this->inventory_model->get_user_items_quantity_data($record_id);
                $this->template->load('default_front', 'front/items/items_non_global_add', $data);
            } else {
                $this->template->load('default_front', 'front/items/items_non_global_add', $data);
            }
        } else {
            if(!is_null($id)) {
                $record_id = base64_decode($id);
                $data['dataArr']['items_quantity'] = $this->inventory_model->get_user_items_quantity_data($record_id);
                $this->template->load('default_front', 'front/items/items_add', $data);
            } else {
                $this->template->load('default_front', 'front/items/items_add', $data);
            }
        }
    }

    /**
     * Add Non Global Items
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function add_non_global($id = null) {
        $dept_Arr = $this->inventory_model->get_all_details(TBL_DEPARTMENTS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $vendor_Arr = $this->inventory_model->get_all_details(TBL_VENDORS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $data = array(
            'title' => 'Add Non Global Item',
            'dept_Arr' => $dept_Arr,
            'vendor_Arr' => $vendor_Arr,
        );
        $data['vendors'] = $this->inventory_model->get_all_details(TBL_VENDORS, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'asc')))->result_array();
        $data['vendor_list'] = $this->inventory_model->get_vendor_list();
        $data['companyArr'] = $companyArr = $this->product_model->get_all_details(TBL_COMPANY, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
        $data['yearArr'] = $yearArr = $this->product_model->get_all_details(TBL_YEAR, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
        $data['modelArr'] = $this->product_model->get_all_details(TBL_MODEL, array('is_delete' => 0), array(array('field' => 'name', 'type' => 'ASC')))->result_array();
        if (!is_null($id)) {
            $record_id = base64_decode($id);
            $data['title'] = 'Edit Add Non Global Item';
            $data['dataArr'] = $this->inventory_model->get_all_details(TBL_USER_ITEMS, array('id' => $record_id, 'is_delete' => 0))->row_array();
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('txt_item_part', 'Item Part', 'trim|required');
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
                           redirect('items/add_non_global');
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
                        'business_user_id' => checkUserLogin('C'),
                        'part_no' => htmlentities($this->input->post('txt_item_part')),
                        'description' => htmlentities($this->input->post('txt_item_description')),
                        'department_id' => htmlentities($this->input->post('txt_department')),
                        'vendor_id' => htmlentities($this->input->post('txt_pref_vendor')),
                        'unit_cost' => htmlentities($this->input->post('txt_unit_cost')),
                        'retail_price' => htmlentities($this->input->post('txt_retail_price')),
                        'qty_on_hand' => htmlentities($this->input->post('txt_in_stock')),
                        'manufacturer' => htmlentities($this->input->post('txt_manufacturer')),
                        'internal_part_no' => htmlentities($this->input->post('hidden_internal_part')),
                        'part_location' => htmlentities($this->input->post('part_location')),
                        'upc_barcode' => htmlentities($this->input->post('txt_upc_barcode')),
                        'low_inventory_limit' => htmlentities($this->input->post('txt_low_inventory')),
                        'image' => ($this->input->post('item_image_hidden') != '') ? $this->input->post('item_image_hidden') : null,
                    );

                    // Manage low inventory notification
                    if($updateArr['low_inventory_limit'] != "")
                    {
                        if($updateArr['qty_on_hand'] <= $updateArr['low_inventory_limit'])
                        {
                            $this->db->select("id");
                            $this->db->from(TBL_USER_ITEMS);
                            $this->db->limit(1);
                            $this->db->order_by('id',"DESC");
                            $query = $this->db->get();
                            $result = $query->row_array();
                            $enum_id = $result['id'] + 1;
                            
                            $notification['business_user_id'] = checkUserLogin('C');
                            $notification['enum_id'] = $enum_id;
                            $notification['estimate_invoice'] = '2';
                            $estimate_id = $this->inventory_model->insert_update('insert', TBL_NOTIFICATION, $notification);
                            $this->session->set_userdata(['item_notification' => 1]);
                        }
                    }

                    if ($item_image != '') {
                        $updateArr['image'] = $item_image;
                    }
                    else 
                    {
                        $updateArr['image'] = htmlentities($this->input->post('hidden_image_name'));
                    }
                    if (!is_null($id)) {
                        $updateArr['last_modified_by'] = checkUserLogin('I');
                        $updateArr['modified_date'] = date('Y-m-d h:i:s');
                        $locationArr = [
                            'quantity' => $updateArr['qty_on_hand'],
                            'modified_date' => date('Y-m-d H:i:s')
                        ];

                        if (!empty($data['dataArr']) && empty($data['dataArr']['user_item_qr_code'])) {
                            $updateArr['user_item_qr_code'] = $this->generat_item_qr_code($this->input->post('txt_item_part'));
                        }

                        if ($this->input->post('txt_item_part') != $data['dataArr']['part_no']) {
                            unlink("./assets/users_qr_codes/" . $data['dataArr']['user_item_qr_code']);
                            $updateArr['user_item_qr_code'] = $this->generat_item_qr_code($this->input->post('txt_item_part'));
                        }

                        $this->inventory_model->insert_update('update', TBL_USER_ITEMS, $updateArr, array('id' => $record_id));

                        $this->inventory_model->insert_update('update', TBL_ITEM_LOCATION_DETAILS, $locationArr, array('item_id' => $record_id));
                        $this->session->set_flashdata('success', 'Item has been updated successfully.');
                    } else {
                        $updateArr['created_date'] = date('Y-m-d h:i:s');
                        $updateArr['added_by'] = checkUserLogin('I');
                        $updateArr['user_item_qr_code'] = $this->generat_item_qr_code($this->input->post('txt_item_part'));
                        $inventory_id = $this->inventory_model->insert_update('insert', TBL_USER_ITEMS, $updateArr);  
                        if ($inventory_id):
                            foreach ($this->input->post('txt_make_name') as $k => $v):
                                if ($v != '') {
                                    $transponder_id = $this->product_model->get_all_details(TBL_TRANSPONDER, array('is_delete' => 0, 'make_id' => $v, 'model_id' => $this->input->post('txt_model_name')[$k], 'year_id' => $this->input->post('txt_year_name')[$k]))->row_array()['id'];
                                    if ($transponder_id != '') {
                                        $t_items[] = [
                                            'transponder_id' => $transponder_id,
                                            'items_id' => $inventory_id
                                        ];
                                    } else {
                                        $this->session->set_flashdata('error', 'Something went wrong for MAKE->MODEL->YEAR.');
                                    }
                                }
                            endforeach;
                            if (isset($t_items) && !empty($t_items)):
                                $this->inventory_model->batch_insert_update('insert', TBL_TRANSPONDER_USER_ITEMS, $t_items);
                            endif;
                        endif;
                        $locArr = $this->inventory_model->get_all_details(TBL_LOCATIONS, array('business_user_id' => checkUserLogin('C'), 'is_default' => 1, 'is_deleted' => 0, 'is_active' => 1))->row_array();
                        if (!is_null($locArr)):
                            $locationArr = [
                                'business_user_id' => checkUserLogin('C'),
                                'item_id' => $inventory_id,
                                'location_id' => $locArr['id'],
                                'quantity' => $updateArr['qty_on_hand'],
                                'last_modified_by' => checkUserLogin('I'),
                                'created_date' => date('Y-m-d H:i:s')
                            ];
                            $this->inventory_model->insert_update('insert', TBL_ITEM_LOCATION_DETAILS, $locationArr);
                        endif;
                        $inventory_id = base64_encode($inventory_id);
                        if (isset($_SESSION['sessionAccessToken'])) {
                            $this->add_edit_item_quickbook($inventory_id, $existing=1);
                        }
                        $this->session->set_flashdata('success', 'Item has been inserted successfully.');
                    }
                    redirect('items');
                }
            }
        }
        $this->template->load('default_front', 'front/items/items_non_global_add', $data);
    }

    /**
     * Delete Items
     * @param $id - String
     * @return --
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function delete_items($id = '') {
        $record_id = base64_decode($id);
        $this->inventory_model->insert_update('update', TBL_USER_ITEMS, array('is_delete' => 1), array('id' => $record_id));

        // Delete notification
        $this->inventory_model->insert_update('update', TBL_NOTIFICATION, array('is_delete' => 1), array('enum_id' => $record_id));
        
        if (isset($_SESSION['sessionAccessToken'])) 
        { 
            global $data_service;
            $data_service['dataService']->throwExceptionOnError(true);
            $where = array(
                'item_id' => $record_id, 
                'realmId' =>$data_service['accessToken']->getRealmID()
            );
            $customer_quickbook_details = $this->inventory_model->get_all_details(TBL_QUICKBOOK_ITEMS, $where)->row_array();
            if($customer_quickbook_details['quickbook_id'] != '' || $customer_quickbook_details['quickbook_id'] != null)
            {
                $customer = $data_service['dataService']->FindbyId('Item', $customer_quickbook_details['quickbook_id']);
                $theResourceObj = Item::update($customer, [
                    "Active" => false
                ]);
                $resultingObj = $data_service['dataService']->Update($theResourceObj);
                $this->inventory_model->insert_update('update', TBL_QUICKBOOK_ITEMS, array('is_deleted' => 1), $where);
            }
        }
        $this->session->set_flashdata('success', 'Item\'s data has been deleted successfully.');
        redirect('items');
    }

    /**
     * Get Item's data by its ID
     * @param --
     * @return HTML data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_item_data_ajax_by_part_id() {
        if ($this->input->post()) {
            $part_id = $this->input->post('id');
            $itsfor = ($this->input->post('itsfor')) ? $this->input->post('itsfor') : '';
//            $vendor = ($this->input->post('vendor')) ? $this->input->post('vendor') : '';
            $this->db->select('i.*,d.name as dept_name,v1.name as v1_name');
            $this->db->from(TBL_ITEMS . ' AS i');
            $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
            $this->db->join(TBL_VENDORS . ' AS v1', 'i.preferred_vendor=v1.id', 'left');
            $this->db->where('i.is_delete', 0);
            if ($itsfor != '') {
                $this->db->where('i.id', $part_id);
                $result = $this->db->get();
                $data['viewArr'] = $result->row_array();
            } else {
                $this->db->where('i.part_no', $part_id);
                $result = $this->db->get();
                $data['viewArr'] = $result->result_array();
            }

            if (!empty($data['viewArr'])) {
                $item_ids = array_column($data['viewArr'], 'id');

                $this->db->select('c.name as company,m.name as model,y.name as year');
                $this->db->from(TBL_ITEMS . ' AS i');
                $this->db->join(TBL_VENDORS . ' AS v1', 'i.preferred_vendor=v1.id', 'left');
                $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
                $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
                $this->db->join(TBL_COMPANY . ' as c', 'c.id=t.make_id and c.status="active" and c.is_delete=0', 'left');
                $this->db->join(TBL_MODEL . ' as m', 'm.id=t.model_id and m.status="active" and m.is_delete=0', 'left');
                $this->db->join(TBL_YEAR . ' as y', 'y.id=t.year_id and y.is_delete=0', 'left');

                if (!empty($item_ids) && is_array($item_ids)) {
                    $this->db->where_in('i.id', $item_ids);
                } else {
                    $this->db->where('i.id', $data['viewArr']['id']);
                }
                $this->db->like('i.part_no', $this->input->get('term'));
                $parts_result = $this->db->get();
                $data['viewArr']['partArr'] = $parts_result->result_array();

                $res = ['code' => 200, 'data' => $data['viewArr'], 'count' => count($data['viewArr'])];
            } else {
                $res = ['code' => 404, 'data' => null];
            }
            echo json_encode($res);
            die;
        }
    }

    /**
     * Get Item's data by its ID
     * @param --
     * @return HTML data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_item_data_ajax_by_id() {
        $where = '';
        $join = 'left';
        $part_id = base64_decode($this->input->post('id'));
        $this->db->select('i.*,mi.item_link,mi.item_qr_code, mi.image as global_item_image,d.name as dept_name,v1.name as v1_name,it.total_quantity,i.manufacturer,t.id as transponder_id,GROUP_CONCAT(CONCAT(c.name, " ", `m`.`name`, " ", y.name)) as compatibility, i.user_item_qr_code');
        $this->db->from(TBL_USER_ITEMS . ' AS i');
        if ($this->input->post('part_type'))
            $this->db->join(TBL_TRANSPONDER_USER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
        else
            $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.referred_item_id=ti.items_id', 'left');
        $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_COMPANY . ' as c', 'c.id=t.make_id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 'm.id=t.model_id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 'y.id=t.year_id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.vendor_id=v1.id', 'left');
        $this->db->join(TBL_ITEMS . ' AS mi', 'mi.id=i.referred_item_id', 'left');
        $this->db->where('i.id', $part_id);
        $result = $this->db->get();
        $data['viewArr'] = $result->row_array();

        if (!empty($data['viewArr'])) {
            if ($this->input->post('make_id') && $this->input->post('model_id') && $this->input->post('year_id')) {
                $where = 'make_id = ' . $this->input->post('make_id') . ' AND model_id = ' . $this->input->post('model_id') . ' AND year_id = ' . $this->input->post('year_id');
                $this->db->select('t.id');
                $this->db->where($where);
                $res = $this->db->get(TBL_TRANSPONDER . ' as t')->row_array();
                $data['viewArr']['transponder_id'] = $res['id'];
            }
            $locations = $this->inventory_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array();
            $this->db->select('il.*');
            $this->db->where(['il.item_id' => $part_id, 'il.is_deleted' => 0]);
            $location_qty = $this->db->get(TBL_ITEM_LOCATION_DETAILS . ' il')->result_array();
            foreach ($locations as $k => $l) {
                if (($key = (array_search($l['id'], array_column($location_qty, 'location_id')))) !== false) {
                    $l['location_quantity'] = $location_qty[$key]['quantity'];
                } else {
                    $l['location_quantity'] = 0;
                }
                $locations[$k] = $l;
            }
            $data['viewArr']['locations'] = $locations;
        }


        $this->db->select('c.name as company,m.name as model,y.name as year');
        $this->db->from(TBL_USER_ITEMS . ' AS i');
        if ($this->input->post('part_type'))
            $this->db->join(TBL_TRANSPONDER_USER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
        else
            $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.referred_item_id=ti.items_id', 'left');
        $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_COMPANY . ' as c', 'c.id=t.make_id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 'm.id=t.model_id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 'y.id=t.year_id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.vendor_id=v1.id', 'left');
        $this->db->join(TBL_ITEMS . ' AS mi', 'mi.id=i.referred_item_id', 'left');
        $this->db->where('i.id', $part_id);
        $parts_result = $this->db->get();
        $data['partArr'] = $parts_result->result_array();

        return $this->load->view('front/partial_view/view_search_data', $data);
        die;
    }

    /**
     * Get Item's data by its ID
     * @param --
     * @return HTML data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_trash_item_data_ajax_by_id() {
        $where = '';
        $join = 'left';
        $part_id = base64_decode($this->input->post('id'));
        $this->db->select('i.*,mi.item_link,mi.item_qr_code, mi.image as global_item_image,d.name as dept_name,v1.name as v1_name,it.total_quantity,i.manufacturer,t.id as transponder_id,GROUP_CONCAT(CONCAT(c.name, " ", `m`.`name`, " ", y.name)) as compatibility, i.user_item_qr_code');
        $this->db->from(TBL_USER_ITEMS . ' AS i');
        if ($this->input->post('part_type'))
            $this->db->join(TBL_TRANSPONDER_USER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
        else
            $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.referred_item_id=ti.items_id', 'left');
        $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_COMPANY . ' as c', 'c.id=t.make_id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 'm.id=t.model_id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 'y.id=t.year_id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.vendor_id=v1.id', 'left');
        $this->db->join(TBL_ITEMS . ' AS mi', 'mi.id=i.referred_item_id', 'left');
        $this->db->where('i.id', $part_id);
        $result = $this->db->get();
        $data['viewArr'] = $result->row_array();

        if (!empty($data['viewArr'])) {
            if ($this->input->post('make_id') && $this->input->post('model_id') && $this->input->post('year_id')) {
                $where = 'make_id = ' . $this->input->post('make_id') . ' AND model_id = ' . $this->input->post('model_id') . ' AND year_id = ' . $this->input->post('year_id');
                $this->db->select('t.id');
                $this->db->where($where);
                $res = $this->db->get(TBL_TRANSPONDER . ' as t')->row_array();
                $data['viewArr']['transponder_id'] = $res['id'];
            }
            $locations = $this->inventory_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array();
            $this->db->select('il.*');
            $this->db->where(['il.item_id' => $part_id, 'il.is_deleted' => 0]);
            $location_qty = $this->db->get(TBL_ITEM_LOCATION_DETAILS . ' il')->result_array();
            foreach ($locations as $k => $l) {
                if (($key = (array_search($l['id'], array_column($location_qty, 'location_id')))) !== false) {
                    $l['location_quantity'] = $location_qty[$key]['quantity'];
                } else {
                    $l['location_quantity'] = 0;
                }
                $locations[$k] = $l;
            }
            $data['viewArr']['locations'] = $locations;
        }


        $this->db->select('c.name as company,m.name as model,y.name as year');
        $this->db->from(TBL_USER_ITEMS . ' AS i');
        if ($this->input->post('part_type'))
            $this->db->join(TBL_TRANSPONDER_USER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
        else
            $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.referred_item_id=ti.items_id', 'left');
        $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_COMPANY . ' as c', 'c.id=t.make_id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 'm.id=t.model_id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 'y.id=t.year_id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.vendor_id=v1.id', 'left');
        $this->db->join(TBL_ITEMS . ' AS mi', 'mi.id=i.referred_item_id', 'left');
        $this->db->where('i.id', $part_id);
        $parts_result = $this->db->get();
        $data['partArr'] = $parts_result->result_array();

        return $this->load->view('front/partial_view/view_search_data_trash', $data);
        die;
    }

//    public function add_location_item() {
//        $Arr = $this->inventory_model->get_all_details(TBL_USER_ITEMS, array('business_user_id' => checkUserLogin('C'), 'is_delete' => 0))->result_array();
//        foreach ($Arr as $value) {
//            $locArr = $this->inventory_model->get_all_details(TBL_LOCATIONS, array('business_user_id' => checkUserLogin('C'), 'is_default' => 1, 'is_deleted' => 0))->row_array();
//            if (!is_null($locArr)):
//                $locationArr = [
//                    'item_id' => $value['id'],
//                    'location_id' => $locArr['id'],
//                    'quantity' => $value['qty_on_hand'],
//                ];
//                $this->inventory_model->insert_update('insert', TBL_ITEM_LOCATION_DETAILS, $locationArr);
//            endif;
//        }
//    }

    /**
     * Get Item's data by its ID
     * @param --
     * @return HTML data
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_global_item_data_ajax_by_id() {
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

        //$data['viewArr'] = $this->inventory_model->get_all_details(TBL_ITEMS,array('id'=>$part_id))->row_array();
        return $this->load->view('front/partial_view/view_global_search_data', $data);
        die;
    }

    /**
     * Get Item's data by its ID
     * @param --
     * @return HTML data
     * @author JJP [Last Edited : 16/09/2019]
     */
    public function get_global_item_data_compatibility_ajax_by_id() {
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

        //$data['viewArr'] = $this->inventory_model->get_all_details(TBL_ITEMS,array('id'=>$part_id))->row_array();
        return $this->load->view('front/partial_view/view_global_search_data_compatibility', $data);
        die;
    }

    /**
     * Get Item's data by search value
     * @param --
     * @return JSOn data
     * @author HPA [Last Edited : 17/09/2018]
     */
    public function get_search_record() {
        if ($this->input->post('term')) {
            $vendor = $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();
            $vendor_list = explode(',', $vendor['vendor_id']);
            $this->db->select('i.part_no,v1.name,i.description,i.image');
            $this->db->from(TBL_ITEMS . ' AS i');
            $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
            $this->db->join(TBL_VENDORS . ' AS v1', 'i.preferred_vendor=v1.id', 'left');
            $this->db->where('i.is_delete', '0');
            $this->db->where('v1.is_active', '1');
            if($vendor['vendor_id'] != "")
            {
                $this->db->where_in('i.preferred_vendor',$vendor_list);
            }
            $this->db->like('i.part_no', $this->input->post('term'));
            $result = $this->db->get()->result_array();
            $new = [];
            if (!empty($result)) {
                foreach ($result as $k => $v) {
                    $test['txt'] = $v['part_no'] . ' (' . $v['name'] . ')'. ' (' . $v['description'] . ')';
                    $test['part_no'] = $v['part_no'];
                    $test['name'] = $v['name'];
                    $test['description'] = $v['description'];
                    $test['txt_exist'] = $v['part_no'];
                    if($v['image'] != "")
                    {   
                        if(file_exists('uploads/items/'. $v['image']))
                        {
                            $test['img'] = "uploads/items/". $v['image'];
                        } else {
                            $test['img'] = "uploads/items/no_image.jpg";
                        }
                    } else {
                        $test['img'] = "uploads/items/no_image.jpg";
                    }
                    array_push($new, $test);
                }
            }
            echo json_encode($new);
            die;
        }
    }

    /**
     * Alert if item is in inventory
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 06/11/2019]
     */
    public function items_exist(){
        if($this->session->userdata('u_business_id') != "" && $this->session->userdata('u_business_id') != 0) {   
            $u_business_id = $this->session->userdata('u_business_id');
        } else {
            $u_business_id = $this->session->userdata('u_user_id');
        }
        $exit = 0; 
        $exit_item_name = $this->input->post('item_name');
        $this->db->select('ui.part_no','ui.business_user_id');
        $this->db->from(TBL_USER_ITEMS.' AS ui');
        $this->db->where('ui.part_no', $exit_item_name);
        $this->db->where('ui.business_user_id', $u_business_id);
        $this->db->where('ui.is_delete',0);
        $query = $this->db->get(TBL_USER_ITEMS);  
        
        if($query->num_rows() > 0) {  
            $exit = 1; 
            echo $exit;
            return true;
        } else {  
            $exit = 0; 
            echo $exit;  
            return false;
        }
        die();
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
     * Get all the Model by ajax on the basis of make
     * @param --
     * @return Object (Json Format)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function print_item_label($part_no) {
        if (!empty($part_no)) {
            $part_id = base64_decode($part_no);

            $this->db->select('i.*');
            $this->db->from(TBL_USER_ITEMS . ' AS i');
            $this->db->join(TBL_ITEMS . ' AS mi', 'mi.id=i.referred_item_id', 'left');
            $this->db->where('i.id', $part_id);
            $result = $this->db->get();
            $data['itemArr'] = $result->row_array();
            $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();
            $data['pdf_size'] = $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();
            $height = "";
            $width = "";
            if($data['pdf_size']['label_size'] != "")
            {
                $height = $data['pdf_size']['label_size'][0]*27;
                $width = $data['pdf_size']['label_size'][2]*27;
            }
            // echo $height; echo $width;
            // die;

            if (!empty($data['itemArr'])) {

            if($height != "" && $height != null && $width != "" && $width != null){
                if($height == 27 && $width == 27){ $html = $this->load->view('front/items/generat_item_label_1x1',$data,true); }
                if($height == 27 && $width == 54){ $html = $this->load->view('front/items/generat_item_label_1x2',$data,true); }
                if($height == 27 && $width == 81){ $html = $this->load->view('front/items/generat_item_label_1x3',$data,true); }
                if($height == 27 && $width == 108){ $html = $this->load->view('front/items/generat_item_label_1x4',$data,true);}
                if($height == 54 && $width == 27){ $html = $this->load->view('front/items/generat_item_label_2x1',$data,true); }
                if($height == 54 && $width == 54){ $html = $this->load->view('front/items/generat_item_label_2x2',$data,true); }
                if($height == 54 && $width == 81){ $html = $this->load->view('front/items/generat_item_label_2x3',$data,true); }
                if($height == 54 && $width == 108){ $html = $this->load->view('front/items/generat_item_label_2x4',$data,true);}
                if($height == 81 && $width == 27){ $html = $this->load->view('front/items/generat_item_label_3x1',$data,true); }
                if($height == 81 && $width == 54){ $html = $this->load->view('front/items/generat_item_label_3x2',$data,true); }
                if($height == 81 && $width == 81){ $html = $this->load->view('front/items/generat_item_label_3x3',$data,true); }
                if($height == 81 && $width == 108){ $html = $this->load->view('front/items/generat_item_label_3x4',$data,true);}
            } else {
                $html = $this->load->view('front/items/generat_item_label', $data, true);
            }

            // $html = $this->load->view('front/items/generat_item_label', $data, true);

                // echo $html; exit;
                
                require_once FCPATH . 'Library/PDF/autoload.php';

                /*if ($_SERVER['HTTP_HOST'] == 'alwaysreliablekeys.com') {
                   $mpdf = new Mpdf();
                } else if ($_SERVER['HTTP_HOST'] == 'clientapp.narola.online') {
                    $mpdf = new Mpdf();
                } else {
                   $mpdf = new \mPDF();
                }*/
                
                if($height != "" && $width != "")
                {
                    $mpdf = new \Mpdf\Mpdf(['format' => [$width, $height]]);
                } else {
                    $mpdf = new \Mpdf\Mpdf(['format' => [54, 54]]);
                }

                $mpdf->SetTitle("Part Number : " . $data['itemArr']['part_no']);
                $mpdf->defaultfooterline = 0;
//                $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');

                $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/print_label.css") . '</style>';
                $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
                $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';

                $mpdf->AddPage('P', // L - landscape, P - portrait 
                        '', '', '', '', 0, // margin_left
                        0, // margin right
                        0, // margin top
                        0, // margin bottom
                        0, // margin header
                        0  // margin footer
                );
                $mpdf->WriteHTML($stylesheet, 1);
                $mpdf->WriteHTML($html, 2);
                $filename = $data['itemArr']['part_no'] . ".pdf";

                $mpdf->Output($filename, "I");
            } else {
                $this->session->set_flashdata('error', 'Item\'s data has not been found.');
                redirect('items');
            }
        } else {
            $this->session->set_flashdata('error', 'Item part number has not been found.');
            redirect('items');
        }
    }

    public function generat_item_qr_code($part_no) {
        $this->load->library('ciqrcode');

        $file_name = $part_no . '.png';

        $params['data'] = $part_no;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = str_replace('"', "", FCPATH . 'assets/users_qr_codes/"' . $file_name);
        $this->ciqrcode->generate($params);

        return $file_name;
    }


    public function get_item_quickbook_status($item_id)
    {
        if (isset($_SESSION['sessionAccessToken'])) {
            $accessToken = $_SESSION['sessionAccessToken'];
            $realmId = $accessToken->getRealmID();
            $this->db->select('*');
            $this->db->where('realmId', $realmId);
            $this->db->where('item_id', $item_id);
            $this->db->where('is_deleted', 0);
            $query = $this->db->get(TBL_QUICKBOOK_ITEMS);
            $customer_status = $query->row_array();
            if($customer_status != '')
            {
                return "0";
            }
            else
            {
                return "1";
            }
        }
    }

    /**
     * Get all the transponder details on the basis of make, model, year
     * @param --
     * @return Object (Json Format)
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_transponder_details() {
        $make_id = $this->input->post('_make_id');
        $model_id = $this->input->post('_model_id');
        $year_id = $this->input->post('_year_id');
       
        $temp = "";

        $vendor_multipul = $this->input->post('vendor_multipul');
        $vendor_multipul = $vendor_multipul[0];

        $manufacturer_multipul=$this->input->post('manufacturer_multipul');
        $manufacturer_multipul = $manufacturer_multipul[0];

        $itemsstatus_multipul=$this->input->post('itemsstatus_multipul');
        $itemsstatus_multipul = $itemsstatus_multipul[0];
        
        $condition = array(
            't.make_id' => $make_id,
            't.model_id' => $model_id,
            't.year_id' => $year_id,
        );

        if($itemsstatus_multipul != "")
        {
            if(in_array("In Stock", $itemsstatus_multipul) && in_array("Out Of Stock", $itemsstatus_multipul))
            {
                $temp = "multipul_In_Stock_Out_Of_Stock";
                $condition = array(
                    't.make_id' => $make_id,
                    't.model_id' => $model_id,
                    't.year_id' => $year_id,
                );
            } else if (in_array("In Stock", $itemsstatus_multipul)) {
                $temp = "multipul_In_Stock";
                $condition = array(
                    't.make_id' => $make_id,
                    't.model_id' => $model_id,
                    't.year_id' => $year_id,
                );
            } else {
                $temp = "multipul_Out_Of_Stock";
                $condition = array(
                    't.make_id' => $make_id,
                    't.model_id' => $model_id,
                    't.year_id' => $year_id,
                );
            }
        }
        
        if($manufacturer_multipul != "")
        {
            $temp = "manufacturer_multipul";
            $condition = array(
                't.make_id' => $make_id,
                't.model_id' => $model_id,
                't.year_id' => $year_id,
                'd.name' => $manufacturer_multipul
            );
        }
        
        if($vendor_multipul != "")
        {
            $temp = "vendor_multipul";
            $condition = array(
                't.make_id' => $make_id,
                't.model_id' => $model_id,
                't.year_id' => $year_id,
                'vg.name' => $vendor_multipul
            );
        }
        

        $search_array = [
            'business_user_id' => checkUserLogin('C'),
            'make_id' => $make_id,
            'model_id' => $model_id,
            'year_id' => $year_id,
            'created_date' => date('Y-m-d H:i:s')
        ];

        $result['vendor_list'] = $this->dashboard_model->get_vendor_list();

        $result['department_list'] = $this->dashboard_model->get_department_list();

        $this->inventory_model->insert_update('insert', TBL_RECENT_SEARCH_DETAILS, $search_array);
        $settings = $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();

        $transponder_result = $this->dashboard_model->get_user_transponder_details($condition, $settings, $temp)->row_array();
        $result['transponder_result'] = $this->dashboard_model->get_user_transponder_details($condition, $settings, $temp)->row_array();

        $result['locations'] = $this->inventory_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array();
        
        $table_body = $div_part_list = $my_part_list_Arr = $div_tool_list = $table_body2 = $div_my_part_list = '';
        if (!empty($transponder_result)) {
            $table_body = '<tbody>';
            $table_body .= '<tr><td width="40%"><b>Make</b></td><td>' . $transponder_result['make_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Model</b></td><td>' . $transponder_result['model_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Year</b></td><td>' . $transponder_result['year_name'] . '</td></tr>';
            $table_body .= '<tr><td><b>Transponder Equipped</b></td><td>' . ucfirst($transponder_result['transponder_equipped']) . '</td></tr>';
            $table_body .= '<tr><td><b>Key Type</b></td><td>' . str_replace('_', ' ', join(', ', array_map('ucfirst', explode(',', $transponder_result['key_value'])))) . '</td></tr>';
            $table_body .= '<tr><td><b>System</b></td><td>' . $transponder_result['mvp_system'] . '</td></tr>';
            $table_body .= '<tr><td><b>Pincode Required</b></td><td>' . ucfirst($transponder_result['pincode_required']) . '</td></tr>';
            $table_body .= '<tr><td><b>Pincode Reading Available</b></td><td>' . $transponder_result['pincode_reading_available'] . '</td></tr>';
            $table_body .= '<tr><td><b>Key Onboard Programming</b></td><td>' . $transponder_result['key_onboard_progaming'] . '</td></tr>';
            $table_body .= '<tr><td><b>Remote Onboard Programming</b></td><td>' . $transponder_result['remote_onboard_progaming'] . '</td></tr>';
            $table_body .= '<tr><td><b>Test Key</b></td><td>' . ((!empty($transponder_result['test_key'])) ? $transponder_result['test_key'] : '') . '</td></tr>';
            $table_body .= '<tr><td><b>IIco</b></td><td>' . $transponder_result['iico'] . '</td></tr>';
            $table_body .= '<tr><td><b>JET</b></td><td>' . ((!empty($transponder_result['jet'])) ? $transponder_result['jet'] : '') . '</td></tr>';
            $table_body .= '<tr><td><b>JMA</b></td><td>' . $transponder_result['jma'] . '</td></tr>';
            $table_body .= '<tr><td><b>Keyline</b></td><td>' . $transponder_result['keyline'] . '</td></tr>';
            $table_body .= '<tr><td><b>Strattec Non-Remote Key</b></td><td>' . $transponder_result['strattec_non_remote_key'] . '</td></tr>';
            $table_body .= '</tbody>';
            $table_body2 = '<tbody>';
            $table_body2 .= '<tr><td width="40%"><b>Strattec Remote Key</b></td><td>' . $transponder_result['strattec_remote_key'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>OEM Non-Remote Key</b></td><td>' . $transponder_result['oem_non_remote_key'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>OEM Remote Key</b></td><td>' . $transponder_result['oem_remote_key'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Other</b></td><td>' . $transponder_result['other_non_remote_key'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>FCC ID#</b></td><td>' . $transponder_result['fcc_id'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>IC#</b></td><td>' . $transponder_result['ic'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Frequency</b></td><td>' . $transponder_result['frequency'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Code Series</b></td><td>' . $transponder_result['code_series'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Chip ID</b></td><td>' . $transponder_result['chip_ID'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Transponder Re-Use</b></td><td>' . $transponder_result['transponder_re_use'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Max No of Keys</b></td><td>' . $transponder_result['max_no_of_keys'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Key Shell</b></td><td>' . $transponder_result['key_shell'] . '</td></tr>';
            $table_body2 .= '<tr><td><b>Notes</b></td><td>' . $transponder_result['notes'] . '</td></tr>';
            $transponder_result['field_name'] = rtrim($transponder_result['field_name'], ':-:');
            if ($transponder_result['field_name'] != '') {
                $field_name_Arr = explode(':-:', $transponder_result['field_name']);
                $field_value_Arr = explode(':-:', $transponder_result['field_value']);
                foreach ($field_name_Arr as $k => $v) {
                    $table_body2 .= '<tr><td><b>' . ucwords($v) . '</b></td><td>' . $field_value_Arr[$k] . '</td></tr>';
                }
            }
            $table_body2 .= '</tbody>';
            $parts_no_Arr = explode(':-:', $transponder_result['parts_no']);
            $parts_id_Arr = explode(':-:', $transponder_result['parts_id']);
            $parts_stock_Arr = explode(':-:', $transponder_result['qty_on_hand']);
            $vendor_name_Arr = explode(':-:', $transponder_result['vendor_name']);
            $parts_id_Arr = array_unique($parts_id_Arr);

            $user_global_parts_no_Arr = explode(':-:', $transponder_result['user_global_parts_no']);
            $global_parts_no_Arr = explode(':-:', $transponder_result['global_parts_no']);
            $global_parts_id_Arr = explode(':-:', $transponder_result['global_parts_id']);
            $global_vendor_name_Arr = explode(':-:', $transponder_result['global_vendor_name']);

            $non_global_vendor_name_Arr = explode(':-:', $transponder_result['non_global_vendor_name']);
            $my_parts_stock_Arr = explode(':-:', $transponder_result['my_qty_on_hand']);
            $non_global_parts_id_Arr = explode(':-:', $transponder_result['non_global_parts_id']);
            $non_global_parts_no_Arr = explode(':-:', $transponder_result['non_global_parts_no']);

            $part_list_Arr = [];
            $global_parts_id_Arr = array_unique($global_parts_id_Arr);
            $user_global_parts_no_Arr = array_unique($user_global_parts_no_Arr);
            $global_parts_no_Arr = array_intersect_key($global_parts_no_Arr, $global_parts_id_Arr);
            $global_parts_no_Arr = array_diff($global_parts_no_Arr, $user_global_parts_no_Arr);
            
            $manufacturer = explode(':-:', $transponder_result['manufacturer']);
            $image  = $transponder_result['image'];
            $part_no = $transponder_result['part_no'];
            $internal_part_no = explode(':-:', $transponder_result['globalalternatepart']);
            $department_id = $transponder_result['department_id'];
            $department_name = explode(':-:', $transponder_result['department_name']);
            $globalvendorname = explode(':-:', $transponder_result['global_vendor_name']);
            $globalimage = explode(':-:', $transponder_result['globalimage']);
            $globaldescription = explode(':-:', $transponder_result['globaldescription']);
            $transponder_id = explode(':-:', $transponder_result['transponder_id']);
            $retail_price = explode(':-:', $transponder_result['retail_price']);

            $result['part_result'] = array(
                'parts_no_Arr' => $parts_no_Arr,
                'parts_id_Arr' =>$parts_id_Arr,
                'parts_stock_Arr' =>$parts_stock_Arr,
                'vendor_name_Arr' =>$vendor_name_Arr,
                'user_global_parts_no_Arr' => $user_global_parts_no_Arr,
                'global_parts_no_Arr' => $global_parts_no_Arr,
                'global_parts_id_Arr' => $global_parts_id_Arr,
                'global_vendor_name_Arr' => $global_vendor_name_Arr,
                'non_global_vendor_name_Arr' =>$non_global_vendor_name_Arr,
                'my_parts_stock_Arr' =>$my_parts_stock_Arr,
                'non_global_parts_id_Arr' => $non_global_parts_id_Arr,
                'non_global_parts_no_Arr' =>$non_global_parts_no_Arr,
                'part_list_Arr' => $part_list_Arr,      
                'manufacturer' => $manufacturer,
                // 'image' => $image,
                'part_no' => $part_no,
                'internal_part_no' => $internal_part_no,
                'department_id' => $department_id,
                'department_name' => $department_name,
                'globalvendorname' =>$globalvendorname,
                'globalimage' => $globalimage,
                'globaldescription' => $globaldescription,
                'transponder_id' => $transponder_id,
                'retail_price' => $retail_price,
            );
            // pr($result); die();
            // IML Security Supply Ex
            foreach ($global_parts_no_Arr as $k => $v) {
                if ($global_vendor_name_Arr[$k] != ''):
                    $part_list_Arr[$global_vendor_name_Arr[$k]][] = '<a href="javascript:void(0);" class="btn_global_item_view" title="View" id="' . base64_encode($global_parts_id_Arr[$k]) . '"><span class="label bg-orange" style="margin:3px;font-size:12px;font-family:monospace;">' . $global_parts_no_Arr[$k] . '</span></a>'
                            . '&nbsp;-&nbsp;<span class="label label-primary view-part-image" data-parttype="global" data-id="' . base64_encode($global_parts_id_Arr[$k]) . '" style="margin:3px;font-size:12px;font-family:monospace;"><i class="icon icon-image3"></i></span>&nbsp;';
                endif;
            }
            $non_global_parts_id_Arr = array_unique($non_global_parts_id_Arr);
            if (!empty($non_global_parts_id_Arr)) {
                foreach ($non_global_parts_id_Arr as $k => $v) {
                    if (!empty($non_global_parts_id_Arr[$k]) && !empty($non_global_parts_no_Arr[$k]) && !empty($my_parts_stock_Arr[$k])) {
                        if ($my_parts_stock_Arr[$k] > 0) {
                            $my_part_list_Arr[$non_global_vendor_name_Arr[$k]][] = '<a href="javascript:void(0);" class="btn_non_global_item_view" title="View" id="' . base64_encode($non_global_parts_id_Arr[$k]) . '"><span class="label label-success" style="margin:3px;font-size:12px;font-family:monospace;">' . $non_global_parts_no_Arr[$k] . '</span></a>';
                        } else {
                            $my_part_list_Arr[$non_global_vendor_name_Arr[$k]][] = '<a href="javascript:void(0);" class="btn_non_global_item_view" title="View" id="'.base64_encode($non_global_parts_id_Arr[$k]).'"><span class="label label-danger" style="margin:3px;font-size:12px;font-family:monospace;">'.$non_global_parts_no_Arr[$k] .'</span></a>';
                        }
                    }
                }
            }

            foreach ($vendor_name_Arr as $k => $v) {
                if ($v != '' && $v != null):
                    if (isset($parts_id_Arr[$k]) && isset($parts_no_Arr[$k]) && isset($parts_stock_Arr[$k])) {
                        if ($parts_stock_Arr[$k] > 0) {
                            $part_list_Arr[$v][] = '<a href="javascript:void(0);" class="btn_home_item_view" title="View" id="' . base64_encode($parts_id_Arr[$k]) . '"><span class="label label-success" style="margin:3px;font-size:12px;font-family:monospace;">' . $parts_no_Arr[$k] . '</span></a>'
                                    . '&nbsp;-&nbsp;<span class="label label-primary view-part-image" data-parttype="non-global" data-id="' . base64_encode($parts_id_Arr[$k]) . '" style="margin:3px;font-size:12px;font-family:monospace;"><i class="icon icon-image3"></i></span>&nbsp;';
                        } else {
                            $part_list_Arr[$v][] = '<a href="javascript:void(0);" class="btn_home_item_view" title="View" id="' . base64_encode($parts_id_Arr[$k]) . '"><span class="label label-danger" style="margin:3px;font-size:12px;font-family:monospace;">' . $parts_no_Arr[$k] . '</span></a>'
                                    . '&nbsp;-&nbsp;<span class="label label-primary view-part-image" data-parttype="non-global" data-id="' . base64_encode($parts_id_Arr[$k]) . '" style="margin:3px;font-size:12px;font-family:monospace;"><i class="icon icon-image3"></i></span>&nbsp;';
                        }
                    }
                endif;
            }

            $vendor_name = '';
            $cnt = 0;
            foreach ($part_list_Arr as $k => $v) {
                if ($vendor_name != $k) {
                    if ($cnt > 0) {
                        $div_part_list .= '<hr style="border-top: 2px solid #ddd;margin: 10px 0;">';
                    }
                    $vendor_name = $k;
                    $div_part_list .= '<h5 style="margin-bottom:0px">' . $k . '</h5>';
                    $cnt++;
                }
                foreach ($v as $key => $value) {
                    $div_part_list .= $value;
                }
            }

            $vendor_name = '';
            $cnt = 0;

            if(!empty($my_part_list_Arr)){
            foreach ($my_part_list_Arr as $k => $v) {
                if ($vendor_name != $k) {
                        if ($cnt > 0) {
                            $div_my_part_list .= '<hr style="border-top: 2px solid #ddd;margin: 10px 0;">';
                        }
                        $vendor_name = $k;
                        $div_my_part_list .= '<h5 style="margin-bottom:0px">' . $k . '</h5>';
                        $cnt++;
                    }
                    foreach ($v as $key => $value) {
                        $div_my_part_list .= $value;
                    }
                }
            }
            $toolArr = $this->inventory_model->get_tool_details()->result_array();
            if ($transponder_result['tools'] != null) {
                $tools = explode(',', $transponder_result['tools']);
                $tool_details = explode(':-:', $transponder_result['tool_details']);
                if (!empty($tools)) {
                    $equipments = (isset($settings) && $settings['equipment_id'] != null) ? explode(',', $settings['equipment_id']) : '';
                    $div_tool_list .= '<div id="accordion" class="panel-group col-sm-6 panel-flat">';
                    foreach ($tools as $k => $t) {
                        $key = array_search($t, array_column($toolArr, 'id'));
                        $content = (isset($tool_details[$k])) ? $tool_details[$k] : '';
                        if ($equipments != '') {
                            if (in_array($t, $equipments)) {
                            $div_tool_list .= '<div class="panel">' .
                                    '<div class="panel-heading">' .
                                    '<h4 class="panel-title">' .
                                    '<a href="#panelBody' . $t . '" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">&nbsp;&nbsp;&nbsp;<span>' . $toolArr[$key]["equip_name"] . ' (' . $toolArr[$key]['manu_name'] . ')</span></a>' .
                                    '</h4></div>' .
                                    '<div id="panelBody' . $t . '" class="panel-collapse collapse in">' .
                                    '<div class="panel-body">' .
                                    '<p>' . $content . '</p>' .
                                    '</div></div></div>';
                            }
                        } else {
                            $div_tool_list .= '<div class="panel">' .
                                    '<div class="panel-heading">' .
                                    '<h4 class="panel-title">' .
                                    '<a href="#panelBody' . $t . '" class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion">&nbsp;&nbsp;&nbsp;<span>' . $toolArr[$key]["equip_name"] . ' (' . $toolArr[$key]['manu_name'] . ')</span></a>' .
                                    '</h4></div>' .
                                    '<div id="panelBody' . $t . '" class="panel-collapse collapse">' .
                                    '<div class="panel-body">' .
                                    '<p>' . $content . '</p>' .
                                    '</div></div></div>';
                        }
                    }
                    $div_tool_list .= '</div>';
                }
            }
        } else {
            $table_body = '<tbody>';
            $table_body .= '<tr><td class="text-center"><h1 style="font-weight: 500 !important;color: #b5b3b3 !important;">No Such Data Found.</h1></td></tr>';
            $div_part_list = 'No Data Exists';
        }
        $result['tra_result'] = array(
            'table_body' => $table_body,
            'table_body2' => $table_body2,
            'div_part_list' => $div_part_list,
            'div_my_part_list' => $div_my_part_list,
            'div_tool_list' => $div_tool_list,
            'my_part_list_Arr' => $my_part_list_Arr,
            'vendor_multipul' => $vendor_multipul,
            'manufacturer_multipul' => $manufacturer_multipul,
            'itemsstatus_multipul' => $itemsstatus_multipul
        );
        // pr($result); die();
        $view = $this->load->view('front/login/accordion_result_item',$result,true);
        // pr($view); die();
        echo json_encode($view);
        exit;
    }


    /**
     * [add_edit_estimate_quickboook add data to quickbook]
     * @param [type] $id [description]
     * @author  KBH [created : 12-08-2019]
     * Last edited [16-08-2019]
     */
    public function add_edit_item_quickbook($id = NULL, $flag = NULL)
    {
        if($id != '' || $id != NULL)
        {
            $item_id                    = base64_decode($id);
            $this->quickbook_item($item_id, $flag);
        }
        else if($this->input->post('unsync_id') != '' || $this->input->post('unsync_id') != null)
        {
            $unsync_id                  = $this->input->post('unsync_id');
            $item_ids                   = explode(',', $unsync_id);
            foreach ($item_ids as $item_id) 
            {
                $this->quickbook_item($item_id);
            }
            $this->session->set_flashdata('success', "Sync all the Items to Quickbooks successfully.");
            $data['success']            = "success";   
            echo json_encode($data); 
            exit();
        }
    }

    /**
     * [get_low_inventory push_notification for low inventrory]
     * @param  [type] $item_id [description]
     * @author KBH 13-11-2019
     */
    public function get_low_inventory($item_id = NULL, $limit = NULL)
    {
        $this->db->select(TBL_ITEM_LOCATION_DETAILS .'.id,'.TBL_ITEM_LOCATION_DETAILS.'.item_id,SUM(quantity) as total_quantity,' . TBL_USER_ITEMS . '.low_inventory_limit');
        // $this->db->select(TBL_ITEM_LOCATION_DETAILS .'.id,'.TBL_ITEM_LOCATION_DETAILS.'.item_id,SUM(quantity) as total_quantity');
        $this->db->from(TBL_ITEM_LOCATION_DETAILS);
        $this->db->join(TBL_USER_ITEMS, TBL_USER_ITEMS . '.id = ' . TBL_ITEM_LOCATION_DETAILS . '.item_id', 'LEFT');
        $this->db->where('is_deleted', 0);
        $this->db->where('item_id ', $item_id);
        $this->db->group_by('item_id');
        if($limit == '' && $limit == NULL)
        {
            $this->db->having('SUM(quantity) <= 0'); 
        }
        $result         = $this->db->get()->row_array(); 
        return $result;
    }


}

/* End of file Items.php */
/* Location: ./application/controllers/Inventory.php */