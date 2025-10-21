<?php
// Pastikan $user & $perkara dikirim dari controller
$cek = isset($user) && is_object($user) ? $user->row() : null;
$levelTeks = $cek ? ucwords($cek->level) : 'Sekretariat';
?>
<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li class="active">Dashboard</li>
    </ol>

    <h1 class="page-header">Dashboard Sekretariat MKN <small><?= $levelTeks; ?></small></h1>

    <?= $this->session->flashdata('msg'); ?>

    <!-- =======================
         Daftar Permohonan dari APH
         Sumber data: $perkara (Mkn_model->getAll())
         Kolom: nama_notaris, nomor_akta, tahapan, tgl_pengajuan, lampiran_surat, id_perkara
    ======================== -->
    <div class="panel panel-inverse">
        <div class="panel-heading"><h4 class="panel-title">Daftar Permohonan dari APH</h4></div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Notaris</th>
                    <th>Nomor Akta</th>
                    <th>Tahapan</th>
                    <th>Tanggal Permohonan</th>
                    <th>Lampiran</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($perkara) && is_array($perkara)): $no = 1; ?>
                    <?php foreach ($perkara as $p): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= html_escape($p->nama_notaris); ?></td>
                            <td><?= html_escape($p->nomor_akta); ?></td>
                            <td><?= html_escape($p->tahapan); ?></td>
                            <td><?= !empty($p->tgl_pengajuan) ? date('d/m/Y', strtotime($p->tgl_pengajuan)) : '-'; ?></td>
                            <td>
                                <?php if (!empty($p->lampiran_surat)): ?>
                                    <a href="<?= base_url($p->lampiran_surat); ?>" target="_blank" class="btn btn-xs btn-default">Lihat</a>
                                <?php else: ?>-<?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= site_url('sekretariat_mkn/surat/'.$p->id_perkara); ?>" class="btn btn-primary btn-xs">
                                    Buat Surat
                                </a>
                                <a href="<?= site_url('sekretariat_mkn/detail/'.$p->id_perkara); ?>" class="btn btn-info btn-xs">
                                    Detail
                                </a>
                                <?php if (strtolower($p->tahapan) === 'penyelidikan'): ?>
                                    <a href="<?= site_url('sekretariat_mkn/kirim_ke_anggota/'.$p->id_perkara); ?>"
                                       class="btn btn-warning btn-xs"
                                       onclick="return confirm('Kirim perkara ini ke Anggota MKN (ubah tahapan ke penyidikan)?');">
                                        Kirim ke Anggota
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">Belum ada permohonan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- =======================
         Daftar Surat Sekretariat
         Ambil dari alias VIEW `tbl_surat` (alias ke tbl_mkn_surat)
         Kolom: id_surat, id_perkara, jenis_surat, no_surat, perihal, lampiran_path, status_bawa, tgl_surat
         Toggle status: sekretariat_mkn/surat/bawa/{id_surat}
    ======================== -->
    <div class="panel panel-inverse">
        <div class="panel-heading"><h4 class="panel-title">Daftar Surat Sekretariat</h4></div>
        <div class="panel-body">
            <?php $surat = $this->db->order_by('id_surat', 'DESC')->get('tbl_surat')->result(); ?>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Surat</th>
                    <th>No. Surat</th>
                    <th>Perihal</th>
                    <th>Lampiran</th>
                    <th>Status Dibawa</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($surat)): $no=1; foreach($surat as $s): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= html_escape($s->jenis_surat); ?></td>
                        <td><?= html_escape($s->no_surat); ?></td>
                        <td><?= html_escape($s->perihal); ?></td>
                        <td>
                            <?php if(!empty($s->lampiran_path)): ?>
                                <a href="<?= base_url($s->lampiran_path); ?>" target="_blank" class="btn btn-xs btn-default">Lihat</a>
                            <?php else: ?>-<?php endif; ?>
                        </td>
                        <td><?= html_escape($s->status_bawa); ?></td>
                        <td>
                            <a href="<?= site_url('sekretariat_mkn/surat/bawa/'.$s->id_surat); ?>" class="btn btn-xs btn-info">
                                Toggle Dibawa
                            </a>
                            <a href="<?= site_url('sekretariat_mkn/surat/'.$s->id_perkara); ?>" class="btn btn-xs btn-primary">
                                Perkara
                            </a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center">Belum ada surat.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
