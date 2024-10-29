<?php
include_once 'header.php';
$userinfo = userinfo();
$bankinfo = bankinfo();
?>

<style>
    input {
        background-color: red !important;
    }

    .form-control::placeholder {
        color: red !important;
    }
</style>


<div class="main-content app-content mt-0">

    <div class="container-fluid">
        <div class="">
            <div class="row row-space-20">
                <div class="col-md-12">
                    <div class="tab-content p-0">
                        <div class="row " style="display:none;">

                            <div class="col-md-12 mb-4">
                                <div class="card card-body" style="display:block">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Select Profile Image</h4>
                                        <?php echo $this->session->flashdata('message'); ?>
                                    </div>
                                    <div class="col-xs-12 col-md-9 col-lg-12">
                                        <div class="panel-white panel">


                                            <div class="panel-body">
                                                <?php echo form_open_multipart(base_url('Dashboard/User/UploadProof/'), array('method' => 'post', 'class' => 'proofForm')); ?>
                                                <table class="table table-layout-fixed uploaded-docs-table" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td class="uploaded-docs-table-status">
                                                                <div class="profile-img" id="ImgID">
                                                                    <input type="file" name="userfile" class="" placeholder="" /><br>
                                                                    <input type="hidden" name="proof_type" value="profile_image" /><br>
                                                                    <?php
                                                                    if ($user_bank->profile_image != '') {
                                                                        echo '<img src="' . base_url('uploads/' . $user_bank->profile_image) . '" class="img-responsive" style="max-width:200px;"><br>';
                                                                    } else {
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </td>
                                                            <td class="uploaded-docs-table-btn pr0">
                                                                <div class="loader"></div>
                                                                <?php
                                                                if ($user_bank->kyc_status != 2)
                                                                    echo '<input type="submit" class="btn btn-primary thgy" value="upload"> ';
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <?php echo form_close(); ?>
                                                <div class="col-sm-12">
                                                    <img id="ImgAdd7898558" alt="Bank account proof" width="80" height="80" style="display:none;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-heading mb-3">
                            <h4 class="panel-title">MY PERSONAL INFORMATION</h4>
                            <?php echo $this->session->flashdata('message'); ?>
                        </div>

                        <div class="tab-pane active" id="ACCOUNT-DETAILS">
                            <div class="col-md-6 card card-body">
                                <div class="">
                                    <div class="post">
                                        <?php echo form_open(base_url('Dashboard/Profile/Index'), array('class' => '')); ?>
                                        <div class="row align-items-center mb-1">

                                            <div class="col-md-12  profile-contact">
                                                <div class="profile-heading mt-1 mb-2">
                                                    <span>Name</span>
                                                </div>
                                                <input type="text" class="form-control" value="<?php echo $userinfo->name; ?>" name="name">
                                            </div>

                                            <div class="col-md-12  profile-contact">
                                                <div class="profile-heading mt-3 mb-2">
                                                    <span>Contact Number</span>
                                                </div>
                                                <input type="number" class="form-control" value="<?php echo $userinfo->phone; ?>" name="phone">
                                            </div>

                                            <div class="col-md-12  profile-contact">
                                                <div class="profile-heading mt-3 mb-2">
                                                    <span>Email</span>
                                                </div>
                                                <input type="email" class="form-control" value="<?php echo $userinfo->email; ?>" name="email">
                                            </div>


                                            <div class="col-md-12  profile-contact">
                                                <div class="profile-heading mt-3 mb-2">
                                                    <span>Country</span>
                                                </div>
                                                <select name="country" class="form-control">
                                                    <option value="">-- Select Country --</option>
                                                    <?php foreach ($countries as $key => $value) {
                                                        echo '<option value="' . $value['name'] . '" ' . ($value['name'] == $userinfo->country ? 'selected' : '') . '>' . $value['name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>

                                            <div class="col-md-12  profile-contact">
                                                <div class="profile-heading mt-3 mb-2">
                                                    <span>Status</span>
                                                </div>
                                                <span id="sts" class="form-control">
                                                    <?php echo $userinfo->package_id > 0 ? 'Active' : 'Free'; ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- <div class="col-md-12 pr-1 p-0" style="display:none;" id="enter_otp">

                                            <div class="profile-contact">
                                                <div class="profile-heading mt-3 mb-2">
                                                    <span>OTP</span>
                                                </div>
                                                <input type="text" class="form-control" name="otp" id="" placeholder="Enter OTP" value="<?php echo set_value('otp'); ?>" required="true">
                                                <span class="text-danger"><?php echo form_error('otp') ?></span>
                                            </div>
                                        </div> -->

                                        <tr>
                                            <td class="field"></td>
                                            <td class="value mt-2">
                                                <!-- <button type="button" name="save" class="btn btn-info mt-2" id="otp">Send OTP</button> -->
                                                <button type="subimt" name="save" class="btn btn-info mt-2" id="" style="display:blaock;">Update</button>
                                            </td>
                                        </tr>

                                        </tbody>

                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>



                            <div class="panel-body gtdtf" style="display:none">
                                <!-- BEGIN file-upload-form -->
                                <div ui-view="" class="">
                                    <!-- uiView:  -->
                                    <div ui-view="" class="fade-in-up ng-scope">
                                        <div class="container-fluid my-documents-page">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">KYC</h4>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="lead">
                                                        Verify your Identity and Proof of Residence in order to activate your account and get access to all areas of AG Token.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ng-scope " data-ng-controller="VerifyProfileDocStatusCtrl">
                                                <div class="col-xs-12">
                                                    <div class="panel-white panel">
                                                        <div class="panel-body pt5 pb5">
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <?php echo form_open_multipart(base_url('Dashboard/User/UploadProof/'), array('method' => 'post', 'class' => 'proofForm')); ?>
                                                                    <table class="table table-layout-fixed uploaded-docs-table" width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="uploaded-docs-table-name">
                                                                                    <span class="document-verify-step1 lead mb0">
                                                                                        <i class="ti-user color-light-blue" style="color: #007aff;"></i>
                                                                                        Aadhar Card Front
                                                                                    </span>
                                                                                </td>

                                                                                <td class="uploaded-docs-table-status">
                                                                                    <div class="verification-img" id="ImgID">
                                                                                        <input type="file" name="userfile" class="" placeholder="" /><br>
                                                                                        <input type="hidden" name="proof_type" value="id_proof" /><br>
                                                                                        <?php
                                                                                        if ($user_bank->id_proof != '') {
                                                                                            echo '<img src="' . base_url('uploads/' . $user_bank->id_proof) . '" class="img-responsive" style="max-width:200px;"><br>';
                                                                                        } else {
                                                                                            echo '<img src="' . base_url('uploads/' . $user_bank->id_proof) . '" alt="no-image" class="img-responsive" style="max-width:20px;"><br>';
                                                                                            echo '<span class="wanki">Not Uploaded</span>';
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="uploaded-docs-table-btn pr0">
                                                                                    <div class="loader"></div>
                                                                                    <?php
                                                                                    if ($user_bank->kyc_status != 2)
                                                                                        echo '<input type="submit" class="btn btn-primary thgy" value="upload"> ';
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <?php echo form_close(); ?>
                                                                    <?php echo form_open_multipart(base_url('Dashboard/User/UploadProof/'), array('method' => 'post', 'class' => 'proofForm')); ?>
                                                                    <table class="table table-layout-fixed uploaded-docs-table" width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="uploaded-docs-table-name">
                                                                                    <span class="document-verify-step1 lead mb0">
                                                                                        <i class="ti-user color-light-blue" style="color: #007aff;"></i>
                                                                                        Aadhar Card Back
                                                                                    </span>
                                                                                </td>

                                                                                <td class="uploaded-docs-table-status">
                                                                                    <div class="verification-img" id="ImgID">
                                                                                        <input type="file" name="userfile" class="" placeholder="" /><br>
                                                                                        <input type="hidden" name="proof_type" value="id_proof2" /><br>
                                                                                        <?php
                                                                                        if ($user_bank->id_proof2 != '') {
                                                                                            echo '<img src="' . base_url('uploads/' . $user_bank->id_proof2) . '" class="img-responsive" style="max-width:200px;"><br>';
                                                                                        } else {
                                                                                            echo '<img src="' . base_url('uploads/' . $user_bank->id_proof2) . '" alt="no-image" class="img-responsive" style="max-width:20px;"><br>';
                                                                                            echo '<span class="wanki">Not Uploaded</span>';
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="uploaded-docs-table-btn pr0">
                                                                                    <div class="loader"></div>
                                                                                    <?php
                                                                                    if ($user_bank->kyc_status != 2)
                                                                                        echo '<input type="submit" class="btn btn-primary thgy" value="upload"> ';
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <?php echo form_close(); ?>
                                                                    <?php echo form_open_multipart(base_url('Dashboard/User/UploadProof/'), array('method' => 'post', 'class' => 'proofForm')); ?>
                                                                    <table class="table table-layout-fixed uploaded-docs-table" width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="uploaded-docs-table-name">
                                                                                    <span class="document-verify-step1 lead mb0">
                                                                                        <i class="ti-user color-light-blue" style="color: #007aff;"></i>
                                                                                        Pan Card
                                                                                    </span>
                                                                                </td>

                                                                                <td class="uploaded-docs-table-status">
                                                                                    <div class="verification-img" id="ImgID">
                                                                                        <input type="file" name="userfile" class="" placeholder="" /><br>
                                                                                        <input type="hidden" name="proof_type" value="id_proof3" /><br>
                                                                                        <?php
                                                                                        if ($user_bank->id_proof3 != '') {
                                                                                            echo '<img src="' . base_url('uploads/' . $user_bank->id_proof3) . '" class="img-responsive" style="max-width:200px;"><br>';
                                                                                        } else {
                                                                                            echo '<img src="' . base_url('uploads/' . $user_bank->id_proof3) . '" alt="no-image" class="img-responsive" style="max-width:20px;"><br>';
                                                                                            echo '<span class="wanki">Not Uploaded</span>';
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="uploaded-docs-table-btn pr0">
                                                                                    <div class="loader"></div>
                                                                                    <?php
                                                                                    if ($user_bank->kyc_status != 2)
                                                                                        echo '<input type="submit" class="btn btn-primary thgy" value="upload"> ';
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <?php echo form_close(); ?>
                                                                    <?php echo form_open_multipart(base_url('Dashboard/User/UploadProof/'), array('method' => 'post', 'class' => 'proofForm')); ?>
                                                                    <table class="table table-layout-fixed uploaded-docs-table" width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="uploaded-docs-table-name">
                                                                                    <span class="document-verify-step1 lead mb0">
                                                                                        <i class="ti-user color-light-blue" style="color: #007aff;"></i>
                                                                                        Bank Passbook/Cancel Check
                                                                                    </span>
                                                                                </td>

                                                                                <td class="uploaded-docs-table-status">
                                                                                    <div class="verification-img" id="ImgID">
                                                                                        <input type="file" name="userfile" class="" placeholder="" /><br>
                                                                                        <input type="hidden" name="proof_type" value="id_proof4" /><br>
                                                                                        <?php
                                                                                        if ($user_bank->id_proof4 != '') {
                                                                                            echo '<img src="' . base_url('uploads/' . $user_bank->id_proof4) . '" class="img-responsive" style="max-width:200px;"><br>';
                                                                                        } else {
                                                                                            echo '<img src="' . base_url('uploads/' . $user_bank->id_proof4) . '" alt="no-image" class="img-responsive" style="max-width:20px;"><br>';
                                                                                            echo '<span class="wanki">Not Uploaded</span>';
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="uploaded-docs-table-btn pr0">
                                                                                    <div class="loader"></div>
                                                                                    <?php
                                                                                    if ($user_bank->kyc_status != 2)
                                                                                        echo '<input type="submit" class="btn btn-primary thgy" value="upload"> ';
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <?php echo form_close(); ?>
                                                                    <tr>
                                                                        <td colspan="6">
                                                                            <span id="sta">
                                                                                <div class="alert alert-danger alert-rounded">Please Upload your Id &amp; Address Proof for Profile Documents Verification
                                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                                        <span aria-hidden="true" style="position: relative;top: -5px;">×</span>
                                                                                    </button>
                                                                                </div>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                    </table>
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
                    </div>
                </div>
                <!-- BEGIN col-4 -->

                <!-- END col-4 -->
            </div>
            <!-- END col-8 -->
        </div>
        <!-- END row -->
    </div>


</div>

<?php include_once 'footer.php'; ?>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).on('click', '#btnCopy', function() {
        //linkTxt
        var copyText = document.getElementById("linkTxt");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/
        /* Copy the text inside the text field */
        document.execCommand("copy");
        /* Alert the copied text */
        alert("Link Copied : " + copyText.value);
    })
    $("form.proofForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url = $(this).attr('action');
        var t = $(this);
        t.find('.loader').css('display', 'block');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(data) {
                var res = JSON.parse(data)
                alert(res.message);
                $("form.proofForm").append('<input type="hidden" name="' + res.csrfName + '" value="' + res.csrfHash + '" style="display:none;">')
                $("form.pswrdrst").append('<input type="hidden" name="' + res.csrfName + '" value="' + res.csrfHash + '" style="display:none;">')
                $("form#bankform").append('<input type="hidden" name="' + res.csrfName + '" value="' + res.csrfHash + '" style="display:none;">')
                t.find('.loader').css('display', 'none');
                if (res.success == 1) {
                    t.find('.verification-img img').attr('src', res.image)
                    t.find('span.wanki').remove();
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
    $("#bankform").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url = $(this).attr('action');
        var t = $(this);
        t.find('.loader').css('display', 'block');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(data) {
                var res = JSON.parse(data)
                alert(res.message);
                $("form.proofForm").append('<input type="hidden" name="' + res.csrfName + '" value="' + res.csrfHash + '" style="display:none;">')
                $("form.pswrdrst").append('<input type="hidden" name="' + res.csrfName + '" value="' + res.csrfHash + '" style="display:none;">')
                $("form#bankform").append('<input type="hidden" name="' + res.csrfName + '" value="' + res.csrfHash + '" style="display:none;">')
                t.find('.loader').css('display', 'none');
                if (res.success == 1) {}
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
    $(document).on('submit', '.pswrdrst', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url = $(this).attr('action');
        var formData = $(this).serialize();
        $.post(url, formData, function(res) {
            alert(res.message);
            $("form.proofForm").append('<input type="hidden" name="' + res.csrfName + '" value="' + res.csrfHash + '" style="display:none;">')
            $("form.pswrdrst").append('<input type="hidden" name="' + res.csrfName + '" value="' + res.csrfHash + '" style="display:none;">')
            $("form#bankform").append('<input type="hidden" name="' + res.csrfName + '" value="' + res.csrfHash + '" style="display:none;">')
            // if(res.success == 1){
            //     document.getElementById("pswrdrst").reset();
            // }
        }, 'json')
    })

    $.get('<?php echo base_url("Assets/banks.json") ?>', function(res) {
        var html = '<option value="">Choose your bank</option>';
        var bank_name = '<?php echo $user_bank->bank_name; ?>';
        $.each(res, function(key, value) {
            html += '<option value="' + value + '" ' + (value == bank_name ? 'selected' : '') + '>' + key + '</option>';
        })
        $("#txtBakName").html(html);
    }, 'json')

    $(document).on('change', '#bnktoggle', function() {
        $('#bankform').toggle();
        $('#btcForm').toggle();
    })



    // function getMultiOtp(){
    //     document.getElementById('get_otp').style.display = 'none';
    //     document.getElementById('resend_otp').style.display = 'block';
    //     document.getElementById('submit').style.display = 'block';
    //     document.getElementById('enter_otp').style.display = 'block';

    //     // $(document).on('click','#otp',function(){
    //         var url = '<?php echo base_url('Dashboard/Settings/getOtp'); ?>'
    //         $.get(url,function(res){
    //             if(res.status == 1){
    //                 //$("#otp").css("display", "none");
    //                 alert('OTP send to registered email');
    //             }else{
    //                 alert('Network error,please try later');
    //             }
    //         },'JSON')
    //     // })
    // }
</script>
<script>
    $(document).on('click', '#otp', function() {
        var url = '<?php echo base_url('Dashboard/secureWithdraw/getOtpMail'); ?>'
        $.get(url, function(res) {
            if (res.status == 1) {
                $("#otp").css("display", "none");
                $("#submit1").css("display", "block");
                $("#enter_otp").css("display", "block");

                alert('OTP send to registered E-mail and Phone Number');
            } else {
                alert('Network error,please try later');
            }
        }, 'JSON')
    })
</script>