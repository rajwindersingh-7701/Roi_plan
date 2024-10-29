<?php include_once 'header.php'; ?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->


<div class="main-content main_content_new">

    <div class="page-content">
        <div class="container-fluid">


            <div class="row m-0 mb-4 mt-1 new__sec ">
                <div class="col-md-6">
                    <div class="sub__header">
                        <h5 class="m-0 text-dark starte__txt">Starter Page</h5>
                        <h5 class="mb-0 text-success"> <?php echo $this->session->flashdata('process'); ?></h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="sub__header">
                        <h5 class="m-0 text-dark">SMS Left: <?php echo smslimit - $totalSms['totalSms']; ?></h5>
                        <a href="" class="btn"> Refresh</a>
                    </div>

                </div>
            </div>

            <!-- end page title -->

            <div class="row card__row">
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="mini-stat-img">
                                <i class="fas fa-money-check-alt"></i>

                            </div>
                            <a href="<?php echo base_url('admin/income-ledgar'); ?>" class="stretched-link">
                                <h5 class="font-size-16 text-uppercase mt-0 mt-2">Total Payout</h5>
                            </a>
                            <h5 class="font-weight-medium mb-0 mt-1">Total : <?php echo currency; ?> <?php echo number_format($total_payout, 2); ?> </h5>
                        </div>
                    </div>
                </div>


                <?php
                $incomes = incomes();
                foreach ($incomes as $incKey => $inc) :
                    $table = "tbl_income_wallet";

                    $getBalance = $this->Main_model->get_single_record($table, ['type' => $incKey], 'ifnull(sum(amount),0) as balance');
                ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class=" mini-stat-img">
                                    <!-- <i class="fas fa-dollar-sign" aria-hidden="true"></i> -->
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <a href="<?php echo base_url('admin/incomes/' . $incKey); ?>" class="stretched-link">
                                    <h5 class="font-size-16 text-uppercase mt-0 mt-2"><?php echo $inc; ?></h5>
                                </a>
                                <h5 class="font-weight-medium mb-0 mt-1">Total : <?php echo currency; ?> <?php echo number_format($getBalance['balance'], 2); ?> </h5>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="mini-stat-img">
                                <i class="fas fa-dollar-sign" aria-hidden="true"></i>
                            </div>
                            <a href="<?php echo base_url('admin/users/'); ?>" class="stretched-link">
                                <h5 class="font-size-16 text-uppercase mt-0 mt-2">Total Members</h5>
                            </a>
                            <h5 class="font-weight-medium mb-0 mt-1">Total : <?php echo $total_users; ?></span></h5>
                        </div>
                    </div>
                </div>




                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="mini-stat-img">
                                <i class="fas fa-dollar-sign" aria-hidden="true"></i>
                            </div>
                            <a href="<?php echo base_url('admin/todaypaid-users'); ?>" class="stretched-link">
                                <h5 class="font-size-16 text-uppercase mt-0 mt-2">Today Active Members</h5>
                            </a>
                            <h5 class="font-weight-medium mb-0 mt-1"><?php echo $today_paid_users; ?></span></h4>
                        </div>
                    </div>
                </div>




                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class=" mini-stat-img">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="<?php echo base_url('admin/paid-users/'); ?>" class="stretched-link">
                                <h5 class="font-size-16 text-uppercase mt-0 mt-2">Paid Members</h5>
                            </a>
                            <h5 class="font-weight-medium mb-0 mt-1">Total : <?php echo $paid_users; ?></h5>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class=" mini-stat-img">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <a href="<?php echo base_url('admin/todayJoined/'); ?>" class="stretched-link">
                                <h5 class="font-size-16 text-uppercase mt-0 mt-2">Today Joined Members</h5>
                            </a>
                            <h5 class="font-weight-medium mb-0 mt-1">Total : <?php echo $today_joined_users; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 d-none">
                    <div class="card">
                        <div class="card-body">
                            <div class=" mini-stat-img">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <a href="<?php echo base_url('Admin/Management/today_joinings/'); ?>" class="stretched-link">
                                <h5 class="font-size-16 text-uppercase mt-0 mt-2"> Members</h5>
                            </a>
                            <h5 class="font-weight-medium mb-0 mt-1">Total : <?php echo $today_joined_users; ?></h5>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="mini-stat-img">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <h3 class="font-size-16 text-uppercase mt-0 mt-2 primary-color"><?php echo currency; ?>-Wallet</h3>

                            <p class="mb-0">Wallet Bal.: <?php echo $total_sent_fund; ?></p>
                            <p class="mb-0">Used : <?php echo $used_fund; ?></p>
                            <p>Requested : <?php echo $requested_fund; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class=" mini-stat-img">
                                <!-- <i class="fas fa-dollar-sign" aria-hidden="true"></i> -->
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3 class="font-size-16 text-uppercase mt-0 mt-2 primary-color">E-Mail</h3>
                            <p class="mb-0">Total : <?php echo $total_mail; ?></p>
                            <p class="mb-0">Read : <?php echo $approved_mail; ?></p>
                            <p>Unread : <?php echo $pending_mail ?></p>

                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class=" mini-stat-img">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="small-box">
                                <div class="inner">
                                    <h3 class="font-size-16 text-uppercase mt-0 mt-2 primary-color">Today Payout</h3>
                                    <p class="mb-0">Today Paid ID : <?php echo $today_paid_users; ?></p>
                                    <p class="mb-0">Today Business : <?php echo $today_business; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>

            <?php include_once 'footer.php';  ?>