<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas extends CI_Controller {

	public function index()
	{
		redirect('petugas/v');
	}

	public function v($aksi='', $id='')
	{
		$id = hashids_decrypt($id);
		$ceks 	 = $this->session->userdata('username');
		$id_user = $this->session->userdata('id_user');
		$level 	 = $this->session->userdata('level');
		if(!isset($ceks)) {
			redirect('web/login');
		}else{
			$data['user']  			  = $this->Mcrud->get_users_by_un($ceks);

			if ($level != 'superadmin') {
					redirect('404_content');
			}

			$this->db->join('tbl_user','tbl_user.id_user=tbl_petugas.id_user');
			$this->db->order_by('id_petugas', 'DESC');
			$data['query'] = $this->db->get("tbl_petugas");

				if ($aksi == 't') {
					$p = "tambah";
					$data['judul_web'] 	  = "REGISTRASI PETUGAS";
				}elseif ($aksi == 'e') {
					$p = "edit";
					$data['judul_web'] 	  = "EDIT PETUGAS";
					$this->db->join('tbl_user','tbl_user.id_user=tbl_petugas.id_user');
					$data['query'] = $this->db->get_where("tbl_petugas", array('tbl_user.id_user' => "$id"))->row();
					if ($data['query']->id_user=='') {redirect('404');}
				}
				elseif ($aksi == 'h') {
					$cek_data = $this->db->get_where("tbl_petugas", array('id_user' => "$id"));
					if ($cek_data->num_rows() != 0) {
							$this->db->delete('tbl_petugas', array('id_user' => $id));
							$this->db->delete('tbl_user', array('id_user' => $id));
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
							redirect("petugas/v");
					}else {
						redirect('404');
					}
				}else{
					$p = "index";
					$data['judul_web'] 	  = "PETUGAS";
				}

					$this->load->view('users/header', $data);
					$this->load->view("users/petugas/$p", $data);
					$this->load->view('users/footer');

					date_default_timezone_set('Asia/Jakarta');
					$tgl = date('Y-m-d H:i:s');

					if (isset($_POST['btnsimpan'])) {
						$nama 	 = htmlentities(strip_tags($this->input->post('nama')));
						$jk 		 = htmlentities(strip_tags($this->input->post('jk')));
						$alamat  = htmlentities(strip_tags($this->input->post('alamat')));
						$no_telp = htmlentities(strip_tags($this->input->post('no_telp')));
						$email 	 = htmlentities(strip_tags($this->input->post('email')));
						$username = htmlentities(strip_tags($this->input->post('username')));
						$password  = htmlentities(strip_tags($this->input->post('password')));
						$password2 = htmlentities(strip_tags($this->input->post('password2')));

						$cek_data = $this->db->get_where('tbl_user', array('username'=>$username));
						$simpan = 'y';
						$pesan  = '';
						if ($cek_data->num_rows()!=0) {
							$simpan = 'n';
							$pesan  = "Username '<b>$username</b>' sudah ada";
						}else {
							if ($password!=$password2) {
								$simpan = 'n';
								$pesan  = "Password tidak cocok!";
							}
						}

						if ($simpan=='y') {
										$data = array(
											'nama_lengkap' => $nama,
											'username' 		 => $username,
											'password' 		 => $password,
											'level' 			 => "petugas",
											'tgl_daftar' 	 => $tgl,
											'aktif'				 => '1',
											'dihapus' 		 => 'tidak'
										);
										$this->db->insert('tbl_user',$data);

										$data2 = array(
											'nama' 		=> $nama,
											'jk' 			=> $jk,
											'alamat'  => $alamat,
											'no_telp' => $no_telp,
											'email'   => $email,
											'id_user' => $this->db->insert_id()
										);
										$this->db->insert('tbl_petugas',$data2);

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
							 redirect("petugas/v/t");
						}
						 redirect("petugas/v");
					}


					if (isset($_POST['btnupdate'])) {
						$nama 	 = htmlentities(strip_tags($this->input->post('nama')));
						$jk 		 = htmlentities(strip_tags($this->input->post('jk')));
						$alamat  = htmlentities(strip_tags($this->input->post('alamat')));
						$no_telp = htmlentities(strip_tags($this->input->post('no_telp')));
						$email 	 = htmlentities(strip_tags($this->input->post('email')));
						$username = htmlentities(strip_tags($this->input->post('username')));
						$password  = htmlentities(strip_tags($this->input->post('password')));
						$password2 = htmlentities(strip_tags($this->input->post('password2')));

						$data_lama = $this->db->get_where('tbl_user', array('id_user'=>$id))->row();
						$cek_data  = $this->db->get_where('tbl_user', array('username'=>$username,'username!='=>$data_lama->username));
						$simpan = 'y';
						$pesan  = '';
						if ($cek_data->num_rows()!=0) {
							$simpan = 'n';
							$pesan  = "Username '<b>$username</b>' sudah ada";
						}else {
							$pass_lama = $data_lama->password;
							if ($password=='') {
								$password = $pass_lama;
							}else {
								if ($password!=$password2) {
									$simpan = 'n';
									$pesan  = "Password tidak cocok!";
								}
							}
						}

						if ($simpan=='y') {
										$data = array(
											'nama_lengkap' => $nama,
											'username' 		 => $username,
											'password' 		 => $password
										);
										$this->db->update('tbl_user',$data, array('id_user'=>$id));

										$data2 = array(
											'nama' 		=> $nama,
											'jk' 			=> $jk,
											'alamat'  => $alamat,
											'no_telp' => $no_telp,
											'email'   => $email
										);
										$this->db->update('tbl_petugas',$data2, array('id_user'=>$id));

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
										 redirect("petugas/v/e/".hashids_encrypt($id));
					 	 }
						 redirect("petugas/v");
					}
		}
	}

}
