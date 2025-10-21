<div class="container">
    <ol class="breadcrumb pull-right">
        <li class="active">Dashboard Anggota</li>
    </ol>
    <h3>Dashboard Anggota MKN</h3>

    <?= $this->session->flashdata('msg'); ?>

    <p>Daftar perkara pada tahapan <b>penyidikan</b>:</p>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Nama Notaris</th>
            <th>Nomor Akta</th>
            <th>Tahapan</th>
            <th>Status</th>
            <th>Tanggal Pengajuan</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($perkara)): $no=1; foreach($perkara as $p): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= html_escape($p->nama_notaris); ?></td>
                <td><?= html_escape($p->nomor_akta); ?></td>
                <td><?= html_escape($p->tahapan); ?></td>
                <td><?= html_escape($p->status); ?></td>
                <td><?= !empty($p->tgl_pengajuan) ? date('d/m/Y', strtotime($p->tgl_pengajuan)) : '-'; ?></td>
                <td>
                    <a class="btn btn-xs btn-primary" href="<?= site_url('anggota_mkn/periksa/'.$p->id_perkara); ?>">Periksa</a>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="7" class="text-center">Belum ada perkara untuk diperiksa.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
