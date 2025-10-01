<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_lap extends CI_Controller {

	public function index()
	{
		redirect('kategori_lap/v');
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

			$this->db->order_by('id_kategori_lap', 'DESC');
			$data['query'] = $this->db->get("tbl_kategori_lap");

				if ($aksi == 't') {
					$p = "tambah";
					$data['judul_web'] 	  = "+ Kategori";
				}elseif ($aksi == 'e') {
					$p = "edit";
					$data['judul_web'] 	  = "Edit Kategori";
					$data['query'] = $this->db->get_where("tbl_kategori_lap", array('id_kategori_lap' => "$id"))->row();
					if ($data['query']->id_kategori_lap=='') {redirect('404');}
				}
				elseif ($aksi == 'h') {
					$cek_data = $this->db->get_where("tbl_kategori_lap", array('id_kategori_lap' => "$id"));
					if ($cek_data->num_rows() != 0) {
							$this->db->delete('tbl_kategori_lap', array('id_kategori_lap' => $id));
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
							redirect("kategori_lap/v");
					}else {
						redirect('404');
					}
				}else{
					$p = "index";
					$data['judul_web'] 	  = "Kategori Laporan";
				}

					$this->load->view('users/header', $data);
					$this->load->view("users/kategori_lap/$p", $data);
					$this->load->view('users/footer');

					date_default_timezone_set('Asia/Jakarta');
					$tgl = date('Y-m-d H:i:s');

					if (isset($_POST['btnsimpan'])) {
						$nama_kategori_lap = htmlentities(strip_tags($this->input->post('nama_kategori_lap')));

										$data = array(
											'nama_kategori_lap' => $nama_kategori_lap
										);
										$this->db->insert('tbl_kategori_lap',$data);

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

						 redirect("kategori_lap/v");
					}


					if (isset($_POST['btnupdate'])) {
						$nama_kategori_lap = htmlentities(strip_tags($this->input->post('nama_kategori_lap')));

										$data = array(
											'nama_kategori_lap' => $nama_kategori_lap
										);
										$this->db->update('tbl_kategori_lap',$data, array('id_kategori_lap' => $id));

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

						 redirect("kategori_lap/v");
					}
		}
	}


	public function sub($aksi='', $id='')
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

			$this->db->order_by('nama_kategori_lap', 'ASC');
			$data['v_kat'] = $this->db->get("tbl_kategori_lap");

			$this->db->join('tbl_kategori_lap','tbl_kategori_lap.id_kategori_lap=tbl_sub_kategori_lap.id_kategori_lap');
			$this->db->order_by('id_sub_kategori_lap', 'DESC');
			$data['query'] = $this->db->get("tbl_sub_kategori_lap");

				if ($aksi == 't') {
					$p = "tambah";
					$data['judul_web'] 	  = "+ Sub Kategori";
				}elseif ($aksi == 'e') {
					$p = "edit";
					$data['judul_web'] 	  = "Edit Sub Kategori";
					$data['query'] = $this->db->get_where("tbl_sub_kategori_lap", array('id_sub_kategori_lap' => "$id"))->row();
					if ($data['query']->id_sub_kategori_lap=='') {redirect('404');}
				}
				elseif ($aksi == 'h') {
					$cek_data = $this->db->get_where("tbl_sub_kategori_lap", array('id_sub_kategori_lap' => "$id"));
					if ($cek_data->num_rows() != 0) {
							$this->db->delete('tbl_sub_kategori_lap', array('id_sub_kategori_lap' => $id));
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
							redirect("kategori_lap/sub");
					}else {
						redirect('404');
					}
				}else{
					$p = "index";
					$data['judul_web'] 	  = "Sub Kategori";
				}

					$this->load->view('users/header', $data);
					$this->load->view("users/kategori_lap/sub/$p", $data);
					$this->load->view('users/footer');

					date_default_timezone_set('Asia/Jakarta');
					$tgl = date('Y-m-d H:i:s');

					if (isset($_POST['btnsimpan'])) {
						$id_kategori_lap 			 = htmlentities(strip_tags($this->input->post('id_kategori_lap')));
						$nama_sub_kategori_lap = htmlentities(strip_tags($this->input->post('nama_sub_kategori_lap')));

										$data = array(
											'id_kategori_lap' 			=> $id_kategori_lap,
											'nama_sub_kategori_lap' => $nama_sub_kategori_lap
										);
										$this->db->insert('tbl_sub_kategori_lap',$data);

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

						 redirect("kategori_lap/sub");
					}


					if (isset($_POST['btnupdate'])) {
						$id_kategori_lap 			 = htmlentities(strip_tags($this->input->post('id_kategori_lap')));
						$nama_sub_kategori = htmlentities(strip_tags($this->input->post('nama_sub_kategori_lap')));

										$data = array(
											'id_kategori_lap' 			=> $id_kategori_lap,
											'nama_sub_kategori_lap' => $nama_sub_kategori
										);
										$this->db->update('tbl_sub_kategori_lap',$data, array('id_sub_kategori_lap' => $id));

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

						 redirect("kategori_lap/sub");
					}
		}
	}

}
