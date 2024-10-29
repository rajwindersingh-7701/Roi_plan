<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'Binance'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'super', 'security', 'email', 'compose')); 
    }

    public function signin()
    {
        if (is_logged_in()) {
            redirect('dashboard');
        }
        $response['message'] = '';
        $response['status'] = '0';
        if ($this->input->is_ajax_request()) {
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $user = get_single_record('tbl_users', array('user_id' => $data['user_id'], 'password' => $data['password']), '*');
                // if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
                //     $secretKey = '6Ld8xoEpAAAAAC_-KFRWsUigvwTBVSrhe38gtsPW';  // SECRET KEY 
                //     $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $_POST['g-recaptcha-response']);
                    // $responseData = json_decode($verifyResponse);
                    // if ($responseData) {
                        if (!empty($user)) {
                            if ($user['disabled'] == 0) {
                                $this->session->set_userdata('user_id', $user['user_id']);
                                $this->session->set_userdata('role', $user['role']);
                                $response['status'] = '1';
                                $response['message'] = 'Login Successfully';
                                $response['url'] = base_url('dashboard');
                            } else {
                                $response['status'] = '2';
                                $response['message'] = 'This Account Is Blocked Please Contact to Administrator';
                            }
                        } else {
                            $response['status'] = '0';
                            $response['message'] = 'Invalid Credentials';
                        }
                    // } else {
                    //     $response['status'] = '0';
                    //     $response['message'] = 'reCAPTCHA verification failed!';
                    // }
                // } else {
                //     $response['status'] = '0';
                //     $response['message'] = 'Please check on the reCAPTCHA box!';
                // }
            }
            echo json_encode($response, true);
            return;
        }
        $this->load->view('main_login', $response);
    }

    public function forgetPassword()
    {
        $response = array();
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = $this->security->xss_clean($this->input->post());
            $user = get_single_record('tbl_users', ' email = "' . $data['email'] . '"', 'name,user_id,email,password,master_key,phone');
            if (!empty($user)) {
                $message = "Dear " . $user['name'] . ' <p>your User ID ' . $user['user_id'] . '</p><p>  password for Your Account is ' . $user['password'] . ' </p>Transaction Password ' . $user['master_key'];
                $response['message'] = 'Account Detail Sent on Your Email Please check';
                $sms_text = 'Dear ' . $user['name'] . '. Your Account Successfully created. User ID : ' . $user['user_id'] . '. Password :' . $user['password'] . '. Transaction Password:' . $user['master_key'] . '. ' . base_url() . '';
                //notify($user['user_id'], $sms_text, $entity_id = '1201161518339990262', $temp_id = '1207161730102098562');
                // composeMail($user['email'], 'Security Alert', $message);
                $message = 'Dear  ' . $user['name'] . ', Thanks for Choosing ' . title . '. Your New Website Your Id is: ' . $user['user_id'] . ' and Password is: ' . $user['password'] . ' and TXN Password is:  ' . $user['master_key'] . '
                <br>Thank you<br>' . base_url();
                set_flashdata('message', span_success('Account Details sent on your registered Email'));
            } else {
                set_flashdata('message', span_info('Invalid Email'));
            }
        }
        $this->load->view('forgot_password', $response);
    }


    public function passwordReset()
    {
        if (is_logged_in()) {
            $response = array();
            $message = 'reset-password';
            $response['script'] = true;
            $response['header'] = 'PASSWORD MANAGEMENT';
            $response['form_open'] = form_open(base_url('dashboard/reset-password'));
            $response['form'] = [
                'cpassword' => form_label('Current Password', 'cpassword') . form_input(array('type' => 'password', 'name' => 'cpassword', 'id' => 'cpassword', 'class' => 'form-control', 'placeholder' => 'Enter Current Password')),
                'npassword' => form_label('New Password', 'npassword') . form_input(array('type' => 'password', 'name' => 'npassword', 'id' => 'npassword', 'class' => 'form-control',  'placeholder' => 'Enter New Password')),
                'vpassword' => form_label('Verify Password', 'vpassword') . form_input(array('type' => 'password', 'name' => 'vpassword', 'id' => 'vpassword', 'class' => 'form-control',  'placeholder' => 'Enter Verify Password')),
                'txn_password' => form_label('Transaction Password', 'txn_password') . form_input(array('type' => 'password', 'name' => 'txn_password', 'id' => 'txn_password', 'class' => 'form-control', 'placeholder' => 'Enter Transaction Password')),
                // 'otp' => form_label('OTP', 'otp') . form_input(array('type' => 'text', 'name' => 'otp', 'id' => 'otp_input', 'class' => 'form-control', 'style' => 'display: block;',  'placeholder' => 'Enter OTP')),
            ];
            $response['form_button'] = [
                // 'otp' =>  form_button('otp', 'OTP', ['class' => 'btn btn-info', 'id' => 'otp']),
                'submit' => form_submit('reset-password', 'Update', ['class' => 'btn btn-info', 'id' => 'reset-password', 'style' => 'display: block;'])
            ];
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('cpassword', 'Current Password', 'trim|required|xss_clean');
                $this->form_validation->set_rules('npassword', 'New Password', 'trim|required|xss_clean');
                $this->form_validation->set_rules('vpassword', 'Verify Password', 'trim|required|xss_clean');
                if ($this->form_validation->run() != false) {
                    $user = get_single_record('tbl_users', array('user_id' => $this->session->userdata['user_id']), 'id,user_id,password,master_key');
                    if ($user['master_key'] == $data['txn_password']) {
                        $cpassword = $data['cpassword'];
                        $npassword = $data['npassword'];
                        $vpassword = $data['vpassword'];
                        if ($npassword !== $vpassword) {
                            set_flashdata($message, span_danger('Verify Password Does Not Match'));
                        } elseif ($cpassword !== $user['password']) {
                            set_flashdata($message, span_danger('Wrong Current Password'));
                        } else {
                            $updres = update('tbl_users', array('user_id' => $this->session->userdata['user_id']), array('password' => $vpassword));
                            if ($updres == true) {
                                set_flashdata($message, span_success('Password Updated Successfully'));
                            } else {
                                set_flashdata($message, span_danger('There is an error while Changing Password Please Try Again'));
                            }
                        }
                    } else {
                        set_flashdata($message, span_danger('Incorrect Transaction Password!'));
                    }
                }
            }
            $response['message'] = $message;
            $this->load->view('forms', $response);
        } else {
            redirect('login');
        }
    }

    public function signout()
    {
        $this->session->unset_userdata(array('user_id', 'role'));
        redirect('login');
    }
}
