<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller
{

    public function index()
    {
        $data['judul_web'] = $this->Mcrud->judul_web();
        $this->load->view('web/header', $data);
        $this->load->view('web/beranda', $data);
        $this->load->view('web/footer', $data);
    }

    public function pengurus($id = '')
    {
        if ($id == '') {
            echo redirect('404');
        }
        $id = hashids_decrypt($id);
        $this->db->where('id_pengurus', $id);
        $this->db->order_by('id_pengurus', 'DESC');
        $data['query'] = $this->db->get("tbl_pengurus")->row();
        $nama = ucwords($data['query']->nama);
        $jabatan = ucwords($data['query']->jabatan);
        $data['judul_web'] = "$nama - $jabatan";

        $this->load->view('web/header', $data);
        $this->load->view('web/pengurus/detail', $data);
        $this->load->view('web/footer', $data);
    }

    public function download()
    {
        $data['judul_web'] = "Download - " . $this->Mcrud->judul_web();
        $this->db->order_by('id_upload_file', 'DESC');
        $data['query'] = $this->db->get("tbl_upload_file");

        $this->load->view('web/header', $data);
        $this->load->view('web/download', $data);
        $this->load->view('web/footer', $data);
    }

    public function kontak()
    {
        $data['judul_web'] = "Kontak - " . $this->Mcrud->judul_web();
        $this->load->view('web/header', $data);
        $this->load->view('web/kontak', $data);
        $this->load->view('web/footer', $data);
    }

    public function user_register()
    {
        $data['judul_web'] = "Halaman Pendaftaran - " . $this->Mcrud->judul_web();
        $this->load->view('web/log/header', $data);
        $this->load->view('web/log/daftar', $data);
        $this->load->view('web/log/footer', $data);

        date_default_timezone_set('Asia/Jakarta');
        $tgl = date('Y-m-d H:i:s');

        if (isset($_POST['btndaftar'])) {
            $nama     = htmlentities(strip_tags($_POST['nama']));
            $no_ktp   = htmlentities(strip_tags($_POST['no_ktp']));
            $alamat   = htmlentities(strip_tags($_POST['alamat']));
            $kontak   = htmlentities(strip_tags($_POST['kontak']));
            $email    = htmlentities(strip_tags($_POST['email']));
            $username = htmlentities(strip_tags($_POST['username']));
            $pass     = htmlentities(strip_tags($_POST['password']));
            $pass2    = htmlentities(strip_tags($_POST['password2']));

            $cek_data  = $this->db->get_where('tbl_data_user', array('no_ktp' => $no_ktp));
            $cek_data2 = $this->db->get_where('tbl_user', array('username' => $username));
            $simpan = 'y';
            $pesan  = '';
            if ($cek_data->num_rows() != 0) {
                $simpan = 'n';
                $pesan  = "No. NIK '<b>$no_ktp</b>' Sudah Terdaftar!";
            } elseif ($cek_data2->num_rows() != 0) {
                $simpan = 'n';
                $pesan  = "Username '<b>$username</b>' Sudah Terdaftar!";
            } else {
                if ($pass != $pass2) {
                    $simpan = 'n';
                    $pesan  = "Password tidak cocok!";
                }
            }
            $level = 'user';
            if ($simpan == 'y') {
                // NOTE: menyimpan apa adanya (plaintext) agar kompatibel dengan sistem lama
                $data = array(
                    'nama_lengkap' => $nama,
                    'username'     => $username,
                    'password'     => $pass,
                    'level'        => $level,
                    'tgl_daftar'   => $tgl,
                    'aktif'        => '1',
                    'dihapus'      => 'tidak'
                );
                $this->db->insert('tbl_user', $data);

                $id_user = $this->db->insert_id();
                $data2 = array(
                    'no_ktp'  => $no_ktp,
                    'nama'    => $nama,
                    'alamat'  => $alamat,
                    'kontak'  => $kontak,
                    'email'   => $email,
                    'id_user' => $id_user
                );
                $this->db->insert('tbl_data_user', $data2);

                $this->session->set_flashdata('msg',
                    '<div class="alert alert-success alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                         </button>
                         <strong>Registrasi Sukses!</strong> Silahkan login, dan lengkapi profil Anda.
                     </div><br>'
                );
            } else {
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                         </button>
                         <strong>GAGAL!</strong> ' . $pesan . '.
                     </div><br>'
                );
                redirect("web/user_register");
            }
            redirect("web/login");
        }

    }

    public function login()
    {
        // Jika sudah login, arahkan sesuai role (bukan selalu ke /dashboard)
        if ($this->session->userdata('username')) {
            $level = $this->session->userdata('level');
            switch ($level) {
                case 'sekretariat_mkn': redirect('sekretariat_mkn');       return;
                case 'anggota_mkn':     redirect('anggota_mkn');           return;
                case 'aph':             redirect('aph/pengajuan');         return;
                case 'admin':           redirect('admin/dashboard');       return;
                case 'user': redirect('dashboard'); return; // was: redirect('user/dashboard');
                default:                redirect('dashboard');             return;
            }
        }

        $data['judul_web'] = "Halaman Login - " . $this->Mcrud->judul_web();
        $this->load->view('web/log/header', $data);
        $this->load->view('web/log/login', $data);
        $this->load->view('web/log/footer', $data);

        if (isset($_POST['btnlogin'])) {
            $username = htmlentities(strip_tags($this->input->post('username', true)));
            $pass     = htmlentities(strip_tags($this->input->post('password', true)));

            $query  = $this->Mcrud->get_users_by_un($username);
            $jumlah = $query->num_rows();

            // Cegah akses index sebelum jumlah dicek
            if ($jumlah == 0) {
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                         </button>
                         <strong>Username "' . html_escape($username) . '"</strong> belum terdaftar.
                     </div>'
                );
                redirect('web/login');
            }

            $row = $query->row();

            // Akun belum aktif
            if ($row->aktif == '0') {
                if ($row->level == 'user') {
                    $email = $this->db->get_where('tbl_data_user', array('id_user' => $row->id_user))->row()->email;
                    $tgl   = date('Y-m-d');
                    $id    = md5("$email * $tgl");
                    $link  = base_url() . "web/verify/$id/$email/kirim";
                    $pesan = "belum diaktifkan, Aktifkan Akun dengan cara Klik => <b><a href='$link'>Kirim Aktivasi Akun ke email</a></b>";
                } else {
                    $pesan = "tidak aktif";
                }
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-danger alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                         </button>
                         <strong>Username "' . html_escape($username) . '"</strong> ' . $pesan . '.
                     </div>'
                );
                redirect('web/login');
            }

            // Verifikasi password
            // Kompatibel: plaintext (lama) atau MD5 (seed awal).
            $pass_match = false;
            if ($row->password === $pass) {
                $pass_match = true;
            } elseif ($row->password === md5($pass)) {
                $pass_match = true;
            }

            if (!$pass_match) {
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-warning alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                         </button>
                         <strong>Username atau Password Salah!</strong>.
                     </div>'
                );
                redirect('web/login');
            }

            // Set session lengkap
            $this->session->set_userdata([
                'username'       => $row->username,
                'id_user'        => $row->id_user,
                'level'          => $row->level,
                'jml_notif_bell' => "0",
                'logged_in'      => TRUE,
            ]);

            // Redirect sesuai role (rute baru MKN + role lain tetap)
            switch ($row->level) {
                case 'sekretariat_mkn':
                    redirect('sekretariat_mkn');        // Sekretariat_mkn::index
                    break;
                case 'anggota_mkn':
                    redirect('anggota_mkn');            // Anggota_mkn::index
                    break;
                case 'aph':
                    redirect('aph/pengajuan');          // Aph::form_pengajuan
                    break;
                case 'admin':
                    redirect('admin/dashboard');
                    break;
                    case 'user':
                        redirect('dashboard'); // was: redirect('user/dashboard');
                        break;                
                default:
                    redirect('dashboard');
                    break;
            }
        }
    }

    public function logout()
    {
        if ($this->session->has_userdata('username') and $this->session->has_userdata('id_user')) {
            $this->session->sess_destroy();
        }
        redirect('web/login');
    }

    function error_not_found()
    {
        $this->load->view('404_content');
    }

    public function notif_bell($aksi = '')
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_user = $this->session->userdata('id_user');
        $level   = $this->session->userdata('level');

        $this->db->order_by('id_notif', 'DESC');
        $data['query'] = $this->db->get_where('tbl_notif', array('penerima' => $id_user));
        $jml_notif_baru = 0;
        foreach ($data['query']->result() as $key => $value) {
            if (!preg_match("/$id_user/i", $value->hapus_notif)) {
                $jml_notif_baru++;
            }
        }
        foreach ($data['query']->result() as $key => $value) {
            if (preg_match("/$id_user/i", $value->baca_notif)) {
                $jml_notif_baru--;
            }
        }
        $data['jml_notif'] = $jml_notif_baru;
        if ($aksi == 'pesan_baru') {
            $jml_notif_bell = $this->session->userdata('jml_notif_bell');
            $stt = ($jml_notif_bell >= $jml_notif_baru) ? '0' : '1';
            $this->session->set_userdata('jml_notif_bell', "$jml_notif_baru");
            if ($id_user == '') {
                echo '11';
            } else {
                echo $stt;
            }
        } elseif ($aksi == 'jml') {
            echo number_format($jml_notif_baru, 0, ",", ".");
        } else {
            $this->load->view('users/notif/bell', $data);
        }
    }

    public function notif($aksi = '', $id = '')
    {
        $id     = hashids_decrypt($id);
        $level  = $this->session->userdata('level');
        $ceks   = $this->session->userdata('username');
        $id_user= $this->session->userdata('id_user');
        if (!isset($ceks)) {
            redirect('web/login');
        } else {
            $data['user']      = $this->Mcrud->get_users_by_un($ceks);
            $data['users']     = $this->Mcrud->get_users();
            $data['judul_web'] = "Notifikasi";

            $this->db->order_by('id_notif', 'DESC');
            if ($level=="superadmin") {
                $data['query'] = $this->db->get_where('tbl_notif', array('penerima' => $id_user));
            } else if($level=="petugas"){
                $data['query'] = $this->db->get_where('tbl_notif', array('penerima' => $id_user));
            }

            if ($aksi == 'h' or $aksi == 'h_all') {
                if ($aksi == 'h') {
                    $cek_data = $this->db->get_where("tbl_notif", array('id_notif' => "$id"));
                } else {
                    $cek_data = $this->db->get_where("tbl_notif", array('penerima' => "$id_user"));
                }
                if ($cek_data->num_rows() != 0) {
                    if ($aksi == 'h') {
                        $h_notif = $cek_data->row()->hapus_notif;
                        if (!preg_match("/$id_user/i", $h_notif)) {
                            $data = array('hapus_notif' => "$id_user, $h_notif");
                            $this->db->update('tbl_notif', $data, array('id_notif' => $id));
                        }
                    } else {
                        foreach ($cek_data->result() as $key => $value) {
                            $h_notif = $value->hapus_notif;
                            if (!preg_match("/$id_user/i", $h_notif)) {
                                $data = array('hapus_notif' => "$id_user, $h_notif");
                                $this->db->update('tbl_notif', $data, array('penerima' => $id_user));
                            }
                        }
                    }
                    $this->session->set_flashdata('msg',
                        '<div class="alert alert-success alert-dismissible" role="alert">
                             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                             </button>
                             <strong>Sukses!</strong> Berhasil dihapus.
                         </div><br>'
                    );
                    redirect("web/notif");
                } else {
                    if ($aksi == 'h') {
                        redirect('404_content');
                    } else {
                        redirect("web/notif");
                    }
                }
            }

            $this->load->view('users/header', $data);
            $this->load->view('users/notif/index', $data);
            $this->load->view('users/footer');
        }
    }

}
