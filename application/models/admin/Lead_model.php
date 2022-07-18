<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead_model extends MY_Model {

    /**
     * It will get all the records of transponder for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_lead($type) {
        // pr($this->input->get('txt_strattec_part'));
        // pr($this->input->get('txt_status'));


//        $columns = ['','c.name','m.name','t.notes','t.modified_date',''];
//        $keyword = $this->input->get('search');
//        $this->db->select('t.*,c.name as make_name,m.name as model_name,y.name as year_name');
//        $this->db->where(array(
//            't.is_delete' => 0,
//            'm.is_delete' => 0,
//            'c.is_delete' => 0
//        ));
//
//        if(!empty($this->input->get('txt_strattec_part'))){
//            $this->db->where('i.part_no', $this->input->get('txt_strattec_part'));
//        }
//
//        if(!empty($this->input->get('txt_status')) && $this->input->get('txt_status') != 'all'){
//             $this->db->where('t.status',$this->input->get('txt_status'));
//        }
//
//        if (!empty($keyword['value'])) {
//            $where = '(c.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR m.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR y.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.notes LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(t.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
//            $this->db->where($where);
//        }
//
//        $this->db->join(TBL_COMPANY.' as c','t.make_id=c.id','left');
//        $this->db->join(TBL_MODEL.' as m','t.model_id=m.id','left');
//        $this->db->join(TBL_YEAR.' as y','t.year_id=y.id','left');
//        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
        	
            $query = $this->db->get(TBL_LEAD . ' t');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_LEAD . ' t');
            return $query;
        }
    }

    /**
     * It will get all the records of transponder data its ID
     * @param  : $id - int
     * @return : Array
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_transponder_data_by_id($id){
        $this->db->select('t.*,c.name as make_name,m.name as model_name,y.name as year_name,i.qty_on_hand,i.qty_on_order');
        $this->db->from(TBL_TRANSPONDER.' t');
        $this->db->join(TBL_COMPANY.' as c','t.make_id=c.id and c.status="active" and c.is_delete=0','left');
        $this->db->join(TBL_MODEL.' as m','t.model_id=m.id and m.status="active" and m.is_delete=0','left');
        $this->db->join(TBL_YEAR.' as y','t.year_id=y.id and y.is_delete=0','left');
        $this->db->join(TBL_TRANSPONDER_ITEMS.' as ti','t.id=ti.transponder_id','left');
        $this->db->join(TBL_ITEMS.' as i','ti.items_id=i.id','left');
        $this->db->where(array(
            't.is_delete' => 0,
            't.status'    => 'active',
            't.id'        => $id
        ));
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * It will get all the records of year
     * @param $type string
     * @return $query -> Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_year($type) {
        $columns = ['y.id', 'y.name', 'y.modified_date', 'y.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('y.*');
        $this->db->where('y.is_delete', 0);
        if (!empty($keyword['value'])) {
            $where = '(y.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(y.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_YEAR . ' y');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_YEAR . ' y');
            return $query;
        }
    }

    /**
     * To check uniqueness at the time of ADD/EDIT functionality
     * @param string $table, array $condition
     * @return array()
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function check_unique_name($table, $condition) {
        $this->db->where($condition);
        $this->db->where('is_delete', 0);
        $query = $this->db->get($table);
        
        return $query->row_array();
    }

    /**
     * It will get all the records of make
     * @param $type string
     * @return $query -> Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_make($type) {
        $columns = ['c.id', 'c.name', 'c.modified_date', 'c.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('c.*');
        $this->db->where('c.is_delete', 0);
        if (!empty($keyword['value'])) {
            $where = '(c.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(c.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_COMPANY . ' c');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_COMPANY . ' c');
            return $query;
        }
    }

    /**
     * It will get all the records of model
     * @param $type string
     * @return $query -> Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_model($type) {
        $columns = ['m.id', 'm.name', 'c.name as make_name', 'm.modified_date', 'm.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('m.*,c.name as make_name');
        $this->db->where('m.is_delete', 0);
        if (!empty($keyword['value'])) {
            $where = '(m.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . 'OR c.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(m.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_COMPANY.' as c','m.make_id=c.id','left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_MODEL . ' m');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_MODEL . ' m');
            return $query;
        }
    }

    /**
     * It will get record of model by its ID
     * @param $type string
     * @return Object
     * @author PAV [Last Edited : 03/02/2018]
     */
    public function get_model_by_id($id){
        $this->db->select('m.*,c.name as make_name,c.id as make_id');
        $this->db->from(TBL_MODEL.' as m');
        $this->db->join(TBL_COMPANY.' as c','m.make_id=c.id','left');
        $this->db->where(array(
            'm.id'  => $id,
            'm.is_delete' => 0,
            'm.status' => 'active'
        ));
        return $this->db->get();
    }
}

/* End of file Product_model.php */
/* Location: ./application/models/Product_model.php */
