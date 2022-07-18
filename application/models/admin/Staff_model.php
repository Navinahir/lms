<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_model extends MY_Model {

    /**
     * It will get all the records of Staff for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_ajax_data($type) {
        $columns = ['u.id', 'u.status', 'r.role_name', 'u.full_name', 'u.username', 'u.email_id', 'u.modified_date', 'u.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('u.*,r.role_name');
        $this->db->where(array(
            'u.is_delete' => 0,
            'r.is_active' => 1,
        ));
//        $this->db->where_in('u.user_role', array('1', '2', '3'));
        if (!empty($keyword['value'])) {
            $where = '(u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.email_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR r.role_name LIKE ' . $this->db->escape('%' . strtolower(str_replace(' ', '_', $keyword['value'])) . '%') . ' OR u.status LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.username LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(u.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_ROLES . ' as r', 'u.user_role=r.id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->result_array();
        }
    }

    /**
     * Check email exist or not for unique email id
     * @param string/array $where
     * @return int
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function check_unique_email_for_staff($where) {
        $this->db->where(array(
            'is_delete' => 0,
        ));
        $this->db->where(['user_role!=' => 1]);
        $this->db->where($where);
        $query = $this->db->get(TBL_USERS);
        return $query->num_rows();
    }

}

/* End of file Staff_model.php */
/* Location: ./application/models/Staff_model.php */