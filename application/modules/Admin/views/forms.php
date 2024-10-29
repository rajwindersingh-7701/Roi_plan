<?php
include_once 'header.php';
(empty($export) ? $export = false : $export = $export);
(empty($script) ? $script = false : $script = $script);
(empty($popup) ? $popup = false : $popup = $popup);
(empty($message) ? $message = 'message' : $message = $message);
?>
<style>
    .btn_popup {
        margin: 0;
        padding: 0;
        width: 87px;
        height: 38px;
        border: 2px solid black;
        display: inline-block;
        vertical-align: middle;
        text-align: center;
        border-radius: 1.2em;
        position: relative;
        transition: background .15s ease-in-out;
    }

    .btn_popup:after {
        content: "";
        position: absolute;
        height: 30px;
        width: 30px;
        border-radius: 50%;
        top: .075em;
        border: 1px solid #777;
        transition: left .15s ease-in-out;
        will-change: left;
    }

    label {
        display: block;
        margin-bottom: .25em;
    }

    .btn_popup[aria-pressed="false"] {
        background: #ccc;
    }

    .btn_popup[aria-pressed="false"]:after {
        background: red;
        left: 2.5em;
    }

    .btn_popup[aria-pressed="true"] {
        background: #ccc;
    }

    .btn_popup[aria-pressed="true"]:after {
        background: green;
        left: .1em;
    }

    .toggle {
        width: 135px;
    }
</style>
<div class="main-content main_content_new">
    <div class="page-content">
        <div class="container-fluid">
            <!-- all page subHeader  -->
            <div class="row m-0 mb-4 mt-1 new__sec">
                <div class="col-12">
                    <div class="sub__header">
                        <h5 class="m-0 d-flex text-dark starte__txt"><?php echo $header; ?> </h5>
                        <ol class="breadcrumb float-sm-right mb-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard') ?>">Home</a></li>
                        </ol>
                    </div>
                </div>
            </div>
            <div>
                <div class="row">
                    <div class="col-md-12 d-flex align-items-center justify-content-start">
                        <div class="card w-100 h-auto">
                            <div class="card-header text-center">
                            </div>
                            <div class="card-body w-50">
                                <?php echo $form_open ?>
                                <span><?php echo $this->session->flashdata($message); ?></span> <!-- Form Message  -->
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
    <?php
    include_once 'footer.php';
    if ($script == true) {
    ?>
        <script>
            $(document).on('blur', '#user_id', function() {
                var user_id = $(this).val();
                var url = '<?php echo base_url("get_user/") ?>' + user_id;
                $.get(url, function(res) {
                    console.log(res);
                    $('#errorMessageForm').html(res);
                })
            })
        </script>
    <?php
    }
    if ($popup == true) {
    ?>
        <script>
            async function ceheckstatus() {
                var url = "<?php echo base_url('Admin/Settings/getStatus/'); ?>";
                $.get(url, function(res) {
                    console.log(res);
                    document.getElementById("statusValue").value = res.data.status;
                }, 'json');
            }
        </script>
        <script>
            var toggles = document.querySelectorAll(".toggle");

            for (i = 0, l = toggles.length; i < l; i++) {
                var tog = toggles[i];
                tog.addEventListener("click", async function(e) {
                    await ceheckstatus();

                    // alert('Are you sure for this action')
                    var dataName = document.getElementById('statusValue').value;
                    if (dataName == 0) {
                        var sendCode = "true";
                    } else {
                        var sendCode = "false";
                    }

                    var el = this.querySelector("button"),
                        state = el.getAttribute("aria-pressed") == sendCode,
                        val = state ? "OFF" : "ON",
                        realInput = this.querySelector("input")
                    if (state == true) {
                        var status = 1;
                    } else {
                        var status = 0;
                    }
                    var url = "<?php echo base_url('Admin/Settings/popupSetting/'); ?>" + status;
                    $.get(url, function(res) {}, 'json')

                    el.setAttribute("aria-pressed", state ? "false" : "true");
                    el.setAttribute("aria-checked", state ? "false" : "true");

                    el.nextElementSibling.innerHTML = val;
                    realInput.value = val;
                });
            }
        </script>
    <?php } ?>