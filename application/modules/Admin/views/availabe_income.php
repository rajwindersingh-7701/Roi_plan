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
                            <li class="breadcrumb-item active"><a href="<?php echo base_url('admin/dashboard') ?>">Home</a></li>
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
                                        <?php if (exportsAll === true) : ?>
                                            <div class="col-12">
                                                <div class="main__center card-header-column-main">
                                                    <h4 class="mb-0"> </h4>
                                                    <div class="export-table">
                                                        <a href="<?php echo $path . '?export=xls'; ?>" class="export-btn btn-primary "><img src="<?php echo base_url('NewDashboard/'); ?>assets/images/xls.png">Export to xls</a>
                                                        <a href="<?php echo $path . '?export=csv'; ?>" class="export-btn btn-success "><img src="<?php echo base_url('NewDashboard/'); ?>assets/images/csv.png">Export to csv</a>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="dropdown-divider"></div>
                                        <div class="col-12">
                                            <div class="New___sub_tabs card-header-column-main">
                                                <?php //echo $field; 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <p id="demo"></p>
                                <table class="table table-hover" id="tableView">
                                    <thead>
                                        <th>#</th>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Total Package</th>

                                        <th>Total Income</th>

                                        <th>Available Balance</th>
                                        <th>E-Walet Balance</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($availableIncome as $key => $rec) {
                                            extract($rec);
                                            $user = get_single_record('tbl_users', 'user_id = "' . $user_id . '"', 'name,total_package');
                                            $e_wallet = get_single_record('tbl_wallet', array('user_id' => $user_id), 'ifnull(sum(amount),0) as e_wallet');
                                            $total_income = get_single_record('tbl_income_wallet', array('user_id' => $user_id,'amount >' => 0), 'ifnull(sum(amount),0) as total_income');

                                            echo '<tr>
                                            <td>' . ($key + 1) . '</td>
                                            <td>' . $user_id . '</td>
                                            <td>' . $user['name'] . '</td>
                                            <td>' . $user['total_package'] . '</td>

                                            <td>' . number_format($total_income['total_income'],2) . '</td>

                                            <td>' . number_format($total_amount,2) . '</td>
                                            <td>' .  number_format($e_wallet['e_wallet'],2) . '</td>
                                         </tr>';
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">

                                <div class="row align-items-center">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info" id="tableView_info" role="status" aria-live="polite">
                                            Showing <?php //echo ($segment + 1) . ' to  ' . ($i - 1);  
                                                    ?> of
                                            <?php //echo $total_records;  
                                            ?> entries</div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_simple_numbers d-flex justify-content-end align-items-center" id="tableView_paginate">
                                            <?php
                                            // echo $this->pagination->create_links();
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
    <script>
        $(document).on('click', '.apvbtn', function() {
            var id = $(this).data('id');
            var status = $(this).data('status')
            var url = '<?php echo base_url("Admin/Withdraw/ApproveUserAddressRequest/"); ?>' + id + '/' + status;
            var r = confirm("Are You Sure to Approve this User for Withdraw");
            if (r == true) {
                $.get(url, function(res) {
                    alert(res.message)
                    if (res.success == 1)
                        location.reload()
                    console.log(res);
                }, 'json')
            }

        })

        $(document).on('click', '#btnCopy', function() {
            var copyText = document.getElementById("linkTxt");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            toaster.success("Copied the text: " + copyText.value)
            // alert("Copied the text: " + copyText.value);
        })
    </script>
<?php } ?>