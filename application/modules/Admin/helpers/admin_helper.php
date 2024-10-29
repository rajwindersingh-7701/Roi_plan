<?php
if (!function_exists('is_admin')) {
    function is_admin()
    {
        $ci = &get_instance();
        $ci->load->library(['session', 'uri']);
        $ci->load->model('Main_model');
        if (isset($ci->session->userdata['role']) && $ci->session->userdata['role'] == 'A') {
            return true;
        } elseif (isset($ci->session->userdata['role']) && $ci->session->userdata['role'] == 'SA') {
            $uriRoutes = $ci->uri->segment_array();
            // pr($uriRoutes);
            // die;
            if (!empty($uriRoutes[4])) {
                $value4 = $uriRoutes[4];
            }
            if (!empty($uriRoutes[3])) {
                $value3 = $uriRoutes[3];
            }
            // $value3 = $uriRoutes[3];
            $value2 = $uriRoutes[2];
            $value1 = $uriRoutes[1];
            $userAccess = $ci->Main_model->get_single_record('tbl_admin', ['user_id' => $ci->session->userdata['admin_id']], 'access');
            $access = json_decode($userAccess['access'], true);

            if ($ci->session->userdata['admin_id'] != 'admin') {
                if ($value1 == 'admin' && $value2 == 'dashboard') {
                    return true;
                    die('wait');
                } elseif (!empty($uriRoutes[4])) {
                    if (in_array($value1 . '/' . $value2 . '/' . $value3 . '/' . $value4, $access)) {
                        return true;
                        die('ok');
                    } else {
                        die('not ok');
                        redirect('access-deined');
                    }
                } elseif (!empty($uriRoutes[3])) {
                    if (in_array($value1 . '/' . $value2 . '/' . $value3, $access)) {
                        return true;
                        die('ok');
                    } else {
                        die('not ok');
                        redirect('access-deined');
                    }
                } elseif (in_array($value1 . '/' . $value2, $access)) {
                    return true;
                    die('ok');
                } else {
                    if ($value1 == 'Sublogin') {
                        return true;
                        die('stop');
                    } else {
                        pr($value1);
                        pr($value2);
                        pr($value3);
                        die('not ok');
                        redirect('access-deined');
                    }
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('notifySms')) {
    function notifySms($message)
    {
        $message = urlencode($message);
        $url = 'http://msgalert.in/apiv2?route=4&authkey=43defe854dc3d59cf12c6ff6aca2716a&senderid=EANDGR&numbers=' . adminContact . '&message=' . $message . '&template_id=1207164260024841437&route=4';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=df3d72d62c7159d9938935f8363fc73ae75dfef8; csrf_cookie_shubhme=9e4183a6ef7486c254ef188601104b16'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);
        if ($result['status'] == 1) {
            return true;
        } else {
            return false;
        }
    }
}
