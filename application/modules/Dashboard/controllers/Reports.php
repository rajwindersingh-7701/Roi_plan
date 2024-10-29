<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'pagination'));
        $this->load->model(array('User_model'));
        $this->load->helper(array('user', 'super'));
        if (is_logged_in() === false) {
            redirect('Dashboard/User/logout');
            exit;
        }
    }

       public function levelReport()
    {
        $response['header'] = 'Level Report';

        $where = ['level <=' => 10];

        $rowCount = $this->User_model->grouped_by_level2('tbl_sponser_count', $where, '*');
        $config['total_rows'] = count($rowCount);
        $config['base_url'] = base_url() . 'dashboard/levelReport';
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
        $records['records'] = $this->User_model->grouped_by_levels($where, $config['per_page'], $segment);
        //    pr($records);
        //    die;
        $response['path'] =   $config['base_url'];
        $response['field'] = '';
        $response['thead'] = '<tr>
                                   
                                    <th>level</th>
                                    <th>Count Id</th>
                                    <th>Active</th>
                                    <th>Pending</th>
                                    <th>Action</th>
                                 </tr>';
        $tbody = [];
        $i = $segment + 1;
        // $i = $records['segment'] + 1;
        foreach ($records['records'] as $key => $rec) {
            $getActive = $this->User_model->calculateTeamLevel($this->session->userdata['user_id'], 1, $rec['level']);
            $getFree = $this->User_model->calculateTeamLevel($this->session->userdata['user_id'], 0, $rec['level']);
            $ids = get_single_record('tbl_sponser_count', ['user_id' => $this->session->userdata['user_id'], 'level' => $rec['level']], 'count(id)as total');
            extract($rec);
            $button = '<a href="' . base_url('Dashboard/Reports/levelView/' . $level) . '" class ="btn btn-info" >view</a>';
            $tbody[$key]  = ' <tr>
                                
                                    <td>' . $level  . '</td>
                                    <td>' . $ids['total'] . '</td>
                                <td>' . $getActive['team'] . '</td>
                                <td>' . $getFree['team'] . '</td>
                                    <td>' . $button . '</td>
                                 </tr>';
            $i++;
        }

        $response['tbody'] = $tbody;
        $response['segment'] = $i;
        $response['total_records'] = $config['total_rows'];
        $response['i'] = $i;
        $this->load->view('reports', $response);
    }



    public function levelView($level)
    {
        $response['header'] = 'Level';
        $where = 'user_id ="' . $this->session->userdata['user_id'] . '" and level = "' . $level . '"';
        $records = pagination('tbl_sponser_count', $where, '*', 'Dashboard/Reports/levelView/' . $level,  5, 10);
        $response['path'] =  $records['path'];
        $response['field'] = '';
        $response['thead'] = '<tr>
                                     <th>#</th>  
                                    <th>Downline Id</th>
                                    <th>Package</th>
                                    <th>Name</th>
                                    <th>level</th>
                                    <th>Date</th>
                                 </tr>';
        $i = $records['segment'] + 1;
        $tbody = [];
        foreach ($records['records'] as $key => $rec) {
            extract($rec);
            $user = get_single_record('tbl_users', ['user_id' => $rec['downline_id']], '*');

            $tbody[$key]  = ' <tr>
                                 <td>' . $i . '</td>        
                                    <td>' . $downline_id . '</td>
                                    <td>' . currency . $user['package_amount'] . '</td>
                                    <td>' . $user['name'] . '</td>
                                    <td>' . $level . '</td>
                                    <td>' . $created_at . '</td>
                                 </tr>';
            $i++;
        }
        $response['tbody'] = $tbody;
        $response['segment'] = $records['segment'];
        $response['total_records'] = $records['total_records'];
        $response['i'] = $i;
        $this->load->view('reports', $response);
    }

}
