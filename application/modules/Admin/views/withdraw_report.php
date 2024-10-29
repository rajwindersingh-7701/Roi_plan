<?php
include_once 'header.php';
(empty($export)) ? $export = false : $export = $export;
(empty($script)) ? $script = false : $script = $script;
?>

<style>
    .width-set {
        width: auto;
    }

    html,
    body {
        background-color: #fff;
    }

    .form-popup-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        align-content: center;
        justify-content: center;
    }

    .form-popup-bg {
        position: fixed;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        background-color: rgba(94, 110, 141, 0.9);
        opacity: 0;
        visibility: hidden;
        -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s;
        -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s;
        transition: opacity 0.3s 0s, visibility 0s 0.3s;
        overflow-y: auto;
        z-index: 10000;
    }

    .form-popup-bg.is-visible {
        opacity: 1;
        visibility: visible;
        -webkit-transition: opacity 0.3s 0s, visibility 0s 0s;
        -moz-transition: opacity 0.3s 0s, visibility 0s 0s;
        transition: opacity 0.3s 0s, visibility 0s 0s;
    }

    .form-container {
        background-color: #2d3638;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        padding: 40px;
        color: #fff;
    }

    .close-button {
        background: none;
        color: #fff;
        width: 40px;
        height: 40px;
        position: absolute;
        top: 0;
        right: 0;
        border: solid 1px #fff;
    }

    .form-popup-bg:before {
        content: '';
        background-color: #fff;
        opacity: .25;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
</style>
<div class="main-content main_content_new">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row m-0 mb-4 mt-1 new__sec">
                <div class="col-12">
                    <div class="sub__header">
                        <h5 class="m-0 text-dark starte__txt"><?php echo $header; ?></h5>
                        <ol class="breadcrumb float-sm-right mb-0 bg-transparent">
                            <li class="breadcrumb-item active"><a href="<?php echo base_url('admin/dashboard') ?>">Home</a></li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="table__sec">
                <div class="row">
                    <div class="col-12">
                        <div class="form-popup-bg">
                            <div class="form-container">
                                <button id="btnCloseForm" class="close-button">X</button>
                                <h1>Withdraw Request</h1>
                                <p></p>
                                <?php echo form_open(base_url('Admin/Withdraw/request')); ?>

                                <div class="form-group">
                                    <label for="user_id">User ID</label>
                                    <input class="form-control" name="user_id" id="user_id" type="text" readonly />
                                    <input class="form-control" name="main_id" id="main_id" type="hidden" readonly />
                                </div>
                                <div class="form-group">
                                    <label for="">Remark</label>
                                    <textarea class="form-control" name="remark" id="" cols="3" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select class="form-control" name="status" id="">
                                        <option value="1">Approved</option>
                                        <option value="2">Rejected</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success btn_success">Submit</button>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card table__card_dc">

                            <div class="card-header">
                                <form method="GET" class="w-100" action="<?php echo $url; ?>">
                                    <div class="row">
                                        <?php
                                        // if (exportsAll === true) :
                                            if ($export == true) :
                                        ?>
                                                <div class="col-12">
                                                    <div class="main__center card-header-column-main">
                                                        <h4 class="mb-0"> </h4>
                                                        <div class="export-table">
                                                            <a href="<?php echo $url . 'export=xls'; ?>" class="export-btn btn-primary "><img src="<?php echo base_url('NewDashboard/'); ?>assets/images/xls.png">Export to xls</a>
                                                            <a href="<?php echo $url . 'export=csv'; ?>" class="export-btn btn-success "><img src="<?php echo base_url('NewDashboard/'); ?>assets/images/csv.png">Export to csv</a>
                                                        </div>

                                                    </div>
                                                </div>
                                        <?php
                                            endif;
                                        //endif; ?>

                                        <div class="dropdown-divider"></div>
                                        <div class="col-12">
                                            <div class="New___sub_tabs card-header-column-main">
                                                <?php echo $field; ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <p id="demo"></p>
                                <table class="table table-hover" id="">
                                    <thead>
                                        <?php echo $thead; ?>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($tbody as $key => $value) {
                                            echo $value;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">

                                <div class="row align-items-center">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info" id="tableView_info" role="status" aria-live="polite">
                                            Showing <?php echo ($segment + 1) . ' to  ' . ($i - 1);  ?> of
                                            <?php echo $total_records;  ?> entries</div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_simple_numbers d-flex justify-content-end align-items-center" id="tableView_paginate">
                                            <?php
                                            echo $this->pagination->create_links();
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once 'footer.php';
if ($script == true) {
?>
    <script>
        function closeForm() {
            $('.form-popup-bg').removeClass('is-visible');
        }
        $(document).ready(function($) {
            /* Contact Form Interactions */
            $('.WithdrawUser').on('click', function(event) {
                event.preventDefault();

                $('.form-popup-bg').addClass('is-visible');
            });
            //close popup when clicking x or off popup
            $('.form-popup-bg').on('click', function(event) {
                if ($(event.target).is('.form-popup-bg') || $(event.target).is('#btnCloseForm')) {
                    event.preventDefault();
                    $(this).removeClass('is-visible');
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.WithdrawUser', function() {
            // var user_id = $(this).data('user_id');
            var id = $(this).data('id');
            var url = "<?php echo base_url('Admin/Withdraw/WithdrawUserCheck/'); ?>" + id;
            $.get(url, function(res) {
                if (res.success == 1) {
                    // console.log(res);
                    document.getElementById("user_id").value = res.data.user_id;
                    document.getElementById("main_id").value = res.data.id;
                } else {
                    document.getElementById("errorMessage").value = res.message;
                }
            }, 'json')
        })
    </script>
<?php } ?>