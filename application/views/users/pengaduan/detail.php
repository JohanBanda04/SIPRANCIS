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
                $level 	= $this->session->userdata('level');
                ?>
              <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%">
                  <tbody>
                    <tr>
                      <th valign="top" width="160">Nama Pelapor</th>
                      <th valign="top" width="1">:</th>
                      <td><?php echo $this->Mcrud->d_pelapor($query->user,'nama_pelapor'); ?></td>
                    </tr>
                    <tr>
                      <th valign="top">Kategori Aduan</th>
                      <th valign="top">:</th>
                      <td><?php echo $this->Mcrud->d_pelapor($query->id_kategori,'kategori'); ?></td>
                    </tr>
                    <tr>
                      <th valign="top">Sub Kategori Aduan</th>
                      <th valign="top">:</th>
                      <td><?php echo $this->Mcrud->d_pelapor($query->id_sub_kategori,'sub_kategori'); ?></td>
                    </tr>
                    <tr>
                      <th valign="top">Waktu Pengaduan</th>
                      <th valign="top">:</th>
                      <td><?php echo $this->Mcrud->tgl_id(date('d-m-Y H:i:s', strtotime($query->tgl_pengaduan)),'full'); ?></td>
                    </tr>
                    <tr>
                      <th valign="top">Uraian Aduan</th>
                      <th valign="top">:</th>
                      <td><?php echo $query->isi_pengaduan; ?></td>
                    </tr>
                    <tr>
                      <th valign="top">Keterangan</th>
                      <th valign="top">:</th>
                      <td><?php echo $query->ket_pengaduan; ?></td>
                    </tr>
                    <tr>
                      <th valign="top">Lampiran</th>
                      <th valign="top">:</th>
                      <td><?php echo $query->pesan_petugas; ?></td>
                    </tr>
                    <tr>
                      <th valign="top">Lampiran Pedukung</th>
                      <th valign="top">:</th>
                      <td>
                        <a href="<?php echo $query->file_petugas; ?>" target="_blank"><?php echo $query->file_petugas; ?></a>
                      </td>
                    </tr>
                    <tr>
                      <th valign="top">File Pendukung</th>
                      <th valign="top">:</th>
                      <td>
                        <a href="<?php echo $query->bukti; ?>" target="_blank"><?php echo $query->bukti; ?></a>
                      </td>
                    </tr>
                    <tr>
                      <th valign="top">Status</th>
                      <th valign="top">:</th>
                      <td><?php echo $this->Mcrud->cek_status($query->status); ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <hr style="margin-top:0px;">
              <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>.html" class="btn btn-default"><< Kembali</a>
              <?php if ($level=='superadmin'){ ?>
                <?php if ($query->status=='proses'){ ?>
                   <a href="javascript:;" class="btn btn-primary" title="Konfirmasi" data-toggle="modal" onclick="modal_show(<?php echo $query->id_pengaduan; ?>);" style="float:right;"><i class="fa fa-file"></i> Konfirmasi</a>
                <?php }else{ ?>
                  <a href="javascript:;" class="btn btn-success" title="Terkonfirmasi" disabled style="float:right;"><i class="fa fa-check"></i> konfirmasi</a>
                <?php } ?>
              <?php }elseif ($level=='petugas'){ ?>
                <?php //if ($query->status=='konfirmasi'){ ?>
                   <a class="btn btn-success" title="Edit" data-toggle="modal" onclick="modal_show(<?php echo $query->id_pengaduan; ?>);" style="float:right;"><i class="fa fa-pencil"></i> Edit</a>
                <?php //}else{ ?>
                  <!-- <a href="javascript:;" class="btn btn-success btn-xs" title="Edit" disabled><i class="fa fa-check"></i> Edit</a> -->
                <?php //} ?>
              <?php } ?>
            </div>

        </div>
      </div>
    </div>
    <!-- /dashboard content -->

    <?php $this->load->view('users/pengaduan/modal_konfirm'); ?>
