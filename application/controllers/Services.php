<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . '/Library/QuickBook/autoload.php';
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Item;
class Services extends MY_Controller {

    public function __construct() {
        global $data_service;
        parent::__construct();
        $this->load->model(array('admin/service_model'));
        $data_service = $this->data_service();
    }

    /**
     * 
     * Display Users
     * @param  : ---
     * @return : ---
     * @author HPA [Last Edited : 01/06/2018]
     */
    public function display_services() {
        $data['title'] = 'Display Labor and Services';
        $this->template->load('default_front', 'front/services/service_display', $data);
    }

    /**
     * Get data of Locations request for listing
     * @param  : ---
     * @return : json
     * @author HPA [Last Edited : 29/06/2018]
     */
    public function get_ajax_data() {
        $final['recordsTotal'] = $this->service_model->get_ajax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $roles = $this->service_model->get_ajax_data('result');
        $start = $this->input->get('start') + 1;
        foreach ($roles as $key => $val) {
            $roles[$key] = $val;
            $roles[$key]['sr_no'] = $start++;
            $roles[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $roles[$key]['quickbooks'] = $this->service_quickbook_status($val['id']);
            $roles[$key]['responsive'] = '';
        }
        $final['data'] = $roles;
        echo json_encode($final);
    }

    /**
     * Add/Edit Locations
     * @param  : $id String
     * @return : ---
     * @author HPA [Last Edited : 08/06/2018]
     */
    public function edit_services($id = null) {
        $data['title'] = 'Add Labor and Service';
        if (!is_null($id)) {
            $record_id = base64_decode($id);
            $data['title'] = 'Edit Labor and Service';
            $data['dataArr'] = $this->service_model->get_all_details(TBL_SERVICES, array('id' => $record_id, 'is_deleted' => 0))->row_array();
        }
        if ($this->input->post()) {
            $this->form_validation->set_rules('txt_service_name', 'Service Name', 'trim|required|max_length[100]');
            if ($this->form_validation->run() == true) {
                $insertArr = array(
                    'name' => htmlentities($this->input->post('txt_service_name')),
                    'description' => htmlentities($this->input->post('txt_description')),
                    'rate' => htmlentities($this->input->post('txt_rate')),
                    'business_user_id' => checkUserLogin('C'),
                    'modified_date' => date('Y-m-d H:i:s')
                );
                extract($insertArr);
                if (!is_null($id)) {
                    $record_id = base64_decode($id);
                    $insertArr['last_modified_by'] = checkUserLogin('I');
                    $update_id = $this->service_model->insert_update('update', TBL_SERVICES, $insertArr, ['id' => $record_id]);

                    if (isset($_SESSION['sessionAccessToken'])) 
                    {
                        $session_accesToken = $_SESSION['sessionAccessToken'];

                        $service_quickbook_details = $this->service_model->get_all_details(TBL_QUICKBOOK_SERVICE, ['service_id' => $record_id,'realmId' => $session_accesToken->getRealmID()])->row_array();

                        if($service_quickbook_details['quickbook_id'] != '' || $service_quickbook_details['quickbook_id'] != null)
                        {
                            $this->add_edit_service_quickbook($id, $flag = "update");
                        }
                    }
                    $this->session->set_flashdata('success', '"<b>' . $name . '</b>" has been updated successfully.');
                } else {
                    $insertArr['created_date'] = date('Y-m-d H:i:s');
                    $insert_id = $this->service_model->insert_update('insert', TBL_SERVICES, $insertArr);
                    if ($insert_id > 0) {
                        if (isset($_SESSION['sessionAccessToken'])) {
                            $insert_id = base64_encode($insert_id);
                            $this->add_edit_service_quickbook($insert_id, $flag=1);
                        }
                        $this->session->set_flashdata('success', '"<b>' . $name . '</b>" has been added successfully.');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Please try again.');
                    }
                }
                redirect('services');
            }
        }
    }

    /**
     * It will check name is unique or not for Role
     * @param  : $id String
     * @return : Boolean (true/false)
     * @author HPA [Last Edited : 08/06/2018]
     */
    public function checkUniqueName($id = null) {
        $service_name = trim($this->input->get_post('txt_service_name'));
        $data = array(
            'name' => $service_name,
            'business_user_id' => checkUserLogin('C')
        );
        if (!is_null($id)) {
            $data = array_merge($data, array('id!=' => $id));
        }
        $user = $this->service_model->check_unique_service($data);
        if ($user > 0) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    public function action($action, $id) {
        $record_id                              = base64_decode($id);
        if ($action == 'delete'):

            $res        = [
                'is_deleted'                    => 1,
            ];

            $this->session->set_flashdata('success', 'Service has been deleted successfully.');
        endif;

        $this->service_model->insert_update('update', TBL_SERVICES, $res, array('id' => $record_id));


        if (isset($_SESSION['sessionAccessToken'])) 
        {
            global $data_service;
            $where = array(
                'service_id'                    => $record_id, 
                'realmId'                       => $data_service['accessToken']->getRealmID()
            );

            $service_quickbook_details          = $this->service_model->get_all_details(TBL_QUICKBOOK_SERVICE, $where)->row_array();
            if($service_quickbook_details['quickbook_id'] != '' || $service_quickbook_details['quickbook_id'] != null)
            { 
                $service                        = $data_service['dataService']->FindbyId('item', $service_quickbook_details['quickbook_id']);
                if($service)
                {
                    $theResourceObj             = Item::update($service, [
                            "Active"            => false
                        ]);
                    $resultingObj               = $data_service['dataService']->Update($theResourceObj);

                    $this->service_model->insert_update('update', TBL_QUICKBOOK_SERVICE, array('is_deleted' => 1), $where);
                }
            }
        }


        $service_detail             = $this->service_model->get_all_details(TBL_SERVICES, ['id' => $record_id])->row_array();   
        if($service_detail['quickbook_id'] != '' || $service_detail['quickbook_id'] != null)
        {
            $data_service           = $this->data_service();
            if(isset($data_service['accessTokenJson']))
            {     
                $customer           = $data_service['dataService']->FindbyId('item', $service_detail['quickbook_id']);
                $theResourceObj     = Item::update($customer, [
                    "Active"    => false
                ]);
                $resultingObj       = $data_service['dataService']->Update($theResourceObj);
            }
        }
        redirect('services');
    }

    /**
     * This function is used to GET PAYOUT REASONS via ajax
     * @param  : ---
     * @return : json data
     * @author HPA [Last Edited : 22/06/2018]
     */
    public function get_service_by_id() {
        $record_id      = base64_decode($this->input->post('id'));
        $condition      = array(
            'id'                => $record_id,
            'is_deleted'        => 0,
            'business_user_id'  => checkUserLogin('C')
        );
        $dataArr = $this->service_model->get_all_details(TBL_SERVICES, $condition)->row_array();
        echo json_encode($dataArr);
    }


    /**
     * [add_edit_service_quickbook add data to quickbook]
     * @param [type] $id [description]
     * @author  KBH [created : 03-03-2020]
     */
    public function add_edit_service_quickbook($id = NULL, $flag = NULL)
    {
        if($id != '' || $id != NULL)
        {
            $service_id            = base64_decode($id);
            $this->quickbook_service($service_id, $flag);
        }
        else if($this->input->post('unsync_id') != '' || $this->input->post('unsync_id') != null)
        {

            $unsync_id              =  $this->input->post('unsync_id');
            $service_ids            = explode(',', $unsync_id);
            foreach ($service_ids as $service_id) 
            {
                $this->quickbook_service($service_id);
            }
            $this->session->set_flashdata('success', "Sync all the Services to Quickbooks successfully.");
            $data['success']        = "success";
            echo json_encode($data);
            exit();
        }
        else if($this->input->post('unsync_id') == '' || $this->input->post('unsync_id') == null)
        {
            $this->session->set_flashdata('error', 'Not found any records to synchronous to Quickbooks');
            $data['sync_id_not_found']        = "sync id not found";
            echo json_encode($data);
            exit();
        }
    }

    /**
     * [add_edit_service_quickbook add data to quickbook]
     * @param [type] $id [description]
     * @author  KBH [Last Edited : 14/08/2019]
     */
    public function quickbook_service($service_id = null, $flag= null)
    {
        global $data_service;
        if(isset($data_service['accessTokenJson']))
        {
            $service_data           = $this->service_model->get_all_details(TBL_SERVICES, array('id' => $service_id, 'is_deleted' => 0))->row_array();
            $customer_config        = $this->service_model->get_all_details(TBL_QUICKBOOK_CONFIG, ['user_id' => $this->session->userdata('u_user_id'),'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

            if(empty($customer_config))
            {
                $this->session->set_flashdata('error', 'Please select your Income, Expenses and Inventory account.');
                redirect('quickbook');
            }
            else 
            {
                $services           = $data_service['dataService']->Query("select * from Account where ID = '". $customer_config['income_account_service'] ."'");
                if(empty($services))
                {
                    $this->session->set_flashdata('error', "Service are not available in your quickbook.");
                    if ($this->input->is_ajax_request()) 
                    {
                        $data['services'] = "not available";
                        echo json_encode($data);
                        exit();
                    }
                    else
                    {
                        redirect('services');
                    }
                }
                $service_array      = [
                        "Name"                  => $service_data['name'], 
                        "FullyQualifiedName"    => $service_data['name'],
                        "Description"           => $service_data['description'],
                        "Type"                  => "Service", 
                        "IncomeAccountRef"      => [
                             "value"            => $services[0]->Id,
                             "name"             => $services[0]->Name
                            ],
                        "PurchaseCost"          => 0,
                        "UnitPrice"             => $service_data['rate'],
                    ];
                $data_service['dataService']->throwExceptionOnError(true);
                try 
                {
                    if ($flag == "update")
                    {
                        $item_quickbook_details = $this->service_model->get_all_details(TBL_QUICKBOOK_SERVICE, ['service_id' => $service_data['id'],'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();

                        $service                = $data_service['dataService']->FindbyId('item', $item_quickbook_details['quickbook_id']);

                        $theResourceObj         = Item::update($service, $service_array);

                        $resultingObj           = $data_service['dataService']->Update($theResourceObj);
                    }
                    else
                    {
                        $theResourceObj         = Item::create($service_array);
                        $resultingObj           = $data_service['dataService']->Add($theResourceObj);

                        $quickbook_item['realmId']          = $data_service['accessToken']->getRealmID();
                        $quickbook_item['service_id']       = $service_id;
                        $quickbook_item['quickbook_id']     = $resultingObj->Id; 

                        $is_quickbook_service_saved = $this->service_model->insert_update('insert', TBL_QUICKBOOK_SERVICE, $quickbook_item); 
                            $this->session->set_flashdata('success', 'Service has been successfully added in quickbook.'); 
                    }
                    $error = $data_service['dataService']->getLastError(); 
                    if ($error) 
                    {
                   
                        $this->session->set_flashdata('error', $error->getHttpStatusCode());
                        $this->session->set_flashdata('error', $error->getOAuthHelperError());
                        $this->session->set_flashdata('error', $error->getResponseBody());
                    }
                    else 
                    {
                        if ($flag == "update")
                        {
                            $this->session->set_flashdata('success', 'Customer has been updated successfully also in Quickbook.');  
                        }
                        else 
                        {
                            if($flag)
                            {
                                $this->session->set_flashdata('success', 'Service has been created successfully also in Quickbook.');    
                            }
                            else
                            {
                                if (!$this->input->is_ajax_request()) 
                                {
                                    $this->session->set_flashdata('success', 'Service has been created in Quickbook successfully.');
                                    $last_url      = base_url() . 'quickbook/service';
                                    if ($this->agent->referrer() == $last_url)
                                    {
                                        $refer     =  $this->agent->referrer();
                                        redirect($refer);
                                    }
                                    else
                                    {
                                        redirect('services');
                                    }
                                }

                            }
                        } 
                    }
                } 
                catch (Exception $e) 
                {
                    $e->getMessage();
                    $this->session->set_flashdata('error', "Service : " . $service_data['name'] . " For " .$e->getMessage());
                    if ($this->input->is_ajax_request()) 
                    {
                        $data['error'] = "error";
                        echo json_encode($data);
                        exit();
                    }
                    else
                    {
                        redirect('services');
                    }
                }
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'Please login to Quickbook! Your session has been exprire.');       
            if ($this->input->is_ajax_request()) 
            {
                $data['session']   = "exprired"; 
                echo json_encode($data); 
                exit(); 
            }
            else
            {
                redirect('services'); 
            }
        }
    }
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */