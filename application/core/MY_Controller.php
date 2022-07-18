<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . '/Library/QuickBook/autoload.php';
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Data\IPPReferenceType;
use QuickBooksOnline\API\Data\IPPAttachableRef;
use QuickBooksOnline\API\Data\IPPAttachable;
class MY_Controller extends CI_Controller {

    static $access_method = array();
    static $access_controller = array();
    static $company_id = array();
    static $permission_id = array();
    static $date_format = array();
    static $currency = array();

    public function __construct() {
        parent::__construct();

        // Check Internet Explorer condition 
        $this->check_browser();

        if(!isset($_SESSION['testing']))
        {
            $_SESSION['testing'] = time();
        }
        $inactive = 3600; 
     //   ini_set('session.gc_maxlifetime', $inactive);
        if (isset($_SESSION['testing']) && (time() - $_SESSION['testing'] > $inactive)) {
            $this->session->unset_userdata('sessionAccessToken');
            $this->session->unset_userdata('testing');
        }
        $this->load->model(array('admin/users_model'));

        if ($this->session->userdata('vendor_logged_in') == 1) {
            $this->logged_in_id = $this->session->userdata('v_user_id');
        } else if ($this->session->userdata('user_logged_in') == 1) {
            $this->logged_in_id = $this->session->userdata('u_user_id');
        } else if ($this->session->userdata('user_id') != '') {
            $this->logged_in_id = $this->session->userdata('user_id');
        }

        MY_Controller::$date_format = checkUserLogin('D');
        MY_Controller::$currency = checkUserLogin('CU');

        $directory = $this->router->fetch_directory();

//        if (!empty($directory) && $directory != '') {
        if ($this->session->userdata('vendor_logged_in') == 1) {
            $data['user_data'] = $this->users_model->get_profile(checkVendorLogin('I'));
        } else if ($this->session->userdata('user_logged_in') == 1) {
            $data['user_data'] = $this->users_model->get_profile(checkUserLogin('I'));
        }

        if (!empty($data['user_data'])):
            if ($data['user_data']['user_role'] == 4) {
                MY_Controller::$company_id = $data['user_data']['uid'];
            } else if ($data['user_data']['user_role'] == 5) {
                MY_Controller::$company_id = $data['user_data']['uid'];
            } else {
                $controller = $this->router->fetch_class();
                $method = $this->router->fetch_method();
                MY_Controller::$company_id = $data['user_data']['business_user_id'];
                MY_Controller::$access_controller = array('home', 'dashboard');
                $per_data = $this->users_model->get_user_permissions(['permission_id' => $data['user_data']['user_role'], 'company_id' => MY_Controller::$company_id]);

                if (!empty($per_data)) {
                    $contoller_ids = array_unique(array_column($per_data, 'controller_id'));
                    $per_data2 = $this->users_model->get_undisplayed_methods(['controller_id' => $contoller_ids, 'is_display' => 0]);
                    $final_per_data = array_merge($per_data, $per_data2);
                }

                if (!empty($final_per_data)) {
                    foreach ($final_per_data as $key => $val) {
                        MY_Controller::$access_method [str_replace(' ', '_', strtolower($val['controller_name']))] [str_replace(' ', '_', strtolower($val['name']))] = $val['method_name'];
                        MY_Controller::$access_controller[] = str_replace(' ', '_', strtolower($val['controller_name']));
                    }

                    if (!in_array(strtolower($controller), MY_Controller::$access_controller)) {
                        echo $this->load->view('front/error403', null, true);
                        die;
                    } else if (in_array(strtolower($controller), MY_Controller::$access_controller) && !empty(MY_Controller::$access_method[strtolower($controller)]) && !in_array($method, MY_Controller::$access_method[strtolower($controller)])) {
                        echo $this->load->view('front/error403', null, true);
                        die;
                    }
                } else {
                    if (!in_array(strtolower($controller), MY_Controller::$access_controller)) {
                        echo $this->load->view('front/error403', null, true);
                        die;
                    } else if (in_array(strtolower($controller), MY_Controller::$access_controller) && !empty(MY_Controller::$access_method[strtolower($controller)]) && !in_array($method, MY_Controller::$access_method[strtolower($controller)])) {
                        echo $this->load->view('front/error403', null, true);
                        die;
                    }
                }
            }
        endif;
//        }
        
    }

