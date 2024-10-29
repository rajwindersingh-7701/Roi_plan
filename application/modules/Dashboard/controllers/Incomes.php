<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Incomes extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'pagination'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'super'));
        if (is_logged_in() === false) {
            redirect('Dashboard/UserInfo/logout');
            exit;
        }
    }


    public function payout_summary()
    {
        if (is_logged_in()) {
            $config_incomes = $this->config->item('incomes');
            $response['header'] = 'Payout Summary';
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            $export = $this->input->get('export');
            if (!empty($start_date)) {
                $where = 'date(created_at) >= "' . $start_date . '" AND date(created_at) <= "' . $end_date . '" and type != "withdraw_request" and user_id = "' . $this->session->userdata['user_id'] . '"';
                $rowCount = $this->User_model->payout_sum2('tbl_income_wallet', $where, 'date(created_at) as date');
            } else {
                $where = array('type !=' => 'withdraw_request', 'user_id' => $this->session->userdata['user_id']);
                $rowCount = $this->User_model->payout_sum('tbl_income_wallet',  'date(created_at) as date');
            }
            $config['total_rows'] = count($rowCount);
            $config['base_url'] = base_url() . 'dashboard/payout-summary';
            $config['uri_segment'] = 3;
            $config['per_page'] = 10;
            $config['suffix'] = '?' . http_build_query($_GET);
            $config['attributes'] = array('class' => 'page-link');
            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = '</ul>';
            $config['num_tag_open'] = '<li class="paginate_button page-item ">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="paginate_button page-item  active"><a href="#" class="page-link">';
            $config['cur_tag_close'] = '</a></li>';
            $config['prev_tag_open'] = '<li class="paginate_button page-item ">';
            $config['prev_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li class="paginate_button page-item">';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li class="paginate_button page-item next">';
            $config['last_tag_close'] = '</li>';
            $config['prev_link'] = 'Previous';
            $config['prev_tag_open'] = '<li class="paginate_button page-item previous">';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = 'Next';
            $config['next_tag_open'] = '<li  class="paginate_button page-item next">';
            $config['next_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            $segment = $this->uri->segment(3);
            $records['records'] = $this->User_model->payout_summary($where, $config['per_page'], $segment);
            if ($export) {
                $application_type = 'application/' . $export;
                $header = ["#", "Date", "Direct Income", "Level Income", "Pool Income", "Total Payout"];
                foreach ($records['records'] as $key => $request) {
                    $incomes = $this->User_model->get_incomes('tbl_income_wallet', 'date(created_at) = "' . $request['date'] . '"', 'ifnull(sum(amount),0) as sum,type');
                    $calculate = calculate_income($incomes);
                    $records_export[$key]['i'] = ($key + 1);
                    $records_export[$key]['date'] = $request['date'];
                    foreach ($config_incomes as $inc_type => $value) {
                        $records_export[$key][$inc_type] = number_format($calculate[$inc_type], 2);
                        $records_export[$key]['total_payout'] = number_format($calculate['total_payout'], 2);
                    }
                }
                finalExport($export, $application_type, $header, $records_export);
            }
            $response['path'] =  $config['base_url'];
            $searchField = '<div class="col-4">
                                <input type="date" name="start_date" class="form-control"
                                value="' . $start_date . '" placeholder="Start Date">
                            </div>
                            <div class="col-4">
                                <input type="date" name="end_date" class="form-control"
                                value="' . $end_date . '" placeholder="End Date">
                            </div>
                            <div class="col-4">
                            <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                          </div>
                            </div> 
                            
                            </div>';
            $response['field'] = '';
            $response['thead'] = '<tr>
                                   <th>#</th>
                                   <th>Date</th>';
            foreach ($config_incomes as $value) {
                $response['thead'] .= '<th>' . $value . '</th>';
            }
            $response['thead'] .=  '<th>Total</th>
                                   <th>Action</th>
                                 </tr>';
            $tbody = [];
            $i = $segment + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $incomes = $this->User_model->get_incomes('tbl_income_wallet', 'date(created_at) = "' . $rec['date'] . '" and user_id = "' . $this->session->userdata['user_id'] . '"', 'ifnull(sum(amount),0) as sum,type');
                $calculate = calculate_income($incomes);
                $tbody[$key]  =  '<tr>
                                   <td>' . $i . '</td>
                                   <td>' . $rec['date'] . '</td>';
                foreach ($config_incomes as $inc_type => $value) {
                    $tbody[$key] .=  ' <td>' . number_format($calculate[$inc_type], 2) . '</td>';
                }
                $tbody[$key] .=
                    ' <td>' . number_format($calculate['total_payout'], 2) . '</td>
                                   <td><a href="' . base_url('dashboard/datewise-payout/' . $rec['date']) . '">View</a></td>
                                 </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $response['total_income'] = '';
            $this->load->view('reports', $response);
        } else {
            redirect('Admin/Management/login');
        }
    }
    public function week_payout_summary()
    {
        if (is_logged_in()) {
            $response = array();
            $response['records'] = $this->User_model->week_summary();
            foreach ($response['records'] as $key => $record) {
                //
                $incomes = $this->User_model->get_incomes('tbl_income_wallet', 'WEEK(created_at)%MONTH(created_at)+1 = "' . $record['date'] . '" and user_id = "' . $this->session->userdata['user_id'] . '" and amount > 0', 'ifnull(sum(amount),0) as sum,type');
                $response['records'][$key]['incomes'] = calculate_income($incomes);
            }
            $response['type'] = get_records('tbl_income_wallet', " amount > '0' Group by type", 'type');
            $this->load->view('week_payout_summary', $response);
        } else {
            redirect('Dashboard/User/login');
        }
    }

    public function weekWisePayout($date = '')
    {
        if (is_logged_in()) {
            $response['header'] = 'Week Payout Summary';
            // $config['base_url'] = base_url() . 'Dashboard/Settings/incomeLedgar';
            // $config['total_rows'] = get_sum('tbl_income_wallet', 'date(created_at) = "'.$date.'" and user_id = "'.$this->session->userdata['user_id'].'"', 'ifnull(count(id),0) as sum');
            // $config ['uri_segment'] = 4;
            // $config['per_page'] = 100;
            // $this->pagination->initialize($config);
            // $segment = $this->uri->segment(4);
            $response['total_income'] = get_single_record('tbl_income_wallet', 'week(created_at)+1 = "' . $date . '" and user_id = "' . $this->session->userdata['user_id'] . '"', 'ifnull(sum(amount),0) as total_income');
            $response['user_incomes'] = get_records('tbl_income_wallet', 'week(created_at)+1 = "' . $date . '" and user_id = "' . $this->session->userdata['user_id'] . '"', '*');
            //$response['segament'] = 0;
            // pr($response,true);
            $this->load->view('incomes', $response);
        } else {
            redirect('Dashboard/User/login');
        }
    }


    public function dateWisePayout($date = '')
    {
        if (is_logged_in()) {
            $response['header'] = 'Datewise Payout Summary';
            $where = ['date(created_at)' => $date, 'type !=' => 'withdraw_request', 'user_id' => $this->session->userdata['user_id']];
            $records = pagination('tbl_income_wallet', $where, '*', 'dashboard/datewise-payout/' . $date, 4, 10);
            $response['path'] =  $records['path'];
            $response['field'] = '';
            $response['thead'] = '<tr>
                                    <th>#</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Desciption</th>
                                    <th>Date</th>
                                  
                                 </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $tbody[$key]  = ' <tr>
                                    <td>' . $i . '</td>
                                    <td>' . $amount . '</td>
                                    <td>' . ucwords(str_replace('_', ' ', $type)) . '</td>
                                    <td>' . ($amount > 0  ? badge_success('Credit') : badge_danger('Debit')) . '</td>
                                    <td>' . $description . '</td>
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


    public function incomes($getType)
    {
        if (is_logged_in()) {
            $response['header'] = ucwords(str_replace('_', ' ', $getType));
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = ['user_id' => $this->session->userdata['user_id'], 'type' => $getType];
            if (!empty($type)) {
                $where = [$type => $value, 'user_id' => $this->session->userdata['user_id'], 'type' => $getType];
            }

            $table = "tbl_income_wallet";
            $records = pagination($table, $where, '*', 'dashboard/incomes/' . $getType, 4, 10);
            $response['path'] =  $records['path'];
            $response['field'] = '';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th>Date</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . currency . $amount . '</td>
                                <td>' . $this->config->item('incomes')[$getType] . '</td>
                                <td>' . ($amount > 0  ? badge_success('Credit') : badge_danger('Debit')) . '</td>
                                <td>' . $description . '</td>
                                <td>' . $created_at . '</td>

                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $response['balance'] = true;
            $response['total_income'] = get_sum($table, $where, 'ifnull(sum(amount),0) as sum');
            $this->load->view('reports', $response);
        } else {
        }
    }

    public function incomesLedger()
    {
        if (is_logged_in()) {
            $response['header'] = 'Income Ledger';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = ['user_id' => $this->session->userdata['user_id']];
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_income_wallet', $where, '*', 'dashboard/income-ledger/', 3, 10);
            $response['path'] =  $records['path'];
            $response['field'] = '';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th>Date</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                // $button =  form_open().form_hidden('orderID',$order_id).form_submit(['type' => 'submit','class' => 'btn btn-success','value' => 'Withdraw']);
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . currency . $amount . '</td>
                                <td>' . ((!empty($this->config->item('incomes')[$type]) ? $this->config->item('incomes')[$type] : $type)) . '</td>
                                <td>' . ($amount > 0  ? badge_success('Credit') : badge_danger('Debit')) . '</td>
                                <td>' . $description . '</td>
                                <td>' . $created_at . '</td>
                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $response['balance'] = true;
            $response['total_income'] = get_sum('tbl_wallet', $where, 'ifnull(sum(amount),0) as sum');
            $this->load->view('reports', $response);
        } else {
            redirect('login');
        }
    }
}
