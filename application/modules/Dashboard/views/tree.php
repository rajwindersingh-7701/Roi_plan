<?php include 'header.php' ?>
<style>
    .treemain li::before,
    .treemain li::after {
        content: '';
        position: absolute;
        top: -14px !important;
        right: 50%;
        border-top: 2px solid #000;
        width: 50%;
        height: 20px;
        z-index: 999999;
    }

    .treemain ul ul::before {
        content: '';
        position: absolute;
        top: -12px !important;
        left: 50%;
        border-left: 2px solid #000;
        width: 0;
        height: 20px;
    }

    .treemain ul {
        padding-top: 20px;
        position: relative;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    .treemain li {
        float: left;
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 0px 0px 0 5px;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    .treemain li::before,
    .treemain li::after {
        content: '';
        position: absolute;
        top: 0;
        right: 50%;
        border-top: 2px solid #fff;
        width: 50%;
        height: 20px;
    }

    .treemain li::after {
        right: auto;
        left: 50%;
        border-left: 2px solid #000;
    }

    .treemain li:only-child::after,
    .treemain li:only-child::before {
        display: none;
    }

    .treemain li:only-child {
        padding-top: 0;
    }

    .treemain li:first-child::before,
    .treemain li:last-child::after {
        border: 0 none;
    }

    .Tree_box {
        overflow: scroll;
    }

    @media (min-width: 320px) and (max-width: 1200px) {
        .treemain {
            margin: 0px auto;
            display: table;
            width: 1000px !important;
            overflow: scroll;
            margin: 0px auto;
            width: 100000px !important;
        }
    }

    .treemain li:last-child::before {
        border-right: 2px solid #000;
        border-radius: 0 5px 0 0;
        -webkit-border-radius: 0 5px 0 0;
        -moz-border-radius: 0 5px 0 0;
        z-index: -0;
    }

    .treemain li:first-child::after {
        border-radius: 5px 0 0 0;
        -webkit-border-radius: 5px 0 0 0;
        -moz-border-radius: 5px 0 0 0;
        z-index: -0;
    }

    .treemain ul ul::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        border-left: 2px solid #000;
        width: 0;
        height: 20px;
    }

    /*.tree li div:hover, .tree li div:hover+ul li div {
    background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
    }*/
    .treemain li div:hover+ul li::after,
    .treemain li div:hover+ul li::before,
    .treemain li div:hover+ul::before,
    .treemain li div:hover+ul ul::before {
        border-color: #94a0b4;
    }

    .user-block {
        position: relative;
    }

    /*Popup Styling Starts*/
    .mx_pop_content.pop-up-content {
        display: none;
    }

    .user-block:hover .pop-up-content {
        display: block;
    }

    .pop-up-content {
        padding: 8px !important;
    }

    .mx_pop_content {
        position: absolute !important;
        top: -65px !important;
    }

    .pop-up-content {
        width: 280px;
        height: auto;
        display: block;
        background: #e7e7e7;
        border: solid 1px #c2c0c0;
        border-radius: 10px;
        z-index: 3;
        top: -100px;
        left: 86px;
        text-align: left;
        padding: 13px;
        position: relative;
    }

    .pop-up-content {
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 2px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        background: #fff;
    }

    .profile_tooltip_pick {
        display: block;
        text-align: center;
        vertical-align: top;
        height: 30px;
        line-height: 30px;
        border-bottom: solid 1px #ccc;
        padding-bottom: 40px;
    }

    .profile_tooltip_pick {
        display: inline-block;
        vertical-align: top;
    }

    .image_tooltip {
        margin: 0 auto;
        line-height: 30px;
        box-shadow: none;
        width: 30px;
        height: 30px;
        display: inline-block;
    }

    .image_tooltip {
        width: 80px;
        height: 80px;
        background: #333333;
        border-radius: 50%;
        box-shadow: 4px 4px 3px #aaa;
    }

    a:link {
        cursor: pointer;
        -webkit-transition: 3s;
        -moz-transition: 3s;
        -o-transition: 3s;
        -ms-transition: 3s;
        transition: 3s;
    }

    .image_tooltip img {
        width: 100%;
        height: auto;
    }

    .profile-rounded-image-small {
        border-radius: 50px 50px 50px 50px;
    }

    .profile_tooltip_pick h2 {
        display: inline-block;
        padding: 0;
        vertical-align: middle;
        border: none;
    }

    .pop-up-content h2 {
        font-size: 17px;
        padding: 10px 0;
        border-bottom: solid 1px #ccc;
    }

    .tooltip_profile_detaile {
        border-bottom: solid 1px #ccc;
        padding-bottom: 5px;
    }

    .tooltip_profile_detaile {
        display: block;
        padding-left: 0;
        text-align: center;
        vertical-align: top;
    }

    .tooltip_profile_detaile {
        display: inline-block;
        vertical-align: top;
        padding-left: 10px;
    }

    .binary-node-single-item .tooltip_profile_detaile dl {
        line-height: 30px;
        margin: 0;
    }

    .tooltip_profile_detaile dl dt {
        color: #333333;
        position: relative;
        padding-right: 10px;
    }

    .tooltip_profile_detaile dl dt,
    .tooltip_profile_detaile dl dd {
        display: inline-block;
        vertical-align: middle;
        font-family: "latoregular";
        font-size: 14px;
        color: #727272;
    }

    .tooltip_profile_detaile dl dt:after {
        content: ':';
        position: absolute;
        top: 0;
        right: 0;
        display: block;
    }

    .tooltip_profile_detaile dl dt,
    .tooltip_profile_detaile dl dd {
        display: inline-block;
        vertical-align: middle;
        font-family: "latoregular";
        font-size: 14px;
        color: #727272;
    }

    .pop-up-content .rank_area {
        text-align: center;
    }

    .pop-up-content .rank_area .created-dl {
        display: inline-block;
        padding: 0;
        text-align: right;
        line-height: normal;
        margin-bottom: 0;
        line-height: 0;
    }

    .pop-up-content .rank_area .created-dl dt {
        display: inline-block;
        vertical-align: middle;
    }

    .pop-up-content .rank_area .created-dl dd {
        display: inline-block;
        vertical-align: middle;
    }

    .pop-up-content:after {
        background: url() no-repeat scroll 0 0 transparent !important;
    }

    .member-img {
        cursor: pointer;
        display: inline-block;
        position: relative;
    }

    .member-img {
        position: relative;
        padding: 5px 10px;
        text-decoration: none;
        color: #666;
        font-family: arial, verdana, tahoma;
        font-size: 11px;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    .member-img img {
        box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
        border-radius: 50%;
    }

    .treemain span {
        display: block;
        color: #000;
        position: relative;
        top: 5px;
        padding: 2px 0;
        font-size: 18px;
        letter-spacing: 0.4px;
    }

    .hover-box {
        width: 218px;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 2px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        background: #fff;
        position: absolute;
        height: 222px;
        display: none;
        z-index: 9999;
        left: 106px;
        top: -41px;
        padding: 10px 10px;
    }

    .member-img:hover .hover-box {
        display: block;
    }

    .hover-box {
        text-align: left;
    }

    .ttreemainree {
        margin: 0px auto;
        display: table;
        /*        width:800px;*/
    }

    .treemain ul:last-child::before {
        display: none;
    }

    /*div#content {
        background: white;
    }*/
    .treemain {
        overflow: scroll;
        margin: 0px auto;
        width: 100000px !important;
        min-height: calc(100vh - 250px);
    }

    .Tree_box {
        overflow: scroll;
        width: 100%;
        display: -webkit-box;
    }

    .title {
        padding: 20px;
        border-bottom: 1px solid #dee2e6;
    }

    #global-loader {
        display: none !important;
    }

    .member-img img {
        max-width: 80px
    }

    .member-img .on-hover {
        position: absolute;
        background: #fff;
        border: 2px solid #efefef;
        width: 220px;
        font-size: 14px;
        text-align: left;
        padding: 10px;
        color: #000;
        visibility: hidden;
        opacity: 0;
        transition: all 0.3s linear 0s;
        top: 100%;
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.12);
        z-index: 111;
    }

    .member-img:hover .on-hover {
        visibility: visible;
        opacity: 1;
    }

    .wizard {
        overflow: scroll;
    }

    .member-img img {
        max-width: 40px;
    }


    .main-content .container,
    .main-content .container-fluid {
        padding-left: 0px;
        padding-right: 0px;
    }

    /*Popup Styling Ends*/
