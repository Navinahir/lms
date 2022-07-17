<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends MY_Model {

    /**
     * It will get all the records of low inventory for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_low_inventory_items_data($type, $wh = null) {
        $columns = ['i.id', 'i.global_part_no', 'd.name', 'v.name', 'i.retail_price', 'total_quantity'];
        $keyword = $this->input->get('search');
        $search_filed = array_column(array_column($this->input->get('columns'), 'search'), 'value');
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name,it.total_quantity');
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
                        if ($columns["$key"] == 'it.total_quantity') {
                            $whr_field .= $columns["$key"] . ' <= ' . $value;
                        } else {
                            $whr_field .= $columns["$key"] . ' LIKE ' . $this->db->escape('%' . $value . '%');
                        }
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
            $where = '(i.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.global_part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR d.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR v.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.retail_price LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR total_quantity LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(i.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.vendor_id=v.id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        if ($type == 'count') {
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query->num_rows();
        } else {
            if ($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query;
        }
    }

    /**
     * It will get all the records of low inventory for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_low_inventory_items_graph_data() {
        $this->db->select('i.part_no as name,it.total_quantity as value');
        $this->db->where(array(
            'i.is_delete' => 0,
            'i.business_user_id' => checkUserLogin('C')
        ));
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.vendor_id=v.id', 'left');
        
        // Display Only last 30 lower items
        $this->db->order_by('total_quantity','ASC');
        $this->db->limit(30);

        $query = $this->db->get(TBL_USER_ITEMS . ' i')->result_array();
        return $query;
    }

    public function get_low_inventory_items_data_pdf($qty = null) {
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name,it.total_quantity');
        $this->db->where(array(
            'i.is_delete' => 0,
            'i.business_user_id' => checkUserLogin('C'),
        ));
        if (!is_null($qty)) {
            $this->db->where(array(
                'total_quantity <= ' => $qty
            ));
        }
        $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.vendor_id=v.id', 'left');
        $this->db->limit($this->input->get('length'), $this->input->get('start'));
        $this->db->order_by('it.total_quantity', 'ASC');
        $query = $this->db->get(TBL_USER_ITEMS . ' i')->result_array();
        return $query;
    }

    /**
     * It will get all the records of low inventory for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_labor_and_services_data($type, $wh = null) {
        $columns = ['s.id', 's.name', 'total', 'estimate_rate'];
        $keyword = $this->input->get('search');
        $this->db->select('s.*,s.name as service_name,s.rate as service_rate,count(e.estimate_id) as total, SUM(es.amount) as estimate_rate_w,SUM(es.amount + es.tax_rate) as estimate_rate,DATE(e.estimate_date),COUNT(eo.estimate_id) as total_used');
        $this->db->where(array(
            's.is_deleted' => 0,
            's.business_user_id' => checkUserLogin('C')
        ));
        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));
            $this->db->where([
                'DATE(e.estimate_date) >=' => $date[0],
                'DATE(e.estimate_date) <=' => $date[1]
            ]);
        }
        if (!is_null($this->input->get('service')) && $this->input->get('service') != '') {
            $service = $this->input->get('service');
            $this->db->where_in('s.id', $service);
        }
        if (!empty($keyword['value'])) {
            $having = '(count(e.estimate_id) LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . 'OR SUM(es.amount + es.tax_rate) LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR s.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR s.rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->having($having);
        }
        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.service_id=s.id AND es.is_deleted = 0', 'left');
        // AND e.is_sent = 1
        $this->db->join(TBL_ESTIMATES . ' as e', 'es.estimate_id=e.id AND e.is_invoiced = 1 AND e.is_deleted = 0 ', 'right');
        // AND (eo.is_save_draft=1 OR eo.is_sent=1)
        $this->db->join(TBL_ESTIMATES . ' as eo', 'es.estimate_id=eo.id AND eo.is_invoiced = 1 AND eo.is_deleted = 0 ', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        $this->db->group_by(['s.id']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_SERVICES . ' s');
            return $query->num_rows();
        } else {
            if ($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_SERVICES . ' s');
            return $query;
        }
    }

    /**
     * It will get all the records of low inventory for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_labor_and_services_data_pdf($wh) {
        $this->db->select('s.*,s.name as service_name,s.rate as service_rate,count(e.estimate_id) as total, SUM(es.amount) as estimate_rate_w,SUM(es.amount + es.tax_rate) as estimate_rate,DATE(e.estimate_date)');
        $this->db->where(array(
            's.is_deleted' => 0,
            's.business_user_id' => checkUserLogin('C')
        ));
        if (!is_null($wh)):
            if (!is_null($wh['date'])) {
                $date = explode(':=:', $wh['date']);
                $this->db->where([
                    'DATE(e.estimate_date) >=' => $date[0],
                    'DATE(e.estimate_date) <=' => $date[1]
                ]);
            }
            if (!is_null($wh['service'])) {
                $this->db->where_in('s.id', $wh['service']);
            }
        endif;
        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.service_id=s.id AND es.is_deleted = 0', 'left');
        $this->db->join(TBL_TAXES . ' as t', 'es.tax_id=t.id AND t.is_deleted = 0', 'left');
        // AND e.is_sent = 1
        $this->db->join(TBL_ESTIMATES . ' as e', 'es.estimate_id=e.id AND e.is_invoiced = 1 AND e.is_deleted = 0 ', 'left');
        $this->db->order_by('estimate_rate', 'desc');
        $this->db->group_by(['s.id']);
        $this->db->limit($this->input->get('length'), $this->input->get('start'));
        $query = $this->db->get(TBL_SERVICES . ' s');
        return $query;
    }

    public function get_labor_and_services_graph_data($wh = null, $service = null) {
        $this->db->select('s.name as name,s.rate as service_rate,SUM(es.amount + es.tax_rate)as value,COUNT(e.estimate_id),COUNT(eo.estimate_id) as other_value');
        $this->db->where(array(
            's.is_deleted' => 0,
            's.business_user_id' => checkUserLogin('C')
        ));
        if (!is_null($wh)):
            if (!is_null($wh['date'])) {
                $date = explode(':=:', $wh['date']);
                $this->db->where([
                    'DATE(e.estimate_date) >=' => $date[0],
                    'DATE(e.estimate_date) <=' => $date[1]
                ]);
            }
            if (!is_null($wh['service']) && !is_null($service)) {
                $this->db->where_in('s.id', $wh['service']);
            }
        endif;
        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.service_id=s.id AND es.is_deleted = 0', 'left');
        // AND e.is_sent = 1
        $this->db->join(TBL_ESTIMATES . ' as e', 'es.estimate_id=e.id AND e.is_invoiced = 1 AND e.is_deleted = 0 ', 'left');
        // AND (eo.is_save_draft=1 OR eo.is_sent=1)
        $this->db->join(TBL_ESTIMATES . ' as eo', 'es.estimate_id=eo.id AND eo.is_invoiced = 1 AND eo.is_deleted = 0 ', 'left');
        $this->db->order_by('value', 'desc');
        $this->db->group_by('s.id');
        $query = $this->db->get(TBL_SERVICES . ' s')->result_array();
        return $query;
    }

    public function get_sales_by_category_data($type, $wh = null) {
        $columns = ['d.id', 'd.name', 'total', 'estimate_rate'];
        $keyword = $this->input->get('search');
        $this->db->select('d.*,d.name as cat_name,count(e.estimate_id) as total,SUM(ep.amount + ep.tax_rate) as estimate_rate,DATE(e.estimate_date)');
        $this->db->where(array(
            'd.is_delete' => 0,
        ));
        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));
            $this->db->where([
                'DATE(e.estimate_date) >=' => $date[0],
                'DATE(e.estimate_date) <=' => $date[1]
            ]);
        }
        if (!is_null($this->input->get('category')) && $this->input->get('category') != '') {
            $category = $this->input->get('category');
            $this->db->where_in('d.id', $category);
        }
        if (!empty($keyword['value'])) {
            $having = '(count(e.estimate_id) LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . 'OR SUM(ep.amount + ep.tax_rate) LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR d.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->having($having);
        }
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.department_id=d.id AND ui.is_delete = 0 AND business_user_id = ' . checkUserLogin('C'), 'left');
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.part_id=ui.id AND ep.is_deleted = 0', 'left');
        // AND e.is_sent = 1
        $this->db->join(TBL_ESTIMATES . ' as e', 'ep.estimate_id=e.id AND e.is_invoiced = 1 AND e.is_deleted = 0 ', 'right');
        $this->db->group_by('d.id');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_DEPARTMENTS . ' d');
            return $query->num_rows();
        } else {
            if ($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_DEPARTMENTS . ' d');
            return $query;
        }
    }

    public function get_sales_by_category_graph_data($wh = null, $category = null) {
        $this->db->select('d.name as name,COUNT(e.estimate_id),SUM(ep.amount + ep.tax_rate) as value');
        $this->db->where(array(
            'd.is_delete' => 0,
        ));
        if (!is_null($wh)):
            if (!is_null($wh['date'])) {
                $date = explode(':=:', $wh['date']);
                $this->db->where([
                    'DATE(e.estimate_date) >=' => $date[0],
                    'DATE(e.estimate_date) <=' => $date[1]
                ]);
            }
            if (!is_null($wh['category']) && !is_null($category)) {
                $this->db->where_in('d.id', $wh['category']);
            }
        endif;
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.department_id=d.id AND ui.is_delete = 0 AND business_user_id = ' . checkUserLogin('C'), 'left');
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.part_id=ui.id AND ep.is_deleted = 0', 'left');
        // AND e.is_sent = 1
        $this->db->join(TBL_ESTIMATES . ' as e', 'ep.estimate_id=e.id AND e.is_invoiced = 1 AND e.is_deleted = 0 ', 'right');
        $this->db->order_by('value', 'desc');
        $this->db->group_by('d.id');
        $query = $this->db->get(TBL_DEPARTMENTS . ' d')->result_array();
        return $query;
    }

    /**
     * It will get all the records of low inventory for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_sales_by_category_data_pdf($wh) {
        $this->db->select('d.*,d.name as cat_name,count(e.estimate_id) as total,SUM(ep.amount + ep.tax_rate) as estimate_rate,DATE(e.estimate_date)');
        $this->db->where(array(
            'd.is_delete' => 0,
        ));
        if (!is_null($wh)):
            if (!is_null($wh['date'])) {
                $date = explode(':=:', $wh['date']);
                $this->db->where([
                    'DATE(e.estimate_date) >=' => $date[0],
                    'DATE(e.estimate_date) <=' => $date[1]
                ]);
            }
            if (!is_null($wh['category'])) {
                $this->db->where_in('d.id', $wh['category']);
            }
        endif;
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.department_id=d.id AND ui.is_delete = 0 AND business_user_id = ' . checkUserLogin('C'), 'left');
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.part_id=ui.id AND ep.is_deleted = 0', 'left');
        // AND e.is_sent = 1
        $this->db->join(TBL_ESTIMATES . ' as e', 'ep.estimate_id=e.id AND e.is_invoiced = 1 AND e.is_deleted = 0 ', 'right');
        $this->db->order_by('estimate_rate', 'desc');
        $this->db->group_by(['d.id']);
        $query = $this->db->get(TBL_DEPARTMENTS . ' d');
        return $query;
    }

    public function get_sales_by_user_data($type, $wh = null) {
        $columns = ['u.id', 'u.full_name', 'total', 'estimate_rate'];
        $keyword = $this->input->get('search');
        $this->db->select('u.*,count(e.estimate_id) as total_count,SUM(e.total) as estimate_rate,DATE(e.estimate_date),r.role_name,r.id as role_id');
        $this->db->where('(`u`.`is_delete` = 0 AND `u`.`business_user_id` = ' . checkUserLogin('C') . ' AND `u`.`status` = "active" OR `u`.`id` = ' . checkUserLogin('C') . ')');
        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));
            $this->db->where([
                'DATE(e.estimate_date) >=' => $date[0],
                'DATE(e.estimate_date) <=' => $date[1]
            ]);
        }
        if (!is_null($this->input->get('user')) && $this->input->get('user') != '') {
            $user = $this->input->get('user');
            $this->db->where_in('u.id', $user);
        }
        if (!empty($keyword['value'])) {
            $having = '(count(e.estimate_id) LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . 'OR SUM(e.total) LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.email_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR r.role_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->having($having);
        }
        // AND e.is_sent = 1
        $this->db->join(TBL_ESTIMATES . ' as e', 'u.id=e.sales_person AND e.is_invoiced = 1 AND e.is_deleted = 0 ', 'LEFT');
        $this->db->join(TBL_ROLES . ' as r', 'u.user_role=r.id', 'left');
        $this->db->group_by('u.id');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_USERS . ' u');
            return $query->num_rows();
        } else {
            if ($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_USERS . ' u');
            return $query;
        }
    }

    public function get_sales_by_user_graph_data($wh = null, $category = null) {
        $this->db->select('u.full_name as name,COUNT(e.estimate_id),SUM(e.total) as value');
        $this->db->where('(`u`.`is_delete` = 0 AND `u`.`business_user_id` = ' . checkUserLogin('C') . ' AND `u`.`status` = "active" OR `u`.`id` = ' . checkUserLogin('C') . ')');
        if (!is_null($wh)):
            if (!is_null($wh['date'])) {
                $date = explode(':=:', $wh['date']);
                $this->db->where([
                    'DATE(e.estimate_date) >=' => $date[0],
                    'DATE(e.estimate_date) <=' => $date[1]
                ]);
            }
            if (!is_null($wh['user']) && !is_null($category)) {
                $this->db->where_in('u.id', $wh['user']);
            }
        endif;
        // AND e.is_sent = 1
        $this->db->join(TBL_ESTIMATES . ' as e', 'u.id=e.sales_person AND e.is_invoiced = 1 AND e.is_deleted = 0 ', 'right');
        $this->db->join(TBL_ROLES . ' as r', 'u.user_role=r.id', 'left');
        $this->db->group_by('u.id');
        $this->db->order_by('value', 'desc');
        $query = $this->db->get(TBL_USERS . ' u')->result_array();
        return $query;
    }

    /**
     * It will get all the records of low inventory for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_sales_by_user_data_pdf($wh) {
        $this->db->select('u.*,count(e.estimate_id) as total_count,SUM(e.total) as estimate_rate,DATE(e.estimate_date),r.role_name,r.id as role_id');
        $this->db->where('(`u`.`is_delete` = 0 AND `u`.`business_user_id` = ' . checkUserLogin('C') . ' AND `u`.`status` = "active" OR `u`.`id` = ' . checkUserLogin('C') . ')');
        if (!is_null($wh)):
            if (!is_null($wh['date'])) {
                $date = explode(':=:', $wh['date']);
                $this->db->where([
                    'DATE(e.estimate_date) >=' => $date[0],
                    'DATE(e.estimate_date) <=' => $date[1]
                ]);
            }
            if (!is_null($wh['user'])) {
                $user_id = explode(',', $wh['user']);
                $this->db->where_in('u.id', $user_id);
            }
        endif;
        // AND e.is_sent = 1
        $this->db->join(TBL_ESTIMATES . ' as e', 'u.id=e.sales_person AND e.is_invoiced = 1 AND e.is_deleted = 0 ', 'LEFT');
        $this->db->join(TBL_ROLES . ' as r', 'u.user_role=r.id', 'left');
        $this->db->group_by('u.id');
        $this->db->order_by('estimate_rate', 'desc');
        $query = $this->db->get(TBL_USERS . ' u');
        return $query;
    }

    function array_mesh() {
        $numargs = func_num_args();
        $arg_list = func_get_args();
        $out = array();
        for ($i = 0; $i < $numargs; $i++) {
            $in = $arg_list[$i];
            foreach($in as $key => $value) {
                if(array_key_exists($key, $out)) {
                    $sum = $in[$key] + $out[$key];
                    $out[$key] = $sum;
                }else{
                    $out[$key] = $in[$key];
                }
            }
        }
        return $out;
    }


    public function get_tax_data($type, $wh = null,$return_result = 0) {
        $columns = ['a.id', 'name', 'total_count', 'estimate_rate'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id AS e_id,`t`.`name`, `t`.`id`,`t`.`rate`,ep.tax_rate,ep.tax_id,ep.individual_part_tax');
        // AND e.is_sent = 1
        $this->db->where('e.is_invoiced = 1 AND e.is_deleted = 0  AND `e`.`business_user_id` = ' . checkUserLogin('C'));
        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));
            $this->db->where([
                'DATE(e.estimate_date) >=' => $date[0],
                'DATE(e.estimate_date) <=' => $date[1]
            ]);
        }
        if (!is_null($this->input->get('tax')) && $this->input->get('tax') != '') {
            $tax = $this->input->get('tax');
            $this->db->where_in('t.id', $tax);
        }

        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'e.id=ep.estimate_id AND ep.is_deleted = 0 AND ep.tax_id != 0', 'LEFT');
        $this->db->join(TBL_TAXES . ' as t', 't.id=ep.tax_id AND `t`.`is_deleted` = 0', 'RIGHT');
        $this->db->from(TBL_ESTIMATES . ' e');
        $parts = $this->db->get()->result_array();
        $final_tax_id = array();
        $final_count_part_id = array(); 
        $graph_id = array();
        $new_part_array = array();

        foreach ($parts as $key => $part) {
            $tax_part_id = explode(',', $part['tax_id']);
            $individual_tax = explode(',', $part['individual_part_tax']);
            $tax_name = $part['name'];

            foreach ($tax_part_id as $parttaxkey => $t_id) {
                if(!(array_key_exists($t_id, $final_tax_id)))
                {
                    $graph_id[] = $t_id;
                    $final_count_part_id[$t_id] = 1;
                    $final_tax_id[$t_id] = isset($individual_tax[$parttaxkey]) ? $individual_tax[$parttaxkey] : '';
                    $new_part_array[$t_id] = array(
                        'e_id' => $part['e_id'],
                        'name' => $tax_name,
                        'id' => $t_id,
                        'rate' => 0,
                        'tax_rate' => $part['tax_rate'],
                        'tax_id' => $t_id,
                        'individual_part_tax' => $final_tax_id[$t_id],
                    );
                } else {
                    $final_count_part_id[$t_id] = $final_count_part_id[$t_id] + 1;
                    $final_tax_id[$t_id] = (isset($final_tax_id[$t_id]) ? $final_tax_id[$t_id] : 0) + (isset($individual_tax[$parttaxkey]) ? $individual_tax[$parttaxkey] : 0);
                    $new_part_array[$t_id] = array(
                        'e_id' => $part['e_id'],
                        'name' => $tax_name,
                        'id' => $t_id,
                        'rate' => 0,
                        'tax_rate' => $part['tax_rate'],
                        'tax_id' => $t_id,
                        'individual_part_tax' => $final_tax_id[$t_id],
                    );
                }
            }
        } 
        $query1 = $this->db->last_query();

        $this->db->select('e.id AS e_id,`t`.`name`, `t`.`id`,`t`.`rate`,es.tax_rate,es.tax_id,es.individual_service_tax');
        $this->db->where('e.is_invoiced = 1 AND e.is_deleted = 0  AND `e`.`business_user_id` = ' . checkUserLogin('C'));
        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));
            $this->db->where([
                'DATE(e.estimate_date) >=' => $date[0],
                'DATE(e.estimate_date) <=' => $date[1]
            ]);
        }
        if (!is_null($this->input->get('tax')) && $this->input->get('tax') != '') {
            $tax = $this->input->get('tax');
            $this->db->where_in('t.id', $tax);
        }

        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'e.id=es.estimate_id AND es.is_deleted = 0 AND es.tax_id!=0', 'LEFT');
        $this->db->join(TBL_TAXES . ' as t', 't.id=es.tax_id AND `t`.`is_deleted` = 0', 'RIGHT');
        $this->db->from(TBL_ESTIMATES . ' e');

        $services = $this->db->get()->result_array();
        $final_count_service_id = array();
        $new_service_array = array();

        foreach ($services as $key => $service) {
            $tax_service_id = explode(',', $service['tax_id']);
            $individual_tax = explode(',', $service['individual_service_tax']);
            foreach ($tax_service_id as $servicetaxkey => $s_id) {
                if(!(array_key_exists($s_id, $final_tax_id)))
                {
                    $graph_id[] = $s_id;
                    $final_count_service_id[$s_id] = 1;
                    $final_tax_id[$s_id] = isset($individual_tax[$servicetaxkey]) ? $individual_tax[$servicetaxkey] : '';
                    $new_service_array[$s_id] = array(
                        'e_id' => $service['e_id'],
                        'name' => $s_id,
                        'id' => $s_id,
                        'rate' => 0,
                        'tax_rate' => $service['tax_rate'],
                        'tax_id' => $s_id,
                        'individual_part_tax' => $final_tax_id[$s_id],
                    );
                } else {
                    $final_count_service_id[$s_id] = (isset($final_count_service_id[$s_id]) ? $final_count_service_id[$s_id] + 1 : 1);
                    $final_tax_id[$s_id] = (isset($final_tax_id[$s_id]) ? $final_tax_id[$s_id] : 0) + (isset($individual_tax[$servicetaxkey]) ? $individual_tax[$servicetaxkey] : 0);
                    $new_service_array[$s_id] = array(
                        'e_id' => $service['e_id'],
                        'name' => $s_id,
                        'id' => $s_id,
                        'rate' => 0,
                        'tax_rate' => $service['tax_rate'],
                        'tax_id' => $s_id,
                        'individual_part_tax' => $final_tax_id[$s_id],
                    );
                }
            }
        }

        $first_tax_column    = array_column($new_part_array, 'tax_id');
        $second_tax_column   = array_column($new_service_array, 'tax_id');
        $new_array_merge     = array_merge($first_tax_column,$second_tax_column);
        $new_array_uniq      = array_unique($new_array_merge);
        $display_graph_array = array(); 

        if(!empty($tax)){
            $new_array_uniq = $tax;
            foreach ($new_array_uniq as $uniq_key => $final_value) {
                if(!(array_key_exists($final_value, $display_graph_array)))
                {
                    $display_graph_array[$final_value] = array(
                        'name' => $final_value,
                        'id' => $final_value,
                        'rate' => 0,
                        'tax_id' => $final_value,
                        'individual_part_tax' => $final_tax_id[$final_value],
                    );
                } else {
                    $display_graph_array[$final_value] = array(
                        'name' => $final_value,
                        'id' => $final_value,
                        'rate' => 0,
                        'tax_id' => $final_value,
                        'individual_part_tax' => $final_tax_id[$final_value],
                    );
                }
            }
        } else {
            foreach ($new_array_uniq as $uniq_key => $final_value) {
                if(!(array_key_exists($final_value, $display_graph_array)))
                {
                    $display_graph_array[$final_value] = array(
                        'name' => $final_value,
                        'id' => $final_value,
                        'rate' => 0,
                        'tax_id' => $final_value,
                        'individual_part_tax' => $final_tax_id[$final_value],
                    );
                } else {
                    $display_graph_array[$final_value] = array(
                        'name' => $final_value,
                        'id' => $final_value,
                        'rate' => 0,
                        'tax_id' => $final_value,
                        'individual_part_tax' => $final_tax_id[$final_value],
                    );
                }
            }
        }

        // pr($display_graph_array);
        // die();

        $count_id = array();
        $count_id = $this->array_mesh($final_count_part_id, $final_count_service_id);

        $query2 = $this->db->last_query();

        $where = '';
        $select = 'SELECT a.id,name,rate, 
        SUM(IF(tax_rate IS NULL,0,tax_rate)) AS estimate_rate,
        COUNT(id) as total_count FROM (';

        if (!empty($keyword['value'])) {
            $where = 'WHERE (name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
        }

        $q = $select . $query1 . " UNION " . $query2 . ") as a " . $where . " GROUP BY a.id ORDER BY " . $columns[$this->input->get('order')[0]['column']] . " " . $this->input->get('order')[0]['dir'];
        $query = $this->db->query($q);
        $result = $query->result_array();

        $tax_id_list = $this->tax_list();
        $tax_name_list = array_column($tax_id_list, 'name','id');
        $tax_rate_list = array_column($tax_id_list, 'rate','id');
                    
        if(!empty($display_graph_array)){
            foreach ($display_graph_array as $key => $value) {
                if(isset($value['id'])){
                    $get_id = $value['id'];
                    if(isset($final_tax_id[$get_id])){
                        $display_graph_array[$key]['estimate_rate'] = $final_tax_id[$get_id];
                    }
                    if(isset($count_id[$get_id])){
                        $display_graph_array[$key]['total_count'] = $count_id[$get_id];
                    }
                    if(isset($tax_name_list[$get_id])){
                        $display_graph_array[$key]['name'] = $tax_name_list[$get_id];
                    }
                    if(isset($tax_rate_list[$get_id])){
                        $display_graph_array[$key]['rate'] = $tax_rate_list[$get_id];
                    }
                }
            }
        }

        if($return_result == 1){
            $estimate_rate_column = array_column($display_graph_array, 'estimate_rate');
            array_multisort($estimate_rate_column, SORT_ASC, $display_graph_array);
            return $display_graph_array;
        }else{
            return $query;
        }


        if ($type == 'count') {
            return $query->num_rows();
        } else {
            if ($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            return $query;
        }
    }

    public function get_invoice_tax_data($type, $wh = null) {
        $columns = ['a.id', 'estimate_date', 'estimate_id', 'estimate_rate'];
        $keyword = $this->input->get('search');
        // ((e.total * IFNULL(`ep`.`tax_rate`,0)) / 100) AS invoice_tax_amount
        $this->db->select('`e`.`id` AS `e_id`, `e`.`estimate_id`, IFNULL(`ep`.`tax_rate`,0) as estimate_rate, e.estimate_date as estimate_date,e.total, `ep`.`tax_rate` AS invoice_tax_amount');
        // AND e.is_sent = 1
        $this->db->where('e.is_invoiced = 1 AND e.is_deleted = 0  AND `e`.`business_user_id` = ' . checkUserLogin('C'));

        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1]))
            ]);
        }

        if (!is_null($this->input->get('tax')) && $this->input->get('tax') != '') {
            $tax = $this->input->get('tax');
            $this->db->where_in('t.id', $tax);
        }
        
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'e.id=ep.estimate_id AND ep.is_deleted = 0 AND ep.tax_id != 0', 'LEFT');
        $this->db->join(TBL_TAXES . ' as t', 't.id=ep.tax_id AND `t`.`is_deleted` = 0', 'RIGHT');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']] . " " . $this->input->get('order')[0]['dir']);

        if (!empty($keyword['value'])) {
            $where = '(e.estimate_date LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR `e`.`estimate_id` LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->having($where);
        }

        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' e');
            return $query->num_rows();
        } else {
            if ($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' e');
            return $query;
        }
    }

    public function get_service_tax_data($type, $wh = null) {
        $columns = ['a.id', 'estimate_date', 'estimate_id', 'estimate_rate'];
        $keyword = $this->input->get('search');
        // ((e.total * IFNULL(`es`.`tax_rate`,0)) / 100) AS invoice_tax_amount
        $this->db->select('`e`.`id` AS `e_id`, `e`.`estimate_id`, IFNULL(`es`.`tax_rate`,0) as estimate_rate, e.estimate_date as estimate_date,e.total, `es`.`tax_rate` AS invoice_tax_amount');
        // AND e.is_sent = 1
        $this->db->where('e.is_invoiced = 1 AND e.is_deleted = 0  AND `e`.`business_user_id` = ' . checkUserLogin('C'));

        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1]))
            ]);
        }

        if (!is_null($this->input->get('tax')) && $this->input->get('tax') != '') {
            $tax = $this->input->get('tax');
            $this->db->where_in('t.id', $tax);
        }
        
        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'e.id=es.estimate_id AND es.is_deleted = 0 AND es.tax_id!=0', 'LEFT');
        $this->db->join(TBL_TAXES . ' as t', 't.id=es.tax_id AND `t`.`is_deleted` = 0', 'RIGHT');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']] . " " . $this->input->get('order')[0]['dir']);

        if (!empty($keyword['value'])) {
            $where = '(e.estimate_date LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR `e`.`estimate_id` LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->having($where);
        }

        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' e');
            return $query->num_rows();
        } else {
            if ($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' e');
            return $query;
        }
    }

    /**
     * It will get all tax list
     * @param  : $type - Array
     * @return : Object
     * @author JJP [Last Edited : 09/01/2020]
     */
    
    function tax_list(){
        $query_tax = $this->db
                    ->select('*')
                    ->where('t.business_user_id = ' . checkUserLogin('C'))
                    ->where('t.is_deleted = 0')
                    ->from(TBL_TAXES. ' as t')
                    ->get();
        return $query_tax->result();
    }

    /**
     * It will get all the records of low inventory for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 29/06/2018
     */
    public function get_tax_data_pdf($wh, $tax = null, $pdf = null,$return_result = 0) {
        $this->db->select('e.id AS e_id,`t`.`name`, `t`.`id`,`t`.`rate`,ep.tax_rate,ep.tax_id,ep.individual_part_tax');
        $this->db->where('e.is_invoiced = 1 AND e.is_deleted = 0  AND `e`.`business_user_id` = ' . checkUserLogin('C'));
        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));
            $this->db->where([
                'DATE(e.estimate_date) >=' => $date[0],
                'DATE(e.estimate_date) <=' => $date[1]
            ]);
        }

        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'e.id=ep.estimate_id AND ep.is_deleted = 0 AND ep.tax_id != 0', 'LEFT');
        $this->db->join(TBL_TAXES . ' as t', 't.id=ep.tax_id AND `t`.`is_deleted` = 0', 'RIGHT');
        $this->db->from(TBL_ESTIMATES . ' e');

        $parts = $this->db->get()->result_array();
        $final_tax_id = array();
        $final_count_part_id = array();
        $graph_id = array();
        $new_part_array = array();
        foreach ($parts as $key => $part) {
            $tax_part_id = explode(',', $part['tax_id']);
            $individual_tax = explode(',', $part['individual_part_tax']);
            $tax_name = $part['name'];

            foreach ($tax_part_id as $parttaxkey => $t_id) {
                if(!(array_key_exists($t_id, $final_tax_id)))
                {

                    $graph_id[] = $t_id;
                    $final_count_part_id[$t_id] = 1;
                    $final_tax_id[$t_id] = isset($individual_tax[$parttaxkey]) ? $individual_tax[$parttaxkey] : '';

                    $new_part_array[$t_id] = array(
                        'e_id' => $part['e_id'],
                        'name' => $tax_name,
                        'id' => $t_id,
                        'rate' => 0,
                        'tax_rate' => $part['tax_rate'],
                        'tax_id' => $t_id,
                        'individual_part_tax' => $final_tax_id[$t_id],
                    );
                } else {
                    $final_count_part_id[$t_id] = $final_count_part_id[$t_id] + 1;
                    $final_tax_id[$t_id] = (isset($final_tax_id[$t_id]) ? $final_tax_id[$t_id] : 0) + (isset($individual_tax[$parttaxkey]) ? $individual_tax[$parttaxkey] : 0);
                    $new_part_array[$t_id] = array(
                        'e_id' => $part['e_id'],
                        'name' => $tax_name,
                        'id' => $t_id,
                        'rate' => 0,
                        'tax_rate' => $part['tax_rate'],
                        'tax_id' => $t_id,
                        'individual_part_tax' => $final_tax_id[$t_id],
                    );
                }
            }
        }        
        
        $query1 = $this->db->last_query();

        $this->db->select('e.id AS e_id,`t`.`name`, `t`.`id`,`t`.`rate`,es.tax_rate,es.tax_id,es.individual_service_tax');
        $this->db->where('e.is_invoiced = 1 AND e.is_deleted = 0 AND  `e`.`business_user_id` = ' . checkUserLogin('C'));
        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));
            $this->db->where([
                'DATE(e.estimate_date) >=' => $date[0],
                'DATE(e.estimate_date) <=' => $date[1]
            ]);
        }

        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'e.id=es.estimate_id AND es.is_deleted = 0 AND es.tax_id!=0', 'LEFT');
        $this->db->join(TBL_TAXES . ' as t', 't.id=es.tax_id AND `t`.`is_deleted` = 0', 'RIGHT');
        $this->db->from(TBL_ESTIMATES . ' e');

        $services = $this->db->get()->result_array();
        $final_count_service_id = array();
        $new_service_array = array();
        
        foreach ($services as $key => $service) {
            $tax_service_id = explode(',', $service['tax_id']);
            $individual_tax = explode(',', $service['individual_service_tax']);
            foreach ($tax_service_id as $servicetaxkey => $s_id) {
                if(!(array_key_exists($s_id, $final_tax_id)))
                {
                    $graph_id[] = $s_id;
                    $final_count_service_id[$s_id] = 1;
                    $final_tax_id[$s_id] = isset($individual_tax[$servicetaxkey]) ? $individual_tax[$servicetaxkey] : '';
                    $new_service_array[$s_id] = array(
                        'e_id' => $service['e_id'],
                        'name' => $s_id,
                        'id' => $s_id,
                        'rate' => 0,
                        'tax_rate' => $service['tax_rate'],
                        'tax_id' => $s_id,
                        'individual_part_tax' => $final_tax_id[$s_id],
                    );

                } else {
                    $final_count_service_id[$s_id] = (isset($final_count_service_id[$s_id]) ? $final_count_service_id[$s_id] + 1 : 1);
                    $final_tax_id[$s_id] = (isset($final_tax_id[$s_id]) ? $final_tax_id[$s_id] : 0) + (isset($individual_tax[$servicetaxkey]) ? $individual_tax[$servicetaxkey] : 0);
                    $new_service_array[$s_id] = array(
                        'e_id' => $service['e_id'],
                        'name' => $s_id,
                        'id' => $s_id,
                        'rate' => 0,
                        'tax_rate' => $service['tax_rate'],
                        'tax_id' => $s_id,
                        'individual_part_tax' => $final_tax_id[$s_id],
                    );
                }
            }
        }

        $first_tax_column    = array_column($new_part_array, 'tax_id');
        $second_tax_column   = array_column($new_service_array, 'tax_id');
        $new_array_merge     = array_merge($first_tax_column,$second_tax_column);
        $new_array_uniq      = array_unique($new_array_merge);
        $display_graph_array = array(); 
        
        if(!empty($wh['tax'])){
            $new_array_uniq = $wh['tax'];
            foreach ($new_array_uniq as $uniq_key => $final_value) {
                if(!(array_key_exists($final_value, $display_graph_array)))
                {
                    $display_graph_array[$final_value] = array(
                        'name' => $final_value,
                        'id' => $final_value,
                        'rate' => 0,
                        'tax_id' => $final_value,
                        'individual_part_tax' => $final_tax_id[$final_value],
                    );
                } else {
                    $display_graph_array[$final_value] = array(
                        'name' => $final_value,
                        'id' => $final_value,
                        'rate' => 0,
                        'tax_id' => $final_value,
                        'individual_part_tax' => $final_tax_id[$final_value],
                    );
                }
            }
        } else {
            foreach ($new_array_uniq as $uniq_key => $final_value) {
                if(!(array_key_exists($final_value, $display_graph_array)))
                {
                    $display_graph_array[$final_value] = array(
                        'name' => $final_value,
                        'id' => $final_value,
                        'rate' => 0,
                        'tax_id' => $final_value,
                        'individual_part_tax' => $final_tax_id[$final_value],
                    );
                } else {
                    $display_graph_array[$final_value] = array(
                        'name' => $final_value,
                        'id' => $final_value,
                        'rate' => 0,
                        'tax_id' => $final_value,
                        'individual_part_tax' => $final_tax_id[$final_value],
                    );
                }
            }
        }

        // pr($display_graph_array);
        // die();
        
        $count_id = array();
        $count_id = $this->array_mesh($final_count_part_id, $final_count_service_id);
        
        $query2 = $this->db->last_query();

        $jjp = implode(',', $graph_id);
        
        $where = ''; 
        $select = 'SELECT a.id,name,rate, 
        SUM(IF(tax_rate IS NULL,0,tax_rate)) AS estimate_rate,
        COUNT(id) as total_count FROM (';

        if (!is_null($wh['tax']) && !is_null($tax)) {
            if (is_null($pdf)) {
                $where = ' WHERE a.id IN(' . implode(',', $wh['tax']) . ')';
            } else {
                $where = ' WHERE a.id IN(' . $wh['tax'] . ')';
            }
        }

        $q = $select . $query1 . " UNION " . $query2 . ") as a " . $where . " GROUP BY a.id ORDER BY estimate_rate DESC";

        // pr($q); die();

        $query = $this->db->query($q);
        $result = $query->result_array();
        
        $tax_id_list = $this->tax_list();
        $tax_name_list = array_column($tax_id_list, 'name','id');
        $tax_rate_list = array_column($tax_id_list, 'rate','id');
        
        if(!empty($display_graph_array)){
            foreach ($display_graph_array as $key => $value) {
                if(isset($value['id'])){
                    $get_id = $value['id'];
                    if(isset($final_tax_id[$get_id])){
                        $display_graph_array[$key]['estimate_rate'] = $final_tax_id[$get_id];
                    }
                    if(isset($count_id[$get_id])){
                        $display_graph_array[$key]['total_count'] = $count_id[$get_id];
                    }
                    if(isset($tax_name_list[$get_id])){
                        $display_graph_array[$key]['name'] = $tax_name_list[$get_id];
                    }
                    if(isset($tax_rate_list[$get_id])){
                        $display_graph_array[$key]['rate'] = $tax_rate_list[$get_id];
                    }
                }
            }
        }

        if($return_result == 1){
            $estimate_rate_column = array_column($display_graph_array, 'estimate_rate');
            array_multisort($estimate_rate_column, SORT_ASC, $display_graph_array);
            return $display_graph_array;
        }else{
            return $query;
        }
    }

    /**
     * It will get all the records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 23/11/2020]
     */
    public function get_inventory_value_items_data($type=NULL) {
        $columns = ['i.id','','i.part_no','','','i.part_location','i.retail_price','it.total_quantity','',''];
        $keyword = $this->input->get('search');
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name,it.total_quantity,it.image as dimage');
        $this->db->where(array(
            'i.is_delete' => 0,
            'i.business_user_id' => checkUserLogin('C')
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
        // $this->db->join('(SELECT item_id,SUM(quantity) as total_quantity FROM ' . TBL_ITEM_LOCATION_DETAILS . ' WHERE is_deleted = 0 group by item_id) it', 'it.item_id=i.id', 'left');
        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.vendor_id=v.id', 'left');
        $this->db->join(TBL_ITEMS . ' as it', 'it.id=i.referred_item_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query->num_rows();
        } else {
            if ($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query;
        }
       
    }

    /**
     * It will get all the records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 26/11/2020]
     */
    public function inventory_investment_items_data($type=NULL) {
        $columns = ['i.id','','i.part_no','','','i.part_location','i.unit_cost','it.total_quantity','',''];
        $keyword = $this->input->get('search');
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name,it.total_quantity,it.image as dimage');
        $this->db->where(array(
            'i.is_delete' => 0,
            'i.business_user_id' => checkUserLogin('C')
        ));
        if (!empty($keyword['value'])) {
            $where = '(i.part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.upc_barcode LIKE ' .$this->db->escape('%' . $keyword['value'] . '%'). ' OR i.global_part_no LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR d.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR v.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.unit_cost LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR total_quantity LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR i.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(i.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
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
            if ($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_USER_ITEMS . ' i');
            return $query;
        }
       
    }

    /**
     * It will get all the records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 23/11/2020]
     */
    public function get_print_inventory_value_items_data($type=NULL) {
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name,it.total_quantity,it.image as dimage,(i.retail_price * it.total_quantity) as inventory_value');
        $this->db->where(array(
            'i.is_delete' => 0,
            'i.business_user_id' => checkUserLogin('C')
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
        $this->db->order_by('i.id','DESC');
        $query = $this->db->get(TBL_USER_ITEMS . ' i')->result_array();
        return $query;
    }

    /**
     * It will get all the records of items for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 23/11/2020]
     */
    public function get_print_inventory_investment_items_data($type=NULL) {
        $this->db->select('i.*,d.name as dept_name, v.name as pref_vendor_name,it.total_quantity,it.image as dimage,(i.unit_cost * it.total_quantity) as inventory_investment');
        $this->db->where(array(
            'i.is_delete' => 0,
            'i.business_user_id' => checkUserLogin('C')
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
        $this->db->order_by('i.id','DESC');
        $query = $this->db->get(TBL_USER_ITEMS . ' i')->result_array();
        return $query;
    }

    public function get_popular_item_data($wh = null) {
        // SUM(ep.amount + ep.tax_rate)
        $this->db->select('i.part_no,i.global_part_no,d.name as dept_name, v.name as pref_vendor_name,SUM(ep.quantity) as total_quantity,SUM(ep.amount) as total_amount,i.image,i.id, , gi.image as global_image');
        $this->db->where(array(
            'i.is_delete' => 0,
            'i.business_user_id' => checkUserLogin('C')
        ));
        if (!is_null($wh)):
            if (!is_null($wh['date'])) {
                $date = explode(':=:', $wh['date']);
                $this->db->where([
                    'DATE(e.estimate_date) >=' => $date[0],
                    'DATE(e.estimate_date) <=' => $date[1]
                ]);
            }
            if (!is_null($wh['parts'])) {
                $this->db->where_in('i.id', $wh['parts']);
            }
        endif;
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'i.id=ep.part_id AND ep.is_deleted = 0', 'LEFT');
        // AND e.is_sent = 1
        $this->db->join(TBL_ESTIMATES . ' as e', 'e.id=ep.estimate_id AND e.is_invoiced = 1 AND e.is_deleted = 0 ', 'RIGHT');
        $this->db->join(TBL_DEPARTMENTS . ' as d', 'i.department_id=d.id', 'left');
        $this->db->join(TBL_VENDORS . ' as v', 'i.vendor_id=v.id', 'left');
        $this->db->join(TBL_ITEMS . ' AS gi', 'i.referred_item_id=gi.id', 'left');
        $this->db->order_by('total_quantity', 'desc');
        $this->db->group_by('i.id');
        $this->db->limit(25, 0);
        $query = $this->db->get(TBL_USER_ITEMS . ' i');
        return $query->result_array();
    }

    public function get_sales_data($wh) {
        $this->db->select('e.id AS e_id,SUM(e.total) as estimate_rate,DATE(e.estimate_date) as estimate_date');
        // AND e.is_sent = 1
        $this->db->where('e.is_invoiced = 1 AND e.is_deleted = 0  AND `e`.`business_user_id` = ' . checkUserLogin('C'));
        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));
            $this->db->where([
                'DATE(e.estimate_date) >=' => $date[0],
                'DATE(e.estimate_date) <=' => $date[1]
            ]);
        }

        $this->db->order_by('DATE(e.estimate_date)', 'DESC');
        $this->db->group_by('DATE(e.estimate_date)');
        $query = $this->db->get(TBL_ESTIMATES . ' e');
        return $query;
    }

    public function get_sales_ajax_data($type, $wh = null) {
        $columns = ['a.id', 'estimate_date', 'estimate_rate'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id AS e_id,SUM(e.total) as estimate_rate,DATE(e.estimate_date) as estimate_date');
        // AND e.is_sent = 1
        $this->db->where('e.is_invoiced = 1 AND e.is_deleted = 0  AND `e`.`business_user_id` = ' . checkUserLogin('C'));
        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));
            $this->db->where([
                'DATE(e.estimate_date) >=' => $date[0],
                'DATE(e.estimate_date) <=' => $date[1]
            ]);
        }
        if (!empty($keyword['value'])) {
            $where = '(DATE_FORMAT(estimate_date,"%M %d, %Y") LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR estimate_rate LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->having($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        $this->db->group_by('DATE(e.estimate_date)');

        if ($type == 'count') {
            $query = $this->db->get(TBL_ESTIMATES . ' e');
            return $query->num_rows();
        } else {
            if ($this->input->get('length') >= 0) {
                $this->db->limit($this->input->get('length'), $this->input->get('start'));
            }
            $query = $this->db->get(TBL_ESTIMATES . ' e');
            return $query;
        }
    }

    public function get_daily_sales_ajax_data($type, $wh = null) {
        $columns = ['a.id', 'estimate_date', 'total'];
        $keyword = $this->input->get('search');
        $this->db->select('e.id AS e_id,e.estimate_id,e.total,e.estimate_date');
        // AND e.is_sent = 1
        $this->db->where('e.is_invoiced = 1 AND e.is_deleted = 0  AND `e`.`business_user_id` = ' . checkUserLogin('C'));

        if (!is_null($this->input->get('date'))) {
            $date = explode(':=:', $this->input->get('date'));

            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1]))
            ]);
        }

        if (!empty($keyword['value'])) {
            $where = '(estimate_date LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR total LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
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
            return $query;
        }
    }

}

/* End of file Report_model.php */
/* Location: ./application/models/admin/Report_model.php */