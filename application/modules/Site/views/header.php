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
<!DOCTYPE html>
<html>

<head>

    <!-- Responsive Meta -->
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Font Family -->
    <link href="<?php echo base_url('Assets/'); ?>css/css" rel="stylesheet">
    <link href="<?php echo base_url('Assets/'); ?>css/css(1)" rel="stylesheet">
    <link href="<?php echo base_url('Assets/'); ?>css/css(2)" rel="stylesheet">


    <!-- Website Title -->
    <title> <?php echo title; ?> </title>
    <meta name="application-name" content="<?php echo title; ?> ">
    <meta name="author" content="<?php echo title; ?>">
    <meta name="keywords" content="<?php echo title; ?>">
    <link rel="shortcut icon" href="<?php echo base_url('uploads/favicon.png'); ?>" type="image/x-icon">
    <meta name="description" content="<?php echo description; ?>">


    <!-- OG meta data -->
    <meta property="og:title" content="<?php echo title; ?>">
    <meta property="og:site_name" content=<?php echo title; ?>>
    <meta property="og:url" content="<?php echo base_url(); ?>">
    <meta property="og:description" content="<?php echo description; ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo og_image; ?>">



    <!-- Stylesheets Start -->
    <link rel="stylesheet" href="<?php echo base_url('Assets/'); ?>css/fontawesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('Assets/'); ?>css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('Assets/'); ?>css/animate.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('Assets/'); ?>css/jquery.fancybox.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('Assets/'); ?>css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('Assets/'); ?>css/slick.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('Assets/'); ?>css/my-style2.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('Assets/'); ?>css/responsive2.css" type="text/css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <!--  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>

<body>

    <!--Main Wrapper Start-->
    <div class="wrapper" id="top">
        <!--Header Start -->
        <header class="">
            <div class="container-fluid">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-md-2 logo">
                            <a href="#" title="<?php echo title;?>">
                                <img src=" <?php echo logo; ?>" class="header-brand-img desktop-logo" alt="logo" />
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-10 main-menu">
                            <div class="menu-icon">
                                <span class="top"></span>
                                <span class="middle"></span>
                                <span class="bottom"></span>
                            </div>
                            <nav class="onepage">
                                <ul>
                                    <div class="for_responsive">

                                        <li class=""><a href="#home">Home</a></li>
                                        <li><a href="#about">About Us</a></li>
                                        <li><a href="#organic">Why Organic</a></li>
                                        <li><a href="<?php echo base_url('uploads/bussiness.pdf'); ?>" target="_blank">Business</a></li>
                                        <li><a href="#blog">Blog</a></li>
                                    </div>
                                    <div class="for-flex">
                                        <li class="nav-btn login"><a href="<?php echo base_url('login'); ?>">Sign In</a></li>
                                        <li class="nav-btn m-0"><a href="<?php echo base_url('register'); ?>">Sign Up</a></li>

                                    </div>
                                </ul>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>
        </header>
        <!--Header End-->