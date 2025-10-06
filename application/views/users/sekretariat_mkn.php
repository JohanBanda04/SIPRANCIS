<?php
$cek = $user->row();
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li class="active">Dashboard</li>
    </ol>

    <h1 class="page-header">Dashboard Sekretariat MKN <small><?= ucwords($cek->level); ?></small></h1>

    <?= $this->session->flashdata('msg'); ?>

    <!-- Daftar Permohonan dari APH -->
    <div class="panel panel-inverse">
        <div class="panel-heading"><h4 class="panel-title">Daftar Permohonan dari APH</h4></div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Notaris</th>
                        <th>Nama APH</th>
                        <th>Tahapan</th>
                        <th>Tanggal Permohonan</th>
                        <th>Lampiran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($perkara)): $no = 1; foreach ($perkara as $p): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $p->nama_notaris; ?></td>
                        <td><?= $p->nama_aph; ?></td>
                        <td><?= $p->tahapan; ?></td>
                        <td><?= date('d/m/Y', strtotime($p->created_at)); ?></td>
                        <td>
                            <a href="<?= base_url('uploads/permohonan/'.$p->lampiran_surat); ?>" target="_blank">Lihat</a>
                        </td>
                        <td>
                            <a href="<?= base_url('sekretariat/buat_surat/'.$p->id_perkara); ?>" class="btn btn-primary btn-sm">
                                Buat Surat
                            </a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center">Belum ada permohonan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daftar Surat -->
    <div class="panel panel-inverse">
        <div class="panel-heading"><h4 class="panel-title">Daftar Surat Sekretariat</h4></div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Surat</th>
                        <th>Lampiran</th>
                        <th>Status Dibawa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $surat = $this->db->get('tbl_surat')->result();
                if (!empty($surat)): $no=1; foreach($surat as $s): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $s->jenis_surat; ?></td>
                        <td>
                            <a href="<?= base_url('uploads/surat/'.$s->lampiran_file); ?>" target="_blank">Download</a>
                        </td>
                        <td>
                            <form action="<?= base_url('sekretariat/ubah_status/'.$s->id_surat); ?>" method="post">
                                <select name="status_dibawa" onchange="this.form.submit()" class="form-control">
                                    <option value="Belum" <?= ($s->status_dibawa=='Belum')?'selected':''; ?>>Belum</option>
                                    <option value="Sudah" <?= ($s->status_dibawa=='Sudah')?'selected':''; ?>>Sudah</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="<?= base_url('uploads/surat/'.$s->lampiran_file); ?>" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center">Belum ada surat.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
