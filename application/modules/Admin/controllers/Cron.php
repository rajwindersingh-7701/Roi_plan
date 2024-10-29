<?php

use phpDocumentor\Reflection\Types\Null_;

defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email'));
        $this->load->model(array('Main_model'));
        $this->load->helper(array('admin', 'security', 'super'));
        date_default_timezone_set('Asia/Kolkata');
    }

    public function empty_Data()
    {
        // die('Data Not Empty');
        $this->Main_model->deleteCron('tbl_withdraw', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_wallet', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_users', ['id !=' => 1]);
        $this->Main_model->deleteCron('tbl_bank_details', ['id !=' => 1]);
        $this->Main_model->deleteCron('tbl_support_message', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_pool', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_pool2', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_pool3', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_pool4', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_income_wallet', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_activation_details', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_cron', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_sponser_count', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_sms_counter', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_roi', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_rewards', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_downline_count', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_downline_business', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_point_matching_income', ['id !=' => 0]);
        $this->Main_model->deleteCron('tbl_payment_request', ['id !=' => 0]);

        $userDtaa = array(
            'user_id' => 'admin',
            'name' => 'administrator',
            'directs' => '0',
            'package_amount' => '0',
            'total_package' => '0',
            'team_business' => '0',
            'paid_status' => '0',
            'email' => 'admin@gmail.com',
            'phone' => '0987654321',
            'left_node' => '',
            'right_node' => '',
            'last_left' => 'admin',
            'last_right' => 'admin',
            'left_count' => '0',
            'right_count' => '0',
            'leftPower' => '0',
            'rightPower' => '0',
            'leftBusiness' => '0',
            'rightBusiness' => '0',
            'team_business_plan' => '0',
            'team_business' => '0',
        );
        $this->Main_model->update('tbl_users', ['id' => 1], $userDtaa);
        $userDtaa = array(
            'user_id' => 'admin',

        );
        $this->Main_model->update('tbl_bank_details', ['id' => 1], $userDtaa);
        echo 'data empty done';
    }

    public function index()
    {
        die;
        $users = $this->Main_model->get_records('tbl_users', [], 'user_id');

        // $users = $this->Main_model->get_records('tbl_income_wallet',"amount > '0' GROUP BY user_id",'ifnull(sum(amount),0) as total,user_id');
        foreach ($users as $user) {
            $this->add_counts($user['user_id'], $user['user_id'], 1);
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
            $this->Main_model->update_count($c, $user['upline_id']);
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

    public function freeUser()
    {
        $users = $this->Main_model->get_records('tbl_users', ['paid_status' => 1], 'user_id,package_amount,topup_date');
        foreach ($users as $user) :
            $cycleData = $this->Main_model->get_single_record('tbl_deactivation_details', ['user_id' => $user['user_id']], 'count(id) as record');
            $userinfo = $this->Main_model->get_single_record('tbl_income_wallet', ['user_id' => $user['user_id'], 'created_at >=' => $user['topup_date']], 'ifnull(sum(amount),0) as balance');

            $incomeLimit = $user['package_amount'] * 3;
            if ($userinfo['balance'] >= $incomeLimit) {
                $deactive = [
                    'paid_status' => 0,
                    'package_id' => 0,
                    'package_amount' => 0,
                    'topup_date' => '0000-00-00 00:00:00',
                    'incomeLimit2' => 0,
                ];
                $this->Main_model->update('tbl_users', ['user_id' => $user['user_id']], $deactive);
                $activeData = [
                    'user_id' => $user['user_id'],
                    'deactivater' => 'auto',
                    'package' => $user['package_amount'],
                    'topup_date' => $user['topup_date'],
                ];
                pr($activeData);
                $this->Main_model->add('tbl_deactivation_details', $activeData);
            }
        endforeach;
    }

    public function sapphireIncome()
    {
        $date1 = date('Y-m-d');
        $cron = $this->Main_model->get_single_record('tbl_cron', ['date' => $date1, 'cron_name' => 'sapphireIncome'], '*');
        if (empty($cron)) {
            $this->Main_model->add('tbl_cron', ['cron_name' => 'sapphireIncome', 'date' => $date1]);
            $date = date('Y-m-d', strtotime(date('Y-m-d') . ' 0 days'));
            $users = $this->Main_model->get_records('tbl_income_wallet', "amount > '0' and type != 'direct_sponsor_leadership' and date(created_at) = '" . $date . "' GROUP BY user_id", 'ifnull(sum(amount),0) as todayIncome,user_id');
            foreach ($users as $key => $user) {
                if ($user['todayIncome'] > 0) {
                    pr($user);
                    $getSponsor = $this->Main_model->get_single_record('tbl_users', array('user_id' => $user['user_id']), 'user_id,sponser_id');
                    if (!empty($getSponsor)) {
                        $perID = $user['todayIncome'] * 0.05;
                        $incomeArr = array(
                            'user_id' => $getSponsor['sponser_id'],
                            'amount' => $perID,
                            'type' => 'direct_sponsor_leadership',
                            'description' => 'Direct Sponsor Leadership Income From User ' . $user['user_id'],
                        );
                        pr($incomeArr);
                        $this->Main_model->add('tbl_income_wallet', $incomeArr);
                    }
                }
            }
        } else {
            echo 'Today Cron already run';
        }
    }

    public function roiCron()
    {
        if (date('D') != 'Sun') {
        
            $date = date('Y-m-d');
            $cron = $this->Main_model->get_single_record('tbl_cron', ['date' => $date, 'cron_name' => 'roiCron'], '*');
            if (empty($cron)) {
                $this->Main_model->add('tbl_cron', ['cron_name' => 'roiCron', 'date' => $date]);
                $roi_users = $this->Main_model->get_records('tbl_roi', array('days >' => 0), '*');
                $tokenValue = $this->Main_model->get_single_record('tbl_token_value', ['id' => 1], 'amount');
                foreach ($roi_users as $key => $user) {
                    $date1 = date('Y-m-d H:i:s');
                    $date2 = date('Y-m-d H:i:s', strtotime($user['creditDate'] . '+ 0 days'));
                    $diff = strtotime($date1) - strtotime($date2);
                    echo $diff . ' / ' . $user['user_id'] . '<br>';
                    if ($diff >= 0) {
                        $userinfo = $this->Main_model->get_single_record('tbl_users', ['user_id' => $user['user_id']], '*');
                        // if ($userinfo['paid_status'] == 1) {

                        //  $checkCapping = $this->Main_model->get_single_record('tbl_income_wallet',['user_id' =>$userinfo['user_id'],'date(created_at)' => $date,'amount >' => 0,'type!=' =>'withdraw_request'],'ifnull(sum(amount),0) as total');
                        // $roi_amount = $user['roi_amount'];
                        // if($user['days'] <= 315){
                        //     $roi_amount = $user['package']*0.006;
                        // }else{
                        $roi_amount = $user['roi_amount'];
                        // }

                        // if($user['days'] > 315){
                        //     $roi_amount = $user['roi_amount'];
                        // }elseif($user['days'] <= 315 && $user['days'] > 290){
                        //     $roi_amount = $user['package']*0.006;
                        // }else{
                        //     $roi_amount = $user['package']*0.003;
                        // }
                        // if($checkCapping['total']+$roi_amount < $userinfo['capping']){
                        $new_day = $user['days'] - 1;
                        $days = ($user['total_days'] + 1) - $user['days'];
                        $incomeArr = array(
                            'user_id' => $user['user_id'],
                            'amount' => $roi_amount,
                            'type' => 'roi_income',
                            'description' => 'Daily ' . ucwords(str_replace('_', ' ', $user['type'])) . ' Income at day ' . $days,
                        );
                        pr($incomeArr);
                        $this->Main_model->add('tbl_income_wallet', $incomeArr);
                        // $this->Main_model->update('tbl_users', ['user_id' => $user['user_id']], ['incomeLimit' => ($userinfo['incomeLimit'] + $incomeArr['amount'])]);
                        $this->Main_model->update('tbl_roi', array('id' => $user['id']), array('days' => $new_day, 'amount' => ($user['amount'] - $user['roi_amount']), 'creditDate' => date('Y-m-d')));
                        $sponsor = $this->Main_model->get_single_record('tbl_users', ['user_id' => $user['user_id']], 'sponser_id');
                        $this->roiLevelIncome($sponsor['sponser_id'], $user['user_id'], $roi_amount);
                        //  }


                    }
                }
                // }
            } else {
                echo 'Today cron already run';
            }
        }else{
            echo 'Sunday Income Off';
        }    
    }

    private function roiLevelIncome($user_id, $linkedID, $amount)
    {
        for ($i = 1; $i <= 50; $i++) {
            if ($i == 1) {
                $incomeArr[$i] = ['amount' => 0.1, 'direct' => 0];
            } elseif ($i == 2) {
                $incomeArr[$i] = ['amount' => 0.1, 'direct' => 0];
            } elseif ($i == 3) {
                $incomeArr[$i] = ['amount' => 0.05, 'direct' => 0];
            } elseif ($i == 4) {
                $incomeArr[$i] = ['amount' => 0.04, 'direct' => 0];
            } elseif ($i == 5) {
                $incomeArr[$i] = ['amount' => 0.03, 'direct' => 0];
            } elseif ($i >= 6 && $i <= 20) {
                $incomeArr[$i] = ['amount' => 0.02, 'direct' => 0];
            } elseif ($i >= 21 && $i <= 30) {
                $incomeArr[$i] = ['amount' => 0.01, 'direct' => 0];
            } elseif ($i >= 31 && $i <= 50) {
                $incomeArr[$i] = ['amount' => 0.005, 'direct' => 0];
            }
        }
        $tokenValue = $this->Main_model->get_single_record('tbl_token_value', ['id' => 1], 'amount');
        foreach ($incomeArr as $key => $income) :
            //$direct = $directArr[$key];
            $userinfo = $this->Main_model->get_single_record('tbl_users', ['user_id' => $user_id], 'user_id,sponser_id,directs,total_limit,pending_limit,total_package,paid_status');
            if (!empty($userinfo['user_id'])) :
                $directs = $this->Main_model->get_single_record('tbl_users', ['sponser_id' => $user_id, 'total_package >=' => $userinfo['total_package']], 'count(id) as direct');
                if ($userinfo['paid_status'] == 1) {
                    // if ($directs['direct'] >= $income['direct']) :
                    // if ($userinfo['total_limit'] > $userinfo['pending_limit']) {
                    //     $totalCredit = $userinfo['pending_limit'] + ($amount * $income['amount']);
                    //     if ($totalCredit < $userinfo['total_limit']) {
                    $level_income = ($amount * $income['amount']);
                    //     } else {
                    //         $level_income = $userinfo['total_limit'] - $userinfo['pending_limit'];
                    //     }
                    $creditIncome = [
                        'user_id' => $userinfo['user_id'],
                        'amount' => $level_income,
                        'type' => 'roi_level_income',
                        'description' => 'ROI Level Income from User ' . $linkedID . ' at level ' . $key,
                    ];
                    $this->Main_model->add('tbl_income_wallet', $creditIncome);
                    // $this->Main_model->update('tbl_users', ['user_id' => $userinfo['user_id']], ['pending_limit' => ($userinfo['pending_limit'] + $creditIncome['amount'])]);
                    // }
                    // endif;

                    $user_id = $userinfo['sponser_id'];
                }
            endif;

        endforeach;
    }

    public function boosterCron()
    {
        if (date('D') != 'Sun') {
            $roi_users = $this->Main_model->get_records('tbl_roi', array('amount >' => 0, 'type' => 'direct_booster_income', 'days >' => 0), '*');
            foreach ($roi_users as $key => $user) {
                $date1 = date('Y-m-d H:i:s');
                $date2 = date('Y-m-d H:i:s', strtotime($user['created_at'] . '+ 1 days'));
                $diff = strtotime($date1) - strtotime($date2);
                if ($diff >= 0) {
                    $new_day = $user['days'] - 1;
                    $days = 21 - $user['days'];
                    $incomeArr = array(
                        'user_id' => $user['user_id'],
                        'amount' => $user['roi_amount'],
                        'type' => 'direct_boost_income',
                        'description' => 'Direct Booster Income at ' . $new_day . ' Day',
                    );
                    pr($incomeArr);
                    $this->Main_model->add('tbl_income_wallet', $incomeArr);
                    $this->Main_model->update('tbl_roi', array('id' => $user['id']), array('days' => $new_day, 'amount' => ($user['amount'] - $user['roi_amount'])));
                    $sponsor = $this->Main_model->get_single_record('tbl_users', ['user_id' => $user['user_id']], 'sponser_id');
                    $this->levelIncome($sponsor['sponser_id'], $user['user_id']);
                }
            }
        }
    }

    public function point_match_cron()
    {
        // if(date('D') == 'Sun'){
        $response['users'] = $this->Main_model->get_records('tbl_users', '(leftPower >= 2 and rightPower >= 1 and directs >= 2) OR (leftPower >= 1 and rightPower >= 2  and directs >= 2)', '*');
        foreach ($response['users'] as $user) {
            pr($user);
            // $package = $this->Main_model->get_single_record_desc('tbl_package', array('id' => $user['package_id']), '*');
            $user_match = $this->Main_model->get_single_record_desc('tbl_point_matching_income', array('user_id' => $user['user_id']), '*');
            // $position_directs = $this->Main_model->count_position_directs($user['user_id']);
            $leftDirect = get_single_record('tbl_users', array('sponser_id' => $user['user_id'], 'position' => 'L', 'paid_status' => 1), 'ifnull(count(id),0) as leftDirect');
            $rightDirect = get_single_record('tbl_users', array('sponser_id' => $user['user_id'], 'position' => 'R', 'paid_status' => 1), 'ifnull(count(id),0) as rightDirect');
            if ($rightDirect['rightDirect'] >= 1 && $leftDirect['leftDirect'] >= 1) {
                if (!empty($user_match)) {
                    if ($user['leftPower'] > $user['rightPower']) {
                        $old_income = $user['rightPower'];
                    } else {
                        $old_income = $user['leftPower'];
                    }
                    if ($user_match['left_bv'] > $user_match['right_bv']) {
                        $new_income = $user_match['right_bv'];
                    } else {
                        $new_income = $user_match['left_bv'];
                    }
                    $income = ($old_income - $new_income);
                    $match_bv = $income;
                    $carry_forward = abs($user['leftPower'] - $user['rightPower']);

                    $user_income = $income * 5 / 100;
                    if ($user_income > 0) {
                        $matchArr = array(
                            'user_id' => $user['user_id'],
                            'left_bv' => $user['leftPower'],
                            'right_bv' => $user['rightPower'],
                            'amount' => $user_income,
                            'match_bv' => $match_bv,
                            'carry_forward' => $carry_forward,
                        );
                        $this->Main_model->add('tbl_point_matching_income', $matchArr);
                        if ($user['capping'] < $user_income) {
                            $user_income = $user['capping'];
                        }
                        // if($user['incomeLimit2'] > $user['incomeLimit']){
                        //     $totalCredit = $user['incomeLimit'] + $user_income;
                        //     if($totalCredit < $user['incomeLimit2']){
                        $matching_income = $user_income;
                        // } else {
                        //     $matching_income = $user['incomeLimit2'] - $user['incomeLimit'];
                        // }

                        $incomeArr = array(
                            'user_id' => $user['user_id'],
                            'amount' => $matching_income,
                            'type' => 'binary_matching_income',
                            'description' => 'Point Matching Bonus'
                        );
                        $this->Main_model->add('tbl_income_wallet', $incomeArr);

                        $checkSponser = $this->Main_model->get_single_record('tbl_users', ['user_id' => $user['sponser_id'], 'paid_status' => 1], 'user_id');
                        if (!empty($checkSponser)) {
                            $sponserincomeArr = array(
                                'user_id' => $checkSponser['user_id'],
                                'amount' => $matching_income * 0.05,
                                'type' => 'sponsor_balancing_income',
                                'description' => 'Sponser Balancing Income form ' . $user['user_id'],
                            );
                            //  $this->Main_model->add('tbl_income_wallet', $sponserincomeArr);
                        }
                        //  $this->Main_model->update('tbl_users',['user_id' => $user['user_id']],['incomeLimit' => ($user['incomeLimit'] + $incomeArr['amount'])]);
                        //  }
                        // $this->generation_income($user['sponser_id'],$matching_income, $user['user_id']);

                        pr($matchArr);
                    }
                } else {
                    if ($user['leftPower'] > $user['rightPower']) {
                        $leftPower = $user['leftPower'] - 0;
                        $rightPower = $user['rightPower'];
                    } else {
                        $rightPower = $user['rightPower'] - 0;
                        $leftPower = $user['leftPower'];
                    }
                    if ($leftPower > $rightPower) {
                        $income = $rightPower;
                    } else {
                        $income = $leftPower;
                    }
                    $match_bv = $income;
                    $carry_forward = abs($leftPower - $rightPower);

                    $user_income = $income * 5 / 100;
                    //                echo $user_income;
                    if ($user['capping'] < $user_income) {
                        $user_income = $user['capping'];
                    }
                    $matchArr = array(
                        'user_id' => $user['user_id'],
                        'left_bv' => $user['leftPower'],
                        'right_bv' => $user['rightPower'],
                        'amount' => $user_income,
                        'match_bv' => $match_bv,
                        'carry_forward' => $carry_forward,
                    );
                    $this->Main_model->add('tbl_point_matching_income', $matchArr);
                    // if($user['incomeLimit2'] > $user['incomeLimit']){
                    //     $totalCredit = $user['incomeLimit'] + $user_income;
                    //     if($totalCredit < $user['incomeLimit2']){
                    $matching_income = $user_income;
                    // } else {
                    //     $matching_income = $user['incomeLimit2'] - $user['incomeLimit'];
                    // }

                    $incomeArr = array(
                        'user_id' => $user['user_id'],
                        'amount' => $matching_income,
                        'type' => 'binary_matching_income',
                        'description' => 'Point Matching Bonus'
                    );
                    $this->Main_model->add('tbl_income_wallet', $incomeArr);

                    $checkSponser = $this->Main_model->get_single_record('tbl_users', ['user_id' => $user['sponser_id'], 'paid_status' => 1], 'user_id');
                    if (!empty($checkSponser)) {
                        $sponserincomeArr = array(
                            'user_id' => $checkSponser['user_id'],
                            'amount' => $matching_income * 0.1,
                            'type' => 'sponsor_balancing_income',
                            'description' => 'Sponser Balancing Income form ' . $user['user_id'],
                        );
                        //  $this->Main_model->add('tbl_income_wallet', $sponserincomeArr);
                    }
                    // $this->Main_model->update('tbl_users',['user_id' => $user['user_id']],['incomeLimit' => ($user['incomeLimit'] + $incomeArr['amount'])]);
                    // }
                    //  $this->generation_income($user['sponser_id'],$matching_income, $user['user_id']);

                    pr($matchArr);
                }
            }
        }
        //  }
        //pr($response);
        die('code executed Successfully');
    }

    // public function point_match_cron() {
    //     $response['users'] = $this->Main_model->get_records('tbl_users', '(leftPower >= 1 and rightPower >= 1) OR (leftPower >= 1 and rightPower >= 1)', '*');
    //     $point_users = [];
    //     $pair_counts = 0;
    //     foreach ($response['users']  as $userkey => $user) {
    //         $user_match = $this->Main_model->get_single_record_desc('tbl_point_matching_income', array('user_id' => $user['user_id']), '*');
    //         $position_directs = $this->Main_model->count_position_directs($user['user_id']);
    //       //   if(!empty($position_directs) && count($position_directs) == 2){
    //             if (!empty($user_match)) {
    //                 if ($user['leftPower'] > $user['rightPower']) {
    //                     $old_income = $user['rightPower'];
    //                 } else {
    //                     $old_income = $user['leftPower'];
    //                 }
    //                 if ($user_match['left_bv'] > $user_match['right_bv']) {
    //                     $new_income = $user_match['right_bv'];
    //                 } else {
    //                     $new_income = $user_match['left_bv'];
    //                 }
    //                 $income = ($old_income - $new_income);
    //                 $match_bv = $income;
    //                 $carry_forward = abs($user['leftPower'] - $user['rightPower']);

    //                 $user_income = $income * 50/100;
    //                 if ($user_income > 0) {
    //                     $matchArr = array(
    //                         'user_id' => $user['user_id'],
    //                         'left_bv' => $user['leftPower'],
    //                         'right_bv' => $user['rightPower'],
    //                         'amount' => $user_income,
    //                         'match_bv' => $match_bv,
    //                         'carry_forward' => $carry_forward,
    //                     );
    //                     $this->Main_model->add('tbl_point_matching_income', $matchArr);
    //                     if($user['capping'] < $user_income){
    //                         $user_income = $user['capping'];
    //                     }
    //                     // if($user['incomeLimit'] > $user['incomeLimit']){
    //                     //     $totalCredit = $user['incomeLimit'] + $user_income;
    //                     //     if($totalCredit < $user['incomeLimit']){
    //                             $matching_income = $user_income;
    //                         // } else {
    //                         //     $matching_income = $user['incomeLimit'] - $user['incomeLimit'];
    //                         // }
    //                         $point_users[$userkey]['user_id'] = $user['user_id'];
    //                         $point_users[$userkey]['point'] =$match_bv;
    //                         $point_users[$userkey]['capping'] = $user['capping'];
    //                         // $point_users[$userkey]['incomeLimit'] = $user['incomeLimit'];
    //                         // $point_users[$userkey]['incomeLimit2'] = $user['incomeLimit2'];
    //                         $pair_counts = $pair_counts + $match_bv;
    //                     // }
    //                     pr($matchArr);
    //                 }
    //             } else {
    //                 if ($user['leftPower'] > $user['rightPower']) {
    //                     $leftPower = $user['leftPower'];
    //                     $rightPower = $user['rightPower'];
    //                 } else {
    //                     $rightPower = $user['rightPower'];
    //                     $leftPower = $user['leftPower'];
    //                 }
    //                 if($leftPower > $rightPower){
    //                     $income = $rightPower;

    //                 }else{
    //                     $income = $leftPower;
    //                 }
    //                 $match_bv = $income;
    //                 $carry_forward = abs($leftPower - $rightPower);

    //                 $user_income = $income * 50/100;
    //                 //                echo $user_income;
    //                 if($user['capping'] < $user_income){
    //                     $user_income = $user['capping'];
    //                 }
    //                 $matchArr = array(
    //                     'user_id' => $user['user_id'],
    //                     'left_bv' => $user['leftPower'],
    //                     'right_bv' => $user['rightPower'],
    //                     'amount' => $user_income,
    //                     'match_bv' => $match_bv,
    //                     'carry_forward' => $carry_forward,
    //                 );
    //                 $this->Main_model->add('tbl_point_matching_income', $matchArr);
    //                 // if($user['incomeLimit'] > $user['incomeLimit']){
    //                 //     $totalCredit = $user['incomeLimit'] + $user_income;
    //                 //     if($totalCredit < $user['incomeLimit']){
    //                         $matching_income = $user_income;
    //                     // } else {
    //                     //     $matching_income = $user['capping'] - $user['incomeLimit'];
    //                     // }

    //                     $point_users[$userkey]['user_id'] = $user['user_id'];
    //                     $point_users[$userkey]['point'] = $match_bv;
    //                     $point_users[$userkey]['capping'] = $user['capping'];
    //                     // $point_users[$userkey]['incomeLimit'] = $user['incomeLimit'];
    //                     // $point_users[$userkey]['incomeLimit2'] = $user['incomeLimit2'];
    //                     $pair_counts = $pair_counts + $match_bv;
    //                 //}
    //                 pr($matchArr);
    //                 pr($point_users);
    //             }
    //         //}
    //     }

    //     $againReceiver = [];
    //     $receiverAmount = 0;
    //     $ar = 0;
    //     $cyclePair = 0;

    //     if(!empty($point_users)){
    //         // pr($point_users);
    //         $date = date('Y-m-d',strtotime(date('Y-m-d').' - 1 day'));
    //         $today_earning = $this->Main_model->get_single_record('tbl_wallet','date(created_at) = "'.$date.'" and type = "account_activation"','ifnull(sum(amount),0) as today_joining');

    //         if(!empty($today_earning)){
    //             echo 'Today joining ' . abs($today_earning['today_joining']) . '<br>';
    //             echo 'Today pairs ' . $pair_counts. '<br>';
    //             $perpairamount = (abs($today_earning['today_joining']) * 50/100) / $pair_counts;
    //             echo 'per pair amount ' . $perpairamount ;
    //             foreach($point_users as $k => $point_user){
    //                 PR($point_user);
    //                 $userIncome = $point_user['point'] * $perpairamount;

    //                 if($point_user['capping'] > $userIncome){
    //                     $userIncome = $userIncome;

    //                     $againReceiver[$ar]['user_id'] = $point_user['user_id'];
    //                     $againReceiver[$ar]['incomeLimit'] = $point_user['incomeLimit'];
    //                     $againReceiver[$ar]['incomeLimit2'] = $point_user['incomeLimit2'];
    //                     $againReceiver[$ar]['points'] = $point_user['point'];

    //                     $cyclePair = $cyclePair + $point_user['point'];

    //                 }else{
    //                     $receiverAmount = $receiverAmount + ($userIncome - $point_user['capping']);
    //                     $userIncome = $point_user['capping'];
    //                 }

    //                 // if($point_user['incomeLimit2'] > $point_user['incomeLimit']){
    //                 //     $totalCredit = $point_user['incomeLimit'] + $userIncome;
    //                 //     if($totalCredit < $point_user['incomeLimit2']){
    //                         $matching_income = $userIncome;
    //                     // } else {
    //                     //     $matching_income = $point_user['incomeLimit2'] - $point_user['incomeLimit'];
    //                     // }

    //                     $incomeArr = array(
    //                         'user_id' => $point_user['user_id'],
    //                         'amount' => $matching_income,
    //                         'type' => 'matching_bonus',
    //                         'description' => 'Point Matching Bonus',
    //                         'per_pair_amount' =>  $perpairamount,
    //                         'total_pair' => $pair_counts,
    //                     );
    //                     $this->Main_model->add('tbl_income_wallet', $incomeArr);
    //                     // $this->Main_model->update('tbl_users',['user_id' => $point_user['user_id']],['incomeLimit' => ($point_user['incomeLimit'] + $incomeArr['amount'])]);
    //                 // }

    //                 if($point_user['capping'] > $userIncome){
    //                     $againReceiver[$ar]['capping'] = $point_user['capping'] - $incomeArr['amount'];
    //                     $ar++;
    //                 }
    //             }
    //         } else {
    //             echo 'No Today Earning<br>';
    //         }
    //     } else {
    //         echo 'No Today Matching<br>';
    //     }

    //     $matching_income = 0;
    //     echo 'First Round<br>';
    //     pr($againReceiver);
    //     if(!empty($receiverAmount) && $receiverAmount > 0 && !empty($againReceiver)){
    //         $this->calculateFinalBinary($receiverAmount,$cyclePair,$againReceiver);
    //     } else {
    //         echo '2nd cycle amount is '.$receiverAmount.'<br>';
    //     }
    //     // pr($response);
    //     die('code executed Successfully');
    // }

    private function calculateFinalBinary($receiverAmount, $cyclePair, $againReceiver)
    {

        $againReceiver2 = [];
        $receiverAmount2 = 0;
        $ar = 0;
        $cyclePair2 = 0;

        $perPairValue = $receiverAmount / $cyclePair;

        echo 'Total Pair ' . $cyclePair . ' & amount is ' . $receiverAmount . ' & per pair value ' . $perPairValue . '<br';
        if (!empty($againReceiver)) {
            foreach ($againReceiver as $agr) {
                $creditValue = $agr['points'] * $perPairValue;

                if ($agr['capping'] > $creditValue) {
                    $creditValue = $creditValue;

                    $againReceiver2[$ar]['user_id'] = $agr['user_id'];
                    $againReceiver2[$ar]['incomeLimit'] = $agr['incomeLimit'];
                    $againReceiver2[$ar]['incomeLimit2'] = $agr['incomeLimit2'];
                    $againReceiver2[$ar]['points'] = $agr['points'];

                    $cyclePair2 = $cyclePair2 + $agr['points'];
                } else {
                    $receiverAmount2 = $receiverAmount2 + ($creditValue - $agr['capping']);
                    $creditValue = $agr['capping'];
                }

                // if($agr['incomeLimit2'] > $agr['incomeLimit']){
                //     $totalCredit = $agr['incomeLimit'] + $creditValue;
                //     if($totalCredit < $agr['incomeLimit2']){
                $matching_income = $creditValue;
                // } else {
                //     $matching_income = $agr['incomeLimit2'] - $agr['incomeLimit'];
                // }

                $incomeArr = array(
                    'user_id' => $agr['user_id'],
                    'amount' => $matching_income,
                    'type' => 'balancing_income',
                    'description' => 'Point Balancing Bonus',
                    'per_pair_amount' =>  $perPairValue,
                    'total_pair' => $cyclePair,
                );
                $this->Main_model->add('tbl_income_wallet', $incomeArr);


                // $this->Main_model->update('tbl_users',['user_id' => $agr['user_id']],['incomeLimit' => ($agr['incomeLimit'] + $incomeArr['amount'])]);
                // }
                if ($agr['capping'] > $creditValue) {
                    if (!empty($incomeArr['amount'])) {
                        $againReceiver2[$ar]['capping'] = $agr['capping'] - $incomeArr['amount'];
                    } else {
                        $againReceiver2[$ar]['capping'] = $agr['capping'];
                    }
                    $ar++;
                }
            }

            if (!empty($receiverAmount2) && $receiverAmount2 > 0) {
                echo 'Repeat Round<br>';
                pr($againReceiver2);
                $this->calculateFinalBinary($receiverAmount2, $cyclePair2, $againReceiver2);
            }
        } else {
            echo 'No 2nd cycle receiver<br>';
        }
    }





    private function generation_income($user_id, $amount, $sender_id)
    {
        for ($i = 1; $i <= 5; $i++) {
            $user = $this->Main_model->get_single_record('tbl_users', ['user_id' => $user_id], 'user_id,sponser_id,package_amount,paid_status,incomeLimit,incomeLimit2');
            if (!empty($user)) {
                if ($i == 1 && ($user['package_amount'] == '200' || $user['package_amount'] >= '500')) {
                    if ($user['package_amount'] == '200') :
                        $percent = 0.005;
                    else :
                        $percent = 0.005;
                    endif;
                    if ($user['incomeLimit2'] > $user['incomeLimit']) {
                        $totalCredit = $user['incomeLimit'] + $amount * $percent;
                        if ($totalCredit < $user['incomeLimit2']) {
                            $leadership_bonus = $amount * $percent;
                        } else {
                            $leadership_bonus = $user['incomeLimit2'] - $user['incomeLimit'];
                        }
                        $incomeArr = array(
                            'user_id' => $user['user_id'],
                            'amount' => $leadership_bonus,
                            'type' => 'leadership_bonus',
                            'description' => 'Leadership Bonus From ' . $sender_id . ' at level ' . $i,
                        );
                        $this->Main_model->add('tbl_income_wallet', $incomeArr);
                        $this->Main_model->update('tbl_users', ['user_id' => $user['user_id']], ['incomeLimit' => ($user['incomeLimit'] + $incomeArr['amount'])]);
                    }
                } elseif ($i == 2 && $user['package_amount'] >= '1000') {
                    $percent = 0.005;
                    if ($user['incomeLimit2'] > $user['incomeLimit']) {
                        $totalCredit = $user['incomeLimit'] + $amount * $percent;
                        if ($totalCredit < $user['incomeLimit2']) {
                            $leadership_bonus = $amount * $percent;
                        } else {
                            $leadership_bonus = $user['incomeLimit2'] - $user['incomeLimit'];
                        }
                        $incomeArr = array(
                            'user_id' => $user['user_id'],
                            'amount' => $leadership_bonus,
                            'type' => 'leadership_bonus',
                            'description' => 'Leadership Bonus From ' . $sender_id . ' at level ' . $i,
                        );
                        $this->Main_model->add('tbl_income_wallet', $incomeArr);
                        $this->Main_model->update('tbl_users', ['user_id' => $user['user_id']], ['incomeLimit' => ($user['incomeLimit'] + $incomeArr['amount'])]);
                    }
                } elseif ($i == 3 && $user['package_amount'] >= '2000') {
                    $percent = 0.005;
                    if ($user['incomeLimit2'] > $user['incomeLimit']) {
                        $totalCredit = $user['incomeLimit'] + $amount * $percent;
                        if ($totalCredit < $user['incomeLimit2']) {
                            $leadership_bonus = $amount * $percent;
                        } else {
                            $leadership_bonus = $user['incomeLimit2'] - $user['incomeLimit'];
                        }
                        $incomeArr = array(
                            'user_id' => $user['user_id'],
                            'amount' => $leadership_bonus,
                            'type' => 'leadership_bonus',
                            'description' => 'Leadership Bonus From ' . $sender_id . ' at level ' . $i,
                        );
                        $this->Main_model->add('tbl_income_wallet', $incomeArr);
                        $this->Main_model->update('tbl_users', ['user_id' => $user['user_id']], ['incomeLimit' => ($user['incomeLimit'] + $incomeArr['amount'])]);
                    }
                } elseif ($i == 4 && $user['package_amount'] >= '2000') {
                    if ($user['package_amount'] == '2000' || $user['package_amount'] == '2500') :
                        $percent = 0.005;
                    else :
                        $percent = 0.005;
                    endif;
                    if ($user['incomeLimit2'] > $user['incomeLimit']) {
                        $totalCredit = $user['incomeLimit'] + $amount * $percent;
                        if ($totalCredit < $user['incomeLimit2']) {
                            $leadership_bonus = $amount * $percent;
                        } else {
                            $leadership_bonus = $user['incomeLimit2'] - $user['incomeLimit'];
                        }
                        $incomeArr = array(
                            'user_id' => $user['user_id'],
                            'amount' => $leadership_bonus,
                            'type' => 'leadership_bonus',
                            'description' => 'Leadership Bonus From ' . $sender_id . ' at level ' . $i,
                        );
                        $this->Main_model->add('tbl_income_wallet', $incomeArr);
                        $this->Main_model->update('tbl_users', ['user_id' => $user['user_id']], ['incomeLimit' => ($user['incomeLimit'] + $incomeArr['amount'])]);
                    }
                } elseif ($i == 5 && $user['package_amount'] == '10000') {
                    $percent = 0.005;
                    if ($user['incomeLimit2'] > $user['incomeLimit']) {
                        $totalCredit = $user['incomeLimit'] + $amount * $percent;
                        if ($totalCredit < $user['incomeLimit2']) {
                            $leadership_bonus = $amount * $percent;
                        } else {
                            $leadership_bonus = $user['incomeLimit2'] - $user['incomeLimit'];
                        }
                        $incomeArr = array(
                            'user_id' => $user['user_id'],
                            'amount' => $leadership_bonus,
                            'type' => 'leadership_bonus',
                            'description' => 'Leadership Bonus From ' . $sender_id . ' at level ' . $i,
                        );
                        $this->Main_model->add('tbl_income_wallet', $incomeArr);
                        $this->Main_model->update('tbl_users', ['user_id' => $user['user_id']], ['incomeLimit' => ($user['incomeLimit'] + $incomeArr['amount'])]);
                    }
                }
                $leadership_bonus = 0;
                $user_id = $user['sponser_id'];
            }
        }
    }


    private function levelIncome($user_id, $linkedID)
    {
        $direct = 0;
        for ($i = 1; $i <= 20; $i++) :
            if ($i % 2 != 0) {
                $direct += 1;
            }
            $incomeArr[$i] = ['amount' => 10, 'direct' => $direct];
        endfor;
        foreach ($incomeArr as $key => $income) :
            $userinfo = $this->Main_model->get_single_record('tbl_users', ['user_id' => $user_id], 'user_id,sponser_id,directs');
            if (!empty($userinfo['user_id'])) :
                if ($userinfo['directs'] >= $income['direct']) :
                    $incomeArr = array(
                        'user_id' => $userinfo['user_id'],
                        'amount' => $income['amount'],
                        'type' => 'booster_level_income',
                        'description' => 'Booster Level Income From User ' . $linkedID,
                    );
                    pr($incomeArr);
                    $this->Main_model->add('tbl_income_wallet', $incomeArr);
                endif;
                $user_id = $userinfo['sponser_id'];
            endif;
        endforeach;
    }

    public function deactiveUser()
    {
        $users = $this->Main_model->get_records('tbl_users', ['user_id !=' => 'T11111', 'paid_status' => 1], 'user_id,package_id,package_amount,topup_date');
        foreach ($users as $user) :
            $date1 = date('Y-m-d');
            $date2 = date('Y-m-d', strtotime($user['topup_date'] . ' + 20 days'));
            $diff = strtotime($date1) - strtotime($date2);
            if ($diff > 0) {
                $topupData = [
                    'paid_status' => 0,
                    'package_id' => 0,
                    'package_amount' => 0,
                    'topup_date' => '0000-00-00 00:00:00',
                    'retopup' => 1,
                ];
                $this->Main_model->update('tbl_users', ['user_id' => $user['user_id']], $topupData);
            }
        endforeach;
    }

    // public function rewardCron()
    // {
    //     $rewards = [
    //         1 => ['business' => 300000, 'reward' => 5000, 'salary' => 1000, 'month' => 12],
    //         2 => ['business' => 700000, 'reward' => 11000, 'salary' => 2000, 'month' => 12],
    //         3 => ['business' => 1200000, 'reward' => 21000, 'salary' => 3500, 'month' => 12],
    //         4 => ['business' => 2100000, 'reward' => 51000, 'salary' => 5000, 'month' => 12],
    //         5 => ['business' => 5000000, 'reward' => 105000, 'salary' => 7000, 'month' => 12],
    //         6 => ['business' => 10000000, 'reward' => 200000, 'salary' => 10000, 'month' => 12],
    //         7 => ['business' => 20000000, 'reward' => 500000, 'salary' => 21000, 'month' => 12],
    //         8 => ['business' => 50000000, 'reward' => 1500000, 'salary' => 51000, 'month' => 12],
    //         9 => ['business' => 100000000, 'reward' => 1500000, 'salary' => 100000, 'month' => 15],
    //         10 => ['business' => 200000000, 'reward' => 4000000, 'salary' => 105000, 'month' => 18],
    //         11 => ['business' => 500000000, 'reward' => 10000000, 'salary' => 205000, 'month' => 18],
    //         12 => ['business' => 1000000000, 'reward' => 20000000, 'salary' => 500000, 'month' => 24],
    //         13 => ['business' => 2000000000, 'reward' => 100000000, 'salary' => 700000, 'month' => 30],
    //         14 => ['business' => 5000000000, 'reward' => 1500000000, 'salary' => 2500000, 'month' => 36],
    //     ];
    //     foreach ($rewards as $key => $reward) {
    //         $users = $this->Main_model->getBusiness($reward['business']);
    //         // $users = $this->Main_model->get_records('tbl_users', ['leftPower >=' => $reward['business'], 'rightPower >=' => $reward['business']], 'user_id');
    //         //pr($users,true);
    //         foreach ($users as $key2 => $user) {
    //             $check = $this->Main_model->get_single_record('tbl_rewards', ['award_id' => $key, 'user_id' => $user['user_id']], '*');
    //             if (empty($check)) {
                    
    //                 $rewardData = [
    //                     'user_id' => $user['user_id'],
    //                     'amount' => $reward['reward'],
    //                     // 'rank' => $reward['rank'],
    //                     'award_id' => $key,
    //                 ];
    //                 $this->Main_model->add('tbl_rewards', $rewardData);
    //                 pr($rewardData);
    //                 // $IncomeData = [
    //                 //     'user_id' => $user['user_id'],
    //                 //     'amount' => $reward['amount'],
    //                 //     'type' => 'reward_income',
    //                 //     'description' => 'You have Achieved your ' . $key . ' Reward Income ',
    //                 // ];
    //                 // pr($IncomeData);
    //                 // $this->Main_model->add('tbl_reward_wallet', $IncomeData);
    //                 $this->Main_model->update('tbl_users', ['user_id' => $user['user_id']], ['rewardLevel' => $key]);
    //                 //}
    //                 //}
    //             }
    //         }
    //     }
    // }

     public function rewardCron()
    {
        // if (date('D') != 'Sun') {
            $rewards = [
                1 => ['business' => 300000, 'reward' => 5000, 'salary' => 1000, 'month' => 12],
                2 => ['business' => 700000, 'reward' => 11000, 'salary' => 2000, 'month' => 12],
                3 => ['business' => 1200000, 'reward' => 21000, 'salary' => 3500, 'month' => 12],
                4 => ['business' => 2100000, 'reward' => 51000, 'salary' => 5000, 'month' => 12],
                5 => ['business' => 5000000, 'reward' => 105000, 'salary' => 7000, 'month' => 12],
                6 => ['business' => 10000000, 'reward' => 200000, 'salary' => 10000, 'month' => 12],
                7 => ['business' => 20000000, 'reward' => 500000, 'salary' => 21000, 'month' => 12],
                8 => ['business' => 50000000, 'reward' => 1500000, 'salary' => 51000, 'month' => 12],
                9 => ['business' => 100000000, 'reward' => 1500000, 'salary' => 100000, 'month' => 15],
                10 => ['business' => 200000000, 'reward' => 4000000, 'salary' => 105000, 'month' => 18],
                11 => ['business' => 500000000, 'reward' => 10000000, 'salary' => 205000, 'month' => 18],
                12 => ['business' => 1000000000, 'reward' => 20000000, 'salary' => 500000, 'month' => 24],
                13 => ['business' => 2000000000, 'reward' => 100000000, 'salary' => 700000, 'month' => 30],
                14 => ['business' => 5000000000, 'reward' => 1500000000, 'salary' => 2500000, 'month' => 36],
            ];

            foreach ($rewards as $key1 => $reward) :
                $users = $this->Main_model->get_records('tbl_users', ['paid_status >' => 0], 'user_id,directs');
                foreach ($users as $key => $user) {
                    $getDirects = $this->Main_model->get_records('tbl_users', ['sponser_id' => $user['user_id']], 'user_id');
                    $directArr = [];
                    foreach ($getDirects as $key2 => $gd) {
                        $selfBusiness = $this->Main_model->get_single_record('tbl_users', ['user_id' => $gd['user_id']], 'total_package');
                        // $sponserBusiness = $this->Main_model->get_single_record('tbl_users', ['user_id' => $gd['user_id']], 'total_package,power_leg');

                        $getBusiness = $this->Main_model->getTeamBusiness($gd['user_id']);
                        $directArr[$key2] = [
                            'user_id' => $gd['user_id'],
                            'business' => $getBusiness['business'] + $selfBusiness['total_package'] ,
                        ];
                    }
                    $columns = array_column($directArr, 'business');
                    array_multisort($columns, SORT_DESC, $directArr);
                    pr($directArr);
                    $teamA = 0;
                    $teamB = 0;
                    // $secondLeg = 0;
                    // $thirdLeg = 0;
                    foreach ($directArr as $dkey => $da) {
                        if ($dkey == 0) {
                            $teamA = $da['business'];
                            // $check1 = $da['business']*0.5;
                        } else {
                            $teamB += $da['business'];
                            // if($dkey == 1){
                            //     $secondLeg = $da['business'];
                            // }
                            // if($dkey == 2){
                            //     $thirdLeg = $da['business'];
                            // }
                        }
                    }
                    echo 'User ID ' . $user['user_id'] . ' Required Business ' . $reward['business'] . ' Team A ' . $teamA . ' Team B ' . $teamB . '<br>';
                    if (($teamA + $teamB) >= $reward['business']) {
                        //if($teamA >= ($reward['business']*0.5) && $secondLeg >= ($reward['business']*0.2) && $thirdLeg >= ($reward['business']*0.2)){
                        //echo die($user['user_id']);
                        if (($teamA) >= ($reward['business'] * 0.50) && ($teamB) >= ($reward['business'] * 0.50)) {
                            echo 'User ID ' . $user['user_id'] . ' Team A ' . $teamA . ' Team B ' . $teamB . '<br>';
                            $check = $this->Main_model->get_single_record('tbl_rewards', ['award_id' => $key1, 'user_id' => $user['user_id']], '*');
                            if (empty($check)) {
                                $rewardData = [
                                    'user_id' => $user['user_id'],
                                    'amount' => $reward['reward'],
                                    'award_id' => $key1,
                                ];
                                $this->Main_model->add('tbl_rewards', $rewardData);
                                pr($rewardData);
                               
                                $this->Main_model->update('tbl_users', ['user_id' => $user['user_id']], ['rewardLevel' => $key]);
                            }
                        }
                    }
                }
            endforeach;
        // }else{
        //     echo 'Sunday Income Off';
        // }    
    }

    public function resetDailyLimit()
    {
        $date = date('Y-m-d');
        $cron = $this->Main_model->get_single_record('tbl_cron', ['date' => $date, 'cron_name' => 'resetDailyLimit'], '*');
        if (empty($cron)) {
            $this->Main_model->update('tbl_users', ['incomeLimit >' => 0], ['incomeLimit' => 0]);
            $this->Main_model->add('tbl_cron', ['cron_name' => 'resetDailyLimit', 'date' => $date]);
        } else {
            echo 'Today daily limit reset done';
        }
    }

    public function resetPackageLimit()
    {
        $date = date('Y-m-d');
        $users = $this->Main_model->get_records('tbl_users', ['paid_status' => 1, 'retopup' => 0], 'user_id,package_amount,retopup_count');
        foreach ($users as $user) {
            $checkBalance = $this->Main_model->get_single_record('tbl_income_wallet', ['amount >' => 0, 'user_id' => $user['user_id']], 'ifnull(sum(amount),0) as balance');
            $totalBalance = $checkBalance['balance'];
            if ($totalBalance >= ($user['package_amount'] * 5)) {
                pr($user);
                $this->Main_model->update('tbl_users', ['user_id' => $user['user_id']], ['retopup' => 1, 'package_amount' => 0, 'topup_date' => '0000-00-00 00:00:00', 'retopup_count' => ($user['retopup_count'] + 1)]);
            }
        }
    }

    public function IncomesSet()
    {
        $users = $this->Main_model->get_records('tbl_users', ['paid_status' => 1], '*');
        foreach ($users as $user) {
            $checkUser = $this->Main_model->get_single_record('tbl_income_wallet', ['user_id' => $user['user_id']], '*');
            $direct_income = $this->Main_model->get_single_record('tbl_income_wallet', ['amount >' => 0, 'user_id' => $user['user_id'], 'type' => 'direct_income'], 'ifnull(sum(amount),0) as balance');
            $level_income = $this->Main_model->get_single_record('tbl_income_wallet', ['amount >' => 0, 'user_id' => $user['user_id'], 'type' => 'level_income'], 'ifnull(sum(amount),0) as balance');
            if ($checkUser) {
                $updateData = array(
                    'direct_income' => $direct_income['balance'],
                    'level_income' => $level_income['balance'],
                );
                pr($updateData);
                $this->Main_model->update('tbl_incomes', ['user_id' => $user['user_id']], $updateData);
            } else {
                $addData = array(
                    'user_id' => $user['user_id'],
                    'direct_income' => $direct_income['balance'],
                    'level_income' => $level_income['balance'],
                );
                pr($addData);
                $this->Main_model->add('tbl_incomes', $addData);
            }
        }
    }

    public function approveFund()
    {
        $request = $this->Main_model->get_records('tbl_payment_request', array('status' => 0), '*');
        foreach ($request as $key => $req) {
            if ($req['status'] == 0) {
                $walletData = array(
                    'user_id' => $req['user_id'],
                    'amount' => $req['amount'],
                    'sender_id' => $req['user_id'],
                    'type' => 'auto_fund',
                    'remark' => 'Auto Fund Deposit',
                );
                pr($walletData);
                $this->Main_model->add('tbl_wallet', $walletData);
                $this->Main_model->update('tbl_payment_request', ['id' => $req['id']], ['status' => 1]);
            }
        }
    }

    public function WithdrawCron()
    {
        $date = date('Y-m-d');
        // $cron = $this->Main_model->get_single_record('tbl_cron',"cron_name = 'withdraw_cron' and date = '".$date."'",'*');
        // if(empty($cron)):
        $users = $this->Main_model->withdraw_users(200);
        pr($users);
        foreach ($users as $key => $user) {
            $checkKYC = $this->Main_model->get_single_record('tbl_bank_details', ['user_id' => $user['user_id'], 'kyc_status' => 2], '*');
            $userinfo = $this->Main_model->get_single_record('tbl_users', ['user_id' => $user['user_id']], '*');
            if (!empty($checkKYC['bank_account_number'])) :
                $DirectIncome = array(
                    'user_id' => $user['user_id'],
                    'amount' => -$user['total_amount'],
                    'type' => 'withdraw_request',
                    'description' => 'Withdraw Request',
                );
                $this->Main_model->add('tbl_income_wallet', $DirectIncome);
                $withdrawArr = array(
                    'user_id' => $user['user_id'],
                    'amount' => $user['total_amount'],
                    'type' => 'withdraw_request',
                    'tds' => $user['total_amount'] * 5 / 100,
                    'admin_charges' => $user['total_amount']  * 5 / 100,
                    'fund_conversion' => 0,
                    'zil_address' => $userinfo['eth_address'],
                    'payable_amount' => $user['total_amount'] * 90 / 100
                );
                $this->Main_model->add('tbl_withdraw', $withdrawArr);
            endif;
        }
        redirect('Admin/Management');
        // $this->Main_model->add('tbl_cron',['cron_name' => 'withdraw_cron','date' => $date]);
        // else:
        //     echo 'Today Cron already run';
        // endif;
    }

    public function updateTokenValue()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.vindax.com/api/v1/ticker/24hr?symbol=MPYUSDT',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $jsonData = json_decode($response, true);
        pr($jsonData['lastPrice']);
        $this->Main_model->update('tbl_token_value', ['id' => 1], ['amount' => $jsonData['lastPrice'], 'sellValue' => $jsonData['lastPrice']]);
    }

    public function test_node_api()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://18.216.195.54:3490/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $jsonData = json_decode($response, true);
        pr($jsonData);
    }

    // public function TimeCron(){
    //     $tokenValue = $this->Main_model->get_single_record('tbl_token_value',['id' => 1],'*');
    //     $date1 = date('Y-m-d');
    //     $date2 = date('Y-m-d',strtotime($tokenValue['created_at'].' + 7 days'));
    //     $diff = strtotime($date1) - strtotime($date2);
    //     if($diff > 0){
    //         $topupData = [
    //             'amount' => $tokenValue['sellValue'],
    //             'sellValue' => 0.00,
    //             'topup_date' => $date2;,
    //         ];
    //         $this->Main_model->update('tbl_token_value',['id' => 1],$topupData);
    //     }
    // }

    public function boosterIncome()
    {
        $booster_users = $this->Main_model->get_records('tbl_boosting', array('type' => 'boosting_transfer'), '*');
        foreach ($booster_users as $key => $user) {
            $UserCheck = $this->Main_model->get_single_record('tbl_boosting', ['user_id' => $user['user_id'], 'id > ' => $user['id']], 'count(id) as ids,user_id');
            if ($UserCheck['ids'] >= 40) {
                if ($user['level'] == 1 && $user['level'] == 2) {
                    $incomeArr = array(
                        'user_id' => $user['user_id'],
                        'amount' => $user['roi_amount'],
                        'type' => 'booster_income',
                        'description' => 'Booster Income at ' . $user['package_id'] . ' Package',
                    );
                    pr($incomeArr);
                    $this->Main_model->add('tbl_income_wallet', $incomeArr);
                    $this->Main_model->update('tbl_boosting', array('id' => $user['id']), array('status' => 1));
                } else {
                    $check = $this->Main_model->get_single_record('tbl_boosting', 'user_id = "' . $user['user_id'] . '" and status = "1" and level != "1" and level != "2"', 'count(id) as ids');
                    if ($check['ids'] >= 1) {
                        $Directs = $this->Main_model->get_single_record('tbl_users', ['sponser_id' => $user['user_id'], 'paid_status' => 1], 'count(id) as ids');
                        if ($Directs['ids'] >= 1) {
                            $incomeArr = array(
                                'user_id' => $user['user_id'],
                                'amount' => $user['roi_amount'],
                                'type' => 'booster_income',
                                'description' => 'Booster Income at ' . $user['package_id'] . ' Package',
                            );
                            pr($incomeArr);
                            $this->Main_model->add('tbl_income_wallet', $incomeArr);
                            $this->Main_model->update('tbl_boosting', array('id' => $user['id']), array('status' => 1));
                        }
                    } else {
                        $incomeArr = array(
                            'user_id' => $user['user_id'],
                            'amount' => $user['roi_amount'],
                            'type' => 'booster_income',
                            'description' => 'Booster Income at ' . $user['package_id'] . ' Package',
                        );
                        pr($incomeArr);
                        $this->Main_model->add('tbl_income_wallet', $incomeArr);
                        $this->Main_model->update('tbl_boosting', array('id' => $user['id']), array('status' => 1));
                    }
                }
            }
        }
    }

    public function fornightlyCron()
    {
        $cron = $this->Main_model->get_single_record('tbl_cron', '  date(created_at) = date(now()) and cron_name = "fortnightly_divined_income"', '*');
        $dividend = $this->Main_model->get_single_record_desc('tbl_dividend_income', [], 'fortnightly_income');

        if (empty($cron)) {
            $currentmonth = date('m');
            $lastDateOfMonth = $this->getLastDateOfMonth($currentmonth);
            if ($lastDateOfMonth == 31) {
                $minusDays = 16;
                $minusDays2 = 15;
            } else {
                $minusDays = 15;
                $minusDays2 = 15;
            }
            // echo $minusDays;
            // echo '<br>';
            $date1 = '2023-08-01'; //date('Y-m-d');
            $date2 = date('Y-m-d', strtotime($date1 . '-' . $minusDays . ' days'));
            //    echo $date2 ;
            $total_business = $this->Main_model->get_single_record('tbl_activation_details', ['date(created_at) >=' => $date2, 'date(created_at) <' => $date1], 'ifnull(sum(amount),0) as total_business');
            // echo '<br>';
            // echo $total_business['total_business'];

            //get users for distributions//
            $previousDate = $date2;
            $distributionDate = date('Y-m-d', strtotime($previousDate . '-' . $minusDays2 . ' days'));
            $wokringUsers = $this->Main_model->get_records('tbl_users', ['date(topup_date) >=' => $distributionDate, 'date(topup_date) <' => $previousDate, 'directs >=' => 1], 'user_id');
            $notwokringUsers = $this->Main_model->get_records('tbl_users', ['date(topup_date) >=' => $distributionDate, 'date(topup_date) <' => $previousDate], 'user_id');

            $all_users = count($wokringUsers);
            $notworkusers = count($notwokringUsers);

            echo '<br>';
            echo $distributionDate;
            echo '<br>';
            echo $all_users;
            if ($total_business['total_business'] > 0 && !empty($wokringUsers)) {
                $cal = $total_business['total_business'] * $dividend['fortnightly_income'];
                // $cal = $total_business['total_business'] * 0.075;
                $perID = $cal / $all_users;
                foreach ($wokringUsers as $w => $wu) {
                    $workIncome = [
                        'user_id' =>   $wu['user_id'],
                        'amount' =>   $perID,
                        'type' =>   'fortnightly_divined_income',
                        'description' =>   'Fortnightly Divined Working Income',

                    ];
                    pr($workIncome);

                    $this->Main_model->add('tbl_income_wallet', $workIncome);
                }
            }
            if ($total_business['total_business'] > 0 && !empty($notwokringUsers)) {
                $secondCal = $total_business['total_business'] * $dividend['fortnightly_income'];
                $perUsers = $secondCal / $notworkusers;

                foreach ($notwokringUsers as $n => $nwu) {
                    $notworkIncome = [
                        'user_id' =>   $nwu['user_id'],
                        'amount' =>   $perUsers,
                        'type' =>   'fortnightly_divined_income',
                        'description' =>   'Fortnightly Divined Not Wokring Income',

                    ];
                    pr($notworkIncome);
                    $this->Main_model->add('tbl_income_wallet', $notworkIncome);
                }
            }
            $this->Main_model->add('tbl_cron', array('cron_name' => 'fortnightly_divined_income'));
        } else {
            echo 'Income Already Distributed!';
        }
    }

    public function EMIGenrate(){
        $date = date('Y-m-d');
        $cron = $this->Main_model->get_single_record('tbl_cron', ['date' => $date, 'cron_name' => 'EMIGenrate'], '*');
        if(empty($cron)){
                $this->Main_model->add('tbl_cron', ['cron_name' => 'EMIGenrate', 'date' => $date]);
                $roi_users = $this->Main_model->get_records('tbl_emi_plan', array('month >' => 0), '*');
            foreach ($roi_users as $key => $user) {
                $date1 = date('Y-m-d H:i:s');
                $date2 = date('Y-m-d H:i:s', strtotime($user['emi_date'] . '+ 0 days'));
                $diff = strtotime($date1) - strtotime($date2);
                echo $diff . ' / ' . $user['user_id'] . '<br>';
                if ($diff >= 0) {
                    $userinfo = $this->Main_model->get_single_record('tbl_users', ['user_id' => $user['user_id']], '*');
                    $getEMIpending = $this->Main_model->get_single_record('tbl_kist_add', ['user_id' => $user['user_id'],'emi_status' =>0], 'ifnull(sum(amount),0)as totalEmi,count(id)as countEMI,amount');
                    if($getEMIpending['countEMI'] >=3){
                        $this->Main_model->update('tbl_emi_plan', array('id' => $user['id']),['emi_close' =>1]);
                        }else{
                            $tax = $getEMIpending['totalEmi']+500;
                            $monthly = $getEMIpending['amount'] * 5 /100;
                            $new_day = $user['month'] - 1;
                            $addemi = [
                                    'user_id' => $user['user_id'],
                                    'amount' => $user['emi'],
                                    'tax' => $tax,
                                    'monthly_benifit' => $monthly ,
                                    'created_at' => date('Y-m-d'),
                                 ];
                                 add('tbl_kist_add',$addemi); 
                        $this->Main_model->update('tbl_emi_plan', array('id' => $user['id']), array('month' => $new_day,'emi_date' => date('Y-m-d H:i:s')));
                        $Clearamout = $this->Main_model->get_single_record('tbl_kist_add', ['user_id' =>$user_id,'status' => 1], 'ifnull(sum(amount),0)as balance , ifnull(sum(monthly_benifit),0)as monthly_benifit');
                        $Cpackage = $this->Main_model->get_records('tbl_emi_plan', array('user_id' =>$user_id), '*');
                        if($Clearamout['balance'] == $Cpackage['total_wmi']){
                            $this->clearemi($user['user_id'],$Clearamout['balance'],$Clearamout['monthly_benifit']);
                        }
                    }
                }
            }
        }else{
            echo 'Today Cron Already Run';
        }        
    }

     public function clearemi(){
        $date = date('Y-m-d');
            $cron = $this->Main_model->get_single_record('tbl_cron', ['date' => $date, 'cron_name' => 'clearemi'], '*');
        if(empty($cron)){
                $this->Main_model->add('tbl_cron', ['cron_name' => 'clearemi', 'date' => $date]);
            $Clearamout = $this->Main_model->get_single_record('tbl_kist_add', ['status' => 1], 'ifnull(sum(amount),0)as balance , ifnull(sum(monthly_benifit),0)as monthly_benifit');
             $Cpackage = $this->Main_model->get_records('tbl_emi_plan', array('month' => 0), '*');
            foreach($Cpackage as $user){
                $date1 = date('Y-m-d H:i:s');
                $date2 = date('Y-m-d H:i:s', strtotime($user['emi_date'] . '+ 1 month'));
                $diff = strtotime($date1) - strtotime($date2);
                echo $diff . ' / ' . $user['user_id'] . '<br>';
                if ($diff > 0) {
                    if($Clearamout['balance'] == $user['total_emi']){
                        $totalAmount = ($Clearamout['balance'] + $Clearamout['monthly_benifit'] + $user['emi']);
                        $cleartotal = [
                            'user_id' => $user['user_id'],
                            'amount' => $totalAmount,
                            'monthly_benifit' => $Clearamout['monthly_benifit'],
                            'total_benifit' => ($Clearamout['monthly_benifit'] + $user['emi']),
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        add('tbl_emi_amount',$cleartotal); 
                    }    
                }
            }
        }else{
            echo 'Today Cron Already Run';
        }    
    }

    public function EMIGenrate2(){
            $date = date('Y-m-d');
            $cron = $this->Main_model->get_single_record('tbl_cron', ['date' => $date, 'cron_name' => 'EMIGenrate2'], '*');
        if(empty($cron)){
                $this->Main_model->add('tbl_cron', ['cron_name' => 'EMIGenrate2', 'date' => $date]);
                $Clearamounr = $this->Main_model->get_single_record('tbl_emi_plan2', [], '*');
            $Clearamout = $this->Main_model->get_single_record('tbl_kist_add2', ['user_id' =>$Clearamounr['user_id'],'status' => 1], 'ifnull(sum(amount),0)as balance ');
                $roi_users = $this->Main_model->get_records('tbl_emi_plan2', array('month >' => 0), '*');
            foreach ($roi_users as $key => $user) {
                $date1 = date('Y-m-d H:i:s');
                $date2 = date('Y-m-d H:i:s', strtotime($user['emi_date'] . '+ 0 days'));
                $diff = strtotime($date1) - strtotime($date2);
                echo $diff . ' / ' . $user['user_id'] . '<br>';
                if ($diff >= 0) {
                    $userinfo = $this->Main_model->get_single_record('tbl_users', ['user_id' => $user['user_id']], '*');
                    $getEMIpending = $this->Main_model->get_single_record('tbl_kist_add2', ['user_id' => $user['user_id'],'emi_status' =>0], 'ifnull(sum(amount),0)as totalEmi,count(id)as countEMI');
                    if($getEMIpending['countEMI'] >=3){
                        $this->Main_model->update('tbl_emi_plan2', array('id' => $user['id']),['emi_close' =>1]);
                    }else{
                        $new_day = $user['month'] - 1;
                        $addemi = [
                                'user_id' => $user['user_id'],
                                'amount' => $user['emi'],
                                'created_at' => date('Y-m-d'),
                                ];
                                add('tbl_kist_add2',$addemi); 
                        $this->Main_model->update('tbl_emi_plan2', array('id' => $user['id']), array('month' => $new_day,'emi_date' => date('Y-m-d H:i:s')));
                    }
                }
            }
        }else{
            echo 'Today Cron Already Run';
        }    
    }

   

    public function clearemi2(){
        $date = date('Y-m-d');
        $cron = $this->Main_model->get_single_record('tbl_cron', ['date' => $date, 'cron_name' => 'clearemi2'], '*');
        if(empty($cron)){
                $this->Main_model->add('tbl_cron', ['cron_name' => 'clearemi2', 'date' => $date]);
            $Clearamout = $this->Main_model->get_single_record('tbl_kist_add2', ['status' => 1], 'ifnull(sum(amount),0)as balance');
             $Cpackage = $this->Main_model->get_records('tbl_emi_plan2', array('month' => 0), '*');
            foreach($Cpackage as $user){
                $date1 = date('Y-m-d H:i:s');
                $date2 = date('Y-m-d H:i:s', strtotime($Cpackage['emi_date'] . '+ 1 month'));
                $diff = strtotime($date1) - strtotime($date2);
                echo $diff . ' / ' . $user['user_id'] . '<br>';
                if ($diff > 0) {
                    if($Clearamout['balance'] == $user['total_emi']){
                        $extra = 7000 ;
                        $extratotal = $Clearamout['balance'] ;
                        $totalbenifit = ($Clearamout['balance'] + $extra);
                        $totalAmount = ($Clearamout['balance'] * 2 + $extra);
                        $cleartotal = [
                            'user_id' => $user['user_id'],
                            'amount' => $totalAmount,
                            'double_benifit' => $extratotal,
                            'total_benifit' => $totalbenifit,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        add('tbl_emi_amount',$cleartotal); 
                    }
                }    
            }
        }else{
            echo 'Today Cron Already Run';
        }
    }
}
