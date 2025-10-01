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
                    <h4 class="panel-title">TABEL DAFTAR PERSURATAN</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12"><b>Filter Status Surat</b></div>
                        <div class="col-md-3">
                            <select class="form-control default-select2" id="stt">
                                <option value="">- Semua -</option>
                                <option value="belum diproses" <?php if ('proses' == $link3) {
                                    echo "selected";
                                } ?>>Belum Diproses
                                </option>
                                <option value="sedang diproses" <?php if ('konfirmasi' == $link3) {
                                    echo "selected";
                                } ?>>Sedang Diproses
                                </option>
                                <option value="sudah diproses" <?php if ('selesai' == $link3) {
                                    echo "selected";
                                } ?>>Sudah Diproses
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6"></div>
                        <div class="col-md-2">
                            <?php if ($level == 'notaris'): ?>
                                <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/t.html"
                                   class="btn btn-primary" style="float:right;">Buat Laporan</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr>
                    <div class="table-responsive">
                        <div class="custom-tabs" id="jenisTab">
                            <button class="tab-item active" data-filter="">
                                <span class="label">Semua</span>
                                <span class="badge badge-gray"><?= $jumlah_surat_total; ?></span>
                            </button>

                            <button class="tab-item" data-filter="surat masuk">
                                <span class="label">Surat Masuk</span>
                                <span class="badge badge-blue"><?= $jumlah_surat_masuk; ?></span>
                            </button>

                            <button class="tab-item" data-filter="surat keluar">
                                <span class="label">Surat Keluar</span>
                                <span class="badge badge-orange"><?= $jumlah_surat_keluar; ?></span>
                            </button>


                        </div>



                        <table id="data-table" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul Surat</th>
                                <th>Jenis Surat</th>
                                <th>Status</th>
                                <th>Opsi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $no = 1;
                            foreach ($query->result() as $baris): ?>
                                <tr>
                                    <td><b><?= $no++; ?>.</b></td>
                                    <td><?php echo $baris->judul_surat; ?></td>
                                    <td><?php echo strtolower($baris->jenis_surat); ?></td>
                                    <!-- lowercase agar konsisten -->
                                    <td><?php echo $baris->status; ?></td>
                                    <td align="center">
                                        <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/d/<?php echo hashids_encrypt($baris->id_persuratan); ?>"
                                           class="btn btn-info btn-xs" title="Detailnya"><i class="fa fa-search"></i></a>
                                        <?php if ($level == 'superadmin') { ?>
                                            <?php if ($baris->status == 'proses') { ?>
                                                <a href="javascript:;" class="btn btn-primary btn-xs" title="Konfirmasinya"
                                                   data-toggle="modal"
                                                   onclick="modal_show(<?php echo $baris->id_persuratan; ?>);"><i
                                                            class="fa fa-file"></i> Konfirmasinyo</a>
                                            <?php } else { ?>
                                                <a href="javascript:;" onclick="modal_show(<?= $baris->id_persuratan?>)" class="btn btn-success btn-xs"
                                                   title="Terkonfirmasi" ><i class="fa fa-check"></i> Konfirmasinyas</a>
                                            <?php } ?>
                                        <?php } elseif ($level == 'petugas') { ?>
                                            <?php //if ($baris->status=='konfirmasi'){ ?>
                                            <a class="btn btn-success btn-xs" title="Edit" data-toggle="modal"
                                               onclick="modal_show(<?php echo $baris->id_persuratan; ?>);"><i
                                                        class="fa fa-pencil"></i> Edit</a>
                                            <?php //}else{ ?>
                                            <!-- <a href="javascript:;" class="btn btn-success btn-xs" title="Edit" disabled><i class="fa fa-check"></i> Edit</a> -->
                                            <?php //} ?>
                                        <?php } else { ?>
                                            <?php if ($baris->status == 'proses') { ?>
                                                <a href="<?php echo strtolower($this->uri->segment(1)); ?>/<?php echo strtolower($this->uri->segment(2)); ?>/h/<?php echo hashids_encrypt($baris->id_laporan); ?>"
                                                   class="btn btn-danger btn-xs" title="Hapus"
                                                   onclick="return confirm('Anda yakin?');"><i
                                                            class="fa fa-trash-o"></i></a>
                                            <?php } else { ?>
                                                <a href="javascript:;" class="btn btn-danger btn-xs" title="Hapus"
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
<?php $this->load->view('users/persuratan/modal_konfirm'); ?>
<script>
    $(document).ready(function () {
        var selectedJenis = "";   // dari tab
        var selectedStatus = "";  // dari dropdown

        var table = $('#data-table').DataTable({
            responsive: true,
            columnDefs: [{
                targets: 0,
                searchable: false,
                orderable: false
            }],
            order: [[1, 'asc']], // urut berdasarkan judul surat
            drawCallback: function (settings) {
                var api = this.api();

                // --- Update nomor urut ---
                api.column(0, {search: 'applied', order: 'applied'})
                    .nodes()
                    .each(function (cell, i) {
                        cell.innerHTML = (i + 1) + '.';
                    });

                // --- Update badge count ---
                updateBadges(api);
            }
        });

        // --- Custom Combine Filter (Jenis + Status) ---
        if (!$.fn.dataTable.ext.search._customFilterAdded) {
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var jenis = (data[2] || '').trim().toLowerCase();   // kolom ke-3 = Jenis Surat
                var status = (data[3] || '').trim().toLowerCase();  // kolom ke-4 = Status

                var matchJenis = !selectedJenis || jenis === selectedJenis;
                var matchStatus = !selectedStatus || status === selectedStatus;

                return matchJenis && matchStatus;
            });
            $.fn.dataTable.ext.search._customFilterAdded = true;
        }

        // --- Event Tab Klik (Jenis Surat) ---
        $('#jenisTab .tab-item').on('click', function () {
            $('#jenisTab .tab-item').removeClass('active');
            $(this).addClass('active');

            selectedJenis = $(this).data('filter').toLowerCase().trim();
            if (selectedJenis === "semua" || selectedJenis === "") {
                selectedJenis = "";
            }

            table.draw();
        });

        // --- Event Dropdown (Status Surat) ---
        $('#stt').on('change', function () {
            selectedStatus = $(this).val().toLowerCase().trim();
            table.draw();
        });

        // --- Fungsi untuk update badge ---
        function updateBadges(api) {
            var countTotal = 0;
            var countMasuk = 0;
            var countKeluar = 0;

            // Loop semua data tabel (bukan hanya yg tampil di halaman)
            api.rows().every(function () {
                var data = this.data();
                var jenis = (data[2] || '').trim().toLowerCase();
                var status = (data[3] || '').trim().toLowerCase();

                // Hitung sesuai status yg dipilih
                if (!selectedStatus || status === selectedStatus) {
                    countTotal++;
                    if (jenis === 'surat masuk') countMasuk++;
                    if (jenis === 'surat keluar') countKeluar++;
                }
            });

            // Update badge sesuai hasil hitung
            $('#jenisTab .tab-item[data-filter=""] .badge').text(countTotal);
            $('#jenisTab .tab-item[data-filter="surat masuk"] .badge').text(countMasuk);
            $('#jenisTab .tab-item[data-filter="surat keluar"] .badge').text(countKeluar);
        }

        // Trigger pertama kali
        table.draw();
    });
</script>





