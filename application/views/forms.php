<?php
include_once 'header.php';
(empty($export) ? $export = false : $export = $export);
(empty($script) ? $script = false : $script = $script);
?>
<div class="main-content main_content_new">
    <div class="page-content">
        <div class="container-fluid">

            <!-- all page subHeader  -->

            <div class="row m-0 mb-4 mt-1 new__sec">
                <div class="col-12">
                    <div class="sub__header">
                        <h5 class="m-0 text-dark starte__txt"><?php echo $header; ?> </h5>

                        <ol class="breadcrumb float-sm-right mb-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('DeveloperMode') ?>">Home</a></li>
                        </ol>
                    </div>
                </div>
            </div>
            <div>
                <div class="row">
                    <div class="col-md-12 d-flex align-items-center justify-content-start">
                        <div class="card w-100 h-auto">
                            <div class="card-header text-center">
                                <!-- <?php //echo $header; ?> -->
                            </div>
                            <div class="card-body">
                                <?php echo $form_open ?>
                                <span><?php echo $this->session->flashdata('message'); ?></span>
                                <?php foreach ($form as $key => $value) { ?>
                                    <div class="form-group"> <?php echo $value; ?></div>
                                    <span class="text-danger"><?php echo form_error($key); ?></span>
                                <?php  } ?>
                                <div class="col-md-12">
                                    <div class="form-group mt-3">
                                        <?php echo $form_button; ?>
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
    <?php include_once 'footer.php'; ?>
    <script>
    $('#global-loader').hide()
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#slipImage').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#payment_slip").change(function () {
        readURL(this);
    });
    </script>