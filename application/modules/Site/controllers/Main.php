<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email'));
        $this->load->model(array('Main_model'));
        $this->load->helper(array('site'));
    }

    public function index()
    {
        $response['news'] = $this->Main_model->get_records('tbl_news', array(), '*');
        $response['popup'] = $this->Main_model->get_single_record1('tbl_popup', '*');
        // $response['top_ranks'] = $this->Main_model->top_ranks();
        // $response['top_eaners'] = $this->Main_model->top_earners();
        //$this->load->view('header',$response);
        $this->load->view('index', $response);
        // $this->load->view('footer',$response);
    }

    public function Login()
    {
        $this->load->view('login.php');
    }
    public function bank()
    {
        $this->load->view('bank.php');
    }
    public function buy()
    {
        $this->load->view('buy.php');
    }
    public function about()
    {
        $this->load->view('about.php');
    }
    public function contact()
    {
        $this->load->view('contact.php');
    }
    public function terms()
    {
        $this->load->view('terms.php');
    }
    public function wallet()
    {
        $this->load->view('wallet.php');
    }
    public function market()
    {
        $this->load->view('market.php');
    }
    public function news()
    {
        $this->load->view('news.php');
    }
    public function buysell()
    {
        $this->load->view('buy_sell.php');
    }
    public function exchange()
    {
        $this->load->view('exchange.php');
    }
    public function marketdata()
    {
        $this->load->view('marketdata.php');
    }
    public function content($content)
    {
        $this->load->view($content);
    }

    public function check_sponser()
    {
        $response = array();
        $response['success'] = 0;
        $user_id = $this->input->post('sponser_id');
        $sponser = $this->Main_model->get_single_record('tbl_users', array('user_id' => $user_id), 'user_id,last_left,last_right,name');
        if (!empty($sponser)) {
            $response['message'] = 'Sponser Found';
            $response['success'] = 1;
            $response['sponser'] = $sponser;
        } else {
            $response['message'] = 'Sponser Not Found';
        }

        echo json_encode($response);
    }
}
