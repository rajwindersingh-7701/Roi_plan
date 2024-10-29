<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Management extends CI_Controller
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
            $response = array();
            $response['total_users'] = get_sum('tbl_users', array(), 'ifnull(count(id),0) as sum');
            $response['paid_users'] = get_sum('tbl_users', array('paid_status' => '1'), 'ifnull(count(id),0) as sum');
            $response['totalSms'] = get_single_record('tbl_sms_counter', [], 'count(id) as totalSms');
            $response['admin'] = get_single_record('tbl_admin', [], '*');
            $response['today_joined_users'] = get_sum('tbl_users', 'date(created_at) = date(now())', 'ifnull(count(id),0) as sum');
            $response['today_paid_users'] = get_sum('tbl_users', 'date(created_at) = date(now()) and paid_status > 0', 'ifnull(count(id),0) as sum');
            $response['total_payout'] = get_sum('tbl_income_wallet', array('amount > ' => 0), 'ifnull(sum(amount),0) as sum');
            $response['direct_income'] = get_sum('tbl_income_wallet', array('type' => 'direct_income'), 'ifnull(sum(amount),0) as sum , type');
            $response['total_sent_fund'] = get_sum('tbl_wallet', array(), 'ifnull(sum(amount),0) as sum');
            $response['used_fund'] = get_sum('tbl_wallet', array('amount <' => '0'), 'ifnull(sum(amount),0) as sum ');
            $response['requested_fund'] = get_sum('tbl_payment_request', array(), 'ifnull(sum(amount),0) as sum');
            $response['today_business'] = get_sum('tbl_users', 'date(topup_date) = date(now())', 'ifnull(sum(package_amount),0) as sum');
            $response['todayPair'] = $this->Main_model->todayPair();
            $response['total_mail'] = get_sum('tbl_support_message', array('user_id !=' => 'adminstator'), 'ifnull(count(id),0) as sum');
            $response['pending_mail'] = get_sum('tbl_support_message', array('status' => '0', 'user_id !=' => 'adminstator'), 'ifnull(count(id),0) as sum');
            $response['approved_mail'] = get_sum('tbl_support_message', array('status' => '1', 'user_id !=' => 'adminstator'), 'ifnull(count(id),0) as sum');

            $this->load->view('dashboard', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function get_user($user_id = '')
    {
        if (is_admin()) {
            $response = array();
            $response['success'] = 0;
            $user = get_single_record('tbl_users', array('user_id' => $user_id), 'id,user_id,sponser_id,role,name,email,phone,paid_status,created_at');
            if (!empty($user)) {
                $response['success'] = 1;
                $response['message'] = 'user Found';
                $response['user'] = $user;
                echo $user['name'];
            } else {
                echo 'User Not Found';
            }
        } else {
            redirect('admin/login');
        }
    }
    public function users()
    {
        if (is_admin()) {
            $response['header'] = 'All Users';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $export = $this->input->get('export');
            $where = array($type => $value);
            if (empty($where[$type]))
                $where = array();
            $records = pagination('tbl_users', $where, '*', 'admin/users/', 3, 10);
            if ($export) {
                $application_type = 'application/' . $export;
                $header = ['#', 'User ID', 'Name', 'Phone', 'Email', 'Password', 'Transaction Pin', 'Sponsor ID', 'Package', 'E-wallet', 'Income', 'Joining Date'];
                foreach ($records['records'] as $key => $rec) {
                    $e_wallet = get_single_record('tbl_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as e_wallet');
                    $income_wallet = get_single_record('tbl_income_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as income_wallet');
                    $records_export[$key]['i'] = ($key + 1);
                    $records_export[$key]['user_id'] = $rec['user_id'];
                    $records_export[$key]['name'] = $rec['name'];
                    $records_export[$key]['phone'] = $rec['phone'];
                    $records_export[$key]['email'] = $rec['email'];
                    $records_export[$key]['password'] = $rec['password'];
                    $records_export[$key]['master_key'] = $rec['master_key'];
                    $records_export[$key]['sponser_id'] = $rec['sponser_id'];
                    $records_export[$key]['package_amount'] = $rec['package_amount'];
                    $records_export[$key]['e_wallet'] = $e_wallet['e_wallet'];
                    $records_export[$key]['income_wallet'] = $income_wallet['income_wallet'];
                    $records_export[$key]['created_at'] = $rec['created_at'];
                }
                finalExport($export, $application_type, $header, $records_export);
            }
            $response['path'] =  $records['path'];
            $response['field'] = '<div class="col-4">
                        <select class="form-control" name="type">
                            <option value="user_id" ' . $type . ' == "user_id" ? "selected" : "";?>User ID</option>
                            <option value="name" ' . $type . ' == "name" ? "selected" : "";?>Name</option>
                            <option value="phone" ' . $type . ' == "phone" ? "selected" : "";?>Phone</option>
                            <option value="sponser_id" ' . $type . ' == "sponser_id" ? "selected" : "";?>Sponser ID</option>
                        </select>
                        </div>
                        <div class="col-4">
                        <input type="text" name="value" class="form-control text-dark float-right"
                            value="' . $value . '" placeholder="Search">
                        </div>
                        <div class="col-4">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                        </div>
                        </div>';
            $response['thead'] = '<tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Password</th>
                                    <th>TXN Pin</th>
                                    <th>Position</th>
                                    <th>Sponsor ID</th>
                                    <th>Package</th>
                                    <th>Package ID</th>
                                    <th>Activation Date</th>
                                    <th>E-wallet</th>
                                    <th>Income</th>
                                    <th>Active Month Income</th>
                                    <th>Joining Date</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $e_wallet = get_single_record('tbl_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as e_wallet');
                $income_wallet = get_single_record('tbl_income_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as income_wallet');
                $btn_disabled = '<a class="blockUser btn btn-' . ($disabled == 0 ? "danger" : "primary") . '" data-status="' . ($disabled == 0 ? "1" : "0") . '" data-user_id="' . $user_id . '">' . ($disabled == 0 ? "Block" : " UnBlock") . '</a> ';
                $login = '<a class=" btn btn-info "  href="' . base_url('admin/user-login/') . $user_id . '" target="_blank">Login</a>';
                $edit = '<a class=" btn btn-warning "  href="' . base_url('admin/edit-user/') . $user_id . '" target="_blank">Edit</a>';
                // $active_month_income_wallet = get_single_record(
                //     'tbl_income_wallet',
                //     'user_id = "' . $rec['user_id'] . '" AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())',
                //     'IFNULL(SUM(amount), 0) AS income_wallet'
                // );

                $active_month_income_wallet = get_single_record(
                    'tbl_income_wallet',
                    ['user_id' => $rec['user_id'], 'MONTH(created_at)' => date('n'), 'YEAR(created_at)' => date('Y')],
                    'IFNULL(SUM(amount), 0) AS income_wallet'
                );
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <div><td colspan="1"> ' . $login . ' ' . $edit . ' ' . $btn_disabled . '</td></div>
                                <td>' . $user_id . '</td>
                                <td>' . $name . '</td>
                                <td>' . $phone . '</td>
                                <td>' . $password . '</td>
                                <td>' . $master_key . '</td>
                                <td>' . $position . '</td>
                                <td>' . $sponser_id . '</td>
                                <td>' . $package_amount . '</td>
                                <td>' . $package_id . '</td>
                                <td>' . $topup_date . '</td>
                                <td>' . $e_wallet['e_wallet'] . '</td>
                                <td>' . $income_wallet['income_wallet'] . '</td>
                                <td>' . $active_month_income_wallet['income_wallet'] . '</td>
                                <td>' . $created_at . '</td>
                             </tr>';
                $i++;
            }
            $response['segment'] = $records['segment'];
            $response['total_records'] = $records['total_records'];
            $response['i'] = $i;
            $response['tbody'] = $tbody;
            $response['export'] = true;
            $response['script'] = true;
            $this->load->view('reports', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function todayJoinedusers()
    {
        if (is_admin()) {
            $response['header'] = 'Today Joined Users';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $export = $this->input->get('export');
            $where = array($type => $value, 'date(created_at)' => date('Y-m-d'));
            if (empty($where[$type]))
                $where = array('date(created_at)' => date('Y-m-d'));
            $records = pagination('tbl_users', $where, '*', 'admin/todayJoined/', 3, 10);
            if ($export) {
                $application_type = 'application/' . $export;
                $header = ['#', 'User ID', 'Name', 'Phone', 'Email', 'Password', 'Transaction Pin', 'Sponsor ID', 'Package', 'E-wallet', 'Income', 'Joining Date'];
                foreach ($records['records'] as $key => $rec) {
                    $e_wallet = get_single_record('tbl_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as e_wallet');
                    $income_wallet = get_single_record('tbl_income_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as income_wallet');
                    $records_export[$key]['i'] = ($key + 1);
                    $records_export[$key]['user_id'] = $rec['user_id'];
                    $records_export[$key]['name'] = $rec['name'];
                    $records_export[$key]['phone'] = $rec['phone'];
                    $records_export[$key]['email'] = $rec['email'];
                    $records_export[$key]['password'] = $rec['password'];
                    $records_export[$key]['master_key'] = $rec['master_key'];
                    $records_export[$key]['sponser_id'] = $rec['sponser_id'];
                    $records_export[$key]['package_amount'] = $rec['package_amount'];
                    $records_export[$key]['e_wallet'] = $e_wallet['e_wallet'];
                    $records_export[$key]['income_wallet'] = $income_wallet['income_wallet'];
                    $records_export[$key]['created_at'] = $rec['created_at'];
                }
                finalExport($export, $application_type, $header, $records_export);
            }
            $response['path'] =  $records['path'];
            $response['field'] = '<div class="col-4">
                        <select class="form-control" name="type">
                            <option value="user_id" ' . $type . ' == "user_id" ? "selected" : "";?>User ID</option>
                            <option value="name" ' . $type . ' == "name" ? "selected" : "";?>Name</option>
                            <option value="phone" ' . $type . ' == "phone" ? "selected" : "";?>Phone</option>
                            <option value="sponser_id" ' . $type . ' == "sponser_id" ? "selected" : "";?>Sponser ID</option>
                        </select>
                        </div>
                        <div class="col-4">
                        <input type="text" name="value" class="form-control text-dark float-right"
                            value="' . $value . '" placeholder="Search">
                        </div>
                        <div class="col-4">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                        </div>
                        </div>';
            $response['thead'] = '<tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Password</th>
                                    <th>TXN Pin</th>
                                    <th>Position</th>
                                    <th>Sponsor ID</th>
                                    <th>Package</th>
                                    <th>Package ID</th>
                                    <th>Activation Date</th>
                                    <th>E-wallet</th>
                                    <th>Income</th>
                                    <th>Joining Date</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $e_wallet = get_single_record('tbl_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as e_wallet');
                $income_wallet = get_single_record('tbl_income_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as income_wallet');
                $btn_disabled = '<a class="blockUser btn btn-' . ($disabled == 0 ? "danger" : "primary") . '" data-status="' . ($disabled == 0 ? "1" : "0") . '" data-user_id="' . $user_id . '">' . ($disabled == 0 ? "Block" : " UnBlock") . '</a> ';
                $login = '<a class=" btn btn-info "  href="' . base_url('admin/user-login/') . $user_id . '" target="_blank">Login</a>';
                $edit = '<a class=" btn btn-warning "  href="' . base_url('admin/edit-user/') . $user_id . '" target="_blank">Edit</a>';

                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <div><td colspan="1"> ' . $login . ' ' . $edit . ' ' . $btn_disabled . '</td></div>
                                <td>' . $user_id . '</td>
                                <td>' . $name . '</td>
                                <td>' . $phone . '</td>
                                <td>' . $password . '</td>
                                <td>' . $master_key . '</td>
                                <td>' . $position . '</td>
                                <td>' . $sponser_id . '</td>
                                <td>' . $package_amount . '</td>
                                <td>' . $package_id . '</td>
                                <td>' . $topup_date . '</td>
                                <td>' . $e_wallet['e_wallet'] . '</td>
                                <td>' . $income_wallet['income_wallet'] . '</td>
                                <td>' . $created_at . '</td>
                             </tr>';
                $i++;
            }
            $response['segment'] = $records['segment'];
            $response['total_records'] = $records['total_records'];
            $response['i'] = $i;
            $response['tbody'] = $tbody;
            $response['export'] = true;
            $response['script'] = true;
            $this->load->view('reports', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function paidUsers()
    {
        if (is_admin()) {
            $response['header'] = 'Paid Users';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $export = $this->input->get('export');
            $where = array($type => $value, 'paid_status' => 1);
            if (empty($where[$type]))
                // $where = 'date(created_at) = date(now())';
                $where = array('paid_status' => 1);
            // $where = array();

            $records = pagination('tbl_users', $where, '*', 'admin/paid-users/', 3, 10);
            if ($export) {
                $application_type = 'application/' . $export;
                $header = ['#', 'User ID', 'Name', 'Phone', 'Email', 'Password', 'Transaction Pin', 'Sponsor ID', 'Package', 'E-wallet', 'Income', 'Joining Date'];
                foreach ($records['records'] as $key => $rec) {
                    $e_wallet = get_single_record('tbl_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as e_wallet');
                    $income_wallet = get_single_record('tbl_income_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as income_wallet');
                    $records_export[$key]['i'] = ($key + 1);
                    $records_export[$key]['user_id'] = $rec['user_id'];
                    $records_export[$key]['name'] = $rec['name'];
                    $records_export[$key]['phone'] = $rec['phone'];
                    $records_export[$key]['email'] = $rec['email'];
                    $records_export[$key]['password'] = $rec['password'];
                    $records_export[$key]['master_key'] = $rec['master_key'];
                    $records_export[$key]['sponser_id'] = $rec['sponser_id'];
                    $records_export[$key]['package_amount'] = $rec['package_amount'];
                    $records_export[$key]['e_wallet'] = $e_wallet['e_wallet'];
                    $records_export[$key]['income_wallet'] = $income_wallet['income_wallet'];
                    $records_export[$key]['created_at'] = $rec['created_at'];
                }
                finalExport($export, $application_type, $header, $records_export);
            }
            $response['path'] =  $records['path'];
            $response['field'] = '<div class="col-4">
                        <select class="form-control" name="type">
                            <option value="user_id" ' . $type . ' == "user_id" ? "selected" : "";?>
                                User ID</option>
                        </select>
                        </div>
                        <div class="col-4">
                        <input type="text" name="value" class="form-control text-dark float-right"
                            value="' . $value . '" placeholder="Search">
                        </div>
                        <div class="col-4">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                        </div>
                        </div>';
            $response['thead'] = '<tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Activater</th>
                                    <th>Password</th>
                                    <th>TXN Pin</th>
                                    <th>Sponsor ID</th>
                                    <th>Package</th>
                                    <th>Package ID</th>
                                    <th>Activation Date</th>
                                    <th>E-wallet</th>
                                    <th>Income</th>
                                    <th>Joining Date</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $activater = get_single_record('tbl_activation_details', array('user_id' => $rec['user_id']), 'activater');
                $e_wallet = get_single_record('tbl_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as e_wallet');
                $income_wallet = get_single_record('tbl_income_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as income_wallet');
                $btn_disabled = '<a class="blockUser btn btn-' . ($disabled == 0 ? "danger" : "primary") . '" data-status="' . ($disabled == 0 ? "1" : "0") . '" data-user_id="' . $user_id . '">' . ($disabled == 0 ? "Block" : " UnBlock") . '</a> ';
                $login = '<a class=" btn btn-info "  href="' . base_url('Admin/Management/user_login/') . $user_id . '" target="_blank">Login</a>';
                $edit = '<a class=" btn btn-warning "  href="' . base_url('Admin/Settings/EditUser/') . $user_id . '" target="_blank">Edit</a>';

                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <div><td colspan="1"> ' . $btn_disabled . '' . $login . '' . $edit . '</td></div>
                                <td>' . $user_id . '</td>
                                <td>' . $name . '</td>
                                <td>' . $activater['activater'] . '</td>
                                <td>' . $password . '</td>
                                <td>' . $master_key . '</td>
                                <td>' . $sponser_id . '</td>
                                <td>' . $package_amount . '</td>
                                <td>' . $package_id . '</td>
                                <td>' . $topup_date . '</td>
                                <td>' . $e_wallet['e_wallet'] . '</td>
                                <td>' . $income_wallet['income_wallet'] . '</td>
                                <td>' . $created_at . '</td>
                             </tr>';
                $i++;
            }
            $response['segment'] = $records['segment'];
            $response['total_records'] = $records['total_records'];
            $response['i'] = $i;
            $response['tbody'] = $tbody;
            $response['export'] = true;
            $response['script'] = true;
            $this->load->view('reports', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function TodaypaidUsers()
    {
        if (is_admin()) {
            $response['header'] = 'Today Paid Users';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $export = $this->input->get('export');
            $where = array($type => $value, 'paid_status' => 1, 'date(topup_date)' => date('Y-m-d'));
            if (empty($where[$type]))
                // $where = 'date(created_at) = date(now())';
                $where = array('paid_status' => 1, 'date(topup_date)' => date('Y-m-d'));
            // $where = array();

            $records = pagination('tbl_users', $where, '*', 'admin/todaypaid-users/', 3, 10);
            if ($export) {
                $application_type = 'application/' . $export;
                $header = ['#', 'User ID', 'Name', 'Phone', 'Email', 'Password', 'Transaction Pin', 'Sponsor ID', 'Package', 'E-wallet', 'Income', 'Joining Date'];
                foreach ($records['records'] as $key => $rec) {
                    $e_wallet = get_single_record('tbl_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as e_wallet');
                    $income_wallet = get_single_record('tbl_income_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as income_wallet');
                    $records_export[$key]['i'] = ($key + 1);
                    $records_export[$key]['user_id'] = $rec['user_id'];
                    $records_export[$key]['name'] = $rec['name'];
                    $records_export[$key]['phone'] = $rec['phone'];
                    $records_export[$key]['email'] = $rec['email'];
                    $records_export[$key]['password'] = $rec['password'];
                    $records_export[$key]['master_key'] = $rec['master_key'];
                    $records_export[$key]['sponser_id'] = $rec['sponser_id'];
                    $records_export[$key]['package_amount'] = $rec['package_amount'];
                    $records_export[$key]['e_wallet'] = $e_wallet['e_wallet'];
                    $records_export[$key]['income_wallet'] = $income_wallet['income_wallet'];
                    $records_export[$key]['created_at'] = $rec['created_at'];
                }
                finalExport($export, $application_type, $header, $records_export);
            }
            $response['path'] =  $records['path'];
            $response['field'] = '<div class="col-4">
                        <select class="form-control" name="type">
                            <option value="user_id" ' . $type . ' == "user_id" ? "selected" : "";?>
                                User ID</option>
                        </select>
                        </div>
                        <div class="col-4">
                        <input type="text" name="value" class="form-control text-dark float-right"
                            value="' . $value . '" placeholder="Search">
                        </div>
                        <div class="col-4">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                        </div>
                        </div>';
            $response['thead'] = '<tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Password</th>
                                    <th>TXN Pin</th>
                                    <th>Sponsor ID</th>
                                    <th>Package</th>
                                    <th>Package ID</th>
                                    <th>Activation Date</th>
                                    <th>E-wallet</th>
                                    <th>Income</th>
                                    <th>Joining Date</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $e_wallet = get_single_record('tbl_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as e_wallet');
                $income_wallet = get_single_record('tbl_income_wallet', array('user_id' => $rec['user_id']), 'ifnull(sum(amount),0) as income_wallet');
                $btn_disabled = '<a class="blockUser btn btn-' . ($disabled == 0 ? "danger" : "primary") . '" data-status="' . ($disabled == 0 ? "1" : "0") . '" data-user_id="' . $user_id . '">' . ($disabled == 0 ? "Block" : " UnBlock") . '</a> ';
                $login = '<a class=" btn btn-info "  href="' . base_url('Admin/Management/user_login/') . $user_id . '" target="_blank">Login</a>';
                $edit = '<a class=" btn btn-warning "  href="' . base_url('Admin/Settings/EditUser/') . $user_id . '" target="_blank">Edit</a>';

                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <div><td colspan="1"> ' . $btn_disabled . '' . $login . '' . $edit . '</td></div>
                                <td>' . $user_id . '</td>
                                <td>' . $name . '</td>
                                <td>' . $phone . '</td>
                                <td>' . $password . '</td>
                                <td>' . $master_key . '</td>
                                <td>' . $sponser_id . '</td>
                                <td>' . $package_amount . '</td>
                                <td>' . $package_id . '</td>
                                <td>' . $topup_date . '</td>
                                <td>' . $e_wallet['e_wallet'] . '</td>
                                <td>' . $income_wallet['income_wallet'] . '</td>
                                <td>' . $created_at . '</td>
                             </tr>';
                $i++;
            }
            $response['segment'] = $records['segment'];
            $response['total_records'] = $records['total_records'];
            $response['i'] = $i;
            $response['tbody'] = $tbody;
            $response['export'] = false;
            $response['script'] = true;
            $this->load->view('reports', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function user_login($user_id)
    {
        if (is_admin()) {
            $this->session->set_userdata('user_id', $user_id);
            redirect('dashboard');
        } else {
            redirect('admin/login');
        }
    }

    public function Genelogy($user_id = 'admin')
    {
        if (is_admin()) {
            $response = array();
            $response['level1'] = $this->Main_model->get_tree_user($user_id);
            $response['level2'][1] = $this->Main_model->get_tree_user($response['level1']->left_node);
            $response['level2'][2] = $this->Main_model->get_tree_user($response['level1']->right_node);
            if (!empty($response['level2'][1]->left_node))
                $response['level3'][1] = $this->Main_model->get_tree_user($response['level2'][1]->left_node);
            else
                $response['level3'][1] = array();
            if (!empty($response['level2'][1]->right_node))
                $response['level3'][2] = $this->Main_model->get_tree_user($response['level2'][1]->right_node);
            else
                $response['level3'][2] = array();
            if (!empty($response['level2'][2]->left_node))
                $response['level3'][3] = $this->Main_model->get_tree_user($response['level2'][2]->left_node);
            else
                $response['level3'][3] = array();
            if (!empty($response['level2'][2]->right_node))
                $response['level3'][4] = $this->Main_model->get_tree_user($response['level2'][2]->right_node);
            else
                $response['level3'][4] = array();
            $this->load->view('genelogy', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function Tree($user_id = 'adminadmin')
    {
        if (is_admin()) {
            $response = array();
            $response['user'] = get_single_record('tbl_users', array('user_id' => $user_id), 'id,user_id,sponser_id,role,name,email,phone,paid_status,created_at');
            $response['users'] = get_records('tbl_users', array('sponser_id' => $user_id), 'id,user_id,sponser_id,role,name,email,phone,paid_status,created_at');
            foreach ($response['users'] as $key => $directs) {
                $response['users'][$key]['sub_directs'] = get_records('tbl_users', array('user_id' => $directs['user_id']), 'id,user_id,sponser_id,role,name,email,phone,paid_status,created_at');
            }
            $this->load->view('tree', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function Pool($user_id = 'adminadmin', $pool_id)
    {
        if (is_admin()) {
            $response = array();
            // $response['user'] = get_single_record('tbl_pool', array('user_id' => $user_id , 'pool_level' => $pool_id), '*');
            $response['users'] = get_records('tbl_pool', array('pool_level' => $pool_id), '*');
            // foreach($response['users'] as $key => $directs){
            //     $response['users'][$key]['user_info'] = get_single_record('tbl_users', array('user_id' => $directs['user_id']), 'id,user_id,sponser_id,role,name,email,phone,paid_status,created_at');
            // }
            // $response['pool_id'] = $pool_id;
            // $this->load->view('pool', $response);
            $this->load->view('pool_view', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function login()
    {
        if (is_admin()) {
            redirect('admin/dashboard');
        } else {
            $response['message'] = '';
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
                $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

                if ($this->form_validation->run() != FALSE) {
                    $user = get_single_record('tbl_admin', array('id' => 1), '*');
                    if (!empty($data) && $data['user_id'] == 'secureadmin' && $data['password'] == $user['password']) {
                        // if (!empty($data) && $data['user_id'] == 'secureadmin' && $data['password'] == '#$di8GwL@T') {

                        // $this->session->set_userdata('user_id', $user['user_id']);
                        // $this->session->set_userdata('role', $user['role']);
                        $guard = md5(rand(100000, 999999));
                        $this->session->set_userdata('admin_id', 'admin');
                        $this->session->set_userdata('role', 'A');
                        $this->session->set_userdata('guard', $guard);
                        redirect('admin/dashboard');
                    } else {
                        $response['message'] = 'Invalid Credentials';
                    }
                } else {
                    $response['message'] = 'Invalid Validation!';
                }
            }
            $response['base_url'] = base_url('admin/login');
            $this->load->view('login', $response);
        }
    }


    public function Sublogin()
    {
        if (is_admin()) {
            redirect('admin/dashboard');
        } else {
            $response['message'] = '';
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $user = $this->Main_model->get_single_record('tbl_admin', array('user_id' => $data['user_id'], 'password' => $data['password'], 'role' => 'SA'), 'id,user_id,role,name,email');
                if (!empty($user)) {
                    $guard = md5(rand(100000, 999999));
                    $this->session->set_userdata('admin_id', $user['user_id']);
                    $this->session->set_userdata('role', $user['role']);
                    $this->session->set_userdata('guard', $guard);
                    redirect('admin/dashboard');
                } else {
                    $response['message'] = 'Invalid Credentials';
                }
            }
            $response['base_url'] = base_url('admin/sub-login');
            $this->load->view('login', $response);
        }
    }

    public function getMailOtp()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->server('REQUEST_METHOD') == 'GET') {
                $_SESSION['verification_otp'] = rand(100000, 999999);
                $this->session->mark_as_temp('verification_otp', 300);
                //$message = 'You OTP is '.$this->session->userdata['verification_otp'].' (One Time Password), this otp expire in 2 mintues!';
                //    $message = 'Dear User, Your OTP is '.$this->session->userdata['verification_otp'].' Never share this OTP with anyone, this OTP expire in two minutes. More Info: '.base_url().' From mlmsig';
                //composeMail($userinfo['email'],'OTP','OTP',$message,$display=false);
                $msg = 'Dear Admin Login OTP ' . $this->session->userdata['verification_otp'] . ' EANDGR';
                notifySms($msg);
                if ($msg) {
                    $response['status'] = 1;
                } else {
                    $response['status'] = 0;
                }
            }
        } else {
            $response['status'] = 0;
        }

        echo json_encode($response);
    }

    public function getOtp()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->server('REQUEST_METHOD') == 'GET') {
                $_SESSION['verification_otp'] = rand(100000, 999999);
                $this->session->mark_as_temp('verification_otp', 300);
                $message = 'Dear User, Your OTP is ' . $this->session->userdata['verification_otp'] . ' Never share this OTP with anyone, this OTP expire in two minutes. More Info : ' . base_url() . ' From mlmsig';
                $user = get_single_record('tbl_admin', array('id' => 1), 'name,email,phone');
                get_otp($user['phone'], $message, $entity_id = '1201161518339990262', $temp_id = '1207162142573795782');
                // get_otp($user['phone2'], $message, $entity_id = '1201161518339990262', $temp_id = '1207162142573795782');
                // notify_user($this->session->userdata['user_id'], $message);
                if ($message) {
                    $response['status'] = 1;
                } else {
                    $response['status'] = 0;
                }
            }
        } else {
            $response['status'] = 0;
        }

        echo json_encode($response);
    }

    public function logout()
    {
        $this->session->unset_userdata(array('user_id', 'role', 'admin_id', 'guard'));
        redirect('admin/login');
    }

    public function fund_history()
    {
        if (is_admin()) {

            $response['header'] = 'Fund History';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = [];
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_wallet', $where, '*', 'admin/fund-history', 3, 10);
            $response['path'] =  $records['path'];
            $response['field'] = '<div class="col-4">
                                  <select class="form-control" name="type">
                                      <option value="user_id" ' . $type . ' == "user_id" ? "selected" : "";?>
                                          User ID</option>
                                  </select>
                                </div>
                              <div class="col-4">
                                  <input type="text" name="value" class="form-control text-black float-right"
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
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Created At</th>

                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $amount . '</td>
                                <td>' . ($amount > 0 ? badge_success('Credit') : badge_danger('Debit')) . '</td>
                                <td>' . $remark . '</td>
                                <td>' . $created_at . '</td>
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

    public function admin_fund_history()
    {
        if (is_admin()) {

            $response['header'] = 'Admin Fund History';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = ['sender_id' => 'admin'];
            if (!empty($type)) {
                $where = [$type => $value, 'sender_id' => 'admin'];
            }
            $records = pagination('tbl_wallet', $where, '*', 'admin/adminfund-history', 3, 10);
            $response['path'] =  $records['path'];
            $response['field'] = '<div class="col-4">
                                  <select class="form-control" name="type">
                                      <option value="user_id" ' . $type . ' == "user_id" ? "selected" : "";?>
                                          User ID</option>
                                  </select>
                                </div>
                              <div class="col-4">
                                  <input type="text" name="value" class="form-control text-black float-right"
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
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Created At</th>

                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $amount . '</td>
                                <td>' . ($amount > 0 ? badge_success('Credit') : badge_danger('Debit')) . '</td>
                                <td>' . $remark . '</td>
                                <td>' . $created_at . '</td>
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

    public function SendWallet()
    {
        if (is_admin()) {

            $response = array();
            $response['script'] = true;
            $response['form_open'] = form_open(base_url('admin/send-wallet'));
            $response['form'] = [
                'user_id' => form_label('User ID', 'user_id') . form_input(array('type' => 'text', 'name' => 'user_id', 'id' => 'user_id', 'class' => 'form-control', 'placeholder' => 'User ID')),
                'error' =>  '<span id="errorMessageForm"></span>',
                'amount' => form_label('Amount', 'amount') . form_input(array('type' => 'number', 'name' => 'amount', 'id' => 'amount', 'class' => 'form-control', 'placeholder' => 'Amount')),
                'type' => form_label('Type', 'type') . form_dropdown('type', ['credit'  => 'Credit Fund'], 'credit', ['id' => 'credit', 'class' => 'form-control']),

            ];
            $response['form_button'] = form_submit('loginSubmit', 'Send', "class='btn btn-success'");
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    $user_id = $data['user_id'];
                    $amount = $data['amount'];
                    $user = get_single_record('tbl_users', array('user_id' => $data['user_id']), '*');
                    // if ($data['type'] == 'credit') {
                    //     $amount = abs($data['amount']);
                    // }
                    // if ($data['type'] == 'debit') {
                    //     $amount = '-' . abs($data['amount']);
                    // }
                    if (!empty($user)) {
                        $date = date('Y-m-d H:i:s');
                        $checkIntervel = $this->checkIntervel();
                        if ($checkIntervel === true) {
                            $this->Main_model->update('tbl_admin', ['id' => 1], ['fund_wallet' => $date]);

                            if ($amount >= 1) {
                                $send_Wallet = array(
                                    'user_id' => $data['user_id'],
                                    'amount' => $amount,
                                    'remark' => 'Fund ' . strtoupper($data['type']) . ' by Admin ',
                                    'type' => 'admin_amount',
                                    'sender_id' => 'admin',
                                );
                                add('tbl_wallet', $send_Wallet);
                                set_flashdata('message', span_success('Fund ' . ucwords($data['type']) . ' Successfully'));
                            } else {
                                set_flashdata('message', span_danger('Minimum Send Amount is 1'));
                            }
                        } else {
                            set_flashdata('message', span_danger('Please wait 5 Seconds for Send Fund.'));
                        }
                    } else {
                        set_flashdata('message', span_danger('Invalid User ID'));
                    }
                }
            }
            $response['header'] = 'Send Fund Personally';
            $this->load->view('forms', $response);
        } else {
            redirect('admin/login');
        }
    }

    private function checkIntervel()
    {
        $checkId = $this->Main_model->get_single_record('tbl_admin', ['id' => 1], '*');
        $date1 = date('Y-m-d H:i:s');
        $date2 = date('Y-m-d H:i:s', strtotime($checkId['fund_wallet'] . ' + 5 seconds'));
        $diff = strtotime($date1) - strtotime($date2);
        if ($diff > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function SendCoin()
    {
        if (is_admin()) {

            $response = array();
            $response['form_open'] = form_open(base_url('admin/send-coin'));
            $response['form'] = [
                'user_id' => form_label('User ID', 'user_id') . form_input(array('type' => 'text', 'name' => 'user_id', 'id' => 'user_id', 'class' => 'form-control', 'placeholder' => 'User ID')),
                'error' =>  '<span id="errorMessageForm"></span>',
                'amount' => form_label('Amount', 'amount') . form_input(array('type' => 'number', 'name' => 'amount', 'id' => 'amount', 'class' => 'form-control', 'placeholder' => 'Amount')),
            ];
            $response['form_button'] = form_submit('loginSubmit', 'Send', "class='btn btn-success'");
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    $user_id = $data['user_id'];
                    $amount = $data['amount'];
                    $user = get_single_record('tbl_users', array('user_id' => $user_id), '*');
                    if (!empty($user)) {
                        if ($amount >= 1) {
                            $Send_Coin = array(
                                'user_id' => $user_id,
                                'amount' => $amount,
                                'type' => 'admin_amount',
                                'description' => 'Send by Admin',
                            );
                            add('tbl_coin_wallet', $Send_Coin);
                            set_flashdata('message', span_success('Coin Sent Successfully'));
                        } else {
                            set_flashdata('message', span_danger('Minimum Send Coin is 1'));
                        }
                    } else {
                        set_flashdata('message', span_danger('Invalid User ID'));
                    }
                }
            }
            $response['script'] = true;
            $response['header'] = 'Send Coin Personally';
            $this->load->view('forms', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function update_fund_request($id)
    {
        if (is_admin()) {
            $request = get_single_record('tbl_payment_request', array('id' => $id), '*');
            $message = 'message_fund';
            $response['header'] = 'Update Fund Request';
            $response['form_open'] = form_open(base_url('admin/update-fund-request/' . $id));
            $response['form'] = [
                'user_id' => form_label('User ID', 'user_id') . form_input(array('type' => 'text', 'name' => 'user_id', 'id' => 'user_id', 'value' => $request['user_id'], 'class' => 'form-control', 'placeholder' => 'User ID', 'readonly' => true)),
                'image' =>  form_label('Proof', 'proof') . '<img alt="Proof Image" class="img-thumbnail" src="' . base_url('uploads/' . $request['image']) . '">',
                'amount' => form_label('Amount', 'amount') . form_input(array('type' => 'number', 'name' => 'amount', 'id' => 'amount', 'value' => $request['amount'], 'class' => 'form-control', 'placeholder' => 'Amount', 'readonly' => true)),
                'remarks' => form_label('Remarks', 'remarks') . form_textarea(array('type' => 'text', 'name' => 'remarks', 'id' => 'remarks', 'class' => 'form-control', 'rows' => 5, 'cols' => 3)),
                'status' => form_label('Status', 'status') . form_dropdown('status', ['approve'  => 'Approved', 'reject'  => 'Rejected'], 'status', ['id' => 'status_', 'class' => 'form-control']),
            ];
            $response['form_button'] = form_submit('dsjks', 'Submit', "class='btn btn-success'");
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                if ($data['status'] == 'reject') {
                    $updres = update('tbl_payment_request', array('id' => $id), array('status' => 2, 'remarks' => $data['remarks']));
                    if ($updres == true) {
                        set_flashdata($message, span_success('Reqeust Rejected Successfully'));
                    } else {
                        set_flashdata($message, span_danger('There is an error while Rejecting request Please try Again ..'));
                    }
                } elseif ($data['status'] == 'approve') {
                    if ($request['status'] != 1) {
                        /*                         * Topup Member */
                        $user = get_single_record('tbl_users', array('user_id' => $request['user_id']), '*');
                        $package = get_single_record('tbl_package', array('price' => $request['amount']), '*');
                        // pr($user,true);
                        // if ($user['paid_status'] == 0) {

                        //     $topupData = array(
                        //         'paid_status' => 1,
                        //         'package_id' => $package['id'],
                        //         'package_amount' => $package['price'],
                        //         'topup_date' => date('Y-m-d h:i:s'),
                        //         'capping' => $package['capping'],
                        //     );
                        //     update('tbl_users', array('user_id' => $user['user_id']), $topupData);
                        //     update_directs($user['sponser_id']);
                        //     $sponser = get_single_record('tbl_users', array('user_id' => $user['sponser_id']), 'sponser_id,directs');
                        //     $DirectIncome = array(
                        //         'user_id' => $user['sponser_id'],
                        //         'amount' => $package['direct_income'],
                        //         'type' => 'direct_income',
                        //         'description' => 'Direct Income from Activation of Member ' . $user['user_id'],
                        //     );
                        //     add('tbl_income_wallet', $DirectIncome);
                        //     $this->update_business($user['user_id'], $user['user_id'], $level = 1, $package['bv'], $type = 'topup');
                        //     $roiArr = array(
                        //         'user_id' => $user['user_id'],
                        //         'amount' => ($package['price'] * 2),
                        //         'roi_amount' => $package['commision'],
                        //     );
                        //     add('tbl_roi', $roiArr);
                        //     set_flashdata($message, 'Account Activated Successfully');
                        //     $updres = update('tbl_payment_request', array('id' => $id), array('status' => 1, 'remarks' => $data['remarks']));
                        // } else {
                        //     set_flashdata($message, 'This Account Already Acitvated');
                        // }
                        /*                         * Topup Member */
                        $updres = update('tbl_payment_request', array('id' => $id), array('status' => 1, 'remarks' => $data['remarks']));
                        if ($updres == true) {
                            set_flashdata($message, span_success('Reqeust Accepted And Fund released Successfully'));
                            $walletData = array(
                                'user_id' => $request['user_id'],
                                'amount' => $request['amount'],
                                'sender_id' => 'admin',
                                'type' => 'admin_fund',
                                'remark' => $data['remarks'],
                            );
                            add('tbl_wallet', $walletData);
                        } else {
                            set_flashdata($message, span_danger('There is an error while Rejecting request Please try Again ..'));
                        }
                    } else {
                        set_flashdata($message, span_success('This Payment Request Already Approved'));
                    }
                }
            }
            $response['message'] = $message;
            $this->load->view('forms', $response);
        } else {
            redirect('admin/login');
        }
    }

    function update_business($user_name = 'A915813', $downline_id = 'A915813', $level = 1, $business = '40', $type = 'topup')
    {
        $user = get_single_record('tbl_users', array('user_id' => $user_name), $select = 'upline_id , position,user_id');
        if (!empty($user)) {
            if ($user['position'] == 'L') {
                $c = 'leftPower';
            } else if ($user['position'] == 'R') {
                $c = 'rightPower';
            } else {
                return;
            }
            update_business($c, $user['upline_id'], $business);
            $downlineArray = array(
                'user_id' => $user['upline_id'],
                'downline_id' => $downline_id,
                'position' => $user['position'],
                'business' => $business,
                'type' => $type,
                'created_at' => date('Y-m-d h:i:s'),
                'level' => $level,
            );
            add('tbl_downline_business', $downlineArray);
            $user_name = $user['upline_id'];

            if ($user['upline_id'] != '') {
                $this->update_business($user_name, $downline_id, $level + 1, $business, $type);
            }
        }
    }

    public function adminEditProfileOtp($beneficiry_id)
    {
        if (is_admin()) {
            $_SESSION['otp'] = rand(100000, 999999);
            $this->session->mark_as_temp('otp', 120);
            if (!empty($_SESSION['otp'])) {
                $message = 'Your OTP is ' . $_SESSION['otp'] . ' Please never share your OTP (One Time Password) with anyone, This OTP is valid for 2 Mintues.';
                send_otp($message);
                set_flashdata('message', 'OTP send on your registered mobile no.');
            } else {
                set_flashdata('message', 'ERROR:: OTP Failed!  ');
            }
            redirect('Admin/Settings/EditUser/' . $beneficiry_id . '');
        } else {
            redirect('admin/login');
        }
    }


    public function sendIncome()
    {
        if (is_admin()) {
            $response = array();

            $response['form_open'] = form_open(base_url('admin/sendIncome'));
            $response['form'] = [
                'user_id' => form_label('User ID', 'user_id') . form_input(array('type' => 'text', 'name' => 'user_id', 'id' => 'user_id', 'class' => 'form-control', 'placeholder' => 'User ID')),
                'error' =>  '<span class="text-danger" id="errorMessageForm"></span>',
                'amount' => form_label('Amount', 'amount') . form_input(array('type' => 'number', 'name' => 'amount', 'id' => 'amount', 'class' => 'form-control', 'placeholder' => 'Amount')),
                'income_type' => form_label('Income Type', 'income_type') . form_dropdown('income_type', ['direct_income' => 'Direct Income', 'roi_income' => 'Roi Income', 'roi_level_income' => 'Roi Level Income'], 'direct_income', ['id' => 'income_type', 'class' => 'form-control']),
                'type' => form_label('Type', 'type') . form_dropdown('type', ['credit'  => 'Credit Income', 'debit'  => 'Debit Income'], 'direct_income', ['id' => 'income_type', 'class' => 'form-control']),
            ];
            $response['form_button'] = form_submit('send', 'Send', "class='btn btn-success'");
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
                $this->form_validation->set_rules('income_type', 'Income Type', 'trim|required|xss_clean');
                $this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
                // $this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    $user_id = $data['user_id'];
                    $amount = $data['amount'];
                    $user = get_single_record('tbl_users', array('user_id' => $user_id), '*');
                    if (!empty($user)) {

                        if ($data['type'] == 'credit') {
                            $amount = abs($data['amount']);
                        }
                        if ($data['type'] == 'debit') {
                            $amount = '-' . abs($data['amount']);
                        }
                        $sendIncome = array(
                            'user_id' => $user_id,
                            'amount' => $amount,
                            'type' => $data['income_type'],
                            'description' => 'Income ' . strtoupper($data['type']) . ' by Admin ',
                            'admin_status' => 1
                        );
                        add('tbl_income_wallet', $sendIncome);
                        set_flashdata('message', span_success('Income ' . ucwords($data['type']) . ' Successfully'));
                    } else {
                        set_flashdata('message', span_danger('Invalid User ID'));
                    }
                }
            }
            $response['header'] = 'Credit/Debit Income';
            $response['script'] = true;
            $this->load->view('forms', $response);
        } else {
            redirect('admin/login');
        }
    }



    function blockStatus($user_id, $status)
    {
        if (is_admin()) {
            $response['success'] = 0;
            $updres = update('tbl_users', array('user_id' => $user_id), array('disabled' => $status));
            if ($updres == true) {
                $response['success'] = 1;
                $response['message'] = 'Status Updated Successfully';
            } else {
                $response['message'] = 'Error While Updating Status';
            }
            echo json_encode($response);
        } else {
            redirect('admin/login');
        }
    }

    function popup_upload()
    {
        if (is_admin()) {
            $response = array();

            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());

                $data = html_escape($data);
                if ($data['type'] == 'image') {
                    if (!empty($_FILES['media']['name'])) {
                        $config['upload_path'] = './uploads/';
                        $config['allowed_types'] = 'gif|jpg|png|pdf|jpeg';
                        $config['file_name'] = 'payment_slip';
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('media')) {
                            $error = array('error' => $this->upload->display_errors());
                            $response = set_flashdata('error', $this->upload->display_errors());
                            $this->load->view('popup.php', $response);
                            print_r($error);
                            die('here');
                        } else {

                            $fileData = array('upload_data' => $this->upload->data());

                            //die('here');
                            $fileData = array('upload_data' => $this->upload->data());
                            $userData['media'] = $fileData['upload_data']['file_name'];
                            $userData['type'] = 'image';
                            $userData['caption'] = $this->input->post('caption');
                            $updres = add('tbl_popup', $userData);
                            if ($updres == true) {
                                $response = array('error' => 'Popup Uploaded Successfully');
                                set_flashdata('error', 'Popup Uploaded Successfully');
                                $this->load->view('popup.php', $response);
                            } else {
                                $response = array('error' => 'There is an error while uploading Popup Image, Please try Again ..');
                                set_flashdata('error', 'There is an error while uploading Popup Image, Please try Again ..');
                                $this->load->view('popup.php', $response);
                            }
                        }
                    } else {
                        $response = array('error' => 'There is an error while uploading Popup Image, Please try Again ..');
                        set_flashdata('error', 'There is an error while uploading Popup Image, Please try Again ..');
                        $this->load->view('popup.php', $response);
                    }
                } else {
                    $userData['media'] = $this->input->post('media');
                    $userData['type'] = 'video';
                    $userData['caption'] = $this->input->post('caption');
                    $updres = add('tbl_popup', $userData);
                    if ($updres == true) {
                        $response = array('error' => 'Popup Uploaded Successfully');
                        set_flashdata('error', 'Popup Uploaded Successfully');
                        $this->load->view('popup.php', $response);
                    } else {
                        $response = array('error' => 'There is an error while uploading Popup Image, Please try Again ..');
                        set_flashdata('error', 'There is an error while uploading Popup Image, Please try Again ..');
                        $this->load->view('popup.php', $response);
                    }
                }
            }
            $response['popup'] = get_single_record('tbl_user_popup', [], '*');
            $this->load->view('popup.php', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function Fund_requests($status)
    {
        if (is_admin()) {
            $export = $this->input->get('export');
            $type = $this->input->get('type');
            $value = $this->input->get('value');

            if ($status  == 'pending') {
                $response['header'] = ' Pending Fund Request';
                $where = ['status' => 0];
            } elseif ($status == 'approved') {
                $response['header'] = 'Approved Fund  Request';
                $where = ['status' => 1];
            } elseif ($status == 'rejected') {
                $response['header'] =  ' Rejected Fund  Request';
                $where = ['status' => 2];
            } elseif ($status == 'allrequest') {
                $response['header'] = 'All Fund Request';
                $where = [];
            } else {
                $response['header'] = 'Fund Request';
                $where = [];
            }


            if (!empty($type)) {
                if ($status  == 'pending') {
                    $response['header'] = ' Pending Fund Request';
                    $where = ['status' => 0, $type => $value];
                } elseif ($status == 'approved') {
                    $response['header'] = 'Approved Fund  Request';
                    $where = ['status' => 1, $type => $value];
                } elseif ($status == 'rejected') {
                    $response['header'] =  ' Rejected Fund  Request';
                    $where = ['status' => 2, $type => $value];
                } elseif ($status == 'allrequest') {
                    $response['header'] = 'All Fund Request';
                    $where = [$type => $value];
                } else {
                    $response['header'] = 'Fund Request';
                    $where = [$type => $value];
                }
            }

            $records = pagination('tbl_payment_request', $where, '*', 'admin/fund-requests/' . $status, 4, 10);
            if ($export) {
                $application_type = 'application/' . $export;
                $header = ['#', 'User ID', 'Name', 'Phone', 'Amount', 'Type', 'Status', 'Request Date'];
                foreach ($records['records'] as $key1 => $request) {
                    $user = get_single_record('tbl_users', array('user_id' => $request['user_id']), 'id,name,sponser_id,email,phone');
                    $records_export[$key1]['i'] = ($key1 + 1);
                    $records_export[$key1]['user_id'] = $request['user_id'];
                    $records_export[$key1]['name'] = $user['name'];
                    $records_export[$key1]['phone'] = $user['phone'];
                    $records_export[$key1]['amount'] = $request['amount'];
                    $records_export[$key1]['type'] = ucwords(str_replace('_', ' ', $request['type']));
                    $records_export[$key1]['status'] = ($request['status'] == 0 ? 'Pending' : ($request['status'] == 1 ? 'Approved' : ($request['status'] == 2 ? 'Rejected' : 'Fund')));
                    $records_export[$key1]['created_at'] = $request['created_at'];
                }
                finalExport($export, $application_type, $header, $records_export);
            }
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
                                <th>User Id</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Amount</th>
                                <th>Image</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Transaction ID</th>
                                <th>Remark</th>
                                <th>Date</th>
                                <th>Action</th>
                              
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $user = get_single_record('tbl_users', array('user_id' => $user_id), 'id,name,phone,eth_address');

                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $user['name'] . '</td>
                                <td>' . $user['phone'] . '</td>
                                <td>' . $amount . '</td>
                                <td><img alt="Proof Image" style="width: 100px; height: 100px;" class="img-thumbnail" src="' . base_url('uploads/' . $image) . '"></td>
                                <td>' . ucwords(str_replace('_', ' ', $type)) . '</td>
                                <td>' . ($status == 0 ? badge_warning('Pending') : ($status == 1 ? badge_success('Approved') : ($status == 2 ? badge_danger('Rejected') : badge_info('Fund')))) . '</td>
                                <td>' . $transaction_id . '</td>
                                <td>' . $remarks . '</td>
                                <td>' . $created_at . '</td>
                                <td>' . ($status == 0 ? ' <a target="_blank" href="' . base_url('admin/update-fund-request/') . $id . '">view</a>' : '') . '</td>
                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $response['segment'] = $records['segment'];
            $response['total_records'] = $records['total_records'];
            $response['i'] = $i;
            $response['export'] = true;
            $this->load->view('reports', $response);
        } else {
            redirect('admin/login');
        }
    }
}
