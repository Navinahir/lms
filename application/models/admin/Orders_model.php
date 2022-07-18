<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_model extends MY_Model {

    /**
     * It will get all the records of Estimates for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_orders_data($type, $wh = null) {
        $added_by = checkUserLogin('I');

        $columns = ['o.id', 'o.ordered_date', 'o.order_no', 'o.customer_name', 'o.vendor_part_no', 's.status_name', 'o.total_receipt_amount'];
        $keyword = $this->input->get('search');
        $this->db->select('o.*, u.first_name, u.last_name, s.status_name, s.status_color');
        $this->db->where(array(
            'o.is_deleted' => 0,
            'o.added_by' => $added_by,
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(o.customer_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . '  OR s.status_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id = o.added_by', 'left');
        $this->db->join(TBL_STATUS . ' as s', 's.id = o.status_id', 'left');

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ORDER . ' as o');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ORDER . ' as o');
            return $query->result_array();
        }
    }

    public function auth_customer_id(){
        $user_id = checkUserLogin('I');
        
        if(checkUserLogin('R') != 4){
            $this->db->select('business_user_id');
            $this->db->where('id',$user_id);
            $query=$this->db->get(TBL_USERS);
            $user_data = $query->row(); 

            $user_id = $user_data->business_user_id;
        }

        return $user_id;
    }

    /**
     * It will get all the records of Estimates for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_customer_order_data($type, $wh = null) {
        $added_by = checkUserLogin('I');
    
        $columns = ['o.id', 'o.ordered_date', 'o.order_no', 'o.customer_name', 'o.vendor_part_no', 's.status_name', 'o.total_receipt_amount',''];
        $keyword = $this->input->get('search');
        $this->db->select('o.*, u.first_name, u.last_name, s.status_name, s.status_color');
        
        $this->db->where(array(
            'o.is_deleted' => 0,
            'o.added_by' => $added_by,
        ));
        
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(o.customer_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . '  OR s.status_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id = o.added_by', 'left');
        $this->db->join(TBL_STATUS . ' as s', 's.id = o.status_id', 'left');


        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ORDER . ' as o');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ORDER . ' as o');
            return $query->result_array();
        }
    }

}
