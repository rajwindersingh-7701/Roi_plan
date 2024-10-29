<?php include_once 'header.php'; ?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<style>
    .btn_toggle {
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

    .btn_toggle:after {
        content: "";
        position: absolute;
        height: 30px;
        width: 30px;
        border-radius: 50%;
        background: red;
        top: .075em;
        border: 1px solid #777;
        left: 50px;
        transition: left .15s ease-in-out;
        will-change: left;
    }

    label {
        display: block;
        margin-bottom: .25em;
    }

    .btn_toggle[aria-pressed="false"] {
        background: #ccc;
    }

    .btn_toggle[aria-pressed="false"]:after {
        background: red;
        left: 3.5em;
    }

    .btn_toggle[aria-pressed="true"] {
        background: #ccc;
    }

    .btn_toggle[aria-pressed="true"]:after {
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


            <div class="row m-0 mb-4 mt-1 new__sec ">
                <div class="col-md-6">
                    <div class="sub__header">
                        <h5 class="m-0 text-dark starte__txt">Starter Page</h5>
                        <h5 class="mb-0 text-success"> <?php echo $this->session->flashdata('process'); ?></h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="sub__header">
                        <h5 class="m-0 text-dark">SMS Left: <?php echo smslimit ?></h5>
                        <a href="<?php echo base_url('DeveloperMode'); ?>" class="btn"> Refresh</a>
                    </div>

                </div>
            </div>

            <!-- end page title -->

            <div class="row card__row">
                <!-- <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <label for=""><?php //echo $acs; 
                                            ?></label>
                            <div class="<?php //echo $key; 
                                        ?>"><button class="btn_toggle" role="<?php //echo $key; 
                                                                                ?>" value="<?php //echo $setting[$key]; 
                                                                                            ?>" aria-pressed="<?php //($setting[$key] == 1 ? 'false' : 'true') 
                                                                                                                ?>" aria-checked="true" id="<?php //echo $key; 
                                                                                                                                            ?>" aria-describedby="<?php //echo $key; 
                                                                                                                                                                    ?>"></button>
                                <span id="state" aria-live="assertive" aria-atomic="true"><?php //($setting[$key] == 1 ? 'OFF' : 'ON') 
                                                                                            ?></span><input type="hidden" aria-hidden="true" value="ON">
                            </div>
                        </div>
                    </div>
                </div> -->
                <?php
                $access = $this->config->item('access');
                foreach ($access as $key => $acs) :
                    if ($key == 'registration') {
                        $on = 'Now Binary ON';
                        $off = 'Now Simple ON';
                    } elseif ($key == 'withdraw') {
                        $on = 'Now Wallet';
                        $off = 'Now Bank';
                    } elseif ($key == 'activation_process') {
                        $on = 'Now Package';
                        $off = 'Now Amount';
                    } elseif ($key == 'fund_process') {
                        $on = 'Now Deposit Fund';
                        $off = 'Now Fund Request';
                    } else {
                        $on = 'ON';
                        $off = 'OFF';
                    }
                ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <label class="text-<?php echo ($setting[$key] == 0 ? 'success' : 'danger') ?>"><?php echo $acs; ?></label>
                                <?php if ($setting[$key] == 0) { ?>
                                    <a class="btn btn-danger" href="<?php echo base_url('DeveloperMode/UpdateButton/' . $key . '/1') ?>"><?php echo $off; ?></a>
                                <?php  } else { ?>
                                    <a class="btn btn-success" href="<?php echo base_url('DeveloperMode/UpdateButton/' . $key . '/0') ?>"><?php echo $on; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php include_once 'footer.php';  ?>

    <script>
        async function ceheckstatus() {
            var url = "<?php echo base_url('DeveloperMode/getStatus/'); ?>";
            $.get(url, function(res) {
                document.getElementById("direct_access").value = res.data.direct_access;
            }, 'json');
        }
    </script>
    <script>
        let text = "";
        const access = ["direct_access", "level_access", "roi_access"];
        access.forEach(accessFunction);

        function accessFunction(item, index) {
            var toggles = document.querySelectorAll("." + item);

            for (i = 0, l = toggles.length; i < l; i++) {
                var tog = toggles[i];
                tog.addEventListener("click", async function(e) {

                    // console.log(item);
                    var dataName = document.getElementById(item).value;
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
                    console.log(el);
                    var url = "<?php echo base_url('DeveloperMode/DevSetting/'); ?>" + item + "/" + status;
                    $.get(url, function(res) {}, 'json')

                    el.setAttribute("aria-pressed", state ? "false" : "true");
                    el.setAttribute("aria-checked", state ? "false" : "true");

                    el.nextElementSibling.innerHTML = val;
                    realInput.value = val;

                });
            }
        }
    </script>