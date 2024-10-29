<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'Binance'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'security', 'super'));
    }

    /// Binary Registration /// 
    public function binaryRegister()
    {
        $response = array();
        $sponser_id = $this->input->get('sponser_id');
        if ($sponser_id == '') {
            $sponser_id = '';
        }
        $response['sponser_id'] = $sponser_id;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->form_validation->set_rules('sponser_id', 'Sponser ID', 'trim|required|xss_clean');
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('userPosition', 'Position', 'trim|required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('register_message', span_danger_simple(validation_errors()));
                $this->load->view('register_binary', $response);
            } else {
                $sponser_id = $this->input->post('sponser_id');
                $response['sponser_id'] = $sponser_id;
                $sponser = get_single_record('tbl_users', array('user_id' => $sponser_id), '*');
                if (!empty($sponser)) {
                    $id_number = $this->getUserIdForRegister();
                    $userData['user_id'] =  $id_number;
                    $userData['sponser_id'] = $sponser_id;
                    $userData['name'] = $this->input->post('name');
                    $userData['phone'] = $this->input->post('phone');
                    $userData['position'] = $this->input->post('userPosition');
                    $userData['last_left'] = $userData['user_id'];
                    $userData['last_right'] = $userData['user_id'];
                    $userData['password'] = rand(100000, 999999); //$this->input->post('password');
                    $userData['master_key'] = rand(100000, 999999);
                    if ($userData['position'] == 'L') {
                        $userData['upline_id'] = $sponser['last_left'];
                    } else {
                        $userData['upline_id'] = $sponser['last_right'];
                    }
                    $res = add('tbl_users', $userData);
                    $res2 = add('tbl_bank_details', array('user_id' => $userData['user_id']));
                    // $res3 = add('tbl_income_wallet', array('user_id' => $userData['user_id'], 'amount' => 300, 'type' => 'welcome_bonus'));
                    if ($res == true) {
                        if ($userData['position'] == 'R') {
                            update('tbl_users', array('last_right' => $userData['upline_id']), array('last_right' => $userData['user_id']));
                            update('tbl_users', array('user_id' => $userData['upline_id']), array('right_node' => $userData['user_id']));
                        } elseif ($userData['position'] == 'L') {
                            update('tbl_users', array('last_left' => $userData['upline_id']), array('last_left' => $userData['user_id']));
                            update('tbl_users', array('user_id' => $userData['upline_id']), array('left_node' => $userData['user_id']));
                        }
                        $this->add_counts($userData['user_id'], $userData['user_id'], 1);
                        $this->add_sponser_counts($userData['user_id'], $userData['user_id'], $level = 1);
                        $response['message'] = 'Dear ' . $userData['name'] . ', Your Account Successfully Created. <br>User ID :  ' . $userData['user_id'] .  ' <br> Password :  ' . $userData['password'] .  ' <br> Transaction Password :  ' . $userData['master_key'];
                        $this->load->view('success', $response);
                    } else {
                        $this->session->set_flashdata('register_message', span_danger_simple('Error while Registraion please try Again'));
                        $this->load->view('register_binary', $response);
                    }
                } else {
                    $this->session->set_flashdata('register_message', span_danger_simple("Please enter valid Sponsor ID."));
                    $this->load->view('register_binary', $response);
                }
            }
        } else {
            $this->load->view('register_binary', $response);
        }
    }


    /// Simple Registration /// 
    public function simpleRegister()
    {
        $response = array();
        $sponser_id = $this->input->get('sponser_id');
        if ($sponser_id == '') {
            $sponser_id = '';
        }

        $response['packages'] = $this->User_model->get_records('tbl_package', [], '*');

        $response['sponser_id'] = $sponser_id;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->form_validation->set_rules('sponser_id', 'Sponser ID', 'trim|required|xss_clean');
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
           $this->form_validation->set_rules('phone', 'Phone', 'trim|required|numeric|xss_clean');
            // $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                set_flashdata('register_message', span_danger(validation_errors()));
                $this->load->view('register', $response);
            } else {
                $paidReg = false;
                $sponser_id = $this->input->post('sponser_id');
                $package_invest = $this->input->post('package');
                $response['sponser_id'] = $sponser_id;
                $sponser = get_single_record('tbl_users', array('user_id' => $sponser_id), '*');
                $package = get_single_record('tbl_package', ['id' => 1], '*');
                $checkEmail = get_single_record('tbl_users', ['email' => $this->input->post('email')], '*');
                $checkPhone = get_single_record('tbl_users', ['phone' => $this->input->post('phone')], 'count(id) as phn');
                $checkPan = get_single_record('tbl_users', ['pan' => $this->input->post('pan')], 'count(id) as pan');
                if (!empty($sponser)) {
                    // if(empty($checkEmail)){
                    // if ($checkPhone['phn'] < 1) {
                    // if ($checkPan['pan'] < 1) {
                    $id_number = $this->getUserIdForRegister();
                    $userData['user_id'] =  $id_number;
                    $userData['sponser_id'] = $sponser_id;
                    $userData['name'] = $this->input->post('name');
                   $userData['phone'] = $this->input->post('phone');
                    $userData['password'] = rand(100000, 999999); //$this->input->post('password');
                    // $userData['email'] = $this->input->post('email');
                    $userData['master_key'] = rand(100000, 999999);

                    if ($paidReg == true) {
                        $userData['package_id'] = $package['id'];
                        $userData['package_amount'] = $package_invest; //$package['price'];
                        $userData['paid_status'] = 1;
                        $userData['topup_date'] = date('Y-m-d H:i:s');
                    }
                    $res = add('tbl_users', $userData);
                    $res = add('tbl_bank_details', array('user_id' => $userData['user_id']));
                    if ($res) {
                        $this->add_sponser_counts($userData['user_id'], $userData['user_id'], $level = 1);
                        if ($paidReg == true) {
                            $this->User_model->update_directs($userData['sponser_id']);

                            $roiMaker = $userData['hub_price'] * $package['commision'];

                            $roiArr = array(
                                'user_id' => $userData['user_id'],
                                'amount' => ($roiMaker * $package['days']),
                                'roi_amount' => $roiMaker,
                                'days' => $package['days'],
                                'type' => 'roi_income',
                            );
                            add('tbl_roi', $roiArr);

                            $directIncome = [
                                'user_id' => $userData['sponser_id'],
                                'amount' => $package_invest * $package['direct_income'] / 100,
                                'type' => 'direct_income',
                                'description' => 'Direct Income From User ' . $userData['user_id'],
                            ];
                            add('tbl_income_wallet', $directIncome);
                            $sponser = get_single_record('tbl_users', array('user_id' => $userData['sponser_id']), 'sponser_id,paid_status,package_amount,package_id,directs');
                            // $this->level_income($sponser['sponser_id'], $userData['user_id'], $package['level_income'], $package_invest);
                        }
                      //  $response['message'] = 'Dear ' . $userData['name'] . ', Your Account Successfully Created. <br>User ID :  ' . $userData['user_id'] . ' <br> Password :  ' . $userData['password'] . ' <br> Transaction Password :  ' . $userData['master_key'];
                        $response['message'] = '<h5 class="mainboxes">Dear '.$userData['name'].', Your Account Successfully Created. <br>User ID :  '.$userData['user_id'].' <br> Password :  ' . $userData['password'] . ' <br> Transaction Password :  ' . $userData['master_key'] . ' </h5>';
                         $msg = 'Dear%20' . $userData['name'] . '%20you%20are%20successfully%20registered%20Your%20id%20%3A%20' . $userData['user_id'] . '%20and%20password%20is%20' . $userData['password'] . '%20and%20transaction%20password%20is%20' . $userData['master_key'] . '%20Website%3A%20' . base_url() . '%20omkarent';

                           phoneMessageregister($userData['user_id'], $userData['phone'], $msg);

                        // send_crypto_email($userData['email'], 'Registration', $response['message']);
                        // notify($userData['user_id'], $response['message'], $entity_id = '1201161518339990262', $temp_id = '1207161730102098562');
                        // composeMail($userData['email'], 'Registration', $response['message'], $display = false);
                        // $sms_text = 'Dear ' . $userData['name'] . '. Your Account Successfully created. User ID : ' . $userData['user_id'] . '. Password :' . $userData['password'] . '. Transaction Password:' . $userData['master_key'] . '. ' . base_url() . '';

                        //userMail($userData['email'],'Registration',$sms_text);

                        $this->load->view('success', $response);

                        // $sms_text = 'Dear ' .$userData['name']. ', Your Account Successfully created. User ID :  ' . $userData['user_id'] . ' Password :' . $userData['password'] . ' Transaction Password:' .$userData['master_key'] . base_url();
                        // //sendMail($sms_text,$userData['email']);
                        // $resp['status'] = 'success';
                        // $resp['message'] = $sms_text;
                        // echo json_encode($resp);

                        // notify_user($userData['user_id'] , $sms_text);
                        // notify_mail($userData['email'] , $sms_text,'Registration Alert');

                    } else {
                        set_flashdata('register_message', span_danger('Error while Registraion please try Again!'));
                        $this->load->view('register', $response);
                    }
                    //     } else {
                    //         set_flashdata('register_message', span_danger('This Pan Number use only 1 time,Please try another!'));
                    //         $this->load->view('register', $response);
                    //     }
                    // } else {
                    //     set_flashdata('register_message', span_danger('This Phone Number use only 1 time,Please try another!'));
                    //     $this->load->view('register', $response);
                    // }
                    // } else {
                    // set_flashdata('register_message', span_danger('This Email Address already exists,Please try another!'));
                    //     $this->load->view('register', $response);
                    // }
                } else {
                    set_flashdata('register_message', span_danger('Please enter valid Sponsor ID!'));
                    $this->load->view('register', $response);
                }
            }
        } else {
            $this->load->view('register', $response);
        }
    }

     public function testMial()
    {
        // die;
        $userData['name'] = 'rajwinder';
        $userData['user_id'] = 'rajwinder';
        $userData['password'] = 'rajwinder';
        $userData['master_key'] = '12345';

        $msg = 'Dear%20' . $userData['name'] . '%20you%20are%20successfully%20registered%20Your%20id%20%3A%20' . $userData['user_id'] . '%20and%20password%20is%20' . $userData['password'] . '%20and%20transaction%20password%20is%20' . $userData['master_key'] . '%20Website%3A%20' . base_url() . '%20omkarent';

        phoneMessageregister('admin', '6239992068', $msg);
        // $message = "Dear Team ,<br></br> I Informed you that I Requested for fund amount is Rs.100 Please Update fund in my wallet from User ID:" . $this->session->userdata['user_id'] . ',' . base_url();
        // composeMail('gnirajwinder@gmail.com', 'Fund Request', $message);
    }


    public function indexAjax()
    {
        $response = array();
        $sponser_id = $this->input->get('sponser_id');
        if ($sponser_id == '') {
            $sponser_id = '';
        }
        $response['packages'] = $this->User_model->get_records('tbl_package', [], '*');
        $response['csrt'] =  $this->security->get_csrf_hash();

        $addressIsAlready = get_single_record('tbl_users', array('eth_address' => $this->input->post('wallet_address')), '*');
        if (!empty($addressIsAlready)) {
            $response['status'] = 'fail';
            $response['msg'] = 'Account is already Exist.';
            echo json_encode($response, true);
            return;
        }

        $response['sponser_id'] = $sponser_id;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->form_validation->set_rules('sponser_id', 'Sponser ID', 'trim|required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                set_flashdata('register_message', span_danger(validation_errors()));
                $this->load->view('register', $response);
            } else {

                $paidReg = false;

                $sponser_id = $this->input->post('sponser_id');
                $phone = $this->input->post('phone');
                $package_invest = $this->input->post('package');
                $response['sponser_id'] = $sponser_id;
                $sponser = get_single_record('tbl_users', array('user_id' => $sponser_id), '*');
                $package = get_single_record('tbl_package', ['id' => 1], '*');
                if (!empty($sponser)) {
                    $status = $this->input->post('status');
                    $id_number = $this->getUserIdForRegister();
                    $userData['user_id'] =  $id_number;
                    $userData['sponser_id'] = $sponser_id;
                    // $userData['name'] = $this->input->post('name');
                    $userData['eth_address'] = $this->input->post('wallet_address');
                    // $userData['phone'] = $this->input->post('phone');
                    $userData['password'] = rand(100000, 999999); //$this->input->post('password');
                    // $userData['email'] = $this->input->post('email');
                    $userData['master_key'] = rand(100000, 999999);

                    if ($paidReg == true) {
                        $userData['package_id'] = $package['id'];
                        $userData['package_amount'] = $package_invest; //$package['price'];

                        $userData['paid_status'] = 1;
                        $userData['topup_date'] = date('Y-m-d H:i:s');
                        $hub_rate = get_single_record('tbl_admin', ['id' => 1], 'hub_rate');
                        $userData['hub_price'] = $package_invest / $hub_rate['hub_rate'];
                        $userData['capping'] = $package['capping'];
                    }
                    $res = add('tbl_users', $userData);
                    $res = add('tbl_bank_details', array('user_id' => $userData['user_id']));
                    if ($res) {
                        $this->add_sponser_counts($userData['user_id'], $userData['user_id'], $level = 1);
                        if ($paidReg == true) {
                            $this->User_model->update_directs($userData['sponser_id']);

                            $roiMaker = $userData['hub_price'] * $package['commision'];

                            $roiArr = array(
                                'user_id' => $userData['user_id'],
                                'amount' => ($roiMaker * $package['days']),
                                'roi_amount' => $roiMaker,
                                'days' => $package['days'],
                                'type' => 'roi_income',
                            );
                            add('tbl_roi', $roiArr);

                            $directIncome = [
                                'user_id' => $userData['sponser_id'],
                                'amount' => $package_invest * $package['direct_income'] / 100,
                                'type' => 'direct_income',
                                'description' => 'Direct Income From User ' . $userData['user_id'],
                            ];
                            add('tbl_income_wallet', $directIncome);

                            $sponser = get_single_record('tbl_users', array('user_id' => $userData['sponser_id']), 'sponser_id,paid_status,package_amount,package_id,directs');

                            $this->level_income($sponser['sponser_id'], $userData['user_id'], $package['level_income'], $package_invest);
                        }

                        $user = get_single_record('tbl_users', array('user_id' => $userData['user_id']), 'id,user_id,role,name,email,paid_status,disabled');

                        $this->session->set_userdata('user_id', $user['user_id']);
                        $this->session->set_userdata('role', $user['role']);

                        $response['msg'] = 'Dear , Your Account Successfully created. <br>User ID :  ' . $userData['user_id'] . ' <br> Password :' . $userData['password'] . ' <br> Transaction Password:' . $userData['master_key'];

                        //composeMail($userData['email'],'Registration','Registration',$response['msg'],$display=false);

                        $response['status'] = 'success';
                        echo json_encode($response, true);
                        return;
                    } else {
                        $response['status'] = 'fail';
                        $response['msg'] = 'Error while Registraion please try Again';
                        echo json_encode($response, true);
                        return;
                    }
                } else {
                    $response['status'] = 'fail';
                    $response['msg'] = 'Please enter valid Sponsor ID.';
                    echo json_encode($response, true);
                    return;
                }
            }
        } else {
            $response['status'] = 'fail';
            $response['msg'] = 'Opps! something went wrong. Please try again.';
            echo json_encode($response, true);
            return;
        }
    }

    private function getUserIdForRegister()
    {
        $user_id = prefix . rand(10000, 99999);
        $sponser = get_single_record('tbl_users', array('user_id' => $user_id), 'user_id,name');
        if (!empty($sponser)) {
            return $this->getUserIdForRegister();
        } else {
            return $user_id;
        }
    }

    private function add_sponser_counts($user_name, $downline_id, $level)
    {
        $user = get_single_record('tbl_users', array('user_id' => $user_name), $select = 'sponser_id,user_id,position');
        if ($user['sponser_id'] != '' && $user['sponser_id'] != 'none') {
            $downlineArray = array(
                'user_id' => $user['sponser_id'],
                'downline_id' => $downline_id,
                'position' => $user['position'],
                'level' => $level,
            );
            add('tbl_sponser_count', $downlineArray);
            $user_name = $user['sponser_id'];
            $this->add_sponser_counts($user_name, $downline_id, $level + 1);
        }
    }

    private function add_counts($user_name, $downline_id, $level)
    {
        $user = get_single_record('tbl_users', array('user_id' => $user_name), 'upline_id,position,user_id');
        if (!empty($user)) {
            if ($user['position'] == 'L') {
                $count = array('left_count' => ' left_count + 1');
                $c = 'left_count';
            } else if ($user['position'] == 'R') {
                $c = 'right_count';
                $count = array('right_count' => ' right_count + 1');
            } else {
                return;
            }
            $this->User_model->update_count($c, $user['upline_id']);
            $downlineArray = array(
                'user_id' => $user['upline_id'],
                'downline_id' => $downline_id,
                'position' => $user['position'],
                'created_at' => date('Y-m-d h:i:s'),
                'level' => $level,
            );
            add('tbl_downline_count', $downlineArray);
            $user_name = $user['upline_id'];

            if ($user['upline_id'] != '') {
                $this->add_counts($user_name, $downline_id, $level + 1);
            }
        }
    }
}
