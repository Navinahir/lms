<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends MY_Model {
	
	public function get_item_report($type, $wh = null) {        
        $columns = ['items.id', 'items.part_no', 'items.item_qr_code', 'items.image','items.manufacturer','items.preferred_vendor_part'];

        $keyword = $this->input->get('search');
        // $this->db->query('SELECT * FROM `items` WHERE image IS NULL OR image = "" OR item_qr_code IS NULL OR item_qr_code = "" ');

        $this->db->select('items.*');
        $this->db->where(array(
            'items.is_delete' => 0, 
        ));
        // $this->db->where('image IS NULL OR image = "" OR item_qr_code IS NULL OR item_qr_code = "" ');

        if (!is_null($wh)) {
            $this->db->where($wh);
        }

        if (!empty($keyword['value'])) {
            $where = '(items.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ') ';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        
        // print_r($this->db->last_query()); die();
        if ($type == 'count') {
            $query = $this->db->get(TBL_ITEMS . ' as items');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ITEMS . ' as items');
            return $query->result_array();
        }
    }
	
	public function get_transponder_report($condition){
		extract($condition);
		$this->db->select('t.*,c.name as make_name,m.name as model_name,y.name as year_name,i.qty_on_hand');
		$this->db->from(TBL_TRANSPONDER.' as t');
		$this->db->join(TBL_COMPANY.' as c','t.make_id=c.id and c.is_delete=0 and c.status="active"','left');
		$this->db->join(TBL_MODEL.' as m','t.model_id=m.id and m.is_delete=0 and m.status="active"','left');
		$this->db->join(TBL_YEAR.' as y','t.year_id=y.id and y.is_delete=0','left');
		$this->db->join(TBL_TRANSPONDER_ITEMS.' as ti','t.id=ti.transponder_id','left');
        $this->db->join(TBL_ITEMS.' as i','ti.items_id=i.id','left');
		if(!empty($condition)){
			if(isset($keyword) && $keyword!=''){
				$this->db->group_start();
					$this->db->like('y.name',$keyword);
					$this->db->or_like('c.name',$keyword);
					$this->db->or_like('m.name',$keyword);
				$this->db->group_end();
			}
			if(isset($strattec_non_remote_key) && $strattec_non_remote_key!=''){
				$this->db->where('i.part_no',$strattec_non_remote_key);
			}
			if(isset($status) && $status!='all'){
				$this->db->where('t.status',$status);
			}
		}
		$this->db->where('t.is_delete',0);
		$this->db->group_by('t.id');
		$this->db->order_by('make_name');
		return $this->db->get();
		
	}

	/**
     * It will get all the records of transponder for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 18/02/2018]
     */
    public function get_transponder($type) {
        $filter = [];
        if($this->input->get('make_id') != "" || $this->input->get('model_id') != "" || $this->input->get('year_id') != "")
        {
            if($this->input->get('make_id') != "" && $this->input->get('model_id') == "" && $this->input->get('year_id') == "")
            {
                $filter = array(
                    't.make_id' => $this->input->get('make_id'),
                );
            }
            if($this->input->get('make_id') == "" && $this->input->get('model_id') != "" && $this->input->get('year_id') == "")
            {
                $filter = array(
                    't.model_id' => $this->input->get('model_id'),
                );
            }
            if($this->input->get('make_id') == "" && $this->input->get('model_id') == "" && $this->input->get('year_id') != "")
            {
                $filter = array(
                    't.year_id' => $this->input->get('year_id'),
                );
            }
            if($this->input->get('make_id') != "" && $this->input->get('model_id') != "" && $this->input->get('year_id') == "")
            {
                $filter = array(
                    't.make_id' => $this->input->get('make_id'),
                    't.model_id' => $this->input->get('model_id'),
                );
            }
            if($this->input->get('make_id') != "" && $this->input->get('model_id') == "" && $this->input->get('year_id') != "")
            {
                $filter = array(
                    't.make_id' => $this->input->get('make_id'),
                    't.year_id' => $this->input->get('year_id'),
                );
            }
            if($this->input->get('make_id') == "" && $this->input->get('model_id') != "" && $this->input->get('year_id') != "")
            {
                $filter = array(
                    't.model_id' => $this->input->get('model_id'),
                    't.year_id' => $this->input->get('year_id'),
                );
            }
            if($this->input->get('make_id') != "" && $this->input->get('model_id') != "" && $this->input->get('year_id') != "")
            {
                $filter = array(
                    't.make_id' => $this->input->get('make_id'),
                    't.model_id' => $this->input->get('model_id'),
                    't.year_id' => $this->input->get('year_id')
                );
            }
        }
        // if($this->input->get('make_id') != "" || $this->input->get('model_id') != "" || $this->input->get('year_id') != "")
        // {
        //     if($this->input->get('make_id') != ""){
        //         $filter = array(
        //             't.make_id' => $this->input->get('make_id'),
        //         );
        //     }
        //     if($this->input->get('make_id') != "" && $this->input->get('model_id') != "")
        //     {
        //         $filter = array(
        //             't.make_id' => $this->input->get('make_id'),
        //             't.model_id' => $this->input->get('model_id'),
        //         );
        //     }
        //     if($this->input->get('make_id') != "" && $this->input->get('model_id') != "" && $this->input->get('year_id') != "")
        //     {
        //         $filter = array(
        //             't.make_id' => $this->input->get('make_id'),
        //             't.model_id' => $this->input->get('model_id'),
        //             't.year_id' => $this->input->get('year_id')
        //         );
        //     }
        // }   
        $columns = ['t.id', 'c.name', 'm.name','y.name','t.notes','t.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select("t.*,c.name as make_name,m.name as model_name,y.name as year_name");
        $this->db->where(array(
            't.is_delete' => 0,
            'm.is_delete' => 0,
            'c.is_delete' => 0
        ));
        if(!empty($filter))
        {
            $this->db->where($filter);    
        }

        if(!empty($this->input->get('txt_strattec_part'))){
            $this->db->where('i.part_no', $this->input->get('txt_strattec_part'));
        }

        if(!empty($this->input->get('txt_status')) && $this->input->get('txt_status') != 'all'){
             $this->db->where('t.status',$this->input->get('txt_status'));
        }

        if (!empty($keyword['value'])) {
            $where = '(c.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR m.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR y.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.notes LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(t.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }

        $this->db->join(TBL_COMPANY.' as c','t.make_id=c.id','left');
        $this->db->join(TBL_MODEL.' as m','t.model_id=m.id','left');
        $this->db->join(TBL_YEAR.' as y','t.year_id=y.id','left');

        if(!empty($this->input->get('txt_strattec_part'))){
            $this->db->join(TBL_TRANSPONDER_ITEMS.' as ti','t.id=ti.transponder_id','left');
            $this->db->join(TBL_ITEMS.' as i','ti.items_id=i.id','left');
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_TRANSPONDER . ' t');
        	return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        	$query = $this->db->get(TBL_TRANSPONDER . ' t');
            return $query;
        }
    }

    /**
     * It will get all the records of item bu its id.
     * @param  : $id - Int
     * @return : Object
     * @author JJP [Last Edited : 02/03/2020]
     */
    public function get_item_details($id = '') {
        $this->db->select('i.id');
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
     * It will get all the records of transponder with selected yead for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 18/02/2018]
     */
    public function get_transponder_year_filter($type) {
        $filter = [];
        if($this->input->get('year_id') != "")
        {
            $filter = array(
                'y.name' => $this->input->get('year_id'),
            );
            // pr($filter); die();
        }   
        $columns = ['t.id', 'c.name', 'm.name','y.name','t.notes','t.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select("t.*,c.name as make_name,m.name as model_name,y.name as year_name");
        $this->db->where(array(
            't.is_delete' => 0,
            'm.is_delete' => 0,
            'c.is_delete' => 0
        ));

        if(!empty($filter))
        {
            $this->db->where($filter);    
        }

        if(!empty($this->input->get('txt_strattec_part'))){
            $this->db->where('i.part_no', $this->input->get('txt_strattec_part'));
        }

        if(!empty($this->input->get('txt_status')) && $this->input->get('txt_status') != 'all'){
             $this->db->where('t.status',$this->input->get('txt_status'));
        }

        if (!empty($keyword['value'])) {
            $where = '(c.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR m.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR y.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.notes LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(t.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }

        $this->db->join(TBL_COMPANY.' as c','t.make_id=c.id','left');
        $this->db->join(TBL_MODEL.' as m','t.model_id=m.id','left');
        $this->db->join(TBL_YEAR.' as y','t.year_id=y.id','left');

        if(!empty($this->input->get('txt_strattec_part'))){
            $this->db->join(TBL_TRANSPONDER_ITEMS.' as ti','t.id=ti.transponder_id','left');
            $this->db->join(TBL_ITEMS.' as i','ti.items_id=i.id','left');
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_TRANSPONDER . ' t');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_TRANSPONDER . ' t');
            return $query;
        }
    }
}

/* End of file Reports.php */
/* Location: ./application/models/Reports.php */