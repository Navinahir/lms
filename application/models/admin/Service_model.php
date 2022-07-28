<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Service_model extends MY_Model {

    /**
     * It will get all the records of Locations for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_ajax_data($type, $wh = null, $wh_not = null) {
        $columns = ['s.id', 's.name', 's.description', 's.rate', 's.modified_date', 's.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('s.*');
        $this->db->where(array(
            's.is_deleted' => 0,
            's.business_user_id' => checkUserLogin('C'),
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!is_null($wh_not)) {
            $this->db->where_not_in('s.id',$wh_not);
        }
        if (!empty($keyword['value'])) {
            $where = '(s.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR s.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR s.rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(s.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_SERVICES . ' as s');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_SERVICES . ' as s');
            return $query->result_array();
        }
    }

    public function check_unique_service($where) {
        $this->db->where(array(
            'is_deleted' => 0,
        ));
        $this->db->where($where);
        $query = $this->db->get(TBL_SERVICES);
        return $query->num_rows();
    }
}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */