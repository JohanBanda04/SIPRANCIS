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
                        <style>
                            .wajib-isi { color: red; font-weight: bold; }
                        </style>

                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

                            <div class="alert alert-success">
                                <i class="fa fa-info-circle"></i>
                                <strong>Note :</strong> Isikan data aduan dengan jujur & bertanggung jawab. Bersama kita wujudkan NTB yang Bersih & Jujur!
                            </div>

                            <!-- Dropdown MPD -->
                            <div class="form-group">
                                <label class="col-lg-12">Pilih MPD <span class="wajib-isi">*</span></label>
                                <div class="col-lg-12">
                                    <select class="form-control" name="mpd_area_id" id="mpd_area_id" required>
                                        <option value="">- Pilih MPD -</option>
                                        <?php foreach ($query_mpd->result() as $mpd): ?>
                                            <option value="<?php echo $mpd->id_petugas; ?>">
                                                <?php echo ucwords($mpd->nama); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Dropdown Notaris -->
                            <div class="form-group">
                                <label class="col-lg-12">Pilih Notaris <span class="wajib-isi">*</span></label>
                                <div class="col-lg-12">
                                    <select class="form-control" name="id_data_notaris" id="id_data_notaris" required>
                                        <option value="">- Pilih Notaris -</option>
                                    </select>
                                </div>
                            </div>


                            <!-- Uraian -->
                            <div class="form-group">
                                <label class="col-lg-12">Uraian Aduan <span class="wajib-isi">*</span></label>
                                <div class="col-lg-12">
                                    <textarea name="isi_pengaduan" class="form-control" rows="4" placeholder="Jabarkan dengan jelas..!" required></textarea>
                                </div>
                            </div>


                            <!-- Keterangan -->
                            <div class="form-group">
                                <label class="col-lg-12">Keterangan Tambahan <span class="wajib-isi">*</span></label>
                                <div class="col-lg-12">
                                    <textarea name="ket_pengaduan" class="form-control" rows="4" placeholder="Keterangan Tambahan.." required></textarea>
                                </div>
                            </div>

                            <!-- Upload -->
                            <div class="form-group">
                                <label class="col-lg-12">Upload File Aduan
                                    <small class="wajib-isi">* (Bermuatkan Surat Aduan, beserta Files Pendukung)</small>
                                </label>
                                <div class="col-lg-12">
                                    <input type="file" name="daduk_aduan" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                </div>
                            </div>

                            <hr>
                            <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>.html" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" name="btnsimpan" class="btn btn-primary pull-right">
                                <i class="fa fa-save"></i> Kirim Aduan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /dashboard content -->
    </div>
</div>

<!-- JS dinamis -->
<script>
    document.getElementById('mpd_area_id').addEventListener('change', function() {
        const mpdId = this.value;
        const notarisSelect = document.getElementById('id_data_notaris');
        notarisSelect.innerHTML = '<option value="">Loading...</option>';

        if (mpdId === "") {
            notarisSelect.innerHTML = '<option value="">- Pilih Notaris -</option>';
            return;
        }

        fetch("<?php echo base_url('laporaduan/v/getNotaris?mpd_id='); ?>" + mpdId)
            .then(response => response.json())
            .then(data => {
                notarisSelect.innerHTML = '<option value="">- Pilih Notaris -</option>';
                data.forEach(item => {
                    const opt = document.createElement("option");
                    opt.value = item.id_data_notaris;
                    opt.textContent = item.nama;
                    notarisSelect.appendChild(opt);
                });
            })
            .catch(err => {
                console.error(err);
                notarisSelect.innerHTML = '<option value="">Error load data</option>';
            });
    });
</script>


