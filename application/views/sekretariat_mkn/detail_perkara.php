<div class="container">
    <ol class="breadcrumb pull-right">
        <li><a href="<?= site_url('sekretariat_mkn'); ?>">Dashboard</a></li>
        <li class="active">Detail Perkara</li>
    </ol>
    <h3>Detail Perkara</h3>

    <?php if($this->session->flashdata('msg')) echo $this->session->flashdata('msg'); ?>

    <?php if (empty($perkara)): ?>
        <div class="alert alert-warning">Data perkara tidak ditemukan.</div>
        <a href="<?= site_url('sekretariat_mkn'); ?>" class="btn btn-default">&laquo; Kembali</a>
        <?php return; ?>
    <?php endif; ?>

    <table class="table table-bordered">
        <tr><th style="width:220px;">Nama Notaris</th><td><?= html_escape($perkara->nama_notaris); ?></td></tr>
        <tr><th>Nomor Akta</th><td><?= html_escape($perkara->nomor_akta); ?></td></tr>
        <tr><th>Alamat Notaris</th><td><?= nl2br(html_escape($perkara->alamat_notaris)); ?></td></tr>
        <tr><th>Kronologi</th><td><?= nl2br(html_escape($perkara->kronologi)); ?></td></tr>
        <tr><th>Tahapan</th><td><?= html_escape($perkara->tahapan); ?></td></tr>
        <tr><th>Status</th><td><?= html_escape($perkara->status); ?></td></tr>
        <tr><th>Tanggal Pengajuan</th><td><?= !empty($perkara->tgl_pengajuan) ? date('d/m/Y H:i', strtotime($perkara->tgl_pengajuan)) : '-'; ?></td></tr>
        <tr>
            <th>Lampiran Permohonan</th>
            <td>
                <?php if(!empty($perkara->lampiran_surat)): ?>
                    <a class="btn btn-xs btn-default" target="_blank" href="<?= base_url($perkara->lampiran_surat); ?>">Lihat</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <div class="text-right" style="margin-bottom:10px;">
        <a class="btn btn-success" href="<?= site_url('sekretariat_mkn/surat/'.$perkara->id_perkara); ?>">Buat Surat</a>
        <a class="btn btn-default" href="<?= site_url('sekretariat_mkn'); ?>">Kembali</a>
    </div>

    <h4>Surat Terkait Perkara Ini</h4>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>#</th><th>Jenis</th><th>No. Surat</th><th>Perihal</th><th>Lampiran</th><th>Status Dibawa</th><th>Dibuat</th><th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($surat)): $no=1; foreach($surat as $s): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= html_escape($s->jenis_surat); ?></td>
                <td><?= html_escape($s->no_surat); ?></td>
                <td><?= html_escape($s->perihal); ?></td>
                <td>
                    <?php if(!empty($s->lampiran_path)): ?>
                        <a target="_blank" class="btn btn-xs btn-default" href="<?= base_url($s->lampiran_path); ?>">Lihat</a>
                    <?php else: ?>-<?php endif; ?>
                </td>
                <td><?= html_escape($s->status_bawa); ?></td>
                <td><?= !empty($s->tgl_surat) ? date('d/m/Y H:i', strtotime($s->tgl_surat)) : '-'; ?></td>
                <td>
                    <a class="btn btn-xs btn-info" href="<?= site_url('sekretariat_mkn/surat/bawa/'.$s->id_surat); ?>">Toggle Dibawa</a>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="8" class="text-center">Belum ada surat.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
