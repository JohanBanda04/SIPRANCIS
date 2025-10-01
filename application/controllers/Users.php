<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function index()
    {
        $ceks = $this->session->userdata('username');
        $id_user = $this->session->userdata('id_user');
        if (!isset($ceks)) {
            redirect('web/login');
        } else {
            $data['user'] = $this->Mcrud->get_users_by_un($ceks);
            $data['users'] = $this->Mcrud->get_users();
            $data['judul_web'] = "Dashboard";

            $this->load->view('users/header', $data);
            $this->load->view('users/dashboard', $data);
            $this->load->view('users/footer');
        }
    }

    public function v($aksi = '', $id = '')
    {
        $id = hashids_decrypt($id);
        $ceks = $this->session->userdata('username');
        $id_user = $this->session->userdata('id_user');
        $level = $this->session->userdata('level');
        if (!isset($ceks)) {
            redirect('web/login');
        }
        $data['user'] = $this->Mcrud->get_users_by_un($ceks);

        if ($level == 'user') {
            redirect('404_content');
        }

        $this->db->join('tbl_user', 'tbl_user.id_user=tbl_data_user.id_user');
        $this->db->order_by('id_data_user', 'DESC');
        $data['query'] = $this->db->get("tbl_data_user");

        if ($aksi == 'd') {
            $p = "detail";
            $data['judul_web'] = "Detail User";
            $this->db->join('tbl_user', 'tbl_user.id_user=tbl_data_user.id_user');
            $data['query'] = $this->db->get_where("tbl_data_user", array('tbl_user.id_user' => "$id"))->row();
            if ($data['query']->id_user == '') {
                redirect('404');
            }
        } elseif ($aksi == 'h') {
            if ($level == 'petugas') {
                redirect('404_content');
            }
            $cek_data = $this->db->get_where("tbl_data_user", array('id_user' => "$id"));
            if ($cek_data->num_rows() != 0) {
                $cek_foto = $cek_data->row()->foto;
                if ($cek_foto != '') {
                    unlink($cek_foto);
                }
                $this->db->delete('tbl_pengaduan', array('user' => $id));
                $this->db->delete('tbl_data_user', array('id_user' => $id));
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
            } else {
                redirect('404');
            }
        } else {
            $p = "index";
            $data['judul_web'] = "User";
        }

        if ($aksi == 'cetak') {
            $this->load->view("users/user/cetak", $data);
        } else {
            $this->load->view('users/header', $data);
            $this->load->view("users/user/$p", $data);
            $this->load->view('users/footer');
        }
    }

    public function profile()
    {
        $ceks = $this->session->userdata('username');
        $id_user = $this->session->userdata('id_user');
        $level = $this->session->userdata('level');
        if (!isset($ceks)) {
            redirect('web/login');
        } else {
            if ($level == 'user') {
                $this->db->join('tbl_data_user', 'tbl_data_user.id_user=tbl_user.id_user');
            }
            $data['user'] = $this->Mcrud->get_users_by_un($ceks);
            $data['level_users'] = $this->Mcrud->get_level_users();
            $data['judul_web'] = "Profile";

            $this->load->view('users/header', $data);
            $this->load->view('users/profile', $data);
            $this->load->view('users/footer');

            $lokasi = 'img/user';
            $file_size = 1024 * 3; // 3 MB
            $this->upload->initialize(array(
                "file_type" => "image/jpeg",
                "upload_path" => "./$lokasi",
                "allowed_types" => "jpg|jpeg|png",
                "max_size" => "$file_size"
            ));

            if (isset($_POST['btnupdate'])) {
                $username = htmlentities(strip_tags($this->input->post('username')));
                $nama_lengkap = htmlentities(strip_tags($this->input->post('nama_lengkap')));

                $update = 'yes';
                $pesan = '';
                if ($ceks == $username) {
                    $update = 'yes';
                } else {
                    $cek_un = $this->Mcrud->get_users_by_un($username)->num_rows();
                    if ($cek_un == 0) {
                        $update = 'yes';
                    } else {
                        $update = 'no';
                        $pesan = 'Username "<b>' . $username . '</b>" sudah ada';
                    }
                }

                if ($update == 'yes' AND $level == 'user') {
                    $no_ktp = htmlentities(strip_tags($this->input->post('no_ktp')));
                    $alamat = htmlentities(strip_tags($this->input->post('alamat')));
                    $tgl_lahir = date('Y-m-d', strtotime(htmlentities(strip_tags($this->input->post('tgl_lahir')))));
                    $jk = htmlentities(strip_tags($this->input->post('jk')));
                    $kontak = htmlentities(strip_tags($this->input->post('kontak')));
                    $email = htmlentities(strip_tags($this->input->post('email')));

                    $cek_foto = $this->db->get_where('tbl_data_user', "id_user='$id_user'")->row()->foto;
                    if ($_FILES['foto']['error'] <> 4) {
                        if (!$this->upload->do_upload('foto')) {
                            $update = 'no';
                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        } else {
                            if ($cek_foto != '') {
                                unlink($cek_foto);
                            }
                            $gbr = $this->upload->data();
                            $filename = "$lokasi/" . $gbr['file_name'];
                            $foto = preg_replace('/ /', '_', $filename);
                            $update = 'yes';
                        }
                    } else {
                        $foto = $cek_foto;
                        $update = 'yes';
                    }

                }

                if ($update == 'yes') {
                    $data = array(
                        'username' => $username,
                        'nama_lengkap' => $nama_lengkap
                    );
                    $this->Mcrud->update_user(array('id_user' => $id_user), $data);

                    if ($level == 'user') {
                        $data2 = array(
                            'no_ktp' => $no_ktp,
                            'nama' => $nama_lengkap,
                            'alamat' => $alamat,
                            'tgl_lahir' => $tgl_lahir,
                            'jk' => $jk,
                            'kontak' => $kontak,
                            'email' => $email,
                            'foto' => $foto,
                        );
                        $this->db->update('tbl_data_user', $data2, array('id_user' => $id_user));
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
                } else {
                    $this->session->set_flashdata('msg',
                        '
								<div class="alert alert-warning alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;</span>
									 </button>
									 <strong>Gagal!</strong> ' . $pesan . '.
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
        if (!isset($ceks)) {
            redirect('web/login');
        } else {
            $data['user'] = $this->Mcrud->get_users_by_un($ceks);
            $data['level_users'] = $this->Mcrud->get_level_users();
            $data['judul_web'] = "Ubah Password";

            $this->load->view('users/header', $data);
            $this->load->view('users/ubah_pass', $data);
            $this->load->view('users/footer');

            if (isset($_POST['btnupdate2'])) {
                $password0 = htmlentities(strip_tags($this->input->post('password0')));
                $password = htmlentities(strip_tags($this->input->post('password')));
                $password2 = htmlentities(strip_tags($this->input->post('password2')));

                if ($password0 != $data['user']->row()->password) {
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
                } else {
                    $data = array(
                        'password' => $password
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


    public function dossier_pribadi()
    {
        $ceks = $this->session->userdata('username');
        $id_user = $this->session->userdata('id_user');
        $level = $this->session->userdata('level');

        if (!isset($ceks)) {
            redirect('web/login');
        } else {
            if ($level == 'user') {
                $this->db->join('tbl_data_user', 'tbl_data_user.id_user=tbl_user.id_user');
            }

            // Load data untuk view
            $data['user'] = $this->Mcrud->get_users_by_un($ceks);
            $data['level_users'] = $this->Mcrud->get_level_users();
            $data['jenisFiles'] = $this->db->get('tbl_file');
            $data['judul_web'] = "DOSSIER";

            // Set file upload configuration
            $lokasi = 'file/dossiers';
            $file_size = 1024 * 5; // 5 MB
            $allowed_types = "pdf";
            $data['file_size'] = $file_size;
            $data['allowed_types'] = $allowed_types;

            $this->upload->initialize(array(
                "upload_path" => "./$lokasi",
                "allowed_types" => $allowed_types,
                "max_size" => $file_size
            ));

            $getLinkGdrive = $this->db->get_where('tbl_gdrive',array('user_id'=>$id_user))->row()->link_gdrive??"";
            $data['link_gdrive']=$getLinkGdrive;

            $this->load->view('users/header', $data);
            $this->load->view('users/dossier_pribadi', $data);
            $this->load->view('users/footer');

            date_default_timezone_set('Asia/Jakarta');
            $tgl = date('Y-m-d H:i:s');

            // Handle form submission
            if (isset($_POST['btnsimpan'])) {
                            $file_paths = [];
                            $fields = [
                                'ktp_path' => 'KTP',
                                'ijazah_sh_path' => 'Ijazah SH',
                                'ijazah_magister_path' => 'Ijazah Magister',
                                'npwp_path' => 'NPWP',
                                'kepmenkum_pengangkatan_path' => 'Keputusan Menteri Hukum Pengangkatan',
                                'berita_acara_sumpah_path' => 'Berita Acara Sumpah',
                                'kepmenkum_pindah_wilayah_path' => 'Keputusan Menteri Hukum Pindah Wilayah',
                                'berita_acara_sumpah_pindah_path' => 'Berita Acara Sumpah Pindah Wilayah'
                            ];

                            // cek apakah sudah ada data lama
                            $cekData = $this->db->get_where('tbl_user_hasfile', ['user_id' => $id_user])->row_array();

                            foreach ($fields as $field => $label) {
                                if (!empty($_FILES[$field]['name'])) {
                                    if (!$this->upload->do_upload($field)) {
                                        $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                                        $this->session->set_flashdata('msg', $pesan);
                                        $simpan = 'n';
                                        break;
                                    } else {
                                        $gbr = $this->upload->data();
                                        $filename = "$lokasi/" . $gbr['file_name'];
                                        $file_path = preg_replace('/ /', '_', $filename);

                                        // kalau ada file lama, hapus
                                        if (!empty($cekData[$field]) && file_exists($cekData[$field])) {
                                            unlink($cekData[$field]);
                                        }

                                        $file_paths[$field] = $file_path;
                                    }
                                }
                            }

                            if (!empty($file_paths)) {
                                $file_paths['updated_at'] = $tgl;

                                if ($cekData) {
                                    // update kalau sudah ada
                                    $this->db->where('user_id', $id_user)->update('tbl_user_hasfile', $file_paths);
                                } else {
                                    // insert kalau belum ada
                                    $file_paths['user_id'] = $id_user;
                                    $file_paths['created_at'] = $tgl;
                                    $this->db->insert('tbl_user_hasfile', $file_paths);
                                }

                                $this->session->set_flashdata('msg',
                                    '<div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Sukses!</strong> File berhasil disimpan/diupdate.
                                </div><br>'
                                );
                            } else {
                                $this->session->set_flashdata('msg',
                                    '<div class="alert alert-warning alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Gagal!</strong> Tidak ada file yang diupload.
                                </div><br>'
                                );
                            }

                            redirect("users/dossier_pribadi");
                        }


            if (isset($_POST['btnsimpan_gdrive'])) {
                $user_id = $this->session->userdata('id_user');
                $link_gdrive = $this->input->post('link_gdrive');
                $cekData = $this->db->get_where('tbl_gdrive', array('user_id' => $user_id));
                $jmlData = $cekData->num_rows();
                //echo $jmlData; die;
                if ($jmlData <= 0) {
                    //lakukan insert
                    $data3 = array(
                        'user_id' => $user_id,
                        'link_gdrive' => $link_gdrive,
                    );
                    $this->db->insert('tbl_gdrive', $data3);
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success">Link Google Drive berhasil disimpan!</div>'
                    );
                } else if ($jmlData > 0) {
                    //lakukan update thd colom link_gdrive pd tbl_gdrive
                    // Update link_gdrive
                    $this->db->where('user_id', $user_id);
                    $this->db->update('tbl_gdrive', array('link_gdrive' => $link_gdrive));

                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-info">Link Google Drive berhasil diperbarui!</div>'
                    );
                }
                redirect("users/dossier_pribadi");
            }

            if (isset($_POST['btnhapus_gdrive'])) {
                $this->db->where('user_id', $this->session->userdata('id_user'));
                $this->db->delete('tbl_gdrive'); // benar2 hapus record

                $this->session->set_flashdata('msg',
                    '<div class="alert alert-danger">Link berhasil dihapus</div>'
                );
                redirect("users/dossier_pribadi");
            }

            if (isset($_POST['btnhapus_dossier'])) {
                $cekData = $this->db->get_where('tbl_user_hasfile', ['user_id' => $id_user])->row_array();

                if ($cekData) {
                    // Daftar kolom file
                    $fields = [
                        'ktp_path',
                        'ijazah_sh_path',
                        'ijazah_magister_path',
                        'npwp_path',
                        'kepmenkum_pengangkatan_path',
                        'berita_acara_sumpah_path',
                        'kepmenkum_pindah_wilayah_path',
                        'berita_acara_sumpah_pindah_path'
                    ];

                    // Hapus file fisik
                    foreach ($fields as $field) {
                        if (!empty($cekData[$field]) && file_exists($cekData[$field])) {
                            unlink($cekData[$field]);
                        }
                    }

                    // Hapus record dari database
                    $this->db->where('user_id', $id_user)->delete('tbl_user_hasfile');
                }

                $this->session->set_flashdata('msg',
                    '<div class="alert alert-danger">Semua file berhasil dihapus!</div>'
                );
                redirect("users/dossier_pribadi");
            }

        }
    }


    public function unggah_file()
    {
        $user_id = $this->session->userdata('id_user');
        if (!$user_id) {
            redirect('web/login');
        }

        $file_id = $this->input->post('file_id');
        //echo "hey" ; die;
        if (!$this->upload->do_upload('file_upload')) {
            //echo "gagal aplot"; die;
            $simpan = 'n';
            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
        } else {
            $lokasi = 'file';
            $file_size = 1024 * 3; // 3 MB
            $this->upload->initialize(array(
                "upload_path" => "./$lokasi",
                "allowed_types" => "*",
                "max_size" => "$file_size"
            ));

            $gbr = $this->upload->data();
            $filename = "$lokasi/" . $gbr['file_name'];
            $lampiran = preg_replace('/ /', '_', $filename);
            $simpan = 'y';
        }
//echo $simpan; die;
        if ($simpan == 'y') {

        } else {

        }
        //echo "lanjut aplot"; die;


    }


}
