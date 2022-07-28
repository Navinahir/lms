<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead_model extends MY_Model {

    /**
     * It will get all the records of transponder for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_lead($type) {

        $columns = ['note','firstname','lastname','email'];
        $keyword = $this->input->get('search');
//
        if (!empty($keyword['value'])) {
            $where = '(firstname LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR lastname LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR email LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR note LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
        	
            $query = $this->db->get(TBL_LEAD);
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_LEAD);
            return $query;
        }
    }
}

/* End of file lead_model.php */
/* Location: ./application/models/lead_model.php */
