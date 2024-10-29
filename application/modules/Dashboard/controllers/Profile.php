<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'super', 'security'));
        $this->userinfo = userinfo();
        $this->bankinfo = bankinfo();
    }

    public function index()
    {
        if (is_logged_in()) {
            $response = array();
            $message = "profile";
            $response['header'] = 'MY PERSONAL INFORMATION';
            $response['form_open'] = form_open(base_url('dashboard/profile'));
            $nominee = $this->userinfo->nominee;
            $response['form'] = [
                'name' => form_label('Name', 'name') . form_input(array('type' => 'text', 'name' => 'name', 'id' => 'name', 'class' => 'form-control', 'value' => '' . $this->userinfo->name . '', 'placeholder' => 'Enter Name')),
                'email' => form_label('Email', 'email') . form_input(array('type' => 'email', 'name' => 'email', 'id' => 'email', 'class' => 'form-control', 'value' => '' . $this->userinfo->email . '', 'placeholder' => 'Enter Email')),
                'phone' => form_label('Phone', 'phone') . form_input(array('type' => 'number', 'name' => 'phone', 'id' => 'phone', 'class' => 'form-control', 'value' => '' . $this->userinfo->phone . '', 'placeholder' => 'Enter Phone')),
                'nominee' => form_label('Nominee', 'nominee') . form_dropdown('nominee', ['father'  => 'Father', 'mother'  => 'Mother','son'  => 'Son','daughter'  => 'Daughter','wife'  => 'Wife',],''.$nominee.'' , ['id' => 'nominee', 'class' => 'form-control']),
                'status' => form_label('Status', 'status') . form_input(array('type' => 'text', 'class' => 'form-control', 'value' => '' . ($this->userinfo->package_id > 0 ? 'Active' : 'Free') . '', 'readonly' => true)),
                'txn_password' => form_label('Transaction Password', 'txn_password') . form_input(array('type' => 'password', 'name' => 'txn_password', 'id' => 'txn_password', 'class' => 'form-control', 'placeholder' => 'Enter Transaction Password')),
            ];
            $response['form_button'] = [
                'submit' => form_submit('updateProfile', 'Update', ['class' => 'btn btn-info', 'id' => 'updateProfile', 'style' => 'display: block;'])
            ];
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
                $this->form_validation->set_rules('txn_password', 'Transaction Password', 'trim|required|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    if ($this->userinfo->master_key == $data['txn_password']) {
                        $Userdata['name'] = $data['name'];
                        $Userdata['email'] = $data['email'];
                        $Userdata['phone'] = $data['phone'];
                        $Userdata['nominee'] = $data['nominee'];
                        $check = update('tbl_users', array('user_id' => $this->session->userdata['user_id']), $Userdata);
                        if (!empty($check)) {
                            set_flashdata($message, span_success('Details Updated Successfully'));
                            redirect('dashboard/profile');
                        } else {
                            set_flashdata($message, span_danger('Please contact to the admin for more changes'));
                        }
                    } else {
                        set_flashdata($message, span_danger('Incorrect Transaction Password!'));
                    }
                } else {
                    set_flashdata($message, span_danger(validation_errors()));
                }
            }
            $response['message'] = $message;
            $this->load->view('forms', $response);
        } else {
            redirect('login');
        }
    }

    public function accountDetails()
    {
        if (is_logged_in()) {
            $response = array();
            $message = "account-details";
            $response['header'] = 'BANK ACCOUNT DETAILS';
            $response['form_open'] = form_open(base_url('dashboard/account-details'));
            $response['form'] = [
                'bank_name' => form_label('Bank Name', 'bank_name') . form_input(array('type' => 'text', 'name' => 'bank_name', 'id' => 'bank_name', 'class' => 'form-control', 'value' => '' . $this->bankinfo->bank_name . '', 'placeholder' => 'Enter Bank Name')),
                'account_holder_name' => form_label('Account Holder Name', 'account_holder_name') . form_input(array('type' => 'text', 'name' => 'account_holder_name', 'id' => 'account_holder_name', 'class' => 'form-control', 'value' => '' . $this->bankinfo->account_holder_name . '', 'placeholder' => 'Enter Account Holder Name')),
                'ifsc_code' => form_label('IFSC Code', 'ifsc_code') . form_input(array('type' => 'text', 'name' => 'ifsc_code', 'id' => 'ifsc_code', 'class' => 'form-control', 'value' => '' . $this->bankinfo->ifsc_code . '', 'placeholder' => 'Enter IFSC Code')),
                'branch_name' => form_label('Branch Address', 'branch_name') . form_input(array('type' => 'text', 'name' => 'branch_name', 'id' => 'branch_name', 'class' => 'form-control', 'value' => '' . $this->bankinfo->branch_name . '', 'placeholder' => 'Enter Branch Address')),
                'bank_account_number' => form_label('Bank Account Number', 'bank_account_number') . form_input(array('type' => 'text', 'name' => 'bank_account_number', 'id' => 'bank_account_number', 'class' => 'form-control', 'value' => '' . $this->bankinfo->bank_account_number . '', 'placeholder' => 'Enter Bank Account Number')),
                'confirm_account_number' => form_label('Confirm Bank Account Number', 'confirm_account_number') . form_input(array('type' => 'text', 'name' => 'confirm_account_number', 'id' => 'confirm_account_number', 'class' => 'form-control', 'placeholder' => 'Enter Confirm Bank Account Number')),
                'txn_password' => form_label('Transaction Password', 'txn_password') . form_input(array('type' => 'password', 'name' => 'txn_password', 'id' => 'txn_password', 'class' => 'form-control', 'placeholder' => 'Enter Transaction Password')),
                // 'kyc_status' => form_label('Status', 'kyc_status') . form_input(array('type' => 'text', 'class' => 'form-control', 'value' => '' . ($this->bankinfo->kyc_status == 2 ? 'Approved' : 'Pending') . '', 'readonly' => true)),
            ];
            if ($this->bankinfo->kyc_status != 2) {
                $response['form_button'] = [
                    'submit' => form_submit('updateAccount', 'Update', ['class' => 'btn btn-info', 'id' => 'updateAccount', 'style' => 'display: block;'])
                ];
            } else {
                $response['form_button'] = [
                    'button' => '<span class="badge bg-success">Approved</span>',
                ];
            }
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required|xss_clean');
                $this->form_validation->set_rules('account_holder_name', 'Bank Holder Name', 'trim|required|xss_clean');
                $this->form_validation->set_rules('bank_account_number', 'Bank Account Number', 'trim|required|xss_clean');
                $this->form_validation->set_rules('branch_name', 'Branch Address', 'trim|required|xss_clean');
                $this->form_validation->set_rules('confirm_account_number', 'Confirm Bank Account Number', 'trim|required|xss_clean');
                $this->form_validation->set_rules('ifsc_code', 'IFSC Code', 'trim|required|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    if ($this->userinfo->master_key == $data['txn_password']) {
                        if ($data['confirm_account_number'] == $data['bank_account_number']) {
                            if ($this->bankinfo->kyc_status != 2) {
                                $user_data['bank_name'] = $data['bank_name'];
                                $user_data['account_holder_name'] = $data['account_holder_name'];
                                $user_data['bank_account_number'] = $data['bank_account_number'];
                                $user_data['branch_name'] = $data['branch_name'];
                                $user_data['ifsc_code'] = $data['ifsc_code'];
                                $user_data['kyc_status'] = 1;

                                $updres = update('tbl_bank_details', array('user_id' => $this->session->userdata['user_id']), $user_data);
                                if ($updres) {
                                    set_flashdata($message, span_success('Account Details Updated Successfully'));
                                    redirect('dashboard/account-details');
                                } else {
                                    set_flashdata($message, span_danger('Verify There is an error while Updating Account Details Please Try Again'));
                                }
                            } else {
                                set_flashdata($message, span_info('KYC Already Approved'));
                            }
                        } else {
                            set_flashdata($message, span_danger('Bank Account Number Does not Match!'));
                        }
                    } else {
                        set_flashdata($message, span_danger('Incorrect Transaction Password!'));
                    }
                } else {
                    set_flashdata($message, span_danger(validation_errors()));
                }
            }
            $response['message'] = $message;
            $this->load->view('forms', $response);
        } else {
            redirect('login');
        }
    }

    public function transPassword()
    {
        if (is_logged_in()) {
            $response = array();
            $message = "transaction-password";
            $response['header'] = 'TRANSACTION PASSWORD MANAGEMENT';
            $response['form_open'] = form_open(base_url('dashboard/transaction-password'));
            $response['form'] = [
                'cpassword' => form_label('Current Transaction Password', 'cpassword') . form_input(array('type' => 'password', 'name' => 'cpassword', 'id' => 'cpassword', 'class' => 'form-control', 'placeholder' => 'Enter Current Transaction Password')),
                'npassword' => form_label('New Transaction Password', 'npassword') . form_input(array('type' => 'password', 'name' => 'npassword', 'id' => 'npassword', 'class' => 'form-control',  'placeholder' => 'Enter New Transaction Password')),
                'vpassword' => form_label('Verify Transaction Password', 'vpassword') . form_input(array('type' => 'password', 'name' => 'vpassword', 'id' => 'vpassword', 'class' => 'form-control',  'placeholder' => 'Enter Verify Transaction Password')),
            ];
            $response['form_button'] = [
                'submit' => form_submit('updateProfile', 'Update', ['class' => 'btn btn-info', 'id' => 'updateProfile', 'style' => 'display: block;'])
            ];
            if ($this->input->server('REQUEST_METHOD') == "POST") {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('cpassword', 'Current Transaction Password', 'trim|required|numeric');
                $this->form_validation->set_rules('npassword', 'New Transaction Password', 'trim|required|numeric');
                $this->form_validation->set_rules('vpassword', 'Verify Password', 'trim|required|numeric');
                if ($this->form_validation->run() === true) {
                    $cpassword = $data['cpassword'];
                    $npassword = $data['npassword'];
                    $vpassword = $data['vpassword'];
                    if ($npassword != $vpassword) {
                        set_flashdata($message, span_danger('Verify Transaction Password Does Not Match'));
                    } elseif ($cpassword != $this->userinfo->master_key) {
                        set_flashdata($message, span_danger('Wrong Current Transaction Password'));
                    } else {
                        $updres = update('tbl_users', array('user_id' => $this->session->userdata['user_id']), array('master_key' => $vpassword));
                        if ($updres == true) {
                            set_flashdata($message, span_success('Password Updated Successfully'));
                        } else {
                            set_flashdata($message, span_danger('There is an error while Changing Password Please Try Again!'));
                        }
                    }
                }
            }
            $response['message'] = $message;
            $this->load->view('forms', $response);
        } else {
            redirect('login');
        }
    }


    public function zilUpdate()
    {
        if (is_logged_in()) {
            $response = array();
            $message = "zil-update";
            $response['header'] = 'WALLET ADDRESS UPDATE';
            $response['form_open'] = form_open(base_url('dashboard/zil-update'));
            $response['form'] = [
                'ziladdress' => form_label('Wallet Address', 'ziladdress') . form_input(array('type' => 'text', 'name' => 'ziladdress', 'id' => 'ziladdress', 'value' => '' . $this->userinfo->eth_address . '', 'class' => 'form-control', 'placeholder' => 'Enter Wallet Address')),
            ];
            $response['form_button'] = [
                'submit' => form_submit('walletUpdate', 'Update', ['class' => 'btn btn-info', 'id' => 'walletUpdate', 'style' => 'display: block;'])
            ];
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('ziladdress', 'BEP20 Address', 'trim|required|xss_clean');
                // $this->form_validation->set_rules('otp', 'OTP', 'trim|required|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    // if ($data['otp'] == $_SESSION['verification_otp'] && !empty($_SESSION['verification_otp'])) {
                    $update = ['eth_address' => $data['ziladdress']];
                    $res = update('tbl_users', ['user_id' => $this->session->userdata['user_id']], $update);
                    if ($res) {
                        set_flashdata($message, span_success('Wallet Address Update Successfully'));
                        redirect('dashboard/zil-update');
                    } else {
                        set_flashdata($message, span_danger('error!'));
                    }
                    // } else {
                    // set_flashdata($message, span_danger('Please enter correct OTP!'));
                    // }
                }
            }
            $response['message'] = $message;
            $this->load->view('forms', $response);
        } else {
            redirect('login');
        }
    }
}
