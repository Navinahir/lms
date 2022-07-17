<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_report_model extends MY_Model {

    /**
     * It will get all the records of part cost for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/05/2020]
     */
    public function get_part_cost_data($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['e.id', 'e.estimate_id', 'ui.part_no', 'ep.quantity', '', 'ep.tax_rate', 'ui.unit_cost', ''];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,ui.part_no,ep.quantity,ep.discount,ep.discount_type_id,ep.tax_rate,ep.rate,ep.amount,ui.unit_cost');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(ui.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.quantity LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.tax_rate LIKE "%' . $keyword['value'] . '%" OR ui.unit_cost LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the taxable part sales total AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/05/2020]
     */
    public function get_total_taxable_part_sales($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['', 'e.estimate_id', 'ui.part_no', 'ep.quantity', '', 'ep.tax_rate'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,ui.part_no,ep.quantity,ep.discount,ep.discount_type_id,ep.tax_rate,ep.rate,ep.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0,
            'ep.tax_id > ' => 0,
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ui.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.tax_rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.quantity LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Gross Sale Invoice for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 21/05/2020]
     */
    public function get_shipping_charge($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.shipping_charge'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'e.shipping_charge >'=> 0
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_date LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.shipping_charge LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of discount Service Sales AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/05/2020]
     */
    public function get_service_discount_sales($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['', 'e.id', 's.name', 'es.qty', '', 'es.discount_rate'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,s.name,es.qty,es.discount,es.discount_type_id,es.tax_rate,es.rate,es.amount,es.discount_rate');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'es.is_deleted' => 0,
            'es.discount_rate >' => 0
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(s.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }
        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->join(TBL_SERVICES . ' as s', 's.id = es.service_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the discount of Net Part Sales AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 01/06/2020]
     */
    public function get_part_discount_sales($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['', 'e.estimate_id', 'ui.part_no', 'ep.quantity', '', 'ep.discount_rate'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,ui.part_no,ep.quantity,ep.discount,ep.discount_type_id,ep.discount_rate,ep.rate,ep.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0,
            'ep.discount_rate >' => 0
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ui.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.discount_rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.quantity LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of non taxable Service Sales AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/05/2020]
     */
    public function get_non_taxable_service_sales($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['', 'e.estimate_id', 's.name', 'es.qty', '', 'es.tax_rate', 'es.rate','es.amount'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,s.name,es.qty,es.discount,es.discount_type_id,es.tax_rate,es.rate,es.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'es.is_deleted' => 0,
            'es.tax_id' => 0,
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR s.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.qty LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.tax_rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.amount LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }
        // if (!empty($keyword['value'])) {
        //     $where = '(s.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
        //     $this->db->where($where);
        // }
        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->join(TBL_SERVICES . ' as s', 's.id = es.service_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the non taxable part sales AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/05/2020]
     */
    public function get_non_taxable_part_sales($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['', 'e.estimate_id', 'ui.part_no', 'ep.quantity', '', 'ep.tax_rate', 'ep.rate','ep.amount'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,ui.part_no,ep.quantity,ep.discount,ep.discount_type_id,ep.tax_rate,ep.rate,ep.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0,
            'ep.tax_id' => 0,
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ui.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.tax_rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.amount LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.quantity LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }
        // if (!empty($keyword['value'])) {
        //     $where = '(ui.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
        //     $this->db->where($where);
        // }
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of taxable Service Sales AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/05/2020]
     */
    public function get_taxable_service_sales($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['', 'e.estimate_id', 's.name', 'es.qty', '', 'es.tax_rate', 'es.rate','es.amount'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,s.name,es.qty,es.discount,es.discount_type_id,es.tax_rate,es.rate,es.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'es.is_deleted' => 0,
            'es.tax_id > ' => 0,
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR s.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.qty LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.tax_rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.amount LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }
        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->join(TBL_SERVICES . ' as s', 's.id = es.service_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the taxable part sales AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/05/2020]
     */
    public function get_taxable_part_sales($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['', 'e.estimate_id', 'ui.part_no', 'ep.quantity', '', 'ep.tax_rate', 'ep.rate','ep.amount'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,ui.part_no,ep.quantity,ep.discount,ep.discount_type_id,ep.tax_rate,ep.rate,ep.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0,
            'ep.tax_id > ' => 0,
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ui.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.tax_rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.amount LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.quantity LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Gross profit shipping for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 28/05/2020]
     */
    public function get_gross_profit_shipping($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.shipping_charge'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'e.shipping_charge >' => 0,
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_date LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.shipping_charge LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
            $this->db->where($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of gross profit Service cost AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/05/2020]
     */
    public function get_gross_profit_service_data($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['e.id', 'e.estimate_id', 's.name', 'es.qty', '', 'es.tax_rate', 'es.rate','es.amount'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,s.name,es.qty,es.discount,es.discount_type_id,es.tax_rate,es.rate,es.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'es.is_deleted' => 0
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(s.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.qty LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.tax_rate LIKE "%' . $keyword['value'] . '%" OR es.rate LIKE "%' . $keyword['value'] . '%" OR es.amount LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        // if (!empty($keyword['value'])) {
        //     $where = '(s.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' )';
        //     $this->db->where($where);
        // }
        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->join(TBL_SERVICES . ' as s', 's.id = es.service_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Gross profit part for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/05/2020]
     */
    public function get_gross_profit_part_data($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['e.id', 'e.estimate_id', 'ep.quantity', '', 'ep.tax_rate', 'ui.unit_cost', ''];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,ui.part_no,ep.quantity,ep.discount,ep.discount_type_id,ep.tax_rate,ep.rate,ep.amount,ui.unit_cost');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(ui.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.quantity LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.tax_rate LIKE "%' . $keyword['value'] . '%" OR ep.rate LIKE "%' . $keyword['value'] . '%" OR ep.amount LIKE "%' . $keyword['value'] . '%" OR ui.unit_cost LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Gross profit Invoice for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 28/05/2020]
     */
    public function get_gross_profit_invoices_data($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.sub_total', '', 'e.shipping_charge'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,e.estimate_date,e.sub_total,e.shipping_charge,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
        ));
        $this->db->group_by('e.id');
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.cust_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_date LIKE "%' . $keyword['value'] . '%" OR e.sub_total LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Net Service Sales AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/05/2020]get_gross_profit_shipping
     */
    public function get_service_net_sales($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['', 'e.id', 's.name', 'es.qty', '', 'es.tax_rate', 'es.rate','es.amount'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,s.name,es.qty,es.discount,es.discount_type_id,es.tax_rate,es.rate,es.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'es.is_deleted' => 0
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR s.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.qty LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR es.tax_rate LIKE "%' . $keyword['value'] . '%" OR es.rate LIKE "%' . $keyword['value'] . '%" OR es.amount LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->join(TBL_SERVICES . ' as s', 's.id = es.service_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Net Part Sales AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 08/06/2020]
     */
    public function get_net_sales_part($type, $wh = null){
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;

        $columns = ['', 'e.estimate_date', 'e.estimate_id', 'u.full_name', '', ''];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,e.estimate_date,e.total,e.sub_total,u.full_name,SUM(ep.amount) as part_amount,SUM(ep.discount_rate) as part_discount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0
        ));
        $this->db->group_by('e.id');
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_date LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.total LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        // $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

     /**
     * It will get all the records of Net Service Sales AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 08/06/2020]
     */
    public function get_net_sales_service($type, $wh = null){
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;

        $columns = ['', 'e.estimate_date', 'e.estimate_id', 'u.full_name', '', ''];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,e.estimate_date,e.total,e.sub_total,u.full_name,SUM(es.amount) as service_amount,SUM(es.discount_rate) as service_discount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'es.is_deleted' => 0
        ));
        $this->db->group_by('e.id');
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_date LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.total LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        // $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Net Part Sales AJAX Data
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/05/2020]
     */
    public function get_part_net_sales($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['', 'e.id', 'ui.part_no', 'ep.quantity', '', 'ep.tax_rate', 'ep.rate','ep.amount'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id,e.estimate_id,ui.part_no,ep.quantity,ep.discount,ep.discount_type_id,ep.tax_rate,ep.rate,ep.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(e.estimate_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ui.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.quantity LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ep.tax_rate LIKE "%' . $keyword['value'] . '%" OR ep.rate LIKE "%' . $keyword['value'] . '%" OR ep.amount LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->num_rows();
        } else {
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Gross Sale Invoice for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 21/05/2020]
     */
    public function get_invoices_data($type, $wh = null) {
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $from_date = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),2,6).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $to_date = $t_date_y.$t_date_m.$t_date_d;
               
        $columns = ['e.id', 'e.estimate_date', 'e.estimate_id', 'e.cust_name', 'u.full_name', 'e.total'];
        $keyword = $this->input->get('search');
        $this->db->select('e.*,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
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
            if($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' as e');
            return $query->result_array();
        }
    }


}
