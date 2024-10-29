<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Instalment extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'pagination'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'super', 'compose'));
        $this->userinfo = userinfo();
        $this->bankinfo = bankinfo();
    }

    public function select_installment() {
    if (is_logged_in()) {
        $response = array();
             $addDropDwn = [];
        $packageEMI = get_records('tbl_kist_package',[],'*');
        foreach($packageEMI as $key => $EMI){
            $addDropDwn[$EMI['emi']] = $EMI['emi'];
        }
        $drop = form_dropdown('package', $addDropDwn,'package', ['class' => 'form-control']);
        $response['form_open'] = form_open(base_url('dashboard/select_installment_small'));
        $response['form'] = [
            'package' => form_label('Choose Package', 'package') . $drop,
        ];
        $response['form_button'] = [
            'submit' => form_submit('updateProfile', 'Submit', ['class' => 'btn btn-info', 'id' => 'updateProfile', 'style' => 'display: block;'])
        ];
         
        
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = $this->security->xss_clean($this->input->post());
         $this->form_validation->set_rules('package', 'EMI', 'trim|required');
            if ($this->form_validation->run() != FALSE) {
                $user = get_single_record('tbl_users', ['user_id' => $this->session->userdata['user_id']], '*');
                $balance = get_single_record('tbl_wallet', ['user_id' => $this->session->userdata['user_id']], 'IFNULL(SUM(amount), 0) as balance');
                $EMI = get_single_record('tbl_kist_package', ['emi' => $data['package']],'*');
                if (!empty($user)) {
                    $emiAmount = $EMI['emi'];
                    if ($balance['balance'] >= $emiAmount) {
                            $senderData = [
                                'user_id' => $this->session->userdata['user_id'],
                                'amount' => -$emiAmount,
                                'type' => 'Instalment Fund',
                                'created_at' => date('Y-m-d H:i:s'),
                            ];
                            add('tbl_wallet', $senderData);

                            $emiAdd = [
                                'user_id' => $this->session->userdata['user_id'],
                                'emi' => $EMI['emi'],
                                'total_emi' => $EMI['emi']*$EMI['month'],
                                'month' => $EMI['month']-1,
                                'total_month' => $EMI['month'],
                                'emi_date' => date('Y-m-d H:i:s')
                            ];
                            add('tbl_emi_plan',$emiAdd);
                            $addemi = [
                                'user_id' => $this->session->userdata['user_id'],
                                'amount' => $EMI['emi'],
                                'created_at' => date('Y-m-d'),
                                'emi_status' =>1
                             ];
                             add('tbl_kist_add',$addemi);
                            set_flashdata('message', span_success('Select ' . $data['package'] . ' Successfully'));
                             redirect('dashboard/select_installment_small');
                     
                    } else {
                        set_flashdata('message', span_danger('Insufficient Balance'));
                    }    
                } else {
                    set_flashdata('message', span_danger('Invalid User ID'));
                }
            }
        }
        $response['balance'] = get_single_record('tbl_wallet', ['user_id' => $this->session->userdata['user_id']], 'IFNULL(SUM(amount), 0) as balance');
        $response['extra_header'] = true;
        $response['header'] = 'Monthly Installment';
        $response['header2'] = 'Wallet Balance: ' . currency;
        $this->load->view('kist_package', $response);
    } else {
        redirect('login');
    }
}

public function kist_entry() {
    if (is_logged_in()) {
        $response = array();
        $message = 'kist_entry';
        $response['header'] = 'EMI Entry 10 Months';
        $response['header2'] = 'Wallet Balance: ' . currency;
        $response['form_open'] = form_open(base_url('dashboard/emi_entry'));
        $user_id = $this->session->userdata['user_id'];
         $getEMIpending = $this->User_model->get_single_record('tbl_kist_add', ['user_id' => $user_id,'emi_status' =>0], 'ifnull(sum(amount),0)as totalEmi,ifnull(sum(tax),0)as totalTax,,amount');
                 $EMiTotal = $getEMIpending['totalEmi']+$getEMIpending['totalTax'];
                 if(date('d') >=1 && date('d') <=5){
                    $EMiTotal = $EMiTotal;
                 }elseif(date('d') >= 6 && date('d') <=10){
                    $date  = date('d');
                    $dateMulti =  $date-5;
                    $addTexMulti = $dateMulti*100;
                    $addTex = $getEMIpending['amount']+$addTexMulti;
                    $EMiTotal = $EMiTotal+$addTex;
                 }
        $response['form'] = [
            'error' => '<span class="text-danger" id="errorMessage"></span>',
            'amount' => form_label('Amount', 'amount') . form_input(['type' => 'number', 'step' => 0.01, 'name' => 'amount', 'id' => 'amount','value' => ''.$EMiTotal.'', 'class' => 'form-control', 'placeholder' => 'Enter Amount','readonly' => true]),
        ];
        $response['form_button'] = [
            'submit' => form_submit('kistentryTransfer', 'Transfer', ['class' => 'btn btn-info', 'id' => 'kistentryTransfer', 'style' => 'display: block;'])
        ];
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
           $data = $this->security->xss_clean($this->input->post());
             $this->form_validation->set_rules('amount','Amount', 'trim|required|numeric');
            if ($this->form_validation->run() !== FALSE) {
                 $balance = get_single_record('tbl_wallet', ['user_id' => $user_id], 'IFNULL(SUM(amount), 0) as balance');
                    if ($balance['balance'] >= $EMiTotal) {
                        $senderData = [
                            'user_id' => $user_id,
                            'amount' => -$EMiTotal,
                            'type' => 'Instalment Fund',
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        add('tbl_wallet', $senderData);
                        update('tbl_kist_add',['user_id' => $this->session->userdata['user_id']],['emi_status' =>1]);
                        set_flashdata($message, span_success('EMI Added Successfully'));
                    } else {
                        set_flashdata($message, span_danger('Insufficient Balance'));
                    }
            } else {
                set_flashdata($message, span_danger(validation_errors()));
            }
        }
        $response['message'] = $message;
        $response['balance'] = get_single_record('tbl_wallet', ['user_id' => $user_id], 'IFNULL(SUM(amount), 0) as balance');
         $response['getEMIpending'] = $this->User_model->get_single_record('tbl_kist_add', ['user_id' => $user_id,'emi_status' =>0], 'ifnull(sum(amount),0)as totalEmi,ifnull(sum(tax),0)as totalTax,amount');
        $response['totalEmiAmount'] = get_single_record('tbl_kist_add', ['user_id' => $user_id,'emi_status' => 0], 'IFNULL(SUM(amount), 0) as balance');
        $this->load->view('kist', $response);
    } else {
        redirect('login');
    }
}

