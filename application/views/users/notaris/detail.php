<?php
$link1 = strtolower($this->uri->segment(1));
$link2 = strtolower($this->uri->segment(2));
$link3 = strtolower($this->uri->segment(3));
$link4 = strtolower($this->uri->segment(4));
?>
<!-- Main content -->
<div class="content-wrapper">
  <!-- Content area -->
  <div class="content">

    <!-- Dashboard content -->
    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title"><?php echo $judul_web; ?></h4>
            </div>
            <div class="panel-body">
                <?php
                echo $this->session->flashdata('msg');
                $foto = "img/user/user-default.jpg";
                $foto_k = $query->foto;
              	if ($foto_k!='') {
              		if(file_exists("$foto_k")){
              			$foto = $foto_k;
              		}
              	}
                ?>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped" width="100%">
                    <tbody>
                      <tr>
                        <th colspan="3">
                          <center><img src="<?php echo $foto; ?>" alt="" width="100"></cneter>
                        </th>
                      </tr>
                      <tr>
                        <th width="135">No.Reg Notaris</th>
                        <th width="1">:</th>
                        <th><?php echo $query->no_idn; ?></th>
                      </tr>
                      <tr>
                        <th>N a m a</th>
                        <th>:</th>
                        <th><?php echo $query->nama; ?></th>
                      </tr>
                      <tr>
                        <th>Nomor SK</th>
                        <th>:</th>
                        <th><?php echo $query->no_sk; ?></th>
                      </tr>
                      <tr>
                        <th>Alamat</th>
                        <th>:</th>
                        <th><?php echo $query->alamat_notaris; ?></th>
                      </tr>
                      <tr>
                        <th>Tempat Kedudukan</th>
                        <th>:</th>
                        <th><?php echo $query->tempat_kedudukan; ?></th>
                      </tr>
                      <tr>
                        <th>No. Telp</th>
                        <th>:</th>
                        <th><?php echo $query->telpon; ?></th>
                      </tr>
                      <tr>
                        <th>Email</th>
                        <th>:</th>
                        <th><?php echo $query->email_notaris; ?></th>
                      </tr>
                      <tr>
                        <th>Username</th>
                        <th>:</th>
                        <th><?php echo $query->username; ?></th>
                      </tr>
                      <tr>
                        <th>Tanggal Terdaftar</th>
                        <th>:</th>
                        <th><?php echo $this->Mcrud->tgl_id(date('d-m-Y',strtotime($query->tgl_daftar)),'full'); ?></th>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <hr style="margin-top:0px;">
                <a href="<?php echo $link1; ?>/<?php echo $link2; ?>.html" class="btn btn-default"><< Kembali</a>
            </div>

        </div>
      </div>
    </div>
    <!-- /dashboard content -->
