<?php
$user = $user->roW();
$level = $user->level;
?>
<!-- Main content -->
<div class="content-wrapper">

  <!-- Content area -->
  <div class="content">

    <!-- Dashboard content -->
    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-6">
      <div class="panel panel-flat">
          <div class="panel-body">
            <fieldset class="content-group">
              <legend class="text-bold"><i class="icon-key"></i> Ubah Password</legend>
              <?php
              echo $this->session->flashdata('msg2');
              ?>
              <form class="form-horizontal" action="" method="post" data-parsley-validate="true">
                <div class="form-group">
                  <label class="control-label col-lg-3">Password Lama</label>
                  <div class="col-lg-9">
                    <input type="password" name="password0" class="form-control" value="" placeholder="Password Lama" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-lg-3">Password</label>
                  <div class="col-lg-9">
                    <input type="password" name="password" class="form-control" value="" placeholder="Password" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-lg-3">Ulangi Password</label>
                  <div class="col-lg-9">
                    <input type="password" name="password2" class="form-control" value="" placeholder="Ulangi Password" required>
                  </div>
                </div>
                <hr>
                <!-- <div class="col-md-12"> -->
                  <a href="profile" class="btn btn-info">Profile</a>
                  <button type="submit" name="btnupdate2" class="btn btn-primary" style="float:right;">Simpan</button>
                <!-- </div> -->
            </fieldset>
          </form>
          </div>
      </div>
      </div>

    </div>
    <!-- /dashboard content -->
