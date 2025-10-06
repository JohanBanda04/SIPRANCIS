<?php
$cek = $user->row();
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li><a href="<?= base_url('sekretariat/dashboard') ?>">Dashboard</a></li>
        <li class="active">Buat Surat</li>
    </ol>

    <h1 class="page-header">Buat Surat <small><?= ucwords($cek->level); ?></small></h1>

    <?= $this->session->flashdata('msg'); ?>

    <div class="panel panel-inverse">
        <div class="panel-heading"><h4 class="panel-title">Form Pembuatan Surat</h4></div>
        <div class="panel-body">
            <form action="<?= base_url('sekretariat/buat_surat/'.$perkara->id_perkara) ?>" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label>Nama Notaris</label>
                    <input type="text" class="form-control" value="<?= $perkara->nama_notaris; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tahapan</label>
                    <input type="text" class="form-control" value="<?= $perkara->tahapan; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Jenis Surat</label>
                    <select name="jenis_surat" class="form-control" required>
                        <option value="">-- Pilih Jenis Surat --</option>
                        <option value="Surat Keterangan">Surat Keterangan (Tahap Penyelidikan)</option>
                        <option value="Surat Pemanggilan">Surat Pemanggilan</option>
                        <option value="Surat Undangan">Surat Undangan</option>
                        <option value="Surat Putusan">Surat Putusan</option>
                        <option value="Surat Jawaban">Surat Jawaban</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Upload File Surat</label>
                    <input type="file" name="file_surat" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" required>
                </div>

                <input type="hidden" name="id_perkara" value="<?= $perkara->id_perkara; ?>">

                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Simpan Surat
                </button>
                <a href="<?= base_url('sekretariat/dashboard') ?>" class="btn btn-default">Kembali</a>
            </form>
        </div>
    </div>
</div>
