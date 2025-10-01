<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notaris extends CI_Controller {

	public function index()
	{
		$ceks = $this->session->userdata('username');
		$id_user = $this->session->userdata('id_user');
		if(!isset($ceks)) {
			redirect('web/login');
		}else{
			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$data['users']  	 = $this->Mcrud->get_users();
			$data['judul_web'] = "Dashboard";

			$this->load->view('users/header', $data);
			$this->load->view('users/dashboard', $data);
			$this->load->view('users/footer');
		}
	}

	public function v($aksi='', $id='')
	{
		$id = hashids_decrypt($id);
		$ceks 	 = $this->session->userdata('username');
		$id_user = $this->session->userdata('id_user');
		$level 	 = $this->session->userdata('level');
		if(!isset($ceks)) {
			redirect('web/login');
		}
			$data['user']  			  = $this->Mcrud->get_users_by_un($ceks);

			if ($level == 'notaris') {
					redirect('404_content');
			}

			$this->db->join('tbl_user','tbl_user.id_user=tbl_data_notaris.id_user');
			$this->db->order_by('id_data_notaris', 'DESC');
			$data['query'] = $this->db->get("tbl_data_notaris");

				if ($aksi == 'd') {
					$p = "detail";
					$data['judul_web'] 	  = "Detail Notaris";
					$this->db->join('tbl_user','tbl_user.id_user=tbl_data_notaris.id_user');
					$data['query'] = $this->db->get_where("tbl_data_notaris", array('tbl_user.id_user' => "$id"))->row();
					if ($data['query']->id_user=='') {redirect('404');}
				}
				elseif ($aksi == 'h') {
					if ($level == 'petugas') {
							redirect('404_content');
					}
					$cek_data = $this->db->get_where("tbl_data_notaris", array('id_user' => "$id"));
					if ($cek_data->num_rows() != 0) {
							$cek_foto = $cek_data->row()->foto;
							if ($cek_foto!='') {
								unlink($cek_foto);
							}
							$this->db->delete('tbl_laporan', array('notaris' => $id));
							$this->db->delete('tbl_data_notaris', array('id_user' => $id));
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
							redirect("users/v");
					}else {
						redirect('404');
					}
				}else if($aksi == 'l'){
                    //echo "L"; die;
                    $p = "dossier_pribadi";
                    $data['getNamaNotaris'] = $this->db->get_where('tbl_data_notaris',array(
                            'id_user'=>$id
                        ))->row()->nama??"";
                    $data['judul_web'] = "Dossier Pribadi Notaris ".$data['getNamaNotaris'];

                    // ambil data file dari notaris yang diklik
                    $data['file_size'] = 2048; // contoh max size KB
                    $data['allowed_types'] = "pdf|jpg|png";

                    $data['getData'] = $this->db->get_where("tbl_user_hasfile", [
                        'user_id' => $id
                    ])->row();
                    $data['id_notaris']=$id;
                    //echo "<pre>";print_r($data['getData']); die;
                }else{

				    //echo "daerah_kedudukan"; die;
					$p = "index";
					$data['judul_web'] 	= "Notaris Data";
					$data['daerah_kedudukan']= $this->db->get("tbl_kedudukan");
					//echo "<pre>"; print_r($data['daerah_kedudukan']->result());die;
				}

				if ($aksi=='cetak') {
					$this->load->view("users/notaris/cetak", $data);
				}else {
					$this->load->view('users/header', $data);
					$this->load->view("users/notaris/$p", $data);
					$this->load->view('users/footer');
				}
	}

	public function profile()
	{
		$ceks = $this->session->userdata('username');
		$id_user = $this->session->userdata('id_user');
		$level = $this->session->userdata('level');
		if(!isset($ceks)) {
			redirect('web/login');
		}else{
			if ($level=='notaris') {
				$this->db->join('tbl_data_notaris','tbl_data_notaris.id_user=tbl_user.id_user');
			}
			$data['user']  			  = $this->Mcrud->get_users_by_un($ceks);
			$data['level_users']  = $this->Mcrud->get_level_users();
			$data['judul_web'] 		= "Profile Notaris";

					$this->load->view('users/header', $data);
					$this->load->view('users/profile_notaris', $data);
					$this->load->view('users/footer');

					$lokasi = 'img/user';
					$file_size = 1024 * 3; // 3 MB
					$this->upload->initialize(array(
						"file_type"     => "image/jpeg",
						"upload_path"   => "./$lokasi",
						"allowed_types" => "jpg|jpeg|png",
						"max_size" => "$file_size"
					));

					if (isset($_POST['btnupdate'])) {
						$username	 		= htmlentities(strip_tags($this->input->post('username')));
						$nama_lengkap	= htmlentities(strip_tags($this->input->post('nama_lengkap')));

						$update = 'yes';
						$pesan = '';
						if ($ceks == $username) {
							$update = 'yes';
						}else{
							$cek_un = $this->Mcrud->get_users_by_un($username)->num_rows();
							if ($cek_un == 0) {
									$update = 'yes';
							}else{
									$update = 'no';
									$pesan  = 'Username "<b>'.$username.'</b>" sudah ada';
							}
						}

						if ($update=='yes' AND $level=='notaris') {
							$no_idn	= htmlentities(strip_tags($this->input->post('no_idn')));
							$alamat_notaris	= htmlentities(strip_tags($this->input->post('alamat_notaris')));
							$tempat_kedudukan	= htmlentities(strip_tags($this->input->post('tempat_kedudukan')));
							$no_sk	= htmlentities(strip_tags($this->input->post('no_sk')));
							$telpon	= htmlentities(strip_tags($this->input->post('telpon')));
							$email_notaris	= htmlentities(strip_tags($this->input->post('email_notaris')));

							$cek_foto = $this->db->get_where('tbl_data_notaris',"id_user='$id_user'")->row()->foto;
							if ($_FILES['foto']['error'] <> 4) {
								if ( ! $this->upload->do_upload('foto'))
								{
										$update = 'no';
										$pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
								}
								 else
								{
									if ($cek_foto!='') {
										unlink($cek_foto);
									}
											$gbr = $this->upload->data();
											$filename = "$lokasi/".$gbr['file_name'];
											$foto = preg_replace('/ /', '_', $filename);
											$update = 'yes';
								}
							}else {
								$foto = $cek_foto;
								$update = 'yes';
							}

						}

						if ($update == 'yes') {
									$data = array(
										'username'			=> $username,
										'nama_lengkap'	=> $nama_lengkap
									);
									$this->Mcrud->update_user(array('id_user' => $id_user), $data);

								if ($level=='notaris') {
									$data2 = array(
										'no_idn' => $no_idn,
										'nama_notaris'   => $nama,
										'alamat_notaris' => $alamat_notaris,
										'tempat_kedudukan' => $tempat_kedudukan,
										'no_sk' 		 => $no_sk,
										'telpon' => $telpon,
										'email_notaris'	 => $email_notaris,
										'foto_notaris'	 => $foto_notaris,
									);
									$this->db->update('tbl_data_notaris', $data2, array('id_user' => $id_user));
								}

									$this->session->has_userdata('username');
									$this->session->set_userdata('username', "$username");

									$this->session->set_flashdata('msg',
										'
										<div class="alert alert-success alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;</span>
											 </button>
											 <strong>Sukses!</strong> Profile berhasil disimpan.
										</div>
	 								 <br>'
									);
									redirect('profile');
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
							redirect('profile');
						}
					}
		}
	}

	public function ubah_pass()
	{
		$ceks = $this->session->userdata('username');
		if(!isset($ceks)) {
			redirect('web/login');
		}else{
			$data['user']  			  = $this->Mcrud->get_users_by_un($ceks);
			$data['level_users']  = $this->Mcrud->get_level_users();
			$data['judul_web'] 		= "Ubah Password";

					$this->load->view('users/header', $data);
					$this->load->view('users/ubah_pass', $data);
					$this->load->view('users/footer');

					if (isset($_POST['btnupdate2'])) {
						$password0 	= htmlentities(strip_tags($this->input->post('password0')));
						$password 	= htmlentities(strip_tags($this->input->post('password')));
						$password2 	= htmlentities(strip_tags($this->input->post('password2')));

						if ($password0 != $data['notaris']->row()->password) {
								$this->session->set_flashdata('msg2',
									'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;</span>
										 </button>
										 <strong>Gagal!</strong> Password lama salah.
									</div>
 								 <br>'
								);
								redirect('ubah_pass');
						}

						if ($password != $password2) {
								$this->session->set_flashdata('msg2',
									'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;</span>
										 </button>
										 <strong>Gagal!</strong> Password tidak cocok.
									</div>
 								 <br>'
								);
						}else{
									$data = array(
										'password'	=> $password
									);
									$this->Mcrud->update_user(array('username' => $ceks), $data);

									$this->session->set_flashdata('msg2',
										'
										<div class="alert alert-success alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;</span>
											 </button>
											 <strong>Sukses!</strong> Password berhasil disimpan.
										</div>
	 								 <br>'
									);
						}
									redirect('ubah_pass');
					}
		}
	}




}
