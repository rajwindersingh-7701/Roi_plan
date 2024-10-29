<footer id="contact" class="iq-footer">
    <div class="footer-top iq-mtb-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4 mb-lg-0">
                    <div class="logo">
                        <img id="logo_img_2" class="img-fluid" src="<?php echo base_url('Assets/');?>image/logo.png" alt="# ">
                        <div class="text-white iq-mt-15 ">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12 mb-4 mb-lg-0 footer-menu">
                    <h5 class="small-title iq-tw-5 text-white">Menu</h5>
                    <ul class="iq-pl-0">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Faqs</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 iq-contact mb-4 mb-lg-0">
                    <h5 class="small-title iq-tw-5 text-white">Contact <?php echo title;?> </h5>
                    <div class="iq-mb-30">
                        <div class="blog"><i class="ion-ios-telephone-outline"></i>
                            <div class="content ">
                                <div class="iq-tw-6 title ">Phone</div> +0123 456 789</div>
                        </div>
                    </div>
                    <div class="iq-mb-30">
                        <div class="blog "><i class="ion-ios-email-outline"></i>
                            <div class="content ">
                                <div class="iq-tw-6 title ">Mail</div> mail@<?php echo title;?> .com</div>
                        </div>
                    </div>
                    <div class="blog"><i class="ion-ios-location-outline"></i>
                        <div class="content ">
                            <div class="iq-tw-6 title ">Address</div> 1234 North Luke Lane, South Bend,IN 360001</div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4 mb-lg-0">
                    <div class="call-back">
                        <h5 class="small-title iq-tw-5 text-white">Request a Call Back</h5>
                        <form>
                            <div class="form-group iq-mb-20">
                                <input type="text" class="form-control" id="exampleInputName" placeholder="Enter Name">
                            </div>
                            <div class="form-group iq-mb-20">
                                <input type="text" class="form-control" id="exampleInputPhone" placeholder="Phone Number">
                            </div>
                            <div class="form-group iq-mb-20">
                                <input type="text" class="form-control" id="exampleInputsubject" placeholder="Subject">
                            </div>
                            <a class="button" href="javascript:void(0)">Submit</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom iq-ptb-20 ">
        <div class="container">
            <div class="row">
                <div class="col-md-6 align-self-center">
                    <div class="iq-copyright text-white">© Copyright 2023 <?php echo title;?>  </div>
                </div>
                <div class="col-md-6">
                    <ul class="iq-media-blog ">
                        <li><a href="#"><i class="fa fa-twitter "></i></a></li>
                        <li><a href="#"><i class="fa fa-facebook "></i></a></li>
                        <li><a href="#"><i class="fa fa-google "></i></a></li>
                        <li><a href="#"><i class="fa fa-github "></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!--================================= Footer -->
<div class="modal fade iq-login" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content blue-bg">
            <div class="modal-header text-center">
                <h4 class="modal-title ">Login</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="contact-form">
                    <div class="section-field">
                        <input class="require" id="contact_name" type="text" placeholder="Name*" name="name">
                    </div>
                    <div class="section-field">
                        <input class="require" id="contact_email" type="email" placeholder="Email*" name="email">
                    </div>
                    <a class="button iq-mtb-10" href="javascript:void(0)">Sign In</a>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-sm-6 text-end">
                            <a href="javascript:void(0)">Forgot Password</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <div> Don't Have an Account? <a href="javascript:void(0)" class="iq-font-yellow">Register Now</a></div>
                <ul class="iq-media-blog iq-mt-20">
                    <li><a href="#"><i class="fa fa-twitter "></i></a></li>
                    <li><a href="#"><i class="fa fa-facebook "></i></a></li>
                    <li><a href="#"><i class="fa fa-google "></i></a></li>
                    <li><a href="#"><i class="fa fa-github "></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- back-to-top -->
<div id="back-to-top" class="animate__animated animate__fadeIn">
    <a class="top" id="top" href="#top"><i class="fa fa-angle-double-up" aria-hidden="true"></i> </a>
</div>
<!-- back-to-top End -->
<!-- bubbly -->
<canvas id="canvas1"></canvas>
<!-- bubbly End -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="<?php echo base_url('Assets/');?>js/jquery-min.js"></script>
<!-- popper JavaScript -->
<script src="<?php echo base_url('Assets/');?>js/popper.min.js"></script>
<!-- canvas -->
<script src="<?php echo base_url('Assets/');?>js/canvas2.js"></script>
<!-- Bootstrap JavaScript -->
<script src="<?php echo base_url('Assets/');?>js/bootstrap.min.js"></script>
<!-- All-plugins JavaScript -->
<script src="<?php echo base_url('Assets/');?>js/all-plugins.js"></script>
<!-- timeline JavaScript -->
<script src="<?php echo base_url('Assets/');?>js/timeline.min.js"></script>
<!-- canvas JavaScript -->
<script src="<?php echo base_url('Assets/');?>js/canvasjs.min.js"></script>
<script src="<?php echo base_url('Assets/');?>js/particles1.min.js"></script>
<script src="<?php echo base_url('Assets/');?>js/app.js"></script>
<script src="<?php echo base_url('Assets/');?>js/stats.js"></script>
<!-- amcharts -->
<script src="<?php echo base_url('Assets/');?>js/amcharts.js"></script>
<script src="<?php echo base_url('Assets/');?>js/serial.js"></script>
<script src="<?php echo base_url('Assets/');?>js/export.min.js"></script>
<script src="<?php echo base_url('Assets/');?>js/none.js"></script>
<script src="<?php echo base_url('Assets/');?>js/pie.js"></script>
 <!-- carousel JavaScript -->
 <script src="<?php echo base_url('Assets/');?>js/owl.carousel.min.js"></script>
<!-- Custom JavaScript -->
<script src="<?php echo base_url('Assets/');?>js/custom.js"></script>


</body></html>