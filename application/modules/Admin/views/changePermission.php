<?php include 'header.php';

$none = 0;
?>
<div class="main-content main_content_new">
  <div class="page-content">
    <div class="container-fluid">
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
        <div class="card">
        <div class="row">
          <div class="col-md-12">
            <?php echo $this->session->flashdata('per_mission_message'); ?>
          </div>
          <div class="col-md-4">
            <?php echo form_open(); ?>
            <h5 class="border-bottom mt-3">Members</h5>
            <div class="form-check">
              <label> <input type="checkbox" name="userdetail/access" value="userdetail/access" <?php if (!empty($access['userdetail/access'])) echo 'checked'; ?> class="form-check-input">User Detail Dropdown</label>
            </div>

            <div class="form-check">
              <label> <input type="checkbox" name="admin/users" value="admin/users" <?php if (!empty($access['admin/users'])) echo 'checked'; ?> class="form-check-input">All Members</label>
            </div>

            <div class="form-check">
              <label> <input type="checkbox" name="admin/user-login" value="admin/user-login" <?php if (!empty($access['admin/user-login'])) echo 'checked'; ?> class="form-check-input">User Login</label>
            </div>

            <div class="form-check">
              <label> <input type="checkbox" name="admin/edit-user" value="admin/edit-user" <?php if (!empty($access['admin/edit-user'])) echo 'checked'; ?> class="form-check-input">Edit User</label>
            </div>

            <div class="form-check">
              <label><input type="checkbox" name="admin/paid-users" value="admin/paid-users" <?php if (!empty($access['admin/paid-users'])) echo 'checked'; ?> class="form-check-input"> Active Member </label>
            </div>
          </div>

          <div class="col-md-4">
            <h5 class="border-bottom mt-3">Settings</h5>

            <div class="form-check">
              <label> <input type="checkbox" name="settings/access" value="settings/access" <?php if (!empty($access['settings/access'])) echo 'checked'; ?> class="form-check-input">Settings Dropdown</label>
            </div>

            <div class="form-check">
              <label><input type="checkbox" name="admin/buy-price" value="admin/buy-price" <?php if (!empty($access['admin/buy-price'])) echo 'checked'; ?> class="form-check-input">Buy Price </label>
            </div>

            <div class="form-check">
              <label><input type="checkbox" name="admin/sell-value" value="admin/sell-value" <?php if (!empty($access['admin/sell-value'])) echo 'checked'; ?> class="form-check-input">Sell Price </label>
            </div>

            <div class="form-check">
              <label>
                <input type="checkbox" name="admin/news" value="admin/news" <?php if (!empty($access['admin/news'])) echo 'checked'; ?> class="form-check-input">News </label>
            </div>

            <div class="form-check">
              <label><input type="checkbox" name="admin/create-news" value="admin/create-news" <?php if (!empty($access['admin/create-news'])) echo 'checked'; ?> class="form-check-input">Create News </label>
            </div>

            <div class="form-check">
              <label><input type="checkbox" name="admin/edit-news" value="admin/edit-news" <?php if (!empty($access['admin/edit-news'])) echo 'checked'; ?> class="form-check-input">Edit News </label>
            </div>

            <div class="form-check">
              <label>
                <input type="checkbox" name="admin/delete-news" value="admin/delete-news" <?php if (!empty($access['admin/delete-news'])) echo 'checked'; ?> class="form-check-input">Delete News </label>
            </div>

            <div class="form-check">
              <label>
                <input type="checkbox" name="admin/popup" value="admin/popup" <?php if (!empty($access['admin/popup'])) echo 'checked'; ?> class="form-check-input">Upload Popup Image </label>
            </div>
          </div>

          <div class="col-md-4">
            <h5 class="border-bottom mt-3">Income Report</h5>
            <div class="form-check">
              <label> <input type="checkbox" name="incomes/access" value="incomes/access" <?php if (!empty($access['incomes/access'])) echo 'checked'; ?> class="form-check-input">Incomes Dropdown</label>
            </div>
            <?php
            $IncForeach = $this->config->item('incomes');
            foreach ($IncForeach as $Inckey => $IncName) { ?>
              <div class="form-check">
                <label><input type="checkbox" name="admin/incomes/<?php echo $Inckey; ?>" value="admin/incomes/<?php echo $Inckey; ?>" <?php if (!empty($access['admin/incomes/' . $Inckey])) echo 'checked'; ?> class="form-check-input"><?php echo $IncName; ?> </label>
              </div>
            <?php } ?>

            <div class="form-check">
              <label> <input type="checkbox" name="admin/income-ledgar" value="admin/income-ledgar" <?php if (!empty($access['admin/income-ledgar'])) echo 'checked'; ?> class="form-check-input">Income Ledger </label>
            </div>

            <div class="form-check">
              <label> <input type="checkbox" name="admin/payout-summary" value="admin/payout-summary" <?php if (!empty($access['admin/payout-summary'])) echo 'checked'; ?> class="form-check-input">Payout Summary </label>
            </div>
            <div class="form-check">
              <label> <input type="checkbox" name="admin/dateWisePayout" value="admin/dateWisePayout" <?php if (!empty($access['admin/dateWisePayout'])) echo 'checked'; ?> class="form-check-input">Date Wise Payout </label>
            </div>

          </div>
          <div class="col-md-4">
            <h5 class="border-bottom mt-3">KYC</h5>
            <div class="form-check">
              <label> <input type="checkbox" name="kyc/access" value="kyc/access" <?php if (!empty($access['kyc/access'])) echo 'checked'; ?> class="form-check-input">KYC Dropdown</label>
            </div>
            <div class="form-check">
              <label><input type="checkbox" name="admin/kyc-history/allrequest" value="admin/kyc-history/allrequest" <?php if (!empty($access['admin/kyc-history/allrequest'])) echo 'checked'; ?> class="form-check-input">All KYC </label>
            </div>
            <div class="form-check">
              <label><input type="checkbox" name="admin/kyc-history/pending" value="admin/kyc-history/pending" <?php if (!empty($access['admin/kyc-history/pending'])) echo 'checked'; ?> class="form-check-input">Pending KYC </label>
            </div>

            <div class="form-check">
              <label><input type="checkbox" name="admin/kyc-history/approved" value="admin/kyc-history/approved" <?php if (!empty($access['admin/kyc-history/approved'])) echo 'checked'; ?> class="form-check-input">Approved KYC </label>
            </div>

            <div class="form-check">
              <label><input type="checkbox" name="admin/kyc-history/rejected" value="admin/kyc-history/rejected" <?php if (!empty($access['admin/kyc-history/rejected'])) echo 'checked'; ?> class="form-check-input">Rejected KYC </label>
            </div>

          </div>

          <div class="col-md-4">
            <h5 class="border-bottom mt-3">Withdraw</h5>

            <div class="form-check">
              <label> <input type="checkbox" name="withdraw/access" value="withdraw/access" <?php if (!empty($access['withdraw/access'])) echo 'checked'; ?> class="form-check-input">Withdraw Dropdown</label>
            </div>

            <div class="form-check">
              <label><input type="checkbox" name="admin/withdraw-history/allrequest" value="admin/withdraw-history/allrequest" <?php if (!empty($access['admin/withdraw-history/allrequest'])) echo 'checked'; ?> class="form-check-input"> All Withdraw</label>
            </div>
            <div class="form-check">
              <label><input type="checkbox" name="admin/withdraw-history/pending" value="admin/withdraw-history/pending" <?php if (!empty($access['admin/withdraw-history/pending'])) echo 'checked'; ?> class="form-check-input"> Pending Withdraw</label>
            </div>
            <div class="form-check">
              <label><input type="checkbox" name="admin/withdraw-history/approved" value="admin/withdraw-history/approved" <?php if (!empty($access['admin/withdraw-history/approved'])) echo 'checked'; ?> class="form-check-input"> Approved Withdraw</label>
            </div>
            <div class="form-check">
              <label><input type="checkbox" name="admin/withdraw-history/rejected" value="admin/withdraw-history/rejected" <?php if (!empty($access['admin/withdraw-history/rejected'])) echo 'checked'; ?> class="form-check-input"> Rejected Withdraw</label>
            </div>

          </div>
          <div class="col-md-4">
            <h5 class="border-bottom mt-3">Mail</h5>

            <div class="form-check">
              <label> <input type="checkbox" name="mail/access" value="mail/access" <?php if (!empty($access['mail/access'])) echo 'checked'; ?> class="form-check-input">Mail Dropdown </label>
            </div>
            <div class="form-check">
              <label> <input type="checkbox" name="admin/inbox" value="admin/inbox" <?php if (!empty($access['admin/inbox'])) echo 'checked'; ?> class="form-check-input">Inbox </label>
            </div>

            <div class="form-check">
              <label> <input type="checkbox" name="admin/compose" value="admin/compose" <?php if (!empty($access['admin/compose'])) echo 'checked'; ?> class="form-check-input">Compose Mail </label>
            </div>

            <div class="form-check">
              <label><input type="checkbox" name="admin/outbox" value="admin/outbox" <?php if (!empty($access['admin/outbox'])) echo 'checked'; ?> class="form-check-input"> Outbox </label>
            </div>
            <div class="form-check">
              <label><input type="checkbox" name="admin/support-view" value="admin/support-view" <?php if (!empty($access['admin/support-view'])) echo 'checked'; ?> class="form-check-input"> Support View </label>
            </div>

          </div>
        </div>
        <div class="form-group">
          <?php echo form_submit(['type' => 'submit', 'value' => 'Permission', 'class' => 'btn btn-primary']); ?>
          <a href="<?php echo base_url('admin/permissions'); ?>" class="btn btn-dark">Go back</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo form_close(); ?>
</div>
<?php include 'footer.php' ?>