<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    /**
     * This function used to add or update records in particular table based on condition. 
     * @param String $mode
     * @param String $table
     * @param Array $dataArr        	
     * @param Array $condition
     * @return Integer $affected_row
     * @author PAV
     * */
    public function insert_update($mode = '', $table = '', $dataArr = '', $condition = '') {
        if ($mode == 'insert') {
            if ($this->db->insert($table, $dataArr)) {
                return $this->db->insert_id();
            } else {
                return 0;
            }
        } else if ($mode == 'update') {
            $this->db->where($condition);
            $this->db->update($table, $dataArr);
            $affected_row = $this->db->affected_rows();
            return $affected_row;
        } else if ($mode == 'delete') {
            $this->db->where($condition);
            $this->db->delete($table);
        }
    }

    /**
     * This function returns the table contents based on data. 
     * @param String $table
     * @param Array $condition          
     * @param Array $sortArr
     * @return Array
     * @author PAV
     * */
    public function get_all_details($table = '', $condition = '', $sortArr = '', $limitArr = '') {
        if ($sortArr != '' && is_array($sortArr)) {
            foreach ($sortArr as $sortRow) {
                if (is_array($sortRow)) {
                    $this->db->order_by($sortRow ['field'], $sortRow ['type']);
                }
            }
        }
        if ($limitArr != '') {
            return $this->db->get_where($table, $condition, $limitArr['l1'], $limitArr['l2']);
        } else {
            return $this->db->get_where($table, $condition);
        }
    }

    /**
     * This function used to add/edit in bulk. 
     * @param String $mode
     * @param String $table
     * @param Array $dataArr          
     * @param String $column
     * @author PAV
     * */
    public function batch_insert_update($mode = '', $table = '', $dataArr = '', $column = '', $condition = '') {
        if ($mode == 'insert') {
            $this->db->insert_batch($table, $dataArr);
        } else if ($mode == 'update') {
            if ($condition != '') {
                $this->db->where($condition);
            }
            $this->db->update_batch($table, $dataArr, $column);
        }
    }

    /**
     * Custom Query
     * @author KU
     */
    public function customQuery($query) {
        $result = $this->db->query($query);
        return $result;
    }

    /**
     * This function used to check Privileges for a particular user (page wise).
     * @param String $page_name
     * @param String $user_id
     * @return Object 
     * @author PAV
     * */
    public function checkPrivleges($page_name = '', $user_id = '') {
        $this->db->select('up.*');
        $this->db->from(TBL_USER_PRIVILEGES . ' as up');
        $this->db->join(TBL_USERS . ' as u', 'up.user_id=u.id', 'left');
        $this->db->join(TBL_PAGE . ' as p', 'up.page_id=p.id', 'left');
        $this->db->where(
                array(
                    'p.page_name' => $page_name,
                    'p.is_testdata' => IS_TESTDATA,
                    'p.is_delete' => 0,
                    'u.user_role' => 5,
                    'u.is_testdata' => IS_TESTDATA,
                    'u.is_delete' => 0,
                    'u.user_status' => 'active',
                    'up.user_id' => $user_id,
                    'up.is_testdata' => IS_TESTDATA,
                    'up.is_delete' => 0
                )
        );
        return $this->db->get();
    }

    /**
     * @uses : This function is used get result from the table
     * @param : @table 
     * @author : HPA
     */
    public function get_result($table, $condition = null, $fields = null, $single = null, $total = null, $order_by = null) {
        if (!empty($fields))
            $this->db->select($fields);
        else
            $this->db->select('*');
        if ($order_by != null) {
            $this->db->order_by($order_by);
        }
        if (!is_null($condition)) {
            $this->db->where($condition);
        }
        $query = $this->db->get($table);

        if (!is_null($single)) {
            return $query->row_array();
        } else if (!is_null($total)) {
            return $query->num_rows();
        } else {
            return $query->result_array();
        }
    }

    /**
     * @uses : This function is used get vehical details from API
     * @param : @table 
     * @author : HGA
     */
    public function get_vehical_details($vin_no) {
        $response_data = [];

        $postdata = http_build_query(
                array(
                    'format' => 'json',
                    'data' => (string)$vin_no
                )
        );
        $opts = array('http' =>
            array(
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                "Content-Length: " . strlen($postdata) . "\r\n" .
                "User-Agent:MyAgent/1.0\r\n",
                'method' => 'POST',
                'content' => $postdata
            )
        );
        $apiURL = "https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVINValuesBatch/";
        $context = stream_context_create($opts);
        $fp = fopen($apiURL, 'rb', false, $context);
        $response = @stream_get_contents($fp);

        if ($response == TRUE) {
            $response_data = json_decode($response);
        }

        return $response_data;
    }

}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */