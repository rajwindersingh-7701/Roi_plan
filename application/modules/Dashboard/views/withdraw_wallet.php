<?php
include_once 'header.php';
date_default_timezone_set('Asia/Kolkata');
$bankinfo = bankinfo();
$userinfo = userinfo();
?>

<div class="main-content app-content mt-0">
    <div class="container-fluid">
        <div class="">
            <div class="panel-heading">
                <h4 class="panel-title"> Direct Income Withdraw</h4>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card cstm-card">
                        <div class="card-body p-0">
                            <div id="rootwizard" class="wizard-full-width border-0">
                                <div class="wizard-content tab-content p-0">
                                    <!-- BEGIN tab-pane -->
                                    <div class="tab-pane active show" id="tabFundRequestForm">
                                        <!-- BEGIN row -->
                                        <div class="row">
                                            <!-- BEGIN col-6 -->
                                            <?php if (withdraw_status == 0) { ?>
                                                <div class="col-md-12">

                                                    <?php
                                                    // if (date('D') == 'Wed') {
                                                    // if(date('H:i') >= '10:00' && date('H:i') <= '16:00'): 

                                                    echo form_open('', array('id' => 'TopUpForm'));
                                                    ?>
                                                    <div class="form-group">
                                                        <span class="text-success">Available balance (<?php echo currency. round($balance['balance'], 2); ?>)</span>
                                                    </div>
                                                    <?php echo $this->session->flashdata('withdraw_wallet_message'); ?>

                                                    <div class="form-group">
                                                        <label>Amount</label>
                                                        <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" value="<?php echo set_value('amount'); ?>" onkeyup="calculateAmount(this)" />
                                                        <span class="text-danger"><?php echo form_error('amount') ?></span>
                                                        <!-- <span class="text-danger" id="payableAmount"></span> -->
                                                    </div>

                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>TRC20 USDT Address</label>
                                                        <input type="text" class="form-control" name="wallet_address" value="<?php echo $userinfo->eth_address; ?>" id="wallet_address" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Transaction Pin</label>
                                                        <input type="password" class="form-control" name="master_key" placeholder="Transaction Key" value="" />
                                                        <span class="text-danger"><?php echo form_error('master_key') ?></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <?php echo (!empty($userinfo->eth_address) ? ' <button type="subimt" name="save" class="btn btn-info" />Withdrawal Now</button>' : '<a href="' . base_url('dashboard/zil-update') . '" class="btn btn-danger" />Please Update Wallet Address</a>'); ?>
                                                </div>
                                            <?php
                                                echo form_close();
                                            } else {
                                                echo '<span class="text-danger">Withdraw Closed By Admin !</span>';
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
        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>
<script>
    function calculateAmount(evt) {
        var amount = evt.value;
        var token = "<?php echo $tokenValue['amount']; ?>";
        document.getElementById('payableAmount').innerHTML = 'You will get Coin ' + amount / token;
    }

    $(document).on('click', '#otp', function() {
        var url = '<?php echo base_url('Dashboard/secureWithdraw/getOtp'); ?>'
        $.get(url, function(res) {
            if (res.status == 1) {
                $("#otp").css("display", "none");
                alert('OTP send to registered mobile number');
            } else {
                alert('Network error,please try later');
            }
        }, 'JSON')
    })
</script>
<script>
    $(document).on('blur', '#user_id', function() {
        var user_id = $('#user_id').val();
        if (user_id != '') {
            var url = '<?php echo base_url("Dashboard/get_app_user/") ?>' + user_id;
            $.get(url, function(res) {
                if (res.success == 1) {
                    $('#errorMessage').html(res.user.name);
                } else {
                    $('#errorMessage').html(res.message);
                }

            }, 'json')
        }
    })
    $(document).on('submit', '#TopUpForm', function() {
        if (confirm('Are You Sure U want to Withdraw This Account')) {
            yourformelement.submit();
        } else {
            return false;
        }
    })


    function total_hub(evt) {
        var tokenValue = "<?php echo $tokenValue['amount']; ?>";
        var amount = evt.value;
        //console.log(tokenValue)
        document.getElementById('coinGet').innerHTML = 'Estimated Value in ' + amount / tokenValue + ' JW';
    }
</script>



<script src="<?php echo base_url('SmartChain/web3.min.js'); ?>"></script>

<script type="text/javascript" src="https://unpkg.com/web3modal@1.9.0/dist/index.js"></script>
<script type="text/javascript" src="https://unpkg.com/evm-chains@0.2.0/dist/umd/index.min.js"></script>
<!-- <script src="<?php  // echo base_url('SmartChain/config.js'); 
                    ?>"></script> -->
<script src="<?php echo base_url('SmartChain/config.js'); ?>"></script>
<script src="<?php echo base_url('SmartChain/binance.js'); ?>"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />




<script>
    let addressInfo = "";
    let account = "";
    let web3Modal;
    let provider;
    let selectedAccount;


    function init() {
        const providerOptions = {};

        web3Modal = new Web3Modal({
            cacheProvider: false, // optional
            providerOptions, // required
        });

        //console.log(web3Modal);



    }


    async function onConnect() {
        try {
            provider = await web3Modal.connect();
        } catch (e) {
            toastr.info("Could not get a wallet connection!");
        }

        provider.on("accountsChanged", (accounts) => {
            fetchAccountData();
        });

        provider.on("chainChanged", (chainId) => {
            fetchAccountData();
        });

        provider.on("networkChanged", (networkId) => {
            fetchAccountData();
        });

        await refreshAccountData();
    }


    async function refreshAccountData() {
        await fetchAccountData(provider);
    }


    async function fetchAccountData() {
        // if (window.ethereum) {
        //     web3 = new Web3(window.ethereum);
        //     try { 
        //         window.ethereum.enable().then(function() {
        //             // User has allowed account access to DApp...
        //         });
        //     } catch(e) {
        //         // User has denied account access to DApp...
        //     }

        // }else if (window.web3) {
        //     const web3 = new Web3(provider);
        // }
        const web3 = new Web3(provider);
        const chainId = await web3.eth.getChainId();
        const chainData = evmChains.getChain(chainId);
        const accounts = await web3.eth.getAccounts();
        const selectedAccount = accounts[0];
        if (selectedAccount) {

            // BUSD BALANCE
            const contract_1 = new web3.eth.Contract(busd_abi,
                busd_contract, {
                    from: selectedAccount
                });
            const balance1 = await contract_1.methods.balanceOf(selectedAccount)
                .call();
            // const humanFriendlyBalance = parseFloat(balance).toFixed(4);
            const symbol1 = await contract_1.methods.symbol().call();

            const busd_balance = (balance1 / 1000000000000000000);


            // TOKEN BALANCE
            const contract_3 = new web3.eth.Contract(token_abi,
                token_contract, {
                    from: selectedAccount
                });
            const balance3 = await contract_3.methods.balanceOf(selectedAccount)
                .call();
            // const humanFriendlyBalance = parseFloat(balance).toFixed(4);
            const symbol3 = await contract_3.methods.symbol().call();
            console.log(balance3);
            const token_balance = (balance3 / 100000000);


            // BNB BALANCE

            const symbol2 = 'BNB';
            const get_system_balance = await web3.eth.getBalance(selectedAccount);
            console.log(get_system_balance);
            const bnb_balance = get_system_balance / 1000000000000000000;

            //    document.getElementById('walletBalance').innerHTML = symbol1+busd_balance+' | '+symbol2+' '+bnb_balance + ' | '+symbol3+' '+token_balance;

            document.getElementById('wallet_address').value = selectedAccount;
            //    document.getElementById('bnb_balance').innerHTML = bnb_balance;
            //    document.getElementById('jw_balance').innerHTML = token_balance;
            toastr.info("Wallet connected successfully!");
        }
        // await Promise.all(rowResolvers);
    }




    setTimeout(() => {
        init();
        onConnect();
    }, 1e3)
</script>