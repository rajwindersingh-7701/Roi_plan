<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Network extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption', 'form_validation', 'security', 'email', 'Binance', 'pagination'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'super'));
        if (is_logged_in() === false) {
            redirect('Dashboard/User/logout');
            exit;
        }
    }

    public function levelView()
    {
        if (is_logged_in()) {
            $response['header'] = 'Team Business View';
            $total = $this->User_model->getLevelSum($this->session->userdata['user_id']);
            $config['total_rows'] = $total['ids'];
            $config['base_url'] = base_url('Dashboard/Network/levelView');
            $config['uri_segment'] = 4;
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
            $config['first_tag_open'] = '<li class="paginate_button page-item first">';
            $config['first_link'] = 'First';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li class="paginate_button page-item last">';
            $config['last_link'] = 'Last';
            $config['last_tag_close'] = '</li>';
            $config['prev_link'] = 'Previous';
            $config['prev_tag_open'] = '<li class="paginate_button page-item previous">';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = 'Next';
            $config['next_tag_open'] = '<li  class="paginate_button page-item next">';
            $config['next_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            $segment = $this->uri->segment(4);
            $records['records'] = $this->User_model->getLevelMember1($this->session->userdata['user_id'], $config['per_page'], $segment);
            $response['path'] = $config['base_url'];
            $response['field'] = '';
            $response['thead'] = '<tr>
                                    <th>#</th>
                                    <th>Level</th>
                                    <th>Total id</th>
                                    <th>Paid Team</th>
                                    <th>Free Team</th>
                                    <th>Action</th>
                                 </tr>';
            $tbody = [];
            $i = $segment + 1;
            foreach ($records['records'] as $key => $rec) {
                extract($rec);
                $freeTeam = $this->User_model->calculateLevelTeam($this->session->userdata['user_id'], 0, $rec['level']);
                $paidTeam = $this->User_model->calculateLevelTeam($this->session->userdata['user_id'], 1, $rec['level']);
                $view = '<a class=" btn btn-info "  href="' . base_url('Dashboard/Network/levelDetails/') . $level . '" target="_blank">View</a>';

                $tbody[$key] = '<tr>
                                    <td>' . $i . '</td>
                                    <td>' . $level . '</td>
                                    <td>' . $team . '</td>
                                    <td>' . $paidTeam['team'] . '</td>
                                    <td>' . $freeTeam['team'] . '</td>
                                    <td>' . $view . '</td>
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
            redirect('Dashboard/User/login');
        }
    }

    public function levelDetails($level)
    {
        $response['header'] = 'Level View';
        $type = $this->input->get('type');
        $value = $this->input->get('value');
        $where = ['user_id' => $this->session->userdata['user_id'], 'level' => $level];
        if (!empty($type)) {
            $where = ['user_id' => $this->session->userdata['user_id'], 'level' => $level, $type => $value];
        }
        $records = pagination('tbl_sponser_count', $where, '*', 'Dashboard/Network/levelDetails/' . $level, 5, 10);
        $response['path'] = $records['path'];
        $response['field'] = '';
        $response['thead'] = '<tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Sponsor ID</th>
                                <th>Stake Amount</th>
                                <th>Level</th>
                                <th>Date</th>
                             </tr>';
        $tbody = [];
        $i = $records['segment'] + 1;
        foreach ($records['records'] as $key => $rec) {
            extract($rec);
            $userinfo = get_single_record('tbl_users', ['user_id' => $rec['downline_id']], '*');
            $tbody[$key] = ' <tr>
                                <td>' . $i . '</td>
                                <td>' . $downline_id . '</td>
                                <td>' . $userinfo['name'] . '</td>
                                <td>' . $userinfo['sponser_id'] . '</td>
                                <td>' . $userinfo['package_amount'] . '</td>
                                <td>' . $level . '</td>
                                <td>' . $updated_at . '</td>
                             </tr>';
            $i++;
        }
        $response['tbody'] = $tbody;
        $response['export'] = false;
        $response['search'] = false;
        $response['balance'] = false;
        $response['total_income'] = '';
        $this->load->view('reports', $response);
    }
}
