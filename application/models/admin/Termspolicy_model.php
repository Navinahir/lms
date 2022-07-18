<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Termspolicy_model extends MY_Model {

    /**
     * It will get all the records of Estimates for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HGA [Last Edited : 29/06/2018
     */
    public function get_page_data($type, $wh = null) {
        $columns = ['tcpc.id', 'tcpc.page_title', 'tcpc.page_content'];
        $keyword = $this->input->get('search');
        $this->db->select('tcpc.*');
        $this->db->where(array(
            'tcpc.is_deleted' => 0,
        ));

        if (!is_null($wh)) {
            $this->db->where($wh);
        }

        if (!empty($keyword['value'])) {
            $where = '(tcpc.page_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ') ';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        if ($type == 'count') {
            $query = $this->db->get(TBL_TERMS_AND_PRIVACY_POLICIES . ' as tcpc');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_TERMS_AND_PRIVACY_POLICIES . ' as tcpc');
            return $query->result_array();
        }
    }

    public function get_contents_result($keyword = null) {
        $this->db->where(array(
            'tcpc.is_deleted' => 0,
        ));

        if (!empty($keyword)) {
            $where = '(mc.module_name LIKE ' . $this->db->escape('%' . $keyword . '%') . ') ';
            $this->db->where($where);
        }

        $query = $this->db->get(TBL_MODULES_CONTENT . ' as mc');
        return $query->result_array();
    }

}

/* End of file Content_model.php */
/* Location: ./application/models/admin/Content_model.php */