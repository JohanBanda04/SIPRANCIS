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
                      <td><?php
                          $getNamaPelapor = $this->db->get_where('tbl_user',array('id_user'=>$query->user))->row()->nama_lengkap;
                          echo $getNamaPelapor;
                          ?></td>
                    </tr>
                    <tr>
                      <th valign="top">Kategori Aduan</th>
                      <th valign="top">:</th>
                      <td><?php
                          $getKategoriAduan = $this->db->get_where('tbl_kategori',array('id_kategori'=>$query->id_kategori))->row()->nama_kategori;
                          echo $getKategoriAduan;
                          ?></td>
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
                    <tr hidden>
                        <th valign="top">Lampiran</th>
                        <th valign="top">:</th>
                        <td><?php echo $query->pesan_petugas; ?></td>
                    </tr>
                    <tr hidden>
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
                            <a href="<?php echo $query->bukti; ?>" target="_blank"><?php
                                $getFilenameOnly = explode('/',$query->bukti)[2];
                                echo $getFilenameOnly;
                                ?></a>
                        </td>
                    </tr>
                    <tr>
                        <th valign="top">Status</th>
                        <th valign="top">:</th>
                        <td><?php echo $this->Mcrud->cek_status($query->status); ?></td>
                    </tr>
                    <tr>
                        <th valign="top">Lampiran Tambahan</th>
                        <th valign="top">:</th>
                        <td>
                            <button id="btnLampiran"
                                    type="button" class="btn btn-info btn-xs"
                                    onclick="toggleLampiran()">
                                Lihat Lampiran
                            </button>
                        </td>
                    </tr>
                  </tbody>
                </table>
                  <!-- bagian lampiran tambahan (disembunyikan default) -->
                  <div id="lampiranTambahan" style="display:none; margin-top:10px;">
                      <table class="table table-bordered table-striped" width="100%">
                          <tbody>
                          <tr>
                              <th width="200">Surat Pemberitahuan</th>
                              <td>
                                  <?php if (!empty($query_tbl_aduan_hasfile->surat_pemberitahuan)) { ?>
                                      <a href="<?php echo base_url($query_tbl_aduan_hasfile->surat_pemberitahuan); ?>" target="_blank">
                                          <?php echo basename($query_tbl_aduan_hasfile->surat_pemberitahuan); ?>
                                      </a>
                                  <?php } else { echo "Belum diupload"; } ?>
                              </td>
                          </tr>
                          <tr>
                              <th>Surat Undangan</th>
                              <td>
                                  <?php if (!empty($query_tbl_aduan_hasfile->surat_undangan)) { ?>
                                      <a href="<?php echo base_url($query_tbl_aduan_hasfile->surat_undangan); ?>" target="_blank">
                                          <?php echo basename($query_tbl_aduan_hasfile->surat_undangan); ?>
                                      </a>
                                  <?php } else { echo "Belum diupload"; } ?>
                              </td>
                          </tr>
                          <tr>
                              <th>Surat Pemanggilan</th>
                              <td>
                                  <?php if (!empty($query_tbl_aduan_hasfile->surat_pemanggilan)) { ?>
                                      <a href="<?php echo base_url($query_tbl_aduan_hasfile->surat_pemanggilan); ?>" target="_blank">
                                          <?php echo basename($query_tbl_aduan_hasfile->surat_pemanggilan); ?>
                                      </a>
                                  <?php } else { echo "Belum diupload"; } ?>
                              </td>
                          </tr>
                          <tr>
                              <th>Undangan TTD BAP</th>
                              <td>
                                  <?php if (!empty($query_tbl_aduan_hasfile->undangan_ttd_bap)) { ?>
                                      <a href="<?php echo base_url($query_tbl_aduan_hasfile->undangan_ttd_bap); ?>" target="_blank">
                                          <?php echo basename($query_tbl_aduan_hasfile->undangan_ttd_bap); ?>
                                      </a>
                                  <?php } else { echo "Belum diupload"; } ?>
                              </td>
                          </tr>
                          <tr>
                              <th>BAP Pemeriksaan (Signed)</th>
                              <td>
                                  <?php if (!empty($query_tbl_aduan_hasfile->bap_pemeriksaan_has_ttd)) { ?>
                                      <a href="<?php echo base_url($query_tbl_aduan_hasfile->bap_pemeriksaan_has_ttd); ?>" target="_blank">
                                          <?php echo basename($query_tbl_aduan_hasfile->bap_pemeriksaan_has_ttd); ?>
                                      </a>
                                  <?php } else { echo "Belum diupload"; } ?>
                              </td>
                          </tr>
                          <tr>
                              <th>Surat Laporan ke MPW</th>
                              <td>
                                  <?php if (!empty($query_tbl_aduan_hasfile->surat_laporan_ke_mpw)) { ?>
                                      <a href="<?php echo base_url($query_tbl_aduan_hasfile->surat_laporan_ke_mpw); ?>" target="_blank">
                                          <?php echo basename($query_tbl_aduan_hasfile->surat_laporan_ke_mpw); ?>
                                      </a>
                                  <?php } else { echo "Belum diupload"; } ?>
                              </td>
                          </tr>
                          <tr>
                              <th>Surat Penolakan</th>
                              <td>
                                  <?php if (!empty($query_tbl_aduan_hasfile->surat_penolakan)) { ?>
                                      <a href="<?php echo base_url($query_tbl_aduan_hasfile->surat_penolakan); ?>" target="_blank">
                                          <?php echo basename($query_tbl_aduan_hasfile->surat_penolakan); ?>
                                      </a>
                                  <?php } else { echo "Belum diupload"; } ?>
                              </td>
                          </tr>
                          </tbody>
                      </table>
                  </div>

              </div>

              <hr style="margin-top:0px;">
              <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>.html" class="btn btn-default"><< Kembali</a>
              <?php if ($level=='superadmin'){ ?>
                <?php if ($query->status=='proses'){ ?>
                   <a href="javascript:;" class="btn btn-primary" title="Konfirmasi" data-toggle="modal" onclick="modal_show(<?php echo $query->id_laporan; ?>);" style="float:right;"><i class="fa fa-file"></i> Konfirmasi</a>
                <?php }else{ ?>
                  <a href="javascript:;" class="btn btn-success" title="Terkonfirmasi" disabled style="float:right;"><i class="fa fa-check"></i> konfirmasi</a>
                <?php } ?>
              <?php }elseif ($level=='petugas'){ ?>
                <?php //if ($query->status=='konfirmasi'){ ?>
                   <a class="btn btn-success" title="Edit" data-toggle="modal" onclick="modal_show(<?php echo $query->id_laporan; ?>);" style="display: none;float:right;"><i class="fa fa-pencil"></i> DEdit</a>
                <?php //}else{ ?>
                  <!-- <a href="javascript:;" class="btn btn-success btn-xs" title="Edit" disabled><i class="fa fa-check"></i> Edit</a> -->
                <?php //} ?>
              <?php } ?>
            </div>

        </div>
      </div>
    </div>
    <!-- /dashboard content -->
      <script>
          function toggleLampiran() {

              var el = document.getElementById("lampiranTambahan");
              var btn = document.getElementById("btnLampiran");


              if (el.style.display === "none") {
                  el.style.display = "block";
                  btn.innerText = "Sembunyikan Lampiran"; // ubah teks tombol
              } else {
                  el.style.display = "none";
                  btn.innerText = "Lihat Lampiran"; // kembali ke teks awal
              }
          }
      </script>
    <?php $this->load->view('users/laporan/modal_konfirm'); ?>
