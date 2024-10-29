<?php $this->load->view('header');
$userinfo = userinfo();
?>
<style>
    .flg img {
        width: 23px;
        position: relative;
        top: -1px;
        left: 5px;
    }

    #txt {
        position: relative;
        left: 10px;
        top: 1px;
    }

    .widget-stats-title {
        text-transform: uppercase;
    }

    .vert {
        font-size: 11px !important;
        vertical-align: super;
        margin: 0 0 0 3px;
        font-weight: normal;
    }

    .widget-reminder-divider {
        position: absolute;
        top: 20px;
        bottom: 20px;
        left: 4px;
        width: .125rem;
        background: #bcc0c5;
    }

    .Get-Help-History {
        text-align: center;
        /* background: red; */
        padding: 20px 0px;
    }

    .Get-Help-History span {
        margin: 0px 17px;
        display: block;
        /* background: #0fbf0f;*/
        padding: 5px 6px 5px;
    }

    .Get-Help-History span a {
        font-size: 16px;
        color: #5d58d8;
        font-weight: 600;
        text-decoration: none;
    }

    .Get-Help-History .widget-stats-icon {
        text-align: center;
        font-size: 2.5rem;
        padding: 0;
        border-radius: 50%;
        opacity: .33;
    }

    .close {
        margin-top: -6px;
    }


    .product-review {
        margin: 0px;
        padding: 25px;
    }

    .amount ul {
        padding: 0;
        margin: 0;
    }

    .amount ul li {
        display: initial;
        padding-right: 10px;
    }

    .amount ul li span {
        font-size: 20px;
    }

    /********
    div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
    position: absolute;
    right: 0;
    }*********/
    .form-horizontal .control-label {
        padding-top: .4375rem;
        margin-bottom: 0;
        text-align: left;
        font-weight: normal;
    }

    @media (min-width: 768px) and (max-width:992px) {
        .form-horizontal .control-label {
            padding-top: .4375rem;
            margin-bottom: 0;
            text-align: left;
        }

        .amount ul li span {
            font-size: 18px;
        }

        div.dataTables_wrapper div.dataTables_filter {
            text-align: right;
            position: absolute;
            right: -142px;
        }
    }

    @media (max-width: 767px) {

        .content,
        .page-header-fixed.page-sidebar-fixed .content {
            margin-left: 0;
            padding: .9375rem .9375rem 3.6875rem;
            overflow: initial;
            height: auto;
            margin-top: 16px;
        }

        #condition .control-label {
            text-align: left;
            width: 100%;
            display: inline-block;
        }

        .form-group {
            margin-bottom: 1rem;
            width: 100%;
        }

        .widget-reminder-divider {
            position: absolute;
            top: 0px;
            bottom: 0px;
            left: 7px;
            width: 96%;
            background: #bcc0c5;
            /* border-top: 2px solid gray; */
            height: 2px;
        }
    }

    .btn-success {
        color: #fff !important;
        background-color: #28a745;
        border-color: #28a745;
        /*margin-top: 0px!important; */
        padding: 1.175rem 0.75rem;
        font-size: 15px;
    }
</style>
<style>
    #header {
        height: 72px;
    }

    .side-padding {
        padding: 30px 0 0 0;
    }

    #page-container {
        padding-top: 4.10rem;
    }

    #mobile-logo {
        display: none;
    }

    @media only screen and (max-width: 767px) {

        .navbar-nav-list .nav.navbar-nav>li,
        .navbar-xs-justified .nav.navbar-nav>li {
            width: auto;
        }

        .diamond {
            padding: 12px 5px !important;
        }

        #screen-logo {
            display: none;
        }

        .moview {
            font-size: 15px;
        }

        #mobile-logo {
            display: block;
        }

        .list-inline>li {
            display: inline-block;
            padding: 0 7px 0;
        }

        .breadcrumb {
            margin-top: 35px;
        }

        .navbar-nav-list .nav.navbar-nav>li,
        .navbar-xs-justified .nav.navbar-nav>li {
            width: auto;
        }

        .diamond {
            padding: 12px 5px !important;
        }
    }

    .diamond {
        margin: 5px 0;
        padding: 8px 5px;
        border-radius: 19px;
    }

    @media (max-width: 767px) {
        div.dataTables_wrapper div.dataTables_filter {
            padding-left: 151px;
        }

        div.dt-buttons {
            padding-left: 47px;
        }
    }
