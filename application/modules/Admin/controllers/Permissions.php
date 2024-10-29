<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Permissions extends CI_Controller
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
            $response['header'] = 'Permissions';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = ['role' => 'SA'];
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_admin', $where, '*', 'admin/permissions', 3, 10);
            $response['path'] =  $records['path'];
            $response['field'] = '<div class="col-4">
                                <div class="input-group-append">
                                    <a href="' . base_url('admin/create-subadmin') . '" class="btn btn-success">Create Sub Admin</a>
                                </div>
                            </div>
                            <div class="col-5">
                                <input type="text" name="value" id="linkTxt" class="form-control text-dark  float-right" readonly
                                    value="' . base_url('admin/sub-login') . '" placeholder="Link">
                                </div>
                            <div class="col-3">
                                <div class="input-group-append">
                                    <input type="button" id="btnCopy" value="Copy Link" class="btn btn-info">
                                </div>
                            </div>';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Password</th>
                                <th>Date</th>
                                <th>Action</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $permission = '<a href="' . base_url('admin/change-permissions/' . $user_id) . '">Permission</a>';
                $edit_admin = '<a href="' . base_url('admin/edit-subadmin/' . $id) . '">Edit</a>';
                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $password . '</td>
                                <td>' . $created_at . '</td>
                                <td>' . $permission . ' / ' . $edit_admin . '</td>
                             </tr>';
                $i++;
            }
            $response['tbody'] = $tbody;
            $response['segment'] = $records['segment'];
            $response['total_records'] = $records['total_records'];
            $response['i'] = $i;
            $response['script'] = true;
            $this->load->view('reports', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function accessDeined()
    {
        die('accessDeined die');
        $this->load->view('accessDenied');
    }

    public function CreateSubAdmin()
    {
        if (is_admin()) {

            $response = array();
            $response['script'] = true;
            $response['form_open'] = form_open(base_url('admin/create-subadmin'));
            $response['form'] = [
                'user_id' => form_label('User ID', 'user_id') . form_input(array('type' => 'text', 'name' => 'user_id', 'id' => 'user_id', 'class' => 'form-control', 'placeholder' => 'User ID')),
                'error' =>  '<span class="text-danger" id="errorMessageForm"></span>',
                'password' => form_label('Password', 'password') . form_input(array('type' => 'password', 'name' => 'password', 'id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password')),
            ];
            $response['form_button'] = form_submit('subCreate', 'Create', "class='btn btn-success'");
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
                $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    $user = get_single_record('tbl_admin', ['user_id' => $data['user_id']], 'name');
                    $check = get_single_record('tbl_users', ['user_id' => $data['user_id']], 'user_id');
                    if (empty($user)) {
                        if (!empty($check)) {
                            $userdata = [
                                'user_id' => $data['user_id'],
                                'password' => $data['password'],
                                'access' => '{"admin\/dashboard": "admin\/dashboard"}',
                                'role' => 'SA',
                            ];
                            add('tbl_admin', $userdata);
                            set_flashdata('message', span_success('Sub Admin Created Successfully'));
                            redirect('admin/create-subadmin');
                        } else {
                            set_flashdata('message', span_danger('Invalid User ID'));
                        }
                    } else {
                        set_flashdata('message', span_info('User ID Already Exists'));
                    }
                }
            }

            $response['header'] = 'Add New Sub Admin  <a href="' . base_url('admin/permissions') . '" class="btn btn-success">User List</a>';
            $this->load->view('forms', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function ChangePermissions($user_id)
    {
        if (is_admin()) {
            if ($this->input->server("REQUEST_METHOD") == "POST") {
                $data = $this->security->xss_clean($this->input->post());
                $access = json_encode($data);
                $res = update('tbl_admin', ['user_id' => $user_id], ['access' => $access]);
                if ($res) {
                    set_flashdata('per_mission_message', span_success('Permissions Updated successfully'));
                    redirect('admin/change-permissions/' . $user_id);
                } else {
                    set_flashdata('per_mission_message', span_danger('Network error,Please try later'));
                }
            }
            $users = get_single_record('tbl_admin', ['user_id' => $user_id], 'access');
            $response['access'] = json_decode($users['access'], true);

            $response['header'] = 'Change Permissions';
            $response['user_id'] = $user_id;
            $this->load->view('changePermission', $response);
        } else {
            redirect('Admin/Management/login');
        }
    }

    public function deleteUser($id)
    {
        if (is_admin()) {
            $checkUser = get_single_record('tbl_admin', ['id' => $id], '*');
            if (!empty($checkUser)) {
                $this->Main_model->delete('tbl_admin', $id);
                redirect('admin/permissions');
            } else {
                die('Server Issue');
            }
        } else {
            redirect('admin/login');
        }
    }

    public function edit($id)
    {
        if (is_admin()) {
            $response = array();
            $message = 'sub_admin_message';
            $response['header'] = 'Edit Sub Admin';
            $response['script'] = true;
            $checkUser = get_single_record('tbl_admin', ['id' => $id], '*');
            $response['form_open'] = form_open(base_url('admin/edit-subadmin/' . $id));
            $response['form'] = [
                'password' => form_label('Password', 'password') . form_input(array('type' => 'text', 'name' => 'password', 'id' => 'password', 'value' => $checkUser['password'], 'class' => 'form-control', 'placeholder' => 'Password')),
            ];
            $response['form_button'] = form_submit('Updatesub', 'Update', "class='btn btn-success'");
            if ($this->input->server("REQUEST_METHOD") == "POST") {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                if ($this->form_validation->run() != FALSE) {
                    $pass = ['password' => $data['password']];
                    update('tbl_admin', ['id' => $id], $pass);
                    set_flashdata($message, span_success('Password Updated successfully'));
                    redirect('admin/permissions');
                } else {
                    set_flashdata($message, span_danger('Network error,Please try later'));
                }
            }
            $response['message'] = $message;
            $this->load->view('forms', $response);
        } else {
            redirect('Admin/Management/login');
        }
    }

    public function check()
    {
        // $this->load->library('controllerlist');
        // $list = $this->controllerlist->setControllerMethods('Management', 'index');
        $controller = $this->router->fetch_class();
        $method = $this->router->fetch_method();
        $get = get_class_methods('check');
        pr($get);
        pr($controller);
        pr($method);
        // pr($list);
    }


    public function access()
    {
        $this->load->view('accessDenied');
        //  $this->load->helper('file');
        //  $controllers = get_filenames( APPPATH . 'modules/Admin/controllers/' ); 

        // foreach( $controllers as $k => $v )
        // {
        //     if( strpos( $v, '.php' ) === FALSE)
        //     {
        //     // die('here');

        //         unset( $controllers[$k] );
        //     }
        //     // pr($v);
        // }

        // echo '<ul>';
        //         // die;

        // foreach( $controllers as $controller )
        // {
        //     echo '<li>' . str_replace( '.php', '',$controller) . '<ul>';
        //     $class = str_replace( '.php', '',$controller);

        //     include_once APPPATH . 'modules/Admin/controllers/' . $controller;

        //     $methods = get_class_methods( str_replace( '.php', '', $controller ) );
        //     // $method=[];
        //     // $aUserMethods[]p
        //     // if($methods  != '__construct'){
        //     foreach( $methods as $method )
        //     {
        //         // if($method != '__construct' && $method != 'get_instance') {
        //         //             $aUserMethods[] = $method;
        //         //         }
        //         // pr($method);
        //         if($methods != '__construct' && $methods != 'get_instance'){
        //             $arry = array($class => $method);
        //             pr($arry);
        //         }
        //             // echo '<li>' . $method . '</li>';
        //     }
        // // }
        //     echo '</ul></li>';
        // }

        // echo '</ul>'; 
    }
}