</style>


<div class="main-content app-content mt-0">
    <div class="container-fluid">
        <div class="col-12">
            <div class="panel-heading">
                <h4 class="panel-title">Direct Referral Tree</h4>
            </div>
            <div class="card">
                <div class="card-body">
                    <!-- BEGIN page-header -->


                    <div id="content" class="content">

                        <h2 class="page-titel">
                            <spna style=""></span>
                        </h2>
                        <div id="rootwizard" class="wizard wizard-full-width">
                            <div class="treemain">
                                <ul class="clearfix">
                                    <li>
                                        <div class="member-img">
                                            <a href="<?php echo base_url('dashboard/tree/' . $user['user_id']); ?>">
                                                <img src="<?php echo base_url('all_images/userIcon') ?><?php echo $user['paid_status'] == 1 ? 'active' : 'inactive'; ?>.png">
                                            </a>
                                            <br><span><?php echo ($user['name']); ?>(<?php echo ($user['user_id']); ?>)</span>
                                            <span><?php echo ($user['user_id']); ?></span>
                                            <span class="on-hover">
                                                User ID : <?php echo ($user['user_id']); ?> <br>
                                                User Name : <?php echo ($user['name']); ?> <br>
                                                Package Amount : <?php echo $user['package_amount']; ?> <br>
                                                Total Business : <?php echo getBusiness($user['user_id']); ?> <br>

                                            </span>
                                        </div>
                                        <ul class="clearfix">
                                            <?php
                                            foreach ($users as $key => $direct) {
                                                //pr($direct);
                                            ?>
                                                <li>
                                                    <div class="member-img">
                                                        <a href="<?php echo base_url('dashboard/tree/' . $direct['user_id']); ?>">
                                                            <img src="<?php echo base_url('all_images/userIcon') ?><?php echo $direct['paid_status'] == 1 ? 'active' : 'inactive'; ?>.png">
                                                        </a>
                                                        <br><span><?php echo ($direct['name']); ?></span>
                                                        <span><?php echo $direct['user_id']; ?></span>
                                                        <span class="on-hover">
                                                            User ID : <?php echo $direct['user_id']; ?> <br>
                                                            User Name : <?php echo $direct['name']; ?> <br>
                                                            Package Amount : <?php echo $direct['package_amount']; ?> <br>
                                                            Total Business : <?php echo getBusiness($direct['user_id']); ?> <br>

                                                        </span>
                                                    </div>
                                                    <ul class="clearfix">
                                                        <?php
                                                        foreach ($direct['sub_directs'] as $k => $sub_direct) {
                                                            //    pr($sub_direct);
                                                        ?>
                                                            <li>
                                                                <div class="member-img">
                                                                    <a href="<?php echo base_url('dashboard/tree/' . $sub_direct['user_id']); ?>">
                                                                        <img src="<?php echo base_url('all_images/userIcon') ?><?php echo $sub_direct['paid_status'] == 1 ? 'active' : 'inactive'; ?>.png">
                                                                    </a>
                                                                    <br><span><?php echo ($sub_direct['name']); ?></span>
                                                                    <br><span><?php echo ($sub_direct['user_id']); ?></span>
                                                                    <span class="on-hover">
                                                                        User ID : <?php echo $sub_direct['user_id']; ?> <br>
                                                                        User Name: <?php echo $sub_direct['name']; ?> <br>
                                                                        Package Amount : <?php echo $sub_direct['package_amount']; ?> <br>
                                                                        Total Business : <?php echo getBusiness($sub_direct['user_id']); ?> <br>

                                                                    </span>
                                                                </div>
                                                            </li>
                                                        <?php
                                                        }
                                                        ?>

                                                    </ul>
                                                </li>
                                            <?php
                                            }
                                            ?>

                                        </ul>
                                    </li>
                                </ul>
                                <hr>
                                <!--count_downline_by_level($num, $level)-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php' ?>