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
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default"
                               data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success"
                               data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                               data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger"
                               data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                        <h4 class="panel-title"><?php echo $judul_web; ?></h4>
                    </div>

                    <div class="panel-body">
                        <?php echo $this->session->flashdata('msg'); ?>

                        <form class="form-horizontal" action="" data-parsley-validate="true" method="post"
                              enctype="multipart/form-data">
                            <div class="alert alert-success">
                                <strong>Note :</strong> Pastikan Berkas Cuti Anda Sudah Lengkap.!
                            </div>
                            <br>

                            <!-- Alasan Cuti -->
                            <div class="form-group">
                                <label class="col-lg-12">Alasan Cuti <span class="text-danger">*</span></label>
                                <div class="col-lg-12">
                                    <input type="text" name="alasan_cuti" class="form-control"
                                           placeholder="Alasan Cuti" required autofocus
                                           value="<?= $query->alasan ?>">
                                    <input name="id_cuti" type="hidden" value="<?= $query->id_cuti ?>">
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
                                        <input type="text" class="form-control" name="tgl_awal_cuti"
                                               placeholder="YYYY-MM-DD" required
                                               value="<?= $query->tgl_awal ?>"/>
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                </div>

                                <!-- Kolom tengah: Tanggal Akhir -->
                                <div class="col-lg-4">
                                    <label>Tanggal Akhir Cuti <span class="text-danger">*</span></label>
                                    <div class="input-group date" id="tgl_akhir_picker">
                                        <input type="text" class="form-control" name="tgl_akhir_cuti"
                                               placeholder="YYYY-MM-DD" required
                                               value="<?= $query->tgl_akhir ?>"/>
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                </div>

                                <!-- Kolom kanan: Jumlah Hari -->
                                <div class="col-lg-4">
                                    <label>Jumlah Hari Cuti</label>
                                    <input type="text" class="form-control" id="jumlah_hari" name="jumlah_hari"
                                           readonly value="<?= $query->jml_hari_cuti ?> hari">
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div class="form-group">
                                <label class="col-lg-12">Keterangan <span class="text-danger">*</span></label>
                                <div class="col-lg-12">
                                    <textarea name="ket_laporan" class="form-control" rows="4" placeholder="Keterangan..."
                                              required><?= $query->keterangan ?></textarea>

                                </div>
                            </div>

                            <!-- Lampiran Surat Permohonan Cuti-->
                            <div class="form-group">
                                <label class="col-lg-12">Surat Permohonan Cuti</label>
                                <div class="col-lg-12">
                                    <?php if (!empty($query->surat_permohonan_cuti)): ?>
                                        <p>
                                            File saat ini: <a href="<?= base_url($query->surat_permohonan_cuti) ?>" target="_blank">
                                                <?= basename($query->surat_permohonan_cuti) ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    <input type="file" name="surat_permohonan_cuti" class="form-control" accept=".pdf,.doc,.docx">
                                    <small class="text-muted">Kosongkan jika tidak ingin ganti.</small>
                                </div>
                            </div>

                            <!--sk_pengangkatan_notaris-->
                            <div class="form-group">
                                <label class="col-lg-12">SK Pengangkatan Notaris</label>
                                <div class="col-lg-12">
                                    <?php if (!empty($query->sk_pengangkatan_notaris)): ?>
                                        <p>
                                            File saat ini: <a href="<?= base_url($query->sk_pengangkatan_notaris) ?>" target="_blank">
                                                <?= basename($query->sk_pengangkatan_notaris) ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    <input type="file" name="sk_pengangkatan_notaris" class="form-control" accept=".pdf,.doc,.docx">
                                    <small class="text-muted">Kosongkan jika tidak ingin ganti.</small>
                                </div>
                            </div>

                            <!--berita_acara_sumpah-->
                            <div class="form-group">
                                <label class="col-lg-12">Berita Acara Sumpah</label>
                                <div class="col-lg-12">
                                    <?php if (!empty($query->berita_acara_sumpah)): ?>
                                        <p>
                                            File saat ini: <a href="<?= base_url($query->berita_acara_sumpah) ?>" target="_blank">
                                                <?= basename($query->berita_acara_sumpah) ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    <input type="file" name="berita_acara_sumpah" class="form-control" accept=".pdf,.doc,.docx">
                                    <small class="text-muted">Kosongkan jika tidak ingin ganti.</small>
                                </div>
                            </div>

                            <!--sertifikat_cuti_asli-->
                            <div class="form-group">
                                <label class="col-lg-12">Sertifikat Cuti Asli</label>
                                <div class="col-lg-12">
                                    <?php if (!empty($query->sertifikat_cuti_asli)): ?>
                                        <p>
                                            File saat ini: <a href="<?= base_url($query->sertifikat_cuti_asli) ?>" target="_blank">
                                                <?= basename($query->sertifikat_cuti_asli) ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    <input type="file" name="sertifikat_cuti_asli" class="form-control" accept=".pdf,.doc,.docx">
                                    <small class="text-muted">Kosongkan jika tidak ingin ganti.</small>
                                </div>
                            </div>

                            <!--surat_penunjukan_notaris_pengganti-->
                            <div class="form-group">
                                <label class="col-lg-12">Surat Penunjukan Notaris Pengganti</label>
                                <div class="col-lg-12">
                                    <?php if (!empty($query->surat_penunjukan_notaris_pengganti)): ?>
                                        <p>
                                            File saat ini: <a href="<?= base_url($query->surat_penunjukan_notaris_pengganti) ?>" target="_blank">
                                                <?= basename($query->surat_penunjukan_notaris_pengganti) ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    <input type="file" name="surat_penunjukan_notaris_pengganti" class="form-control" accept=".pdf,.doc,.docx">
                                    <small class="text-muted">Kosongkan jika tidak ingin ganti.</small>
                                </div>
                            </div>

                            <!--dokumen pendukung-->
                            <div class="form-group">
                                <label class="col-lg-12">Dokumen Pendukung Lainnya</label>
                                <div class="col-lg-12">
                                    <?php if (!empty($query->lamp_syarat_cuti)): ?>
                                        <p>
                                            File saat ini: <a href="<?= base_url($query->lamp_syarat_cuti) ?>" target="_blank">
                                                <?= basename($query->lamp_syarat_cuti) ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    <input type="file" name="lamp_syarat_cuti" class="form-control" accept=".pdf,.doc,.docx">
                                    <small class="text-muted">Kosongkan jika tidak ingin ganti.</small>
                                </div>
                            </div>

                            <hr>
                            <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>.html"
                               class="btn btn-default"><< Kembali</a>
                            <button type="submit" name="btnupdate" class="btn btn-primary" style="float:right;">Kirim
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /dashboard content -->

    </div>
</div>


<script>
    $(document).ready(function () {
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
        function hitungHari() {
            let tglAwal = $('#tgl_awal_picker input').val();
            let tglAkhir = $('#tgl_akhir_picker input').val();

            if (tglAwal !== "" && tglAkhir !== "") {
                let start = moment(tglAwal, "YYYY-MM-DD");
                let end = moment(tglAkhir, "YYYY-MM-DD");
                let diff = end.diff(start, 'days') + 1; // termasuk hari awal
                if (diff > 0) {
                    $('#jumlah_hari').val(diff + " hari");
                } else {
                    $('#jumlah_hari').val("");
                }
            }
        }
    });
</script>
