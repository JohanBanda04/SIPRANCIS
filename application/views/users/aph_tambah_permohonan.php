<div class="container mt-4">
    <h3>Tambah Permohonan Perkara</h3>
    <hr>

    <!-- Notifikasi pesan sukses / error -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Form Tambah Permohonan -->
    <?= form_open_multipart(base_url('aph/simpan_permohonan')); ?>

        <div class="form-group mb-3">
            <label>Nama Notaris</label>
            <input type="text" name="nama_notaris" class="form-control" placeholder="Masukkan nama notaris" required>
        </div>

        <div class="form-group mb-3">
            <label>Alamat Notaris</label>
            <textarea name="alamat_notaris" class="form-control" rows="2" placeholder="Alamat lengkap notaris" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label>Kronologi Singkat</label>
            <textarea name="kronologi" class="form-control" rows="3" placeholder="Tuliskan kronologi singkat perkara" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label>Nomor Akta</label>
            <input type="text" name="nomor_akta" class="form-control" placeholder="Masukkan nomor akta yang terkait" required>
        </div>

        <div class="form-group mb-3">
            <label>Tahapan</label>
            <select name="tahapan" class="form-select" required>
                <option value="">-- Pilih Tahapan --</option>
                <option value="Penyelidikan">Penyelidikan</option>
                <option value="Penyidikan">Penyidikan</option>
                <option value="Penuntutan">Penuntutan</option>
                <option value="Sidang">Sidang</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label>Lampiran Surat Permohonan (PDF/JPG/PNG)</label>
            <input type="file" name="lampiran_surat" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
            <small class="text-muted">Ukuran maksimal 3 MB.</small>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-paper-plane"></i> Kirim Permohonan
            </button>
            <a href="<?= base_url('aph/dashboard'); ?>" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

    <?= form_close(); ?>
</div>
