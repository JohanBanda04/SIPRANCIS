<?php
$user = $user->row();
$level = $user->level;

$foto_profile = "img/user/user-default.jpg";
if ($level=='user') {
	$d_k = $this->db->get_where('tbl_data_user', array('id_user'=>$user->id_user))->row();
	$foto_k = $d_k->foto;
	if ($foto_k!='') {
		if(file_exists("$foto_k")){
			$foto_profile = $foto_k;
		}
	}
}
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
              <center>
                <img src="<?php echo $foto_profile; ?>" alt="<?php echo $user->nama_lengkap; ?>" class="img-circle" width="176">
              </center>
            <br>
            <fieldset class="content-group">
              <hr style="margin-top:0px;">
              <i class="icon-calendar"></i> <b>Tanggal Terdaftar</b> : <?php echo $this->Mcrud->tgl_id(date('d-m-Y H:i:s', strtotime($user->tgl_daftar))); ?>
            </fieldset>
          </form>
          </div>
      </div>

      <div class="panel panel-flat">
          <div class="panel-body">
            <fieldset class="content-group">
              <legend class="text-bold"><i class="icon-user"></i>
                PROFILE
              </legend>
              <?php
              echo $this->session->flashdata('msg');
              ?>
              <form class="form-horizontal" action="" method="post" data-parsley-validate="true" enctype="multipart/form-data">
                <?php if ($level=='user'){ ?>
                  <div class="form-group">
                    <label class="control-label col-lg-3">No. KTP/NIK</label>
                    <div class="col-lg-9">
                      <input type="text" name="no_ktp" class="form-control" value="<?php echo $user->no_ktp; ?>" placeholder="No. KTP" maxlength="30" onkeypress="return hanyaAngka(event);" required autofocus onfocus="this.value=this.value">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Nama Lengkap</label>
                    <div class="col-lg-9">
                      <input type="text" name="nama_lengkap" class="form-control" value="<?php echo $user->nama_lengkap; ?>" placeholder="Nama Lengkap" maxlength="100" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Alamat</label>
                    <div class="col-lg-9">
                      <input type="text" name="alamat" class="form-control" value="<?php echo $user->alamat; ?>" placeholder="Alamat" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Tanggal Lahir</label>
                    <div class="col-lg-9">
                      <input type="text" name="tgl_lahir" id="tgl_1" class="form-control" value="<?php if($user->tgl_lahir!=''){echo date('d-m-Y',strtotime($user->tgl_lahir));} ?>" placeholder="Tanggal Lahir" maxlength="10" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Jenis Kelamin</label>
                    <div class="col-lg-9">
                      <div class="radio radio-css radio-inline radio-primary">
                        <input type="radio" name="jk" id="jk_1" value="Laki-Laki" <?php if($user->jk=='Laki-Laki'){echo "checked";} ?> required>
                        <label for="jk_1">
                          Laki-Laki
                        </label>
                      </div>
                      <div class="radio radio-css radio-inline radio-primary">
                        <input type="radio" name="jk" id="jk_2" value="Perempuan" <?php if($user->jk=='Perempuan'){echo "checked";} ?> required>
                        <label for="jk_2">
                          Perempuan
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Kontak/Telepon</label>
                    <div class="col-lg-9">
                      <input type="text" name="kontak" class="form-control" value="<?php echo $user->kontak; ?>" placeholder="Kontak/Telepon" onkeypress="return hanyaAngka(event);" maxlength="14" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Email</label>
                    <div class="col-lg-9">
                      <input type="email" name="email" class="form-control" value="<?php echo $user->email; ?>" placeholder="Email" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Username</label>
                    <div class="col-lg-9">
                      <input type="text" name="username" class="form-control" value="<?php echo $user->username; ?>" placeholder="Nama Pengguna" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Foto</label>
                    <div class="col-lg-9">
                      <input type="file" name="foto" class="form-control" placeholder="Foto">
                    </div>
                  </div>
                <?php }else{ ?>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Username</label>
                    <div class="col-lg-9">
                      <input type="text" name="username" class="form-control" value="<?php echo $user->username; ?>" placeholder="Nama Pengguna" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Nama</label>
                    <div class="col-lg-9">
                      <input type="text" name="nama_lengkap" class="form-control" value="<?php echo $user->nama_lengkap; ?>" placeholder="Nama Lengkap" maxlength="100" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Level</label>
                    <div class="col-lg-9">
                      <input type="text" name="" class="form-control" value="<?php echo $level; ?>" placeholder="Level User" readonly>
                    </div>
                  </div>
                <?php } ?>
                <hr>
                <!-- <div class="col-md-12"> -->
                  <a href="ubah_pass" class="btn btn-info">Ubah Password</a>
                  <button type="submit" name="btnupdate" class="btn btn-primary" style="float:right;">Simpan</button>
                <!-- </div> -->
            </fieldset>

          </form>
          </div>
      </div>
      </div>


    </div>
    <!-- /dashboard content -->


        <script src="assets/js/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="assets/panel/plugin/datetimepicker/jquery.datetimepicker.css"/>
        <script src="assets/panel/plugin/datetimepicker/jquery.datetimepicker.js"></script>
        <script>
        $('#tgl_1').datetimepicker({
          lang:'en',
          timepicker:false,
          format:'d-m-Y'
        });
        </script>
