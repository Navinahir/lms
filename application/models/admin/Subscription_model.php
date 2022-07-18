<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription_model extends MY_Model {

    /**
     * It will get all the records of Users Request for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_ajax_data($type, $wh = null) {
        $columns = ['s.id', 's.name', 'amount', 's.no_of_months', 's.max_redemption', 's.expiry_date', 's.modified_date', 's.is_deleted'];
        $keyword = $this->input->get('search');
        $this->db->select('s.*,IF(s.amount_off=0 && s.percent_off=0 ,"---",IF(s.amount_off=0,CONCAT(percent_off, "%"),CONCAT("$",amount_off))) as amount');
        $this->db->where(array(
            's.is_deleted' => 0,
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(s.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR s.amount LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR s.max_redemption LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR s.percent_off LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR s.no_of_months LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(s.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%" OR DATE_FORMAT(s.expiry_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_SUBSCRIPTIONS . ' as s');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_SUBSCRIPTIONS . ' as s');
            return $query->result_array();
        }
    }

    /**
     * To check uniqueness at the time of ADD/EDIT functionality
     * @param string $table, array $condition
     * @return array()
     * @author HPA [Last Edited : 31/05/2018]
     */
    public function check_unique_name($condition) {
        $this->db->where($condition);
        $this->db->where('is_deleted', 0);
        $query = $this->db->get(TBL_SUBSCRIPTIONS);
        return $query->row_array();
    }

    public function get_user_subscription_details($user_id) {
        $this->db->select('u.*,us.id as user_subscription_id,us.status as subscription_status,us.*,cd.*,st.name as state_name,p.name as package_name, p.price as package_price');

        $this->db->where(
                array(
                    'u.id' => $user_id,
                )
        );

        $this->db->join(TBL_USER_SUBSCRIPTIONS . ' as us', 'u.id=us.user_id', 'left');
        $this->db->join(TBL_PACKAGES . ' as p', 'us.package_id=p.id', 'left');
        $this->db->join(TBL_CARD_DETAILS . ' as cd', 'u.id=cd.user_id', 'left');
        $this->db->join(TBL_STATES . ' as st', 'cd.billing_state_id=st.id', 'left');
        $query = $this->db->get(TBL_USERS . ' as u');

        return $query->row_array();
    }

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */