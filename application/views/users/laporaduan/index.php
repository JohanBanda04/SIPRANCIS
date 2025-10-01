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
                                        if (in_array($this->session->userdata('level'), ['superadmin', 'petugas', 'notaris'])) {
                                            ?>
                                            <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/h/<?php echo hashids_encrypt($baris->id_pengaduan); ?>"
                                               class="btn btn-danger btn-xs btn-delete"
                                               title="Delete data?">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                        /* tombol tindak lanjut tidak ditampilkan untuk akun masyarakat */
                                        if ($level != 'user' && $level != "notaris") {
                                            $style = '';
                                            if ($this->session->userdata('level') == 'superadmin' && $baris->status == 'dispo_mpd') {
                                                $style = 'style="pointer-events:none;opacity:0.6;"';
                                            }
                                            ?>
                                            <a href="javascript:;"
                                               class="btn btn-warning btn-xs"
                                               title="Tindak Lanjut"
                                               data-toggle="modal"
                                               data-target="#modalTindakLanjut"
                                               data-id="<?php echo $baris->id_pengaduan; ?>"
                                               data-pelapor="<?php
                                               $getNamaPelapor = $this->db->get_where('tbl_user', ['id_user' => $baris->user])
                                                       ->row()->nama_lengkap ?? '';
                                               echo $getNamaPelapor;
                                               ?>"
                                               data-notaris="<?php
                                               $getNamaNotaris = $this->db->get_where('tbl_sub_kategori', ['id_sub_kategori' => $baris->id_sub_kategori])
                                                       ->row()->nama_sub_kategori ?? '';
                                               echo $getNamaNotaris;
                                               ?>"
                                               data-status="<?php echo $baris->status; ?>"
                                               data-surat-penolakan="<?php
                                               $suratPenolakan = $this->db->get_where('tbl_aduan_hasfile',array('pengaduan_id'=>$baris->id_pengaduan))
                                                   ->row()->surat_penolakan??'';
                                               echo html_escape($suratPenolakan);
                                               ?>"
                                                <?= $style; ?>>
                                                <i class="fa fa-share"></i>
                                            </a>
                                            <?php
                                        }
                                        ?>


                                        <?php if ($level == 'superadmin') { ?>
                                            <?php if ($baris->status == 'proses') { ?>
                                                <a href="javascript:;" class="btn btn-primary btn-xs" title="Konfirmasi"
                                                   data-toggle="modal"
                                                   onclick="modal_show(<?php echo $baris->id_pengaduan; ?>);"><i
                                                            class="fa fa-file"></i> Konfirmasi</a>
                                            <?php } else { ?>
                                                <a href="javascript:;" class="btn btn-success btn-xs"
                                                   title="Terkonfirmasi" disabled><i class="fa fa-check"></i> konfirmasi</a>
                                            <?php } ?>
                                        <?php } elseif ($level == 'petugas') { ?>
                                            <?php //if ($baris->status=='konfirmasi'){ ?>
                                            <a class="btn btn-success btn-xs" title="Edit" data-toggle="modal"
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
        const statusSelect        = $('#modalStatus');
        const btnLampiranWrapper  = $('#btnLampiranWrapper');
        const btnTambahLampiran   = $('#btnTambahLampiran');
        const lampiranContainer   = $('#lampiranContainer');

        const penolakanContainer  = $('#penolakanContainer');
        const suratExistWrap      = $('#suratPenolakanExistWrap');
        const suratExistLink      = $('#suratPenolakanExistLink');
        const removeFlag          = $('#remove_surat_penolakan');
        const formTindakLanjut    = $('#formTindakLanjut');

        let suratPenolakanPath = ''; // diisi saat modal dibuka

        const BASE_URL = <?= json_encode(base_url()); ?>;
        function buildUrl(path) {
            if (!path) return '#';
            path = String(path).replace(/^\.\//,'');
            if (/^https?:\/\//i.test(path)) return path;
            return BASE_URL.replace(/\/+$/,'') + '/' + path.replace(/^\/+/, '');
        }

        function updateUI() {
            var val = statusSelect.val();
            <?php if ($this->session->userdata('level') == "petugas"): ?>
            if (val === 'konfirmasi') {
                btnLampiranWrapper.show();
                lampiranContainer.hide();
                btnTambahLampiran.show();

                penolakanContainer.hide();
                suratExistWrap.hide();
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
            } else {
                btnLampiranWrapper.hide();
                lampiranContainer.hide();
                penolakanContainer.hide();
                suratExistWrap.hide();
            }
            <?php endif; ?>
        }

        // status berubah
        statusSelect.on('change', function(){
            const newVal = $(this).val();
            if (newVal === 'tolak') {
                if (removeFlag.length) removeFlag.val('0'); // jangan hapus di server
            } else {
                if (removeFlag.length) removeFlag.val('1'); // minta server hapus saat submit
            }
            updateUI();
        });

        // tombol "LAMPIRAN"
        btnTambahLampiran.on('click', function () {
            lampiranContainer.slideDown();
            btnLampiranWrapper.hide();
            penolakanContainer.hide();
            suratExistWrap.hide();
        });

        // modal dibuka
        $('#modalTindakLanjut').on('show.bs.modal', function (event) {
            var button  = $(event.relatedTarget);
            var status  = button.data('status') || '';
            var pelapor = button.data('pelapor') || '';
            var notaris = button.data('notaris') || '';
            var id      = button.data('id') || '';

            // baca path dari data-surat-penolakan
            suratPenolakanPath = button.data('suratPenolakan')
                || button.attr('data-surat-penolakan')
                || button.attr('data-surat_penolakan')
                || '';

            if (removeFlag.length) removeFlag.val('0'); // reset flag saat modal dibuka

            $('#modalStatus').val(status);
            $('#modalNamaPelapor').text(pelapor);
            $('#modalNamaNotaris').text(notaris);
            $('#modalIdPengaduan').val(id);

            updateUI();
        });

        // modal ditutup
        $('#modalTindakLanjut').on('hidden.bs.modal', function () {
            lampiranContainer.hide();
            btnLampiranWrapper.hide();
            btnTambahLampiran.show();

            penolakanContainer.hide();
            suratExistWrap.hide();

            $('#surat_penolakan').val('');
            suratPenolakanPath = '';
            if (removeFlag.length) removeFlag.val('0');
        });

        // KONFIRMASI saat SUBMIT jika file lama akan dihapus
        let isSubmitting = false;
        formTindakLanjut.on('submit', function(e){
            if (isSubmitting) return; // cegah loop

            <?php if ($this->session->userdata('level') == "petugas"): ?>
            const statusNow   = statusSelect.val();
            const willRemove  = removeFlag.length && removeFlag.val() === '1';
            const hasExisting = !!suratPenolakanPath;

            // Hanya tampilkan peringatan jika: status ≠ tolak, ada file lama, dan removeFlag = 1
            if (statusNow !== 'tolak' && hasExisting && willRemove) {
                e.preventDefault();

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
                        formTindakLanjut.trigger('submit');
                    }
                });

                return; // tunggu konfirmasi
            }

            // (Opsional) kalau statusNow === 'tolak' dan TIDAK ada file baru & tidak ada existing,
            // Anda bisa cegah submit di sisi klien di sini. Controller Anda sudah memvalidasi, jadi ini opsional.
            // if (statusNow === 'tolak' && !hasExisting && !$('#surat_penolakan').val()) { ... }

            <?php endif; ?>
        });

        // init pertama
        updateUI();
    });
</script>





<?php $this->load->view('users/pengaduan/modal_konfirm'); ?>
