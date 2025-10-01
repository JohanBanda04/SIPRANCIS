<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	public function index()
	{
		$data['judul_web'] = "Laporan";
		$this->db->order_by('id_laporan', 'DESC');
		$data['query'] = $this->db->get("tbl_laporan");
		$this->load->view('web/header', $data);
		$this->load->view('web/laporan', $data);
		$this->load->view('web/footer', $data);
	}

	public function cek($no_idn='')
	{
		$data['judul_web'] = "Laporan";
		if ($no_idn!='') {
			$this->db->join('tbl_data_notaris','tbl_data_notaris.id_user=tbl_laporan.user');
			$this->db->order_by('id_laporan', 'DESC');
			$data['query'] = $this->db->get_where("tbl_laporan", array('no_idn'=>"$no_idn"));
		}
		$data['no_idn'] = $no_idn;
		$this->load->view('web/header', $data);
		$this->load->view('web/cek', $data);
		$this->load->view('web/footer', $data);
	}

	public function v($aksi='',$id='')
	{
	    //echo "pret"; die;
		$id = hashids_decrypt($id);
		$ceks 	 = $this->session->userdata('username');
		$id_user = $this->session->userdata('id_user');
		$level 	 = $this->session->userdata('level');
		if(!isset($ceks)) {
			redirect('web/login');
		}

			$data['user']  			  = $this->Mcrud->get_users_by_un($ceks);

			if ($level=='petugas') {
				$this->db->where('status!=','proses');
				$this->db->where('petugas',$id_user);
			}
			if ($level=='notaris') {
				$this->db->where('notaris',$id_user);
			}
			if ($aksi=='proses' or $aksi=='konfirmasi' or $aksi=='selesai') {
				$this->db->where('status',$aksi);
			}
			$this->db->order_by('id_laporan', 'DESC');
			$data['query'] = $this->db->get("tbl_laporan");

			$cek_notif = $this->db->get_where("tbl_notif", array('penerima'=>"$id_user"));
			foreach ($cek_notif->result() as $key => $value) {
				$b_notif = $value->baca_notif;
				if(!preg_match("/$id_user/i", $b_notif)) {
					$data_notif = array('baca_notif'=>"$id_user, $b_notif");
					$this->db->update('tbl_notif', $data_notif, array('penerima'=>$id_user));
				}
			}

			if ($aksi == 't') {
				if ($level!='notaris') {
					redirect('404');
				}
				$p = "tambah";
				$data['judul_web'] 	  = "BUAT LAPORAN BARU";
			}elseif ($aksi == 'd') {
				$p = "detail";
				$data['judul_web'] 	  = "RINCIAN LAPORAN";
				$data['query'] = $this->db->get_where("tbl_laporan", array('id_laporan' => "$id"))->row();
				if ($data['query']->id_laporan=='') {redirect('404');}
			}
			elseif ($aksi == 'h') {
				$cek_data = $this->db->get_where("tbl_laporan", array('id_laporan' => "$id"));
				if ($cek_data->num_rows() != 0) {
						if ($cek_data->row()->status!='proses') {
							redirect('404');
						}
						if ($cek_data->row()->lampiran != '') {
							unlink($cek_data->row()->lampiran);
						}
						$this->db->delete('tbl_notif', array('pengirim'=>$id_user,'id_laporan'=>$id));
						$this->db->delete('tbl_laporan', array('id_laporan' => $id));
						$this->session->set_flashdata('msg',
							'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil dihapus.
							</div>
							<br>'
						);
						redirect("laporan/v");
				}else {
					redirect('404_content');
				}
			}else{
				$p = "index";
				$data['judul_web'] 	  = "LAPORAN";
			}

				$this->load->view('users/header', $data);
				$this->load->view("users/laporan/$p", $data);
				$this->load->view('users/footer');

				date_default_timezone_set('Asia/Jakarta');
				$tgl = date('Y-m-d H:i:s');

				$lokasi = 'file';
				$file_size = 1024 * 3; // 3 MB
				$this->upload->initialize(array(
					"upload_path"   => "./$lokasi",
					"allowed_types" => "*",
					"max_size" => "$file_size"
				));

				if (isset($_POST['btnsimpan'])) {
				    //echo "testy";die;
					$id_kategori_lap 		 = htmlentities(strip_tags($this->input->post('id_kategori_lap')));
					$id_sub_kategori_lap = htmlentities(strip_tags($this->input->post('id_sub_kategori_lap')));
					$isi_laporan 	 = htmlentities(strip_tags($this->input->post('isi_laporan')));
					$ket_laporan 	 = htmlentities(strip_tags($this->input->post('ket_laporan')));

					if ( ! $this->upload->do_upload('lampiran'))
					{
							$simpan = 'n';
							$pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
					}
					 else
					{
								$gbr = $this->upload->data();
								$filename = "$lokasi/".$gbr['file_name'];
								$lampiran = preg_replace('/ /', '_', $filename);
								$simpan = 'y';
					}

					if ($simpan=='y') {
									$data = array(
										'id_kategori_lap' 		=> $id_kategori_lap,
										'id_sub_kategori_lap' => $id_sub_kategori_lap,
										'isi_laporan'   => $isi_laporan,
										'ket_laporan'   => $ket_laporan,
										'lampiran'						=> $lampiran,
										'notaris'						=> $id_user,
										'status'					=> 'proses',
										'tgl_laporan'   => $tgl
									);
									$this->db->insert('tbl_laporan',$data);

									$id_laporan = $this->db->insert_id();
									$this->Mcrud->kirim_notif($id_user,'superadmin',$id_laporan,'notaris_kirim_laporan');

									$this->session->set_flashdata('msg',
										'
										<div class="alert alert-success alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;</span>
											 </button>
											 <strong>Sukses!</strong> Berhasil disimpan.
										</div>
									 <br>'
									);
					 }else {
							 $this->session->set_flashdata('msg',
	 							'
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
	 						);
							redirect("laporan/v/$aksi/".hashids_decrypt($id));
					 }
					 redirect("laporan/v");
				}


				if (isset($_POST['btnkirim'])) {
					$id_laporan = htmlentities(strip_tags($this->input->post('id_laporan')));
					$data_lama = $this->db->get_where('tbl_laporan',array('id_laporan'=>$id_laporan))->row();
					$simpan = 'y';
					$pesan = '';
					if ($level=='superadmin') {
						$id_petugas 	= htmlentities(strip_tags($this->input->post('id_petugas')));
						$data = array(
							'petugas'					=> $id_petugas,
							'status'					=> 'konfirmasi',
							'tgl_konfirmasi'  => $tgl
						);
						$pesan = 'Berhasil dikirim ke petugas';
						$this->Mcrud->kirim_notif('superadmin',$id_petugas,$id_laporan,'superadmin_ke_petugas');
						$this->Mcrud->kirim_notif('superadmin',$data_lama->user,$id_laporan,'superadmin_ke_notaris');
					}else {
						$pesan_petugas = htmlentities(strip_tags($this->input->post('pesan_petugas')));
						$status = htmlentities(strip_tags($this->input->post('status')));
						$file = $data_lama->file_petugas;
						$pesan = 'Berhasil disimpan';
						if ($_FILES['file']['error'] <> 4) {
							if ( ! $this->upload->do_upload('file'))
							{
									$simpan = 'n';
									$pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
							}
							 else
							{
								if ($file!='') {
									unlink("$file");
								}
										$gbr = $this->upload->data();
										$filename = "$lokasi/".$gbr['file_name'];
										$file = preg_replace('/ /', '_', $filename);
							}
						}

						$data = array(
							'pesan_petugas' => $pesan_petugas,
							'status'				=> $status,
							'file_petugas'  => $file,
							'tgl_selesai'   => $tgl
						);
						$this->Mcrud->kirim_notif($data_lama->petugas,$data_lama->notaris,$id_laporan,'petugas_ke_notaris');
					}

					if ($simpan=='y') {
						$this->db->update('tbl_laporan',$data, array('id_laporan'=>$id_laporan));
						$this->session->set_flashdata('msg',
							'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> '.$pesan.'.
							</div>
						 <br>'
						);
					}else {
						$this->session->set_flashdata('msg',
							'
							<div class="alert alert-warning alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Gagal!</strong> '.$pesan.'.
							</div>
						 <br>'
						);
					}
					redirect('laporan/v');
				}

	}


	public function ajax()
	{
		if (isset($_POST['btnkirim'])) {
			$id = $this->input->post('id');
			$data = $this->db->get_where('tbl_laporan',array('id_laporan'=>$id))->row();
			$pesan_petugas = $data->pesan_petugas;
			$status = $data->status;
			echo json_encode(array('pesan_petugas'=>$pesan_petugas,'status'=>$status));
		}else {
			redirect('404');
		}
	}

}
