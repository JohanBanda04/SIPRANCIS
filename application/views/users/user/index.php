<?php
$link1 = strtolower($this->uri->segment(1));
$link2 = strtolower($this->uri->segment(2));
$link3 = strtolower($this->uri->segment(3));
$link4 = strtolower($this->uri->segment(4));
?>
<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="dashboard.html">Dashboard</a></li>
				<li class="active"><?php echo $judul_web; ?></li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Data <small><?php echo $judul_web; ?></small></h1>
			<!-- end page-header -->

			<!-- begin row -->
			<div class="row">
			    <!-- begin col-12 -->
			    <div class="col-md-12">
			        <!-- begin panel -->
              <?php
                echo $this->session->flashdata('msg');
								$level 	= $this->session->userdata('level');
              ?>
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
																<a href="users/v/cetak" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-file"></i> Cetak</a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title"><?php echo $judul_web; ?></h4>
                        </div>
                        <div class="panel-body">
                          <div class="table-responsive">
                            <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="1%">No.</th>
                                        <th width="15%">No. KTP</th>
																				<th>Nama</th>
																				<th>Alamat</th>
																				<th width="12%">Tanggal Lahir</th>
																				<th width="15%">No. Telp</th>
                                        <th width="10%">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $no=1;
                                   foreach ($query->result() as $baris):
																		 	?>
                                    <tr>
                                        <td><b><?php echo $no++; ?>.</b></td>
																				<td><?php echo $baris->no_ktp; ?></td>
																				<td><?php echo $baris->nama; ?></td>
																				<td><?php echo $baris->alamat; ?></td>
																				<td><?php echo date('d-m-Y',strtotime($baris->tgl_lahir)); ?></td>
																				<td><?php echo $baris->kontak; ?></td>
																				<td align="center">
																				  <a href="<?php echo $link1; ?>/<?php echo $link2; ?>/d/<?php echo hashids_encrypt($baris->id_user); ?>" class="btn btn-info btn-xs" title="Detail"><i class="fa fa-list"></i></a>
																					<?php if ($level=='superadmin'): ?>
																					<a href="<?php echo $link1; ?>/<?php echo $link2; ?>/h/<?php echo hashids_encrypt($baris->id_user); ?>" class="btn btn-danger btn-xs" title="Hapus" onclick="return confirm('Anda yakin?');"><i class="fa fa-trash-o"></i></a>
																					<?php endif; ?>
																				</td>
                                    </tr>
                                  <?php endforeach; ?>
                                </tbody>
                            </table>
													</div>
                        </div>
                    </div>
                    <!-- end panel -->
                </div>
                <!-- end col-12 -->
            </div>
            <!-- end row -->
		</div>
		<!-- end #content -->
