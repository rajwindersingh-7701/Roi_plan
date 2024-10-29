<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Activation extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'pagination'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'super'));
        date_default_timezone_set('Asia/Kolkata');
    }

    public function index()
    {
        if (is_logged_in()) {
            $response['user'] = get_single_record('tbl_users', array('user_id' => $this->session->userdata['user_id']), '*');
            $response['tokenValue'] = get_single_record('tbl_token_value', ['id' => 1], 'amount');
            $response['wallet'] = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as wallet_balance');
            $response['packages'] = get_records('tbl_package', [], '*');
            $this->load->view('activate_account', $response);
        } else {
            redirect('login');
        }
    }


    public function UpgradeAccount()
    {
        if (is_logged_in()) {
            $response['user'] = get_single_record('tbl_users', array('user_id' => $this->session->userdata['user_id']), '*');
            $response['tokenValue'] = get_single_record('tbl_token_value', ['id' => 1], 'amount');
            $response['wallet'] = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as wallet_balance');
            $response['packages'] = get_records('tbl_package', [], '*');
            $this->load->view('upgrade_account', $response);
        } else {
            redirect('login');
        }
    }

    /// ------------------------ SIMPLE ACTIVATION ------------------------  ///
    public function activate()
    {
        if (is_logged_in()) {
            $date = date('Y-m-d');
            /// ------------------------ ACCOUNT ACTIVATE WITH AMOUNT ------------------------  ///
            if (activation_process == 0) {

                if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    $response['status'] = false;
                    $response['token'] = $this->security->get_csrf_hash();
                    $data = $this->security->xss_clean($this->input->post());
                    $this->form_validation->set_rules('user_id', 'User ID', 'required|trim');
                    $this->form_validation->set_rules('amount', 'Amount', 'required|trim|numeric');
                    if ($this->form_validation->run() != FALSE) {
                        $activationProcess = false;
                        $user_id = $data['user_id'];
                        $user = get_single_record('tbl_users', array('user_id' => $user_id), '*');
                        $walletTable = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as wallet_balance');

                        $activationAmount = $data['amount'];
                        $package_id = $user['package_id'] + 1;
                        if ($walletTable['wallet_balance'] >= $activationAmount) {
                            $activationProcess = true;
                        } else {
                            $response['message'] =  span_danger_simple('Insuffcient Balance!');
                        }

                        if (!empty($user)) {
                            if ($activationProcess == true) {
                                if ($user['paid_status'] == 0) {
                                    $sendWallet = array(
                                        'user_id' => $this->session->userdata['user_id'],
                                        'amount' => -$activationAmount,
                                        'type' => 'account_activation',
                                        'remark' => 'Account Activation Deduction for ' . $user_id,
                                    );
                                    add('tbl_wallet', $sendWallet);
                                    $topupData = array(
                                        'paid_status' => 1,
                                        'package_id' => $package_id,
                                        'package_amount' => $activationAmount,
                                        'total_package' => $user['total_package'] + $activationAmount,
                                        'topup_date' => date('Y-m-d H:i:s'),
                                        'capping' => $activationAmount,
                                    );
                                    if (income_limit == 0) {
                                        $topupData['total_limit'] = $user['total_limit'] + ($activationAmount * 3);
                                    }
                                    update('tbl_users', array('user_id' => $user_id), $topupData);

                                    $activationData = [
                                        'user_id' => $user['user_id'],
                                        'activater' => $this->session->userdata['user_id'],
                                        'package' => $activationAmount,
                                        'topup_date' => date('Y-m-d H:i:s'),
                                        'type' => $user['package_amount'] == 0 ? 'activation' : 'upgradation',
                                    ];
                                    $res =  add('tbl_activation_details', $activationData);
                                    if ($res) {
                                        if ($user['paid_status'] == 0) {
                                            $this->User_model->update_directs($user['sponser_id']);
                                        }
                                        $tokenValue = get_single_record('tbl_token_value', ['id' => 1], 'amount');
                                        if (roi_access == 0) : // ROI START IF 
                                            $roiArr = array(
                                                'user_id' => $user['user_id'],
                                                'amount' => $activationAmount * 0.005 * 500,
                                                'roi_amount' => $activationAmount * 0.005,
                                                'days' => 365,
                                                'total_days' => 365,
                                                'package' => $activationAmount,
                                                'token_price' => $tokenValue['amount'],
                                                'coin' => $activationAmount / $tokenValue['amount'],
                                                'type' => 'roi_income',
                                                'creditDate' => date('Y-m-d'),
                                            );
                                            add('tbl_roi', $roiArr);
                                        endif;  // ROI STOP ENDIF

                                        $sponser = get_single_record('tbl_users', array('user_id' => $user['sponser_id']), '*');
                                        if (!empty($sponser['user_id']) && $sponser['paid_status'] == 1) {
                                            if (direct_access == 0) : // DIRECT START IF
                                                $direct_income = $activationAmount * 0.10;

                                                if (income_limit == 0) {
                                                    $CheckLimit = incomeProccess($sponser['user_id'], $direct_income);
                                                    if ($CheckLimit > 0) {
                                                        $direct_income = $CheckLimit;
                                                    } else {
                                                        $direct_income = 0;
                                                    }
                                                }
                                                if ($direct_income > 0) {
                                                    $DirectIncome = array(
                                                        'user_id' => $sponser['user_id'],
                                                        'amount' => $direct_income,
                                                        'type' => 'direct_income',
                                                        'description' => 'Direct Income from Activation of Member ' . $user_id . ' with package ' . $activationAmount,
                                                    );
                                                    add('tbl_income_wallet', $DirectIncome);
                                                }
                                            endif;  // DIRECT  STOP ENDIF
                                        }
                                        if (level_access == 0) : // LEVEL START IF
                                            $level_income = '1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1';
                                            $this->level_income($sponser['sponser_id'], $user['user_id'], $level_income, $activationAmount);
                                        endif; // LEVEL STOP ENDIF

                                        $response['status'] = true;
                                        $response['message'] = span_success_simple('Account Activated Successfully');
                                    } else {
                                        $response['message'] = span_info_simple('Errorrrs');
                                    }
                                } else {
                                    $response['message'] = span_info_simple('This Account Already Acitvated');
                                }
                            }
                        } else {
                            $response['message'] = span_danger_simple('Invalid User ID ');
                        }
                    } else {
                        $response['message'] = span_danger_simple(validation_errors());
                    }
                    echo json_encode($response);
                    exit;
                }
            } else {
                /// ------------------------ ACCOUNT ACTIVATE WITH PACKAGE ------------------------  ///
                if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    $response['status'] = false;
                    $response['token'] = $this->security->get_csrf_hash();
                    $data = $this->security->xss_clean($this->input->post());
                    $this->form_validation->set_rules('user_id', 'User ID', 'required|trim');
                    $this->form_validation->set_rules('package_id', 'Package ID', 'required|trim|numeric');
                    if ($this->form_validation->run() != FALSE) {
                        $activationProcess = false;
                        $user_id = $data['user_id'];
                        $user = get_single_record('tbl_users', array('user_id' => $user_id), '*');
                        $walletTable = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as wallet_balance');
                        $package = get_single_record('tbl_package', ['id' => $data['package_id']], '*');

                        $activationAmount =  $package['price']; //$data['amount']; 
                        $package_id = $package['id'];
                        if ($walletTable['wallet_balance'] >= $activationAmount) {
                            $activationProcess = true;
                        } else {
                            $response['message'] =  span_danger_simple('Insuffcient Balance!');
                        }

                        if (!empty($user)) {
                            if ($activationProcess == true) {
                                $tokenValue = get_single_record('tbl_token_value', ['id' => 1], 'amount');

                                if ($user['paid_status'] == 0) {
                                    $sendWallet = array(
                                        'user_id' => $this->session->userdata['user_id'],
                                        'amount' => -$activationAmount,
                                        'type' => 'account_activation',
                                        'remark' => 'Account Activation Deduction for ' . $user_id,
                                    );
                                    add('tbl_wallet', $sendWallet);
                                    $topupData = array(
                                        'paid_status' => 1,
                                        'package_id' => $package_id,
                                        'package_amount' => $activationAmount,
                                        'total_package' => $user['total_package'] + $activationAmount,
                                        'topup_date' => date('Y-m-d H:i:s'),
                                        'capping' => $activationAmount,
                                    );
                                    update('tbl_users', array('user_id' => $user_id), $topupData);

                                    $activationData = [
                                        'user_id' => $user['user_id'],
                                        'activater' => $this->session->userdata['user_id'],
                                        'package' => $activationAmount,
                                        'topup_date' => date('Y-m-d H:i:s'),
                                        'type' => $user['package_amount'] == 0 ? 'activation' : 'upgradation',
                                    ];
                                    $res =  add('tbl_activation_details', $activationData);
                                    if ($res) {
                                        if ($user['paid_status'] == 0) {
                                            $this->User_model->update_directs($user['sponser_id']);
                                        }

                                        if (roi_access == 0) : // ROI START IF 
                                            $roiArr = array(
                                                'user_id' => $user['user_id'],
                                                'amount' => $package['commision'] * $package['days'] ,
                                                'roi_amount' => $package['commision'],
                                                'days' => $package['days'],
                                                'total_days' => $package['days'],
                                                'package' => $activationAmount,
                                                // 'token_price' => $tokenValue['amount'],
                                                // 'coin' => $activationAmount / $tokenValue['amount'],
                                                'type' => 'roi_income',
                                                'creditDate' => date('Y-m-d'),
                                            );
                                            add('tbl_roi', $roiArr);
                                        endif;  // ROI STOP ENDIF

                                        $sponser = get_single_record('tbl_users', array('user_id' => $user['sponser_id']), '*');
                                        if (!empty($sponser['user_id']) && $sponser['paid_status'] == 1) {
                                            if (direct_access == 0) : // DIRECT START IF

                                                $direct_income = $activationAmount * $package['direct_income'];


                                                $DirectIncome = array(
                                                    'user_id' => $sponser['user_id'],
                                                    'amount' => $direct_income,
                                                    'type' => 'direct_income',
                                                    'description' => 'Direct Income from Activation of Member ' . $user_id . ' with package ' . $activationAmount,
                                                );
                                                add('tbl_income_wallet', $DirectIncome);

                                            endif;  // DIRECT  STOP ENDIF
                                        }
                                        if (level_access == 0) : // LEVEL START IF
                                            $this->level_income($sponser['sponser_id'], $user['user_id'], $package['level_income'], $activationAmount);
                                        endif; // LEVEL STOP ENDIF

                                        $response['status'] = true;
                                        $response['message'] = span_success_simple('Account Activated Successfully');
                                    } else {
                                        $response['message'] = span_info_simple('Errorrrs');
                                    }
                                } else {
                                    $response['message'] = span_info_simple('This Account Already Acitvated');
                                }
                            }
                        } else {
                            $response['message'] = span_danger_simple('Invalid User ID ');
                        }
                    } else {
                        $response['message'] = span_danger_simple(validation_errors());
                    }
                    echo json_encode($response);
                    exit;
                }
            }
        } else {
            $response = ['status' => false, 'message' => 'Please login first!'];
            echo json_encode($response);
            exit;
        }
    }

    /// ------------------------ BINARY ACTIVATION ------------------------  ///
    public function activateBinary()
    {
        if (is_logged_in()) {
            $date = date('Y-m-d');

            /// ------------------------ ACCOUNT ACTIVATE WITH AMOUNT ------------------------  ///
            if (activation_process == 0) {
                if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    $response['status'] = false;
                    $response['token'] = $this->security->get_csrf_hash();
                    $data = $this->security->xss_clean($this->input->post());
                    $this->form_validation->set_rules('user_id', 'User ID', 'required|trim');
                    $this->form_validation->set_rules('amount', 'Amount', 'required|trim|numeric');
                    if ($this->form_validation->run() != FALSE) {
                        $activationProcess = false;
                        $user_id = $data['user_id'];
                        $user = get_single_record('tbl_users', array('user_id' => $user_id), '*');
                        $walletTable = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as wallet_balance');

                        $activationAmount = $data['amount'];
                        $package_id = $user['package_id'] + 1;
                        if ($walletTable['wallet_balance'] >= $activationAmount) {
                            $activationProcess = true;
                        } else {
                            $response['message'] =  span_danger_simple('Insuffcient Balance!');
                        }

                        if (!empty($user)) {
                            if ($activationProcess == true) {
                                if ($user['paid_status'] == 0) {
                                    $sendWallet = array(
                                        'user_id' => $this->session->userdata['user_id'],
                                        'amount' => -$activationAmount,
                                        'type' => 'account_activation',
                                        'remark' => 'Account Activation Deduction for ' . $user_id,
                                    );
                                    add('tbl_wallet', $sendWallet);
                                    $topupData = array(
                                        'paid_status' => 1,
                                        'package_id' => $package_id,
                                        'package_amount' => $activationAmount,
                                        'total_package' => $user['total_package'] + $activationAmount,
                                        'topup_date' => date('Y-m-d H:i:s'),
                                        'capping' => $activationAmount,
                                    );
                                    update('tbl_users', array('user_id' => $user_id), $topupData);

                                    $activationData = [
                                        'user_id' => $user['user_id'],
                                        'activater' => $this->session->userdata['user_id'],
                                        'package' => $activationAmount,
                                        'topup_date' => date('Y-m-d H:i:s'),
                                        'type' => $user['package_amount'] == 0 ? 'activation' : 'upgradation',
                                    ];
                                    $res =  add('tbl_activation_details', $activationData);
                                    if ($res) {
                                        if ($user['paid_status'] == 0) {
                                            $this->User_model->update_directs($user['sponser_id']);
                                        }
                                        $tokenValue = get_single_record('tbl_token_value', ['id' => 1], 'amount');
                                        if (roi_access == 0) : // ROI START IF 
                                            $roiArr = array(
                                                'user_id' => $user['user_id'],
                                                'amount' => $activationAmount * 0.005 * 500,
                                                'roi_amount' => $activationAmount * 0.005,
                                                'days' => 365,
                                                'total_days' => 365,
                                                'package' => $activationAmount,
                                                'token_price' => $tokenValue['amount'],
                                                'coin' => $activationAmount / $tokenValue['amount'],
                                                'type' => 'roi_income',
                                                'creditDate' => date('Y-m-d'),
                                            );
                                            add('tbl_roi', $roiArr);
                                        endif;  // ROI STOP ENDIF

                                        $sponser = get_single_record('tbl_users', array('user_id' => $user['sponser_id']), '*');
                                        if (!empty($sponser['user_id']) && $sponser['paid_status'] == 1) {
                                            if (direct_access == 0) : // DIRECT START IF

                                                $direct_income = $activationAmount * 0.10;


                                                $DirectIncome = array(
                                                    'user_id' => $sponser['user_id'],
                                                    'amount' => $direct_income,
                                                    'type' => 'direct_income',
                                                    'description' => 'Direct Income from Activation of Member ' . $user_id . ' with package ' . $activationAmount,
                                                );
                                                add('tbl_income_wallet', $DirectIncome);

                                            endif;  // DIRECT  STOP ENDIF
                                        }
                                        if (level_access == 0) : // LEVEL START IF
                                            $level_income = '1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1';
                                            $this->level_income($sponser['sponser_id'], $user['user_id'], $level_income, $activationAmount);
                                        endif; // LEVEL STOP ENDIF
                                        $this->updateBusiness($user['sponser_id'], 'team_business', $activationAmount);
                                        $this->updateBusiness($user['sponser_id'], 'team_business_plan', $activationAmount);
                                        $response['status'] = true;
                                        $response['message'] = span_success_simple('Account Activated Successfully');
                                    } else {
                                        $response['message'] = span_info_simple('Errorrrs');
                                    }
                                } else {
                                    $response['message'] = span_info_simple('This Account Already Acitvated');
                                }
                            }
                        } else {
                            $response['message'] = span_danger_simple('Invalid User ID ');
                        }
                    } else {
                        $response['message'] = span_danger_simple(validation_errors());
                    }
                    echo json_encode($response);
                    exit;
                }
            } else {
                /// ------------------------ ACCOUNT ACTIVATE WITH PACKAGE ------------------------  ///
                if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    $response['status'] = false;
                    $response['token'] = $this->security->get_csrf_hash();
                    $data = $this->security->xss_clean($this->input->post());
                    $this->form_validation->set_rules('user_id', 'User ID', 'required|trim');
                    $this->form_validation->set_rules('package_id', 'Package ID', 'required|trim|numeric');
                    if ($this->form_validation->run() != FALSE) {
                        $activationProcess = false;
                        $user_id = $data['user_id'];
                        $user = get_single_record('tbl_users', array('user_id' => $user_id), '*');
                        $walletTable = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as wallet_balance');
                        $package = get_single_record('tbl_package', ['id' => $data['package_id']], '*');

                        $activationAmount =  $package['price']; //$data['amount']; 
                        $package_id = $package['id'];
                        if ($walletTable['wallet_balance'] >= $activationAmount) {
                            $activationProcess = true;
                        } else {
                            $response['message'] =  span_danger_simple('Insuffcient Balance!');
                        }

                        if (!empty($user)) {
                            if ($activationProcess == true) {
                                $tokenValue = get_single_record('tbl_token_value', ['id' => 1], 'amount');

                                if ($user['paid_status'] == 0) {
                                    $sendWallet = array(
                                        'user_id' => $this->session->userdata['user_id'],
                                        'amount' => -$activationAmount,
                                        'type' => 'account_activation',
                                        'remark' => 'Account Activation Deduction for ' . $user_id,
                                    );
                                    add('tbl_wallet', $sendWallet);
                                    $topupData = array(
                                        'paid_status' => 1,
                                        'package_id' => $package_id,
                                        'package_amount' => $activationAmount,
                                        'total_package' => $user['total_package'] + $activationAmount,
                                        'topup_date' => date('Y-m-d H:i:s'),
                                        'capping' => $activationAmount * 0.5,
                                        'direct_percent' => $package['direct_percent'],
                                    );
                                    update('tbl_users', array('user_id' => $user_id), $topupData);

                                    $activationData = [
                                        'user_id' => $user['user_id'],
                                        'activater' => $this->session->userdata['user_id'],
                                        'package' => $activationAmount,
                                        'topup_date' => date('Y-m-d H:i:s'),
                                        'type' => $user['package_amount'] == 0 ? 'activation' : 'upgradation',
                                    ];
                                    $res =  add('tbl_activation_details', $activationData);
                                    if ($res) {
                                        if ($user['paid_status'] == 0) {
                                            $this->User_model->update_directs($user['sponser_id']);
                                        }

                                        if (roi_access == 0) : // ROI START IF 
                                            $roiArr = array(
                                                'user_id' => $user['user_id'],
                                                'amount' => $activationAmount * $package['roi_income'] *  $package['days'],
                                                'roi_amount' => $activationAmount * $package['roi_income'],
                                                'days' => $package['days'],
                                                'total_days' => $package['days'],
                                                'package' => $activationAmount,
                                                'type' => 'roi_income',
                                                'creditDate' => date('Y-m-d'),
                                            );
                                            add('tbl_roi', $roiArr);
                                        endif;  // ROI STOP ENDIF

                                        $sponser = get_single_record('tbl_users', array('user_id' => $user['sponser_id']), '*');
                                        if (!empty($sponser['user_id']) && $sponser['paid_status'] == 1) {
                                            if (direct_access == 0) : // DIRECT START IF
                                                // $checkCapping = get_single_record('tbl_income_wallet',['user_id' =>$sponser['user_id'],'date(created_at)' => $date,'amount >' => 0,'type!=' =>'withdraw_request'],'ifnull(sum(amount),0) as total');
                                                $direct_income = $activationAmount * $sponser['direct_percent'] / 100;
                                                // $direct_income = $activationAmount * $package['direct_income'];
                                                // if($checkCapping['total']+$direct_income < $sponser['capping']){
                                                $DirectIncome = array(
                                                    'user_id' => $sponser['user_id'],
                                                    'amount' => $direct_income,
                                                    'type' => 'direct_income',
                                                    'description' => 'Direct Income from Activation of Member ' . $user_id . ' with package ' . $activationAmount,
                                                );
                                                add('tbl_income_wallet', $DirectIncome);
                                            //  }
                                            endif;  // DIRECT  STOP ENDIF
                                        }
                                        if (level_access == 0) : // LEVEL START IF
                                            $this->level_income($sponser['sponser_id'], $user['user_id'], $package['level_income'], $activationAmount);
                                        endif; // LEVEL STOP ENDIF

                                        $response['status'] = true;
                                        $response['message'] = span_success_simple('Account Activated Successfully');
                                        $this->updateBusiness($user['sponser_id'], 'team_business', $activationAmount);
                                        $this->updateBusiness($user['sponser_id'], 'team_business_plan', $activationAmount);
                                        $this->update_business($user['user_id'], $user['user_id'], $level = 1, $package['price'], $package['price'], $type = 'topup', '');
                                    } else {
                                        $response['message'] = span_info_simple('Errorrrs');
                                    }
                                } else {
                                    $response['message'] = span_info_simple('This Account Already Acitvated');
                                }
                            }
                        } else {
                            $response['message'] = span_danger_simple('Invalid User ID ');
                        }
                    } else {
                        $response['message'] = span_danger_simple(validation_errors());
                    }
                    echo json_encode($response);
                    exit;
                }
            }
        } else {
            $response = ['status' => false, 'message' => 'Please login first!'];
            echo json_encode($response);
            exit;
        }
    }

    /// ------------------------ BINARY UPGRADATION ------------------------  ///
    public function upgradeBinary()
    {
        if (is_logged_in()) {
            $date = date('Y-m-d');

            /// ------------------------ ACCOUNT UPGRADE WITH AMOUNT ------------------------  ///
            if (activation_process == 0) {
                if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    $response['status'] = false;
                    $response['token'] = $this->security->get_csrf_hash();
                    $data = $this->security->xss_clean($this->input->post());
                    $this->form_validation->set_rules('user_id', 'User ID', 'required|trim');
                    $this->form_validation->set_rules('amount', 'Amount', 'required|trim|numeric');
                    if ($this->form_validation->run() != FALSE) {
                        $activationProcess = false;
                        $user_id = $data['user_id'];
                        $user = get_single_record('tbl_users', array('user_id' => $user_id), '*');
                        $walletTable = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as wallet_balance');

                        $activationAmount = $data['amount'];
                        $package_id = $user['package_id'] + 1;
                        if ($walletTable['wallet_balance'] >= $activationAmount) {
                            $activationProcess = true;
                        } else {
                            $response['message'] =  span_danger_simple('Insuffcient Balance!');
                        }

                        if (!empty($user)) {
                            if ($activationProcess == true) {
                                if ($user['paid_status'] == 0) {
                                    $sendWallet = array(
                                        'user_id' => $this->session->userdata['user_id'],
                                        'amount' => -$activationAmount,
                                        'type' => 'account_activation',
                                        'remark' => 'Account Activation Deduction for ' . $user_id,
                                    );
                                    add('tbl_wallet', $sendWallet);
                                    $topupData = array(
                                        'paid_status' => 1,
                                        'package_id' => $package_id,
                                        'package_amount' => $activationAmount,
                                        'total_package' => $user['total_package'] + $activationAmount,
                                        'topup_date' => date('Y-m-d H:i:s'),
                                        'capping' => $activationAmount,
                                    );
                                    update('tbl_users', array('user_id' => $user_id), $topupData);

                                    $activationData = [
                                        'user_id' => $user['user_id'],
                                        'activater' => $this->session->userdata['user_id'],
                                        'package' => $activationAmount,
                                        'topup_date' => date('Y-m-d H:i:s'),
                                        'type' => $user['package_amount'] == 0 ? 'activation' : 'upgradation',
                                    ];
                                    $res =  add('tbl_activation_details', $activationData);
                                    if ($res) {
                                        if ($user['paid_status'] == 0) {
                                          //  $this->User_model->update_directs($user['sponser_id']);
                                        }
                                        $tokenValue = get_single_record('tbl_token_value', ['id' => 1], 'amount');
                                        if (roi_access == 0) : // ROI START IF 
                                            $roiArr = array(
                                                'user_id' => $user['user_id'],
                                                'amount' => $activationAmount * 0.005 * 500,
                                                'roi_amount' => $activationAmount * 0.005,
                                                'days' => 365,
                                                'total_days' => 365,
                                                'package' => $activationAmount,
                                                'token_price' => $tokenValue['amount'],
                                                'coin' => $activationAmount / $tokenValue['amount'],
                                                'type' => 'roi_income',
                                                'creditDate' => date('Y-m-d'),
                                            );
                                            add('tbl_roi', $roiArr);
                                        endif;  // ROI STOP ENDIF

                                        $sponser = get_single_record('tbl_users', array('user_id' => $user['sponser_id']), '*');
                                        if (!empty($sponser['user_id']) && $sponser['paid_status'] == 1) {
                                            if (direct_access == 0) : // DIRECT START IF

                                                $direct_income = $activationAmount * 0.10;


                                                $DirectIncome = array(
                                                    'user_id' => $sponser['user_id'],
                                                    'amount' => $direct_income,
                                                    'type' => 'direct_income',
                                                    'description' => 'Direct Income from Activation of Member ' . $user_id . ' with package ' . $activationAmount,
                                                );
                                                add('tbl_income_wallet', $DirectIncome);

                                            endif;  // DIRECT  STOP ENDIF
                                        }
                                        if (level_access == 0) : // LEVEL START IF
                                            $level_income = '1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1';
                                            $this->level_income($sponser['sponser_id'], $user['user_id'], $level_income, $activationAmount);
                                        endif; // LEVEL STOP ENDIF
                                        $this->updateBusiness($user['sponser_id'], 'team_business', $activationAmount);
                                        $this->updateBusiness($user['sponser_id'], 'team_business_plan', $activationAmount);
                                        $response['status'] = true;
                                        $response['message'] = span_success_simple('Account Activated Successfully');
                                    } else {
                                        $response['message'] = span_info_simple('Errorrrs');
                                    }
                                } else {
                                    $response['message'] = span_info_simple('This Account Already Acitvated');
                                }
                            }
                        } else {
                            $response['message'] = span_danger_simple('Invalid User ID ');
                        }
                    } else {
                        $response['message'] = span_danger_simple(validation_errors());
                    }
                    echo json_encode($response);
                    exit;
                }
            } else {
                /// ------------------------ ACCOUNT UPGRADE WITH PACKAGE ------------------------  ///
                if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    $response['status'] = false;
                    $response['token'] = $this->security->get_csrf_hash();
                    $data = $this->security->xss_clean($this->input->post());
                    $this->form_validation->set_rules('user_id', 'User ID', 'required|trim');
                    $this->form_validation->set_rules('package_id', 'Package ID', 'required|trim|numeric');
                    if ($this->form_validation->run() != FALSE) {
                        $activationProcess = false;
                        $user_id = $data['user_id'];
                        $user = get_single_record('tbl_users', array('user_id' => $user_id), '*');
                        $walletTable = get_single_record('tbl_wallet', array('user_id' => $this->session->userdata['user_id']), 'ifnull(sum(amount),0) as wallet_balance');
                        $package = get_single_record('tbl_package', ['id' => $data['package_id']], '*');

                        $activationAmount =  $package['price']; //$data['amount']; 
                        $package_id = $package['id'];
                        if ($walletTable['wallet_balance'] >= $activationAmount) {
                            $activationProcess = true;
                        } else {
                            $response['message'] =  span_danger_simple('Insuffcient Balance!');
                        }

                        if (!empty($user)) {
                            if ($activationAmount > $user['package_amount']) {
                                if ($activationProcess == true) {
                                    $tokenValue = get_single_record('tbl_token_value', ['id' => 1], 'amount');

                                    if ($user['paid_status'] == 1) {
                                        $sendWallet = array(
                                            'user_id' => $this->session->userdata['user_id'],
                                            'amount' => -$activationAmount,
                                            'type' => 'upgrade_account',
                                            'remark' => 'Upgrade Account Deduction for ' . $user_id,
                                        );
                                        add('tbl_wallet', $sendWallet);
                                        $topupData = array(
                                            'paid_status' => 1,
                                            'package_id' => $package_id,
                                            'package_amount' => $activationAmount,
                                            'total_package' => $user['total_package'] + $activationAmount,
                                            'topup_date' => date('Y-m-d H:i:s'),
                                            'capping' => $activationAmount * 0.5,
                                            'direct_percent' => $package['direct_percent'],
                                        );
                                        update('tbl_users', array('user_id' => $user_id), $topupData);

                                        $activationData = [
                                            'user_id' => $user['user_id'],
                                            'activater' => $this->session->userdata['user_id'],
                                            'package' => $activationAmount,
                                            'topup_date' => date('Y-m-d H:i:s'),
                                            'type' => $user['package_amount'] == 0 ? 'activation' : 'upgradation',
                                        ];
                                        $res =  add('tbl_activation_details', $activationData);
                                        if ($res) {
                                            if ($user['paid_status'] == 0) {
                                                $this->User_model->update_directs($user['sponser_id']);
                                            }

                                          
                                            if (roi_access == 0) : // ROI START IF 
                                                $roiArr = array(
                                                    'user_id' => $user['user_id'],
                                                    'amount' => $package['commision'] * $package['days'] ,
                                                    'roi_amount' => $package['commision'],
                                                    'days' => $package['days'],
                                                    'total_days' => $package['days'],
                                                    'package' => $activationAmount,
                                                    // 'token_price' => $tokenValue['amount'],
                                                    // 'coin' => $activationAmount / $tokenValue['amount'],
                                                    'type' => 'roi_income',
                                                    'creditDate' => date('Y-m-d'),
                                                );
                                                add('tbl_roi', $roiArr);
                                            endif; 
                                            $sponser = get_single_record('tbl_users', array('user_id' => $user['sponser_id']), '*');
                                            if (!empty($sponser['user_id']) && $sponser['paid_status'] == 1) {
                                                if (direct_access == 0) : // DIRECT START IF
                                                    // $checkCapping = get_single_record('tbl_income_wallet',['user_id' =>$sponser['user_id'],'date(created_at)' => $date,'amount >' => 0,'type!=' =>'withdraw_request'],'ifnull(sum(amount),0) as total');
                                                    $direct_income = $activationAmount * $sponser['direct_percent'] / 100;
                                                    // $direct_income = $activationAmount * $package['direct_income'];
                                                    // if($checkCapping['total']+$direct_income < $sponser['capping']){
                                                    $DirectIncome = array(
                                                        'user_id' => $sponser['user_id'],
                                                        'amount' => $direct_income,
                                                        'type' => 'direct_income',
                                                        'description' => 'Direct Income from Upgradation of Member ' . $user_id . ' with package ' . $activationAmount,
                                                    );
                                                    add('tbl_income_wallet', $DirectIncome);
                                                //  }
                                                endif;  // DIRECT  STOP ENDIF
                                            }
                                            if (level_access == 0) : // LEVEL START IF
                                                $this->level_income($sponser['sponser_id'], $user['user_id'], $package['level_income'], $activationAmount);
                                            endif; // LEVEL STOP ENDIF

                                            $response['status'] = true;
                                            $response['message'] = span_success_simple('Account Upgrade Successfully');
                                            $this->updateBusiness($user['sponser_id'], 'team_business', $activationAmount);
                                            $this->updateBusiness($user['sponser_id'], 'team_business_plan', $activationAmount);
                                            $this->update_business($user['user_id'], $user['user_id'], $level = 1, $package['price'], $package['price'], $type = 'topup', '');
                                        } else {
                                            $response['message'] = span_info_simple('Errorrrs');
                                        }
                                    } else {
                                        $response['message'] = span_info_simple('Please First Acitvate This Account');
                                    }
                                }
                            } else {
                                $response['message'] = span_danger_simple('Please upgrade this account with more than ' . $user['package_amount'] . ' Package');
                            }
                        } else {
                            $response['message'] = span_danger_simple('Invalid User ID ');
                        }
                    } else {
                        $response['message'] = span_danger_simple(validation_errors());
                    }
                    echo json_encode($response);
                    exit;
                }
            }
        } else {
            $response = ['status' => false, 'message' => 'Please login first!'];
            echo json_encode($response);
            exit;
        }
    }

    private function level_income($sponser_id, $activated_id, $package_income, $package)
    {
        $incomeArr = explode(',', $package_income);

        foreach ($incomeArr as $key => $income) {
            $level = $key + 1;
            $direct = $key + 1;
            $sponser = get_single_record('tbl_users', array('user_id' => $sponser_id), 'id,user_id,sponser_id,paid_status,package_amount,directs');
            if (!empty($sponser)) {
                if ($sponser['paid_status'] == 1) {
                    if ($sponser['directs'] >= $direct) {

                        $level_income = $income * $package / 100;

                        $LevelIncome = array(
                            'user_id' => $sponser['user_id'],
                            'amount' => $level_income,
                            'type' => 'level_income',
                            'description' => 'Level Income from Activation of Member (' . currency . $package . ') ' . $activated_id . ' At level ' . ($level),
                        );
                        add('tbl_income_wallet', $LevelIncome);
                    }
                }
                $sponser_id = $sponser['sponser_id'];
            }
        }
    }

    public function updateRank($user_id)
    {
        $rankArr = [
            1 => ['business' => 1500, 'commission' => 5, 'rank' => 1],
            2 => ['business' => 5000, 'commission' => 7, 'rank' => 2],
            3 => ['business' => 15000, 'commission' => 9, 'rank' => 3],
            4 => ['business' => 40000, 'commission' => 11, 'rank' => 4],
            5 => ['business' => 75000, 'commission' => 13, 'rank' => 5],
            6 => ['business' => 200000, 'commission' => 15, 'rank' => 6],
            7 => ['business' => 500000, 'commission' => 18, 'rank' => 7],
            8 => ['business' => 1000000, 'commission' => 21, 'rank' => 8],
            9 => ['business' => 5000000, 'commission' => 23, 'rank' => 9],
            10 => ['business' => 10000000, 'commission' => 25, 'rank' => 10],
        ];
        $userinfo = get_single_record('tbl_users', ['user_id' => $user_id], 'rank,team_business,sponser_id');
        foreach ($rankArr as $rank) {
            if ($rank['rank'] > $userinfo['rank'] && $userinfo['team_business'] >= $rank['business']) {
                $this->db->query("UPDATE tbl_users SET rank = '" . $rank['rank'] . "' WHERE user_id = '$user_id'");
            }
        }
        if (!empty($userinfo['sponser_id'])) {
            $this->updateRank($userinfo['sponser_id']);
        }
    }

    private function generationIncome($user_id, $package, $linkedID)
    {
        $user = get_single_record('tbl_users', ['user_id' => $user_id], 'user_id,package_amount');
        if (!empty($user['user_id'])) {
            $this->addIncome($user['user_id'], $package, 0, 0, $linkedID);
        }
    }

    private function addIncome($user_id, $Business, $rank, $commission, $linkedID)
    {
        $slabArr = [
            1 => ['commission' => 5],
            2 => ['commission' => 7],
            3 => ['commission' => 9],
            4 => ['commission' => 11],
            5 => ['commission' => 13],
            6 => ['commission' => 15],
            7 => ['commission' => 18],
            8 => ['commission' => 21],
            9 => ['commission' => 23],
            10 => ['commission' => 25],
        ];
        $userinfo = get_single_record('tbl_users', ['user_id' => $user_id], 'sponser_id');
        $tokenValue = get_single_record('tbl_token_value', ['id' => 1], 'amount');

        if (!empty($userinfo['sponser_id'])) {
            $userinfo2 = get_single_record('tbl_users', ['user_id' => $userinfo['sponser_id'], 'rank >' => $rank], 'user_id,sponser_id,team_business,rank');
            $nextRank = $rank;
            if (!empty($userinfo2['user_id'])) {
                // pr($userinfo2);
                $commission = $slabArr[$userinfo2['rank']]['commission'] - $commission;

                if ($userinfo2['rank'] == 1) {
                    $userData = [
                        'user_id' => $userinfo['sponser_id'],
                        'amount' => ($Business * $commission / 100) / $tokenValue['amount'],
                        // 'dollar' => $Business*$commission/100,
                        // 'token_price' => $tokenValue['amount'],
                        'type' => 'roi_level_income',
                        'description' => 'ROI Level Income From User ' . $linkedID . ' with amount ' . $Business,
                    ];
                    $nextRank = $userinfo2['rank'];
                }
                if ($userinfo2['rank'] == 2) {
                    $userData = [
                        'user_id' => $userinfo['sponser_id'],
                        'amount' => ($Business * $commission / 100) / $tokenValue['amount'],
                        // 'dollar' => $Business*$commission/100,
                        // 'token_price' => $tokenValue['amount'],
                        'type' => 'roi_level_income',
                        'description' => 'ROI Level Income From User ' . $linkedID . ' with amount ' . $Business,
                    ];
                    $nextRank = $userinfo2['rank'];
                }
                if ($userinfo2['rank'] == 3) {
                    $userData = [
                        'user_id' => $userinfo['sponser_id'],
                        'amount' => ($Business * $commission / 100) / $tokenValue['amount'],
                        // 'dollar' => $Business*$commission/100,
                        // 'token_price' => $tokenValue['amount'],
                        'type' => 'roi_level_income',
                        'description' => 'ROI Level Income From User ' . $linkedID . ' with amount ' . $Business,
                    ];
                    $nextRank = $userinfo2['rank'];
                }
                if ($userinfo2['rank'] == 4) {
                    $userData = [
                        'user_id' => $userinfo['sponser_id'],
                        'amount' => ($Business * $commission / 100) / $tokenValue['amount'],
                        // 'dollar' => $Business*$commission/100,
                        // 'token_price' => $tokenValue['amount'],
                        'type' => 'roi_level_income',
                        'description' => 'ROI Level Income From User ' . $linkedID . ' with amount ' . $Business,
                    ];
                    $nextRank = $userinfo2['rank'];
                }
                if ($userinfo2['rank'] == 5) {
                    $userData = [
                        'user_id' => $userinfo['sponser_id'],
                        'amount' => ($Business * $commission / 100) / $tokenValue['amount'],
                        // 'dollar' => $Business*$commission/100,
                        // 'token_price' => $tokenValue['amount'],
                        'type' => 'roi_level_income',
                        'description' => 'ROI Level Income From User ' . $linkedID . ' with amount ' . $Business,
                    ];
                    $nextRank = $userinfo2['rank'];
                }
                if ($userinfo2['rank'] == 6) {
                    $userData = [
                        'user_id' => $userinfo['sponser_id'],
                        'amount' => ($Business * $commission / 100) / $tokenValue['amount'],
                        // 'dollar' => $Business*$commission/100,
                        // 'token_price' => $tokenValue['amount'],
                        'type' => 'roi_level_income',
                        'description' => 'ROI Level Income From User ' . $linkedID . ' with amount ' . $Business,
                    ];
                    $nextRank = $userinfo2['rank'];
                }
                if ($userinfo2['rank'] == 7) {
                    $userData = [
                        'user_id' => $userinfo['sponser_id'],
                        'amount' => ($Business * $commission / 100) / $tokenValue['amount'],
                        // 'dollar' => $Business*$commission/100,
                        // 'token_price' => $tokenValue['amount'],
                        'type' => 'roi_level_income',
                        'description' => 'ROI Level Income From User ' . $linkedID . ' with amount ' . $Business,
                    ];
                    $nextRank = $userinfo2['rank'];
                }
                if ($userinfo2['rank'] == 8) {
                    $userData = [
                        'user_id' => $userinfo['sponser_id'],
                        'amount' => ($Business * $commission / 100) / $tokenValue['amount'],
                        // 'dollar' => $Business*$commission/100,
                        // 'token_price' => $tokenValue['amount'],
                        'type' => 'roi_level_income',
                        'description' => 'ROI Level Income From User ' . $linkedID . ' with amount ' . $Business,
                    ];
                    $nextRank = $userinfo2['rank'];
                }
                if ($userinfo2['rank'] == 9) {
                    $userData = [
                        'user_id' => $userinfo['sponser_id'],
                        'amount' => ($Business * $commission / 100) / $tokenValue['amount'],
                        // 'dollar' => $Business*$commission/100,
                        // 'token_price' => $tokenValue['amount'],
                        'type' => 'roi_level_income',
                        'description' => 'ROI Level Income From User ' . $linkedID . ' with amount ' . $Business,
                    ];
                    $nextRank = $userinfo2['rank'];
                }
                if ($userinfo2['rank'] == 10) {
                    $userData = [
                        'user_id' => $userinfo['sponser_id'],
                        'amount' => ($Business * $commission / 100) / $tokenValue['amount'],
                        // 'dollar' => $Business*$commission/100,
                        // 'token_price' => $tokenValue['amount'],
                        'type' => 'roi_level_income',
                        'description' => 'ROI Level Income From User ' . $linkedID . ' with amount ' . $Business,
                    ];
                    $nextRank = $userinfo2['rank'];
                }
                if ($userData['amount'] > 0) {

                    $userData['amount'] = $userData['amount'];

                    add('tbl_income_wallet', $userData);
                }
                $commission = $slabArr[$userinfo2['rank']]['commission'];
            }
            $this->addIncome($userinfo['sponser_id'], $Business, $nextRank, $commission, $linkedID);
        }
    }

    ///--------------Generation Income--------------------------

    private function update_business($user_name, $downline_id, $level, $power, $business, $type, $point)
    {
        $user = get_single_record('tbl_users', array('user_id' => $user_name), $select = 'upline_id , position,user_id');
        if (!empty($user)) {
            if ($user['position'] == 'L') {
                $c = 'leftPower';
                $d = 'leftBusiness';
            } else if ($user['position'] == 'R') {
                $c = 'rightPower';
                $d = 'rightBusiness';
            } else {
                return;
            }
            $this->User_model->update_business($c, $user['upline_id'], $power);
            $this->User_model->update_business($d, $user['upline_id'], $business);
            $downlineArray = array(
                'user_id' => $user['upline_id'],
                'downline_id' => $downline_id,
                'position' => $user['position'],
                'business' => $business,
                'type' => $type,
                'created_at' => date('Y-m-d H:i:s'),
                'level' => $level,
            );
            add('tbl_downline_business', $downlineArray);

            $user_name = $user['upline_id'];

            if ($user['upline_id'] != '') {
                $this->update_business($user_name, $downline_id, $level + 1, $power, $business, $type, $point);
            }
        }
    }

    protected function globlePoolEntry($user_id, $table, $org)
    {
        if ($table == 'tbl_pool') {
            $amount = 20;
        } elseif ($table == 'tbl_pool2') {
            $amount = 400;
        } elseif ($table == 'tbl_pool3') {
            $amount = 2000;
        } elseif ($table == 'tbl_pool4') {
            $amount = 5000;
        }
        $pool_upline = get_single_record($table, array('down_count <' => 2, 'org' => $org), 'id,user_id,down_count');
        if (!empty($pool_upline)) {
            $poolArr =  array(
                'user_id' => $user_id,
                'upline_id' => $pool_upline['user_id'],
                'org' => $org,
            );
            add($table, $poolArr);
            update($table, array('id' => $pool_upline['id'], 'org' => $org), array('down_count' => $pool_upline['down_count'] + 1));
            $this->globalUpdateTeam($user_id, $table, $org);
            $this->poolIncome2($table, $user_id, $user_id, $org);
        } else {
            $poolArr =  array(
                'user_id' => $user_id,
                'upline_id' => '',
                'org' => $org,
            );
            add($table, $poolArr);
            $this->globalUpdateTeam($user_id, $table, $org);
            $this->poolIncome2($table, $user_id, $user_id, $org);
        }
    }

    protected function globalUpdateTeam($user_id, $table, $org)
    {
        $uplineID = get_single_record($table, array('user_id' => $user_id, 'org' => $org), 'upline_id');
        if (!empty($uplineID['upline_id'])) {
            $team = get_single_record($table, array('user_id' => $uplineID['upline_id'], 'org' => $org), 'team');
            $newTeam = $team['team'] + 1;
            update($table, array('user_id' => $uplineID['upline_id'], 'org' => $org), array('team' => $newTeam));
            $this->globalUpdateTeam($uplineID['upline_id'], $table, $org);
        }
    }

    private function poolIncome2($table, $user_id, $linkedID, $org)
    {
        $poolDetails = $this->poolDetails($table);
        $poolData = $poolDetails[$org];
        $upline = get_single_record($table, ['user_id' => $user_id], ['upline_id']);
        if (!empty($upline['upline_id'])) {
            $checkTeam = get_single_record($table, ['user_id' => $upline['upline_id']], 'team');
            if ($checkTeam['team'] == 2) {
                $creditIncome = [
                    'user_id' => $upline['upline_id'],
                    'amount' => $poolData['amount'],
                    'type' => 'pool_income',
                    'description' => 'Pool Income from level ' . $org,
                ];
                add('tbl_income_wallet', $creditIncome);
                $creditIncome = [
                    'user_id' => $upline['upline_id'],
                    'amount' => -$poolData['amount'],
                    'type' => 'upgrade_deduction',
                    'description' => 'Pool Upgrade Deduction',
                ];
                add('tbl_income_wallet', $creditIncome);
                $orgNext = $org + 1;
                $this->globlePoolEntry($upline['upline_id'], $table, $orgNext);
            }
        }
    }

    // public function test($table,$org){
    //     $poolDetails = $this->poolDetails($table);
    //     $poolData = $poolDetails[$org];
    //     pr($poolData);
    // }

    private function poolDetails($table)
    {
        $poolArr = [
            'tbl_pool' => [
                1 => ['amount' => 20],
                2 => ['amount' => 40],
                3 => ['amount' => 80],
                4 => ['amount' => 160],
                5 => ['amount' => 320],
            ],
            'tbl_pool2' => [
                1 => ['amount' => 400],
                2 => ['amount' => 800],
                3 => ['amount' => 1600],
                4 => ['amount' => 3200],
                5 => ['amount' => 6400],
            ],
            'tbl_pool3' => [
                1 => ['amount' => 2000],
                2 => ['amount' => 4000],
                3 => ['amount' => 8000],
                4 => ['amount' => 16000],
                5 => ['amount' => 32000],
            ],
            'tbl_pool4' => [
                1 => ['amount' => 5000],
                2 => ['amount' => 10000],
                3 => ['amount' => 20000],
                4 => ['amount' => 40000],
                5 => ['amount' => 80000],
            ],
        ];

        return $poolArr[$table];
    }

    protected function individualPoolEntry($user_id, $table)
    {
        if ($table == 'tbl_pool1') {
            $org = 1;
            $amount = 100;
        } elseif ($table == 'tbl_pool2') {
            $org = 2;
            $amount = 200;
        } elseif ($table == 'tbl_pool3') {
            $org = 3;
            $amount = 400;
        } elseif ($table == 'tbl_pool4') {
            $org = 4;
            $amount = 800;
        } elseif ($table == 'tbl_pool5') {
            $org = 5;
            $amount = 1600;
        } elseif ($table == 'tbl_pool6') {
            $org = 6;
            $amount = 3200;
        } elseif ($table == 'tbl_pool7') {
            $org = 7;
            $amount = 6400;
        } elseif ($table == 'tbl_pool8') {
            $org = 7;
            $amount = 12800;
        } elseif ($table == 'tbl_pool9') {
            $org = 7;
            $amount = 25600;
        } elseif ($table == 'tbl_pool10') {
            $org = 7;
            $amount = 51200;
        }
        $sponsorID = get_single_record('tbl_users', ['user_id' => $user_id], 'sponser_id');
        $pool_upline = get_single_record($table, array('user_id' => $sponsorID['sponser_id'], 'down_count <' => 3), 'user_id');
        //pr($pool_upline,true);
        if ($pool_upline['user_id'] == '') {
            $uplineID = $this->get_pool_upline($sponsorID['sponser_id'], $table, $org);
        } else {
            $uplineID = $pool_upline['user_id'];
        }
        $userinfo = get_single_record($table, ['user_id' => $uplineID], 'down_count');
        $poolArr = [
            'user_id' => $user_id,
            'upline_id' => $uplineID,
        ];
        //pr($poolArr,true);
        add($table, $poolArr);
        update($table, array('user_id' => $uplineID), ['down_count' => ($userinfo['down_count'] + 1)]);
        $this->updateTeam($user_id, $table);
        $this->update_pool_downline($uplineID, $user_id, $level = 1, $table, $org);
        $this->poolIncome($table, $user_id, $user_id, $org, 3, 1, $amount);
    }

    protected function updateTeam($user_id, $table, $org)
    {
        $uplineID = get_single_record($table, array('user_id' => $user_id, 'org' => $org), 'upline_id');
        if (!empty($uplineID['upline_id'])) {
            $team = get_single_record($table, array('user_id' => $uplineID['upline_id'], 'org' => $org), 'team');
            $newTeam = $team['team'] + 1;
            update($table, array('user_id' => $uplineID['upline_id'], 'org' => $org), array('team' => $newTeam));
            $this->updateTeam($uplineID['upline_id'], $table, $org);
        }
    }

    public function update_pool_downline($upline_id, $user_id, $level, $table, $org)
    {
        $user = get_single_record($table, array('user_id' => $upline_id), $select = 'user_id,upline_id');
        if (!empty($user['user_id'])) {
            $pool_downArr = [
                'user_id' => $user['user_id'],
                'downline_id' => $user_id,
                'level' => $level,
                'org' => $org,
            ];
            add('tbl_pool_downline', $pool_downArr);
            $this->update_pool_downline($user['upline_id'], $user_id, $level + 1, $table, $org);
        }
    }

    private function poolIncome($table, $user_id, $linkedID, $org, $team, $level, $amount)
    {
        $upline = get_single_record($table, ['user_id' => $user_id], ['upline_id']);

        if (!empty($upline['upline_id'])) {
            $checkTeam = get_single_record('tbl_pool_downline', ['user_id' => $upline['upline_id'], 'level' => $level, 'org' => $org], 'count(id) as team');
            if ($checkTeam['team'] == $team) {
                $creditSIncome = [
                    'user_id' => $upline['upline_id'],
                    'amount' => $amount,
                    'type' => 'working_pool',
                    'description' => 'Working Pool Income from User ' . $linkedID,
                ];
                add('tbl_income_wallet', $creditSIncome);

                $debitIncome = [
                    'user_id' => $upline['upline_id'],
                    'amount' => -$amount,
                    'type' => 'upgradation_deduction',
                    'description' => 'Working Pool Income from User ' . $linkedID,
                ];
                add('tbl_income_wallet', $debitIncome);
            } else {
                $creditIncome = [
                    'user_id' => $upline['upline_id'],
                    'amount' => $amount,
                    'type' => 'working_pool',
                    'description' => 'Working Pool upgradation deduction',
                ];
                add('tbl_income_wallet', $creditIncome);
            }
            $level += 1;
            $team *= 3;
            $this->poolIncome($table, $upline['upline_id'], $linkedID, $org, $team, $level, $amount);
        }
    }


    private function getSponsor($user_id, $table)
    {
        $users = get_records('tbl_sponser_count', "downline_id = '" . $user_id . "' and user_id != 'none' ORDER BY level ASC", 'user_id');
        foreach ($users as $user) {
            $check = get_single_record($table, ['user_id' => $user['user_id']], 'user_id');
            if (!empty($check['user_id'])) {
                $check2 = get_single_record($table, ['user_id' => $user['user_id'], 'down_count <' => 3], 'user_id');
                $this->exceptionCase = $check2['user_id'];
                if (!empty($check2['user_id'])) {
                    return $check2['user_id'];
                    break;
                }
            }
        }
    }

    private function get_pool_upline($sponser_id, $table, $org)
    {
        $users = get_records('tbl_pool_downline', "user_id = '" . $sponser_id . "' and org = '" . $org . "' ORDER BY level,created_at ASC", 'downline_id');
        if (!empty($users)) {
            foreach ($users as $key => $user) {
                $check = get_single_record($table, ['user_id' => $user['downline_id'], 'down_count <' => 3], 'user_id');
                if (!empty($check['user_id'])) {
                    return $check['user_id'];
                    break;
                }
            }
        } else {
            $sponsorID = $this->getSponsor($sponser_id, $table);
            if (!empty($sponsorID)) {
                return $sponsorID;
            } else {
                return $this->get_pool_upline($this->exceptionCase, $table, $org);
            }
        }
    }


    private function updateBusiness($user_id, $field, $business)
    {
        $userinfo = get_single_record('tbl_users', ['user_id' => $user_id], 'user_id,sponser_id');
        if (!empty($userinfo['user_id']) && $userinfo['user_id'] != 'none') {
            $this->User_model->update_business($field, $userinfo['user_id'], $business);
            $this->updateBusiness($userinfo['sponser_id'], $field, $business);
        }
    }


    public function userExist($csrf, $eth_address)
    {
        // sk (Tony)
        if ($this->input->is_ajax_request()) {
            if (!empty($csrf)) {
                if ($csrf == $this->security->get_csrf_hash()) {
                    $user = get_single_record('tbl_users', ['user_id' =>  $this->session->userdata['user_id']], '*');
                    if (!empty($user)) {
                        $response['success'] = 1;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Invaild Wallet Address/Not Registered!';
                    }
                } else {
                    $response['success'] = 0;
                    $response['message'] = 'Invaild Token';
                }
            } else {
                $response['success'] = 0;
                $response['message'] = 'Invaild Token';
            }
        } else {
            $response['success'] = 0;
            $response['message'] = 'Do not hit with direct script!';
        }
        echo json_encode($response);
        exit();
    }


    public function checkPackage($csrf, $package)
    {
        date_default_timezone_set("Asia/Calcutta");
        if ($this->input->is_ajax_request()) {
            if (!empty($csrf)) {
                if ($csrf == $this->security->get_csrf_hash()) {
                    // if($month == 1 || $month == 7 || $month == 30 || $month == 180){
                    // $cal = $package % 1;
                    if ($package >= 1) {
                        $response['success'] = 1;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Minimum Staking 50USDT!';
                    }
                    // }else{
                    //     $response['success'] = 0;
                    //     $response['message'] = 'Invaild Days Selected!';
                    // }
                } else {
                    $response['success'] = 0;
                    $response['message'] = 'Invaild Token';
                }
            } else {
                $response['success'] = 0;
                $response['message'] = 'Invaild Token';
            }
        } else {
            $response['success'] = 0;
            $response['message'] = 'Do not hit with direct script!';
        }
        echo json_encode($response);
        exit();
    }


    public function accountHistory()
    {
        if (is_logged_in()) {
            $response['header'] = 'Activation History';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = ['activater' => $this->session->userdata['user_id']];
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_activation_details', $where, '*', 'dashboard/activate-history', 3, 10);
            $response['path'] =  $records['path'];
            $response['field'] = '';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Activator</th>
                                <th>Package</th>
                                <th>Type</th>
                                <th>Date</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $activater . '</td>
                                <td>' . $package . '</td>
                                <td>' . $type . '</td>
                                <td>' . $created_at . '</td>
                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $this->load->view('reports', $response);
        } else {
            redirect('login');
        }
    }
}
