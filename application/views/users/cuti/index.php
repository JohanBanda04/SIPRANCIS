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
                    <h4 class="panel-title"><?= $tableName; ?></h4>
                </div>
                <div class="panel-body">
                    <div class="row align-items-center mb-2">
                        <div class="col-md-3">
                            <b>Filter Cuti</b>
                            <select class="form-control default-select2" id="stt">
                                <option value="">- Semua -</option>
                                <option value="pengajuan" <?php if ('pengajuan' == $link3) {
                                    echo "selected";
                                } ?>>Pengajuan
                                </option>
                                <option value="dispo_mpd" <?php if ('dispo_mpd' == $link3) {
                                    echo "selected";
                                } ?>>Disposisi MPD
                                </option>
                                <option value="approve" <?php if ('approve' == $link3) {
                                    echo "selected";
                                } ?>>Disetujui
                                </option>
                                <option value="decline" <?php if ('decline' == $link3) {
                                    echo "selected";
                                } ?>>Ditolak
                                </option>
                            </select>
                        </div>

                        <div class="col-md-9 d-flex justify-content-end " style="margin-top: 20px;">
                            <?php if ($level == 'notaris'): ?>
                                <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/t.html"
                                   class="btn btn-primary" style="float:right;">
                                    Permohonan Cuti
                                </a>
                            <?php endif; ?>
                        </div>



                    </div>

                    <hr>
                    <div class="table-responsive">
                        <table id="data-table" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="1%">No.</th>
                                <th width="15%">Tanggal Pengajuan</th>
                                <th width="15%">Alasan</th>
                                <th width="15%">Jml Hari Cuti</th>
                                <th width="25%">Nama Pemohon</th> <!-- dikurangi dari 50% -->
                                <th width="14%">Status</th>
                                <th width="15%" style="text-align: center">Opsi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            foreach ($query->result() as $baris):?>
                                <tr>
                                    <td><b><?php echo $no++; ?>.</b></td>
                                    <td><?php
                                        $DateAndTime = $this->Mcrud->tgl_id(date('d-m-Y H:i:s', strtotime($baris->created_at)), 'full');
                                        $dateOnly = explode(" ", $DateAndTime);
                                        echo $dateOnly[0] . " " . $dateOnly[1] . " " . $dateOnly[2];
                                        ?></td>
                                    <td>
                                        <?= $baris->alasan; ?>
                                    </td>
                                    <td>
                                        <?= $baris->jml_hari_cuti . " hari"; ?>
                                    </td>
                                    <td><?php
                                        $getNamaPemohon = $this->db->get_where('tbl_user', array("id_user" => $baris->user_id))->row()->nama_lengkap;
                                        echo $getNamaPemohon;
                                        ?></td>
                                    <td>
                                        <?php
                                        if ($baris->status == 'pengajuan') {
                                            $backColor = 'background-color: blue';
                                        } else if ($baris->status == 'dispo_mpd') {
                                            $backColor = 'background-color: purple';
                                        } else if ($baris->status == 'decline') {
                                            $backColor = 'background-color: red';
                                        } else if ($baris->status == 'approve') {
                                            $backColor = 'background-color: green';
                                        }
                                        ?>
                                        <label for="" style="<?= $backColor; ?>; color: white; border-radius: 5px;">
                                            <?php echo $baris->status; ?>
                                        </label>
                                    </td>
                                    <td align="center">
                                        <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/d/<?php echo hashids_encrypt($baris->id_cuti); ?>"
                                           class="btn btn-info btn-xs" title="Detail Cuti"><i class="fa fa-search"></i></a>
                                        <?php
                                        if ($level == "petugas" || $level == "superadmin") {
                                            $display = ''; // default kosong
                                            if (($baris->status == "approve" || $baris->status == "decline") && $level == 'superadmin') {
                                                $display = 'display: none;';
                                            }
                                            ?>

                                            <a href="javascript:;"
                                               class="btn btn-success btn-xs btn-tindak"
                                               data-id="<?= $baris->id_cuti; ?>"
                                               data-status="<?= $baris->status; ?>"
                                               data-catatan="<?= htmlspecialchars($baris->catatan, ENT_QUOTES); ?>"
                                               data-sk="<?= $baris->sk_cuti_bympd; ?>"
                                               title="sTindak Lanjut" style="<?= $display; ?>">
                                                <i class="fa fa-forward"></i>
                                            </a>
                                            <?php
                                        }
                                        ?>






                                        <?php
                                        if ($level == 'notaris' || $level=="superadmin" || $level=="petugas") {
                                            $allowed_levels_delete = ['superadmin', 'petugas'];
                                            $allowed_levels_edit = ['petugas', 'notaris'];
                                            if (($level == 'notaris' && $baris->status == 'pengajuan') || (in_array($level, $allowed_levels_delete) && $baris->status != 'pengajuan') ) { ?>
                                                <a href="javascript:;"
                                                   class="btn btn-danger btn-xs btn-hapus"
                                                   data-url="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/h/<?php echo hashids_encrypt($baris->id_cuti); ?>"
                                                   title="Hapus Permohonan Cuti">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>

                                            <?php }
                                            if($baris->status!='pengajuan' && in_array($level,$allowed_levels_edit)){ ?>
                                                <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/e/<?php echo hashids_encrypt($baris->id_cuti); ?>"
                                                   class="btn btn-warning btn-xs" title="Edit Cutii"><i
                                                            class="fa fa-edit"></i></a>
                                            <?php }
                                            ?>

                                        <?php }
                                        ?>
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
    <!-- Modal -->
    <?php $this->load->view('users/cuti/modal_tindak_lanjut'); ?>



</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hapusButtons = document.querySelectorAll('.btn-hapus');

        hapusButtons.forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url; // redirect ke controller hapus
                    }
                });
            });
        });
    });

    $(document).ready(function () {
        $('.btn-tindak').on('click', function () {
            var idCuti = $(this).data('id');
            var status = $(this).data('status');
            var catatan = $(this).data('catatan') || "";
            var fileSk  = $(this).data('sk') || "";
            var level   = "<?= $level ?>";

                  // isi form dasar
            $('#id_cuti').val(idCuti);
            $('#catatan_petugas').val(catatan);

            // 🔹 Hapus link SK lama agar tidak menumpuk
            $('#sk_cuti_bympd').next('.link-skcuti').remove();

            // 🔹 Tampilkan link SK lama jika ada
            if (fileSk && fileSk !== "null") {
                const baseUrl = "<?= base_url(); ?>";
                const fileLink = `
                <p class="mt-2 link-skcuti">
                    File saat ini:
                    <a href="${baseUrl}${fileSk}" target="_blank" class="btn btn-link">
                        <i class="fa fa-file"></i> Lihat SK Cuti
                    </a>
                </p>`;
                $('#sk_cuti_bympd').after(fileLink);
            }


            // 🔹 Atur nilai dropdown tindakan sesuai level
            if (level === "petugas") {
                let defaultVal = "";
                if (status === "dispo_mpd") defaultVal = "dispo_mpd";
                else if (status === "approve") defaultVal = "approve";
                else if (status === "decline") defaultVal = "decline";
                else defaultVal = "dispo_mpd";
                $('#aksi').val(defaultVal).trigger('change');
            } else {
                $('#aksi').val(status).trigger('change');
            }

            // Tampilkan modal
            $('#modalTindakLanjut').modal('show');

        });

        // --- inisialisasi DataTable + filter realtime
        var table = $('#data-table').DataTable();

        $('#stt').on('change', function () {
            var val = $(this).val();
            if (val) {
                // Cari teks apa adanya (case-insensitive), tanpa regex ketat
                table.column(5).search(val, true, false).draw();
            } else {
                table.column(5).search('').draw();
            }
        });
    });


    /* $(document).ready(function () {
         $('.btn-tindak').on('click', function () {
             var idCuti = $(this).data('id');       // ambil id_cuti
             var status = $(this).data('status');   // ambil status

             $('#id_cuti').val(idCuti);             // isi ke input hidden
             $('#aksi').val(status).trigger('change'); // auto-select opsi sesuai status

             $('#modalTindakLanjut').modal('show'); // tampilkan modal
         });
     });
 */

</script>

<!-- end #content -->

<?php $this->load->view('users/laporan/modal_konfirm'); ?>
