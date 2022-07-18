<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Equipment_model extends MY_Model {

    /**
     * It will get all the records of manufacturer for ajax datatable
     * @param  : $condition - array
     * @return : Object
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_manufacturer($type) {
        $columns = ['m.id', 'm.name', 'm.description', 'm.modified_date', 'm.deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('m.*');
        $this->db->where(array(
            'm.is_deleted' => 0
        ));
        if (!empty($keyword['value'])) {
            $where = '(m.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR m.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(m.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_MANUFACTURERES . ' m');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_MANUFACTURERES . ' m');
            return $query;
        }
    }

    /**
     * It will get all the records of manufacturer for ajax datatable
     * @param  : $condition - array
     * @return : Object
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_equipment_type($type) {
        $columns = ['m.id', 'm.name', 'm.description', 'm.modified_date', 'm.deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('m.*');
        $this->db->where(array(
            'm.is_deleted' => 0
        ));
        if (!empty($keyword['value'])) {
            $where = '(m.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR m.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(m.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_EQUIPMENT_TYPES . ' m');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_EQUIPMENT_TYPES . ' m');
            return $query;
        }
    }

    /**
     * It will get all the records of manufacturer for ajax datatable
     * @param  : $condition - array
     * @return : Object
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_equipment_name($type) {
        $columns = ['en.id', 'm.name', 'et.name', 'en.description', 'en.modified_date', 'en.deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('en.*,m.name as man_name,et.name as type_name,en.description as name_description');
        $this->db->where(array(
            'en.is_deleted' => 0
        ));
        if (!empty($keyword['value'])) {
            $where = '(et.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR en.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . 'OR m.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(en.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_MANUFACTURERES . ' m', 'm.id=en.manufacturer_id AND m.is_deleted=0', 'left');
        $this->db->join(TBL_EQUIPMENT_TYPES . ' et', 'et.id=en.equipment_type_id AND et.is_deleted=0', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_EQUIPMENT_NAMES . ' en');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_EQUIPMENT_NAMES . ' en');
            return $query;
        }
    }

    /**
     * To check uniqueness at the time of ADD/EDIT functionality
     * @param string $table, array $condition
     * @return array()
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function check_unique_name($table, $condition) {
        $this->db->where($condition);
        $this->db->where('is_deleted', 0);
        $query = $this->db->get($table);
        return $query->row_array();
    }

}

/* End of file Inventory_model.php */
/* Location: ./application/models/Inventory_model.php */