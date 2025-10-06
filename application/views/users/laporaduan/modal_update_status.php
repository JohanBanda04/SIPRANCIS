<div class="modal fade" id="modalTindakLanjut" tabindex="-1" role="dialog" aria-labelledby="modalTindakLanjutLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" id="formTindakLanjut">
                <div class="modal-header btn-success" style="background-color: #102980!important;">
                    <h4 class="modal-title" id="modalTindakLanjutLabel" style="color: white">Tindak Lanjut Pengaduan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id_pengaduan" id="modalIdPengaduan">
                    <input type="hidden" name="remove_surat_penolakan" id="remove_surat_penolakan" value="0">
                    <input type="hidden" name="status_lama" id="modalStatusLama" value="">
                    <input type="hidden" name="btnupdate_status" value="1">
                    <input type="hidden" id="hasSuratPenolakan" value="0">

                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Nama Pelapor</th>
                            <td id="modalNamaPelapor"></td>
                        </tr>
                        <tr>
                            <th>Notaris Terlapor</th>
                            <td id="modalNamaNotaris"></td>
                        </tr>
                        <tr>
                            <th>Status Aduan</th>
                            <td>
                                <?php
                                $level = $this->session->userdata('level');
                                if ($level == "superadmin") {
                                    $statusOptions = [
                                        'tolak'     => 'DITOLAK',
                                        'proses'     => 'PROSES',
                                        'dispo_mpd'  => 'DISPOSISI KE MPD',
                                    ];
                                } elseif ($level == "petugas") {
                                    $statusOptions = [
                                        'proses'     => 'PROSES',
                                        'dispo_mpd'  => 'DISPOSISI KE MPD',
                                        'konfirmasi' => 'SEDANG DITANGANI',
                                        'tolak'      => 'DITOLAK',
                                        'selesai'    => 'SELESAI'
                                    ];
                                } elseif (in_array($level, ["notaris", "user"])) {
                                    $statusOptions = [
                                        'proses'     => 'PROSES',
                                        'dispo_mpd'  => 'DISPOSISI KE MPD',
                                        'konfirmasi' => 'SEDANG DITANGANI',
                                        'tolak'      => 'DITOLAK',
                                        'selesai'    => 'SELESAI'
                                    ];
                                } else {
                                    $statusOptions = [];
                                }
                                ?>
                                <select name="status" id="modalStatus" class="form-control">
                                    <?php foreach ($statusOptions as $value => $label): ?>
                                        <option value="<?= $value; ?>"><?= $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <?php if ($this->session->userdata('level') == "petugas"): ?>
                        <!-- wrapper tombol entry lampiran -->
                        <div id="btnLampiranWrapper" style="display:none; margin:10px 0;">
                            <button type="button" class="btn btn-sm btn-primary" id="btnTambahLampiran">
                                LAMPIRAN
                            </button>
                        </div>

                        <!-- container form lampiran -->
                    <?php
                    /*styleOverflow*/
                            $styleOverflow = 'style="
    display:inline-block;
    max-width:100%;
    word-wrap:break-word;
    white-space:normal;
    overflow-wrap:break-word;
"';

                        ?>
                        <div id="lampiranContainer" style="display:none; margin-top:15px;">
                            <h5>Lampiran Tambahan</h5>

                            <div class="form-group">
                                <label>SURAT PEMBERITAHUAN</label>
                                <input type="file" name="lampiran_pemberitahuan" class="form-control">
                                <small id="oldPemberitahuan" <?= $styleOverflow ?>></small>
                            </div>

                            <div class="form-group">
                                <label>SURAT UNDANGAN</label>
                                <input type="file" name="lampiran_undangan" class="form-control">
                                <small id="oldUndangan" <?= $styleOverflow ?>></small>
                            </div>

                            <div class="form-group">
                                <label>SURAT PEMANGGILAN</label>
                                <input type="file" name="lampiran_pemanggilan" class="form-control">
                                <small id="oldPemanggilan" <?= $styleOverflow ?>></small>
                            </div>

                            <div class="form-group">
                                <label>UNDANGAN TTD BAP</label>
                                <input type="file" name="lampiran_bap_ttd" class="form-control">
                                <small id="oldBapTtd" <?= $styleOverflow ?>></small>
                            </div>

                            <div class="form-group">
                                <label>BAP PEMERIKSAAN YG TELAH DI TANDATANGANI</label>
                                <input type="file" name="lampiran_bap_pemeriksaan" class="form-control">
                                <small id="oldBapPemeriksaan" <?= $styleOverflow ?>></small>
                            </div>

                        </div>
                    <?php endif; ?>


                    <?php if ($this->session->userdata('level') == "petugas" ||$this->session->userdata('level') == "superadmin" ): ?>
                        <div id="mpwContainer" style="display:none; margin-top:15px;">
                            <div id="mpwExistWrap" style="display:none; margin-bottom:10px;">
                                <div class="alert alert-info" style="margin-bottom:8px; padding:8px 12px;">
                                    <i class="fa fa-paperclip"></i> <strong>File saat ini:</strong>
                                    <a id="mpwExistLink" href="#" target="_blank" rel="noopener">Lihat / Unduh</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lampiran_laporan_mpw">SURAT LAPORAN KE MPW</label>
                                <input type="file"
                                       id="lampiran_laporan_mpw"
                                       name="lampiran_laporan_mpw"
                                       class="form-control"
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->userdata('level') == "petugas" || $this->session->userdata('level') == "superadmin"): ?>
                        <div id="penolakanContainer" style="display:none; margin-top:15px;">
                            <h5>Surat Penolakan</h5>
                            <div id="suratPenolakanExistWrap" style="display:none; margin-bottom:10px;">
                                <div class="alert alert-info" style="margin-bottom:8px; padding:8px 12px;">
                                    <i class="fa fa-paperclip"></i> <strong>File saat ini:</strong>
                                    <a id="suratPenolakanExistLink" href="#" target="_blank" rel="noopener">Lihat / Unduh</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="surat_penolakan">Unggah Surat Penolakan</label>
                                <input type="file" id="surat_penolakan" name="surat_penolakan" class="form-control"
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="btnupdate_status" class="btn btn-info" style="background-color:#0015ff!important;">
                        Update Status
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
