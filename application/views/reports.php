<?php
include_once 'header.php';
(empty($export)) ? $export = false : $export = $export;
(empty($script)) ? $script = false : $script = $script;
?>
<div class="main-content main_content_new">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row m-0 mb-4 mt-1 new__sec">
                <div class="col-12">
                    <div class="sub__header">
                        <h5 class="m-0 text-dark starte__txt"><?php echo $header; ?></h5>
                        <ol class="breadcrumb float-sm-right mb-0 bg-transparent">
                            <li class="breadcrumb-item active"><a href="<?php echo base_url('DeveloperMode') ?>">Home</a></li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="table__sec">
                <div class="row">
                    <div class="col-12">
                        <div class="card table__card_dc">

                            <div class="card-header">
                                <form method="GET" class="w-100" action="<?php echo $path; ?>">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="main__center card-header-column-main">
                                                <?php echo $this->session->flashdata('developer_message') ?>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="main__center card-header-column-main">
                                                <h4 class="mb-0"> </h4>

                                                <div class="export-table">
                                                    <a href="<?php echo $path . '?export=xls'; ?>" class="export-btn btn-primary "><img src="<?php echo base_url('NewDashboard/'); ?>assets/images/xls.png">Export to xls</a>
                                                    <a href="<?php echo $path . '?export=csv'; ?>" class="export-btn btn-success "><img src="<?php echo base_url('NewDashboard/'); ?>assets/images/csv.png">Export to csv</a>
                                                </div>

                                            </div>
                                        </div>

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
                                            Showing <?php echo ($records['segment'] + 1) . ' to  ' . ($i - 1);  ?> of
                                            <?php echo $records['total_records'];  ?> entries</div>
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
        $(document).on('click', '.blockUser', function() {
            var status = $(this).data('status');
            var user_id = $(this).data('user_id');
            var url = "<?php echo base_url('Admin/Management/blockStatus/'); ?>" + user_id + '/' + status;
            $.get(url, function(res) {
                alert(res.message)
                if (res.success == 1)
                    location.reload()
            }, 'json')
        })
    </script>
<?php } ?>