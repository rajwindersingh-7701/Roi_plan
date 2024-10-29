<?php
if (http == 0) {
  if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
  }
}

$user_info = userinfo();
$bankinfo = bankinfo();
$mynews = mynews();
$none = 0;
?>
<html>

<head>
  <!-- meta LInks -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />

  <!-- Website Title -->
  <title> <?php echo title; ?> </title>
  <meta name="application-name" content="<?php echo title; ?> ">
  <meta name="author" content="<?php echo title; ?>">
  <meta name="keywords" content="<?php echo title; ?>">
  <link rel="shortcut icon" href="<?php echo base_url('uploads/logo.png'); ?>" type="image/x-icon">
  <meta name="description" content="<?php echo description; ?>">


  <!-- OG meta data -->
  <meta property="og:title" content="<?php echo title; ?>">
  <meta property="og:site_name" content=<?php echo title; ?>>
  <meta property="og:url" content="<?php echo base_url(); ?>">
  <meta property="og:description" content="<?php echo description; ?>">
  <meta property="og:type" content="website">
  <meta property="og:image" content="<?php echo og_image; ?>">




  <!-- Stylesheets Start -->
  <link id="style" href=" <?php echo base_url('Ldashboard/'); ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href=" <?php echo base_url('Ldashboard/'); ?>css/style.css" rel="stylesheet" />
  <link href=" <?php echo base_url('Ldashboard/'); ?>css/dark-style.css" rel="stylesheet" />
  <link href=" <?php echo base_url('Ldashboard/'); ?>css/transparent-style.css" rel="stylesheet" />
  <link href=" <?php echo base_url('Ldashboard/'); ?>css/skin-modes.css" rel="stylesheet" />
  <link href=" <?php echo base_url('Ldashboard/'); ?>css/icons.css" rel="stylesheet" />
  <link href=" <?php echo base_url('Ldashboard/'); ?>css/myteam.css" rel="stylesheet" />
  <link href=" <?php echo base_url('Ldashboard/'); ?>css/myform.css" rel="stylesheet" />

  <link id="theme" rel="stylesheet" type="text/css" media="all" href=" <?php echo base_url('Ldashboard/'); ?>colors/color1.css" />
  <link href=" <?php echo base_url('Ldashboard/'); ?>switcher/css/switcher.css" rel="stylesheet" />
  <link href=" <?php echo base_url('Ldashboard/'); ?>switcher/demo.css" rel="stylesheet" />
  <link href=" <?php echo base_url('Ldashboard/'); ?>css/Newstyle.css" rel="stylesheet" />
  <!-- EXTRA LINKS -->

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
</head>

