<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_model extends MY_Model {

    /**
     * It will get all the records of transponder for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_categories($type) {

        $columns = ['createdon','name'];
        $keyword = $this->input->get('search');
//
        if (!empty($keyword['value'])) {
            $where = '(name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
        	
            $query = $this->db->get(TBL_CATEGORIES);
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_CATEGORIES);
            return $query;
        }
    }
	/**
	 * Update User profile
	 * @author PAV
	 * @param integer user id
	 * @return boolean
	 */
	public function get_all_categories() {
		$this->db->select('*');
		$this->db->from(TBL_CATEGORIES);
		$res = $this->db->get();
		return $res->result_array();
	}
}

/* End of file Categories_model.php */
/* Location: ./application/models/Categories_model.php */
