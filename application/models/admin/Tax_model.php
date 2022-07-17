<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tax_model extends MY_Model {

    /**
     * It will get all the records of Roles for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 08/06/2018
     */
    public function get_taxes_ajax_data($type, $wh = null) {
        $columns = ['t.id', 't.name', 't.rate', 't.modified_date', 't.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('t.*');
        $this->db->where(array(
            't.is_deleted' => 0,
            't.business_user_id' => checkUserLogin('C'),
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(t.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(t.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_TAXES . ' as t');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_TAXES . ' as t');
            return $query->result_array();
        }
    }

    public function check_unique_tax($where) {
        $this->db->where(array(
            'is_deleted' => 0,
        ));
        $this->db->where($where);
        $query = $this->db->get(TBL_TAXES);
        return $query->num_rows();
    }

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */