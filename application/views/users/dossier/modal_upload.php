<!-- Modal Upload -->
<div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" aria-labelledby="modalUploadLabel">
    <div class="modal-dialog" role="document">
        <form method="post" enctype="multipart/form-data" action="<?= site_url('users/unggah_file') ?>">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modalUploadLabel">Unggah File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- hidden ID file -->
                    <input type="hidden" name="file_id" id="upload_id">
                    <input type="hidden" name="user_id" value="<?= $this->session->userdata('id_user'); ?>">

                    <div class="form-group">
                        <label>Nama File</label>
                        <input type="text" class="form-control" id="upload_nama" readonly>
                    </div>

                    <div class="form-group">
                        <label>Pilih File</label>
                        <!-- Wajib pakai name="file_upload" -->
                        <input type="file" name="file_upload" class="form-control" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Upload</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
