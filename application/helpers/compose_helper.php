<?php
if (!function_exists('composeMail')) {
    function composeMail($email, $subject, $message, $display = false)
    {
        if (!empty($email)) {
            // Integrate by MV 
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.zeptomail.in/v1.1/email",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                // CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => '{
                    "bounce_address":"' . bounce_address . '",
                    "from": { "address": "' . from . '"},
                    "to": [{"email_address": {"address": "' . $email . '"}}],
                    "subject":"' . $subject . '",
                    "htmlbody":"<div><b>' . $message . ' </b></div>",
                    }',
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "authorization: " . authorization,
                    "cache-control: no-cache",
                    "content-type: application/json",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
        }
        if ($display == true) {
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
            // echo json_encode($data);
        }
    }
}

if (!function_exists('composeMail2')) {
    function composeMail2($email, $subject, $msg)
    {
        if (!empty($email)) {

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => '18.216.195.54:3490/send_email',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'to=' . $email . '&subject=' . $subject . '&message=<div style=\' background: #000; margin:auto; max-width:500px;\'><center><img style=\'max-width:200px;margin: 0;border-radius: 10px;\' src=\'' . logo . '\' alt=\'logo\'><br><h3 style=\'color:#fff;\'>' . $msg . '</h3><div style=\'font-size:20px;font-weight: bold; color:#45aed7; margin-top:20px\'><a href=\'' . base_url() . '\' style=\'background-color:#17b824; color:#fff;width: 100%; font-weight:normal; border-radius: 4px;  display: block;\'>Click here to login</a></div></center></div>',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            // echo $response;
            // echo 'herte';   
        }
    }
}
