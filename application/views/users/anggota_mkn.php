<?php
$cek    = $user->row();
$level  = $cek->level;
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li class="active">Dashboard</li>
    </ol>

    <h1 class="page-header">Dashboard <small><?= ucwords($cek->level); ?></small></h1>

    <!-- Statistik Proses Perkara -->
    <div class="row">
        <?php
        $tahapan = ['Penyelidikan', 'Penyidikan', 'Penuntutan', 'Sidang'];
        $warna   = ['bg-info', 'bg-warning', 'bg-primary', 'bg-success'];
        foreach($tahapan as $i => $tahap):
            $jumlah = $this->db->get_where('tbl_perkara', ['tahapan'=>$tahap])->num_rows();
            ?>
            <div class="col-md-3">
                <div class="widget widget-stats <?= $warna[$i] ?> text-inverse">
                    <div class="stats-icon stats-icon-lg stats-icon-square bg-gradient-black">
                        <i class="fa fa-file-text"></i>
                    </div>
                    <div class="stats-title"><?= strtoupper($tahap) ?></div>
                    <div class="stats-number"><?= number_format($jumlah,0,",","."); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Form Permohonan APH -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"><h4 class="panel-title">Form Permohonan APH</h4></div>
                <div class="panel-body">
                    <form action="<?= base_url('sekretariat/tambah_permohonan') ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nama Notaris</label>
                            <input type="text" name="nama_notaris" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat Notaris</label>
                            <textarea name="alamat_notaris" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Kronologi Perkara (Singkat)</label>
                            <textarea name="kronologi" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Nomor Akta</label>
                            <input type="text" name="nomor_akta" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Surat Permohonan (Lampiran)</label>
                            <input type="file" name="surat_permohonan" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Kirim Permohonan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Surat / Notifikasi -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"><h4 class="panel-title">Alur Proses Surat / Perkara</h4></div>
                <div class="panel-body">
                    <ul>
                        <li>Sekretariat MKN → Pemanggilan Pemeriksaan → Notaris Terlapor</li>
                        <li>Sekretariat MKN → Undangan Pemeriksaan → Anggota MKN sesuai SK Ketua</li>
                        <li>Sekretariat MKN → Surat Putusan Hasil Pemeriksaan → Semua Anggota MKN & Notaris Terlapor</li>
                        <li>Sekretariat MKN → Surat Jawaban Ketua MKN → APH</li>
                        <li>Sekretariat MKN → Putusan Pengadilan (Jika sampai pidana) → MPD</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
