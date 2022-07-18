<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Location_model extends MY_Model {

    /**
     * It will get all the records of Locations for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_ajax_data($type, $wh = null) {
        $columns = ['l.id', 'l.name', 'l.description', 'l.is_active', 'l.modified_date', 'l.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('l.*');
        $this->db->where(array(
            'l.is_deleted' => 0,
            'l.business_user_id' => checkUserLogin('C'),
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(l.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR l.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(l.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_LOCATIONS . ' as l');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_LOCATIONS . ' as l');
            return $query->result_array();
        }
    }

    public function check_unique_location($where) {
        $this->db->where(array(
            'is_deleted' => 0,
        ));
        $this->db->where($where);
        $query = $this->db->get(TBL_LOCATIONS);
        return $query->num_rows();
    }

    public function total_locations() {
        $this->db->where(array(
            'l.is_deleted' => 0,
            'l.business_user_id' => checkUserLogin('C'),
            'l.is_active' => 1
        ));
        $query = $this->db->get(TBL_LOCATIONS . ' as l');
        return $query->num_rows();
    }

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */