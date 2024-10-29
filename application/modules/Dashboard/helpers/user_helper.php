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
if (!function_exists('is_logged_in')) {

    //    protected $CI;
    function is_logged_in()
    {
        $ci = &get_instance();
        $ci->load->library('session');
        if (isset($ci->session->userdata['user_id']) && isset($ci->session->userdata['role'])) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('userinfo')) {

    function userinfo()
    {
        $ci = &get_instance();
        $ci->load->model('user_model');
        $userdetails = $ci->user_model->get_single_object('tbl_users', array('user_id' => $ci->session->userdata['user_id']), '*');
        return $userdetails;
    }
}


if (!function_exists('mynews')) {

    function mynews()
    {
        $ci = &get_instance();
        $ci->load->model('user_model');
        $mynews = $ci->user_model->get_records('tbl_news', array(), '*');
        return $mynews;
    }
}

if (!function_exists('bankinfo')) {
    function bankinfo()
    {
        $ci = &get_instance();
        $ci->load->model('user_model');
        $userdetails = $ci->user_model->get_single_object('tbl_bank_details', array('user_id' => $ci->session->userdata['user_id']), '*');
        return $userdetails;
    }
}

if (!function_exists('pool_count')) {

    function pool_count()
    {
        $ci = &get_instance();
        $ci->load->model('user_model');
        $pool_count = $ci->user_model->get_single_object('tbl_pool', array('user_id' => $ci->session->userdata['user_id']), 'ifnull(count(id),0) as pool_count');
        return $pool_count;
    }
}
if (!function_exists('pool_levels')) {

    function pool_levels()
    {
        $ci = &get_instance();
        $ci->load->model('user_model');
        $pool_count = $ci->user_model->get_records('tbl_pool', array('user_id' => $ci->session->userdata['user_id']), '*');
        return $pool_count;
    }
}

if (!function_exists('notify_user')) {

    function notify_user($user_id, $message)
    {
        $ci = &get_instance();
        $ci->load->model('user_model');
        $user = $ci->user_model->get_single_object('tbl_users', array('user_id' => $user_id), 'name,phone,email');
        // $msg = urlencode($message);
        // $url = "http://opendnd.smsmedia.org:8381/app/smsapi/index.php?username=IPOINTSA&password=iPoint123&campaign=8967&routeid=100308&type=text&contacts=".$user->phone."&senderid=NOTICE&msg=".$msg;
        // $user_id = 'NOTICE';

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_HEADER, 0);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_URL, $url);
        // $data = curl_exec($ch);
        // curl_close($ch);
        // $sms_data = array('user_id' => $user_id, 'message' => $msg, 'response' => $data);
        // $ci->user_model->add('tbl_sms_counter', $sms_data);
        /* for sms */
        $smsCount = $ci->user_model->get_single_record('tbl_sms_counter', array(), 'count(id) as record');
        if ($smsCount['record'] <= smslimit) {
            $key = "a08f1ade94XXAAAA";
            $userkey = "gniweb2";
            $senderid = "GRAMIN";
            $baseurl = "sms.gniwebsolutions.com/submitsms.jsp?";
            $msg = urlencode($message);
            $url = $baseurl . 'user=' . $userkey . '&&key=' . $key . '&&mobile=' . $user->phone . '&&senderid=' . $senderid . '&&message=' . $msg . '&&accusage=1';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            $data = curl_exec($ch);
            curl_close($ch);
            $sms_data = array('user_id' => $user_id, 'message' => $msg, 'response' => $data);
            $ci->user_model->add('tbl_sms_counter', $sms_data);
        }
        /* for sms */
        /* for email */
        //        date_default_timezone_set('Asia/Singapore');
        //        $CI = & get_instance();
        //        $CI->load->library('email');
        //        $CI->email->from('info@eindians.in', 'E Indians');
        //        $CI->email->to($user->email);
        //        $CI->email->subject('Registrataion Alert');
        //        $CI->email->message($message);
        //
        //        $CI->email->send();
        /* for email */
    }
}