<body class="app sidebar-mini ltr dark-mode">
  <div class="page">
    <div class="page-main is-expanded">
      <!-- app-Header -->
      <div class="app-header header py-2" style="margin-bottom: -74px">
        <div class="container-fluid main-container">
          <div class="d-flex align-items-center justify-content-between">
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
            <!-- sidebar-toggle-->
            <a class="logo-horizontal" href="<?php echo base_url('dashboard/'); ?>">
              <img src="<?php echo logo; ?>" class="header-brand-img desktop-logo" alt="logodc" style="max-width:90%;" />
              <img src="<?php echo logo; ?>" class="header-brand-img light-logo1" alt="logo" />
            </a>
            <!-- LOGO -->
            <div class="d-flex order-lg-2  header-right-icons">
              <!-- SEARCH -->
              <div class="dropdown d-flex profile-1">
                <span class="text-dark"><?php echo $user_info->user_id; ?></span>

                <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                  <?php if (!empty($bankinfo->profile_image)) : ?>
                    <img alt="" src="<?php echo base_url('uploads/' . $bankinfo->profile_image); ?>" class="avatar profile-user brround cover-image">
                  <?php else : ?>
                    <img alt="" src="<?php echo base_url('all_images/users_icon.png'); ?>" width="20" class="avatar profile-user brround cover-image">
                  <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <div class="drop-heading d-flex justify-content-start align-items-center">
                    <div class="">
                      <img alt="" src="<?php echo base_url('all_images/users_icon.png'); ?>" width="20" class="avatar profile-user brround cover-image">
                    </div>
                    <div class="ms-3">
                      <h5 class="text-white mb-0 fs-14 fw-semibold">
                        <?php echo $user_info->name; ?>
                      </h5>
                      <small class="text-white"> <?php echo $user_info->user_id; ?></small>
                    </div>
                  </div>
                  <div class="dropdown-divider m-0"></div>
                  <a class="dropdown-item" href="<?php echo base_url('dashboard/profile'); ?>">
                    <i class="dropdown-icon fe fe-user"></i> Profile
                  </a>
                  <a class="dropdown-item" href="<?php echo base_url('logout'); ?>">
                    <i class="dropdown-icon fe fe-alert-circle"></i>
                    Logout
                  </a>
                </div>
              </div>

              <div class="navbar navbar-collapse responsive-navbar p-0">
                <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                  <div class="d-flex order-lg-2">
                    <div class="dropdown d-lg-none d-flex">
                      <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
                        <i class="fe fe-search"></i>
                      </a>
                      <div class="dropdown-menu header-search dropdown-menu-start">
                        <div class="input-group w-100 p-2">
                          <input type="text" class="form-control" placeholder="Search...." />
                          <div class="input-group-text btn btn-primary">
                            <i class="fa fa-search" aria-hidden="true"></i>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- MESSAGE-BOX -->
                    <!-- SIDE-MENU -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /app-Header -->
      <!--APP-SIDEBAR-->
      <div class="sticky is-expanded" style="margin-bottom: -74px">
        <div class="app-sidebar__overlay active" data-bs-toggle="sidebar"></div>
        <div class="app-sidebar ps ps--active-y open">
          <div class="side-header">
            <a class="header-brand1 d-block" href="<?php echo base_url('dashboard/'); ?>">
              <img src="<?php echo logo; ?>" class="header-brand-img desktop-logo" style="max-width:65%;" alt="logo" />

            </a>
          </div>

          <div class="main-sidemenu is-expanded">
            <div class="slide-left disabled active is-expanded" id="slide-left">
              <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
              </svg>
            </div>
            <ul class="side-menu open" style="margin-right: 0px; margin-left: 0px" id="sideBar__ul">
              <!--    <li class="sub-category bg-danger"><h3>Main</h3></li> -->
              <li class="slide is-expanded">
                <a class="side-menu__item has-link active" data-bs-toggle="slide" href="<?php echo base_url('dashboard/'); ?>"><i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">Dashboard</span></a>
              </li>
              <!-- <li class="sub-category"><h3>UI Kit</h3></li> -->
              <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-slack"></i><span class="side-menu__label">Profile</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="slide-menu">
                  <li><a href="<?php echo base_url('dashboard/profile'); ?>" class="slide-item"> Edit Profile</a></li>
                  <li><a href="<?php echo base_url('dashboard/account-details'); ?>" class="slide-item"> Bank Account Details</a></li>
                  <li><a href="<?php echo base_url('dashboard/reset-password'); ?>" class="slide-item"> Change Password</a></li>
                </ul>
              </li>

              <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-slack"></i><span class="side-menu__label">Monthly Installment</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="slide-menu">
                  <li><a href="<?php echo base_url('dashboard/select_installment_small'); ?>" class="slide-item">Select Plan</a></li>
                  <li><a href="<?php echo base_url('dashboard/emi_entry'); ?>" class="slide-item">EMI Entry</a></li>
                  <li><a href="<?php echo base_url('dashboard/emi_history'); ?>" class="slide-item">EMI History</a></li>
                </ul>
              </li>
               <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-slack"></i><span class="side-menu__label">One Time Investment</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="slide-menu">
                  <li><a href="<?php echo base_url('dashboard/select_installment_big'); ?>" class="slide-item">Select Plan</a></li>
                  <li><a href="<?php echo base_url('dashboard/emi_entry_big'); ?>" class="slide-item">EMI Entry</a></li>
                  <li><a href="<?php echo base_url('dashboard/emi_history_big'); ?>" class="slide-item">EMI History</a></li>
                </ul>
              </li>


              <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-package"></i><span class="side-menu__label">Activate Account</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="slide-menu mega-slide-menu">
                  <li><a href="<?php echo base_url('dashboard/activate-account'); ?>" class="slide-item">Activation</a></li>
                  <?php if ($user_info->paid_status == 1) { ?>
                    <li><a href="<?php echo base_url('dashboard/updgrade-account'); ?>" class="slide-item">Upgrade Account </a></li>
                  <?php } ?>
                  <li><a href="<?php echo base_url('dashboard/activate-history'); ?>" class="slide-item"> Activation History</a></li>
                </ul>
              </li>
              <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-layers"></i><span class="side-menu__label"> <?php echo (fund_process == 0 ? 'Request Fund' : 'Deposit Fund') ?></span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="slide-menu">
                  <li><a href="<?php echo base_url('dashboard/fund-request'); ?>" class="slide-item"> <?php echo (fund_process == 0 ? 'Request Fund' : 'Deposit Fund') ?></a></li>
                  <li><a href="<?php echo base_url('dashboard/fundrequest-history'); ?>" class="slide-item"> Fund Request History</a></li>
                  <!-- <li><a href="<?php //echo base_url('dashboard/income_wallet-transfer'); ?>" class="slide-item"> Income To E-Wallet Transfer</a></li>
                  <li><a href="<?php //echo base_url('dashboard/fund-history'); ?>" class="slide-item"> Income to E-Wallet Transfer History</a></li>
                  <li><a href="<?php //echo base_url('dashboard/wallet-transfer'); ?>" class="slide-item"> E-Wallet To E-Wallet Transfer</a></li> -->
                </ul>
              </li>
              <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-shopping-bag"></i><span class="side-menu__label">Income Reports</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="slide-menu">
                  <?php
                  $incomes = incomes();
                  foreach ($incomes as $key => $inc) :
                  ?>
                    <li><a href="<?php echo base_url('dashboard/incomes/' . $key); ?>" class="slide-item"><?php echo $inc; ?></a></li>
                  <?php
                  endforeach;
                  ?>
                  <li><a href="<?php echo base_url('dashboard/income-ledger'); ?>" class="slide-item">Income Ledger</a></li>
                  <li><a href="<?php echo base_url('dashboard/payout-summary'); ?>" class="slide-item">Payout Summary</a></li>
                </ul>
              </li>
              <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-folder"></i><span class="side-menu__label">My Team</span>
                  <i class="angle fe fe-chevron-right"></i>
                </a>
                <ul class="slide-menu">
                  <li><a href="<?php echo base_url('dashboard/directs/' . $user_info->user_id); ?>" class="slide-item">My Directs</a></li>
                  <li><a href="<?php echo base_url('dashboard/my-team'); ?>" class="slide-item">Total Team</a></li>
                  <li><a href="<?php echo base_url('dashboard/level-report'); ?>" class="slide-item">Level Team</a></li>
                  <li><a href="<?php echo base_url('Dashboard/UserInfo/Tree/' . $user_info->user_id); ?>" class="slide-item">Team Tree</a></li>
                  <!-- <li><a href="<?php //echo base_url('dashboard/myDownline/L'); ?>" class="slide-item">Left Downline Team</a></li>
                  <li><a href="<?php //echo base_url('dashboard/myDownline/R'); ?>" class="slide-item">Right Downline Team</a></li> -->
                  <!-- <li><a href="<?php //echo base_url('dashboard/genelogy-tree/' . $user_info->user_id); ?>" class="slide-item">Genelogy Tree</a></li> -->
                </ul>
              </li>
              <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">Withdraw</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="slide-menu">
                  <li><a href="<?php echo base_url('dashboard/directIncomeWithdraw') ?>" class="slide-item">Withdrawal</a></li>
                  <li><a href="<?php echo base_url('dashboard/withdraw-history') ?>" class="slide-item">Withdrawal History</a></li>
                </ul>
              </li>
              <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-map-pin"></i><span class="side-menu__label">Support</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="slide-menu">
                  <li><a href="<?php echo base_url('dashboard/compose-mail'); ?>" class="slide-item"> Create Ticket</a></li>
                  <li><a href="<?php echo base_url('dashboard/inbox-mail'); ?>" class="slide-item"> Inbox</a></li>
                  <li><a href="<?php echo base_url('dashboard/outbox-mail'); ?>" class="slide-item"> Outbox</a></li>
                </ul>
              </li>
              <li class="slide">
                <a class="side-menu__item" href="<?php echo base_url('logout'); ?>"><i class="side-menu__icon fe fe-zap"></i><span class="side-menu__label">Logout</span></a>
              </li>
            </ul>
            <div class="slide-right" id="slide-right">
              <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
              </svg>
            </div>
          </div>
          <div class="ps__rail-x" style="left: 0px; bottom: 0px">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px"></div>
          </div>
          <div class="ps__rail-y" style="top: 0px; height: 702px; right: 0px">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 561px"></div>
          </div>
        </div>
        <!--/APP-SIDEBAR-->
      </div>