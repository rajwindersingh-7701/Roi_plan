<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(array('session', 'form_validation', 'security'));
        $this->load->model(array('Main_model'));
        $this->load->helper(array('site'));
    }

    public function index(){
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = $this->security->xss_clean($this->input->post());
            $user = $this->Main_model->get_single_record('tbl_users', array('user_id' => $data['user_id'], 'password' => $data['password']), 'id,user_id,role,name,email,paid_status,disabled');
            if (!empty($user)) {
                if ($user['disabled'] == 0) {
                    // if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
                    //     $secretKey = '6Lf7tSwdAAAAANS3ECG0paCbzcIj8hRhZuZcuU7a';
                    //     $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
                    //     $responseData = json_decode($verifyResponse);
                    //     if($responseData){
                            $this->session->set_userdata('user_id', $user['user_id']);
                            $this->session->set_userdata('role', $user['role']);
                            redirect('App/User/index');
                    //     }else{
                    //         $this->session->set_flashdata('message','Robot verification failed, please try again.');
                    //     }
                    // }else{
                    //     $this->session->set_flashdata('message','Please check on the reCAPTCHA box.');
                    // }
                } else {
                    $this->session->set_flashdata('message','This Account Is Blocked Please Contact to Administrator');
                }
            } else {
                $this->session->set_flashdata('message','Invalid Credentials');
            }
        }
        $this->load->view('app-login');
    }
}
?>