<h2>Buat Surat Baru</h2>
<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_perkara" value="<?= $perkara->id_perkara ?? '' ?>">
    <p>
        <label>Jenis Surat:</label>
        <select name="tipe_surat" required>
            <option value="Surat Keterangan">Surat Keterangan</option>
            <option value="Surat Pemanggilan">Surat Pemanggilan</option>
            <option value="Surat Undangan">Surat Undangan</option>
            <option value="Surat Putusan">Surat Putusan</option>
            <option value="Surat Jawaban">Surat Jawaban</option>
        </select>
    </p>
    <p>
        <label>Lampiran File (opsional):</label>
        <input type="file" name="file_surat">
    </p>
    <p>
        <button type="submit">Simpan Surat</button>
    </p>
</form>
