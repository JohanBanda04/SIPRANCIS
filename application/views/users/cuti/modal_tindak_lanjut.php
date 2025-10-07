<div class="modal fade" id="modalTindakLanjut" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" enctype="multipart/form-data">
                <div class="modal-header btn-info">
                    <h5 class="modal-title" id="modalTitle">Disposisi Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id_cuti" id="id_cuti">
                    <div class="form-group">
                        <label for="aksi">Pilih Tindakan</label>
                        <?php if ($level == "superadmin") {
                            ?>
                            <select name="aksi" id="aksi" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="pengajuan">Pengajuan</option>
                                <option value="dispo_mpd">Disposisi cuti ke MPD</option>
                                <option value="decline">Tolak permohonan cuti</option>
                            </select>
                            <?php
                        } else if ($level == "petugas") { ?>
                            <select name="aksi" id="aksi" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="dispo_mpd">Disposisi cuti ke MPD</option>
                                <option value="approve">Setuju</option>
                                <option value="decline">Tolak</option>
                            </select>
                            <br>
                            <div class="form-group">
                                <label for="catatan_petugas">Catatan untuk Pemohon</label>
                                <textarea name="catatan_petugas" id="catatan_petugas" class="form-control" rows="3"
                                          placeholder="Tulis catatan atau alasan di sini..."></textarea>
                            </div>
                            <!-- 🔽 Tambahan: Upload SK Cuti -->
                            <div class="form-group">
                                <label for="sk_cuti_bympd">Upload SK Cuti (PDF/DOC, maks. 3MB)</label>
                                <input type="file" name="sk_cuti_bympd" id="sk_cuti_bympd" class="form-control">

                                <!-- tampilkan link jika sudah ada SK CUTI sebelumnya -->
                                <?php if (!empty($sk_cuti_bympd)): ?>
                                    <p class="mt-2">
                                        File saat ini:
                                        <a href="<?= base_url($sk_cuti_bympd); ?>" target="_blank" class="btn btn-link">
                                            <i class="fa fa-file"></i> Lihat SK Cuti
                                        </a>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php } ?>

                    </div>
                </div>

                <div class="modal-footer">
                    <?php
                    if ($level == "superadmin") { ?>
                        <button type="submit" name="btnupdate_status" class="btn btn-primary">Simpan</button>
                    <?php } else if ($level == "petugas") { ?>
                        <button type="submit" name="btnupdate_status_bympd" class="btn btn-info">Simpan</button>
                    <?php }
                    ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>

        </div>
    </div>
</div>