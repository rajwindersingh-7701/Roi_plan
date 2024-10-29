<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'pagination'));
        $this->load->model(array('Main_model'));
        $this->load->helper(array('admin', 'security', 'super'));
    }

    public function index()
    {
        if (is_admin()) {
            redirect('admin/dashboard');
        } else {
            redirect('admin/login');
        }
    }


    public function ActivationHistory()
    {
        if (is_admin()) {
            $response['header'] = 'Activation History';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = [];
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_activation_details', $where, '*', 'Admin/Report/ActivationHistory', 4, 10);
            $response['path'] =  $records['path'];
            $response['field'] = '<div class="col-4">
                                  <select class="form-control" name="type">
                                      <option value="user_id" ' . $type . ' == "user_id" ? "selected" : "";?>
                                          User ID</option>
                                  </select>
                                </div>
                              <div class="col-4">
                                  <input type="text" name="value" class="form-control text-white float-right"
                                      value="' . $value . '" placeholder="Search">
                              </div>
                              <div class="col-4">
                                  <div class="input-group-append">
                                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                  </div>
                              </div>';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Amount</th>
                                <th>Date</th>

                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                // $button =  form_open().form_hidden('orderID',$order_id).form_submit(['type' => 'submit','class' => 'btn btn-success','value' => 'Withdraw']); 
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $package . '</td>
                                <td>' . $topup_date . '</td>
                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $this->load->view('reports', $response);
        } else {
            redirect('admin/login');
        }
    }





    public function availableIncome()
    {
        if (is_admin()) {
            $response['header'] = 'Available Income Balance';

            $response['availableIncome'] = $this->Main_model->availableIncome();
            // foreach ($incoems as $key => $inc) {
            //     pr($inc);
            // }
            $this->load->view('availabe_income', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function availableIncome1()
    {
        if (is_admin()) {
            $response['header'] = 'Available Income Balance';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = [];
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_users', $where, '*', 'admin/availableIncome', 3, 10);
            $response['path'] =  $records['path'];
            $response['field'] = '<div class="col-4">
                                  <select class="form-control" name="type">
                                      <option value="user_id" ' . $type . ' == "user_id" ? "selected" : "";?>
                                          User ID</option>
                                  </select>
                                </div>
                              <div class="col-4">
                                  <input type="text" name="value" class="form-control float-right"
                                      value="' . $value . '" placeholder="Search">
                              </div>
                              <div class="col-4">
                                  <div class="input-group-append">
                                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                  </div>
                              </div>';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Total Package</th>
                                <th>Total Income</th>
                                <th>Available Balance</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $income_balance = get_single_record('tbl_income_wallet', 'user_id = "' . $user_id . '"', 'ifnull(sum(amount),0) as income_balance');
                $total_income = get_single_record('tbl_income_wallet', 'user_id = "' . $user_id . '" and amount >0', 'ifnull(sum(amount),0) as total_income ');

                $user = get_single_record('tbl_users', 'user_id = "' . $user_id . '"', 'name,total_package');

                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $user['name'] . '</td>
                                <td>' . $user['total_package'] . '</td>
                                <td>' . $total_income['total_income'] . '</td>
                                <td>' . $income_balance['income_balance'] . '</td>
                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $response['segment'] = $records['segment'];
            $response['total_records'] = $records['total_records'];
            $response['i'] = $i;
            $this->load->view('reports', $response);
        } else {
            redirect('admin/login');
        }
    }



     public function ActivationReport()
    {
        if (is_admin()) {
            $response['header'] = 'Activation History';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = [];
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_activation_details', $where, '*', 'Admin/Report/ActivationReport', 4, 10);
            $response['path'] =  $records['path'];
            $response['field'] = '<div class="col-4">
                                  <select class="form-control" name="type">
                                      <option value="user_id" ' . $type . ' == "user_id" ? "selected" : "";?>
                                          User ID</option>
                                  </select>
                                </div>
                              <div class="col-4">
                                  <input type="text" name="value" class="form-control text-white float-right"
                                      value="' . $value . '" placeholder="Search">
                              </div>
                              <div class="col-4">
                                  <div class="input-group-append">
                                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                  </div>
                              </div>';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Amount</th>
                                <th>Date</th>

                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                // $button =  form_open().form_hidden('orderID',$order_id).form_submit(['type' => 'submit','class' => 'btn btn-success','value' => 'Withdraw']); 
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $package . '</td>
                                <td>' . $topup_date . '</td>
                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $this->load->view('reports', $response);
        } else {
            redirect('admin/login');
        }
    }


}
