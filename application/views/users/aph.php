<?php
$level = $user->level;
?>

<div id="content" class="content">
    <!-- Breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="active">Dashboard</li>
    </ol>

    <h1 class="page-header">
        Dashboard <small><?= ucwords($level); ?></small>
    </h1>

    <!-- Statistik Proses Perkara -->
    <div class="row">
        <?php
        $tahapan = ['Penyelidikan', 'Penyidikan', 'Penuntutan', 'Sidang'];
        $warna   = ['bg-info', 'bg-warning', 'bg-primary', 'bg-success'];

        foreach ($tahapan as $i => $tahap):
            $jumlah = 0; // TODO: ganti dengan query hitung jumlah perkara sesuai tahapan
        ?>
            <div class="col-md-3">
                <div class="widget widget-stats <?= $warna[$i]; ?> text-inverse">
                    <div class="stats-icon stats-icon-lg stats-icon-square bg-gradient-black">
                        <i class="fa fa-file-text"></i>
                    </div>
                    <div class="stats-title"><?= strtoupper($tahap); ?></div>
                    <div class="stats-number"><?= number_format($jumlah, 0, ",", "."); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Alur Proses Surat / Perkara -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Alur Proses Surat / Perkara</h4>
                </div>
                <div class="panel-body">
                    <ul>
                        <li>Mengirim <b>Surat Permohonan</b> kepada MKN untuk memeriksa notaris.</li>
                        <li>Menunggu tanggapan MKN:
                            <ul>
                                <li>Penyelidikan → menerima <b>Surat Keterangan</b> (tidak kewenangan MKN).</li>
                                <li>Penyidikan / Penuntutan → menerima <b>Surat Jawaban Ketua MKN</b> berisi hasil pemeriksaan.</li>
                            </ul>
                        </li>
                        <li>Melanjutkan proses hukum sesuai hasil dari MKN.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
