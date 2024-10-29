<?php

defined('BASEPATH') or exit('No direct script access allowed');

class secureWithdraw extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'birthdate', 'security', 'email', 'super'));
        date_default_timezone_set('Asia/Kolkata');
    }

    public function index()
    {
        echo ('undefined URL');
    }

    public function getOtp()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->server('REQUEST_METHOD') == 'GET') {
                $userinfo = get_single_record('tbl_users', ['user_id' => $this->session->userdata['user_id']], 'email');
                $_SESSION['verification_otp'] = rand(100000, 999999);
                $this->session->mark_as_temp('verification_otp', 300);
                $message = 'Dear User, Your OTP is ' . $this->session->userdata['verification_otp'] . ' Never share this OTP with anyone, this OTP expire in two minutes. More Info: ' . base_url() . ' From mlmsig';
                // notifySms($this->session->userdata['user_id'], $message, '1201161518339990262', '1207162142573795782');
                //$message = 'You OTP is '.$this->session->userdata['verification_otp'].' (One Time Password), this otp expire in 2 mintues!';
                // $message = 'Dear User, Your OTP is '.$this->session->userdata['verification_otp'].' Never share this OTP with anyone, this OTP expire in two minutes. More Info: '.base_url().' From mlmsig';
                $message = 'Dear User, Your OTP is ' . $this->session->userdata['verification_otp'] . ' Never share this OTP with anyone, this OTP expire in two minutes. More Info: ' . base_url();
                $subject = "OTP Alert !";
                // send_crypto_email($userinfo['email'], $subject, $message);
                // notify($this->session->userdata['user_id'],$message, '1201161518339990262', '1207162142573795782');
                if ($message) {
                    $response['status'] = 1;
                } else {
                    $response['status'] = 0;
                }
            }
        } else {
            $response['status'] = 0;
        }

        echo json_encode($response);
    }

    // public function getOtpLogin($user_id)
    // {   
    //     if ($this->input->is_ajax_request()) {
    //         if ($this->input->server('REQUEST_METHOD') == 'GET') {
    //             $userinfo = get_single_record('tbl_users',['user_id' => $user_id],'email');
    //             $_SESSION['verification_otp'] = rand(100000, 999999);
    //             $this->session->mark_as_temp('verification_otp', 300);
    //             $message = 'Dear User, Your OTP is '.$this->session->userdata['verification_otp'].' Never share this OTP with anyone, this OTP expire in two minutes. More Info: '.base_url().' From mlmsig';
    //             notifySms($user_id,$message, '1201161518339990262', '1207162142573795782');
    //             //$message = 'You OTP is '.$this->session->userdata['verification_otp'].' (One Time Password), this otp expire in 2 mintues!';
    //             // $message = 'Dear User, Your OTP is '.$this->session->userdata['verification_otp'].' Never share this OTP with anyone, this OTP expire in two minutes. More Info: '.base_url().' From mlmsig';
    //             // composeMail($userinfo['email'],'OTP','OTP',$message,$display=false);
    //             //notify($this->session->userdata['user_id'],$message, '1201161518339990262', '1207162142573795782');
    //             if($message){
    //                 $response['status'] = 1;

    //             }else{
    //                 $response['status'] = 0;
    //             }
    //         }
    //     }else{
    //         $response['status'] = 0;
    //     }

    //     echo json_encode($response);
    // }



    public function getOtpMail()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->server('REQUEST_METHOD') == 'GET') {
                $userinfo = get_single_record('tbl_users', ['user_id' => $this->session->userdata['user_id']], 'country_code,email,user_id');
                $_SESSION['verification_otp'] = rand(100000, 999999);
                $this->session->mark_as_temp('verification_otp', 300);
                $message = 'Dear User, Your OTP is ' . $this->session->userdata['verification_otp'] . ' Never share this OTP with anyone, this OTP expire in two minutes. More Info: ' . base_url();
                $subject = "OTP Alert !";
                composeMail($userinfo['email'], $subject, $message, $display = false);

                // send_crypto_email($userinfo['email'],$subject,$message);
                $message = 'Dear User, Your OTP is ' . $this->session->userdata['verification_otp'] . ' Never share this OTP with anyone, this OTP expire in two minutes. More Info: ' . base_url() . ' From mlmsig';
                // if($userinfo['country_code'] != 91){
                //     intSMS($this->session->userdata['user_id'], $message);
                // }else{
                // $message = 'Dear Customer, OTP for Verfication of your registeration is '.$this->session->userdata['verification_otp'].' -TE';

                //     notifySms($userinfo['user_id'], $message,'BFCARD');
                $message = 'Dear Customer, OTP for Verfication of your registeration is ' . $this->session->userdata['verification_otp'] . '-TE';
                // $message = 'Dear Customer, OTP for Verfication of your registeration is '.$this->session->userdata['verification_otp'].'';
                notifySms1($userinfo['user_id'], $message, 'BFCARD', $entity = '1201159465994268632', $temp = '1207165978424390644');


                // }
                if ($message) {
                    $response['status'] = 1;
                } else {
                    $response['status'] = 0;
                }
            }
        } else {
            $response['status'] = 0;
        }

        echo json_encode($response);
    }
    // public function getOtpMail()
    // {   
    //     if ($this->input->is_ajax_request()) {
    //         if ($this->input->server('REQUEST_METHOD') == 'GET') {
    //             $userinfo = get_single_record('tbl_users',['user_id' => $this->session->userdata['user_id']],'email');
    //             $_SESSION['verification_otp'] = rand(100000, 999999);
    //             $this->session->mark_as_temp('verification_otp', 300);
    //             $message = 'Dear User, Your OTP is '.$this->session->userdata['verification_otp'].' Never share this OTP with anyone, this OTP expire in two minutes. More Info: '.base_url();
    //             $subject = "OTP Alert !";
    //             // send_crypto_email($userinfo['email'],$subject,$message);
    //             composeMail($userinfo['email'],$subject,$message,$display=false);
    //             $message = 'Dear User, Your OTP is '.$this->session->userdata['verification_otp'].' Never share this OTP with anyone, this OTP expire in two minutes. More Info: '.base_url().' From mlmsig';
    //             notifySms($this->session->userdata['user_id'],$message, '1201161518339990262', '1207162142573795782');
    //             if($message){
    //                 $response['status'] = 1;

    //             }else{
    //                 $response['status'] = 0;
    //             }
    //         }
    //     }else{
    //         $response['status'] = 0;
    //     }

    //     echo json_encode($response);
    // }

}
