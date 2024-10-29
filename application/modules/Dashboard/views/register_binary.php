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
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" /> -->
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url('uploads/favicon.png'); ?>">
    <!-- Bootstrap Css -->
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/register.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.cdnfonts.com/css/koho-2" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">



</head>

<body>


    <div id="">
        <div class="">

            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="sub_title">
                                    <a href="<?php echo base_url(); ?>">
                                        <img src=" <?php echo logo; ?>" class="header-brand-img desktop-logo" alt="logo" />
                                    </a>
                                    <h6 class="account1">Register</h6>
                                    <p>Fill the details below.</p>
                                </div>
                                <div class="col-sm-12 col-md-12 text-white text-center mt-4">
                                    <h5 style="text-transform: uppercase;color: #000;font-weight: bold;font-size: 22px;"></h5>
                                    <!-- <p class="m-0">Login to Your Personal Account</p> -->
                                </div>
                                <div class="panel panel-primary">
                                    <div class="form-element">
                                        <?php echo $this->session->flashdata('register_message'); ?>
                                        <?php echo form_open('register', array('id' => '')); ?>
                                        <div class="form-wrap has-feedback align-items-start mb-0">
                                            <input type="text" class="form-control" id="sponser_id" placeholder="Sponser ID" value="<?php echo $sponser_id; ?>" name="sponser_id" required />
                                            <span class="text-danger"><?php echo form_error('sponser_id'); ?></span>
                                            <span id="sponser_name" class="text-danger"> </span>
                                        </div>
                                        <div class="form-wrap has-feedback mb-0 mt-3">
                                            <input type="text" class="form-control" id="name" placeholder="Name" name="name" />
                                            <!-- <span class="text-danger"><?php echo form_error('name'); ?></span> -->
                                        </div>
                                        <div class="form-wrap has-feedback mb-0 mt-3">
                                            <input type="number" class="form-control" id="phone" placeholder="Phone" name="phone" required />

                                            <!-- <span class="text-danger"><?php echo form_error('name'); ?></span> -->
                                        </div>
                                        <?php if(empty($_GET['position'])){ ?>
                                        <div class="form-control mt-3 mycheck">
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" id="left" name="userPosition" value="L" <?php echo (!empty($_GET['position']) && $_GET['position'] == 'L') ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="left">Left</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" id="right" name="userPosition" value="R" <?php echo (!empty($_GET['position']) && $_GET['position'] == 'R') ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="right">Right</label>
                                            </div>
                                        </div>
                                        <?php }else{ 
                                            if($_GET['position'] == "R"){
                                                $name   = "Right";
                                            }else{
                                                $name   = "Left";

                                            }
                                            ?>
                                            
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" id="<?php echo $name; ?>" name="userPosition" value="<?php echo $_GET['position']; ?>" checked >
                                                <label class="form-check-label" for="<?php echo $name; ?>"><?php echo $name; ?></label>
                                            </div>
                                        <?php } ?>
                                        <div class="form-check-inline mt-3">
                                            <input id="deviceremember" type="checkbox" class="mr-2">
                                            <label class="form-check-label text-white" for="deviceremember">I have read the <a href="">Terms & Conditions</a></label>
                                        </div>
                                    </div>


                                    <div class="form-group has-feedback" id="">
                                        <button type="submit" value="register" class="button-three">Register</button>
                                    </div>
                                    <div class="text-center create-acc mt-3">
                                        <p class="m-0">Already have account? <a href="<?php echo base_url('login'); ?>" class="tgreen">Login</a></p>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="<?php echo base_url('NewDashboard/') ?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url('NewDashboard/') ?>assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="<?php echo base_url('NewDashboard/') ?>assets/libs/simplebar/simplebar.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var selection = document.getElementById("allcountry");
        selection.onchange = function(event) {
            var option = '';
            var countryID = event.target.value;
            if (countryID != '') {
                var url = "<?php echo base_url('Dashboard/Register/countryCode/'); ?>" + countryID;
                fetch(url, {
                        method: "GET",
                    })
                    .then(response => response.json())
                    .then(response => {
                        console.log(response);
                        document.getElementById('countryCode1').value = '+' + response.phonecode;
                    });
            } else {
                document.getElementById('countryCode1').value = '';
            }
        };
        const maxWidth = window.screen.width;
        const maxHeight = window.screen.height;

        function Random(min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min) + min);
        }

        function Shadows(amount) {
            let shadow = "";
            for (let i = 0; i < amount; i++) {
                shadow += Random(0, maxWidth) + "px " + Random(0, maxHeight) + "px " + "rgb(255," + Random(0, 256) + "," + Random(0, 256) + "), ";
            }
            shadow += Random(0, maxWidth) + "px " + Random(0, maxHeight) + "px " + "rgb(255," + Random(0, 256) + "," + Random(0, 256) + ")";
            return (shadow);
        }

        for (let i = 1; i <= 3; i++) {
            document.documentElement.style.setProperty('--shadows' + i, Shadows(100));
        }
    </script>

    <script>
        $(document).on('submit', 'form', function() {
            if (confirm('Are You sure!')) {
                yourformelement.submit();
            } else {
                return false;
            }
        })

        function submitFunction() {
            document.getElementById('subbtn').style.display = 'none';
        }

        $(document).on('blur', '#sponser_id', function() {
            check_sponser();
        })

        function check_sponser() {
            var user_id = $('#sponser_id').val();
            if (user_id != '') {
                var url = '<?php echo base_url("Dashboard/UserInfo/get_user/") ?>' + user_id;
                $.get(url, function(res) {
                    $('#sponser_name').html(res);
                })
            }
        }

        check_sponser();
    </script>

    <script>
        async function submit_form(evt, id) {

            var url = document.getElementById(id).action;
            var element = document.getElementById(id);
            fetch(url, {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: new FormData(element),
                })
                .then(response => response.json())
                .then(result => {
                    toastr.options.newestOnTop = true;
                    toastr.options.progressBar = true;
                    toastr.options.closeButton = true;
                    toastr.options.preventDuplicates = true;
                    var csrf_length = document.getElementsByName("csrf_test_name").length;
                    for (let i = 0; i < csrf_length; i++) {
                        document.getElementsByName("csrf_test_name")[i].value = result.token;
                    }

                    if (result.status == '1') {
                        toastr.success(result.message);
                        window.location.href = result.url;
                        //location.reload();
                    } else if (result.status == '2') {
                        toastr.info(result.message)
                    } else {
                        toastr.error(result.message)
                    };
                });
        }
    </script>
</body>

</html>