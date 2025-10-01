<?php
$link1 = strtolower($this->uri->segment(1));
$link2 = strtolower($this->uri->segment(2));
$link3 = strtolower($this->uri->segment(3));
$link4 = strtolower($this->uri->segment(4));
?>
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
            ?>
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="notaris/v/cetak" class="btn btn-success btn-xs" target="_blank"><i
                                    class="fa fa-file"></i> Cetak</a>
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

                    <!-- Filter dropdown -->
                    <div class="row" style="margin-bottom:10px;">
                        <div class="col-md-3">
                            <select id="filter-daerah" class="form-control">
                                <option value="">- Pilih Daerah Kedudukan -</option>
                                <?php foreach ($daerah_kedudukan->result() as $row): ?>
                                    <option value="<?= strtolower(trim($row->tempat_kedudukan)); ?>">
                                        <?= $row->tempat_kedudukan; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <table id="data-table" class="table table-striped table-bordered dt-responsive nowrap"
                           style="width:100%">
                        <thead>
                        <tr>
                            <th width="1%">No.</th>
                            <th>Nama</th>
                            <th>Kedudukan</th>
                            <th width="12%">No. SK</th>
                            <th>Alamat</th>
                            <th width="15%">No.Telp</th>
                            <th width="15%">Email</th>
                            <th width="10%">Detail</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1;
                        foreach ($query->result() as $baris): ?>
                            <tr>
                                <!--<td><b><? /*= $no++; */ ?>.</b></td>-->
                                <td></td>
                                <td><?= $baris->nama; ?></td>
                                <td><?= $baris->tempat_kedudukan ?? $baris->tempat_kedudukan_old; ?></td>
                                <td><?= $baris->no_sk; ?></td>
                                <td><?= $baris->alamat_notaris; ?></td>
                                <td><?= $baris->telpon; ?></td>
                                <td><?= $baris->email_notaris; ?></td>
                                <td align="center">
                                    <a href="<?= $link1; ?>/<?= $link2; ?>/d/<?= hashids_encrypt($baris->id_user); ?>"
                                       class="btn btn-info btn-xs" title="Detail">
                                        <i class="fa fa-search"></i>
                                    </a>
                                    <?php if ($level == 'superadmin'): ?>
                                        <a href="<?= $link1; ?>/<?= $link2; ?>/h/<?= hashids_encrypt($baris->id_user); ?>"
                                           class="btn btn-danger btn-xs" title="Hapus"
                                           onclick="return confirm('Anda yakin?');">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php
                                    if ($this->session->userdata('level') == "user") {
                                        $displayNone = 'display: none!important';
                                    } else {
                                        $displayNone = '';
                                    }
                                    ?>
                                    <a style="<?= $displayNone; ?>"
                                       href="<?php echo $link1; ?>/<?php echo $link2; ?>/l/<?php echo hashids_encrypt($baris->id_user); ?>"
                                       class="btn btn-primary btn-xs" title="Lihat Dossier">
                                        <i class="fa fa-folder-open"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->
<script>
    $(document).ready(function () {
        var selectedDaerah = '';

        // Tambahkan filter khusus satu kali saja
        if (!$.fn.dataTable.ext.search._customFilterKedudukan) {
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var kedudukan = (data[2] || '').toLowerCase(); // Kolom ke-3 (Kedudukan)
                if (!selectedDaerah) return true;
                return kedudukan === selectedDaerah;
            });

            // Flag agar tidak ditambahkan berkali-kali
            $.fn.dataTable.ext.search._customFilterKedudukan = true;
        }

        // Ambil instance DataTable yang sudah ada
        //var table = $('#data-table').DataTable();
        var table = $.fn.dataTable.isDataTable('#data-table')
            ? $('#data-table').DataTable()   // jika sudah diinisialisasi, ambil instance-nya
            : $('#data-table').DataTable({
                responsive: true,
                columnDefs: [{
                    targets: 0, // Kolom pertama (No.)
                    searchable: false,
                    orderable: false,
                }],
                order: [[1, 'asc']], // Urutkan default berdasarkan nama (misalnya)
                drawCallback: function (settings) {
                    var api = this.api();
                    api.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = (i + 1) + '.'; // format 1., 2., 3., ...
                    });
                }
            });  // jika belum, inisialisasi

        // Event: Ketika dropdown filter berubah
        $('#filter-daerah').on('change', function () {
            selectedDaerah = $(this).val().toLowerCase().trim();
            table.draw(); // Redraw table untuk apply filter
        });

        // Global search tetap aktif, tidak perlu dimodifikasi
    });
</script>


<!-- Inisialisasi old version DataTables khusus untuk halaman ini -->
<!--<script>-->
<!--    $(document).ready(function () {-->
<!--        // Destroy jika sudah ada-->
<!--        if ($.fn.DataTable.isDataTable('#data-table')) {-->
<!--            $('#data-table').DataTable().destroy();-->
<!--        }-->
<!---->
<!--        // Inisialisasi DataTable-->
<!--        var table = $('#data-table').DataTable({-->
<!--            responsive: true-->
<!--        });-->
<!---->
<!--        // Variabel penyimpanan filter dropdown-->
<!--        var selectedDaerah = '';-->
<!---->
<!--        // Tambahkan fungsi filter khusus berdasarkan Kedudukan (kolom ke-2)-->
<!--        $.fn.dataTable.ext.search.push(-->
<!--            function (settings, data, dataIndex) {-->
<!--                // Ambil isi kolom Kedudukan (kolom ke-3, index ke-2)-->
<!--                var kedudukan = data[2].toLowerCase();-->
<!---->
<!--                // Jika tidak ada filter daerah, tampilkan semua-->
<!--                if (!selectedDaerah) {-->
<!--                    return true;-->
<!--                }-->
<!---->
<!--                // Cocokkan nilai dropdown dengan kolom Kedudukan-->
<!--                return kedudukan === selectedDaerah;-->
<!--            }-->
<!--        );-->
<!---->
<!--        // Event saat dropdown berubah-->
<!--        $('#filter-daerah').off('change').on('change', function () {-->
<!--            var selectedOptionText = $(this).find('option:selected').text().trim().toLowerCase();-->
<!--            selectedDaerah = $(this).val() ? selectedOptionText : '';-->
<!--            table.draw(); // Trigger redraw untuk mengaktifkan filter-->
<!--        });-->
<!---->
<!--        // Event input global search-->
<!--        $('#data-table_filter input').off('keyup').on('keyup', function () {-->
<!--            table.search(this.value).draw();-->
<!--        });-->
<!--    });-->
<!--</script>-->


