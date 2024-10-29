<?php include 'header.php' ?>
<div class="main-content main_content_new">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row m-0 mb-4 mt-1 new__sec">
        <div class="col-12">
          <section class="sub__header">
            <h5 class="m-0 text-dark starte__txt">
              <span class="">Edit User</span>
            </h5>
            <ol class="breadcrumb float-sm-right mb-0 bg-transparent">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit User</li>
            </ol>
          </section>
        </div><!-- /.col -->
      </div><!-- /.row -->
      <div class="row">
        <div class="col-md-6">
          <!-- <div class="card form-cards">
            <div class="card-header">
              Update Address
            </div>
            /.card-header
            <div class="card-body pt-0">
              <h5><?php echo $this->session->flashdata('addressMessage'); ?></h5>
              <?php echo form_open(); ?>
              <div class="form-group">
                <label>TrustWallet Address</label>
                <input type="text" name="eth_address" class="form-control" value="<?php echo $user['eth_address']; ?>" />
                <label class="text-danger"><?php echo form_error('eth_address'); ?></label>
                <input type="hidden" name="form_type" class="form-control" value="walletAddress" />
              </div>
              <div class="form-group for-button">
                <button type="submit" class="btn btn-success pull-right">Update</button>
              </div>
              <?php echo form_close(); ?>
            </div>
          </div> -->
          <div class="card form-cards">
            <div class="card-header">
              Password Manager
            </div>
            <!-- /.card-header -->
            <div class="card-body pt-0">
              <h5><?php echo $this->session->flashdata('password_message'); ?></h5>
              <?php echo form_open(); ?>
              <div class="form-group">
                <label>New Passowrd</label>
                <input type="text" name="password" class="form-control" value="<?php echo $user['password']; ?>" />
                <label class="text-danger"><?php echo form_error('password'); ?></label>
                <input type="hidden" name="form_type" class="form-control" value="password" />
              </div>
              <div class="form-group for-button">
                <button type="submit" class="btn btn-success pull-right">Update</button>
              </div>
              <?php echo form_close(); ?>
            </div>
          </div>
          <div class="card form-cards">
            <div class="card-header">
              Transaction Password Manager
            </div>
            <!-- /.card-header -->
            <div class="card-body pt-0">
              <h5><?php echo $this->session->flashdata('trx_message'); ?></h5>
              <?php echo form_open(); ?>
              <div class="form-group">
                <label>New Transaction Passowrd</label>
                <input type="text" name="master_key" class="form-control" value="<?php echo $user['master_key']; ?>" />
                <label class="text-danger"><?php echo form_error('master_key'); ?></label>
                <input type="hidden" name="form_type" class="form-control" value="master_key" />
              </div>
              <div class="form-group for-button">
                <button type="submit" class="btn btn-success pull-right">Update</button>
              </div>
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card for-hight">
            <div class="card-body p-0">
              <div class="card-header">
                Personal Details
              </div>
              <!-- /.card-header -->
              <div class="card-body pt-0">
                <h5><?php echo $this->session->flashdata('personal_message'); ?></h5>
                <?php echo form_open(); ?>
                <div class="form-group">
                  <label>Name</label>
                  <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" />
                  <label class="text-danger"><?php echo form_error('name'); ?></label>
                </div>
                <div class="form-group">
                  <label>Phone</label>
                  <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" />
                  <label class="text-danger"><?php echo form_error('phone'); ?></label>
                </div>
             <!--    <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" />
                  <label class="text-danger"><?php // echo form_error('email'); ?></label>
                  <input type="hidden" name="form_type" class="form-control" value="personal" />
                </div> -->
                <div class="form-group">
                  <label>Directs</label>
                  <input type="text" name="direct" class="form-control" value="<?php echo $user['directs']; ?>" />
                  <label class="text-danger"><?php echo form_error('direct'); ?></label>
                  <input type="hidden" name="form_type" class="form-control" value="personal" />
                </div>
                <div class="form-group">
                  <label>Left Power</label>
                  <input type="text" name="leftPower" class="form-control" value="<?php echo $user['leftPower']; ?>" />
                  <label class="text-danger"><?php echo form_error('leftPower'); ?></label>
                </div>
                <div class="form-group">
                  <label>Right Power</label>
                  <input type="text" name="rightPower" class="form-control" value="<?php echo $user['rightPower']; ?>" />
                  <label class="text-danger"><?php echo form_error('rightPower'); ?></label>
                </div>
                <div class="form-group for-button">
                  <button type="submit" class="btn btn-success pull-right">Update</button>
                </div>
                <?php echo form_close(); ?>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 ">
          <div class="card">
            <div class="card-header">
              Bank Details
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <h5><?php echo $this->session->flashdata('bank_message'); ?></h5>
              <?php echo form_open(); ?>
              <div class="form-group">
                <label>Account Holder Name</label>
                <input type="text" name="account_holder_name" class="form-control" value="<?php echo $user['bank']['account_holder_name']; ?>" />
                <label class="text-danger"><?php echo form_error('account_holder_name'); ?></label>
              </div>
              <div class="form-group">
                <label>Bank Name</label>
                <input type="text" name="bank_name" class="form-control" value="<?php echo $user['bank']['bank_name']; ?>" />
                <label class="text-danger"><?php echo form_error('bank_name'); ?></label>
              </div>
              <div class="form-group">
                <label>Bank Account Number</label>
                <input type="text" name="bank_account_number" class="form-control" value="<?php echo $user['bank']['bank_account_number']; ?>" />
                <label class="text-danger"><?php echo form_error('bank_account_number'); ?></label>
              </div>
              <div class="form-group">
                <label>Branch Address</label>
                <input type="text" name="branch_name" class="form-control" value="<?php echo $user['bank']['branch_name']; ?>" />
                <label class="text-danger"><?php echo form_error('branch_name'); ?></label>
              </div>
              <div class="form-group">
                <label>IFSC Code</label>
                <input type="text" name="ifsc_code" class="form-control" value="<?php echo $user['bank']['ifsc_code']; ?>" />
                <input type="hidden" name="form_type" class="form-control" value="bank_details" />
                <label class="text-danger"><?php echo form_error('ifsc_code'); ?></label>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-success pull-right">Update</button>
              </div>
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">

        </div>
        <div class="col-md-6">

        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php' ?>