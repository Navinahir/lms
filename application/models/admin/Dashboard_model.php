<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends MY_Model {

    /**
     * It will get all the records of transponder for ajax datatable
     * @param  : $condition - array
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_transponder_details($condition) {
        $this->db->select("t.*,c.name as make_name,c.id as make_id,m.name as model_name,y.name as year_name,GROUP_CONCAT(COALESCE(i.part_no,'') SEPARATOR ':-:') as parts_no,GROUP_CONCAT(COALESCE(i.id,'') SEPARATOR ':-:') as parts_id,GROUP_CONCAT(COALESCE(v.name,'') SEPARATOR ':-:') as vendor_name,GROUP_CONCAT(COALESCE(i.qty_on_hand,'') SEPARATOR ':-:') as qty_on_hand,GROUP_CONCAT(COALESCE(ta.field_name,'') SEPARATOR ':-:') as field_name,GROUP_CONCAT(COALESCE(ta.field_value,'') SEPARATOR ':-:') as field_value");
        $this->db->from(TBL_TRANSPONDER . ' as t');
        $this->db->join(TBL_COMPANY . ' as c', 't.make_id=c.id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 't.model_id=m.id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id and y.is_delete=0', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_ITEMS . ' as i', 'ti.items_id=i.id', 'left');
        $this->db->join(TBL_TRANSPONDER_ADDITIONAL . ' as ta', 't.id=ta.transponder_id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.preferred_vendor=v.id', 'left');
        $this->db->where(array(
            't.is_delete' => 0,
            't.status' => 'active',
        ));
        $this->db->where($condition);
        $this->db->having('parts_no!=' . NULL);
        return $this->db->get();
    }

    /**
     * It will get all the records of transponder for ajax datatable
     * @param  : $condition - array
     * @return : Object
     * @author JJP [Last Edited : 25/09/2019]
     */
    public function get_user_transponder_details_filter($condition, $settings) {
        $vendor = '';
        $g_vendor = '';
        $u_vendor = '';
        $vendors = (isset($settings) && $settings['vendor_id'] != null) ? explode(',', $settings['vendor_id']) : '';
        if ($vendors != '') {
//            $this->db->where_in('v.id', $vendors);
            $vendor = ' AND v.id IN (' . implode(',', $vendors) . ') AND v.is_active=1';
            $g_vendor = ' AND vg.id IN (' . implode(',', $vendors) . ') AND vg.is_active=1';
            $u_vendor = ' AND vg.id IN (' . implode(',', $vendors) . ') AND vg.is_active=1';
        }
        $this->db->select("t.*,i.*,d.*,c.name as make_name,c.id as make_id,m.name as model_name,y.name as year_name,GROUP_CONCAT(COALESCE(ui.part_no,'') SEPARATOR ':-:') as parts_no,GROUP_CONCAT(COALESCE(ui.global_part_no,'') SEPARATOR ':-:') as user_global_parts_no,GROUP_CONCAT(COALESCE(ui.internal_part_no,'') SEPARATOR ':-:') as user_internal_parts_no,GROUP_CONCAT(COALESCE(i.internal_part_no,'') SEPARATOR ':-:') as globalalternatepart,GROUP_CONCAT(COALESCE(d.name,'') SEPARATOR ':-:') as department_name,GROUP_CONCAT(COALESCE(i.manufacturer,'') SEPARATOR ':-:') as manufacturer,GROUP_CONCAT(COALESCE(i.image,'') SEPARATOR ':-:') as globalimage,GROUP_CONCAT(COALESCE(i.description,'') SEPARATOR ':-:') as globaldescription,GROUP_CONCAT(COALESCE(i.part_no,'') SEPARATOR ':-:') as global_parts_no,GROUP_CONCAT(COALESCE(t.id,'') SEPARATOR ':-:') as transponder_id,GROUP_CONCAT(COALESCE(ui.id,'') SEPARATOR ':-:') as parts_id,GROUP_CONCAT(COALESCE(i.id,'') SEPARATOR ':-:') as global_parts_id,GROUP_CONCAT(COALESCE(uit.id,'') SEPARATOR ':-:') as non_global_parts_id,GROUP_CONCAT(COALESCE(uit.part_no,'') SEPARATOR ':-:') as non_global_parts_no,GROUP_CONCAT(COALESCE(v.name,'') SEPARATOR ':-:') as vendor_name,GROUP_CONCAT(COALESCE(vg.name,'') SEPARATOR ':-:') as global_vendor_name,GROUP_CONCAT(COALESCE(ug.name,'') SEPARATOR ':-:') as non_global_vendor_name,GROUP_CONCAT(COALESCE(it.total_quantity,'') SEPARATOR ':-:') as qty_on_hand,GROUP_CONCAT(COALESCE(nit.total_quantity,'') SEPARATOR ':-:') as my_qty_on_hand,GROUP_CONCAT(COALESCE(ta.field_name,'') SEPARATOR ':-:') as field_name,GROUP_CONCAT(COALESCE(ta.field_value,'') SEPARATOR ':-:') as field_value");
        $this->db->from(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_COMPANY . ' as c', 't.make_id=c.id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 't.model_id=m.id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id and y.is_delete=0', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_ITEMS . ' as i', 'ti.items_id=i.id AND i.is_delete = 0', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'i.id=ui.referred_item_id AND ui.is_delete = 0 AND ui.business_user_id = ' . checkUserLogin('C'), 'left');
        $this->db->join(TBL_TRANSPONDER_USER_ITEMS . ' as uti', 't.id=uti.transponder_id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as uit', 'uit.id=uti.items_id AND uit.is_delete = 0 AND uit.business_user_id = ' . checkUserLogin('C') . ' AND uit.global_part_no IS NULL', 'left');
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=ui.id', 'left');
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) nit', 'nit.item_id=uit.id', 'left');
        $this->db->join(TBL_TRANSPONDER_ADDITIONAL . ' as ta', 't.id=ta.transponder_id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'ui.vendor_id=v.id' . $vendor, 'left');
        $this->db->join(TBL_VENDORS . ' as vg', 'i.preferred_vendor=vg.id' . $g_vendor, 'left');
        $this->db->join(TBL_VENDORS . ' as ug', 'uit.vendor_id=ug.id' . $u_vendor, 'left');

        $this->db->join(TBL_DEPARTMENTS. ' as d', 'i.department_id=d.id');

        $this->db->where(array(
            't.is_delete' => 0,
            't.status' => 'active',
        ));

        $this->db->where($condition);
        // $this->db->group_by('i.part_no,i.preferred_vendor');
        $this->db->having('parts_no IS NOT NULL AND global_parts_no !=' . NULL);        
        return $this->db->get();
    }

    /**
     * It will get all the records of transponder for ajax datatable
     * @param  : $condition - array
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_user_transponder_details($condition, $settings, $temp) {
        $vendor = '';
        $g_vendor = '';
        $u_vendor = '';
        $vendors = (isset($settings) && $settings['vendor_id'] != null) ? explode(',', $settings['vendor_id']) : '';
        if ($vendors != '') {
//            $this->db->where_in('v.id', $vendors);
            $vendor = ' AND v.id IN (' . implode(',', $vendors) . ') AND v.is_active=1';
            $g_vendor = ' AND vg.id IN (' . implode(',', $vendors) . ') AND vg.is_active=1';
            $u_vendor = ' AND vg.id IN (' . implode(',', $vendors) . ') AND vg.is_active=1';
            
            $vendor_list = $this->inventory_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->row_array();
            $vendor_id = explode(',', $vendor_list['vendor_id']);
        }
        
        // GROUP_CONCAT(COALESCE(dep.name,'') SEPARATOR ':-:') as mypartdepartment,
        // ORDER BY it.total_quantity DESC 
        $this->db->select("t.*,i.*,d.*,c.name as make_name,c.id as make_id,m.name as model_name,y.name as year_name,
            GROUP_CONCAT(COALESCE(ui.part_no,'') SEPARATOR ':-:') as parts_no,
            GROUP_CONCAT(COALESCE(uit.part_no,'') SEPARATOR ':-:') as mypartpartsno,
            GROUP_CONCAT(COALESCE(uit.description,'') SEPARATOR ':-:') as mypartdescription,
            GROUP_CONCAT(COALESCE(uit.image,'') SEPARATOR ':-:') as mypartimage,
            GROUP_CONCAT(COALESCE(uit.internal_part_no,'') SEPARATOR ':-:') as mypartinternalpart,
            GROUP_CONCAT(COALESCE(uit.retail_price,'') SEPARATOR ':-:') as mypartrate,
            GROUP_CONCAT(COALESCE(uit.manufacturer,'') SEPARATOR ':-:') as mypartmanufacturer,
            GROUP_CONCAT(COALESCE(dep.name,'') SEPARATOR ':-:') as mypartdepartment,
            GROUP_CONCAT(COALESCE(ui.global_part_no,'') SEPARATOR ':-:') as user_global_parts_no,
            GROUP_CONCAT(COALESCE(ui.internal_part_no,'') SEPARATOR ':-:') as user_internal_parts_no,
            GROUP_CONCAT(COALESCE(ui.retail_price,'') SEPARATOR ':-:') as retail_price,
            GROUP_CONCAT(COALESCE(i.internal_part_no,'') SEPARATOR ':-:') as globalalternatepart,
            GROUP_CONCAT(COALESCE(d.name,'') SEPARATOR ':-:') as department_name,
            GROUP_CONCAT(COALESCE(i.manufacturer,'') SEPARATOR ':-:') as manufacturer,
            GROUP_CONCAT(COALESCE(i.image,'') SEPARATOR ':-:') as globalimage,
            GROUP_CONCAT(COALESCE(i.description,'') SEPARATOR ':-:') as globaldescription,
            GROUP_CONCAT(COALESCE(i.part_no,'') SEPARATOR ':-:') as global_parts_no,
            GROUP_CONCAT(COALESCE(t.id,'') SEPARATOR ':-:') as transponder_id,
            GROUP_CONCAT(COALESCE(ui.id,'') SEPARATOR ':-:') as parts_id,
            GROUP_CONCAT(COALESCE(i.id,'') SEPARATOR ':-:') as global_parts_id,
            GROUP_CONCAT(COALESCE(uit.id,'') SEPARATOR ':-:') as non_global_parts_id,
            GROUP_CONCAT(COALESCE(uit.part_no,'') SEPARATOR ':-:') as non_global_parts_no,
            GROUP_CONCAT(COALESCE(v.name,'') SEPARATOR ':-:') as vendor_name,
            GROUP_CONCAT(COALESCE(vg.name,'') SEPARATOR ':-:') as global_vendor_name,
            GROUP_CONCAT(COALESCE(ug.name,'') SEPARATOR ':-:') as non_global_vendor_name,
            GROUP_CONCAT(COALESCE(it.total_quantity,'') SEPARATOR ':-:') as qty_on_hand,
            GROUP_CONCAT(COALESCE(nit.total_quantity,'') SEPARATOR ':-:') as my_qty_on_hand,
            GROUP_CONCAT(COALESCE(ta.field_name,'') SEPARATOR ':-:') as field_name,
            GROUP_CONCAT(COALESCE(ta.field_value,'') SEPARATOR ':-:') as field_value");

        $this->db->from(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_COMPANY . ' as c', 't.make_id=c.id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 't.model_id=m.id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id and y.is_delete=0', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_ITEMS . ' as i', 'ti.items_id=i.id AND i.is_delete = 0', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'i.id=ui.referred_item_id AND ui.is_delete = 0 AND ui.business_user_id = ' . checkUserLogin('C'), 'left');
        $this->db->join(TBL_TRANSPONDER_USER_ITEMS . ' as uti', 't.id=uti.transponder_id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as uit', 'uit.id=uti.items_id AND uit.is_delete = 0 AND uit.business_user_id = ' . checkUserLogin('C') . ' AND uit.global_part_no IS NULL', 'left');
        if(!empty($this->session->userdata('u_location_id')) && ($this->session->userdata('u_location_id') != NULL)){
            $loc_id = $this->session->userdata('u_location_id');
            $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 and location_id = '. $loc_id .' group by item_id) it', 'it.item_id=ui.id', 'left');
        } else {
            $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=ui.id', 'left');   
        }
        // $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=ui.id', 'left');
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) nit', 'nit.item_id=uit.id', 'left');
        $this->db->join(TBL_TRANSPONDER_ADDITIONAL . ' as ta', 't.id=ta.transponder_id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'ui.vendor_id=v.id' . $vendor, 'left');
        $this->db->join(TBL_VENDORS . ' as vg', 'i.preferred_vendor=vg.id' . $g_vendor, 'left');
        // $this->db->join(TBL_VENDORS . ' as ug', 'uit.vendor_id=ug.id' . $u_vendor, 'left');
        $this->db->join(TBL_VENDORS . ' as ug', 'uit.vendor_id=ug.id', 'left');

        $this->db->join(TBL_DEPARTMENTS. ' as d', 'i.department_id=d.id');
        $this->db->join(TBL_DEPARTMENTS. ' as dep', 'uit.department_id=dep.id', 'left');
        
        $this->db->where(array(
            't.is_delete' => 0,
            't.status' => 'active',
        ));

        if ($vendors != '') {
            $this->db->where_in('i.preferred_vendor',$vendor_id);
            // $this->db->where_in('uit.vendor_id',$vendor_id);
        }

        if($temp == ""){
            $this->db->where($condition);
        }

        if($temp == "multipul_In_Stock_Out_Of_Stock"){
            $this->db->where('ui.global_part_no != "" ');
            $this->db->where($condition);      
        }

        if($temp == "multipul_In_Stock"){
            $this->db->where('it.total_quantity > 0');
            $this->db->where($condition);       
        } 
        
        if($temp == "multipul_Out_Of_Stock"){
            $this->db->where('it.total_quantity <= 0');
            $this->db->where($condition);           
        } 
        
        if($temp == "vendor_multipul") {
            $v = implode(',', $condition['vg.name']);
            $this->db->where_in('vg.name' , explode(',', $v));
            $this->db->where(array(
                't.make_id' => $condition['t.make_id'],
                't.model_id' => $condition['t.model_id'],
                't.year_id' => $condition['t.year_id'],
            ));
        }
        
        if($temp == "manufacturer_multipul") {
            $v = implode(',', $condition['d.name']);
            $this->db->where_in('d.name' , explode(',', $v));
            $this->db->where(array(
                't.make_id' => $condition['t.make_id'],
                't.model_id' => $condition['t.model_id'],
                't.year_id' => $condition['t.year_id'],
            ));
        } 

        // $this->db->where($condition);      
        // $this->db->group_by('i.part_no,i.preferred_vendor');
        $this->db->having('parts_no IS NOT NULL AND global_parts_no !=' . NULL);
        
        // $abc = $this->db->get()->result_array();
        // pr($abc); die();
        
        return $this->db->get();
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
            return $query->result();
        }else{
            return false;
        }
     }

     /**
     * It will get all the records of department from datatable
     * @return : Object
     * @author JJP [Last Edited : 24/09/2019]
     */
     public function get_department_list(){
        $this->db->where('is_delete',0);
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('departments');
        if($query->num_rows()>0){
            return $query->result();
        }else{
            return false;
        }
     }

    
    /**
     * It will get all the records of transponder for ajax datatable
     * @param  : $condition - array
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_vendor_transponder_details($condition) {
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

        $this->db->select("t.*,c.name as make_name,c.id as make_id,m.name as model_name,y.name as year_name,GROUP_CONCAT(COALESCE(i.part_no,'') SEPARATOR ':-:') as parts_no,GROUP_CONCAT(COALESCE(i.id,'') SEPARATOR ':-:') as parts_id,GROUP_CONCAT(COALESCE(v.name,'') SEPARATOR ':-:') as vendor_name,GROUP_CONCAT(COALESCE(i.qty_on_hand,'') SEPARATOR ':-:') as qty_on_hand,GROUP_CONCAT(COALESCE(ta.field_name,'') SEPARATOR ':-:') as field_name,GROUP_CONCAT(COALESCE(ta.field_value,'') SEPARATOR ':-:') as field_value");
        $this->db->from(TBL_TRANSPONDER . ' as t');
        $this->db->join(TBL_COMPANY . ' as c', 't.make_id=c.id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 't.model_id=m.id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id and y.is_delete=0', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_ITEMS . ' as i', 'ti.items_id=i.id AND i.is_delete = 0', 'left');
        $this->db->join(TBL_TRANSPONDER_ADDITIONAL . ' as ta', 't.id=ta.transponder_id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.preferred_vendor=v.id AND v.user_id = ' . $user_id, 'left');
        $this->db->where(array(
            't.is_delete' => 0,
            't.status' => 'active',
        ));
        $this->db->where($condition);
        $this->db->having('parts_no!=' . NULL);
        return $this->db->get();
    }

    public function get_stripe_details($table, $condition) {
        $this->db->select('*');
        $this->db->where($condition);
        return $this->db->get($table);
    }

    public function get_recent_search_details($table, $condition) {
        $this->db->select('t.*,c.name as make_name,m.name as model_name,y.name as year_name');
        $this->db->where($condition);
        $this->db->join(TBL_COMPANY . ' as c', 't.make_id=c.id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 't.model_id=m.id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id and y.is_delete=0', 'left');
        $this->db->order_by('id', 'desc');
        $this->db->group_by('t.make_id, t.model_id, t.year_id');
        $this->db->limit(10, 0);
        return $this->db->get($table . ' t');
    }

    public function get_user_recent_search_details($table, $condition) {
        $this->db->select('t.*,c.name as make_name,m.name as model_name,y.name as year_name');
        $this->db->where($condition);
        $this->db->join(TBL_COMPANY . ' as c', 't.make_id=c.id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 't.model_id=m.id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id and y.is_delete=0', 'left');
        $this->db->order_by('t.id', 'desc');
        // $this->db->group_by('t.make_id, t.model_id, t.year_id');
        $this->db->limit(10, 0);
        return $this->db->get($table . ' as t');
    }

    public function get_application_details($search_value) {
        if (!is_null($search_value)) {
            $this->db->select("t.fcc_id,t.chip_ID,c.name as make_name,c.id as make_id,m.name as model_name,y.name as year_name,i.part_no as parts_no,i.id as parts_id,v.name as vendor_name");
            $this->db->from(TBL_TRANSPONDER . ' as t');
            $this->db->join(TBL_COMPANY . ' as c', 't.make_id=c.id and c.status="active" and c.is_delete=0', 'left');
            $this->db->join(TBL_MODEL . ' as m', 't.model_id=m.id and m.status="active" and m.is_delete=0', 'left');
            $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id and y.is_delete=0', 'left');
            $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 't.id=ti.transponder_id', 'left');
            $this->db->join(TBL_ITEMS . ' as i', 'ti.items_id=i.id', 'left');
            $this->db->join(TBL_VENDORS . ' as v', 'i.preferred_vendor=v.id', 'left');
            $this->db->where(array(
                't.is_delete' => 0,
                't.status' => 'active',
            ));
            $where = '(i.description LIKE ' . $this->db->escape('%' . $search_value . '%') . ' OR t.fcc_id LIKE ' . $this->db->escape('%' . $search_value . '%') . ' OR t.chip_ID LIKE ' . $this->db->escape('%' . $search_value . '%') . ')';
            $this->db->where($where);
            $this->db->group_by('parts_id');
            $this->db->having('parts_no!=' . NULL);
            return $this->db->get();
        }
    }

    public function get_fcc_details($type) {
        $search_value = $this->input->get('q');
        $columns = ['t.id', 'c.name,m.name,y.name', 'i.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select("t.id,t.fcc_id,c.name as make_name,m.name as model_name,y.name as year_name,c.id as make_id,m.id as model_id,y.id as year_id");
        $this->db->join(TBL_COMPANY . ' as c', 't.make_id=c.id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 't.model_id=m.id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id and y.is_delete=0', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_ITEMS . ' as i', 'ti.items_id=i.id', 'left');
//        $this->db->join(TBL_VENDORS . ' as v', 'i.preferred_vendor=v.id', 'left');
        $this->db->where(array(
            't.is_delete' => 0,
            't.status' => 'active',
        ));

        // $where = '(i.description LIKE ' . $this->db->escape('%' . $search_value . '%') . ' OR t.fcc_id LIKE ' . $this->db->escape('%' . $search_value . '%') . ')';
        $where = '(t.fcc_id LIKE ' . $this->db->escape('%' . $search_value . '%') . ')';
        
        $this->db->where($where);
        if (!empty($keyword['value'])) {
            $where = '(c.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR m.name LIKE "%' . $keyword['value'] . '%" OR y.name LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->group_by('t.id');
//        $this->db->having('i.part_no IS NOT NULL');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_TRANSPONDER . ' as t');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_TRANSPONDER . ' as t');
            return $query->result_array();
        }
    }

    public function get_chip_details($type) {
        $search_value = $this->input->get('q');
        $columns = ['t.id', 'c.name,m.name,y.name', 'i.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select("t.id,t.chip_ID,c.name as make_name,m.name as model_name,y.name as year_name,c.id as make_id,m.id as model_id,y.id as year_id");
        $this->db->join(TBL_COMPANY . ' as c', 't.make_id=c.id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 't.model_id=m.id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id and y.is_delete=0', 'left');
        $this->db->where(array(
            't.is_delete' => 0,
            't.status' => 'active',
        ));
        $where = '(t.chip_ID LIKE ' . $this->db->escape('%' . $search_value . '%') . ')';
        $this->db->where($where);
        if (!empty($keyword['value'])) {
            $where = '(c.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR m.name LIKE "%' . $keyword['value'] . '%" OR y.name LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_TRANSPONDER . ' as t');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_TRANSPONDER . ' as t');
            return $query->result_array();
        }
    }

    public function get_cust_details($type) {
        $search_value = $this->input->get('q');
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.cust_name', 'e.is_invoiced,e.is_sent', 'e.total', 'e.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
        ));

        $where = '(e.cust_name LIKE ' . $this->db->escape('%' . $search_value . '%') . ')';
        $this->db->where($where);
        if (!empty($keyword['value'])) {
            $where = '(e.cust_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_date LIKE "%' . $keyword['value'] . '%" OR e.total LIKE "%' . $keyword['value'] . '%")';
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

    public function get_part_details($type) {
        $search_value = $this->input->get('q');
        $columns = ['i.id', 'i.part_no', 'v.name', 'i.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select("i.*,v.name as vendor_name");
        $this->db->join(TBL_VENDORS . ' as v', 'i.preferred_vendor=v.id', 'left');
        $this->db->where(array(
            'i.is_delete' => 0,
        ));
        $where = '(i.part_no LIKE ' . $this->db->escape('%' . $search_value . '%') . ')';
        $this->db->where($where);
        if (!empty($keyword['value'])) {
            $where = '(v.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.part_no LIKE "%' . $keyword['value'] . '%" OR i.internal_part_no LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
//        $this->db->group_by('i.id');
//        $this->db->having('i.part_no IS NOT NULL');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ITEMS . ' as i');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ITEMS . ' as i');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of transponder for ajax datatable
     * @param  : $condition - array
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_api_vendor_transponder_details($condition, $vendor_id) {
        $this->db->select("t.*,c.name as make_name,c.id as make_id,m.name as model_name,y.name as year_name,GROUP_CONCAT(COALESCE(i.part_no,'') SEPARATOR ':-:') as parts_no,GROUP_CONCAT(COALESCE(i.id,'') SEPARATOR ':-:') as parts_id,GROUP_CONCAT(COALESCE(v.name,'') SEPARATOR ':-:') as vendor_name,GROUP_CONCAT(COALESCE(i.qty_on_hand,'') SEPARATOR ':-:') as qty_on_hand,GROUP_CONCAT(COALESCE(ta.field_name,'') SEPARATOR ':-:') as field_name,GROUP_CONCAT(COALESCE(ta.field_value,'') SEPARATOR ':-:') as field_value");
        $this->db->from(TBL_TRANSPONDER . ' as t');
        $this->db->join(TBL_COMPANY . ' as c', 't.make_id=c.id and c.status="active" and c.is_delete=0', 'left');
        $this->db->join(TBL_MODEL . ' as m', 't.model_id=m.id and m.status="active" and m.is_delete=0', 'left');
        $this->db->join(TBL_YEAR . ' as y', 't.year_id=y.id and y.is_delete=0', 'left');
        $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 't.id=ti.transponder_id', 'left');
        $this->db->join(TBL_ITEMS . ' as i', 'ti.items_id=i.id AND i.is_delete = 0', 'left');
        $this->db->join(TBL_TRANSPONDER_ADDITIONAL . ' as ta', 't.id=ta.transponder_id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.preferred_vendor=v.id AND v.id = ' . $vendor_id, 'left');
        $this->db->where(array(
            't.is_delete' => 0,
            't.status' => 'active',
        ));
        $this->db->where($condition);
        $this->db->having('parts_no!=' . NULL);
        return $this->db->get();
    }

    public function get_vin_details($type) {
        $search_value = $this->input->get('q');
        $columns = ['e.id', 'e.estimate_id', 'e.estimate_date', 'e.is_invoiced'];
        $keyword = $this->input->get('search');

        $this->db->select("e.*");
        $this->db->where(array(
            'e.is_deleted' => 0,
        ));

        $where = '(e.vin_id LIKE ' . $this->db->escape('%' . $search_value . '%') . ')';
        $this->db->where($where);

        if (!empty($keyword['value'])) {
            $where = '(e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . '")';
            $this->db->where($where);
        }

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

}

/* End of file Dashboard_model.php */
/* Location: ./application/models/Dashboard_model.php */