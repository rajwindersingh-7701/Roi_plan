<footer class="footer">
    <!-- To the right -->
    <!-- Default to the left -->
    <div><strong>Copyright &copy; <?php echo date('Y'); ?></strong> <?php echo title; ?> All rights reserved.</div>
</footer>

</div>
<!-- end main content-->

</div>
</div>
<!-- END layout-wrapper -->

<!-- Right Sidebar -->

<!-- /Right-bar -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- JAVASCRIPT -->
<script src="<?php echo base_url('NewDashboard/') ?>assets/libs/jquery/jquery.min.js"></script>
<script src="<?php echo base_url('NewDashboard/') ?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('NewDashboard/') ?>assets/libs/metismenu/metisMenu.min.js"></script>
<script src="<?php echo base_url('NewDashboard/') ?>assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?php echo base_url('NewDashboard/') ?>assets/libs/node-waves/waves.min.js"></script>

<!-- Peity chart-->
<script src="<?php echo base_url('NewDashboard/') ?>assets/libs/peity/jquery.peity.min.js"></script>

<!-- Plugin Js-->
<script src="<?php echo base_url('NewDashboard/') ?>assets/libs/chartist/chartist.min.js"></script>
<script src="<?php echo base_url('NewDashboard/') ?>assets/libs/chartist-plugin-tooltips/chartist-plugin-tooltip.min.js"></script>

<script src="<?php echo base_url('NewDashboard/') ?>assets/js/pages/dashboard.init.js"></script>

<script src="<?php echo base_url('NewDashboard/') ?>assets/js/app.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script>
    //$("#tableView").DataTable();
    $(document).ready(function() {
        $('#tableView').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>

</body>

</html>