<?php
include_once 'header.php';
$userinfo = userinfo();
$bankinfo = bankinfo();

(empty($extra_header) ? $extra_header = false : $extra_header = $extra_header);
(empty($message) ? $message = 'message' : $message = $message);
(empty($script) ? $script = false : $script = $script);
?>
<?php
$getpak = get_single_record('tbl_emi_plan',['user_id' => $this->session->userdata['user_id']],'*');
 ?>

<div class="main-content app-content mt-0">
    <div class="container-fluid">
        <div class="">
            <?php 
            if (!empty($getpak)) {
             if ($getpak['emi_close'] == 0) {
                // code...
             ?>
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $header; ?></h4>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card cstm-card">
                        <div class="card-body">
                            <div>
                            <?php

                             if ($extra_header == true) { ?>
                                <p class="text-success"><?php echo $header2 ?> <span id=""><?php echo number_format($balance['balance'], 2) ?></span></p>
                            <?php } 
                 $EMiTotal = $getEMIpending['totalEmi']+$getEMIpending['totalTax'];
                 if(date('d') >=1 && date('d') <=5){
                    $EMiTotal = $EMiTotal;
                 }elseif(date('d') >= 6){
                    $addTex = $getEMIpending['amount']*0.05;
                     $EMiTotal = $EMiTotal+$addTex;
                 }


                            ?>
                            <div>
                               <!--  <h5 class="text-white">Total EMI Amount : <?php echo $total_package ; ?></h5>
                                <h5 class="text-white">Pending EMI Amount : <?php echo $pendig_package ; ?></h5>
                                 -->
                                 <h5 class="text-white">Current EMI Amount : <?php echo $EMiTotal; ?></h5>
                            </div>
                            </div>

                            <div class="wizard-content tab-content p-0">
                                <div class="tab-pane active show" id="tabFundRequestForm">
                                    <div>
                                        <div class="col-md-12 p-0">

                                            <span><?php echo $this->session->flashdata($message); 
                                            $this->session->unset_userdata($message);
                                            
                                        ?></span> <!-- Form Message  -->
                                        <?php if(date('d') >= 1 && date('d') <= 10) { ?>
                                            <?php echo $form_open ?> <!-- Form Open Path  -->

                                            <?php foreach ($form as $key => $value) { ?> <!-- Form Foreach Loop -->
                                                <div class="form-group"> <?php echo $value; ?></div>
                                                <span class="text-danger"><?php echo form_error($key); ?></span>
                                            <?php  } ?>

                                            <?php foreach ($form_button as $key => $btn) { ?> <!-- Button Foreach Loop -->
                                                <div class="form-group">
                                                    <?php echo $btn; ?>
                                                </div>
                                            <?php  } ?>
                                            <?php echo form_close();
                                            }else{
                                                echo '<H1 class="text-danger"> EMI Date is 1 To 10 <H1> ';
                                            } 
                                       
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <?php
}else{ 
echo '<H1 class="text-danger"> Your EMI Closed ..! <H1> ';
}
            } else {
            echo '<H1 class="text-danger">Before Select Plan..! <H1> ';

        }
?>
        </div>
    </div>
</div>



<?php include_once 'footer.php'; ?>
<?php if ($script == true) { ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        $(document).on('blur', '#user_id', function() {
            check_sponser();
        })

        function check_sponser() {
            var user_id = $('#user_id').val();
            if (user_id != '') {
                var url = '<?php echo base_url("Dashboard/UserInfo/get_user/") ?>' + user_id;
                $.get(url, function(res) {
                    // $("#errorMessage").css("display", "block");
                    $('#errorMessage').html(res);
                })
            }
        }
        check_sponser();

        $(document).on('blur', '#amount', function() {
            payableAmount();
        })

        function payableAmount() {
            var amount = $('#amount').val();
            if (amount != '') {
                var payable = Number(amount) * 0.90;
                document.getElementById('payableAmount').innerHTML = 'payable amount ' + payable;
            }
        }
        payableAmount();

        $(document).on('click', '#otp', function() {
            var url = '<?php echo base_url('Dashboard/secureWithdraw/getOtp'); ?>'
            $.get(url, function(res) {
                if (res.status == 1) {
                    $("#otp").css("display", "none");
                    $("#updateProfile").css("display", "block");
                    $("#otp_input").css("display", "block");
                    alert('Testing OTP Button');
                } else {
                    alert('Network error,please try later');
                }
            }, 'JSON')
        })
    </script>
<?php } ?>