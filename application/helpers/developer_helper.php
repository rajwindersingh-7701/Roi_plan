
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

if (!function_exists('is_Developer')) {

    function is_Developer()
    {
        $ci = &get_instance();
        $ci->load->library('session');
        if (isset($ci->session->userdata['role']) && $ci->session->userdata['role'] == 'DA' && !empty($ci->session->userdata['developer_id']) && $ci->session->userdata['developer_id'] == 'developer') {
            if (!empty($ci->session->userdata['guard'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

?>