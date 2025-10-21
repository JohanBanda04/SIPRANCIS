<?php
// header membutuhkan $user (opsional), tapi aman jika tak ada
$cek       = isset($user) && is_object($user) ? $user->row() : null;
$levelTeks = $cek ? ucwords($cek->level) : 'Anggota';

// Controller bisa mengirim $perkara atau $perkara_list
$rows = [];
if (isset($perkara) && is_array($perkara)) {
    $rows = $perkara;
} elseif (isset($perkara_list) && is_array($perkara_list)) {
    $rows = $perkara_list;
}
$total = count($rows);
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li class="active">Dashboard</li>
    </ol>

    <h1 class="page-header">Dashboard Anggota MKN <small><?= html_escape($levelTeks); ?></small></h1>

    <?= $this->session->flashdata('msg'); ?>

    <p>
        Daftar perkara pada tahapan <b>penyidikan</b>.
        <span class="label label-info">Total: <?= (int)$total; ?></span>
    </p>

    <div class="panel panel-inverse">
        <div class="panel-heading"><h4 class="panel-title">Perkara (Tahap Penyidikan)</h4></div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th style="width: 60px">#</th>
                    <th>Nama Notaris</th>
                    <th>Nomor Akta</th>
                    <th>Tahapan</th>
                    <th>Status</th>
                    <th>Tanggal Pengajuan</th>
                    <th style="width: 120px">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($rows)): $no=1; foreach($rows as $p): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= html_escape($p->nama_notaris); ?></td>
                        <td><?= html_escape($p->nomor_akta); ?></td>
                        <td><?= html_escape($p->tahapan); ?></td>
                        <td><?= html_escape($p->status); ?></td>
                        <td><?= !empty($p->tgl_pengajuan) ? date('d/m/Y', strtotime($p->tgl_pengajuan)) : '-'; ?></td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="<?= site_url('anggota_mkn/periksa/'.$p->id_perkara); ?>">
                                Periksa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center">Belum ada perkara untuk diperiksa.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
