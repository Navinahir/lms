<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_model extends MY_Model {

    /**
     * It will get all the records of departments for ajax datatable
     * @param  : $condition - array
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_departments_data($type) {
        $columns = ['d.id', 'd.name', 'd.description', 't.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('d.*');
        $this->db->where(array(
            'd.is_delete' => 0
        ));
        if (!empty($keyword['value'])) {
            $where = '(d.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR d.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(d.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_DEPARTMENTS . ' d');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_DEPARTMENTS . ' d');
            return $query;
        }
    }

    /**
     * It will get all the records of vendor for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 20/02/2018]
     */
    public function get_vendors_data($type) {
        $columns = ['v.id', 'v.name', 'u.email_id', 'u.username', 'v.contact_person', 'v.description', 'v.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('v.*,u.email_id,u.username');
        $this->db->where(array(
            'v.is_delete' => 0
        ));
        if (!empty($keyword['value'])) {
            $where = '(v.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR v.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR v.contact_person LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR v.contact_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(v.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id = v.user_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_VENDORS . ' v');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_VENDORS . ' v');
            return $query;
        }
    }

    /**
     * It will get all the records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 20/02/2018]
     */
    public function get_items_data($type) {
        $columns = ['i.id', 'i.part_no', 'd.name', 'v.name', 'i.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name');
        $this->db->where(array(
            'i.is_delete' => 0
        ));
        if (!empty($keyword['value'])) {
            $where = '(i.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.internal_part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR d.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR v.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(i.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.preferred_vendor=v.id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ITEMS . ' i');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ITEMS . ' i');
            return $query;
        }
    }

    /**
     * It will get all the records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 20/02/2018]
     */
    public function get_user_items_data($type=NULL) {
        $columns = ['i.id','it.image as dimage', 'i.part_no','i.description','i.part_location','i.retail_price','total_quantity','d.name','i.upc_barcode','i.global_part_no','i.is_delete'];
        $keyword = $this->input->get('search');
        $search_filed = array_column(array_column($this->input->get('columns'), 'search'), 'value');
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name,it.total_quantity,it.image as dimage');
        $this->db->where(array(
            'i.is_delete' => 0,
            'i.business_user_id' => checkUserLogin('C')
        ));
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
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query;
        }
       
    }

    /**
     * It will get all the records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 14/10/2020]
     */
    public function get_user_items_data_trash($type=NULL) {
        $columns = ['','','i.id','', 'i.part_no','i.retail_price','total_quantity',''];
        $keyword = $this->input->get('search');
        $search_filed = array_column(array_column($this->input->get('columns'), 'search'), 'value');
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name,it.total_quantity,it.image as dimage');
        $this->db->where(array(
            'i.is_delete' => 1,
            'i.business_user_id' => checkUserLogin('C'),
            'i.modified_date >' => date('Y-m-d', strtotime('-30 days')),
        ));
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
        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.vendor_id=v.id', 'left');
        $this->db->join(TBL_ITEMS . ' as it', 'it.id=i.referred_item_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query;
        }
    }

    /**
     * It will user quantity records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 14/02/2020]
     */
    public function get_user_items_quantity_data($record_id) {
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name,it.total_quantity,it.image as dimage');
        $this->db->where(array(
            'i.is_delete' => 0,
            'i.business_user_id' => checkUserLogin('C'),
            'i.id' => $record_id,
        ));
       
        if(!empty($this->session->userdata('u_location_id')) && ($this->session->userdata('u_location_id') != NULL)){
            $loc_id = $this->session->userdata('u_location_id');
            $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 and location_id = '. $loc_id .'  group by item_id) it', 'it.item_id=i.id', 'left');
        } else {
            $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        }

        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.vendor_id=v.id', 'left');
        $this->db->join(TBL_ITEMS . ' as it', 'it.id=i.referred_item_id', 'left');
        $query = $this->db->get(TBL_USER_ITEMS . ' i');
        return $query->result_array();  
    }

    /**
     * It will get all the records for quickbook
     * @param  : $type - string
     * @return : Object
     * @author KBH [Last Edited : 17-10-2019]
     */
    public function get_user_items_data_quickbook($item_id = null) {
        $columns = ['i.id','it.image as dimage', 'i.part_no','i.description','v.name','i.retail_price','total_quantity','d.name','i.upc_barcode','i.global_part_no','i.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name,it.total_quantity,it.image as dimage');
        $this->db->where(array(
            'i.is_delete' => 0,
            'i.business_user_id' => checkUserLogin('C'),
            'i.id' => $item_id
        ));
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.vendor_id=v.id', 'left');
        $this->db->join(TBL_ITEMS . ' as it', 'it.id=i.referred_item_id', 'left');
        $query = $this->db->get(TBL_USER_ITEMS . ' i');
        return $query;
    }

    /**
     * It will get all the records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 20/02/2018]
     */
    public function get_vendor_items_data($type) {
        if (checkVendorLogin('R') != 5) {
            $this->db->select('*,u.id as uid');
            $this->db->from(TBL_USERS . ' u');
            $this->db->where(['u.id' => checkVendorLogin('I')]);
            $this->db->join(TBL_CARD_DETAILS . ' c', 'u.id = c.user_id', 'left');
            $res = $this->db->get();
            $user_arr = $res->row_array();

            $user_id = $user_arr['business_user_id'];
        } else {
            $user_id = checkVendorLogin('I');
        }

        $columns = ['i.id', 'i.part_no', 'i.internal_part_no', 'd.name', 'i.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('i.*,d.name as dept_name');
        $this->db->where(array(
            'i.is_delete' => 0,
        ));
        if (!empty($keyword['value'])) {
            $where = '(i.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR d.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.internal_part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(i.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
//        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.preferred_vendor=v.id AND v.user_id =' . $user_id);
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ITEMS . ' i');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ITEMS . ' i');
            return $query;
        }
    }

    /**
     * It will get all the records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 20/02/2018]
     */
    public function get_location_items_data($type, $location_id = null) {
        $columns = ['

        il. id', 'i.global_part_no', 'i.part_no', 'v.name', 'il.quantity', 'il.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('il.*, i.*, v.name as pref_vendor_name');
        $this->db->where(array(
            'i.is_delete' => 0,
            'il.location_id' => $location_id,
            'i.business_user_id' => checkUserLogin('C')
        ));
        if (!empty($keyword['value'])) {
            $where = '(i.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.global_part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR v.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(il.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_USER_ITEMS . ' as i', 'i.id = il.item_id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.vendor_id = v.id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ITEM_LOCATION_DETAILS . ' il');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ITEM_LOCATION_DETAILS . ' il');
            return $query;
        }
    }

    /**
     * It will get all the records of item bu its id.
     * @param  : $id - Int
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_item_details($id = '') {
        $this->db->select('i.*, v.name as pref_vendor_name');
        $this->db->from(TBL_ITEMS . ' as i');
        $this->db->join(TBL_VENDORS . ' as v', 'i.preferred_vendor = v.id and v.is_delete = 0', 'left');
        if ($id != '') {
            $this->db->where('i.id', $id);
        }
        $this->db->where(array(
            'i.is_delete' => 0
        ));
        $this->db->order_by('i.part_no', 'ASC');
        return $this->db->get();
    }

    /**
     * It will get all the records of item bu its id.
     * @param  : $id - Int
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_tool_details($id = '') {
        $this->db->select('en.*, et.name as type_name, m.name as manu_name, en.description as equip_name');
        $this->db->from(TBL_EQUIPMENT_NAMES . ' as en');
        $this->db->join(TBL_EQUIPMENT_TYPES . ' as et', 'et.id = en.equipment_type_id and et.is_deleted = 0', 'left');
        $this->db->join(TBL_MANUFACTURERES . ' as m', 'en.manufacturer_id = m.id and et.is_deleted = 0', 'left');
        if ($id != '') {
            $this->db->where('en.id', $id);
        }
        $this->db->where(array(
            'en.is_deleted' => 0
        ));
        $this->db->order_by('et.name', 'ASC');
        return $this->db->get();
    }

    /**
     * It will get all the records of vendor for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 20/02/2018]
     */
    public function get_vendor_data($table, $where = null) {
        $this->db->select('v.*, u.email_id, u.username');
        $this->db->where(array(
            'v.is_delete' => 0
        ));
        if (!is_null($where)) {
            $this->db->where($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id = v.user_id', 'left');
        $query = $this->db->get($table . ' v');
        return $query;
    }

    /**
     * It will get all the records of items with vendor for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 20/02/2018]
     */
    public function get_inventory_value() {
        $this->db->select('ui.*, ui.id as i_id, v.name as vendor_name, i.internal_part_no');
        $this->db->from(TBL_USER_ITEMS . ' as ui');
        $this->db->join(TBL_ITEMS . ' as i', 'i.id = ui.referred_item_id AND i.is_delete = 0', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'ui.vendor_id = v.id and v.is_delete = 0', 'left');
        $this->db->where(array(
            'ui.is_delete' => 0,
            'ui.business_user_id' => checkUserLogin('C')
        ));
        $this->db->order_by('ui.part_no', 'ASC');
        return $this->db->get();
    }

    /**
     * It will get all the records of items with vendor for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 20/02/2018]
     */
    public function get_inventory_vendor_data() {
        $this->db->select('ui.*, ui.id as i_id, v.name as vendor_name, i.internal_part_no');
        $this->db->from(TBL_USER_ITEMS . ' as ui');
        $this->db->join(TBL_ITEMS . ' as i', 'i.id = ui.referred_item_id AND i.is_delete = 0', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'ui.vendor_id = v.id and v.is_delete = 0', 'left');
        $this->db->where(array(
            'ui.is_delete' => 0,
            'ui.business_user_id' => checkUserLogin('C')
        ));
        $this->db->order_by('ui.part_no', 'ASC');
        return $this->db->get();
    }

    public function get_total_quantity($item_id) {
        $this->db->select('ui.id, IF(it.total_quantity IS NULL, 0, it.total_quantity) as total_quantity');
        $this->db->where(['ui.id' => $item_id]);
        $this->db->join('(SELECT item_id, SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id = ui.id', 'left');
        $this->db->from(TBL_USER_ITEMS . ' as ui');
        $res = $this->db->get()->row_array();
        return $res['total_quantity'];
    }

    public function get_inventory_history($type) {
        $columns = ['ih.id', 'ih.created_date', 'ih.its_for', 'u.full_name', 'ui.part_no', '', 'ih.notes', 'ih.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('ih.*,u.full_name,ih.created_date as created_date,ui.part_no');
        $this->db->join(TBL_USERS . ' as u', 'ih.user_id=u.id and u.is_delete=0', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id=ih.item_id', 'left');
        $this->db->where(array(
            'ih.is_deleted' => 0,
            'ih.business_user_id' => checkUserLogin('C')
        ));
        if (!empty($keyword['value'])) {
            $where = '(ih.notes LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ui.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . 'OR u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ih.created_date LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_INVENTORY_HISTORY . ' ih');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_INVENTORY_HISTORY . ' ih')->result_array();
           
            if (!empty($query)) {
                foreach ($query as $k => $v) {

                    if ($v['its_for'] == 'receive') {
                        $this->db->select('ivd.id,l.name,ivd.quantity');
                        $this->db->join(TBL_LOCATIONS . ' as l', 'l.id=ivd.location_id', 'left');
                        $this->db->where_in('ivd.id', explode(',', $v['relate_id']));
                        $res = $this->db->get(TBL_ITEM_INVENTORY_DETAILS . ' as ivd')->result_array();
                        $des = '';
                        if (!empty($res)) {
                            $des = $query[$k]['full_name'] . ' has been added new inventory for <b>' . $query[$k]['part_no'] . '</b> Item.';
                            foreach ($res as $r) {
                                $des .= '<br/><b>' . $r['name'] . '</b> : ' . $r['quantity'];
                            }
                        }
                        $query[$k]['description'] = $des;
                        
                        } else if ($v['its_for'] == 'adjust') {
                        
                        $this->db->select('ivd.id,l.name,ivd.quantity');
                        $this->db->join(TBL_LOCATIONS . ' as l', 'l.id=ivd.location_id', 'left');
//                        $this->db->join(TBL_ITEM_LOCATION_DETAILS . ' as ild', 'l.id=ivd.location_id', 'right');
                        $this->db->where_in('ivd.id', explode(',', $v['relate_id']));
                        $res = $this->db->get(TBL_ITEM_INVENTORY_DETAILS . ' as ivd')->result_array();
                        $des = '';
                        if (!empty($res)) {
                            $des = $query[$k]['full_name'] . ' has been adjusted inventory for <b>' . $query[$k]['part_no'] . '</b> Item.';
                            foreach ($res as $r) {
                                $des .= '<br/><b>' . $r['name'] . '</b> : ' . ($r['quantity']);
                            }
                        }
                        $query[$k]['description'] = $des;
                    } else {
                        $this->db->select('ilt.id,lf.name as from_location,lt.name as to_location,ilt.quantity');
                        $this->db->join(TBL_LOCATIONS . ' as lf', 'lf.id=ilt.from_location_id', 'left');
                        $this->db->join(TBL_LOCATIONS . ' as lt', 'lt.id=ilt.to_location_id', 'left');
                        $this->db->where_in('ilt.id', $v['relate_id']);
                        $res = $this->db->get(TBL_ITEM_LOCATION_TRANSFER_DETAILS . ' as ilt')->row_array();
                        $des = '';
                        if (!empty($res)) {
                            $des = $query[$k]['full_name'] . ' has been moved <b>' . $res['quantity'] . '</b> inventory from <b>' . $res['from_location'] . '</b> to <b>' . $res['to_location'] . '</b> for <b>' . $query[$k]['part_no'] . '</b> Item.';
                        }
                        $query[$k]['description'] = $des;
                    }
                }
            }
           // die();
            return $query;
        }
    }

    public function get_trans_items($id = null) {
        if (!is_null($id)):
            $this->db->select('t.make_id,t.model_id,t.year_id,ti.id');
            $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
            $this->db->where('ti.items_id', $id);
            $res = $this->db->get(TBL_TRANSPONDER_ITEMS . ' as ti')->result_array();
            return $res;
        endif;
    }

    public function edit_trans_items($id = null) {
        if (!is_null($id)):
            $this->db->select('t.make_id,t.model_id,t.year_id,ti.id');
            $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
            $this->db->where('ti.items_id', $id);
            $this->db->group_by('t.make_id,t.model_id');
            $res = $this->db->get(TBL_TRANSPONDER_ITEMS . ' as ti')->result_array();
            return $res;
        endif;
    }

    public function get_user_trans_items($id = null) {
        if (!is_null($id)):
            $this->db->select('t.make_id,t.model_id,t.year_id,ti.id');
            $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
            $this->db->where('ti.items_id', $id);
            $res = $this->db->get(TBL_TRANSPONDER_USER_ITEMS . ' as ti')->result_array();
            return $res;
        endif;
    }

    /**
     * It will get all the records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 20/02/2018]
     */
    public function get_vendor_reports_data($type) {
        $columns = ['i.id', 'i.global_part_no', 'i.part_no', 'd.name', 'compatibility', 'i.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('i.*,d.name as dept_name,item.internal_part_no,i.internal_part_no as user_internal_part_no,item.description as item_description,i.description as user_item_description,GROUP_CONCAT(CONCAT(c.name, " ", `m`.`name`, " ", y.name)) as compatibility');
        $this->db->where(array(
            'i.is_delete' => 0,
        ));
        if (!empty($keyword['value'])) {
            $where = '(i.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.global_part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR d.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR c.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR y.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR `m`.`name` LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(i.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
//        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.vendor_id=v.id AND v.user_id = ' . checkVendorLogin('I'), 'left');
//        $this->db->join(TBL_USERS . ' as u', 'i.business_user_id=u.id', 'left');
        $this->db->join(TBL_ITEMS . ' as item', 'i.referred_item_id=item.id', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'ti.items_id = i.referred_item_id', 'left');
        $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_COMPANY . ' as c', 'c.id=t.make_id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 'm.id=t.model_id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 'y.id=t.year_id and y.is_delete=0', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        $this->db->group_by('i.id');
        if ($type == 'count') {
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query;
        }
    }

    public function get_vendor_history_data($type) {
        if (checkVendorLogin('R') != 5) {
            $this->db->select('*,u.id as uid');
            $this->db->from(TBL_USERS . ' u');
            $this->db->where(['u.id' => checkVendorLogin('I')]);
            $res = $this->db->get();
            $user_arr = $res->row_array();

            $user_id = $user_arr['business_user_id'];
        } else {
            $user_id = checkVendorLogin('I');
        }

        $columns = ['vh.id', 'vh.created_date', 'item.part_no', 'vh.action', '', ''];
        $keyword = $this->input->get('search');
        $this->db->select('vh.*,item.part_no');
        $this->db->where(array(
            'vh.vendor_id' => $user_id
        ));
        if (!empty($keyword['value'])) {
            $where = '(vh.created_date LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR item.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR vh.action LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }
        $this->db->join(TBL_ITEMS . ' as item', 'vh.item_id=item.id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_VENDOR_HISTROY . ' vh');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_VENDOR_HISTROY . ' vh');
            return $query;
        }
    }

    public function get_searched_part_details($where) {
        //Get loggedin user details
        if (checkVendorLogin('R') != 5) {
            $this->db->select('*,u.id as uid');
            $this->db->from(TBL_USERS . ' u');
            $this->db->where(['u.id' => checkVendorLogin('I')]);
            $res = $this->db->get();
            $user_arr = $res->row_array();

            $user_id = $user_arr['business_user_id'];
        } else {
            $user_id = checkVendorLogin('I');
        }

        //Get vendor details
        $this->db->select('*');
        $this->db->from(TBL_VENDORS);
        $this->db->where(['user_id' => $user_id]);
        $res = $this->db->get();
        $vendor_details = $res->row_array();

        //Get Item data based on search text
        $this->db->select('*');
        $this->db->where(array(
            'preferred_vendor' => $vendor_details['id'],
            'is_delete' => 0
        ));

        if (!empty($where)) {
            if (!empty($where['part_no'])) {
                $this->db->like('part_no', $where['part_no']);
            }

            if (!empty($where['description'])) {
                $this->db->like('description', $where['description']);
            }
        }

        $query = $this->db->get(TBL_ITEMS);
        return $query->result_array();
    }

    /**
     * It will get all the records of Estimates for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_parts_compatability_data($type, $wh = null) {
        $user_id = checkVendorLogin('I');

        $this->db->select('*');
        $this->db->from(TBL_VENDORS);
        $this->db->where(['user_id' => $user_id]);
        $res = $this->db->get();
        $vendor_details = $res->row_array();

        $vendor_id = $vendor_details['id'];

        $columns = ['t.id', 'm.name', 'md.name', 'yr.name', 'compatible_parts'];
        $keyword = $this->input->get('search');
        $this->db->select('t.id, m.name AS make_name, md.name AS model_name, yr.name AS year_name, COUNT(i.id) AS compatible_parts');

        $this->db->where(array(
            't.is_delete' => 0,
        ));

        if (!is_null($wh)) {
            $this->db->where($wh);
        }

        if (!empty($keyword['value'])) {
            $where = '(m.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . '  OR md.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR yr.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }

        $this->db->join(TBL_COMPANY . ' as m', 't.make_id = m.id', 'left');
        $this->db->join(TBL_MODEL . ' as md', 't.model_id = md.id', 'left');
        $this->db->join(TBL_YEAR . ' as yr', 't.year_id = yr.id', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 't.id = ti.transponder_id', 'left');
        $this->db->join(TBL_ITEMS . ' as i', 'ti.items_id = i.id AND i.preferred_vendor=' . $vendor_id . ' AND i.is_delete = 0', 'left');

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        $this->db->group_by('ti.transponder_id');

        if ($type == 'count') {
            $query = $this->db->get(TBL_TRANSPONDER . ' as t');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_TRANSPONDER . ' as t');
            return $query->result_array();
        }
    }

    public function export_vendor_all_items($user_id) {
        $this->db->select('i.part_no,i.internal_part_no,d.name as department,i.description,v.name as vendor,i.manufacturer');
        $this->db->where(array(
            'i.is_delete' => 0,
        ));

        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.preferred_vendor=v.id AND v.user_id =' . $user_id);
        $this->db->order_by('i.id','DESC');

        $query = $this->db->get(TBL_ITEMS . ' i');
        return $query->result_array();
    }

    /**
     * It will get all the records of vendor from datatable
     * @return : Object
     * @author JJP [Last Edited : 24/09/2019]
     */
     public function get_vendor_list(){
        $vendor = $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();
        $vendor_list = explode(',', $vendor['vendor_id']);
        $this->db->where('is_delete',0);
        $this->db->where_in('id',$vendor_list);
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('vendors');
        if($query->num_rows()>0){
            return $query->result_array();
        }else{
            return false;
        }
    }

    /**
     * It will get all the records of Locations for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function invoice_inventory_history_ajax_data($type) {
        $columns = ['i.id', 'i.created_date', 'i.its_for', 'i.user_name', 'ui.part_no', '',''];
        $keyword = $this->input->get('search');
        $this->db->select('i.*,ui.part_no');
        $this->db->where(array(
            'i.is_deleted' => 0,
            'i.user_id' => checkUserLogin('C'),
        ));

        if (!empty($keyword['value'])) {
            $where = '(i.user_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ui.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }

        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = i.item_name', 'left');

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_INVOICE_INVENTORY . ' as i');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_INVOICE_INVENTORY . ' as i');
            return $query->result_array();
        }
    }

}

/* End of file Inventory_model.php */
/* Location: ./application/models/Inventory_model.php */