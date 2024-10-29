<!DOCTYPE html>
<html dir="ltr" lang="en">

<?php
if (http == 0) {
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirect);
        exit();
    }
}

?>

<head>
    <!-- Meta Tags -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="description" content="<?php echo title; ?>">
    <meta name="keywords" content="<?php echo title; ?>">

    <!-- Title -->
    <title><?php echo title; ?> </title>
    <link href="<?php echo base_url('classic/assets/css/site.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/style.css" rel="stylesheet">

    <link rel="icon" href="<?php echo logo; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Oswald:200,300,400,500,600,700|Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

</head>

<body>

    <section class="login_body_1">
        <div class="container">
            <div class="card_login_1">

                <div class="logo__sec">
                    <img alt="progress" src="<?php echo logo; ?>" width="100%" />
                </div>

                <div class="login_txt">
                    <h4>Admin Login</h4>
                    <!-- <p>Ensure that your login page is served over HTTPS </p> -->
                </div>



                <div class="log-box">
                    <span class="text-center text-danger"><?php echo $message; ?></span>
                    <?php echo form_open($base_url, array('id' => 'loginForm')); ?>
                    <form id="login-form" action="" method="post" style="display:block">
                        <div class="form-group">
                            <div class="irs-donation-col">
                                <?php
                                echo form_input(array('type' => 'text', 'name' => 'user_id', 'class' => 'form-control', 'placeholder' => 'User ID', 'required' => 'true',)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="irs-donation-col">
                                <?php
                                echo form_input(array('type' => 'password', 'name' => 'password', 'class' => 'form-control', 'placeholder' => 'Password', 'required' => 'true',)); ?>
                            </div>
                        </div>

                        <div class="form-group remove-margin">
                            <button class="btn new_btn" type="submit">Login Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</body>

</html>
<script>
    function getOTP() {
        var url = '<?php echo base_url('Admin/Management/getOTP'); ?>';
        fetch(url, {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(response => {
                alert(response.message);
            })
    }
</script>