<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a href="dashboard.html">Dashboard</a></li>
        <li class="active"><?php echo $judul_web; ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">Data
        <small><?php echo $judul_web; ?></small>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-12">
            <!-- begin panel -->
            <?php
            echo $this->session->flashdata('msg');
            $level = $this->session->userdata('level');
            $link3 = strtolower($this->uri->segment(3));
            ?>
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
                    <h4 class="panel-title">TABEL DAFTAR PENGADUAN</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12"><b>KFilter Pengaduan</b></div>
                        <div class="col-md-3">
                            <select class="form-control default-select2" id="stt">
                                <option value="">- Semua -</option>
                                <option value="proses" <?php if ('proses' == $link3) {
                                    echo "selected";
                                } ?>>Aduan Baru
                                </option>
                                <option value="dispo_mpd" <?php if ('dispo_mpd' == $link3) {
                                    echo "selected";
                                } ?>>Disposisi ke MPD
                                </option>
                                <option value="konfirmasi" <?php if ('konfirmasi' == $link3) {
                                    echo "selected";
                                } ?>>Sedang Ditangani
                                </option>
                                <option value="tolak" <?php if ('tolak' == $link3) {
                                    echo "selected";
                                } ?>>Ditolak
                                </option>
                                <option value="selesai" <?php if ('selesai' == $link3) {
                                    echo "selected";
                                } ?>>Selesai
                                </option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-default"
                                    onclick="window.location.href='pengaduan/v/'+$('#stt').val();"><i
                                        class="fa fa-search"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-6"></div>
                        <div hidden class="col-md-2">
                            <?php if ($level == 'user'): ?>
                                <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/t.html"
                                   class="btn btn-primary" style="float:right;">Buat Pengaduan</a>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-2">

                            <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/t.html"
                               class="btn btn-primary" style="float:right;">Buat Aduans </a>
                        </div>
                    </div>

                    <hr>
                    <div class="table-responsive">
                        <table id="data-table" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="3%">NO.</th>
                                <th width="15%">Waktu</th>
                                <th width="40%">Pengaduan</th>
                                <th width="17%">Status</th>
                                <th width="25%" style="text-align: center">Detail</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $no = 1;
                            foreach ($query->result() as $baris):?>
                                <tr>
                                    <td><b><?php echo $no++; ?>.</b></td>
                                    <td><?php echo $this->Mcrud->tgl_id(date('d-m-Y H:i:s', strtotime($baris->tgl_pengaduan)), 'full'); ?></td>
                                    <td><?php echo $baris->isi_pengaduan; ?></td>
                                    <td><?php echo $this->Mcrud->cek_status($baris->status); ?></td>
                                    <td align="center">
                                        <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/d/<?php echo hashids_encrypt($baris->id_pengaduan); ?>"
                                           class="btn btn-info btn-xs" title="Detail Aduan"><i class="fa fa-search"></i></a>

                                        <?php
                                        $styleVisibility = '';
                                        $delbutton_statustrigger_tonotaris = ['proses','dispo_mpd', 'tolak', 'konfirmasi', 'selesai'];
                                        if ($this->session->userdata('level') == 'notaris' && in_array($baris->status, $delbutton_statustrigger_tonotaris)) {
                                            $styleVisibility = 'style="visibility:hidden;"';
                                        }
                                        ?>

                                        <?php
                                        if (in_array($this->session->userdata('level'), ['superadmin', 'petugas', 'notaris'])) {
                                            ?>
                                            <a <?= $styleVisibility; ?> href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/h/<?php echo hashids_encrypt($baris->id_pengaduan); ?>"
                                               class="btn btn-danger btn-xs btn-delete"
                                               title="Delete data?">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                            <?php
                                        }
                                        ?>

                                        <!--tes commit lagi-->
                                        <?php
                                            $level = $this->session->userdata('level');
                                        ?>

                                        <?php
                                            $allowedEdit = ['user'];
                                            if($baris->status!='proses'){
                                                $styleAndDisabled = 'style="pointer-events:none;opacity:0.6;" disabled';
                                                //$href= 'href="javascript:;"';

                                            }
                                            if(in_array($level,$allowedEdit)){
                                                ?>
                                                <a <?= $styleAndDisabled; ?> href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/e/<?php echo hashids_encrypt($baris->id_pengaduan); ?>"
                                                   class="btn btn-success btn-xs btn-success"
                                                   title="Edited By Netizen?">
                                                    <i class="fa fa-pencil-square"></i>
                                                </a>
                                                <?php
                                            }
                                        ?>

                                        <?php
                                            $allowedEditedByPetugas = ['petugas'];
                                            if(in_array($level,$allowedEditedByPetugas)){
                                                ?>
                                                <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/ep/<?php echo hashids_encrypt($baris->id_pengaduan); ?>"
                                                   class="btn btn-success btn-xs btn-success" style="background-color: #0b2e13!important;"
                                                   title="Edited By Petugas?">
                                                    <i class="fa fa-pencil-square"></i>
                                                </a>
                                                <?php
                                            }
                                        ?>


                                        <?php
                                        /* tombol tindak lanjut tidak ditampilkan untuk akun masyarakat */
                                        if ($level != 'user' && $level != "notaris") {
                                            $style = '';
                                            $statustrigger_tosuperadmin = ['dispo_mpd', 'tolak', 'konfirmasi', 'selesai'];
                                            if ($this->session->userdata('level') == 'superadmin' && in_array($baris->status, $statustrigger_tosuperadmin)) {
                                                $style = 'style="pointer-events:none;opacity:0.6;"';
                                            }
                                            ?>
                                            <?php
                                            // Ambil data yang diperlukan (boleh pakai JOIN di controller biar nggak N+1)
                                            $getNamaPelapor = $this->db->select('nama_lengkap')
                                                    ->get_where('tbl_user', ['id_user' => $baris->user])
                                                    ->row()->nama_lengkap ?? '';

                                            $getNamaNotaris = $this->db->select('nama_sub_kategori')
                                                    ->get_where('tbl_sub_kategori', ['id_sub_kategori' => $baris->id_sub_kategori])
                                                    ->row()->nama_sub_kategori ?? '';

                                            $getDataAduan = $this->db->select('surat_penolakan, surat_laporan_ke_mpw')
                                                ->get_where('tbl_aduan_hasfile', ['pengaduan_id' => $baris->id_pengaduan])
                                                ->row();

                                            $suratPenolakan = $getDataAduan->surat_penolakan ?? '';
                                            $suratLaporanKeMpw = $getDataAduan->surat_laporan_ke_mpw ?? '';


                                            ?>

                                            <a <?= $style; ?> href="javascript:;"
                                                              class="btn btn-warning btn-xs"
                                                              title="Tindak Lanjut"
                                                              data-toggle="modal"
                                                              data-target="#modalTindakLanjut"
                                                              data-id="<?= $baris->id_pengaduan; ?>"
                                                              data-pelapor="<?= html_escape($getNamaPelapor); ?>"
                                                              data-notaris="<?= html_escape($getNamaNotaris); ?>"
                                                              data-status="<?= html_escape($baris->status); ?>"
                                                              data-surat-penolakan="<?= html_escape($suratPenolakan); ?>"
                                                              data-mpw-laporan="<?= html_escape($suratLaporanKeMpw); ?>"
                                                <?= $style; ?>>
                                                <i class="fa fa-share"></i>
                                            </a>
                                            <?php
                                        }
                                        ?>


                                        <?php if ($level == 'superadmin') { ?>
                                            <?php if ($baris->status == 'proses') { ?>
                                                <a style="display: none;" href="javascript:;"
                                                   class="btn btn-primary btn-xs" title="Konfirmasi"
                                                   data-toggle="modal"
                                                   onclick="modal_show(<?php echo $baris->id_pengaduan; ?>);"><i
                                                            class="fa fa-file"></i> Konfirmasis</a>
                                            <?php } else { ?>
                                                <a style="display: none;" href="javascript:;"
                                                   class="btn btn-success btn-xs"
                                                   title="Terkonfirmasi" disabled><i class="fa fa-check"></i>
                                                    konfirmasiz</a>
                                            <?php } ?>
                                        <?php } elseif ($level == 'petugas') { ?>
                                            <?php //if ($baris->status=='konfirmasi'){ ?>
                                            <a style="display: none;" class="btn btn-success btn-xs" title="Edit"
                                               data-toggle="modal"
                                               onclick="modal_show(<?php echo $baris->id_pengaduan; ?>);"><i
                                                        class="fa fa-pencil"></i> Edit</a>
                                            <?php //}else{ ?>
                                            <!-- <a href="javascript:;" class="btn btn-success btn-xs" title="Edit" disabled><i class="fa fa-check"></i> Edit</a> -->
                                            <?php //} ?>
                                        <?php } else {
                                            $style = "";
                                            if ($level == "notaris") {
                                                //$style = 'style="pointer-events:none;opacity:0.6;"';
                                                $style = 'style="display:none;"';
                                            }
                                            ?>
                                            <?php if ($baris->status == 'proses') { ?>
                                                <a <?= $style; ?>
                                                        href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/h/<?php echo hashids_encrypt($baris->id_pengaduan); ?>"
                                                        class="btn btn-danger btn-xs" title="Hapuss"
                                                        onclick="return confirm('Anda yakin?');"><i
                                                            class="fa fa-trash-o"></i></a>
                                            <?php } else { ?>
                                                <a <?= $style; ?> href="javascript:;" class="btn btn-danger btn-xs"
                                                                  title="Hapusz"
                                                                  disabled><i class="fa fa-trash-o"></i></a>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->
<!-- Modal Tindak Lanjut -->
<?php echo $this->load->view('users/laporaduan/modal_update_status'); ?>
<script>
    $(document).ready(function () {
        $('.btn-delete').on('click', function (e) {
            e.preventDefault(); // cegah langsung redirect
            var linkURL = $(this).attr('href');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = linkURL; // redirect ke URL delete
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        const statusSelect = $('#modalStatus');
        const btnLampiranWrapper = $('#btnLampiranWrapper');
        const btnTambahLampiran = $('#btnTambahLampiran');
        const lampiranContainer = $('#lampiranContainer');

        const penolakanContainer = $('#penolakanContainer');
        const suratExistWrap = $('#suratPenolakanExistWrap');
        const suratExistLink = $('#suratPenolakanExistLink');
        const removeFlag = $('#remove_surat_penolakan');
        const formTindakLanjut = $("#formTindakLanjut");

        // >>> elemen MPW (A, C)
        const mpwContainer = $('#mpwContainer');
        const mpwExistWrap = $('#mpwExistWrap');
        const mpwExistLink = $('#mpwExistLink');

        let suratPenolakanPath = ''; // diisi saat modal dibuka
        let lampiranMpwPath = ''; // diisi saat modal dibuka (B, C)

        const BASE_URL = <?= json_encode(base_url()); ?>;

        function buildUrl(path) {
            if (!path) return '#';
            path = String(path).replace(/^\.\//, '');
            if (/^https?:\/\//i.test(path)) return path;
            return BASE_URL.replace(/\/+$/, '') + '/' + path.replace(/^\/+/, '');
        }

        function updateUI() {
            var val = statusSelect.val();
            <?php if ($this->session->userdata('level') == "petugas" || $this->session->userdata('level') == "superadmin"): ?>
            if (val === 'konfirmasi') {
                btnLampiranWrapper.show();
                lampiranContainer.hide();
                btnTambahLampiran.show();

                penolakanContainer.hide();
                suratExistWrap.hide();

                mpwContainer.hide();
                mpwExistWrap.hide();

            } else if (val === 'tolak') {
                btnLampiranWrapper.hide();
                lampiranContainer.hide();

                penolakanContainer.show();
                if (suratPenolakanPath) {
                    suratExistLink.attr('href', buildUrl(suratPenolakanPath));
                    suratExistWrap.show();
                } else {
                    suratExistWrap.hide();
                }

                mpwContainer.hide();
                mpwExistWrap.hide();

            } else if (val === 'selesai') {
                btnLampiranWrapper.hide();
                lampiranContainer.hide();

                penolakanContainer.hide();
                suratExistWrap.hide();

                mpwContainer.show();
                if (lampiranMpwPath) {
                    mpwExistLink.attr('href', buildUrl(lampiranMpwPath));
                    mpwExistWrap.show();
                } else {
                    mpwExistWrap.hide();
                }

            } else {
                btnLampiranWrapper.hide();
                lampiranContainer.hide();
                penolakanContainer.hide();
                suratExistWrap.hide();

                mpwContainer.hide();
                mpwExistWrap.hide();
            }
            <?php endif; ?>
        }

        // status berubah
        statusSelect.on('change', function () {
            const newVal = $(this).val();
            if (newVal === 'tolak') {
                if (removeFlag.length) removeFlag.val('0');
            } else {
                if (removeFlag.length) removeFlag.val('1');
            }
            updateUI();
        });

        // tombol "LAMPIRAN"
        btnTambahLampiran.on('click', function () {
            lampiranContainer.slideDown();
            btnLampiranWrapper.hide();

            penolakanContainer.hide();
            suratExistWrap.hide();

            mpwContainer.hide();
            mpwExistWrap.hide();
        });

        // modal dibuka
        $('#modalTindakLanjut').on('show.bs.modal', function (event) {
            //alert("hey");
            //return false;
            var button = $(event.relatedTarget);
            var status = button.data('status') || '';
            var pelapor = button.data('pelapor') || '';
            var notaris = button.data('notaris') || '';
            var id = button.data('id') || '';

            suratPenolakanPath = button.data('suratPenolakan')
                || button.attr('data-surat-penolakan')
                || '';


            lampiranMpwPath = button.data('mpwLaporan')
                || button.attr('data-mpw-laporan')
                || '';

            if (removeFlag.length) removeFlag.val('0');

            $('#modalStatus').val(status);
            $('#modalStatusLama').val(status);
            $('#modalNamaPelapor').text(pelapor);
            $('#modalNamaNotaris').text(notaris);
            $('#modalIdPengaduan').val(id);

            updateUI();

            $.ajax({
                url: "<?= base_url('laporaduan/get_files_ajax/'); ?>" + id,
                type: "GET",
                dataType: "json",
                success: function (res) {
                    $('#oldPemberitahuan, #oldUndangan, #oldPemanggilan, #oldBapTtd, #oldBapPemeriksaan').html('');

                    if (res.surat_pemberitahuan) {
                        $('#oldPemberitahuan').html(
                            `File lama: <a href="<?= base_url(); ?>${res.surat_pemberitahuan}" target="_blank">${res.surat_pemberitahuan.split('/').pop()}</a>`
                        );
                    }
                    if (res.surat_undangan) {
                        $('#oldUndangan').html(
                            `File lama: <a href="<?= base_url(); ?>${res.surat_undangan}" target="_blank">${res.surat_undangan.split('/').pop()}</a>`
                        );
                    }
                    if (res.surat_pemanggilan) {
                        $('#oldPemanggilan').html(
                            `File lama: <a href="<?= base_url(); ?>${res.surat_pemanggilan}" target="_blank">${res.surat_pemanggilan.split('/').pop()}</a>`
                        );
                    }
                    if (res.undangan_ttd_bap) {
                        $('#oldBapTtd').html(
                            `File lama: <a href="<?= base_url(); ?>${res.undangan_ttd_bap}" target="_blank">${res.undangan_ttd_bap.split('/').pop()}</a>`
                        );
                    }
                    if (res.bap_pemeriksaan_has_ttd) {
                        $('#oldBapPemeriksaan').html(
                            `File lama: <a href="<?= base_url(); ?>${res.bap_pemeriksaan_has_ttd}" target="_blank">${res.bap_pemeriksaan_has_ttd.split('/').pop()}</a>`
                        );
                    }
                }
            });
        });

        // modal ditutup
        $('#modalTindakLanjut').on('hidden.bs.modal', function () {
            lampiranContainer.hide();
            btnLampiranWrapper.hide();
            btnTambahLampiran.show();

            penolakanContainer.hide();
            suratExistWrap.hide();

            mpwContainer.hide();
            mpwExistWrap.hide();

            $('#surat_penolakan').val('');
            $('#lampiran_laporan_mpw').val('');

            suratPenolakanPath = '';
            lampiranMpwPath = '';
            if (removeFlag.length) removeFlag.val('0');
        });

        /*quncinya*/
        // KONFIRMASI saat SUBMIT
        let isSubmitting = false;
        formTindakLanjut.on("submit", function (e) {
            if (isSubmitting) return;
            e.preventDefault();

            const statusNow = $("#modalStatus").val();
            const statusLama = $("#modalStatusLama").val();
            const hasExisting = $("#hasSuratPenolakan").val() === "1";

            if (statusLama === "tolak" && statusNow !== "tolak" && suratPenolakanPath) {
                Swal.fire({
                    title: 'Hapus Surat Penolakan?',
                    html: 'Anda mengubah status ke <b>selain DITOLAK</b>.<br>File <i>Surat Penolakan</i> yang sudah ada akan <b>dihapus</b> saat disimpan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((res) => {
                    if (res.isConfirmed) {
                        isSubmitting = true;
                        formTindakLanjut.off("submit").submit();
                    }
                });
                return;
            }

            // 🔎 cek kalau ada file lama & user pilih file baru
            let conflictMsg = "";

            if (suratPenolakanPath && $('#surat_penolakan')[0].files.length > 0) {
                conflictMsg += "- Surat Penolakan\n";
            }
            if (lampiranMpwPath && $('#lampiran_laporan_mpw')[0].files.length > 0) {
                conflictMsg += "- Lampiran Laporan MPW\n";
            }
            if ($('#oldPemberitahuan a').length && $('input[name="lampiran_pemberitahuan"]')[0].files.length > 0) {
                conflictMsg += "- Surat Pemberitahuan\n";
            }
            if ($('#oldUndangan a').length && $('input[name="lampiran_undangan"]')[0].files.length > 0) {
                conflictMsg += "- Surat Undangan\n";
            }
            if ($('#oldPemanggilan a').length && $('input[name="lampiran_pemanggilan"]')[0].files.length > 0) {
                conflictMsg += "- Surat Pemanggilan\n";
            }
            if ($('#oldBapTtd a').length && $('input[name="lampiran_bap_ttd"]')[0].files.length > 0) {
                conflictMsg += "- Undangan TTD BAP\n";
            }
            if ($('#oldBapPemeriksaan a').length && $('input[name="lampiran_bap_pemeriksaan"]')[0].files.length > 0) {
                conflictMsg += "- BAP Pemeriksaan TTD\n";
            }

            if (conflictMsg) {
                Swal.fire({
                    title: "Ganti File Lama?",
                    text: "File berikut akan diganti dengan file baru:\n" + conflictMsg,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, ganti",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        isSubmitting = true;
                        formTindakLanjut.off("submit").submit();
                    }
                });
            } else {
                isSubmitting = true;
                formTindakLanjut.off("submit").submit();
            }
        });

        // init
        updateUI();
    });
</script>



