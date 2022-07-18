<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Mpdf\Mpdf;

class Reports extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/report_model', 'admin/users_model', 'admin/dashboard_model', 'admin/inventory_model', 'admin/customers_model', 'admin/custom_report_model'));
    }



    /**
     * Display Custom Reports 
     * @param --
     * @return --
     * @author JJP [Last Edited : 06/05/2020]
     */
    public function custom_reports(){
        $data['title'] = "Custom Reports";
        $data['currency'] = MY_Controller::$currency;

        $event_arr = array();
        if ($this->input->get()) {
            $get = explode(':=:', $this->input->get('date'));
            $event_arr['from_date'] = date('Y-m-d', strtotime($get[0]));
            $event_arr['to_date'] = date('Y-m-d', strtotime($get[1]));
        } else {
            $event_arr['from_date'] = date('Y-m-d', strtotime(date('Y-m-1')));
            $event_arr['to_date'] = date('Y-m-d', strtotime(date('Y-m-d')));
        }
        $data['get'] = $event_arr;
        $this->template->load('default_front', 'front/reports/custom_reports', $data);
    }

    // Diplay service discount sales data
    public function view_service_discount(){
        $data['title'] = "View Display Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_service_discount', $data);
    } 

    // Print taxable service Sales Data
    public function print_total_taxable_service_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
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

        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->join(TBL_SERVICES . ' as s', 's.id = es.service_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();

        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/taxable_total_service_sales_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of taxable Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Diplay taxable sales data
    public function view_service_tax(){
        $data['title'] = "View Taxable Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_service_total_tax', $data);
    }

    // Diplay non taxable service sales data
    public function view_service_non_taxable_sale(){
        $data['title'] = "View Non Taxable Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_service_non_taxable_sales', $data);
    }

    // Diplay service taxable sales data
    public function view_service_taxable_sales(){
        $data['title'] = "View Taxable Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_service_taxable_sales', $data);
    } 

    // Diplay Net Sales data
    public function view_service_net_sales(){
        $data['title'] = "View Net Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_service_net_sales', $data);
    }

    // Print gross part cost sales data
    public function print_part_cost_data(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
        $this->db->select('e.id,e.estimate_id,ui.part_no,ep.quantity,ep.discount,ep.discount_type_id,ep.tax_rate,ep.rate,ep.amount,ui.unit_cost');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0
        ));

        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();
        
        $final_cost = 0;
        foreach ($pdf_data as $key => $value) {
            $total_cost = $value['quantity'] * $value['unit_cost'];
            $final_cost += $total_cost;
        }

        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $data['final_cost'] = $final_cost;
        $html = $this->load->view('front/reports/part_cost_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Part Costing");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Display part cost AJAX Data
    public function get_part_cost_data(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_part_cost_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_part_cost_data('result');
        $start = $this->input->get('start') + 1;        
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['final_unit_cost'] = $val['quantity'] * $val['unit_cost'];
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    // Diplay Part cost data
    public function view_part_cost(){
        $data['title'] = "View Part Costing";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_part_cost', $data);
    }

    // Diplay discount sales data
    public function view_part_discount(){
        $data['title'] = "View discount Part Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_part_discount', $data);
    } 

    // Print taxable Part sales data
    public function print_total_taxable_part_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
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
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();
        
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/taxable_total_part_sale_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Taxable Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Display taxable Part Sales AJAX Data
    public function get_total_taxable_part_sales(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_total_taxable_part_sales('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_total_taxable_part_sales('result');
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

    // Diplay taxable part total sales data
    public function view_part_tax(){
        $data['title'] = "View Taxable Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_part_total_tax', $data);
    }

    // Diplay non taxable sales data
    public function view_part_non_taxable_sale(){
        $data['title'] = "View Non Taxable Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_part_non_taxable_sale', $data);
    } 

    // Diplay taxable sales data
    public function view_part_taxable_sale(){
        $data['title'] = "View Taxable Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_part_taxable_sale', $data);
    }

    // Diplay Part Net Sales data
    public function view_part_net_sale(){
        $data['title'] = "View Net Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_part_net_sale', $data);
    }

    // Display shipping charge AJAX Data
    public function get_shipping_charge(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_shipping_charge('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_shipping_charge('result');
        $start = $this->input->get('start') + 1;        
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    // Print gross Sales Data
    public function print_shipping_charge(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
        $this->db->select('e.estimate_date,e.estimate_id,e.cust_name,e.total,u.full_name,e.shipping_charge');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'e.shipping_charge >'=> 0
        ));
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();
        
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/shipping_charge_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Shipping Charge");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Diplay Gross Sales data
    public function view_shipping_charge(){
        $data['title'] = "View Shipping Charge";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_shipping_charge', $data);
    }

    // Print Service discount Sales Data
    public function print_service_discount_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
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

        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->join(TBL_SERVICES . ' as s', 's.id = es.service_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();

        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/service_discount_sales_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Discount Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Display Service Discount Sales AJAX Data
    public function get_service_discount_sales(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_service_discount_sales('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_service_discount_sales('result');
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

    // Print discount Part Sales Data
    public function print_part_discount_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        $this->db->select('e.id,e.estimate_id,ui.part_no,ep.quantity,ep.discount,ep.discount_type_id,ep.tax_rate,ep.discount_rate,ep.rate,ep.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0,
            'ep.discount_rate >' => 0
        ));
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();
        
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['dataArr'] = $pdf_data;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $html = $this->load->view('front/reports/part_discount_sales_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Discount Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Display Net Part Sales AJAX Data
    public function get_part_discount_sales(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_part_discount_sales('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_part_discount_sales('result');
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

    // Diplay discount sales data
    public function view_discount_sales(){
        $data['title'] = "View Display Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_discount_sales', $data);
    } 

    // Print non taxable service Sales Data
    public function print_non_taxable_service_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
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

        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->join(TBL_SERVICES . ' as s', 's.id = es.service_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();

        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/non_taxable_service_sales_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Non Taxable Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Display non taxable service sales AJAX Data
    public function get_non_taxable_service_sales(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_non_taxable_service_sales('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_non_taxable_service_sales('result');
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

    // Print non taxable part sales data
    public function print_non_taxable_part_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
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
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();
        
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/non_taxable_part_sale_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Non Taxable Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Display non taxable Part Sales AJAX Data
    public function get_non_taxable_part_sales(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_non_taxable_part_sales('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_non_taxable_part_sales('result');
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

    // Diplay non taxable sales data
    public function view_non_taxable_sales(){
        $data['title'] = "View Non Taxable Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_non_taxable_sales', $data);
    } 

    // Display taxable service sales AJAX Data
    public function get_taxable_service_sales(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_taxable_service_sales('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_taxable_service_sales('result');
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

    // Print taxable service Sales Data
    public function print_taxable_service_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
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

        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->join(TBL_SERVICES . ' as s', 's.id = es.service_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();

        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/taxable_service_sales_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of taxable Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Print taxable Part sales data
    public function print_taxable_part_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
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
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();
        
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/taxable_part_sale_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Taxable Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    /**
     * View Custom Reports Data 
     * @param --
     * @return --
     * @author JJP [Last Edited : 20/05/2020]
     */

    // Display taxable Part Sales AJAX Data
    public function get_taxable_part_sales(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_taxable_part_sales('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_taxable_part_sales('result');
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

    // Diplay taxable sales data
    public function view_taxable_sales(){
        $data['title'] = "View Taxable Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_taxable_sales', $data);
    } 

    // Diplay Gross Profit data
    public function view_gross_profit(){
        $data['title'] = "View Gross Profit";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_gross_profit', $data);
    }

    // Display gross profit invoice  AJAX Data
    public function get_gross_profit_invoice(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_gross_profit_invoices_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_gross_profit_invoices_data('result');
        $start = $this->input->get('start') + 1;        
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++; 
            $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    // count gross profit unit cost 
    public function get_gross_profit_unit_cost() {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $invoice_id = $this->input->post('invoice_id');
        $this->db->select('e.id,e.estimate_id,SUM(ui.unit_cost * ep.quantity) as unit_cost');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0,
            'ep.estimate_id' => $invoice_id,
        ));
        $this->db->group_by('e.id');
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
        if($data == "") {   
            $data['id'] = "";
        }
        echo json_encode($data);
    }

    // Print gross profit invoice data
    public function print_gross_profit_invoice_data(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
        $this->db->select('e.id,e.estimate_date,e.estimate_id,e.cust_name,e.sub_total,u.full_name,e.shipping_charge');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
        ));
        $this->db->order_by('e.id','desc');
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();

        $part_cost = [];
        $total_part_cost = 0;
        foreach ($pdf_data as $key => $value) {
            if($value['id'] != ""){
                $invoice_id = $value['id'];
                $this->db->select('e.id,e.estimate_id,SUM(ui.unit_cost * ep.quantity) as unit_cost');
                $this->db->where(array(
                    'e.is_deleted' => 0,
                    'e.business_user_id' => checkUserLogin('C'),
                    'e.is_invoiced' => 1,
                    'e.estimate_date >=' => $from_date,
                    'e.estimate_date <=' => $to_date,
                    'ep.is_deleted' => 0,
                    'ep.estimate_id' => $invoice_id,
                ));
                $this->db->group_by('e.id');
                $this->db->order_by('e.estimate_id','DESC');
                $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
                $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
                $cost = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
                $total_part_cost += $cost['unit_cost'];
                array_push($part_cost, $cost['unit_cost']);
            }
        }
        
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $data['part_cost'] = $part_cost;
        $data['total_part_cost'] = $total_part_cost;
        $html = $this->load->view('front/reports/gross_profit_invoice_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Gross Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Display gross profit Part Sales AJAX Data
    public function get_gross_profit_part_data(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_gross_profit_part_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_gross_profit_part_data('result');
        $start = $this->input->get('start') + 1;        
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['final_unit_cost'] = $val['quantity'] * $val['unit_cost'];
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    // Print gross profit part sales data
    public function print_gross_profit_part_data(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
        $this->db->select('e.id,e.estimate_id,ui.part_no,ep.quantity,ep.discount,ep.discount_type_id,ep.tax_rate,ep.rate,ep.amount,ui.unit_cost');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0
        ));

        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();

        $final_cost = 0;
        foreach ($pdf_data as $key => $value) {
            $total_cost = $value['quantity'] * $value['unit_cost'];
            $final_cost += $total_cost;
        }

        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $data['final_cost'] = $final_cost;
        $html = $this->load->view('front/reports/gross_profit_part_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Gross Profit");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Display gross profit Part Sales AJAX Data
    public function get_gross_profit_service_data(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_gross_profit_service_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_gross_profit_service_data('result');
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

    // Print gross profit service sales data
    public function print_gross_profit_service_data(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
        $this->db->select('e.id,e.estimate_id,s.name,es.qty,es.discount,es.discount_type_id,es.tax_rate,es.rate,es.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'es.is_deleted' => 0
        ));

        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->join(TBL_SERVICES . ' as s', 's.id = es.service_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();

        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['dataArr'] = $pdf_data;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $html = $this->load->view('front/reports/gross_profit_service_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Gross Profit");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Display gross profit shipping AJAX Data
    public function get_gross_profit_shipping(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_gross_profit_shipping('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_gross_profit_shipping('result');
        $start = $this->input->get('start') + 1;        
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset'] );
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    // Print gross profit invoice data
    public function print_gross_profit_shipping_data(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
        $this->db->select('e.estimate_date,e.estimate_id,e.shipping_charge');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'e.shipping_charge >' => 0,
        ));
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();
        
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/gross_profit_shipping_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Gross Profit");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Diplay Net Sales data
    public function view_net_sales(){
        $data['title'] = "View Net Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_net_sales', $data);
    }    

    // Display net sales data ("Include part discount")
    public function get_net_sales_part(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_net_sales_part('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_net_sales_part('result');
        $start = $this->input->get('stat') + 1;     
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
            $items[$key]['sr_no'] = $start++;
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        // pr($final['data']); die;
        echo json_encode($final);
        die;
    }

    // Display net sales data 
    public function get_net_sales_service(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_net_sales_service('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_net_sales_service('result');
        $start = $this->input->get('stat') + 1;     
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
            $items[$key]['sr_no'] = $start++;
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        // pr($final['data']); die;
        echo json_encode($final);
        die;
    }

    // Display net sales data ("Include service discount")
    // public function get_net_sales_service(){
    //     $from_date = $this->input->post('from_date');
    //     $to_date = $this->input->post('to_date');
    //     $invoice_id = $this->input->post('invoice_id');

    //     $this->db->select('e.id,e.estimate_id,e.estimate_date,e.total,es.estimate_id as s_estimate_id,SUM(es.discount_rate) as service_discount');
    //     $this->db->where(array(
    //         'e.is_deleted' => 0,
    //         'e.business_user_id' => checkUserLogin('C'),
    //         'e.is_invoiced' => 1,
    //         'e.estimate_date >=' => $from_date,
    //         'e.estimate_date <=' => $to_date,
    //         'es.is_deleted' => 0,
    //         'es.estimate_id' => $invoice_id,
    //     ));
    //     $this->db->group_by('e.id');
    //     $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
    //     $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
    //     echo json_encode($data);
    //     die;
    // }   

    // Display Net Part Sales AJAX Data
    public function get_part_net_sales(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_part_net_sales('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_part_net_sales('result');
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

    // Print part Invoice deatil
    public function print_part_invoice_detail_net_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
        $this->db->select('e.id,e.estimate_id,e.estimate_date,e.total,e.sub_total,u.full_name,SUM(ep.amount) as part_amount,SUM(ep.discount_rate) as part_discount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0
        ));
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->group_by('e.id');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();

        $final_discount = [];
        foreach ($pdf_data as $key => $value) {
            if($value['id'] != ""){
                $invoice_id = $value['id'];
                $this->db->select('SUM(es.discount_rate) as service_discount');
                $this->db->where(array(
                    'e.is_deleted' => 0,
                    'e.business_user_id' => checkUserLogin('C'),
                    'e.is_invoiced' => 1,
                    'e.estimate_date >=' => $from_date,
                    'e.estimate_date <=' => $to_date,
                    'es.is_deleted' => 0,
                    'es.estimate_id' => $invoice_id,
                ));
                $this->db->group_by('e.id');
                $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
                $srv_discount = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
                $discount['total_discount'] = $value['part_discount'] + $srv_discount['service_discount'];
                array_push($final_discount, $discount['total_discount']);
            }
        }
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $data['final_discount'] = $final_discount;
        $html = $this->load->view('front/reports/part_invoice_detail_net_sales_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Net Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Print part Invoice deatil
    public function print_service_invoice_detail_net_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
        $this->db->select('e.id,e.estimate_id,e.estimate_date,e.total,e.sub_total,u.full_name,SUM(es.amount) as part_amount,SUM(es.discount_rate) as part_discount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'es.is_deleted' => 0
        ));
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->group_by('e.id');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();

        $final_discount = [];
        foreach ($pdf_data as $key => $value) {
            if($value['id'] != ""){
                $invoice_id = $value['id'];
                $this->db->select('SUM(es.discount_rate) as service_discount');
                $this->db->where(array(
                    'e.is_deleted' => 0,
                    'e.business_user_id' => checkUserLogin('C'),
                    'e.is_invoiced' => 1,
                    'e.estimate_date >=' => $from_date,
                    'e.estimate_date <=' => $to_date,
                    'es.is_deleted' => 0,
                    'es.estimate_id' => $invoice_id,
                ));
                $this->db->group_by('e.id');
                $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
                $srv_discount = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
                $discount['total_discount'] = $value['part_discount'] + $srv_discount['service_discount'];
                array_push($final_discount, $discount['total_discount']);
            }
        }
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $data['final_discount'] = $final_discount;
        $html = $this->load->view('front/reports/service_invoice_detail_net_sales_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Net Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Print Part Net Sales Data
    public function print_part_net_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
        $this->db->select('e.id,e.estimate_id,ui.part_no,ep.quantity,ep.discount,ep.discount_type_id,ep.tax_rate,ep.rate,ep.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'ep.is_deleted' => 0
        ));
        $this->db->join(TBL_ESTIMATE_PARTS . ' as ep', 'ep.estimate_id = e.id', 'left');
        $this->db->join(TBL_USER_ITEMS . ' as ui', 'ui.id = ep.part_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();
        
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/part_net_sales_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Net Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Print Service Net Sales Data
    public function print_service_net_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
        $this->db->select('e.id,e.estimate_id,s.name,es.qty,es.discount,es.discount_type_id,es.tax_rate,es.rate,es.amount');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
            'es.is_deleted' => 0
        ));

        $this->db->join(TBL_ESTIMATE_SERVICES . ' as es', 'es.estimate_id = e.id', 'left');
        $this->db->join(TBL_SERVICES . ' as s', 's.id = es.service_id', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();

        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/service_net_sales_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Net Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    // Display Net Service Sales AJAX Data
    public function get_service_net_sales(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_service_net_sales('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_service_net_sales('result');
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

    // Diplay Gross Sales data
    public function view_gross_sales(){
        $data['title'] = "View Gross Sales";        
        $f_date_y = $this->uri->segment(3).'/';
        $f_date_m = $this->uri->segment(4).'/';
        $f_date_d = substr($this->uri->segment(5),0,2);
        $data['from_date'] = $f_date_y.$f_date_m.$f_date_d;

        $t_date_y = substr($this->uri->segment(5),5).'/';
        $t_date_m = $this->uri->segment(6).'/';
        $t_date_d = $this->uri->segment(7);
        $data['to_date'] = $t_date_y.$t_date_m.$t_date_d;

        $this->template->load('default_front', 'front/custom_report_data/view_gross_sales', $data);
    }

    // Display Gross Sales AJAX Data
    public function get_gross_sales(){
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->custom_report_model->get_invoices_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->custom_report_model->get_invoices_data('result');
        $start = $this->input->get('start') + 1;        
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']));
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    // Print gross Sales Data
    public function print_gross_sales(){
        $date = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $from_date = substr($date,0,10);
        $to_date = substr($date,10,20);
        
        $this->db->select('e.estimate_date,e.estimate_id,e.cust_name,e.total,u.full_name');
        $this->db->where(array(
            'e.is_deleted' => 0,
            'e.business_user_id' => checkUserLogin('C'),
            'e.is_invoiced' => 1,
            'e.estimate_date >=' => $from_date,
            'e.estimate_date <=' => $to_date,
        ));
        $this->db->join(TBL_USERS . ' as u', 'u.id = e.sales_person', 'left');
        $this->db->order_by('e.id','DESC');
        $pdf_data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();
        
        $data['format'] = MY_Controller::$date_format;
        $data['currency'] = MY_Controller::$currency;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['dataArr'] = $pdf_data;
        $html = $this->load->view('front/reports/gross_sales_pdf_view', $data, true);
        
        require_once FCPATH . 'Library/PDF/autoload.php';
        // require_once FCPATH . 'vendor/autoload.php';
        $mpdf = new Mpdf();
        $mpdf->SetTitle("Report Of Gross Sales");
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
        $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
        $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
        $mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5  // margin footer
        );
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
        $mpdf->SetJS('this.print();');
        $mpdf->Output($filename, "I");
        $mpdf->Output("uploads/pdf/reports/custom/" . $filename, "F");
        $pdf = base_url() . 'uploads/pdf/reports/custom/' . $filename;
        return $pdf;
    }

    /**
     * Display Custom Reports Data 
     * @param --
     * @return --
     * @author JJP [Last Edited : 06/05/2020]
     */

    // Count Gross Sales
    public function custom_reports_data_gross_sales() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(e.id) as count_invoice ,IFNULL(SUM(e.total), 0) AS gross_sales');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
            ]);
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }

    // Count Shipping charge
    public function custom_reports_shipping_charge() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(e.shipping_charge) as count_shipping_charge ,IFNULL(SUM(e.shipping_charge), 0) AS shipping_charge');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
                'e.shipping_charge !=' => ""
            ]);
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }

    // Count Part Tax Amount
    public function custom_reports_data_part_tax_amount() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(ep.individual_part_tax) as count_part_tax_amount ,IFNULL(SUM(ep.tax_rate), 0) AS part_tax_amount');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
                'ep.is_deleted' => 0,
                'ep.individual_part_tax !=' => ""
            ]);
            $this->db->join(TBL_ESTIMATE_PARTS. ' AS ep','e.id = ep.estimate_id');
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }

    // Count Service Tax Amount
    public function custom_reports_data_service_tax_amount() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(es.individual_service_tax) AS count_service_tax_amount, IFNULL(SUM(es.tax_rate), 0) AS service_tax_amount');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
                'es.is_deleted' => 0,
                'es.individual_service_tax !=' => ""
            ]);
            $this->db->join(TBL_ESTIMATE_SERVICES. ' AS es','e.id = es.estimate_id');
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }

    // Count Part Net Sales
    public function custom_reports_data_net_sell_part() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(ep.id) AS count_net_sell_part, IFNULL(SUM(ep.amount), 0) AS net_sell_part');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
                'ep.is_deleted' => 0,           
            ]);
            $this->db->join(TBL_ESTIMATE_PARTS. ' AS ep','e.id = ep.estimate_id');
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }

    // Count Part Net Discount Sales
    public function custom_reports_data_net_discount_sell_part() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(ep.discount_rate) AS count_part_total_discount, IFNULL(SUM(ep.discount_rate), 0) AS net_discount_sell_part');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
                'ep.is_deleted' => 0,           
                'ep.discount_rate >' => 0,           
            ]);
            $this->db->join(TBL_ESTIMATE_PARTS. ' AS ep','e.id = ep.estimate_id');
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }

    // Count Service Net Sales
    public function custom_reports_data_net_sell_service() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(es.id) as count_net_sell_service, IFNULL(SUM(es.amount), 0) AS net_sell_service');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
                'es.is_deleted' => 0,           
            ]);
            $this->db->join(TBL_ESTIMATE_SERVICES. ' AS es','e.id = es.estimate_id');
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }

    // Count Service Net Discount Sales
    public function custom_reports_data_net_discount_sell_service() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(es.discount_rate) AS count_service_total_discount, IFNULL(SUM(es.discount_rate), 0) AS net_discount_sell_service');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
                'es.is_deleted' => 0, 
                'es.discount_rate >' => 0,
            ]);
            $this->db->join(TBL_ESTIMATE_SERVICES. ' AS es','e.id = es.estimate_id');
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }
    
    // Count Taxable Part 
    public function custom_reports_data_taxable_part() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(ep.tax_id) as count_net_sell_taxable_part, IFNULL(SUM(ep.amount), 0) AS net_sell_taxable_part');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
                'ep.is_deleted' => 0,
                'ep.tax_id > ' => 0,
            ]);
            $this->db->join(TBL_ESTIMATE_PARTS. ' AS ep','e.id = ep.estimate_id');
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }

    // Count Taxable Service 
    public function custom_reports_data_taxable_service() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(es.tax_id) as count_net_sell_taxable_service, IFNULL(SUM(es.amount), 0) AS net_sell_taxable_service');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
                'es.is_deleted' => 0,
                'es.tax_id > ' => 0,
            ]);
            $this->db->join(TBL_ESTIMATE_SERVICES. ' AS es','e.id = es.estimate_id');
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }

    // Count Non Taxable Part 
    public function custom_reports_data_non_taxable_part() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(ep.tax_id) count_net_sell_non_taxable_part, IFNULL(SUM(ep.amount),0) AS net_sell_non_taxable_part');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
                'ep.is_deleted' => 0,
                'ep.tax_id' => 0,
            ]);
            $this->db->join(TBL_ESTIMATE_PARTS. ' AS ep','e.id = ep.estimate_id');
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }

    // Count Non Taxable Service
    public function custom_reports_data_non_taxable_service() {
        if(!empty($this->input->post('date'))) {
            $this->db->select('count(es.tax_id) as count_net_sell_non_taxable_service, IFNULL(SUM(es.amount), 0) AS net_sell_non_taxable_service');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
                'es.is_deleted' => 0,
                'es.tax_id' => 0,
            ]);
            $this->db->join(TBL_ESTIMATE_SERVICES. ' AS es','e.id = es.estimate_id');
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->row_array();
            echo json_encode($data); exit;        
        }
    }

    // Count Gross Profit
    public function custom_reports_data_gross_profit(){
        if(!empty($this->input->post('date'))) {
            $this->db->select('e.id AS estimate_id');
            $date = explode(':=:', $this->input->post('date'));
            $this->db->where([
                'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                'e.is_deleted' => 0,
                'e.is_invoiced' => 1,
                'e.business_user_id' => checkUserLogin('C'),
            ]);
            $data = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();
            $estimate_id = array_column($data, 'estimate_id'); 
            
            foreach ($estimate_id as $key => $est_id) {
                $this->db->select('e.id AS estimate_id, ui.part_no as part_no, ep.part_id as part_id, (ep.quantity * ui.unit_cost) as unit_cost');
                $this->db->where([
                    'e.estimate_date >=' => date('Y/m/d', strtotime($date[0])),
                    'e.estimate_date <=' => date('Y/m/d', strtotime($date[1])),
                    'e.is_deleted' => 0,
                    'e.is_invoiced' => 1,
                    'e.business_user_id' => checkUserLogin('C'),
                    'ep.is_deleted' => 0,
                ]);
                $this->db->join(TBL_ESTIMATE_PARTS. ' AS ep','e.id = ep.estimate_id');
                $this->db->join(TBL_USER_ITEMS. ' AS ui','ep.part_id = ui.id');
                $data['gross_profit'] = $this->db->get(TBL_ESTIMATES . ' as e')->result_array();    
            }
            echo json_encode($data); exit;        
        }
    }

    /**
     * Display All Sales report 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function sales() {
        $data['title'] = 'Report of Sales';
        $event_arr = array();
        if ($this->input->get()) {
            $get = explode(':=:', $this->input->get('date'));
            $event_arr['from_date'] = date('Y-m-d', strtotime($get[0]));
            $event_arr['to_date'] = date('Y-m-d', strtotime($get[1]));
        } else {
            $event_arr['from_date'] = date('Y-m-d', strtotime(date('Y-m-1')));
            $event_arr['to_date'] = date('Y-m-d', strtotime(date('Y-m-d')));
        }
        $data['get'] = $event_arr;
        $this->template->load('default_front', 'front/reports/sales', $data);
    }

    /**
     * Return date array for given date-range.
     * @param type $start_date
     * @param type $end_date
     * @param type $format
     * @return type
     */
    public function getRangeNDays($start_date, $end_date, $format = 'd-M') {
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));
        $day = 86400; // Day in seconds  
        $sTime = strtotime($start_date); // Start as time  
        $eTime = strtotime($end_date); // End as time  
        $numDays = round(($eTime - $sTime) / $day) + 1;
        $days = array();
        for ($d = 0; $d < $numDays; $d++) {
            $days['"' . date('Y-m-d', ($sTime + ($d * $day))) . '"'] = date($format, ($sTime + ($d * $day)));
        }
        return $days;
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_sales_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->report_model->get_sales_ajax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->report_model->get_sales_ajax_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_rate'] = number_format((float) $val['estimate_rate'], 2, '.', '');
            $items[$key]['estimate_date'] = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get today's all invoices and total.
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_daily_sales_data() {
        $format = MY_Controller::$date_format;

        $final['recordsTotal'] = $this->report_model->get_daily_sales_ajax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->report_model->get_daily_sales_ajax_data('result')->result_array();

        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_rate'] = number_format((float) $val['total'], 2, '.', '');
            $items[$key]['invoice_date'] = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
            
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    public function get_sales_graph_data() {
        $final['code'] = '404';
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $date = explode(":=:", $wh['date']);
        $currency = MY_Controller::$currency;
        $items = $this->report_model->get_sales_data($wh)->result_array();
        $arr = $this->getRangeNDays($date['0'], $date['1'], 'jS M \'y');
        $new_array = [];
        foreach ($arr as $k => $v) {
            if (false !== ($key = array_search(trim($k, '"'), array_column($items, 'estimate_date')))) {
                $new_array[trim($k, '"')] = round($items[$key]['estimate_rate'],2);
            } else {
                $new_array[trim($k, '"')] = 0;
            }
        }
        if (!empty($new_array)):
            $final['code'] = '200';
            $final['xAxis'] = array_keys($new_array);
            $final['data'] = array_values($new_array);
            $final['sum'] = array_sum(array_values($new_array));
            $final['cur'] = $currency['symbol'];
        endif;
        echo json_encode($final);
        die;
    }

    public function print_sales() {
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $pdf_data = $this->report_model->get_sales_data($wh)->result_array();
        if (!empty($pdf_data)):
            $data['format'] = MY_Controller::$date_format;
            $data['currency'] = MY_Controller::$currency;
            $data['dataArr'] = $pdf_data;
            $html = $this->load->view('front/reports/sales_pdf_view', $data, true);
            
            require_once FCPATH . 'Library/PDF/autoload.php';
            // require_once FCPATH . 'vendor/autoload.php';
            $mpdf = new Mpdf();
            $mpdf->SetTitle("Report of Collected Tax");
            $mpdf->defaultfooterline = 0;
            $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
            $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
            $filename = "sales_pdf_" . date('Ymdhis') . ".pdf";
            $mpdf->SetJS('this.print();');
            $mpdf->Output($filename, "I");
            $mpdf->Output("uploads/pdf/reports/sales/" . $filename, "F");
            $pdf = base_url() . 'uploads/pdf/reports/sales/' . $filename;
            return $pdf;
        endif;
    }

    /**
     * Display All Tax report 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function tax() {
        $data['title'] = 'Report of Collected Tax';
        $data['dataArr'] = $this->report_model->get_all_details(TBL_TAXES, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->result_array();
        $event_arr = array();
        if ($this->input->get()) {
            $get = explode('-', $this->input->get('date'));
            $event_arr['from_date'] = date('Y-m-d', strtotime($get[0]));
            $event_arr['to_date'] = date('Y-m-d', strtotime($get[1]));
        } else {
            $event_arr['from_date'] = date('Y-m-d', strtotime(date('Y-m-1')));
            $event_arr['to_date'] = date('Y-m-d');
        }
        $data['get'] = $event_arr;
        $this->template->load('default_front', 'front/reports/tax', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_tax_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->report_model->get_tax_data('count',null,1);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        // $items = $this->report_model->get_tax_data('result')->result_array();
        $items = $this->report_model->get_tax_data('result',null,1);
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_rate'] = number_format((float) $val['estimate_rate'], 2, '.', '');
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_invoice_tax_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->report_model->get_invoice_tax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->report_model->get_invoice_tax_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_rate'] = number_format((float) $val['estimate_rate'], 2, '.', '');
            $items[$key]['invoice_date'] = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_service_tax_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->report_model->get_service_tax_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->report_model->get_service_tax_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_rate'] = number_format((float) $val['estimate_rate'], 2, '.', '');
            $items[$key]['invoice_date'] = date($format['format'], strtotime($val['estimate_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get all the data of get_labor_and_services_graph_data for graph
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_tax_graph_data() {
        $final['code'] = '404';
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $wh['tax'] = ($this->input->get('tax') && $this->input->get('tax') != '') ? $this->input->get('tax') : null;
        $currency = MY_Controller::$currency;
        $items = $this->report_model->get_tax_data_pdf($wh, 1,null,1);
        $maxitems = $this->report_model->get_tax_data_pdf($wh,null,null,1);
        if (!empty($items)):
            $final['code'] = '200';
            $final['tax'] = array_column($items, 'name');
            $final['data'] = $items;
            $final['sum'] = array_sum(array_column($maxitems, 'estimate_rate'));
            $final['cur'] = $currency['symbol'];
        endif;
        echo json_encode($final);
        die;
    }

    public function print_tax_data() {
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $wh['tax'] = ($this->input->get('tax') && $this->input->get('tax') != '') ? $this->input->get('tax') : null;
        $pdf_data = $this->report_model->get_tax_data_pdf($wh, 1, 1,1);
        if (!empty($pdf_data)):
            $data['format'] = MY_Controller::$date_format;
            $data['currency'] = MY_Controller::$currency;
            $data['dataArr'] = $pdf_data;
            $html = $this->load->view('front/reports/tax_pdf_view', $data, true);
            require_once FCPATH . 'Library/PDF/autoload.php';
            // require_once FCPATH . 'vendor/autoload.php';
            $mpdf = new Mpdf();
            $mpdf->SetTitle("Report of Collected Tax");
            $mpdf->defaultfooterline = 0;
            $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
            $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
            $filename = "tax_pdf_" . date('Ymdhis') . ".pdf";
            $mpdf->SetJS('this.print();');
            $mpdf->Output($filename, "I");
            $mpdf->Output("uploads/pdf/reports/tax/" . $filename, "F");
            $pdf = base_url() . 'uploads/pdf/reports/tax/' . $filename;
            return $pdf;
        endif;
    }

    /**
     * Display All sales_by_user report 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function sales_by_user() {
        $data['title'] = 'Report of Sales by user';
        $data['dataArr'] = $this->users_model->total_users(1);
        $event_arr = array();
        if ($this->input->get()) {
            $get = explode('-', $this->input->get('date'));
            $event_arr['from_date'] = date('Y-m-d', strtotime($get[0]));
            $event_arr['to_date'] = date('Y-m-d', strtotime($get[1]));
        } else {
            $event_arr['from_date'] = date('Y-m-d', strtotime(date('Y-m-1')));
            $event_arr['to_date'] = date('Y-m-d');
        }
        $data['get'] = $event_arr;
        $this->template->load('default_front', 'front/reports/sales_by_user', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_sales_by_user_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->report_model->get_sales_by_user_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->report_model->get_sales_by_user_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_rate'] = number_format((float) $val['estimate_rate'], 2, '.', '');
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get all the data of get_labor_and_services_graph_data for graph
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_sales_by_user_graph_data() {
        $final['code'] = '404';
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $wh['user'] = ($this->input->get('user') && $this->input->get('user') != '') ? $this->input->get('user') : null;
        $currency = MY_Controller::$currency;
        $items = $this->report_model->get_sales_by_user_graph_data($wh, 1);
        $maxitems = $this->report_model->get_sales_by_user_graph_data($wh);
        if (!empty($items)):
            $final['code'] = '200';
            $final['user'] = array_column($items, 'name');
            $final['data'] = $items;
            $final['sum'] = array_sum(array_column($maxitems, 'value'));
            $final['cur'] = $currency['symbol'];
        endif;
        echo json_encode($final);
        die;
    }

    public function print_sales_by_user() {
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $wh['user'] = ($this->input->get('user') && $this->input->get('user') != '') ? $this->input->get('user') : null;
        $pdf_data = $this->report_model->get_sales_by_user_data_pdf($wh)->result_array();
        if (!empty($pdf_data)):
            $data['format'] = MY_Controller::$date_format;
            $data['currency'] = MY_Controller::$currency;
            $data['dataArr'] = $pdf_data;
            $html = $this->load->view('front/reports/sales_by_user_pdf_view', $data, true);
            require_once FCPATH . 'Library/PDF/autoload.php';
            // require_once FCPATH . 'vendor/autoload.php';
            $mpdf = new Mpdf();
            $mpdf->SetTitle("Report of Sales By user");
            $mpdf->defaultfooterline = 0;
            $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
            $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
            $filename = "sales_by_user_pdf_" . date('Ymdhis') . ".pdf";
            $mpdf->SetJS('this.print();');
            $mpdf->Output($filename, "I");
            $mpdf->Output("uploads/pdf/reports/sales_by_user/" . $filename, "F");
            $pdf = base_url() . 'uploads/pdf/reports/sales_by_user/' . $filename;
            return $pdf;
        endif;
    }

    /**
     * Display All inventory value report 
     * @param --
     * @return --
     * @author HPA [Last Edited : 13/11/2020]
     */
    public function inventory_value() {
        $data['title'] = 'Report Of Inventory Value';
        $this->template->load('default_front', 'front/reports/inventory_value', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 23/11/2020]
     */
    public function get_inventory_value_items_data() {
        $final['recordsTotal'] = $this->report_model->get_inventory_value_items_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->report_model->get_inventory_value_items_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $items[$key]['inventory_value'] = round($val['total_quantity'] * $val['retail_price'],2);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Print inventory value data
     * @param --
     * @return --
     * @author JJP [Last Edited : 25/11/2020]
     */
    public function print_inventory_value_data() {
        $pdf_data = $this->report_model->get_print_inventory_value_items_data();
        if (!empty($pdf_data)):
            $data['format'] = MY_Controller::$date_format;
            $data['currency'] = MY_Controller::$currency;
            $data['dataArr'] = $pdf_data;
            $html = $this->load->view('front/reports/inventory_value_pdf_view', $data, true);
            require_once FCPATH . 'Library/PDF/autoload.php';
            $mpdf = new Mpdf();
            $mpdf->SetTitle("Report Of Inventory Value");
            $mpdf->defaultfooterline = 0;
            $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
            $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
            $filename = "inventory_value_pdf_" . date('Ymdhis') . ".pdf";
            $mpdf->SetJS('this.print();');
            $mpdf->Output($filename, "I");
            $mpdf->Output("uploads/pdf/reports/inventory_value/" . $filename, "F");
            $pdf = base_url() . 'uploads/pdf/reports/inventory_value/' . $filename;
            return $pdf;
        endif;
    }
    
    /**
     * Display All inventory investment report 
     * @param --
     * @return --
     * @author HPA [Last Edited : 13/11/2020]
     */
    public function inventory_investment() {
        $data['title'] = 'Report Of Inventory Investment';
        $this->template->load('default_front', 'front/reports/inventory_investment', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author JJP [Last Edited : 26/11/2020]
     */
    public function get_inventory_investment_items_data() {
        $final['recordsTotal'] = $this->report_model->inventory_investment_items_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->report_model->inventory_investment_items_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['modified_date'] = date('m-d-Y h:i A', strtotime($val['modified_date']) + $_COOKIE['currentOffset']);
            $items[$key]['inventory_investment'] = round($val['total_quantity'] * $val['unit_cost'],2);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Print inventory investment data
     * @param --
     * @return --
     * @author JJP [Last Edited : 25/11/2020]
     */
    public function print_inventory_investment_data() {
        $pdf_data = $this->report_model->get_print_inventory_investment_items_data();
        if (!empty($pdf_data)):
            $data['format'] = MY_Controller::$date_format;
            $data['currency'] = MY_Controller::$currency;
            $data['dataArr'] = $pdf_data;
            $html = $this->load->view('front/reports/inventory_investment_pdf_view', $data, true);
            require_once FCPATH . 'Library/PDF/autoload.php';
            $mpdf = new Mpdf();
            $mpdf->SetTitle("Report Of Inventory Investment");
            $mpdf->defaultfooterline = 0;
            $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
            $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
            $filename = "inventory_investment_pdf_" . date('Ymdhis') . ".pdf";
            $mpdf->SetJS('this.print();');
            $mpdf->Output($filename, "I");
            $mpdf->Output("uploads/pdf/reports/inventory_investment/" . $filename, "F");
            $pdf = base_url() . 'uploads/pdf/reports/inventory_investment/' . $filename;
            return $pdf;
        endif;
    }
    
    /**
     * Display All popular_items report 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function popular_items() {
        $data['title'] = 'Report of Popular Items';
        $data['dataArr'] = $this->inventory_model->get_inventory_vendor_data()->result_array();
        $event_arr = array();
        if ($this->input->get()) {
            $get = explode(':=:', $this->input->get('date'));
            $event_arr['from_date'] = date('Y-m-d', strtotime($get[0]));
            $event_arr['to_date'] = date('Y-m-d', strtotime($get[1]));
        } else {
            $event_arr['from_date'] = date('Y-m-d', strtotime(date('Y-m-1')));
            $event_arr['to_date'] = date('Y-m-d');
        }
        $event_arr['date'] = (isset($event_arr['from_date']) && isset($event_arr['from_date'])) ? ($event_arr['from_date']) . ":=:" . ($event_arr['to_date']) : null;
        $event_arr['parts'] = ($this->input->get('parts') && $this->input->get('parts') != '') ? explode(',', $this->input->get('parts')) : null;
        $data['get'] = $event_arr;
        $data['ItemArr'] = $this->report_model->get_popular_item_data($event_arr);
        $data['currency'] = MY_Controller::$currency;
        $this->template->load('default_front', 'front/reports/popular_items', $data);
    }

    public function get_popular_graph_data() {
        $final['code'] = '404';
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $wh['parts'] = ($this->input->get('parts') && $this->input->get('parts') != '') ? $this->input->get('parts') : null;
        $currency = MY_Controller::$currency;
        $items = $this->report_model->get_popular_item_data($wh);
        if (!empty($items)):
            $new = [];
            foreach ($items as $k => $v) {
                $new[$k]['name'] = $v['part_no'];
                $new[$k]['value'] = round($v['total_amount'],2);
            }
            $final['code'] = '200';
            $final['parts'] = array_column($items, 'part_no');
            $final['data'] = $new;
            $final['sum'] = array_sum(array_column($items, 'total_amount'));
            $final['max_value'] =round(max(array_column($items, 'total_amount')),2);
            $final['cur'] = $currency['symbol'];
        endif;
        echo json_encode($final);
        die;
    }

    public function print_popular_items() {
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $wh['parts'] = ($this->input->get('parts') && $this->input->get('parts') != '') ? explode(',', $this->input->get('parts')) : null;
        $pdf_data = $this->report_model->get_popular_item_data($wh);
        if (!empty($pdf_data)):
            $data['format'] = MY_Controller::$date_format;
            $data['currency'] = MY_Controller::$currency;
            $data['dataArr'] = $pdf_data;
            $html = $this->load->view('front/reports/popular_item_pdf_view', $data, true);
            require_once FCPATH . 'Library/PDF/autoload.php';
            // require_once FCPATH . 'vendor/autoload.php';
            $mpdf = new Mpdf();
            $mpdf->SetTitle("Report of Popular Parts");
            $mpdf->defaultfooterline = 0;
            $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
            $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
            $filename = "popular_item_pdf_" . date('Ymdhis') . ".pdf";
            $mpdf->SetJS('this.print();');
            $mpdf->Output($filename, "I");
            $mpdf->Output("uploads/pdf/reports/popular_item/" . $filename, "F");
            $pdf = base_url() . 'uploads/pdf/reports/popular_item/' . $filename;
            return $pdf;
        endif;
    }

    /**
     * Display All popular_items report 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function sales_by_category() {
        $data['title'] = 'Report of Sales By Category';
        $data['dataArr'] = $this->report_model->get_all_details(TBL_DEPARTMENTS, array('is_delete' => 0))->result_array();
        $event_arr = array();
        if ($this->input->get()) {
            $get = explode('-', $this->input->get('date'));
            $event_arr['from_date'] = date('Y-m-d', strtotime($get[0]));
            $event_arr['to_date'] = date('Y-m-d', strtotime($get[1]));
        } else {
            $event_arr['from_date'] = date('Y-m-d', strtotime(date('Y-m-1')));
            $event_arr['to_date'] = date('Y-m-d');
        }
        $data['get'] = $event_arr;
        $this->template->load('default_front', 'front/reports/sales_by_category', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_sales_by_category_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->report_model->get_sales_by_category_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->report_model->get_sales_by_category_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_rate'] = number_format((float) $val['estimate_rate'], 2, '.', '');
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get all the data of get_labor_and_services_graph_data for graph
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_sales_by_category_graph_data() {
        $final['code'] = '404';
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $wh['category'] = ($this->input->get('category') && $this->input->get('category') != '') ? $this->input->get('category') : null;
        $currency = MY_Controller::$currency;
        $items = $this->report_model->get_sales_by_category_graph_data($wh, 1);
        $maxitems = $this->report_model->get_sales_by_category_graph_data($wh);
        if (!empty($items)):
            $final['code'] = '200';
            $final['category'] = array_column($items, 'name');
            $final['data'] = $items;
            $final['sum'] = array_sum(array_column($maxitems, 'value'));
            $final['cur'] = $currency['symbol'];
        endif;
        echo json_encode($final);
        die;
    }

    public function print_sales_by_category() {
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $wh['category'] = ($this->input->get('category') && $this->input->get('category') != '') ? $this->input->get('category') : null;
        $pdf_data = $this->report_model->get_sales_by_category_data_pdf($wh)->result_array();
        if (!empty($pdf_data)):
            $data['format'] = MY_Controller::$date_format;
            $data['currency'] = MY_Controller::$currency;
            $data['dataArr'] = $pdf_data;
            $html = $this->load->view('front/reports/sales_by_category_pdf_view', $data, true);
            require_once FCPATH . 'Library/PDF/autoload.php';
            // require_once FCPATH . 'vendor/autoload.php';
            $mpdf = new Mpdf();
            $mpdf->SetTitle("Report of Sales By Category");
            $mpdf->defaultfooterline = 0;
            $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
            $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
            $filename = "sales_by_category_pdf_" . date('Ymdhis') . ".pdf";
            $mpdf->SetJS('this.print();');
            $mpdf->Output($filename, "I");
            $mpdf->Output("uploads/pdf/reports/sales_by_category/" . $filename, "F");
            $pdf = base_url() . 'uploads/pdf/reports/sales_by_category/' . $filename;
            return $pdf;
        endif;
    }

    /**
     * Display All labour_and_services report 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function labor_and_services() {
        $data['title'] = 'Report for Labor and Services';
        $data['dataArr'] = $this->report_model->get_all_details(TBL_SERVICES, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->result_array();
        $event_arr = array();
        if ($this->input->get()) {
            $get = explode('-', $this->input->get('date'));
            $event_arr['from_date'] = date('Y-m-d', strtotime($get[0]));
            $event_arr['to_date'] = date('Y-m-d', strtotime($get[1]));
        } else {
            $event_arr['from_date'] = date('Y-m-d', strtotime(date('Y-m-1')));
            $event_arr['to_date'] = date('Y-m-d');
        }
        $data['get'] = $event_arr;
        $this->template->load('default_front', 'front/reports/labor_and_services', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_labor_and_services_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->report_model->get_labor_and_services_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->report_model->get_labor_and_services_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['estimate_rate'] = number_format((float) $val['estimate_rate'], 2, '.', '');
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get all the data of get_labor_and_services_graph_data for graph
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_labor_and_services_graph_data() {
        $final['code'] = '404';
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $wh['service'] = ($this->input->get('service') && $this->input->get('service') != '') ? $this->input->get('service') : null;
        $items = $this->report_model->get_labor_and_services_graph_data($wh, 1);
        $maxitems = $this->report_model->get_labor_and_services_graph_data($wh);
        $currency = MY_Controller::$currency;
        if (!empty($items)):
            $final['code'] = '200';
            $final['services'] = array_column($items, 'name');
            $final['data'] = $items;
            $final['sum'] = array_sum(array_column($maxitems, 'value'));
            $final['cur'] = $currency['symbol'];
        endif;
        echo json_encode($final);
        die;
    }

    public function print_labor_and_services() {
        $wh['date'] = ($this->input->get('date') && $this->input->get('date') != '') ? $this->input->get('date') : null;
        $wh['service'] = ($this->input->get('service') && $this->input->get('service') != '') ? $this->input->get('service') : null;
        $pdf_data = $this->report_model->get_labor_and_services_data_pdf($wh)->result_array();
        if (!empty($pdf_data)):
            $data['format'] = MY_Controller::$date_format;
            $data['currency'] = MY_Controller::$currency;
            $data['dataArr'] = $pdf_data;
            $html = $this->load->view('front/reports/labor_and_services_pdf_view', $data, true);
            require_once FCPATH . 'Library/PDF/autoload.php';
            // require_once FCPATH . 'vendor/autoload.php';
            $mpdf = new Mpdf();
            $mpdf->SetTitle("Report of Labor and Services");
            $mpdf->defaultfooterline = 0;
            $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
            $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
            $filename = "labor_and_services_pdf_" . date('Ymdhis') . ".pdf";
            $mpdf->SetJS('this.print();');
            $mpdf->Output($filename, "I");
            $mpdf->Output("uploads/pdf/reports/labor_and_services/" . $filename, "F");
            $pdf = base_url() . 'uploads/pdf/reports/labor_and_services/' . $filename;
            return $pdf;
        endif;
    }

    /**
     * Display All low_inventory_items report 
     * @param --
     * @return --
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function low_inventory_items() {
        $data['title'] = 'Report for Low Inventory Items';
        $this->template->load('default_front', 'front/reports/low_inventory_items', $data);
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_low_inventory_items_data() {
        $format = MY_Controller::$date_format;
        $final['recordsTotal'] = $this->report_model->get_low_inventory_items_data('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $items = $this->report_model->get_low_inventory_items_data('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($items as $key => $val) {
            $items[$key] = $val;
            $items[$key]['sr_no'] = $start++;
            $items[$key]['created_date'] = date($format['format'], strtotime($val['created_date']) + $_COOKIE['currentOffset']);
            $items[$key]['responsive'] = '';
        }
        $final['data'] = $items;
        echo json_encode($final);
        die;
    }

    /**
     * Get all the data of items for displaying in ajax datatable
     * @param --
     * @return Object (Json Format)
     * @author HPA [Last Edited : 03/02/2018]
     */
    public function get_low_inventory_items_graph_data() {
        $final['code'] = '404';
        $items = $this->report_model->get_low_inventory_items_graph_data();
        if (!empty($items)):
            $final['code'] = '200';
            $final['parts'] = array_column($items, 'name');
            $final['data'] = $items;
            $final['max_value'] = (!empty($items)) ? max(array_column($items, 'value')) : 0;
        endif;
        echo json_encode($final);
        die;
    }

    public function print_low_inventory_item() {
        $qty = ($this->input->get('qty')) ? $this->input->get('qty') : null;
        $pdf_data = $this->report_model->get_low_inventory_items_data_pdf($qty);
        if (!empty($pdf_data)):
            $data['format'] = MY_Controller::$date_format;
            $data['currency'] = MY_Controller::$currency;
            $data['dataArr'] = $pdf_data;
            $html = $this->load->view('front/reports/low_inventory_items_pdf_view', $data, true);
            require_once FCPATH . 'Library/PDF/autoload.php';
            // require_once FCPATH . 'vendor/autoload.php';
            $mpdf = new Mpdf();
            $mpdf->SetTitle("Report of Low Inventory Items");
            $mpdf->defaultfooterline = 0;
            $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
            $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
            $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
            $filename = "low_inventory_items_pdf_" . date('Ymdhis') . ".pdf";
            $mpdf->SetJS('this.print();');
            $mpdf->Output($filename, "I");
            $mpdf->Output("uploads/pdf/reports/low_inventory_items/" . $filename, "F");
            $pdf = base_url() . 'uploads/pdf/reports/low_inventory_items/' . $filename;
            return $pdf;
        endif;
    }

}

/* End of file Inventory.php */
/* Location: ./application/controllers/Inventory.php */