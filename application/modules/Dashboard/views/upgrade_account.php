<?php include_once 'header.php'; ?>
<style>
    .table-head-box {
        background: #29327f;
        text-align: center;
        padding: 7px 0 6px;
        color: #fff;
    }

    .table-head-box h4 {
        margin: 0px;
    }
</style>
<div class="main-content app-content mt-0">
    <div class="container-fluid">
        <div class="">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo 'Upgrade Account'; ?></h4>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card cstm-card">
                        <div class="card-body">
                            <p class="text-dark">Wallet balance: <span id=""><?php echo currency . $wallet['wallet_balance']; ?></span></p>
                            <div class="wizard-content tab-content p-0">
                                <div class="tab-pane active show" id="tabFundRequestForm">
                                    <div>
                                        <div class="col-md-12 p-0">
                                            <?php echo form_open('upgradeAjax', array('id' => 'TopUpForm')); ?>
                                            <div class="form-group">
                                                <label>User ID</label>
                                                <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo $this->session->userdata['user_id']; ?>" placeholder="User ID" readonly />
                                                <span class="text-danger"><?php echo form_error('user_id') ?></span>
                                                <span class="text-danger" id="userName"></span>
                                            </div>

                                            <?php if (activation_process == 0) { ?>
                                                <div class="form-group">
                                                    <label>Enter Amount in $</label>
                                                    <input type="text" class="form-control" name="amount" value="<?php echo set_value('amount'); ?>" placeholder="Enter Amount" id="amount" >
                                                    <span class="text-danger"><?php echo form_error('amount') ?></span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="form-group">
                                                    <label>Choose Package</label>
                                                    <select class="form-control" name="package_id">
                                                        <?php
                                                        foreach ($packages as $key => $package) {
                                                            echo '<option value="' . $package['id'] . '">'  . currency . ' ' . $package['price'] . ' </option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            <?php } ?>

                                            <div class="form-group" id="SaveBtn">
                                                <button type="button" id="stake" name="save" class="btn btn-info" onclick="upgradeNow(this,'TopUpForm')"><?php echo 'Upgrade'; ?></button>
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

<?php include_once 'footer.php'; ?>

<script>
    const upgradeNow = (evt, id) => {
        // toastr.info('Please wait...');
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
                toastr.options = {
                    "closeButton": true,
                    "newestOnTop": true,
                    "progressBar": true,
                    "preventDuplicates": true,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                if (result.status == true) {
                    toastr.success(result.message);
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    toastr.info(result.message)
                };
            });
    }
</script>
<script>
    $(document).on('submit', 'form', function() {
        if (confirm('Are You Sure U want to Topup This Account')) {
            yourformelement.submit();
        } else {
            return false;
        }
    })
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>
    $(document).on('blur', '#user_id', function() {
        check_sponser();
    })

    function check_sponser() {
        var user_id = $('#user_id').val();
        if (user_id != '') {
            var url = '<?php echo base_url('get_user/') ?>' + user_id;
            $.get(url, function(res) {
                $('#userName').html(res);
            })
        }
    }
    check_sponser();
</script>

<!----------------------->