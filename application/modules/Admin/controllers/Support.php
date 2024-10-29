<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Support extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'pagination'));
        $this->load->model(array('Main_model'));
        $this->load->helper(array('admin', 'security', 'super'));
    }

    public function SubmitQuery()
    {
        if (is_admin()) {
            $message = $this->input->post('message');
            $user_id = $this->input->post('user_id');
            $messageArr = array(
                'user_id' => $user_id,
                'message' => $message,
                'sender' => 'admin'
            );
            $res = $this->Main_model->add('tbl_support_message', $messageArr);
            if ($res) {
                $data['message'] = 'Message Sent Successfully';
                $data['success'] = 1;
            } else {
                $data['message'] = 'Error while sending message';
                $data['success'] = 0;
            }
            echo json_encode($data);
            exit();
        } else {
            redirect('Dashboard/User/login');
        }
    }

    public function inbox()
    {
        if (is_admin()) {
            $response = array();
            $response['header'] = 'Composed Message History';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = array('user_id !=' => 'adminstator');
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_support_message', $where, '*', 'admin/inbox/', 3, 10, 'ASC');
            $response['path'] =  $records['path'];
            $searchField = '';
            $response['field'] = $searchField;
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Attachment</th>
                                <th>Remark</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);

                $tbody[$key]  = '<tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $title . '</td>
                                <td>' . $message . '</td>
                                <td><img src="' . base_url('uploads/' . $image) . '" alt="attachment" style="withd: 150px;height: 150px;" class="img-thumbnail"></td>
                                <td>' . $remark . '</td>
                                <td>' . ($status == 0 ? badge_warning('Pending') : ($status == 1 ? badge_success('Approved') : ($status == 2 ? badge_danger('Rejected') : badge_info('Support')))) . '</td>
                                <td>' . $created_at . '</td>
                                <td> ' . ($status == 0 ? '<a href="' . base_url('admin/support-view/' . $id) . '">View</a>' : '') . '</td>
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

    public function ComposeMail()
    {
        if (is_admin()) {
            $response = array();
            $message = 'compose-mail';
            $response['header'] = 'COMPOSE MAIL MESSAGE';
            $response['form_open'] = form_open_multipart(base_url('admin/compose-mail'));
            $response['form'] = [
                'user_id' => form_label('User ID', 'user_id') . form_input(array('type' => 'text', 'name' => 'user_id', 'id' => 'user_id', 'class' => 'form-control',  'placeholder' => 'Enter User ID')),
                'error' =>  '<span class="text-danger" id="errorMessageForm"></span>',
                'title' => form_label('Title', 'title') . form_input(array('type' => 'text', 'name' => 'title', 'id' => 'title', 'class' => 'form-control',  'placeholder' => 'Enter title')),
                'message' => form_label('Description', 'message') . form_textarea(array('type' => 'text', 'name' => 'message', 'id' => 'message', 'class' => 'form-control', 'rows' => 5, 'cols' => 3)),
                'image' => form_label('Attachment', 'image') . form_input(array('type' => 'file', 'name' => 'image', 'id' => 'image', 'class' => 'form-control',  'placeholder' => '')),
            ];
            $response['form_button'] = form_submit('composed_msg', 'Submit', ['class' => 'btn btn-info', 'id' => 'composed_msg', 'style' => 'display: block;']);
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    $receiver_id = get_single_record('tbl_users', ['user_id' => $data['user_id']], 'user_id');
                    if (!empty($receiver_id)) {
                        $config['upload_path'] = './uploads/';
                        $config['allowed_types'] = 'gif|jpg|png|pdf|jpeg';
                        $config['max_size'] = 100000;
                        $config['file_name'] = 'attachment' . time();
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('image')) {
                            $sendWallet = array(
                                'user_id' => 'adminstator',
                                'receiver_id' => $data['user_id'],
                                'title' => $data['title'],
                                'message' => $data['message'],
                                'status' => 0,
                            );
                            add('tbl_support_message', $sendWallet);
                            set_flashdata($message, span_success('Mail Composed Successfully'));
                            // set_flashdata($message, span_danger($this->upload->display_errors()));
                        } else {
                            $fileData = array('upload_data' => $this->upload->data());
                            $sendWallet = array(
                                'user_id' => 'adminstator',
                                'receiver_id' => $data['user_id'],
                                'title' => $data['title'],
                                'message' => $data['message'],
                                'image' => $fileData['upload_data']['file_name'],
                            );
                            $updres = add('tbl_support_message', $sendWallet);
                            if ($updres == true) {
                                set_flashdata($message, span_success('Mail Composed Successfully'));
                            } else {
                                set_flashdata($message, span_danger('There is an error while updating Bank details Please try Again ..'));
                            }
                        }
                    } else {
                        set_flashdata($message, span_danger('Invalid User ID'));
                    }
                }
            }
            $response['message'] = $message;
            $response['script'] = true;
            $this->load->view('forms', $response);
        } else {
            redirect('admin/login');
        }
    }

    public function Outbox()
    {
        if (is_admin()) {
            $response = array();
            $response['header'] = 'Outbox Message History';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = array('user_id' => 'adminstator');
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_support_message', $where, '*', 'admin/outbox/', 3, 10, 'ASC');
            $response['path'] =  $records['path'];
            $searchField = '';
            $response['field'] = $searchField;
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>From</th>
                                <th>Receiver ID</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Image</th>
                                <th>Date</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $tbody[$key]  = '<tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $receiver_id . '</td>
                                <td>' . $title . '</td>
                                <td>' . $message . '</td>
                                <td><img src="' . base_url('uploads/' . $image) . '" alt="attachment" style="withd: 150px;height: 150px;" class="img-thumbnail"></td>
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

    public function Compose()
    {
        if (is_admin()) {
            $response = array();
            $response['header'] = 'Support Message History';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = array('user_id !=' => 'adminstator');
            if (!empty($type)) {
                $where = [$type => $value];
            }
            $records = pagination('tbl_support_message', $where, '*', 'admin/compose/', 3, 10, 'ASC');
            $response['path'] =  $records['path'];
            $searchField = '';
            $response['field'] = $searchField;
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>USER ID</th>
                                <th>TITLE</th>
                                <th>MESSAGE</th>
                                <th>STATUS</th>
                                <th>REMARK</th>
                                <th>IMAGE</th>
                                <th>DATE</th>
                                <th>ACTION</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                if ($status == 0) {
                    $sts = 'Pending';
                } elseif ($status == 1) {
                    $sts = 'Approved';
                } elseif ($status == 2) {
                    $sts = 'Rejected';
                }
                $tbody[$key]  = '<tr>
                                <td>' . $i . '</td>
                                <td>' . $user_id . '</td>
                                <td>' . $title . '</td>
                                <td>' . $message . '</td>
                                <td>' . $sts . '</td>
                                <td>' . $remark . '</td>
                                <td><img src="' . base_url('uploads/' . $image) . '" alt="attachment" style="withd: 150px;height: 150px;" class="img-thumbnail"></td>
                                <td>' . $created_at . '</td>
                                <td><a href="' . base_url('Admin/Support/view/' . $id) . '">View</a></td>
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

    public function view($id)
    {
        if (is_admin()) {
            $response = array();
            $check = $this->Main_model->get_single_record('tbl_support_message', array('id' => $id), '*');
            $response['form_open'] = form_open(base_url('admin/support-view/' . $id));
            $response['form'] = [
                'title' => form_label('Package Title', 'title') . form_input(array('type' => 'text', 'name' => 'title', 'id' => 'title', 'class' => 'form-control', 'value' => $check['title'])),
                'message' => form_label('Description', 'message') . form_textarea(array('type' => 'text', 'name' => 'message', 'id' => 'message', 'class' => 'form-control', 'rows' => 5, 'cols' => 3, 'value' => $check['message'])),
                'status' => form_label('Status', 'status') . form_dropdown('status', [0  => 'Pending', 1  => 'Resolved', 2 => 'Unresolved'], '0', ['class' => 'form-control']),
                'remark' => form_label('Remark', 'remark') . form_textarea(array('type' => 'text', 'name' => 'remark', 'id' => 'remark', 'class' => 'form-control', 'rows' => 5, 'cols' => 3, 'value' => $check['remark'])),

            ];
            $response['form_button'] = form_submit('update', 'Update', "class='btn btn-primary'");
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
                $this->form_validation->set_rules('remark', 'Remark', 'trim|xss_clean');
                if ($this->form_validation->run() != FALSE) {
                    if ($check['status'] == 0) {
                        $packArr = array(
                            'status' => $data['status'],
                            'remark' => $data['remark'],
                        );
                        $res = update('tbl_support_message', array('id' => $id), $packArr);
                        if ($res == TRUE) {
                            set_flashdata('message', span_success('Request Updated Successfully'));
                        } else {
                            set_flashdata('message', span_danger('Error While Updating Remarks  Please Try Again ...'));
                        }
                    } else {
                        set_flashdata('message', span_info('Request Already Updated!'));
                    }
                }
            }
            $response['header'] = 'View Support Message';
            $this->load->view('forms', $response);
        } else {
            redirect('admin/login');
        }
    }
}
