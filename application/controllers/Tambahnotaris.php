<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TambahNotaris extends CI_Controller
{

    public function index()
    {
        redirect('tambahnotaris/v');
    }

    public function v($aksi = '', $id = '')
    {
        //$this->session->set_flashdata('msg', null);
        $id = hashids_decrypt($id);
        $ceks = $this->session->userdata('username');
        $id_user = $this->session->userdata('id_user');
        $level = $this->session->userdata('level');
        if (!isset($ceks)) {
            redirect('web/login');
        } else {
            $data['user'] = $this->Mcrud->get_users_by_un($ceks);

            if ($level != 'superadmin') {
                redirect('404_content');
            }

            $this->db->join('tbl_user', 'tbl_user.id_user=tbl_data_notaris.id_user');
            $this->db->order_by('id_data_notaris', 'DESC');
            $data['query'] = $this->db->get("tbl_data_notaris");
            $data['getTempatKedudukan'] = $this->db->get("tbl_kedudukan");
            $data['getMpdAreas'] = $this->db->get("tbl_petugas");

            if ($aksi == 't') {
                $p = "tambah";
                $data['judul_web'] = "REGISTRASI NOTARIS";
                //echo "<pre>"; print_r($data['getTempatKedudukan']->result());
                //die;
            } elseif ($aksi == 'e') {
                //echo $id; die;
                //$data['getTempatKedudukan'] = $this->db->get("tbl_kedudukan");
                //echo "<pre>"; print_r($data['getTempatKedudukan']->result());die;
                $p = "edit";
                //echo "<pre>"; print_r($data['getTempatKedudukan']->result()); die;
                $data['judul_web'] = "DATA NOTARIS";
                $this->db->join('tbl_user', 'tbl_user.id_user=tbl_data_notaris.id_user');
                $data['query'] = $this->db->get_where("tbl_data_notaris", array('tbl_user.id_user' => "$id"))->row();
                //echo "<pre>"; print_r($data['query']); die;
                if ($data['query']->id_user == '') {
                    redirect('404');
                }
            } elseif ($aksi == 'h') {
                $cek_data = $this->db->get_where("tbl_data_notaris", array('id_user' => "$id"));
                if ($cek_data->num_rows() != 0) {
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
                    redirect("tambahnotaris/v");
                } else {
                    redirect('404');
                }
            } else if($aksi == 'l'){
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
            } else if($aksi=="gd"){

            } else {
                $p = "index";
                $data['judul_web'] = "NOTARIS";
            }


            $this->load->view('users/header', $data);
            $this->load->view("users/tambahnotaris/$p", $data);
            $this->load->view('users/footer');

            date_default_timezone_set('Asia/Jakarta');
            $tgl = date('Y-m-d H:i:s');

            if (isset($_POST['btnsimpan'])) {
                //echo '<pre>'; print_r($this->input->post()); echo '</pre>';  die;

                $no_idn = htmlentities(strip_tags($this->input->post('no_idn')));
                $nama = htmlentities(strip_tags($this->input->post('nama')));
                $no_sk = htmlentities(strip_tags($this->input->post('no_sk')));
                $alamat_notaris = htmlentities(strip_tags($this->input->post('alamat_notaris')));
                $tempat_kedudukan = htmlentities(strip_tags($this->input->post('tempat_kedudukan_new')));
                $mpd_penanggung_jawab = htmlentities(strip_tags($this->input->post('mpd_penanggung_jawab')));

                $telpon = htmlentities(strip_tags($this->input->post('telpon')));
                $email_notaris = htmlentities(strip_tags($this->input->post('email_notaris')));
                $google_drive = htmlentities(strip_tags($this->input->post('google_drive')));
                $username = htmlentities(strip_tags($this->input->post('username')));
                $password = htmlentities(strip_tags($this->input->post('password')));
                $password2 = htmlentities(strip_tags($this->input->post('password2')));

                //echo $username; die;
                $cek_data = $this->db->get_where('tbl_user', array('username' => $username));
                //echo $cek_data->num_rows(); die;
                $simpan = 'y';
                $pesan = '';

                if ($cek_data->num_rows() != 0) {
                    $simpan = 'n';
                    $pesan = "Username '<b>$username</b>' sudah ada";

                } else {
                    if ($password != $password2) {
                        $simpan = 'n';
                        $pesan = "Password tidak cocok!";
                    }
                }

                //echo $simpan."-".$pesan; die;
                if ($simpan == 'y') {
                    $data = array(
                        'nama_lengkap' => $nama,
                        'username' => $username,
                        'password' => $password,
                        'level' => "notaris",
                        'tgl_daftar' => $tgl,
                        'aktif' => '1',
                        'dihapus' => 'tidak'
                    );
                    $this->db->insert('tbl_user', $data);
                    $id_user = $this->db->insert_id();

                    $data2 = array(
                        'no_idn' => $no_idn,
                        'nama' => $nama,
                        'no_sk' => $no_sk,
                        'alamat_notaris' => $alamat_notaris,
                        'tempat_kedudukan' => $tempat_kedudukan,
                        'mpd_area_id' => $mpd_penanggung_jawab,
                        'tempat_kedudukan_old' => $tempat_kedudukan,
                        'telpon' => $telpon,
                        'email_notaris' => $email_notaris,
                        //'foto_notaris' => "",
                        'id_user' => $id_user
                    );

                    //echo "<pre>"; print_r($data2); die;
                    $this->db->insert('tbl_data_notaris', $data2);

                    $data3 = array(
                        'user_id' => $id_user,
                        'link_gdrive' => $google_drive,
                    );
                    $this->db->insert('tbl_gdrive', $data3);

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
                } else {
                    //echo "tidak dapat menyimpan"; die;
                    //echo $pesan; die;
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
                    //echo $this->session->flashdata('msg'); die;
                    redirect("tambahnotaris/v/t");
                }
                redirect("tambahnotaris/v");
            }


            if (isset($_POST['btnupdate'])) {
                //echo "<pre>"; print_r($data['query']); die;
                //$this->db->get_where('tbl', array('username' => $username));
                $getNotarisIdUser = $this->db->get_where('tbl_data_notaris', array(
                        'id_data_notaris' => $data['query']->id_data_notaris)
                );
                //echo "<pre>"; print_r($getNotarisIdUser->row()); die;
                $idUserNotaris = $getNotarisIdUser->result()[0]->id_user;
                //echo $idUserNotaris; die;
                $notarisOldPassword = $this->db->get_where('tbl_user', array(
                        'id_user' => $idUserNotaris)
                )->result()[0]->password;

                $no_idn = htmlentities(strip_tags($this->input->post('no_idn')));
                $nama = htmlentities(strip_tags($this->input->post('nama')));
                $no_sk = htmlentities(strip_tags($this->input->post('no_sk')));
                $alamat_notaris = htmlentities(strip_tags($this->input->post('alamat_notaris')));
                $tempat_kedudukan = htmlentities(strip_tags($this->input->post('tempat_kedudukan_edit')));
                $mpd_penanggung_jawab = htmlentities(strip_tags($this->input->post('mpd_penanggung_jawab')));
                $telpon = htmlentities(strip_tags($this->input->post('telpon')));
                $email_notaris = htmlentities(strip_tags($this->input->post('email_notaris')));
                $google_drive_link = htmlentities(strip_tags($this->input->post('google_drive')));
                $username = htmlentities(strip_tags($this->input->post('username')));
                $password = htmlentities(strip_tags($this->input->post('password')));
                $password2 = htmlentities(strip_tags($this->input->post('password2')));
                $data_lama = $this->db->get_where('tbl_user', array('id_user' => $id))->row();
                //echo $tempat_kedudukan; die;
                //jika kosong pake old pass

                //jika tidak kosong pake new pass
                //echo $password2; die;

                // tricky, ini intinya IF ELSE bertingakt ,IF pertama get data user dimana
                // usernamenya apakah sesuai inputan user ketika proses edit untuk mengetahui apakah ada
                // username yang sama atau tidak di Database, lalu IF kedua berikutnya, get data user dari kondisi
                // IF pertama dimana username tidak sama dengan username yg melakukan input (authenticated user)
                //
                $cek_data = $this->db->get_where('tbl_user', array(
                    'username' => $username, // dari $this->input->post('') inputan terbaru
                    'username!=' => $data_lama->username,
                ));
                //echo $cek_data->num_rows(); die;
                $simpan = 'y';
                $pesan = '';
                //echo $no_idn; die;
                if ($cek_data->num_rows() != 0) {
                    //echo "tidak boleh";
                    $simpan = 'n';
                    $pesan = "Username '<b>$username</b>' sudah ada";
                } else {
                    //echo "boleh";
                    $pass_lama = $data_lama->password;
                    if ($password == '') {
                        $password = $pass_lama;
                    } else {
                        //$password dilakukan isian pada input
                        if ($password != $password2) {
                            //echo "simpan gagal karena p1 dan p2 tidak cocok"; die;
                            $simpan = 'n';
                            $pesan = "Password tidak cocok!";
                        }
                    }
                }

                //die;
                //echo $simpan;die;
                //echo "<pre>"; print_r($this->input->post()); echo "</pre>";die;
                if ($simpan == 'y') {
                    $data = array(
                        'nama_lengkap' => $nama,
                        'username' => $username,
                        'password' => $password
                    );
                    $this->db->update('tbl_user', $data, array('id_user' => $id));

                    $data2 = array(
                        'nama' => $nama,
                        'no_sk' => $no_sk,
                        'no_idn' => $no_idn,
                        'alamat_notaris' => $alamat_notaris,
                        'tempat_kedudukan' => $tempat_kedudukan,
                        'mpd_area_id' => $mpd_penanggung_jawab,
                        'tempat_kedudukan_old' => $tempat_kedudukan,
                        'telpon' => $telpon,
                        'email_notaris' => $email_notaris,
                    );
                    $this->db->update('tbl_data_notaris', $data2, array('id_user' => $id));

                    $data3 = array(
                       'link_gdrive'=>$google_drive_link
                    );
                    $this->db->update('tbl_gdrive', $data3, array('user_id' => $id));

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
                    redirect("tambahnotaris/v");
                    //$this->session->set_flashdata('msg', null);
                } else {
                    //echo "gafgal update bro"; die;
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
                    //$this->session->flashdata('msg');

                    redirect("tambahnotaris/v/e/" . hashids_encrypt($id));
                    //$this->session->set_flashdata('msg', null);
                }
                //redirect("tambahnotaris/v/e/" . hashids_encrypt($id));
                redirect("tambahnotaris/v");
            }
        }
    }

}
