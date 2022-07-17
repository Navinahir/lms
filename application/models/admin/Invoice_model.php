<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends MY_Model {

    /**
     * It will get all the records of Estimates for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_invoices_data($type, $wh = null) {
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.cust_name', 'u.full_name', 'e.is_sent', 'e.total', 'e.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1
        ));
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
     * It will get all deleted records of Invoice for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_invoice_data_trash($type, $wh = null) {
        $columns = ['','','e.id', 'e.estimate_date', 'e.estimate_id', 'e.cust_name', 'u.full_name', 'e.is_sent', 'e.total', 'e.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 1,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.modified_date >' => date('Y-m-d', strtotime('-30 days')),
        ));
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

    public function get_estimate($id = null) {
        $this->db->select('e.*,u.full_name,com.name as make_name,m.name as modal_name,y.name as year_name,c.name as color_name,t.name as tax_name,t.rate as tax_per,u.contact_number,u.address as user_address');
        $this->db->where(array(
            'e.id' => $id,
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
        ));
        $this->db->join(TBL_MODEL . ' as m', 'm.id = e.modal_id', 'left');
        $this->db->join(TBL_COMPANY . ' as com', 'com.id = e.make_id', 'left');
        $this->db->join(TBL_YEAR . ' as y', 'y.id = e.year_id', 'left');
        $this->db->join(TBL_VEHICLE_COLORS . ' as c', 'c.id = e.color_id', 'left');
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->join(TBL_TAXES . ' as t', 't.id = e.tax_id', 'left');
        $query = $this->db->get(TBL_ESTIMATES . ' as e');
        $estimate = $query->row_array();
        if (!empty($estimate)) {
            $this->db->select('ep.*,ui.part_no, v.name as v1_name,ui.image,d.name as dept_name,it.total_quantity,l.name as location_name');
            $this->db->where(array(
                'ep.is_deleted' => 0,
                'ep.estimate_id' => $id,
            ));
            $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
            $this->db->join(TBL_VENDORS . ' as v', 'v.id = ui.vendor_id', 'left');
            $this->db->join(TBL_DEPARTMENTS . ' as d', 'ui.department_id=d.id', 'left');
            $this->db->join(TBL_LOCATIONS . ' as l', 'l.id=ep.location_id', 'left');
            $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=ui.id', 'left');
            $query = $this->db->get(TBL_ESTIMATE_PARTS . ' as ep')->result_array();
            if (!empty($query)):
                foreach ($query as $k => $q):
                    $q['location_quantity'] = $this->get_location_qty($q['part_id'], $q['location_id']);
                    $location = $this->get_all_details(TBL_ITEM_LOCATION_DETAILS, ['is_deleted' => 0, 'item_id' => $q['part_id'], 'is_active' => 1, 'quantity >' => 0])->result_array();
                    $q['location_inventory'] = array_column($location, 'location_id');
                    $query[$k] = $q;
                endforeach;
            endif;
            $estimate['parts'] = $query;
        }
        return $estimate;
    }

    function get_location_qty($item_id, $location_id) {
        $query = $this->db->query('SELECT item_id,location_id,IF(quantity IS NULL,0,quantity) as location_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 AND item_id=' . $item_id . ' AND location_id=' . $location_id)->row_array();
        return $query['location_quantity'];
    }

    public function get_recent_invoices_data() {
        $this->db->select('e.*');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1
        ));
        $this->db->order_by('id', 'desc');
        $this->db->limit(5, 0);
        $query = $this->db->get(TBL_ESTIMATES . ' as e');
        return $query->result_array();
    }

    public function get_invoice_paid_options() {
        $names = ['Credit Card', 'Cash', 'Check'];

        $this->db->select('id');
        $this->db->where_in('name', $names);
        $this->db->where(['is_deleted' => 0]);
        $query = $this->db->get(TBL_PAYMENT_METHODS);
        return $query->result_array();
    }

    public function get_invoice_bill_to_account_options() {
        $names = ['Bill to Account'];

        $this->db->select('id');
        $this->db->where_in('name', $names);
        $this->db->where(['is_deleted' => 0]);
        $query = $this->db->get(TBL_PAYMENT_METHODS);
        return $query->result_array();
    }

    public function get_open_invoices($type, $wh = null){
        $user_id = $this->auth_customer_id();
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.cust_name', 'e.is_sent', 'e.total'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*, pm.name as payment_method_name');
        $this->db->where(['c.is_deleted' => 0, 'c.added_by' => $user_id, 'pm.name' => 'Bill to Account']);
       
        if (!is_null($wh)) {
            $this->db->where($wh);
        }

        if (!empty($keyword['value'])) {
            $where = '(e.cust_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_date LIKE "%' . $keyword['value'] . '%" OR e.total LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        
        $this->db->join(TBL_ESTIMATES . ' as e', 'c.id=e.customer_id AND e.is_invoiced = 1 AND e.is_deleted = 0', 'inner');
        $this->db->join(TBL_PAYMENT_METHODS . ' as pm', 'e.payment_method_id=pm.id', 'inner');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        if ($type == 'count') {
            $query = $this->db->get(TBL_CUSTOMERS . ' AS c');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_CUSTOMERS . ' AS c');
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
     * Add part list in inventory history
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 02/09/2018]
     */
    public function addinventoryitem($field){
        $this->db->insert('inventory_history',$field);
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Add part list in inventory Description
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 02/09/2018]
     */
    public function addinventorydetail($inventorydetail){
        $this->db->insert('item_inevntory_details',$inventorydetail);
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Add inventory Description
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 02/09/2018]
     */
    public function addinvoiceinventory($invoice_inventory){
        $this->db->insert('invoice_inventory',$invoice_inventory);
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }


        /**
     * It get single Invoice from id
     * @param  : $type - string
     * @return : Object
     * @author KBH (06-11-2019)
     */
    public function get_invoice_from_id($wh = null) {
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.cust_name', 'u.full_name', 'e.is_sent', 'e.total', 'e.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.cust_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_date LIKE "%' . $keyword['value'] . '%" OR e.total LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        $query = $this->db->get(TBL_ESTIMATES . ' as e');
        return $query->row_array();
    }

}
