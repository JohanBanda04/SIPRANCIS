<!-- Main content -->
<div class="content-wrapper">
    <!-- Content area -->
    <div class="content">

        <!-- Dashboard content -->
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
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

                        <form class="form-horizontal" action="" data-parsley-validate="true" method="post" enctype="multipart/form-data">
                            <div class="alert alert-success">
                                <strong>Note :</strong> Pastikan Berkas Cuti Anda Sudah Lengkap.!
                            </div>
                            <br>

                            <!-- Alasan Cuti -->
                            <div class="form-group">
                                <label class="col-lg-12">Alasan Cuti <span class="text-danger">*</span></label>
                                <div class="col-lg-12">
                                    <input type="text" name="alasan_cuti" class="form-control"
                                           placeholder="Alasan Cuti" required autofocus>
                                </div>
                            </div>

                            <!-- Pemohon Cuti -->
                            <div class="form-group">
                                <label class="col-lg-12">Pemohon Cuti <span class="text-danger">*</span></label>
                                <div class="col-lg-12">
                                    <select class="form-control default-select2" name="id_pemohon" id="id_pemohon" required>
                                        <!--<option value="">- Pilih -</option>-->
                                        <?php foreach ($getNotaris as $not): ?>
                                            <option value="<?= $not->id_user ?>"
                                                <?= ($this->session->userdata('id_user') == $not->id_user) ? 'selected="selected"' : 'disabled' ?>>
                                                <?= $not->nama ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>
                            <!-- Tanggal Cuti -->
                            <div class="form-group">
                                <!-- Kolom kiri: Tanggal Awal -->
                                <div class="col-lg-4">
                                    <label>Tanggal Awal Cuti <span class="text-danger">*</span></label>
                                    <div class="input-group date" id="tgl_awal_picker">
                                        <input type="text" class="form-control" name="tgl_awal_cuti" placeholder="YYYY-MM-DD" required/>
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                </div>

                                <!-- Kolom tengah: Tanggal Akhir -->
                                <div class="col-lg-4">
                                    <label>Tanggal Akhir Cuti <span class="text-danger">*</span></label>
                                    <div class="input-group date" id="tgl_akhir_picker">
                                        <input type="text" class="form-control" name="tgl_akhir_cuti" placeholder="YYYY-MM-DD" required/>
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                </div>

                                <!-- Kolom kanan: Jumlah Hari -->
                                <div class="col-lg-4">
                                    <label>Jumlah Hari Cuti</label>
                                    <input type="text" class="form-control" id="jumlah_hari" name="jumlah_hari" readonly>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div class="form-group">
                                <label class="col-lg-12">Keterangan <span class="text-danger">*</span></label>
                                <div class="col-lg-12">
                                    <textarea name="ket_laporan" class="form-control" rows="4" placeholder="Keterangan..." required></textarea>
                                </div>
                            </div>

                            <!-- Lampiran -->
                            <div class="form-group">
                                <label class="col-lg-12">Surat Permohonan Cuti
                                    <span class="text-danger">
                                        <small style="font-style: italic">berisi alasan cuti, lama waktu cuti, dan nama Notaris Pengganti</small>
                                    </span>
                                </label>
                                <div class="col-lg-12">
                                    <input type="file" name="surat_permohonan_cuti" class="form-control" accept=".pdf,.jpg,.png" required>
                                    <small class="text-muted">Format diperbolehkan: PDF, JPG, PNG</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-12">SK Pengangkatan/Perpindahan Notaris
                                    <span class="text-danger">
                                        <small style="font-style: italic">*</small>
                                    </span>
                                </label>
                                <div class="col-lg-12">
                                    <input type="file" name="sk_pengangkatan_notaris" class="form-control" accept=".pdf,.jpg,.png" required>
                                    <small class="text-muted">Format diperbolehkan: PDF, JPG, PNG</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-12">Berita Acara Sumpah
                                    <span class="text-danger">
                                        <small style="font-style: italic">*</small>
                                    </span>
                                </label>
                                <div class="col-lg-12">
                                    <input type="file" name="berita_acara_sumpah" class="form-control" accept=".pdf,.jpg,.png" required>
                                    <small class="text-muted">Format diperbolehkan: PDF, JPG, PNG</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-12">Sertifikat Cuti Asli
                                    <span class="text-danger">
                                        <small style="font-style: italic">*</small>
                                    </span>
                                </label>
                                <div class="col-lg-12">
                                    <input type="file" name="sertifikat_cuti_asli" class="form-control" accept=".pdf,.jpg,.png" required>
                                    <small class="text-muted">Format diperbolehkan: PDF, JPG, PNG</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-12">Dokumen Penunjukan Notaris Pengganti
                                    <span class="text-danger">
                                        <small style="font-style: italic">Dokumen berisikan : Ijazah Sarjana Hukum,KTP,SKCK,Surat Keterangan Sehat,Pasphoto 3x4 latar biru,Daftar CV,Surat keterangan bekerja min. 24 bulan</small>
                                    </span>
                                </label>
                                <div class="col-lg-12">
                                    <input type="file" name="surat_penunjukan_notaris_pengganti" class="form-control" accept=".pdf,.jpg,.png" required>
                                    <small class="text-muted">Format diperbolehkan: PDF, JPG, PNG</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-12">Dokumen Pendukung Lainnya
                                    <span class="text-danger">
                                        <small style="font-style: italic">Dokumen berisikan : (contoh : Capture Pembayaran PNBP..dan lain lain)</small>
                                    </span>
                                </label>
                                <div class="col-lg-12">
                                    <input type="file" name="lamp_syarat_cuti" class="form-control" accept=".pdf,.jpg,.png" required>
                                    <small class="text-muted">Format diperbolehkan: PDF, JPG, PNG</small>
                                </div>
                            </div>

                            <hr>
                            <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>.html" class="btn btn-default"><< Kembali</a>
                            <button type="submit" name="btnsimpan" class="btn btn-primary" style="float:right;">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /dashboard content -->

    </div>
</div>


<script>
    $(document).ready(function(){
        // Inisialisasi datetimepicker
        $('#tgl_awal_picker').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false
        });
        $('#tgl_akhir_picker').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false
        });

        // Agar tgl akhir tidak bisa lebih kecil dari tgl awal
        $("#tgl_awal_picker").on("dp.change", function (e) {
            $('#tgl_akhir_picker').data("DateTimePicker").minDate(e.date);
            hitungHari();
        });
        $("#tgl_akhir_picker").on("dp.change", function (e) {
            $('#tgl_awal_picker').data("DateTimePicker").maxDate(e.date);
            hitungHari();
        });

        // Hitung jumlah hari cuti
        function hitungHari(){
            let tglAwal = $('#tgl_awal_picker input').val();
            let tglAkhir = $('#tgl_akhir_picker input').val();

            if(tglAwal !== "" && tglAkhir !== ""){
                let start = moment(tglAwal, "YYYY-MM-DD");
                let end = moment(tglAkhir, "YYYY-MM-DD");
                let diff = end.diff(start, 'days') + 1; // termasuk hari awal
                if(diff > 0){
                    $('#jumlah_hari').val(diff + " hari");
                } else {
                    $('#jumlah_hari').val("");
                }
            }
        }
    });
</script>
