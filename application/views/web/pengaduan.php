<!-- BEGIN: PAGE CONTAINER -->
<div class="c-layout-page">
  <!-- BEGIN: LAYOUT/BREADCRUMBS/BREADCRUMBS-2 -->
	<!-- <div class="c-layout-breadcrumbs-1 c-subtitle c-fonts-uppercase c-fonts-bold c-bordered c-bordered-both">
		<div class="container">
			<div class="c-page-title c-pull-left">
				<h3 class="c-font-uppercase c-font-sbold">Halaman Pengaduan</h3>
			</div>
		</div>
	</div> -->
  <div class="panel-heading" style="text-align: center;">
    <div class="alert alert-info">
      <h4 class="panel-title">SILAHKAN LOGIN/DAFTAR TERLEBIH DAHULU UNTUK MELAKUKAN PENGADUAN</h4>
      <br>
      <a href="web/login.html" class="btn bg-danger">MASUK</a>
      <a href="web/user_register.html" class="btn bg-success">REGISTER</a>
    </div>
  </div>

  <div class="c-content-box c-bg-grey-1">
		<div class="container">
              <br>
              <h1 align="center"><b>~ DAFTAR PENGADUAN ~</b></h1>
              <hr>
              <style>
                #bg-white{color:#fff;}
              </style>
              <div class="table-responsive">
								<table id="myTable" class="table table-bordered table-striped">
									<thead>
										<tr style="background:gray;">
											<th id="bg-white" width="1">No.</th>
											<th id="bg-white">PENGADUAN</th>
											<th id="bg-white" width="210">TANGGAL LAPORAN</th>
											<th id="bg-white" width="150">STATUS</th>
										</tr>
									</thead>
									<tbody>
										<?php
                    $no=1;
                    foreach ($query->result() as $key => $value): ?>
                      <tr>
                        <td><b><?php echo $no++; ?>.</b></td>
                        <td><?php echo $value->isi_pengaduan; ?></td>
                        <td><?php echo $this->Mcrud->waktu($value->tgl_pengaduan,'full'); ?></td>
                        <td align="center"><?php echo $this->Mcrud->cek_status($value->status); ?></td>
                      </tr>
                    <?php endforeach; ?>
									</tbody>
								</table>
              </div>
			</div>
      <br>
  </div>

</div>
<!-- END: PAGE CONTAINER -->