    /**
     * Generate unique username
     * @param $string_name, $rand_no
     * @return $username
     */
    public function generate_unique_username($string_name = "", $rand_no = 200) {
        while (true) {
            $username_parts = array_filter(explode(" ", strtolower($string_name))); //explode and lowercase name
            $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

            $part1 = (!empty($username_parts[0])) ? substr($username_parts[0], 0, 8) : ""; //cut first name to 8 letters
            $part2 = (!empty($username_parts[1])) ? substr($username_parts[1], 0, 5) : ""; //cut second name to 5 letters
            $part3 = ($rand_no) ? rand(0, $rand_no) : "";

            $username = $part1 . str_shuffle($part2) . $part3; //str_shuffle to randomly shuffle all characters 

            $username_exist_in_db = $this->username_exist_in_database($username); //check username in database
            if (!$username_exist_in_db) {
                return $username;
            }
        }
    }

    public function username_exist_in_database($username) {
        $result = $this->users_model->get_all_details(TBL_USERS, array('username' => $username));
        return $result->num_rows();
    }

    public function test_email() {
        $email_var = array(
            'user_id' => '123',
            'first_name' => 'Parth',
            'username' => 'Viramgama',
            'email_id' => 'pav.narola@gmail.com',
            'password' => 'password123#'
        );
        $message = $this->load->view('email_template/default_header.php', $email_var, true);
        $message .= $this->load->view('email_template/staff_registration.php', $email_var, true);
        $message .= $this->load->view('email_template/default_footer.php', $email_var, true);
        $email_array = array(
            'mail_type' => 'html',
            'from_mail_id' => $this->config->item('smtp_user'),
            'from_mail_name' => 'ARK Team',
            'to_mail_id' => 'pav.narola@gmail.com',
            'cc_mail_id' => '',
            'subject_message' => 'Account Registration',
            'body_messages' => 'Demo Message'
        );
        $email_send = common_email_send($email_array);
        die;
    }

