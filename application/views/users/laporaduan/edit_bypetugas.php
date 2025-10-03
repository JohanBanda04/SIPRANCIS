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
                        <h4 class="panel-title"><?php echo $judul_web . " "; ?>
                            <label style="color: white">
                                <?= "(Status : " . strtoupper($dataPengaduan->status) . ")"; ?>
                            </label>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $this->session->flashdata('msg'); ?>
                        <style>
                            .wajib-isi {
                                color: red;
                                font-weight: bold;
                            }
                        </style>

                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

                            <div class="alert alert-success">
                                <i class="fa fa-info-circle"></i>
                                <strong>Note :</strong> Isikan data aduan dengan jujur & bertanggung jawab. Bersama kita
                                wujudkan NTB yang Bersih & Jujur!
                            </div>

                            <?php
                            $selectedMpd = $dataPengaduan->petugas ?? '';

                            /*cari id_data_notaris*/
                            $idSubKategori = $dataPengaduan->id_sub_kategori ?? '';

                            $namaKategori = '';
                            if (!empty($idSubKategori)) {
                                $subKategoriRow = $this->db->get_where('tbl_sub_kategori', ['id_sub_kategori' => $idSubKategori])
                                    ->row();
                                if ($subKategoriRow) {
                                    $namaKategori = $subKategoriRow->nama_sub_kategori;
                                }
                            }
                            //echo $namaKategori; die;

                            /*normalisasi ternormal misal nama notaris deby fakhira, deby fakhira S.H. ,deby fakhira. SH dsb*/
                            $selectedNotaris = '';
                            if (!empty($namaKategori)) {
                                // Normalisasi nama notaris dari sub kategori
                                $getNamaKategori = trim($namaKategori);

                                // Potong nama sebelum koma (hilangkan gelar)
                                $namaUtama = explode(',', $getNamaKategori)[0]; // ambil nama utama
                                $namaUtama = trim($namaUtama);

                                // Cari notaris yang namanya diawali nama utama
                                $this->db->like('nama', $namaUtama, 'after'); // LIKE 'BAMBANG GEDE%'
                                $getDataNotaris = $this->db->get('tbl_data_notaris', 1)->row();

                                $selectedNotaris = $getDataNotaris->id_data_notaris ?? '';
                            }

                            ?>

                            <!-- Hidden ID Pengaduan -->
                            <input type="hidden" name="id_pengaduan"
                                   value="<?php echo $dataPengaduan->id_pengaduan ?? ''; ?>">
                            <!-- Dropdown MPD -->

                            <div class="form-group">
                                <label class="col-lg-12">Pilih MPD <span class="wajib-isi">*</span></label>
                                <div class="col-lg-12">
                                    <select class="form-control" name="mpd_area_id" id="mpd_area_id" required>
                                        <option value="">- Pilih MPD -</option>
                                        <?php foreach ($query_mpd->result() as $mpd): ?>
                                            <option value="<?php echo $mpd->id_petugas; ?>"
                                                <?php echo ($mpd->id_user == $selectedMpd) ? 'selected' : ''; ?>>
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
                                    <textarea name="isi_pengaduan" class="form-control" rows="4"
                                              placeholder="Jabarkan dengan jelas..!" required
                                              style="text-align: left;"><?php echo isset($dataPengaduan->isi_pengaduan) ? htmlspecialchars($dataPengaduan->isi_pengaduan) : ''; ?></textarea>
                                </div>
                            </div>


                            <!-- Keterangan -->
                            <div class="form-group">
                                <label class="col-lg-12">Keterangan Tambahan <span class="wajib-isi">*</span></label>
                                <div class="col-lg-12">
                                    <textarea name="ket_pengaduan" class="form-control" rows="4"
                                              placeholder="Keterangan Tambahan.."
                                              required><?php echo isset($dataPengaduan->ket_pengaduan) ? htmlspecialchars($dataPengaduan->ket_pengaduan) : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Upload -->
                            <?php
                            $uploadedFile = $dataPengaduan->bukti ?? '';
                            /* styleOverflow */
                            $styleOverflow = 'style="
    display:inline-block;
    max-width:100%;
    word-wrap:break-word;
    white-space:normal;
    overflow-wrap:break-word;
"';
                            ?>
                            <!-- Upload -->
                            <div class="form-group">
                                <label class="col-lg-12">Upload File Aduan
                                    <small class="wajib-isi">* (Bermuatkan Surat Aduan, beserta Files Pendukung)</small>
                                </label>
                                <div class="col-lg-12">
                                    <?php if (!empty($dataPengaduan->bukti)): ?>
                                        <p>File saat ini:
                                            <a <?php echo $styleOverflow; ?>
                                                    href="<?php echo base_url($dataPengaduan->bukti); ?>"
                                                    target="_blank">
                                                <?php echo htmlspecialchars(basename($dataPengaduan->bukti)); ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    <input type="file" name="daduk_aduan" class="form-control"
                                           accept=".jpg,.jpeg,.png,.pdf">
                                    <small>Kosongkan jika tidak ingin mengganti file.</small>
                                </div>
                            </div>

                            <?php
                            foreach ($additionalFiles as $fieldName => $label) {
                                $uploadedFile = $aduanHasFile->$fieldName ?? '';
                                ?>
                                <div class="form-group">
                                    <label class="col-lg-12"><?php echo $label; ?>
                                        <small class="wajib-isi">* (Upload file jika tersedia)</small>
                                    </label>
                                    <div class="col-lg-12">
                                        <?php if (!empty($uploadedFile)): ?>
                                            <p>File saat ini:
                                                <a style="display:inline-block; max-width:100%; word-wrap:break-word; white-space:normal; overflow-wrap:break-word;"
                                                   href="<?php echo base_url($uploadedFile); ?>" target="_blank">
                                                    <?php echo htmlspecialchars(basename($uploadedFile)); ?>
                                                </a>
                                            </p>
                                        <?php endif; ?>

                                        <input type="file" name="<?= $fieldName ?>" class="form-control"
                                               accept=".jpg,.jpeg,.png,.pdf">
                                        <small>Kosongkan jika tidak ingin mengganti file.</small>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <hr>
                            <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>.html"
                               class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" name="btnupdate_aduan_bypetugas" class="btn btn-primary pull-right">
                                <i class="fa fa-save"></i> Update Aduan
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
    document.addEventListener("DOMContentLoaded", function () {
        const mpdSelect = document.getElementById('mpd_area_id');
        const notarisSelect = document.getElementById('id_data_notaris');
        const selectedNotaris = "<?php echo $selectedNotaris; ?>";
        console.log(selectedNotaris);

        function loadNotaris(mpdId, callback) {
            if (!mpdId) {
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
                        opt.textContent = item.nama.toUpperCase();
                        if (item.id_data_notaris == selectedNotaris) {
                            opt.selected = true;
                        }
                        notarisSelect.appendChild(opt);
                    });
                    if (callback) callback();
                })
                .catch(err => {
                    console.error(err);
                    notarisSelect.innerHTML = '<option value="">Error load data</option>';
                });
        }

        // ketika MPD berubah
        mpdSelect.addEventListener('change', function () {
            loadNotaris(this.value);
        });

        // saat halaman load, otomatis panggil sesuai mpd yang tersimpan
        if (mpdSelect.value) {
            loadNotaris(mpdSelect.value);
        }
    });
</script>





