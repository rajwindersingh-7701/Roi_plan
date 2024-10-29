<?php

defined('BASEPATH') or exit('No direct script access allowed');

class UserInfo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'Binance', 'pagination'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'security', 'email', 'super'));
    }

    public function index()
    {
        if (is_logged_in()) {

            $response['total_activation_sum'] = get_single_record('tbl_users', [''], 'ifnull(sum(package_amount),0) as total_activation_sum');
            $response['stakeAchiever'] = get_records('tbl_roi', "user_id != '' GROUP BY user_id ORDER BY totalStake DESC LIMIT 100", 'ifnull(sum(coin),0) as totalStake,user_id');
            $response['tron_balance'] = get_single_record('tbl_wallet', ['user_id' => $this->session->userdata['user_id']], 'ifnull(sum(amount),0) as tron_balance');
            $response['hub_rate'] = get_single_record('tbl_admin', array('id' => 1), 'hub_rate,title');
            $response['total_coin_income'] = get_single_record('tbl_coin_wallet', 'user_id = "' . $this->session->userdata['user_id'] . '" and amount > 0', 'ifnull(sum(amount),0) as total_coin_income');
            $response['user'] = get_single_record('tbl_users', array('user_id' => $this->session->userdata['user_id']), '*');
            $response['totalMurphyAmount'] = get_single_record('tbl_roi', "user_id = '" . $this->session->userdata['user_id'] . "'", 'ifnull(sum(amount),0) as balance,ifnull(sum(coin),0) as balance2,ifnull(sum(package),0) as balance3');
            $response['murphyAmount'] = get_single_record('tbl_roi', "user_id = '" . $this->session->userdata['user_id'] . "' ORDER BY id ASC limit 1", '*');
            $response['roiRecords'] = get_single_record('tbl_roi', array('user_id' => $this->session->userdata['user_id']), 'total_days');
            $response['token_value'] = get_single_record('tbl_token_value', ['id' => 1], '*');
            $response['today_income'] = get_single_record('tbl_income_wallet', 'user_id = "' . $this->session->userdata['user_id'] . '" and date(created_at) = date(now()) and type !="withdraw_request"', 'ifnull(sum(amount),0) as today_income');
            /*incomes */
            $response['reward_income'] = get_single_record('tbl_income_wallet', 'user_id = "' . $this->session->userdata['user_id'] . '" and type = "reward_income"', 'ifnull(sum(amount),0) as reward_income');
            $response['total_income'] = get_single_record('tbl_income_wallet', 'user_id = "' . $this->session->userdata['user_id'] . '" and amount > 0 and type != "withdraw_request"', 'ifnull(sum(amount),0) as total_income');
            $response['income_balance'] = get_single_record('tbl_income_wallet', 'user_id = "' . $this->session->userdata['user_id'] . '"', 'ifnull(sum(amount),0) as income_balance');

            $response['wallet_balance'] = get_single_record('tbl_wallet', 'user_id = "' . $this->session->userdata['user_id'] . '" ', 'ifnull(sum(amount),0) as wallet_balance');
            $response['coin_balance'] = get_single_record('tbl_roi', 'user_id = "' . $this->session->userdata['user_id'] . '" ', 'ifnull(sum(coin),0) as coin_balance');
            $response['coinBalance'] = get_single_record('tbl_coin_wallet', ['user_id' => $this->session->userdata['user_id']], '*');
            $response['total_withdrawal'] = get_single_record('tbl_income_wallet', 'user_id = "' . $this->session->userdata['user_id'] . '" and amount < 0 ', 'ifnull(sum(amount),0) as balance');
            $response['today_withdrawal'] = get_single_record('tbl_withdraw', 'user_id = "' . $this->session->userdata['user_id'] . '" and date(created_at) = "' . date('Y-m-d') . '"', 'ifnull(sum(amount),0) as balance');
            $response['news'] = get_records('tbl_news', array(), '*');
            $response['popup'] = get_single_record('tbl_popup', [], '*');
            $response['trasfer_wallet'] = get_single_record('tbl_wallet', 'user_id = "' . $this->session->userdata['user_id'] . '"  and type ="income_wallet_transfer"', 'ifnull(sum(amount),0) as trasfer_wallet');


            // TEAM BUSINESS // 

            $response['paid_directs'] = get_single_record('tbl_users', 'sponser_id = "' . $this->session->userdata['user_id'] . '" and paid_status = 1', 'ifnull(count(id),0) as paid_directs');
            $response['free_directs'] = get_single_record('tbl_users', 'sponser_id = "' . $this->session->userdata['user_id'] . '"  and paid_status = 0', 'ifnull(count(id),0) as free_directs');
            $response['LeftPaidteam'] = $this->User_model->calculateTeam($this->session->userdata['user_id'], 1, 'L');
            $response['LeftUnPaidteam'] = $this->User_model->calculateTeam($this->session->userdata['user_id'], 0, 'L');
            $response['RightPaidteam'] = $this->User_model->calculateTeam($this->session->userdata['user_id'], 1, 'R');
            $response['RightUnPaidteam'] = $this->User_model->calculateTeam($this->session->userdata['user_id'], 0, 'R');
            $response['RightBusiness'] = $this->User_model->getBusinessPosition($this->session->userdata['user_id'], 'R');
            $response['LeftBusiness'] = $this->User_model->getBusinessPosition($this->session->userdata['user_id'], 'L');
            $response['directBusiness'] = get_single_record('tbl_users', ['sponser_id' => $this->session->userdata['user_id']], 'ifnull(sum(total_package),0) as directBusiness');
            $response['directBusinessL'] = get_single_record('tbl_users', ['sponser_id' => $this->session->userdata['user_id'], 'position' => 'L'], 'ifnull(sum(total_package),0) as directBusinessL');
            $response['directBusinessR'] = get_single_record('tbl_users', ['sponser_id' => $this->session->userdata['user_id'], 'position' => 'R'], 'ifnull(sum(total_package),0) as directBusinessR');


            //<=========================== Monthly Investment===========================>

            $response['MonthlyCheck'] = get_single_record('tbl_emi_plan', ['user_id' => $this->session->userdata['user_id']], '*');
            $response['PendingEmi'] = get_single_record('tbl_kist_add', ['user_id' => $this->session->userdata['user_id'],'emi_status' => 1], 'count(id) as PendingEmi');


            //<=========================== One Time Investment Investment===========================>
            $response['OneInvestment'] = get_single_record('tbl_emi_plan2', ['user_id' => $this->session->userdata['user_id']], '*');
           $response['PendingEmi2'] = get_single_record('tbl_kist_add2', ['user_id' => $this->session->userdata['user_id'],'emi_status' => 1], 'count(id) as PendingEmi');
            $this->load->view('index', $response);

        } else {
            redirect('login');
        }
    }


    public function UploadProof()
    {
        if (is_logged_in()) {
            $response = array();
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();

            if (!empty($_FILES['userfile'])) {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|pdf|jpeg';
                $config['max_size'] = 100000;
                $config['file_name'] = 'id_proof' . time();
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('userfile')) {
                    $response['message'] = $this->upload->display_errors();
                    // set_flashdata('error', $this->upload->display_errors());
                    $response['success'] = '0';
                } else {
                    $type = $this->input->post('proof_type');
                    $fileData = array('upload_data' => $this->upload->data());
                    $userData[$type] = $fileData['upload_data']['file_name'];
                    $updres = update('tbl_bank_details', array('user_id' => $this->session->userdata['user_id']), $userData);
                    if ($updres == true) {
                        $response['success'] = '1';
                        $response['image'] = base_url('uploads/') . $fileData['upload_data']['file_name'];
                        $response['message'] = 'Proof Uploaded Successfully';
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'There is an error while updating Bank details Please try Again ..';
                    }
                }
            } else {
                $response['message'] = 'There is an error while updating Bank details Proof Please try Again ..';
                $response['success'] = '0';
            }
            echo json_encode($response);
        } else {
            redirect('login');
        }
    }

    public function directs($user_id)
    {
        if (is_logged_in()) {
            $response['header'] = 'Directs';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = array('sponser_id' => $user_id);
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_users', $where, '*', 'dashboard/directs/' . $user_id, 4, 10);
            $response['path'] =  $records['path'];

            $response['field'] = '';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Package</th>
                                <th>Activation Date</th>
                               
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                // $this->User_model->get_single_record('tbl_package',array('user_id' =>$rec['user_id']),'*');
                extract($rec);

                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $name . '</td>
                                <td>'.$package_amount.'</td>
                                <td>' . $topup_date . '</td>
                               
                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $response['export'] = false;
            $response['search'] = false;
            $response['balance'] = false;
            $response['total_income'] = '';
            $this->load->view('reports', $response);
        } else {
            redirect('login');
        }
    }

    public function myTeam()
    {
        if (is_logged_in()) {
            $response['header'] = 'My Downline Team';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = array('user_id' => $this->session->userdata['user_id']);
            // $where = array('user_id' => $this->session->userdata['user_id'], 'level !=' => 1);
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_sponser_count', $where, '*', 'dashboard/my-team', 3, 10);
            $response['path'] =  $records['path'];

            $response['field'] = '';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>User ID</th>
                                <th>Level</th>
                                <th>Package</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $user = get_single_record('tbl_users', ['user_id' => $downline_id], '*');

                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user['name'] . '</td>
                                <td>' . $downline_id . '</td>
                                <td>' . $level . '</td>
                                <td>' . $user['package_amount'] . '</td>
                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $response['export'] = false;
            $response['search'] = false;
            $response['balance'] = false;
            $response['total_income'] = '';
            $this->load->view('reports', $response);
        } else {
            redirect('login');
        }
    }

    public function myDownline($position)
    {
        if (is_logged_in()) {
            if ($position == 'L') {
                $response['header'] = 'Left Downline Team';
            } else {
                $response['header'] = 'Right Downline Team';
            }
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = array('user_id' => $this->session->userdata['user_id'], 'position' => $position);
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_downline_count', $where, '*', 'dashboard/myDownline/' . $position, 4, 10);
            $response['path'] =  $records['path'];

            $response['field'] = '';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>User ID</th>
                                <th>Position</th>
                                <th>Level</th>
                                <th>Package</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $user = get_single_record('tbl_users', ['user_id' => $downline_id], '*');

                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user['name'] . '</td>
                                <td>' . $downline_id . '</td>
                                <td>' . $position . '</td>
                                <td>' . $level . '</td>
                                <td>' . $user['package_amount'] . '</td>
                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $response['export'] = false;
            $response['search'] = false;
            $response['balance'] = false;
            $response['total_income'] = '';
            $this->load->view('reports', $response);
        } else {
            redirect('login');
        }
    }

    public function Tree($user_id)
    {
        if (is_logged_in()) {
            $response = array();
            $response['user'] = get_single_record('tbl_users', array('user_id' => $user_id), '*');
            $response['users'] = get_records('tbl_users', array('sponser_id' => $user_id), '*');
            foreach ($response['users'] as $key => $directs) {
                $response['users'][$key]['sub_directs'] = get_records('tbl_users', array('sponser_id' => $directs['user_id']), '*');
            }
            $this->load->view('tree', $response);
        } else {
            redirect('login');
        }
    }
    public function GenelogyTree($user_id = '')
    {
        if (is_logged_in()) {
            $validate_user = 0;
            $response = array();
            if ($user_id == '') {
                $user_id = $this->input->get('user_id');
            }
            $user = get_single_record('tbl_users', array('user_id' => $user_id), 'user_id');
            if (!empty($user)) {
                if ($user_id == $this->session->userdata['user_id']) {
                    $validate_user = 1;
                } else {
                    $down_user = get_single_record('tbl_downline_count', array('user_id' => $this->session->userdata['user_id'], 'downline_id' => $user_id), '*');
                    if (!empty($down_user)) {
                        $validate_user = 1;
                    }
                }
            } else {
                $validate_user = 0;
            }

            if ($validate_user == 1) {
                $response['validate_user'] = 1;
                $response['level1'] = $this->User_model->get_tree_user($user_id);
                if (!empty($response['level1'])) {
                    $response['level2'][1] = $this->User_model->get_tree_user($response['level1']->left_node);
                    $response['level2'][2] = $this->User_model->get_tree_user($response['level1']->right_node);
                    if (!empty($response['level2'][1]->left_node)) {
                        $response['level3'][1] = $this->User_model->get_tree_user($response['level2'][1]->left_node);
                        if (!empty($response['level3'][1]->left_node)) {
                            $response['level4'][1] = $this->User_model->get_tree_user($response['level3'][1]->left_node);
                        } else {
                            $response['level4'][1] = array();
                        }
                        if (!empty($response['level3'][1]->right_node)) {
                            $response['level4'][2] = $this->User_model->get_tree_user($response['level3'][1]->right_node);
                        } else {
                            $response['level4'][2] = array();
                        }
                    } else {
                        $response['level3'][1] = array();
                        $response['level4'][1] = array();
                        $response['level4'][2] = array();
                    }
                    if (!empty($response['level2'][1]->right_node)) {
                        $response['level3'][2] = $this->User_model->get_tree_user($response['level2'][1]->right_node);
                        if (!empty($response['level3'][2]->left_node)) {
                            $response['level4'][3] = $this->User_model->get_tree_user($response['level3'][2]->left_node);
                        } else {
                            $response['level4'][3] = array();
                        }
                        if (!empty($response['level3'][2]->right_node)) {
                            $response['level4'][4] = $this->User_model->get_tree_user($response['level3'][2]->right_node);
                        } else {
                            $response['level4'][4] = array();
                        }
                    } else {
                        $response['level3'][2] = array();
                        $response['level4'][3] = array();
                        $response['level4'][4] = array();
                    }
                    if (!empty($response['level2'][2]->left_node)) {
                        $response['level3'][3] = $this->User_model->get_tree_user($response['level2'][2]->left_node);
                        if (!empty($response['level3'][3]->left_node)) {
                            $response['level4'][5] = $this->User_model->get_tree_user($response['level3'][3]->left_node);
                        } else {
                            $response['level4'][5] = array();
                        }
                        if (!empty($response['level3'][3]->right_node)) {
                            $response['level4'][6] = $this->User_model->get_tree_user($response['level3'][3]->right_node);
                        } else {
                            $response['level4'][6] = array();
                        }
                    } else {
                        $response['level3'][3] = array();
                        $response['level4'][5] = array();
                        $response['level4'][6] = array();
                    }
                    if (!empty($response['level2'][2]->right_node)) {
                        $response['level3'][4] = $this->User_model->get_tree_user($response['level2'][2]->right_node);
                        if (!empty($response['level3'][4]->left_node)) {
                            $response['level4'][7] = $this->User_model->get_tree_user($response['level3'][4]->left_node);
                        } else {
                            $response['level4'][7] = array();
                        }
                        if (!empty($response['level3'][4]->right_node)) {
                            $response['level4'][8] = $this->User_model->get_tree_user($response['level3'][4]->right_node);
                        } else {
                            $response['level4'][8] = array();
                        }
                    } else {
                        $response['level3'][4] = array();
                        $response['level4'][7] = array();
                        $response['level4'][8] = array();
                    }
                } else {
                    $response['level1'] = [];
                }
                // $response['level2'][1]['placement'] = 0;
                // $response['level2'][2]['placement'] = 0;
                // $response['level3'][1]['placement'] = 0;
                // $response['level3'][4]['placement'] = 0;
                // $response['level4'][1]['placement'] = 0;
                // $response['level4'][8]['placement'] = 0;
                if (!empty($response['level2'][1])) {
                    if (!empty($response['level3'][1])) {
                        if (empty($response['level4'][1])) {
                            $response['level4'][1]['placement'] = 1;
                        }
                    } else {
                        $response['level3'][1]['placement'] = 1;
                    }
                } else {
                    $response['level2'][1]['placement'] = 1;
                }
                if (!empty($response['level2'][2])) {
                    if (!empty($response['level3'][4])) {
                        if (empty($response['level4'][8])) {
                            $response['level4'][8]['placement'] = 1;
                        }
                    } else {
                        $response['level3'][4]['placement'] = 1;
                    }
                } else {
                    $response['level2'][2]['placement'] = 1;
                }
            } else {
                $response['validate_user'] = 0;
            }

            // pr($response,true);
            $this->load->view('gonology-tree', $response);
        } else {
            redirect('login');
        }
    }
    public function Pool($user_id)
    {
        if (is_logged_in()) {
            $response = array();
            $response['pool_id'] = 1;
            $response['user'] = get_single_record('tbl_pool', array('user_id' => $user_id), '*');
            $response['users'] = get_records('tbl_pool', array('upline_id' => $user_id), '*');
            foreach ($response['users'] as $key => $directs) {
                $response['users'][$key]['user_info'] = get_single_record('tbl_users', array('user_id' => $directs['user_id']), 'id,user_id,sponser_id,role,name,first_name,last_name,email,phone,paid_status,created_at');
                $response['users'][$key]['level_2'] = get_records('tbl_pool', array('upline_id' => $directs['user_id']), '*');
            }
            //            pr($response,true);
            $this->load->view('pool', $response);
        } else {
            redirect('login');
        }
    }


    public function genelogy_users($user_id)
    {
        if (is_logged_in()) {
            $response = array();
            $response['directs'] = get_records('tbl_users', array('sponser_id' => $user_id), 'id,user_id,name,sponser_id');
            echo json_encode($response);
        } else {
            redirect('login');
        }
    }

    public function image_upload()
    {
        if (is_logged_in()) {
            $response = array();
            $data = $_POST['image'];
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);

            $data = base64_decode($data);
            $imageName = time() . '.png';
            file_put_contents(APPPATH . '../uploads/' . $imageName, $data);
            $updres = update('tbl_users', array('user_id' => $this->session->userdata['user_id']), array('image' => $imageName));
            $response['message'] = 'Image uploaed Succesffully';
            echo json_encode($response);
            exit();
        } else {
            redirect('login');
        }
    }

    public function get_states($country_id)
    {
        $countries = get_records('states', array('country_id' => $country_id), '*');
        echo json_encode($countries);
    }

    public function get_city($state_id)
    {
        $countries = get_records('cities', array('state_id' => $state_id), '*');
        echo json_encode($countries);
    }

    public function get_user($user_id = '')
    {
        $response = array();
        $response['success'] = 0;
        $user = get_single_record('tbl_users', array('user_id' => $user_id), 'id,user_id,sponser_id,role,name,email,phone,paid_status,created_at');
        if (!empty($user)) {
            echo $user['name'];
        } else {
            echo 'User Not Found';
        }
    }

    public function check_sponser($user_id)
    {
        $res = array();
        $res['success'] = 0;
        $user = get_single_record('tbl_users', array('user_id' => $user_id), 'id,user_id,name');
        if (!empty($user)) {
            $res['message'] = 'User Found';
            $res['user'] = $user;
            $res['success'] = 1;
        } else {
            $res['message'] = 'Invalid User ID';
        }
        echo json_encode($res);
    }

    public function sucecss(){
        if (is_logged_in()) {
            $user = get_single_record('tbl_users', array('user_id' => $this->session->userdata['user_id']), '*'); 
            $userData['name'] = $user['name'];
        $userData['user_id'] = $user['user_id'];
        $userData['password'] = $user['password'];
        $userData['master_key'] = $user['master_key'];
        $response['message'] = 'Dear ' . $userData['name'] . ', Your Account Successfully Created. <br>User ID :  ' . $userData['user_id'] . ' <br> Password :  ' . $userData['password'] . ' <br> Transaction Password :  ' . $userData['master_key'];
            $this->load->view('success',$response);
        } else {
            redirect('login');
        }
    }
}
