<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Csv extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->model(array('Main_model'));
    }

    public function index(){
        ini_set('max_execution_time', '0');
        if($this->input->server("REQUEST_METHOD") == "POST"){
            //die('stop');
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'csv';
            $config['max_size'] = 2048;
            $config['file_name'] = 'csv'.time();
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('file')){
                $this->session->set_flashdata('message', $this->upload->display_errors());
            } else {
                $this->session->set_flashdata('message','No User available for address');
                $file = $this->upload->data('file_name');
                $file2 = 'http://oxbin.io/stacking/uploads/'.$file;
                $row = 1;
                if (($handle = fopen($file2, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $num = count($data);
                        echo "<p> $num fields in line $row: <br /></p>\n";
                        if($row > 0){
                            
                            // $userData = [
                            //     'user_id' => $data[0],
                            //     'amount' => -$data[3],
                            //     'type' => 'withdaw_request',
                            //     'description' => 'Withdraw Request',
                            // ];
                            // echo '<pre>';
                            // print_r($userData);
                            //$this->Main_model->add('tbl_income_wallet',$userData);
                            // $this->Main_model->update('tbl_users',['user_id' => $data[0]],$userData);
                            // $user = $this->Main_model->get_single_record('tbl_users', array('user_id' => $data[0]), 'sponser_id');
                            // $this->Main_model->update_directs($user['sponser_id']);
                            if($data[3] <= 100){
                                $commission = 0.0075;
                            } elseif($data[3] == 250) {
                                $commission = 0.0075;
                            } elseif($data[3] == 500) {
                                $commission = 0.01;
                            } elseif($data[3] == 1000) {
                                $commission = 0.01;
                            } elseif($data[3] == 2500) {
                                $commission = 0.01;
                            } elseif($data[3] == 5000) {
                                $commission = 0.0125;
                            }
                            $roiArr = array(
                                'user_id' => $data[0],
                                'amount' => ($commission * $data[3] * 365),
                                'roi_amount' => $commission* $data[3],
                                'days' => 365,
                                'total_days' => 365,
                                'package' =>  $data[3],
                                'type' => 'dividend_share',
                                'creditDate' => date('Y-m-d H:i:s',strtotime($data[1])),
                            );
                            echo '<pre>';
                            print_r($roiArr);
                            //$this->Main_model->add('tbl_roi', $roiArr);

                            // $coinCredit = array(
                            //     'user_id' => $data[0],
                            //     'amount' => $data[3]/0.1,
                            //     'type' => 'self_coin',
                            //     'description' => 'Self Coin from Activation of Member ' . $data[0],
                            // );
                            // $this->Main_model->add('tbl_coin_wallet', $coinCredit);
                           
                            // $this->updateBusiness($user['sponser_id'],'team_business',1);
                            // $this->updateBusiness($user['sponser_id'],'team_business_plan',$data[3]);
                            // $this->Main_model->add('tbl_users',$userData);
                            // $this->Main_model->add('tbl_bank_details',['user_id' => $data[0]]);
                            //$this->add_sponser_counts($userData['user_id'],$userData['user_id'], $level = 1);
                            // for ($c=0; $c < $num; $c++) {
                            //     echo 'User ID :<b>   '.$data[$c].'</b> | Amount: <b>'.$data[$c]. "</b><br />\n";
                            //     // $getUser = $this->Main_model->get_single_record('tbl_users',['wallet_address' => $data[3],'eth_address' => ''],'user_id');
                            //     // if(!empty($getUser)){
                            //     //     $this->Main_model->update('tbl_users',['wallet_address' => $data[3],'eth_address' => ''],['eth_address' => $data[4]]);
                            //     //     $this->session->set_flashdata('message','Address Updated');
                            //     // }
                            // }
                        }
                        $row++;
                    }
                    fclose($handle);
                }
                unlink(FCPATH.'uploads/'.$file);
                //redirect('csv');
                die;
            }
        }
        $this->load->view('form');
    }

    // public function updateRoi($user_id,$field,$business){
    //     $users = $this->Main_model->get_records('tbl_users',['paid_ >' => 400],'user_id');
    //     foreach($users as $user){
    //         $this->add_sponser_counts($user['user_id'],$user['user_id'], $level = 1);
    //     }
    // }

    // private function updateBusiness($user_id,$field,$business){
    //     $userinfo = $this->Main_model->get_single_record('tbl_users',['user_id' => $user_id],'user_id,sponser_id');
    //     if(!empty($userinfo['user_id']) && $userinfo['user_id'] != '-1'){
    //         $this->Main_model->update_business($field,$userinfo['user_id'],$business);
    //         $this->updateBusiness($userinfo['sponser_id'],$field,$business);
    //     }
    // }

    // public function makeDownline(){
    //     $cron = $this->Main_model->get_single_record('tbl_cron',['cron_name' => 'makeDownline3','date' => date('Y-m-d')],'*');
    //     if(empty($cron)){
    //         $this->Main_model->add('tbl_cron',['cron_name' => 'makeDownline3','date' => date('Y-m-d')]);
    //         $users = $this->Main_model->get_records('tbl_users',['id >' => 400],'user_id');
    //         foreach($users as $user){
    //             $this->add_sponser_counts($user['user_id'],$user['user_id'], $level = 1);
    //         }
    //     } else {
    //         echo 'cron done';
    //     }
    // }

    // private function add_sponser_counts($user_name, $downline_id , $level) {
    //     $user = $this->Main_model->get_single_record('tbl_users', array('user_id' => $user_name), $select = 'sponser_id,user_id');
    //     if ($user['sponser_id'] != '' && $user['sponser_id'] != '-1') {
    //         $downlineArray = array(
    //             'user_id' => $user['sponser_id'],
    //             'downline_id' => $downline_id,
    //             'position' => '',
    //             'level' => $level,
    //         );
    //         echo '<pre>';
    //         print_r($downlineArray);
    //         $this->Main_model->add('tbl_sponser_count', $downlineArray);
    //         $user_name = $user['sponser_id'];
    //         $this->add_sponser_counts($user_name, $downline_id, $level + 1);
    //     }
    // }
}
?>