<?php
$cek    = $user->row();
$level  = $cek->level;
?>

<div id="content" class="content">
    <ol class="breadcrumb pull-right">
        <li class="active">Dashboard</li>
    </ol>

    <h1 class="page-header">Dashboard <small><?= ucwords($cek->level); ?></small></h1>

    <!-- Statistik / Info -->
    <div class="row">
        <?php
        $tahapan = ['Penyelidikan', 'Penyidikan', 'Penuntutan', 'Sidang'];
        $warna   = ['bg-info', 'bg-warning', 'bg-primary', 'bg-success'];
        foreach($tahapan as $i => $tahap):
            $jumlah = 0; // Tabel tbl_perkara tidak ada, set 0
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

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"><h4 class="panel-title">Informasi APH</h4></div>
                <div class="panel-body">
                    <p>Selamat datang, <?= ucwords($cek->username); ?>. Anda dapat memantau permohonan dan menindaklanjuti sesuai tugas APH.</p>
                </div>
            </div>
        </div>
    </div>
</div>
