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
    <link rel="shortcut icon" href="<?php echo base_url('uploads/logo.png'); ?>">
    <!-- Bootstrap Css -->
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/bootstrap.min.css" id="bootstrap-style"
        rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/register.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/app.min.css" id="app-style" rel="stylesheet"
        type="text/css" />
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.cdnfonts.com/css/koho-2" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">



</head>

<body>


    <div id="space">
        <div class="account-pages login-41">

            <div class="container">
                <div class="row tab-row bg-img justify-content-center">

                    <div class="col-lg-8 inner-form-wrap">

                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="sub_title">
                                    <a href="<?php echo base_url(); ?>">
                                        <img src=" <?php echo logo; ?>" class="header-brand-img desktop-logo"
                                            alt="logo" />
                                    </a>
                                </div>
                                <h6 class="account1">Register</h6>
                                <div class="plants-svg">
                                    <svg version="1.1" class="edgtf-animated-svg edgtf-animated-svg-appeared"
                                        id="edgtf-animated-svg-239" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="70.976px"
                                        height="18.01px" viewBox="0 0 70.976 18.01"
                                        enable-background="new 0 0 70.976 18.01" xml:space="preserve">
                                        <path fill-rule="evenodd" fill="#3d6039" clip-rule="evenodd" d="M0.095,14.197c0.101-0.171,0.398-0.668,0.778-1.221
	c0.471-0.686,1.172-1.622,1.299-1.759c0.127-0.137,0.357-0.262,0.676-0.137c0.318,0.125,0.611,0.424,1.452,0.449
	s1.096,0.162,2.611,0.225c1.516,0.062,3.414,0.148,6.723-0.041c3.308-0.189,6.578-0.265,8.357-0.189
	c1.78,0.076,4.236-0.019,6.152,0.019c1.915,0.038,4.834,0.012,5.088,0.006c0.254-0.006,0.335-0.073,0.484-0.201
	c0.148-0.127,0.315-0.267,0.545-0.407c0.229-0.14,0.402-0.2,0.75-0.243c0.348-0.042,1.013-0.352,1.739-0.528
	c0.727-0.176,1.422-0.344,1.899-0.788c0.477-0.444,0.875-0.757,1.485-1.216c0.609-0.459,1.149-0.83,1.597-1.138
	c0.447-0.308,0.676-0.438,0.799-0.6c0.123-0.161,0.095-0.313,0.042-0.464c-0.053-0.151-0.046-0.131-0.369-0.271
	c-0.323-0.14-0.496-0.264-0.496-0.736c0-0.473-0.26-0.729-0.26-0.729s0.423,0,0.739,0.218c0.316,0.218,0.475,0.578,0.505,0.963
	c0.03,0.385,0.124,0.647,0.124,0.647s0.376-0.313,0.527-0.749c0.15-0.436,0.01-0.674-0.119-1.053s0.077-0.582-0.035-1.222
	c-0.112-0.639,0-1.271,0.49-1.785c0.49-0.513,0.533-0.774,0.533-0.774s0.344,0.724,0.292,1.373
	c-0.051,0.648-0.478,1.029-0.886,1.414c-0.409,0.385-0.033,1.233-0.033,1.233s0.123-0.448,0.373-0.907s0.559-0.797,0.649-0.906
	c0.091-0.11,0.16,0.015,0.054,0.14c-0.107,0.125-0.699,1.111-0.833,1.577c-0.133,0.465-0.205,0.898-0.338,1.167
	c-0.133,0.269-0.279,0.497-0.375,0.562c-0.096,0.065-0.129,0.192,0.191,0.126s0.611-0.188,0.786-0.313
	c0.175-0.126,0.346,0.033,0.159,0.175c-0.187,0.143-0.562,0.371-0.761,0.456s-0.35,0.082-0.437,0.069
	c-0.087-0.012-0.163-0.024-0.246,0.098c-0.083,0.123-0.827,0.794-1.184,1.094c-0.357,0.299-0.978,0.709-1.466,1.222
	c-0.489,0.513-1.065,0.965-1.667,1.273c-0.603,0.308-1.326,0.521-1.902,0.667c-0.577,0.145-0.881,0.367-1.1,0.436
	c-0.218,0.069-0.131,0.214,0.087,0.248s0.917,0.205,2.592,0.205c1.675,0,3.089-0.068,3.666-0.111s0.724-0.051,0.995-0.222
	c0.27-0.17,1.306-0.8,1.701-1.052c0.394-0.252,0.643-0.445,0.909-0.671S45.982,9.415,46.212,9c0.23-0.415,0.497-0.901,0.72-1.134
	c0.223-0.232,0.495-0.405,0.617-0.789c0.122-0.383,0.032-1.03-0.155-1.397s-0.244-0.703,0.058-1.286
	c0.301-0.583,0.767-0.663,0.767-0.663s-0.311,0.583-0.098,1.158c0.212,0.575,0.233,1.455-0.018,1.829s-0.444,0.559-0.522,0.683
	c-0.077,0.124-0.097,0.228,0.121,0.18s0.727-0.183,1.197-0.605c0.47-0.423,0.632-0.838,0.632-1.283c0-0.445-0.032-0.781,0.077-0.962
	c0.11-0.182,0.22-0.315,0.292-0.371c0.072-0.056,0.129,0.015,0.053,0.111c-0.075,0.096-0.159,0.218-0.212,0.326
	c-0.053,0.107-0.076,0.223-0.053,0.408c0.022,0.185,0.011,0.608-0.019,0.79s-0.125,0.43-0.186,0.522
	c-0.061,0.093-0.038,0.182,0.087,0.089c0.125-0.092,0.666-0.479,0.981-1.054c0.315-0.575,0.291-0.703,0.265-1.06
	c-0.025-0.356-0.038-0.513,0.017-0.762c0.055-0.249-0.105-0.676-0.122-1.162c-0.015-0.485,0.094-1.402,0.829-1.978
	S52.586,0,52.586,0s-0.387,0.485-0.241,1.106c0.148,0.622,0.023,1.229-0.372,1.675c-0.395,0.448-0.593,0.695-0.742,0.776
	c-0.148,0.081-0.231,0.248-0.231,0.383s0.033,0.48,0.022,0.599c-0.011,0.119,0.116,0.194,0.204,0.011
	c0.088-0.184,0.167-0.403,0.445-0.722s0.771-0.801,1.044-0.997c0.274-0.195,0.736-0.542,0.844-0.63
	c0.108-0.09,0.16,0.039,0.04,0.156c-0.12,0.117-0.564,0.418-0.803,0.613c-0.24,0.196-0.672,0.681-0.855,0.882
	c-0.183,0.201-0.279,0.358-0.302,0.447c-0.022,0.089,0.086,0.072,0.239,0c0.154-0.072,0.342-0.262,0.587-0.368
	c0.245-0.106,0.377-0.235,0.479-0.34c0.102-0.107,0.513-0.525,0.941-0.737c0.427-0.212,0.649-0.156,1.219,0.1
	c0.57,0.257,1.14-0.022,1.14-0.022s-0.376,0.458-1.003,0.876c-0.627,0.418-1.26,0.675-1.704,0.675c-0.445,0-0.77-0.146-0.946-0.185
	c-0.177-0.039-0.351,0.065-0.529,0.156c-0.179,0.091-0.458,0.327-0.637,0.479c-0.179,0.152-0.272,0.365-0.419,0.608
	s-0.536,0.699-1.04,1.102c-0.505,0.403-1.134,0.768-1.352,0.851c-0.217,0.084-0.155,0.16,0.047,0.206
	c0.201,0.045,0.38,0.095,1.143,0.081c0.763-0.014,1.153-0.132,1.52-0.218c0.368-0.087,0.615-0.06,0.693-0.041
	c0.08,0.018,0.056,0.114-0.047,0.109c-0.102-0.005-0.423,0.018-0.739,0.104c-0.315,0.086-0.548,0.132-0.637,0.136
	s-0.121,0.082-0.004,0.141c0.116,0.06,0.271,0.09,0.933,0.083c0.661-0.007,1.021,0.179,1.513,0.782
	c0.491,0.603,1.165,0.853,1.165,0.853s-0.878,0.231-1.578,0.205c-0.701-0.025-1.323-0.288-1.696-0.609
	c-0.374-0.321-0.57-1.103-0.675-1.23c-0.104-0.128-0.196-0.135-0.295-0.109c-0.098,0.025-0.74,0.058-1.25-0.019
	c-0.511-0.077-0.708-0.122-0.806-0.141c-0.099-0.02-0.197-0.038-0.347,0.09s-0.485,0.602-0.609,1.058
	c-0.125,0.455-0.407,0.984-1.013,1.523c-0.605,0.54-1.827,1.231-2.056,1.367c-0.229,0.134-0.165,0.278,0.037,0.296
	c0.202,0.018,0.735,0.251,2.709,0.251c1.974,0,2.175,0.135,3.819,0.234s3.757-0.059,4.152-0.115c0.394-0.056,0.53-0.14,0.68-0.288
	c0.151-0.147,0.858-0.931,1.309-1.405c0.451-0.473,0.803-0.914,1.1-1.227c0.297-0.312,0.824-0.548,1.417-0.699
	c0.594-0.151,1.198-0.549,1.693-0.786c0.494-0.237,2.341-1.313,2.648-1.528s0.396-0.043,0.088,0.161
	c-0.308,0.205-1.725,1.081-2.321,1.449c-0.595,0.369-1.235,0.847-1.673,1.007c-0.439,0.16-0.847,0.332-1.135,0.614
	c-0.289,0.283-1.204,1.283-1.311,1.387c-0.106,0.104,0.044,0.135,0.194,0.013s0.57-0.387,1.015-0.602
	c0.446-0.215,0.878-0.289,1.022-0.307c0.145-0.019,0.208,0.11-0.024,0.184s-0.696,0.215-1.091,0.417s-1.31,0.835-1.486,0.976
	s-0.082,0.203,0.094,0.178c0.176-0.024,1.127-0.079,1.778-0.148c0.652-0.069,1.09-0.095,1.323-0.102
	c0.232-0.006,0.342-0.082,0.613-0.189c0.271-0.107,0.484-0.265,0.793-0.606c0.31-0.341,0.506-0.544,1.141-0.78
	c0.635-0.237,0.96-0.363,1.255-0.57c0.295-0.207,0.998-0.584,1.316-0.555c0.317,0.03,0.196,0.141,0.03,0.163
	c-0.167,0.022-0.514,0.082-0.907,0.355c-0.394,0.274-0.726,0.652-1.21,0.874c-0.484,0.223-1.345,0.866-1.467,1.029
	c-0.121,0.163-0.007,0.2,0.099,0.244c0.106,0.044,0.521,0.163,1.081,0.111c0.559-0.052,1.089-0.2,1.36-0.622
	c0.272-0.422,0.378-0.777,0.877-1.214c0.5-0.437,0.885-0.444,1.633-0.547s1.051-0.399,1.051-0.399s-0.136,0.488-0.446,0.902
	c-0.31,0.415-0.892,0.992-1.52,1.347c-0.627,0.355-1.27,0.377-1.542,0.393c-0.272,0.015-0.469,0.082-0.727,0.155
	c-0.257,0.074-0.62,0.148-0.703,0.155c-0.083,0.008-0.083,0.112,0.012,0.155c0.094,0.043,0.591,0.271,0.792,0.351
	c0.201,0.08,0.774,0.08,0.938,0.08s0.265-0.055,0.359-0.129c0.094-0.074,0.459-0.45,1.51-0.579c1.051-0.129,1.674,0.449,1.674,0.449
	s-0.542-0.123-1.013,0.025c-0.472,0.148-0.988,0.407-1.511,0.456c-0.521,0.049-0.711-0.049-0.868-0.08
	c-0.158-0.031-0.458-0.027-0.531-0.027c-0.074,0-0.159,0.066-0.055,0.136c0.104,0.069,0.444,0.417,0.642,0.577
	c0.197,0.16,0.456,0.309,0.563,0.341c0.107,0.033,0.355,0.05,0.49,0.05c0.135,0,0.501,0.033,0.794-0.11
	c0.292-0.144,0.996-0.436,2.1-0.38c1.104,0.055,2.439,0.893,2.439,0.893s-0.67,0.027-1.206,0.154
	c-0.535,0.127-1.121,0.463-2.011,0.276c-0.89-0.187-1.261-0.551-1.43-0.623c-0.168-0.071-0.354-0.061-0.541-0.055
	c-0.186,0.005-0.237,0.011-0.276,0.011c-0.04,0-0.141,0.039-0.051,0.088c0.09,0.049,0.336,0.187,0.555,0.425
	c0.218,0.237,0.282,0.572,0.406,0.726c0.125,0.154,0.024,0.208-0.112,0.089c-0.137-0.118-0.236-0.355-0.321-0.534
	c-0.084-0.178-0.397-0.438-0.69-0.536c-0.294-0.098-0.642-0.413-0.93-0.626c-0.288-0.214-0.812-0.505-0.945-0.555
	c-0.132-0.049-0.251,0-0.347,0.094c-0.096,0.094-0.137,0.419,0.132,0.594c0.27,0.174,0.372,0.172,0.535,0.383
	c0.163,0.21,0.403,0.453,0.67,0.676c0.267,0.224,0.632,0.829,0.515,1.32c-0.117,0.491-0.762,0.886-1.075,1.154
	c-0.313,0.268-0.144,0.669-0.144,0.669s-0.332-0.178-0.338-0.708c-0.007-0.529-0.065-0.612-0.248-0.938
	c-0.183-0.325-0.248-0.682,0.032-0.988s0.339-0.612,0.352-0.867c0.013-0.255-0.026-0.376-0.15-0.427
	c-0.123-0.051-0.442-0.204-0.514-0.421s-0.027-0.447,0.05-0.555c0.077-0.107,0.055-0.183-0.027-0.269
	c-0.083-0.087-0.368-0.189-0.643-0.227c-0.274-0.038-0.929-0.113-1.132-0.183s-0.368-0.101-0.652-0.029
	c-0.285,0.071-0.729,0.164-1.442,0.221c-0.714,0.057-1.959,0.05-2.184,0.05c-0.226,0-0.379,0.05-0.452,0.15
	c-0.073,0.1-0.192,0.355-0.414,0.473c-0.221,0.118-0.398,0.173-0.493,0.179c-0.095,0.006-0.297,0.186-0.088,0.26
	c0.208,0.074,0.755,0.333,1.27,0.678s0.681,0.667,0.945,0.825c0.263,0.158,0.567,0.175,0.933,0.152
	c0.364-0.023,1.017-0.021,1.633,0.582c0.617,0.604,1.001,0.694,1.001,0.694s-0.57,0.052-1.054,0.032
	c-0.483-0.02-0.901-0.104-1.305-0.396c-0.404-0.292-0.384-0.311-0.457-0.383c-0.073-0.071-0.192-0.123-0.345-0.123
	c-0.152,0-0.391-0.071-0.597-0.253c-0.206-0.182-0.663-0.589-1.191-0.854c-0.528-0.264-1.471-0.527-1.662-0.67
	c-0.191-0.143-0.264-0.258-0.441-0.23c-0.178,0.028-2.121,0.278-3.177,0.252c-1.056-0.024-3.348-0.1-4.468-0.187
	c-1.12-0.087-3.266,0.017-5.657-0.063c-2.39-0.078-4.271-0.131-7.01-0.026c-2.74,0.105-6.626,0.252-8.625,0.333
	s-6.725,0.163-8.578,0.143c-1.854-0.02-3.227,0.021-5.08,0.082c-1.853,0.062-4.185,0.143-5.434,0.143
	c-1.249,0-3.544,0.181-4.472,0.219c-0.928,0.038-1.366,0.012-1.521-0.051C-0.073,14.571,0.031,14.306,0.095,14.197z">
                                        </path>
                                    </svg>
                                </div>
                                <!-- <p>Fill In The Details Below.</p> -->
                                <div class="">
                                    <div class="panel panel-primary">

                                        <div class="">

                                            <div class="card-body">
                                                <div class="">
                                                    <div class="form-element">
                                                        <?php echo $this->session->flashdata('register_message'); ?>
                                                        <?php echo form_open('register', array('id' => 'registerForm')); ?>
                                                        <div class="form-wrap has-feedback align-items-start mb-0">
                                                            <input type="text" class="form-control" id="sponser_id"
                                                                placeholder="Sponser ID"
                                                                value="<?php echo $sponser_id; ?>" name="sponser_id"
                                                                required />
                                                            <span
                                                                class="text-danger"><?php echo form_error('sponser_id'); ?></span>
                                                            <span id="sponser_name" class="text-danger"> </span>
                                                        </div>
                                                        <div class="form-wrap has-feedback mb-0 mt-3">
                                                            <input type="text" class="form-control" id="name"
                                                                placeholder="Name" name="name" required />
                                                            <span
                                                                class="text-danger"><?php echo form_error('name'); ?></span>
                                                        </div>

                                                        <!--  <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-wrap has-feedback mt-3">
                                                                    <input type="email" class="form-control" placeholder="Email ID" name="email" value="<?php echo set_value('email'); ?>" required>
                                                                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                                                                </div>

                                                            </div> -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-wrap has-feedback mt-3">
                                                                    <input type="number" class="form-control" id="phone"
                                                                        placeholder="Phone" name="phone" required />
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <!-- <div class="col-md-12">
                                                                <div class="form-wrap has-feedback ">
                                                                    <select class="form-control" name='country' id="allcountry">
                                                                        <option value="<?php //echo $conty['name'] 
                                                                                        ?>"><?php //echo $conty['name']; 
                                                                                            ?></option>
                                                                        <?php //foreach ($countries as $key => $cou) { 
                                                                        ?>
                                                                            <option value="<?php //echo $cou['name'] 
                                                                                            ?>"><?php //echo $cou['name'] 
                                                                                                ?></option>
                                                                        <?php  //} 
                                                                        ?>
                                                                    </select>
                                                                    <span class="ion ion-locked form-control-feedback "></span>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-2 pr-0">
                                                                <div class="form-wrap has-feedback ">
                                                                    <input type="text" class="form-control country_code" name="country_code" value="+91" id="countryCode1" readonly>

                                                                </div>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <div class="form-wrap has-feedback">
                                                                    <input type="number" class="form-control" id="phone" placeholder="Phone" name="phone" required />
                                                                </div>

                                                            </div>
                                                            </div> -->
                                                    </div>


                                                    <div class="form-group has-feedback" id="">
                                                        <button type="submit" value="register"
                                                            class="button-three">Register</button>
                                                    </div>
                                                    <div class="text-center create-acc mt-3">
                                                        <p class="m-0">Already Have Account? <a
                                                                href="<?php echo base_url('login'); ?>"
                                                                class="tgreen">Login</a></p>
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
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
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
            shadow += Random(0, maxWidth) + "px " + Random(0, maxHeight) + "px " + "rgb(255," + Random(0, 256) + "," +
                Random(0, 256) + "), ";
        }
        shadow += Random(0, maxWidth) + "px " + Random(0, maxHeight) + "px " + "rgb(255," + Random(0, 256) + "," +
            Random(0, 256) + ")";
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