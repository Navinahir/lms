<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_quickbook_model extends MY_Model {

    /**
     * It will get all un sync estimate of current login id
     * @param  : $type - string
     * @return : Object
     * @author KBH 08-11-2019
     */
     public function get_unsync_estimates_data($type, $sync_id = null ,$wh = null) {
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.cust_name', 'u.full_name', 'e.is_sent', 'e.total', 'e.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 0,
        ));
        $this->db->where_not_in('e.id',$sync_id);
        // $this->db->or_where('e.turn_invoiced','1');
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.cust_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_date LIKE "%' . $keyword['value'] . '%" OR e.total LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }


    /**
     * It will get all un sync invoice of current login id
     * @param  : $type - string
     * @return : Object
     * @author KBH 12-11-2019
     */
    public function get_unsync_invoice_data($type, $sync_id = null,$wh = null) {
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.cust_name', 'u.full_name', 'e.is_sent', 'e.total', 'e.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1
        ));
        $this->db->where_not_in('e.id',$sync_id);

        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.cust_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_date LIKE "%' . $keyword['value'] . '%" OR e.total LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }


     /**
     * It will get all the records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 20/02/2018]
     */
    public function get_user_items_data($type=NULL, $sync_id = NULL) {
        $columns = ['i.id','it.image as dimage', 'i.part_no','i.description','v.name','i.retail_price','total_quantity','d.name','i.upc_barcode','i.global_part_no','i.is_delete'];
        $keyword = $this->input->get('search');
        $search_filed = array_column(array_column($this->input->get('columns'), 'search'), 'value');
        // print_r($search_filed); die();
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name,it.total_quantity,it.image as dimage');
        $this->db->where(array(
            'i.is_delete' => 0,
            'i.business_user_id' => checkUserLogin('C')
        ));
        $this->db->where_not_in('i.id',$sync_id);
        
        if (!empty($search_filed)) {
            $whr_field = '';
            $i = 1;
            $whr_field = '(';
            foreach ($search_filed as $key => $value) {
                if (!is_null($value) && $value != '') {
                    if ($i <= 1) {
                        $whr_field .= $columns["$key"] . ' LIKE ' . $this->db->escape('%' . $value . '%');
                    } else {
                        $whr_field .= ' AND ' . $columns["$key"] . ' LIKE ' . $this->db->escape('%' . $value . '%');
                    }
                    $i++;
                }
            }
            $whr_field .= ')';
            if ($whr_field != '()') {
                $this->db->where($whr_field);
            }
        }
        if (!empty($keyword['value'])) {
            $where = '(i.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.upc_barcode LIKE ' .$this->db->escape('%' . $keyword['value'] . '%'). ' OR i.global_part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR d.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR v.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.retail_price LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR total_quantity LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(i.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }

        if(!empty($this->session->userdata('u_location_id')) && ($this->session->userdata('u_location_id') != NULL)){
            $loc_id = $this->session->userdata('u_location_id');
            $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 and location_id = '. $loc_id .'  group by item_id) it', 'it.item_id=i.id', 'left');
        } else {
            $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        }

        // $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.vendor_id=v.id', 'left');
        $this->db->join(TBL_ITEMS . ' as it', 'it.id=i.referred_item_id', 'left');
        // $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], 'ASC');
        if ($type == 'count') {
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query->result_array();
        }
       
    }


    public function get_item_details_from_quickbook_id($realm_id, $qb_item_id)
    {
        $this->db->select('*');
        $this->db->from(TBL_QUICKBOOK_ITEMS);
        $this->db->where(array('realmId' => $realm_id, 'quickbook_id' => $qb_item_id, 'is_deleted' => 0));
        $result = $this->db->get()->row_array();
        return $result;
    }


    /**
     * [get_quickbook_items get item_id from qb-id and realm id]
     * @param  [type] $quicknook_item_id [quickbook_id]
     * @param  [type] $realm_id          [login realm id]
     * @return [arrya]                   [total quanti and ark_item_id]
     * @author KBH 20-02-2020
     */
    public function get_quickbook_items($quicknook_item_id, $realm_id)
    {
        $this->db->select('item_id');
        $this->db->from(TBL_QUICKBOOK_ITEMS);
        $this->db->where('quickbook_id', $quicknook_item_id);
        $this->db->where('realmId', $realm_id);
        $this->db->where('is_deleted', 0);
        $result = $this->db->get()->row_array();
       
        $ark_data['total_quantity'] = $this->inventory_model->get_total_quantity($result['item_id']);
        $ark_data['ark_id'] = $result['item_id'];
        if(!empty($result))
        {
            $final['success'] = "true";
            $final['data'] = $ark_data;
            return $final;
        }
        else
        {
            $final['success'] = "false";
            $final['data'] = $ark_data;
            return $final;
        }
    }
}
