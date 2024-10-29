<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Withdraw extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'pagination'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'security', 'super'));
        $this->userinfo = userinfo();
    }

    public function index()
    {
        if (is_logged_in()) {
            redirect('dashboard');
        } else {
            redirect('login');
        }
    }


    public function DirectIncomeWithdraw_Wallet()
    {
        //die('this page is accessable');
        //route('dashboard/DirectIncomeWithdraw'); ---------------------------->
        if (is_logged_in()) {
            $response['user'] = get_single_record('tbl_users', array('user_id' => $this->userinfo->user_id), '*');
            $response['tokenValue'] = get_single_record('tbl_token_value', ['id' => 1], 'amount');
            $response['admin'] = get_single_record('tbl_admin', ['id' => 1], '*');
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
                $this->form_validation->set_rules('master_key', 'Master Key', 'trim|required|xss_clean');
                $this->form_validation->set_rules('wallet_address', 'Wallet Address', 'trim|required|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    $todayWithdraw = get_single_record('tbl_withdraw', array('user_id' => $this->userinfo->user_id, 'date(created_at)' => date('Y-m-d')), '*');
                    $withdraw_amount = abs($data['amount']);
                    $master_key = $data['master_key'];
                    $balance = get_single_record('tbl_income_wallet', ' user_id = "' . $this->userinfo->user_id . '"', 'ifnull(sum(amount),0) as balance');
                    if (empty($todayWithdraw)) {
                        if ($withdraw_amount >= min_withdraw && $withdraw_amount <= max_withdraw) {
                            if ($balance['balance'] >= $withdraw_amount) {
                                if ($this->userinfo->master_key == $master_key) {
                                    $DirectIncome = array(
                                        'user_id' => $this->userinfo->user_id,
                                        'amount' => -$withdraw_amount,
                                        'type' => 'withdraw_request',
                                        'description' => 'Withdrawal Amount ',
                                        'dollar' => $withdraw_amount,
                                        'token_price' => $response['tokenValue']['amount'],
                                    );
                                    add('tbl_income_wallet', $DirectIncome);
                                    $withdrawArr = array(
                                        'user_id' => $this->userinfo->user_id,
                                        'amount' => $withdraw_amount,
                                        'type' => 'withdraw_request',
                                        'tds' => $withdraw_amount * withdraw_charges / 100,
                                        'admin_charges' => $withdraw_amount * withdraw_charges / 100,
                                        'fund_conversion' => ($withdraw_amount - ($withdraw_amount * 0 / 100)),
                                        'zil_address' => $response['user']['eth_address'],
                                        'payable_amount' => ($withdraw_amount - ($withdraw_amount * withdraw_charges / 100)),
                                        'coin' => 0,
                                        'credit_type' => 'Wallet',
                                    );
                                    add('tbl_withdraw', $withdrawArr);
                                    set_flashdata('withdraw_wallet_message', span_success_simple('Withdraw Requested Successfully'));
                                } else {
                                    set_flashdata('withdraw_wallet_message', span_danger('Invalid Master Key'));
                                }
                            } else {
                                set_flashdata('withdraw_wallet_message', span_info('Insuffcient Balance'));
                            }
                        } else {
                            set_flashdata('withdraw_wallet_message', span_danger('Minimum Withdrawal Amount is ' . currency . min_withdraw . '   and Maximum  ' . currency . max_withdraw));
                        }
                    } else {
                        set_flashdata('withdraw_wallet_message', span_info('Once a withdraw in a day,Please try next sunday'));
                    }
                }
            }
            $response['balance'] = get_single_record('tbl_income_wallet', ' user_id = "' . $this->userinfo->user_id . '"', 'ifnull(sum(amount),0) as balance');
            $this->load->view('withdraw_wallet', $response);
        } else {
            redirect('Dashboard/User/login');
        }
    }

    public function DirectIncomeWithdraw_Bank()
    {
        //die('this page is accessable');
        //route('dashboard/DirectIncomeWithdraw'); ---------------------------->
        if (is_logged_in()) {
            $response['tokenValue'] = get_single_record('tbl_token_value', ['id' => 1], 'amount');
            $response['admin'] = get_single_record('tbl_admin', ['id' => 1], '*');
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
                $this->form_validation->set_rules('master_key', 'Master Key', 'trim|required|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    $leftDirect = get_single_record('tbl_users', array('sponser_id' => $this->userinfo->user_id, 'position' => 'L', 'paid_status' => 1), 'ifnull(count(id),0) as leftDirect');
                    $rightDirect = get_single_record('tbl_users', array('sponser_id' => $this->userinfo->user_id, 'position' => 'R', 'paid_status' => 1), 'ifnull(count(id),0) as rightDirect');
                    $todayWithdraw = get_single_record('tbl_withdraw', array('user_id' => $this->userinfo->user_id, 'date(created_at)' => date('Y-m-d')), '*');
                    $withdraw_amount = abs($data['amount']);
                    $master_key = $data['master_key'];
                    $max_withdraw = $this->userinfo->package_amount * 0.5;
                    $balance = get_single_record('tbl_income_wallet', ' user_id = "' . $this->userinfo->user_id . '"', 'ifnull(sum(amount),0) as balance');
                    if (empty($todayWithdraw)) {
                        if ($rightDirect['rightDirect'] >= 1 && $leftDirect['leftDirect'] >= 1) {
                            if ($withdraw_amount >= min_withdraw && $withdraw_amount <= $max_withdraw) {
                                if ($withdraw_amount % multiple_withdraw == 0) {
                                    if ($balance['balance'] >= $withdraw_amount) {
                                        if ($this->userinfo->master_key == $master_key) {
                                            $DirectIncome = array(
                                                'user_id' => $this->userinfo->user_id,
                                                'amount' => -$withdraw_amount,
                                                'type' => 'withdraw_request',
                                                'description' => 'Withdrawal Amount ',
                                            );
                                            add('tbl_income_wallet', $DirectIncome);
                                            $withdrawArr = array(
                                                'user_id' => $this->userinfo->user_id,
                                                'amount' => $withdraw_amount,
                                                'type' => 'withdraw_request',
                                                'tds' => $withdraw_amount * withdraw_charges / 100,
                                                'admin_charges' => $withdraw_amount * withdraw_charges / 100,
                                                'fund_conversion' => ($withdraw_amount - ($withdraw_amount * 0 / 100)),
                                                'payable_amount' => ($withdraw_amount - ($withdraw_amount * withdraw_charges / 100)),
                                                'coin' => 0,
                                                'credit_type' => 'Bank',
                                            );
                                            add('tbl_withdraw', $withdrawArr);
                                            set_flashdata('withdraw_bank_message', span_success('Withdraw Requested Successfully'));
                                        } else {
                                            set_flashdata('withdraw_bank_message', span_danger('Invalid Master Key'));
                                        }
                                    } else {
                                        set_flashdata('withdraw_bank_message', span_info('Insuffcient Balance'));
                                    }
                                } else {
                                    set_flashdata('withdraw_bank_message', span_info('Multiple Withdraw Amount is ' . multiple_withdraw));
                                }
                            } else {
                                set_flashdata('withdraw_bank_message', span_danger('Minimum Withdrawal Amount is ' . currency . min_withdraw . '   and Maximum  ' . currency . $max_withdraw));
                            }
                        } else {
                            set_flashdata('withdraw_bank_message', span_info('1 Left & 1 Right Direct are required for Withdrawal !'));
                        }
                    } else {
                        set_flashdata('withdraw_bank_message', span_info('Once a withdraw in a day,Please try next day'));
                    }
                }
            }
            $response['balance'] = get_single_record('tbl_income_wallet', ' user_id = "' . $this->userinfo->user_id . '"', 'ifnull(sum(amount),0) as balance');
            $this->load->view('withdraw_bank', $response);
        } else {
            redirect('Dashboard/User/login');
        }
    }

    public function withdrawHistory()
    {
        if (is_logged_in()) {
            $response['header'] = 'Withdraw Summary';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = ['user_id' => $this->userinfo->user_id];
            if (!empty($type)) {
                $where = [$type => $value, 'user_id' => $this->userinfo->user_id];
            }
            $records = pagination('tbl_withdraw', $where, '*', 'dashboard/withdraw-history', 3, 10);

            $response['path'] =  $records['path'];

            $response['field'] =  '';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Amount</th>
                                <th>Payable Amount</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Remark</th>
                                <th>Date</th>
                                
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;

            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $amount . '</td>
                                <td>' . $payable_amount . '</td>
                                <td>' . ($status == 0 ? badge_warning('Pending') : ($status == 1 ? badge_success('Approved') : ($status == 2 ? badge_danger('Rejected') : badge_info('Withdraw')))) . '</td>
                                <td>' . ucwords(str_replace('_', ' ', $type)) . '</td>
                                <td>' . $remark . '</td>
                                <td>' . $created_at . '</td>
                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $this->load->view('reports', $response);
        } else {
            redirect('login');
        }
    }
}
