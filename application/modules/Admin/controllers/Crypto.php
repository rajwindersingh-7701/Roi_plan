<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Crypto extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email','pagination'));
        $this->load->model(array('Main_model'));
        $this->load->helper(array('admin', 'security'));
    }

    public function index() {
        if (is_admin()) {
            $field = $this->input->get('type');
            $value = $this->input->get('value');
            $where = array($field => $value);
            if (empty($where[$field])){
                $where = array();
                // $config['base_url'] = base_url() . 'Admin/Withdraw/index/';
            }
            // else{
                // $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
            // }
            
            $config['base_url'] = base_url() . 'Admin/Crypto/index';
            $config['suffix'] = '?'.http_build_query($_GET);
            $config['total_rows'] = $this->Main_model->get_sum('tbl_block_address', $where, 'ifnull(count(id),0) as sum');
            $config ['uri_segment'] = 4;
            $config['per_page'] = 1000;
            $config['attributes'] = array('class' => 'page-link');
            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = '</ul>';
            $config['num_tag_open'] = '<li class="paginate_button page-item ">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="paginate_button page-item  active"><a href="#" class="page-link">';
            $config['cur_tag_close'] = '</a></li>';
            $config['prev_tag_open'] = '<li class="paginate_button page-item ">';
            $config['prev_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li class="paginate_button page-item">';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li class="paginate_button page-item next">';
            $config['last_tag_close'] = '</li>';
            $config['prev_link'] = 'Previous';
            $config['prev_tag_open'] = '<li class="paginate_button page-item previous">';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = 'Next';
            $config['next_tag_open'] = '<li  class="paginate_button page-item next">';
            $config['next_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            $segment = $this->uri->segment(4);
            $response['segament'] = $segment;
            $response['type'] = $field;
            $response['value'] = $value;
            $response['total_records'] = $config['total_rows'];
            $response['transactions'] = $this->Main_model->get_limit_records('tbl_block_address', $where, '*', $config['per_page'], $segment);
            $this->load->view('crypto_transaction', $response);
        } else {
            redirect('admin/login');
        }
    }

     public function Transaction($transaction_id){
        if (is_admin()) {
            $response['transaction'] = $this->Main_model->get_single_record('tbl_block_address', array('hash' => $transaction_id), '*');
            // if ($this->input->server('REQUEST_METHOD') == 'POST') {
            //     $data = $this->security->xss_clean($this->input->post());
            //     if ($response['request']['status'] != 0) {
            //         $this->session->set_flashdata('message', 'Status of this request already updated!');
            //     } else {
            //         if ($data['status'] == 1) {
            //             $wArr = array(
            //                 'status' => 1,
            //                 'remark' => $data['remark'],
            //             );
            //             $res = $this->Main_model->update('tbl_withdraw', array('id' => $id), $wArr);
            //             if ($res) {
            //                 $user = $this->Main_model->get_single_record('tbl_users', array('user_id' => $response['request']['user_id']), 'user_id,area_code');
            //                 $investMentArr = array(
            //                     'user_id' => $response['request']['user_id'],
            //                     'amount' => $response['request']['amount'],
            //                     'mode' => 'get',
            //                     'area_code' => $user['area_code'],
            //                 );
            //                 $this->Main_model->add('tbl_investment', $investMentArr);
            //                 $this->session->set_flashdata('message', 'Withdraw request approved');
            //             } else {
            //                 $this->session->set_flashdata('message', 'Error while Rejecting WithdraW');
            //             }
            //         } elseif ($data['status'] == 2) {
            //             $wArr = array(
            //                 'status' => 2,
            //                 'remark' => $data['remark'],
            //             );
            //             $res = $this->Main_model->update('tbl_withdraw', array('id' => $id), $wArr);
            //             if ($res) {
            //                 $productArr = array(
            //                     'user_id' => $response['request']['user_id'],
            //                     'amount' => $response['request']['amount'],
            //                     'type' => $response['request']['type'],
            //                     'description' => 'Working Withdraw Refund',
            //                 );
            //                 $this->Main_model->add('tbl_income_wallet', $productArr);
            //                 $this->session->set_flashdata('message', 'Withdraw request rejected');
            //             } else {
            //                 $this->session->set_flashdata('message', 'Error while Rejecting WithdraW');
            //             }
            //         }
            //     }
            // }
            $response['user_details'] = $this->Main_model->get_single_record('tbl_users', array('user_id' => $response['transaction']['user_id']), 'id,name,first_name,last_name,sponser_id,email,phone');
            $this->load->view('crypto_transactions', $response);
        } else {
            redirect('admin/login');
        }
    }

}    