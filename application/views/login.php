<?php
// if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
//     $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//     header('HTTP/1.1 301 Moved Permanently');
//     header('Location: ' . $redirect);
//     exit();
// }
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <!-- Meta Tags -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta content="<?php echo title; ?>" name="description" />
    <meta content="<?php echo title; ?>" name="author" />
    <!-- Title -->
    <title><?php echo title; ?></title>
    <link href="<?php echo base_url('classic/assets/css/site.css'); ?>" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Oswald:200,300,400,500,600,700|Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.bimboo.com.br/layout/owlcarousel/1.3.3/owl.carousel.css">
    <script src="https://cdn.bimboo.com.br/layout/owlcarousel/1.3.3/owl.carousel.js"></script>

</head>

<body>

    <style>
        body {
            background: url(https://www.bigdataforhumans.com/media/1164/gdpr_success-1.jpeg);
            background-size: cover;
            padding: 40px 0px
        }

        p {
            margin: 0 0 10px;
            color: #fff;
        }
    </style>
    <!--<link type="text/css" rel="stylesheet" href="mlm-design/assets/css/toastr.css" media="all" />-->
    <div class="midpart p-t-b-95">
        <section class="" style="">
            <div class="login_page">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6">
                            <div style="background: #000;border-radius: 20px;text-align: center;display: table;margin: 0px auto;max-width: 600px;padding-top: 20px;padding: 20px 20px;margin-bottom: 40px; margin-top:10px;   -webkit-box-shadow: -1px 2px 17px -2px rgba(0,0,0,0.75);
                                     -moz-box-shadow: -1px 2px 17px -2px rgba(0,0,0,0.75);
                                     box-shadow: -1px 2px 17px -2px rgba(0,0,0,0.75);
                                     border-radius:20px;">
                                <h1 style="color:#e60040; font-size:24px">Login Now</h1>
                                <p>Fill up the below form to get access to our site</p>
                                <p style="text-align:center; color:red; font-size:20px; display:none">We are moving our server to high VPS Server so it should be take 1 day to complete. We will be back soon.</p>
                                <div id="CPH1_Up1">
                                    <div id="CPH1_updProgress" style="display:block;">
                                        <div class="ProgressBg" style="background: #000;">
                                            <center>
                                                <img alt="progress" src="<?php echo logo; ?>" width="200px" />
                                            </center>
                                        </div>
                                    </div>
                                    <div class="log-box">
                                        <span class="text-center text-danger"><?php echo $message; ?></span>
                                        <?php echo form_open(base_url('DeveloperMode/login'), array('id' => 'loginForm')); ?>
                                        <form id="login-form" action="" method="post" style="display:block">
                                            <div class="form-group">
                                                <div class="irs-donation-col">
                                                    <?php
                                                    echo form_input(array(
                                                        'type' => 'text',
                                                        'name' => 'user_id',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'User ID',
                                                        'required' => 'true',
                                                    ));
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="irs-donation-col">
                                                    <?php
                                                    echo form_input(array(
                                                        'type' => 'password',
                                                        'name' => 'password',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'Password',
                                                        'required' => 'true',
                                                    ));
                                                    ?>
                                                </div>
                                            </div>


                                          
                                            <!-- <div class="form-group" id="input_otp" style="display:none;">
                                                    <div class="irs-donation-col">
                                                    <?php
                                                    // echo form_input(array(
                                                    //     'type' => 'text',
                                                    //     'name' => 'otp',
                                                    //     'class' => 'form-control',
                                                    //     'placeholder' => 'Enter OTP',
                                                    //     'required' => 'true',
                                                    // ));
                                                    ?>
                                                    </div>
                                                </div> -->
                                            <div class="col-md-12 col-sm-12">
                                                <div class="irs-donation-col">
                                                    <!-- <button id="otp" class="btn btn-success pull-right" type="button" onclick="getOtp()">Get OTP</button> -->

                                                    <button class="btn btn-success pull-right" id="login" style="display:block;" type="submit">Login Now</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>Ï
            </div>
        </section>
    </div>

</body>

</html>
