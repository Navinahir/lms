<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscriber_model extends MY_Model {

    /**
     * It will get all the records of Estimates for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HGA [Last Edited : 29/06/2018
     */
    public function get_subscriber_data($type, $wh = null) {
        $columns = ['s.id','s.email'];
        $keyword = $this->input->get('search');
        $this->db->select('*');

        if (!is_null($wh)) {
            $this->db->where($wh);
        }

        if (!empty($keyword['value'])) {
            $where = '(s.email LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ') ';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        if ($type == 'count') {
            $query = $this->db->get(TBL_SUBSCRIBER . ' as s');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_SUBSCRIBER . ' as s');
            return $query->result_array();
        }
    }

}
