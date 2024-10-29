<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'pagination'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'super', 'security', 'email'));
    }

    public function index()
    {
        if (is_logged_in()) {
            redirect('dashboard');
        } else {
            redirect('login');
        }
    }
}
