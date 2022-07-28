<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Mpdf\Mpdf;

class Pdf extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('admin/estimate_model', 'admin/users_model', 'admin/dashboard_model', 'admin/inventory_model'));
        $this->load->library('m_pdf');
    }

    /**
     * View Estimates From Email Without Login
     * @param $id - String
     * @return --
     * @author HGA [Added : 22/12/2018]
     */
    public function index() {
        try {
            $id = base64_decode($this->uri->segment(3));
            $user_id = base64_decode($this->uri->segment(4));
            $pdf_type = base64_decode($this->uri->segment(5));

            if(!is_null($id) && !is_null($user_id) && !is_null($pdf_type)){
                $data['UserInfo'] = $this->users_model->get_profile($user_id);
                $data['format'] = $this->users_model->get_all_details(TBL_DATE_FORMAT, array('is_deleted' => 0, 'id' => $data['UserInfo']['date_format_id']))->row_array();
                $data['currency'] = $this->users_model->get_all_details(TBL_CURRENCY, array('id' => $data['UserInfo']['currency_id']))->row_array();
                $data['estimate'] = $this->estimate_model->get_estimate($id, $user_id);
                $data['taxes'] = $this->estimate_model->get_all_details(TBL_TAXES, array('is_deleted' => 0, 'business_user_id' => checkUserLogin('C')))->result_array();
    
                $filename = $data['estimate']['estimate_id'] . "_" . date('Ymdhis') . ".pdf";
    
                $data['company_info'] = $this->users_model->get_profile($user_id);

                $this->db->select('s.name');
                $this->db->where(array(
                    's.id' => $data['company_info']['state_id'],
                    'u.username' => $data['company_info']['username']
                ));
                $this->db->join(TBL_STATES. ' AS s','u.state_id = s.id', 'left');
                $data['company_info']['state_name']  = $this->db->get(TBL_USERS.' AS u')->row_array();

                if ($pdf_type == "estimate") {
                    $data['terms_condition'] = $this->estimate_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => $user_id))->row_array()['estimate_terms_condition'];
                    $html = $this->load->view('front/estimates/estimate_pdf_view', $data, true);
                } else if ($pdf_type == "invoice") {
                    $data['terms_condition'] = $this->estimate_model->get_all_details(TBL_USER_SETTINGS, array('is_deleted' => 0, 'business_user_id' => $user_id))->row_array()['invoice_terms_condition'];
                    $html = $this->load->view('front/invoices/invoice_pdf_view', $data, true);
                }
    
                if (isset($html) && !empty($html)) {
                    
                    // require_once FCPATH . 'vendor/autoload.php';
                    if ($_SERVER['HTTP_HOST'] == 'www.alwaysreliablekeys.com') {
                        require_once FCPATH . 'vendor/autoload.php';
                    } else if ($_SERVER['HTTP_HOST'] == 'clientapp.narola.online') {
                        require_once FCPATH . 'vendor/autoload.php';
                    } else {
                        require_once FCPATH . 'vendor 1/autoload.php';
                    }

                    // For Online Server(ClientApp)
                    // $mpdf = new Mpdf();
                    // Load Library 
                    // For Off Line (Localhost)

                    if ($_SERVER['HTTP_HOST'] == 'alwaysreliablekeys.com') {
                        $mpdf = new Mpdf();
                    } else if ($_SERVER['HTTP_HOST'] == 'clientapp.narola.online') {
                        $mpdf = new Mpdf();
                    } else {
                        $mpdf = new \mPDF();
                    }

                    // $mpdf = new \mPDF();
                    if ($pdf_type == "estimate") {
                        $mpdf->SetTitle("Estimate : " . $data['estimate']['estimate_id']);
                    } 
                    if ($pdf_type == "invoice") {
                        $mpdf->SetTitle("Invoice : " . $data['estimate']['estimate_id']);
                    }
                    $mpdf->defaultfooterline = 0;
                    $mpdf->setFooter('<div class = "footer-bg"><span>Page</span> {PAGENO}</div>');
                    $stylesheet = '<style>' . file_get_contents(base_url() . "assets/css/icons/icomoon/styles.css") . '</style>';
                    $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/bootstrap.css") . '</style>';
                    $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/core.css") . '</style>';
                    $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/components.css") . '</style>';
                    $stylesheet .= '<style>' . file_get_contents(base_url() . "assets/css/pdf.css") . '</style>';
                    $mpdf->AddPage('P', // L - landscape, P - portrait 
                            '', '', '', '', 0, // margin_left
                            1, // margin right
                            6, // margin top
                            6, // margin bottom
                            0, // margin header
                            0  // margin footer
                    );
                    $mpdf->WriteHTML($stylesheet, 1);
                    $mpdf->WriteHTML($html, 2);
                    return $mpdf->Output($filename, "I");
                } else {
                    $this->session->set_flashdata('error', 'Soemthing went wrong. Please open URL again.');
                    return redirect(base_url().'login');
                }   
            }else{
                $this->session->set_flashdata('error', 'Soemthing went wrong. Please open URL again.');
                return redirect(base_url().'login');
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', 'Soemthing went wrong. Please open URL again.');
            return redirect(base_url().'login');
        }
    }

}
