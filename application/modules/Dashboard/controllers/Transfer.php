<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Transfer extends CI_Controller
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


    public function IncomeTransfer()
    {
        if (is_logged_in()) {
            $response = [];
            $message = 'income-transfer';
            $response['extra_header'] = true;
            $response['script'] = true;
            $response['header'] = 'Income Transfer';
            $response['header2'] = 'Income Balance:- ' . currency . '';
            $response['form_open'] = form_open(base_url('dashboard/income-transfer'));
            $response['form'] = [
                'user_id' => form_label('User ID', 'user_id') . form_input(array('type' => 'text', 'name' => 'user_id', 'id' => 'user_id', 'value' => set_value('user_id'), 'class' => 'form-control', 'placeholder' => 'Enter User ID')),
                'errorMessage' => '<span class="text-danger" id="errorMessage"></span>',
                'amount' => form_label('Amount', 'amount') . form_input(array('type' => 'number', 'name' => 'amount', 'id' => 'amount', 'class' => 'form-control', 'placeholder' => 'Enter Amount')),
            ];
            $response['form_button'] = [
                'submit' => form_submit('IncomeTransfer', 'Transfer', ['class' => 'btn btn-info', 'id' => 'IncomeTransfer', 'style' => 'display: block;'])
            ];
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    $withdraw_amount = $this->input->post('amount');
                    $user_id = $this->input->post('user_id');
                    $Checkuser = get_single_record('tbl_users', array('user_id' => $user_id), 'user_id');
                    $balance = get_single_record('tbl_income_wallet', ' user_id = "' . $this->session->userdata['user_id'] . '"', 'ifnull(sum(amount),0) as balance');
                    $minimum_amount = 1;
                    if ($withdraw_amount >= $minimum_amount) {
                        if ($balance['balance'] >= $withdraw_amount) {
                            if (!empty($Checkuser['user_id'])) {
                                if ($data['user_id'] != $this->session->userdata['user_id']) {
                                    // if($user['master_key'] == $master_key){
                                    $DebitIncome = array(
                                        'user_id' => $this->session->userdata['user_id'],
                                        'amount' => -$withdraw_amount,
                                        'type' => 'income_transfer',
                                        'description' => 'Sent ' . $withdraw_amount . ' to ' . $user_id,
                                    );
                                    add('tbl_income_wallet', $DebitIncome);
                                    $CrdeitIncome = array(
                                        'user_id' => $user_id,
                                        'amount' => $withdraw_amount * 95 / 100,
                                        'type' => 'income_transfer',
                                        'description' => 'Got ' . $withdraw_amount . ' from ' . $this->session->userdata['user_id'],
                                    );
                                    add('tbl_income_wallet', $CrdeitIncome);
                                    set_flashdata($message, span_success('Income Transferred Successfully'));
                                } else {
                                    set_flashdata($message, span_info('Cannot Transfer as same amount'));
                                }
                            } else {
                                set_flashdata($message, span_danger('Invalid User'));
                            }
                        } else {
                            set_flashdata($message, span_danger('Insuffcient Balance'));
                        }
                    } else {
                        set_flashdata($message, span_info('Minimum Transfer Amount is ' . currency . $minimum_amount . ''));
                    }
                }
            }
            $response['message'] = $message;
            $response['balance'] = get_single_record('tbl_income_wallet', ' user_id = "' . $this->session->userdata['user_id'] . '"', 'ifnull(sum(amount),0) as balance');
            $this->load->view('forms', $response);
        } else {
        }
    }

    public function incomeToeWalletTransfer()
    {
        // die;
        if (is_logged_in()) {
            $response = [];
            $message = "wallet-transfer";
            $response['extra_header'] = true;
            $response['script'] = true;
            $response['header'] = 'Income Transfer to E-Wallet';
            $response['header2'] = 'Income Balance:-';
            $response['form_open'] = form_open(base_url('dashboard/income_wallet-transfer'));
            $response['form'] = [
                'user_id' => form_label('User ID', 'user_id') . form_input(array('type' => 'text', 'name' => 'user_id', 'id' => 'user_id', 'value' => $this->userinfo->user_id, 'readonly' => true, 'class' => 'form-control', 'placeholder' => 'Enter User ID')),
                'errorMessage' => '<span class="text-danger" id="errorMessage"></span>',
                'amount' => form_label('Amount', 'amount') . form_input(array('type' => 'number', 'name' => 'amount', 'id' => 'amount', 'class' => 'form-control', 'placeholder' => 'Enter Amount')),
                'erroraaa' =>  '<h6 class="text-success" id="payableAmount"></h6>',

            ];
            $response['form_button'] = [
                'submit' => form_submit('wallet-transfer', 'Transfer', ['class' => 'btn btn-info', 'id' => 'wallet-transfer', 'style' => 'display: block;'])
            ];
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    $withdraw_amount = $this->input->post('amount');
                    $user_id = $this->input->post('user_id');
                    $Checkuser = get_single_record('tbl_users', array('user_id' => $data['user_id']), 'user_id');
                    $balance = get_single_record('tbl_income_wallet', ' user_id = "' . $this->session->userdata['user_id'] . '"', 'ifnull(sum(amount),0) as balance');
                    $todayTransfer = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id'], 'date(created_at)' => date('Y-m-d'), 'type' => 'income_wallet_transfer'), '*');

                    $minimum_amount = 300;
                    $maximum_amount = 10000;
                    $charges = 0.90;
                    if (empty($todayTransfer)) {
                        if ($this->userinfo->directs >= 1) {
                            if ($withdraw_amount >= $minimum_amount && $withdraw_amount <= $maximum_amount) {
                                if ($withdraw_amount % $minimum_amount == 0) {
                                    if (!empty($Checkuser['user_id'])) {
                                        if ($balance['balance'] >= $withdraw_amount) {
                                            $DebitIncome = array(
                                                'user_id' => $this->session->userdata['user_id'],
                                                'amount' => -$withdraw_amount,
                                                'type' => 'income_wallet_transfer',
                                                'description' => 'Sent ' . $withdraw_amount . ' to ' . $user_id,
                                            );
                                            add('tbl_income_wallet', $DebitIncome);

                                            $CrdeitIncome = array(
                                                'user_id' => $user_id,
                                                'amount' => ($withdraw_amount * $charges),
                                                'type' => 'income_wallet_transfer',
                                                'remark' => 'Got ' . ($withdraw_amount * $charges) . ' from ' . $this->session->userdata['user_id'],
                                            );
                                            add('tbl_wallet', $CrdeitIncome);
                                            set_flashdata($message, span_success('Income Transfer Successfully'));
                                        } else {
                                            set_flashdata($message, span_danger('Insuffcient Balance'));
                                        }
                                    } else {
                                        set_flashdata($message, span_danger('Invalid User'));
                                    }
                                } else {
                                    set_flashdata($message, span_info('Multiple Transfer Amount is ' . $minimum_amount));
                                }
                            } else {
                                set_flashdata($message, span_danger('Minimum Transfer Amount is ' . currency . $minimum_amount . '   and Maximum  ' . currency . $maximum_amount));
                            }
                        } else {
                            set_flashdata($message, span_info('1 Direct required for Transfer !'));
                        }
                    } else {
                        set_flashdata($message, span_info('Income to E-Wallet Transfer Once in a Day Only !'));
                    }
                }
            }
            $response['message'] = $message;
            $response['balance'] = get_single_record('tbl_income_wallet', ' user_id = "' . $this->session->userdata['user_id'] . '"', 'ifnull(sum(amount),0) as balance');
            $this->load->view('forms', $response);
        } else {
            redirect('login');
        }
    }


    public function eWalletTransfer()
    {
        if (is_logged_in()) {
            $response = array();
            $message = 'e_wallet-transfer';
            $response['extra_header'] = true;
            $response['script'] = true;
            $response['header'] = 'TRANSFER WALLET';
            $response['header2'] = 'Wallet Balance:- ' . currency . '';
            $response['form_open'] = form_open(base_url('dashboard/wallet-transfer'));
            $response['form'] = [
                'user_id' => form_label('User ID', 'user_id') . form_input(array('type' => 'text', 'name' => 'user_id', 'id' => 'user_id', 'class' => 'form-control', 'placeholder' => 'Enter User ID')),
                'error' =>  '<span class="text-danger" id="errorMessage"></span>',
                'amount' => form_label('Amount', 'amount') . form_input(array('type' => 'number', 'name' => 'amount', 'id' => 'amount', 'class' => 'form-control',  'placeholder' => 'Enter Amount')),
                'erroraaa' =>  '<h6 class="text-success" id="payableAmount"></h6>',
                // 'txn_password' => form_label('Transaction Password', 'txn_password') . form_input(array('type' => 'password', 'name' => 'txn_password', 'id' => 'txn_password', 'class' => 'form-control',  'placeholder' => 'Enter Transaction Password')),
            ];
            $response['form_button'] = [
                'submit' => form_submit('ewalletTransfer', 'Transfer', ['class' => 'btn btn-info', 'id' => 'ewalletTransfer', 'style' => 'display: block;'])
            ];
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                // $this->form_validation->set_rules('txn_password', 'Transaction Password', 'trim|required');
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
                if ($this->form_validation->run() != FALSE) {
                    $user = get_single_record('tbl_users', array('user_id' => $this->session->userdata['user_id']), 'directs');
                    $balance = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as balance');
                    $minimum_amount = 100;
                    $charges = 0.90;
                    if ($user['directs'] >= 1) {
                        if ($data['amount'] > $minimum_amount) {
                            if ($data['amount'] % 100 == 0) {
                                if ($data['user_id'] != $this->session->userdata['user_id']) {
                                    // if ($user['master_key'] == $data['txn_password']) {
                                    $receiver = get_single_record('tbl_users', array('user_id' => $data['user_id']), '*');
                                    if (!empty($receiver)) {
                                        if ($balance['balance'] >= $data['amount']) {
                                            $senderData = array(
                                                'user_id' => $this->session->userdata['user_id'],
                                                'amount' => -$data['amount'],
                                                'sender_id' => $data['user_id'],
                                                'type' => 'fund_transfer',
                                                'remark' => 'Fund Transfer To ' . $data['user_id'],
                                            );
                                            add('tbl_wallet', $senderData);

                                            $receiverData = array(
                                                'user_id' => $data['user_id'],
                                                'amount' => ($data['amount'] * $charges),
                                                'sender_id' => $this->session->userdata['user_id'],
                                                'type' => 'fund_transfer',
                                                'remark' => 'Fund Transfer From ' . $this->session->userdata['user_id'],
                                            );
                                            add('tbl_wallet', $receiverData);
                                            set_flashdata($message, span_success('Fund Transferred Successfully'));
                                        } else {
                                            set_flashdata($message, span_danger('Insufficient Balance'));
                                        }
                                    } else {
                                        set_flashdata($message, span_danger('Invalid User'));
                                    }
                                    // } else {
                                    //     set_flashdata($message, span_danger('Incorrect Transaction Password'));
                                    // }
                                } else {
                                    set_flashdata($message, span_info('You Cannot Transfer Amount In Same Account'));
                                }
                            } else {
                                set_flashdata($message, span_info('Multiple Transfer Amount is ' . currency . 100));
                            }
                        } else {
                            set_flashdata($message, span_info('Minimum Transfer Amount is ' . currency . $minimum_amount . ''));
                        }
                    } else {
                        set_flashdata($message, span_info('1 Direct required for Transfer !'));
                    }
                }
            }
            $response['message'] = $message;
            // $response['script'] = true;
            $response['balance'] = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as balance');
            $this->load->view('forms', $response);
        } else {
            redirect('login');
        }
    }
    // public function eWalletTransfer()
    // {
    //     if (is_logged_in()) {
    //         $response = array();
    //         $message = 'e_wallet-transfer';
    //         $response['extra_header'] = true;
    //         $response['script'] = true;
    //         $response['header'] = 'TRANSFER WALLET';
    //         $response['header2'] = 'Wallet Balance:- ' . currency . '';
    //         $response['form_open'] = form_open(base_url('dashboard/wallet-transfer'));
    //         $response['form'] = [
    //             'user_id' => form_label('User ID', 'user_id') . form_input(array('type' => 'text', 'name' => 'user_id', 'id' => 'user_id', 'class' => 'form-control', 'placeholder' => 'Enter User ID')),
    //             'error' =>  '<span class="text-danger" id="errorMessage"></span>',
    //             'amount' => form_label('Amount', 'amount') . form_input(array('type' => 'number', 'name' => 'amount', 'id' => 'amount', 'class' => 'form-control',  'placeholder' => 'Enter Amount')),
    //             // 'txn_password' => form_label('Transaction Password', 'txn_password') . form_input(array('type' => 'password', 'name' => 'txn_password', 'id' => 'txn_password', 'class' => 'form-control',  'placeholder' => 'Enter Transaction Password')),
    //         ];
    //         $response['form_button'] = [
    //             'submit' => form_submit('ewalletTransfer', 'Transfer', ['class' => 'btn btn-info', 'id' => 'ewalletTransfer', 'style' => 'display: block;'])
    //         ];
    //         if ($this->input->server('REQUEST_METHOD') == 'POST') {
    //             $data = $this->security->xss_clean($this->input->post());
    //             $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
    //             // $this->form_validation->set_rules('txn_password', 'Transaction Password', 'trim|required');
    //             $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
    //             if ($this->form_validation->run() != FALSE) {
    //                 // $user = get_single_record('tbl_users', array('user_id' => $this->session->userdata['user_id']), 'master_key');
    //                 $balance = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as balance');
    //                 $minimum_amount = 1;
    //                 if ($data['amount'] > $minimum_amount) {
    //                     if ($data['user_id'] != $this->session->userdata['user_id']) {
    //                         // if ($user['master_key'] == $data['txn_password']) {
    //                         $receiver = get_single_record('tbl_users', array('user_id' => $data['user_id']), '*');
    //                         if (!empty($receiver)) {
    //                             if ($balance['balance'] >= $data['amount']) {
    //                                 $senderData = array(
    //                                     'user_id' => $this->session->userdata['user_id'],
    //                                     'amount' => -$data['amount'],
    //                                     'sender_id' => $data['user_id'],
    //                                     'type' => 'fund_transfer',
    //                                     'remark' => 'Fund Transfer To ' . $data['user_id'],
    //                                 );
    //                                 add('tbl_wallet', $senderData);

    //                                 $receiverData = array(
    //                                     'user_id' => $data['user_id'],
    //                                     'amount' => $data['amount'] * 0.90,
    //                                     'sender_id' => $this->session->userdata['user_id'],
    //                                     'type' => 'fund_transfer',
    //                                     'remark' => 'Fund Received From ' . $this->session->userdata['user_id'],
    //                                 );
    //                                 add('tbl_wallet', $receiverData);
    //                                 set_flashdata($message, span_success('Fund Transferred Successfully'));
    //                             } else {
    //                                 set_flashdata($message, span_danger('Insufficient Balance'));
    //                             }
    //                         } else {
    //                             set_flashdata($message, span_danger('Invalid User'));
    //                         }
    //                         // } else {
    //                         //     set_flashdata($message, span_danger('Incorrect Transaction Password'));
    //                         // }
    //                     } else {
    //                         set_flashdata($message, span_info('You Cannot Transfer Amount In Same Account'));
    //                     }
    //                 } else {
    //                     set_flashdata($message, span_info('Minimum Transfer Amount is ' . currency . $minimum_amount . ''));
    //                 }
    //             }
    //         }
    //         $response['message'] = $message;
    //         $response['balance'] = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as balance');
    //         $this->load->view('forms', $response);
    //     } else {
    //         redirect('login');
    //     }
    // }
}
