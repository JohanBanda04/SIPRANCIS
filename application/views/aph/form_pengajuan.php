<div class="container">
    <h3 style="margin-top:10px;">Form Pengajuan Perkara (APH)</h3>

    <?php if($this->session->flashdata('msg')): ?>
        <?= $this->session->flashdata('msg'); ?>
    <?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <form action="<?= site_url('aph/simpan_pengajuan'); ?>" method="post" enctype="multipart/form-data" novalidate>

                <?php // CSRF (jika diaktifkan di config)
                if (isset($this->security) && method_exists($this->security, 'get_csrf_token_name')): ?>
                    <input type="hidden"
                           name="<?= $this->security->get_csrf_token_name(); ?>"
                           value="<?= $this->security->get_csrf_hash(); ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Notaris <span class="text-danger">*</span></label>
                            <input type="text" name="nama_notaris" class="form-control" required placeholder="Nama Notaris">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor Akta (opsional)</label>
                            <input type="text" name="nomor_akta" class="form-control" placeholder="Contoh: AKT-001/2025">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Notaris <span class="text-danger">*</span></label>
                    <textarea name="alamat_notaris" class="form-control" rows="3" required placeholder="Alamat lengkap notaris"></textarea>
                </div>

                <div class="form-group">
                    <label>Kronologi <span class="text-danger">*</span></label>
                    <textarea name="kronologi" class="form-control" rows="5" required placeholder="Uraikan kronologi singkat"></textarea>
                </div>

                <div class="form-group">
                    <label>Lampiran (PDF/JPG/PNG, maks 4MB)</label>
                    <input type="file" name="lampiran" class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png">
                    <p class="help-block">Opsional—unggah surat/berkas pendukung.</p>
                </div>

                <div class="text-right">
                    <a href="<?= site_url('aph/pengajuan'); ?>" class="btn btn-default">Reset</a>
                    <button type="submit" class="btn btn-primary">Simpan Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    <?php // Opsional: jika nanti kamu kirim $riwayat dari controller, table ini akan muncul ?>
    <?php if (!empty($riwayat) && is_array($riwayat)): ?>
        <h4>Riwayat Pengajuan Saya</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Notaris</th>
                    <th>Nomor Akta</th>
                    <th>Tahapan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Lampiran</th>
                </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach($riwayat as $r): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= html_escape($r->nama_notaris); ?></td>
                        <td><?= html_escape($r->nomor_akta); ?></td>
                        <td><?= html_escape($r->tahapan); ?></td>
                        <td><?= html_escape($r->status); ?></td>
                        <td><?= html_escape($r->tgl_pengajuan); ?></td>
                        <td>
                            <?php if(!empty($r->lampiran_surat)): ?>
                                <a class="btn btn-xs btn-default" target="_blank" href="<?= base_url($r->lampiran_surat); ?>">Lihat</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
