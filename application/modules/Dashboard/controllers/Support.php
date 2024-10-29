<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Support extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'pagination'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'super'));
    }

    public function index()
    {
        if (is_logged_in()) {
            redirect('dashboard');
        } else {
            redirect('login');
        }
    }

    public function SubmitQuery()
    {
        if (is_logged_in()) {
            $message = $this->input->post('message');
            $messageArr = array(
                'user_id' => $this->session->userdata['user_id'],
                'message' => $message,
                'sender' => $this->session->userdata['user_id']
            );
            $res = add('tbl_support_message', $messageArr);
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

    public function ComposeMail()
    {
        if (is_logged_in()) {
            $response = array();
            $message = 'compose-mail';
            $response['extra_header'] = false;
            $response['script'] = false;
            $response['header'] = 'COMPOSE MAIL MESSAGE';
            $response['form_open'] = form_open_multipart(base_url('dashboard/compose-mail'));
            $response['form'] = [
                'title' => form_label('Title', 'title') . form_input(array('type' => 'text', 'name' => 'title', 'id' => 'title', 'class' => 'form-control',  'placeholder' => 'Enter title')),
                'message' => form_label('Description', 'message') . form_textarea(array('type' => 'text', 'name' => 'message', 'id' => 'message', 'class' => 'form-control', 'rows' => 5, 'cols' => 3)),
                'image' => form_label('Attachment', 'image') . form_input(array('type' => 'file', 'name' => 'image', 'id' => 'image', 'class' => 'form-control',  'placeholder' => '')),
            ];
            $response['form_button'] = [
                'submit' => form_submit('composed_msg', 'Submit', ['class' => 'btn btn-info', 'id' => 'composed_msg', 'style' => 'display: block;'])
            ];
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data = $this->security->xss_clean($this->input->post());
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|pdf|jpeg';
                $config['max_size'] = 100000;
                $config['file_name'] = 'attachment' . time();
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image')) {
                    $sendWallet = array(
                        'user_id' => $this->session->userdata['user_id'],
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
                        'user_id' => $this->session->userdata['user_id'],
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
            }
            $response['message'] = $message;
            $this->load->view('forms', $response);
        } else {
            redirect('login');
        }
    }

    public function Inbox()
    {
        if (is_logged_in()) {
            $response['header'] = 'Inbox';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = array('user_id' => 'adminstator', 'receiver_id' => $this->session->userdata['user_id']);
            if (!empty($type)) {
                $where = [$type => $value, 'user_id' => 'adminstator'];
            }
            $records = pagination('tbl_support_message', $where, '*', 'dashboard/inbox-mail', 3, 10);
            $response['path'] =  $records['path'];

            $response['field'] = '';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>From</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Attachment</th>
                                <th>Date</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);

                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>Adminstator</td>
                                <td>' . $title . '</td>
                                <td>' . $message . '</td>
                                <td><img style="width: 100px; height: 100px;" src="' . base_url('uploads/' . $image) . '" alt="attachment" class="img-thumbnail"></td>
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

    public function Outbox()
    {
        if (is_logged_in()) {
            $response['header'] = 'Outbox';
            $type = $this->input->get('type');
            $value = $this->input->get('value');
            $where = array('user_id' => $this->session->userdata['user_id']);
            if (!empty($type)) {
                $where = [$type => $value, 'user_id' => $this->session->userdata['user_id']];
            }
            $records = pagination('tbl_support_message', $where, '*', 'dashboard/outbox-mail', 3, 10);
            $response['path'] =  $records['path'];

            $response['field'] =  '';
            $response['thead'] = '<tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Attachment</th>
                                <th> Date</th>
                             </tr>';
            $tbody = [];
            $i = $records['segment'] + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);

                $tbody[$key]  = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $title . '</td>
                                <td>' . $message . '</td>
                                <td>' . ($status == 0 ? badge_warning('Pending') : ($status == 1 ? badge_success('Approved') : ($status == 2 ? badge_danger('Rejected') : badge_info('Support')))) . '</td>
                                <td>' . $remark . '</td>
                                <td><img src="' . base_url('uploads/' . $image) . '" alt="attachment" style="withd: 150px;height: 150px;" class="img-thumbnail"></td>
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
