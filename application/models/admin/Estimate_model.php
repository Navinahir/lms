<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estimate_model extends MY_Model {

    /**
     * It will update estimation status(Send OR Not Send)
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 25/07/2019]
     */
    public function updatestatus($id,$setdstatus){
        $this->db->where('id',$id);
        $this->db->update('estimates',$setdstatus);
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * It will get all the records of Estimates for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_estimates_data($type, $wh = null) {
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.cust_name', 'u.full_name', 'e.is_sent', 'e.total', 'e.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 0,
        ));
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
     * It will get all deleted records of Estimates for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_estimates_data_trash($type, $wh = null) {
        $columns = ['','','e.id', 'e.estimate_date', 'e.estimate_id', 'e.cust_name', 'u.full_name', 'e.is_sent', 'e.total', 'e.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 1,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 0,
            'e.modified_date >' => date('Y-m-d', strtotime('-30 days')),
        ));
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

    public function get_estimate($id = null, $user_id = null) {
        if (is_null($user_id)) {
            $user_id = checkUserLogin('C');
        }
        $this->db->select('e.*,u.first_name, u.last_name,u.username, u.full_name,com.name as make_name,m.name as modal_name,y.name as year_name,c.name as color_name,t.name as tax_name,t.rate as tax_per,u.contact_number,u.address as user_address,p.name as payment_name');
        $this->db->where(array(
            'e.id' => $id,
            'e.is_deleted' => 0,
            'e.business_user_id' => $user_id,
        ));

        $this->db->join(TBL_MODEL . ' as m', 'm.id = e.modal_id', 'left');
        $this->db->join(TBL_COMPANY . ' as com', 'com.id = e.make_id', 'left');
        $this->db->join(TBL_YEAR . ' as y', 'y.id = e.year_id', 'left');
        $this->db->join(TBL_VEHICLE_COLORS . ' as c', 'c.id = e.color_id', 'left');
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->join(TBL_TAXES . ' as t', 't.id = e.tax_id', 'left');
        $this->db->join(TBL_PAYMENT_METHODS . ' as p', 'p.id = e.payment_method_id', 'left');
        $query = $this->db->get(TBL_ESTIMATES . ' as e');
        $estimate = $query->row_array();
        if (!empty($estimate)) {
            $this->db->select('ep.*,ui.part_no, v.name as v1_name,ui.image,itm.image as global_part_image,d.name as dept_name,it.total_quantity,l.name as location_name,t.name as tax_name,t.rate as t_rate');
            $this->db->where(array(
                'ep.is_deleted' => 0,
                'ep.estimate_id' => $id,
            ));
            $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
            $this->db->join(TBL_ITEMS . ' as itm', 'itm.id = ui.referred_item_id', 'left');
            $this->db->join(TBL_VENDORS . ' as v', 'v.id = ui.vendor_id', 'left');
            $this->db->join(TBL_DEPARTMENTS . ' as d', 'ui.department_id=d.id', 'left');
            $this->db->join(TBL_LOCATIONS . ' as l', 'l.id=ep.location_id', 'left');
            $this->db->join(TBL_TAXES . ' as t', 't.id = ep.tax_id', 'left');
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

            $this->db->select('es.*,t.name as tax_name,t.rate as t_rate,s.name as service_name,s.rate as service_rate');
            $this->db->where(array(
                'es.is_deleted' => 0,
                'es.estimate_id' => $id,
            ));
            $this->db->join(TBL_SERVICES . ' as s', 's.id=es.service_id', 'left');
            $this->db->join(TBL_TAXES . ' as t', 't.id = es.tax_id', 'left');
            $service = $this->db->get(TBL_ESTIMATE_SERVICES . ' as es')->result_array();
            $estimate['services'] = $service;
        }
        return $estimate;
    }

    function get_location_qty($item_id, $location_id) {
        $query = $this->db->query('SELECT item_id,location_id,IF(quantity IS NULL,0,quantity) as location_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 AND item_id=' . $item_id . ' AND location_id=' . $location_id)->row_array();
        return $query['location_quantity'];
//        $this->db->join(TBL_ITEM_LOCATION_DETAILS . ' as il', 'il.item_id=' . $item_id . ' AND il.location_id=' . $location_id, 'left');
    }

    function get_all_parts($part_id, $location_id, $transponder_id = null, $type = null) {
        $select = '';
        if (!is_null($transponder_id)) {
            $select = ',it.total_quantity,t.make_id as item_make_id,t.model_id as item_model_id,t.year_id as item_year_id';
        }
        $this->db->select('i.*,d.name as dept_name,v1.name as v1_name' . $select);
        $this->db->from(TBL_USER_ITEMS . ' AS i');
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.vendor_id=v1.id', 'left');
        if (!is_null($transponder_id)) {
            if (!is_null($type)) {
                $this->db->join(TBL_TRANSPONDER_USER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
            } else {
                $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.referred_item_id=ti.items_id', 'left');
            }
            $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id AND t.id = ' . $transponder_id, 'left');
            $this->db->having('item_make_id IS NOT NULL AND item_model_id IS NOT NULL AND item_year_id IS NOT NULL');
        }

        $this->db->where('i.id', $part_id);
        $result = $this->db->get();
        $data['itemArr'] = $result->row_array();
        if (!empty($data['itemArr'])) {
            $this->db->select('il.*');
            $this->db->where(['il.item_id' => $part_id, 'il.location_id' => $location_id]);
            $this->db->join(TBL_LOCATIONS . ' l', 'l.id=il.location_id', 'left');
            $location_qty = $this->db->get(TBL_ITEM_LOCATION_DETAILS . ' il')->row_array();
            $data['itemArr']['location_quantity'] = $location_qty['quantity'];
            if (!is_null($transponder_id)) {
                $this->db->select("ui.part_no,ui.id,v.name,it.total_quantity,ui.retail_price,i.part_no as global_part,d.name as dept_name");
                $this->db->where(['t.make_id' => $data['itemArr']['item_make_id'], 't.model_id' => $data['itemArr']['item_model_id'], 't.year_id' => $data['itemArr']['item_year_id']]);
                $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 't.id=ti.transponder_id', 'left');
                $this->db->join(TBL_ITEMS . ' as i', 'ti.items_id=i.id', 'left');
                $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.referred_item_id=i.id', 'left');
                $this->db->join(TBL_VENDORS . ' as v', 'ui.vendor_id=v.id', 'left');
                $this->db->join(TBL_DEPARTMENTS . ' as d', 'ui.department_id=d.id', 'left');
                
                if(!empty($this->session->userdata('u_location_id')) && ($this->session->userdata('u_location_id') != NULL)){
                $loc_id = $this->session->userdata('u_location_id');
                $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 and location_id = '. $loc_id .' group by item_id) it', 'it.item_id=ui.id', 'left');
                } else {
                $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=ui.id', 'left');
                }

                $this->db->where(array(
                    'ui.is_delete' => 0,
                    't.is_delete' => 0,
                    't.status' => 'active',
                    'ui.business_user_id' => checkUserLogin('C')
                ));
                $res_t = $this->db->get(TBL_TRANSPONDER . ' AS t')->result_array();
                $data['itemArr']['AllParts'] = $res_t;
            }
        }

        return $data;
    }

    function get_all_parts_multiple($part_id, $location_id, $transponder_id = null, $type = null) {
        $select = '';
        if (!is_null($transponder_id)) {
            $select = ',it.total_quantity,t.make_id as item_make_id,t.model_id as item_model_id,t.year_id as item_year_id';
        }
        $this->db->select('i.*,d.name as dept_name,v1.name as v1_name' . $select);
        $this->db->from(TBL_USER_ITEMS . ' AS i');
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' AS d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' AS v1', 'i.vendor_id=v1.id', 'left');
        if (!is_null($transponder_id)) {
            if (!is_null($type)) {
                $this->db->join(TBL_TRANSPONDER_USER_ITEMS . ' as ti', 'i.id=ti.items_id', 'left');
            } else {
                $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 'i.referred_item_id=ti.items_id', 'left');
            }
            $this->db->join(TBL_TRANSPONDER . ' as t', 't.id=ti.transponder_id AND t.id = ' . $transponder_id, 'left');
            $this->db->having('item_make_id IS NOT NULL AND item_model_id IS NOT NULL AND item_year_id IS NOT NULL');
        }
        $this->db->where_in('i.id', explode(',', $part_id));
        // $this->db->where('i.id', $part_id);
        
        $result = $this->db->get();
        $data['itemArr_multi']['items_list'] = $result->result_array();

        if(!empty($data['itemArr_multi']['items_list'])) {
            $this->db->select('il.*');

            $this->db->where_in('il.item_id',explode(',',$part_id));
            $this->db->where('il.location_id',$location_id);

            // $this->db->where(['il.item_id' => $part_id, 'il.location_id' => $location_id]);
            
            $this->db->join(TBL_LOCATIONS . ' l', 'l.id=il.location_id', 'left');
            
            $location_qty = $this->db->get(TBL_ITEM_LOCATION_DETAILS . ' il')->result_array();
            
            $location_qty = array_column($location_qty, 'quantity');

            // Location Array
            $data['itemArr_multi']['location_quantity'] = $location_qty;

            $item_make_id = array_column($data['itemArr_multi']['items_list'],'item_make_id')[0]; 
            $item_model_id = array_column($data['itemArr_multi']['items_list'],'item_model_id')[0];
            $item_year_id = array_column($data['itemArr_multi']['items_list'], 'item_year_id')[0];
           
            if (!is_null($transponder_id)) {
                $this->db->select("ui.part_no,ui.id,v.name,it.total_quantity,ui.retail_price,i.part_no as global_part,d.name as dept_name");
                // $this->db->where(['t.make_id' => $data['itemArr_multi']['item_make_id'], 't.model_id' => $data['itemArr_multi']['item_model_id'], 't.year_id' => $data['itemArr_multi']['item_year_id']]);
                $this->db->where('t.make_id',$item_make_id);
                $this->db->where('t.model_id',$item_model_id);
                $this->db->where('t.year_id',$item_year_id);
                $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 't.id=ti.transponder_id', 'left');
                $this->db->join(TBL_ITEMS . ' as i', 'ti.items_id=i.id', 'left');
                $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.referred_item_id=i.id', 'left');
                $this->db->join(TBL_VENDORS . ' as v', 'ui.vendor_id=v.id', 'left');
                $this->db->join(TBL_DEPARTMENTS . ' as d', 'ui.department_id=d.id', 'left');
                if(!empty($this->session->userdata('u_location_id')) && ($this->session->userdata('u_location_id') != NULL)){
                $loc_id = $this->session->userdata('u_location_id');
                $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 and location_id = '. $loc_id .' group by item_id) it', 'it.item_id=ui.id', 'left');
                } else {
                $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=ui.id', 'left');
                }
                $this->db->where(array(
                    'ui.is_delete' => 0,
                    't.is_delete' => 0,
                    't.status' => 'active',
                    'ui.business_user_id' => checkUserLogin('C')
                ));
                $res_t = $this->db->get(TBL_TRANSPONDER . ' AS t')->result_array();

                // Compitable Part List Array
                $data['itemArr_multi']['AllParts'] = $res_t;

                // pr($data['itemArr_multi']);
                // pr($data['itemArr_multi']['items_list']);
                // pr($data['itemArr_multi']['location_quantity']);
                // pr($data['itemArr_multi']['AllParts']); die();
                // echo $this->db->last_query(); die();

            }
        }

        return $data;
    }    

    public function get_all_parts_by_make($array) {
        if (!empty($array)) {
            $this->db->select("ui.part_no,ui.id,v.name,it.total_quantity,ui.retail_price,i.part_no as global_part,i.image as item_image,d.name as dept_name");
            $this->db->where(['t.make_id' => $array['make_id'], 't.model_id' => $array['model_id'], 't.year_id' => $array['year_id']]);
            $this->db->join(TBL_TRANSPONDER_ITEMS . ' as ti', 't.id=ti.transponder_id', 'left');
            $this->db->join(TBL_ITEMS . ' as i', 'ti.items_id=i.id', 'left');
            $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.referred_item_id=i.id', 'left');
            $this->db->join(TBL_VENDORS . ' as v', 'ui.vendor_id=v.id', 'left');
            $this->db->join(TBL_DEPARTMENTS . ' as d', 'ui.department_id=d.id', 'left');
            $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=ui.id', 'left');
            $this->db->where(array(
                'ui.is_delete' => 0,
                't.is_delete' => 0,
                't.status' => 'active',
                'ui.business_user_id' => checkUserLogin('C')
            ));
            $res_t = $this->db->get(TBL_TRANSPONDER . ' AS t')->result_array();
            $data['AllParts'] = $res_t;
            return $data;
        }
    }

    public function get_recent_estimates_data() {
        $this->db->select('e.*');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 0
        ));
        $this->db->order_by('e.id', 'desc');
        $this->db->limit(5, 0);
        $query = $this->db->get(TBL_ESTIMATES . ' as e');
        return $query->result_array();
    }


     /**
     * It get single Estimates from id
     * @param  : $type - string
     * @return : Object
     * @author KBH (05-11-2019)
     */
    public function get_estimates_from_id($wh = null) {
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.cust_name', 'u.full_name', 'e.is_sent', 'e.total', 'e.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 0,
        ));
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
        
        $this->db->limit($this->input->get('length'), $this->input->get('start'));
        $query = $this->db->get(TBL_ESTIMATES . ' as e');
        return $query->row_array();
    }
}

/* End of file Estimate_model.php */
/* Location: ./application/models/admin/Estimate_model.php */