if (!function_exists('send_otp')) {

    function send_otp($user_id, $message)
    {
        $ci = &get_instance();
        $ci->load->model('user_model');
        $user = $ci->user_model->get_single_object('tbl_users', array('user_id' => $user_id), 'name,phone,email');
        $smsCount = $ci->user_model->get_single_record('tbl_sms_counter', array(), 'count(id) as record');
        // if($smsCount['record'] <= 15000){
        /*for sms */
        //  $key = "55D358D995FB72";
        $key = "a08f1ade94XX";
        $userkey = "gniweb2";
        $senderid = "GRAMIN";
        $baseurl = "sms.gniwebsolutions.com/submitsms.jsp?";

        $msg = urlencode($message);
        $url = $baseurl . 'user=' . $userkey . '&&key=' . $key . '&&mobile=' . $user->phone . '&&senderid=' . $senderid . '&&message=' . $msg . '&&accusage=1';

        // $msg = urlencode($message);
        // $url = "http://opendnd.smsmedia.org:8381/app/smsapi/index.php?username=IPOINT&password=iPoint123&campaign=8967&routeid=100308&type=text&contacts=".$user->phone."&senderid=NOTICE&msg=".$msg;
        // $user_id = 'NOTICE';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        $sms_data = array('user_id' => $user_id, 'message' => $msg, 'response' => $data);
        $ci->user_model->add('tbl_sms_counter', $sms_data);
        /*for sms */
        // }
    }
}


if (!function_exists('tax')) {
    function tax()
    {
        $ci = &get_instance();
        $ci->load->model('user_model');
        $tax = $ci->user_model->get_single_object('tbl_tax', array('id' => 1), '*');
        return $tax->tax;
    }
}

if (!function_exists('sendMail')) {
    function sendMail($message, $email)
    {
        $CI = &get_instance();
        $CI->load->library('email');
        $CI->email->from('info@theroyalfuture.com', 'The Royal Future');
        $CI->email->to($email);
        $CI->email->subject('Registration Message');
        $CI->email->message($message);
        $CI->email->send();
    }
}

if (!function_exists('cart_items')) {

    function cart_items()
    {
        $ci = &get_instance();
        $ci->load->model('Shopping_model');
        $userdetails = $ci->Shopping_model->cart_items($ci->session->userdata['user_id']);
        return $userdetails;
    }
}

if (!function_exists('tree_img')) {

    function tree_img($package_amount, $empty)
    {
        if ($empty == 0) {
            if ($package_amount > 0) {
                $img = base_url('Assets/treeimage/tree.png');
            } else {
                $img = base_url('Assets/treeimage/male.jpg');
            }
        } else {
            $img = base_url('Assets/treeimage/unknown.jpg');
        }
        return $img;
    }
}
if (!function_exists('arrow_img')) {

    function arrow_img()
    {
        return base_url('Assets/treeimage/arrow.jpg');
    }
}
if (!function_exists('arrow1_img')) {

    function arrow1_img()
    {
        return base_url('Assets/treeimage/arrow1.jpg');
    }
}
if (!function_exists('arrow2_img')) {

    function arrow2_img()
    {
        return base_url('Assets/treeimage/arrow2.jpg');
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


function notify_mail($to_email, $message, $alert)
{
    $personalizations = new stdClass();

    $content = new stdClass();
    $from = new stdClass();
    $reply_to = new stdClass();

    $content->type  = 'text/plain';
    $content->value = $message;
    $from->email = 'afxcoin8@gmail.com';
    $from->name = title;


    $reply_to->email = 'afxcoin8@gmail.com';
    $reply_to->name = title;

    $to[0] = ['email' => $to_email];
    $personalizations->subject = $alert;
    $post_data['personalizations'][0] = $personalizations;
    $post_data['personalizations'][0]->to = $to;
    $post_data['content'][0] = $content;
    $post_data['from'] = $from;

    $post_data['reply_to'] = $reply_to;
    // echo json_encode($post_data);
    // pr($post_data);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($post_data),
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer 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",
            "cache-control: no-cache",
            "content-type: application/json",
        ),
    ));

    echo $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}