</style>
<style>
    #content .page-titel spna {
        color: #3fbfd7;
    }

    #content .page-titel {
        font-size: 13px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .search-bar {
        padding: 20px;
        border-bottom: 2px solid #efefef;
        background: #15112e !important;
    }

    table {
        background: transparent;
    }

    .form-control {
        display: block;
        width: 100%;
        /* height: calc(2.25rem + 2px); */
        padding: 2.375rem 0.75rem;
        font-size: 15px;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .has-tooltip {
        display: inline-block;
        position: relative;

    }

    .has-tooltip .on-hover {
        position: absolute;
        background: #fff;
        border: 2px solid #efefef;
        width: 220px;
        font-size: 11px;
        text-align: left;
        padding: 6px;
        color: #000;
        visibility: hidden;
        opacity: 0;
        transition: all 0.3s linear 0s;
        left: calc(100% - 110px);
        top: 100%;
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.12);
    }

    .has-tooltip:hover .on-hover {
        visibility: visible;
        opacity: 1;
    }

    section.content-header {
        background-color: #e0e0e0;
        padding: 10px;
        font-size: 20px;
        margin: 21px 0px;
        border-radius: 10px;
    }

    select.form-control {
        background: transparent !important;
    }

    td {
        color: white;
    }
</style>
<div class="main-content app-content mt-0">
    <div class="container-fluid">
        <div class="col-12">
            <div class="panel-heading">
                <h4 class="panel-title">Genelogy Tree</h4>
            </div>
            <div class="card">
                <div class="card-body">
                    <!-- BEGIN page-header -->


                    <div id="content" class="content">

                        <h2 class="page-titel">
                            <spna></span>
                        </h2>
                        <div id="rootwizard" class="wizard wizard-full-width">
                            <div class="row">
                                <!-- BEGIN col-3 -->
                                <div class="col-lg-12">
                                    <!-- <div class="search-bar bg-white d-none" style="padding: 20px;">
                                        <form action="<?php echo base_url('dashboard/genelogy-tree/'); ?>" id="srchform" method="GET">
                                            <div class="row">
                                                <div class="col-12 col-md-3">
                                                    <select class="form-control">
                                                        <option value="0">Search Type</option>
                                                        <option value="1">Search By Username</option>
                                                        <option value="2">Search By Name</option>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <input type="text" class="form-control" name="user_id" id="user_id" placeholder="Search Value">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <button type="submit" id="btnSearch" class="btn btn-primary">Search Now</button>
                                                    <?php
                                                    if ($validate_user == 1) {
                                                        echo '<a type="submit" id="btnLevelUp" class="btn btn-danger" href="' . base_url('dashboard/genelogy-tree/' . $level1->upline_id) . '"><i class="icon-arrow-up"></i> Level up</a>';
                                                    }
                                                    ?>
                                                    <a class="btn btn-success" style="" href="<?php echo base_url('dashboard/genelogy-tree/' . $this->session->userdata['user_id']); ?>"><i class="icon-home"></i> Go to My view</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div> -->
                                    <div id="tree" class="table-responsive">
                                        <?php
                                        if ($validate_user == 1) {
                                        ?>
                                            <div style="max-width:100%; overflow:scroll;color: #000;">
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="8" align="center" valign="top" class="viewtd">
                                                                <strong>
                                                                    <input name="last_member_id" type="hidden" id="last_member_id" value=""></strong><br>
                                                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="4" align="center">
                                                                                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td colspan="4" align="left" class="red2">
                                                                                                <strong>Left Total Team : </strong>
                                                                                                <font id="left_team"><?php echo $level1->left_count; ?></font>
                                                                                            </td>
                                                                                            <td align="right" class="red2"><strong>Right Total
                                                                                                    Team : </strong>
                                                                                                <font id="right_team"><?php echo $level1->right_count; ?></font>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <!-- <tr>
                                                                        <td colspan="4" align="left" class="red2">
                                                                            <strong>Left Total Points : </strong>
                                                                            <font id="left_bv"><?php echo $level1->leftPower; ?></font>
                                                                        </td>
                                                                        <td align="right" class="red2">
                                                                            <strong>Right Total Points : </strong>
                                                                            <font id="right_bv"><?php echo $level1->rightPower; ?></font>
                                                                        </td>
                                                                    </tr> -->
                                                                                        <!-- <tr>
                                                                                            <td colspan="4" align="left" class="red2">
                                                                                                <strong>Left Power : </strong>
                                                                                                <font id="left_matching"><?php echo $level1->leftPower; ?></font>
                                                                                            </td>
                                                                                            <td align="right" class="red2">
                                                                                                <strong>Right
                                                                                                    Power : </strong>
                                                                                                <font id="right_matching"><?php echo $level1->rightPower; ?></font>
                                                                                            </td>
                                                                                        </tr> -->
                                                                                        <tr>
                                                                                            <td colspan="4" align="left" class="red2">
                                                                                                <strong>Left Business : </strong>
                                                                                                <font id="left_matching"><?php echo $level1->leftBusiness; ?></font>
                                                                                            </td>
                                                                                            <td align="right" class="red2">
                                                                                                <strong>Right
                                                                                                    Business : </strong>
                                                                                                <font id="right_matching"><?php echo $level1->rightBusiness; ?></font>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <!-- <tr>
                                                                        <td colspan="4" align="left" class="red2">
                                                                            <strong>Left Pending Points : </strong>
                                                                            <font id="left_pending">2072</font>
                                                                        </td>
                                                                        <td align="right" class="red2"><strong>Right Pending
                                                                                Points : </strong>
                                                                            <font id="right_pending">0</font>
                                                                        </td>
                                                                    </tr> -->
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="p0">
                                                                            <td width="229" align="center" valign="top" class="message">&nbsp;
                                                                            </td>
                                                                            <td colspan="2" align="center">
                                                                                <a href="#" class="has-tooltip">
                                                                                    <img src="<?php echo tree_img($level1->package_amount, 0); ?>" alt="<?php echo $level1->name; ?>" border="0" width="60" class="left-right">
                                                                                    <span class="on-hover">
                                                                                        <!-- Username : <?php echo $level1->user_id; ?> <br> -->
                                                                                        Package : <?php echo $level1->package_amount; ?> <br>
                                                                                        Left team : <?php echo $level1->left_count; ?> | Right team : <?php echo $level1->right_count; ?> <br>
                                                                                        <?php //echo $level1->right_count; 
                                                                                        ?> <br>
                                                                                        <!-- Left Green Team : <?php //echo downlineTeam($level1->user_id, 1, 'L'); 
                                                                                                                ?> | Right Green Team : <?php echo downlineTeam($level1->user_id, 1, 'R'); ?><br>
                                                                                        Left points : <?php //echo $level1->leftPower; 
                                                                                                        ?> | Right points : <?php //echo $level1->rightPower; 
                                                                                                                            ?> <br> -->
                                                                                        Left Business : <?php echo $level1->leftBusiness; ?> | Right Business : <?php echo $level1->rightBusiness; ?> <br>
                                                                                    </span>
                                                                                </a>
                                                                                <br>
                                                                                <?php echo $level1->user_id; ?><br><?php echo $level1->name; ?> <br> <br>
                                                                            </td>
                                                                            <td width="230" align="center" valign="top" class="message">&nbsp;
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4" align="center">
                                                                                <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td align="center"><img src="<?php echo arrow_img() ?>" width="100%"></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <!----tree level 2 starts----->
                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                    <tbody>
                                                                                        <tr valign="top">
                                                                                            <?php
                                                                                            if (!empty($level2[1]->user_id)) {
                                                                                                echo '<td id="p1" width="50%" align="center">
                                                                                <a href="' . base_url('dashboard/genelogy-tree/' . $level2[1]->user_id) . '" class="has-tooltip">
                                                                                    <img src="' . tree_img($level2[1]->package_amount, 0) . '" alt="' . $level2[1]->name . '" border="0" width="60">
                                                                                    <span class="on-hover">
                                                                                        Username : ' . $level2[1]->name . ' <br>
                                                                                        Package : ' . $level2[1]->package_amount . '  <br>
                                                                                        Left team : ' . $level2[1]->left_count . ' | Right team : ' . $level2[1]->right_count . ' <br>

                                                                                        Left Business : ' . $level2[1]->leftBusiness . ' | Right Business : ' . $level2[1]->rightBusiness . ' <br>
                                                                                    </span>
                                                                                </a>
                                                                                <br>
                                                                                ' . $level2[1]->user_id . '<br>' . $level2[1]->name . ' <br> <br>
                                                                            </td>';
                                                                                            } else {
                                                                                                echo '<td  id="p1" width="50%" align="center">';
                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=L') . '">
                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                    <span class="on-hover">
                                                                                        Add New
                                                                                    </span>
                                                                                </a>';
                                                                                                echo ' <br></td>';
                                                                                            }
                                                                                            ?>
                                                                                            <?php
                                                                                            if (!empty($level2[2]->user_id)) {
                                                                                                echo '<td id="p1" width="50%" align="center">
                                                                                <a href="' . base_url('dashboard/genelogy-tree/' . $level2[2]->user_id) . '" class="has-tooltip">
                                                                                    <img src="' . tree_img($level2[2]->package_amount, 0) . '" alt="' . $level2[2]->name . '" border="0" width="60">
                                                                                    <span class="on-hover">
                                                                                        Username : ' . $level2[2]->name . ' <br>
                                                                                        Package : ' . $level2[2]->package_amount . '  <br>
                                                                                        Left team : ' . $level2[2]->left_count . ' | Right team : ' . $level2[2]->right_count . ' <br>
                                                                                        
                                                                                        Left Business : ' . $level2[2]->leftBusiness . ' | Right Business : ' . $level2[2]->rightBusiness . ' <br>
                                                                                    </span>
                                                                                </a>
                                                                                <br>
                                                                                ' . $level2[2]->user_id . '<br>' . $level2[2]->name . ' <br> <br>
                                                                            </td>';
                                                                                            } else {
                                                                                                echo '<td  id="p1" width="50%" align="center">';
                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=R') . '">
                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                    <span class="on-hover">
                                                                                        Add New
                                                                                    </span>
                                                                                </a>';
                                                                                                echo ' <br></td>';
                                                                                            }
                                                                                            ?>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td width="50%" height="28" align="center">
                                                                                                <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="sl_text">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td valign="top">
                                                                                                                <img src="<?php echo arrow1_img() ?>" width="100%" class="left-right">
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                            <td width="50%" height="26" align="center">
                                                                                                <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="sl_text">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td valign="top">
                                                                                                                <img src="<?php echo arrow1_img() ?>" width="100%" class="left-right">
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <table width="100%" height="0%" border="0" cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr align="center">
                                                                                            <?php
                                                                                            if (!empty($level3[1]->user_id)) {
                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                <a href="' . base_url('dashboard/genelogy-tree/' . $level3[1]->user_id) . '" class="has-tooltip">
                                                                                    <img src="' . tree_img($level3[1]->package_amount, 0) . '" alt="' . $level3[1]->name . '" border="0" width="60">
                                                                                    <span class="on-hover">
                                                                                        Username : ' . $level3[1]->name . ' <br>
                                                                                        Package : ' . $level3[1]->package_amount . '  <br>
                                                                                        Left team : ' . $level3[1]->left_count . ' | Right team : ' . $level3[1]->right_count . ' <br>
                                                                                         

                                                                                        Left Business : ' . $level3[1]->leftBusiness . ' | Right Business : ' . $level3[1]->rightBusiness . ' <br>
                                                                                    </span>
                                                                                    </a>
                                                                                    <br>
                                                                                    ' . $level3[1]->user_id . '<br>' . $level3[1]->name . ' <br> <br>
                                                                                </td>';
                                                                                            } else {
                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=L') . '">
                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                    <span class="on-hover">
                                                                                        Add New
                                                                                    </span>
                                                                                </a>';
                                                                                                echo ' <br></td>';
                                                                                            }
                                                                                            if (!empty($level3[2]->user_id)) {
                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                    <a href="' . base_url('dashboard/genelogy-tree/' . $level3[2]->user_id) . '" class="has-tooltip">
                                                                                        <img src="' . tree_img($level3[2]->package_amount, 0) . '" alt="' . $level3[2]->name . '" border="0" width="60">
                                                                                        <span class="on-hover">
                                                                                            Username : ' . $level3[2]->name . ' <br>
                                                                                            Package : ' . $level3[2]->package_amount . '  <br>
                                                                                            Left team : ' . $level3[2]->left_count . ' | Right team : ' . $level3[2]->right_count . ' <br>
                                                                                            
                                                                                            Left Business : ' . $level3[2]->leftBusiness . ' | Right Business : ' . $level3[2]->rightBusiness . ' <br>
                                                                                        </span>
                                                                                    </a>
                                                                                    <br>
                                                                                    ' . $level3[2]->user_id . '<br>' . $level3[2]->name . ' <br> <br>
                                                                                </td>';
                                                                                            } else {
                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=L') . '">
                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                    <span class="on-hover">
                                                                                        Add New
                                                                                    </span>
                                                                                </a>';
                                                                                                echo ' <br></td>';
                                                                                            }
                                                                                            if (!empty($level3[3]->user_id)) {
                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                    <a href="' . base_url('dashboard/genelogy-tree/' . $level3[3]->user_id) . '" class="has-tooltip">
                                                                                        <img src="' . tree_img($level3[3]->package_amount, 0) . '" alt="' . $level3[3]->name . '" border="0" width="60">
                                                                                        <span class="on-hover">
                                                                                            Username : ' . $level3[3]->name . ' <br>
                                                                                            Package : ' . $level3[3]->package_amount . ' <br>
                                                                                            Left team : ' . $level3[3]->left_count . ' | Right team : ' . $level3[3]->right_count . ' <br>
                                                                                          
                                                                                            Left Business : ' . $level3[3]->leftBusiness . ' | Right Business : ' . $level3[3]->rightBusiness . ' <br>
                                                                                        </span>
                                                                                    </a>
                                                                                    <br>
                                                                                    ' . $level3[3]->user_id . '<br>' . $level3[3]->name . ' <br> <br>
                                                                                </td>';
                                                                                            } else {
                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=L') . '">
                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                    <span class="on-hover">
                                                                                        Add New
                                                                                    </span>
                                                                                </a>';
                                                                                                echo ' <br></td>';
                                                                                            }
                                                                                            if (!empty($level3[4]->user_id)) {
                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                    <a href="' . base_url('dashboard/genelogy-tree/' . $level3[4]->user_id) . '" class="has-tooltip">
                                                                                        <img src="' . tree_img($level3[4]->package_amount, 0) . '" alt="' . $level3[4]->name . '" border="0" width="60">
                                                                                        <span class="on-hover">
                                                                                            Username : ' . $level3[4]->name . ' <br>
                                                                                            Package : ' . $level3[4]->package_amount . ' <br>
                                                                                            Left team : ' . $level3[4]->left_count . ' | Right team : ' . $level3[4]->right_count . ' <br>
                                                                                            
                                                                                            Left Business : ' . $level3[4]->leftBusiness . ' | Right Business : ' . $level3[4]->rightBusiness . ' <br>
                                                                                        </span>
                                                                                    </a>
                                                                                    <br>
                                                                                    ' . $level3[4]->user_id . '<br>' . $level3[4]->name . ' <br> <br>
                                                                                </td>';
                                                                                            } else {
                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=R') . '">
                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                    <span class="on-hover">
                                                                                        Add New
                                                                                    </span>
                                                                                </a>';
                                                                                                echo ' <br></td>';
                                                                                            }

                                                                                            ?>
                                                                                        </tr>
                                                                                        <tr align="center">
                                                                                            <td height="5" colspan="4"></td>
                                                                                        </tr>
                                                                                        <tr align="center">
                                                                                            <td width="25%" height="15%">
                                                                                                <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="sl_text">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td valign="top"><img src="<?php echo arrow2_img() ?>" width="100%"></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                            <td width="25%" height="15%">
                                                                                                <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="sl_text">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td valign="top"><img src="<?php echo arrow2_img() ?>" width="100%"></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                            <td width="25%" height="15%">
                                                                                                <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="sl_text">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td valign="top"><img src="<?php echo arrow2_img() ?>" width="100%"></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                            <td width="25%" height="15%">
                                                                                                <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="sl_text">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td valign="top"><img src="<?php echo arrow2_img() ?>" width="100%"></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width="50%" align="center" valign="top">
                                                                                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                                                                    <tbody>
                                                                                                        <tr align="center" valign="top">
                                                                                                            <?php
                                                                                                            if (!empty($level4[1]->user_id)) {
                                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                                    <a href="' . base_url('dashboard/genelogy-tree/' . $level4[1]->user_id) . '" class="has-tooltip">
                                                                                                        <img src="' . tree_img($level4[1]->package_amount, 0) . '" alt="' . $level4[1]->name . '" border="0" width="60">
                                                                                                        <span class="on-hover">
                                                                                                            Username : ' . $level4[1]->name . ' <br>
                                                                                                            Package : ' . $level4[1]->package_amount . ' <br>
                                                                                                            Left team : ' . $level4[1]->left_count . ' | Right team : ' . $level4[1]->right_count . ' <br>
                                                                                                           
                                                                                                            Left Business : ' . $level4[1]->leftBusiness . ' | Right Business : ' . $level4[1]->rightBusiness . ' <br>
                                                                                                        </span>
                                                                                                    </a>
                                                                                                    <br>
                                                                                                    ' . $level4[1]->user_id . '<br>' . $level4[1]->name . ' <br> <br>
                                                                                                </td>';
                                                                                                            } else {
                                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=L') . '">
                                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                                    <span class="on-hover">
                                                                                                        Add New
                                                                                                    </span>
                                                                                                </a>';
                                                                                                                echo ' <br></td>';
                                                                                                            }
                                                                                                            ?>
                                                                                                            <?php
                                                                                                            if (!empty($level4[2]->user_id)) {
                                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                                    <a href="' . base_url('dashboard/genelogy-tree/' . $level4[2]->user_id) . '" class="has-tooltip">
                                                                                                        <img src="' . tree_img($level4[2]->package_amount, 0) . '" alt="' . $level4[2]->name . '" border="0" width="60">
                                                                                                        <span class="on-hover">
                                                                                                            Username : ' . $level4[2]->name . ' <br>
                                                                                                            Package : ' . $level4[2]->package_amount . ' <br>
                                                                                                            Left team : ' . $level4[2]->left_count . ' | Right team : ' . $level4[2]->right_count . ' <br>
                                                                                                           
                                                                                                            Left Business : ' . $level4[2]->leftBusiness . ' | Right Business : ' . $level4[2]->rightBusiness . ' <br>
                                                                                                        </span>
                                                                                                    </a>
                                                                                                    <br>
                                                                                                    ' . $level4[2]->user_id . '<br>' . $level4[2]->name . ' <br> <br>
                                                                                                </td>';
                                                                                                            } else {
                                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=L') . '">
                                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                                    <span class="on-hover">
                                                                                                        Add New
                                                                                                    </span>
                                                                                                </a>';
                                                                                                                echo ' <br></td>';
                                                                                                            }
                                                                                                            ?>
                                                                                                            <?php
                                                                                                            if (!empty($level4[3]->user_id)) {
                                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                                    <a href="' . base_url('dashboard/genelogy-tree/' . $level4[3]->user_id) . '" class="has-tooltip">
                                                                                                        <img src="' . tree_img($level4[3]->package_amount, 0) . '" alt="' . $level4[3]->name . '" border="0" width="60">
                                                                                                        <span class="on-hover">
                                                                                                            Username : ' . $level4[3]->name . ' <br>
                                                                                                            Package : ' . $level4[3]->package_amount . ' <br>
                                                                                                            Left team : ' . $level4[3]->left_count . ' | Right team : ' . $level4[3]->right_count . ' <br>
                                                                                                             
                                                                                                            Left Business : ' . $level4[3]->leftBusiness . ' | Right Business : ' . $level4[3]->rightBusiness . ' <br>
                                                                                                        </span>
                                                                                                    </a>
                                                                                                    <br>
                                                                                                    ' . $level4[3]->user_id . '<br>' . $level4[3]->name . ' <br> <br>
                                                                                                </td>';
                                                                                                            } else {
                                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=L') . '">
                                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                                    <span class="on-hover">
                                                                                                        Add New
                                                                                                    </span>
                                                                                                </a>';
                                                                                                                echo ' <br></td>';
                                                                                                            }
                                                                                                            ?>
                                                                                                            <?php
                                                                                                            if (!empty($level4[4]->user_id)) {
                                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                                    <a href="' . base_url('dashboard/genelogy-tree/' . $level4[4]->user_id) . '" class="has-tooltip">
                                                                                                        <img src="' . tree_img($level4[4]->package_amount, 0) . '" alt="' . $level4[4]->name . '" border="0" width="60">
                                                                                                        <span class="on-hover">
                                                                                                            Username : ' . $level4[4]->name . ' <br>
                                                                                                            Package : ' . $level4[4]->package_amount . ' <br>
                                                                                                            Left team : ' . $level4[4]->left_count . ' | Right team : ' . $level4[4]->right_count . ' <br>
                                                                                                            
                                                                                                            Left Business : ' . $level4[4]->leftBusiness . ' | Right Business : ' . $level4[4]->rightBusiness . ' <br>
                                                                                                        </span>
                                                                                                    </a>
                                                                                                    <br>
                                                                                                    ' . $level4[4]->user_id . '<br>' . $level4[4]->name . ' <br> <br>
                                                                                                </td>';
                                                                                                            } else {
                                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=L') . '">
                                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                                    <span class="on-hover">
                                                                                                        Add New
                                                                                                    </span>
                                                                                                </a>';
                                                                                                                echo ' <br></td>';
                                                                                                            }
                                                                                                            ?>

                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                            <td width="50%" align="center" valign="top">
                                                                                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                                                                    <tbody>
                                                                                                        <tr align="center" valign="top">
                                                                                                            <?php
                                                                                                            if (!empty($level4[5]->user_id)) {
                                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                                    <a href="' . base_url('dashboard/genelogy-tree/' . $level4[5]->user_id) . '" class="has-tooltip">
                                                                                                        <img src="' . tree_img($level4[5]->package_amount, 0) . '" alt="' . $level4[5]->name . '" border="0" width="60">
                                                                                                        <span class="on-hover">
                                                                                                            Username : ' . $level4[5]->name . ' <br>
                                                                                                            Package : ' . $level4[5]->package_amount . ' <br>
                                                                                                            Left team : ' . $level4[5]->left_count . ' | Right team : ' . $level4[5]->right_count . ' <br>
                                                                                                             
                                                                                                            Left Business : ' . $level4[5]->leftBusiness . ' | Right Business : ' . $level4[5]->rightBusiness . ' <br>
                                                                                                        </span>
                                                                                                    </a>
                                                                                                    <br>
                                                                                                    ' . $level4[5]->user_id . '<br>' . $level4[5]->name . ' <br> <br>
                                                                                                </td>';
                                                                                                            } else {
                                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=R') . '">
                                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                                    <span class="on-hover">
                                                                                                        Add New
                                                                                                    </span>
                                                                                                </a>';
                                                                                                                echo ' <br></td>';
                                                                                                            }
                                                                                                            ?>
                                                                                                            <?php
                                                                                                            if (!empty($level4[6]->user_id)) {
                                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                                <a href="' . base_url('dashboard/genelogy-tree/' . $level4[6]->user_id) . '" class="has-tooltip">
                                                                                                        <img src="' . tree_img($level4[6]->package_amount, 0) . '" alt="' . $level4[6]->name . '" border="0" width="60">
                                                                                                        <span class="on-hover">
                                                                                                            Username : ' . $level4[6]->name . ' <br>
                                                                                                            Package : ' . $level4[6]->package_amount . ' <br>
                                                                                                            Left team : ' . $level4[6]->left_count . ' | Right team : ' . $level4[6]->right_count . ' <br>
                                                                                                            
                                                                                                            Left Business : ' . $level4[6]->leftBusiness . ' | Right Business : ' . $level4[6]->rightBusiness . ' <br>
                                                                                                        </span>
                                                                                                    </a>
                                                                                                    <br>
                                                                                                    ' . $level4[6]->user_id . '<br>' . $level4[6]->name . ' <br> <br>
                                                                                                </td>';
                                                                                                            } else {
                                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=R') . '">
                                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                                    <span class="on-hover">
                                                                                                        Add New
                                                                                                    </span>
                                                                                                </a>';
                                                                                                                echo ' <br></td>';
                                                                                                            }
                                                                                                            ?>
                                                                                                            <?php
                                                                                                            if (!empty($level4[7]->user_id)) {
                                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                                    <a href="' . base_url('dashboard/genelogy-tree/' . $level4[7]->user_id) . '" class="has-tooltip">
                                                                                                        <img src="' . tree_img($level4[7]->package_amount, 0) . '" alt="' . $level4[7]->name . '" border="0" width="60">
                                                                                                        <span class="on-hover">
                                                                                                            Username : ' . $level4[7]->name . ' <br>
                                                                                                            Package : ' . $level4[7]->package_amount . ' <br>
                                                                                                            Left team : ' . $level4[7]->left_count . ' | Right team : ' . $level4[7]->right_count . ' <br>
                                                                                                             
                                                                                                            Left Business : ' . $level4[7]->leftBusiness . ' | Right Business : ' . $level4[7]->rightBusiness . ' <br>
                                                                                                        </span>
                                                                                                    </a>
                                                                                                    <br>
                                                                                                    ' . $level4[7]->user_id . '<br>' . $level4[7]->name . ' <br> <br>
                                                                                                </td>';
                                                                                                            } else {
                                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=R') . '">
                                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                                    <span class="on-hover">
                                                                                                        Add New
                                                                                                    </span>
                                                                                                </a>';
                                                                                                                echo ' <br></td>';
                                                                                                            }
                                                                                                            ?>
                                                                                                            <?php
                                                                                                            if (!empty($level4[8]->user_id)) {
                                                                                                                echo '<td id="p3" width="25%" height="85%" valign="top">
                                                                                                    <a href="' . base_url('dashboard/genelogy-tree/' . $level4[8]->user_id) . '" class="has-tooltip">
                                                                                                        <img src="' . tree_img($level4[8]->package_amount, 0) . '" alt="' . $level4[8]->name . '" border="0" width="60">
                                                                                                        <span class="on-hover">
                                                                                                            Username : ' . $level4[8]->name . ' <br>
                                                                                                            Package : ' . $level4[8]->package_amount . ' <br>
                                                                                                            Left team : ' . $level4[8]->left_count . ' | Right team : ' . $level4[8]->right_count . ' <br>
                                                                                                            
                                                                                                            Left Business : ' . $level4[8]->leftBusiness . ' | Right Business : ' . $level4[8]->rightBusiness . ' <br>
                                                                                                        </span>
                                                                                                    </a>
                                                                                                    <br>
                                                                                                    ' . $level4[8]->user_id . '<br>' . $level4[8]->name . ' <br> <br>
                                                                                                </td>';
                                                                                                            } else {
                                                                                                                echo '<td  id="p1" width="25%" height="85%" valign="top">';
                                                                                                                echo '<a href="' . base_url('register/?sponser_id=' . $this->session->userdata['user_id'] . '&position=R') . '">
                                                                                                    <img src="' . tree_img(0, 1) . '" alt="waiting" border="0" width="60"><br>
                                                                                                    <span class="on-hover">
                                                                                                        Add New
                                                                                                    </span>
                                                                                                </a>';
                                                                                                                echo ' <br></td>';
                                                                                                            }
                                                                                                            ?>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8" align="center" valign="top">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8" align="center" valign="top">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8" align="center" valign="top">
                                                                <table cellpadding="5">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php
                                        } else {
                                            echo 'This Account is not in Your Team';
                                        }
                                        ?>

                                    </div>
                                </div>
                                <!-- END col-3 -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php //}
        $this->load->view('footer');
        ?>
        <!-- <script>
$(document).on('submit','#srchform',functione(e){
    e.preventDefault();
    var url = $(this).attr('action')+'/'+$('#user_id').val();
    $(this).attr('action',url)
    console.log(url);
    // $(this).submit();
})
</script> -->