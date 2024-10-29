<?php $this->load->view('header'); ?>
<div class="main-content app-content mt-0">
    <div class="contanier-fluid">
        <!-- <section class="main-content"> -->
        <div class="col-md-12">
            <div class="pannel-box-custom">
                <div class="panel-heading mb-3">
                    <h4 class="panel-title"><?php echo $heeader ?></h4>
                </div>

            </div>
        </div>
        <div class="content mt-3 col-12">
            <div id="rootwizard" class="card wizard-full-width cstm-card">
                <div class="card-body">
                    <div class="wizard-content tab-content">
                        <div class="tab-pane active show" id="tabFundRequestForm">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <div class="row row-space-6">
                                            <div class="col-12">
                                                <div class="to-padding widget widget-stats">
                                                    <div class="widget-stats-info mm-info">
                                                        <div class="widget-stats-value to-fontsize" id="balance_received"> E-Wallet Balance: <?php echo currency; ?></div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-b-10 d-block">

                                    <?php echo form_open_multipart(); ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <h2><?php echo $this->session->flashdata('message'); ?></h2>
                                            <div class="form-group">
                                                <label class="d-block">QR Code</label>
                                                <img style="width: 300px;background: #fff;padding: 10px;box-shadow: 0px 0px 10px rgb(230,230,230);" class="img-thumbnail" src="<?php echo base_url('uploads/' . $qrcode['media']) ?>" alt="">
                                            </div>
                                            <div class="form-group">
                                                <label>Enter Amount you want to Request in <?php echo currency; ?></label>
                                                <?php
                                                echo form_input(array('type' => 'number', 'name' => 'amount', 'class' => 'form-control', 'placeholder' => 'Enter Amount'));
                                                ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Choose Payment Method</label>
                                                <?php
                                                $option = [
                                                    'bank' => 'Bank',
                                                    'upi' => 'UPI',
                                                ];
                                                echo form_dropdown('payment_method', $option, '', 'class = form-control');
                                                ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Enter Txn ID</label>
                                                <?php
                                                echo form_input(array('type' => 'text', 'name' => 'txn_id', 'class' => 'form-control', 'placeholder' => 'Enter Txn ID'));
                                                ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Proof</label>
                                                <?php
                                                echo form_input(array('type' => 'file', 'name' => 'image', 'id' => 'payment_slip', 'class' => 'form-control'));
                                                ?>
                                            </div>


                                            <div class="form-group">
                                                <?php
                                                echo form_input(array('type' => 'submit', 'class' => 'btn btn-info', 'name' => '`fundbtn`', 'value' => 'Request'));
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <img src="" title="Payment Slip" id="slipImage" style="width: 90%; display:none">
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- </section> -->
</div>
</div>


<?php $this->load->view('footer'); ?>
<script>
    $('#global-loader').hide()

    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#slipImage').css('display', 'block');
                $('#slipImage').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#payment_slip").change(function() {
        readURL(this);
    });
    $(document).on('submit', '#paymentForm', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#savebtn').css('display', 'none');
        $('#uploadnot').css('display', 'block');
        var action = $(this).attr('action');
        $.ajax({
            url: action,
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                data = JSON.parse(data);
                if (data.success === 1) {
                    toastr.success(data.message);
                    //                    swal("Thank You", data.message);
                    location.reload();
                } else {
                    toastr.error(data.message);
                }
                $('#savebtn').css('display', 'block');
                $('#uploadnot').css('display', 'none');
            }
        });
    });
</script>