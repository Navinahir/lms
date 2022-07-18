<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscriber extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/subscriber_model'));
    }

    /**
     * Disaply Content: listing
     * @param --
     * @return --
     * @author HGA [Added : 28/12/2018]
     */
    public function display() {
        $data['title'] = 'Admin | List Subscriber';
        $this->template->load('default', 'admin/subscriber/display', $data);
    }

    /**
     * Get Content: data by ajax and displaying in datatable while displaying
     * @param --
     * @return Object (Json Format)
     * @author HGA [Added : 28/12/2018]
     */
    public function get_subscriber() {
        $where = [];
        $final['recordsTotal'] = $this->subscriber_model->get_subscriber_data('count', $where);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];

        $items = $this->subscriber_model->get_subscriber_data('result', $where);

        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

}

/* End of file subscriber.php */
/* Location: ./application/controllers/subscriber.php */