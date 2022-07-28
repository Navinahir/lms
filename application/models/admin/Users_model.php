<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends MY_Model {

    /**
     * Check user exist or not for unique email
     * @author pav
     * @param string username, string password
     * @return array
     */
    public function check_login_validation($uname, $pass) {
        $this->db->select('u.*');
        $this->db->from(TBL_USERS . ' as u');
        $this->db->group_start();
        $this->db->where('u.email_id', $uname);
        $this->db->or_where('u.username', $uname);
        $this->db->group_end();
        $this->db->where(
                array(
                    'u.password' => $pass,
                    'u.status' => 'active',
                    'u.is_delete' => 0,
                    'u.user_role!=' => 0
                )
        );
        $this->db->limit(1);
        $res = $this->db->get();
        return $res->row_array();
    }

    public function find_user_by_email($email_add, $user_role = null) {
        $this->db->select('id as user_id, first_name, last_name');
        $this->db->from(TBL_USERS);
        $this->db->where('email_id', $email_add);
        $this->db->where(
                array(
                    'is_delete' => 0,
                    'status' => 'active'
                )
        );
        if (!is_null($user_role)):
            $this->db->where('user_role', $user_role);
        endif;
        $res = $this->db->get();
        return $res->row_array();
    }

    public function get_user_details($user_id, $password_verify = NULL) {
        $this->db->select('*');
        $this->db->from(TBL_USERS);
        $this->db->where('id', $user_id);
        if (!is_null($password_verify)) {
            $this->db->where('password_verify', $password_verify);
        }
        $this->db->limit(1);
        $res = $this->db->get();
        return $res->row_array();
    }

    /**
     * Update User profile
     * @author PAV
     * @param integer user id
     * @return boolean
     */
    public function get_user_profile($user_id) {
        $this->db->select('*');
        $this->db->from(TBL_USERS);
        $this->db->where('id', $user_id);
        //$this->db->where('user_role', 1);
        $res = $this->db->get();
        return $res->row_array();
    }

    /**
     * It will get all the records of Users Request for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_ajax_data($type, $wh = null) {
        $columns = ['u.id', 'u.status', 'u.full_name', 'u.username', 'u.email_id', 'u.business_name', 'bu.full_name',  'u.modified_date', 'u.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('u.*,r.role_name');
        $this->db->where(array(
            'u.is_delete' => 0,
//            'u.user_role' => 4,
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.email_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.username LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(u.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_ROLES . ' as r', 'u.user_role = r.id', 'left');
//        $this->db->join(TBL_USERS . ' as bu', 'u.business_user_id = bu.id', 'left');

		if ( isset($columns[$this->input->get('order')[0]['column']]) ) {
			$this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
		}
        if ($type == 'count') {
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of cancle subscription Users listing for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author JJP [Last Edited : 15/07/2020]
     */
    public function get_cancel_subscription_users_ajax_data($type) {
        $columns = ['u.id','u.status' ,'u.full_name', 'u.business_name', 'u.email_id', 'u.contact_number', 'p.name', 'u.modified_date'];
        $keyword = $this->input->get('search');
        $this->db->select('u.*,r.role_name, bu.full_name as primary_account_holder,p.name as package_name,p.price');
        $this->db->where(array(
            'u.is_delete' => 1,
            'u.business_user_id' => 0
            // 'u.user_role' => 4,
        ));
        if (!empty($keyword['value'])) {
            $where = '(u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.email_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.business_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.contact_number LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR p.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(u.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_ROLES . ' as r', 'u.user_role = r.id', 'left');
        $this->db->join(TBL_USERS . ' as bu', 'u.business_user_id = bu.id', 'left');
        $this->db->join(TBL_PACKAGES . ' as p', 'u.package_id = p.id', 'left');

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        if ($type == 'count') {
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->result_array();
        }
    }

    /**
     * Check email exist or not for unique email id
     * @param string/array $where
     * @return int
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function check_unique_email_for_user($where) {
        $this->db->where(array(
            'is_delete' => 0,
        ));
        $this->db->where('(`user_role` = 4 OR `business_user_id` != 0)');
        $this->db->where($where);
        $query = $this->db->get(TBL_USERS);
        return $query->num_rows();
    }

    /**
     * It will get all the records of Roles for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 08/06/2018
     */
    public function get_roles_ajax_data($type, $wh = null) {
        $columns = ['r.id', 'r.role_name', 'r.description', 'r.modified_date', 'r.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('r.*');
        $this->db->where(array(
            'r.is_delete' => 0,
            'u.is_delete' => 0,
            'r.is_active' => 0,
            'r.business_user_id' => checkUserLogin('C'),
        ));
//        $this->db->where('(r.business_user_id = ' . checkUserLogin('C') . ' OR r.id = 4)');
        $this->db->or_where(array(
            'r.id' => 4,
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(r.role_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR r.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(r.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->having($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id=r.business_user_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_ROLES . ' as r');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ROLES . ' as r');
            return $query->result_array();
        }
    }

    public function check_unique_role_for_user($where) {
        $this->db->where(array(
            'is_delete' => 0,
            'is_active' => 0,
        ));
        $this->db->where($where);
        $query = $this->db->get(TBL_ROLES);
        return $query->num_rows();
    }

    public function get_business_users_ajax_data($type, $wh = null) {
        $columns = ['u.id', 'u.full_name', 'u.username', 'r.role_name', 'u.modified_date', 'u.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('u.*,r.role_name');
        $this->db->where(array(
            'u.is_delete' => 0,
        ));
        $this->db->where('(`u`.`business_user_id` = ' . checkUserLogin('C') . ' OR `u`.`id` = ' . checkUserLogin('I') . ')');
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.email_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR r.role_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.username LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(u.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_ROLES . ' as r', 'u.user_role=r.id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->result_array();
        }
    }

    public function get_users_details($wh = null) {
        $this->db->select('*,u.id as user_id,r.id as role_id,p.id as package_id');
        $this->db->where(array(
            'u.is_delete' => 0,
            'r.is_delete' => 0,
            'p.is_delete' => 0,
            'u.id' => checkUserLogin('C'),
            'u.status' => 'active'
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        $this->db->join(TBL_ROLES . ' as r', 'u.user_role=r.id', 'left');
        $this->db->join(TBL_PACKAGES . ' as p', 'u.package_id=p.id', 'left');
        $query = $this->db->get(TBL_USERS . ' as u');
        return $query->row_array();
    }

    public function total_users($all = null) {
        $this->db->where(array(
            'u.is_delete' => 0,
            'u.business_user_id' => checkUserLogin('C'),
            'u.status' => 'active'
        ));
        $this->db->or_where(array(
            'u.id' => checkUserLogin('C'),
        ));
        $query = $this->db->get(TBL_USERS . ' as u');
        if (!is_null($all)) {
            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }

    public function total_vendor_users($all = null) {
        $this->db->where(array(
            'u.is_delete' => 0,
            'u.business_user_id' => checkVendorLogin('I'),
            'u.status' => 'active'
        ));

        $this->db->or_where(array(
            'u.id' => checkVendorLogin('I'),
        ));

        $query = $this->db->get(TBL_USERS . ' as u');
        if (!is_null($all)) {
            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }

    /**
     * Update User profile
     * @author HPA
     * @param integer user id
     * @return boolean
     */
    public function get_profile($user_id) {
        $this->db->select('*,u.id as uid');
        $this->db->from(TBL_USERS . ' u');
        $this->db->where(['u.id' => $user_id]);
        $this->db->join(TBL_CARD_DETAILS . ' c', 'u.id = c.user_id', 'left');
        $res = $this->db->get();
        return $res->row_array();
    }

    /**
     * Get all controller and method list
     * @author HPA
     * @param integer condition
     * @return array
     */
    public function get_controllers($condition = null) {
        $this->db->select('m.*,c.name as controller_name');

        if (!is_null($condition)) {
            $this->db->where($condition);
        }
        $this->db->where(['c.type'=>'User','c.is_delete' => 0, 'm.is_delete' => 0]);
        $this->db->join(TBL_CONTROLLERS . ' c', 'c.id=m.controller_id', 'left');
        $res_data = $this->db->get(TBL_METHODS . ' m')->result_array();

        if (!is_null($condition)) {
            return $res_data;
        } else {
            if (!empty($res_data)) {
                $new_array = [];
                foreach ($res_data as $key => $val) {
                    $array = [
                        'id' => $val['id'],
                        'controller_id' => $val['controller_id'],
                        'name' => $val['name'],
                        'method_name' => $val['method_name'],
                        'is_display' => $val['is_display'],
                        'controller_name' => $val['controller_name'],
                    ];
                    $new_array[$val['controller_id']][] = $array;
                }
            }
            return $new_array;
        }
    }

    /**
     * Get all controller and method list
     * @author HPA
     * @param integer condition
     * @return array
     */
    public function get_vendor_controllers($where_in_condition) {
        $this->db->select('m.*,c.name as controller_name');

        $this->db->where_in('c.name', $where_in_condition);

        $this->db->where(['c.type'=>'Vendor','c.is_delete' => 0, 'm.is_delete' => 0]);
        $this->db->join(TBL_CONTROLLERS . ' c', 'c.id=m.controller_id', 'left');
        $res_data = $this->db->get(TBL_METHODS . ' m')->result_array();

        if (!empty($res_data)) {
            $new_array = [];
            foreach ($res_data as $key => $val) {
                $array = [
                    'id' => $val['id'],
                    'controller_id' => $val['controller_id'],
                    'name' => $val['name'],
                    'method_name' => $val['method_name'],
                    'is_display' => $val['is_display'],
                    'controller_name' => $val['controller_name'],
                ];
                $new_array[$val['controller_id']][] = $array;
            }
        }
        return $new_array;
    }

    /**
     * Get all role wise permission's access
     * @author HPA
     * @param integer condition
     * @return array
     */
    public function get_user_permissions($condition = array()) {
        $this->db->select('m.*,c.name as controller_name,per.method_id');
        $this->db->where(['c.is_delete' => 0, 'm.is_delete' => 0]);
        $this->db->join(TBL_CONTROLLERS . ' c', 'c.id=m.controller_id', 'left');
        $this->db->join('(SELECT method_id FROM ' . TBL_USER_PERMISSION . '  WHERE permission_id=' . $condition['permission_id'] . ' AND company_id =' . $condition['company_id'] . ') as per', 'FIND_IN_SET (m.id, per.method_id)!=0', 'inner');
//        $this->db->join(TBL_METHODS . ' as m2', 'm2.controller_id=m.controller_id AND m2.is_display=0', 'left');
        $res_data = $this->db->get(TBL_METHODS . ' m')->result_array();
        return $res_data;
    }

    /**
     * Get all method's list which used in background
     * @author HPA
     * @param integer user id
     * @return boolean
     */
    public function get_undisplayed_methods($condition = array()) {

        if (is_array($condition)) {
            foreach ($condition as $key => $val) {
                if (is_array($val))
                    $this->db->where_in($key, $val);
                else
                    $this->db->where($key, $val);
            }
        }
        $this->db->select('m.*,c.name as controller_name');
        $this->db->where(['c.is_delete' => 0, 'm.is_delete' => 0]);
        $this->db->join(TBL_CONTROLLERS . ' c', 'c.id=m.controller_id', 'left');
        $res_data = $this->db->get(TBL_METHODS . ' m')->result_array();
        return $res_data;
    }

    public function get_role_permission($id) {
        $this->db->select('ep.method_id');
        $this->db->where(['ep.is_delete' => 0, 'ep.company_id' => checkUserLogin('C'), 'ep.permission_id' => $id]);
        $res_data = $this->db->get(TBL_USER_PERMISSION . ' ep')->row_array();
        return $res_data['method_id'];
    }

    public function get_vendor_permission($id) {
        $this->db->select('ep.method_id');
        $this->db->where(['ep.is_delete' => 0, 'ep.company_id' => checkVendorLogin('I'), 'ep.permission_id' => $id]);
        $res_data = $this->db->get(TBL_USER_PERMISSION . ' ep')->row_array();

        return $res_data['method_id'];
    }

    /**
     * Update User profile
     * @author HPA
     * @param integer user id
     * @return boolean
     */
    public function get_date_format($user_id) {
        $this->db->select('*');
        $this->db->from(TBL_USERS . ' u');
        $this->db->where(['u.id' => $user_id]);
//        $this->db->join(TBL_DATE_FORMAT . ' d', 'd.id = u.date_format_id', 'left');
        $res = $this->db->get();
        return $res->row_array();
    }

    /**
     * Update User profile
     * @author HPA
     * @param integer user id
     * @return boolean
     */
    public function get_currency($user_id) {
        $this->db->select('*');
        $this->db->from(TBL_USERS . ' u');
        $this->db->where(['u.id' => $user_id]);
//        $this->db->join(TBL_CURRENCY . ' c', 'c.id = u.currency_id', 'left');
        $res = $this->db->get();
        return $res->row_array();
    }

    public function block_users_locations($package, $existing_package) {
        if ($package['no_of_users'] > 1) {
            $this->db->limit($existing_package['no_of_users'], $package['no_of_users']);
        }
        $users = $this->get_result(TBL_USERS, ['business_user_id' => checkUserLogin('C'), 'status' => 'active'], 'id');

        if (!empty($users)) {
            foreach ($users as $k => $u):
                $array[$k] = [
                    'status' => 'block',
                    'id' => $u['id']
                ];
            endforeach;
            $this->batch_insert_update('update', TBL_USERS, $array, 'id');
        }

        if ($package['no_of_locations'] > 1) {
            $this->db->limit($existing_package['no_of_locations'], $package['no_of_locations']);
        }
        $locations = $this->get_result(TBL_LOCATIONS, ['business_user_id' => checkUserLogin('C'), 'is_deleted' => 0, 'is_default' => 0, 'is_active' => 1], 'id');

        $default = $this->get_result(TBL_LOCATIONS, ['business_user_id' => checkUserLogin('C'), 'is_deleted' => 0, 'is_default' => 1], null, 1);
        if (!empty($locations)) {
            foreach ($locations as $k => $l):
                $l_array[$k] = [
                    'is_active' => 0,
                    'id' => $l['id']
                ];

                $item_quantity = $this->get_result(TBL_ITEM_LOCATION_DETAILS, ['business_user_id' => checkUserLogin('C'), 'location_id' => $l['id'], 'is_deleted' => 0, 'quantity >' => 0]);
                if (!empty($item_quantity)) {
                    foreach ($item_quantity as $iq) {
                        $item_l[] = [
                            'id' => $iq['id'],
                            'is_active' => 0,
                            'quantity' => 0,
                            'is_deleted' => 1
                        ];
                        $is_exists = $this->get_result(TBL_ITEM_LOCATION_DETAILS, ['business_user_id' => checkUserLogin('C'), 'location_id' => $default['id'], 'item_id' => $iq['item_id'], 'is_deleted' => 0], null, 1);
                        if (!empty($is_exists)) {
                            $quantity = ($is_exists['quantity'] + $iq['quantity']);
                            $this->insert_update('update', TBL_ITEM_LOCATION_DETAILS, ['quantity' => $quantity], ['item_id' => $iq['item_id'], 'location_id' => $default['id']]);
                        } else {
                            $this->insert_update('insert', TBL_ITEM_LOCATION_DETAILS, ['business_user_id' => checkUserLogin('C'), 'item_id' => $iq['item_id'], 'location_id' => $default['id'], 'quantity' => $iq['quantity']]);
                        }
                    }
                    if (isset($item_l) && !empty($item_l)) {
                        $this->batch_insert_update('update', TBL_ITEM_LOCATION_DETAILS, $item_l, 'id');
                    }
                }
            endforeach;
            $this->batch_insert_update('update', TBL_LOCATIONS, $l_array, 'id');
        }
    }

    public function check_role_wise_user($id) {
        $this->db->where(array(
            'u.is_delete' => 0,
            'u.status' => 'active'
        ));
        $this->db->join(TBL_USERS . ' u', 'u.user_role = r.id', 'left');
        $this->db->where(['r.id' => $id]);
        $query = $this->db->get(TBL_ROLES . ' r');
        return $query->num_rows();
    }

    public function get_active_business_users($type, $wh = null) {
        $columns = ['u.id', 'u.full_name', 'u.username', 'r.role_name', 'u.modified_date', 'u.is_delete'];
        $this->db->select('u.*,r.role_name');
        $this->db->where(array(
            'u.is_delete' => 0,
            'u.status' => 'active'
        ));
        $this->db->where('(`u`.`business_user_id` = ' . checkUserLogin('C') . ' OR `u`.`id` = ' . checkUserLogin('I') . ')');

        if (!is_null($wh)) {
            $this->db->where($wh);
        }

        $this->db->join(TBL_ROLES . ' as r', 'u.user_role=r.id', 'left');

        if ($type == 'count') {
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Users payment history for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HGA [Last Edited : 06/12/2018]
     */
    public function get_user_payment_history($type, $wh = null) {
        $columns = ['pm.id', 'pm.description', 'pm.amount', 'pm.payment_date'];
        $keyword = $this->input->get('search');
        $this->db->select('pm.*');
        $this->db->where(array(
            'pm.is_delete' => 0,
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(pm.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR pm.amount LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR pm.payment_date LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . '")';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        if ($type == 'count') {
            $query = $this->db->get(TBL_PAYMENT . ' as pm');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_PAYMENT . ' as pm');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Users payment history for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HGA [Last Edited : 06/12/2018]
     */
    public function get_account_under_users_data($type, $wh = null) {
        $columns = ['u.id', 'u.full_name', 'u.username', 'u.email_id', 'r.role_name', 'u.status'];
        $keyword = $this->input->get('search');
        $this->db->select('u.*, r.role_name');
        $this->db->where(array(
            'u.is_delete' => 0,
            'u.status' => 'active'
        ));
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR r.role_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.username LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . '")';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        $this->db->join(TBL_ROLES . ' as r', 'u.user_role=r.id', 'left');

        if ($type == 'count') {
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->result_array();
        }
    }

    public function get_business_vendors_data($type, $wh = null) {
        $columns = ['u.id', 'u.first_name', 'u.username', 'r.role_name', 'u.modified_date', 'u.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('u.*,r.role_name');
        $this->db->where(array(
            'u.is_delete' => 0,
        ));
        $this->db->where('(`u`.`business_user_id` = ' . checkVendorLogin('I') . ' OR `u`.`id` = ' . checkVendorLogin('I') . ')');
        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(u.full_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.email_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR r.role_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.username LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(u.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }
        $this->db->join(TBL_ROLES . ' as r', 'u.user_role=r.id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_USERS . ' as u');
            return $query->result_array();
        }
    }

    /**
     * It will get all the records of Roles for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HPA [Last Edited : 08/06/2018
     */
    public function get_vendor_roles_data($type, $wh = null) {
        $columns = ['r.id', 'r.role_name', 'r.description', 'r.modified_date', 'r.is_delete'];
        $keyword = $this->input->get('search');
        $this->db->select('r.*');
        $this->db->where(array(
            'r.is_delete' => 0,
            'u.is_delete' => 0,
            'r.is_active' => 0,
            'r.business_user_id' => checkVendorLogin('I'),
        ));

//        $this->db->or_where(array(
//            'r.id' => 5,
//        ));

        if (!is_null($wh)) {
            $this->db->where($wh);
        }
        if (!empty($keyword['value'])) {
            $where = '(r.role_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR r.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR DATE_FORMAT(r.modified_date, "%m-%d-%Y %I:%i %p") LIKE "%' . $keyword['value'] . '%")';
            $this->db->having($where);
        }
        $this->db->join(TBL_USERS . ' as u', 'u.id=r.business_user_id', 'left');
        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        if ($type == 'count') {
            $query = $this->db->get(TBL_ROLES . ' as r');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_ROLES . ' as r');
            return $query->result_array();
        }
    }

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */
