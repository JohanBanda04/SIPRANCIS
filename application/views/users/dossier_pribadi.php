<!-- Custom CSS biar tombol choose file full lebar -->
<style>
    .table td input[type="file"] {
        display: block;
        width: 100% !important;   /* paksa full width */
        max-width: 100%;          /* biar ga overflow */
        box-sizing: border-box;   /* padding dihitung ke dalam */
    }
</style>
<!-- Main content -->
<div class="content-wrapper">
    <!-- Content area -->
    <div class="content">

        <!-- Dashboard content -->
        <div class="row">
            <div class="col-md-12">
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
                        <?php echo $this->session->flashdata('msg'); ?>

                        <form class="form-horizontal" action=""
                              data-parsley-validate="true" method="post"
                              enctype="multipart/form-data" style="margin-bottom: 50px;">

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th style="width:20%">Jenis File</th>
                                        <th style="width:60%">Upload</th> <!-- perbesar biar lega -->
                                        <th style="width:20%">File</th>
                                        <th hidden style="width:20%">Aturan</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $getData = $this->db->get_where("tbl_user_hasfile", array('user_id' => $this->session->userdata('id_user')))->row()??"";
                                    $getKtp = $getData->ktp_path??"Belum Ada";
                                    $getIjazahSH = $getData->ijazah_sh_path??"Belum Ada";
                                    $getIjazahMagister = $getData->ijazah_magister_path??"Belum Ada";
                                    $getNpwp = $getData->npwp_path??"Belum Ada";
                                    $getPengangkatan = $getData->kepmenkum_pengangkatan_path??"Belum Ada";
                                    $getBaSumpah = $getData->berita_acara_sumpah_path??"Belum Ada";
                                    $getPindahWil = $getData->kepmenkum_pindah_wilayah_path??"Belum Ada";
                                    $getBaPindah = $getData->berita_acara_sumpah_pindah_path??"Belum Ada";
                                    //echo "<pre>"; print_r($getData); die;
                                    ?>
                                    <tr>
                                        <td><b>KTP</b></td>
                                        <td>
                                            <input type="file" name="ktp_path" class="form-control">
                                        </td>
                                        <td>

                                            <label for="">
                                                <a href="<?php echo $getKtp; ?>" target="_blank">
                                                    <?php echo explode('/',$getKtp)[2]??"Belum Ada"; ?>
                                                </a>
                                            </label>

                                        </td>
                                        <td hidden>
                                            <small style="color: red; font-weight: bold">
                                                Maks: <?= $file_size ?> KB<br>
                                                Ext: <?= $allowed_types ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Ijazah SH</b></td>
                                        <td>
                                            <input type="file" name="ijazah_sh_path" class="form-control">
                                        </td>
                                        <td>

                                            <label for="">
                                                <a href="<?php echo $getIjazahSH; ?>" target="_blank">
                                                    <?php echo explode('/',$getIjazahSH)[2]??"Belum Ada"; ?>
                                                </a>
                                            </label>

                                        </td>
                                        <td hidden>
                                            <small style="color: red; font-weight: bold">
                                                Maks: <?= $file_size ?> KB<br>
                                                Ext: <?= $allowed_types ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Ijazah Magister</b></td>
                                        <td>
                                            <input type="file" name="ijazah_magister_path" class="form-control">
                                        </td>
                                        <td>

                                            <label for="">
                                                <a href="<?php echo $getIjazahMagister; ?>" target="_blank">
                                                    <?php echo explode('/',$getIjazahMagister)[2]??"Belum Ada"; ?>
                                                </a>
                                            </label>
                                        </td>
                                        <td hidden>
                                            <small style="color: red; font-weight: bold">
                                                Maks: <?= $file_size ?> KB<br>
                                                Ext: <?= $allowed_types ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>NPWP</b></td>
                                        <td>
                                            <input type="file" name="npwp_path" class="form-control">
                                        </td>
                                        <td>

                                            <label for="">
                                                <a href="<?php echo $getNpwp; ?>" target="_blank">
                                                    <?php echo explode('/',$getNpwp)[2]??"Belum Ada"; ?>
                                                </a>
                                            </label>
                                        </td>
                                        <td hidden>
                                            <small style="color: red; font-weight: bold">
                                                Maks: <?= $file_size ?> KB<br>
                                                Ext: <?= $allowed_types ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Keputusan Menteri Hukum tentang Pengangkatan Notari</b></td>
                                        <td>
                                            <input type="file" name="kepmenkum_pengangkatan_path" class="form-control">
                                        </td>
                                        <td>

                                            <label for="">
                                                <a href="<?php echo $getPengangkatan; ?>" target="_blank">
                                                    <?php echo explode('/',$getPengangkatan)[2]??"Belum Ada"; ?>
                                                </a>
                                            </label>
                                        </td>
                                        <td hidden>
                                            <small style="color: red; font-weight: bold">
                                                Maks: <?= $file_size ?> KB<br>
                                                Ext: <?= $allowed_types ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>berita acara sumpah pengangkatan Notaris/ surat keterangan dari kantor wilayah tempat kedudukan Notari</b></td>
                                        <td>
                                            <input type="file" name="berita_acara_sumpah_path" class="form-control">
                                        </td>
                                        <td>

                                            <label for="">
                                                <a href="<?php echo $getBaSumpah; ?>" target="_blank">
                                                    <?php echo explode('/',$getBaSumpah)[2]??"Belum Ada"; ?>
                                                </a>
                                            </label>
                                        </td>
                                        <td hidden>
                                            <small style="color: red; font-weight: bold">
                                                Maks: <?= $file_size ?> KB<br>
                                                Ext: <?= $allowed_types ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Keputusan Menteri Hukum tentang pindah wilayah jabatan Notaris; (jika ada)</b></td>
                                        <td>
                                            <input type="file" name="kepmenkum_pindah_wilayah_path" class="form-control">
                                        </td>
                                        <td>

                                            <label for="">
                                                <a href="<?php echo $getPindahWil; ?>" target="_blank">
                                                    <?php echo explode('/',$getPindahWil)[2]??"Belum Ada"; ?>
                                                </a>
                                            </label>
                                        </td>
                                        <td hidden>
                                            <small style="color: red; font-weight: bold">
                                                Maks: <?= $file_size ?> KB<br>
                                                Ext: <?= $allowed_types ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>B.A. Sumpah pindah wilayah jabatan Notaris/ surat keterangan dari kantor wilayah tempat kedudukan Notaris; (jika ada)</b></td>
                                        <td>
                                            <input type="file" name="berita_acara_sumpah_pindah_path" class="form-control">
                                        </td>
                                        <td>

                                            <label for="">
                                                <a href="<?php echo $getBaPindah; ?>" target="_blank">
                                                    <?php echo explode('/',$getBaPindah)[2]??"Belum Ada"; ?>
                                                </a>
                                            </label>
                                        </td>
                                        <td hidden>
                                            <small style="color: red; font-weight: bold">
                                                Maks: <?= $file_size ?> KB<br>
                                                Ext: <?= $allowed_types ?>
                                            </small>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>

                            <button type="submit" name="btnsimpan" class="btn btn-primary" style="float:right;">Kirim</button>
                            <button type="button" id="btnHapusFileDossier" class="btn btn-danger" style="float:right; margin-right: 10px;">
                                Reset Semua File
                            </button>
                        </form>
                        <br>

                        <center><b>Link Google Drive</b></center>
                        <form class="form-horizontal" action="" method="post" id="formGdrive">
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <input type="url" name="link_gdrive" class="form-control"
                                           value="<?php echo $link_gdrive ?? ''; ?>"
                                           placeholder="Masukkan link Google Drive" >
                                </div>
                            </div>

                            <div style="float:right;">
                                <button type="submit" name="btnsimpan_gdrive" class="btn btn-success">
                                    Simpan Link
                                </button>
                                <?php if (!empty($link_gdrive)) : ?>
                                    <button type="button" id="btnHapusGdrive" class="btn btn-danger">
                                        Hapus Link
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <script>
            document.getElementById('btnHapusGdrive')?.addEventListener('click', function () {
                Swal.fire({
                    title: 'Yakin hapus link Google Drive?',
                    text: "Tindakan ini tidak bisa dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // bikin input hidden biar terdeteksi di controller
                        let form = document.getElementById('formGdrive');
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'btnhapus_gdrive';
                        input.value = '1';
                        form.appendChild(input);
                        form.submit();
                    }
                })
            });

            document.getElementById('btnHapusFileDossier')?.addEventListener('click', function () {
                Swal.fire({
                    title: 'Yakin reset semua file?',
                    text: "Semua file yang sudah diupload akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus semua',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.querySelector('form[enctype="multipart/form-data"]');
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'btnhapus_dossier';
                        input.value = '1';
                        form.appendChild(input);
                        form.submit();
                    }
                })
            });

        </script>
        <!-- /dashboard content -->
