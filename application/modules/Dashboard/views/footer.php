<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="row align-items-center flex-row-reverse">
            <div class="col-md-12 col-sm-12 text-center">
                Copyright © <span><?php echo date('Y'); ?></span>
                <a href="javascript:void(0)" class="custom-text-color"><?php echo title; ?></a> All rights reserved.
            </div>
        </div>
    </div>
</footer>

<!-- TOASTER LINKS -->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- FOOTER END -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script src="<?php echo base_url('Ldashboard/'); ?>js/jquery.min.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/bootstrap/js/popper.min.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>js/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>js/sticky.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>js/circle-progress.min.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/peitychart/jquery.peity.min.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/peitychart/peitychart.init.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/sidebar/sidebar.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/p-scroll/perfect-scrollbar.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/p-scroll/pscroll.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/p-scroll/pscroll-1.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/chart/Chart.bundle.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/chart/rounded-barchart.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/chart/utils.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/datatable/js/dataTables.bootstrap5.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/datatable/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>js/apexcharts.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/apexchart/irregular-data-series.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/flot/jquery.flot.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/flot/jquery.flot.fillbetween.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/flot/chart.flot.sampledata.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/flot/dashboard.sampledata.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/sidemenu/sidemenu.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>plugins/bootstrap5-typehead/autocomplete.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>js/typehead.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>js/index1.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>js/themeColors.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>js/custom.js"></script>
<script src="<?php echo base_url('Ldashboard/'); ?>switcher/js/switcher.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script>
    $("#tableView").DataTable();
</script>
<!-- <script src="js/styleSwitcher.js"></script> -->
<script type="text/javascript">
    function copyToClipboard(element) {

        var $temp = $("<input>");
        $("body").append($temp);

        $temp.val($(element).text()).select();

        document.execCommand("copy");
        alert("Referal Link copied");
        $temp.remove();
    }
</script>
<script>
    jQuery(document).ready(function() {
        setTimeout(function() {
            dezSettingsOptions.version = 'dark';
            new dezSettings(dezSettingsOptions);
        }, 200);

    });

    function carouselReview() {
        jQuery('.testimonial-one').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 20,
            nav: false,
            rtl: true,
            dots: false,
            navText: ['', ''],
            responsive: {
                0: {
                    items: 3
                },
                450: {
                    items: 4
                },
                600: {
                    items: 5
                },
                991: {
                    items: 5
                },

                1200: {
                    items: 7
                },
                1601: {
                    items: 5
                }
            }
        })
    }
    jQuery(window).on('load', function() {
        setTimeout(function() {
            carouselReview();
        }, 1000);
    });
</script>
</div>
</body>

</html>