    /**
     * It will genrate quickbook accesskey and refreshkey
     * @param  :
     * @return :
     * @author KBH [Last Edited : 10/08/2019
     */
    function processCode()
    {
        $config = $this->config();
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' =>  $config['client_secret'],
            'RedirectURI' => $config['oauth_redirect_uri'],
            'scope' => $config['oauth_scope'],
            'baseUrl' => $config['baseUrl']
        ));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $parseUrl = $this->parseAuthRedirectUrl($_SERVER['QUERY_STRING']);
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'], $parseUrl['realmId']);
        // pr($parseUrl, 1);
        $dataService->updateOAuth2Token($accessToken);
        $_SESSION['sessionAccessToken'] = $accessToken;
    }


      /**
     * It will genrate quickbook accesskey and refreshkey
     * @param  :
     * @return :
     * @author KBH [Last Edited : 10/08/2019
     */
    function parseAuthRedirectUrl($url)
    {
        parse_str($url,$qsArray);
        if(array_key_exists("error", $qsArray))
        {
            if($qsArray['error'] != '')
            {
                echo "<script language=\"javascript\">window.opener = self; window.close();</script>";
            }
        }
        else
        {
            return array(
                'code' => $qsArray['code'],
                'realmId' => $qsArray['realmId']
            );  
        }
    }



    /**
     * It will genrate quickbook accesskey and refreshkey
     * @param  :
     * @return :
     * @author KBH [Last Edited : 10/08/2019
     */
    function config()
    {
        if ($_SERVER['HTTP_HOST'] == 'www.alwaysreliablekeys.com') 
        {
            $base_url = 'production';
            $oauth_redirect_uri = "https://www.alwaysreliablekeys.com/customers/callback";
            $client_id = 'ABogxY6P2747ql1M4myAi079eaujKW2SCJlVE32xbCxiD2Dind';
            $client_secret = 'd38ocJVY0loiqRQFjtaLSIUZ1HYNPEMWTuMLwJRa';
        } 
        else if ($_SERVER['HTTP_HOST'] == 'clientapp.narola.online') 
        {
            $base_url = 'development';
            $oauth_redirect_uri = "http://clientapp.narola.online/HD/always_reliable_keys/customers/callback";
            $client_id = 'AB76pWDPIHWWH688n4TVnugIm6sy2AJgO0dxRFiGYwp4NguwbQ';
            $client_secret = 'cSxIJ4TMeQC6Mm3n1DB9tYtfP4KuUoCttaivtHp0';
        } 
        else 
        {
            $base_url = 'development';
            $oauth_redirect_uri = "http://localhost/always_reliable_keys/customers/callback";
            $client_id = 'ABG387dKaIDIJcc3J92zKwV0ZyNe9iW8djwecvvAYib0Nf5jRC';
            $client_secret = '5JZBRDtMhHuII5f7fY590Q7bGkTwfAxGJ8UxYhJy';
            
        }
        return array(
            'authorizationRequestUrl' => 'https://appcenter.intuit.com/connect/oauth2',
            'tokenEndPointUrl' => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'oauth_scope' => 'com.intuit.quickbooks.accounting',
            'oauth_redirect_uri' => $oauth_redirect_uri,
            'baseUrl' => $base_url
        );
    }


    public function data_service()
    {
        $final = array();
        $config = $this->config();
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' =>  $config['client_secret'],
            'RedirectURI' => $config['oauth_redirect_uri'],
            'scope' => $config['oauth_scope'],
            'baseUrl' => $config['baseUrl']
        ));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        $_SESSION['authUrl'] = $authUrl;
        if (isset($_SESSION['sessionAccessToken'])) 
        {
                $accessToken = $_SESSION['sessionAccessToken'];
                $accessTokenJson = array('token_type' => 'bearer',
                'access_token' => $accessToken->getAccessToken(),
                'refresh_token' => $accessToken->getRefreshToken(),
                'x_refresh_token_expires_in' => $accessToken->getRefreshTokenExpiresAt(),
                'expires_in' => $accessToken->getAccessTokenExpiresAt()
            );

            $dataService->updateOAuth2Token($accessToken); 
            $oauthLoginHelper = $dataService->getOAuth2LoginHelper(); 
            $final['oauthLoginHelper'] = $oauthLoginHelper; 
            $final['accessTokenJson'] = $accessTokenJson; 
            $final['accessToken'] = $accessToken; 
        }
        $final['dataService'] = $dataService;
        $final['OAuth2LoginHelper'] = $OAuth2LoginHelper;
        $final['authUrl'] = $authUrl;
        return $final;
    }

    /**
     * [echoBase64 encode_image for upload quickbook image]
     * @param  [type] $filename [file path]
     */
    public function echoBase64($filename)
    {
        $str = '';
        $contents = file_get_contents($filename);
        $base64_contents = base64_encode($contents);
        $base64_contents_split = str_split($base64_contents, 80);
        foreach ($base64_contents_split as $one_line) {
            $str .=  "\t\t\"{$one_line}\" . \n";
        }
        return $str;
    }


    /**
     * [estimate_quickbook_status get Quickbook status for for login quickboook account]
     * @return [type] [description]
     */
    public function estimate_quickbook_status($estimate_id)
    {
        if (isset($_SESSION['sessionAccessToken'])) {
            $accessToken = $_SESSION['sessionAccessToken'];
            $realmId = $accessToken->getRealmID();
            $this->db->select('*');
            $this->db->where('realmId', $realmId);
            $this->db->where('estimate_id', $estimate_id);
            $this->db->where('is_deleted', 0);
            $query = $this->db->get(TBL_QUICKBOOK_ESTIMATE);
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


    public function invoice_quickbook_status($invoice_id)
    {
        if (isset($_SESSION['sessionAccessToken'])) {
            $accessToken        = $_SESSION['sessionAccessToken'];
            $realmId            = $accessToken->getRealmID();
            $this->db->select('*');
            $this->db->where('realmId', $realmId);
            $this->db->where('invoice_id', $invoice_id);
            $this->db->where('is_deleted', 0);
            $query              = $this->db->get(TBL_QUICKBOOK_INVOICE);
            $customer_status    = $query->row_array();
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
     * [invoice_quickbook_status get status of quickbook]
     * @param  [int] $item_id
     * @return [boolean] 
     */
    public function item_quickbook_status($item_id)
    {
        if (isset($_SESSION['sessionAccessToken'])) {
            $accessToken    = $_SESSION['sessionAccessToken'];
            $realmId        = $accessToken->getRealmID();

            $this->db->select('*');
            $this->db->where('realmId', $realmId);
            $this->db->where('item_id', $item_id);
            $this->db->where('is_deleted', 0);
            $query          = $this->db->get(TBL_QUICKBOOK_ITEMS);
            $item_status    = $query->row_array();

            if($item_status != '' || $item_status != NULL)
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
     * [add_invoice_to_quickbook add data to quickbook]
     * @param [type] $id [description]
     * @author  KBH [Date : 12/08/2019]
     */
    public function quickbook_item($item_id = null, $existing = null, $total_receive_amount = null)
    {
        global $data_service;
        if(isset($data_service['accessTokenJson']))
        {
            $item_data              = $this->inventory_model->get_user_items_data_quickbook($item_id)->row_array();
            $customer_config        = $this->inventory_model->get_all_details(TBL_QUICKBOOK_CONFIG, ['user_id' => $this->session->userdata('u_user_id'),'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();
            if(empty($customer_config))
            {
                $this->session->set_flashdata('error', 'Please select your Income, Expenses and Inventory accounts.');
                redirect('quickbook');
            }
            else
            {
                $INCOME_ACCT                = $data_service['dataService']->Query("select * from Account where Id = '". $customer_config['income_account'] ."'");
                $EXPENSE_ACCT               = $data_service['dataService']->Query("select * from Account where Id = '". $customer_config['expense_account'] ."'");
                $INVENTORY_ASSET_ACCT       = $data_service['dataService']->Query("select * from Account where Id = '". $customer_config['inventory_asset_account'] ."'");
                if(empty($INCOME_ACCT) || empty($EXPENSE_ACCT) || empty($INVENTORY_ASSET_ACCT))
                {
                    $this->session->set_flashdata('error', 'Please select your Income, Expenses and Inventory accounts.');
                    redirect('quickbook');
                }
                else
                {
                    $item_array = [
                            "TrackQtyOnHand"    => true, 
                            "Name"              => $item_data['part_no'], 
                            "Type"              => "Inventory", 
                            "QtyOnHand"         => $item_data['total_quantity'],
                            // "InvStartDate" => date('Y-m-d', time()),
                            "InvStartDate"      => date('Y-m-d',strtotime("-1 days")),
                            // "InvStartDate" => date('Y-m-d',$item_data['created_date']),

                            "IncomeAccountRef"  => [
                                "name"          => $INCOME_ACCT[0]->Name, 
                                "value"         =>  $INCOME_ACCT[0]->Id
                                ],
                            "AssetAccountRef"   => [
                                "name"          => $INVENTORY_ASSET_ACCT[0]->Name, 
                                "value"         =>$INVENTORY_ASSET_ACCT[0]->Id
                              ], 
                            "ExpenseAccountRef" => [
                                 "value"        => $EXPENSE_ACCT[0]->Id,
                                 "name"         => $EXPENSE_ACCT[0]->Name
                                ],
                            "Description"       => $item_data['description'], 
                            "Sku"               => $item_data['internal_part_no'], 
                            "UnitPrice"         => $item_data['retail_price'], 
                            "PurchaseCost"      => $item_data['unit_cost'], 
                        ];
                    $data_service['dataService']->throwExceptionOnError(true);
                }
                try 
                {
                    if ($existing == "update") 
                    {
                        // $item_array['ReorderPoint'] = $total_receive_amount;
                        $item_quickbook_details     = $this->inventory_model->get_all_details(TBL_QUICKBOOK_ITEMS, ['item_id' => $item_data['id'],'realmId' => $data_service['accessToken']-> getRealmID()])->row_array();
                        $item                       = $data_service['dataService']->FindbyId('item', $item_quickbook_details['quickbook_id']);
                        $theResourceObj             = Item::update($item, $item_array);
                        $resultingObj               = $data_service['dataService']->Update($theResourceObj);
                    }
                    else
                    {
                        $theResourceObj                 = Item::create($item_array);
                        $resultingObj                   = $data_service['dataService']->Add($theResourceObj);
                        $item_data      = $this->db->query("SELECT id,image,global_part_no FROM user_items WHERE id = '".$item_id."' and is_delete = '0' ")->row_array();
                       

                        if(empty($item_data['image']))
                        {
                            if(!empty($item_data['global_part_no']))
                            {
                                $item_data = $this->db->query("SELECT image FROM `items` WHERE `part_no` LIKE '".$item_data['global_part_no']."' and is_delete = '0' ")->row_array();
                            }
                        }
                        // qry();
                        // pr($item_data);die;
                        if($item_data['image'] != '' && $item_data['image'] != null)
                        {
                            $item_image                     = base_url() . ITEMS_IMAGE_PATH . "/" . $item_data['image'];
                            $ext                            = strtolower( pathinfo($item_data['image'], PATHINFO_EXTENSION));

                            $imageBase64                    = array();
                            $file                           =  $this->echoBase64($item_image);
                            $imageBase64['image/jpeg']      = $file;
                            $sendMimeType                   = "image/jpeg";

                            $randId                         = rand();
                            $entityRef                      = new IPPReferenceType(array('value'=>$resultingObj->Id, 'type'=>'Item'));
                            $attachableRef                  = new IPPAttachableRef(array('EntityRef'=>$entityRef));
                            $objAttachable                  = new IPPAttachable();
                            $objAttachable->FileName        = $randId . "." . $ext;
                            $objAttachable->AttachableRef   = $attachableRef;
                            $objAttachable->Category        = 'Image';
                            $objAttachable->Tag             = 'Tag_' . $randId;

                            $resultObj = $data_service['dataService']->Upload(base64_decode($imageBase64[$sendMimeType]), $objAttachable->FileName, $sendMimeType, $objAttachable);
                        }

                        $quickbook_item['realmId']      = $data_service['accessToken']->getRealmID();
                        $quickbook_item['item_id']      =     $item_id;
                        $quickbook_item['quickbook_id'] = $resultingObj->Id;

                        $is_quickbook_item_saved        = $this->inventory_model->insert_update('insert', TBL_QUICKBOOK_ITEMS, $quickbook_item);
                    }

                    $error                          = $data_service['dataService']->getLastError();

                    if ($error) 
                    {
                        
                        $this->session->set_flashdata('error', $error->getHttpStatusCode());
                        $this->session->set_flashdata('error', $error->getOAuthHelperError());
                        $this->session->set_flashdata('error', $error->getResponseBody());
                    }
                    else 
                    {
                        if ($existing == "update")
                        {
                            $this->session->set_flashdata('success', 'Data has been updated successfully also in Quickbook.');  
                        } 
                        else
                        {
                           if($existing)
                           {
                                $this->session->set_flashdata('success', 'Data has been inserted successfully in Quickbook also.');
                           }
                           else
                           {
                                if (!$this->input->is_ajax_request()) 
                                {
                                    $this->session->set_flashdata('success', 'Data has been inserted successfully in quickbook.');
                                    $last_url      = base_url() . 'quickbook/item';
                                    if ($this->agent->referrer() == $last_url)
                                    {
                                        $refer     =  $this->agent->referrer();
                                        redirect($refer);
                                    }
                                    else
                                    {
                                        redirect('items');
                                    }
                                }
                           }
                       }               
                    }
                } 
                catch (Exception $e) 
                {
                    // echo $e->getMessage();
                    $this->session->set_flashdata('error', "Items number : " . $item_data['part_no'] . " " .  $e->getMessage());
                    if ($this->input->is_ajax_request()) 
                    {
                        $data['error'] = "error";
                        echo json_encode($data);
                        exit();
                    }
                    else
                    {
                        redirect('items');
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
                redirect('items');
            }
        }
    }



    /**
     * [service_quickbook_status service quickbook status added or not for particular Quickbook a/c]
     * @return [type] [description]
     * @author KBH
     * @date(06-09-2019)
     */
    public function service_quickbook_status($service_id)
    {
        if (isset($_SESSION['sessionAccessToken'])) {
            $accessToken            = $_SESSION['sessionAccessToken'];
            $realmId                = $accessToken->getRealmID();

            $this->db->select('*');
            $this->db->where('realmId', $realmId);
            $this->db->where('service_id', $service_id);
            $this->db->where('is_deleted', 0);
            $query                  = $this->db->get(TBL_QUICKBOOK_SERVICE);
            $service_status         = $query->row_array();

            if($service_status != '')
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
     * [get_customer_added_status get particular customer Quickbook status]
     * @param  [Int] $customer_id [customer id]
     * @return [array]            []
     * @author KBH
     * @date(28-08-2019)
     */
    public function get_customer_quickbook_status($customer_id)
    {
        if (isset($_SESSION['sessionAccessToken'])) {
            $accessToken                = $_SESSION['sessionAccessToken'];
            $realmId                    = $accessToken->getRealmID();

            $this->db->select('*');
            $this->db->where('realmId', $realmId);
            $this->db->where('customer_id', $customer_id);
            $query                      = $this->db->get(TBL_QUICKBOOK_CUSTOMER);
            $customer_status            = $query->row_array();

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
     * [get_customer_ark_status check QB customer available in ARK ]
     * @param  [int] $qb_customer_id [QB customer id]
     * @return [boolen]              [description]
     * @author KBH <04-12-2020>
     */
    public function get_customer_ark_status($qb_customer_id)
    {
        if (isset($_SESSION['sessionAccessToken'])) {
            $accessToken                = $_SESSION['sessionAccessToken'];
            $realmId                    = $accessToken->getRealmID();

            $this->db->select('*');
            $this->db->where('realmId', $realmId);
            $this->db->where('quickbook_id', $qb_customer_id);
            $query                      = $this->db->get(TBL_QUICKBOOK_CUSTOMER);
            $customer_status            = $query->row_array();
            if(empty($customer_status))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    // IE condition
    public function check_browser(){
        $browser_detect = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
        if (preg_match('~MSIE|Internet Explorer~i', $browser_detect) || (strpos($browser_detect, 'Trident/7.0') !== false && strpos($browser_detect, 'rv:11.0') !== false) && $this->router->fetch_method() != 'update_browser')
        {
            redirect('update_browser','location',301);
        }
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */