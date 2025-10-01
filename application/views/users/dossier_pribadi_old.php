<?php
$user = $user->row();
$level = $user->level;

$foto_profile = "img/user/user-default.jpg";
if ($level == 'user') {
    $d_k = $this->db->get_where('tbl_data_user', array('id_user' => $user->id_user))->row();
    $foto_k = $d_k->foto;
    if ($foto_k != '') {
        if (file_exists("$foto_k")) {
            $foto_profile = $foto_k;
        }
    }
}
?>
<!-- Main content -->
<div class="content-wrapper">

    <!-- Content area -->
    <div class="content">

        <!-- Dashboard content -->
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <center>
                            <img src="<?php echo $foto_profile; ?>" alt="<?php echo $user->nama_lengkap; ?>"
                                 class="img-circle" width="176">
                        </center>
                        <br>
                        <fieldset class="content-group">
                            <hr style="margin-top:0px;">
                            <i class="icon-calendar"></i> <b>Tanggal Terdaftar</b>
                            : <?php echo $this->Mcrud->tgl_id(date('d-m-Y H:i:s', strtotime($user->tgl_daftar))); ?>
                        </fieldset>
                        </form>
                    </div>
                </div>

                <div class="panel panel-flat">
                    <div class="panel-body">
                        <fieldset class="content-group">
                            <legend class="text-bold"><i class="icon-user"></i>
                                <center>
                                    <?= $judul_web ?>
                                </center>
                            </legend>
                            <?php
                            echo $this->session->flashdata('msg');
                            ?>
                            <div class="table-responsive">
                                <style>
                                    .table.no-border th,
                                    .table.no-border td {
                                        border: none !important;
                                    }
                                </style>
                                <table class="table no-border" >
                                    <thead class="bg-primary-600">
                                    <tr>
                                        <th style="width:5%; text-align:center;">No.</th>
                                        <th style="width:40%;">Nama File</th>
                                        <th style="width:40%;">File</th>
                                        <th style="width:25%; text-align:center;">Opsi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $no = 1;
                                    foreach ($jenisFiles->result() as $index => $dt) {
                                        ?>
                                        <tr>
                                            <td><b><?= $no++; ?>.</b></td>
                                            <td><?= $dt->nama_file; ?></td>
                                            <td><!-- kalau ada file tampilkan di sini --></td>
                                            <td style="text-align:center;">
                                                <button type="button" class="btn btn-sm btn-success"
                                                        data-toggle="modal"
                                                        data-target="#modalUpload"
                                                        data-id="<?= $dt->id_file ?>"
                                                        data-nama="<?= $dt->nama_file ?>">
                                                    <i class="fa fa-upload"></i>
                                                </button>

                                                <!-- Tombol Hapus -->
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        data-toggle="modal" data-target="#modalHapus"
                                                        data-id="<?= $dt->id; ?>" data-nama="<?= $dt->nama_file; ?>">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>


        </div>
        <!-- /dashboard content -->
        <?php $this->load->view('users/dossier/modal_upload'); ?>
        <?php $this->load->view('users/dossier/konfirm_hapus'); ?>

        <script src="assets/js/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="assets/panel/plugin/datetimepicker/jquery.datetimepicker.css"/>
        <script src="assets/panel/plugin/datetimepicker/jquery.datetimepicker.js"></script>
        <script>
            $('#tgl_1').datetimepicker({
                lang: 'en',
                timepicker: false,
                format: 'd-m-Y'
            });
        </script>
        <script>
            $(document).ready(function(){
                // Modal Upload
                $('#modalUpload').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('id');         // file_id
                    var nama = button.data('nama');     // nama_file dari tbl_file
                    $('#upload_id').val(id);
                    $('#upload_nama').val(nama);
                });


                // Modal Hapus
                $('#modalHapus').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('id');
                    var nama = button.data('nama');
                    $('#hapus_id').val(id);
                    $('#hapus_nama').text(nama);
                });
            });
        </script>

