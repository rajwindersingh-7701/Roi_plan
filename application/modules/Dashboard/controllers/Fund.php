<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Fund extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'pagination'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'super', 'compose'));
        $this->userinfo = userinfo();
        $this->bankinfo = bankinfo();
    }

    private function wallet_generate()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://50.116.10.111:3000/generate_bep_address',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function Request_fund()
    {
        if (is_logged_in()) {
            $response = array();
            $response['user'] = get_single_record('tbl_users', array('user_id' => $this->session->userdata['user_id']), '*');
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $check = get_single_record('tbl_payment_request', array('transaction_id' => $data['txn_id']), '*');
                if (empty($check) && !empty($data['txn_id'])) {
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|heic';
                    $config['file_name'] = 'payment_slip' . time();
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('image')) {
                        set_flashdata('message', $this->upload->display_errors());
                    } else {
                        $fileData = array('upload_data' => $this->upload->data());
                        $reqArr = array(
                            'user_id' => $this->session->userdata['user_id'],
                            'amount' => $data['amount'],
                            'payment_method' => $data['payment_method'],
                            'transaction_id' => $data['txn_id'],
                            'image' => $fileData['upload_data']['file_name'],
                            'type' => 'fund_request',
                            'status' => 0,
                        );
                        $res = add('tbl_payment_request', $reqArr);
                        if (fund_mail == 0 && compose_mail == 0) {
                            $message = "Dear Team ,<br></br> I Informed you that I Requested for fund amount is Rs." . $data['amount'] . " Please Update fund in my wallet from User ID:" . $this->session->userdata['user_id'] . ',' . base_url();
                            composeMail(email, 'Fund Request', $message);
                        }
                        if ($res) {
                            set_flashdata('message', span_success('Payment Request Submitted Successfully'));
                        } else {
                            set_flashdata('message', span_danger('Error While Submitting Payment Request Please Try Again ...'));
                        }
                    }
                } else {
                    set_flashdata('message', span_info('Error please enter vaild Hash ID.'));
                }
            }
            $response['heeader'] = 'Request Fund';
            $response['qrcode'] = get_single_record('tbl_qrcode', array('id' => 1), '*');
            $this->load->view('request_fund', $response);
        } else {
            redirect('login');
        }
    }

    public function testMial()
    {
        die;
        $message = "Dear Team ,<br></br> I Informed you that I Requested for fund amount is Rs.100 Please Update fund in my wallet from User ID:" . $this->session->userdata['user_id'] . ',' . base_url();
        composeMail('gnirv18@gmail.com', 'Fund Request', $message);
    }

    public function Deposit_fund()
    {
        if (is_logged_in()) {
            $response = array();
            $response['user'] = get_single_record('tbl_users', array('user_id' => $this->session->userdata['user_id']), '*');
            if (empty($response['user']['wallet_address']) && empty($response['user']['private_key'])) {
                $walletGenerate = $this->wallet_generate();
                $json_wallet = json_decode($walletGenerate, true);
                // pr($json_wallet,true);
                $update['wallet_address'] = $json_wallet['account']['address'];
                $update['wallet_private'] = $json_wallet['account']['private_key'];
                update('tbl_users', ['user_id' => $this->session->userdata['user_id']], $update);
                redirect('dashboard/fund-request');
            }
            $response['heeader'] = 'Deposit Fund';
            $this->load->view('deposit_fund', $response);
        } else {
            redirect('login');
        }
    }



    public function fundHistory()
    {
        if (is_logged_in()) {
            $response['header'] = 'wallet Ledger';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = ['user_id' => $this->session->userdata['user_id']];
            if (!empty($type)) {
                $where = [$type => $value, 'user_id' => $this->session->userdata['user_id']];
            }
            $records = pagination('tbl_wallet', $where, '*', 'dashboard/fund-history', 3, 10);
            $response['path'] =  $records['path'];

            $response['field'] = '';
            $response['thead'] = '<tr>
                            <th>#</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Remark</th>
                            <th>Created At</th>
                         </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $tbody[$key]  = ' <tr>
                            <td>' . $i . '</td>
                            <td>' . $amount . '</td>
                            <td>' . ($amount > 0 ? badge_success('Credit') : badge_danger('Debit')) . '</td>
                            <td>' . ucwords(str_replace('_', ' ', $type)) . '</td>
                            <td>' . $remark . '</td>
                            <td>' . $created_at . '</td>
                         </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $response['balance'] = true;
            $response['total_income'] = get_sum('tbl_wallet', $where, 'ifnull(sum(amount),0) as sum');
            $this->load->view('reports', $response);
        } else {
            redirect('login');
        }
    }

    public function fundRequestHistory()
    {
        if (is_logged_in()) {
            $response['header'] = 'Fund Request History';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = ['user_id' => $this->session->userdata['user_id']];
            if (!empty($type)) {
                $where = [$type => $value, 'user_id' => $this->session->userdata['user_id']];
            }
            $records = pagination('tbl_payment_request', $where, '*', 'dashboard/fundrequest-history', 3, 10);
            $response['path'] =  $records['path'];

            $response['field'] = '';
            $response['thead'] = '<tr>
                            <th>#</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Remarks</th>
                            <th>Created At</th>
                            <th>Status</th>
                         </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $tbody[$key]  = ' <tr>
                            <td>' . $i . '</td>
                            <td>' . $amount . '</td>
                            <td>' . ucwords(str_replace('_', ' ', $type)) . '</td>
                            <td>' . $remarks . '</td>
                            <td>' . $created_at . '</td>
                            <td>' . ($status == 0 ? badge_warning('Pending') : ($status == 1 ? badge_success('Approved') : ($status == 2 ? badge_danger('Rejected') : badge_info('Fund')))) . '</td>
                         </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $response['balance'] = true;
            $this->load->view('reports', $response);
        } else {
            redirect('login');
        }
    }
}
