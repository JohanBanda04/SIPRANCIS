
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
              ?>
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <!-- <h4 class="panel-title"> -->
															<a href="web/notif/h_all" class="btn btn-danger btn-xs" onclick="return confirm('Anda Yakin??');"> <i class="fa fa-trash"></i> Hapus Semua Notifikasi Saya</a>
														<!-- </h4> -->
                        </div>
                        <div class="panel-body">
													<div class="table-responsive">
                            <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="1%">NO.</th>
                                        <th width="10%">FOTO</th>
                                        <th width="25%">PENGIRIM</th>
                                        <th width="40%">Pesan</th>
                                        <th width="14%">WAKTU</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $no=1;
																	$foto  = "img/user/user-default.jpg";
																	$id_user = $this->session->userdata('id_user');
                                  foreach ($query->result() as $value):
																		 $cek_penerima = $this->db->get_where('tbl_user', array('id_user'=>$value->penerima));
																	   if ($cek_penerima->num_rows()!=0) {
																	     $cek_pengirim = $this->db->get_where('tbl_user', array('id_user'=>$value->pengirim));
																	     if ($cek_pengirim->num_rows()==0) {
																	       $nama  = ""; $pesan = ""; $waktu = "";
																	     }else {
																	       $baris = $cek_pengirim->row();
																	       $nama  = $baris->nama_lengkap;
																	       $pesan = $value->pesan;
																	       $waktu = $this->Mcrud->waktu($value->tgl_notif,'full');
																				 $level = $baris->level;
																	       if ($level=='user') {
																	         $foto_k = $this->db->get_where('tbl_data_user', array('id_user'=>$value->pengirim))->row()->foto;
																	       	if ($foto_k!='') {
																	       		if(file_exists("$foto_k")){
																	       			$foto = $foto_k;
																	       		}
																	       	}
																	       }
																	     }

																			 $link = "javascript:;";
																       if ($value->link!='') {
																         $link = $value->link;
																       }

																			 if(!preg_match("/$id_user/i", $value->hapus_notif)) {
																			?>
                                    <tr>
                                        <td><b><?php echo $no++; ?>.</b></td>
																				<td>
																					<a href="<?php echo $foto; ?>" data-fancybox="all" data-caption="<?php echo "$level - $nama"; ?>">
																						<img src="<?php echo $foto; ?>" alt="<?php echo $nama; ?>" width="100">
																					</a>
																				</td>
																				<td><?php echo $nama; ?></td>
																				<td><?php echo $pesan; ?></td>
																				<td><?php echo $waktu; ?></td>
																				<td align="center">
																					<a href="<?php echo $link; ?>" class="btn btn-info btn-xs" title="Detail"><i class="fa fa-search"></i></a>
                                          <a href="web/notif/h/<?php echo hashids_encrypt($value->id_notif); ?>" class="btn btn-danger btn-xs" title="Hapus" onclick="return confirm('Anda yakin?');"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                  <?php
																			}
																		}
																	endforeach; ?>
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
