<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends MY_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/estimate_model');
    }
    public function index()
    {
        $data = array();
        $allmsgs = $this->db->select('*')->from('tbl_msg')->get()->result_array();
        $data['allMsgs'] = $allmsgs;
        $this->load->view('message',$data);
    }
    public function send()
    {
        $arr['msg'] = $this->input->post('message');
        $arr['date'] = date('Y-m-d');
        $arr['status'] = 1;
        $this->db->insert('tbl_msg',$arr);
        $detail = $this->db->select('*')->from('tbl_msg')->where('id',$this->db->insert_id())->get()->row();
        $msgCount = $this->db->select('*')->from('tbl_msg')->get()->num_rows();
        $arr['message'] = $detail->msg;
        $arr['date'] = date('m-d-Y', strtotime($detail->date));
        $arr['msgcount'] = $msgCount;
        $arr['success'] = true;
        echo json_encode($arr);
    }


    /**
     * [view_notification bind notification view]
     * @author KBH 05-11-2019
     */
    public function view_notification()
    {
        $format = MY_Controller::$date_format; 

        $this->db->select("n.*");
        $this->db->join(TBL_USER_ITEMS . ' as ui','n.enum_id = ui.id');
        $this->db->where(['n.business_user_id' => checkUserLogin('C'),'n.is_delete' => '0','ui.is_delete' => '0']);
        $this->db->order_by('n.created_at', "desc");
        $new_notifications_list = $this->db->get(TBL_NOTIFICATION . ' n')->result_array();
        
        // Count number of low inventory notification
        // $this->db->select("n.*");
        // $this->db->join(TBL_USER_ITEMS . ' as ui','n.enum_id = ui.id');
        // $this->db->where(['n.business_user_id' => checkUserLogin('C'),'n.estimate_invoice' => '2','n.is_delete' => '0','ui.is_delete' => '0']);
        // $count = $this->db->get(TBL_NOTIFICATION . ' n')->num_rows();

        $count = 0;
        $est_inv_notification_count = 0;

        // Count number of estimates notification
        $this->db->select("n.*");
        $this->db->where(['n.business_user_id' => checkUserLogin('C'),'n.estimate_invoice' => '0','n.is_delete' => '0']);
        $count += $this->db->get(TBL_NOTIFICATION . ' n')->num_rows();

        // Count number of invoice notification
        $this->db->select("n.*");
        $this->db->where(['n.business_user_id' => checkUserLogin('C'),'n.estimate_invoice' => '1','n.is_delete' => '0']);
        $count += $this->db->get(TBL_NOTIFICATION . ' n')->num_rows();  
        $est_inv_notification_count = $count;

        // Low inventory notification
        $html_new_notification = '';
        $low_inventory_count = array();
        foreach ($new_notifications_list as $new_notification) 
        {
            if($new_notification['estimate_invoice'] == '2') 
            {
                $part_id = $new_notification['enum_id'];
                $this->db->select('i.low_inventory_limit,it.total_quantity');
                $this->db->from(TBL_USER_ITEMS . ' AS i');
                $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
                $this->db->where('i.id', $part_id);
                $result = $this->db->get();
                $data['viewArr'] = $result->row_array();
                
                if($data['viewArr']['low_inventory_limit'] >= $data['viewArr']['total_quantity'])
                {
                    $this->db->select("id, part_no, image");
                    $this->db->from(TBL_USER_ITEMS);
                    $this->db->where(['id' => $new_notification['enum_id']]);
                    $query = $this->db->get();
                    $item_data =  $query->row_array();
                    
                    // Count number of low inventory notification
                    $item_data_count = $query->num_rows();
                    array_push($low_inventory_count, $item_data_count);

                    $message = "Low limit Inventory hit";
                    $name = '<a href="javascript:void(0);" id= '.base64_encode($new_notification['enum_id']).' class="notification_item_view_btn pushnotification-name" >'.$item_data['part_no'].'</a>';
                    if($item_data['image'] != '' && $item_data['image'] != null){
                        $image = base_url('uploads/items/'. $item_data['image']);
                    }else{
                        $image = 'uploads/items/no_image.jpg';
                    }
                    $url = '';
                    $close = '<a class="close-notification" id = '.base64_encode($new_notification['enum_id']).'><span class="media-annotation pull-right">Close</span></a>';
                    
                    $html_new_notification .= '<li class="media">
                        <div class="media-left">
                            <img src="' . $image . '" class="img-circle img-sm" alt="">
                            <span class="badge bg-danger-400 media-badge">5</span>
                        </div>
                        <div class="media-body">
                            <div class="pushnotification-wrapper">
                                <div class="pushnotification-details">
                                    <a class="pushnotification-name" href="'.$url.'" target=”_blank”>'. $name . '</a>
                                    <p class="text-muted pushnotification-label">'.$message.'</p>
                                </div>
                                <div class="pushnotification-date">
                                    <span class="media-annotation pull-right">'.date($format['format'], strtotime($new_notification['created_at'])).'</span>
                                    <p>'.$close.'</p>
                                </div>
                            </div>
                        </div>
                    </li>'; 
                }                    
            }
        }
        $low_inventory_notification_count = (array_sum($low_inventory_count));
        $count += (array_sum($low_inventory_count));

        // estimate & invoice notification
        $this->db->select("n.*,u.first_name as first_name, u.last_name as last_name");
        $this->db->join(TBL_USERS . ' as u', 'u.id = n.added_by');
        $this->db->where(['n.business_user_id' => checkUserLogin('C'),'n.is_delete' => '0']);
        $this->db->order_by('n.created_at', "desc");
        $est_inv_notification_list = $this->db->get(TBL_NOTIFICATION . ' as n')->result_array();
    
        $html_estimate_invoice = '';
        foreach ($est_inv_notification_list as $est_inv_notification) 
        {
            if($est_inv_notification['estimate_invoice'] == '0')
            {
                $message = "Added Estimate";
                $name = $est_inv_notification['first_name'] . " " . $est_inv_notification['last_name'];
                $image = 'assets/images/user_image.png';
                $url = base_url('estimates/view/' .base64_encode($est_inv_notification['enum_id']));
                // if($this->session->userdata('u_business_id') != "") {   
                if(!empty($this->session->userdata('u_location_id')) && ($this->session->userdata('u_location_id') != NULL)){
                    $close = '';
                } else {
                    $close = '<a class="close-notification" id = '.base64_encode($est_inv_notification['enum_id']).'><span class="media-annotation pull-right">Close</span></a>';
                }
            }
            if($est_inv_notification['estimate_invoice'] == '1')
            {
                $message = "Added Invoice";
                $name = $est_inv_notification['first_name'] . " " . $est_inv_notification['last_name'];
                $image = 'assets/images/user_image.png';
                $url = base_url('invoices/view/' . base64_encode($est_inv_notification['enum_id']));
                // if($this->session->userdata('u_business_id') != "") {   
                if(!empty($this->session->userdata('u_location_id')) && ($this->session->userdata('u_location_id') != NULL)){
                    $close = '';
                } else {
                    $close = '<a class="close-notification" id = '.base64_encode($est_inv_notification['enum_id']).'><span class="media-annotation pull-right">Close</span></a>';
                }
            }
            $html_estimate_invoice .= '<li class="media">
                    <div class="media-left">
                        <img src="' . $image . '" class="img-circle img-sm" alt="">
                        <span class="badge bg-danger-400 media-badge">5</span>
                    </div>
                    <div class="media-body">
                        <div class="pushnotification-wrapper">
                            <div class="pushnotification-details">
                             <a class="pushnotification-name" href="'.$url.'" target=”_blank”>'. $name . '</a>
                             <p class="text-muted pushnotification-label">'.$message.'</p>
                            </div>
                            <div class="pushnotification-date">
                                <span class="media-annotation pull-right">'.date($format['format'], strtotime($est_inv_notification['created_at'])).'</span>
                               <p>'.$close.'</p>
                            </div>
                        </div>
                    </div>
                </li>'; 
        }

        // Display estiate & invoice close notification
        $this->db->select("n.*,u.first_name as first_name, u.last_name as last_name");
        $this->db->join(TBL_USERS . ' as u', 'u.id = n.added_by');
        $this->db->where(['n.business_user_id' => checkUserLogin('C'),'n.is_delete' => '1']);
        $this->db->order_by('n.created_at', "desc");
        $close_notification_list = $this->db->get(TBL_NOTIFICATION . ' as n')->result_array();
        
        // Estimate & invoice close notification
        $html_closed_notification = '';
        foreach ($close_notification_list as $close_notification) 
        {
            if($close_notification['estimate_invoice'] == '0')
            {
                $message = "Added Estimate";
                $name = $close_notification['first_name'] . " " . $close_notification['last_name'];
                $image = 'assets/images/user_image.png';
                $url = base_url('estimates/view/' .base64_encode($close_notification['enum_id']));
            
            } 
            if($close_notification['estimate_invoice'] == '1') {
            
                $message = "Added Invoice";
                $name = $close_notification['first_name'] . " " . $close_notification['last_name'];
                $image = 'assets/images/user_image.png';
                $url = base_url('invoices/view/' . base64_encode($close_notification['enum_id']));
            }
            
            $html_closed_notification .= '<li class="media">
                    <div class="media-left">
                        <img src="' . $image . '" class="img-circle img-sm" alt="">
                        <span class="badge bg-danger-400 media-badge">5</span>
                    </div>
                    <div class="media-body">
                        <div class="pushnotification-wrapper">
                            <div class="pushnotification-details">
                                <a class="pushnotification-name" href="'.$url.'" target=”_blank”>'. $name . '</a>
                                <p class="text-muted pushnotification-label">'.$message.'</p>
                            </div>
                            <div class="pushnotification-date">
                                <span class="media-annotation pull-right">'.date($format['format'], strtotime($close_notification['created_at'])).'</span>
                            </div>
                        </div>
                    </div>
                </li>'; 
        }

        // Display item close notification
        $this->db->select("n.*,ui.part_no,ui.image");
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = n.enum_id');
        $this->db->where(['n.business_user_id' => checkUserLogin('C'),'n.is_delete' => '1']);
        $this->db->order_by('n.created_at', "desc");
        $item_close_notification_list = $this->db->get(TBL_NOTIFICATION . ' as n')->result_array();

        // Item close notification
        $item_html_closed_notification = '';
        foreach ($item_close_notification_list as $item_notification) 
        {
            if($item_notification['estimate_invoice'] == '2')
            {
                $message = "Low limit Inventory hit";
                $name = '<a href="javascript:void(0);" id= '.base64_encode($item_notification['enum_id']).' class="notification_item_view_btn pushnotification-name" >'.$item_notification['part_no'].'</a>';
                if($item_notification['image'] != '' && $item_notification['image'] != null){
                    $image = base_url('uploads/items/'. $item_notification['image']);
                }else{
                    $image = 'uploads/items/no_image.jpg';
                }
                $url = '';
            } 

            $item_html_closed_notification .= '<li class="media">
                    <div class="media-left">
                        <img src="' . $image . '" class="img-circle img-sm" alt="">
                        <span class="badge bg-danger-400 media-badge">5</span>
                    </div>
                    <div class="media-body">
                        <div class="pushnotification-wrapper">
                            <div class="pushnotification-details">
                                <a class="pushnotification-name" href="'.$url.'" target=”_blank”>'. $name . '</a>
                                <p class="text-muted pushnotification-label">'.$message.'</p>
                            </div>
                            <div class="pushnotification-date">
                                <span class="media-annotation pull-right">'.date($format['format'], strtotime($item_notification['created_at'])).'</span>
                            </div>
                        </div>
                    </div>
                </li>'; 
        }

        $data['html_new_notification'] = $html_new_notification;
        $data['html_estimate_invoice'] = $html_estimate_invoice;
        $data['html_closed_notification'] = $html_closed_notification;
        $data['item_html_closed_notification'] = $item_html_closed_notification;
        $data['count'] = $count;
        $data['low_inventory_notification_count'] = $low_inventory_notification_count;
        $data['est_inv_notification_count'] = $est_inv_notification_count;
        
        echo json_encode($data);
    }


    public function update_count()
    {
        $this->estimate_model->insert_update('update', TBL_NOTIFICATION, ['status' => 1], array('business_user_id' => checkUserLogin('C')));
    }

    /**
     * [remove notification]
     * @param  [type] $item_id [description]
     * @author JJP 22-07-20200
     */
    public function remove_notification($id = NULL)
    {
        $query = $this->estimate_model->insert_update('update',TBL_NOTIFICATION,array('is_delete' => 1),array('enum_id' => base64_decode($this->input->post('id'))));
        return $query;
    }

        

}
?>