if (!function_exists('calculate_income')) {

    function calculate_income($incomeArr)
    {

        $incomes = incomes();
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

if (!function_exists('downlineTeam')) {
    function downlineTeam($user_id, $status, $position)
    {
        $ci = &get_instance();
        $ci->load->model('User_model');
        $team = $ci->User_model->calculate_Position_team($user_id, $status, $position);
        //pr($team);
        return $team['team'];
    }
}

if (!function_exists('notify')) {
    function notify($user_id, $message, $entity_id = '1201161518339990262', $temp_id = '')
    {
        $ci = &get_instance();
        $ci->load->model('user_model');
        $user = $ci->user_model->get_single_object('tbl_users', array('user_id' => $user_id), 'name,phone,email');
        $id_count = $ci->user_model->get_single_record('tbl_sms_counter', array(), 'count(id) as ids');
        if ($id_count['ids'] <= smslimit) {
            $key = "a08f1ade94XX";
            $userkey = "gniweb2";
            $senderid = "MLMSIG";
            $baseurl = "sms.gniwebsolutions.com/submitsms.jsp?";
            $msg = urlencode($message);
            // $url = $baseurl . 'user=' . $userkey . '&&key=' . $key . '&&mobile=' . $user->phone . '&&senderid=' . $senderid . '&&message=' . $msg . '&&accusage=1';
            $url = $baseurl . 'user=' . $userkey . '&&key=' . $key . '&&mobile=' . $user->phone . '&&senderid=' . $senderid . '&&message=' . $msg . '&&accusage=1&&entityid=' . $entity_id . '&&tempid=' . $temp_id;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            $data = curl_exec($ch);
            curl_close($ch);
            $sms_data = array('user_id' => $user_id, 'message' => $msg, 'response' => $data);
            $ci->user_model->add('tbl_sms_counter', $sms_data);
        }
    }
}

if (!function_exists('getBusiness')) {
    function getBusiness($user_id)
    {
        $ci = &get_instance();
        $ci->load->model('user_model');
        $teamBusiness = $ci->user_model->getBusiness($user_id);
        return $teamBusiness['teamBusiness'];
    }
}

if (!function_exists('send_crypto_email')) {
    function send_crypto_email($email, $subject, $message)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://64.227.105.87:8000/send_email_multi',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'user=' . user . '&pass=' . pass . '&from=' . sender_id . '&title=' . sender_name . '&email=' . $email . '&subject=' . $subject . '&html=<div style=\' background: #000000;
    margin: auto;
    max-width: 400px;
    padding: 20px;
    border: 4px #4ce7ff solid;
    margin: 10px auto;\'><center><img style=\'max-width:200px;margin: 0;border-radius: 10px;\' src="' . base_url(logo) . '" alt=\'logo\'><br><h3 style=\'color:#fff;\'>' . $message . '</h3><div style=\'font-size:20px;font-weight: bold; color:#ffea81; margin-top:20px\'><a href="' . base_url('Dashboard/User/MainLogin') . '" style=\'background-color:#17b824; color:#fff;width: 100%; font-weight:normal; border-radius: 4px;  display: block;\'>Click here to login</a></div></center></div>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
    }
}


if (!function_exists('notifySms')) {
    function notifySms($user_id, $message, $entity_id = '1201161518339990262', $temp_id = '')
    {
        $ci = &get_instance();
        $ci->load->model('User_model');
        $user = $ci->User_model->get_single_record('tbl_users', array('user_id' => $user_id), 'name,phone,email,user_id');
        $id_count = $ci->User_model->get_single_record('tbl_sms_counter', array(), 'count(id) as ids');
        // if($id_count['ids'] <= smslimit){
        $key = "a08f1ade94XX";
        $userkey = "gniweb2";
        $senderid = "MLMSIG";
        $baseurl = "sms.gniwebsolutions.com/submitsms.jsp?";

        $msg = urlencode($message);
        //  echo $user->phone;
        // $url = $baseurl . 'user=' . $userkey . '&&key=' . $key . '&&mobile=' . $user->phone . '&&senderid=' . $senderid . '&&message=' . $msg . '&&accusage=1';
        $url = $baseurl . 'user=' . $userkey . '&&key=' . $key . '&&mobile=' . $user['phone'] . '&&senderid=' . $senderid . '&&message=' . $msg . '&&accusage=1&&entityid=' . $entity_id . '&&tempid=' . $temp_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        // pr($data);
        $sms_data = array('user_id' => $user_id, 'message' => $msg, 'response' => $data);
        $ci->User_model->add('tbl_sms_counter', $sms_data);
        // }
    }
    if (!function_exists('notifySms1')) {
        function notifySms1($user_id, $message, $sender_id, $entity_id, $temp_id)
        {
            $ci = &get_instance();
            $ci->load->model('User_model');
            $user = $ci->User_model->get_single_record('tbl_users', array('user_id' => $user_id), 'name,phone,email,user_id');
            $id_count = $ci->User_model->get_single_record('tbl_sms_counter', array(), 'count(id) as ids');
            if ($id_count['ids'] <= smslimit) {
                $curl = curl_init();
                $msg = urlencode($message);
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://sms2.pmassindia.com/submitsms.jsp?user=kuldeepgni&key=0c3f226a6eXX&mobile=+91' . $user['phone'] . '&message=' . $msg . '&senderid=' . $sender_id . '&accusage=1&entityid=' . $entity_id . '&tempid=' . $temp_id . '',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Cookie: JSESSIONID=88613C89F0C3276FCDA9F508F62C7DA1'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                // echo $response;
                $sms_data = array('user_id' => $user_id, 'message' => $msg, 'response' => $response);
                $ci->User_model->add('tbl_sms_counter', $sms_data);
            }
        }
    }
}


if (!function_exists('phoneVerify')) {
    function phoneVerify($phone, $message, $sender_id, $entity_id, $temp_id)
    {
        $ci = &get_instance();
        $ci->load->model('User_model');
        //$user = $ci->User_model->get_single_record('tbl_users', array('user_id' => $user_id), 'name,phone,email,user_id');
        $id_count = $ci->User_model->get_single_record('tbl_sms_counter', array(), 'count(id) as ids');
        if ($id_count['ids'] <= smslimit) {
            $curl = curl_init();
            $user['phone'] = $phone;
            $msg = urlencode($message);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://sms2.pmassindia.com/submitsms.jsp?user=kuldeepgni&key=0c3f226a6eXX&mobile=+91' . $user['phone'] . '&message=' . $msg . '&senderid=' . $sender_id . '&accusage=1&entityid=' . $entity_id . '&tempid=' . $temp_id . '',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Cookie: JSESSIONID=88613C89F0C3276FCDA9F508F62C7DA1'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            // echo $response;
            $sms_data = array('user_id' => $phone, 'message' => $msg, 'response' => $response);
            $ci->User_model->add('tbl_sms_counter', $sms_data);
        }
    }
}

if (!function_exists('phoneMessageregister')) {
    function phoneMessageregister($user_id , $phone ,$msg)
    {
        $ci = &get_instance();
        $ci->load->model('User_model');
        $user = $ci->User_model->get_single_record('tbl_users', array('user_id' => $user_id), 'phone,name,user_id,master_key,password');
        $id_count = $ci->User_model->get_single_record('tbl_sms_counter', array(), 'count(id) as ids');
        if ($id_count['ids'] <= smslimit) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                // CURLOPT_URL => 'http://sms2.pmassindia.com/submitsms.jsp?user=kuldeepgni&key=0c3f226a6eXX&mobile=%2B91' . $user['phone'] . '&message=Dear%20user%20your%20profile%20id%20' . $user['user_id'] . '%20has%20been%20activated%20successfully.%20Regards%3A%' . base_url() . '%20Omkar%20Enterprises&senderid=%20OENTER&accusage=1&entityid=1401487200000053882&tempid=%201407170903820868433',
               
                CURLOPT_URL => 'http://sms2.pmassindia.com/submitsms.jsp?user=kuldeepgni&key=0c3f226a6eXX&mobile=' . $phone . '&message=' . $msg . '&senderid=%20OENTER&accusage=1&entityid=1401487200000053882&tempid=1407168594286519178',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Cookie: JSESSIONID=2B9C686A70A22EFE443B333E5C985E0D'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            // echo $response;
            // $msg = 'message=Dear%20user%20your%20profile%20id%20' . $user['user_id'] . '%20has%20been%20activated%20successfully.%20Regards%3A%20GNI%20SOFT%20Omkar%20Enterprises';
            // $msg = 'message=Dear%20' . $user['name'] . '%20you%20are%20successfully%20registered%20Your%20id%20%3A%20' . $user['user_id'] . '%20and%20password%20is%20' . $user['password'] . '%20and%20transaction%20password%20is%20' . $user['master_key'] . '%20Website%3A%20' . base_url() . '%20omkarent';
            $sms_data = array('user_id' => $user['user_id'], 'message' => $msg, 'response' => $response);
            $ci->User_model->add('tbl_sms_counter', $sms_data);
        }
    }
}