public function emi_history()
    {
        if (is_logged_in()) {
            $response['header'] = 'EMI History Monthly Installment';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = array('user_id' => $this->session->userdata['user_id']);
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_kist_add', $where, '*', 'dashboard/emi_history', 3, 10);
            $response['path'] =  $records['path'];

            $response['field'] = '';
            $response['thead'] = '<tr>
            
                                <th>#</th>
                                <th>User ID</th>
                                <th>Amount</th>
                                <th>Monthly Benifit</th>
                                <th>Tax</th>
                                <th>Status</th>
                                <th>Total Pay</th>
                                <th>Date</th>

                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                if($rec['emi_status'] == 0){
                    $emi_status = badge_warning('Pending');
                }else{
                    $emi_status = badge_success('Achieved');
                }
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $amount. '</td>
                                <td>' . $monthly_benifit. '</td>
                                <td>' . $tax . '</td>
                                <td>' . $emi_status . '</td>
                                <td>' . ($amount + $tax) . '</td>
                                <td>' . $created_at. '</td>

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


    public function select_installment2() {
    if (is_logged_in()) {
        $response = array();
             $addDropDwn = [];
        $packageEMI = get_records('tbl_kist_package2',[],'*');
        foreach($packageEMI as $key => $EMI){
            $addDropDwn[$EMI['emi']] = $EMI['emi'];
        }
        $drop = form_dropdown('package', $addDropDwn,'package', ['class' => 'form-control']);
        $response['form_open'] = form_open(base_url('dashboard/select_installment_big'));
        $response['form'] = [
            'package' => form_label('Choose Package', 'package') . $drop,
        ];
        $response['form_button'] = [
            'submit' => form_submit('updateProfile', 'Submit', ['class' => 'btn btn-info', 'id' => 'updateProfile', 'style' => 'display: block;'])
        ];
         
        
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = $this->security->xss_clean($this->input->post());
         $this->form_validation->set_rules('package', 'EMI', 'trim|required');
            if ($this->form_validation->run() != FALSE) {
                $user = get_single_record('tbl_users', ['user_id' => $this->session->userdata['user_id']], '*');
                $balance = get_single_record('tbl_wallet', ['user_id' => $this->session->userdata['user_id']], 'IFNULL(SUM(amount), 0) as balance');
                $EMI = get_single_record('tbl_kist_package2', ['emi' => $data['package']],'*');
                if (!empty($user)) {
                    $emiAmount = $EMI['emi'];
                    if ($balance['balance'] >= $emiAmount) {
                            $senderData = [
                                'user_id' => $this->session->userdata['user_id'],
                                'amount' => -$emiAmount,
                                'type' => 'Instalment Fund',
                                'created_at' => date('Y-m-d H:i:s'),
                            ];
                            add('tbl_wallet', $senderData);

                            $emiAdd = [
                                'user_id' => $this->session->userdata['user_id'],
                                'emi' => $EMI['emi'],
                                'total_emi' => $EMI['emi']*$EMI['month'],
                                'month' => $EMI['month']-1,
                                'total_month' => $EMI['month'],
                                'emi_date' => date('Y-m-d H:i:s')
                            ];
                            add('tbl_emi_plan2',$emiAdd);
                            $addemi = [
                                'user_id' => $this->session->userdata['user_id'],
                                'amount' => $EMI['emi'],
                                'created_at' => date('Y-m-d'),
                             ];
                             add('tbl_kist_add2',$addemi);
                            set_flashdata('message', span_success('Select ' . $data['package'] . ' Successfully'));
                             redirect('ashboard/select_installment_big');
                     
                    } else {
                        set_flashdata('message', span_danger('Insufficient Balance'));
                    }    
                } else {
                    set_flashdata('message', span_danger('Invalid User ID'));
                }
            }
        }
        $response['balance'] = get_single_record('tbl_wallet', ['user_id' => $this->session->userdata['user_id']], 'IFNULL(SUM(amount), 0) as balance');
        $response['extra_header'] = true;
        $response['header'] = 'One Time Investment';
        $response['header2'] = 'Wallet Balance: ' . currency;
        $this->load->view('kist_package', $response);
    } else {
        redirect('login');
    }
}

public function kist_entry2() {
    if (is_logged_in()) {
        $response = array();
        $message = 'kist_entry2';
        $response['header'] = 'EMI Entry 20 Months';
        $response['header2'] = 'Wallet Balance: ' . currency;
        $response['form_open'] = form_open(base_url('dashboard/emi_entry_big'));
        $user_id = $this->session->userdata['user_id'];
         $getEMIpending = $this->User_model->get_single_record('tbl_kist_add2', ['user_id' => $user_id,'emi_status' =>0], 'ifnull(sum(amount),0)as totalEmi,ifnull(sum(tax),0)as totalTax,amount');
                 $EMiTotal = $getEMIpending['totalEmi']+$getEMIpending['totalTax'];
                 if(date('d') >=1 && date('d') <=5){
                    $EMiTotal = $EMiTotal;
                 }
                 // elseif(date('d') >= 6 && date('d') <=10){
                 //    $date  = date('d');
                 //    $dateMulti =  $date-5;
                 //    $addTexMulti = $dateMulti*200;
                 //    $addTex = $getEMIpending['amount']+$addTexMulti;
                 //     $EMiTotal = $EMiTotal+$addTex;
                 // }
        $response['form'] = [
            'error' => '<span class="text-danger" id="errorMessage"></span>',
            'amount' => form_label('Amount', 'amount') . form_input(['type' => 'number', 'step' => 0.01, 'name' => 'amount', 'id' => 'amount','value' => ''.$EMiTotal.'', 'class' => 'form-control', 'placeholder' => 'Enter Amount','readonly' => true]),
        ];
        $response['form_button'] = [
            'submit' => form_submit('kistentryTransfer', 'Transfer', ['class' => 'btn btn-info', 'id' => 'kistentryTransfer', 'style' => 'display: block;'])
        ];
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
           $data = $this->security->xss_clean($this->input->post());
             $this->form_validation->set_rules('amount','Amount', 'trim|required|numeric');
            if ($this->form_validation->run() !== FALSE) {
                 $balance = get_single_record('tbl_wallet', ['user_id' => $user_id], 'IFNULL(SUM(amount), 0) as balance');
                    if ($balance['balance'] >= $EMiTotal) {
                        $senderData = [
                            'user_id' => $user_id,
                            'amount' => -$EMiTotal,
                            'type' => 'Instalment Fund',
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        add('tbl_wallet', $senderData);
                        update('tbl_kist_add2',['user_id' => $this->session->userdata['user_id']],['emi_status' =>1]);
                        set_flashdata($message, span_success('EMI Added Successfully'));
                    } else {
                        set_flashdata($message, span_danger('Insufficient Balance'));
                    }
            } else {
                set_flashdata($message, span_danger(validation_errors()));
            }
        }
        $response['message'] = $message;
        $response['balance'] = get_single_record('tbl_wallet', ['user_id' => $user_id], 'IFNULL(SUM(amount), 0) as balance');
         $response['getEMIpending'] = $this->User_model->get_single_record('tbl_kist_add', ['user_id' => $user_id,'emi_status' =>0], 'ifnull(sum(amount),0)as totalEmi,ifnull(sum(tax),0)as totalTax,amount');
        $response['totalEmiAmount'] = get_single_record('tbl_kist_add2', ['user_id' => $user_id,'emi_status' => 0], 'IFNULL(SUM(amount), 0) as balance');
        $this->load->view('kist2', $response);
    } else {
        redirect('login');
    }
}


public function emi_history2()
    {
        if (is_logged_in()) {
            $response['header'] = 'EMI History One Time Investment';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = array('user_id' => $this->session->userdata['user_id']);
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_kist_add2', $where, '*', 'dashboard/emi_history_big', 3, 10);
            $response['path'] =  $records['path'];

            $response['field'] = '';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Amount</th>
                                <th>Monthly Benifit</th>
                                <th>Tax</th>
                                <th>Status</th>
                                <th>Total Pay</th>
                                <th>Date</th>

                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                 if($rec['emi_status'] == 0){
                    $emi_status = badge_warning('Pending');
                }else{
                    $emi_status = badge_success('Achieved');
                }
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $amount. '</td>
                                <td>' . $monthly_benifit. '</td>
                                <td>' . $tax . '</td>
                                <td>' . $status . '</td>
                                <td>' . ($amount + $tax) . '</td>
                                <td>' . $created_at. '</td>

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


}    
?>