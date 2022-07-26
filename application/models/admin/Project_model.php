<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends MY_Model {

    /**
     * It will get all the records of transponder for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author PAV [Last Edited : 03/02/2018]
     */
	public function get_project($type) {
		$columns = ['system_size','warranties','category','electric_kit'];
		$keyword = $this->input->get('search');
//
		if (!empty($keyword['value'])) {
			$where = '(system_size LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR warranties LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR category LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR electric_kit LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
			$this->db->where($where);
		}

		$this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
		if ($type == 'count') {

			$query = $this->db->get(TBL_PROJECT);
			return $query->num_rows();
		} else {
			$this->db->limit($this->input->get('length'), $this->input->get('start'));
			$query = $this->db->get(TBL_PROJECT);
			return $query;
		}
	}
}

/* End of file Project_model.php */
/* Location: ./application/models/Project_model.php */
