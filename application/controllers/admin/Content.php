<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/content_model'));
    }

    /**
     * Disaply Content: listing
     * @param --
     * @return --
     * @author HGA [Added : 28/12/2018]
     */
    public function display_content() {
        $data['title'] = 'Admin | List Of Module Content';
        $this->template->load('default', 'admin/content/display', $data);
    }

    /**
     * Get Content: data by ajax and displaying in datatable while displaying
     * @param --
     * @return Object (Json Format)
     * @author HGA [Added : 28/12/2018]
     */
    public function get_contents() {
        $final['recordsTotal'] = $this->content_model->get_contents_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $where = [];

        $items = $this->content_model->get_contents_data('result', $where);

        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['created_date'] = date('m-d-Y h:i A', strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Add Content
     * @param --
     * @return --
     * @author HGA [Added : 28/12/2018]
     */
    public function add_content($id = null) {
        $data = array(
            'title' => 'Add Module Content',
        );

        if (!is_null($id)) {
            $record_id = base64_decode($id);
            $data['title'] = 'Edit Module Content';
            $data['dataArr'] = $this->content_model->get_all_details(TBL_MODULES_CONTENT, ['id' => $record_id])->row_array();
        }

        if ($this->input->post()) {
            $slug = strtolower(str_replace(" ", "_", $this->input->post('txt_module_name')));
            $content_array = [
                'module_name' => $this->input->post('txt_module_name'),
                'module_description' => $this->input->post('module_description'),
                'module_slug' => $slug,
                'created_date' => date('Y-m-d h:i:s')
            ];

            if (!is_null($id)) {
                $module_content_id = $this->content_model->insert_update('update', TBL_MODULES_CONTENT, $content_array, array('id' => $record_id));

                if ($module_content_id) {
                    $this->session->set_flashdata('success', 'Content has been updated successfully.');
                }
            } else {
                $module_content_id = $this->content_model->insert_update('insert', TBL_MODULES_CONTENT, $content_array);
                if ($module_content_id) {
                    $this->session->set_flashdata('success', 'Content has been added successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Content has not been added.');
                }
            }
            redirect('admin/content');
        }

        $this->template->load('default', 'admin/content/content_add', $data);
    }

    public function get_content_data_ajax_by_id() {
        $data['result'] = array();
        $module_id = base64_decode($this->input->post('id'));

        $this->db->select('*');
        $this->db->from(TBL_MODULES_CONTENT);
        $this->db->where(['is_deleted' => 0, 'id' => $module_id]);
        $result = $this->db->get()->row_array();
        
        $data['result'] = $result;
        return $this->load->view('admin/partial_view/view_content_data', $data);
    }

    public function delete_content($id = '') {
        $record_id = base64_decode($id);
        $this->content_model->insert_update('update', TBL_MODULES_CONTENT, array('is_deleted' => 1), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Content has been deleted successfully.');
        redirect('admin/content');
    }

}

/* End of file Content.php */
/* Location: ./application/controllers/Content.php */