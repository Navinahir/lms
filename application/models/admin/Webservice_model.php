<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Webservice_model extends CI_Model{

	public function getitem(){
		$this->db->where('v.id',4);
		$this->db->where('i.is_delete',0);
		$query=$this->db
					->select('i.part_no as item_part_no ,i.description as item_description, i.image as item_image, i.internal_part_no as item_internal_part_no,i.manufacturer as item_manufacturer')
					->select('d.name as department_name')
					->select('v.name as vendor_name')
					->from(TBL_ITEMS .' as i')
					->join(TBL_DEPARTMENTS. ' as d','d.id = i.department_id')
					->join(TBL_VENDORS .' as v','v.id = i.preferred_vendor')
					->get();
		if($query->num_rows()>0){
			return $query->result();	
		}else{
			return false;
		}
	}

}