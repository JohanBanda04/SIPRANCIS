<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slide extends CI_Controller {

	public function index()
	{
		redirect('slide/v');
	}

	public function v($aksi='',$id='')
	{
		$id = hashids_decrypt($id);
		$ceks 	 = $this->session->userdata('username');
		$id_user = $this->session->userdata('id_user');
		$level 	 = $this->session->userdata('level');
		if(!isset($ceks)) {
			redirect('web/login');
		}

			$data['user']  			  = $this->Mcrud->get_users_by_un($ceks);

			if ($level!='superadmin') {
				redirect('404');
			}

			$this->db->order_by('id_slide', 'DESC');
			$data['query'] = $this->db->get("tbl_slide");

			if ($aksi == 't') {
				$p = "tambah";
				$data['judul_web'] 	  = "+ Slide";
			}elseif ($aksi == 'e') {
				$p = "edit";
				$data['judul_web'] 	  = "Edit Slide Info";
				$data['query'] = $this->db->get_where("tbl_slide", array('id_slide' => "$id"))->row();
				if ($data['query']->id_slide=='') {redirect('404');}
			}
			elseif ($aksi == 'h') {
				$cek_data = $this->db->get_where("tbl_slide", array('id_slide' => "$id"));
				if ($cek_data->num_rows() != 0) {
						if ($cek_data->row()->foto_slide != '') {
							unlink($cek_data->row()->foto_slide);
						}
						$this->db->delete('tbl_slide', array('id_slide' => $id));
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
						redirect("slide/v");
				}else {
					redirect('404_content');
				}
			}else{
				$p = "index";
				$data['judul_web'] 	  = "Slide";
			}

				$this->load->view('users/header', $data);
				$this->load->view("users/slide/$p", $data);
				$this->load->view('users/footer');

				date_default_timezone_set('Asia/Jakarta');
				$tgl = date('Y-m-d H:i:s');

				$lokasi = 'img/slide';
				$file_size = 1024 * 3; // 3 MB
				$this->upload->initialize(array(
					"file_type"     => "image/jpeg",
					"upload_path"   => "./$lokasi",
					"allowed_types" => "jpg|jpeg|png",
					"max_size" => "$file_size"
				));

				if (isset($_POST['btnsimpan'])) {
					$ket_slide = htmlentities(strip_tags($this->input->post('ket_slide')));
					if ( ! $this->upload->do_upload('foto'))
					{
							$simpan = 'n';
							$pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
					}
					 else
					{
								$gbr = $this->upload->data();
								$filename = "$lokasi/".$gbr['file_name'];
								$foto = preg_replace('/ /', '_', $filename);
								$simpan = 'y';
					}

					if ($simpan=='y') {
									$data = array(
										'ket_slide'  => $ket_slide,
										'foto_slide' => $foto,
										'tgl_slide'  => $tgl
									);
									$this->db->insert('tbl_slide',$data);

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
					 }
					 redirect("slide/v");
				}

				if (isset($_POST['btnupdate'])) {
					$ket_slide = htmlentities(strip_tags($this->input->post('ket_slide')));
					$cek_foto = $this->db->get_where('tbl_slide',"id_slide='$id'")->row()->foto_slide;
					if ($_FILES['foto']['error'] <> 4) {
						if ( ! $this->upload->do_upload('foto'))
						{
								$simpan = 'n';
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
									$simpan = 'y';
						}
					}else {
						$foto = $cek_foto;
						$simpan = 'y';
					}

					if ($simpan=='y') {
									$data = array(
										'ket_slide'  => $ket_slide,
										'foto_slide' => $foto
									);
									$this->db->update('tbl_slide',$data, array('id_slide' => $id));

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
									redirect("slide/v");
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
							redirect("slide/v/e/".hashids_encrypt($id));
					 }

				}

	}


}
