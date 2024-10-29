<?php

defined('BASEPATH') or exit('No direct script access allowed');

class DeveloperMode extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'pagination'));
        $this->load->model(array('Developer_model'));
        $this->load->helper(array('developer', 'security', 'super'));
    }

    public function index()
    {
        if (is_Developer()) {
            $response = array();
            $response['setting'] = get_single_record('plan_settings', array('id' => 1), '*');
            $this->load->view('index', $response);
        } else {
            redirect('DeveloperMode/login');
        }
    }


    public function getStatus()
    {
        if (is_Developer()) {
            $response['success'] = 0;
            $plan = get_single_record('plan_settings', array('id' => 1), '*');
            $response['success'] = 1;
            $response['data'] = $plan;
            echo json_encode($response);
        } else {
            redirect('DeveloperMode/login');
        }
    }


    public function DevSetting($field, $status)
    {
        if (is_Developer()) {
            $response['success'] = 0;
            $updres = update('plan_settings', array('id' => 1), array($field => $status));
            if ($updres == true) {
                $response['success'] = 1;
                $response['message'] = 'Successfully';
            } else {
                $response['message'] = 'Error While Updating Withdraw';
            }
            echo json_encode($response);
        } else {
            redirect('DeveloperMode/login');
        }
    }
    public function UpdateButton($field, $status)
    {
        if (is_Developer()) {
            $updres = update('plan_settings', array('id' => 1), array($field => $status));
            if ($updres == true) {
                $response['message'] = 'Successfully';
                redirect('DeveloperMode');
            } else {
                $response['message'] = 'Error While Updating Withdraw';
                redirect('DeveloperMode');
            }
        } else {
            redirect('DeveloperMode/login');
        }
    }

    public function PlanSet()
    {
        if (is_Developer()) {
            $response = array();
            $message = 'message';
            $response['header'] = ' Developer  Mode';
            $plan = get_single_record('plan_settings', array('id' => 1), '*');
            $response['form_open'] = form_open_multipart(base_url('DeveloperMode/PlanSet'));
            $response['form'] = [
                'title' => form_label('Title', 'title') . form_input(array('type' => 'text', 'name' => 'title', 'id' => 'title', 'class' => 'form-control', 'value' => '' . $plan['title'] . '', 'placeholder' => 'title')),
                'phone' => form_label('Phone', 'phone') . form_input(array('type' => 'text', 'name' => 'phone', 'id' => 'phone', 'class' => 'form-control', 'value' => '' . $plan['phone'] . '', 'placeholder' => 'phone')),
                'base_url' => form_label('Base Url', 'base_url') . form_input(array('type' => 'text', 'name' => 'base_url', 'id' => 'base_url', 'class' => 'form-control', 'value' => '' . $plan['base_url'] . '', 'placeholder' => 'base_url')),
                'address' => form_label('Address', 'address') . form_input(array('type' => 'text', 'name' => 'address', 'id' => 'address', 'class' => 'form-control', 'value' => '' . $plan['address'] . '', 'placeholder' => 'address')),
                'email' => form_label('Email', 'email') . form_input(array('type' => 'text', 'name' => 'email', 'id' => 'email', 'class' => 'form-control', 'value' => '' . $plan['email'] . '', 'placeholder' => 'email')),
                'currency' => form_label('Currency', 'currency') . form_input(array('type' => 'text', 'name' => 'currency', 'id' => 'currency', 'class' => 'form-control', 'value' => '' . $plan['currency'] . '', 'placeholder' => 'plan')),
                'id_start' => form_label('ID Start', 'id_start') . form_input(array('type' => 'text', 'name' => 'id_start', 'id' => 'id_start', 'class' => 'form-control', 'value' => '' . $plan['id_start'] . '', 'placeholder' => 'id_start')),
                'min_withdraw' => form_label('Min Withdraw', 'min_withdraw') . form_input(array('type' => 'text', 'name' => 'min_withdraw', 'id' => 'min_withdraw', 'class' => 'form-control', 'value' => '' . $plan['min_withdraw'] . '', 'placeholder' => 'min_withdraw')),
                'max_withdraw' => form_label('Max WIthdraw', 'max_withdraw') . form_input(array('type' => 'text', 'name' => 'max_withdraw', 'id' => 'max_withdraw', 'class' => 'form-control', 'value' => '' . $plan['max_withdraw'] . '', 'placeholder' => 'max_withdraw')),
                'multiple_withdraw' => form_label('Multiple Withdraw', 'multiple_withdraw') . form_input(array('type' => 'text', 'name' => 'multiple_withdraw', 'id' => 'multiple_withdraw', 'class' => 'form-control', 'value' => '' . $plan['multiple_withdraw'] . '', 'placeholder' => 'multiple_withdraw')),
                'payment_access' => form_label('Payment Process', 'payment_access') . form_dropdown('type', ['bank'  => 'Bank', 'wallet'  => 'Wallet Address'], 'bank', ['class' => 'form-control']),
                'registration' => form_label('Registration', 'registration') . form_dropdown('type', ['0'  => 'Simple', '1'  => 'Binary'], '0', ['class' => 'form-control']),
            ];
            $response['form_button'] = form_submit('developer', 'Update', "class='btn btn-primary'");
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $PlanArr = array(
                    'title' => $data['title'],
                    'phone' => $data['phone'],
                    'base_url' => $data['base_url'],
                    'address' => $data['address'],
                    'email' => $data['email'],
                    'currency' => $data['currency'],
                    'id_start' => $data['id_start'],
                    'min_withdraw' => $data['min_withdraw'],
                    'max_withdraw' => $data['max_withdraw'],
                    'multiple_withdraw' => $data['multiple_withdraw'],
                    'multiple_access' => $data['multiple_access'],
                    'payment_access' => $data['payment_access'],
                    'registration' => $data['registration'],
                );
                $res = update('plan_settings', array('id' => 1), $PlanArr);
                if ($res == TRUE) {
                    set_flashdata($message, span_success('Plan Edit Successfully'));
                    redirect('DeveloperMode/PlanSet');
                } else {
                    set_flashdata($message, span_danger('Error While Creating Plan  Please Try Again ...'));
                }
            }
            $response['message'] = $message;
            $response['popup'] = get_single_record('plan_settings', array('id' => 1), '*');
            $this->load->view('forms', $response);
        } else {
            redirect('DeveloperMode/login');
        }
    }

    public function editPackage($id)
    {
        if (is_Developer()) {
            $response = array();
            $package = get_single_record('tbl_package', array('id' => $id), '*');
            $response['header'] = ' Developer Mode';
            $response['form_open'] = form_open_multipart(base_url('DeveloperMode/editPackage/' . $package['id']));
            $response['form'] = [
                'id' => form_label('ID', 'id') . form_input(array('type' => 'text', 'name' => 'main_id', 'main_id' => 'id', 'class' => 'form-control', 'value' => '' . $package['id'] . '', 'placeholder' => 'title')),
                'title' => form_label('Title', 'title') . form_input(array('type' => 'text', 'name' => 'title', 'id' => 'title', 'class' => 'form-control', 'value' => '' . $package['title'] . '', 'placeholder' => 'title')),
                'price' => form_label('price', 'price') . form_input(array('type' => 'text', 'name' => 'price', 'id' => 'price', 'class' => 'form-control', 'value' => '' . $package['price'] . '', 'placeholder' => 'price')),
                'days' => form_label('days', 'days') . form_input(array('type' => 'text', 'name' => 'days', 'id' => 'days', 'class' => 'form-control', 'value' => '' . $package['days'] . '', 'placeholder' => 'days')),
                'direct_income' => form_label('direct_income', 'direct_income') . form_input(array('type' => 'text', 'name' => 'direct_income', 'id' => 'direct_income', 'class' => 'form-control', 'value' => '' . $package['direct_income'] . '', 'placeholder' => 'direct_income')),
                'level_income' => form_label('level_income', 'level_income') . form_input(array('type' => 'text', 'name' => 'level_income', 'id' => 'level_income', 'class' => 'form-control', 'value' => '' . $package['level_income'] . '', 'placeholder' => 'level_income')),
                'roi_income' => form_label('roi_income', 'roi_income') . form_input(array('type' => 'text', 'name' => 'roi_income', 'id' => 'roi_income', 'class' => 'form-control', 'value' => '' . $package['roi_income'] . '', 'placeholder' => 'roi_income')),
            ];
            $response['form_button'] = form_submit('developer', 'Update', "class='btn btn-primary'");
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $packArr = array(
                    'id' => $data['main_id'],
                    'title' => $data['title'],
                    'price' => $data['price'],
                    'days' => $data['days'],
                    'direct_income' => $data['direct_income'],
                    'level_income' => $data['level_income'],
                    'roi_income' => $data['roi_income'],
                );
                $res = $this->Developer_model->update('tbl_package', array('id' => $id), $packArr);
                if ($res == TRUE) {
                    set_flashdata('developer_message', span_success('Package Edit Successfully'));
                    redirect('DeveloperMode/package');
                } else {
                    set_flashdata('developer_message', span_danger('Error While Creating package Please Try Again ...'));
                }
            }
            $this->load->view('forms', $response);
        } else {
            redirect('DeveloperMode/login');
        }
    }


    public function package()
    {
        if (is_Developer()) {
            $response['header'] = 'Package List';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $export = $this->input->get('export');
            $where = [];
            if (!empty($type)) {
                $where = [$type => $value];
            }

            $records = pagination('tbl_package', $where, '*', 'DeveloperMode/package', 3, 10);
            if ($export) {
                $application_type = 'application/' . $export;
                $header = ['#', 'Title', 'Price', 'Days', 'Direct Income', 'Level Income', 'ROI Income'];
                foreach ($records['records'] as $expKey => $record) {
                    $records_export[$expKey]['i'] = ($expKey + 1);
                    $records_export[$expKey]['title'] = $record['title'];
                    $records_export[$expKey]['price'] = $record['price'];
                    $records_export[$expKey]['days'] = $record['days'];
                    $records_export[$expKey]['direct_income'] = $record['direct_income'];
                    $records_export[$expKey]['level_income'] = $record['level_income'];
                    $records_export[$expKey]['roi_income'] = $record['roi_income'];
                }
                finalExport($export, $application_type, $header, $records_export);
            }
            $response['path'] =  $records['path'];
            $response['field'] = '<div class="col-4">
            <a href="' . base_url('DeveloperMode/CreatePackage/') . '" class="btn btn-success">Create Package</a>
                                </div>';
            $response['thead'] = '<tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Days</th>
                                    <th>Direct Income</th>
                                    <th>Level Income</th>
                                    <th>Roi Income</th>
                                    <th>Action</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $id . '</td>
                                <td>' . $title . '</td>
                                <td>' . $price . '</td>
                                <td>' . $days . '</td>
                                <td>' . $direct_income . '</td>
                                <td>' . $level_income . '</td>
                                <td>' . $roi_income . '</td>
                                <td><a href="' . base_url('DeveloperMode/editPackage/' . $id) . '" class="btn btn-info">Edit</a> <a href="' . base_url('DeveloperMode/deletePackage/' . $id) . '" class="btn btn-danger">Delete</a></td>
                             </tr>';
                $i++;
            }
            $response['records'] = $records['segment'];
            $response['i'] = $i;
            $response['tbody'] = $tbody;
            $this->load->view('reports', $response);
        } else {
            redirect('DeveloperMode/login');
        }
    }

    public function deletePackage($id)
    {

        $get = $this->Developer_model->get_single_record('tbl_package', array('id' => $id), '*');
        if (!empty($get['id'])) {
            $delete = $this->Developer_model->delete('tbl_package', $id);
            if ($delete) {
                set_flashdata('developer_message', span_success('Package Deleted Successfully!'));
                redirect('DeveloperMode/package');
            } else {
                set_flashdata('developer_message', span_danger('Request not found!'));
            }
        } else {
            set_flashdata('developer_message', span_danger('Invaild not found!'));
        }
    }

    public function CreatePackage()
    {
        if (is_Developer()) {
            $response = array();
            $message = 'message';
            $response['header'] = ' Developer Mode';
            $response['form_open'] = form_open_multipart(base_url('DeveloperMode/CreatePackage'));
            $response['form'] = [
                'title' => form_label('Title', 'title') . form_input(array('type' => 'text', 'name' => 'title', 'id' => 'title', 'class' => 'form-control', 'placeholder' => 'title', 'required' => true)),
                'price' => form_label('price', 'price') . form_input(array('type' => 'text', 'name' => 'price', 'id' => 'price', 'class' => 'form-control', 'placeholder' => 'price', 'required' => true)),
                'days' => form_label('days', 'days') . form_input(array('type' => 'text', 'name' => 'days', 'id' => 'days', 'class' => 'form-control', 'placeholder' => 'days', 'required' => true)),
                'direct_income' => form_label('direct_income', 'direct_income') . form_input(array('type' => 'text', 'name' => 'direct_income', 'id' => 'direct_income', 'class' => 'form-control', 'placeholder' => 'direct_income')),
                'level_income' => form_label('level_income', 'level_income') . form_input(array('type' => 'text', 'name' => 'level_income', 'id' => 'level_income', 'class' => 'form-control', 'placeholder' => 'level_income')),
                'roi_income' => form_label('roi_income', 'roi_income') . form_input(array('type' => 'text', 'name' => 'roi_income', 'id' => 'roi_income', 'class' => 'form-control', 'placeholder' => 'roi_income')),

            ];
            $response['form_button'] = form_submit('developer', 'Update', "class='btn btn-primary'");

            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $packArr = array(
                    'title' => $data['title'],
                    'price' => $data['price'],
                    'days' => $data['days'],
                    'direct_income' => $data['direct_income'],
                    'level_income' => $data['level_income'],
                    'roi_income' => $data['roi_income'],
                );
                $res = $this->Developer_model->add('tbl_package', $packArr);
                if ($res == TRUE) {
                    set_flashdata($message, span_success('Package Added Successfully'));
                } else {
                    set_flashdata($message, span_danger('Error While Creating Package Please Try Again ...'));
                }
            }
            $this->load->view('forms', $response);
        } else {
            redirect('DeveloperMode/login');
        }
    }



    public function login()
    {
        if (is_Developer()) {
            redirect('DeveloperMode');
        } else {
            $response['message'] = '';
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
                $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    if (!empty($data) && $data['user_id'] == 'Developer' && $data['password'] == 'Developer') {
                        $guard = md5(rand(1000, 9999));
                        $this->session->set_userdata('developer_id', 'developer');
                        $this->session->set_userdata('role', 'DA');
                        $this->session->set_userdata('guard', $guard);
                        redirect('DeveloperMode/');
                    } else {
                        $response['message'] = 'Invalid Credentials';
                    }
                } else {
                    $response['message'] = 'Invalid Validation!';
                }
            }
            $this->load->view('login', $response);
        }
    }

    public function logout()
    {
        $this->session->unset_userdata(array('user_id', 'role', 'developer_id', 'guard'));
        redirect('DeveloperMode/login');
    }

    public function logo_upload()
    {
        if (is_Developer()) {
            $response = array();
            $product = get_single_record('plan_settings', array('id' => 1), '*');
            //     $logo = '<div class="col-md-6">
            //     <img src=' .base_url('uploads/' . $product['logo']) .'  height="100px" width="100px">
            // </div>';
            $logo = '<div class="col-md-6">
        <img src=' . base_url('classic/no_image.png') . ' title="Payment Slip" id="slipImage" style="width: 100%;">
    </div>';

            $response['header'] = ' Logo Upload' . $logo;
            $response['form_open'] = form_open_multipart(base_url('DeveloperMode/logo_upload'));
            $response['form'] = [
                'logo' => form_label('Logo', 'logo') . form_input(array('type' => 'file', 'name' => 'logo', 'id' => 'payment_slip', 'class' => 'form-control')),
            ];
            $response['form_button'] = form_submit('logo_upload', 'Update', "class='btn btn-primary'");
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'doc|pdf|jpg|jpeg|png';
                $config['file_name'] = 'logo' . time();

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('logo')) {
                    set_flashdata('message', $this->upload->display_errors());
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $promoArr = array(
                        'logo' => $data['upload_data']['file_name'],
                    );
                    $res = update('plan_settings', array('id' => 1), $promoArr);
                    if ($res) {
                        set_flashdata('message', span_success('Logo Update Successfully'));
                    } else {
                        set_flashdata('message', span_danger('Error While Updating Logo Please Try Again ...'));
                    }
                }
            }

            // $response['popup'] = true;
            $this->load->view('forms', $response);
        } else {
            redirect('DeveloperMode/login');
        }
    }
}
