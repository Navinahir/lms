<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blogs_model extends MY_Model {

    /**
     * It will get all the records of Estimates for ajax datatable
     * @param  : $type - string
     * @return : Object
     * @author HGA [Last Edited : 29/06/2018
     */
    public function get_blogs_data($type, $wh = null) {
        $columns = ['blog.id', 'blog.blog_title', 'blog.is_active', 'blog.blog_content'];
        $keyword = $this->input->get('search');
        $this->db->select('blog.*');
        $this->db->where(array(
            'blog.is_deleted' => 0,
        ));

        if (!is_null($wh)) {
            $this->db->where($wh);
        }

        if (!empty($keyword['value'])) {
            $where = '(blog.blog_title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ') ';
            $this->db->where($where);
        }

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);

        if ($type == 'count') {
            $query = $this->db->get(TBL_BLOGS . ' as blog');
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get(TBL_BLOGS . ' as blog');
            return $query->result_array();
        }
    }

    public function get_all_blogs($length, $start, $wh = null) {
        $this->db->select('blog.*,bmc.file');

        if (!is_null($wh)) {
            $this->db->where($wh);
        }

        $this->db->where(array(
            'blog.is_active' => 1,
            'blog.is_deleted' => 0,
        ));

        $this->db->join(TBL_BLOG_MEDIA_CONTENTS . ' as bmc', 'blog.id = bmc.blog_id AND bmc.blog_content_type="Image"', 'left');

        $this->db->order_by('blog.id', 'DESC');
        $this->db->group_by('bmc.blog_id');
        $this->db->limit($length, $start);

        $query = $this->db->get(TBL_BLOGS . ' as blog');

        $data['count'] = $query->num_rows();
        $data['records'] = $query->result_array();

        return $data;
    }

    public function get_num_blogs() {
        $this->db->select('blog.*');

        $this->db->where(array(
            'blog.is_active' => 1,
            'blog.is_deleted' => 0,
        ));

        $query = $this->db->get(TBL_BLOGS . ' as blog');

        $data['count'] = $query->num_rows();
        $data['records'] = $query->result_array();

        return $data;
    }

}
