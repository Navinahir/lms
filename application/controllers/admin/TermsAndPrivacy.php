<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TermsAndPrivacy extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/termspolicy_model'));
    }

    /**
     * Disaply Content: listing
     * @param --
     * @return --
     * @author HGA [Added : 28/12/2018]
     */
    public function display() {
        $data['title'] = 'Admin | List Terms And Privacy Policies';
        $this->template->load('default', 'admin/terms_and_policy/display', $data);
    }

    /**
     * Get Content: data by ajax and displaying in datatable while displaying
     * @param --
     * @return Object (Json Format)
     * @author HGA [Added : 28/12/2018]
     */
    public function get_pages() {
        $where = [];

        if (!empty($this->input->get('page_type'))) {
            $where = array('tcpc.page_type' => $this->input->get('page_type'));
        }

        $final['recordsTotal'] = $this->termspolicy_model->get_page_data('count', $where);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];


        $items = $this->termspolicy_model->get_page_data('result', $where);

        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['created_date'] = date('m-d-Y h:i A', strtotime($val['created_at']) + $_COOKIE['currentOffset']);
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
    public function add_edit($id = null) {
        $data = array(
            'title' => 'Add Terms Conditions And Privacy Policies',
        );

        if (!is_null($id)) {
            $record_id = base64_decode($id);
            $data['title'] = 'Edit Terms Conditions And Privacy Policies';
            $data['dataArr'] = $this->termspolicy_model->get_all_details(TBL_TERMS_AND_PRIVACY_POLICIES, ['id' => $record_id])->row_array();
        }

        if ($this->input->post()) {

            if (!empty($this->input->post('is_default'))) {
                $is_introduction_page = $this->input->post('is_default');
            } else {
                $is_introduction_page = 0;
            }

            $page_array = [
                'page_title' => $this->input->post('page_name'),
                'page_content' => $this->input->post('page_description'),
                'page_type' => $this->input->post('page_type'),
                'is_default' => $is_introduction_page,
            ];


            if (!is_null($id)) {
                $page_id = $this->termspolicy_model->insert_update('update', TBL_TERMS_AND_PRIVACY_POLICIES, $page_array, array('id' => $record_id));

                if ($page_id) {
                    $this->session->set_flashdata('success', 'Page has been updated successfully.');
                }
            } else {
                $page_array['slug'] = uniqid();

                $page_id = $this->termspolicy_model->insert_update('insert', TBL_TERMS_AND_PRIVACY_POLICIES, $page_array);
                if ($page_id) {
                    $this->session->set_flashdata('success', 'Page has been added successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Page has not been added.');
                }
            }
            redirect('admin/terms/privacy');
        }

        $this->template->load('default', 'admin/terms_and_policy/add', $data);
    }

    public function get_page_data_ajax_by_id() {
        $data['result'] = array();
        $page_id = base64_decode($this->input->post('id'));

        $this->db->select('*');
        $this->db->from(TBL_TERMS_AND_PRIVACY_POLICIES);
        $this->db->where(['is_deleted' => 0, 'id' => $page_id]);
        $data['result'] = $this->db->get()->row_array();

        return $this->load->view('admin/partial_view/view_page_data', $data);
    }

    public function delete($id = '') {
        $record_id = base64_decode($id);
        $this->termspolicy_model->insert_update('update', TBL_TERMS_AND_PRIVACY_POLICIES, array('is_deleted' => 1), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Page has been deleted successfully.');
        redirect('admin/terms/privacy');
    }

}

/* End of file Content.php */
/* Location: ./application/controllers/Content.php */