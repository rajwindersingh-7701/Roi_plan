<?php $guard = $this->session->userdata['guard'];

if (empty($guard)) {
    redirect('Admin/Management/logout');
}
if (http == 0) {
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirect);
        exit();
    }
}


$subAccess = $this->Main_model->get_single_record('tbl_admin', ['role' => $this->session->userdata['role'], 'user_id' => $this->session->userdata['admin_id']], '*');
$accesshead = json_decode($subAccess['access'], true);
// pr($accesshead);
// die;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="icon" href="<?php echo logo; ?>">
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/adminform.css" rel="stylesheet">
    <!-- <link href="<?php echo base_url('NewDashboard/') ?>assets/css/style.css" rel="stylesheet"> -->


    <!-- Bootstrap Css -->
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url('NewDashboard/') ?>assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

</head>

<body data-sidebar="dark">
    <!-- Begin page -->
    <div id="layout-wrapper" class="admin__header">
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex align-items-center header__logo_sec">
                    <!-- LOGO -->
                    <div class="Navbar__new">
                        <a href="<?php echo base_url('admin/dashboard'); ?>" class="logo logo-light d-block Logo_newLInk">
                            <div class="logo__sec">
                                <img class="sidebar__logo" src="<?php echo logo; ?>" alt="<?php echo title; ?> Logo" width="100%">
                                <img src="<?php echo logo; ?>" alt="<?php echo title; ?> Logo" width="100%" class="d-none">

                            </div>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                        <i class="mdi mdi-menu"></i>
                    </button>

                </div>
            </div>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title"></li>

                        <li>
                            <a href="<?php echo base_url('admin/dashboard'); ?>" class="waves-effect">
                                <i class="ti-dashboard"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li>
                        <li style="display:<?php echo (in_array("userdetail/access", $accesshead)) ? 'block' : 'none'; ?>">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ti-package"></i>
                                <span> User Details</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li style="display:<?php echo (in_array('admin/users', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/users'); ?>">All Members</a></li>
                                <li style="display:<?php echo (in_array('admin/paid-users', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/paid-users'); ?>">Paid Members</a></li>
                                <li style="display:<?php echo (in_array('admin/users', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/todayJoined'); ?>">Today Joined Members</a></li>
                                <li style="display:<?php echo (in_array('admin/paid-users', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/todaypaid-users'); ?>">Today Paid Members</a></li>
                                <li style="display:<?php echo (in_array('admin/paid-users', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/availableIncome'); ?>">Available Income Balance</a></li>
                            </ul>
                        </li>

                        <li style="display:<?php echo (in_array("settings/access", $accesshead)) ? 'block' : 'none'; ?>">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ti-settings"></i>
                                <span> Settings</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <!-- <li style="display:<?php echo (in_array('admin/buy-price', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/buy-price'); ?>">Buy Price</a></li>
                                <li style="display:<?php echo (in_array('admin/sell-value', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/sell-value'); ?>">Sell Price</a></li> -->
                                <li style="display:<?php echo (in_array('admin/news', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/reset-password'); ?>">Password Reset</a></li>
                                <li style="display:<?php echo (in_array('admin/news', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/news'); ?>">News</a></li>
                                <li style="display:<?php echo (in_array('admin/popup', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/popup'); ?>">User Popup Image</a></li>
                                <li style="display:<?php echo (in_array('admin/popup', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/qrcode'); ?>">QR Code Change</a></li>
                                <li style="display:<?php echo (in_array('admin/popup', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/links'); ?>">Change Links</a></li>
                                <!-- <li style="display:<?php echo (in_array('admin/popup', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/withdraw_popup'); ?>">Withdraw Popup Image</a></li> -->
                            </ul>
                        </li>
                        <?php if (sub_admin == 0) { ?>
                            <li style="display:<?php echo (in_array("admin/permissions", $accesshead)) ? 'block' : 'none'; ?>">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ti-id-badge"></i>
                                    <span>Administrators</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li style="display:<?php echo (in_array('admin/permissions', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/permissions'); ?>">Manage</a></li>
                                </ul>
                            </li>
                        <?php } ?>

                        <li style="display:<?php echo (in_array("incomes/access", $accesshead)) ? 'block' : 'none'; ?>">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ti-package"></i>
                                <span> Income Reports</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <?php
                                $IncForeach = $this->config->item('incomes');
                                foreach ($IncForeach as $Inckey => $IncName) { ?>
                                    <li><a href="<?php echo base_url('admin/incomes/' . $Inckey); ?>"><?php echo $IncName; ?></a></li>
                                <?php } ?>
                                <li style="display:<?php echo (in_array('admin/income-ledgar', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/sendIncome'); ?>">Income Credit/Debit</a></li>
                                <li style="display:<?php echo (in_array('admin/income-ledgar', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/income-ledgar'); ?>">Income Ledgar</a></li>
                                <li style="display:<?php echo (in_array('admin/income-ledgar', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/adminincome-ledgar'); ?>">Admin Income Ledgar</a></li>
                                <li style="display:<?php echo (in_array('admin/payout-summary', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/payout-summary'); ?>">Payout Summary</a></li>
                            </ul>
                        </li>


                        <li style="display:<?php echo (in_array("kyc/access", $accesshead)) ? 'block' : 'none'; ?>">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ti-id-badge"></i>
                                <span> Kyc Details</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li style="display:<?php echo (in_array('admin/kyc-history/allrequest', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/kyc-history/allrequest'); ?>">Kyc Request</a></li>
                                <li style="display:<?php echo (in_array('admin/kyc-history/pending', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/kyc-history/pending'); ?>">Pending Request</a></li>
                                <li style="display:<?php echo (in_array('admin/kyc-history/approved', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/kyc-history/approved'); ?>">Approved Request</a></li>
                                <li style="display:<?php echo (in_array('admin/kyc-history/rejected', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/kyc-history/rejected'); ?>">Rejected Request</a></li>
                            </ul>
                        </li>

                        <li style="display:<?php echo (in_array("withdraw/access", $accesshead)) ? 'block' : 'none'; ?>">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ti-package"></i>
                                <span>Withdraw Management</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li style="display:<?php echo (in_array('admin/withdraw-history/allrequest', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/withdraw-history/allrequest'); ?>"> Withdraw Request</a></li>
                                <li style="display:<?php echo (in_array('admin/withdraw-history/pending', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/withdraw-history/pending'); ?>"> Pending Withdraw Request</a></li>
                                <li style="display:<?php echo (in_array('admin/withdraw-history/approved', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/withdraw-history/approved'); ?>">ApprovedWithdraw Request</a></li>
                                <li style="display:<?php echo (in_array('admin/withdraw-history/rejected', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/withdraw-history/rejected'); ?>">Rejected Withdraw Request</a></li>
                            </ul>
                        </li>

                        <li style="display:<?php echo (in_array("fund/access", $accesshead)) ? 'block' : 'none'; ?>">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ti-package"></i>
                                <span>Fund Management</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li style="display:<?php echo (in_array('admin/fund-requests/pending', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/fund-requests/pending'); ?>"> Pending Fund Requests</a></li>
                                <li style="display:<?php echo (in_array('admin/fund-requests/approved', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/fund-requests/approved'); ?>">Approved Fund Requests</a></li>
                                <li style="display:<?php echo (in_array('admin/fund-requests/rejected', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/fund-requests/rejected'); ?>">Rejected Fund Requests</a></li>
                                <!-- <li style="display:<?php echo (in_array('admin/send-coin', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/send-coin'); ?>">Send Coin</a></li> -->
                                <!-- <li style="display:<?php echo (in_array('admin/fund-history', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/fund-history'); ?>">Fund History</a></li> -->
                                <li style="display:<?php echo (in_array('admin/send-wallet', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/send-wallet'); ?>">Credit Fund</a></li>
                                <li style="display:<?php echo (in_array('admin/fund-history', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/adminfund-history'); ?>">Admin Fund History</a></li>
                            </ul>
                        </li>

                        <li style="display:<?php echo (in_array("mail/access", $accesshead)) ? 'block' : 'none'; ?>">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ti-email"></i>
                                <span>Mail</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li style="display:<?php echo (in_array('admin/inbox', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/inbox'); ?>">Inbox</a></li>
                                <li style="display:<?php echo (in_array('admin/compose', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/compose-mail'); ?>">Compose Mail</a></li>
                                <li style="display:<?php echo (in_array('admin/outbox', $accesshead)) ? 'block' : 'none'; ?>"><a href="<?php echo base_url('admin/outbox'); ?>">Outbox</a></li>
                            </ul>
                        </li>

                        <li style="display:<?php echo (in_array("admin/logout", $accesshead)) ? 'block' : 'none'; ?>">
                            <a href="<?php echo base_url('admin/logout'); ?>">
                                <i class="ti-share-alt"></i>
                                <span>Logout</span>
                            </a>

                        </li>

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->