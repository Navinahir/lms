<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webservice extends CI_Controller
{
	 public function __construct() {
        parent::__construct();
        $this->load->model('admin/Webservice_model');
    }

	public function vehicle_part_details(){
		$items= array();				
		$result = array();
		$final = array();
		$token = "";

		if(isset($_REQUEST['token']))
		{
			if($_REQUEST['token'] != "")
			{
				$token = $_REQUEST['token'];
			} else {
				$token ="";
			}
		}else{
			$token ="";
		}

		if($token != ""){
			if($token == "qp87irg5mkdfv85mef6drqw6mhoplk59hqw56wjdfiehfu"){
				
				$data['items'] = $this->Webservice_model->getitem();	
				
				$result['status'] = 'SUCCESS';
				$result['message'] = 'load items successfully';
				
				foreach($data['items'] as $data['item'])
				{
					$items['item_part_no'] = $data['item']->item_part_no;
					$items['item_description'] = $data['item']->item_description;
					if($data['item']->item_image != "" || $data['item']->item_image != NULL)
					{
						$items['item_image'] = site_url('uploads/items/'.$data['item']->item_image);
					} else {
						$items['item_image'] = site_url('uploads/items/no_image.jpg');
					}
					$items['item_internal_part_no'] = $data['item']->item_internal_part_no;
					$items['item_manufacturer'] = $data['item']->item_manufacturer;
					$items['department_name'] = $data['item']->department_name;
					$items['vendor_name'] = $data['item']->vendor_name;

					// array_push($result['data'], $items);
					$result['data'][] = $items;
				}
				// pr(json_encode($final)); die();
			
			} else {
				$result['status'] = 'FAILED';
				$result['message'] = 'Invalid token id. Please verify it with admin.';
			}
		} else {
			$result['status'] = 'FAILED';
			$result['message'] = 'Missing token id';
		}
		$final = $result;
		pr(json_encode($final)); die();
		die();
	}

}


// URL :- http://localhost/always_reliable_keys/api/Webservice/vehicle_part_details?token=qp87irg5mkdfv85mef6drqw6mhoplk59hqw56wjdfiehfu