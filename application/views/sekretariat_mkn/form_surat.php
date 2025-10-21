<div class="container">
    <h3>Buat Surat Sekretariat</h3>
    <?php if($this->session->flashdata('msg')) echo $this->session->flashdata('msg'); ?>

    <?php if(!$perkara){ echo '<div class="alert alert-warning">Perkara tidak ditemukan.</div>'; return; } ?>

    <div class="panel panel-default">
        <div class="panel-heading"><b>Perkara</b></div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr><th>Nama Notaris</th><td><?= html_escape($perkara->nama_notaris); ?></td></tr>
                <tr><th>Nomor Akta</th><td><?= html_escape($perkara->nomor_akta); ?></td></tr>
                <tr><th>Tahapan</th><td><?= html_escape($perkara->tahapan); ?></td></tr>
                <tr><th>Status</th><td><?= html_escape($perkara->status); ?></td></tr>
            </table>
        </div>
    </div>

    <form action="<?= site_url('sekretariat_mkn/surat/simpan'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_perkara" value="<?= (int)$perkara->id_perkara; ?>">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Jenis Surat <span class="text-danger">*</span></label>
                    <select name="jenis_surat" class="form-control" required>
                        <option value="">-- pilih --</option>
                        <?php foreach($jenis_surat_options as $k=>$v): ?>
                            <option value="<?= $k; ?>"><?= $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>No. Surat <span class="text-danger">*</span></label>
                    <input type="text" name="no_surat" class="form-control" required placeholder="001/MKN/I/2025">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Perihal</label>
            <input type="text" name="perihal" class="form-control" placeholder="Perihal singkat...">
        </div>

        <div class="form-group">
            <label>Lampiran (PDF, maks 4MB)</label>
            <input type="file" name="lampiran" class="form-control" accept=".pdf">
        </div>

        <div class="row">
            <div class="col-md-6">
                <label>Ditujukan ke (role)</label>
                <select name="ditujukan_ke_role" class="form-control">
                    <option value="notaris">Notaris</option>
                    <option value="anggota_mkn">Anggota MKN</option>
                    <option value="aph">APH</option>
                    <option value="mpd">MPD</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>Ditujukan ke (ID user/relasi)</label>
                <input type="number" name="ditujukan_ke_id" class="form-control" placeholder="opsional">
            </div>
        </div>

        <div class="text-right" style="margin-top:10px;">
            <a href="<?= site_url('sekretariat_mkn'); ?>" class="btn btn-default">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Surat</button>
        </div>
    </form>

    <hr>
    <h4>Surat Terkait Perkara Ini</h4>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>#</th><th>Jenis</th><th>No. Surat</th><th>Perihal</th><th>Lampiran</th><th>Status Dibawa</th><th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($list_surat)): $no=1; foreach($list_surat as $s): ?>
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
                <td>
                    <a class="btn btn-xs btn-info" href="<?= site_url('sekretariat_mkn/surat/bawa/'.$s->id_surat); ?>">
                        Toggle Dibawa
                    </a>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="7" class="text-center">Belum ada surat.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
