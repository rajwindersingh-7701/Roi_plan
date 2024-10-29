<?php
if (!function_exists('pr')) {
    function pr($array, $die = false)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        if ($die)
            die();
    }
}

if (!function_exists('set_flashdata')) {
    function set_flashdata($set, $message)
    {
        $ci = &get_instance();
        $ci->load->library('session');
        $message = $ci->session->set_flashdata($set, $message);
        return $message;
    }
}

if (!function_exists('update')) {
    function update($table, $where, $data)
    {
        $ci = &get_instance();
        $ci->load->model('Super_model');
        $userdetails = $ci->Super_model->update($table, $where, $data);
        return $userdetails;
    }
}

if (!function_exists('get_single_record')) {
    function get_single_record($table, $where, $select)
    {
        $ci = &get_instance();
        $ci->load->model('Super_model');
        $userdetails = $ci->Super_model->get_single_record($table, $where, $select);
        return $userdetails;
    }
}

if (!function_exists('get_records')) {
    function get_records($table, $where, $select)
    {
        $ci = &get_instance();
        $ci->load->model('Super_model');
        $userdetails = $ci->Super_model->get_records($table, $where, $select);
        return $userdetails;
    }
}

if (!function_exists('get_sum')) {
    function get_sum($table, $where, $select)
    {
        $ci = &get_instance();
        $ci->load->model('Super_model');
        $userdetails = $ci->Super_model->get_sum($table, $where, $select);
        return $userdetails;
    }
}

if (!function_exists('get_limit_records')) {
    function get_limit_records($table, $where, $select, $per_page, $segment)
    {
        $ci = &get_instance();
        $ci->load->model('Super_model');
        $userdetails = $ci->Super_model->get_limit_records($table, $where, $select, $per_page, $segment);
        return $userdetails;
    }
}

if (!function_exists('add')) {
    function add($table, $data)
    {
        $ci = &get_instance();
        $ci->load->model('Super_model');
        $userdetails = $ci->Super_model->add($table, $data);
        return $userdetails;
    }
}

if (!function_exists('pagination')) {
    function pagination($table, $where, $select, $base_url, $segment, $per_page, $orderby = 'asc')
    {
        $ci = &get_instance();
        $ci->load->model('Super_model');
        $config['total_rows'] = $ci->Super_model->get_sum($table, $where, 'ifnull(count(id),0) as sum');
        $config['base_url'] = base_url($base_url);
        $config['suffix'] = '?' . http_build_query($_GET);
        $config['uri_segment'] = $segment;
        $config['per_page'] = $per_page;
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
        $ci->pagination->initialize($config);
        $segment = $ci->uri->segment($segment);
        $records =  $ci->Super_model->get_limit_records($table, $where, $select, $config['per_page'], $segment, $orderby);
        $response = ['records' => $records, 'segment' => $segment, 'path' => $config['base_url'], 'total_records' => $config['total_rows']];
        return $response;
    }
}

if (!function_exists('finalExport')) {

    function finalExport($export, $application_type, $header, $records)
    {
        if ($export) {
            $filename = $export . 'Summary_' . time() . '.' . $export;
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: " . $application_type . "");
            $file = fopen('php://output', 'w');
            $header = $header;
            fputcsv($file, $header);

            foreach ($records as $key => $line) {
                fputcsv($file, $line);
            }

            fclose($file);
            exit();
        }
    }
}

if (!function_exists('mynews')) {

    function mynews()
    {
        $ci = &get_instance();
        $ci->load->model('Super_model');
        $mynews = $ci->Super_model->get_records('tbl_news', array(), '*');
        return $mynews;
    }
}

if (!function_exists('tree_img')) {

    function tree_img($package_amount, $empty)
    {
        if ($empty == 0) {
            if ($package_amount > 0) {
                $img = base_url('SiteAssets/treeimage/tree.png');
            } else {
                $img = base_url('SiteAssets/treeimage/male.jpg');
            }
        } else {
            $img = base_url('SiteAssets/treeimage/unknown.jpg');
        }
        return $img;
    }
}


if (!function_exists('incomes')) {
    function incomes()
    {
        $ci = &get_instance();
        $ci->load->config('config');
        $incomes = $ci->config->item('incomes');
        return $incomes;
    }
}

if (!function_exists('get_income_name')) {
    function get_income_name($income_name)
    {
        $ci = &get_instance();
        $ci->load->config('config');
        $incomes = $ci->config->item('getType');
        //return $incomes;
        return $incomes[$income_name];
    }
}


if (!function_exists('calculate_income')) {

    function calculate_income($incomeArr)
    {

        $ci = &get_instance();
        $ci->load->config('config');
        $incomes = $ci->config->item('incomes');
        // $incomes = incomes();
        $income_count = array();
        $total_payout = 0;
        foreach ($incomes as $key => $income) {
            $income_count[$key] = 0;
            foreach ($incomeArr as $arr) {
                if ($arr['type'] == $key) {
                    $income_count[$key] = $arr['sum'];
                }
            }
            $total_payout = $income_count[$key] + $total_payout;
        }
        $income_count['total_payout'] = $total_payout;
        return $income_count;
    }
}

if (!function_exists('span_success')) {

    function span_success($message)
    {
        return '<div class="alert alert-success" role="alert"> ' . $message . ' </div>';
    }
}

if (!function_exists('span_danger')) {

    function span_danger($message)
    {
        return '<div class="alert alert-danger" role="alert"> ' . $message . ' </div>';
    }
}

if (!function_exists('span_info')) {

    function span_info($message)
    {
        return '<div class="alert alert-info" role="alert"> ' . $message . ' </div>';
    }
}

if (!function_exists('span_danger_simple')) {

    function span_danger_simple($message)
    {
        return '<span class="text-danger"> ' . $message . ' </span>';
    }
}

if (!function_exists('span_success_simple')) {

    function span_success_simple($message)
    {
        return '<span class="text-success"> ' . $message . ' </span>';
    }
}
if (!function_exists('span_info_simple')) {

    function span_info_simple($message)
    {
        return '<span class="text-info"> ' . $message . ' </span>';
    }
}
if (!function_exists('badge_success')) {

    function badge_success($message)
    {
        return '<span class="badge bg-success"> ' . $message . ' </span>';
    }
}
if (!function_exists('badge_danger')) {

    function badge_danger($message)
    {
        return '<span class="badge bg-danger"> ' . $message . ' </span>';
    }
}
if (!function_exists('badge_info')) {

    function badge_info($message)
    {
        return '<span class="badge bg-info"> ' . $message . ' </span>';
    }
}
if (!function_exists('badge_warning')) {

    function badge_warning($message)
    {
        return '<span class="badge bg-warning"> ' . $message . ' </span>';
    }
}


if (!function_exists('incomeProccess')) {
    function incomeProccess($user_id, $amount)
    {
        $ci = &get_instance();
        $ci->load->model('User_model');
        $userinfo = $ci->User_model->get_single_record('tbl_users', ['user_id' => $user_id], 'sponsor_id,paid_status,directs,total_limit,pending_limit');
        if ($userinfo['paid_status'] == 1) {
            if ($userinfo['total_limit'] > $userinfo['pending_limit']) {
                $totalCredit = $userinfo['pending_limit'] + ($amount);
                if ($totalCredit < $userinfo['total_limit']) {
                    $income = $amount;
                } else {
                    $income = $userinfo['total_limit'] - $userinfo['pending_limit'];
                }
                if ($income > 0) {
                    $this->Main_model->update('tbl_users', ['user_id' => $user_id], ['pending_limit' => ($userinfo['pending_limit'] + $income)]);
                    return $income;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}
