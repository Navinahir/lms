<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Package_model extends MY_Model {

    /**
     * It will get all the records of Package
     * @param $type string
     * @return $query -> Object
     * @author HPA [Last Edited : 31/05/2018]
     */
    public function get_package($type) {
        $columns = ['p.id', 'p.name', 'p.modified_date', 'p.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('p.*');
        $this->db->where('p.is_delete', 0);
        if (!empty($keyword['value'])) {
            $where = '(p.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(p.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_PACKAGES . ' p');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_PACKAGES . ' p');
            return $query;
        }
    }

    /**
     * To check uniqueness at the time of ADD/EDIT functionality
     * @param string $table, array $condition
     * @return array()
     * @author HPA [Last Edited : 31/05/2018]
     */
    public function check_unique_name($table, $condition) {
        $this->db->where($condition);
        $this->db->where('is_delete', 0);
        $query = $this->db->get($table);
        return $query->row_array();
    }

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */