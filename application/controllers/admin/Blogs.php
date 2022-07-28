<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blogs extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/blogs_model'));
    }

    /**
     * Disaply Content: listing
     * @param --
     * @return --
     * @author HGA [Added : 28/12/2018]
     */
    public function display() {
        $data['title'] = 'Admin | List Blogs';
        $this->template->load('default', 'admin/media_and_contents/display', $data);
    }

    /**
     * Get Content: data by ajax and displaying in datatable while displaying
     * @param --
     * @return Object (Json Format)
     * @author HGA [Added : 28/12/2018]
     */
    public function get_blogs() {
        $where = [];
        $final['recordsTotal'] = $this->blogs_model->get_blogs_data('count', $where);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];


        $items = $this->blogs_model->get_blogs_data('result', $where);

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
    public function add_edit($id = null) {
        $data = array(
            'title' => 'Add Blog',
        );

        if (!is_null($id)) {
            $record_id = base64_decode($id);
            $data['title'] = 'Edit Blog';
            $data['dataArr'] = $this->blogs_model->get_all_details(TBL_BLOGS, ['id' => $record_id])->row_array();
        }

        if ($this->input->post()) {
            $is_active = 0;
            if (!empty($this->input->post('is_active')) && $this->input->post('is_active') == 1) {
                $is_active = 1;
            }

            $dataArr = [
                'blog_title' => $this->input->post('blog_title'),
                'tags' => $this->input->post('blog_tags'),
                'blog_content' => $this->input->post('blog_description'),
                'is_active' => $is_active,
            ];

            if (!is_null($id)) {
                $this->blogs_model->insert_update('update', TBL_BLOGS, $dataArr, array('id' => $record_id));
                $insert_id = $record_id;
                $blog_no = $data['dataArr']['blog_no'];
            } else {
                $blog_no = rand(1000000, 1000000000000);
                $dataArr['blog_no'] = $blog_no;
                $insert_id = $this->blogs_model->insert_update('insert', TBL_BLOGS, $dataArr);
            }

            // If file upload form submitted
            if (!empty($_FILES['content_files']['name'])) {
                $images = array();

                if (!is_dir('./uploads/blogs/' . $blog_no . '/')) {
                    mkdir('./uploads/blogs/' . $blog_no . '/', 0777, TRUE);
                }

                $config = array(
                    'upload_path' => './uploads/blogs/' . $blog_no . '/',
                    'allowed_types' => '*',
                );
                $this->load->library('upload', $config);

                foreach ($_FILES['content_files']['name'] as $key => $image) {
                    $_FILES['images[]']['name'] = $_FILES['content_files']['name'][$key];
                    $_FILES['images[]']['type'] = $_FILES['content_files']['type'][$key];
                    $_FILES['images[]']['tmp_name'] = $_FILES['content_files']['tmp_name'][$key];
                    $_FILES['images[]']['error'] = $_FILES['content_files']['error'][$key];
                    $_FILES['images[]']['size'] = $_FILES['content_files']['size'][$key];

                    $fileName = $_FILES['content_files']['name'][$key];
                    $images[] = $fileName;
                    $config['file_name'] = $fileName;

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('images[]')) {
                        if (!empty($this->upload->data())) {
                            $file_response_data = $this->upload->data();
                            $file_name = $file_response_data['file_name'];

                            $content_types = $this->input->post('content_types');
                            $alt_text = $this->input->post('titles');

                            $blogContentArr = [
                                'blog_id' => $insert_id,
                                'file' => $file_name,
                                'blog_content_type' => $content_types[$key],
                                'alt' => $alt_text[$key],
                            ];

                            $this->blogs_model->insert_update('insert', TBL_BLOG_MEDIA_CONTENTS, $blogContentArr);
                        }
                    }
                }
            }

            if (!is_null($id)) {
                $this->session->set_flashdata('success', 'Blog has been updated successfully.');
            } else {
                $this->session->set_flashdata('success', 'Blog and blog contents has been added successfully.');
            }

            redirect('admin/blogs');
        }
        $this->template->load('default', 'admin/media_and_contents/add', $data);
    }

    public function delete($id = '') {
        $record_id = base64_decode($id);
        $this->blogs_model->insert_update('update', TBL_BLOGS, array('is_deleted' => 1), array('id' => $record_id));
        $this->session->set_flashdata('success', 'Blog has been deleted successfully.');
        redirect('admin/blogs');
    }

    public function view($id) {
        try {
            if (!empty($id)) {
                $data = array(
                    'title' => 'View Blog Media Content',
                );

                $blog_id = base64_decode($id);
                $data['blogArr'] = $this->blogs_model->get_all_details(TBL_BLOGS, ['id' => $blog_id])->row_array();
                $data['blogDataArr'] = $this->blogs_model->get_all_details(TBL_BLOG_MEDIA_CONTENTS, ['blog_id' => $blog_id])->result_array();

                return $this->template->load('default', 'admin/media_and_contents/view', $data);
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('success', $ex->getMessage());
            redirect('admin/blogs');
        }
    }

    public function remove_blog_media_content_files() {
        $blog_content_id = $this->input->post('blog_content_id');
        $file_url = $this->input->post('file_url');

        if (!empty($file_url)) {
            unlink($file_url);
        }

        $this->db->where('id', $blog_content_id);
        $is_deleted = $this->db->delete(TBL_BLOG_MEDIA_CONTENTS);

        if ($is_deleted) {
            $flag = TRUE;
        } else {
            $flag = FALSE;
        }
        
        echo $flag; exit;
    }

}

/* End of file Content.php */
/* Location: ./application/controllers/Content.php */