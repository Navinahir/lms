<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_model extends MY_Model {

    /**
     * It will Count Total Revenue
     * @param  : 
     * @return : 
     * @author JJP [Last Edited : 20/06/2019]
     */
    public function totalrevenue($year,$user_id){
        $this->db->where('id',$user_id);
        $query=$this->db->get('estimates');
        if($this->db->affected_rows()>0){
            return $query->row();
        } else {
            return false;
        } 
    }

    /**
     * It will Count Total Revenue Year Wish
     * @param  : 
     * @return : 
     * @author JJP [Last Edited : 20/06/2019]
     */
    public function totalrevenueyear($year,$user_id){
        $this->db->where('id',$user_id);
        $this->db->where('year(estimate_date)',$year);
        $query=$this->db->get('estimates');
        if($this->db->affected_rows()>0){
            return $query->row();
        } else {
            return false;
        } 
    }

    /**
     * It will get all the records of Estimates for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 04/11/2020
     */
    public function get_customers_data_trash($type, $wh = null, $wh_not = NULL) {
        $added_by = $this->customers_model->auth_customer_id();
        
        $columns = ['','','c.id', 'c.first_name', 'c.company', 'c.phone', 'c.mobile', 's.email', 'c.fax'];
        $keyword = $this->input->get('search');
        $this->db->select('c.*');

        $this->db->where(array(
            'c.is_deleted' => 1,
            'c.added_by' => $added_by,
            'c.updated_date >' => date('Y-m-d', strtotime('-30 days')),
        ));

        if (!is_null($wh)) {
            $this->db->where($wh);
        } 
        if (!is_null($wh_not)) {
            $this->db->where_not_in('c.id', $wh_not);
        }
        if (!empty($keyword['value'])) {
            $where = '(c.first_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . '  OR c.last_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR c.company LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR c.phone LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR c.email LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR c.phone LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR c.display_name_as LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_CUSTOMERS . ' as c');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_CUSTOMERS . ' as c');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Estimates for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HGA [Last Edited : 31/05/2019
     */
    public function get_customers_data($type, $wh = null, $wh_not = NULL) {
        $added_by = $this->customers_model->auth_customer_id();
        
        $columns = ['c.id', 'c.first_name', 'c.company', 'c.phone', 'c.mobile', 's.email', 'c.fax'];
        $keyword = $this->input->get('search');
        $this->db->select('c.*');

        $this->db->where(array(
            'c.is_deleted' => 0,
            'c.added_by' => $added_by,
        ));

        if (!is_null($wh)) {
            $this->db->where($wh);
        } 
        if (!is_null($wh_not)) {
            $this->db->where_not_in('c.id', $wh_not);
        }
        if (!empty($keyword['value'])) {
            $where = '(c.first_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . '  OR c.last_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR c.company LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR c.phone LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR c.email LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR c.phone LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR c.display_name_as LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_CUSTOMERS . ' as c');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_CUSTOMERS . ' as c');
            return $query->result_array();
        }
    }

    public function get_customer_invoices_ajax_data($type, $wh = null) {
        $customer_id = $this->input->get('customer_id');
        $columns = ['e.id', 'e.estimate_date', 'e.is_invoiced', 'e.is_invoiced', 'e.total'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id AS e_id,e.*, IF((e.is_invoiced=1&e.is_sent=1&pm.name="Bill to Account"), e.total, 0) as invoice_balance, pm.name as payment_method_name');
               
        // $array = array('e.customer_id' => $customer_id, 'e.is_deleted' => 0);
        // $inv_array = array('e.customer_id' => $customer_id, 'e.is_deleted' => 0, 'e.turn_invoiced' => 1);
         // $this->db->where($array);
         // $this->db->or_where($inv_array);
         $this->db->group_start();
                $this->db->where('e.customer_id', $customer_id);
                $this->db->where('e.is_deleted', 0);
                                $this->db->or_group_start();
                        $this->db->where('e.customer_id', $customer_id);
                        $this->db->where('e.is_deleted', 0);
                        $this->db->where('e.turn_invoiced', 1);
                        $this->db->group_end();
        $this->db->group_end();
        $this->db->join(TBL_PAYMENT_METHODS .' as pm','e.payment_method_id=pm.id','left');
        // $this->db->or_where('e.turn_invoiced = 1');
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->having($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' e');
            return $query->num_rows();
        } else {
            if ($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }

            $query = $this->db->get(TBL_ESTIMATES . ' e');
            // echo $this->db->last_query(); die();
            return $query;
        }
    }

    public function get_customers_notes($type, $wh = null) {
        $user_id = $this->customers_model->auth_customer_id();
        $customer_id = $this->input->get('customer_id');

        $columns = ['c.id', 'c.notes', 'c.created_date'];
        $keyword = $this->input->get('search');
        $this->db->select('c.*');

        $this->db->where(array(
            'c.is_deleted' => 0,
            'c.user_id' => $user_id,
            'c.customer_id' => $customer_id,
        ));

        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(c.notes LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_CUSTOMER_NOTES . ' as c');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_CUSTOMER_NOTES . ' as c');
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
}
